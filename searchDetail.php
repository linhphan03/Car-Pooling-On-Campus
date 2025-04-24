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

?>

<div class="ride-detail-wrapper">

    <!-- Left Section -->
    <div class="ride-detail-left">
        <div class="ride-profile">
            <img class="driver-avatar" src="driver_image.png" alt="Driver Profile">
            <div class="driver-info">
                <h3>Millor</h3>
                <p class="rating">⭐ 5.0 · 115 trips driven</p>
            </div>
        </div>

        <div class="ride-info">
            <h2>Gettysburg to Washington DC</h2>
            <p class="ride-time">Leaving Saturday, April 26 at 8:45 AM</p>
            <p><strong>Pickup:</strong> Gettysburg College</p>
            <p><strong>Dropoff:</strong> Washington DC</p>
            <p class="seats-left">4 seats left · <span class="price">$10 per seat</span></p>
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
                <p><strong>Millor</strong> · ⭐ 5.0 (115 driven)</p>
                <p>Member since July 2023</p>
                <p>🚗 Passengers driven: <strong>115</strong></p>
                <div class="verifications">
                    <p>✅ Driver's license verified</p>
                    <p>📨 Email address: Verified</p>
                    <p>📜 Community agreement: Signed</p>
                </div>
            </div>

            <div class="recent-reviews">
                <h4>Recent reviews</h4>
                <div class="review">
                    <p><strong>Ruchi:</strong> She is very nice and cooperative.</p>
                </div>
                <div class="review">
                    <p><strong>Kai:</strong> Millor was so nice! I loved the travel. It didn’t feel like a 4-hour
                        drive.
                    </p>
                </div>
                <div class="review">
                    <p><strong>Md Baysur:</strong> It was a great experience.</p>
                </div>
                <a href="#" class="view-all-reviews">Read all reviews →</a>
            </div>
        </div>

    </div>

</div>