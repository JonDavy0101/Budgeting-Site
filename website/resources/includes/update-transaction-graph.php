<?php

	$incomeBal = 0;
	$expenseBal = 0;
	$pendingExpenseBal = 0;
	$totalAccountBal = 0;
	$totalTotalAccBal = 0;
	$incomeVal = 0;
	$expenseVal = 0;
	$pendingExpenseVal = 0;
	$incomeMarg = 0;
	$expenseMarg = 0;
	$pendingExpenseMarg = 0;
	$transItems = array();
	$rowResult = 0;

	    
	function transResults() {
	   // Check if we have parameters w1 and w2 being passed to the script through the URL
	   if (isset($_POST["newAccount"])) {
	   		session_start();

	   		include_once('./connect.php');
		    global $incomeBal, $expenseBal, $pendingExpenseBal, $totalAccountBal, $totalTotalAccBal, $incomeVal, $expenseVal, $pendingExpenseVal, $incomeMarg, $expenseMarg, $pendingExpenseMarg, $transItems, $rowResult;
		    $userId = mysqli_real_escape_string($conn, $_SESSION['id']);
		    $newAccount = mysqli_real_escape_string($conn, $_POST['newAccount']);

		    $sqlTrans = "SELECT transId, type, account, amount, pending, transDate, payee, category, description FROM dsTransactions WHERE userId = $userId ORDER BY transDate DESC, transId DESC";
		    $rowResult = $sqlTrans;

		    $resultTrans = mysqli_query($conn, $sqlTrans);
		    $transRow = 0;

		    if (mysqli_num_rows($resultTrans) > 0) {
		        while($row = mysqli_fetch_assoc($resultTrans)) {
		           if ($resultTrans->num_rows > 0) {
		           		if ($_POST['newAccount'] == "All Accounts") {
		           			if ($row["type"] == "Income") {
		           				$incomeBal += $row["amount"];
		           				$totalAccountBal += $row["amount"];
		           				$transItems[$transRow] =  '<div class="data-row row tb-sides" onclick="originalData(\'' . $row["transId"] . '\', \'' . $row["type"] . '\', \'' . $row["account"] . '\', \'' . $row["amount"] . '\', \'' . $row["pending"] . '\', \'' . $row["transDate"] . '\', \'' . $row["payee"] . '\', \'' . $row["category"] . '\', \'' . $row["description"] . '\')"><div class="col-8 p-2 t-center"><img class="t-symbol" src="./resources/images/icons/Income-symbol.svg"><div class="d-inline-block"><p class="t-title t-title-descrip">' . $row["payee"] . ' • ' . date("M j, Y", strtotime($row["transDate"])) . '</p><p class="t-descrip">' . $row["description"] . '</p></div></div><div class="col-4 p-2 tl-sides t-center justify-content-end text-right"><div><p class="t-title">' . $row["account"]. ' • ' . $row["category"] . '</p><p class="t-balance text-color-income">+$' . $row["amount"] . '</p></div></div></div>';
		           				$transRow++;
		           			} elseif ($row["type"] == "Expense" && $row["pending"] == "no") {
		           				$expenseBal += $row["amount"];
		           				$totalAccountBal -= $row["amount"];
		           				$transItems[$transRow] =  '<div class="data-row row tb-sides" onclick="originalData(\'' . $row["transId"] . '\', \'' . $row["type"] . '\', \'' . $row["account"] . '\', \'' . $row["amount"] . '\', \'' . $row["pending"] . '\', \'' . $row["transDate"] . '\', \'' . $row["payee"] . '\', \'' . $row["category"] . '\', \'' . $row["description"] . '\')"><div class="col-8 p-2 t-center"><img class="t-symbol" src="./resources/images/icons/Expense-symbol.svg"><div class="d-inline-block"><p class="t-title t-title-descrip">' . $row["payee"] . ' • ' . date("M j, Y", strtotime($row["transDate"])) . '</p><p class="t-descrip">' . $row["description"] . '</p></div></div><div class="col-4 p-2 tl-sides t-center justify-content-end text-right"><div><p class="t-title">' . $row["account"]. ' • ' . $row["category"] . '</p><p class="t-balance text-color-expenses">-$' . $row["amount"] . '</p></div></div></div>';
		           				$transRow++;
		           			} elseif ($row["type"] == "Expense" && $row["pending"] == "yes") {
		           				$pendingExpenseBal += $row["amount"];
		           				$totalAccountBal -= $row["amount"];
		           				$transItems[$transRow] =  '<div class="data-row row tb-sides" onclick="originalData(\'' . $row["transId"] . '\', \'' . $row["type"] . '\', \'' . $row["account"] . '\', \'' . $row["amount"] . '\', \'' . $row["pending"] . '\', \'' . $row["transDate"] . '\', \'' . $row["payee"] . '\', \'' . $row["category"] . '\', \'' . $row["description"] . '\')"><div class="col-8 p-2 t-center"><img class="t-symbol" src="./resources/images/icons/Expense-symbol.svg"><div class="d-inline-block"><p class="t-title t-title-descrip">' . $row["payee"] . ' • ' . date("M j, Y", strtotime($row["transDate"])) . ' • Pending</p><p class="t-descrip">' . $row["description"] . '</p></div></div><div class="col-4 p-2 tl-sides t-center justify-content-end text-right"><div><p class="t-title">' . $row["account"]. ' • ' . $row["category"] . '</p><p class="t-balance text-color-expenses">-$' . $row["amount"] . '</p></div></div></div>';
		           				$transRow++;
		           			} elseif ($row["type"] == "Initial Balance") {
		           				$totalAccountBal += $row["amount"];
		           				$transItems[$transRow] =  '<div class="data-row row tb-sides" onclick="originalData(\'' . $row["transId"] . '\', \'' . $row["type"] . '\', \'' . $row["account"] . '\', \'' . $row["amount"] . '\', \'' . $row["pending"] . '\', \'' . $row["transDate"] . '\', \'' . $row["payee"] . '\', \'' . $row["category"] . '\', \'' . $row["description"] . '\')"><div class="col-8 p-2 t-center"><img class="t-symbol" src="./resources/images/icons/account-symbol.svg"><div class="d-inline-block"><p class="t-title t-title-descrip">' . $row["payee"] . ' • ' . date("M j, Y", strtotime($row["transDate"])) . '</p><p class="t-descrip">' . $row["description"] . '</p></div></div><div class="col-4 p-2 tl-sides t-center justify-content-end text-right"><div><p class="t-title">' . $row["account"]. ' • ' . $row["category"] . '</p><p class="t-balance">$' . $row["amount"] . '</p></div></div></div>';
		           				$transRow++;
		           			}
		           		} elseif ($row["account"] == $newAccount ) {
		           			if ($row["type"] == "Income") {
		           				$incomeBal += $row["amount"];
		           				$totalAccountBal += $row["amount"];
		           				$transItems[$transRow] =  '<div class="data-row row tb-sides" onclick="originalData(\'' . $row["transId"] . '\', \'' . $row["type"] . '\', \'' . $row["account"] . '\', \'' . $row["amount"] . '\', \'' . $row["pending"] . '\', \'' . $row["transDate"] . '\', \'' . $row["payee"] . '\', \'' . $row["category"] . '\', \'' . $row["description"] . '\')"><div class="col-8 p-2 t-center"><img class="t-symbol" src="./resources/images/icons/Income-symbol.svg"><div class="d-inline-block"><p class="t-title t-title-descrip">' . $row["payee"] . ' • ' . date("M j, Y", strtotime($row["transDate"])) . '</p><p class="t-descrip">' . $row["description"] . '</p></div></div><div class="col-4 p-2 tl-sides t-center justify-content-end text-right"><div><p class="t-title">' . $row["category"] . '</p><p class="t-balance text-color-income">+$' . $row["amount"] . '</p></div></div></div>';
		           				$transRow++;
		           			} elseif ($row["type"] == "Expense" && $row["pending"] == "no") {
		           				$expenseBal += $row["amount"];
		           				$totalAccountBal -= $row["amount"];
		           				$transItems[$transRow] =  '<div class="data-row row tb-sides" onclick="originalData(\'' . $row["transId"] . '\', \'' . $row["type"] . '\', \'' . $row["account"] . '\', \'' . $row["amount"] . '\', \'' . $row["pending"] . '\', \'' . $row["transDate"] . '\', \'' . $row["payee"] . '\', \'' . $row["category"] . '\', \'' . $row["description"] . '\')"><div class="col-8 p-2 t-center"><img class="t-symbol" src="./resources/images/icons/Expense-symbol.svg"><div class="d-inline-block"><p class="t-title t-title-descrip">' . $row["payee"] . ' • ' . date("M j, Y", strtotime($row["transDate"])) . '</p><p class="t-descrip">' . $row["description"] . '</p></div></div><div class="col-4 p-2 tl-sides t-center justify-content-end text-right"><div><p class="t-title">' . $row["category"] . '</p><p class="t-balance text-color-expenses">-$' . $row["amount"] . '</p></div></div></div>';
		           				$transRow++;
		           			} elseif ($row["type"] == "Expense" && $row["pending"] == "yes") {
		           				$pendingExpenseBal += $row["amount"];
		           				$totalAccountBal -= $row["amount"];
		           				$transItems[$transRow] =  '<div class="data-row row tb-sides" onclick="originalData(\'' . $row["transId"] . '\', \'' . $row["type"] . '\', \'' . $row["account"] . '\', \'' . $row["amount"] . '\', \'' . $row["pending"] . '\', \'' . $row["transDate"] . '\', \'' . $row["payee"] . '\', \'' . $row["category"] . '\', \'' . $row["description"] . '\')"><div class="col-8 p-2 t-center"><img class="t-symbol" src="./resources/images/icons/Expense-symbol.svg"><div class="d-inline-block"><p class="t-title t-title-descrip">' . $row["payee"] . ' • ' . date("M j, Y", strtotime($row["transDate"])) . ' • Pending</p><p class="t-descrip">' . $row["description"] . '</p></div></div><div class="col-4 p-2 tl-sides t-center justify-content-end text-right"><div><p class="t-title">' . $row["category"] . '</p><p class="t-balance text-color-expenses">-$' . $row["amount"] . '</p></div></div></div>';
		           				$transRow++;
		           			} elseif ($row["type"] == "Initial Balance") {
		           				$totalAccountBal += $row["amount"];
		           				$transItems[$transRow] =  '<div class="data-row row tb-sides" onclick="originalData(\'' . $row["transId"] . '\', \'' . $row["type"] . '\', \'' . $row["account"] . '\', \'' . $row["amount"] . '\', \'' . $row["pending"] . '\', \'' . $row["transDate"] . '\', \'' . $row["payee"] . '\', \'' . $row["category"] . '\', \'' . $row["description"] . '\')"><div class="col-8 p-2 t-center"><img class="t-symbol" src="./resources/images/icons/account-symbol.svg"><div class="d-inline-block"><p class="t-title t-title-descrip">' . $row["payee"] . ' • ' . date("M j, Y", strtotime($row["transDate"])) . '</p><p class="t-descrip">' . $row["description"] . '</p></div></div><div class="col-4 p-2 tl-sides t-center justify-content-end text-right"><div><p class="t-title">' . $row["account"]. ' • ' . $row["category"] . '</p><p class="t-balance">$' . $row["amount"] . '</p></div></div></div>';
		           				$transRow++;
		           			}
		           		}
		           		if ($row["type"] == "Income") {
	           				$totalTotalAccBal += $row["amount"];
	           			} elseif ($row["type"] == "Expense" && $row["pending"] == "no") {
	           				$totalTotalAccBal -= $row["amount"];
	           			} elseif ($row["type"] == "Expense" && $row["pending"] == "yes") {
	           				$totalTotalAccBal -= $row["amount"];
	           			} elseif ($row["type"] == "Initial Balance") {
	           				$totalTotalAccBal += $row["amount"];
	           			}
		            }
		        }
		    }

		    mysqli_close($conn);

	   }
	}

	function returnResults() {
		global $incomeBal, $expenseBal, $pendingExpenseBal, $totalAccountBal, $totalTotalAccBal, $incomeVal, $expenseVal, $pendingExpenseVal, $incomeMarg, $expenseMarg, $pendingExpenseMarg, $transItems, $rowResult;

	    // Calculate the percentages of the graph
	    if (($pendingExpenseBal > $incomeBal && $pendingExpenseBal > $expenseBal) || ($pendingExpenseBal == $incomeBal && $pendingExpenseBal > $expenseBal) || ($pendingExpenseBal == $expenseBal && $pendingExpenseBal > $incomeBal)) {
	        $pendingExpenseVal = 150;
	        $pendingExpenseMarg = 0;
	        $incomeVal = ($incomeBal / $pendingExpenseBal) * 150;
	        $incomeMarg = 150 - $incomeVal;
	        $expenseVal = ($expenseBal / $pendingExpenseBal) * 150;
	        $expenseMarg = 150 - $expenseVal;
	    } elseif (($incomeBal > $pendingExpenseBal && $incomeBal > $expenseBal) || ($incomeBal == $expenseBal && $incomeBal > $pendingExpenseBal)) {
	        $incomeVal = 150;
	        $incomeMarg = 0;
	        $pendingExpenseVal = ($pendingExpenseBal / $incomeBal) * 150;
	        $pendingExpenseMarg = 150 - $pendingExpenseVal;
	        $expenseVal = ($expenseBal / $incomeBal) * 150;
	        $expenseMarg = 150 - $expenseVal;
	    } elseif ($expenseBal > $pendingExpenseBal && $expenseBal > $incomeBal) {
	        $expenseVal = 150;
	        $expenseMarg = 0;
	        $incomeVal = ($incomeBal / $expenseBal) * 150;
	        $incomeMarg = 150 - $incomeVal;
	        $pendingExpenseVal = ($pendingExpenseBal / $expenseBal) * 150;
	        $pendingExpenseMarg = 150 - $pendingExpenseVal;
	    } elseif ($pendingExpenseBal == 0 && $incomeBal == 0 && $expenseBal == 0) {
	        $pendingExpenseVal = 0;
	        $incomeVal = 0;
	        $expenseVal = 0;
	    } else {
	        $pendingExpenseVal = 150;
	        $pendingExpenseMarg = 0;
	        $incomeVal = 150;
	        $incomeMarg = 0;
	        $expenseVal = 150;
	        $expenseMarg = 0;
	    }

	    // Change Expenses to negative values
	    $expenseBal -= 2 * $expenseBal;
	    $pendingExpenseBal -= 2 * $pendingExpenseBal;

	    // create array and echo results
   		$bResults = array ( 

		    "incomeBal" => round($incomeBal, 2),
		    "incomeVal" => $incomeVal,
		    "incomeMarg" => $incomeMarg,
		    "expenseBal" => round($expenseBal, 2),
		    "expenseVal" => $expenseVal,
		    "expenseMarg" => $expenseMarg,
   			"pendingExpenseBal" => round($pendingExpenseBal, 2),
   			"pendingExpenseVal" => $pendingExpenseVal, 
		    "pendingExpenseMarg" => $pendingExpenseMarg,
		    "totalAccountBal" => round($totalAccountBal, 2),
		    "totalTotalAccBal" => round($totalTotalAccBal, 2),
		    "transItems" => $transItems

		); 

		$bJSON = json_encode($bResults);

		// echo $bJSON;
		echo $bJSON;
	}

	transResults();
	returnResults();

?>