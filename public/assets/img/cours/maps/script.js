const test = document.getElementById('test');
const test1 = document.getElementById('test1');
//test.textContent = "123124";

var mymap = L.map('mapid').setView([36.9006, 10.1866], 16);
L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
    maxZoom: 19,
    attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
}).addTo(mymap);

marker1 = L.marker([36.90061, 10.1866]).addTo(mymap);

function testFunction() {
  mymap.closePopup();
  marker1.bindPopup("<b>JOB.TN</b><br>Ariena Soghra, tunis").openPopup();
}
