function cardUser(user) {

    let html = '';
    let profileUrl = profileUrlBase.replace(':username', user.username);

    html += `
        <div class="col-lg-3 col-md-4 col-sm-6 mb-3"> <!-- Thay đổi số lượng cột tại đây -->
            <div class="card" id="${user.id}" style="height: 350px; max-width: 240px" >`

    if (user.avatar_url == null) {
        html += `<div class="card-image">
                    <img style="max-width: 100%;height: 206px;object-fit: cover;" src="../image/avatar-trang.jpg" class="card-img-top" alt="">
                `
    } else {
        html += `<a href="${profileUrl}">
                    <div class="card-image">
                        <img style="max-width: 100%;height: 206px;object-fit: cover;" src="../${user.avatar_url}" class="card-img-top" alt="">
                    </a>
    `;
    }

    html += `</div>
        <div class="card-body">
        <a href="${profileUrl}" style="color: #000; text-decoration: none;">
            <h6 class="card-title">${user.name}</h6>
             <style>
             .card-title:hover {
                 text-decoration: underline;
             }
    </style>
        </a>
            <p class="card-text">${user.friends} friend</p>
            <div class="d-flex justify-content-between align-items-center">`

    if (user.status === 'Accepted') {
        html += `<button  class="btn btn-danger deleteFriendBtn"  data-id="${user.id}">Delete Friend</button>`
    } else if (user.status === 'null') {
        html += `<button class="btn btn-primary addFriendBtn" data-id="${user.id}" >Add Friend</button>`
    } else if (user.status === 'Pending') {
        html += `<button class="btn btn-success acceptFriendBtn" data-id="${user.id}">Accept</button>
                <button  class="btn btn-secondary deleteBtn" data-id="${user.id}">Delete</button>`
    } else {
        html += `<button  class="btn btn-secondary cancelFriendBtn" data-id="${user.id}">Cancel</button>`
    }

    html += `    </div>
              </div>
         </div>
      </div>`;

    return html;
}

function getDataUser(data) {
    $.ajax({
        method: "GET",
        url: "/api/friend/user",
        data: data,
        headers: {'Accept': 'application/json'},
        success: function (data, textStatus, jqXHR) {
            var users = data.data;
            var htmlContent = '';
            users.forEach(function (user, index) {
                // Nếu là cột đầu tiên hoặc là cột thứ tư, thêm một hàng mới
                if (index % 4 === 0) {
                    htmlContent += `<div class="row">`;
                }
                htmlContent += cardUser(user);
                // Nếu là cột thứ tư hoặc là cột cuối cùng, đóng hàng
                if ((index + 1) % 4 === 0 || index === users.length - 1) {
                    htmlContent += `</div>`;
                }
            });

            $('#add-friend-list').html(htmlContent);

            $('button.addFriendBtn').on('click', function () {
                var button = $(this);
                var userId = button.data("id");
                $.ajax({
                    url: '/api/friend/add',
                    type: 'POST',
                    data: {"user_id2": userId},
                    headers: {'Accept': 'application/json'},
                    success: function (data, textStatus, jqXHR) {
                        button.parent().html(`<button  class="btn btn-secondary cancelFriendBtn" data-id="${userId}">Cancel</button>`)
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
                        button.parent().html(`<button class="btn btn-primary addFriendBtn" data-id="${userId}">Add Friend</button>`)
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
                        button.parent().html(`<button  class="btn btn-secondary deleteBtn" data-id="${userId}">Delete</button>`)
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
                        button.parent().html(`<button class="btn btn-primary addFriendBtn" data-id="${userId}">Add Friend</button>`)
                        loadFriendButton();
                    },
                    error: function (data, textStatus, jqXHR) {
                        console.log(data)
                    }
                })
            })
        },
        error: function (data, textStatus, jqXHR) {
            console.log(data)
        }
    })
}

function getFriend(data) {
    $.ajax({
        method: "GET",
        url: "/api/friend/friend",
        data: data,
        headers: {'Accept': 'application/json'},
        success: function (data, textStatus, jqXHR) {
            var user = data.data;
            var htmlContent = '';
            user.forEach(function (item) {
                htmlContent += cardUser(item);
            }, this)

            // $('#list-friend').html(htmlContent);

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
                                button.parent().html(`<button class="btn btn-primary addFriendBtn" data-id="${userId}">Add Friend</button>`)
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
    })
}

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
                button.parent().html(`<button  class="btn btn-secondary cancelFriendBtn" data-id="${userId}">Cancel</button>`)
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
                button.parent().html(`<button class="btn btn-primary addFriendBtn" data-id="${userId}">Add Friend</button>`)
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
                button.parent().html(`<button  class="btn btn-secondary deleteBtn" data-id="${user.id}">Delete</button>`)
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
                button.parent().html(`<button class="btn btn-primary addFriendBtn" data-id="${userId}">Add Friend</button>`)
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
                        button.parent().html(`<button class="btn btn-primary addFriendBtn" data-id="${userId}">Add Friend</button>`)
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



