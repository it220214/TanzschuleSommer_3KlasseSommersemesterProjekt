<?php
session_start();
require "mysql.php";

if (!isset($_SESSION['userId'])) {
    header("Location: registerOrLogIn.php");
    exit();
}

$userId = $_SESSION['userId'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['course_id'])) {
    $courseId = $_POST['course_id'];
    $stmt = $conn->prepare("DELETE FROM UserCourse WHERE UserId = ? AND CourseId = ?");
    $stmt->bind_param("ss", $userId, $courseId);
    $stmt->execute();
    header("Location: profile.php");
    exit();
}

$stmt = $conn->prepare("SELECT * FROM User WHERE UserId = ?");
$stmt->bind_param("s", $userId);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();

$courseQuery = "
                        SELECT DISTINCT 
                            c.CourseId,
                            c.Name,
                            c.Day,
                            c.StartTime,
                            c.EndTime,
                            c.Categorie,
                            COALESCE(t.FirstName, '') as TeacherFirstName,
                            COALESCE(t.SurName, '') as TeacherSurName
                        FROM Course c 
                        LEFT JOIN UserCourse uc ON c.CourseId = uc.CourseId 
                        LEFT JOIN Teacher t ON c.TeacherId = t.TeacherId
                        WHERE uc.UserId = ?";

$stmtCourses = $conn->prepare($courseQuery);
$stmtCourses->bind_param("s", $userId);
$stmtCourses->execute();
$userCourses = $stmtCourses->get_result();
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil - Tanzschule Sommer</title>
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
<nav>
    <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
        <li class="nav-item">
            <a class="nav-link active" id="pills-home-tab" data-toggle="pill" href="./home.html" role="tab"
               aria-controls="pills-home" aria-selected="true"><img src="./img/Logos/white/homeWhite.png"></a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="pills-course-tab" data-toggle="pill" href="./course.php" role="tab"
               aria-controls="pills-course" aria-selected="false"><img src="./img/Logos/white/courseWhite.png"></a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="pills-profile-tab" data-toggle="pill" href="./profile.php" role="tab"
               aria-controls="pills-profile" aria-selected="false"><img src="./img/Logos/white/profileWhite.png"></a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="pills-timetable-tab" data-toggle="pill" href="./timetable.php" role="tab"
               aria-controls="pills-profile" aria-selected="false"><img src="./img/Logos/white/timetableWhite.png"></a>
        </li>
        <li class="nav-item" id="logo-nav" float-start>
            <a class="nav-link" id="pills-logo-tab"><img src="./img/Logos/white/tsLogoWhite.png" alt=""></a>
        </li>
    </ul>
</nav>
<div class="container">
    <div class="profile-container">
        <div class="profile-header">
            <h2>Mein Profil <span id="profil-span-userId"><?= htmlspecialchars($user['UserId']) ?></span></h2>
            <div class="profile-actions">
                <button onclick="location.href='editProfile.php?UserId=<?= $userId ?>'" class="btn btn-primary">Profil
                    bearbeiten
                </button>
                <form action="logout.php" method="POST" style="display: inline">
                    <button type="submit" class="btn btn-danger">Abmelden</button>
                </form>
            </div>
        </div>
        <div class="profile-grid">
            <div class="image-area">
                <div class="profile-image" style="background-color: var(--background)">
                    <?php if (!empty($user['ProfileImage'])): ?>
                        <img src="<?= htmlspecialchars($user['ProfileImage']) ?>" alt="Profilbild" width="80%">
                    <?php else: ?>
                        <div class="placeholder-image">Profilbild</div>
                    <?php endif; ?>
                </div>
            </div>
            <div class="name-area">
                <div class="profile-item">
                    <div class="info-label">Vorname:</div>
                    <div class="info-value"><?= htmlspecialchars($user['FirstName']) ?></div>
                </div>
            </div>
            <div class="surname-area">
                <div class="profile-item">
                    <div class="info-label">Nachname:</div>
                    <div class="info-value"><?= htmlspecialchars($user['SurName']) ?></div>
                </div>
            </div>
            <div class="email-area">
                <div class="profile-item">
                    <div class="info-label">Email:</div>
                    <div class="info-value"><?= htmlspecialchars($user['Email']) ?></div>
                </div>
            </div>
        </div>
    </div>
    <div id="course-container-grid">
        <div class="courses-header">
            <h2>Meine Kurse</h2>
        </div>
        <?php if ($userCourses && $userCourses->num_rows > 0): ?>
            <div class="courses-grid">
                <?php while ($course = $userCourses->fetch_assoc()): ?>
                    <div class="course-card">
                        <div class="course-image">
                            <?php if (!empty($course['Categorie'])): ?>
                                <img src="img/Tanzstile/Tanzstil<?= htmlspecialchars($course['Categorie']) ?>.png"
                                     alt="<?= htmlspecialchars($course['Categorie']) ?>">
                            <?php else: ?>
                                <div class="placeholder-image">Kein Bild verfügbar</div>
                            <?php endif; ?>
                        </div>
                        <div class="course-content">
                            <div class="course-category">
                                <?= !empty($course['Categorie']) ? htmlspecialchars($course['Categorie']) : 'Keine Kategorie' ?>
                            </div>
                            <div class="course-title">
                                <?= !empty($course['Name']) ? htmlspecialchars($course['Name']) : 'Kein Name' ?>
                            </div>
                            <div class="course-info">
                                <div class="info-label">Tag:</div>
                                <div><?= !empty($course['Day']) ? htmlspecialchars($course['Day']) : '-' ?></div>
                            </div>
                            <div class="course-info">
                                <div class="info-label">Zeit:</div>
                                <div>
                                    <?php
                                    $startTime = !empty($course['StartTime']) ? htmlspecialchars($course['StartTime']) : '-';
                                    $endTime = !empty($course['EndTime']) ? htmlspecialchars($course['EndTime']) : '-';
                                    echo $startTime . ($startTime !== '-' && $endTime !== '-' ? ' - ' : '') . $endTime;
                                    ?>
                                </div>
                            </div>
                            <div class="course-info">
                                <div class="info-label">Lehrer:</div>
                                <div>
                                    <?php
                                    $teacherName = trim($course['TeacherFirstName'] . ' ' . $course['TeacherSurName']);
                                    echo !empty($teacherName) ? htmlspecialchars($teacherName) : '-';
                                    ?>
                                </div>
                            </div>
                            <form method="POST" action="profile.php">
                                <input type="hidden" name="course_id"
                                       value="<?= htmlspecialchars($course['CourseId']) ?>">
                                <button type="submit" class="btn btn-primary w-100">Vom Kurs abmelden</button>
                            </form>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        <?php else: ?>
            <div class="no-courses">
                Du bist noch für keine Kurse angemeldet.
                <br><br>
                <a href="course.php" class="btn btn-primary">Zu den Kursen</a>
            </div>
        <?php endif; ?>
    </div>
</div>
<footer>
    <!--<div id="footerImg"><img src="./img/footer.png"></div>-->
    <div id="footerTxT">
        <p id="oefnungP" class="h4">Öffnungszeiten</p>
        <p id="standortP" class="h4">Standort</p>
        <div id="fTXTTime">
            <div id="days">
                <div><p>Mo. - Do.</p></div>
                <br>
                <div><p>Fr. </p></div>
                <br>
                <div><p>Sa.</p></div>
            </div>
            <div id="time">
                <div><p>09.00 - 12.00</p>
                    <p>13:00 - 22.00</p></div>
                <div><p>13:00 - 23.00</p></div>
                <br>
                <div><p>09:00 - 12.00</p>
                    <p>17:00 - 22.00</p></div>
            </div>
        </div>
        <div id="fTXTOther">
            <div id="stand">
                <div>
                    <p>Bergstraße 12</p>
                    <p>4073 Unterfeld</p>
                </div>
            </div>
            <div id="contact">
                <div>
                    <p id="contactP" class="h4">Kontakt</p>
                    <p>+43 999 123456</p>
                    <p>ts.sommer@email.com</p>
                </div>
            </div>
        </div>
    </div>
</footer>
</body>
</html>