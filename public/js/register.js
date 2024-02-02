$(document).ready(function () {
    $('form#register').on('submit', function (e) {
        e.preventDefault();
        data = getFormData($(this));
        $('#error').html('');
        $.ajax({
            method: "POST",
            url: "/api/register",
            data: data,

            success: function (data, textStatus, jqXHR) {
                location.href = '/login';
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

                    if (error.phone) {
                        var data;
                        data = '';
                        error.phone.forEach(function (item) {
                            data += item + '</br>';
                        }, this)
                        $('#phone-error').html(data);
                    } else {
                        $('#phone-error').html('');
                    }

                    if (error.name) {
                        var data;
                        data = '';
                        error.name.forEach(function (item) {
                            data += item + '</br>';
                        }, this)
                        $('#name-error').html(data);
                    } else {
                        $('#name-error').html('');
                    }

                } else {
                    $('#error').html(jqXHR.responseJSON.message);
                }
            }
        })
    })

});


