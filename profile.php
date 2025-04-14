<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gettysburg CarPool</title>

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous" />
    <link rel="stylesheet" href="profile.css" />
</head>
<body>
<?php
//SPENCER HAGAN
//	Profile Page to display the information of a given user, such as their cars,
//	the last 3 rides they were in, the next 3 rides they're in, and their rating.
	
function genUserProfile($db, $user) {
	$uid = $user;
    
    	//User Info
    	$query = "SELECT * FROM User WHERE uid=$uid";
    	
    	//User Payment Info
    	$payQuery = "SELECT * FROM PaymentInfo WHERE uid=$uid ORDER BY payment_type";
    	$payRes = $db->query($payQuery);
    	
    	//User's rating
    	$rateQuery = "SELECT ROUND(AVG(rating), 2) AS Urating FROM Rates WHERE reviewed_id=$uid GROUP BY reviewed_id";
    	$rateRes = $db->query($rateQuery);
    	
    	//Reviews of the user
    	$reviewQuery = "SELECT reviewer_id, rating, review 
    			  FROM Rates 
    			  WHERE reviewed_id=$uid 
    			  ORDER BY dateTime 
    			  DESC
    			  LIMIT 3";
    	$revsRes = $db->query($reviewQuery);
    	
    	//Cars the user has
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
<div class='profile_form' style='margin-top:30px'>
	<div class='row'>
		<div class="col-sm-4">
			<img src="default_profile.png" alt="Profile Picture" class="img-rounded" width="200" height="200">
			<br>
			<!-- User info will be displayed here -->
			<h2><?php print $name; ?></h2>
			<h5>Account created on: <?php print $created_at; ?></h5>
			<p>Phone number: <?php print $pnum; ?></p>
			<p>Email: <?php print $email; ?></p>
			<br>
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
			<hr class='d-sm-none'>
		</div>
		<div class='col-sm-4'>
			<!-- Payment Info -->
			<h3>Payment Info:</h3>
			<?php
			if ($payRes->rowCount() > 0) {
				while ($rate = $payRes->fetch()) {
					$type = $rate['payment_type'];
					$username = $rate['payment_username'];
					if($type == "Cash") {
						print "<p>$type</p>";
					}
					else {
						print "<p>$type: $username</p>";
					}
				}
			} else {
				print "<p>No payment info found.</p>";
			}
			?>
		</div>
		<div class='col-sm-4'>
			<!-- Display the last three most recent reviews of the user-->
			<h3>Recent Reviews</h3>
			<ul id="reviews" class="list-group">
			<?php
			if ($revsRes->rowCount() > 0) {
				while ($revs = $revsRes->fetch()) {
					print "<li class='list-group-item'>From " . $revs['reviewer_id'] . ": Rated " . $revs['rating'] . " stars, saying '" . $revs['review'] . "'" . "</li>";
				}
			} else {
				print "<li class='list-group-item'>No reviews found.</li>";
			}
			?>
			</ul>
		</div>
	</div>
	<br>
	<br>
	<div class='row'>
		<div class='col-sm-4'>
			<button type="button" class="btn info-btn" data-toggle="collapse" data-target="#cars">Their's car(s)</button>
			<ul id="cars" class="collapse list-group">
			<?php
			if ($carsRes->rowCount() > 0) {
				print "<table class='table table-striped'>";
				print "<tr><th> Plate </th><th> Color </th><th> Make </th><th> Model </th></tr>";
				while ($car = $carsRes->fetch()) {
					$plate = $car['license_plate'];
					$color = $car['color'];
					$make  = $car['make'];
					$model = $car['model'];
					print "<tr><td> $plate </td><td> $color </td><td> $make </td><td> $model </td></tr>";
				}
				print "</table>";
			} else {
				print "<li class='list-group-item'>No cars found.</li>";
			}
			?>
			</ul>
		</div>
		<div class='col-sm-4'>
			<!-- Display the last three most recent rides user was in, passenger or driver-->
			<button type="button" class="btn info-btn" data-toggle="collapse" data-target="#lastRides">Recent Rides</button>
			<ul id="lastRides" class="collapse list-group">
			<?php
			if ($lastRidesRes->rowCount() > 0) {
				while ($ride = $lastRidesRes->fetch()) {
					print "<li class='list-group-item'>" . $ride['destination'] . " on " . $ride['dateTime'] . "</li>";
				}
			} else {
				print "<li class='list-group-item'>No rides found.</li>";
			}
			?>
			</ul>
		</div>
		<div class='col-sm-4'>
			<!-- Display the next three rides where the user is the driver, if any-->
			<button type="button" class="btn info-btn" data-toggle="collapse" data-target="#nextRides">Upcoming Rides</button>
			<ul id="nextRides" class="collapse list-group">
			<?php
			if ($nextRidesRes->rowCount() > 0) {
				while ($ride = $nextRidesRes->fetch()) {
					print "<li class='list-group-item'>" . $ride['destination'] . " on " . $ride['dateTime'] . "</li>";
				}
			} else {
				print "<li class='list-group-item'>No upcoming rides found.</li>";
			}
			?>
			</ul>
			<br>
		</div>
	</div>
</div>
<?php
	}
	else {
		print "<P>Failed to load user profile! Please Reload!</P>";
	}
}
?>
</body>
