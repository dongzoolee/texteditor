var express = require('express');
var fs = require('fs');
var app = express();
var https = require('https');
try {
    const option = {
        cert: fs.readFileSync('/home/ubuntu/docker/etc/phpmyadmin/phpmyadmin/ssl/fullchain.pem'),
        key: fs.readFileSync('/home/ubuntu/docker/etc/phpmyadmin/phpmyadmin/ssl/privkey.pem')
    }
    var server = https.createServer(option, app)
        .listen(3334, () => {
            console.log('https server at 3334 started');
        });
} catch (err) {
    console.error('error running https server');
    console.warn(err);
}

var socketio = require('socket.io');
var io = socketio();
io.attach(server);
var last_msg="";
var arr={};
io.on('connection', (socket)=>{
    console.log('new connection from '+socket.id);
    io.to(socket.id).emit('welcome', last_msg);
    io.to(socket.id).emit('caret welcome', {
        'arr':arr,
        'id':socket.id
    });

    socket.on('disconnect', ()=>{
        console.log('id : '+socket.id+' gone');
        delete arr[socket.id];
        socket.broadcast.emit('disconn', socket.id);
    })

    socket.on('code',(msg)=>{
        socket.broadcast.emit('code edit', msg);
        last_msg = msg;
        //io.emit('code edit', msg);
    })
    socket.on('outerHtml', (msg)=>{
        socket.broadcast.emit('outerHtml', msg);
    })
    socket.on('pos', (data)=>{
        if(arr[socket.id]){
            console.log('old arr');
            arr[socket.id][0]=data.left;
            arr[socket.id][1]=data.top;
            data.id=socket.id;
            socket.broadcast.emit('cng pos', data);
        }else{
            console.log('new arr');
            arr[socket.id]=[data.left, data.top];
            data.id=socket.id;
            socket.broadcast.emit('new pos', data);
        }
    })
})