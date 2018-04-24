$(document).ready(function () {
    var webSocket = WS.connect(_WS_URI);

    webSocket.on("socket/connect", function(session){
        session.subscribe("admin/user-online", function(uri, payload){
            $('#users-online').text(payload.msg);
        });


        console.log("Successfully Connected!");
    });

    webSocket.on("socket/disconnect", function(error){
        //error provides us with some insight into the disconnection: error.reason and error.code

        console.log("Disconnected for " + error.reason + " with code " + error.code);
    })
});