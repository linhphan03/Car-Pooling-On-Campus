<?php
session_start();
include_once 'db_connect.php';

$currentUid = $_SESSION['uid'] ?? null;
$targetUid = $_POST['uid'] ?? null;

if (!$currentUid || !$targetUid) {
    exit; // Prevent unauthorized or malformed requests
}

// Verify admin
$adminCheck = $db->prepare("SELECT 1 FROM Admin WHERE admin_ID = :uid");
$adminCheck->execute([':uid' => $currentUid]);
if (!$adminCheck->fetch()) exit;

// 1. Mark user as banned
$banStmt = $db->prepare("UPDATE User 
    SET is_banned = 1, banned_time = NOW(), banned_by = :admin 
    WHERE uid = :uid");
$banStmt->execute([
    ':admin' => $currentUid,
    ':uid' => $targetUid
]);

// 2. Delete upcoming rides created by the banned user
$deleteRides = $db->prepare("DELETE FROM Ride WHERE uid = :uid AND dateTime >= NOW()");
$deleteRides->execute([':uid' => $targetUid]);

// 3. Delete upcoming requests where they are a passenger
// Get ids of all future rides they are in
$reqRides = $db->prepare("
    SELECT r.ride_ID FROM Requests req
    JOIN Ride r ON req.ride_ID = r.ride_ID
    WHERE req.passenger_ID = :uid AND r.dateTime > NOW()
");
$reqRides->execute([':uid' => $targetUid]);

$rideIds = $reqRides->fetchAll(PDO::FETCH_COLUMN);
if ($rideIds) {
    // Increase available_seats by 1 for each ride
    $updateSeats = $db->prepare("UPDATE Ride SET available_seats = available_seats + 1 WHERE ride_ID = :ride_id");
    foreach ($rideIds as $rid) {
        $updateSeats->execute([':ride_id' => $rid]);
    }

    // Delete requests
    $deleteReq = $db->prepare("DELETE FROM Requests WHERE passenger_ID = :uid");
    $deleteReq->execute([':uid' => $targetUid]);
}

// 4. Delete their rates given to others
$deleteRates = $db->prepare("DELETE FROM Rates WHERE reviewer_id = :uid");
$deleteRates->execute([':uid' => $targetUid]);

// 5. Delete Car and Payment Info
$deleteCars = $db->prepare("DELETE FROM Car WHERE uid = :uid");
$deleteCars->execute([':uid' => $targetUid]);

$deletePayments = $db->prepare("DELETE FROM PaymentInfo WHERE uid = :uid");
$deletePayments->execute([':uid' => $targetUid]);

exit;
?>
