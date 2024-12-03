<?php
session_start(); // Ensure session is started at the beginning

require "../config/config.php";

if (!isset($_SESSION['booking_details'])) {
    header("Location: index.php");
    exit;
}

$booking_details = $_SESSION['booking_details'];
$reference = $_GET['reference'];

// Define APPURL if not already defined
if (!defined('APPURL')) {
    define("APPURL", "http://localhost/hotelBookn/");
}

$paystack_url = "https://api.paystack.co/transaction/initialize";
$callback_url = APPURL . "rooms/payment_success.php";

// Prepare Paystack data
$data = [
    'email' => $booking_details['email'],
    'amount' => $booking_details['price'] * 100, // Paystack uses kobo
    'reference' => $reference,
    'callback_url' => $callback_url,
];

// Set up HTTP options for the Paystack request
$options = [
    "http" => [
        "header" => "Authorization: Bearer sk_test_08a8571ae464462b605b11e3a570a063eeefd45d\r\n" .
                    "Content-Type: application/json\r\n",
        "method" => "POST",
        "content" => json_encode($data),
    ],
];

// Initialize the transaction
$context = stream_context_create($options);
$response = @file_get_contents($paystack_url, false, $context);
$result = json_decode($response, true);

if ($result && isset($result['status']) && $result['status']) {
    // Redirect to Paystack authorization URL
    header("Location: " . $result['data']['authorization_url']);
    exit;
} else {
    // Handle error during Paystack transaction initialization
    echo "Error initializing payment. Please try again.";
}
?>
