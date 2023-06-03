const io = require("../../utiles/socket")
const userChatNamespace = io.of('/userChat/userSocket')

exports.addSlider =  (request, response) => {
    let image =request.body.image;
    console.log("add slider image successfully" + image);
    userChatNamespace.emit('add_slider', {image:image})
    response.send({status: 'success'})
}


exports.addAds =  (request, response) => {
    let id  =request.body.id;
    let type =request.body.type;
    let logo =request.body.logo;
    let data =request.body.data;
    console.log("add adds successfully with id :" + id + ' type :' + type + ' logo ' + logo + ' data ' + data);

    userChatNamespace.emit('add_ads', {id:id,type:type,logo:logo,data:data});
    response.send({status: 'success'})
}


