//declare varaables and arrays
var app = require('express')();
var http = require('http').Server(app);
var io = require('socket.io')(http);
var users = [];
var client_array = [];
var i = 0;
//on client connection
io.on('connection', function (socket) {
        //join chat(join socket_id chat by default as well)
        socket.on('join_chat', function (data) {
            if (get_socket_id(data.id)) {
                //check if user is in chat room already
                io.to(socket.id).emit('user_in_chat');
            }
            else {
                //joining chat room "chat"
                socket.join('chat');
                //push user onto array
                users.push({socket_id: socket.id, username: data.username, id: data.id});
                //add users array to json data
                data.users_array = users;
                //welcome user to chat room
                io.to('chat').emit('join_chat_success', data);
            }

        });
        //sending message to either the entire chat roo, or a specific user by socket_id
        socket.on('custom message', function (data) {
            if(data.id == -1){
                io.to('chat').emit('send custom', data);
            }
            else {
                io.to(get_socket_id(data.id)).emit('send custom', data);
                io.to(socket.id).emit('send custom', data);

            }
        });
        //user disconnects, grab username from socket_id, display message that user has left
        socket.on('disconnect', function () {
            var username = get_username(socket.id);
            io.to('chat').emit('user left', username);
            remove_user(socket.id);
        });
    
});
//listening on port 8081
http.listen(8081, function () {
    //console.log('listening on *:8081');
});
//function to remove user from chat
function remove_user(socket_id) {
    for (i = 0; i < users.length; i++) {
        if (user_exists(users[i]) && users[i].socket_id == socket_id) {
            users.splice(i, 1);
        }
    }
}
//check is user already exists in chat room
function user_exists(user) {
    if (user == undefined || user == null)
        return false;
    return true;
}
//get username by socket_id
function get_username(socket_id){
    for (i = 0; i < users.length; i++) {
        if (user_exists(users[i]))
        if (user_exists(users[i]) && users[i].socket_id == socket_id)
            return users[i].username;
    }

    return false;
}
//get users socket_id
function get_socket_id(user_id) {
    for (i = 0; i < users.length; i++) {
        if (user_exists(users[i]) && users[i].id == user_id)
            return users[i].socket_id;
    }

    return false;
}
