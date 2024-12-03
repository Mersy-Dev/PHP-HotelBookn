<?php
// session_start();
require "../config/config.php";
require "../includes/header.php";

// Ensure booking details are available
if (!isset($_SESSION['booking_details'])) {
    header("Location: " . APPURL . "index.php");
    exit;
}

// Get reference from the query string
if (!isset($_GET['reference'])) {
    echo "<script>
        alert('Invalid request. No payment reference provided.'); 
        window.location.href = '" . APPURL . "index.php';
    </script>";
    exit;
}

$reference = $_GET['reference'];
$paystack_url = "https://api.paystack.co/transaction/verify/$reference";

// Verify payment with Paystack
$options = [
    "http" => [
        "header" => "Authorization: Bearer sk_test_08a8571ae464462b605b11e3a570a063eeefd45d\r\n",
        "method" => "GET",
    ],
];

$context = stream_context_create($options);
$response = @file_get_contents($paystack_url, false, $context);

if ($response === false) {
    echo "<script>
        alert('Error verifying payment. Please contact support.'); 
        window.location.href = '" . APPURL . "index.php';
    </script>";
    exit;
}

$result = json_decode($response, true);

if ($result && $result['status'] && $result['data']['status'] == "success") {
    // Payment verified successfully
    $booking_details = $_SESSION['booking_details'];

    // Insert booking details into the database
    $stmt = $conn->prepare(
        "INSERT INTO bookings (email, full_name, phone_number, check_in, check_out, hotel_name, room_name, user_id, reference) 
        VALUES (:email, :full_name, :phone_number, :check_in, :check_out, :hotel_name, :room_name, :user_id, :reference)"
    );
    $stmt->bindParam(':email', $booking_details['email']);
    $stmt->bindParam(':full_name', $booking_details['full_name']);
    $stmt->bindParam(':phone_number', $booking_details['phone_number']);
    $stmt->bindParam(':check_in', $booking_details['check_in']);
    $stmt->bindParam(':check_out', $booking_details['check_out']);
    $stmt->bindParam(':hotel_name', $booking_details['hotel_name']);
    $stmt->bindParam(':room_name', $booking_details['room_name']);
    $stmt->bindParam(':user_id', $booking_details['user_id']);
    $stmt->bindParam(':reference', $reference);
    $stmt->execute();

    // Success message and cleanup
    echo "<script>
        alert('Payment successful! Your booking has been confirmed.'); 
        window.location.href = '" . APPURL . "index.php';
    </script>";
    unset($_SESSION['booking_details']);
    exit;
} else {
    // Handle failed verification
    echo "<script>
        alert('Payment verification failed. Please try again.'); 
        window.location.href = '" . APPURL . "index.php';
    </script>";
}
?>
