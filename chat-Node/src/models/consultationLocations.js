const {Model, DataTypes, sequelize} = require('../utiles/database')

class Consultation_locations extends Model {}

Consultation_locations.init({
    id: {
        type: DataTypes.BIGINT,
        allowNull: false,
        unsigned: true,
        autoIncrement: true,
        primaryKey: true
    },
    type: {
        type: DataTypes.INTEGER,
        allowNull: false,
    },
    location: {
        type: DataTypes.INTEGER,
        allowNull: false,
    },
    room_id: {
        type: DataTypes.BIGINT,
        allowNull: false,
    },
    country: {
        type: DataTypes.STRING,
        allowNull: false,
    },




}, {sequelize, createdAt: "created_at", updatedAt: "updated_at", tableName:"consultation_locations"});

module.exports = Consultation_locations;

