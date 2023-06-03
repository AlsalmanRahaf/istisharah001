const io = require('../../utiles/socket')
const { authentication } = require('../../middlewares/auth_socket_io');
const userSocket = require('../../controllers/userChat/userSocket');
const User = require('../../models/user');

const blockUserController = require('../../controllers/socket/userblock');

const userChatController = require('../../controllers/userChat/userSocket')
const consultantChatController = require('../../controllers/userChat/consultant')
const FirebaseController = require('../../controllers/FirebaseNotification/notification');

const customConsultation = require('../../controllers/userChat/customConsultation');
const message = require('../../controllers/socket/message');
const consultants = require('../../controllers/userChat/consultant');
const userNamespace = io.of('/userChat/userSocket')
const chatNamespace = io.of('/chat/socketChat')
const consultantNamespace = io.of('/userChat/SpecializedConsultant')
const CustomConsultationNamespace  = io.of('/userChat/CustomConsultation')
const notificationNamespace = io.of('/socket/notification')
const adminNotificationNamespace = io.of('/admin/socket/notification')

const helper = require('./../../utiles/helper');
const Console = require("console");


chatNamespace.use(authentication);
consultantNamespace.use(authentication);
userNamespace.use(authentication);
CustomConsultationNamespace.use(authentication);

consultantNamespace.on('connect',async (socket) => {
    socket.join(socket.request.user.id)
    console.log("consultantNamespace");
    consultantChatController.consultant(socket);
    // socket.join(socket.request.user.id)
});

chatNamespace.on('connect',async (socket) => {
    console.log(socket.handshake.headers)
    // userChatController.send(socket,'1rupr528l8ytqjzn3qma0c');
    //
    userChatController.send(socket);
})

userNamespace.on('connect', async (socket) => {
    socket.join(socket.request.user.id)
    console.log(socket.request.user.id)
    message.getUnReadConsultation(socket);
    userChatController.send(socket);
    consultants.consultant(socket);

});

userNamespace.on('disconnect', function() {
    console.log('Got disconnect!');
    console.log("disconnect TTTTTTTTTTTLLLLLLLLLLLLLLLLLLL user id : "+socket.request.user.id);
});

io.on('disconnect', function() {
    console.log('Got disconnect!');
    console.log("disconnect TTTTTTTTTTTLLLLLLLLLLLLLLLLLLL user id : "+socket.request.user.id);
});

CustomConsultationNamespace.on('connect',async (socket) => {
    socket.join(socket.request.user.id)
    await  customConsultation.CustomConsultant(socket);
    console.log("JOIN TO CustomConsultation"+socket.request.user.id);
})

notificationNamespace.on('connect',async (socket) => {
    socket.join(socket.request.user.id)
    console.log("JOIN TO NOTEFI"+socket.request.user.id);
})

adminNotificationNamespace.on('connect',async (socket) => {
    // let notifcation = await helper.getNotification("en");
    // await helper.update_consultation_notification("new_consultaion",{title:"New Consultation1",body:"You Have New Consultation1"},"en");
})