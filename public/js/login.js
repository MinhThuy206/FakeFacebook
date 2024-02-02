$(document).ready(function () {
    $('form#login').on('submit', function (e) {
        e.preventDefault();
        data = getFormData($(this));
        $('#error').html('');
        $.ajax({
            method: "POST",
            url: "/api/login",
            data: data,

            success: function (data, textStatus, jqXHR) {
                setTimeout(window.location.assign('/post'), 2000);
            },
            error: function (jqXHR, textStatus, errorThrown) {
                if (jqXHR.status == 422) {
                    error = jqXHR.responseJSON.errors;

                    if (error.email) {
                        var data;
                        data = '';
                        error.email.forEach(function (item) {
                            data += item + '</br>';
                        }, this)
                        $('#email-error').html(data);
                    } else {
                        $('#email-error').html('');
                    }

                    if (error.password) {
                        var data;
                        data = '';
                        error.password.forEach(function (item) {
                            data += item + '</br>';
                        }, this)
                        $('#password-error').html(data);
                    } else {
                        $('#password-error').html('');
                    }
                } else {
                    $('#error').html(jqXHR.responseJSON.message);
                }
            }
        })
    });

});


