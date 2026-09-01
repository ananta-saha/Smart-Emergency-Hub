<?php

session_start();

require_once "../../Config/db.php";


// Check provider login
if (!isset($_SESSION["provider_id"])) {

    header("Location: Provider_login.php");
    exit();

}


$provider_id = $_SESSION["provider_id"];


// Check request id and action

if (
    isset($_GET["request_id"]) &&
    isset($_GET["action"])
) {


    $request_id = $_GET["request_id"];
    $action = $_GET["action"];


    // Allowed status

    $status = "";


    if ($action == "accept") {

        $status = "Accepted";

    }

    elseif ($action == "way") {

        $status = "On The Way";

    }

    elseif ($action == "complete") {

        $status = "Completed";

    }

    elseif ($action == "reject") {

        $status = "Rejected";

    }

    else {

        die("Invalid Action");

    }



    /*
        Update emergency request
    */


    $update = mysqli_prepare(
        $conn,
        "
        UPDATE emergency_requests
        SET 
        provider_id = ?,
        status = ?

        WHERE request_id = ?
        "
    );


    mysqli_stmt_bind_param(
        $update,
        "isi",
        $provider_id,
        $status,
        $request_id
    );


    mysqli_stmt_execute($update);



    /*
        Insert status history
    */


    $note = "";


    if ($status == "Accepted") {

        $note = "Provider accepted the emergency request";

    }

    elseif ($status == "On The Way") {

        $note = "Provider is on the way";

    }

    elseif ($status == "Completed") {

        $note = "Emergency request completed";

    }

    elseif ($status == "Rejected") {

        $note = "Provider rejected the emergency request";

    }



    $history = mysqli_prepare(
        $conn,
        "
        INSERT INTO request_status_history
        (
            request_id,
            status,
            updated_by_type,
            updated_by_id,
            note
        )

        VALUES
        (
            ?,
            ?,
            'Provider',
            ?,
            ?
        )
        "
    );



    mysqli_stmt_bind_param(
        $history,
        "isis",
        $request_id,
        $status,
        $provider_id,
        $note
    );



    mysqli_stmt_execute($history);



    mysqli_stmt_close($update);
    mysqli_stmt_close($history);



    header(
        "Location: Provider_Requests.php"
    );

    exit();


}


else {


    header(
        "Location: Provider_Requests.php"
    );

    exit();


}


?>