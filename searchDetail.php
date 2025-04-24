<!-- Prabesh Bista -->
<!-- This page genrate the detail page -->

<?php
$tab = $_GET["tab"];
$ride_id = $_GET['ride_id'];
//This basically a small logic that would help to generate different button based on the read more the use clicked on
//If the read more is for suggested ride then it would have value 1, 2 for upcoming ride and 3 for past rides.
$action_id = 0;

//this to request a ride to a drive
if ($tab == "suggest") {
    $action_id = 1;
} else if ($tab == "upcomingrides") {
    $action_id = 2;
} else {
    $action_id = 3;
}

try {
    //query to get the data of the driver from the database
    $driverQuery = "SELECT * FROM User JOIN Ride ON Ride.uid = User.uid AND Ride.ride_ID = $ride_id;";
    $resDriver = $db->query($driverQuery);
    $driverData = $resDriver->fetch();
    $driverID = $driverData["uid"];
    $driverName = $driverData["name"];
    $destination = $driverData["destination"];
    $availableSeats = $driverData["available_seats"];

    //format the raw date obtained from the db
    $dateTimeFromDB = $driverData['dateTime']; 
    $date = new DateTime($dateTimeFromDB);
    $dateCal = $date->format('l, F j');
    $dateTime = $date->format('g:i A');


    //driver Created Date
    $dateTimeDriver = $driverData["created_at"];
    $created_date = new DateTime($dateTimeDriver);
    $dateCalDriver = $created_date->format('F Y');





    //query to get the average rating of the driver
    $ratingQuery = "SELECT R1.rating, R1.review, U.name FROM Rates AS R1 JOIN User AS U ON R1.reviewer_id = U.uid WHERE R1.reviewed_id = $driverID ORDER BY dateTime ASC LIMIT 3";
    $ratingData = $db->query($ratingQuery);

    //query to get the average rating of the driver
    $avgRatingQuery = "SELECT ROUND(AVG(rating), 2) AS drating FROM Rates WHERE reviewed_id=$driverID GROUP BY reviewed_id";
    $avgRatingResult = $db->query($avgRatingQuery);
    $averageRating = $avgRatingResult->fetch()["drating"];

    //query to find the number of trips completed
    $nRidesQuer = "SELECT COUNT(R1.ride_ID) AS tRides FROM Ride AS R1 WHERE uid=$driverID";
    $nRidesResult = $db->query($nRidesQuer);
    $nRides = $nRidesResult->fetch()["tRides"];

    //query to find the number of passengers driven
    $nPassengerQuer = "SELECT COUNT(R2.passenger_ID) as tPassenger FROM Ride AS R1 NATURAL JOIN Requests AS R2 WHERE uid = $driverID";
    $nPassengerResult = $db->query($nPassengerQuer);

    $nPassenger = $nPassengerResult->fetch()["tPassenger"];

    //query to find the car of the driver
    //TODO
    // $carQuery = "SELECT "







} catch (Exception $ex) {
    print "OOPs! There was some error :)";
}


?>

<div class="ride-detail-wrapper">

    <!-- Left Section -->
    <div class="ride-detail-left">
        <div class="ride-profile">
            <img class="driver-avatar" src="driver_image.png" alt="Driver Profile">
            <div class="driver-info">
                <h3><?= $driverName ?></h3>
                <p class="rating">⭐ <?= $averageRating ?> · <?= $nRides ?> trips driven</p>
            </div>
        </div>

        <div class="ride-info">
            <h2>Gettysburg to <?=$destination?></h2>
            <p class="ride-time">Leaving <?=$dateCal?> at <?=$dateTime?></p>
            <p><strong>Pickup:</strong> Gettysburg College</p>
            <p><strong>Dropoff:</strong> <?=$destination?></p>
            <p class="seats-left"><?=$availableSeats?> left · <span class="price">Price Negotiable</span></p>
        </div>

        <div class="car-info">
            <img class="car-img" src="car.jpeg" alt="Car Image">
            <ul class="car-details">
                <li><strong>Toyota Camry</strong> · 2020 · White</li>
                <li>Max 3 people in the back</li>
                <li>Small luggage ok</li>
                <li>No pets</li>
            </ul>
        </div>

        <?php
        if ($action_id == 1) {
            ?>
            <div class="book-btn-container">
                <button class="book-btn">Book now</button>
            </div>
            <?php
        } else if ($action_id == 2) {

            ?>
                <div class="book-btn-container">
                    <button class="book-btn">Cancel Ride</button>
                </div>


            <?php
        }
        ?>
    </div>

    <!-- Right Section -->
    <div class="ride-detail-right">

        <h3>About the driver</h3>
        <div class="about-grid">
            <div class="driver-about">
                <img class="driver-avatar-large" src="driver_image.png" alt="Driver Profile">
                <p><strong>Millor</strong> · ⭐ <?= $averageRating ?> (<?= $nRides ?> driven)</p>
                <p>Member since <?=$dateCalDriver?></p>
                <p>🚗 Passengers driven: <strong><?= $nPassenger ?></strong></p>
                <div class="verifications">
                    <p>✅ Driver's license verified</p>
                    <p>📨 Email address: Verified</p>
                    <p>📜 Community agreement: Signed</p>
                </div>
            </div>

            <div class="recent-reviews">
                <h4>Recent reviews</h4>
                <?php
                $no_data = 0;
                while ($row = $ratingData->fetch()) {
                    $no_data = 1;

                    ?>
                    <div class="review">
                        <p><strong><?= $row["name"] ?>:</strong> <?= $row["review"] ?></p>
                        <p>⭐ <?= $row["rating"] ?>/5</p>
                    </div>
                    <?php
                }

                ?>
                <?php
                if($no_data!=0){
                    ?>
                    <a href="#" class="view-all-reviews">Read all reviews →</a>
                    <?php
                }else{
                    print "<h6>Looks like everyone enjoyed the ride so much they forgot to review it. 🚗💨🫠</h6>";
                }
                ?>
            </div>
        </div>

    </div>

</div>