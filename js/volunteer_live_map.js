// volunteer_live_map.js
let map, marker;
const POLL_INTERVAL_MS = 5000;

function initVolunteerMap() {
  map = new google.maps.Map(document.getElementById('map'), {
    zoom: 16,
    center: { lat: 32.0853, lng: 34.7818 }
  });

  marker = new google.maps.Marker({
    map: map,
    title: 'מיקום ההולכת רגל'
  });

  // התחלת poll
  fetchAndUpdate();
  setInterval(fetchAndUpdate, POLL_INTERVAL_MS);
}

function fetchAndUpdate() {
  fetch(`get_walker_location.php`)
    .then(res => res.json())
    .then(data => {
      const statusText = document.getElementById('statusText');
      if (!data || !data.success) {
        statusText.textContent = 'אין מיקום זמין כרגע';
        return;
      }
      const lat = parseFloat(data.latitude);
      const lng = parseFloat(data.longitude);
      marker.setPosition({ lat, lng });
      map.setCenter({ lat, lng });
      statusText.textContent = 'מעדכן... ' + new Date(data.updated_at).toLocaleTimeString();
    })
    .catch(err => {
      console.error('Error fetching location', err);
      document.getElementById('statusText').textContent = 'שגיאת רשת';
    });
}

// אתחול המפה לאחר טעינה - Google Maps JS קורא ל־initVolunteerMap בעזרת callback
window.addEventListener('load', () => {
  // אם הספריה נטענה כבר (יתעלה בהמשך על callback), נסה לאתחל עם השהייה קלה
  if (typeof google !== 'undefined' && google.maps) {
    // הספרייה נטענה - אתחל
    setTimeout(initVolunteerMap, 500);
  } else {
    // אם הספריה עדיין לא נטענה, היא תאתחל לאחר מכן; כאן נסתמך על טעינה רגילה
    setTimeout(initVolunteerMap, 1000);
  }
});
