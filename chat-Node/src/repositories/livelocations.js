const UserLiveLocation = require("../models/users_live_locations")
const {calculateDistanceInMeters} = require("../utiles/helper")


exports.getLocationByUser = async userId => {
   return await UserLiveLocation.findOne({where: {user_id: userId}})
}

exports.createLocation = async (latitude, longitude, userId) => {
    const location = new UserLiveLocation()
    location.lat = latitude;
    location.lng = longitude;
    location.available = true;
    location.user_id = userId;
    await location.save()
    return location
}


exports.updateCoordinates = async (currentLatitude, currentLongitude, userLiveLocation) => {
    let distanceInMeters = calculateDistanceInMeters(currentLatitude, currentLongitude, userLiveLocation.lat, userLiveLocation.lng)

    if(distanceInMeters >= 1){
        userLiveLocation.lat = currentLatitude;
        userLiveLocation.lng = currentLongitude;
        await userLiveLocation.save()
        return userLiveLocation
    }
    return false
}

exports.updateAvalible = async (available, userLiveLocation) => {
    userLiveLocation.available = available
    userLiveLocation.save()
}