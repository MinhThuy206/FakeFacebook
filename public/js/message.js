const ws = new WebSocket(`ws://localhost:6001/app/e81326258299847689ba`)
ws.addEventListener('message', event => {
    var data = JSON.parse(event.data)
    if (data.data)
        data = JSON.parse(data.data)
    if (data.socket_id) {
        $.ajax({
            method: "POST",
            url: "/api/broadcast",
            data: {
                "socket_id": data.socket_id
            },
            headers: {'Accept': 'application/json'},

            success: function (data) {
                ws.send(JSON.stringify({
                    "event": "pusher:subscribe",
                    "data": {
                        "auth": data.auth,
                        "channel": "chat." + userId
                    }
                }))
            }
        })
    }
    if (data.message) {
        $('#message').append(renderData(data))
        scrollToBottom();
    }
})

document.addEventListener('DOMContentLoaded', function () {
    var createGroup = document.querySelector('.create-group');
    var editModal = document.getElementById('editModal');
    var closeModalButton = document.querySelector('.close');
    var overlay = document.getElementById('overlay');

    createGroup.addEventListener('click', function () {
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


function renderData(message) {
    if (message.userFrom.id === userId) {
        html = `
         <div class="card-message sender" style="margin-top: 56px">
            <div class="message-text">${message.message}</div>
        </div>
    `
    } else {
        html = `
         <div class="card-message receiver" style="margin-top: 56px">
    <div class="profile-picture">
        <img src="../${message.userFrom.avatar_url}" alt="">
    </div>
    <div class="message-text">${message.message}</div>
    <div class="username-container">
        <span class="name-receiver">${message.userFrom.name}</span>
    </div>
</div>

    `
    }

    return html;
}

function renderConservation(conservation) {
    return `
            <div class="user conservation" data-id="${conservation.id}" data-name="${conservation.name}" data-avatarurl="${conservation.avatar_url}">
                <div class="avatar"><img src="../${conservation.avatar_url}" alt="Avatar"></div>
                <div class="name">${conservation.name}</div>
                <div class="lastMessage">

                </div>
            </div>
    `;
}

function cardUser(user){
    if(user.avatar_url == null){
        user.avatar_url =  "image/avatar-trang.jpg";
    }
    return `
            <div class="user select" data-id="${user.id}" data-name="${user.name}" data-avatarurl="${user.avatar_url}">
                <input type="checkbox" class="user-checkbox" data-id="${user.id}" data-name="${user.name}" data-avatarurl="${user.avatar_url}">
                <div class="avatar"><img src="../${user.avatar_url}" alt="Avatar"></div>
                <div class="name">${user.name}</div>

            </div>
    `;
}

function getData(data){
    $.ajax({
        method: "GET",
        url: "/api/message/filterConservations/",
        headers: {'Accept': 'application/json'},

        success: function (data, textStatus, jqXHR) {
            var listConservations = '';
            data.data.forEach(function (item) {
                console.log(item);
                listConservations += renderConservation(item)
            }, this)

            $('#chat-user').html(listConservations);

            $.ajax({
                method: "GET",
                url: "/api/message/getMessage/" + consId,
                headers: {'Accept': 'application/json'},
                success: function (data) {
                    var message = data.data;
                    var messageBox = $('#message');
                    message.forEach(function (item) {
                        messageBox.prepend(renderData(item))
                    }, this)

                    // Thay đổi đường dẫn URL theo user_id
                    history.pushState(null, '', '/message/' + consId);
                    scrollToBottom();
                    attachUserClickEvent();
                },
                error: function (data, textStatus, jqXHR) {
                    console.log(data)
                }
            })
        }
    })

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
            users.forEach(function (user) {
                htmlContent += cardUser(user);
            });
            $('#listUser').html(htmlContent);
        }
    })
}

$('#createGroupForm').submit(function(event) {
    event.preventDefault();

    var groupName = $('#groupName').val();

    var userIds = [];

    $('.user-checkbox:checked').each(function() {
        userIds.push($(this).data('id'));
    });

    userIds.push(userId)

    $.ajax({
        method: "POST",
        url: "/api/message/storeGroup",
        data: {
            "name": groupName,
            "users": userIds
        },
        headers: {'Accept': 'application/json'},
        success: function (data) {
            location.reload();
        },
        error: function (jqXHR, data) {
            console.error(data);
        }
    });
});


$('form#messageForm').on('submit', function (event) {
    event.preventDefault();
    data = getFormData($(this));
    data.cons_id = consId;
    $.ajax({
        method: "POST",
        url: "/api/message/sent",
        data: data,
        headers: {'Accept': 'application/json'},
        success: function (data, textStatus, jqXHR) {
            $('#message').append(renderData(data));
            scrollToBottom();
            console.log("Upload message success")
        },
        error: function (data, textStatus, jqXHR) {
            console.log(data)
        }
    })
    $("#messageInput").val("");
})

function attachUserClickEvent() {
    $(document).on('click', '.user.conservation', function () {
        let consIdnew = $(this).data('id');
        let consName = $(this).data('name');
        let avt = $(this).data('avatarurl');

        let avtUrl = "../" + avt

        $('.chatname').text(consName);
        $('#avatar-img').attr('src', avtUrl)
        consId = consIdnew;

        $.ajax({
            method: "GET",
            url: "/api/message/getMessage/" + consIdnew,
            headers: {'Accept': 'application/json'},
            success: function (data) {
                var message = data.data;
                var htmlContent = '';
                message.sort((a, b) => (a.created_at > b.created_at) ? -1 : 1);
                // Hiển thị tin nhắn từ dưới lên
                message.forEach(function (item) {
                    htmlContent = renderData(item) + htmlContent;
                }, this)

                // Thay đổi đường dẫn URL theo user_id
                history.pushState(null, '', '/message/' + consIdnew);
                $('#message').html(htmlContent); // Hiển thị tin nhắn của người dùng
                scrollToBottom();
            },
            error: function (data) {
                console.log(data)
            }
        })
    });
}


