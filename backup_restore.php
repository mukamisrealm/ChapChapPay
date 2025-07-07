<?php
// backup.php

// Database config
$dbHost = 'localhost';
$dbUser = 'root';
$dbPass = ''; // Use your password if set
$dbName = 'chapchap';

// File name
$date = date('Y-m-d_H-i-s');
$backupFile = "backups/{$dbName}_{$date}.sql";

// Path to mysqldump (adjust this if needed for your system)
$dumpCommand = "\"C:\\xampp\\mysql\\bin\\mysqldump.exe\" -u {$dbUser} " . 
               (!empty($dbPass) ? "-p{$dbPass} " : "") . 
               "{$dbName} > {$backupFile}";

// Run the command
exec($dumpCommand, $output, $result);

// Return JSON response
if ($result === 0) {
    echo json_encode([
        "success" => true,
        "message" => "Backup created successfully.",
        "file" => $backupFile
    ]);
} else {
    echo json_encode([
        "success" => false,
        "message" => "Backup failed.",
        "error_code" => $result
    ]);
}
?>
