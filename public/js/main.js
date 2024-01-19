function getFormData($form){
    var unindexed_array = $form.serializeArray();
    var indexed_array = {};

    $.map(unindexed_array, function(n, i){
        indexed_array[n['name']] = n['value'];
    });

    return indexed_array;
}

function renderData(post){
    header = `
    <div class="card" id="${post.id}" style="width: 18rem;">
<!--        <img src="${post.image}" class="card-img-top"  alt="...">-->
            <div class="card-body">
                <h2>${post.user}</h2>
                <p class="card-text">${post.content}</p>
<!--                <a href="#" class="btn btn-primary">Go somewhere</a>-->
            </div>
    </div>
    `
    return header;
}

function getData(data){
    $.ajax({
        method: "GET",
        url: "/api/post",
        data: data,
        headers: {'Accept': 'application/json' },

        success: function (data, textStatus, jqXHR) {
            console.log(data);
            var post = data.data;
            var htmlContent = '';
            post.forEach(function (item){
                htmlContent += renderData(item);
            }, this)
            $('#postList').html(htmlContent);
        },
        error: function(data, textStatus, jqXHR){
            // if(jqXHR.status == 422){
            //     error = jqXHR.responseJSON.errors;
            //     $('#error').html(jqXHR.responseJSON.message);
            // }
        }
    })
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
                setTimeout(window.location.assign('/post'),2000);
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


    $('form#postForm').on('submit', function (event) {
        event.preventDefault();
        data = getFormData($(this));
        $('#error').html('');
        $.ajax({
            method: "POST",
            url: "/api/post",
            data: data,
            headers: {'Accept': 'application/json' },

            success: function (data, textStatus, jqXHR) {
                $('#content').val('');
                getData(data);
            },

            error: function(jqXHR, textStatus, errorThrown){
                if(jqXHR.status == 422){
                    error = jqXHR.responseJSON.errors;
                    $('#error').html(jqXHR.responseJSON.message);
                }
            }
        })
    });
});

