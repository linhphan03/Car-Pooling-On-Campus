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

$today = date('Y-m-d H:i:s');
$rides = [];
$title = "";


try {
    if (isset($_GET['from-date'], $_GET['to-date'])) {
        $fromDate = $_GET['from-date'];
        $toDate = $_GET['to-date'];

        $title = "Rides from " . htmlspecialchars($fromDate) . " to " . htmlspecialchars($toDate);

        $stmt = $db->prepare("SELECT ride_ID, destination, available_seats, dateTime, uid 
                              FROM Ride 
                              WHERE dateTime BETWEEN :fromDate AND :toDate 
                              ORDER BY dateTime ASC");
        $stmt->execute([
            ':fromDate' => $fromDate,
            ':toDate' => $toDate
        ]);
        $rides = $stmt->fetchAll();

    } elseif (isset($_GET['ride-to'])) {
        $destination = $_GET['ride-to'];
        $title = "Rides to " . htmlspecialchars($destination);

        $stmt = $db->prepare("SELECT ride_ID, destination, available_seats, dateTime, uid 
                              FROM Ride 
                              WHERE destination = :destination AND dateTime > :today 
                              ORDER BY dateTime ASC");
        $stmt->execute([
            ':destination' => $destination,
            ':today' => $today
        ]);
        $rides = $stmt->fetchAll();

    } elseif (!empty($_GET['ride'])) {
        $searchTerm = '%' . $_GET['ride'] . '%';
        $title = "Search results for \"" . htmlspecialchars($_GET['ride']) . "\"";

        $stmt = $db->prepare("SELECT ride_ID, destination, available_seats, dateTime, uid 
                              FROM Ride 
                              WHERE destination LIKE :search AND dateTime > :today 
                              ORDER BY dateTime ASC");
        $stmt->execute([
            ':search' => $searchTerm,
            ':today' => $today
        ]);
        $rides = $stmt->fetchAll();

    } else {
        echo "<p>Please use the search form to find rides.</p>";
        exit;
    }

} catch (PDOException $e) {
    echo "<p>Error querying rides: " . htmlspecialchars($e->getMessage()) . "</p>";
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Available Rides</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" />
    <link rel="stylesheet" href="rides.css" />
</head>
<body>
    <h1 class="rides-display"><?= $title ?></h1>

    <?php if (count($rides) === 0): ?>
        <p>No rides found for this search.</p>
    <?php else: ?>
        <div class="ride-cards-container">
            <?php foreach ($rides as $ride): ?>
                <article class="ride-card">
                    <p class="ride-date"><?= date('m/d/Y', strtotime($ride['dateTime'])) ?></p>
                    <h3>CUB to <?= htmlspecialchars($ride['destination']) ?></h3>
                    <p><?= htmlspecialchars($ride['available_seats']) ?> Seats Remaining</p>
                    <a href="#" class="read-more-link">Read More</a>
                </article>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</body>
</html>
