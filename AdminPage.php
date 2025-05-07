<head>
    <link rel="stylesheet" href="admin.css" />
</head>
<body>
	<?php
		session_start();
		include_once 'db_connect.php';

		$userQuery = "SELECT uid, name, email, updated_at, is_banned FROM User";
		$userRes = $db->query($userQuery);
	?>

	<div class='row' style='margin-left:30px'>
		<div class="col-sm-4">
			<label for='fmProf'>View Profiles:</label><br>
			<form method="POST" action="index.php?menu=otherProfile" name="fmProf" id="fmProfForm">
				<?php
				print "User : Email : Last_Updated";
				print "<select name='prof' id='userSelect'>\n";
				while($row = $userRes->fetch()) {
					$uid = $row['uid'];
					$name = $row['name'];
					$email = $row['email'];
					$updated_at = $row['updated_at'];
					$is_banned = $row['is_banned'];
					if($uid != $_SESSION['uid']) {
						$statusText = $is_banned ? " (BANNED)" : "";
						print "<option value='$uid' data-banned='$is_banned'>$name : $email : $updated_at$statusText</option>\n";
					}
				}
				print "</select><br /><br />\n";
				?>

				<input type="submit" class="submit_btn" value="View Profile" />
				<button type="button" class="ban_btn" id="banBtn">Ban This User</button>

				<div class="ban_user_block" id="banConfirmPopup" style="display: none; margin-top: 10px;">
					<p id="banConfirmText">Ban this user?</p>
					<button type="button" onclick="confirmBan()">Yes</button>
					<button type="button" onclick="cancelBan()">No</button>
				</div>
			</form>

			<iframe name="hiddenFrame" style="display: none;"></iframe>
			<form id="banForm" method="POST" target="hiddenFrame" action="ban_user.php" style="display: none;">
				<input type="hidden" name="uid" id="banUid" />
			</form>

			<div id="banSuccessMessage" style="display: none; color: green; margin-top: 1rem; font-weight: bold;">
				User successfully banned.
			</div>
			<div id="alreadyBannedMessage" style="display: none; color: red; margin-top: 1rem; font-weight: bold;">
				This user was banned.
			</div>
		</div>
	</div>

	<script>
		const select = document.getElementById('userSelect');
		const banBtn = document.getElementById('banBtn');
		const popup = document.getElementById('banConfirmPopup');
		const banSuccess = document.getElementById('banSuccessMessage');
		const alreadyBanned = document.getElementById('alreadyBannedMessage');
		const banText = document.getElementById('banConfirmText');

		select.addEventListener('change', () => {
			banSuccess.style.display = 'none';
			alreadyBanned.style.display = 'none';
			popup.style.display = 'none';
		});

		banBtn.addEventListener('click', () => {
			const selectedOption = select.selectedOptions[0];
			const isBanned = selectedOption.getAttribute('data-banned');
			banText.textContent = "Ban this user?";
			popup.style.display = 'block';
		});

		function confirmBan() {
			const selectedOption = select.selectedOptions[0];
			const uid = selectedOption.value;
			const isBanned = selectedOption.getAttribute('data-banned');

			if (isBanned === "1") {
				popup.style.display = 'none';
				alreadyBanned.style.display = 'block';
				return;
			}

			document.getElementById('banUid').value = uid;
			document.getElementById('banForm').submit();
			popup.style.display = 'none';
			banSuccess.style.display = 'block';

			setTimeout(() => {
				location.reload();
			}, 2000);
		}

		function cancelBan() {
			popup.style.display = 'none';
		}
	</script>
</body>