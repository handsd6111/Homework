var wsUri = "ws://172.16.0.7:9000";
websocket = new WebSocket(wsUri);

$(document).ready(() => {
    Display_Full_Introduction();
    websocket.onopen = function (ev) { // connection is open 
        websocket.send(localStorage.getItem("JWT"));
        msgBox.append('<div class="system_msg" style="color:#bbbbbb">Welcome to my "Demo WebSocket Chat box"!</div>'); //notify user
        //websocket.send("jtgawoegjpoawewoweoriwerio");
    }

    // Message received from server
    websocket.onmessage = function (ev) {
        var response = JSON.parse(ev.data); //PHP sends Json data
        console.log(response);
        // response

        var res_type = response.type; //message type
        var user_message = response.message; //message text
        var user_name = response.name; //user name
        var user_color = response.color; //color

        switch (res_type) {
            case 'usermsg':
                msgBox.append('<div><span class="user_name" style="color:' + user_color + '">' + user_name + '</span> : <span class="user_message">' + user_message + '</span></div>');
                break;
            case 'system':
                msgBox.append('<div style="color:#bbbbbb">' + user_message + '</div>');
                break;
        }
        msgBox[0].scrollTop = msgBox[0].scrollHeight; //scroll message 

    };

    websocket.onerror = function (ev) { msgBox.append('<div class="system_error">Error Occurred - ' + ev.data + '</div>'); };
    websocket.onclose = function (ev) { msgBox.append('<div class="system_msg">Connection Closed</div>'); };

    //Message send button
    $('.user-input').click(function () {
        send_message();
    });

    $("#message").on("keydown", function (event) {
        if (event.which == 13) {
            send_message();
        }
    });
});




function Display_Full_Introduction() {
    var intro = $('.introduction');
    for (var i = 0; i < intro.length; i++) {
        intro[i].addEventListener('mouseover', (e) => {
            var full = $('.full-introduction');
            full.css("top", e.pageY);
            full.css("left", e.pageX);
            full.text(e.target.innerText);
            setTimeout(() => {
                $('.full-introduction').css("display", "block");
            }, 300);
        });
        intro[i].addEventListener('mouseout', (e) => {
            //console.log(e.target.innerText);
            setTimeout(() => {
                $('.full-introduction').css("display", "none");
            }, 300);
        });
    }
}

function test() {

    var data = $('.user-input').val();
    if (data != "" && data != null) {
        $('.message-zone').append('<div class="message-item">' +
            '<img class="avatar" src="../../Img/Avatar/1642732922.jpeg" alt="" class="avatar">' +
            '<div class="text">' +
            '<div class="info">' +
            '<div class="name">test</div>' +
            '<div class="time">2017/08/21 16:14:21</div>' +
            '</div>' +
            '<p class="message">' + data + '</p>' +
            '</div>' +
            '</div>');
        $('.user-input').val("");
    }
}

