document.addEventListener("DOMContentLoaded", function () {
    // Get the booking form
    const bookingForm = document.getElementById("booking-form");

    // Add a submit event listener to the form
    bookingForm.addEventListener("submit", function (event) {
        event.preventDefault(); // Prevent the form from submitting
        window.location.href = "./new_booking_2.html"; // Redirect to the desired page
    });
});
