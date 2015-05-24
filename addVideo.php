<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
include 'pwd.php';

$validInput = true;
$newImdbID = $_SESSION['imdbID'];
$newName = $_SESSION['Title'];  
$newRating = $_SESSION['Rated'];
$newCategory = $_SESSION['Genre']; 
$newLength = $_SESSION['Runtime']; 
$newPoster = $_SESSION['Poster'];

//validate that all parameters exists

if(!$newName) {
	echo "<br>";
	echo "Missing parameter: Title";
	$validInput = false;
}

//removing checks for category and length entries
//these are not required fields
if(!$newCategory) {
	echo "<br>";
	echo "Missing parameter: Category";
	$validInput = false;
}
if(!$newLength) {
	echo "<br>";
	echo "Missing parameter: Length";
	$validInput = false;
}

//validate that length is used correctly (if used)
if($newLength) {
	if(!is_numeric($newLength) || ($newLength < 0)) {
		echo "<br>";
		echo "Length must be a positive integer";
		$validInput = false;
	}
}

if(!$newCategory) {
	$newCategory = NULL;
} 

if(!$newLength) {
	$newLength = NULL;
}

if($validInput) {
$mysqli = new mysqli("oniddb.cws.oregonstate.edu","etterm-db",$passwd,"etterm-db");
if(!$mysqli || $mysqli->connect_errno) {
	echo "Connection Error ".$mysqli->connect_errno ." ".$mysqli->connect_error;
} 

/* Prepared statement, stage 1: prepare */
if (!($stmt = $mysqli->prepare("INSERT INTO videoInventoryImproved(imdbID,Title,Genre,Runtime,Rated) VALUES (?,?,?,?,?)"))) {
    echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
}

/* Prepared statement, stage 2: bind and execute */
if (!$stmt->bind_param("sssis", $newImdbID,$newName,$newCategory,$newLength,$newRating)) {
    echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
}

if (!$stmt->execute()) {
    echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
}
echo "Adding to inventory...".'<br>'.'<br>';
?>

<!-- Code in following line makes a new API call to fetch the movie poster -->
<img src="http://img.omdbapi.com/?apikey=<?php echo $myKey?>&i=<?php echo $newImdbID?>" alt="Poster Image Not Found"/>
<?php 
echo '<br>'.'<br>'; 

echo '<br>Click <a href="interface.php">here</a>'; 
		echo ' to return to inventory screen';
}
else {
	echo '<br>Execute Failed: Click <a href="interface.php">here</a>'; 
		echo ' to return to previous screen';
		exit();
}

mysqli_close($mysqli);
?>