var avatar_arr;
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

function xulyfile() {
    avatar_arr = [];
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
                avatar_arr.push(id);
            },
        })
    }
}

$(document).ready(function () {
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
                                    success: function () {
                                        console.log("Update avatar success")
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
})

