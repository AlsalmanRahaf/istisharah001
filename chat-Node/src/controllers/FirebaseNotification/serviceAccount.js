let  admin = require("firebase-admin");
let  serviceAccount = require("./health-care-a35ff-firebase-adminsdk-1p1cg-3161b77e28.json");

admin.initializeApp({
    credential: admin.credential.cert(serviceAccount),
});

module.exports=admin;
