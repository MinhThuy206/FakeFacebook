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
        data.message.userFrom = JSON.parse(data.message.userFrom)
        $('#message').append(renderData(data))
        onDataReceived(data);
    }
})

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
            <div class="message-text" >${message.message}</div>
        </div>
    `
    }

    return html;
}

function renderConservation(conservation) {
    return `
            <div class="user" data-id="${conservation.id}" data-name="${conservation.name}" data-avatarUrl="${conservation.avatar_url}">
                <div class="avatar"><img src="${conservation.avatar_url}" alt="Avatar"></div>
                <div class="name">${conservation.name}</div>
                <div class="lastMessage">

                </div>
            </div>
    `;
}

function getData(data) {
    $.ajax({
        method: "GET",
        url: "/api/message/filterConservations/",
        headers: {'Accept': 'application/json'},

        success: function (data, textStatus, jqXHR) {
            var listConservations = '';
            data.data.forEach(function (item) {
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
    data.cons_id = consId
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
    $(document).on('click', '.user', function () {
        let consId = $(this).data('id');
        let consName = $(this).data('name');
        let avt = $(this).data('avatarUrl');

        $('.chatname').text(consName);
        $('#avatar-img').attr('src', avt)

        $.ajax({
            method: "GET",
            url: "/api/message/getMessage/" + consId,
            headers: {'Accept': 'application/json'},
            success: function (data) {
                var message = data.data;
                var htmlContent = '';
                message.forEach(function (item) {
                    htmlContent += renderData(item)
                }, this)

                // Thay đổi đường dẫn URL theo user_id
                history.pushState(null, '', '/message/' + consId);
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

document.addEventListener('DOMContentLoaded', function () {
    // Thêm sự kiện cho nút chỉnh sửa trang cá nhân
    var editProfileButton = document.querySelector('.conservations');
    var editModal = document.getElementById('editModal');
    var closeModalButton = document.querySelector('.close');
    var overlay = document.getElementById('overlay');

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
