function cardUser(user) {
    html = `
    <div class="card" id="${user.id}" style="max-width: 100rem; margin: auto">
        <div class="card">
             <div class="row no-gutters">
                 <div class="col-md-4 order-md-1">
                    <img src="" class="card-img " style="max-width: 100%;max-height: 150px;" alt="Card Image">
                 </div>
                <div class="col-md-8 order-md-2">
                    <div class="card-body">
                         <h5 class="card-title">${user.name}</h5>
                         <p class="card-text">${user.friends} friend</p>
                          <div class="d-flex justify-content-between align-items-center">`

    if (user.status == 'Accepted') {
        html += `<button  class="btn btn-danger deleteFriendBtn"  data-id="${user.id}">Delete Friend</button>`
    } else if (user.status == 'null') {
        html += `<button class="btn btn-primary addFriendBtn" data-id="${user.id}" >Add Friend</button>`
    } else if (user.status == 'Pending') {
        html += `<button class="btn btn-success acceptFriendBtn" data-id="${user.id}">Accept</button>
                <button  class="btn btn-secondary deleteBtn" data-id="${user.id}">Delete</button>`
    } else {
        html += `<button  class="btn btn-secondary cancelFriendBtn" data-id="${user.id}">Cancel</button>`
    }
    html += `            </div>
                    </div>
                </div>
             </div>
        </div>
    </div>`

    return html;
}

function getDataUser(data) {
    $.ajax({
        method: "GET",
        url: "/api/friend/user",
        data: data,
        headers: {'Accept': 'application/json'},
        success: function (data, textStatus, jqXHR) {
            var user = data.data;
            var htmlContent = '';
            user.forEach(function (item) {
                htmlContent += cardUser(item);
            }, this)

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
                        location.reload();
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
                        location.reload();
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
                        location.reload();
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
                        location.reload();
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

            $('#list-friend').html(htmlContent);

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
                                location.reload();
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
