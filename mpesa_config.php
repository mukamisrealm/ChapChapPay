<?php
// Daraja Sandbox Configuration
define('MPESA_ENV', 'sandbox'); 
define('MPESA_CONSUMER_KEY', 'Yib0Iryue9wSWCUs19bGiOYYwKBATgHiJxJxBvhxTgA0zypF'); // key
define('MPESA_CONSUMER_SECRET', 'vCLlXLKtsYaSsSvkylmDIVLDXm05uziHxmCbDuX4UfCQAIcaBjccu4p5J20lZPdh'); //secret
define('MPESA_SHORTCODE', '174379'); // Sandbox Business ShortCode
define('MPESA_PASSKEY', 'bfb279f9aa9bdbcf158e97dd71a467cd2e0c893059b10f78e6b72ada1ed2c919'); // Default Passkey

// For local testing, use ngrok URL or a webhook listener for callbacks
define('MPESA_CALLBACK_URL', 'https://cubicle-bucktooth-reroute.ngrok-free.dev/ChapChapPay/mpesa_callback.php'); 
?>