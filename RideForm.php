<!-- SPENCER HAGAN
	Grab's some info from logged in user (cars, locations) and allows user
	to create a new ride -->
<!DOCTYPE html>
<html>
<style>
.select_text {
	width: 100%;
	padding: 12px 20px;
	margin: 8px 0;
	display: inline-block;
	border: 1px solid #ccc;
	border-radius: 4px;
	box-sizing: border-box;
}

.submit_btn {
	width: 100%;
	background-color: #007bff;
	color: white;
	padding: 14px 20px;
	margin: 8px 0;
	border: none;
	border-radius: 4px;
	cursor: pointer;
}

.date_time {
	width: 15%;
	padding: 12px 20px;
	margin: 8px 0;
	border: 1px solid #ccc;
	border-radius: 4px;
	box-sizing: border-box;
}

.date_time input[type=submit]:hover {
	background-color: #0056b3;
}

.new_ride_form {
	border-radius: 5px;
	background-color: #f2f2f2;
	padding: 20px;
}
</style>
<body>
	
<?php
function genRideForm($db) {
	$uid = $_SESSION['uid'];
	
	$query = "SELECT license_plate, make, model, seats FROM Car WHERE uid=$uid";
	$res = $db->query($query);
	
	if($res != FALSE) {
?>
	<div class="new_ride_form">
		<FORM name='fmRide' method='POST' action='index.php?menu=procNewRide'>
			<label for='car'>Choose a car:</label>
				<select name="car" id=car" class="select_text">
				<?php
				while($row = $res->fetch()) {
					$vid = $row['license_plate'];
					$make = $row['make'];
					$model = $row['model'];
					$seats = $row['seats'];
					print "<option value='$seats'>$make $model</option>";
				}
				?>
				</select>
			<label for='dest'>Choose a destination and time:</label>
				<INPUT type='text' class="select_text" name='dest' placeholder='destination' />
                	<INPUT type="datetime-local" class="date_time" id="date" name="date" />	
			<INPUT type='submit' class="submit_btn" value="Add Ride" />
		</FORM>
	</div>

<?php
	}
	else {
		print "<P>Failed to load form or pull data</P>\n";
	}
} //close genRideForm

function processRide($db, $uid, $rideData) {
	$seats = $rideData['car'];
	$dest = $rideData['dest'];
	$date = $rideData['date'];
	
	
	//query to insert the ride
	$query = "INSERT INTO Ride(destination, available_seats, dateTime, uid) "
		. "VALUE('" . $dest . "', $seats, '" . $date . "', $uid)";
	//print "<P>$query</P>\n";
	$res = $db->query($query);
	
	if($res != FALSE) {
		print "<P>Ride Created </P>\n";
		header("refresh:2;url=index.php");
	}
	else {
		print "<P>Failed to create ride </P>\n";
	}
} //close processRide

?>

</body>
</html>
