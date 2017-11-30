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
				<li><a href="master-client.php">Client Manager</a></li>
				<li>Sales Rep Manager</li>
				<li><a href="master-driver.php">Driver Manager</a></li>
			</ul>
		</nav>
	</header>

<!-- ADD NEW CLIENT - INSERT -->
<p>Add a new sales representative:</p>
<form method="POST" action="master-salesrep.php">
<!--Refresh on submit-->
<pre>
SalesRep ID	<input type="text" name="ins_sid" size="12">
First Name	<input type="text" name="ins_sfname" size="12">
Last Name	<input type="text" name="ins_slname" size="12">
</pre>
<!--define variables to pass the value-->
<input type="submit" value="insert" name="insertsalesrep">
</form>

<!-- REMOVE CLIENT - DELETE -->
<p>Remove a sales representative:</p>
<form method="POST" action="master-salesrep.php">
<!--Refresh on submit-->
<pre>
SalesRep ID	<input type="text" name="del_sid" size="12">
</pre>
<!--define variables to pass the value-->
<input type="submit" value="delete" name="deletesalesrep">
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

}

 // prints sales reps from the select statement
function printSalesReps($result) {
	echo "<br>Sales Rep data:<br>";
	echo "<table>";
	echo "<tr>
			<th><pre>Sales Rep ID	</pre></th>
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

// Connect Oracle...
if ($db_conn) {

	if (array_key_exists('insertsalesrep', $_POST)) {
	// insert a sales rep into the client
		$tuple = array (
			":bind1" => $_POST['ins_sid'],
			":bind2" => $_POST['ins_sfname'],
			":bind3" => $_POST['ins_slname'],
		);
		$alltuples = array (
			$tuple
		);
		// insert the new salesrep into the salesrep table
		executeBoundSQL("INSERT INTO salesrep 
						 VALUES(:bind1, :bind2, :bind3)",
						 $alltuples);
		OCICommit($db_conn);

	} else
		if (array_key_exists('deletesalesrep', $_POST)) {
			// delete the salesrep from the salesrep table
			$tuple = array (
				":bind1" => $_POST['del_sid']
			);
			$alltuples = array (
				$tuple
			);
			executeBoundSQL("DELETE FROM salesrep 
							 WHERE sid=:bind1",
							 $alltuples);
			OCICommit($db_conn);
		}

	if ($_POST && $success) {
		header("location: master-salesrep.php");
	} else {
		// Select salesrep data
		$result = executePlainSQL("SELECT * 
								   FROM salesrep");
		printSalesReps($result);
	}

	//Commit to save changes...
	OCILogoff($db_conn);
} else {
	echo "cannot connect";
	$e = OCI_Error();
	echo htmlentities($e['message']);
}

?>

