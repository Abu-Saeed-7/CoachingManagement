<?php
/**
 * Dedicated Logout Controller
 * Destroys session, cleans cookies, and redirects to loginPage.php cleanly.
 */
require_once __DIR__ . '/../../backend/controllers/AuthController.php';

$authController = new AuthController();
$authController->handleLogout('login_page/loginPage.php');
