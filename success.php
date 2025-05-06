<?php
if ($_GET["type"] == 'cancel') {
    ?>
    <div class="success-message"
        style="padding: 2rem; background-color: #fff3f3; border-radius: 12px; text-align: center; margin-top: 3rem; border: 1px solid #ffc2c2; box-shadow: 0 4px 12px rgba(0,0,0,0.05); font-family: 'Segoe UI', sans-serif;">

        <div style="font-size: 3rem; margin-bottom: 1rem;">🚫</div>

        <h1 style="color: #cc0000; font-size: 2rem; margin-bottom: 0.5rem;">Ride Canceled</h1>

        <p style="color: #990000; font-size: 1rem; margin: 0;">You’ll be redirected to the homepage shortly...</p>
    </div>

    <?php
} else {
    ?>

    <div class="success-message"
        style="padding: 2rem; background-color: #e9f7ef; border-radius: 10px; text-align: center; margin-top: 2rem;">
        <h1 style="color: #28a745;">🎉 Booking Confirmed!</h1>
        <p style="color: #155724;">You’ll be redirected to the homepage shortly...</p>
    </div>

    <?php
}

?>
<script>
    // Redirect to index.php after 3 seconds and clear the history
    setTimeout(function () {
        window.location.replace("index.php");
    }, 3000);
</script>