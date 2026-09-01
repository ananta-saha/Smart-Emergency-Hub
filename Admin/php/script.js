function goTo(page) {
    window.location.href = page;
}

function deleteOrganization(id) {

    var answer = confirm("Are you sure you want to delete this organization?");

    if (answer) {
        alert("Organization deleted successfully.");
    }
}

function deleteProvider(id) {

    var answer = confirm("Are you sure you want to delete this service provider?");

    if (answer) {
        alert("Service provider deleted successfully.");
    }
}

function approveFund(id) {

    var answer = confirm("Do you want to approve this fund request?");

    if (answer) {
        alert("Fund request approved successfully.");
    }
}

function rejectFund(id) {

    var answer = confirm("Do you want to reject this fund request?");

    if (answer) {
        alert("Fund request rejected.");
    }
}

function logout() {

    var answer = confirm("Do you want to logout?");

    if (answer) {
        goTo("admin_login.html");
    }
}

function showMessage(message) {
    alert(message);
}