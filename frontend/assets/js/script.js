// Fare Calculation based on Vehicle Type and Distance
function calculateFare() {
    let vehicleType = document.querySelector('input[name="vehicle_type"]:checked').value;
    let distance = document.getElementById('distance').value;
    let fare = 0;

    // Fare rates
    if (vehicleType === "bike") {
        fare = distance * 10; // ₹10 per km for bike
    } else if (vehicleType === "small_tempo") {
        fare = distance * 15; // ₹15 per km for small tempo
    } else if (vehicleType === "truck") {
        fare = distance * 50; // ₹50 per km for truck
    }

    // Display the calculated fare
    document.getElementById('fare_display').innerHTML = `₹${fare}`;
}

// Event listener for form submission
document.getElementById('booking_form').addEventListener('submit', function(event) {
    event.preventDefault(); // Prevent form submission for validation

    // Collect form data
    let vehicleType = document.querySelector('input[name="vehicle_type"]:checked');
    let distance = document.getElementById('distance').value;
    let pickupLocation = document.getElementById('pickup_location').value;
    let dropLocation = document.getElementById('drop_location').value;

    // Simple validation
    if (!vehicleType || !distance || !pickupLocation || !dropLocation) {
        alert("Please fill in all the fields");
        return;
    }

    // Submit the form via AJAX or POST (here we'll assume a basic POST)
    let formData = new FormData();
    formData.append('vehicle_type', vehicleType.value);
    formData.append('pickup_location', pickupLocation);
    formData.append('drop_location', dropLocation);
    formData.append('distance', distance);

    // Use AJAX to send the form data to the backend (booking.php)
    fetch('backend/booking.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.text())
    .then(data => {
        alert(data); // Show the server response (e.g., "Booking Successful!")
    })
    .catch(error => {
        console.error('Error:', error);
        alert("An error occurred while booking. Please try again.");
    });
});

// Optional: You could add an event listener for any other interactivity you need, such as status updates, etc.
