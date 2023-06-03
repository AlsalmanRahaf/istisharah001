const User = require("../models/user");
const UserLiveLocation = require("../models/users_live_locations");


UserLiveLocation.belongsTo(User, {onDelete: 'CASCADE', onUpdate: 'CASCADE', foreignKey: 'user_id', constraints: true})
User.hasOne(UserLiveLocation, {onDelete: 'CASCADE', onUpdate: 'CASCADE', foreignKey: 'user_id', constraints: true, as: 'livelocation'})


