<?php

$this->pageTitle=Yii::app()->name;
?>

<style>
    form {  padding: 3px; width: 60%; }

    form button { width: 9%; background: rgb(130, 224, 255); border: none; padding: 10px; }
    #messages { list-style-type: none; margin: 0; padding: 0; }
    #messages li { padding: 5px 10px; }
    #messages li:nth-child(odd) { background: #eee; }
    #m{
        height: 20px;
        margin-right: 10px;;
    }
</style>
<script>
    //connect to this socket on port 8081
    var socket = io.connect('bbrusda.cloudapp.net:8081');
    $( document ).ready(function() {
        //join chat room, emit data to the server side
        socket.emit('join_chat', {
            username: "<?php echo ucfirst(Yii::app()->user->getState('username'));  ?>",
            id: "<?php echo Yii::app()->user->id; ?>"
        });
        //on success, build radio button list
        socket.on('join_chat_success',function(data){
            //enable send button in chat
            $('#send-btn').attr('disabled', false);
            var i;
            $('#user_list').html('');
            $('#user_list').append('<input type="radio" id="all" name="users_list" value="-1" checked="checked">Send All<br>');
            for(i=0; i < data.users_array.length; i++){
                if(data.users_array[i] != null)
                    if("<?php echo Yii::app()->user->id; ?>" != data.users_array[i].id)
                        $('#user_list').append('<input type="radio" id="user-'+data.users_array[i].id+'" name="users_list" value='+data.users_array[i].id +'>'+data.users_array[i].username+'</br>');

            }
            '</form>'
            //welcome the user to the chat
            if("<?php echo ucfirst(Yii::app()->user->getState('username')); ?>" == data.username){
                $('#messages').append('<li><strong>'+ "Welcome! "+ data.username +' </strong>');
            }
            else {
                //inform other players that the user has joined
                $('#messages').append('<li><strong>' + data.username + ' </strong> has joined the game');
            }
        });
        //display error message when more than three players are in chat room
        socket.on('join_chat_fail',function(){
            $('#messages').append("<li>Sorry room is full</li>");
        });
        //display error if user is already in chat
        socket.on('user_in_chat',function(){
            $('#messages').append("<li>User already exists</li>");
        });
        //emit the custom message to the server, along with which radio button is checked
        $('form').submit(function(){
            socket.emit('custom message', {msg: $('#m').val(), id: $('input[name=users_list]:checked', '#main').val(), username: '<?php echo ucfirst(Yii::app()->user->getState('username')); ?>'});
            $('#m').val('');
            return false;
        });
        //emit the message to the client, either the entire room or specific user
        socket.on('send custom', function(data) {
            if("<?php echo ucfirst(Yii::app()->user->getState('username')); ?>" == data.username){
                $('#messages').append('<li><strong>'+ "Me:" +' </strong>'+data.msg);
            }
            else {
                $('#messages').append('<li><strong>' + data.username + ': </strong>' + data.msg);
            }
        });
        //alert other players that a user has left
        socket.on('user left', function(username){
            $('#messages').append('<li><strong>' + username + ' </strong> has left the game');
        });

    });
</script>
<body>
<ul id="messages"></ul>
<form id="main" action="">
    <input id="m" autocomplete="off" /><button id="send-btn" disabled="disabled">Send</button></br></br>
<h1>Send Direct Messages Below</h1>
<ul id="user_list"></ul>
</body>
