const {Model, DataTypes, sequelize} = require('../utiles/database')

class UserChat extends Model {}

UserChat.init({
    id: {
        type: DataTypes.BIGINT,
        allowNull: false,
        unsigned: true,
        autoIncrement: true,
        primaryKey: true
    },

    user_id: {
        type: DataTypes.BIGINT,
        allowNull: false,
    },
    room_id: {
        type: DataTypes.BIGINT,
        allowNull: false,
    }


}, {sequelize, createdAt: "created_at", updatedAt: "updated_at", tableName:"user_chats"});

module.exports = UserChat;
