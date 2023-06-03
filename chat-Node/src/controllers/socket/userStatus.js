const io = require("../../utiles/socket")
const userChatNamespace = io.of('/userChat/userSocket')


exports.chnage =  (request, response) => {
    let user_id =parseInt(request.body.user_id);
    console.log("join success" + user_id);
    let type =request.body.type;
    console.log("change type success");
    console.log(request.body.type);
    userChatNamespace.to(user_id).emit('change_user_type', {type:type})
    response.send({status: 'success'})
}
