const {Model, DataTypes, sequelize} = require('../utiles/database')

class Consultation extends Model {}

Consultation.init({
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
    consultant_id: {
        type: DataTypes.BIGINT,
        allowNull: false,
    },
    room_id: {
        type: DataTypes.BIGINT,
        allowNull: false,
    },
    status: {
        type: DataTypes.BOOLEAN,
        allowNull: false,
    },
    consultations_status: {
        type: DataTypes.INTEGER,
        allowNull: false,
    }

}, {sequelize, createdAt: "created_at", updatedAt: "updated_at", tableName:"consultations"});

module.exports = Consultation;


