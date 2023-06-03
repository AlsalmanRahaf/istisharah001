const {Model, DataTypes, sequelize} = require('../utiles/database')

class blockList extends Model {}

blockList.init({
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
    block_id: {
        type: DataTypes.BIGINT,
        allowNull: false,
    },
    type: {
        type: DataTypes.INTEGER,
        allowNull: false,
    },
}, {sequelize, createdAt: "created_at", updatedAt: "updated_at", tableName:"blocked_users"});

module.exports = blockList;


