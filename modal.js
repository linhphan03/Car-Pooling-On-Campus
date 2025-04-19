document.addEventListener("DOMContentLoaded", () => {
    const modal = document.getElementById("ride-modal");
    const modalBody = document.getElementById("modal-body");
    const closeBtn = document.querySelector(".close-btn");

    document.querySelectorAll(".read-more-link").forEach(link => {
        link.addEventListener("click", function (e) {
            e.preventDefault();
            const rideID = this.getAttribute("data-ride-id");

            fetch(`ride_details.php?ride_ID=${rideID}`)
                .then(response => response.text())
                .then(data => {
                    modalBody.innerHTML = data;
                    modal.style.display = "block";
                })
                .catch(err => {
                    modalBody.innerHTML = "<p>Unable to load ride details.</p>";
                    modal.style.display = "block";
                });
        });
    });

    closeBtn.addEventListener("click", () => {
        modal.style.display = "none";
    });

    window.addEventListener("click", (e) => {
        if (e.target == modal) {
            modal.style.display = "none";
        }
    });
});
