(function (e) {

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // Form submission handler
    $("#UserForm").on('submit', function (e) {

        e.preventDefault();

        if (validateSubmit('on')) {

            var data = new FormData(this);
            var url = $(this).attr('action');
            var method = $(this).attr('method').toUpperCase();
            var mode = $(this).attr('mode');

            $.ajax({
                url, method, data, cache: false, contentType: false, processData: false,
                beforeSend: function () {
                    // Disable all submit button
                    $('[type="submit"]').attr('disabled', 'true');
                },
                success: function (response) {

                    if (response.success == true) {
                        responseMessage('success', response.message, '', '#pageMessage');
                        $('#exampleModal').modal('hide');

                        prependUser(response.user, mode);
                    }
                    else if (response.error) {
                        responseMessage('error', response.error.message);
                        if (response.errors != undefined) {
                            Object.keys(response.errors).forEach(key => {
                                fieldError($(`[name="${key}"]`), response.errors[key][0]);
                            });
                        }
                    }

                    $('[type="submit"]').removeAttr('disabled');
                },
                error: function (error) {
                    responseMessage('error', 'Error occurred! Please try again.');
                    $('[type="submit"]').removeAttr('disabled');
                }
            });
        }
    });

    // Add user button controller
    window.addUser = function () {
        // #enable create mode on User form
        $("#UserForm")
            .attr('action', '/api/add-user')
            .attr('mode', 'create')
            .attr('method', 'POST');

        // Custom field control
        $(`input[type=text], input[type=email], input[type=password], select`).val('');
        $(`input[type=password]`).attr('validate-field', 'true');
        $(`[name=email]`).removeAttr('readonly');
        $(`[name=employee_id]`).removeAttr('readonly');

        $('#exampleModalLabel').html(`Add User`);
        $('.add-user-btn').html('Add User');
        $('#exampleModal').modal('show');
    }

    // Edit user controller
    function editUser() {
        $(".edit-user").on('click', function (e) {
            e.preventDefault();
            var url = $(this).attr('href');
            $.ajax({
                url, method: 'GET', data: null,
                success: function (response) {

                    if (response.success) {
                        Object.keys(response.user).forEach(key => {
                            if ($(`[name="${key}"]`)) {
                                $(`[name="${key}"]`).val(response.user[key]);
                            }
                        });

                        response.user.permissions?.forEach((permission, index) => {

                            (permission.read_mode == 1) ?
                                $(`#flexCheckCheckedRead${permission.permission_id}`).attr('checked', true)
                                : $(`#flexCheckCheckedRead${permission.permission_id}`).removeAttr('checked');

                            (permission.write_mode == 1) ?
                                $(`#flexCheckCheckedWrite${permission.permission_id}`).attr('checked', true)
                                : $(`#flexCheckCheckedWrite${permission.permission_id}`).removeAttr('checked');

                            (permission.delete_mode == 1) ?
                                $(`#flexCheckCheckedDelete${permission.permission_id}`).attr('checked', true)
                                : $(`#flexCheckCheckedDelete${permission.permission_id}`).removeAttr('checked');
                        });

                        // #enable update mode on User form
                        $("#UserForm")
                            .attr('action', '/api/user/update')
                            .attr('mode', 'update')
                            .attr('method', 'POST');

                        // Custome field control
                        $(`input[type=password]`).attr('validate-field', 'false');
                        $(`[name=email]`).attr('readonly', 'true');
                        $(`[name=employee_id]`).attr('readonly', 'true');
                        
                        
                        $('#exampleModalLabel').html(`Edit User: #${response.user.id}`);
                        $('.add-user-btn').html('Save Changes');
                        $('#exampleModal').modal('show');
                    }
                    else {
                        responseMessage('error', response.error.message, '', '#pageMessage');
                    }
                }
            })
        });
    };

    // Search user controller
    window.searchUser = function(e) {

        var keywords = $(e).val();
        $.ajax({ 
            url: '/api/search/users',
            method: 'GET',
            data: {keywords},
            success: function(response) {
                if(response.success) {
                    $('#users-list').html('');
                    response.users.forEach((user, index) => {
                        prependUser(user);
                    });
                } else {
                    $('#users-list').html(`<tr><td colspan="5"><p class="text-center">${response.message}</p></td></tr>`);
                }
            }
         })

    }

    // Delete user controller
    function deleteUser() {
        $(".delete-user").on('click', function (e) {
            e.preventDefault();
            var url = $(this).attr('href');
            var row = $(this).attr('row-id');
            $.ajax({
                url, method: 'DELETE', data: null,
                success: function (response) {
                    if (response.success) {
                        responseMessage('success', response.message, '', '#pageMessage');
                        // # remove deleted user from the list
                        $(row).remove();
                    } else {
                        responseMessage('error', response.error.message, '', '#pageMessage');
                    }
                }
            });
        });
    }

    // Actions handles init function
    var actionsHandler = () => {
        editUser();
        deleteUser();
    }

    actionsHandler();

    // Prepend User function on success user creation
    function prependUser(user, mode = 'create') {

        var access = function (access_level) {
            var indicator = null;
            switch (access_level.id) {
                case 1:
                    indicator = `<span class="badge p-2 bg-red">${access_level.name}</span>`;
                    break;
                case 2:
                    indicator = `<span class="badge p-2 bg-primary">${access_level.name}</span>`;
                    break;
                case 3:
                    indicator = `<span class="badge p-2 bg-light">${access_level.name}</span>`;
                    break;
                case 4:
                    indicator = `<span class="badge p-2 bg-green">${access_level.name}</span>`;
                    break;
                default:
                    break;
            }

            return (
                `<div class="bhr__user-access"> ${indicator}</div>`
            );
        }

        function details(user) {
            return `<div class="d-flex">
            <div class="bhr__user-image">
                <img src="/assets/images/avatar-${user.id}.jpg" alt="">
            </div>
            <div class="bhr__user-details">
                <strong>${user.firstname + ' ' + user.lastname}</strong>
                <p class="text-muted p-0 m-0">${user.email}</p>
            </div>
        </div>`;
        }

        var new_user = (`<tr id="RowID${user.id}">
            <td>${details(user)}</td>
            <td>${access(user.access_level)}</td>
            <td> <p class="mt-2 mb-0">${user.created_at}</p></td>
            <td><p class="mt-2 mb-0">${user.role_type.name}</p></td>
            <td>
                <div class="bhr__actions mt-2">
                    <a href="/api/user/${user.id}" class="edit-user me-2"> <i data-feather="edit" class="fa fa-edit icon-sm"></i> </a>
                    <a href="/api/user/delete/${user.id}" row-id="#RowID${user.id}" class="delete-user"> <i data-feather="trash" class="fa fa-trash-alt icon-sm"></i> </a>
                </div>
            </td>
        </tr>`);

        if (mode == 'update') {
            $(`#RowID${user.id}`).after(new_user).remove();
        } else if (mode == 'create') {
            $("#users-list").prepend(new_user);
        }

        actionsHandler();
    }

    // response message component
    var responseMessage = (status, message, styles = '', display = null) => {

        var output = display ?? "#message";

        switch (status) {
            case 'error':
                styles += ' alert-danger';
                break;
            case 'success':
                styles += ' alert-success';
                break;
            case 'warning':
                styles += ' alert-warning';
                break;
            case 'info':
                styles += ' alert-info';
                break;
        }
        var content = `<div class="alert ${styles}">
                <div class='d-flex justify-content-between'>
                    <p class='p-0 m-0'>${message}</p>
                    <span type='button' class='msg-close btn-close text-${status}'></span>
                </div>
            </div>`;

        $(output).html(content);
        $(".msg-close").on('click', function () {
            $(output).html('');
        });

        setTimeout(function () { $(output).html('') }, 10000);
    }

    // form validation controller
    var validateSubmit = (strict = 'off', agree = false) => {
        var validation = true;
        $('[validation-result="true"]').remove();

        $('[validate-field="true"]').each(function () {
            $(this).css('border-color', '#dcdcdc');
            if ($(this).val() == "") {
                var message = $(this).attr("validation-message");
                if (message != "" && message != null) {
                    fieldError($(this), message);
                }
                validation = false;
            }

            // validate strict mode
            if (strict == 'on') {

                if ($(this).attr("type") == "email") {
                    var val = $(this).val();
                    var regex = /[a-zA-Z0-9!@._-]+$/g;
                    if (!val.match(regex)) {
                        fieldError($(this), "Kindly enter a valid email address!");
                        validation = false;
                    }
                }

                if ($(this).attr("type") == "password") {
                    var val = $(this).val();
                    if (val != '' && !$(this).attr('validate-confirm-password')) {
                        var regex = /[a-zA-Z0-9!@#\$%\^\&*\)\(+=._-]+$/g;
                        if (!val.match(regex)) {
                            fieldError(
                                $(this),
                                "Password must have at least one uppercase, lowercase and special char e.g !@#%=*($-?."
                            );
                            validation = false;
                        }
                    }
                }

                if ($(this).attr('validate-confirm-password')) {
                    var $value = $(this).val();
                    var $password = $($(this).attr('validate-confirm-password')).val();

                    if ($password != $value) {
                        fieldError(
                            $(this),
                            "Password not matched."
                        );
                        validation = false;
                    }
                }
            }

        });

        if (agree) {
            if ($("#agree").prop("checked") != true) {
                $('[for="agree-result"]').after(
                    '<small class="text-danger" validationresult="true"><br>kindly read and agree to continue.</small>'
                );

                validation = false;
            }
        }

        return validation;
    };

    // field error component
    var fieldError = (field, message) => {
        if (field.attr("validation-output")) {
            var target = field.attr("validation-output");
            field.css("border-color", "red");
            $(target).html(
                '<small class="text-danger" validation-result="true">' +
                message +
                "</small>"
            );
        } else {
            field.siblings('[validation-result="true"]').remove();
            field
                .css("border-color", "red")
                .after(
                    '<small class="text-danger" validation-result="true">' +
                    message +
                    "</small>"
                );
        }
    };

    // Page Interactive event listeners
    $('.form-check-input').on('change', function () {
        ($(this).prop('checked') == true) ? $(this).val('on') : $(this).val('off');
    })

    $(".side-panel-toggle").on('click', function(){
        $(".bhr__content").toggleClass('extend');
        $(".bhr__sidebar").toggleClass('small');
    });

    // $(".mobile-slide-toggle").on('click', function() {
    //     $(".bhr__sidebar").toggleClass('mobile-slide');
    // });



    // init feather icons
    feather.replace();
})();

