<?php
session_start();error_reporting(E_ALL);
ini_set('display_errors', 1);

//the form data from the user
$newName = $_GET["Title"];  

//replace any whitespaces with '+' needed for multi-word strings
$searchName = preg_replace('/\s+/', '+', $newName);

//make the call with the search string, returning json object
$jsonObj = file_get_contents("http://www.omdbapi.com/?t=$searchName");

//decode json object into an array
$myArr = (json_decode($jsonObj, true));

if($myArr['Response'] != 'False') {
	//Genre is sometime multiple categories
	//example [Genre] => Action, Horror, Thriller 
	//using explode() to take the first category listed
	$genre = explode(",", $myArr['Genre']);
	//Runtime is a string of [number of minutes] + 'min'
	//example:  [Runtime] => 93 min
	//using explode() to capture only the number of minutes
	$time = explode(" ", $myArr['Runtime']);
	$image = $myArr['Poster']; 
?>
<?php

//hold variables in the session to be consumed by addVideo.php
$_SESSION['imdbID'] = $myArr['imdbID'];
$_SESSION['Title'] = $myArr['Title'];
$_SESSION['Rated'] = $myArr['Rated'];
$_SESSION['Genre'] = $genre[0];
$_SESSION['Runtime'] = $time[0];
$_SESSION['Poster'] = $myArr['Poster'];

header("refresh:3;url=addVideo.php" );
echo "Calling the Open Movie Database API...";

}
else {
	echo $myArr['Error'];
	echo '<br>Click <a href="interface.php">here</a>'; 
		echo ' to return to previous screen';
		exit();
} 
?>