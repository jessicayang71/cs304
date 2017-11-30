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
				<li>Client Manager</li>
				<li><a href="master-salesrep.php">Sales Rep Manager</a></li>
				<li><a href="master-driver.php">Driver Manager</a></li>
			</ul>
		</nav>
	</header>

<!-- ADD NEW CLIENT - INSERT -->
<p>Add a new client:</p>
<form method="POST" action="master-client.php">
<!--Refresh on submit-->
<pre>
Client ID	<input type="text" name="ins_cid" size="12">
First Name	<input type="text" name="ins_cfname" size="12">
Last Name	<input type="text" name="ins_clname" size="12">
Company		<input type="text" name="ins_ccompany" size="12">
Street		<input type="text" name="ins_street" size="12">
City		<input type="text" name="ins_city" size="12">
Province	<input type="text" name="ins_province" size="12">
Postal Code	<input type="text" name="ins_postal" size="12">
Phone		<input type="text" name="ins_cphone" size="12">
</pre>
<!--define variables to pass the value-->
<input type="submit" value="insert" name="insertclient">
</form>

<!-- REMOVE CLIENT - DELETE -->
<p>Remove a client:</p>
<form method="POST" action="master-client.php">
<!--Refresh on submit-->
<pre>
Client ID	<input type="text" name="del_cid" size="12">
</pre>
<!--define variables to pass the value-->
<input type="submit" value="delete" name="deleteclient">
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

// prints clients from the select statement
function printClients($result) { 
	echo "<br>Client data:<br>";
	echo "<table>";
	echo "<tr>
			<th><pre>Client ID	</pre></th>
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

	if (array_key_exists('insertclient', $_POST)) {
	// insert a client into the client table
		$tuple = array (
			":bind1" => $_POST['ins_cid'],
			":bind2" => $_POST['ins_cfname'],
			":bind3" => $_POST['ins_clname'],
			":bind4" => $_POST['ins_ccompany'],
			":bind5" => $_POST['ins_street'],
			":bind6" => $_POST['ins_postal'],
			":bind7" => $_POST['ins_cphone'],
			":bind8" => $_POST['ins_city'],
			":bind9" => $_POST['ins_province']
		);
		$alltuples = array (
			$tuple
		);
		// insert the new client into the client table
		executeBoundSQL("INSERT INTO client 
						 VALUES(:bind1, :bind2, :bind3, :bind4, :bind5, :bind6, :bind7)",
						 $alltuples);
		// insert the city and province into the decomposed area table
		executeBoundSQL("INSERT INTO area 
						 VALUES(:bind6, :bind8, :bind9)",
						 $alltuples);
		OCICommit($db_conn);

	} else
		if (array_key_exists('deleteclient', $_POST)) {
			// delete a client from the client table
			$tuple = array (
				":bind1" => $_POST['del_cid']
			);
			$alltuples = array (
				$tuple
			);
			// delete the client from the client table
			executeBoundSQL("DELETE FROM client 
							 WHERE cid=:bind1",
							 $alltuples);
			OCICommit($db_conn);
		}

	if ($_POST && $success) {
		header("location: master-client.php");
	} else {
		// select client data
		$result = executePlainSQL("SELECT cid, cfname, clname 
								   FROM client");
		printClients($result);
	}

	//Commit to save changes...
	OCILogoff($db_conn);
} else {
	echo "cannot connect";
	$e = OCI_Error();
	echo htmlentities($e['message']);
}

?>

