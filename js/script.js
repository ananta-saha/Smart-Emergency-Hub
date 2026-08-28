function login() {
    alert("Login will work after database is connected.");
}

function register() {
    alert("Registration will work after database is connected.");
}

function logout() {
    window.location.href = "index.html";
}

function addProvider() {
    alert("Service Provider added successfully!");
}

function editProvider() {
    alert("Provider information can be edited here.");
}

function deleteProvider() {
    if (confirm("Are you sure you want to delete this provider?")) {
        alert("Service Provider deleted!");
    }
}

function addService() {
    alert("Emergency Service added successfully!");
}

function editService() {
    alert("Service information can be edited here.");
}

function deleteService() {
    if (confirm("Are you sure you want to delete this service?")) {
        alert("Emergency Service deleted!");
    }
}

function acceptRequest() {
    alert("Emergency Request accepted!");
}

function rejectRequest() {
    if (confirm("Are you sure you want to reject this request?")) {
        alert("Emergency Request rejected!");
    }
}

function viewRequest() {
    alert("Emergency Request details.");
}

function createFund() {
    alert("Fund Campaign created successfully!");
}

function manageFund() {
    alert("Fund Campaign management opened.");
}

function viewFund() {

    alert("Fund Campaign details.");

}

function updateProfile() {

    alert("Organization Profile updated successfully!");

}