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
			<h1>SALES REP</h1>
			<ul>
				<li><a href="home.html">Home</a></li>
				<li><a href="client.php">Clients</a></li>
				<li><a href="driver.php">Drivers</a></li>
				<li>Sales Rep</li>
				<li><a href="master.html">Master</a></li>
			</ul>
		</nav>
	</header>

<!-- SHOW CLIENTS - SELECT -->
<p>Show all of your clients by entering your SalesRep ID:</p>
<form method="POST" action="salesrep.php">
<!--Refresh on submit-->
<pre>
Sales Rep ID <input type="text" name="show_sid" size="12">
</pre>
<!--define one variable to pass the value-->
<input type="submit" value ="select" name="selectsalesrep">
</form>


<!-- SHOW SALESREP - SELECT -->
<p>Show the SalesRep of a Client:</p>
<form method="POST" action="salesrep.php">
<!--Refresh on submit-->
<pre>
Client ID <input type="text" name="show_cid" size="12">
</pre>
<!--define one variable to pass the value-->
<input type="submit" value ="select" name="selectclient">
</form>


<!-- ADD A PREMIUM CUSTOMER - INSERT -->
<p>Add a client to the premium table:</p>
<form method="POST" action="salesrep.php">
<!--Refresh on submit-->
<pre>
Client ID	<input type="text" name="add_pid" size="12">
SalesRep ID 	<input type="text" name="add_sid" size="12">
</pre>
<!--define variables to pass the value-->
<input type="submit" value="add" name="addPremium">
</form>


<!-- CANCEL ORDER - DELETE -->
<p>Cancel an order:</p>
<form method="POST" action="salesrep.php">
<!--Refresh on submit-->
<pre>
Order Number 	<input type="text" name="del_ono" size="12">
</pre>
<!--define variables to pass the value-->
<input type="submit" value="delete" name="deleteorder">
</form>


<!-- ADD ITEM TO ORDER - INSERT -->
<p>Add an item to an order:</p>
<form method="POST" action="salesrep.php">
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
<form method="POST" action="salesrep.php">
<!--Refresh on submit-->
<pre>
Order Number	<input type="text" name="up_ono" size="12">
Item ID		<input type="text" name="up_iid" size="12">
Quantity	<input type="text" name="up_qty" size="12">
</pre>
<!--define variables to pass the value-->
<input type="submit" value="update" name="updateitem">
</form>


<!-- GROUP BY ITEM - GROUP BY -->
<p>See item sales volume:</p>
<form method="POST" action="salesrep.php">
<!--Refresh on submit-->
<!--define variables to pass the value-->
<input type="submit" value="see items" name="groupitems">
</form>


<!-- VIEW FOR CLIENTS ORDERING OVER 4 TIMES - VIEW -->
<p>See clients who have ordered more than 4 times:</p>
<form method="POST" action="salesrep.php">
<!--Refresh on submit-->
<!--define variables to pass the value-->
<input type="submit" value="see clients" name="viewclients">
</form>


</body>


<?php

$success = True;
$db_conn = OCILogon("ora_m2j0b", "a21295150", "dbhost.ugrad.cs.ubc.ca:1522/ug");

// takes a plain (no bound variables) SQL command and executes it
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

// execute a SQL command with bound variables
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

// prints results from a select statement
function printSalesRep($result) { 
	echo "<br>Sales Rep:<br>";
	echo "<table>";
	echo "<tr>
			<th><pre>Sid	</pre></th>
			<th><pre>First Name	</pre></th>
			<th><pre>Last Name	</pre></th>
			</tr>";

	while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
		echo "<tr>
		<td>" . $row[0] . "</td>
		<td>" . $row[1] . "</td>
		<td>" . $row[2] . "</td>
		</tr>";
	}
	echo "</table>";

}

// prints orders for given client
function printClients($orders_to_print) { 
	echo "<br>Your clients:<br>";
	echo "<table>";
	echo "<tr>
			<th><pre>Client ID	</pre></th>
			<th><pre>First Name	</pre></th>
			<th><pre>Last Name	</pre></th>
			<th><pre>Company	</pre></th>
			<th><pre>Street	</pre></th>
			<th><pre>Postal Code	</pre></th>
			<th><pre>Phone Number	</pre></th>
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
		 </tr>";
	}
	echo "</table>";
}

// print items in a given order
function printItems($result) {
	echo "<table>";
	echo "<tr>
			<th><pre>Item Name	</pre></th>
			<th><pre>Restaurant	</pre></th>
			<th><pre>Quantity Sold	</pre></th>
		</tr>";

	while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
		echo "<tr>
			<td>" . $row[0] . "</td>
			<td>" . $row[1] . "</td>
			<td>" . $row[2] . "</td>
			</tr>";
	}
	echo "</table>";
}

// print the vips (clients who have ordered over 4 times) from the vip view
function printVIPs($result) {
	echo "<br>VIP Clients:<br>";
	echo "<table>";
	echo "<tr>
			<th><pre>Client ID	</pre></th>
			<th><pre>First Name	</pre></th>
			<th><pre>Last Name	</pre></th>
			<th><pre>Company	</pre></th>
			<th><pre>Street	</pre></th>
			<th><pre>City	</pre></th>
			<th><pre>Province	</pre></th>
			<th><pre>Postal Code	</pre></th>
			<th><pre>Phone	</pre></th>
			<th><pre>Number of Orders	</pre></th>
		</tr>";

	while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
		echo "<tr>
			<td>" . $row[0] . "</td>
			<td>" . $row[1] . "</td>
			<td>" . $row[2] . "</td>
			<td>" . $row[3] . "</td>
			<td>" . $row[4] . "</td>
			<td>" . $row[5] . "</td>
			<td>" . $row[6] . "</td>
			<td>" . $row[7] . "</td>
			<td>" . $row[8] . "</td>
			<td>" . $row[9] . "</td>
			</tr>";
	}
	echo "</table>";
}

// Connect Oracle...
if ($db_conn) {

	if (array_key_exists('selectsalesrep', $_POST)) {
		// select clients for the provided sales rep
		$activesalesrep = $_POST['show_sid'];
		// join the client, premium, and salesrep tables to get the correct data
		$result = executePlainSQL("SELECT c.cid, cfname, clname, ccompany, street, postal, cphone 
								   FROM client c, premium p, salesrep s 
								   WHERE c.cid = p.cid AND p.sid = s.sid AND p.sid = '".$activesalesrep."'");
		printClients($result);
		OCICommit($db_conn);

	} else
		if (array_key_exists('selectclient', $_POST)) {
			// select the salesrep assigned to the provided client
			$activeclient = $_POST['show_cid'];
			// join the premium and salesrep tables to get the correct data
			$result = executePlainSQL("SELECT s.sid, sfname, slname 
								   	   FROM premium p, salesrep s 
									   WHERE p.sid = s.sid AND p.cid = '".$activeclient."'");
			printSalesRep($result);
			OCICommit($db_conn);

		} else		
			if (array_key_exists('addPremium', $_POST)) {
				// insert the provided client into the premium table, with a default point balance of 0
				$tuple = array (
					":bind1" => $_POST['add_pid'],
					":bind2" => '0',
					":bind3" => $_POST['add_sid'],
				);
				$alltuples = array (
					$tuple
				);
				executeBoundSQL("INSERT INTO premium 
								 VALUES(:bind1, :bind2, :bind3)",
								 $alltuples);
				OCICommit($db_conn);

			} else
				if (array_key_exists('deleteorder', $_POST)) {
				// Delete the order provided, ON DELETE CASCADE in the ordereditems table should ensure that those items are also deleted
					$tuple = array (
						":bind1" => $_POST['del_ono']
					);
					$alltuples = array (
						$tuple
					);
					executeBoundSQL("DELETE FROM orders 
									 WHERE ono=:bind1", $alltuples);
					OCICommit($db_conn);
				
				} else
					if (array_key_exists('additem', $_POST)) {
						// insert an item into the ordereditem table
						$tuple = array (
							":bind1" => $_POST['add_ono'],
							":bind2" => $_POST['add_iid'],
							":bind3" => $_POST['add_qty']
						);
						$alltuples = array (
							$tuple
						);
						executeBoundSQL("INSERT INTO ordereditem
										 VALUES(:bind1, :bind2, :bind3)", $alltuples);
						OCICommit($db_conn);

					} else
						if (array_key_exists('updateitem', $_POST)) {
							// update the quantity ordered of an ordereditem
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
											 WHERE ono=:bind1 AND iid=:bind2", $alltuples);
							OCICommit($db_conn);

						} else

							if (array_key_exists('groupitems', $_POST)) {
								// group items by iname and show the total ordered for each item
								// join menuitem, menu, and restaurant to get the restaurant that serves that item
								$result = executePlainSQL("SELECT DISTINCT iname, rname, SUM(qty) as totalqty
													   	   FROM ordereditem o, menuitem i, menu m, restaurant r
														   WHERE o.iid = i.iid AND i.mid = m.mid AND m.rid = r.rid
														   GROUP BY iname, rname
														   ORDER BY totalqty DESC");
								printItems($result);
								OCICommit($db_conn);

							} else

							if (array_key_exists('viewclients', $_POST)) {
								// select clients who are considered 'vip' from vip view
								$result = executePlainSQL("SELECT * 
														   FROM vip");
								printVIPs($result);
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

