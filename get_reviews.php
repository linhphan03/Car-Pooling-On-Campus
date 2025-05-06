<?php
require 'db_connect.php'; // or your DB include

$uid = $_GET['uid'];

try {
    $stmt = $db->prepare("SELECT * FROM Rates WHERE reviewed_id = :uid");
    $stmt->execute([':uid' => $uid]);
    $reviews = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($reviews);
} catch (Exception $e) {
    echo json_encode([]);
}
