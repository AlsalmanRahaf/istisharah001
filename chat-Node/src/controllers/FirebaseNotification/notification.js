
const notification=require('../../models/notification');
const admin = require("./serviceAccount");
const helper = require('./../../utiles/helper');
const {DataTypes} = require("../../utiles/database");

exports.add =  async (user_id,token,message,topic) => {
    console.log("dddd"+topic);
    let notifcation = await helper.getNotification("en");
    if(notifcation){
        const notification_options = {
            priority: "high",
            timeToLive: 60 * 60 * 24
        };

        if(notifcation[0][message] != null){

            if(topic){
                console.log("topic");
                var messageData = {
                    notification: notifcation[0][message],
                };
                console.log(messageData);
                admin.messaging().sendToTopic(topic,messageData)
                    .then(responseRR => {
                        console.log("Notification sent successfully topic" + responseRR);
                    })
                    .catch(error2 => {
                        console.log("error topic");
                        console.log(error2);
                    })
            }else{
                console.log("not topic");
                console.log("============================notification added  ===========================================");
                if(user_id){
                    await notification.create({
                        title:notifcation[0][message].title,
                        body:notifcation[0][message].body,
                        type:1,
                        status:1,
                        user_id:user_id
                    })
                }
                var messageData = {
                    notification: notifcation[0][message]
                };
                console.log(messageData);
                admin.messaging().sendToDevice(token, messageData, notification_options)
                    .then(responseRR => {
                        console.log("Notification sent successfully" + responseRR);
                    })
                    .catch(error2 => {
                        console.log(error2);
                    })
            }

        }
    }
}

