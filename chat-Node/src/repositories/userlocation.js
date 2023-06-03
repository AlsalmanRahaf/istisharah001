const UserLocation = require("../models/user_location")
const {Model, DataTypes, sequelize} = require('../utiles/database')

exports.getNearestLocation = async (userId, latitude, longitude, meters) => {
    const sql = `SELECT users_locations.*,
                        ((ACOS(SIN(:latitude * PI() / 180) * SIN(lat * PI() / 180) + 
                        COS(:latitude  * PI() / 180) * COS(lat * PI() / 180) * COS((:longitude  - lng) * 
                        PI() / 180)) * 180 / PI()) * 60 * 1.1515) * 1.6 * 1000 AS distance 
                FROM users_locations
                WHERE users_locations.user_id = :userId
                HAVING distance < :meters LIMIT 1`;
    const res = await sequelize.query(sql, {replacements: {latitude, longitude, userId, meters}});
    
    return res[0][0] !== undefined ?
                                {id:res[0][0].id, 
                                 title:res[0][0].title,
                                 latitude:res[0][0].lat,
                                 longitude:res[0][0].lng} : false;
}