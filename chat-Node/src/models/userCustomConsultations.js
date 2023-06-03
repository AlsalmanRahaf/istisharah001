const {Model, DataTypes, sequelize} = require('../utiles/database')

class UserCustomConsultations extends Model {}

UserCustomConsultations.init({
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
    custom_consultation_id: {
        type: DataTypes.BIGINT,
        allowNull: false,
    },
    status: {
        type: DataTypes.BIGINT,
        allowNull: false,
    },
    consultation_status: {
        type: DataTypes.BIGINT,
        allowNull: false,
    }



}, {sequelize, createdAt: "created_at", updatedAt: "updated_at", tableName:"user_custom_consultations"});

module.exports = UserCustomConsultations;


