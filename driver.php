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
			<h1>DRIVERS</h1>
			<ul>
				<li><a href="home.html">Home</a></li>
				<li><a href="client.php">Clients</a></li>
				<li>Drivers</li>
				<li><a href="salesrep.php">Sales Reps</a></li>
				<li><a href="master.html">Master</a></li>
			</ul>
		</nav>
	</header>

<!-- UPDATE DRIVER STATUS - UPDATE -->
<p>Update status:</p>
<!--refresh page when submit-->
<form method="POST" action="driver.php">
<pre>
Driver ID	<input type="text" name="insDid" size="12">
Status		<input type="text" name="insStatus" size="12">
</pre>
<!--define variables to pass the value-->
<input type="submit" value="update status" name="updatestatus">
</form>


<!-- UPDATE DRIVER SHIFTS - UPDATE -->
<p>Update shift times:</p>
<!--refresh page when submit-->
<form method="POST" action="driver.php">
<pre>
Driver ID	   <input type="text" name="upDid" size="12">
Start Shift (HHMM) <input type="text" name="upStart" size="12">
End Shift (HHMM)   <input type="text" name="upEnd" size="12">
</pre>
<!--define variables to pass the value-->
<input type="submit" value="update shift" name="updatetime">
</form>


<!-- SELECT ASSIGNED ORDERS FOR A DRIVER FOR A CERTAIN DAY - SELECT -->
<p>See deliveries for a certain day:</p>
<!--refresh page when submit-->
<form method="POST" action="driver.php">
<pre>
Driver ID	 <input type="text" name="selectDid" size="12">
Date (YYYY-MM-DD)<input type="text" name="selectDate" size="12">
</pre>
<!--define variables to pass the value-->
<input type="submit" value="see deliveries" name="selectdeliveries">
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

// executes SQL command with bound variables
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

// prints the deliveries for a driver from a select statement
function printDeliveries($result) {
	echo "<br>Deliveries:<br>";
	echo "<table>";
	echo "<tr>
			<th><pre>Order Number	</pre></th>
			<th><pre>Delivery Time	</pre></th>
			<th><pre>Restaurant	</pre></th>
			<th><pre>Street	</pre></th>
			<th><pre>City	</pre></th>
			<th><pre>Province	</pre></th>
			<th><pre>Postal Code	</pre></th>
			<th><pre>Company	</pre></th>
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
			</tr>";
	}
	echo "</table>";

}

// Connect Oracle...
if ($db_conn) {

	if (array_key_exists('updatestatus', $_POST)) {
		// Update driver status using data provided
		$tuple = array (
			":bind1" => $_POST['insDid'],
			":bind2" => $_POST['insStatus']
		);
		$alltuples = array (
			$tuple
		);
		executeBoundSQL("UPDATE driver 
						 SET dstatus=:bind2 
						 WHERE did=:bind1", 
						 $alltuples);
		OCICommit($db_conn);

	} else
		if (array_key_exists('updatetime', $_POST)) {
			// updates driver shift times using data provided
			$tuple = array (
				":bind1" => $_POST['upDid'],
				":bind2" => $_POST['upStart'],
				":bind3" => $_POST['upEnd']
			);
			$alltuples = array (
				$tuple
			);
			executeBoundSQL("UPDATE driver 
							 SET startshift=:bind2, endshift=:bind3 
							 WHERE did=:bind1",
							 $alltuples);
			OCICommit($db_conn);

		} else
			if (array_key_exists('selectdeliveries', $_POST)) {
				// select orders to be delivered by driver provided on date provided
				$tuple = array (
					":bind1" => $_POST['selectDid'],
					":bind2" => $_POST['selectDate']
				);
				$alltuples = array (
					$tuple
				);
				// select order information by joining 4 tables on the conditions provided
				$result = executeBoundSQL("SELECT o.ono, o.delivertime, r.rname, o.street, a.city, a.province, o.postal, c.ccompany
										   FROM orders o, client c, restaurant r, area a
								 		   WHERE o.cid = c.cid AND o.rid = r.rid AND o.postal = a.postal AND o.did =:bind1 AND o.deliverdate=:bind2", 
								 		   $alltuples);
				printDeliveries($result);
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