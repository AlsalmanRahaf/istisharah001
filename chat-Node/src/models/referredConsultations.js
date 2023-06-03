const {Model, DataTypes, sequelize} = require('../utiles/database')

class ReferredConsultation extends Model {}

ReferredConsultation.init({
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
    consultation_id: {
        type: DataTypes.BIGINT,
        allowNull: false,
    },
    consultant_id: {
        type: DataTypes.BIGINT,
        allowNull: false,
    },
    status: {
        type: DataTypes.BOOLEAN,
        allowNull: false,
    },

    room_id: {
        type: DataTypes.BIGINT,
        allowNull: false,
    },
    type: {
        type: DataTypes.INTEGER,
        allowNull: false,
    },



}, {sequelize, createdAt: "created_at", updatedAt: "updated_at", tableName:"referred_consultations"});

module.exports = ReferredConsultation;
