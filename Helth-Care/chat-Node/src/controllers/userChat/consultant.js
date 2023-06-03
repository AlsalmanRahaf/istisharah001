const User = require('../../models/user');
const Room = require('../../models/room');
const UCspesialists=require("../../models/userlistConsultantSpesialists")
const {sequelize} = require("../../utiles/database");
global.crypto = require('crypto');
const helper = require('./../../utiles/helper');
const Media = require("../../models/media");
const ReConsultations = require("../../models/referredConsultations");
const io = require("../../utiles/socket");
const Consultation_locations = require("../../models/consultationLocations");
const FirebaseController = require("../FirebaseNotification/notification");
const RoomMember = require("../../models/roomMember");


const consultantNamespace = io.of('/userChat/SpecializedConsultant')
const notificationNamespace=io.of('/socket/notification');
const userChatNamespace = io.of('/userChat/userSocket')

exports.consultant = (socket) => {


    //////////START  GET  Consultation //////////////////
    socket.on("getConsultation",async (status) => {
        //0-notReadable 1-readable 2 -follow up 3-not important 4-completed
        let consultationData = await sequelize.query("SELECT JSON_OBJECT('image',(select CONCAT(media.path,media.filename) from media where media.type_id=users.id limit 1),'name',users.full_name) as consultation FROM  consultations join users on users.id=consultations.user_id  WHERE consultations.consultant_id=5 and consultations.status=0 ORDER BY consultations.created_at ")
        console.log(consultationData);
    });
    //////////END  GET  Consultation //////////////////

    /////////////////// Start Switch Consultant Status ////////////////
    socket.on("switch_consultant_status",async (data) => {
        let user =await User.findOne({where: {id: data.user_id,type:'c'}});
        console.log("user"+user);
        if(user){
            let update= user.update({switch_status: data.status});
            console.log("status"+data.status)
            if(update){
                let token=helper.getUserToken(data.user_id);
                console.log("update : "+update);
                console.log("switch_status : "+ user.switch_status);
                console.log("type : "+ user.type);
                if(user.type === "u" && user.switch_status !== 0){
                    socket.emit("switch_status",'c');
                }else if(user.type === "c" && user.switch_status !== 0){
                    socket.emit("switch_status",'u');
                    // await FirebaseController.add(data.user_id,token, "Consultation-type-to-user");
                }else{
                    socket.emit("switch_status",user.type);
                    // await FirebaseController.add(data.user_id,token, "user-type-to-Consultation");
                }
            }
        }
    });
    ////////////// End Switch Consultant Status //////////////////


    /////////////[Start Referred Consultations]////////////////////
    socket.on('referredConsultations', async (data) => {

        let UserData = await User.findOne({where: {id: data.user_id}});
        let getLocation=await helper.getConsultationsLocation(UserData.country_code);
        // referred Consultations type
        // cph-Consultant_Pharmacist cn-Consultant_nutrition cd-Consultant_diabetes
        console.log("tesssssssssssssssssssssssssssssssssssssssssssssssssss"+data.lat);
        console.log("test for you ="+data.consultation_id);
        console.log("::::::::::::::::::::::::::::")
        console.log(JSON.stringify(data.type));
        console.log("type::::::::::::::::1111"+data);

        let RCType='cph';

        switch (data.type){
            case 1:
                RCType="cph";
                break;
            case 2:
                RCType = 'cn';
                break;
            case 3:
                RCType = 'cd';
                break;
        }
        let SpesialistType=getSpesialistType(RCType);
        let room_id_str = Math.random().toString(36).substring(2, 15) + Math.random().toString(36).substring(2, 15);

        console.log("room_id"+room_id_str);
        console.log("type"+SpesialistType);
        //get room data
        await Room.create({
                room_id: room_id_str,
                status: 1,
                type: 1,
                standard: 1,
                chat_type: 2
            }
        ).then(async (room) => {

            await User.findOne({where: {consultant_type: RCType}}).then( async function (consaltent) {
                console.log("error Spesialist++++++++++++"+consaltent);
                console.log("error Spesialist++++++++++++"+consaltent.id);
                console.log("error Spesialist++++++++++++"+data.user_id);
                console.log("error Spesialist++++++++++++"+data.consultation_id);
                console.log("error Spesialist++++++++++++"+consaltent.id);
                console.log("error Spesialist++++++++++++"+ room.id);
                console.log("error Spesialist++++++++++++"+ data.type);
                if(consaltent !== null){
                await ReConsultations.create({
                    user_id: data.user_id,
                    consultation_id: data.consultation_id,
                    consultant_id: consaltent.id,
                    status: 1,
                    room_id: room.id,
                    type: data.type
                }).then(async () => {

                    RoomMember.create({user_id: consaltent.id, room_id: room.id}).then(async () => {
                        RoomMember.create({user_id: data.user_id, room_id: room.id});
                    })

                    Consultation_locations.create({
                        type: 2,
                        location:getLocation.status,
                        room_id:room.id ,
                        country:getLocation.country,
                    }).then(async () => {
                        await FirebaseController.add(null,consaltent.device_token, "referredConsultations");
                    })

                    UCspesialists.findOne({where: {consultant_id: consaltent.id, user_id: data.user_id}}).then( async function (UU) {

                        if(UU == null){
                                UCspesialists.create({
                                    room_id: room.id,
                                    consultant_id: consaltent.id,
                                    user_id: data.user_id,
                                });
                        }else{
                            await UCspesialists.update({room_id: room.id}, { // Clause
                                        where:
                                            {
                                                consultant_id: consaltent.id,
                                                user_id: data.user_id
                                            }
                                    })
                        }
                    });
                });
                console.log("tess+++++++++++++++//++++++++++");
                let imageData = await Media.findOne({where: {type_id: data.user_id}});
                let user_image = "";
                if (imageData !== null) {
                    user_image = process.env.APP_URL + imageData.path + '/' + imageData.filename;
                }
                await User.findOne({where: {id:data.user_id}}).then( async function (user){
                    console.log("consaltent saddddddddddddddddddddddddddddddddddddd////////////////////////////"+consaltent.id);
                    consultantNamespace.to(consaltent.id).emit("showReferredConsultation", {
                        message: "new Consultation Referred To You",
                        user_name: user.full_name,
                        image: user_image,
                        room_id: room_id_str,
                        user_id: user.id
                    })
                    console.log("dialog-spesialist_chat"+user.id);
                    userChatNamespace.emit("dialog-spesialist_chat",{kind:RCType});
                    userChatNamespace.emit("show_spesialist_chat",{
                        room_id:room_id_str,
                        user_name:user.full_name
                    })
                });

                }else{
                    console.log("this is test emit Error_spesialist consultation="+data.consultation_id+"user="+data.user_id);
                    consultantNamespace.emit("Error_spesialist",{type:SpesialistType})
                    userChatNamespace.emit("Error_spesialist",{type:SpesialistType})
                }
            //
            });
        })
    });
    /////////////[END Referred Consultations]////////////////////

    //////////// START CHANGE Referred Consultations STATUS /////////////
    socket.on("change_Referred_consultation_status",async (data) => {
        await Room.findOne({where: {room_id: data.room_id}}).then(async function (RoomDD) {
            console.log("ROOM ROOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOO<<<<<"+RoomDD.id);
            await  ReConsultations.findOne({where: {room_id: RoomDD.id}}).then(async function (ReConsultation) {
                console.log("ReConsultation ++++++++++++++++++++++++++++++++++++" + ReConsultation);
                await User.findOne({where: {id: ReConsultation.consultant_id}}).then(async function (user) {
                   let  token = helper.getUserToken(ReConsultation.user_id);

                    switch (data.status) {
                        case 2:
                        case 3:
                        case 4:
                            await updateRoom(RoomDD.id, 1)
                        case 2 :
                            await FirebaseController.add(null,ReConsultation.user_id,token, "Consultation-accepted-Spesialist");
                            await updateReConsultation(RoomDD.id, data.status)
                            userChatNamespace.emit("check_spesialist_conversation_status", {
                                status: data.status,
                                name: user.full_name
                            });
                            break;
                        case 3 :
                            await FirebaseController.add(null,ReConsultation.user_id,token, "Consultation-not-important-Spesialist");
                        case 4 :
                            await FirebaseController.add(null,ReConsultation.user_id,token, "Consultation-completed-Spesialist");
                            userChatNamespace.emit("check_spesialist_conversation_status", {
                                status: data.status,
                                name: user.full_name
                            });
                            await updateReConsultation(RoomDD.id, data.status)
                            break;
                        case 6 :
                            await updateRoom(RoomDD.id, 2)
                        default:
                            return;
                    }
                    consultantNamespace.emit("get_change_status", {"status": "success"})
                });
            });
        });

        //////////// END CHANGE Referred Consultations STATUS /////////////
});
}

async function updateReConsultation(Room_id, status) {

    return await ReConsultations.update({status: status}, { // Clause
        where:
            {
                room_id: Room_id
            }
    })
}

function getSpesialistType(type){

    switch (type){
        case "cph":type="Pharmacist Consultant";
        break;
        case "cn":type="Nutrition Consultant";
        break;
        case "cd":type="Diabetes Consultant";
        break;
        default:
            type="Pharmacist Consultant"
        break;
    }
    return type;

}
async function updateRoom(Room_id, status) {
    return await Room.update({standard: status}, { // Clause
        where:
            {
                id: Room_id
            }
    })
}