// const io = require('socket.io')(global.server, {cors: {origin: "*"}});
// const passport = require('express-laravel-passport')


// const locationController = require('../controllers/location');
// const { authentication } = require('../middlewares/auth_socket_io');
// const UserLiveLocation = require("../models/users_live_locations")

// const usersLiveLocations = []
// const usersLocations = []
// // server-side
// io.of('/live-locations').use(authentication);
// io.of('/live-locations').on('connection',async (socket) => {
//     console.log('connected');

//     // io.socket.join(request.user.id)

//     // Methods
//     locationController.add(socket)
//     locationController.get(socket)
// })

// io.of('/test').on('connection',async (socket) => {
//         console.log('connected');
//     socket.emit('tes',{test:"test"})
// })