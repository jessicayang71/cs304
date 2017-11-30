<head>
	<title>CPSC 304 Project</title>
	 <meta charset="utf-8">
  	<meta name="viewport" content="width=device-width, initial-scale=1">
 	 <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
	<link rel="stylesheet" href="main.css">
</head>

<body>
	<header>
		<nav>
			<h1>CLIENTS</h1>
			<ul>
				<li><a href="home.html">Home</a></li>
				<li>Clients</li>
				<li><a href="driver.php">Drivers</a></li>
				<li><a href="salesrep.php">Sales Reps</a></li>
				<li><a href="master.html">Master</a></li>
			</ul>
		</nav>
	</header>

<!-- SHOW ALL ORDERS - SELECT -->
<p>Show all of your orders by entering your Client ID:</p>
<form method="POST" action="client.php">
<!--Refresh on submit-->
<pre>
Client ID <input type="text" name="show_cid" size="12">
</pre>
<!--define variables to pass the value-->
<input type="submit" value ="select" name="selectclient">
</form>


<!-- CREATE ORDER - INSERT -->
<p>Create an order:</p>
<form method="POST" action="client.php">
<!--Refresh on submit-->
<pre>
Order Number			<input type="text" name="create_ono" size="12"><!--get system date-->
Street				<input type="text" name="create_street" size="12">
City				<input type="text" name="create_city" size="12">		<!--these go to the area table for BCNFness-->
Province			<input type="text" name="create_province" size="12">	<!--these go to the area table for BCNFness-->
Postal				<input type="text" name="create_postal" size="12">
Delivery Date (YYYY-MM-DD) 	<input type="text" name="create_ddate" size="12">
Delivery Time (HHMM) 		<input type="text" name="create_dtime" size="12"><!--ostatus is NULL by default-->
Order Amount 			<input type="text" name="create_amount" size="12">
Card Number  			<input type="text" name="create_cardno" size="12">
Expiry Date (MMYY)		<input type="text" name="create_expdate" size="12">	<!--This goes to the decomposed card table-->
Restaurant ID 			<input type="text" name="create_rid" size="12"><!--sid is NULL by default-->
Client ID 			<input type="text" name="create_cid" size="12"><!--did is NULL by default-->
</pre>
<!--define variables to pass the value-->
<input type="submit" value="create" name="createorder">
</form>


<!-- CANCEL ORDER - DELETE -->
<p>Cancel an order:</p>
<form method="POST" action="client.php">
<!--Refresh on submit-->
<pre>
Order Number <input type="text" name="cancel_ono" size="12">
</pre>
<!--define variables to pass the value-->
<input type="submit" value="delete" name="deleteorder">
</form>


<!-- SHOW ALL ITEMS IN AN ORDER - SELECT -->
<p>Show all items in an order:</p>
<form method="POST" action="client.php">
<!--Refresh on submit-->
<pre>
Order Number	<input type="text" name="show_ono" size="12">
</pre>
<!--define varables to pass the value-->
<input type="submit" value ="show" name="selectono">
</form>


<!-- ADD ITEM TO ORDER - INSERT -->
<p>Add an item to an order:</p>
<form method="POST" action="client.php">
<!--Refresh on submit-->
<pre>
Order Number	<input type="text" name="add_ono" size="12">
Item ID		<input type="text" name="add_iid" size="12">
Quantity	<input type="text" name="add_qty" size="12">
</pre>
<!--define variables to pass the value-->
<input type="submit" value="add" name="additem">
</form>


<!-- UPDATE QUANTITY OF ORDERED ITEM - UPDATE -->
<p>Update the quantity of an ordered item:</p>
<form method="POST" action="client.php">
<!--Refresh on submit-->
<pre>
Order Number	<input type="text" name="up_ono" size="12">
Item ID		<input type="text" name="up_iid" size="12">
Quantity	<input type="text" name="up_qty" size="12">
</pre>
<!--define variables to pass the value-->
<input type="submit" value="update" name="updateitem">
</form>


<!-- DELETE ITEM FROM ORDER - DELETE -->
<p>Delete an item ordered:</p>
<form method="POST" action="client.php">
<!--Refresh on submit-->
<pre>
Order Number	<input type="text" name="del_ono" size="12">
Item ID		<input type="text" name="del_iid" size="12">
</pre>
<!--define variables to pass the values-->
<input type="submit" value="delete" name="deleteitem">
</form>

</body>
</html>

<?php

$success = True;
$db_conn = OCILogon("ora_m2j0b", "a21295150", "dbhost.ugrad.cs.ubc.ca:1522/ug");

//takes a plain (no bound variables) SQL command and executes it
function executePlainSQL($cmdstr) {
	global $db_conn, $success;
	$statement = OCIParse($db_conn, $cmdstr);

	if (!$statement) {
		echo "<br>Cannot parse the following command: " . $cmdstr . "<br>";
		$e = OCI_Error($db_conn); 
		echo htmlentities($e['message']);
		$success = False;
	}

	$r = OCIExecute($statement, OCI_DEFAULT);
	if (!$r) {
		echo "<br>Cannot execute the following command: " . $cmdstr . "<br>";
		$e = oci_error($statement);
		echo htmlentities($e['message']);
		$success = False;
	} else {

	}
	return $statement;

}

// executes a SQL command with bound variables
function executeBoundSQL($cmdstr, $list) {
	global $db_conn, $success;
	$statement = OCIParse($db_conn, $cmdstr);

	if (!$statement) {
		echo "<br>Cannot parse the following command: " . $cmdstr . "<br>";
		$e = OCI_Error($db_conn);
		echo htmlentities($e['message']);
		$success = False;
	}

	foreach ($list as $tuple) {
		foreach ($tuple as $bind => $val) {
			OCIBindByName($statement, $bind, $val);
			unset ($val);
		}

		$r = OCIExecute($statement, OCI_COMMIT_ON_SUCCESS);
		if (!$r) {
			echo "<br>Cannot execute the following command: " . $cmdstr . "<br>";
			$e = OCI_Error($statement);
			echo htmlentities($e['message']);
			echo "<br>";
			$success = False;
		}
	}
	return $statement;
}

//prints orders for given client
function printOrders($orders_to_print) { 
	echo "<br>Your Orders:<br>";
	echo "<table>";
	echo "<tr>
			<th><pre>Order ID   </pre></th>
			<th><pre>Date Placed	</pre></th>
			<th><pre>Street	</pre></th>
			<th><pre>Postal	</pre></th>
			<th><pre>Delivery Date	</pre></th>
			<th><pre>Delivery Time	</pre></th>
			<th><pre>Status	</pre></th>
			<th><pre>Amount	</pre></th>
		 </tr>";

	while ($row = OCI_Fetch_Array($orders_to_print, OCI_BOTH)) {
		echo "<tr>
			<td>" . $row[0] . "</td>
			<td>" . $row[1] . "</td>
			<td>" . $row[2] . "</td>
			<td>" . $row[3] . "</td>
			<td>" . $row[4] . "</td>
			<td>" . $row[5] . "</td>
			<td>" . $row[6] . "</td>
			<td>" . $row[7] . "</td>
		 </tr>";
	}
	echo "</table>";
}

// prints items for a given order
function printItems($result) {
	echo "<br>Items in the order:<br>";
	echo "<table>";
	echo "<tr>
			<th><pre>Item	</pre></th>
			<th><pre>Price	</pre></th>
			<th><pre>Category	</pre></th>
			<th><pre>Quantity	</pre></th>
		</tr>";

	while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
		echo "<tr>
				<td>" . $row[0] . "</td>
				<td>" . $row[1] . "</td>
				<td>" . $row[2] . "</td>
				<td>" . $row[3] . "</td>
			</tr>";
	}
	echo "</table>";

}

// Connect Oracle...
if ($db_conn) {

	if (array_key_exists('selectclient', $_POST)) {
		// select all orders of the client provided by the user
		$activeclient = $_POST['show_cid'];
		$result = executePlainSQL("SELECT ono, placeddate, street, postal, deliverdate, delivertime, ostatus, oamount 
								   FROM orders 
								   WHERE cid = '".$activeclient."'");
		printOrders($result);
		OCICommit($db_conn);

	} else
		if (array_key_exists('selectono', $_POST)) {
			// Select all items in the order provided by the user
			$activeorder = $_POST['show_ono'];
			$result = executePlainSQL("SELECT iname, iprice, icategory, qty 
									   FROM ordereditem o, menuitem m
									   WHERE o.iid = m.iid AND o.ono = '".$activeorder."'");
			printItems($result);
			OCICommit($db_conn);

		} else
			if (array_key_exists('deleteorder', $_POST)) {
				// Delete the order provided by user
				$tuple = array (
					":bind1" => $_POST['cancel_ono']
				);
				$alltuples = array (
					$tuple
				);
				executeBoundSQL("DELETE FROM orders 
								 WHERE ono=:bind1",
								 $alltuples);
				OCICommit($db_conn);
				
			} else
				if (array_key_exists('additem', $_POST)) {
					// insert item into order with data from user
					$tuple = array (
						":bind1" => $_POST['add_ono'],
						":bind2" => $_POST['add_iid'],
						":bind3" => $_POST['add_qty']
					);
					$alltuples = array (
						$tuple
					);
					executeBoundSQL("INSERT INTO ordereditem 
									 VALUES(:bind1, :bind2, :bind3)",
									 $alltuples);
					OCICommit($db_conn);

				} else
					if (array_key_exists('updateitem', $_POST)) {
						// update item quantity with data from user
						$tuple = array (
							":bind1" => $_POST['up_ono'],
							":bind2" => $_POST['up_iid'],
							":bind3" => $_POST['up_qty']
						);
						$alltuples = array (
							$tuple
						);
						executeBoundSQL("UPDATE ordereditem 
										 SET qty=:bind3 
										 WHERE ono=:bind1 AND iid=:bind2",
										 $alltuples);
						OCICommit($db_conn);
					
					} else
						if (array_key_exists('createorder', $_POST)) {
							// creating an order with data from user
							$tuple = array (
								":bind1"  => $_POST['create_ono'],
								":bind2"  => date('Y-m-d'),
								":bind3"  => $_POST['create_street'],
								":bind4"  => $_POST['create_postal'],
								":bind5"  => $_POST['create_ddate'],
								":bind6"  => $_POST['create_dtime'],
								":bind7"  => NULL,
								":bind8"  => $_POST['create_amount'],
								":bind9"  => $_POST['create_cardno'],
								":bind10" => NULL,
								":bind11" => $_POST['create_rid'],
								":bind12" => $_POST['create_cid'],
								":bind13" => NULL,
								":bind14" => $_POST['create_city'],
								":bind15" => $_POST['create_province'],
								":bind16" => $_POST['create_expdate']
							);
							$alltuples = array (
								$tuple
							);
							// insert the new order into the order table
							executeBoundSQL("INSERT INTO orders
											 VALUES(:bind1, :bind2, :bind3, :bind4, :bind5, :bind6, :bind7, :bind8, :bind9, :bind10, :bind11, :bind12, :bind13)",
											 $alltuples);
							// insert the city and province into the decomposed area table
							executeBoundSQL("INSERT INTO area 
											 VALUES(:bind4, :bind14, :bind15)", 
											 $alltuples);
							// insert the expdate and cardno into the decomposed card table
							executeBoundSQL("INSERT INTO card
											 VALUES(:bind9, :bind16)",
											 $alltuples);
							OCICommit($db_conn);

						} else
							if (array_key_exists('deleteitem', $_POST)) {
								// deleting item in order specified by user
								$tuple = array (
									":bind1" => $_POST['del_ono'],
									":bind2" => $_POST['del_iid']
								);
								$alltuples = array (
									$tuple
								);
								executeBoundSQL("DELETE FROM ordereditem
												 WHERE ono = :bind1 AND iid = :bind2", $alltuples);
								OCICommit($db_conn);
							}

	if ($_POST && !$success) {
		echo "Failed";
	
	}
	
	//Commit to save changes...
	OCILogoff($db_conn);
} else {
	echo "cannot connect";
	$e = OCI_Error();
	echo htmlentities($e['message']);
}

?>

