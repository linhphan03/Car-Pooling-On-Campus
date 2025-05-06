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
    $minPrice = $driverData['min'];
    $maxPrice = $driverData['max'];

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
    $ratingQuery = "SELECT R1.rating, R1.review, U.name FROM Rates AS R1 JOIN User AS U ON R1.reviewer_id = U.uid WHERE R1.reviewed_id = $driverID ORDER BY dateTime DESC LIMIT 3";
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


} catch (Exception $ex) {
    print "OOPs! There was some error :)";
}

//logic to insert the data into the Request table
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['myButton'])) {
    //query to insert in the reqest table
    $user_id = $_SESSION['uid'];

    $stmt = $db->prepare("SELECT 1 FROM Requests WHERE ride_ID = :ride_id AND passenger_ID = :user_id LIMIT 1");
    $stmt->execute([
        ':ride_id' => $ride_id,
        ':user_id' => $user_id
    ]);

    $exists = $stmt->fetch();

    if ($exists) {
        print "Ride Already booked";
    } else {
        try {

            $db->beginTransaction();
            $insertStmt = $db->prepare("INSERT INTO Requests (ride_ID, passenger_ID) VALUES (:ride_id, :user_id)");
            $insertStmt->execute([
                ':ride_id' => $ride_id,
                ':user_id' => $user_id
            ]);

            $updateStmt = $db->prepare("UPDATE Ride SET available_seats = available_seats - 1 WHERE ride_ID = :ride_id AND available_seats > 0");
            $updateStmt->execute([':ride_id' => $ride_id]);

            // 3. Commit the transaction
            $db->commit();
            // Redirect to index.php after processing
            header("Location: index.php?menu=success&type=success");
            exit();

        } catch (Exception $ex) {
            print $ex;
        }
    }



}


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cancelButton'])) {
    try {
        //checks whether the current user is current driver
        $user_id = $_SESSION['uid'];
        if ($user_id == $driverID) {
            //extremly caution
            $db->beginTransaction();
            $deleteRequestStmt = $db->prepare("DELETE FROM Requests WHERE ride_ID = :ride_ID");
            $deleteRequestStmt->execute([':ride_ID' => $ride_id]);
            $deleteStmt = $db->prepare("DELETE FROM Ride WHERE ride_ID = :ride_ID");
            $deleteStmt->execute([':ride_ID' => $ride_id]);

            $db->commit();
            header("Location: index.php?menu=success&type=cancel");
        } else {

            //this for the ride for which the current user is not the driver of that ride   

            $db->beginTransaction();
            $deleteStmt = $db->prepare("DELETE FROM Requests WHERE ride_ID = :ride_id AND passenger_ID=:user_id");
            $deleteStmt->execute([
                ':ride_id' => $ride_id,
                ':user_id' => $user_id
            ]);
            //update the ride 
            $updateStmt = $db->prepare("UPDATE Ride SET available_seats = available_seats + 1 WHERE ride_ID = :ride_id");
            $updateStmt->execute([':ride_id' => $ride_id]);

            $db->commit();
            header("Location: index.php?menu=success&type=cancel");
            exit();
        }


    } catch (Exception $ex) {
        print $ex;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['review-submit'])) {
    $reviewer_id = $_SESSION['uid'];
    $reviewed_id = $_POST['reviewed_id'];
    $ride_id = $_POST['ride_id'];
    $rating = $_POST['rating'];
    $review = $_POST['review'];


    try {
        $stmt = $db->prepare("INSERT INTO Rates (reviewer_id, reviewed_id, rating, review,ride_ID)
                              VALUES (:reviewer_id, :reviewed_id, :rating, :review,:ride_ID)");
        $stmt->execute([
            ':reviewer_id' => $reviewer_id,
            ':reviewed_id' => $reviewed_id,
            ':rating' => $rating,
            ':review' => $review,
            ':ride_ID' => $ride_id
        ]);



        // Refresh the page to show updated reviews
        header("Location: index.php?menu=searchdetail&tab=pastrides&ride_id=$ride_id");
        exit();

    } catch (Exception $ex) {
        $reviewError = "Review submission failed: " . $ex->getMessage();
    }



}


?>

<div class="ride-detail-wrapper">

    <!-- Left Section -->
    <div class="ride-detail-left">
        <div class="ride-profile">
            <img class="driver-avatar" src="driver_image.png" alt="Driver Profile">
            <div class="driver-info">
                <!--  -->
                <h3>
                    <?php
                    if ($driverID == $_SESSION['uid']) {
                        ?>
                        You
                        <?php
                    } else {
                        ?>
                        <?= $driverName ?>
                        <?php
                    }

                    ?>



                </h3>
                <p class="rating">⭐ <?= $averageRating ?> · <?= $nRides ?> trips driven</p>
            </div>
        </div>

        <div class="ride-info">
            <h2>Gettysburg to <?= $destination ?></h2>
            <p class="ride-time">Leaving <?= $dateCal ?> at <?= $dateTime ?></p>
            <p><strong>Pickup:</strong> Gettysburg College</p>
            <p><strong>Dropoff:</strong> <?= $destination ?></p>
            <?php
            if ($availableSeats == 0) {
                ?>
                <p class="seats-left">Ride is Full . <span class="price">$<?= $minPrice ?>-$<?= $maxPrice ?></span></p>
                <?php
            } else {
                ?>
                <p class="seats-left"><?= $availableSeats ?> left · <span
                        class="price">$<?= $minPrice ?>-$<?= $maxPrice ?></span></p>
                <?php
            }
            ?>
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
        //checks whether the date is in the past
        $user_id = $_SESSION['uid'];
        if (new DateTime($dateTimeFromDB) < new DateTime()) {
            //Checks whether the current user is the driver of the selected rider 
            //create a pop-up box where the user can select the rating and write review
            //This query checks whether there is already a review that exist for the driver by the current user for the current ride
        
            if ($driverID == $user_id) {
                //fetch all the passenger that are part of this rides
                $passengerQuery = "SELECT U.uid, U.name FROM Requests R JOIN User U ON R.passenger_ID = U.uid WHERE R.ride_ID = :ride_id";
                $stmt = $db->prepare($passengerQuery);
                $stmt->execute([':ride_id' => $ride_id]);
                $passengers = $stmt->fetchAll();
                ?>

                <h5 style="font-weight:bold; margin-top:40px">What Did You Think of Your Passengers?</h5>
                <?php
                foreach ($passengers as $passenger) {
                    $pid = $passenger["uid"];
                    $pname = $passenger["name"];
                    //have to check whether the driver has already given a review to the passenger
                    $review_exist;
                    $uid = $_SESSION['uid'];
                    try {
                        $check_query = "SELECT 1 FROM Rates WHERE ride_ID =:ride_ID AND reviewed_id=:driverID AND reviewer_id=:userID";
                        $check_stmt = $db->prepare($check_query);
                        $check_stmt->execute([':ride_ID' => $ride_id, ':driverID' => $pid, ':userID' => $uid]);
                        $review_exist = $check_stmt->fetch();
                    } catch (Exception $ex) {
                        print "There was an error";
                    }


                    if ($review_exist) {
                        ?>

                        <div class="passenger-review-entry">
                            <p><strong><?= htmlspecialchars($pname) ?></strong>
                                <button disabled class="reviewed-btn">Already
                                    Reviewed</button>
                            </p>
                        </div>

                        <?php
                    } else {
                        ?>
                        <div class="passenger-review-entry">
                            <p><strong><?= htmlspecialchars($pname) ?></strong>
                                <button class="review-btn"
                                    onclick="openReviewModal(<?= $pid ?>, '<?= htmlspecialchars($pname) ?>')">Review</button>
                            </p>
                        </div>
                        <?php
                    }


                }

            } else {
                $review_exist;
                $uid = $_SESSION['uid'];
                try {
                    $check_query = "SELECT 1 FROM Rates WHERE ride_ID =:ride_ID AND reviewed_id=:driverID AND reviewer_id=:userID";
                    $check_stmt = $db->prepare($check_query);
                    $check_stmt->execute([':ride_ID' => $ride_id, ':driverID' => $driverID, ':userID' => $uid]);
                    $review_exist = $check_stmt->fetch();
                } catch (Exception $ex) {
                    print "There was an error";
                }


                ?>
                <h5 style="font-weight:bold; margin-top:40px">How Was Your Driver?</h5>
                <?php
                if ($review_exist) {
                    ?>

                    <div class="passenger-review-entry">
                        <p><strong><?= htmlspecialchars($driverName) ?></strong>
                            <button disabled class="reviewed-btn">Already
                                Reviewed</button>
                        </p>
                    </div>

                    <?php

                } else {
                    ?>

                    <div class="passenger-review-entry">
                        <p><strong><?= htmlspecialchars($driverName) ?></strong>
                            <button class="review-btn"
                                onclick="openReviewModal(<?= $driverID ?>, '<?= htmlspecialchars($driverName) ?>')">Review</button>
                        </p>
                    </div>

                    <?php
                }


            }


        } else {
            if ($action_id == 1) {


                $stmt = $db->prepare("SELECT 1 FROM Requests WHERE ride_ID = :ride_id AND passenger_ID = :user_id LIMIT 1");
                $stmt->execute([
                    ':ride_id' => $ride_id,
                    ':user_id' => $user_id
                ]);

                $exists = $stmt->fetch();
                if ($exists) {
                    ?>
                    <div class="book-btn-container">
                        ?>


                        <button class="book-btn" style="background-color: red;">Cancel Ride</button>
                    </div>
                    <?php
                } else {
                    ?>
                    <form method="POST">
                        <div class="book-btn-container">
                            <button type="submit" name="myButton" class="book-btn">Book now</button>
                        </div>
                    </form>
                    <?php
                }
            } else if ($action_id == 2) {

                ?>
                    <form method="POST">
                        <div class="book-btn-container">
                            <?php
                            if ($driverID === $_SESSION['uid']) {
                                ?>
                                <button name="cancelButton" type="submit" class="book-btn" style="background-color: red;">Delete This
                                    Ride</button>
                            <?php
                            } else {
                                ?>
                                <button name="cancelButton" type="submit" class="book-btn" style="background-color: red;">Cancel My
                                    Booking</button>
                            <?php
                            }
                            ?>
                        </div>
                    </form>


                <?php
            }
        }
        ?>
    </div>

    <!-- Right Section -->
    <div class="ride-detail-right">
        <?php
        //To check whether the current user is the driver, if it is then just show the passenger that are part of this ride so far
        if ($_SESSION['uid'] === $driverID) {
            ?>
            <h3>Passengers on This Ride</h3>
            <div class="passenger-list"
                style="margin-top: 1rem; padding: 1rem; background-color: #f8f9fa; border-radius: 10px;">
                <?php
                try {
                    $passengerQuery = "
            SELECT 
                U.uid,
                U.name, 
                U.email,
                ROUND(AVG(R.rating), 1) AS avg_rating
            FROM Requests Req
            JOIN User U ON Req.passenger_ID = U.uid
            LEFT JOIN Rates R ON R.reviewed_id = U.uid
            WHERE Req.ride_ID = :ride_id
            GROUP BY U.uid, U.name, U.email
        ";
                    $stmt = $db->prepare($passengerQuery);
                    $stmt->execute([':ride_id' => $ride_id]);

                    $hasPassengers = false;
                    while ($passenger = $stmt->fetch()) {
                        $hasPassengers = true;
                        ?>
                        <div style="padding: 1rem 0; border-bottom: 1px solid #ddd;">
                            <p style="margin: 0; font-size: 1.1rem; font-weight: 600;">
                                👤 <span class="passenger-link" style="color: #007bff; cursor: pointer; text-decoration: underline;"
                                    data-name="<?= htmlspecialchars($passenger['name']) ?>"
                                    data-email="<?= htmlspecialchars($passenger['email']) ?>"
                                    data-rating="<?= ($passenger['avg_rating'] !== null) ? $passenger['avg_rating'] . '/5' : 'N/A' ?>"
                                    data-uid="<?= $passenger['uid'] ?>">
                                    <?= htmlspecialchars($passenger['name']) ?>
                                </span>
                            </p>
                            <p style="margin: 0.25rem 0 0 0; font-size: 0.95rem; color: #555;">
                                📧 <?= htmlspecialchars($passenger['email']) ?>
                            </p>
                            <p style="margin: 0.25rem 0 0 0; font-size: 0.95rem; color: #555;">
                                ⭐ Rating: <?= ($passenger['avg_rating'] !== null) ? $passenger['avg_rating'] . '/5' : 'N/A' ?>
                            </p>
                        </div>
                        <?php
                    }

                    if (!$hasPassengers) {
                        echo "<p>No passengers have booked this ride yet.</p>";
                    }

                } catch (Exception $e) {
                    echo "<p>Error loading passenger list.</p>";
                }
                ?>
            </div>
            <?php

        } else {
            ?>
            <h3>About the driver</h3>
            <div class="about-grid">
                <div class="driver-about">
                    <img class="driver-avatar-large" src="driver_image.png" alt="Driver Profile">
                    <p><strong><?= $driverName ?></strong> · ⭐ <?= $averageRating ?> (<?= $nRides ?> driven)</p>
                    <p>Member since <?= $dateCalDriver ?></p>
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
                    if ($no_data != 0) {
                        ?>
                        <button class="view-all-reviews" onclick="openAllReviewsModal()">Read all reviews →</button>
                        <?php
                    } else {
                        print "<h6>Looks like everyone enjoyed the ride so much they forgot to review it. 🚗💨🫠</h6>";
                    }
                    ?>
                </div>
            </div>


            <?php
        }

        ?>

    </div>

</div>

<!-- Pop up window to show all the reviews -->
<div id="allReviewsModal" class="modal" style="display:none;">
    <div class="modal-content">
        <span class="close" onclick="closeAllReviewsModal()">&times;</span>
        <h3>All Reviews for <?= htmlspecialchars($driverName) ?></h3>
        <div style="max-height: 500px; overflow-y: auto;">
            <?php
            try {
                $allReviewsQuery = "SELECT rating, review, dateTime, U.name 
                                FROM Rates R 
                                JOIN User U ON R.reviewer_id = U.uid 
                                WHERE R.reviewed_id = :driverID 
                                ORDER BY dateTime DESC";
                $allStmt = $db->prepare($allReviewsQuery);
                $allStmt->execute([':driverID' => $driverID]);

                $anyReview = false;
                while ($review = $allStmt->fetch()) {
                    $anyReview = true;
                    ?>
                    <div class="review">
                        <p><strong><?= htmlspecialchars($review["name"]) ?></strong>
                            (<?= date("F j, Y", strtotime($review["dateTime"])) ?>)</p>
                        <p>⭐ <?= $review["rating"] ?>/5</p>
                        <p><?= htmlspecialchars($review["review"]) ?></p>
                        <hr>
                    </div>
                    <?php
                }

                if (!$anyReview) {
                    echo "<p>No reviews yet.</p>";
                }
            } catch (Exception $e) {
                echo "<p>Error loading reviews.</p>";
            }
            ?>
        </div>
    </div>
</div>


<!-- This is the pop  up window for the review -->
<div id="reviewModal" class="modal" style="display:none;">
    <div class="modal-content">
        <span class="close" onclick="closeReviewModal()">&times;</span>
        <h3>Leave a Review for <span id="reviewName"></span></h3>
        <form method="POST">
            <input type="hidden" name="ride_id" value="<?= $ride_id ?>">
            <input type="hidden" name="reviewed_id" id="reviewed_id">
            <label for="rating">Rating (1–5):</label>
            <select name="rating" required>
                <option value="" disabled selected>Select rating</option>
                <?php for ($i = 1; $i <= 5; $i++): ?>
                    <option value="<?= $i ?>"><?= $i ?></option>
                <?php endfor; ?>
            </select>
            <br><br>
            <label for="review">Review:</label><br>
            <textarea name="review" rows="4" cols="40" placeholder="Write your review here..." required></textarea>
            <br><br>
            <button class="review-btn" type="submit" name="review-submit">Submit Review</button>
        </form>
    </div>
</div>

<!-- This is a pop-up window for showing detail information about the user -->
<div id="passengerModal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; 
    background:rgba(0,0,0,0.5); z-index:1000; align-items:center; justify-content:center;">
    <div style="background:white; padding:2rem; border-radius:10px; width:90%; max-width:500px; position:relative;">
        <span id="closeModal"
            style="position:absolute; top:10px; right:15px; cursor:pointer; font-size:20px;">&times;</span>
        <h2 id="modalName"></h2>
        <p><strong>Email:</strong> <span id="modalEmail"></span></p>
        <p><strong>Average Rating:</strong> <span id="modalRating"></span></p>
        <!-- Optional: add more info here -->
        <div id="modalReviews">
            <em>Loading reviews...</em>
        </div>
    </div>
</div>




<script>
    function openReviewModal(uid, name) {
        document.getElementById('reviewed_id').value = uid;
        document.getElementById('reviewName').innerText = name;
        document.getElementById('reviewModal').style.display = 'block';
    }

    function closeReviewModal() {
        document.getElementById('reviewModal').style.display = 'none';
    }

    function openAllReviewsModal() {
        document.getElementById('allReviewsModal').style.display = 'block';
    }

    function closeAllReviewsModal() {
        document.getElementById('allReviewsModal').style.display = 'none';
    }

    //to show the temporary profile of a passenger
    document.querySelectorAll('.passenger-link').forEach(link => {
        link.addEventListener('click', async () => {
            const name = link.dataset.name;
            const email = link.dataset.email;
            const rating = link.dataset.rating;
            const uid = link.dataset.uid;

            document.getElementById('modalName').textContent = name;
            document.getElementById('modalEmail').textContent = email;
            document.getElementById('modalRating').textContent = rating;

            const reviewsContainer = document.getElementById('modalReviews');
            reviewsContainer.innerHTML = "<em>Loading reviews...</em>";

            try {
                const response = await fetch(`get_reviews.php?uid=${uid}`);
                const data = await response.json();
                if (data.length === 0) {
                    reviewsContainer.innerHTML = "<p>No reviews yet.</p>";
                } else {
                    reviewsContainer.innerHTML = "<ul>" + data.map(r => `<li>${r.review} (⭐ ${r.rating})</li>`).join('') + "</ul>";
                }
            } catch (error) {
                reviewsContainer.innerHTML = "<p>Error loading reviews.</p>";
            }

            document.getElementById('passengerModal').style.display = 'flex';
        });
    });

    document.getElementById('closeModal').addEventListener('click', () => {
        document.getElementById('passengerModal').style.display = 'none';
    });

    window.addEventListener('click', (e) => {
        if (e.target === document.getElementById('passengerModal')) {
            document.getElementById('passengerModal').style.display = 'none';
        }
    });


</script>