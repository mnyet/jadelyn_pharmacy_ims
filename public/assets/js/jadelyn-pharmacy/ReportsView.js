$(document).ready(function() {
    console.log('Reports View Loaded.');

    // =============================
    // Product Reports — Generate
    // =============================
    $('#btnGenerateProductReport').on('click', function() {
        const reportId = $('#productReportType').val();
        const reportName = $('#productReportType option:selected').text().trim();

        if (!reportId) {
            Swal.fire('No Report Selected', 'Please choose a report type first.', 'warning');
            return;
        }

        downloadReportCsv(reportId, reportName);
    });

    // =============================
    // Sales Reports — Generate
    // =============================
    $('#btnGenerateSalesReport').on('click', function() {
        const reportId = $('#salesReportType').val();
        const reportName = $('#salesReportType option:selected').text().trim();
        const month = $('#salesReportMonth').val() ?? null;
        const year  = $('#salesReportYear').val() ?? null;
        const isPeriodic = $('#salesReportType option:selected').data('is-periodic');

        if (!reportId) {
            Swal.fire('No Report Selected', 'Please choose a report type first.', 'warning');
            return;
        }

        // Year is always required
        if (!year) {
            Swal.fire('Incomplete Selection', 'Please select a year.', 'warning');
            return;
        }

        // Month is required only for periodic reports
        if (isPeriodic && !month) {
            Swal.fire('Incomplete Selection', 'Please select a month for this report.', 'warning');
            return;
        }

        downloadReportCsv(reportId, reportName, month, year);
    });
});

$('#salesReportType').on('change', function() {
    const isPeriodic = $('#salesReportType option:selected').data('is-periodic');

    if (isPeriodic) {
        $('#salesReportMonth').prop('disabled', false).val('');
    } else {
        $('#salesReportMonth').prop('disabled', true).val('');
    }
});

/**
 * Trigger a CSV download via hidden form submission.
 * Backend looks up the view name from the report_id.
 */
function downloadReportCsv(reportId, reportName = 'Report', month = null, year = null) {

    // Read from hidden inputs
    const baseUrl  = $('#baseUrl').val();
    const csrfName = $('#csrfToken').attr('name');   // e.g., "csrf_test_name"
    const csrfHash = $('#csrfToken').val();          // e.g., "abc123..."

    // Show loading
    Swal.fire({
        title: 'Generating Report...',
        html: 'Please wait while we prepare your file.',
        allowOutsideClick: false,
        allowEscapeKey: false,
        didOpen: () => Swal.showLoading()
    });

    // Build hidden form
    const $form = $('<form>', {
        method: 'POST',
        action: baseUrl + 'reports/process-report',
        target: '_blank',
        style: 'display: none;'
    });

    // CSRF token
    $form.append($('<input>', { type: 'hidden', name: csrfName, value: csrfHash }));

    // Report ID (the only thing backend needs)
    $form.append($('<input>', { type: 'hidden', name: 'report_id', value: reportId }));

    // Optional filters
    if (month) $form.append($('<input>', { type: 'hidden', name: 'month', value: month }));
    if (year)  $form.append($('<input>', { type: 'hidden', name: 'year',  value: year }));

    // Submit and remove
    $form.appendTo('body').submit().remove();

    // Close loader
    setTimeout(() => {
        Swal.close();
        Swal.fire({
            title: 'Download Started!',
            html: `Check your downloads folder for <b>${reportName}</b>.`,
            icon: 'success',
            timer: 2500,
            showConfirmButton: false
        });
    }, 800);
}