require('../../utiles/environment')
const io = require("../../utiles/socket")
const UserChat = require('../../models/userChat');
const RoomMember=require('../../models/roomMember');
const Room = require('../../models/room');
const Message = require('../../models/message');
const VipChat=require('../../models/vipChat')
const ReConsultations = require('../../models/referredConsultations');
const RequestConsultation = require('../../models/RequestConsultation');
const User = require('../../models/user');
const CustomConsultation = require('../../models/customConsultation');
const Media = require('../../models/media');
const consultation=require('../../models/consultation');
const messageText = require('../../models/messageText');
const Consultation_locations=require('../../models/consultationLocations');
const {sequelize, DataTypes} = require("../../utiles/database");
global.crypto = require('crypto');
const { dirname } = require('path');
const helper = require('./../../utiles/helper');
const {isEmpty, forEach, create} = require("lodash");
const fileType = require("file-type");
const SpecializedConsultant = io.of('/userChat/SpecializedConsultant')
const userChatNamespace = io.of('/userChat/userSocket');
const userNamespace = io.of('/userChat/userSocket');
const FirebaseNotification=require('../../controllers/FirebaseNotification/notification');
const FirebaseController = require("../FirebaseNotification/notification");
const CustomFirebase = require("../FirebaseNotification/custom-notifcation");


exports.send = (socket) => {
    ///////////////[Start JOIN CHAT]////////////////////
    socket.on("sendheader", (data) => {
        socket.join(data.socketID);
        socket.socketID = data.socketID;
        socket.emit("join_success","success");

        console.log("join_success_send:"+data.socketID)
    })
    ///////////////[END JOIN CHAT]///////////////////////
    socket.on('send-message', async (data) => {
        console.log(data.message)
        console.log(socket.socketID)
        console.log(socket.request.user.id);
        let findRoom = await RequestConsultation.findOne({where: {user_id:socket.request.user.id}}) ?? await RoomMember.findOne({where: {user_id:socket.request.user.id}});

        // create path if not exist
        // helper.check_path("Message");
        return sequelize.transaction( async function (t) {

            //get room data
            let RoomData = await Room.findOne({where: { room_id : socket.socketID}});
            console.log("TTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTKKKKRRRRRRRRRRRRRooooomm"+RoomData);

            //get room member
            let receive=null;
            //user
            let UserData =await User.findOne({where: {id: socket.request.user.id}});
            if(UserData.type === "u"){
                console.log("test for you RoomDataRoomDataRoomDataRoomDataRoomDataRoomData"+RoomData)
                console.log("test for yousocket.request.user.idsocket.request.user.idsocket.request.user.idsocket.request.user.id"+socket.handshake.query.id)

                let RoomMember = await sequelize.query("SELECT user_id from room_members where room_id="+ RoomData.id +" and user_id !="+ socket.request.user.id ,{type: sequelize.QueryTypes.SELECT});
                console.log("test");
                console.log(RoomMember);
                if(RoomMember.length !== 0){
                    receive= RoomMember[0].user_id;
                    console.log("test+++++++++++++++++++++++",RoomMember)
                }
            }
            // add user
            await UserChat.create({
                user_id: socket.request.user.id,
                room_id: RoomData.id,
            },{transaction: t}).then( async (U_Chat) => {
                console.log("add success");

                if(RoomData.standard === 2){
                    await VipChat.
                    create({chat_id: U_Chat.id}, {transaction: t}).
                    then(async (Vip) => {
                        console.log(Vip.id);
                    });
                }

                let DataType =  helper.getMainType(data);

                await Message.create({
                    user_chat_id: U_Chat.id,
                    type: DataType,
                    status: 1
                }, {transaction: t}).then(async (M_caht) => {


                    //message
                    if (data.message) {
                        console.log("add message success");
                        await messageText.create({
                            Text: data.message,
                            message_id: M_caht.id
                        }, {transaction: t}).then(() => {
                            console.log("add message Text  success");
                        });
                    }

                    let ext =null;
                    let media_type=[];
                    let Array_buff=[];
                    // image
                    if (data.image && !isEmpty(data.image))
                    {
                        if (Array.isArray(data.image)) {

                            $array_base64 = data.image;
                            for (const element of $array_base64) {
                                let buff = Buffer.from(element, 'base64');
                                ext = await fileType.fromBuffer(new Buffer(element, 'base64'));
                                media_type.push(await helper.getMediaType(ext.mime));
                                Array_buff.push(buff);
                            }
                            await helper.saveImage(U_Chat.id,Array_buff, M_caht.id,DataType,socket,data,media_type,UserData.type,receive);

                            let RoomUsers = await sequelize.query("select user_id from room_members where user_id !=:user_id and room_id =:room_id",{replacements: { user_id: UserData.id , room_id:parseInt(RoomData.id)},type: sequelize.QueryTypes.SELECT});
                            console.log(RoomUsers[0])
                            let RoomUser =RoomUsers[0].user_id;
                            let token =await helper.getUserToken(RoomUser);
                            let type  =await helper.getTypeOfchat(RoomData.id);
                            console.log("typpppppppppppppppppppppppppppppppppppppp"+type);
                            if(token !== null ){
                                if(token.device_token){
                                    let device_token=token.device_token
                                    await CustomFirebase.add(device_token,UserData.id,"🌉"+data.message,type);
                                }
                            }

                        } else {
                            console.log("singel");
                        }

                    }else{
                        console.log(RoomData.room_id)
                        console.log(data.socketID)
                        socket.emit("success",{"type":DataType,"media_type":[]})
                        // socket.to("sdfdsfdsfdsf").emit('receive-message',RoomData.room_id)
                        socket.to(socket.socketID).emit('receive-message', {"chat_id":U_Chat.id,"user_id":UserData.id,"user_type":UserData.type,"type":DataType,"text":!isEmpty(data.message) ? data.message:null ,"medias":[]});
                        if(UserData.type === "u" && receive != null){
                            socket.to(RoomData.room_id).emit("last_message"+receive,{sender:UserData.id,type:DataType,text:!isEmpty(data.message) ? data.message:null ,medias:[]});
                            console.log("RoomMember++++++++++++++++++"+receive);
                        }

                    }
                    if(data.message != null){
                        console.log("User Data ++++++++++++"+UserData.id);
                        console.log("Room_id ++++++++++"+RoomData.id);
                        let RoomUsers = await sequelize.query("select user_id from request_consultations where user_id !=:user_id and room_id =:room_id",{replacements: { user_id: UserData.id , room_id:parseInt(RoomData.id)},type: sequelize.QueryTypes.SELECT});
                        console.log(RoomUsers);
                        if(RoomUsers == true){
                            let RoomUser =RoomUsers[0].user_id;
                            let Roomid=RoomData.id;
                            let token =await helper.getUserToken(RoomUser);
                            let type  =await helper.getTypeOfchat(Roomid);
                            console.log("typpppppppppppppppppppppppppppppppppppp"+type);
                            console.log("Tokkkensaddddddddddddddd"+token.device_token);
                            console.log("user_idddddddd" + RoomUser);
                            if(token !== null ){
                                if(token.device_token){
                                    let device_token=token.device_token
                                    await CustomFirebase.add(device_token,UserData.id,data.message,type);
                                }
                            }
                        }else {
                            console.log(1)

                        }
                    }
                })
            })
        }).catch(function (err) {
            console.log(err)
        });


    })


    ///////////////[Start GET CHAT]////////////////////
    socket.on('getChat', async () => {
        let RoomData = await Room.findOne({where: {room_id: socket.socketID}});
        let chat = await sequelize.query("SELECT JSON_OBJECT('chat_id',user_chats.id,'text',(CASE WHEN users.id =1 and  user_chats.status=0 then 'Deleted' else message_texts.Text end ),'type',(CASE WHEN users.id="+socket.request.user.id+"then 'sendr' else 'received' end )) as chat FROM `user_chats` join messages on messages.user_chat_id=user_chats.id join message_texts on message_texts.message_id=messages.id  join users on users.id=user_chats.user_id  WHERE    user_chats.room_id ="+RoomData.id,{type: sequelize.QueryTypes.SELECT});
        socket.to(socket.socketID).emit("Chat_room",chat)
    });
    ///////////////[END GET CHAT]////////////////////




    ///////////////[Start NEW Consultation]////////////////////
    socket.on("newConsultation",async (data) => {
        console.log("success connect")
        let userid = socket.request.user.id;
        console.log(userid);
        let UserData = await User.findOne({where: {id: userid}});
        //if status 4 or 5 then can create Consultation bc 4 -is rejected 5 - is complete
        let getLocation=await helper.getConsultationsLocation(UserData.country_code);


        let Request_valid = await sequelize.query("select id from request_consultations where user_id =:user_id and status NOT IN (4,5)",  {
            replacements: { user_id: userid },
            type: sequelize.QueryTypes.SELECT
        })
        if(Request_valid.length !== 0){
            console.log("Errrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrorrrrrrrrrrrrrrrrrrr for repeted consultation");
            userChatNamespace.to(userid).emit("Error_Consultation","this user already have consultation");
        }else{
            // helper ----
            let room_id_str = Math.random().toString(36).substring(2, 15) + Math.random().toString(36).substring(2, 15);
            //------
            return sequelize.transaction(async function (t) {

                //get room data
                await Room.create({
                        room_id: room_id_str,
                        status: 1,
                        type: 1,
                        standard: 1,
                        chat_type: 2
                    }
                    , {transaction: t}).then(async (room) => {
                    // add user
                    await RequestConsultation.create({
                        user_id: userid,
                        status: 1,
                        room_id: room.id
                    }, {transaction: t}).then(async (RConsultation) => {

                        Consultation_locations.create({
                            type: 1,
                            location:getLocation.status,
                            room_id:room.id ,
                            country:getLocation.country,
                        })

                        await UserChat.create({
                            user_id: userid,
                            room_id: room.id,
                        }, {transaction: t}).then(async (U_Chat) => {
                            console.log("add success");

                            let DataType = helper.getMainType(data);

                            await Message.create({
                                user_chat_id: U_Chat.id,
                                type: DataType,
                                status: 1
                            }, {transaction: t}).then(async (M_caht) => {
                                let ext = null;
                                let media_type = [];
                                let Array_buff = [];
                                // image
                                if (data.image && !isEmpty(data.image)) {
                                    if (Array.isArray(data.image)) {
                                        $array_base64 = data.image;
                                        for (const element of $array_base64) {
                                            let buff = Buffer.from(element, 'base64');
                                            ext = await fileType.fromBuffer(new Buffer(element, 'base64'));
                                            media_type.push(await helper.getMediaType(ext.mime));
                                            Array_buff.push(buff);
                                        }
                                        let URLS = await helper.saveImage(U_Chat.id,Array_buff, M_caht.id, DataType, socket, data, media_type);
                                    } else {
                                        console.log("singel");
                                    }
                                } else {
                                    console.log("socketId" + socket.id)
                                    socket.emit("success", {"type": DataType, "media_type": []})
                                    socket.broadcast.to(room_id_str).emit('receive-message', {
                                        chat_id:U_Chat.id,
                                        type: DataType,
                                        text: !isEmpty(data.message) ? data.message : null,
                                        medias: []
                                    });
                                }
                                if (data.message) {
                                    await messageText.create({
                                        Text: data.message,
                                        message_id: M_caht.id
                                    }, {transaction: t}).then(async () => {


                                        let imageData = await Media.findOne({where: {type_id: userid}});
                                        let user_image="";
                                        if(imageData !== null ) {
                                            user_image = process.env.APP_URL + imageData.path + '/' + imageData.filename;
                                        }
                                        userNamespace.emit("showNewConsultation", {
                                            message: data.message,
                                            user_name: UserData.full_name,
                                            image: user_image,
                                            room_id: room_id_str,
                                            user_id: userid
                                        })
                                        console.log("showNewConsultation kkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkk");
                                        // socket.emit("success",1)
                                        console.log("add new Consultation message Text  success");
                                    });
                                }
                                await FirebaseController.add( null,null,"new_consultaion","consultants");
                            })
                        })
                    })
                })//room
                // socket.emit("requestConsultation", {"user_id": socket.request.user.id})
            })
        }
    })
    ///////////////[End NEW Consultation]////////////////////




    ///////////////[Start Get Consultation By Status]////////////////////
    socket.on("get_consultation_by_status",async (data) => {
        // show Consultation status

        await User.findOne({where: {id:socket.request.user.id}}).then(async (user) => {
            if(user.type == "a"){
                let Data = await sequelize.query("select users.full_name,(select JSON_object('path',path,'file_name',filename) from media where media.type_id=request_consultations.user_id limit 1) as Image,message_texts.Text from request_consultations join users on request_consultations.user_id=users.id join user_chats on request_consultations.room_id=user_chats.room_id join messages on messages.user_chat_id=user_chats.id join message_texts on message_texts.message_id=messages.id where request_consultations.status=" + data.status + " and user_chats.created_at = (select max(created_at) from user_chats where request_consultations.room_id=user_chats.room_id  ORDER BY request_consultations.created_at)", {type: sequelize.QueryTypes.SELECT})
                let consultation_list = [];
                for (const obj of Data) {
                    if (obj !== null) {
                        image_Data = obj.Image;
                        Image = image_Data !== "" ? process.env.APP_URL + image_Data.path + image_Data.file_name : "";
                        full_name = obj.full_name;
                        message = obj.Text;
                        consultation_list.push({"image": Image, "message": message, "user_name": full_name})
                    }
                }
                socket.broadcast.emit("consultation", consultation)
            }else{
                if(data.status == 1){
                    let Data = await sequelize.query("select users.full_name,(select JSON_object('path',path,'file_name',filename) from media where media.type_id=request_consultations.user_id limit 1) as Image,message_texts.Text from request_consultations join users on request_consultations.user_id=users.id join user_chats on request_consultations.room_id=user_chats.room_id join messages on messages.user_chat_id=user_chats.id join message_texts on message_texts.message_id=messages.id where request_consultations.status=" + data.status + " and user_chats.created_at = (select max(created_at) from user_chats where request_consultations.room_id=user_chats.room_id )", {type: sequelize.QueryTypes.SELECT})
                    let consultation_list = [];
                    for (const obj of Data) {
                        if (obj !== null) {
                            image_Data = obj.Image;
                            Image = image_Data !== "" ? process.env.APP_URL + image_Data.path + image_Data.file_name : "";
                            full_name = obj.full_name;
                            message = obj.Text;
                            consultation_list.push({"image": Image, "message": message, "user_name": full_name})
                        }
                    }
                    socket.broadcast.to(socket.request.user.id).emit("consultation", consultation)
                }else{
                    let Data = await sequelize.query("select users.full_name,(select JSON_object('path',path,'file_name',filename) from media where media.type_id=consultations.user_id limit 1) as Image,message_texts.Text from consultations join users on consultations.user_id=users.id join user_chats on consultations.room_id=user_chats.room_id  join messages on messages.user_chat_id=user_chats.id join message_texts on message_texts.message_id=messages.id where consultations.status= "+data.status+"  and consultations.`consultant_id`="+socket.request.user.id+" and user_chats.created_at = (select max(created_at) from user_chats where request_consultations.room_id=user_chats.room_id )", {type: sequelize.QueryTypes.SELECT})
                    let consultation_list = [];
                    for (const obj of Data) {
                        if (obj !== null) {
                            image_Data = obj.Image;
                            Image = image_Data !== "" ? process.env.APP_URL + image_Data.path + image_Data.file_name : "";
                            full_name = obj.full_name;
                            message = obj.Text;
                            consultation_list.push({"image": Image, "message": message, "user_name": full_name})
                        }
                    }
                    socket.broadcast.to(socket.request.user.id).emit("consultation", consultation)
                }
            }


        });
    })
    ///////////////[END Get Consultation By Status]////////////////////
    ////////////// Start CHANGE CONSULTATION STATUS //////////////
    socket.on("change_consultation_status",async (data) => {
        let token=await  helper.getUserToken(data.user_id);
        socket.join(data.user_id);
        socket.emit("join_success","success");
        console.log("join_success :"+ data.user_id);
        let RoomData = await Room.findOne({where: {room_id: data.room_id}}).then(async function (RoomDD) {
            console.log("room_data"+RoomDD);
            console.log("user_id" + data.status);
            console.log(data.status);
            if(data.status === 2 || data.status === 3 || data.status === 4 || data.status === 5) {
                await updateRoom(RoomDD.id, 1)
            }
            if(data.status === 2) {
                await consultation.findOne({
                    where: {
                        room_id: RoomDD.id,
                        user_id: data.user_id,
                        consultant_id: socket.request.user.id,
                        status: 2,
                        consultations_status: 2
                    }
                }).then(async count => {
                    if (count == null) {
                        await consultation.create({
                            user_id: data.user_id,
                            consultant_id: socket.request.user.id,
                            room_id: RoomDD.id,
                            status: 2,
                            consultations_status: 2
                        }).then(async (consultationData) => {
                            RoomMember.create({user_id: data.user_id, room_id: RoomDD.id}).then(async () => {
                                RoomMember.create({user_id: socket.request.user.id, room_id: RoomDD.id});
                            })
                        })
                    } else {
                        await updateConsultation(RoomDD.id, data.status)
                    }

                    await FirebaseController.add(data.user_id, token.device_token, "Consultation-accepted");

                })
                //return room id
                userNamespace.to(data.user_id).emit("get_new_room_id", RoomDD.room_id);
                console.log("+++++++++++++++++++++++++++++________________++++++send new Room ID to user id" + data.user_id);
                await updateRequestConsultation(RoomDD.id, data.status);
            }else if (data.status === 4) {
                await FirebaseController.add(data.user_id,token.device_token, "Consultation-not-important");
                console.log(1)
                userNamespace.emit("error_change_consultation",1)

                // userNamespace.to(data.user_id).emit("error_change_consultation",1)
                await updateRequestConsultation(RoomDD.id, data.status)
                await updateConsultation(RoomDD.id, data.status)
            }else if (data.status === 5) {
                await FirebaseController.add(data.user_id,token.device_token, "Consultation-completed");
                console.log(data.user_id)
                userNamespace.emit("error_change_consultation",2)

                // userNamespace.to(data.user_id).emit("error_change_consultation",2)
                await updateRequestConsultation(RoomDD.id, data.status)
                await updateConsultation(RoomDD.id, data.status)
            }else if (data.status === 3) {
                await FirebaseController.add(data.user_id,token.device_token, "Consultation-followup");
            }else if (data.status === 6) {
                await FirebaseController.add(data.user_id,token.device_token, "Consultation-vip");
                await updateRequestConsultation(RoomDD.id, data.status)
                await updateConsultation(RoomDD.id, data.status)
                await updateRoom(RoomDD.id, 2)
                console.log("ddddddddHHH");
            }
        });
        socket.broadcast.emit("get_change_status",{"status":"success"})

    })



    ///////////////[Start JOIN SWITCH]////////////////////
    socket.on("join_switch",async (data) => {
        socket.Switch_id = data.user_id;
        socket.join(data.user_id);
        console.log("test join success");
    })
    ///////////////[END JOIN SWITCH]////////////////////


    ///////////////[Start Stop consultation]////////////////////
    socket.on("stop_consultation",async (data) => {
        // 1- normal consultation 2-spatialist consultation
        let room_id= data.room_id;
        let type= data.type;
        switch (type){
            case 1:
                break;
            case 2:
        }
        console.log("test join success");
    })
    ///////////////[END Stop consultation]////////////////////


    socket.on("switch_consultant_status",async (data) => {
        await User.findOne({where: {id: data.user_id}}).then(async (user) => {

            console.log("user" + user.type);
            if (user) {
                console.log("status" + data.status)
                let update = await user.update({switch_status: data.status});
                if (update) {

                    const type = ["c", "cn", "cph", "cd"];
                    if ( type.includes(user.type) && user.switch_status !== 0) {
                        console.log("update2 : " + user.type);
                        socket.broadcast.emit("switch_status" + data.user_id, 'u');
                    } else {
                        await CustomConsultation.findOne({where: {consultant_id: data.user_id}}).then(async (CustomConsultant) => {
                            if (CustomConsultant) {
                                if(user.switch_status !== 0) {
                                    socket.broadcast.emit("switch_status" + data.user_id, "u");
                                }else{
                                    let customType ="";
                                    if(data.lang == "en"){
                                        customType= CustomConsultant.consultation_name_en;
                                    }else{
                                        customType= CustomConsultant.consultation_name_ar;
                                    }
                                    socket.broadcast.emit("switch_status" + data.user_id,customType );
                                }
                            }else{
                                socket.broadcast.emit("switch_status" + data.user_id,user.type);
                            }
                        });
                        console.log("update3 : " + user.type);
                    }
                }

            }

        });

    });

}

async function getConsultation(status){

    let Data = await sequelize.query("select users.full_name,(select JSON_object('path',path,'file_name',filename) from media where media.type_id=request_consultations.user_id limit 1) as Image,message_texts.Text from request_consultations join users on request_consultations.user_id=users.id join user_chats on request_consultations.room_id=user_chats.room_id join messages on messages.user_chat_id=user_chats.id join message_texts on message_texts.message_id=messages.id where request_consultations.status=" + status + " and user_chats.created_at = (select max(created_at) from user_chats where request_consultations.room_id=user_chats.room_id )", {type: sequelize.QueryTypes.SELECT})
    let consultation_list = [];
    for (const obj of Data) {
        if (obj !== null) {
            image_Data = obj.Image;
            Image = image_Data !== "" ? process.env.APP_URL + image_Data.path + image_Data.file_name : "";
            full_name = obj.full_name;
            message = obj.Text;
            consultation_list.push({"image": Image, "message": message, "user_name": full_name})
        }
    }
}

async function updateRequestConsultation(Room_id,status) {


    return await RequestConsultation.update({status: status}, { // Clause
        where:
            {
                room_id: Room_id
            }
    })
}
async function updateConsultation(Room_id, consultations_status) {

    let status=2;
    if(consultations_status == 2){status=1}
    return await consultation.update({status:status,consultations_status:consultations_status}, { // Clause
        where:
            {
                room_id: Room_id
            }
    })
}

async function updateRoom(Room_id, status) {
    return await Room.update({standard: status}, { // Clause
        where:
            {
                id: Room_id
            }
    })
}