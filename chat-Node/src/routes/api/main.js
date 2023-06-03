const express = require('express');
const { authentication } = require('../../middlewares/auth_socket_io');

const notificationController = require('../../controllers/socket/notification');
const ConsultantController = require('../../controllers/userChat/consultant');


const router = express.Router();

// router.use(authentication);
// router.use(express.json());
// router.get('/notifications/add', (res,req)=>{res.send("ddd")});



module.exports = router
