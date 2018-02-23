var server = require('http').Server();

var io = require('socket.io')(server);

var Redis = require('ioredis');

var redis = new Redis();

redis.subscribe('trading-tickers');

redis.on('message', function (channel, message) {


    console.log('done');

    // message = JSON.parse(message);
    //
    // io.emit(channel + ':' + message.event, message.data);

});

server.listen(3000, function () {
    console.log('listening on *:3000');
});