function adminLogin(event) {

    event.preventDefault();

    let username = document.getElementById("username").value;
    let password = document.getElementById("password").value;

    if (username == "admin" && password == "1234") {

        window.location.href = "admin.html";

    } else {

        document.getElementById("loginMessage").innerHTML =
            "Invalid username or password";

    }
}
function showSection(sectionName) {

    let sections = document.getElementsByClassName("section");

    for (let i = 0; i < sections.length; i++) {
        sections[i].style.display = "none";
    }

    document.getElementById(sectionName).style.display = "block";
}


function verifyUser(statusId) {

    document.getElementById(statusId).innerHTML = "Verified";

    alert("User verified successfully.");
}


function approveFund(statusId) {

    document.getElementById(statusId).innerHTML = "Approved";

    alert("Fund request approved.");
}


function rejectFund(statusId) {

    document.getElementById(statusId).innerHTML = "Rejected";

    alert("Fund request rejected.");
}


function deleteRow(button) {

    let row = button.parentElement.parentElement;

    row.remove();

    alert("Record deleted.");
}


function addNotification(event) {

    event.preventDefault();

    let title = document.getElementById("notificationTitle").value;
    let message = document.getElementById("notificationMessage").value;

    let table = document.getElementById("notificationTable");

    let row = table.insertRow();

    let cell1 = row.insertCell(0);
    let cell2 = row.insertCell(1);
    let cell3 = row.insertCell(2);

    cell1.innerHTML = title;
    cell2.innerHTML = message;

    cell3.innerHTML =
        '<button onclick="deleteRow(this)">Delete</button>';

    document.getElementById("notificationTitle").value = "";
    document.getElementById("notificationMessage").value = "";

    alert("Notification added.");
}


function addRecord(event) {

    event.preventDefault();

    let name = document.getElementById("recordName").value;
    let type = document.getElementById("recordType").value;
    let phone = document.getElementById("recordPhone").value;

    let table = document.getElementById("recordTable");

    let row = table.insertRow();

    let cell1 = row.insertCell(0);
    let cell2 = row.insertCell(1);
    let cell3 = row.insertCell(2);
    let cell4 = row.insertCell(3);

    cell1.innerHTML = name;
    cell2.innerHTML = type;
    cell3.innerHTML = phone;

    cell4.innerHTML =
        '<button onclick="editRecord(this)">Edit</button>' +
        '<button onclick="deleteRow(this)">Delete</button>';

    document.getElementById("recordName").value = "";
    document.getElementById("recordPhone").value = "";

    alert("Record added.");
}


function editRecord(button) {

    let row = button.parentElement.parentElement;

    let name = prompt("Enter new name:", row.cells[0].innerHTML);

    if (name != null) {
        row.cells[0].innerHTML = name;
    }

    let phone = prompt("Enter new phone:", row.cells[2].innerHTML);

    if (phone != null) {
        row.cells[2].innerHTML = phone;
    }

    alert("Record updated.");
}


showSection("dashboard");