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
    Text: {
        type: DataTypes.TEXT,
        allowNull: false,
    },
    message_id: {
        type: DataTypes.BIGINT,
        allowNull: false,
    }

}, {sequelize, createdAt: "created_at", updatedAt: "updated_at", tableName:"message_texts"});

module.exports = Message;


