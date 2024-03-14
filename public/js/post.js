var image_arr;

function renderData(post) {
    html = `<div class="card mb-6" id="${post.id}" style="max-width: 100rem; margin: auto">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4>${post.user}</h4>
                    <div class="btn-group">
                        <button type="button" class="btn btn-secondary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">

                        </button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><button class="dropdown-item delete-post" type="button" data-id="${post.id}">Delete</button></li>
                            <li><button class="dropdown-item edit-post" type="button" data-id="${post.id}">Edit</button></li>
                        </ul>
                    </div>
                </div>

                <div class="card-body d-flex justify-content-between align-items-center">
                    <p class="card-text">${post.content}</p>
                </div>

                <div class="card-body d-flex flex-wrap justify-content-center">`; // Sử dụng flexbox để tự động sắp xếp các ảnh

    // Kiểm tra nếu chỉ có một ảnh thì đặt kích thước cho nó bằng với kích thước của ô chứa
    if (post.images.length === 1) {
        var url = "../" + post.images[0].url;
        html += `<div class="image-container" style="width: 100%; height: 800px; margin: 5px;"> <!-- Thiết lập kích thước và margin -->
                    <img style="width: 100%; height: 100%; object-fit: cover;" src="${url}" alt="...">
                </div>`;
    } else {
        post.images.forEach(function (item) {
            var url = "../" + item.url;
            html += `<div class="image-container" style="width: 200px; height: 200px; margin: 5px;"> <!-- Thiết lập kích thước và margin -->
                        <img style="width: 100%; height: 100%; object-fit: cover;" src="${url}" alt="...">
                    </div>`;
        }, this);
    }
    html += `</div>
        </div>`;

    return html;
}



function xulyfile() {
    image_arr = [];
    var arr = $('form#postForm input#image').prop('files');
    var imagePreview = document.getElementById('imagePreview');
    var formData;
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

function getData(data) {
    $.ajax({
        method: "GET",
        url: "/api/post",
        data: data,
        headers: {'Accept': 'application/json'},

        success: function (data, textStatus, jqXHR) {
            var post = data.data;
            var htmlContent = '';
            post.forEach(function (item) {
                htmlContent += renderData(item);
            }, this)
            $('#postList').html(htmlContent);
            $("button.delete-post").on('click', function () {
                var postId = $(this).data("id");
                $.ajax({
                    method: "DELETE",
                    url: "/api/post/" + postId,
                    headers: {'Accept': 'application/json'},

                    success: function (data, textStatus, jqXHR) {
                        getData(data)
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

                $("button.save-post").on('click', function (event) {
                    var postId = $(this).data('id');
                    var card = $('#' + postId);

                    var updatedContent = card.find('textarea').val();

                    $.ajax({
                        url: '/api/post/' + postId,
                        type: 'PUT',
                        data: {content: updatedContent},
                        success: function (data, textStatus, jqXHR) {
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
                        error: function (error) {
                            console.error('Error updating post:', error);
                        }
                    });

                });
            });
        },
        error: function (data, textStatus, jqXHR) {
            console.log(1)
        }
    })
}

$(document).ready(function () {
    $('form#postForm').on('submit', function (event) {
        event.preventDefault();
        data = getFormData($(this));
        $('#content').html(' ');
        $.ajax({
            method: "POST",
            url: "/api/post",
            data: data,
            headers: {'Accept': 'application/json'},

            success: function (data, textStatus, jqXHR) {
                if (typeof image_arr != 'undefined') {
                    var post_id = data.id;
                    for (i = 0; i < image_arr.length; i++) {
                        $.ajax({
                            method: "PUT",
                            url: "/api/image",
                            data: {
                                "post_id": post_id,
                                "image_id": image_arr[i]
                            },
                            headers: {'Accept': 'application/json'},
                            success: function (data, textStatus, jqXHR) {
                                getData(data);
                                $('form#postForm').trigger("reset");
                                console.log("Upload post success")
                            },
                            error: function (data, textStatus, jqXHR) {
                                console.log(data);
                            }
                        })
                    }
                } else {
                    getData(data);
                    $('form#postForm').trigger("reset");
                }

            },
            error: function (jqXHR, textStatus, errorThrown) {
                if (jqXHR.status == 422) {
                    error = jqXHR.responseJSON.errors;
                    $('#error').html(jqXHR.responseJSON.message);
                }
            }
        })
    });
})
