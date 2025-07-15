<?php
session_start();
require 'mysql.php';

error_log("=== Debug Start ===");
error_log("POST data: " . print_r($_POST, true));
error_log("SESSION data: " . print_r($_SESSION, true));

$courseName = isset($_POST['course_name']) ? $_POST['course_name'] : null;
$courseId = isset($_POST['course_id']) ? $_POST['course_id'] : null;

if (!isset($_SESSION['userId'])) {
    error_log("User not logged in, redirecting to login");
    $_SESSION['pending_course'] = $courseName;
    $_SESSION['pending_course_id'] = $courseId;
    $showLoginOption = true;
} else {
    $showLoginOption = false;
}

if ($courseName && $courseId) {
    error_log("Processing course registration for: " . $courseName . " (ID: " . $courseId . ")");

    if (isset($_SESSION['userId'])) {
        $userId = $_SESSION['userId'];
        error_log("User ID found: " . $userId);

        try {
            // Starte eine Transaktion
            $conn->begin_transaction();

            // 1. Update User-Tabelle
            $stmtUpdateUser = $conn->prepare("UPDATE UserCourse SET CourseId = ? WHERE UserId = ?");
            if (!$stmtUpdateUser) {
                error_log("Prepare user update failed: " . $conn->error);
                throw new Exception("Prepare user update failed");
            }

            $stmtUpdateUser->bind_param("ss", $courseId, $userId);
            if (!$stmtUpdateUser->execute()) {
                error_log("User update failed: " . $stmtUpdateUser->error);
                throw new Exception("User update failed");
            }

            // 2. Prüfe auf existierende Einträge in UserCourse
            $checkStmt = $conn->prepare("SELECT * FROM UserCourse WHERE UserId = ? AND CourseId = ?");
            $checkStmt->bind_param("ss", $userId, $courseId);
            $checkStmt->execute();
            $checkResult = $checkStmt->get_result();

            if ($checkResult->num_rows == 0) {
                // 3. Füge Eintrag in UserCourse hinzu
                $stmtUserCourse = $conn->prepare("INSERT INTO UserCourse (UserId, CourseId) VALUES (?, ?)");
                if (!$stmtUserCourse) {
                    error_log("Prepare UserCourse insert failed: " . $conn->error);
                    throw new Exception("Prepare UserCourse insert failed");
                }

                $stmtUserCourse->bind_param("ss", $userId, $courseId);
                if (!$stmtUserCourse->execute()) {
                    error_log("UserCourse insert failed: " . $stmtUserCourse->error);
                    throw new Exception("UserCourse insert failed");
                }
            }

            // Wenn alles erfolgreich war, commit die Transaktion
            $conn->commit();

            error_log("Successfully registered for course in both tables");
            header("Location: profile.php?success=course_registered");
            exit();

        } catch (Exception $e) {
            // Bei Fehler, rolle die Transaktion zurück
            $conn->rollback();
            error_log("Error occurred: " . $e->getMessage());
            header("Location: course.php?error=registration_failed");
            exit();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Anmelden/Registrieren - Tanzschule Sommer</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="website icon" type="png" href="./img/tsLogoWhite.png">
    <link rel="stylesheet" href="index.css">
</head>
<body>
<?php if (isset($showLoginOption) && $showLoginOption): ?>
    <div class="container mt-5">
        <div class="d-grid gap-3 row-3 mx-auto" id="registerOrLogInContainer">
            <button class="btn btn-primary" type="button" onclick="window.location.href='anmelden.php'">Anmelden
            </button>
            <button class="btn btn-primary" type="button" onclick="window.location.href='register.php'">Registrieren
            </button>
        </div>
    </div>
<?php else: ?>
<div class="d-grid gap-3 row-3 mx-auto" id="registerOrLogInContainer">
    <button class="btn btn-primary" type="button" onclick="window.location.href='anmelden.php'">Anmelden</button>
    <button class="btn btn-primary" type="button" onclick="window.location.href='register.php'">Registrieren</button>
</div>
<?php endif; ?>
</body>
</html>