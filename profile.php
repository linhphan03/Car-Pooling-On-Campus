<!-- SPENCER HAGAN
Profile Page to display the information of a given user, such as their cars,
the last 3 rides they were in, the next 3 rides they're in, and their rating.

-->

<!DOCTYPE html>
<html>
<head>
<title>User Profile</title>
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
<style>
  .profile-card {
    border: 1px solid #ccc;
    border-radius: 5px;
    padding: 40px;
    margin-bottom: 20px;
  }
  .profile-info-card {
    border: 1px solid #ccc;
    border-radius: 5px;
    padding: 40px;
    margin-bottom: 20px;
    width: 500px;
  }
  .profile-image {
    width: 150px;
    height: 150px;
    border-radius: 50%;
    object-fit: cover;
  }
</style>
</head>
<body>

<script src="scripts.js"></script>
 
    <!-- Query for user info -->
<?php
	include_once("db_connect.php");
	
	$uid = $_SESSION['uid'];
    
    	$query = "SELECT * FROM User WHERE uid=$uid";
    	//print "<P>$query</P>";
    	
    	$rateQuery = "SELECT AVG(rating) AS Urating FROM Rates WHERE reviewed_id=$uid GROUP BY reviewed_id";
    	$rateRes = $db->query($rateQuery);
    	
    	$reviewQuery = "SELECT reviewer_id, rating, review FROM Rates WHERE reviewed_id=$uid";
    	$revsRes = $db->query($reviewQuery);
    	
    	$carsQuery = "SELECT * FROM Car WHERE uid=$uid";
    	$carsRes = $db->query($carsQuery);
    	
    	//Previous rides user either drived for or were a passenger in
    	$lastRidesQuery = "SELECT * 
    			     FROM Ride JOIN Requests ON Ride.ride_ID=Requests.ride_ID 
    			     WHERE Ride.uid=$uid OR Requests.passenger_ID=$uid
    			     ORDER BY dateTime DESC
    			     LIMIT 3";
    	$lastRidesRes = $db->query($lastRidesQuery);
    	
    	//Upcoming Rides the user is the driver for
    	$nextRidesQuery = "SELECT destination, dateTime 
    			     FROM Ride 
    			     WHERE uid=$uid AND dateTime > NOW() 
    			     ORDER BY dateTime ASC 
    			     LIMIT 3";
    	$nextRidesRes = $db->query($nextRidesQuery);
    	
    	$res = $db->query($query);
    	
    	if($res != FALSE) {
    		while($row = $res->fetch()) {
			
			$uid = $row['uid'];
			$pnum = $row['pnum'];
			$email = $row['email'];
			$name = $row['name'];
			$created_at = $row['created_at'];
		}
?>
    
<!-- Display Section -->
<div class='container' style='margin-top:30px'>
	<div class='row'>
		<div class='col-md-2'>
			<div class="profile-card">
				<img src="profile.jpg" alt="Profile Picture" class="profile-image img-thumbnail">
			</div>
			<br>
			<div class="profile-card">
				<h3>Rating:</h3>
				<?php
				if ($rateRes->rowCount() > 0) {
					while ($rate = $rateRes->fetch()) {
						print "<p>" . $rate['Urating'] . "/5" . "</p>";
					}
				} else {
					print "<p>No rating found.</p>";
				}
				?>
			</div>
			<hr class='d-sm-none'>
		</div>
		<div class='col-sm-8'>
			<div class="col-sm-8 profile-info-card" id='user-info'>
				<!-- User info will be displayed here -->
				<h2><?php print $name; ?></h2>
				<h5>Account created on: <?php print $created_at; ?></h5>
				<p>Phone number: <?php print $pnum; ?></p>
				<p>Email: <?php print $email; ?></p>
			</div>
			<div class="profile-card">
				<h3>Recent Reviews:</h3>
				<?php
				if ($revsRes->rowCount() > 0) {
					while ($revs = $revsRes->fetch()) {
						print "<p>From " . $revs['reviewer_id'] . ": Rated " . $revs['rating'] . " stars, saying '" . $revs['review'] . "'" . "</p>";
					}
				} else {
					print "<p>No reviews found.</p>";
				}
				?>
			</div>
			<div class="col-sm-8 profile-info-card" id='cars-table'>
				<h3>Cars Owned</h3>
				<ul>
				<?php
				if ($carsRes->rowCount() > 0) {
					print "<table class='table table-bordered'>";
					print "<tr><th> Plate </th><th> Color </th><th> Make </th><th> Model </th></tr>";
					while ($car = $carsRes->fetch()) {
						$plate = $car['license_plate'];
						$color = $car['color'];
						$make  = $car['make'];
						$model = $car['model'];
						print "<tr><th> $plate </th><th> $color </th><th> $make </th><th> $model </th></tr>";
					}
					print "</table>";
				} else {
					print "<li>No cars found.</li>";
				}
				?>
				</ul>
			</div>
			<div class="col-sm-8 profile-info-card" id='last-rides'>
				<h3>Last 3 Rides</h3>
				<ul>
				<?php
				if ($lastRidesRes->rowCount() > 0) {
					while ($ride = $lastRidesRes->fetch()) {
						print "<li>" . $ride['destination'] . " on " . $ride['dateTime'] . "</li>";
					}
				} else {
					print "<li>No rides found.</li>";
				}
				?>
				</ul>
			</div>
			<div class="col-sm-8 profile-info-card" id='next-rides'>
				<h3>Next 3 Scheduled Rides</h3>
				<ul>
				<?php
				if ($nextRidesRes->rowCount() > 0) {
					while ($ride = $nextRidesRes->fetch()) {
						print "<li>" . $ride['destination'] . " on " . $ride['dateTime'] . "</li>";
					}
				} else {
					print "<li>No upcoming rides found.</li>";
				}
				?>
				</ul>
			</div>
			<br>
		</div>
	</div>
</div>
<?php
	}
	else {
		print "<P>Failed to load user profile! Please Reload!</P>";
	}
?>
	
</body>
</html>
