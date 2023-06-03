const UserLiveLocation = require("../models/users_live_locations")
const { calculateDistanceInMeters } = require("../utiles/helper")


exports.add = (socket, liveLocations) => {
    
    socket.on('add-location',async (data) => {
        let userLocation =  liveLocations[socket.request.user.id] 
        console.log('emmitting')
        if(userLocation === undefined || userLocation === null) {
            const location = new UserLiveLocation()
            location.lat = data.location.latitude;
            location.lng = data.location.longitude;
            location.available = true;
            location.user_id = socket.request.user.id;
            await location.save()
            liveLocations[socket.request.user.id] = location
            console.log("saving location")

        }else{
            let distanceInMeters = calculateDistanceInMeters(data.location.latitude, data.location.longitude, userLocation.lat, userLocation.lng)
            if(distanceInMeters > 3){
                userLocation.lat = data.location.latitude;
                userLocation.lng = data.location.longitude;
                await userLocation.save()
                console.log(distanceInMeters)
            }else{
                console.log("current location")
            }
        }

    })
}