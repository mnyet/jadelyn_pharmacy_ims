$(document).ready(function(){
    console.log('Transaction List View JS Loaded.');

    loadTransactionsDatatable();
});

$('#btnSearch').click(function() {
    loadTransactionsDatatable();
});

function loadTransactionsDatatable() {
    console.log('Initializing DataTable for Transaction List.');
    const baseUrl = $('#baseUrl').val();

    $('#transactionListTable').DataTable({
        destroy: true, // Destroy existing instance before reinitializing
        processing: true,
        serverSide: true,
        ajax: {
            url: baseUrl + 'transactions/get-transaction-list',
            type: "POST",
            data: function(d) {
                d.searchValue = $('#transactionSearchBar').val();
            }
        },
        layout: {
            topEnd: null
        },
        columns: [
            { data: 'transaction_no', name: 'transaction_no', className: 'text-start' },
            {   
                data: 'raw_transaction_date',
                name: 'raw_transaction_date',
                className: 'text-start',
                render: function(data, type, row) {
                    return row.transaction_date; // Display the formatted date in the table
                }
            },
            { 
                data: 'transaction_amount',
                name: 'transaction_amount',
                className: 'text-start',
                render: function(data, type, row) {
                    return `<span>₱ ${parseFloat(data).toFixed(2)}</span>`;
                }
            },
            { data: 'transaction_employee', name: 'transaction_employee', className: 'text-start' },
            {
                data: 'transaction_id',
                name: 'transaction_id',
                render: function (data, type, row) {
                    return `<button class="btn btn-sm btn-primary" onclick="viewTransaction(${data})">View</button>`; 
                }
            }
        ],
        order: [
            [1, 'desc'],
        ]
    });
}

/* Transaction Details */

function viewTransaction(transactionId) {
    $.ajax({
        url: $('#baseUrl').val() + 'transactions/get-transaction-details',
        type: 'POST',
        data: { transactionId: transactionId },
        success: function(response) {
            if (response.success) {
                const data = response.data;
                
                $('#tdTransactionCode').text(data.transaction_no);
                $('#tdTransactionDate').text(data.transaction_date);
                $('#tdCashier').text(data.transaction_employee);
                $('#tdItemCount').text(data.transaction_item_count);
                $('#tdTotal').text(`₱ ${parseFloat(data.transaction_total).toFixed(2)}`);

                $('#transactionDetailModal').modal('show'); 

                // Populate the transaction items table
                $('#transactionDetailTable').DataTable({
                    destroy: true, // Destroy existing instance before reinitializing
                    processing: true,
                    serverSide: true,
                    searching: false,        // ← hides search box
                    lengthChange: false,     // ← hides "entries per page" dropdown
                    paging: false,           // ← hides pagination controls
                    info: false,             // ← hides "Showing X to Y of Z entries"
                    ajax: {
                        url: $('#baseUrl').val() + 'transactions/get-transaction-items',
                        type: "POST",
                        data: { transactionId: transactionId }
                    },
                    layout: {
                        topEnd: null
                    },
                    columns: [
                        { data: 'td_product_name', name: 'td_product_name' },
                        { data: 'td_lot_number', name: 'td_lot_number' },
                        { data: 'td_product_price', name: 'td_product_price' },
                        { data: 'td_product_quantity', name: 'td_product_quantity' },
                        { data: 'td_product_subtotal', name: 'td_product_subtotal' },
                    ],
                });
                
            }
        }
    });
}