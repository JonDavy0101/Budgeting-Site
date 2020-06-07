<?php

	if (isset($_POST["acc"]) && isset($_POST["accName"]) && isset($_POST["origAccName"])) {
		session_start();

		// edit account
		include_once('./connect.php');

        // prepare and bind
        $accStmt = $conn->prepare("UPDATE dsAccounts SET account = ? WHERE userId = ? AND accountId = ?");
        $accStmt->bind_param("sii", $account, $userId, $accId);

        // escape variables for security, set parameters and execute
        $userId = mysqli_real_escape_string($conn, $_SESSION['id']);
        $account = mysqli_real_escape_string($conn, $_POST['accName']);
	    $accId = mysqli_real_escape_string($conn, $_POST['acc']);
        $accStmt->execute();
        $accStmt->close();

        // prepare and bind
        $accStmt = $conn->prepare("UPDATE dsTransactions SET account = ? WHERE userId = ? AND account = ?");
        $accStmt->bind_param("sis", $account, $userId, $origAccName);

        // escape variables for security, set parameters and execute
        $userId = mysqli_real_escape_string($conn, $_SESSION['id']);
        $account = mysqli_real_escape_string($conn, $_POST['accName']);
	    $origAccName = mysqli_real_escape_string($conn, $_POST['origAccName']);
        $accStmt->execute();
        $accStmt->close();
        $conn->close();
   } elseif (isset($_POST["acc"])) {
		  session_start();

   		// delete account
   		include_once('./connect.php');

	    // prepare and bind
        $delStmt = $conn->prepare("DELETE FROM dsAccounts WHERE accountId = ? AND userId = ?");
        $delStmt->bind_param("ii", $accId, $userId);

        // escape variables for security, set parameter and execute
	    $userId = mysqli_real_escape_string($conn, $_SESSION['id']);
	    $accId = mysqli_real_escape_string($conn, $_POST['acc']);
        $delStmt->execute();
        $delStmt->close();
        $conn->close();
   }
   
?>