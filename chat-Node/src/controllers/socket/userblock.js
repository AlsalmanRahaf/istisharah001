const io = require("../../utiles/socket")
const blockList = require("../../models/blockList");
const userChatNamespace = io.of('/userChat/userSocket')


exports.block =  (request, response ) => {
    console.log("user_id" + request.body.user_id);
    let user_id =parseInt(request.body.user_id);
    userChatNamespace.to(user_id).emit('block_user', {user_id:user_id});
    response.send({status: 'success'})
}

exports.block_consultant_consultation =  (request, response) => {
    let consultant_id =parseInt(request.body.consultant_id);
    console.log(consultant_id);
    userChatNamespace.to(consultant_id).emit('block_consultation', {consultant_id:consultant_id})
    response.send({status: 'success'})
}

exports.block_users =  (socket) => {

    // block
    let user_id = socket.handshake.query.id;

    socket.on("block_users",(data)=>{
        console.log("block test man");
        let block=data.block_list;
        block.forEach(async block_id => {
            await blockList.create({type:1,user_id: user_id, block_id: block_id});
            userChatNamespace.to(block_id).emit('user_block', {user_id: user_id})
        })
        userChatNamespace.to(user_id).emit('block_success', "done");
    })


    //unblock
    socket.on("unblock_users",(data)=>{
        console.log("unblock test man");
        let unblock=data.unblock_list;
        unblock.forEach(async block_id => {
            console.log("block_id+++++++++++++++++++++++++++:"+block_id+"++++++++++++++++++"+user_id);

            await blockList.destroy({where:{user_id: user_id, block_id: block_id}});
            userChatNamespace.to(block_id).emit('user_unblock', {user_id: user_id})
        })
        userChatNamespace.to(user_id).emit('unblock_success', "done");
    })

    socket.on("message_seen",(data)=>{
        let message_id=data.message_id;
        let user_id=data.user_id;
        userChatNamespace.to(user_id).emit('seen', {message_id: message_id})
    });

}

