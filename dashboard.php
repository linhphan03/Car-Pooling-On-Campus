<!-- Prabesh Bista -->

<!-- Main container split into two side-by-side columns -->
<div class="main-content">

    <!-- LEFT SIDE: New Ride, Ride To List, and Ride Date Form -->
    <div class="left-side">

        <!-- New Ride Button (unchanged) -->
        <div class="new-ride">
            <button class="new-ride-btn">New Ride +</button>
        </div>

        <!-- Ride To List -->
        <div class="ride-to-section">
            <h2>Ride to</h2>
            <ul class="ride-to-list">
                <li>Target - Hanover</li>
                <li>Walmart - Gettysburg</li>
                <li>Harrisburg Intl. Airport</li>
                <li>Target - Chambersburg</li>
            </ul>
        </div>

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
            <form action="#" method="POST">
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
                <article class="ride-card">
                    <p class="ride-date">03/16/2025</p>
                    <h3>CUB to Target - Hanover</h3>
                    <p>3 Seats Remaining</p>
                    <a href="#" class="read-more-link">Read More</a>
                </article>
                <article class="ride-card">
                    <p class="ride-date">03/17/2025</p>
                    <h3>CUB to Harrisburg Intl. Airport</h3>
                    <p>1 Seat Remaining</p>
                    <a href="#" class="read-more-link">Read More</a>
                </article>
                <article class="ride-card">
                    <p class="ride-date">03/10/2025</p>
                    <h3>Hillel House to Walmart</h3>
                    <p>5 Seats Remaining</p>
                    <a href="#" class="read-more-link">Read More</a>
                </article>
                <article class="ride-card">
                    <p class="ride-date">03/10/2025</p>
                    <h3>CUB to Target - Chambersburg</h3>
                    <p>2 Seats Remaining</p>
                    <a href="#" class="read-more-link">Read More</a>
                </article>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div id="ride-modal" class="modal hidden">
        <div class="modal-content">
            <span class="close-btn">&times;</span>
            <div id="modal-body">
                <!-- Ride details will be loaded here -->
            </div>
        </div>
    </div>

    <!-- Prabesh Bista -->