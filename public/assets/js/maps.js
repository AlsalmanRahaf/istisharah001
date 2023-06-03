let map,marker;
let inputLat = document.querySelector("#lat");
let inputLng = document.querySelector("#lng");
let address = document.querySelector("#address");

function initMap() {
    let latVal = parseFloat(inputLat.value);
    let lngVal = parseFloat(inputLng.value);
    if(latVal == 0 && lngVal == 0 || ( isNaN(latVal) && isNaN(lngVal))){
        latVal = 31.95885924211649;
        lngVal = 35.96497855319606;
    }
    //console.log(latVal,lngVal);

    let defaultPosition = { lat: latVal, lng: lngVal };
    map = new google.maps.Map(document.getElementById("map"), {
        center: defaultPosition,
        zoom: 16,
    });
    marker = new google.maps.Marker({
        position: defaultPosition,
        map: map,
        draggable: true
    });
    
  //  console.log("s");
    // var latlng = new google.maps.LatLng(31.97300389879309, 35.90963322669277);
    // var geocoder = new google.maps.Geocoder();
    // geocoder.geocode({ 'latLng': latlng }, function (results, status) {
    //     if (status !== google.maps.GeocoderStatus.OK) {
    //         alert(status);
    //     }
    //     if (status == google.maps.GeocoderStatus.OK) {
    //         console.log(results);
    //     }
    // });

    // latVal = 31.97616180098815;
    // lngVal = 35.8616042343152;
    // var latLngA = new google.maps.LatLng(31.950384643223156, 35.992123242854895);
    // var latLngB = new google.maps.LatLng(31.976275196092313, 35.862091082440166);

    // var service = new google.maps.DistanceMatrixService();
    // service.getDistanceMatrix(
    //   {
    //     origins: [latLngA],
    //     destinations: [latLngB],
    //     travelMode: 'DRIVING',
    //   }, callback);
    
    // function callback(response, status) {
    //   console.log(response);
    // }




    map.addListener("center_changed",changeMarkerPosition);
    map.addListener("zoom_change",changeMarkerPosition);
    marker.addListener("dragend",setPositionToInputs)
}

function changeMarkerPosition(){
    let latlng = map.getCenter();
    marker.setPosition(latlng);
    setPositionToInputs();
    var geocoder = new google.maps.Geocoder();
    geocoder.geocode({ 'latLng': latlng }, function (results, status) {
        if (status !== google.maps.GeocoderStatus.OK) {
            //alert(status);
        }
        if (status == google.maps.GeocoderStatus.OK) {
            let com1 = results[0].address_components[1];
            let com2 = results[0].address_components[2];
            if(com1.types[1] !== undefined && com1.types[1] =="sublocality"){
                address.value = com1.long_name;

            }else if(com2.types[1] !== undefined && com2.types[1] =="sublocality"){
                address.value = com2.long_name;
            }else{
                console.log("null");
            }
        }
    });
}

function setPositionToInputs(){
    inputLat.value = marker.getPosition().lat();
    inputLng.value = marker.getPosition().lng();
}



function getAddressByReverseGeocoding(lat,lng){
    var latlng = new google.maps.LatLng(lat, lng);
    var geocoder = new google.maps.Geocoder();
    geocoder.geocode({ 'latLng': latlng }, function (results, status) {
        if (status !== google.maps.GeocoderStatus.OK) {
            alert(status);
        }
        if (status == google.maps.GeocoderStatus.OK) {
            console.log(results);
           // var address = (results[0].formatted_address);
        }
    });
}



