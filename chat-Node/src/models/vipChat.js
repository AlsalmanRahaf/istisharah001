const {Model, DataTypes, sequelize} = require('../utiles/database')

class VipChat extends Model {}

VipChat.init({
    id: {
        type: DataTypes.BIGINT,
        allowNull: false,
        unsigned: true,
        autoIncrement: true,
        primaryKey: true
    },

    chat_id: {
        type: DataTypes.BIGINT,
        allowNull: false,
    }

}, {sequelize, createdAt: "created_at", updatedAt: "updated_at", tableName:"vip_chats"});

// UC spesialists  == user list and consultant spesialists
module.exports = VipChat;



