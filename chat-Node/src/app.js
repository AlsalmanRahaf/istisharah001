const express = require('express');
const bodyParser = require('body-parser');
const http = require('http');
require('./utiles/environment')
const app = express();

global.server = http.createServer(app)
port = 8109;
require('./routes/sockets/api')
const mainApiRoute = require('./routes/api/main')
const {authentication} = require("./middlewares/auth_socket_io");
const userController = require('./controllers/socket/userStatus');
const userBlock = require('./controllers/socket/userblock');
const advertisements = require('./controllers/socket/advertisements');
const message = require('./controllers/socket/message');
//Middlewares
app.use(bodyParser.urlencoded({extended: true}));
app.use('/api', mainApiRoute)

app.get("/",function (req,res){res.send("/change_user_type")})


app.post("/change_user_type",userController.chnage);
app.post("/block_user",userBlock.block);
app.post("/addSlider",advertisements.addSlider);
app.post("/addAds",advertisements.addAds);
app.post("/block_consultant_consultation",userBlock.block_consultant_consultation);
app.post("/message_seen",message.message_seen);
app.post("/change_user_to_other_consultant",message.change_user_to_other_consultant);
app.post("/getUnReadConsultation",message.getUnReadConsultation);
app.post("/unread_Admin_Consultant_chat",message.unread_Admin_Consultant_chat);
app.post("/unread_Request_Consultant_chat",message.unread_Request_Consultant_chat);
app.post("/unread_support_ads_chat",message.unread_support_ads_chat);





global.server.listen(port, () => {
    console.log("server is runing "+port);
})
