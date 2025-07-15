<?php
session_start();

// Zerstört alle Session-Daten
session_destroy();

// Löscht alle Session-Cookies
if (isset($_COOKIE[session_name()])) {
    setcookie(session_name(), '', time() - 3600, '/');
}

// Weiterleitung zur Home-Seite
header("Location: home.html");
exit();