const {Model, DataTypes, sequelize} = require('../utiles/database')

class Room extends Model {}

Room.init({
    id: {
        type: DataTypes.BIGINT,
        allowNull: false,
        unsigned: true,
        autoIncrement: true,
        primaryKey: true
    },
    room_id: {
        type: DataTypes.STRING,
        allowNull: false,
    },
    status: {
        type: DataTypes.BOOLEAN,
        allowNull: false,
    },
    type: {
        type: DataTypes.INTEGER,
        allowNull: false,
    },
    standard: {
        type: DataTypes.INTEGER,
        allowNull: false,
    },
    chat_type:{
        type: DataTypes.INTEGER,
        allowNull: false,
    }

}, {sequelize, createdAt: "created_at", updatedAt: "updated_at", tableName:"rooms"});

module.exports = Room;

