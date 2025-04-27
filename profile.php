<head>
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
    	$reviewQuery = "SELECT reviewer_id, name, rating, review 
    			  FROM Rates JOIN User ON reviewer_id=uid
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
    			     WHERE (Ride.uid=$uid OR Requests.passenger_ID=$uid) AND dateTime < NOW()
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
    		$created_date = new DateTime($date);
    		$dateCalDriver = $created_date->format('F Y');
?>
    
<!-- Display Section -->
<div class='profile_form'>
	<?php if($uid == $_SESSION['uid']) {
		print "<a class='submit_btn' href='index.php?menu=editProfile'>Edit Profile</a>";
	} ?>
	<div class='row' style='margin:30px'>
		<div class="col-sm-4">
			<img src="default_profile.png" alt="Profile Picture" class="user-avatar-large">
			<br><br>
			<!-- User info will be displayed here -->
			<h2><?php print $name; ?></h2>
			<h5>Member since <?php print $dateCalDriver; ?></h5>
			<h5>Contact Info:</h5>
			<h6><?php print $pnum; ?></h6>
			<h6><?php print $email; ?></h6>
			<br>
			<h4>Rating:</h4>
			<?php
			if ($rateRes->rowCount() > 0) {
				while ($rate = $rateRes->fetch()) {
					print "<p> ⭐" . $rate['Urating'] . "/5" . "</p>";
				}
			} else {
				print "<p>No rating found.</p>";
			}
			?>
			<hr class='d-sm-none'>
		</div>
		<div class='recent-reviews'>
			<!-- Payment Info -->
			<h4>Payment Info:</h4>
			<?php
			if ($payRes->rowCount() > 0) {
				while ($pay = $payRes->fetch()) {
					$type = $pay['payment_type'];
					$username = $pay['payment_username'];
					if($type == "Cash") {
						print "<div class='review'>";
                        				print "<p><strong>$type</strong></p>";
                    				print "</div>";
					}
					else {
						print "<div class='review'>";
                        				print "<p><strong>$type</strong> @$username</p>";
                    				print "</div>";
					}
				}
			} else {
				print "<p>No payment info found.</p>";
			}
			?>
		</div>
		<div class='recent-reviews'>
			<!-- Display the last three most recent reviews of the user-->
			<h4>Recent Reviews</h4>
			<?php
			if ($revsRes->rowCount() > 0) {
				while ($revs = $revsRes->fetch()) {
					$name = $revs["name"];
					$review = $revs["review"];
					$rating = $revs["rating"];
					
					print "<div class='review'>";
                        			print "<p><strong>$name: </strong>$review</p>";
                        			print "<p>⭐ $rating/5</p>";
                    			print "</div>";
				}
			} else {
				print "<h6>Looks like everyone enjoyed the ride so much they forgot to review it. 🚗💨🫠</h6>";
			}
			?>
			</ul>
		</div>
	</div>
	<br>
	<br>
	<div class='row' style="margin-left:15px; margin-right:15px;">
		<div class='recent-reviews'>
			<!-- Display the cars of the user-->
			<button type="button" class="btn info-btn" data-toggle="collapse" data-target="#cars">Their's car(s)</button>
			<ul id="cars" class="collapse list-group">
				<div class="recent-reviews">
					<?php
					if ($carsRes->rowCount() > 0) {
						while ($car = $carsRes->fetch()) {
							$plate = $car['license_plate'];
							$color = $car['color'];
							$make  = $car['make'];
							$model = $car['model'];
							print "<div class='review'><p> A $color $make $model w/ Plate Number: $plate </p></div>";
						}
					} else {
						print "<div class='review'><p>No cars found.</li>";
					}
					?>
				</div>
			</ul>
		</div>
		<div class='recent-reviews'>
			<!-- Display the last three most recent rides user was in, passenger or driver-->
			<button type="button" class="btn info-btn" data-toggle="collapse" data-target="#lastRides">Recent Rides</button>
			<ul id="lastRides" class="collapse list-group">
				<div class="recent-reviews">
			<?php
			if ($lastRidesRes->rowCount() > 0) {
				while ($ride = $lastRidesRes->fetch()) {
					$dest = $ride['destination'];
					
					$rideTime = $ride['dateTime'];
					$date = new DateTime($rideTime);
    					$dateCal = $date->format('l, F j');
    					$dateTime = $date->format('g:i A');
					
					print "<div class='review'><p>Went to $dest on $dateCal at $dateTime</p></div>";
				}
			} else {
				print "<div class='review'>No rides found.</div>";
			}
			?>
				</div>
			</ul>
		</div>
		<div class='col-sm-4'>
			<!-- Display the next three rides where the user is the driver, if any-->
			<button type="button" class="btn info-btn" data-toggle="collapse" data-target="#nextRides">Upcoming Rides</button>
			<ul id="nextRides" class="collapse list-group">
				<div class="recent-reviews">
					<?php
					if ($nextRidesRes->rowCount() > 0) {
						while ($ride = $nextRidesRes->fetch()) {
							$dest = $ride['destination'];
							
							$rideTime = $ride['dateTime'];
							$date = new DateTime($rideTime);
		    					$dateCal = $date->format('l, F j');
		    					$dateTime = $date->format('g:i A');
							
							print "<div class='review'><p>Going to $dest on $dateCal at $dateTime</p></div>";
						}
					} else {
						print "<div class='review'><p>No upcoming rides found.</p></div>";
					}
					?>
				</div>
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

//Page for a signed in user to edit their profile info (user info, payment options, cars, etc.)
function genEditUserForm($db, $user) {
	$uid = $user;
    
    	//User Info
    	$query = "SELECT * FROM User WHERE uid=$uid";
    	
    	//User Payment Info
    	$payQuery = "SELECT * FROM PaymentInfo WHERE uid=$uid ORDER BY payment_type";
    	$payRes = $db->query($payQuery);
    	
    	//Payment TypeInfo
    	$payTypeQuery = "SELECT DISTINCT payment_type FROM PaymentInfo";
    	$payTypeRes = $db->query($payTypeQuery);
    	
    	//Cars the user has
    	$carsQuery = "SELECT * FROM Car WHERE uid=$uid";
    	$carsRes = $db->query($carsQuery);
    	
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
	<a class='submit_btn' style='margin-bottom:30px' href='index.php?menu=profile'>Go Back</a>
	<br><br>
	<div class='row' style='margin-left:30px'>
	
	
		<!-- User Info Editing Section -->
		<div class='col-sm-4'>
			<FORM name='editUserInfo' method='POST' action='index.php?menu=procUserEdits'>
				<INPUT type='hidden' name='editUserFm' value='1' />
				<h2>User Info</h2>	
				<label for='name'>Name:</label><br>
					<?php print "<INPUT type='text' style='width: 200px' name='name' value='$name' /><br>"; ?>				
				<label for='pnum'>Phone Number:</label><br>
					<?php print "<INPUT type='text' style='width: 200px' name='pnum' value='$pnum' /><br>"; ?>					
				<label for='email'>Email:</label><br>
					<?php print "<INPUT type='email' style='width: 200px' name='email' value='$email' /><br>"; ?>
				<INPUT type='submit' class="submit_btn" style="width:50%" value="Edit User Info" />
			</FORM>
		</div>
		
		
		<!-- User Payment Info Editing Section -->
		<div class='col-sm-4'>
			<FORM name='editPayInfo' method='POST' action='index.php?menu=procUserEdits'>
				<h2>Payment Info</h2>
				<table class='table'>
				<?php
				while ($pay = $payRes->fetch()) {
					$type = $pay['payment_type'];
					$username = $pay['payment_username'];
					if($type == "Cash") {
						print "<tr><td>$type</td><td><INPUT type='submit' class='button' value='Delete' /></td><tr>";
					}
					else {
						print "<tr><td>$type : $username</td><td><INPUT type='submit' class='button' value='Delete' /></td><tr>";
					}
				}
				?>
				<!-- Options for what type of payment, followed by the username for it -->
				<label for='newPay'>Add a new payment:</label><br>
					<?php print "<tr><td><INPUT type='text' name='payment_type' placeholder='Type' /></td>"; ?>				
					<?php print "<td><INPUT type='text' name='payment_username' placeholder='Username' /></td></tr>"; ?>
					<tr><td><INPUT type='submit' class="submit_btn" style="width:75%" value="Add New Payment" /></td></tr>
				</table>				
			</FORM>	
			</div>
		<!-- User Car Info Editing Section -->
		<div class='col-sm-4'>
			<h2>Cars</h2>	
					
		</div>
	</div>
</div>
<?php
	}
	else {
		print "<P>Failed to edit user profile! Please Reload!</P>";
	}
}

//Process any edits made from the profile edit page. Update in database and then refresh to updated profile.
function processUserEdits($db, $editData) {
	print "<P>Process User Info!</P>";
	if(isset($editData['editUserFm'])) {
		$name  = $editData['name'];
		$pnum  = $editData['pnum'];
		$email = $editData['email'];
		$uid   = $_SESSION['uid'];
		
		$sqlUpdate = "UPDATE User SET name='$name', pnum='$pnum', email='$email' WHERE uid=$uid;
";
		//print "<P>$sqlUpdate</P>";
		$res = $db->query($sqlUpdate);
		
		if ($res != FALSE) {
    			print "<P>User Info updated successfully</P>\n";
		} else {
			print "<P>Error updating user info</P>\n";
		}
	}
	else {
		print "<P>No User Edits Detected</P>";
	}
	
	header("refresh:2;url=index.php?menu=profile");
}

//function to allow an admin to view any user's profile from the AdminPage.
function genProfilefromForm($db, $fmData) {
	$uid = $fmData['prof'];
	
	?>
	<!-- Option for admin to return to adminPage after reviewing a user's profile. This function is only called from adminPage -->
	<br>
	<div style='margin-left:10px'>
		<a class='submit_btn' href='index.php?menu=admin'>Go Back</a>
	</div>
	<?php

	genUserProfile($db, $uid);
}
?>
</body>
