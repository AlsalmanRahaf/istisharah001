const {Model, DataTypes, sequelize} = require('../utiles/database')

class Custom_consultations extends Model {}

Custom_consultations.init({
    id: {
        type: DataTypes.BIGINT,
        allowNull: false,
        unsigned: true,
        autoIncrement: true,
        primaryKey: true
    },
    status: {
        type: DataTypes.BIGINT,
        allowNull: false,
    },
    consultant_id: {
        type: DataTypes.BIGINT,
        allowNull: false,
    },
    consultation_name_en:{
        type: DataTypes.STRING,
        allowNull: false,
    },
    consultation_name_ar:{
        type: DataTypes.STRING,
        allowNull: false,
    }



}, {sequelize, createdAt: "created_at", updatedAt: "updated_at", tableName:"custom_consultations"});

module.exports = Custom_consultations;

