function getFormData($form){
    var unindexed_array = $form.serializeArray();
    var indexed_array = {};

    $.map(unindexed_array, function(n, i){
        indexed_array[n['name']] = n['value'];
    });

    return indexed_array;
}

function renderData(post){
    html = `<h2>${post.user}</h2>`;
    post.images.forEach(function(item){
        var url = "../" + item.url;
        console.log(url);
        html += `<img src="${url}"  width="500px"  alt="...">`
    },this)

    html += ` <div class="card-body">
     <p class="card-text">${post.content}</p>
     <p>-------------------------------------------------------</p>
<!--<a href="#" class="btn btn-primary">Go somewhere</a>-->
            </div>`

    header = `
    <div class="card" id="${post.id}" style="width: 50rem">

    </div>
    `


    return html;
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

var image_arr;

function xulyfile(){
    image_arr =[];
    var arr = $('form#postForm input#image').prop('files');
    var formData;
    // console.log(arr);
    for(i=0; i<arr.length; i++){
        formData = new FormData();
        f = arr[i];
        formData.append('image', f);
        // console.log( f.name, f.size, f.type );
        $.ajax({
            contentType : false,
            method : "POST",
            url : "/api/image",
            data : formData,
            processData: false,

            success: function(data, textStatus, jqXHR){
                var id = data.id
                image_arr.push(id);
            },
        })
    }
    // console.log(image_arr);
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
                var post_id = data.id;
                for(i=0; i< image_arr.length;i++){
                    $.ajax({
                        method: "PUT",
                        url : "api/image",
                        data: {"post_id": post_id,
                            "image_id": image_arr[i]},
                    })
                }
                location.reload();
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

