const {Model, DataTypes, sequelize} = require('../utiles/database')

class RequestConsultation extends Model {}

RequestConsultation.init({
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
status: {
type: DataTypes.BOOLEAN,
allowNull: false,
},

room_id: {
    type: DataTypes.BIGINT,
    allowNull: false,
},



}, {sequelize, createdAt: "created_at", updatedAt: "updated_at", tableName:"request_consultations"});

module.exports = RequestConsultation;
