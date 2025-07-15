<?php
session_start();
require "mysql.php";
$errors = [];
$success = false;

$FirstName = $SurName = $Email = $Password = "";
$Level = " ";  
$CourseId = 1; 

// Wenn das Formular abgesendet wird
if (isset($_POST["submit"])) {

    // Eingabewerte validieren
    if (empty($_POST["FirstName"])) {
        $errors[] = "Vorname ist erforderlich.";
    } else {
        $FirstName = mysqli_real_escape_string($conn, $_POST["FirstName"]);
    }

    if (empty($_POST["SurName"])) {
        $errors[] = "Nachname ist erforderlich.";
    } else {
        $SurName = mysqli_real_escape_string($conn, $_POST["SurName"]);
    }

    if (empty($_POST["Email"])) {
        $errors[] = "Email ist erforderlich.";
    } else {
        $Email = mysqli_real_escape_string($conn, $_POST["Email"]);

        // Überprüfen, ob die E-Mail bereits existiert
        $sql = "SELECT UserId FROM User WHERE Email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $Email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $errors[] = "Die E-Mail-Adresse ist bereits registriert.";
        }
        $stmt->close();
    }

    if (empty($_POST["Password"])) {
        $errors[] = "Passwort ist erforderlich.";
    } else {
        $Password = mysqli_real_escape_string($conn, $_POST["Password"]);
        $Password = password_hash($Password, PASSWORD_DEFAULT);  // Passwort verschlüsseln
    }

    // --- Generierung der UserId (z. B. TE001 oder LE002) ---
    $UserType = "User"; 
    // Präfix (TE für Tänzer, LE für Lehrer)
    $prefix = ($UserType === "Teacher") ? "LE" : "TE";

    // Hole die höchste UserId für den jeweiligen Typ (Tänzer oder Lehrer)
    $stmt = $conn->prepare("SELECT MAX(SUBSTRING(UserId, 4)) FROM User WHERE UserId LIKE ?");
    $like = $prefix . "%"; // "TE%" für Tänzer oder "LE%" für Lehrer
    $stmt->bind_param("s", $like);
    $stmt->execute();
    $stmt->bind_result($lastId);
    $stmt->fetch();
    $stmt->close();

    // Nächste Nummer berechnen (z. B. TE001, LE002)
    $number = ($lastId) ? intval($lastId) + 1 : 1; // Falls noch keine ID existiert, starte mit 1

    // UserId im Format TE0001 oder LE0002 erstellen
    $newUserId = $prefix . str_pad($number, 4, "0", STR_PAD_LEFT); //führende Nullen hinzufügen (0004)

    // --- End der Generierung der UserId ---

    // Wenn keine Fehler, Benutzer in die Datenbank einfügen
    if (empty($errors)) {
        // Bereite die SQL-Anweisung vor, um den Benutzer mit der generierten UserId einzufügen
        $stmt = $conn->prepare("INSERT INTO User (UserId, FirstName, SurName, Email, Password, Level) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssss", $newUserId, $FirstName, $SurName, $Email, $Password, $Level);

        if ($stmt->execute()) {
            $success = true;
            // Weiterleitung zur Startseite nach erfolgreicher Registrierung
            header("Location: home.html");  // Weiterleitung zur Login-Seite oder Home (anpassen)
            exit();
        } else {
            $errors[] = "Fehler bei der Registrierung: " . $stmt->error;
        }

        $stmt->close();
    }
}
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrieren - Tanzschule Sommer</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="website icon" type="png" href="./img/tsLogoWhite.png">
    <link rel="stylesheet" href="index.css">

    <script>
        let timestamp = new Date().getTime();
        let link = document.createElement('link');
        link.rel = 'stylesheet';
        let script = document.createElement('script');
        script.defer = true;

        // relative path to your files
        link.href = 'index.css' + `?${timestamp}`;
        script.src = 'index.js' + `?${timestamp}`;

        document.head.appendChild(link);
        document.head.appendChild(script);
    </script>
</head>
<body>
<div class="container">
    <!-- Erfolgsnachricht -->
    <?php if ($success): ?>
        <div class="success">Registrierung erfolgreich!</div>
    <?php endif; ?>

    <!-- Fehlermeldungen -->
    <?php if (!empty($errors)): ?>
        <div class="error">
            <ul>
                <?php foreach ($errors as $err): ?>
                    <li><?= htmlspecialchars($err) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form id="formRegister" method="post" action="">
        <div class="container">
            <div class="mb-3">
                <label for="firstname" class="form-label">Vorname:</label>
                <input type="text" class="form-control" id="firstname" name="FirstName" value="<?= htmlspecialchars($FirstName) ?>" required>
            </div>
            <div class="mb-3">
                <label for="surname" class="form-label">Nachname:</label>
                <input type="text" class="form-control" id="surname" name="SurName" value="<?= htmlspecialchars($SurName) ?>" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email:</label>
                <input type="email" class="form-control" id="email" name="Email" value="<?= htmlspecialchars($Email) ?>" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Passwort:</label>
                <input type="password" class="form-control" id="password" name="Password" value="<?= htmlspecialchars($Password) ?>" required>
            </div>
            <button type="submit" class="btn btn-primary" name="submit">Registrieren</button>
        </div>
    </form>
</div>
</body>
</html>