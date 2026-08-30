function openPage(page) {
    window.location.href = page;
}


function loginAdmin(event) {

    event.preventDefault();

    var email = document.getElementById("loginEmail").value;
    var password = document.getElementById("loginPassword").value;

    if (email == "" || password == "") {

        alert("Please enter email and password");

        return;
    }

    alert("Login successful");

    window.location.href = "admin_dashboard.html";
}


function registerAdmin(event) {

    event.preventDefault();

    var name = document.getElementById("name").value;
    var email = document.getElementById("email").value;
    var phone = document.getElementById("phone").value;
    var password = document.getElementById("password").value;
    var confirmPassword = document.getElementById("confirmPassword").value;

    if (
        name == "" ||
        email == "" ||
        phone == "" ||
        password == "" ||
        confirmPassword == ""
    ) {

        alert("Please fill all fields");

        return;
    }

    if (password != confirmPassword) {

        alert("Password does not match");

        return;
    }

    alert("Admin registration completed successfully");

    window.location.href = "admin_login.html";
}


function logoutAdmin() {

    var result =
        confirm("Do you want to logout?");

    if (result) {

        window.location.href =
            "admin_login.html";
    }
}


function addOrganization(event) {

    event.preventDefault();

    var form = event.target;

    var organizationName =
        form.elements["organization_name"].value;

    var email =
        form.elements["email"].value;

    var phone =
        form.elements["phone"].value;

    var address =
        form.elements["address"].value;

    if (
        organizationName == "" ||
        email == "" ||
        phone == "" ||
        address == ""
    ) {

        alert("Please fill all fields");

        return;
    }

    alert("Organization added successfully");

    window.location.href =
        "organizations.html";
}


function editOrganization() {

    window.location.href =
        "edit_organization.html";
}


function updateOrganization(event) {

    event.preventDefault();

    alert("Organization updated successfully");

    window.location.href =
        "organizations.html";
}


function deleteOrganization(button) {

    var result =
        confirm(
            "Do you want to delete this organization?"
        );

    if (result) {

        button.parentElement.parentElement.remove();

        alert(
            "Organization deleted successfully"
        );
    }
}


function addProvider(event) {

    event.preventDefault();

    var form = event.target;

    var name =
        form.elements["name"].value;

    var email =
        form.elements["email"].value;

    var phone =
        form.elements["phone"].value;

    var location =
        form.elements["location"].value;

    if (
        name == "" ||
        email == "" ||
        phone == "" ||
        location == ""
    ) {

        alert("Please fill all fields");

        return;
    }

    alert(
        "Service provider added successfully"
    );

    window.location.href =
        "service_providers.html";
}


function editProvider() {

    window.location.href =
        "edit_service_provider.html";
}


function updateProvider(event) {

    event.preventDefault();

    alert(
        "Service provider updated successfully"
    );

    window.location.href =
        "service_providers.html";
}


function deleteProvider(button) {

    var result =
        confirm(
            "Do you want to delete this service provider?"
        );

    if (result) {

        button.parentElement.parentElement.remove();

        alert(
            "Service provider deleted successfully"
        );
    }
}


function sendNotification(event) {

    event.preventDefault();

    var form = event.target;

    var subject =
        form.elements["subject"].value;

    var message =
        form.elements["message"].value;

    if (subject == "" || message == "") {

        alert(
            "Please enter subject and message"
        );

        return;
    }

    alert(
        "Notification sent successfully"
    );

    window.location.href =
        "notifications.html";
}


function approveFund(button) {

    var result =
        confirm(
            "Do you want to approve this fund request?"
        );

    if (result) {

        var row =
            button.parentElement.parentElement;

        var status =
            row.querySelector(".status");

        status.innerText =
            "Approved";

        status.className =
            "status active-status";

        button.remove();

        alert(
            "Fund request approved successfully"
        );
    }
}


function rejectFund(button) {

    var result =
        confirm(
            "Do you want to reject this fund request?"
        );

    if (result) {

        var row =
            button.parentElement.parentElement;

        var status =
            row.querySelector(".status");

        status.innerText =
            "Rejected";

        status.className =
            "status rejected-status";

        button.remove();

        alert(
            "Fund request rejected"
        );
    }
}


function viewFund() {

    alert(
        "Fund details viewed"
    );
}


function updateProfile(event) {

    event.preventDefault();

    alert(
        "Profile updated successfully"
    );
}