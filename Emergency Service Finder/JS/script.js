document.addEventListener("DOMContentLoaded", function () {

    const loginForm = document.getElementById("providerLoginForm");

    if (loginForm) {

        loginForm.addEventListener("submit", function (event) {

            event.preventDefault();

            const email = document.getElementById("email").value;
            const password = document.getElementById("password").value;

            const message = document.getElementById("loginMessage");

            const passwordPattern =
                /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/;

            if (email === "" || password === "") {

                message.innerText = "Please fill in all fields.";

            }
            else if (!passwordPattern.test(password)) {

                message.innerText =
                    "Password must be at least 8 characters and contain uppercase, lowercase, number and symbol.";

            }
            else {

                message.innerText = "";

                window.location.href = "Provider_Dashboard.html";

            }

        });

    }

});

const availabilityForm =
    document.getElementById("availabilityForm");

if (availabilityForm) {

    const fullDay =
        document.getElementById("fullDay");

    const startTime =
        document.getElementById("startTime");

    const endTime =
        document.getElementById("endTime");

    const availabilityMessage =
        document.getElementById("availabilityMessage");

    const summaryStatus =
        document.getElementById("summaryStatus");

    const summaryTime =
        document.getElementById("summaryTime");


    fullDay.addEventListener("change", function () {

        if (fullDay.checked) {

            startTime.disabled = true;
            endTime.disabled = true;

            startTime.value = "";
            endTime.value = "";

        } else {

            startTime.disabled = false;
            endTime.disabled = false;

        }

    });


    availabilityForm.addEventListener(
        "submit",
        function (event) {

            event.preventDefault();

            const status =
                document.querySelector(
                    'input[name="status"]:checked'
                ).value;


            if (
                !fullDay.checked &&
                (
                    startTime.value === "" ||
                    endTime.value === ""
                )
            ) {

                availabilityMessage.innerText =
                    "Please select start time and end time.";

                availabilityMessage.style.color =
                    "red";

                return;
            }


            summaryStatus.innerText = status;


            if (fullDay.checked) {

                summaryTime.innerText =
                    "24 Hours";

            } else {

                summaryTime.innerText =
                    startTime.value +
                    " - " +
                    endTime.value;

            }


            availabilityMessage.innerText =
                "Availability updated successfully.";

            availabilityMessage.style.color =
                "green";

        }
    );

}

const vehicleForm =
    document.getElementById("vehicleForm");

if (vehicleForm) {

    vehicleForm.addEventListener(
        "submit",
        function (event) {

            event.preventDefault();

            const vehicleType =
                document.getElementById("vehicleType").value;

            const totalVehicles =
                parseInt(
                    document.getElementById("totalVehicles").value
                );

            const availableVehicles =
                parseInt(
                    document.getElementById("availableVehicles").value
                );

            const message =
                document.getElementById("vehicleMessage");


            if (
                vehicleType === "" ||
                isNaN(totalVehicles) ||
                isNaN(availableVehicles)
            ) {

                message.innerText =
                    "Please fill in all vehicle information.";

                message.style.color = "red";

                return;
            }


            if (totalVehicles < 1) {

                message.innerText =
                    "Total vehicles must be at least 1.";

                message.style.color = "red";

                return;
            }


            if (availableVehicles < 0) {

                message.innerText =
                    "Available vehicles cannot be negative.";

                message.style.color = "red";

                return;
            }


            if (availableVehicles > totalVehicles) {

                message.innerText =
                    "Available vehicles cannot be greater than total vehicles.";

                message.style.color = "red";

                return;
            }


            const table =
                document
                    .getElementById("vehicleTable")
                    .getElementsByTagName("tbody")[0];


            const newRow =
                table.insertRow();


            newRow.innerHTML = `
                <td>${vehicleType}</td>
                <td>${totalVehicles}</td>
                <td>${availableVehicles}</td>
                <td>
                    <button
                        class="delete-btn"
                        onclick="deleteVehicle(this)"
                    >
                        Delete
                    </button>
                </td>
            `;


            message.innerText =
                "Vehicle added successfully.";

            message.style.color = "green";


            vehicleForm.reset();

        }
    );

}


function deleteVehicle(button) {

    const confirmation =
        confirm("Are you sure you want to delete this vehicle?");

    if (confirmation) {

        const row =
            button.parentElement.parentElement;

        row.remove();

    }

}

const areaForm =
    document.getElementById("areaForm");

if (areaForm) {

    areaForm.addEventListener(
        "submit",
        function (event) {

            event.preventDefault();

            const baseArea =
                document.getElementById("baseArea").value;

            const serviceRange =
                document.getElementById("serviceRange").value;

            const message =
                document.getElementById("areaMessage");

            const selectedAreas =
                document.querySelectorAll(
                    'input[name="coveredArea"]:checked'
                );


            if (baseArea === "") {

                message.innerText =
                    "Please select a base area.";

                message.style.color = "red";

                return;
            }


            if (serviceRange === "" || serviceRange <= 0) {

                message.innerText =
                    "Please enter a valid service range.";

                message.style.color = "red";

                return;
            }


            if (selectedAreas.length === 0) {

                message.innerText =
                    "Please select at least one covered area.";

                message.style.color = "red";

                return;
            }


            let coveredAreaNames = [];


            selectedAreas.forEach(
                function (area) {

                    coveredAreaNames.push(
                        area.value
                    );

                }
            );


            document.getElementById(
                "summaryBaseArea"
            ).innerText = baseArea;


            document.getElementById(
                "summaryRange"
            ).innerText =
                serviceRange + " KM";


            document.getElementById(
                "summaryCoveredAreas"
            ).innerText =
                coveredAreaNames.join(", ");


            message.innerText =
                "Service area updated successfully.";

            message.style.color = "green";

        }
    );

}

function acceptRequest(button) {

    const confirmation =
        confirm("Do you want to accept this request?");

    if (!confirmation) {
        return;
    }

    const row =
        button.closest("tr");

    const status =
        row.querySelector(".request-status");

    const actionCell =
        row.querySelector(".action-cell");


    status.innerText = "Accepted";

    status.className =
        "request-status status-accepted";

    row.setAttribute(
        "data-status",
        "Accepted"
    );


    actionCell.innerHTML = `
        <button
            class="way-btn"
            onclick="markOnTheWay(this)"
        >
            On The Way
        </button>
    `;


    showRequestMessage(
        "Request accepted successfully."
    );

}



function rejectRequest(button) {

    const confirmation =
        confirm("Do you want to reject this request?");

    if (!confirmation) {
        return;
    }


    const row =
        button.closest("tr");

    const status =
        row.querySelector(".request-status");

    const actionCell =
        row.querySelector(".action-cell");


    status.innerText =
        "Rejected";

    status.className =
        "request-status status-rejected";


    row.setAttribute(
        "data-status",
        "Rejected"
    );


    actionCell.innerHTML =
        "No Action";


    showRequestMessage(
        "Request rejected."
    );

}



function markOnTheWay(button) {

    const row =
        button.closest("tr");

    const status =
        row.querySelector(".request-status");

    const actionCell =
        row.querySelector(".action-cell");


    status.innerText =
        "On The Way";

    status.className =
        "request-status status-way";


    row.setAttribute(
        "data-status",
        "On The Way"
    );


    actionCell.innerHTML = `
        <button
            class="complete-btn"
            onclick="completeRequest(this)"
        >
            Complete
        </button>
    `;


    showRequestMessage(
        "Request status changed to On The Way."
    );

}



function completeRequest(button) {

    const confirmation =
        confirm("Mark this request as completed?");

    if (!confirmation) {
        return;
    }


    const row =
        button.closest("tr");

    const status =
        row.querySelector(".request-status");

    const actionCell =
        row.querySelector(".action-cell");


    status.innerText =
        "Completed";

    status.className =
        "request-status status-completed";


    row.setAttribute(
        "data-status",
        "Completed"
    );


    actionCell.innerHTML =
        "No Action";


    showRequestMessage(
        "Emergency request completed successfully."
    );

}



function showRequestMessage(message) {

    const requestMessage =
        document.getElementById("requestMessage");

    if (requestMessage) {

        requestMessage.innerText =
            message;

        requestMessage.style.color =
            "green";

    }

}



/* Request Filter */

const requestFilter =
    document.getElementById("requestFilter");


if (requestFilter) {

    requestFilter.addEventListener(
        "change",
        function () {

            const selectedStatus =
                requestFilter.value;


            const rows =
                document.querySelectorAll(
                    "#requestTable tbody tr"
                );


            rows.forEach(
                function (row) {

                    const rowStatus =
                        row.getAttribute(
                            "data-status"
                        );


                    if (
                        selectedStatus === "All" ||
                        rowStatus === selectedStatus
                    ) {

                        row.style.display = "";

                    }

                    else {

                        row.style.display = "none";

                    }

                }
            );

        }
    );

}


const profileForm =
    document.getElementById("profileForm");

if (profileForm) {

    profileForm.addEventListener(
        "submit",
        function (event) {

            event.preventDefault();


            const name =
                document.getElementById(
                    "providerName"
                ).value.trim();


            const email =
                document.getElementById(
                    "providerEmail"
                ).value.trim();


            const phone =
                document.getElementById(
                    "providerPhone"
                ).value.trim();


            const serviceType =
                document.getElementById(
                    "providerServiceType"
                ).value;


            const address =
                document.getElementById(
                    "providerAddress"
                ).value.trim();


            const message =
                document.getElementById(
                    "profileMessage"
                );


            if (
                name === "" ||
                email === "" ||
                phone === "" ||
                address === ""
            ) {

                message.innerText =
                    "Please fill in all profile information.";

                message.style.color =
                    "red";

                return;
            }


            const emailPattern =
                /^[^\s@]+@[^\s@]+\.[^\s@]+$/;


            if (!emailPattern.test(email)) {

                message.innerText =
                    "Please enter a valid email address.";

                message.style.color =
                    "red";

                return;
            }


            const phonePattern =
                /^01[3-9][0-9]{8}$/;


            if (!phonePattern.test(phone)) {

                message.innerText =
                    "Please enter a valid 11-digit Bangladeshi phone number.";

                message.style.color =
                    "red";

                return;
            }


            document.getElementById(
                "summaryProviderName"
            ).innerText = name;


            document.getElementById(
                "summaryProviderEmail"
            ).innerText = email;


            document.getElementById(
                "summaryProviderPhone"
            ).innerText = phone;


            document.getElementById(
                "summaryProviderService"
            ).innerText = serviceType;


            document.getElementById(
                "summaryProviderAddress"
            ).innerText = address;


            message.innerText =
                "Profile updated successfully.";

            message.style.color =
                "green";

        }
    );

}