<?php
// Set the desired session save path
$sessionSavePath = getcwd() . '/ssn';

// Check if the session save path needs to be updated
if (session_status() == PHP_SESSION_ACTIVE && session_save_path() != $sessionSavePath) {
    // Destroy the active session
    session_destroy();

    // Set the new session save path
    ini_set('session.save_path', $sessionSavePath);

    // Start a new session
    session_start();
} else {
    // Set the session save path
    ini_set('session.save_path', $sessionSavePath);
}
