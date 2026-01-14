<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * ToolController
 * 
 * This controller contains administrative tools and should be protected.
 * It is NOT for public use. It helps developers with common tasks like
 * generating password hashes.
 */
class ToolController extends CI_Controller {

    public function __construct() {
        parent::__construct();
        // IMPORTANT: Add security check here in the future if needed.
        // For example, you could check for a specific IP address or a special session variable.
    }

    /**
     * Generates a secure password hash and a sample SQL query.
     * 
     * How to use:
     * 1. Go to the URL: yoursite.com/ToolController/hash
     * 2. To hash a specific password, add it to the URL:
     *    yoursite.com/ToolController/hash/your_password_here
     */
    public function hash($password_string = 'password') // Default password is 'password' if none is provided
    {
        // Sanitize the input for display
        $safe_password = htmlspecialchars($password_string, ENT_QUOTES, 'UTF-8');

        // Generate the secure hash
        $new_hash = password_hash($password_string, PASSWORD_DEFAULT);

        // --- NEW FEATURE: Create the SQL template ---
        $sql_template = "UPDATE `users` \n" .
                        "SET `password` = '" . $new_hash . "' \n" .
                        "WHERE `username` = 'the_secretarys_username';";

        // Display the results in a clean, readable format
        header('Content-Type: text/plain');
        echo "=======================================================\n";
        echo " SECURE PASSWORD HASH GENERATOR\n";
        echo "=======================================================\n\n";
        echo "Password to Hash: " . $safe_password . "\n\n";
        echo "Your New Secure Hash (for phpMyAdmin):\n";
        echo "-------------------------------------------------------\n";
        echo $new_hash . "\n";
        echo "-------------------------------------------------------\n\n";
        
        echo "=======================================================\n";
        echo " SQL QUERY TEMPLATE\n";
        echo "=======================================================\n\n";
        echo "Copy the query below, change the username, and run it in phpMyAdmin's SQL tab:\n\n";
        echo $sql_template;
    }
}