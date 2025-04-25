<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include_once 'db_connect.php';

$rides = [];
$title = "";

try {
    if (!empty($_GET['ride-to'])) {
        $destination = $_GET['ride-to'];
        $fromDate = $_GET['from-date'] ?? null;
        $toDate = $_GET['to-date'] ?? null;

        // Default title
        $title = "Rides to " . htmlspecialchars($destination);

        if (!empty($fromDate) && !empty($toDate)) {
            $title .= " from " . htmlspecialchars($fromDate) . " to " . htmlspecialchars($toDate);

            $stmt = $db->prepare("SELECT ride_ID, destination, available_seats, dateTime, uid 
                                  FROM Ride 
                                  WHERE destination = :destination 
                                    AND dateTime BETWEEN :fromDate AND :toDate 
                                  ORDER BY dateTime ASC");

            $stmt->execute([
                ':destination' => $destination,
                ':fromDate' => $fromDate,
                ':toDate' => $toDate
            ]);
        } else {
            // If no date filters, show future rides only
            $now = date('Y-m-d H:i:s');

            $stmt = $db->prepare("SELECT ride_ID, destination, available_seats, dateTime, uid 
                                  FROM Ride 
                                  WHERE destination = :destination 
                                    AND dateTime > :now
                                  ORDER BY dateTime ASC");

            $stmt->execute([
                ':destination' => $destination,
                ':now' => $now
            ]);
        }

        $rides = $stmt->fetchAll();
    } else {
        echo "<p style='color: red;'>Please select a destination to search for rides.</p>";
        exit;
    }
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
                            <p class="ride-date"><?= date('m/d/Y g:i A', strtotime($ride["dateTime"])) ?></p>
                            <h3 class="ride-destination">From Gettysburg College to <?= htmlspecialchars($ride["destination"]) ?></h3>
                            <p class="ride-driver"><strong>Posted By:</strong> <?= htmlspecialchars($driverData["name"] ?? "Unknown") ?></p>
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
