<nav class="navbar navbar-expand-lg navbar-light bg-light">
	<a class="navbar-brand" href="index.php">Gettysburg CarPooling</a>
	<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
		<span class="navbar-toggler-icon"></span>
	</button>
	<div class="collapse navbar-collapse" id="navbarNav">
		<ul class="navbar-nav">
			<li class="nav-item active">
				<a class="nav-link" href="index.php?menu=home">Home</a>
			</li>
			<!-- SPENCER: Checking Session for a signed in user, displays differently based on this -->
			<?php if (!isset($_SESSION['uid'])) { ?>
			<li class="nav-item">
				<a class="nav-link" href="index.php?menu=profile">Profile</a>
			</li>
			<?php } else { ?>
			<li class="nav-item active">
				<a class="nav-link" href="index.php?menu=profile">Profile</a>
			</li>
			<?php } ?>
			<li class="nav-item">
				<a class="nav-link" href="index.php?menu=faq">FAQ/Support</a>
			</li>
			<li class="nav-item">
				<a class="nav-link" href="index.php?menu=about">About Us</a>
			</li>
		</ul>
		<ul class="navbar-nav ml-auto">
			<!-- SPENCER: Checking Session for a signed in user, displays differently based on this -->
			<?php if (!isset($_SESSION['uid'])) { ?>
				<form class="form-inline" action="index.php" method="POST">
					<div class="form-group">
						<input type="text" class="form-control" placeholder="User_Id" name="uid" required>
					</div>
					<button type="submit" class="btn btn-default">Login</button>
				</form>
			<?php } else { ?>
				<li class="nav-item active">
					<a class="nav-link" href="index.php?menu=logout">Logout</a>
				</li>
			<?php } ?>
		</ul>
	</div>
</nav>
