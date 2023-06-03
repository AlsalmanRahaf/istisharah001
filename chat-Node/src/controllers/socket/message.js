const io = require("../../utiles/socket")
const userChatNamespace = io.of('/userChat/userSocket')
const CustomConsultationNamespace  = io.of('/userChat/CustomConsultation')
const SpecializedConsultantNamespace = io.of('/userChat/SpecializedConsultant')

exports.message_seen =  (request, response) => {

       let user_id =parseInt(request.body.user_id);
        let message_id=request.body.message_id;
        userChatNamespace.to(user_id).emit('seen', {message_id: message_id});
    response.send({status: 'success'})
}

exports.change_user_to_other_consultant =  (request, response) => {
    let user_id =parseInt(request.body.user_id);
    let type =request.body.type;
    userChatNamespace.to(user_id).emit('new_other_consultant', {type: type});
    response.send({status: 'success'});
}


exports.getUnReadConsultation =  (socket) => {
    socket.on("data", (socket)=>{
        // console.log(socket.type)
        // console.log(socket.status)
        // let status = parseInt(socket.status);
        switch (socket.type){
            case "c":
                userChatNamespace.emit('getUnReadConsultation', {type: socket.type,status:1});
                break;
            case "sp":
                SpecializedConsultantNamespace.emit('getUnReadConsultation', {type: socket.type,status:1});
                break;
            case "cs":
                CustomConsultationNamespace.emit('getUnReadConsultation', {type: socket.type,status:1});
                break;
        }
    });
}

exports.unread_Admin_Consultant_chat =  (request, response) => {


    let consultant_id =parseInt(request.body.consultant_id);
    let admin_id =parseInt(request.body.admin_id);
    let status =parseInt(request.body.status);
    let type =request.body.type;
    if(type === "c"){
        userChatNamespace.to(admin_id).emit('Unread_Admin_Consultant_Chat', {user_id: consultant_id,status:status});
    }else{
        userChatNamespace.to(consultant_id).emit('Unread_Admin_Consultant_Chat', {user_id: admin_id,status:status});
    }

    response.send({status: 'success'});
}
exports.unread_Request_Consultant_chat =  (request, response) => {
    let user_id =parseInt(request.body.user_id);
    let admin_id =parseInt(request.body.admin_id);
    let type =request.body.type;
    let status =parseInt(request.body.status);
    if(type === "u"){
        userChatNamespace.to(admin_id).emit('Unread_Request_Consultant_Chat', {user_id: user_id,status,status});
    }else{
        userChatNamespace.to(user_id).emit('Unread_Request_Consultant_Chat', {user_id: admin_id,status,status});
    }
    response.send({status: 'success'});
}
exports.unread_support_ads_chat =  (request, response) => {

    let user_id =parseInt(request.body.user_id);
    let admin_id =parseInt(request.body.admin_id);
    let status =parseInt(request.body.status);
    let type =request.body.type;

    if(type == "u"){
        userChatNamespace.to(admin_id).emit('Unread_Support_Ads_Chat', {admin_id:admin_id,user_id: user_id,status:status,type:type});
    }else{
        userChatNamespace.to(user_id).emit('Unread_Support_Ads_Chat', {admin_id:admin_id,user_id: user_id,status:status,type:type});
    }

    response.send({status: 'success'});
}






