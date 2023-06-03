// Converts numeric degrees to radians
const needle = require('needle');
const  fileType=require('file-type');
const {isEmpty} = require("lodash");
const {dirname} = require("path");
const fs = require("fs");
const querystring = require("querystring");
require('./environment')
const RequestConsultation = require("../models/RequestConsultation");
const consultation= require('../models/consultation');
const fetch = require('node-fetch');
const MENA = require("./MENA.json");
const Room = require("../models/room");
const User = require("../models/user");
const Consultation_locations = require("../models/consultationLocations");
const io = require("./socket");
const chatNamespace = io.of('/chat/socketChat')





async  function saveImage(U_Chat,media, M_chat_id,DataType,socket,data,media_type2,user_type,receiver){

    const url = process.env.APP_URL+'api/en/save_message_media';
    //u_type => url with type
    const u_type = [];

    if (media) {
        var formData = {
            image: media,
            message_id: M_chat_id,
            media_type: media_type2
        };

          await  needle
            .post(url, formData, {multipart: true}, async (err, res) => {
                if (err) {
                    console.error(err);
                }
                for (let i = 0; i < media.length; i++) {
                    let urls = res.body;

                    ext = await fileType.fromBuffer(new Buffer(media[i], 'base64'));
                    media_type = ext != null ? await getMediaType(ext.mime) : {};
                    console.log("there is a media file")
                    u_type.push([urls[i], media_type]);
                    console.log(urls[i])
                    console.log(u_type)
                }
                if (u_type) {
                    $message = {
                        "medias": u_type,
                        "type": DataType,
                        "text": !isEmpty(data.message) ? data.message : null,
                        "user_type": user_type,
                        "chat_id": U_Chat
                    };

                    socket.to(socket.socketID).emit('receive-message', $message);
                }
                if(receiver !== null){
                    console.log("receiver++++++++++++++++++"+receiver)
                    socket.broadcast.to(socket.socketID).emit("last_message"+receiver,{sender:socket.request.user.id,type:DataType,text:!isEmpty(data.message) ? data.message:null ,medias:u_type});
                }


            })
        chatNamespace.emit("success",{"type":DataType,"media_type":media_type2[0]})


    } else {
        return u_type;
    }

}


async function getConsultationsLocationBYlatlng(lat, long) {


    let url = 'https://maps.googleapis.com/maps/api/geocode/json?latlng=' + lat + ',' + long + '&key=' + process.env.google_api_key;
    let fetchData = await fetch(url);
    let country_google =await fetchData.json();
    let results= country_google.results;
    let data ;
          results.map(test=>{
                let country = test.formatted_address.search(",");
                if(country !== -1){
                    country_google = test.formatted_address.substr(country+2);
                }
            })

             // MENA =>  Middle East and North Africa
             let MENA= require("./MENA.json")
            await  MENA.map(country=>{
            if(country.name === country_google){
                data= {status:1,country:country_google};
            }
            })

    if(data){
       return  data;
    }else{
        return {status:2,country:country_google};
    }
    return country_google;
}

async function getConsultationsLocation(UserCountryCode) {
    let data ;
    let MENA= require("./MENA.json")
    await  MENA.map(country=>{
        if(country.name === UserCountryCode){
            data= {status:1,country:UserCountryCode};
        }
    })
    console.log(data);
    if(data){
        return data;
    }else{
        return {status:2,country:UserCountryCode};
    }
}

async function getNotification(lang){
   return require("./Lang/" + lang + ".json")
}


async function update_consultation_notification(index,value,lang){
    console.log(index)
    const fs = require('fs');
    const fileName = "./Lang/" + lang + ".json";
    const file =  require(fileName);
    console.log(file[0][index]);
    fs.writeFile(fileName, JSON.stringify(file), function writeJSON(err) {
        if (err) return console.log(err);
        console.log(JSON.stringify(file));
        console.log('writing to ' + fileName);
    });
}
async function getMediaType(mime) {
    let type = 0;
    switch (mime) {
        case getExt(mime, "image") :
            type = 2
            break;
        case getExt(mime, "video") :
            type = 3
            break;
        case getExt(mime, "audio") :
            type = 4
            break;
        case getExt(mime, "application") :
            type = 5
            break;
        default :
            type = 6;
    }
    return type;
}


function getMainType(data){
    let type2=1;
    if (!isEmpty(data.image) && data.message) {
        type2 = 3;
    } else if (!isEmpty(data.image)) {
        type2 = 2
    } else if (data.message) {
        type2 = 1
    }
    return type2;
}

function  getExt(text,type){
    console.log(text.substr(0,text.search('/')) === type);
    if(text.substr(0,text.search('/')) === type){
        return  text.substr(0,text.search('/'))+'/'+text.substr(text.search('/')+1)
    }
    return "error";
}

function check_path(path){

    const appDir = dirname(require.main.filename);
    var dir = '/public_html/Helth-Care/chat-Node/public/uploads/'+path;


    if (!fs.existsSync(dir)){
        console.log("create new dir ")
        fs.mkdirSync(dir);
    }
}
async function getUserToken(user_id) {
  return await User.findOne({where: {id: user_id}});
}

async function getTypeOfchat(room_id){
    // 1 - normal chat 2-normal consultation 3-specialist consultation 4-custom consultation 5-chat admin with consultation
    $type=1;
    console.log("room_id +dsaasdddddddddddasda"+room_id);
    await  Room.findByPk(room_id).then(async (room) => {
        switch (room.chat_type) {
            case 2:

                await Consultation_locations.findOne({ // Clause
                    where:
                        {
                            room_id: room_id
                        }
                }).then((room_location)=>{
                    let type = room_location.type;
                    switch (type){
                        case 1:
                            $type=2;
                            break;
                        case 2:
                            $type=3;
                            break;
                        case 3:
                            $type=4;
                            break;
                    }
                })
                break;
            case 3:
                $type = 5;
                break;
        }
    })
    return $type;
}


async function updateConsultation(Room_id, status) {

    return await consultation.update({status: status,consultations_status:1}, { // Clause
        where:
            {
                room_id: Room_id
            }
    })
}



module.exports = {saveImage,getMediaType,getMainType,getExt,check_path,updateConsultation,getNotification,getConsultationsLocation,getUserToken,update_consultation_notification,getTypeOfchat}
