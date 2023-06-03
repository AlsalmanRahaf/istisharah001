const jwt = require('jsonwebtoken');
const OauthAccessToken = require('../models/oauth_access_token');
const User = require('../models/user');

exports.authentication = async (socket, next) => {
    const { headers } = socket.request;
    console.log(headers)
    if (headers.authorization != "null") {
        const authorization = headers.authorization;
        console.log(authorization);
        const comp = authorization.split(' ');

        if (comp.length == 2 && comp[0] == `Bearer`) {
            const token = comp[1];
            if(token != "null")
            {


                const { jti } = jwt.decode(token);

                const access_token = await OauthAccessToken.findByPk(jti);
                if(access_token){
                    let user = await User.findByPk(access_token.user_id)
                    if(user && user !== undefined){
                        socket.request.user = user
                        next();
                    }else{
                        const err = new Error("not authorized");
                        err.data = { content: "Please retry later" }; // additional details
                        next(err);
                    }
                }else{
                    const err = new Error("not authorized");
                    err.data = { content: "Please retry later" }; // additional details
                    next(err);
                }
            }else{
                const err = new Error("not authorized");
                err.data = { content: "Please retry later" }; // additional details
                next(err);
            }
        }
    }else{
        const err = new Error("not authorized");
        err.data = { content: "Please retry later" }; // additional details
        next(err);
    }
}
