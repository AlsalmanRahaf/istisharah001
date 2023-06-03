const socketio = require('socket.io');
const io = socketio(global.server, {cors: {origin: "*"},maxHttpBufferSize: 2e7})
module.exports = io