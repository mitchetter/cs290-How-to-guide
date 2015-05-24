<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include 'pwd.php';

$mysqli = new mysqli("oniddb.cws.oregonstate.edu","etterm-db",$passwd,"etterm-db");
if(!$mysqli || $mysqli->connect_errno) {
	echo "Connection Error ".$mysqli->connect_errno ." ".$mysqli->connect_error;
} 

mysqli_query($mysqli,'TRUNCATE TABLE videoInventoryImproved');
mysqli_close($mysqli);
header("refresh:3;url=interface.php" );
echo "Clearing inventory...";

?>