function findServices() {
    var location = document.getElementById("location").value;
    var type = document.getElementById("serviceType").value;
    var result = document.getElementById("serviceResults");

    if (location == "") {
        alert("Please enter location");
        return;
    }
    if (type == "") {
        alert("Please select a service");
        return;
    }
    result.innerHTML =
        "<div class='service-card'>" +
        "<h3>" + type.toUpperCase() + "</h3>" +
        "<p>Location: " + location + "</p>" +
        "<p>Status: <strong>Available</strong></p>" +
        "<p>Distance: 1.5 km</p>" +
        "<button onclick='sendRequest()'>Request Service</button>" +
        "</div>";
}
function sendRequest() {
    window.location.href = "emergency-request.html";
}