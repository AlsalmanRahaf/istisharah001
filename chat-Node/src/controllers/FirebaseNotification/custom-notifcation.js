const admin = require("./serviceAccount");
const notification=require('../../models/notification');
const helper = require('./../../utiles/helper');

exports.add =  async (token,user_id,message,type) => {
    const notification_options = {
        priority: "high",
        timeToLive: 60 * 60 * 24
    };

    const messageData = {
        notification: {
            'body': message,
            'title': "You have new message",
            'icon': "https://dashboard.healthcare.digisolapps.com/assets/logo2.png"
        },
        "data": {
            "user_id":String(user_id),
            "type":String(type),
            "message":message
        },
    };
       await admin.messaging().sendToDevice(token, messageData, notification_options)
            .then(responseRR => {
                console.log("Notification sent successfully" + responseRR);
            })
            .catch(error2 => {
                console.log(error2);
            })


}