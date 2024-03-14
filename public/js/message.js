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
            <div class="user">
                <div class="avatar"><img src="../image/avatar-trang.jpg" alt="User Avatar"></div>
                <div class="username">${user.name}</div>
            </div>
    `
    } else {
        html += `
            <div class="user">
                <div class="avatar"><img src="../${user.avatar_url}" alt="User Avatar"></div>
                <div class="username">${user.name}</div>
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
                data: data,
                headers: {'Accept': 'application/json'},

                success: function (data) {
                    var message = data.data;
                    var htmlContent = '';
                    message.forEach(function (item) {
                        htmlContent += renderData(item)
                    }, this)

                    $('#message').html(htmlContent);
                },
                error: function (data, textStatus, jqXHR) {
                    console.log(data)
                }
            })
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
            console.log("Upload message success")
        },
        error: function (data, textStatus, jqXHR) {
            console.log(data)
        }
    })
})





