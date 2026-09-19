<?php

namespace App\Models\Reports;

use App\Models\BaseModel;

use Hermawan\DataTables\DataTable;

class ReportsModel extends BaseModel
{
    public function getReportContents($viewName, $params)
    {
        $builder = $this->builder($viewName);

        // Special handling for the monthly sales report view
        if ($viewName === 'vw_jadelyn_monthly_sales_report') {
            if (!empty($params['year'])) {
                $builder->where('Year', $params['year']);
            }

            $builder->orderBy('1', 'ASC'); // 1 is Year  in the view. (Column Index)
            $builder->orderBy('2', 'ASC'); // 2 is Month Number in the view. (Column Index)
        }

        if ($params['is_periodic']) { // If the report is periodic, apply month and year filters.
            if (!empty($params['year']) && !empty($params['month'])) {
                $startDate = sprintf('%04d-%02d-01', $params['year'], $params['month']);
                $endDate   = date('Y-m-t', strtotime($startDate));  // last day of month

                $builder->where('created_at >=', $startDate . ' 00:00:00');
                $builder->where('created_at <=', $endDate . ' 23:59:59');
            }
            elseif (!empty($params['year'])) {
                $builder->where('created_at >=', $params['year'] . '-01-01 00:00:00');
                $builder->where('created_at <=', $params['year'] . '-12-31 23:59:59');
            }
        }

        return $builder->get()->getResultArray();
    }

    public function getReportById($reportId)
    {
        return $this->builder('jadelyn_pharmacy_reports')
                    ->where('id', $reportId)
                    ->where('active', 1)
                    ->limit(1)
                    ->get()
                    ->getRowArray();
    }
}