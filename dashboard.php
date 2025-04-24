<!-- Prabesh Bista -->

<!-- Main container split into two side-by-side columns -->
<div class="main-content">

    <!-- LEFT SIDE: New Ride, Ride To List, and Ride Date Form -->
    <div class="left-side">

        <!-- New Ride Button (unchanged) -->
        <div class="new-ride">
            <a href="index.php?menu=fmRide">
                <button class="new-ride-btn">New Ride +</button>
            </a>
        </div>

        <!-- Ride To List -->
        <div class="ride-to-section">
            <h2>Ride to</h2>
            <form id="rideToForm" action="rides.php" method="GET">
                <input type="hidden" id="ride-to" name="ride-to" value="" />
            </form>

            <ul class="ride-to-list">
                <li onclick="submitRideTo('Target')">Target</li>
                <li onclick="submitRideTo('Walmart')">Walmart</li>
                <li onclick="submitRideTo('Dulles International Airport')">Dulles International Airport</li>
                <li onclick="submitRideTo('Washington DC')">Washington DC</li>
            </ul>
        </div>
        <script>
            function submitRideTo(destination) {
                document.getElementById('ride-to').value = destination;
                document.getElementById('rideToForm').submit();
            }
        </script>

        <!-- Ride Date Filter wrapped in a form -->
        <div class="ride-date-filter">
            <h2>Ride Date</h2>
            <!-- Action and method attributes may be adjusted as needed -->
            <form class="date-inputs" action="rides.php" method="GET">
                <label for="from-date">From</label>
                <input type="date" id="from-date" name="fromDate" />
                <label for="to-date">To</label>
                <input type="date" id="to-date" name="toDate" />
                <!-- Submit button styled similarly to "New Ride" -->
                <input type="submit" value="Search" class="date-search-btn" />
            </form>
        </div>
    </div>

    <!-- RIGHT SIDE: Search Bar, Tabs & Ride Cards, Suggested Rides -->
    <div class="right-side">

        <!-- Search Bar wrapped in a form -->
        <div class="search-container">
            <!-- Adjust action and method as needed -->
            <form action="rides.php" method="GET">
                <label for="ride-search">Look for a ride</label>
                <input type="text" id="ride-search" name="ride" placeholder="Search rides..." />
                <button type="submit" class="search-btn">Search</button>
            </form>
        </div>

        <!-- Ride Tabs and Ride Cards for Next/Scheduled/Past Rides -->
        <div class="ride-tabs-content">

            <!-- have to add the logic to differentiate the tab -->
            <form action="" method="get" class="rides-tabs">
                <button type="submit" name="rides" value="upcomingrides" class="tab-button">Upcoming Rides</button>
                <button type="submit" name="rides" value="pastrides" class="tab-button">Past Rides</button>
            </form>
            <!-- Ride Cards Container for the selected tab (sample content) -->
            <div class="ride-cards-container">
                <?php

                include("search.php");
                ?>
            </div>
        </div>

        <!-- Suggested Upcoming Rides Section -->
        <div class="suggested-rides">
            <h2>Suggested Upcoming Rides!</h2>
            <div class="ride-cards-container">
                <?php
                //This will get me the uid
                $uid = $_SESSION["uid"];
                include("db_connect.php");
                //fetching all the rides that are not created by the current user
                //also checks whether the current user has already enrolled in the ride
                $possibleRidesQuery = "SELECT * FROM Ride WHERE uid<>$uid AND dateTime>=NOW() AND $uid NOT IN (SELECT passenger_ID FROM Requests WHERE Requests.ride_ID = Ride.ride_id) ORDER BY dateTime ASC LIMIT 5";
                try {
                    $result = $db->query($possibleRidesQuery);


                    while ($row = $result->fetch()) {
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
                                <a href="index.php?menu=searchdetail&tab=suggest&ride_id=<?= $row["ride_ID"] ?>"
                                    class="read-more-link">
                                    View Details →
                                </a>
                            </div>
                        </article>
                        <?php
                    }



                } catch (Exception $ex) {
                    print "Something went wrong!";
                }


                ?>
            </div>
        </div>
    </div>

</div>