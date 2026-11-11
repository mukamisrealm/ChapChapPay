<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Handle language toggle via GET request
if (isset($_GET['lang'])) {
    $allowed_langs = ['en', 'sw'];
    if (in_array($_GET['lang'], $allowed_langs)) {
        $_SESSION['lang'] = $_GET['lang'];
    }
}

// Set default language to English if not set
$current_lang = $_SESSION['lang'] ?? 'en';

// Dictionary mappings
$translations = [
    'en' => [
        'app_name' => 'ChapChapPay',
        'dashboard' => 'Dashboard',
        'manage_members' => 'Manage Members',
        'add_fine' => 'Add Fine',
        'view_fines' => 'View Fines',
        'reports' => 'Reports',
        'requests' => 'Requests',
        'add_contribution' => 'Add Contribution',
        'payout_queue' => 'Payout Queue',
        'my_contributions' => 'My Contributions',
        'my_fines' => 'My Fines',
        'payout_schedule' => 'Payout Schedule',
        'make_request' => 'Make a Request',
        'logout' => 'Logout',
        'welcome' => 'Welcome',
        'amount' => 'Amount (KES)',
        'date' => 'Date',
        'status' => 'Status',
        'submit' => 'Submit',
        'back_to_dashboard' => 'Back to Dashboard',
        'switch_lang' => 'Kiswahili',
        'switch_code' => 'sw'
    ],
    'sw' => [
        'app_name' => 'ChapChapPay',
        'dashboard' => 'Kipengele Kuu',
        'manage_members' => 'Simamia Wanachama',
        'add_fine' => 'Weka Faini',
        'view_fines' => 'Angalia Faini',
        'reports' => 'Ripoti',
        'requests' => 'Maombi',
        'add_contribution' => 'Weka Michango',
        'payout_queue' => 'Mzunguko wa Malipo',
        'my_contributions' => 'Michango Yangu',
        'my_fines' => 'Faini Zangu',
        'payout_schedule' => 'Ratiba ya Malipo',
        'make_request' => 'Tuma Ombi',
        'logout' => 'Ondoka',
        'welcome' => 'Karibu',
        'amount' => 'Kiasi (KES)',
        'date' => 'Tarehe',
        'status' => 'Hali',
        'submit' => 'Tuma',
        'back_to_dashboard' => 'Rudi kwenye Kipengele Kuu',
        'switch_lang' => 'English',
        'switch_code' => 'en'
    ]
];

// Helper function to render text
function __($key) {
    global $translations, $current_lang;
    return $translations[$current_lang][$key] ?? $key;
}
?>