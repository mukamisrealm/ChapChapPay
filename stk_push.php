<?php
// Ensure no hidden characters or whitespace exist before this tag
error_reporting(E_ALL);
ini_set('display_errors', 0); // Keep output clean for JSON

header('Content-Type: application/json');

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once 'db.php';

// Safe check for config file
if (file_exists('mpesa_config.php')) {
    require_once 'mpesa_config.php';
} else {
    echo json_encode(['status' => 'error', 'message' => 'mpesa_config.php file is missing in the project folder.']);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method.']);
    exit();
}

$phone = $_POST['phone'] ?? '';
$amount = $_POST['amount'] ?? '';
$user_id = $_POST['target_user_id'] ?? ($_SESSION['user_id'] ?? 0);

if (empty($phone) || empty($amount) || (float)$amount <= 0) {
    echo json_encode(['status' => 'error', 'message' => 'Please provide a valid phone number and amount.']);
    exit();
}

// Format Phone Number to 254XXXXXXXXX
$phone = trim($phone);
$phone = preg_replace('/^0/', '254', $phone);
$phone = preg_replace('/^\+/', '', $phone);

if (!preg_match('/^2547\d{8}$|^2541\d{8}$/', $phone)) {
    echo json_encode(['status' => 'error', 'message' => 'Invalid Safaricom phone format. Use 7XXXXXXXX or 07XXXXXXXX.']);
    exit();
}

// 1. Fetch Daraja Access Token
$token_url = 'https://sandbox.safaricom.co.ke/oauth/v1/generate?grant_type=client_credentials';
if (!defined('MPESA_CONSUMER_KEY') || !defined('MPESA_CONSUMER_SECRET')) {
    echo json_encode(['status' => 'error', 'message' => 'Consumer Key or Secret missing in mpesa_config.php.']);
    exit();
}

$credentials = base64_encode(MPESA_CONSUMER_KEY . ':' . MPESA_CONSUMER_SECRET);

$ch = curl_init($token_url);
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Authorization: Basic ' . $credentials]);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
$response = curl_exec($ch);

if (curl_errno($ch)) {
    echo json_encode(['status' => 'error', 'message' => 'cURL Token Error: ' . curl_error($ch)]);
    curl_close($ch);
    exit();
}
curl_close($ch);

$result = json_decode($response, true);
$access_token = $result['access_token'] ?? null;

if (!$access_token) {
    echo json_encode(['status' => 'error', 'message' => 'Failed to obtain access token. Verify Consumer Key and Secret in Daraja.']);
    exit();
}

// 2. Initiate STK Push
$timestamp = date('YmdHis');
$password = base64_encode(MPESA_SHORTCODE . MPESA_PASSKEY . $timestamp);
$stk_url = 'https://sandbox.safaricom.co.ke/mpesa/stkpush/v1/processrequest';

$stkHeader = [
    'Content-Type: application/json',
    'Authorization: Bearer ' . $access_token
];

$stkBody = [
    'BusinessShortCode' => MPESA_SHORTCODE,
    'Password' => $password,
    'Timestamp' => $timestamp,
    'TransactionType' => 'CustomerPayBillOnline',
    'Amount' => (int)$amount,
    'PartyA' => $phone,
    'PartyB' => MPESA_SHORTCODE,
    'PhoneNumber' => $phone,
    'CallBackURL' => MPESA_CALLBACK_URL,
    'AccountReference' => 'ChapChapPay',
    'TransactionDesc' => 'Chama Contribution Payment'
];

$ch = curl_init($stk_url);
curl_setopt($ch, CURLOPT_HTTPHEADER, $stkHeader);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($stkBody));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
$stkResponse = curl_exec($ch);
curl_close($ch);

$stkResult = json_decode($stkResponse, true);

if (isset($stkResult['ResponseCode']) && $stkResult['ResponseCode'] == '0') {
    $checkout_id = $stkResult['CheckoutRequestID'];

    $stmt = $conn->prepare("INSERT INTO contributions (user_id, amount, phone, checkout_request_id, status, created_at) VALUES (?, ?, ?, ?, 'pending', NOW())");
    if ($stmt) {
        $stmt->bind_param("idss", $user_id, $amount, $phone, $checkout_id);
        $stmt->execute();
        $stmt->close();
    }

    echo json_encode([
        'status' => 'success', 
        'message' => 'STK Push sent! Please check your phone to enter your M-Pesa PIN.',
        'checkout_id' => $checkout_id
    ]);
} else {
    $errorMessage = $stkResult['errorMessage'] ?? ($stkResult['ResponseDescription'] ?? 'Failed to trigger STK Push.');
    echo json_encode([
        'status' => 'error', 
        'message' => $errorMessage
    ]);
}
?>