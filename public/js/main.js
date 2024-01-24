var image_arr;

function getFormData($form) {
    var unindexed_array = $form.serializeArray();
    var indexed_array = {};

    $.map(unindexed_array, function (n, i) {
        indexed_array[n['name']] = n['value'];
    });

    return indexed_array;
}


// render data to card(image + post)
function renderData(post) {
    html = `<div class="card" id="${post.id}" style="width: 50rem">
               <h2>${post.user}</h2>
                <div class="btn-group">
                      <button type="button" class="dropdown-toggle" data-bs-toggle="dropdown"" aria-expanded="false">
                      </button>
                      <ul class="dropdown-menu dropdown-menu-end">
                        <li><button class="dropdown-item delete-post" type="button" data-id=${post.id}>Delete</button></li>
                        <li><button class="dropdown-item edit-post" type="button" data-id=${post.id}>Edit</button></li>
                      </ul>
                </div>`;

    html += ` <div class="card-body">
     <p class="card-text">${post.content}</p>
            </div>`

    post.images.forEach(function (item) {
        var url = "../" + item.url;
        // console.log(url);
        html += ` <div class="image-container">
                        <img src="${url}"  width="200px"  alt="...">
<!--                        <button class="btn btn-danger delete-image position-absolute top-0 end-0" data-id="${item.id}">&times;</button>-->
                </div>`
    }, this)

    html += `</div>`

    return html;
}


// get content form post
function getData(data) {
    $.ajax({
        method: "GET",
        url: "/api/post",
        data: data,
        headers: {'Accept': 'application/json'},

        success: function (data, textStatus, jqXHR) {
            // console.log(data);
            var post = data.data;
            var htmlContent = '';
            post.forEach(function (item) {
                htmlContent += renderData(item);
            }, this)
            $('#postList').html(htmlContent);
            $("button.delete-post").on('click', function () {
                var postId = $(this).data("id");
                console.log(postId);
                $.ajax({
                    method: "DELETE",
                    url: "/api/post/" + postId,
                    headers: {'Accept': 'application/json'},

                    success: function (data, textStatus, jqXHR) {
                        location.reload();
                    },
                    error: function (data, textStatus, jqXHR) {
                        console.log(data);
                    }
                })
            });

            $("button.edit-post").on('click', function () {
                var postId = $(this).data("id");
                var card = $('#' + postId);
                card.find('.delete-image').show();
                var currentContent = card.find('.card-text').text();
                var currentImages = card.find('img').map(function () {
                    return $(this).attr('src');
                }).get();

                var editInterface = `
                    <form id="postForm" xmlns="http://www.w3.org/1999/html">
                        <textarea class="form-control">${currentContent}</textarea>
                        <input type="file" id="image" multiple accept="image/*" onchange="xulyfile()" name="f1">

                        <button class="btn btn-primary save-post" type="submit" data-id="${postId}">Save</button>
                    </form>
                `;

                card.find('.card-text').html(editInterface);

                // currentImages.forEach(function(image) {
                //     imageInput.before()
                // });

                $("button.save-post").on('click', function (event) {
                    var postId = $(this).data('id');
                    var card = $('#' + postId);

                    // Lấy nội dung mới từ textarea
                    var updatedContent = card.find('textarea').val();

                    $.ajax({
                        url: '/api/post/' + postId,
                        type: 'PUT',
                        data: { content: updatedContent },
                        success: function(response) {
                            console.log(1);
                            console.log(image_arr);
                            // card.find('.card-text').text(updatedContent);
                            for (i = 0; i < image_arr.length; i++) {
                                $.ajax({
                                    method: "PUT",
                                    url: "api/image",
                                    data: {
                                        "post_id": postId,
                                        "image_id": image_arr[i]
                                    },
                                })
                            }
                            location.reload();
                        },
                        error: function(error) {
                            console.error('Error updating post:', error);
                        }
                    });
                });

            });

        },
        error: function (data, textStatus, jqXHR) {
            // if(jqXHR.status == 422){
            //     error = jqXHR.responseJSON.errors;
            //     $('#error').html(jqXHR.responseJSON.message);
            // }
        }


    })
}



function xulyfile() {
    image_arr = [];
    var arr = $('form#postForm input#image').prop('files');
    var imagePreview = document.getElementById('imagePreview');
    // console.log(arr);
    var formData;
    // console.log(arr);
    for (i = 0; i < arr.length; i++) {
        formData = new FormData();
        f = arr[i];
        formData.append('image', f);

        var reader = new FileReader();
        reader.onload = function (e) {
            var imgElement = new Image();
            imgElement.src = e.target.result;
            imgElement.width = 200; // Đặt chiều rộng là 200px
            imgElement.alt = 'Ảnh trước khi gửi';

            // Thêm ảnh mới vào cuối danh sách các ảnh
            imagePreview.appendChild(imgElement);
        };
        reader.readAsDataURL(f);

        $.ajax({
            contentType: false,
            method: "POST",
            url: "/api/image",
            data: formData,
            processData: false,

            success: function (data, textStatus, jqXHR) {
                var id = data.id
                image_arr.push(id);
            },
        })
    }

}


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


    $('form#postForm').on('submit', function (event) {
        event.preventDefault();
        data = getFormData($(this));
        $('#error').html('');
        $.ajax({
            method: "POST",
            url: "/api/post",
            data: data,
            headers: {'Accept': 'application/json'},

            success: function (data, textStatus, jqXHR) {
                var post_id = data.id;
                for (i = 0; i < image_arr.length; i++) {
                    $.ajax({
                        method: "PUT",
                        url: "api/image",
                        data: {
                            "post_id": post_id,
                            "image_id": image_arr[i]
                        },
                    })
                }
                location.reload();
            },

            error: function (jqXHR, textStatus, errorThrown) {
                if (jqXHR.status == 422) {
                    error = jqXHR.responseJSON.errors;
                    $('#error').html(jqXHR.responseJSON.message);
                }
            }
        })
    });

});

