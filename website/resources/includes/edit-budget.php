<?php

	if (isset($_POST["editBudg"]) && isset($_POST["editType"]) && isset($_POST["editCat"]) && isset($_POST["editAmount"])) {
		session_start();

		// edit account
		include_once('./connect.php');

        // prepare and bind
        $accStmt = $conn->prepare("UPDATE dsBudget SET type = ?, category = ?, budget = ? WHERE userId = ? AND budgetId = ?");
        $accStmt->bind_param("sssii", $type, $category, $budget, $userId, $budgetId);

        // escape variables for security, set parameters and execute
        $userId = mysqli_real_escape_string($conn, $_SESSION['id']);
        $budgetId = mysqli_real_escape_string($conn, $_POST['editBudg']);
        $type = mysqli_real_escape_string($conn, $_POST['editType']);
        $category = mysqli_real_escape_string($conn, $_POST['editCat']);
        $budget = mysqli_real_escape_string($conn, $_POST['editAmount']);
        $accStmt->execute();
        $accStmt->close();
        $conn->close();
   } elseif (isset($_POST["delBudg"])) {
		session_start();

        // delete account
        include_once('./connect.php');

        // prepare and bind
        $delStmt = $conn->prepare("DELETE FROM dsBudget WHERE budgetId = ? AND userId = ?");
        $delStmt->bind_param("ii", $budgetId, $userId);

        // escape variables for security, set parameter and execute
        $userId = mysqli_real_escape_string($conn, $_SESSION['id']);
        $budgetId = mysqli_real_escape_string($conn, $_POST['delBudg']);
        $delStmt->execute();
        $delStmt->close();
        $conn->close();
   }
   
?>