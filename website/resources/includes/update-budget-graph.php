<?php

	$yearMonth = date("Y-m");
	$budgetBal = 0;
	$incomeBal = 0;
	$expenseBal = 0;
	$totalBudgetBal = 0;
	$totalIncomeBal = 0;
	$budgetVal = 0;
	$incomeVal = 0;
	$expenseVal = 0;
	$budgetMarg = 0;
	$incomeMarg = 0;
	$expenseMarg = 0;
	$budgCategories = array();
	$incomeCat = array();
	$expenseCat = array();
	$boolCat = array();
	$onclick = array();
	$rowResult = array();

	    
	function budgetResults() {
	   // Check if we have parameters w1 and w2 being passed to the script through the URL
	   if (isset($_POST["userId"])) {
	   		session_start();
	   		
	   		include_once('./connect.php');
		    global $yearMonth, $budgetBal, $incomeBal, $expenseBal, $totalBudgetBal, $totalIncomeBal, $budgetVal, $incomeVal, $expenseVal, $budgetMarg, $incomeMarg, $expenseMarg, $rowResult, $budgCategories, $incomeCat, $expenseCat, $boolCat, $onclick;
		    $userId = mysqli_real_escape_string($conn, $_SESSION['id']);

		    // $sqlAccounts = "SELECT accountId, userId, account, startBal FROM dsAccounts WHERE userId = $userId ORDER BY accountId DESC";
		    $sqlBudget = "SELECT budgetId, userId, yearMonth, type, category, budget FROM dsBudget WHERE userId = $userId ORDER BY type DESC, category DESC";
		    $sqlIncome = "SELECT transId, userId, type, account, amount, pending, transDate, payee, category, description FROM dsTransactions WHERE userId = $userId AND transDate LIKE '%$yearMonth%' AND type = 'Income' ORDER BY transDate DESC, transId DESC";
		    $sqlExpense = "SELECT transId, userId, type, account, amount, pending, transDate, payee, category, description FROM dsTransactions WHERE userId = $userId AND transDate LIKE '%$yearMonth%' AND type = 'Expense' ORDER BY transDate DESC, transId DESC";

		    $resultBudget = mysqli_query($conn, $sqlBudget);
		    $budgetBal = 0;

		    if (mysqli_num_rows($resultBudget) > 0) {
		        while($row = mysqli_fetch_assoc($resultBudget)) {
		            if ($resultBudget->num_rows > 0) {
		           		if ($row["type"] == "Expense") {
		           			$budgetBal += $row["budget"];
		           			$boolCat[$row["category"]] = "Expense";
		           		} else {
		           			$boolCat[$row["category"]] = "Income";
		           		}
		                $budgCategories[$row["category"]] = $row["budget"];
		                $onclick[$row["category"]] = 'onclick="originalData(\'' . $row["budgetId"] . '\', \'' . $row["type"] . '\', \'' . $row["category"] . '\', \'' . $row["budget"] . '\')"';
		            }
		        }
		    }

		    $resultIncome = mysqli_query($conn, $sqlIncome);
		    $incomeBal = 0;

		    if (mysqli_num_rows($resultIncome) > 0) {
		        while($row = mysqli_fetch_assoc($resultIncome)) {
		        	if ($resultIncome->num_rows > 0) {
		           		$incomeBal += $row["amount"];
		           		$incomeCat[$row["category"]] += $row["amount"];
		            }
		        }
		    }

		    $resultExpense = mysqli_query($conn, $sqlExpense);
		    $expenseBal = 0;

		    if (mysqli_num_rows($resultExpense) > 0) {
		        while($row = mysqli_fetch_assoc($resultExpense)) {
		            if ($resultExpense ->num_rows > 0) {
		                $expenseBal += $row["amount"];
		           		$expenseCat[$row["category"]] += $row["amount"];
		            }
		        }
		    }

		    mysqli_close($conn);

	   }
	}

	function returnResults() {
		global $yearMonth, $budgetBal, $incomeBal, $expenseBal, $totalBudgetBal, $totalIncomeBal, $budgetVal, $incomeVal, $expenseVal, $budgetMarg, $incomeMarg, $expenseMarg, $rowResult, $budgCategories, $incomeCat, $expenseCat, $boolCat, $onclick;

	    // Calculate the percentages of the graph
	    if (($budgetBal > $incomeBal && $budgetBal > $expenseBal) || ($budgetBal == $incomeBal && $budgetBal > $expenseBal) || ($budgetBal == $expenseBal && $budgetBal > $incomeBal)) {
	        $budgetVal = 150;
	        $budgetMarg = 0;
	        $incomeVal = ($incomeBal / $budgetBal) * 150;
	        $incomeMarg = 150 - $incomeVal;
	        $expenseVal = ($expenseBal / $budgetBal) * 150;
	        $expenseMarg = 150 - $expenseVal;
	    } elseif (($incomeBal > $budgetBal && $incomeBal > $expenseBal) || ($incomeBal == $expenseBal && $incomeBal > $budgetBal)) {
	        $incomeVal = 150;
	        $incomeMarg = 0;
	        $budgetVal = ($budgetBal / $incomeBal) * 150;
	        $budgetMarg = 150 - $budgetVal;
	        $expenseVal = ($expenseBal / $incomeBal) * 150;
	        $expenseMarg = 150 - $expenseVal;
	    } elseif ($expenseBal > $budgetBal && $expenseBal > $incomeBal) {
	        $expenseVal = 150;
	        $expenseMarg = 0;
	        $incomeVal = ($incomeBal / $expenseBal) * 150;
	        $incomeMarg = 150 - $incomeVal;
	        $budgetVal = ($budgetBal / $expenseBal) * 150;
	        $budgetMarg = 150 - $budgetVal;
	    } elseif ($budgetBal == 0 && $incomeBal == 0 && $expenseBal == 0) {
	        $budgetVal = 0;
	        $incomeVal = 0;
	        $expenseVal = 0;
	    } else {
	        $budgetVal = 150;
	        $budgetMarg = 0;
	        $incomeVal = 150;
	        $incomeMarg = 0;
	        $expenseVal = 150;
	        $expenseMarg = 0;
	    }

	    // Calculate budget and income balance
	    $totalBudgetBal = $budgetBal - $expenseBal;
	    $totalIncomeBal = $incomeBal - $expenseBal;

	    // Calculate category incomes/expenses and balances
	    $y = 0;
	    foreach($budgCategories as $x => $x_value) {
	    	if ($boolCat[$x] == "Income") {
	    		if (isset($incomeCat[$x]) == false) {
		    		$incomeCat[$x] = 0;
		    	}

		    	if (($x_value-$incomeCat[$x]) < 0) {
		    		$rowBal = '<p class="t-balance text-color-income">+$' . number_format((float)(($x_value-$incomeCat[$x]) - (2 * ($x_value-$incomeCat[$x]))), 2, '.', '');
		    	} else {
		    		$rowBal = '<p class="t-balance">$' . number_format(($x_value-$incomeCat[$x]), 2, '.', '');
		    	}

		    	$rowResult[$y] = '<div class="data-row row tb-sides"><div class="col-5 p-2 t-center" ' . $onclick[$x] . '><img class="t-symbol" src="./resources/images/icons/Income-symbol.svg"><p class="t-descrip d-inline-block">' . $x . '</p></div><div class="col-3 tx-sides p-0 text-center"><div class="tb-sides"><p class="t-amount">$' . number_format((float)$x_value, 2, '.', '') . '</p></div><div><p class="t-amount text-color-income">+$' . number_format((float)$incomeCat[$x], 2, '.', '') . '</p></div></div><div class="col-4 p-2 t-center justify-content-center"><div><p class="t-title">Balance</p>' . $rowBal . '</p></div></div></div>';
		    	$y++;
	    	} elseif ($boolCat[$x] == "Expense") {
	    		if (isset($expenseCat[$x]) == false) {
		    		$expenseCat[$x] = 0;
		    	}

		    	if (($x_value-$expenseCat[$x]) < 0) {
		    		$rowBal = '<p class="t-balance text-color-alert">-$' . number_format((($x_value-$expenseCat[$x]) - (2 * ($x_value-$expenseCat[$x]))), 2, '.', '');
		    	} else {
		    		$rowBal = '<p class="t-balance">$' . number_format(($x_value-$expenseCat[$x]), 2, '.', '');
		    	}

		    	$rowResult[$y] = '<div class="data-row row tb-sides"><div class="col-5 p-2 t-center" ' . $onclick[$x] . '><img class="t-symbol" src="./resources/images/icons/Expense-symbol.svg"><p class="t-descrip d-inline-block">' . $x . '</p></div><div class="col-3 tx-sides p-0 text-center"><div class="tb-sides"><p class="t-amount">$' . number_format((float)$x_value, 2, '.', '') . '</p></div><div><p class="t-amount text-color-expenses">-$' . number_format((float)$expenseCat[$x], 2, '.', '') . '</p></div></div><div class="col-4 p-2 t-center justify-content-center"><div><p class="t-title">Balance</p>' . $rowBal . '</p></div></div></div>';
		    	$y++;
	    	}
		}


	    // create array and echo results
   		$bResults = array ( 

   			"rowResult" => $rowResult,
   			"budgetBal" => $budgetBal,
   			"budgetVal" => $budgetVal, 
		    "budgetMarg" => $budgetMarg,
		    "incomeBal" => $incomeBal,
		    "incomeVal" => $incomeVal,
		    "incomeMarg" => $incomeMarg,
		    "expenseBal" => $expenseBal,
		    "expenseVal" => $expenseVal,
		    "expenseMarg" => $expenseMarg,
		    "totalBudgetBal" => $totalBudgetBal,
		    "totalIncomeBal" => $totalIncomeBal,
		    "rowResult" => $rowResult

		); 

		$bJSON = json_encode($bResults);

		// echo $bJSON;
		echo $bJSON;
	}

	budgetResults();
	returnResults();

?>
