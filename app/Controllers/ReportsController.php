<?php

namespace App\Controllers;

use App\Models\Reports\ReportsModel;
use App\Models\CommonModel;

class ReportsController extends BaseController
{
    public function __construct()
    {
        $this->reportsModel = new ReportsModel();
        $this->commonModel = new CommonModel();
    }

    public function index(): string {
        $response = $this->commonModel->getReportListItems();
        $data = [
            'productReports' => $response['productReports'] ?? [],
            'salesReports' => $response['salesReports'] ?? [],
        ];
        
        return view('Reports/ReportsView', $data);
    }

    public function processReport() {
        $params = $this->request->getPost();

        $reportId   = $params['report_id'] ?? null;
        $month      = $params['month'] ?? null;
        $year       = $params['year'] ?? null;

        if (!$reportId) {
            return $this->response->setStatusCode(400)->setJSON([
                'success' => false,
                'message' => 'Selected Report not specified.'
            ]);
        }

        try {
            $report = $this->reportsModel->getReportById($reportId);

            if (!$report) {
                return $this->response->setStatusCode(404)->setJSON([
                    'success' => false,
                    'message' => 'Report not found.'
                ]);
            }

            $viewName   = $report['report_view_name'];
            $reportName = $report['report_name'];   // trust the DB, not the client
            $isPeriodic = (bool) $report['is_periodic'];

            // Fetch the report data
            $data = $this->reportsModel->getReportContents($viewName, [
                'is_periodic' => $isPeriodic,
                'month' => $month,
                'year'  => $year,
            ]);

            if (empty($data)) {
                return $this->response->setStatusCode(404)->setJSON([
                    'success' => false,
                    'message' => 'No data found for this report.'
                ]);
            }

            // Obtain the column names.
            $columns = array_keys($data[0]);

            // Build filename
            $filename = $this->buildFilename($reportName, $month, $year);

            // Generate CSV contents
            $csvContent = $this->generateCsv($data, $columns);

            // Force download
            return $this->downloadCsv($csvContent, $filename);

        } catch (\Throwable $th) {
            return $this->response->setStatusCode(500)->setJSON([
                'success' => false,
                'message' => 'Failed to generate report - ' . $th->getMessage()
            ]);
        }
    }

    private function buildFilename($reportName, $month = null, $year = null): string {
        $safeName = preg_replace('/[^A-Za-z0-9_\-]/', '_', $reportName);
        $suffix   = '';

        if ($year && $month) {
            $suffix = '_' . $year . '-' . str_pad($month, 2, '0', STR_PAD_LEFT);
        } elseif ($year) {
            $suffix = '_' . $year;
        }

        return date('YmdHis') . '_' . $safeName . $suffix . '.csv';
    }

    private function generateCsv(array $data, array $columns): string {
        // Open a memory stream as a temp buffer
        $handle = fopen('php://temp', 'r+');

        // Add UTF-8 BOM for Excel compatibility
        fprintf($handle, chr(0xEF) . chr(0xBB) . chr(0xBF));

        // Write header row (column names from first row)
        fputcsv($handle, $columns);

        // Write data rows
        foreach ($data as $row) {
            fputcsv($handle, (array) $row);
        }

        // Rewind and read the content
        rewind($handle);
        $csvContent = stream_get_contents($handle);
        fclose($handle);

        return $csvContent;
    }

    private function downloadCsv(string $csvContent, string $filename) {
        return $this->response->download($filename, $csvContent);
    }
}