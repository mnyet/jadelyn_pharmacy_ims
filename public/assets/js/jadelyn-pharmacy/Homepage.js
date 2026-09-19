$(document).ready(function() {
    console.log('Homepage JS Loaded.');
});

function loadLowStockModalView() {
    $('#lowStockModal').modal('show'); 

    // Populate the transaction items table
    $('#lowStockTable').DataTable({
        destroy: true, // Destroy existing instance before reinitializing
        processing: true,
        serverSide: true,
        searching: false,        // ← hides search box
        lengthChange: false,     // ← hides "entries per page" dropdown
        paging: false,           // ← hides pagination controls
        info: false,             // ← hides "Showing X to Y of Z entries"
        ajax: {
            url: $('#baseUrl').val() + 'products/get-low-stock-datatable',
            type: "POST",
        },
        layout: {
            topEnd: null
        },
        columns: [ // These matches the columns from the low stock report view.
            { data: 'Generic_Name', name: 'Generic_Name' },
            { data: 'Product_Type', name: 'Product_Type' },
            { data: 'Total_Quantity', name: 'Total_Quantity' },
        ],
    });
}