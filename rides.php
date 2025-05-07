<!-- Search for rides based on field, excluding driver's -->
<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include_once 'db_connect.php';

$rides = [];
$title = "";
$today = date('Y-m-d H:i:s');

$currentUserId = $_SESSION['uid'] ?? null;

if (!$currentUserId) {
    echo "<p style='color: red;'>You must be logged in to view rides.</p>";
    exit;
}

// Get filters
$rideTo   = $_GET['ride-to'] ?? null;
$fromDate = $_GET['from-date'] ?? null;
$toDate   = $_GET['to-date'] ?? null;

try {
    // Base query and params
    $sql = "SELECT ride_ID, destination, available_seats, dateTime, uid 
            FROM Ride 
            WHERE dateTime >= :today AND uid != :currentUserId";
    $params = [
        ':today' => $today,
        ':currentUserId' => $currentUserId
    ];

    $titleParts = [];

    if (!empty($rideTo)) {
        $sql .= " AND destination = :destination";
        $params[':destination'] = $rideTo;
        $titleParts[] = "to " . htmlspecialchars($rideTo);
    }

    if (!empty($fromDate)) {
        $sql .= " AND dateTime >= :fromDate";
        $params[':fromDate'] = $fromDate;
        $titleParts[] = "starting from " . htmlspecialchars($fromDate);
    }

    if (!empty($toDate)) {
        $sql .= " AND dateTime <= :toDate";
        $params[':toDate'] = $toDate;
        $titleParts[] = "until " . htmlspecialchars($toDate);
    }

    $sql .= " ORDER BY dateTime ASC";

    // Title formatting
    $title = empty($titleParts) ? "All upcoming rides" : "Rides " . implode(' ', $titleParts);

    // Execute
    $stmt = $db->prepare($sql);
    $stmt->execute($params);
    $rides = $stmt->fetchAll();

} catch (PDOException $e) {
    echo "<p style='color: red;'>Error querying rides: " . htmlspecialchars($e->getMessage()) . "</p>";
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
    <div class="container mt-4">
        <h1 class="rides-display"><?= $title ?></h1>

        <?php if (count($rides) === 0): ?>
            <p>No rides found for this search.</p>
        <?php else: ?>
            <div class="ride-cards-container">
                <?php foreach ($rides as $ride): ?>
                    <?php
                        // Fetch driver name based on ride's UID
                        $ride_id = $ride["ride_ID"];
                        $driverQuery = "SELECT name FROM User 
                                        JOIN Ride ON Ride.uid = User.uid 
                                        WHERE Ride.ride_ID = :ride_id 
                                        LIMIT 1";
                        $stmtDriver = $db->prepare($driverQuery);
                        $stmtDriver->execute([':ride_id' => $ride_id]);
                        $driverData = $stmtDriver->fetch();
                    ?>

                    <article class="ride-card">
                        <div class="ride-header">
                            <p class="ride-date">
                                <?php
                                    $date = new DateTime($ride["dateTime"]);
                                    $dateCal = $date->format('l, F j');
                                    $dateTime = $date->format('g:i A');
                                ?>
                                <?= $dateCal ?>, <?= $dateTime ?>
                            </p>
                            
                            <h3 class="ride-destination">From Gettysburg College to <?= htmlspecialchars($ride["destination"]) ?></h3>
                            <p class="ride-driver"><strong>Driver:</strong> <?= htmlspecialchars($driverData["name"] ?? "Unknown") ?></p>
                        </div>
                        <div class="ride-footer">
                            <p><?= htmlspecialchars($ride["available_seats"]) ?> seats remaining</p>
                            <a href="index.php?menu=searchdetail&tab=suggest&ride_id=<?= $ride["ride_ID"] ?>" class="read-more-link">
                                View Details →
                            </a>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
