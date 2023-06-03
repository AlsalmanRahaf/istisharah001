const {Model, DataTypes, sequelize} = require('../utiles/database')

class User extends Model {}

User.init({
    id: {
        type: DataTypes.BIGINT,
        allowNull: false,
        unsigned: true,
        autoIncrement: true,
        primaryKey: true
    },
    full_name: {
        type: DataTypes.STRING,
        allowNull: false,
    },
    switch_status:{
        type: DataTypes.INTEGER,
        allowNull: false,
    },
    age:{
        type: DataTypes.STRING,
        allowNull: false,
    },
    phone_number:{
        type: DataTypes.STRING,
        allowNull: false,
    },
    country:{
        type: DataTypes.STRING,
        allowNull: false,
    },
    date_of_birth:{
        type: DataTypes.DATE,
        allowNull: false,
    },
    description:{
        type: DataTypes.STRING,
        allowNull: false,
    },
    diseases:{
        type: DataTypes.STRING,
        allowNull: false,
    },
    device_token:{
        type: DataTypes.TEXT,
        allowNull: false,
    },
    type:{
        type: DataTypes.STRING,
        allowNull: false,
    } ,
    consultant_type:{
        type: DataTypes.STRING,
        allowNull: false,
    },country_code:{
        type: DataTypes.STRING,
        allowNull: false,
    },

}, {sequelize, createdAt: "created_at", updatedAt: "updated_at", tableName:"users"});

module.exports = User;

