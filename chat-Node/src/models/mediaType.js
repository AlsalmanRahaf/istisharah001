const {Model, DataTypes, sequelize} = require('../utiles/database')

class MediaType extends Model {}

MediaType.init({
    id: {
        type: DataTypes.BIGINT,
        allowNull: false,
        unsigned: true,
        autoIncrement: true,
        primaryKey: true
    },
    media_id: {
        type: DataTypes.BIGINT,
        allowNull: false,
    },
    media_type: {
        type: DataTypes.STRING,
        allowNull: false,
    }

}, {sequelize, createdAt: "created_at", updatedAt: "updated_at", tableName:"media_types"});

module.exports = MediaType;

