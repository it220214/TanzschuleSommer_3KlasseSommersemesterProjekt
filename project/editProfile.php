<?php
require "mysql.php";

// Benutzer-ID aus der URL holen
if (isset($_GET['UserId'])) {
    $user_id = $_GET['UserId'];
} else {
    die("Keine Benutzer-ID angegeben");
}

// Benutzerdaten abrufen
$query = "SELECT * FROM User WHERE UserId = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();

if (!$user) {
    die("Benutzer nicht gefunden");
}

if (isset($_POST['submit'])) {
    $first_name = $_POST['first_name'];
    $sur_name = $_POST['sur_name'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // E-Mail-Prüfung
    $check_query = "SELECT * FROM User WHERE Email = ? AND UserId != ?";
    $check_stmt = $conn->prepare($check_query);
    $check_stmt->bind_param("ss", $email, $user_id);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();

    if ($check_result->num_rows > 0) {
        $error_message = "Diese E-Mail-Adresse wird bereits verwendet.";
    } else {
        $profile_image = $user['ProfileImage'];

        if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] == 0) {
            $target_dir = "uploads/";
            if (!file_exists($target_dir)) {
                mkdir($target_dir, 0777, true);
            }
            $file_extension = strtolower(pathinfo($_FILES['profile_image']['name'], PATHINFO_EXTENSION));
            $new_filename = "profile_" . $user_id . "_" . time() . "." . $file_extension;
            $target_file = $target_dir . $new_filename;
            $valid_image = false;
            $check = getimagesize($_FILES['profile_image']['tmp_name']);
            if ($check !== false) {
                $valid_image = true;
            }
            if ($_FILES['profile_image']['size'] > 5000000) {
                $error_message = "Die Datei ist zu groß. Das Maximum beträgt 5MB.";
            } elseif (!in_array($file_extension, ['jpg', 'jpeg', 'png', 'gif'])) {
                $error_message = "Nur JPG, JPEG, PNG und GIF-Dateien sind erlaubt.";
            } elseif ($valid_image && move_uploaded_file($_FILES['profile_image']['tmp_name'], $target_file)) {
                $profile_image = $target_file;
            } else {
                $error_message = "Beim Hochladen des Bildes ist ein Fehler aufgetreten.";
            }
        }

        if (!isset($error_message)) {
            if (!empty($password)) {
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                $update_query = "UPDATE User SET FirstName = ?, SurName = ?, Email = ?, Password = ?, ProfileImage = ? WHERE UserId = ?";
                $update_stmt = $conn->prepare($update_query);
                $update_stmt->bind_param("ssssss", $first_name, $sur_name, $email, $hashed_password, $profile_image, $user_id);
            } else {
                $update_query = "UPDATE User SET FirstName = ?, SurName = ?, Email = ?, ProfileImage = ? WHERE UserId = ?";
                $update_stmt = $conn->prepare($update_query);
                $update_stmt->bind_param("sssss", $first_name, $sur_name, $email, $profile_image, $user_id);
            }

            if ($update_stmt->execute()) {
                $success_message = "Profil erfolgreich aktualisiert!";
                header("Location: profile.php?id=$user_id");
                $stmt = $conn->prepare($query);
                $stmt->bind_param("s", $user_id);
                $stmt->execute();
                $result = $stmt->get_result();
                $user = $result->fetch_assoc();
                $stmt->close();
            } else {
                $error_message = "Fehler beim Aktualisieren des Profils: " . $conn->error;
            }
            $update_stmt->close();
        }
    }
    $check_stmt->close();
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="de">

<head>
    <meta charset="UTF-8">
    <title>Profil bearbeiten - Tanzschule Sommer</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="index.css">
</head>

<body>
<div class="login-form-container brown-bg">
    <h2 class="form-title">Profil bearbeiten</h2>

    <?php if (isset($error_message)): ?>
        <div class="error-message"><?php echo $error_message; ?></div>
    <?php endif; ?>

    <?php if (isset($success_message)): ?>
        <div class="success-message"><?php echo $success_message; ?></div>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data">
        <div class="profile-image-upload">
            <div class="current-image">
                <?php if (!empty($user['ProfileImage'])): ?>
                    <img src="<?= htmlspecialchars($user['ProfileImage']) ?>" alt="Profilbild" width="30%">
                <?php else: ?>
                    <div class="profile-image-placeholder">Kein Bild ausgewählt</div>
                <?php endif; ?>
            </div>

            <div class="upload-controls">
                <label for="profile_image" class="file-upload-label">Profilbild</label>
                <input type="file" name="profile_image" id="profile_image" accept="image/jpeg,image/png,image/gif"
                       class="file-upload-input">
                <p class="upload-info">Erlaubt sind JPG, JPEG, PNG & GIF (max. 5MB)</p>
            </div>
        </div>

        <div class="form-group">
            <label for="first_name">Vorname</label>
            <input type="text" class="form-control" id="first_name" name="first_name"
                   value="<?= htmlspecialchars($user['FirstName']) ?>" required>
        </div>

        <div class="form-group">
            <label for="sur_name">Nachname</label>
            <input type="text" class="form-control" id="sur_name" name="sur_name"
                   value="<?= htmlspecialchars($user['SurName']) ?>" required>
        </div>

        <div class="form-group">
            <label for="email">E-Mail</label>
            <input type="email" class="form-control" id="email" name="email"
                   value="<?= htmlspecialchars($user['Email']) ?>" required>
        </div>

        <div class="form-group">
            <label for="password">Passwort</label>
            <input type="password" class="form-control" id="password" name="password">
        </div>

        <div class="button-group">
            <button type="submit" class="btn btn-primary" name="submit">Speichern</button>
            <a href="profile.php?id=<?= $user_id ?>" class="btn btn-primary">Abbrechen</a>
        </div>
    </form>
</div>

<!-- JavaScript für Bildvorschau -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const fileInput = document.getElementById('profile_image');
        if (fileInput) {
            fileInput.addEventListener('change', function () {
                if (this.files && this.files[0]) {
                    const reader = new FileReader();
                    reader.onload = function (e) {
                        let currentImage = document.querySelector('.current-image img');
                        if (currentImage) {
                            currentImage.src = e.target.result;
                        } else {
                            const placeholder = document.querySelector('.profile-image-placeholder');
                            if (placeholder) {
                                placeholder.remove();
                            }
                            const img = document.createElement('img');
                            img.src = e.target.result;
                            img.alt = 'Profilbild';
                            document.querySelector('.current-image').appendChild(img);
                        }
                    };
                    reader.readAsDataURL(this.files[0]);
                }
            });
        }
    });
    }
    })
    ;
</script>
</body>

</html>