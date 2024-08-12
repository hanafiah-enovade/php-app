<?php
// Database credentials
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "malaysia";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
	die("Connection failed: " . $conn->connect_error);
}

// Fetch states from the database
$sql = "SELECT name FROM states";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
	echo "<h1>List of Malaysian States</h1>";
	echo "<ul>";
	// Output data of each row
	while($row = $result->fetch_assoc()) {
		echo "<li>" . $row["name"] . "</li>";
	}
	echo "</ul>";
} else {
	echo "0 results";
}

// Close connection
$conn->close();