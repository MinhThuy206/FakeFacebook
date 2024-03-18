const ws = new WebSocket(`ws://localhost:6001/app/e81326258299847689ba`)
ws.addEventListener('message', event => {
    var data = JSON.parse(event.data)
    var event_data = data.event
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
        onDataReceived(data);
    }
})

function renderData(message) {
    if (message.userFrom === userId) {
        html = `
         <div class="card-message sender" style="margin-top: 56px">
            <div class="message-text">${message.message}</div>
        </div>
    `
    } else {
        html = `
         <div class="card-message receiver" style="margin-top: 56px">
            <div class="message-text" >${message.message}</div>
        </div>
    `
    }

    return html;
}

function renderUser(user) {
    let html = ''
    if (user.avatar_url == null) {
        html += `
            <div class="user" data-id="${user.id}" data-username="${user.username}" data-name="${user.name}" data-avatar="${user.avatar_url}">
                <div class="avatar"><img src="../image/avatar-trang.jpg" alt="User Avatar"></div>
                <div class="name">${user.name}</div>
            </div>
    `
    } else {
        html += `
            <div class="user" data-id="${user.id}" data-username="${user.username}" data-name="${user.name}" data-avatar="${user.avatar_url}">
                <div class="avatar"><img src="../${user.avatar_url}" alt="User Avatar"></div>
                <div class="name">${user.name}</div>
            </div>
    `
    }

    return html;
}

function getData(data) {
    $.ajax({
        method: "GET",
        url: "/api/message/filterUserMessage/",
        headers: {'Accept': 'application/json'},

        success: function (data, textStatus, jqXHR) {
            var listUser = '';
            data.forEach(function (item) {
                listUser += renderUser(item)
            }, this)

            $('#chat-user').html(listUser);

            $.ajax({
                method: "GET",
                url: "/api/message/filterMessage/" + userTo,
                headers: {'Accept': 'application/json'},
                success: function (data) {
                    var message = data.data;
                    var htmlContent = '';
                    message.forEach(function (item) {
                        htmlContent += renderData(item)
                    }, this)

                    $('#message').html(htmlContent); // Hiển thị tin nhắn của người dùng

                    // Thay đổi đường dẫn URL theo user_id
                    history.pushState(null, '', '/message/' + userName);
                    scrollToBottom();
                },
                error: function (data, textStatus, jqXHR) {
                    console.log(data)
                }
            })
            attachUserClickEvent();
        }
    })
}

$('form#messageForm').on('submit', function (event) {
    event.preventDefault();
    data = getFormData($(this));
    $.ajax({
        method: "POST",
        url: "/api/message/sent",
        data: {
            "message": data.message,
            "userTo": userTo
        },
        headers: {'Accept': 'application/json'},
        success: function (data, textStatus, jqXHR) {
            getData(data)
            $('form#messageForm').trigger("reset");
            scrollToBottom();
            console.log("Upload message success")
        },
        error: function (data, textStatus, jqXHR) {
            console.log(data)
        }
    })
})

function attachUserClickEvent() {
    $(document).on('click', '.user', function () {
        let userId = $(this).data('id');
        let userName = $(this).data('username');
        let name = $(this).data('name');
        let avatarUrl = $(this).data('avatar');
        let avt = '';
        if (avatarUrl == null) {
            avt = "../image/avatar-trang.jpg"
        } else {
            avt = "../" + avatarUrl;
        }
        userTo = $(this).data('id');

        $('.userchatname').text(name);
        $('#avatar-img').attr('src', avt)

        $.ajax({
            method: "GET",
            url: "/api/message/filterMessage/" + userId,
            headers: {'Accept': 'application/json'},
            success: function (data) {
                var message = data.data;
                var htmlContent = '';
                message.forEach(function (item) {
                    htmlContent += renderData(item)
                }, this)

                // Thay đổi đường dẫn URL theo user_id
                history.pushState(null, '', '/message/' + userName);
                $('#message').html(htmlContent); // Hiển thị tin nhắn của người dùng
                scrollToBottom()


            },
            error: function (data, textStatus, jqXHR) {
                console.log(data)
            }
        })
    });
}

// Hàm này sẽ nhóm tin nhắn theo ngày
function groupMessagesByDate(messages) {
    var groupedMessages = {};
    messages.forEach(function (message) {
        var date = new Date(message.created_at).toLocaleDateString();
        if (!groupedMessages[date]) {
            groupedMessages[date] = [];
        }
        groupedMessages[date].push(message);
    });
    return groupedMessages;
}

// Hàm này sẽ hiển thị tin nhắn và ngày tương ứng
function displayMessages(groupedMessages) {
    var messageBody = document.getElementById("messageBody");
    messageBody.innerHTML = ''; // Xóa tin nhắn hiện tại trước khi hiển thị tin nhắn mới
    Object.keys(groupedMessages).forEach(function (date) {
        var messages = groupedMessages[date];
        var messageDateElement = document.createElement('div');
        messageDateElement.textContent = date;
        messageDateElement.classList.add('message-date');
        messageBody.appendChild(messageDateElement);
        messages.forEach(function (message) {
            var messageElement = document.createElement('div');
            messageElement.textContent = message.content;
            messageElement.classList.add('message-text');
            messageBody.appendChild(messageElement);
        });
    });
}

// Hàm này sẽ được gọi khi nhận được dữ liệu tin nhắn mới từ máy chủ
function onDataReceived(data) {
    var groupedMessages = groupMessagesByDate(data.messages);
    displayMessages(groupedMessages);
    scrollToBottom(); // Cuộn xuống dưới sau khi hiển thị tin nhắn mới
}






