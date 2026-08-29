function showRegister() {

    document.getElementById("loginBox").style.display = "none";

    document.getElementById("registerBox").style.display = "block";
}


function showLogin() {

    document.getElementById("registerBox").style.display = "none";

    document.getElementById("loginBox").style.display = "block";
}


function registerAdmin() {

    let password =
        document.getElementById("password").value;

    let confirmPassword =
        document.getElementById("confirmPassword").value;


    if (password != confirmPassword) {

        document.getElementById("registerMessage").innerHTML =
            "Password does not match.";

        return false;
    }


    return true;
}



function loginAdmin() {

    let username =
        document.getElementById("loginUsername").value;

    let password =
        document.getElementById("loginPassword").value;


    if (username == "" || password == "") {

        document.getElementById("loginMessage").innerHTML =
            "Please enter username and password.";

        return false;
    }


    return true;
}



function verifyUser(statusId) {

    document.getElementById(statusId).innerHTML =
        "Verified";

    alert("Account verified successfully.");
}


function rejectUser(statusId) {

    document.getElementById(statusId).innerHTML =
        "Rejected";

    alert("Account rejected.");
}



function sendNotification(event) {

    event.preventDefault();

    let title =
        document.getElementById("notificationTitle").value;

    let message =
        document.getElementById("notificationMessage").value;

    let table =
        document.getElementById("notificationTable");

    let row = table.insertRow();

    let cell1 = row.insertCell(0);

    let cell2 = row.insertCell(1);

    let cell3 = row.insertCell(2);

    cell1.innerHTML = title;

    cell2.innerHTML = message;

    cell3.innerHTML =
        '<button onclick="deleteNotification(this)">Delete</button>';

    document.getElementById("notificationTitle").value = "";

    document.getElementById("notificationMessage").value = "";

    alert("Notification sent successfully.");
}


function deleteNotification(button) {

    let row =
        button.parentElement.parentElement;

    row.remove();
}



function approveFund(statusId) {

    document.getElementById(statusId).innerHTML =
        "Approved";

    alert("Fund request approved.");
}


function rejectFund(statusId) {

    document.getElementById(statusId).innerHTML =
        "Rejected";

    alert("Fund request rejected.");
}



function addProvider(event) {

    event.preventDefault();

    let name =
        document.getElementById("providerName").value;

    let type =
        document.getElementById("serviceType").value;

    let phone =
        document.getElementById("providerPhone").value;

    let table =
        document.getElementById("providerTable");

    let row = table.insertRow();

    let cell1 = row.insertCell(0);

    let cell2 = row.insertCell(1);

    let cell3 = row.insertCell(2);

    let cell4 = row.insertCell(3);

    cell1.innerHTML = name;

    cell2.innerHTML = type;

    cell3.innerHTML = phone;

    cell4.innerHTML =
        '<button onclick="editProvider(this)">Edit</button>' +
        '<button onclick="deleteProvider(this)">Delete</button>';

    document.getElementById("providerName").value = "";

    document.getElementById("providerPhone").value = "";

    alert("Service provider added.");
}



function editProvider(button) {

    let row =
        button.parentElement.parentElement;

    let newName =
        prompt("Enter provider name:",
        row.cells[0].innerHTML);

    if (newName != null) {

        row.cells[0].innerHTML = newName;
    }


    let newPhone =
        prompt("Enter phone number:",
        row.cells[2].innerHTML);

    if (newPhone != null) {

        row.cells[2].innerHTML = newPhone;
    }
}


function deleteProvider(button) {

    let row =
        button.parentElement.parentElement;

    row.remove();

    alert("Service provider deleted.");
}


function showSection(sectionName) {

    let sections =
        document.getElementsByClassName("section");

    for (let i = 0; i < sections.length; i++) {

        sections[i].style.display = "none";
    }

    document.getElementById(sectionName).style.display =
        "block";
}