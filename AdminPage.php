<head>
    <link rel="stylesheet" href="admin.css" />
</head>
<body>
	<!-- Page for site administrators.
		Here admins will be able to view reviews, delete or modify them to moderate.
		View list of users and ban them if needed. Also easy access to every user's profile page.-->
	<?php	
	
		//Queries for users
		$userQuery = "SELECT uid, name, email, updated_at FROM User";
		$userRes = $db->query($userQuery);
		
		//Queries for reviews, sorted by most recent
		$reviewQuery = "SELECT * FROM";
		
		if($userRes != FALSE) {
			//display a list of all users, if admin clicks on one it should load their profile page
			?>
			<div class='row' style='margin-left:30px'>
				<div class="col-sm-4">
					<label for='fmProf'>View Profiles:</label><br>
					<form method="POST" action="index.php?menu=otherProfile" name="fmProf">
					<?php
					print "User : Email : Last_Updated<SELECT name='prof'>\n";
					while($row = $userRes->fetch()) {
						$uid = $row['uid'];
						$name = $row['name'];								
						$email = $row['email'];
						$updated_at = $row['updated_at'];
						if($uid != $_SESSION['uid']) {
							print "<OPTION value='" . $uid . "'> $name : $email : $updated_at</OPTION>\n";
						}
					}
					print "</SELECT><BR /> <BR />\n";
					?>
					<INPUT type="submit" class="submit_btn" value="View Profile" />
					</form>
				</div>
			</div>
		<?php
		}
	?>	
</body>
