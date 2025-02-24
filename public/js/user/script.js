$(document).ready(function () {
    showUsers();
    showUser();
    editUser();
    deleteUser();
    reports();
});

function showUsers() {
    $('#table-user').DataTable({
        "processing": true,
        "serverSide": true,
        "ajax": {
            "url": "/users/showUsersByFilters",
            "type": "GET",
            "dataSrc": "data",
            "data": function(d) {
                if (!d.order || d.order.length === 0) {
                    d.order = [{ column: 0, dir: 'asc' }];
                }
            }
        },
        "columns": [
            { "data": "name", "name": "name" },
            { "data": "email", "name": "email" },
            { "data": "password", "name": "password" },
            {
                "data": null,
                "name": "actions",
                "orderable": false,
                "render": function(data, type, row) {
                    return '<a data-id="' + row.id + '" class="btn btn-primary show-user">View</a> ' +
                        '<a data-id="' + row.id + '" class="btn btn-warning edit-user">Edit</a> ' +
                        '<a data-id="' + row.id + '" class="btn btn-danger delete-user">Delete</a>';
                }
            }
        ],
        "pageLength": 20,
        "lengthMenu": [20, 50, 100],
        "order": [[0, "asc"]],
        "language": {
            "emptyTable": "No users found.",
            "zeroRecords": "No users found.",
            "info": "Showing _START_ to _END_ of _TOTAL_ users",
            "infoEmpty": "Showing 0 to 0 of 0 users",
            "infoFiltered": "(filtered from _MAX_ total users)",
            "lengthMenu": "Show _MENU_ users",
            "search": "Search:",
            "paginate": {
                "first": "First",
                "last": "Last",
                "next": "Next",
                "previous": "Previous"
            }
        }
    });
}

function showUser() {
    $(document).on('click', '.show-user', function (e) {
        const userId = $(this).data('id');

        $.ajax({
            url: "/users/" + userId,
            type: "GET",
            dataType: "JSON",
            success: function (data) {
                const html = `
                   <table class="table table-bordered table-striped table-hover">
                        <thead class="table">
                            <tr>
                                <th scope="col">Name</th>
                                <th scope="col">Email</th>
                                <th scope="col">Password</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>${Utils.toTitleCase(data.name)}</td>
                                <td>${Utils.toLowerCase(data.email)}</td>
                                <td>${data.password}</td>
                            </tr>
                        </tbody>
                    </table>
                `;

                new $.Zebra_Dialog({
                    type: "info",
                    title: "User details",
                    width: "75%",
                    message: html,
                    buttons: [
                        {
                            caption: "Close"
                        }
                    ]
                });
            },
            error: function (e) {
                new $.Zebra_Dialog({
                    type: "error",
                    title: "Error",
                    message: "Failed to load user details.",
                    buttons: [
                        {
                            caption: "Close"
                        }
                    ]
                });
            }
        });
    });
}

function editUser() {
    $(document).on('click', '.edit-user', function (e) {
        const userId = $(this).data('id');

        $.ajax({
            url: "/users/" + userId,
            type: "GET",
            success: function (data) {
                const html = `
                <form id="editUserForm" class="container-fluid">
                    <input type="hidden" id="userId" name="id" value="${data.id}">

                    <div class="col-auto mb-3">
                        <label for="userName" class="form-label">Name</label>
                        <input type="text" class="form-control" id="userName" name="name" value="${data.name}" required maxlength="100">
                    </div>

                    <div class="col-auto mb-3">
                        <label for="userEmail" class="form-label">Email</label>
                        <input type="email" class="form-control" id="userEmail" name="email" value="${data.email}" required maxlength="100">
                    </div>

                    <div class="col-auto mb-3">
                        <label for="userPassword" class="form-label">Password</label>
                        <input type="password" class="form-control" id="userPassword" name="password" placeholder="Leave blank to keep current password" maxlength="255">
                    </div>
                </form>
                `;

                const dialog = new $.Zebra_Dialog({
                    type: "info",
                    title: "Edit User",
                    width: "50%",
                    message: html,
                    buttons: [
                        {
                            caption: "Close",
                            callback: function () {
                                dialog.close();
                            }
                        },
                        {
                            caption: "Save Changes",
                            callback: function () {
                                const formData = {
                                    id: $("#userId").val(),
                                    name: $("#userName").val(),
                                    email: $("#userEmail").val(),
                                    password: $("#userPassword").val() || undefined
                                };

                                $.ajax({
                                    url: "/users/" + formData.id,
                                    type: "PUT",
                                    contentType: "application/json",
                                    data: JSON.stringify(formData),
                                    success: function (response) {
                                        new $.Zebra_Dialog({
                                            type: "success",
                                            title: "Success",
                                            message: "User updated successfully!",
                                            onClose: function () {
                                                location.reload();
                                            },
                                            buttons: [
                                                {
                                                    caption: "OK",
                                                    callback: function () {
                                                        location.reload();
                                                    }
                                                }
                                            ]
                                        });
                                    },
                                    error: function (request, status, error) {
                                        const errors = JSON.parse(request.responseText).errors;
                                        let html = "";

                                        Object.entries(errors).forEach(([key, value]) => {
                                            html += `<li>${value}</li>`;
                                        });

                                        new $.Zebra_Dialog({
                                            type: "error",
                                            title: "Error",
                                            message: html,
                                            buttons: [
                                                {
                                                    caption: "Close"
                                                }
                                            ]
                                        });

                                        return false;
                                    }
                                });
                                return false;
                            }
                        }
                    ]
                });
            },
            error: function (e) {
                new $.Zebra_Dialog({
                    type: "error",
                    title: "Error",
                    message: "Failed to load user details.",
                    buttons: [
                        {
                            caption: "Close"
                        }
                    ]
                });
            }
        });
    });
}

function deleteUser() {
    $(document).on('click', '.delete-user', function () {
        const userId = $(this).data('id');
        const userRow = $(this).closest('tr');

        new $.Zebra_Dialog({
            type: "question",
            title: "Delete user",
            message: "Are you sure you want to delete this user?",
            buttons: [
                {
                    caption: "Yes",
                    callback: function () {
                        $.ajax({
                            url: "/users/" + userId,
                            type: "DELETE",
                            success: function (data) {
                                $.toast({
                                    text: "User deleted successfully.",
                                    showHideTransition: 'slide',
                                    icon: 'success'
                                });

                                userRow.remove();
                            },
                            error: function (e) {
                                $.toast({
                                    text: "Failed to delete user.",
                                    showHideTransition: 'fade',
                                    icon: 'error'
                                });
                            }
                        });
                    }
                },
                {
                    caption: "No"
                }
            ]
        });
    });
}

function reports() {
    $(document).on('click', '.reports', function () {
        const html = `
            <div class="col-md-12 d-flex justify-content-between gap-4">
                <select class="form-select w-100" id="exportSelect">
                    <option value="pdf">PDF</option>
                    <option value="excel">Excel</option>
                </select>
            </div>
        `;
        new $.Zebra_Dialog({
            type: "info",
            title: "User Reports",
            message: html,
            width: "25%",
            buttons: [
                {
                    caption: "Export",
                    callback: function () {
                        const exportType = $("#exportSelect").val();
                        const url = "/users/" + exportType;
                        window.open(url, '_self');
                    }
                }
            ]
        })
    });
}