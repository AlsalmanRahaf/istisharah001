const {Model, DataTypes, sequelize} = require('../utiles/database')

class UCspesialists extends Model {}

UCspesialists.init({
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
    },
    consultant_id: {
        type: DataTypes.BIGINT,
        allowNull: false,
    }


}, {sequelize, createdAt: "created_at", updatedAt: "updated_at", tableName:"user_list_consultant_spesialists"});

// UC spesialists  == user list and consultant spesialists
module.exports = UCspesialists;



