const {Model, DataTypes, sequelize} = require('../utiles/database')

class roomMember extends Model {}

roomMember.init({
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

}, {sequelize, createdAt: "created_at", updatedAt: "updated_at", tableName:"room_members"});

module.exports = roomMember;

