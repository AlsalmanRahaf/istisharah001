const io = require("../../utiles/socket")

const notificationNamespace = io.of('/socket/notification')
const adminNotificationNamespace = io.of('/admin/socket/notification')


exports.add =  (request, response) => {
    let notification = {title: request.body.title, body: request.body.body}
    let key;
    if(request.body.type === "admin"){
        notification["url"] = request.body.url
        adminNotificationNamespace.emit('get-notification', notification)
    }else{
        notificationNamespace.to(request.body.userId).emit('get-notification', notification)
    }
    
    response.send({status: 'success'})
}