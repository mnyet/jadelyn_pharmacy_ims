$(document).ready(function(){
    console.log(`Sales View JS Loaded.`);

    loadTransactionListDataTable();
    loadProductListDatatable();
    initSearchHandler();
});

// CTRL F Focuses on the product search bar.
$(document).on('keydown', function(e) {
    // Ctrl+F (or Cmd+F on Mac)
    if ((e.ctrlKey || e.metaKey) && (e.key == 'f' || e.key == 'F')) {
        e.preventDefault();           // prevent browser's native find
        $('#productSearchBar').focus();
    }

    // F1 Key (Quick Sale)
    if (e.key === 'F1') {
        e.preventDefault();        // ← stop browser's help dialog
        $('#btnQuickSale').click();
    }

    // Ctrl+Enter (or Cmd+Enter on Mac) to process payment
    if ((e.ctrlKey || e.metaKey) && e.key === 'Enter') {
        e.preventDefault();
        $('#btnProcessPayment').click();
    }
});

/* Functions */

/* Debounced search handler */
function initSearchHandler() {
    let searchTimer;

    $('#productSearchBar').on('input', function() {
        clearTimeout(searchTimer);

        searchTimer = setTimeout(function() {
            loadProductListDatatable();
        }, 500);
    });

    // Optional: Trigger immediately on Enter key
    $('#productSearchBar').on('keydown', function(e) {
        if (e.key === 'Enter') {
            clearTimeout(searchTimer);
            loadProductListDatatable();
        }
    });
}

function loadTransactionListDataTable() {
    const baseUrl = $('#baseUrl').val();

    $('#transactionListTable').DataTable({
        destroy: true, // Destroy existing instance before reinitializing
        processing: true,
        serverSide: true,
        searching: false,
        lengthChange: false,
        paging: false,
        info: false,  
        ajax: {
            url: baseUrl + 'sales/get-transaction-list',
            type: "POST",
        },
        layout: {
            topEnd: null
        },
        columns: [
            {
                data: 'transaction_id',
                orderable: true,
                className: 'text-start',
                render: function(data, type, row) {
                    return `
                        <div class="transaction-item border rounded p-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <div class="fw-semibold">${row.transaction_code}</div>
                                    <small class="text-muted">${row.transaction_item_count} items • ${row.transaction_time}</small>
                                </div>
                                <span class="badge bg-light text-dark border fw-semibold">₱${parseFloat(row.transaction_total).toFixed(2)}</span>
                            </div>
                        </div>
                    `;
                }
            }
        ]
    });
}


function loadProductListDatatable() {
    const baseUrl = $('#baseUrl').val();

    $('#productListTable').DataTable({
        destroy: true, // Destroy existing instance before reinitializing
        processing: true,
        serverSide: true,
        ajax: {
            url: baseUrl + 'sales/get-product-list',
            type: "POST",
            data: function(d) {
                d.salesDataFlag = true; // Indicate that this request is for the sales view
                d.salesSearchValue = $('#productSearchBar').val();
            }
        },
        layout: {
            topEnd: null
        },
        columns: [
            { data: 'product_lot_number', name: 'product_lot_number', orderable: false, className: 'text-start' },
            { data: 'product_name', name: 'product_name', orderable: false, className: 'text-start' },
            { data: 'product_type',
            name: 'product_type',
            orderable: false,
            className: 'text-start',
            render: function(data, type, row) {
                return `<span class="badge bg-light text-dark border">${data}</span>`
            }
            },
            {
                data: 'product_price',
                name: 'product_price',
                orderable: false,
                className: 'text-start',
                render: function(data, type, row) {
                    return `<span>₱ ${parseFloat(data).toFixed(2)}</span>`;
                }
            },
            { data: 'product_qty', name: 'product_qty', orderable: false, className: 'text-start' },
            {
                data: 'product_id',
                name: 'product_id',
                orderable: false,
                className: 'text-center',   // ← keeps button centered
                render: function (data, type, row) {
                    return `<button class="btn btn-success btn-sm btn-add-to-cart"
                                data-id="${data}"
                                data-price="${row.product_price}"
                                data-name="${row.product_name}"
                            >
                                <i class="fas fa-plus"></i> Add
                            </button>`; 
                }
            }
        ],
        order: [
            [1, 'asc']
        ],
    });
}

$('#productListTable').on('click', '.btn-add-to-cart', function() {
    appendToCart({
        id: $(this).data('id'),
        name: $(this).data('name'),
        price: $(this).data('price'),
        quantity: 1
    });
});

$('#btnProcessPayment').on('click', function() {
    processTransaction();
});

$('.cart-items').on('click', '.btn-remove-item', function() {
    removeFromCart($(this).data('id'));
});

$('.cart-items').on('click', '.btn-qty-minus', function() {
    changeQty($(this), -1);
});

$('.cart-items').on('click', '.btn-qty-plus', function() {
    changeQty($(this), +1);
});

$('#btnClearAll').on('click', function() {
    clearCart();
});

function changeQty($btn, delta) {
    const $item = $btn.closest('.cart-item');
    const $qty = $item.find('.cart-item-qty');
    const currentQty = parseInt($qty.text()) || 0;
    const newQty = currentQty + delta;

    // Don't allow less than 1
    if (newQty < 1) return;

    $qty.text(newQty);
    updateQtyButtons($item);
    updateCartTotals();
}

function updateQtyButtons($item) {
    const qty = parseInt($item.find('.cart-item-qty').text()) || 1;
    $item.find('.btn-qty-minus').prop('disabled', qty <= 1);
    $item.find('.btn-qty-plus').prop('disabled', qty >= 999);  // optional max
}

function appendToCart(product) {
    const $existing = $(`.cart-items .cart-item[data-id="${product.id}"]`);

    if ($existing.length) {
        const $qty = $existing.find('.cart-item-qty');
        $qty.text(parseInt($qty.text()) + product.quantity);
        updateCartTotals();
        return $existing;
    }

    const $item = $('#cartItemTemplate').html();

    const $cartItem = $($item)
        .attr('data-id', product.id)
        .attr('data-price', product.price);

    $cartItem.find('.product-name').text(product.name);
    $cartItem.find('.product-price-label').text(`₱${parseFloat(product.price).toFixed(2)} each`);
    $cartItem.find('.cart-item-qty').text(product.quantity);

    // Bind the data-id attributes for the buttons
    $cartItem.find('.btn-qty-minus').attr('data-id', product.id);
    $cartItem.find('.btn-qty-plus').attr('data-id', product.id);
    $cartItem.find('.btn-remove-item').attr('data-id', product.id);

    $('.cart-items').append($cartItem);
    updateCartTotals();

    return $cartItem;
}

function updateCartTotals() {
    let subtotal = 0;
    let itemCount = 0;

    // Loop through all cart items and sum up
    $('.cart-items .cart-item').each(function() {
        const price = parseFloat($(this).data('price')) || 0;
        const qty = parseInt($(this).find('.cart-item-qty').text()) || 0;

        subtotal += price * qty;
        itemCount += qty;
    });

    // Update the DOM
    $('#cart-subtotal').text('₱' + subtotal.toFixed(2));
    $('#cart-total').text('₱' + subtotal.toFixed(2));

    // Update cart header count (optional)
    updateCartHeaderCount();

    return { subtotal, itemCount };
}

function updateCartHeaderCount() {
    let totalItems = 0;

    // Sum up all quantities, not just the row count
    $('.cart-items .cart-item').each(function() {
        totalItems += parseInt($(this).find('.cart-item-qty').text()) || 0;
    });

    $('#cartItemCount').text(totalItems);
}

function removeFromCart(productId) {
    const $item = $(`.cart-items .cart-item[data-id="${productId}"]`);

    if (!$item.length) {
        console.warn('Product not found in cart:', productId);
        return false;
    }

    $item.remove();
    updateCartTotals();
    return true;
}

function clearCart() {
    if (!$('.cart-items .cart-item').length) {
        return; // Nothing to clear
    }

    $('.cart-items').empty();
    updateCartTotals();
}

function processTransaction() {
    const cartData = [];
    $('.cart-items .cart-item').each(function() {
        const productId = $(this).data('id');
        const price = parseFloat($(this).data('price')) || 0;
        const quantity = parseInt($(this).find('.cart-item-qty').text()) || 0;

        cartData.push({
            product_id: productId,
            price: price,
            quantity: quantity
        });
    });
    
    if (cartData.length === 0) {
        Swal.fire({
            title: 'Cannot Proceed Transaction',
            text: 'Cart is empty. Please add items first before processing the transaction.',
            icon: 'error',
            confirmButtonText: 'Ok'
        });
        return;
    }

    const totals = updateCartTotals();

    Swal.fire({
        title: 'Processing Transaction...',
        html: 'Please wait while we process the transaction.',
        allowOutsideClick: false,
        allowEscapeKey: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });

    $.ajax({
        url: $('#baseUrl').val() + 'sales/process-transaction',
        type: 'POST',
        data: {
            cartData: cartData,
            totalPrice: totals.subtotal,
            totalQuantity: totals.itemCount
        },
        dataType: 'json',
        success: function(response) {
            if (response.success) {
                const data = response.data;
                
                Swal.fire({
                    title: 'Success!',
                    text: `Transaction processed successfully with ${cartData.length} item(s).`,
                    icon: 'success',
                    confirmButtonText: 'Ok'
                }).then(() => {
                    clearCart();
                    loadTransactionListDataTable();
                    loadProductListDatatable();
                });
            } else {
                Swal.fire({
                    title: 'Error',
                    text: 'Something happened while processing the transaction. Please try again.',
                    icon: 'error',
                    confirmButtonText: 'Ok'
                });
            }
        }
    });
}