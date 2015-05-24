<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include 'pwd.php';

$value = 'available'; 
//echo $value;

$mysqli = new mysqli("oniddb.cws.oregonstate.edu","etterm-db",$passwd,"etterm-db");
if(!$mysqli || $mysqli->connect_errno) {
	echo "Connection Error ".$mysqli->connect_errno ." ".$mysqli->connect_error;
}
//query for all rented videos
$result = mysqli_query($mysqli, "SELECT * FROM videoInventoryImproved WHERE rented = 'available'");
while($row = $result->fetch_assoc()){
    if($row['id'] == $_GET['indexkey']) {
    	$value = 'checked out';
    }
}

$stmt = $mysqli->prepare("UPDATE videoInventoryImproved SET rented = '$value' WHERE id = ?");
$stmt->bind_param('i', $_GET['indexkey']);
$stmt->execute(); 
$stmt->close();

mysqli_close($mysqli);
header("refresh:3;url=interface.php" );
echo "Toggling Checkin/Checkout...";

?>