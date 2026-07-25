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
    $('#modal_brand_id, #modal_generic_name_id').select2({
        theme: "bootstrap-5",
        placeholder: "Select Type...",
        width: '100%',
        dropdownParent: $('#productPriceModal') // CRITICAL: Fixes focus/scrolling in modals
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
    $('#productPriceForm')[0].reset();

    $('#modal_brand_id').select2('val', '');
    $('#modal_brand_id').trigger('change');
    $('#modal_generic_name_id').select2('val', '');
    $('#modal_generic_name_id').trigger('change');
    $('#modal_brand_id, #modal_generic_name_id').prop('disabled', false); // Enable the select2 fields

    $('#productPriceIdModal').val('');
    $('#productPriceModalLabel').text('Add New Product');
    $('#btnSaveProductPrice').text('Save Pricing').val(1);
    $('#productPriceModal').modal('show');
});

// Edit Product
$('#productPriceTable').on('click', '.btn-edit', function() {
    const productPriceId = $(this).data('id');

    $('#productPriceForm')[0].reset(); 
    $('#productPriceIdModal').val(productPriceId);
    $('#productPriceModalLabel').text('Edit Pricing');
    $('#btnSaveProductPrice').text('Update Pricing').val(2);

    $.ajax({
        url: $('#baseUrl').val() + 'products/get-product-price-details',
        type: 'POST',
        data: { id: productPriceId },
        dataType: 'json',
        success: function(response) {
            if (response.success) {
                const data = response.data;

                $('#modal_brand_id').select2('val', data.brand_id);
                $('#modal_generic_name_id').select2('val', data.generic_name_id);
                $('#modal_brand_id, #modal_generic_name_id').prop('disabled', true); // Disable the select2 fields
                $('#modal_unit_price').val(data.unit_price);
                $('#modal_selling_price').val(data.selling_price);

                $('#productPriceModal').modal('show');
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

// Handling the btnSaveProductPrice click for both Add and Edit
$('#btnSaveProductPrice').click(function() {
    const form = $('#productPriceForm')[0];

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
    const productPriceId = $('#productPriceIdModal').val(); // Get product ID for Edit, will be empty for Add
    
    saveProduct(actionType, productPriceId);
});

/* Functions */

function loadProductListDatatable() {
    console.log('Initializing DataTable for Product List.');
    const baseUrl = $('#baseUrl').val();

    $('#productPriceTable').DataTable({
        destroy: true, // Destroy existing instance before reinitializing
        processing: true,
        serverSide: true,
        ajax: {
            url: baseUrl + 'products/get-product-price-list',
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
            { data: 'unit_price', name: 'unit_price',
                render: function(data, type, row) {
                    return `₱ ${parseFloat(data).toFixed(2)}`;
                }
            },
            { data: 'selling_price', name: 'selling_price',
                render: function(data, type, row) {
                    return `₱ ${parseFloat(data).toFixed(2)}`;
                }
            },
            {
                data: 'product_price_id',
                name: 'product_price_id',
                render: function (data, type, row) {
                    return `<button class="btn btn-sm btn-primary btn-edit" data-id="${data}">Edit</button>
                            <button class="btn btn-sm btn-danger" onclick="deleteProduct(${data})">Delete</button>`; 
                }
            }
        ]
    });
}

function saveProduct(actionType, productPriceId = null) {
    const baseUrl = $('#baseUrl').val();

    Swal.fire({
        title: ` ${actionType == 1 ? 'Add' : 'Edit'} Product Price`,
        text: `Are you sure you want to ${actionType == 1 ? 'add' : 'edit'} the pricing of this product?`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yes',
        showCancelButton: true,
        showLoaderOnConfirm: true,
        preConfirm: () => {
            return $.ajax({
                url: baseUrl + `products/${actionType == 1 ? 'add' : 'edit'}-product-price`,
                type: 'POST',
                data: {
                    id: productPriceId,
                    brand_id: $('#modal_brand_id').val(),
                    generic_name_id: $('#modal_generic_name_id').val(),
                    unit_price: $('#modal_unit_price').val(),
                    selling_price: $('#modal_selling_price').val()
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
                $('#productPriceForm')[0].reset();
                $('#productPriceIdModal').val('');
                $('#productPriceModal').modal('hide'); // Hide modal
                loadProductListDatatable();
            });
        }
    });
}

function deleteProduct(productPriceId) {
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
                url: baseUrl + 'products/delete-product-price',
                type: 'POST',
                data: { id: productPriceId },
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