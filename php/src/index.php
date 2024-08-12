<?php
//These are the defined authentication environment in the db service

// The MySQL service named in the docker-compose.yml.
$host = 'db';

// Database use name
$user = 'root';

//database user password
$pass = 'rahsia';

// database name
$mydatabase = 'malaysia';
// check the mysql connection status

$conn = new mysqli($host, $user, $pass, $mydatabase);

// select query
$sql = 'SELECT * FROM negeri';

if ($result = $conn->query($sql)) {
    while ($data = $result->fetch_object()) {
        $negeris[] = $data;
    }
}

foreach ($negeris as $negeri) {
    echo "<br>";
    echo $negeri->id . " " . $negeri->nama;
    echo "<br>";
}
?>