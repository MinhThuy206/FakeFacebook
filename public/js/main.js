function getFormData($form){
    var unindexed_array = $form.serializeArray();
    var indexed_array = {};

    $.map(unindexed_array, function(n, i){
        indexed_array[n['name']] = n['value'];
    });

    return indexed_array;
}
$(document).ready(function (){
    $('form#login').on('submit', function (e){
        e.preventDefault();
        data = getFormData($(this));
        $('#error').html('');
        $.ajax({
            method: "POST",
            url: "/api/login",
            data: data,

            success: function(data, textStatus, jqXHR){
                console.log(data);
                alert();
                location.href = '/post';
            },
            error: function(jqXHR, textStatus, errorThrown){
                if(jqXHR.status == 422){
                    error = jqXHR.responseJSON.errors;

                    if(error.email){
                        var data;
                        data = '';
                        error.email.forEach(function (item){
                            data += item + '</br>';
                        }, this)
                        $('#email-error').html(data);
                    }else{
                        $('#email-error').html('');
                    }

                    if(error.password){
                        var data;
                        data = '';
                        error.password.forEach(function (item){
                            data += item + '</br>';
                        }, this)
                        $('#password-error').html(data);
                    }else{
                        $('#password-error').html('');
                    }
                }else{
                    $('#error').html(jqXHR.responseJSON.message);
                }
            }
        })
    });

    $('form#register').on('submit', function (e){
        e.preventDefault();
        data = getFormData($(this));
        $('#error').html('');
        $.ajax({
            method: "POST",
            url: "/api/register",
            data: data,

            success: function(data, textStatus, jqXHR){
                location.href = '/login';
            },
            error: function(jqXHR, textStatus, errorThrown){
                if(jqXHR.status == 422){
                    error = jqXHR.responseJSON.errors;

                    if(error.email){
                        var data;
                        data = '';
                        error.email.forEach(function (item){
                            data += item + '</br>';
                        }, this)
                        $('#email-error').html(data);
                    }else{
                        $('#email-error').html('');
                    }

                    if(error.password){
                        var data;
                        data = '';
                        error.password.forEach(function (item){
                            data += item + '</br>';
                        }, this)
                        $('#password-error').html(data);
                    }else{
                        $('#password-error').html('');
                    }

                    if(error.phone){
                        var data;
                        data = '';
                        error.phone.forEach(function (item){
                            data += item + '</br>';
                        }, this)
                        $('#phone-error').html(data);
                    }else{
                        $('#phone-error').html('');
                    }

                    if(error.name){
                        var data;
                        data = '';
                        error.name.forEach(function (item){
                            data += item + '</br>';
                        }, this)
                        $('#name-error').html(data);
                    }else{
                        $('#name-error').html('');
                    }

                }else{
                    $('#error').html(jqXHR.responseJSON.message);
                }
            }
        })
    })

    $('form#post').on('submit', function (e){
        e.preventDefault();
        data = getFormData($(this));
        $('#error').html('');
        $.ajax({
            method: "POST",
            url: "/api/post",
            data: data,

            success: function(data, textStatus, jqXHR){
                location.href = '/showpost';
            },
            error: function(jqXHR, textStatus, errorThrown){
                if(jqXHR.status == 422){
                    error = jqXHR.responseJSON.errors;

                    if(error.content){
                        var data;
                        data = '';
                        error.content.forEach(function (item){
                            data += item + '</br>';
                        }, this)
                        $('#content-error').html(data);
                    }else{
                        $('#content-error').html('');
                    }
                }else{
                    $('#error').html(jqXHR.responseJSON.message);
                }
            }
        })
    });

});

