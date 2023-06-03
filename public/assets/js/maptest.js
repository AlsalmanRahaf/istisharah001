// // This example requires the Drawing library. Include the libraries=drawing
// // parameter when you first load the API. For example:
// // <script src="https://maps.googleapis.com/maps/api/js?key=YOUR_API_KEY&libraries=drawing">
// function initMap() {
//     const map = new google.maps.Map(document.getElementById("map"), {
//       center: { lat: -34.397, lng: 150.644 },
//       zoom: 8,
//     });
//     const drawingManager = new google.maps.drawing.DrawingManager({
//       drawingMode: google.maps.drawing.OverlayType.MARKER,
//       drawingControl: true,
//       drawingControlOptions: {
//         position: google.maps.ControlPosition.TOP_CENTER,
//         drawingModes: [
//           google.maps.drawing.OverlayType.MARKER,
//           google.maps.drawing.OverlayType.CIRCLE,
//           google.maps.drawing.OverlayType.POLYGON,
//           google.maps.drawing.OverlayType.POLYLINE,
//           google.maps.drawing.OverlayType.RECTANGLE,
//         ],
//       },
//       markerOptions: {
//         icon: "https://developers.google.com/maps/documentation/javascript/examples/full/images/beachflag.png",
//       },
//       circleOptions: {
//         fillColor: "#ffff00",
//         fillOpacity: 1,
//         strokeWeight: 5,
//         clickable: false,
//         editable: true,
//         zIndex: 1,
//       },
//     });
    

//     var polygons = [];
//     google.maps.event.addDomListener(drawingManager, 'polygoncomplete', function(polygon) {
//         polygons.push(polygon);
//         // console.log(polygon);
//         var polygonBounds = polygon.getPath();
//         var coordinates = [];

//         for(var i = 0 ; i < polygonBounds.length ; i++)
//         {
//             coordinates.push(polygonBounds.getAt(i).lat(), polygonBounds.getAt(i).lng());
//         } 

//         console.log(coordinates);

//     });
    
//     drawingManager.setMap(map);

//     // google.maps.event.addListener(drawingManager, 'circlecomplete', function(circle) {
//     //     var radius = circle.getRadius();
//     //     console.log (circle.getRadius());

//     //   });
      
//   }