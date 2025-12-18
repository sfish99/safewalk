// walker_share_location.js
let map, marker;
let watchId = null;
let sendIntervalId = null;
let lastPosition = null;
const SEND_INTERVAL_MS = 5000; // שליחת מיקום כל 5 שניות

function initMapInitial() {
  map = new google.maps.Map(document.getElementById('map'), {
    zoom: 16,
    center: { lat: 32.0853, lng: 34.7818 }, // ברירת מחדל
  });
  marker = new google.maps.Marker({
    map: map,
    title: "המיקום שלך",
  });
}

function startSharing() {
  if (!navigator.geolocation) {
    alert('דפדפן זה אינו תומך ב-GPS');
    return;
  }

  // watchPosition עדכוני מיקום רציפים
  watchId = navigator.geolocation.watchPosition(
    (pos) => {
      lastPosition = pos.coords;
      const lat = pos.coords.latitude;
      const lng = pos.coords.longitude;
      marker.setPosition({ lat, lng });
      map.setCenter({ lat, lng });
    },
    (err) => {
      console.error('Geolocation error', err);
      alert('לא ניתן לקבל מיקום - ודא שהמכשיר מאפשר שיתוף מיקום');
    },
    { enableHighAccuracy: true, maximumAge: 2000 }
  );

  // שליחת מיקום כל X שניות לפי lastPosition
  sendIntervalId = setInterval(() => {
    if (!lastPosition) return;
    sendLocationToServer(lastPosition.latitude, lastPosition.longitude);
  }, SEND_INTERVAL_MS);

  document.getElementById('startBtn').disabled = true;
  document.getElementById('stopBtn').disabled = false;
}

function stopSharing() {
  if (watchId !== null) {
    navigator.geolocation.clearWatch(watchId);
    watchId = null;
  }
  if (sendIntervalId !== null) {
    clearInterval(sendIntervalId);
    sendIntervalId = null;
  }
  document.getElementById('startBtn').disabled = false;
  document.getElementById('stopBtn').disabled = true;
}

function sendLocationToServer(lat, lng) {
  fetch('walker_send_location.php', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({
      latitude: lat,
      longitude: lng
    })
  })
  .then(res => res.json())
  .then(data => {
    if (!data?.success) {
      console.warn('Send location failed', data);
    }
  })
  .catch(err => console.error('Fetch send location error', err));
}


window.addEventListener('load', () => {
  initMapInitial();
  document.getElementById('startBtn').addEventListener('click', startSharing);
  document.getElementById('stopBtn').addEventListener('click', stopSharing);
});
