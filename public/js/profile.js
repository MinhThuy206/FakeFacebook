var avatar_arr;
var cover_arr;
document.addEventListener('DOMContentLoaded', function () {
    var profileOptions = document.querySelectorAll('.profile-option');
    profileOptions.forEach(function (option) {
        option.addEventListener("click", function (event) {
            event.preventDefault();
            profileOptions.forEach(function (opt) {
                opt.classList.remove('active');
            });
            option.classList.add('active');
        });
    });

    // Thêm sự kiện cho nút chỉnh sửa trang cá nhân
    var editProfileButton = document.querySelector('.edit-profile-button');
    var editModal = document.getElementById('editModal');
    var closeModalButton = document.querySelector('.close');
    var overlay = document.getElementById('overlay');

    editProfileButton.addEventListener('click', function () {
        editModal.style.display = 'block';
        overlay.style.display = 'block';
    });

    closeModalButton.addEventListener('click', function () {
        editModal.style.display = 'none';
        overlay.style.display = 'none';
    });

    window.addEventListener('click', function (event) {
        if (event.target === overlay) {
            editModal.style.display = 'none';
            overlay.style.display = 'none'
        }
    });
});

function xulyfileAvt() {
    avatar_arr = [];
    var arr = $('form#postAvatar input#image').prop('files');
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
                avatar_arr.push(id);
            },
        })
    }
}
function xulyfileCover() {
    cover_arr = [];
    var arr = $('form#postCover input#image').prop('files');
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
                cover_arr.push(id);
            },
        })
    }
}

$(document).ready(function () {
    $('form#postAvatar').on('submit', function (event) {
        event.preventDefault();
        data = getFormData($(this));
        $('#error').html('');
        $.ajax({
            method: "POST",
            url: "/api/post",
            data: data,
            headers: {'Accept': 'application/json'},

            success: function (data, textStatus, jqXHR) {
                if (typeof avatar_arr != 'undefined') {
                    var post_id = data.id;
                    for (i = 0; i < avatar_arr.length; i++) {
                        var image = avatar_arr[i];
                        $.ajax({
                            method: "PUT",
                            url: "/api/image",
                            data: {
                                "post_id": post_id,
                                "image_id": avatar_arr[i]
                            },
                            headers: {'Accept': 'application/json'},
                            success: function (data, textStatus, jqXHR) {
                                $.ajax({
                                    method: "PUT",
                                    url: '/api/image/avatar/' + image,
                                    headers: {'Accept': 'application/json'},
                                    success: function (data, textStatus, jqXHR) {
                                        console.log("Update cover success")
                                        location.reload();
                                    },
                                    error: function (data, textStatus, jqXHR) {
                                        console.log("Update avatar fail")
                                    }
                                })
                            },
                            error: function (data, textStatus, jqXHR) {
                                console.log(data);
                            }
                        })
                    }
                } else {
                    location.reload();
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

    $('form#postCover').on('submit', function (event) {
        event.preventDefault();
        data = getFormData($(this));
        $('#error').html('');
        $.ajax({
            method: "POST",
            url: "/api/post",
            data: data,
            headers: {'Accept': 'application/json'},

            success: function (data, textStatus, jqXHR) {
                if (typeof cover_arr != 'undefined') {
                    var post_id = data.id;
                    console.log(cover_arr[i])
                    for (i = 0; i < cover_arr.length; i++) {
                        var image = cover_arr[i];
                        $.ajax({
                            method: "PUT",
                            url: "/api/image/",
                            data: {
                                "post_id": post_id,
                                "image_id": cover_arr[i]
                            },
                            headers: {'Accept': 'application/json'},
                            success: function (data, textStatus, jqXHR) {
                                $.ajax({
                                    method: "PUT",
                                    url: '/api/image/cover/' + image,
                                    headers: {'Accept': 'application/json'},
                                    success: function () {
                                        console.log("Update cover success")
                                        location.reload();
                                    },
                                    error: function (data, textStatus, jqXHR) {
                                        console.log("Update avatar fail")
                                    }
                                })
                            },
                            error: function (data, textStatus, jqXHR) {
                                console.log(data);
                            }
                        })
                    }
                } else {

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

    $('button.acceptFriendBtn').on('click', function () {
        var button = $(this);
        var userId = button.data("id");
        $.ajax({
            url: '/api/friend/accept/' + userId,
            type: 'PUT',
            headers: {'Accept': 'application/json'},
            success: function (data, textStatus, jqXHR) {
                button.parent().html(`<button  class="btn btn-secondary deleteBtn" data-id="${userId}" >Hủy kết bạn</button>`)
                loadFriendButton();
            },
            error: function (data, textStatus, jqXHR) {
                console.log(data)
            }
        });
    })

    $('button.addFriendBtn').on('click', function () {
        var button = $(this);
        var userId = button.data("id");
        $.ajax({
            url: '/api/friend/add',
            type: 'POST',
            data: {"user_id2": userId},
            headers: {'Accept': 'application/json'},
            success: function (data, textStatus, jqXHR) {
                button.parent().html(`<button  class="btn btn-secondary cancelFriendBtn" data-id="${userId}">Xóa lời mời</button>`)
                loadFriendButton();
            },
            error: function (data, textStatus, jqXHR) {
                console.log(data)
            }
        });
    })

    $('button.cancelFriendBtn').on('click', function () {
        var button = $(this);
        var userId = button.data("id");
        $.ajax({
            url: '/api/friend/delete/' + userId,
            type: 'DELETE',
            headers: {'Accept': 'application/json'},
            success: function (data, textStatus, jqXHR) {
                button.parent().html(`<button class="btn btn-primary addFriendBtn" data-id="${userId}">Thêm bạn bè</button>`)
                loadFriendButton();
            },
            error: function (data, textStatus, jqXHR) {
                console.log(data)
            }
        });
    })

    $('button.deleteBtn').on('click', function () {
        var button = $(this);
        var userId = button.data("id");
        $.ajax({
            url: '/api/friend/delete/' + userId,
            type: 'DELETE',
            headers: {'Accept': 'application/json'},
            success: function (data, textStatus, jqXHR) {
                button.parent().html(`<button class="btn btn-primary addFriendBtn" data-id="${userId}">Thêm bạn bè</button>`)
                loadFriendButton();
            },
            error: function (data, textStatus, jqXHR) {
                console.log(data)
            }
        })
    })

    $('.sent-message').click(function () {
        var username = $(this).data('username');
        window.location.href = '/message/' + username;
    });
})

function loadFriendButton() {
    $('button.addFriendBtn').on('click', function () {
        var button = $(this);
        var userId = button.data("id");
        $.ajax({
            url: '/api/friend/add',
            type: 'POST',
            data: {"user_id2": userId},
            headers: {'Accept': 'application/json'},
            success: function (data, textStatus, jqXHR) {
                button.parent().html(`<button  class="btn btn-secondary cancelFriendBtn" data-id="${userId}">Xóa lời mời</button>`)
                loadFriendButton();
            },
            error: function (data, textStatus, jqXHR) {
                console.log(data)
            }
        });
    })

    $('button.cancelFriendBtn').on('click', function () {
        var button = $(this);
        var userId = button.data("id");
        $.ajax({
            url: '/api/friend/delete/' + userId,
            type: 'DELETE',
            headers: {'Accept': 'application/json'},
            success: function (data, textStatus, jqXHR) {
                button.parent().html(`<button class="btn btn-primary addFriendBtn" data-id="${userId}">Thêm bạn bè</button>`)
                loadFriendButton();
            },
            error: function (data, textStatus, jqXHR) {
                console.log(data)
            }
        });
    })

    $('button.acceptFriendBtn').on('click', function () {
        var button = $(this);
        var userId = button.data("id");
        $.ajax({
            url: '/api/friend/accept/' + userId,
            type: 'PUT',
            headers: {'Accept': 'application/json'},
            success: function (data, textStatus, jqXHR) {
                button.parent().html(`<button  class="btn btn-secondary deleteBtn" data-id="${userId}">Hủy kết bạn</button>`)
                loadFriendButton();
            },
            error: function (data, textStatus, jqXHR) {
                console.log(data)
            }
        });
    })

    $('button.deleteBtn').on('click', function () {
        var button = $(this);
        var userId = button.data("id");
        $.ajax({
            url: '/api/friend/delete/' + userId,
            type: 'DELETE',
            headers: {'Accept': 'application/json'},
            success: function (data, textStatus, jqXHR) {
                button.parent().html(`<button class="btn btn-primary addFriendBtn" data-id="${userId}">Thêm bạn bè</button>`)
                loadFriendButton();
            },
            error: function (data, textStatus, jqXHR) {
                console.log(data)
            }
        })
    })

    $('button.deleteFriendBtn').on('click', function () {
        var button = $(this);
        var userId = button.data("id");
        $.ajax({
            url: '/api/friend/deleteFriend/' + userId,
            type: 'DELETE',
            headers: {'Accept': 'application/json'},
            success: function (data, textStatus, jqXHR) {
                $.ajax({
                    method: "DELETE",
                    url: "/api/friend/delete/" + userId,
                    headers: {'Accept': 'application/json'},
                    success: function (data, textStatus, jqXHR) {
                        button.parent().html(`<button class="btn btn-primary addFriendBtn" data-id="${userId}">Thêm bạn bè</button>`)
                        loadFriendButton();
                    }
                })
            },
            error: function (data, textStatus, jqXHR) {
                console.log(data)
            }
        })
    })

}

function getData(data) {
    $.ajax({
        method: "GET",
        url: "/api/post/user",
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
                console.log(postId);
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

function renderData(post) {
    html = `<div class="card mb-6" id="${post.id}" style="max-width: 100rem; margin-top:10px; border-radius: 5px";>
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
        html += `<div class="image-container" style="width: 100%; height: 600px; margin: 5px;"> <!-- Thiết lập kích thước và margin -->
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
