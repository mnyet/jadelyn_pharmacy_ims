// Global Variables
const select2Elements = '#modal_brand_id, #modal_product_type_id, #modal_generic_name_id, #modal_search_product_combination';

$(document).ready(function(){
    console.log('Product List View JS Loaded.');

    loadProductListDatatable();

    // Handling select2 (Adds searchbar in the select options)
    $(select2Elements).select2({
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

    $(select2Elements).select2('val', '');
    $(select2Elements).trigger('change');
    $(select2Elements).prop('disabled', true); // Enable the select2 fields

    $('#productSearchContainer').removeClass('d-none'); // Show the search container - Only use in Add Product
    $('#modal_search_product_combination').val('').prop('disabled', false); // Enable the search field - Only use in Add Product
    $('#productModalLabel').text('Add New Product');
    $('#btnSaveProduct').text('Save Product').val(1);
    $('#productModal').modal('show');
});

// On change of product combination selection, fetch and populate the product details.
$('#modal_search_product_combination').on('change', function(e) {
    // Get the selected option using the event
    const selectedOption = e.target.options[e.target.selectedIndex];

    const selectedGenericNameId = $(selectedOption).data('generic-name-id');
    const selectedBrandId = $(selectedOption).data('brand-id');
    const selectedProductTypeId = $(selectedOption).data('product-type-id');

    $('#modal_generic_name_id').val(selectedGenericNameId).trigger('change');
    $('#modal_brand_id').val(selectedBrandId).trigger('change');
    $('#modal_product_type_id').val(selectedProductTypeId).trigger('change');
});

// Edit Product
$('#productListTable').on('click', '.btn-edit', function() {
    const productId = $(this).data('id');

    $('#productSearchContainer').addClass('d-none'); // Hide the search container - Only use in Add Product
    $('#modal_search_product_combination').val('').prop('disabled', true); // Disable the search field - Only use in Add Product
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
            { data: 'generic_name', name: 'generic_name' },
            { data: 'brand_name', name: 'brand_name' },
            { data: 'product_type', name: 'product_type' },
            { data: 'purchase_date', name: 'purchase_date' },
            { data: 'expiry_date', name: 'expiry_date' },
            { data: 'lot_number', name: 'lot_number' },
            { data: 'quantity', name: 'quantity' },
            {
                data: 'product_id',
                name: 'product_id',
                render: function (data, type, row) {
                    return `<button class="btn btn-sm btn-primary btn-edit" data-id="${data}">Edit</button>
                            <button class="btn btn-sm btn-danger" onclick="deleteProduct(${data})">Delete</button>`; 
                }
            }
        ],
        order: [
            [0, 'asc'],
            [1, 'asc']
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