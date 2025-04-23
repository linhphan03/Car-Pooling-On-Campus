//Prabesh Bista
//Code to update the button color when clicked
function setupRideTabButtons() {
    const tabButtons = document.querySelectorAll(".rides-tabs .tab-button");

    tabButtons.forEach(button => {
        button.addEventListener("click", function () {
            tabButtons.forEach(btn => btn.classList.remove("active"));
            this.classList.add("active");
        });
    });
}

document.addEventListener("DOMContentLoaded", setupRideTabButtons);