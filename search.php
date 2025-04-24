<!-- Prabesh Bista -->
<?php
session_start();
include("db_connect.php");

if (!isset($_SESSION["uid"])) {
    ?>
    <h1>You have to login before seeing your past rides! </h1>
    <?php
} else {
    $uid = $_SESSION["uid"];
    $search_tab = $_GET['rides'] ?? 'upcomingrides';
    //by default this will fetch any upcoming rides
    $query = "SELECT * FROM Ride  WHERE (Ride.uid=$uid) AND Ride.dateTime >= NOW()  ORDER BY dateTime ASC";

    if ($search_tab == 'upcomingrides') {
        $query = "SELECT * FROM Ride WHERE (Ride.uid=$uid) AND Ride.dateTime >= NOW() ORDER BY dateTime ASC";
    } else {
        $query = "SELECT * 
                  FROM Ride 
                  JOIN Requests ON Ride.ride_ID=Requests.ride_ID 
                  WHERE Ride.uid=$uid OR Requests.passenger_ID=$uid 
                  ORDER BY dateTime DESC";
    }









    try {


        $res = $db->query($query);

        $pastRidesData = $res->fetchAll();



        if (count($pastRidesData) == 0) {
            if ($search_tab == 'upcomingrides') {
                print ("There are no upcoming rides!");
            } else {
                print ("There are no past rides!");
            }


        } else {

            foreach ($pastRidesData as $row) {

                $ride_id = $row["ride_ID"];
                $driverQuery = "SELECT name FROM User JOIN Ride ON Ride.uid = User.uid AND Ride .ride_ID = $ride_id";
                $resDriver = $db->query($driverQuery);
                $driverData = $resDriver->fetch();

               


                ?>

                <article class="ride-card">
                    <div class="ride-header">
                        <p class="ride-date"><?= $row["dateTime"] ?></p>
                        <h3 class="ride-destination">From Gettysburg College to
                            <?= htmlspecialchars($row["destination"]) ?></h3>
                            <p class="ride-driver"><strong>Posted By:</strong> <?=$driverData["name"]?></p>
                    </div>
                    <div class="ride-footer">
                        <p><?= $row["available_seats"] ?> seats remaining</p>
                        <a href="index.php?menu=searchdetail&tab=<?=$search_tab?>&ride_id=<?= $row["ride_ID"] ?>"
                            class="read-more-link">
                            View Details →
                        </a>
                    </div>
                </article>
                <?php
            }

        }

    } catch (Exception $ex) {
        print "There was an error";

    }
}


?>