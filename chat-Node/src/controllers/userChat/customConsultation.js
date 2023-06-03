const User = require('../../models/user');
const Room = require('../../models/room');
const {sequelize} = require("../../utiles/database");
global.crypto = require('crypto');
const helper = require('./../../utiles/helper');
const Media = require("../../models/media");
const io = require("../../utiles/socket");
const UserChat = require("../../models/userChat");
const Message = require("../../models/message");
const {isEmpty} = require("lodash");
const fileType = require("file-type");
const messageText = require("../../models/messageText");
const UserCustomConsultation = require("../../models/userCustomConsultations");
const Custom_consultations = require("../../models/customConsultation");
const FirebaseController = require("../FirebaseNotification/notification");
const Consultation_locations = require("../../models/consultationLocations");
const {Op} = require("sequelize");
const RoomMember = require("../../models/roomMember");
const chatNamespace = io.of('/chat/socketChat')
const userNamespace = io.of('/userChat/userSocket')



const notificationNamespace=io.of('/socket/notification');
const userChatNamespace = io.of('/userChat/userSocket')
const CustomConsultationNamespace  = io.of('/userChat/CustomConsultation')

exports.CustomConsultant = (socket) => {
    //////////START  New  custom  Consultation //////////////////
    socket.on("new_custom_consultation",async (data) => {
        console.log("this is test for you");
        let userid = socket.request.user.id;
        let UserData = await User.findOne({where: {id: userid}});
        let getLocation=await helper.getConsultationsLocation(UserData.country_code);

        let Request_valid = await sequelize.query("select id from user_custom_consultations where user_id =:user_id and status NOT IN (4,5) ",  {
            replacements: { user_id: userid },
            type: sequelize.QueryTypes.SELECT
        })
        if(Request_valid.length !== 0){
            console.log("Errrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrorrrrrrrrrrrrrrrrrrr for repeted custom consultation");
            userChatNamespace.emit("Error_Custom_Consultation","this user already have consultation");
        }else{
            //if status 4 or 5 then can create Consultation bc 4 -is rejected 5 - is complete

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


                    await Custom_consultations.findOne({where: {
                        consultation_name_en: data.type,
                            [Op.or]: [
                        { consultation_name_en: data.type },
                        { consultation_name_ar: data.type }
                    ]
                            ,status:1
                    }})
                        .then(async (custom_consultation) => {

                            if(custom_consultation.length === 0){
                                console.log("Errrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrorrrrrrrrrrrrrrrrrrr for repeted custom consultation");
                                userChatNamespace.emit("Error_Custom_Consultation","not custom consultant added yet");
                            }else{
                                await UserCustomConsultation.create({
                                    user_id: userid,
                                    custom_consultation_id: custom_consultation.id,
                                    status: 1,
                                    consultation_status: 1,
                                    room_id: room.id
                                }, {transaction: t}).then(async (RConsultation) => {

                                     RoomMember.create({user_id: userid, room_id: room.id}).then( () => {
                                         RoomMember.create({user_id: custom_consultation.consultant_id, room_id: room.id});
                                    })
                                    // await Consultation_locations.create({
                                    //     type: 3,
                                    //     location:getLocation.status,
                                    //     room_id:room.id ,
                                    //     country:getLocation.country,
                                    // })
                                    await UserChat.create({
                                        user_id: socket.request.user.id ,
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
                                                    let URLS = await helper.saveImage(U_Chat.id, Array_buff, M_caht.id, DataType, socket, data, media_type);
                                                } else {
                                                    console.log("singel");
                                                }
                                            } else {
                                                console.log("socketId" + 1)
                                                chatNamespace.emit("success", {"type": DataType, "media_type": []})
                                                socket.broadcast.to(room_id_str).emit('receive-message', {
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

                                                    let UserData = await User.findOne({where: {id: userid}});
                                                    let imageData = await Media.findOne({where: {type_id: userid}});
                                                    let user_image = "";
                                                    if (imageData !== null) {
                                                        user_image = process.env.APP_URL + imageData.path + '/' + imageData.filename;
                                                    }
                                                    console.log("custom_consultation.consultant_id PPPPPPPPPPPPPPPPPPPPPPPPPPPPPPPP"+custom_consultation.consultant_id);

                                                    CustomConsultationNamespace.to(custom_consultation.consultant_id).emit("show_New_custom_Consultation", {
                                                        message: data.message,
                                                        user_name: UserData.full_name,
                                                        image: user_image,
                                                        room_id: room_id_str,
                                                        user_id: userid
                                                    })

                                                    // socket.emit("success",1)
                                                    console.log("add new Consultation message Text  success");
                                                });
                                            }
                                        })
                                    })

                                })
                            }
                            }
                        )//Custom_consultations
                })//room
                // socket.emit("requestConsultation", {"user_id": socket.request.user.id})
            })
        }
    })

    //////////END  New  custom Consultation //////////////////





    //////////// START custom  Consultations STATUS /////////////

    socket.on("change_custom_consultation_status",async (data) => {
        console.log("join success");
        let token=await  helper.getUserToken(data.user_id);
        console.log ("token +sadsadasdas"+token.device_token);
        console.log({"room_id": data.room_id});
        let RoomData = await Room.findOne({where: {room_id: data.room_id}}).then(async function (RoomDD) {
            console.log("room_data"+RoomDD);
            console.log("user_id" + socket.request.user.id);
            switch (data.status) {
                case 2:
                case 3:
                case 4:
                case 5:
                    await updateRoom(RoomDD.id, 1)
                case 2 :
                    await FirebaseController.add(data.user_id,token.device_token, "Consultation-accepted");
                    await updateCustomConsultation(RoomDD.id, data.status)
                    break;
                case 4:
                    await FirebaseController.add(data.user_id,token.device_token, "Consultation-not-important");
                    await updateCustomConsultation(RoomDD.id, data.status)
                    break;
                case 5:
                    await FirebaseController.add(data.user_id,token.device_token, "Consultation-completed");
                    await updateCustomConsultation(RoomDD.id, data.status)
                    break;
                case 3:
                    await FirebaseController.add(data.user_id,token.device_token, "Consultation-followup");
                case 6:
                    await FirebaseController.add(data.user_id,token.device_token, "Consultation-vip");
                    await updateCustomConsultation(RoomDD.id, data.status)
                    await updateRoom(RoomDD.id, 2)
                    break;
                default:
                    return;
            }
        });
        socket.emit("get_change_status",{"status":"success"})
    })
    //////////// END custom  Consultations STATUS /////////////
}

async function updateCustomConsultation(Room_id, consultations_status) {
    return await UserCustomConsultation.update({consultation_status:consultations_status}, { // Clause
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