<?php
// database logingegevens
$db_hostname = 'localhost';
$db_username = '81918_beroeps2';
$db_password = 'Fpi!j078';
$db_database = '81918_beroeps2';

// error_reporting(E_ALL);
// ini_set('error_reporting', 1);

// Maak de database-verbinding
$mysqli = mysqli_connect($db_hostname, $db_username, $db_password, $db_database);

// Als de verbinding niet gemaakt kan worden: geef een melding
if (!$mysqli){
	echo "FOUT: Geen connectie naar database. <br>";
	echo " Errno: " . mysqli_connect_errno() . "<br>";
	echo " Error: " . mysqli_connect_error() .  "<br>";
	exit;
}
?>
