const {Model, DataTypes, sequelize} = require('../utiles/database')

class Notification extends Model {}

Notification.init({
    id: {
        type: DataTypes.BIGINT,
        allowNull: false,
        unsigned: true,
        autoIncrement: true,
        primaryKey: true
    },
    title: {
        type: DataTypes.STRING,
        allowNull: false,
    },
    body: {
        type: DataTypes.TEXT,
        allowNull: false,
    },
    type: {
        type: DataTypes.TINYINT,
        allowNull: false,
    },
    status: {
        type: DataTypes.TINYINT,
        allowNull: false,
    },
    user_id: {
        type: DataTypes.BIGINT,
        allowNull: false,
    },


}, {sequelize, createdAt: "created_at", updatedAt: "updated_at", tableName:"notifications"});

module.exports = Notification;


