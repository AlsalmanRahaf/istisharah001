const {Model, DataTypes, sequelize} = require('../utiles/database')

class CountryCode extends Model {}

CountryCode.init({
    id: {
        type: DataTypes.BIGINT,
        allowNull: false,
        unsigned: true,
        autoIncrement: true,
        primaryKey: true
    },
    ContryName: {
        type: DataTypes.STRING,
        allowNull: false,
    },
    CountryCode: {
        type: DataTypes.STRING,
        allowNull: false,
    },



}, {sequelize, createdAt: "created_at", updatedAt: "updated_at", tableName:"country_codes"});

module.exports = countryCode;

