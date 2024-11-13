<?php
session_start();
if (!isset($_SESSION['isloggedIn']) || $_SESSION['isloggedIn'] !== true) {
    header("Location: ../index.html"); // Redirect to the login page if not logged in
    exit();
}
?>