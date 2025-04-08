<input?php ?>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Dashboard</title>
        <link rel="stylesheet" href="dashboard.css" />
    </head>

    <body>
        <nav class="nav">
            <div class="logo">Gettysburg CarPooling</div>
            <div class="navButton">
                <a href="#">Home</a>
                <a href="#">Profile</abs>
                    <a href="#">FAQ/Support</a>
                    <a href="#">About us</a>
            </div>



        </nav>
        <div class="mainContainer">
            <div class="container">
                <div class="rideRequest">
                    <div class="newRide">
                        <a href="#">New Ride +</a>
                        <div class="rideTo">
                            <div></div>
                            <h2>Ride to</h2>
                            <!-- This is where the data would be fetched from DB using PHP -->
                            <ol>
                                <li>Target-Hanover</li>
                                <li>Walmart-Gettysburg</li>
                                <li>Gettysburg-Harrisburg Intl. Airport</li>
                                <li>Target - Chambersburg</li>
                            </ol>
                        </div>
                    </div>
                    <div class="rideDate">
                        <h2>Ride Date</h2>
                        <form class="formDate" action="#" method="POST">
                            <div class="fromDate">
                                <label>From:</label>
                                <input type="date" />
                            </div>
                            <div class="toDate">
                                <label>To: </label>
                                <input type="date" />
                            </div>
                            <input class="submitButton" type="submit" value="Search Ride" />
                        </form>
                    </div>
                </div>
                <div class="suggestBox">
                    <div class="sgButn"> Suggested Upcoming Rides!</div>
                    <div class="sugBox1">
                        <div class="sgcard">
                            <h3>Cub To Target -Hanover</h3>
                            <h3>03/6/2025</h3>

                            <h6>3 Seats Remaining</h6>
                            <a href="#">Read More</a>
                        </div>
                        <div class="sgcard">
                            <h3>Cub To Target -Hanover</h3>
                            <h3>03/6/2025</h3>

                            <h6>3 Seats Remaining</h6>
                            <a href="#">Read More</a>
                        </div>
                        <div class="sgcard">
                            <h3>Cub To Target -Hanover</h3>
                            <h3>03/6/2025</h3>

                            <h6>3 Seats Remaining</h6>
                            <a href="#">Read More</a>
                        </div>
                        <div class="sgcard">
                            <h3>Cub To Target -Hanover</h3>
                            <h3>03/6/2025</h3>

                            <h6>3 Seats Remaining</h6>
                            <a href="#">Read More</a>
                        </div>

                    </div>
                </div>

            </div>
        </div>




    </body>

    </html>




    <?php

    ?>