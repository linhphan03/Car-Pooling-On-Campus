<?php
include("db_connect.php");

if (isset($_GET["ride_ID"])) {
    $ride_ID = intval($_GET["ride_ID"]);

    $stmt = $db->prepare("SELECT * FROM Ride WHERE ride_ID = ?");
    $stmt->execute([$ride_ID]);
    $ride = $stmt->fetch();

    if ($ride) {
        ?>
        <h2>Ride Details</h2>
        <p><strong>Date:</strong> <?= htmlspecialchars($ride["dateTime"]) ?></p>
        <p><strong>Destination:</strong> <?= htmlspecialchars($ride["destination"]) ?></p>
        <p><strong>Seats Remaining:</strong> <?= htmlspecialchars($ride["available_seats"]) ?></p>
        <p><strong>Description:</strong> <?= htmlspecialchars($ride["description"] ?? "No description") ?></p>
        <?php
    } else {
        echo "<p>Ride not found.</p>";
    }
} else {
    echo "<p>Invalid request.</p>";
}
?>
