<?php

	if (isset($_POST["newType"])) {
		session_start();
		
		$catItems = array();

		include_once('./connect.php');

	    $userId = mysqli_real_escape_string($conn, $_SESSION['id']);
	    $newType = mysqli_real_escape_string($conn, $_POST['newType']);

	    $sqlCat = "SELECT type, category FROM dsBudget WHERE userId = $userId and type = '$newType' ORDER BY category DESC";

	    $resultCat = mysqli_query($conn, $sqlCat);
	    $catRow = 0;

	    if (mysqli_num_rows($resultCat) > 0) {
	        while($row = mysqli_fetch_assoc($resultCat)) {
	           if ($resultCat->num_rows > 0) {
	           		if ($row["type"] == $_POST["newType"]) {
	           			$catItems[0] = '<option value="" disabled selected>Select..</option>';
           				$catRow++;
           				$catItems[$catRow] = '<option value="' . $row["category"] . '">' . $row["category"] . '</option>';
           			} else {
           				$catItems[$catRow] = '<option value="" disabled selected>Add budget category first</option>';
           			}
	            }
	        }
	    } else {
			$catItems[$catRow] = '<option value="" disabled selected>Add budget category first</option>';
		}

	    mysqli_close($conn);

	    // create array and echo results
   		$catResults = array ( 

		    "rowResult" => $catItems

		); 

   		$catJSON = json_encode($catResults);
		echo $catJSON;
	}

?>