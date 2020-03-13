$(function() {
    
});

$('.mygeolocation').click(function(){
    //e.preventDefault();
    //alert("mygeolocation");
   
     if (navigator.geolocation) {
        //navigator.geolocation.watchPosition(showPosition);
        navigator.geolocation.getCurrentPosition(showPosition);
      } else { 
        x.innerHTML = "Geolocation is not supported by this browser.";
      }
});

function showPosition(position) {
   // x.innerHTML="Latitude: " + position.coords.latitude + "<br>Longitude: " + position.coords.longitude;
    
    $uri='/files/ajax/geolocation.php';    
    $.post($uri, {latitude:position.coords.latitude,longitude: position.coords.longitude}, function(data){
        
        //console.log(data.status);
        if (data.status) {
            window.location.replace("/trainers/?status="+data.status);
//           for (i = 0; i < myObj.cars.length; i++) {
//              x += myObj.cars[i];
//            } 
//            var trainers=data.items;
//            //document.getElementById("demo").innerHTML = data.items.length;
//            
//            for (var trainer in trainers) {
//              if (trainers.hasOwnProperty(trainer)) {
//                var val = trainers[trainer];
//                var nodeLI = document.createElement("LI");                 // Create a <li> node
//                var nodeSPAN = document.createElement("SPAN");
//                var nodeA = document.createElement('A');
//                //var newlink.setAttribute('class', 'signature');
//                nodeA.setAttribute('href', '/user/'+val.username);
//                var textnode = document.createTextNode(val.username);         // Create a text node
//                nodeA.appendChild(textnode); 
//                nodeSPAN.appendChild(nodeA);  
//                nodeLI.appendChild(nodeSPAN);                              // Append the text to <li>
//                document.getElementById("trainers").appendChild(nodeLI);     // Append <li> to <ul> with id="myList"
//                //document.getElementById("trainers").innerHTML += val.username + "<br>";
//              }
//            }
            //console.log(data.facebook);
            //console.log(data.fbusername);
            //window.location.replace("?facebook="+data.facebook+"&fbusername="+data.fbusername);
            //$('#demo').innerHTML="Latitude: " + position.coords.latitude + "<br>Longitude: " + position.coords.longitude;
            
        }
        else{
            //console.log(data.status);
        }
    },'json');

}


//$('.mygeolocation').on('click', function(e) {
//    e.preventDefault();
//    //alert("geolocation1");
//
//    if (navigator.geolocation) {
//        //navigator.geolocation.watchPosition(showPosition);
//        navigator.geolocation.getCurrentPosition(showPosition);
//      } else { 
//        x.innerHTML = "Geolocation is not supported by this browser.";
//      }
//
//    var $this = $(this);
//    $uri='/files/ajax/geolocation.php';    
//    $.post($uri, {latitude:'20',longitude: '30'}, function(data){
//        console.log(data.status);
//        if (data.status) {
//            //console.log(data.facebook);
//            //console.log(data.fbusername);
//            //window.location.replace("?facebook="+data.facebook+"&fbusername="+data.fbusername);
//        }
//        else{
//            //console.log(data.status);
//        }
//    },'json');
//
//});
