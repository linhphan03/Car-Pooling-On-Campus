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


    if ($search_tab == 'pastrides') {
        $query = "SELECT * FROM Ride WHERE ((Ride.uid=$uid) OR Ride.ride_id IN(SELECT ride_ID FROM Requests WHERE passenger_ID = $uid)) AND Ride.dateTime <NOW()  ORDER BY dateTime DESC";
    } else {
        $query = "SELECT * FROM Ride WHERE ((Ride.uid=$uid) OR Ride.ride_id IN(SELECT ride_ID FROM Requests WHERE passenger_ID = $uid)) AND Ride.dateTime >= NOW()  ORDER BY dateTime ASC";
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
                $driverQuery = "SELECT name,User.uid AS user_id FROM User JOIN Ride ON Ride.uid = User.uid AND Ride .ride_ID = $ride_id";
                $resDriver = $db->query($driverQuery);
                $driverData = $resDriver->fetch();
                $dateTimeFromDB = $row['dateTime'];
                $date = new DateTime($dateTimeFromDB);
                $dateCal = $date->format('l, F j');
                $dateTime = $date->format('g:i A');
                $cardTypeClass = ($driverData["user_id"] == $uid) ? "Driver" : "Passenger";
                ?>



                <article class="ride-card">
                        <div class="stack <?= ($driverData["user_id"] == $uid) ? 'created-tag' : 'joined-tag' ?>"><?=$cardTypeClass?></div>
                        <div class="ride-header">
                            <p class="ride-date"><?= $dateCal ?>, <?= $dateTime ?></p>
                            <h3 class="ride-destination">From Gettysburg College to
                                <?= htmlspecialchars($row["destination"]) ?>
                            </h3>
                            <p class="ride-driver"><strong>Driver:</strong>
                                <?php

                                if ($driverData["user_id"] == $uid) {
                                    ?>
                                    You

                                    <?php
                                } else {
                                    ?>
                                    <?= $driverData["name"] ?>
                                    <?php
                                }


                                ?>


                            </p>
                        </div>
                        <div class="ride-footer">
                            <p><?= $row["available_seats"] ?> seats remaining</p>
                            <a href="index.php?menu=searchdetail&tab=<?= $search_tab ?>&ride_id=<?= $row["ride_ID"] ?>"
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