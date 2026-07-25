$(document).ready(function(){
    console.log('Product List View JS Loaded.');

    loadProductListDatatable();

    /*
    Swal.fire({
        title: 'Welcome to the Product List!',
        text: 'Here you can manage your products. Use the search bar to find specific items.',
        icon: 'info',
        confirmButtonText: 'Got it!'
    }); */

    // Handling select2 (Adds searchbar in the select options)
    $('#modal_brand_id, #modal_product_type_id, #modal_generic_name_id').select2({
        theme: "bootstrap-5",
        placeholder: "Select Type...",
        width: '100%',
        dropdownParent: $('#productModal') // CRITICAL: Fixes focus/scrolling in modals
    });
});

/* FE shenanigans */
$('#btnSearch').click(function() {
    loadProductListDatatable();
});

$('#productSearchBar').on('keypress', function(e) {
    if (e.which == 13) { // 13 is the key code for 'Enter'
        e.preventDefault(); // Prevents the page from refreshing if inside a form
        loadProductListDatatable();
    }
});

// Add Product
$('#btnAddProduct').click(function() {
    $('#productForm')[0].reset(); 
    $('#productIdModal').val('');

    $('#modal_brand_id, #modal_product_type_id, #modal_generic_name_id').select2('val', '');
    $('#modal_brand_id, #modal_product_type_id, #modal_generic_name_id').trigger('change');
    $('#modal_brand_id, #modal_product_type_id, #modal_generic_name_id').prop('disabled', false); // Enable the select2 fields

    $('#productModalLabel').text('Add New Product');
    $('#btnSaveProduct').text('Save Product').val(1);
    $('#productModal').modal('show');
});

// Edit Product
$('#productListTable').on('click', '.btn-edit', function() {
    const productId = $(this).data('id');

    $('#productForm')[0].reset(); 
    $('#productIdModal').val('');
    $('#productModalLabel').text('Edit Product');
    $('#btnSaveProduct').text('Update Product').val(2);

    $.ajax({
        url: $('#baseUrl').val() + 'products/get-product-details',
        type: 'POST',
        data: { id: productId },
        dataType: 'json',
        success: function(response) {
            if (response.success) {
                const data = response.data;
                $('#productIdModal').val(data.product_id);
                $('#modal_brand_id').select2('val', data.brand_id);
                $('#modal_lot_number').val(data.lot_number);
                $('#modal_product_type_id').select2('val', data.product_type_id);
                $('#modal_generic_name_id').select2('val', data.generic_name_id);
                $('#modal_expiry_date').val(data.expiry_date);
                $('#modal_purchase_date').val(data.purchase_date);
                $('#modal_quantity').val(data.quantity);

                $('#productModal').modal('show');
            } else {
                Swal.fire({
                    title: 'Error',
                    text: 'Failed to fetch product details. Please try again.',
                    icon: 'error',
                    confirmButtonText: 'Ok'
                });
            }
        }
    });
});

// Handling the btnSaveProduct click for both Add and Edit
$('#btnSaveProduct').click(function() {
    const form = $('#productForm')[0];

    if (!form.checkValidity()) {
        form.reportValidity();
        return;
    }

    if ($('#modal_expiry_date').val() < $('#modal_purchase_date').val()) {
        Swal.fire({
            title: 'Invalid Dates',
            text: 'Expiry date cannot be earlier than purchase date.',
            icon: 'error',
            confirmButtonText: 'Ok'
        });

        $('#modal_expiry_date, #modal_purchase_date').addClass('is-invalid');
        return;
    } else {
        $('#modal_expiry_date, #modal_purchase_date').removeClass('is-invalid');
    }

    const actionType = $(this).val(); // 1 for Add, 2 for Edit
    const productId = $('#productIdModal').val(); // Get product ID for Edit, will be empty for Add
    
    saveProduct(actionType, productId);
});

/* Functions */

function loadProductListDatatable() {
    console.log('Initializing DataTable for Product List.');
    const baseUrl = $('#baseUrl').val();

    $('#productListTable').DataTable({
        destroy: true, // Destroy existing instance before reinitializing
        processing: true,
        serverSide: true,
        ajax: {
            url: baseUrl + 'products/get-product-list',
            type: "POST",
            data: function(d) {
                d.searchType = $('#searchBy').val();
                d.searchValue = $('#productSearchBar').val();
            }
        },
        layout: {
            topEnd: null
        },
        columns: [
            { data: 'quantity', name: 'quantity' },
            { data: 'purchase_date', name: 'purchase_date' },
            { data: 'lot_number', name: 'lot_number' },
            { data: 'expiry_date', name: 'expiry_date' },
            { data: 'brand_name', name: 'brand_name' },
            { data: 'generic_name', name: 'generic_name' },
            { data: 'product_type', name: 'product_type' },
            {
                data: 'product_id',
                name: 'product_id',
                render: function (data, type, row) {
                    return `<button class="btn btn-sm btn-primary btn-edit" data-id="${data}">Edit</button>
                            <button class="btn btn-sm btn-danger" onclick="deleteProduct(${data})">Delete</button>`; 
                }
            }
        ]
    });
}

function saveProduct(actionType, productId = null) {
    const baseUrl = $('#baseUrl').val();

    Swal.fire({
        title: ` ${actionType == 1 ? 'Add' : 'Edit'} Product`,
        text: `Are you sure you want to ${actionType == 1 ? 'add' : 'edit'} this product?`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yes',
        showCancelButton: true,
        showLoaderOnConfirm: true,
        preConfirm: () => {
            return $.ajax({
                url: baseUrl + `products/${actionType == 1 ? 'add' : 'edit'}-product`,
                type: 'POST',
                data: {
                    id: productId,
                    brand_id: $('#modal_brand_id').val(),
                    lot_number: $('#modal_lot_number').val(),
                    expiry_date: $('#modal_expiry_date').val(),
                    purchase_date: $('#modal_purchase_date').val(),
                    generic_name_id: $('#modal_generic_name_id').val(),
                    product_type_id: $('#modal_product_type_id').val(),
                    quantity: $('#modal_quantity').val()
                },
                dataType: 'json'
            })
            .done(response => {
                if (response.success) {
                    return response;
                }
                else {
                    Swal.showValidationMessage(`Request failed: ${response.message}`);
                }
            })
        },
        allowOutsideClick: () => !Swal.isLoading()
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire({
                title: 'Success!',
                text: `Product has been successfully ${actionType == 1 ? 'added' : 'updated'}.`,
                icon: 'success',
                confirmButtonText: 'Ok'
            }).then(() => {
                $('#productForm')[0].reset();
                $('#productIdModal').val('');
                $('#productModal').modal('hide'); // Hide modal
                loadProductListDatatable();
            });
        }
    });
}

function deleteProduct(productId) {
    const baseUrl = $('#baseUrl').val();

    Swal.fire({
        title: 'Delete Product',
        text: "Are you sure you want to delete this product?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yes',
        cancelButtonText: 'Cancel',
        showLoaderOnConfirm: true,
        preConfirm: () => {
            return $.ajax({
                url: baseUrl + 'products/delete-product',
                type: 'POST',
                data: { id: productId },
                dataType: 'json'
            })
            .done(response => {
                if (response.success) {
                    return response;
                }
                else {
                    Swal.showValidationMessage(`Request failed: ${response.message}`);
                }
            })
        },
        allowOutsideClick: () => !Swal.isLoading()
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire({
                title: 'Success!',
                text: `Product has been successfully deleted.`,
                icon: 'success',
                confirmButtonText: 'Ok'
            }).then(() => {
                loadProductListDatatable();
            });
        }
    });
}