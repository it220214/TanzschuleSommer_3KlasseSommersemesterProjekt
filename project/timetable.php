<?php
session_start();
require "mysql.php";
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Filterwerte holen
$filterTeacher = $_GET['teacher'] ?? '';
$filterCategory = $_GET['category'] ?? '';
$filterLevel = $_GET['level'] ?? '';

// Kategorien und Level laden
$categories = [];
$levels = [];
$res = $conn->query("SELECT DISTINCT Categorie, Level FROM Course");
while ($row = $res->fetch_assoc()) {
    if ($row['Categorie']) $categories[] = $row['Categorie'];
    if ($row['Level']) $levels[] = $row['Level'];
}
$categories = array_unique($categories);
$levels = array_unique($levels);

// SQL für Kurse mit Filtern
$sql = "
    SELECT cs.*, c.Name, c.Level, c.Categorie
    FROM CourseSchedule cs
    JOIN Course c ON cs.CourseId = c.CourseId
    WHERE 1
";
if ($filterTeacher) $sql .= " AND cs.TeacherId = '" . $conn->real_escape_string($filterTeacher) . "'";
if ($filterCategory) $sql .= " AND c.Categorie = '" . $conn->real_escape_string($filterCategory) . "'";
if ($filterLevel) $sql .= " AND c.Level = '" . $conn->real_escape_string($filterLevel) . "'";

$scheduleId = isset($_SESSION['scheduleId']) ? $_SESSION['scheduleId'] : '';

// Kurse holen
$courses = [];
try {
    $stmt = $conn->query($sql);
    if ($stmt) {
        while ($row = $stmt->fetch_assoc()) {
            $courses[] = $row;
        }
    }
} catch (Exception $e) {
    echo "<!-- ERROR beim Laden der Kurse: " . $e->getMessage() . " -->\n";
}

// Lehrer holen
$teachers = [];
try {
    $result = $conn->query("SELECT * FROM Teacher");
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $teachers[$row['TeacherId']] = $row['FirstName'] . ' ' . $row['SurName'];
        }
    }
} catch (Exception $e) {
    echo "<!-- ERROR beim Laden der Lehrer: " . $e->getMessage() . " -->\n";
}

// Räume, Tage, Zeiten wie gehabt
$rooms = [1, 2, 3, 4];
$days = ['Montag', 'Dienstag', 'Mittwoch', 'Donnerstag', 'Freitag', 'Samstag'];
$times = ['09:00 - 10:30', '10:45 - 12:15', '12:30-14:00', '14:15-15:15', '15:30-17:00', '17:15-18:45', '19:00-21:00'];
?>
<!doctype html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Stundenplan - Tanzschule Sommer</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-SgOJa3DmI69IUzQ2PVdRZhwQ+dy64/BUtbMJw1MZ8t5HZApcHrRKUc4W0kG879m7" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-k6d4wzSIapyDyv1kpU366/PK5hCdSbCRGRCMv+eplOQJWyd1fbcAu9OCUj5zNLiq"
            crossorigin="anonymous"></script>
    <link rel="website icon" type="png" href="./img/tsLogoWhite.png">

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
    <style>
        .empty-cell {
            color: #bbb;
            text-align: center;
            font-style: italic;
        }

        h1 {
            font-size: 2rem;
            margin-top: 2rem;
        }

        .table-day {
            margin-bottom: 3rem;
        }

        .course-card {
            border-radius: 0.5rem;
            padding: 0.5rem;
            margin-bottom: 0.3rem;
        }
    </style>

</head>
<body>
<!-- Navigation -->
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

<!--formular-->
<form method="get" class="mb-4" id="filterForm">
    <div class="row g-2">
        <div class="col">
            <select name="teacher" id="teacherSelect" class="form-select"
                    onchange="resetOtherFilters('teacher')">
                <option value="">Alle Lehrer</option>
                <?php foreach ($teachers as $tid => $name): ?>
                    <option value="<?= htmlspecialchars($tid) ?>" <?= $filterTeacher == $tid ? 'selected' : '' ?>>
                        <?= htmlspecialchars($name) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col">
            <select name="category" id="categorySelect" class="form-select"
                    onchange="resetOtherFilters('category')">
                <option value="">Alle Kategorien</option>
                <?php foreach ($categories as $cat): ?>
                    <option value="<?= htmlspecialchars($cat) ?>" <?= $filterCategory == $cat ? 'selected' : '' ?>>
                        <?= htmlspecialchars($cat) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col">
            <select name="level" id="levelSelect" class="form-select"
                    onchange="resetOtherFilters('level')">
                <option value="">Alle Level</option>
                <?php foreach ($levels as $lvl): ?>
                    <option value="<?= htmlspecialchars($lvl) ?>" <?= $filterLevel == $lvl ? 'selected' : '' ?>>
                        <?= htmlspecialchars($lvl) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>
</form>
<script>
    function resetOtherFilters(changed) {
        if (changed !== 'teacher') document.getElementById('teacherSelect').selectedIndex = 0;
        if (changed !== 'category') document.getElementById('categorySelect').selectedIndex = 0;
        if (changed !== 'level') document.getElementById('levelSelect').selectedIndex = 0;
        document.getElementById('filterForm').submit();
    }
</script>
<div id="container" style="margin-top: 1vw">
    <div id="container-timetable" style="margin-top:1vw">
        <div id="body-table">
            <?php if (empty($courses)): ?>
                <div class="alert alert-warning">
                    <strong>Keine Kurse gefunden!</strong>
                    Überprüfen Sie die Datenbankverbindung und Tabellennamen.
                </div>
            <?php endif; ?>

            <?php foreach ($days as $day): ?>

                <h1><?= htmlspecialchars($day) ?></h1>
                <table class="table-day table table-striped table-bordered align-middle">
                    <thead>
                    <tr>
                        <th style="width: 12rem;">Zeit</th>
                        <?php foreach ($rooms as $room): ?>
                            <th>Saal <?= htmlspecialchars($room) ?></th>
                        <?php endforeach; ?>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($times as $time): ?>
                        <tr>
                            <th scope="row"><?= htmlspecialchars($time) ?></th>
                            <?php
                            // Zeit aufteilen - verschiedene Formate berücksichtigen
                            $timeParts = preg_split('/\s*-\s*/', $time, 2);
                            $startTime = trim($timeParts[0]);
                            $endTime = isset($timeParts[1]) ? trim($timeParts[1]) : '';

                            // Sekunden zu den Zeiten hinzufügen für DB-Vergleich
                            $startTimeWithSeconds = $startTime . ':00';
                            $endTimeWithSeconds = $endTime . ':00';

                            foreach ($rooms as $room):
                                $found = false;
                                $matchedCourses = [];

                                foreach ($courses as $course) {
                                    $dayMatch = strcasecmp($course['Day'], $day) === 0;
                                    $startMatch = strcasecmp($course['StartTime'], $startTimeWithSeconds) === 0;
                                    $endMatch = strcasecmp($course['EndTime'], $endTimeWithSeconds) === 0;
                                    $roomMatch = $course['RoomId'] == $room;

                                    echo "<!-- DEBUG Kurs: {$course['Name']}, Day: '{$course['Day']}' vs '{$day}' ({$dayMatch}), Start: '{$course['StartTime']}' vs '{$startTimeWithSeconds}' ({$startMatch}), End: '{$course['EndTime']}' vs '{$endTimeWithSeconds}' ({$endMatch}), Room: '{$course['RoomId']}' vs '{$room}' ({$roomMatch}) -->\n";

                                    if ($dayMatch && $startMatch && $endMatch && $roomMatch) {
                                        $matchedCourses[] = $course;
                                        $found = true;
                                    }
                                }
                                if ($found) {
                                    echo '<td>';
                                    foreach ($matchedCourses as $course) {
                                        $bgColor = ''; // Standardfarbe
                                        if (isset($course['Categorie'])) {
                                            switch ($course['Categorie']) {
                                                case 'Ballroom':
                                                    $bgColor = 'var(--ballroom)';
                                                    break;
                                                case 'Classic':
                                                    $bgColor = 'var(--classic)';
                                                    break;
                                                case 'Urban':
                                                    $bgColor = 'var(--urban)';
                                                    break;
                                                case 'Lablast':
                                                    $bgColor = 'var(--lablast)';
                                                    break;
                                                // Weitere Kategorien nach Bedarf
                                                default:
                                                    $bgColor = 'white';
                                            }
                                        }
                                        ?>
                                        <div class="course-card" style="background: <?= htmlspecialchars($bgColor) ?>">
                                            <span class="text-muted">ScheduleId: <?= htmlspecialchars($course['ScheduleId']) ?></span>
                                            <span class="fw-bold"><?= htmlspecialchars($course['Name']) ?></span>
                                            <span class='badge bg-secondary'><?= htmlspecialchars($course['Level']) ?></span><br>
                                            <span class='text-muted'><?= htmlspecialchars($startTime . ' - ' . $endTime) ?></span><br>
                                            <span class='text-muted'><?= isset($teachers[$course['TeacherId']]) ? htmlspecialchars($teachers[$course['TeacherId']]) : 'Kein Lehrer' ?></span>
                                        </div>
                                        <?php
                                    }
                                    echo '</td>';
                                } else {
                                    echo '<td><span class="empty-cell">–</span></td>';
                                }
                            endforeach;
                            ?>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endforeach; ?>
        </div>
    </div>
    <!-- Footer -->
    <footer>
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