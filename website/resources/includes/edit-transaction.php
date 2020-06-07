<?php

	if (isset($_POST["editTrans"]) && isset($_POST["editType"]) && isset($_POST["editAcc"]) && isset($_POST["editAmount"]) && isset($_POST["editPend"]) && isset($_POST["editDate"]) && isset($_POST["editPayee"]) && isset($_POST["editCat"]) && isset($_POST["editDescrip"])) {
		session_start();

		// edit account
		include_once('./connect.php');

        // prepare and bind
        $accStmt = $conn->prepare("UPDATE dsTransactions SET type = ?, account = ?, amount = ?, pending = ?, transDate = ?, payee = ?, category = ?, description = ? WHERE userId = ? AND transId = ?");
        $accStmt->bind_param("ssssssssii", $type, $account, $amount, $pending, $transDate, $payee, $category, $description, $userId, $transId);

        // escape variables for security, set parameters and execute
        $userId = mysqli_real_escape_string($conn, $_SESSION['id']);
        $transId = mysqli_real_escape_string($conn, $_POST['editTrans']);
        $type = mysqli_real_escape_string($conn, $_POST['editType']);
        $account = mysqli_real_escape_string($conn, $_POST['editAcc']);
        $amount = mysqli_real_escape_string($conn, $_POST['editAmount']);
        $pending = mysqli_real_escape_string($conn, $_POST['editPend']);
        $transDate = mysqli_real_escape_string($conn, $_POST['editDate']);
        $payee = mysqli_real_escape_string($conn, $_POST['editPayee']);
        $category = mysqli_real_escape_string($conn, $_POST['editCat']);
        $description = mysqli_real_escape_string($conn, $_POST['editDescrip']);
        $accStmt->execute();
        $accStmt->close();
        $conn->close();
   } elseif (isset($_POST["delTrans"])) {
		session_start();

        // delete account
        include_once('./connect.php');

        // prepare and bind
        $delStmt = $conn->prepare("DELETE FROM dsTransactions WHERE transId = ? AND userId = ?");
        $delStmt->bind_param("ii", $transId, $userId);

        // escape variables for security, set parameter and execute
        $userId = mysqli_real_escape_string($conn, $_SESSION['id']);
        $transId = mysqli_real_escape_string($conn, $_POST['delTrans']);
        $delStmt->execute();
        $delStmt->close();
        $conn->close();
   }
   
?>