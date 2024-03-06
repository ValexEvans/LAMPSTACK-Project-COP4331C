<?php
    $inData = getRequestInfo();

    $firstName = $inData["firstName"];
    $lastName = $inData["lastName"];
    $phone = $inData["phone"];
    $email = $inData["email"];
    $userid = $inData["userid"];

    $conn = new mysqli("localhost", "TheBeast", "WeLoveCOP4331", "COP4331"); 
    if ($conn->connect_error) {
        returnWithError($conn->connect_error);
    } else {
        // Insert new contact with auto-incrementing ID
        $stmt = $conn->prepare("INSERT INTO Contacts (FirstName, LastName, Phone, Email, userid) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssi", $firstName, $lastName, $phone, $email, $userid);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            $id = $stmt->insert_id;
            returnWithInfo("Contact added successfully. ID: $id");
        } else {
            returnWithError("Error in adding contact");
        }

        $stmt->close();
        $conn->close();
    }

    function getRequestInfo() {
        return json_decode(file_get_contents('php://input'), true);
    }

    function sendResultInfoAsJson($obj) {
        header('Content-type: application/json');
        echo $obj;
    }

    function returnWithError($err) {
        $retValue = '{"error":"' . $err . '"}';
        sendResultInfoAsJson($retValue);
    }

    function returnWithInfo($info) {
        $retValue = '{"info":"' . $info . '", "error":""}';
        sendResultInfoAsJson($retValue);
    }
?>