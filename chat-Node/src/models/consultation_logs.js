const {Model, DataTypes, sequelize} = require('../utiles/database')

class consultation_logs extends Model {}

consultation_logs.init({
    id: {
        type: DataTypes.BIGINT,
        allowNull: false,
        unsigned: true,
        autoIncrement: true,
        primaryKey: true
    },
    consultation_id: {
        type: DataTypes.BIGINT,
        allowNull: false,
    },
    consultant_type: {
        type: DataTypes.STRING,
        allowNull: false,
    },
    action: {
        type: DataTypes.INTEGER,
        allowNull: false,
    },

}, {sequelize, createdAt: "created_at", updatedAt: "updated_at", tableName:"consultations"});

module.exports = consultation_logs;


