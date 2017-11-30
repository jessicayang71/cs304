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
			<h1>MASTER</h1>
			<ul>
				<li><a href="home.html">Home</a></li>
				<li><a href="client.php">Clients</a></li>
				<li><a href="driver.php">Drivers</a></li>
				<li><a href="salesrep.php">Sales Reps</a></li>
				<li><a href="master.html">Master</a></li>
			</ul>
			<ul>
				<li><a href="master-client.php">Client Manager</li>
				<li>Sales Rep Manager</li>
				<li><a href="master-driver.php">Driver Manager</a></li>
			</ul>
		</nav>
	</header>

<!-- ADD NEW CLIENT - INSERT -->
<p>Add a new driver:</p>
<form method="POST" action="master-driver.php">
<!--Refresh on submit-->
<pre>
Driver ID		<input type="text" name="ins_did" size="12">
First Name		<input type="text" name="ins_dfname" size="12">
Last Name		<input type="text" name="ins_dlname" size="12">
Location		<input type="text" name="ins_dlocation" size="12">
Start Shift		<input type="text" name="ins_startshift" size="12">
End Shift		<input type="text" name="ins_endshift" size="12">
Current Status 		<input type="text" name="ins_dstatus" size="12"> 
</pre>
<!--define variables to pass the value-->
<input type="submit" value="insert" name="insertdriver">
</form>

<!-- REMOVE CLIENT - DELETE -->
<p>Remove a driver:</p>
<form method="POST" action="master-driver.php">
<!--Refresh on submit-->
<pre>
Driver ID	<input type="text" name="del_did" size="12">
</pre>
<!--define variables to pass the value-->
<input type="submit" value="delete" name="deletedriver">
</form>

<!-- UPDATE the did an order - UPDATE -->
<p>Update or add the did of an order:</p>
<form method="POST" action="master-driver.php">
<!--Refresh on submit-->
<pre>
Order Number		<input type="text" name="up_ono" size="12">
Driver ID		<input type="text" name="up_did" size="12">
</pre>
<!--define variables to pass the value-->
<input type="submit" value="update" name="updatedid">
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
		$e = OCI_Error($db_conn); // For OCIParse errors pass the       
		// connection handle
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

}

// prints drivers from the select statement
function printResult($result) {
	echo "<br>Driver data:<br>";
	echo "<table>";
	echo "<tr>
			<th><pre>Driver ID	</pre></th>
			<th><pre>First Name</pre></th>
			<th><pre>Last Name</pre></th>
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


// Connect Oracle...
if ($db_conn) {

	if (array_key_exists('insertdriver', $_POST)) {
	// insert a driver into the driver table
		$tuple = array (
			":bind1" => $_POST['ins_did'],
			":bind2" => $_POST['ins_dfname'],
			":bind3" => $_POST['ins_dlname'],
			":bind4" => $_POST['ins_dlocation'],
			":bind5" => $_POST['ins_startshift'],
			":bind6" => $_POST['ins_endshift'],
			":bind7" => $_POST['ins_dstatus']
		);
		$alltuples = array (
			$tuple
		);
		executeBoundSQL("INSERT INTO driver 
						 VALUES(:bind1, :bind2, :bind3,:bind4, :bind5, :bind6, :bind7)",
						 $alltuples);
		OCICommit($db_conn);

	} else
		if (array_key_exists('deletedriver', $_POST)) {
			// delete a driver from the driver table
			$tuple = array (
				":bind1" => $_POST['del_did']
			);
			$alltuples = array (
				$tuple
			);
			executeBoundSQL("DELETE FROM driver 
							 WHERE did=:bind1", 
							 $alltuples);
			OCICommit($db_conn);
		}
		else
			if (array_key_exists('updatedid', $_POST)) {
				// getting data from user to update the table
				$tuple = array (
					":bind1" => $_POST['up_ono'],
					":bind2" => $_POST['up_did']
				);
				$alltuples = array (
					$tuple
				);
			
				executeBoundSQL("UPDATE orders 
								 SET did=:bind2 
								 WHERE ono=:bind1",
								 $alltuples);
				echo "Driver has been added to order.";
				OCICommit($db_conn);
			}

	if ($_POST && $success) {
		header("location: master-driver.php");
	} else {
		// Select driver data
		$result = executePlainSQL("SELECT * 
								   FROM driver");
		printResult($result);
	}

	//Commit to save changes...
	OCILogoff($db_conn);
} else {
	echo "cannot connect";
	$e = OCI_Error();
	echo htmlentities($e['message']);
}

?>