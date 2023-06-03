const {Model, DataTypes, sequelize} = require('../utiles/database')

class Message extends Model {}

Message.init({
    id: {
        type: DataTypes.BIGINT,
        allowNull: false,
        unsigned: true,
        autoIncrement: true,
        primaryKey: true
    },
    type: {
        type: DataTypes.INTEGER,
        allowNull: false,
    },
    status: {
        type: DataTypes.INTEGER,
        allowNull: false,
    },
    user_chat_id: {
        type: DataTypes.BIGINT,
        allowNull: false,
    }

}, {sequelize, createdAt: "created_at", updatedAt: "updated_at", tableName:"messages"});

module.exports = Message;


