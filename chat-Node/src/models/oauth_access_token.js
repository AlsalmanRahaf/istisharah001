const {Model, DataTypes, sequelize} = require('../utiles/database')

class OauthAccessToken extends Model {}

OauthAccessToken.init({
    user_id: {
        type: DataTypes.BIGINT,
        allowNull: false,
        unsigned: true,
    },
    
}, {sequelize, createdAt: "created_at", updatedAt: "updated_at", tableName:"oauth_access_tokens"});

module.exports = OauthAccessToken