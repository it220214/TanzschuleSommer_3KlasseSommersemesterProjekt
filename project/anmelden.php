<?php
session_start();
require "mysql.php";

// Debug-Ausgaben
error_log("=== Login Debug Start ===");
error_log("POST data: " . print_r($_POST, true));
error_log("SESSION data: " . print_r($_SESSION, true));
error_log("GET data: " . print_r($_GET, true));
error_log("=== Login Debug End ===");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    
    $stmt = $conn->prepare("SELECT UserId, Password FROM User WHERE Email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $row = $result->fetch_assoc();

        if (password_verify($password, $row['Password'])) {
            $_SESSION["userId"] = $row['UserId'];
            $_SESSION["email"] = $email;

            // Verarbeite ausstehende Kursanmeldung
            if (isset($_SESSION['pending_course'])) {
                $courseName = $_SESSION['pending_course'];
                $courseId = $_SESSION['pending_course_id'] ?? null;
                unset($_SESSION['pending_course']);
                unset($_SESSION['pending_course_id']);

                if ($courseId) {
                    // Prüfe ob User bereits für den Kurs registriert ist
                    $checkStmt = $conn->prepare("SELECT * FROM UserCourse WHERE UserId = ? AND CourseId = ?");
                    $checkStmt->bind_param("ss", $row['UserId'], $courseId);
                    $checkStmt->execute();

                    if ($checkStmt->get_result()->num_rows == 0) {
                        // Führe Kursanmeldung durch
                        $insertStmt = $conn->prepare("INSERT INTO UserCourse (UserId, CourseId) VALUES (?, ?)");
                        $insertStmt->bind_param("ss", $row['UserId'], $courseId);
                        if ($insertStmt->execute()) {
                            header("Location: profile.php?success=course_registered");
                            exit();
                        }
                    }
                }
            }

            header("Location: profile.php");
            exit();
        }
    }
    $error = "Ungültige Anmeldedaten";
}
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Anmelden - Tanzschule Sommer</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="website icon" type="png" href="./img/tsLogoWhite.png">
    <link rel="stylesheet" href="index.css">

</head>
<body>


<div id="anmeldenContainer">
    <?php if (isset($error)): ?>
        <div class="error-message"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="POST">
        <div class="mb-3">
            <label for="email" class="form-label">Email:</label>
            <input type="email" class="form-control" id="email" name="email" required>
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Passwort:</label>
            <input type="password" class="form-control" id="password" name="password" required>
        </div>
        <button type="submit" class="btn btn-primary w-100">Einloggen</button>
    </form>
</div>

</body>
</html>