<?php
//error_reporting(E_ALL);
//ini_set('display_errors', 1);

include 'pwd.php';

?>
<a href="index.html">Back to Home</a><br />
<?php

//some code borrowed from http://community.spiceworks.com/topic/
//398696-have-users-edit-a-mysql-table-via-php-html
$mysqli = new mysqli("oniddb.cws.oregonstate.edu","etterm-db",$passwd,"etterm-db");
if(!$mysqli || $mysqli->connect_errno) {
	echo "Connection Error ".$mysqli->connect_errno ." ".$mysqli->connect_error;
}
$result = mysqli_query($mysqli,"SELECT * FROM videoInventoryImproved");
$filter = mysqli_query($mysqli,"SELECT * FROM videoInventoryImproved");

?>
<h1>Video Inventory System</h1>

<table border="1" id="videoInventoryImproved">
	<caption>Current Video Inventory</caption>
	<thead>
		<tr>
			<th>ID</th>
			<th>IMDB ID</th>
			<th>Name</th>
			<th>Category</th>
			<th>Length(mins)</th>
			<th>Rated</th>
			<th>Rented</th>
			<th>Delete</th>
			<th>CheckIn/CheckOut</th>
		</tr>
	</thead>
	<tbody>

<h4>Submit this form to add a new video</h4>
<form action="apiTest.php" method="get">
	Enter Video Title: <input name="Title" type="text"><br>
	<input type="submit"><br><br>
</form> 

<h4>Click this button to delete all videos in inventory</h4>
<form action="flushDB.php" method="get">
	<input type="Submit" value="Clear Inventory">
</form>

<h4>Filter by Category</h4>
<form action="interface.php" method="get">

<?php
$myArr = array();
echo '<select name="myCat">';
while($entry = mysqli_fetch_array($filter)) {
	if(!in_array($entry['Genre'], $myArr)) {
		if($entry['Genre'] != NULL) {
		array_push($myArr, $entry['Genre']);
		echo "<option value='".$entry['Genre']."'>".$entry['Genre']."</option>";
		}	
	}
}
if(!empty($myArr)) {
	echo '<option value="All">All Videos</option>';
}
echo '<input type = "submit" value="Filter">';
echo '</select>';
echo '</form>';

if($_GET['myCat']) {
	echo '<br>'.'Current Filter: '.$_GET["myCat"];
}
else {
	$_GET['myCat'] = 'All';
} 

$valCat = $_GET['myCat'];

//populate table with filtered db contents
if(!($valCat == 'All')) {
	while($row = mysqli_fetch_array($result)) {
		if($row['Genre'] == $_GET['myCat']) {
		echo "<tr>";
		echo "<td>".$row['id']."</td>";
		echo "<td>".$row['imdbID']."</td>";
		echo "<td>".$row['Title']."</td>";
		echo "<td>".$row['Genre']."</td>";
		echo "<td>".$row['Runtime']."</td>";
		echo "<td>".$row['Rated']."</td>";
		echo "<td>".$row['rented']."</td>";
		echo "<td><form method=\"get\" action=\"delVideo.php\">";
		echo "<input type=\"hidden\" name=\"indexkey\" value=\"".$row['id']."\">";
		echo "<input type=\"submit\" value=\"Delete\">";
		echo "</form>";
		echo "<td><form method=\"get\" action=\"checkout.php\">";
		echo "<input type=\"hidden\" name=\"indexkey\" value=\"".$row['id']."\">";
		echo "<input type=\"submit\" value=\"CheckIn/Checkout\">";
		echo "</form>";
		echo "</tr>";
		}
	}
}
else {
	//unfiltered (all movies to be listed)
	while($row = mysqli_fetch_array($result)) {
		echo "<tr>";
		echo "<td>".$row['id']."</td>";
		echo "<td>".$row['imdbID']."</td>";
		echo "<td>".$row['Title']."</td>";
		echo "<td>".$row['Genre']."</td>";
		echo "<td>".$row['Runtime']."</td>";
		echo "<td>".$row['Rated']."</td>";
		echo "<td>".$row['rented']."</td>";
		echo "<td><form method=\"get\" action=\"delVideo.php\">";
		echo "<input type=\"hidden\" name=\"indexkey\" value=\"".$row['id']."\">";
		echo "<input type=\"submit\" value=\"Delete\">";
		echo "</form>";
		echo "<td><form method=\"get\" action=\"checkout.php\">";
		echo "<input type=\"hidden\" name=\"indexkey\" value=\"".$row['id']."\">";
		echo "<input type=\"submit\" value=\"CheckIn/Checkout\">";
		echo "</form>";
		echo "</tr>";
		}
}
mysqli_close($mysqli); 
?>