const managementType = $('#managementType').val();
const managementTypeMap = {
    1: 'Generic Name',
    2: 'Product Type',
    3: 'User',
    4: 'User Role',
    5: 'Brand'
};
const managementTypeName = managementTypeMap[managementType] || 'Unknown';

$(document).ready(function(){
    console.log(`Management Type: ${managementTypeName} - Generic JS Loaded.`);

    if (!(managementType in managementTypeMap)) {
        Swal.fire({
            title: 'Error',
            text: 'Invalid management type specified.',
            icon: 'error',
            confirmButtonText: 'Ok'
        }).then(() => {
            window.location.href = $('#baseUrl').val();
        });
        return; // Stop execution if invalid
    }

    loadGenericNameListDatatable();

    /*
    Swal.fire({
        title: `Welcome to the ${managementTypeName} Management!`,
        text: `Here you can manage your ${managementTypeName.toLowerCase()}. Use the search bar to find specific items.`,
        icon: 'info',
        confirmButtonText: 'Got it!'
    }); */
});

/* FE shenanigans */
$('#btnSearch').click(function() {
    loadGenericNameListDatatable();
});

$('#searchText').on('keypress', function(e) {
    if (e.which == 13) { // 13 is the key code for 'Enter'
        e.preventDefault(); // Prevents the page from refreshing if inside a form
        loadGenericNameListDatatable();
    }
});

// Add Entry
$('#btnAdd').click(function() {
    $('#managementForm')[0].reset(); 
    $('#mgmtId').val('');
    $('#managementModalLabel').text('Add New Entry');

    if (managementType === '3') {
        $('#mgmtNameSection').hide();
        $('#mgmtName').prop('required', false);
        loadUserManagementSection();
    } else if (managementType === '4') {
        loadUserRoleManagementSection();
    }

    $('#btnSaveManagement').text('Save Entry').val(1);
    $('#managementModal').modal('show');
});

// Edit Entry
$('#managementListTable').on('click', '.btn-edit', function() {
    const productId = $(this).data('id');

    $('#managementForm')[0].reset(); 
    $('#mgmtId').val('');

    if (managementType === '3') {
        $('#mgmtNameSection').hide();
        $('#mgmtName').prop('required', false);
        loadUserManagementSection();
    } else if (managementType === '4') {
        loadUserRoleManagementSection();
    }

    $('#managementModalLabel').text('Edit Entry');
    $('#btnSaveManagement').text('Update Entry').val(2);

    $.ajax({
        url: $('#baseUrl').val() + 'management/get-management-details',
        type: 'POST',
        data: {
            id: productId,
            managementType: $('#managementType').val() || 0
        },
        dataType: 'json',
        success: function(response) {
            if (response.success) {
                const data = response.data;
                $('#mgmtId').val(data.id);
                $('#mgmtName').val(data.name);

                if (managementType === '3') {
                    $('#mgmtRole').val(data.role_id);
                    $('#mgmtUsername').val(data.username);
                    $('#mgmtEmail').val(data.email);

                    $('#changePasswordSection').show();
                    $('#enablePasswordChange').prop('checked', false).trigger('change').prop('required', false);
                } else if (managementType === '4') {
                    $('#mgmtRoleCode').val(data.role_code);
                    $('#mgmtRoleDescription').val(data.role_description);
                }

                $('#managementModal').modal('show');
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

// Prevent the management name input from submitting the form when 'Enter' is pressed.
$('#mgmtName').on('keydown', function(e) {
    if (e.keyCode === 13) {
        e.preventDefault();
        return false;
    }
});

$('#btnSaveManagement').click(function() {
    const form = $('#managementForm')[0];

    if (!form.checkValidity()) {
        form.reportValidity();
        return;
    }

    const actionType = $(this).val(); // 1 for Add, 2 for Edit
    const productId = $('#mgmtId').val(); // Get product ID for Edit, will be empty for Add
    
    saveEntry(actionType, productId);
});

$('#enablePasswordChange').on('change', function() {
    if ($(this).is(':checked')) {
        $('#mgmtPassword').prop('disabled', false).focus();
    } else {
        $('#mgmtPassword').prop('disabled', true).val('');
    }
});

/* Functions */
function loadGenericNameListDatatable() {
    console.log('Initializing DataTable for Management List.');
    const baseUrl = $('#baseUrl').val();

    $('#managementListTable').DataTable({
        destroy: true, // Destroy existing instance before reinitializing
        processing: true,
        serverSide: true,
        ajax: {
            url: baseUrl + 'management/get-management-list',
            type: "POST",
            data: function(d) {
                d.managementType = $('#managementType').val() || 0;
                d.searchType = $('#searchBy').val();
                d.searchValue = $('#searchText').val();
            },
            dataSrc: function(json) {
                if (json.success === false) {
                    Swal.fire({
                        title: 'Error',
                        text: 'Failed to fetch data. Please try again.',
                        icon: 'error',
                        confirmButtonText: 'Ok'
                    });
                    return [];
                }
                return json.data || [];
            }
        },
        layout: {
            topEnd: null
        },
        columns: [
            { data: '1', name: `name` },
            {
                data: 0,
                name: 'id',
                render: function (data, type, row) {
                    return `<button class="btn btn-sm btn-primary btn-edit" data-id="${row[0]}">Edit</button>
                            <button class="btn btn-sm btn-danger" onclick="deleteEntry(${row[0]})">Delete</button>`; 
                }
            }
        ]
    });
}

function saveEntry(actionType, entryId = null) {
    const baseUrl = $('#baseUrl').val();

    let data = {
        id: entryId,
        name: $('#mgmtName').val(),
        managementType: $('#managementType').val()
    }

    if (managementType === '3') {
        data.username = $('#mgmtUsername').val().trim();
        data.roleId = $('#mgmtRole').val();
        data.password = $('#mgmtPassword').val().trim();
        data.email = $('#mgmtEmail').val().trim();
        data.changePasswordFlag = $('#enablePasswordChange').is(':checked');
    } else if (managementType === '4') {
        data.role_code = $('#mgmtRoleCode').val().trim();
        data.description = $('#mgmtRoleDescription').val().trim();
    }

    console.log(data);

    Swal.fire({
        title: ` ${actionType == 1 ? 'Add' : 'Edit'} ${managementTypeName}`,
        text: `Are you sure you want to ${actionType == 1 ? 'add' : 'edit'} this ${managementTypeName}?`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yes',
        showCancelButton: true,
        showLoaderOnConfirm: true,
        preConfirm: () => {
            return $.ajax({
                url: baseUrl + `management/${actionType == 1 ? 'add' : 'edit'}-entry`,
                type: 'POST',
                data: data,
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
                text: `${managementTypeName} has been successfully ${actionType == 1 ? 'added' : 'updated'}.`,
                icon: 'success',
                confirmButtonText: 'Ok'
            }).then(() => {
                $('#managementForm')[0].reset();
                $('#mgmtName').val('');
                $('#managementModal').modal('hide'); // Hide modal
                loadGenericNameListDatatable();
            });
        }
    });
}

function deleteEntry(id) {
    const baseUrl = $('#baseUrl').val();

    Swal.fire({
        title: 'Delete ' + managementTypeName,
        text: "Are you sure you want to delete this " + managementTypeName + "?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yes',
        cancelButtonText: 'Cancel',
        showLoaderOnConfirm: true,
        preConfirm: () => {
            return $.ajax({
                url: baseUrl + 'management/delete-entry',
                type: 'POST',
                data: {
                    id: id,
                    managementType: $('#managementType').val()
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
                text: `${managementTypeName} has been successfully deleted.`,
                icon: 'success',
                confirmButtonText: 'Ok'
            }).then(() => {
                loadGenericNameListDatatable();
            });
        }
    });
}

function loadUserManagementSection() {
    $('#userManagementSection').show();
    $('#userManagementSection').find('input, select').prop('required', true);
}

function loadUserRoleManagementSection() {
    $('#userRoleManagementSection').show();
    $('#userRoleManagementSection').find('input, select').prop('required', true);
    $('#mgmtRoleDescription').prop('required', false); // Since the description is only optional
}