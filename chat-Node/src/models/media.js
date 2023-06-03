const {Model, DataTypes, sequelize} = require('../utiles/database')

class Media extends Model {}

Media.init({
    id: {
        type: DataTypes.BIGINT,
        allowNull: false,
        unsigned: true,
        autoIncrement: true,
        primaryKey: true
    },
    filename: {
        type: DataTypes.TEXT,
        allowNull: false,
    },
    path: {
        type: DataTypes.TEXT,
        allowNull: false,
    },
    media_type: {
        type: DataTypes.TEXT,
        allowNull: false,
    },
    type_id: {
        type: DataTypes.BIGINT,
        allowNull: false,
    },
    group: {
        type: DataTypes.TEXT,
        allowNull: false,
    }

}, {sequelize, createdAt: "created_at", updatedAt: "updated_at", tableName:"media"});

module.exports = Media;


