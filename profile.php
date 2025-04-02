<!DOCTYPE html>
<body>
    <!-- Query for user info -->
<?php
	include_once("db_connect.php");
    
    	$query = "SELECT * FROM User WHERE uid=1";
    	//print "<P>$query</P>";
    	
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
    			<div class='col-sm-4'>
      				<h2>About Me</h2>
      				<h5>Photo:</h5>
      				<div class='fakeimg'>Placeholder till we have images (If we do that) generic image if they haven't added yet.</div>
      				<br>
      				<h3>Info</h3>
      				<!-- These will pull up some information if someone clicks on them, last 3 rides, next 3 rides, what cars they have added.-->
      				<ul class='nav nav-pills flex-column'>
        				<li class='nav-item'>
          					<a class='nav-link active' href='#'>Upcoming Rides</a>
        				</li>
        				<li class='nav-item'>
          					<a class='nav-link' href='#'>Recent Rides</a>
		 			</li>
		 			<li class='nav-item'>
		   				<a class='nav-link' href='#'>Cars</a>
		 			</li>
	      			</ul>
	      			<hr class='d-sm-none'>
	    		</div>
	    		<div class='col-sm-8'>
	    		<div class='col-sm-8' id='user-info'>
    				<!-- User info will be displayed here -->
			</div>
			<div id='cars-table' style='display:none;'>
    				<!-- Car table will be displayed here -->
			</div>
<?php
	      			print "<h2>$name</h2>";
	      			print "<h5>Account created on: $created_at</h5>";
	      			print "<p>Phone number: $pnum</p>";
	      			print "<p>Email: $email</p>";
?>
	      			<p>This a placeholder until I create the query to grab and display user info. This will be for the currently logged in user. Next step after this will be to display other user's profiles.</p>
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

<?php
function genCarTable() {
	$query = "SELECT * FROM Cars WHERE user_id = $uid"; // Adjust the query as needed
	$res = $db->query($query);

	if ($res != FALSE) {
    		print "<table class='table'>";
    		print "<thead><tr><th>Car ID</th><th>Model</th><th>Year</th></tr></thead><tbody>";
    		while ($row = $res->fetch()) {
        		print "<tr>";
        		print "<td>" . $row['make'] . "</td>";
        		print "<td>" . $row['model'] . "</td>";
        		print "<td>" . $row['color'] . "</td>";
        		print "</tr>";
    		}	
    		print "</tbody></table>";
	} else {
    		print "No cars found.";
	}
}
?>
