const {Sequelize, Model, DataTypes} = require('sequelize')

require('./environment')


const sequelize = new Sequelize(process.env.DB_NAME,
                                process.env.DB_USERNAME,
                                process.env.DB_PASSWORD,
                                {dialect:'mysql', host: process.env.DB_HOST, freezeTableName: true,port: process.env.DB_PORT})

module.exports = {sequelize, Model, DataTypes};
