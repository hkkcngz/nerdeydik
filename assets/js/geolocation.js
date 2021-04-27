// Upload Profile Photo
var loadFile = function(event, targetItem) {
  var output = document.getElementById(targetItem);
  output.src = URL.createObjectURL(event.target.files[0]);
  output.onload = function() {
    URL.revokeObjectURL(output.src) // free memory
  }
};

// <!-- GEOLOCATION -->

// KONUM HESABI KM
function deg2rad(mDeg) {
    // Açıları dereceden radyana dönüştürme
    return mDeg* (Math.PI/180)
}
function getDistanceFromLatLongInKm(originLat, originLong, destinationLat, destinationLong) {

    var Radius = 6371; // dünya yarıçapı km
    var dLat = deg2rad(destinationLat-originLat);
    var dLong = deg2rad(destinationLong-originLong);
    var a =
    Math.sin(dLat/2) * Math.sin(dLat/2) +
    Math.cos(deg2rad(originLat)) * Math.cos(deg2rad(destinationLat)) * Math.sin(dLong/2) * Math.sin(dLong/2);
    var c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));
    var result = Radius * c;// KM cinsinden mesafe
    return result;
}


// Gereken Fonksiyonlar
var x = document.getElementById("infoLocation");

function getLocation() {
  if (navigator.geolocation) {
    navigator.geolocation.watchPosition(showPosition, showError);

  } else {
    x.innerHTML = "Geolocation is not supported by this browser.";
  }
}

function showPosition(position) {
   
  let myLat  = position.coords.latitude;
  let myLong = position.coords.longitude;
  
  var inputMyLat  = document.getElementById("inputMyLat");
  var inputMyLong = document.getElementById("inputMyLong");
  inputMyLat.value  = myLat;
  inputMyLong.value = myLong;
 
}

function showError(error) {
  switch (error.code) {
    case error.PERMISSION_DENIED:
      x.innerHTML = "Konum bulmaya izin vermelisiniz.";
      break;
    case error.POSITION_UNAVAILABLE:
      x.innerHTML = "Yer bilgisine ulaşılamıyor.";
      break;
    case error.TIMEOUT:
      x.innerHTML = "Kullanıcı yer bilgisi zaman aşımına uğradı.";
      break;
    case error.UNKNOWN_ERROR:
      x.innerHTML = "Bilinmeyen bir nedenden hataya uğradı.";
      break;
  }
}


jQuery(function ($) {
  // Konum Checked ?
  $("#checkLocation").on("click", function () {
      // Konum Bilgisi
      getLocation();
  });
});