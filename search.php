<?php
include("db_connect.php");

if (!isset($_SESSION["uid"])) {
    ?>
        <h1>You have to login before seeing your past rides!    </h1>
    <?php
} else {
    $uid = $_SESSION["uid"];

    //Past rides
    $lastRidesQuery = "SELECT * 
FROM Ride JOIN Requests ON Ride.ride_ID=Requests.ride_ID 
WHERE Ride.uid=$uid OR Requests.passenger_ID=$uid
ORDER BY dateTime DESC";



    //Upcoming Rides

    $nextRidesQuery = "SELECT * FROM Ride  WHERE (Ride.uid=$uid) AND Ride.dateTime >= NOW()  ORDER BY dateTime ASC";


    try {
        $res = $db->query($nextRidesQuery);
        $pastRidesData = $res->fetchAll();

        if (count($pastRidesData) == 0) {
            print ("There are no upcoming rides!");
        } else {

            foreach ($pastRidesData as $row) {

                ?>

                <article class="ride-card">
                    <p class="ride-date"><?=$row["dateTime"]?></p>
                    <h3>Gettysburg College to <?=$row["destination"]?></h3>
                    <p><?=$row["available_seats"]?> seats remaining </p>
                    <a href="#" class="read-more-link">Read More</a>
                </article>
                <?php
            }

        }

    } catch (Exception $ex) {
        print "There was an error";

    }
}


?>