<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include_once 'db_connect.php';

$fromDate = $_GET['fromDate'] ?? null;
$toDate = $_GET['toDate'] ?? null;

if (!$fromDate || !$toDate) {
    echo "<p>Invalid date input. Please use the form to search again.</p>";
    exit;
}

try {
    $stmt = $db->prepare("SELECT ride_ID, destination, available_seats, dateTime, uid 
                          FROM Ride 
                          WHERE dateTime BETWEEN :fromDate AND :toDate 
                          ORDER BY dateTime ASC");
    $stmt->execute([
        ':fromDate' => $fromDate,
        ':toDate' => $toDate
    ]);
    $rides = $stmt->fetchAll();
} catch (PDOException $e) {
    echo "<p>Error querying rides: " . htmlspecialchars($e->getMessage()) . "</p>";
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Available Rides</title>
    <link rel="stylesheet" href="rides.css">
</head>
<body>

    <h1>Rides from <?= htmlspecialchars($fromDate) ?> to <?= htmlspecialchars($toDate) ?></h1>

    <?php if (count($rides) === 0): ?>
        <p>No rides found for this date range.</p>
    <?php else: ?>
        <div class="sugBox1">
            <?php foreach ($rides as $ride): ?>
                <div class="sgcard">
                    <h3>Cub to <?= htmlspecialchars($ride['destination']) ?></h3>
                    <h3><?= date('m/d/Y', strtotime($ride['dateTime'])) ?></h3>
                    <h6><?= htmlspecialchars($ride['available_seats']) ?> Seats Remaining</h6>
                    <a href="#">Read More</a>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

</body>
</html>
