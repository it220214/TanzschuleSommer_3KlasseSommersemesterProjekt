<?php
session_start();
require 'mysql.php';

function renderCourses($conn, $categorie = 'all')
{
    $sql = $categorie === 'all'
        ? "SELECT CourseId, Name, Level, TeacherId, Day, StartTime, EndTime, Categorie FROM Course"
        : "SELECT CourseId, Name, Level, TeacherId, Day, StartTime, EndTime, Categorie FROM Course WHERE Categorie=?";
    $stmt = $conn->prepare($sql);
    if ($categorie !== 'all') $stmt->bind_param("s", $categorie);
    $stmt->execute();
    $result = $stmt->get_result();
    $output = '';
    while ($r = $result->fetch_assoc()) {
        $output .= "
<div class='card'>
    <img src='img/Tanzstile/Tanzstil" . htmlspecialchars($r['Categorie'] ?? '') . ".png' alt='CourseImg' class='cardImg'>
    <div class='cardBody'>
        <h3>" . htmlspecialchars($r['Name'] ?? '') . " (" . htmlspecialchars($r['Level'] ?? '') . ")</h3>
        <p>Teacher: " . htmlspecialchars($r['TeacherId'] ?? '') . "<br>
            Day: " . htmlspecialchars($r['Day'] ?? '') . " <br>
            Time: <br>" . htmlspecialchars($r['StartTime'] ?? '') . " - " . htmlspecialchars($r['EndTime'] ?? '') . "</p>
        <form method='POST' action='registerOrLogIn.php'>
            <input type='hidden' name='course_name' value='" . htmlspecialchars($r['Name']) . "'>
            <input type='hidden' name='course_id' value='" . htmlspecialchars($r['CourseId']) . "'>
            <button type='submit' class='btn btn-primary'>Anmelden</button>
        </form>
    </div>
</div>";
    }
    $stmt->close();
    return $output;
}

// AJAX-Handler für Filter
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['categorie'])) {
    echo renderCourses($conn, $_POST['categorie']);
    exit;
}

// Kategorien aus der Datenbank laden
$categories = [];
$stmt = $conn->prepare("SELECT DISTINCT Categorie FROM Course");
$stmt->execute();
$stmt->bind_result($categorie);
while ($stmt->fetch()) $categories[] = $categorie;
$stmt->close();
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kurse - Tanzschule Sommer</title>
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

<?php if (isset($_GET['error'])): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <?php
        $error_message = "";
        switch ($_GET['error']) {
            case 'already_registered':
                $error_message = "Sie sind bereits für diesen Kurs angemeldet.";
                break;
            case 'course_not_found':
                $error_message = "Der ausgewählte Kurs wurde nicht gefunden.";
                break;
            case 'registration_failed':
                $error_message = "Bei der Kursanmeldung ist ein Fehler aufgetreten. Bitte versuchen Sie es später erneut.";
                break;
            default:
                $error_message = "Ein unerwarteter Fehler ist aufgetreten.";
        }
        echo htmlspecialchars($error_message);
        ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<?php if (isset($_GET['success'])): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        Kursanmeldung erfolgreich!
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<div id="course">
    <div class="dropdown">
        <button id="dropdownBtn" class="dropdownBtn">Kategorien ▼</button>
        <ul id="dropdownMenu" class="dropdownMenu">
            <li><a href="#" dataCategorie="all">Alle Kurse</a></li>
            <?php foreach ($categories as $category): ?>
                <li><a href="#"
                       dataCategorie="<?= htmlspecialchars($category) ?>"><?= htmlspecialchars($category) ?></a>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>

    <div id="courseContainer">
        <?= renderCourses($conn, 'all') ?>
    </div>

    <script>
        let dropdownBtn = document.getElementById('dropdownBtn');
        let dropdownMenu = document.getElementById('dropdownMenu');

        dropdownBtn.addEventListener('click', () => {
            dropdownMenu.style.display = dropdownMenu.style.display === 'block' ? 'none' : 'block';
        });

        dropdownMenu.querySelectorAll('a').forEach(item => {
            item.addEventListener('click', e => {
                e.preventDefault();
                let categorie = item.getAttribute('dataCategorie');
                dropdownBtn.textContent = item.textContent;
                dropdownMenu.style.display = 'none';

                fetch(window.location.pathname, {
                    method: 'POST',
                    body: new URLSearchParams({categorie: categorie})
                })
                    .then(res => res.text())
                    .then(html => {
                        document.getElementById('courseContainer').innerHTML = html;
                    })
                    .catch(console.error);
            });
        });

        document.addEventListener('click', e => {
            if (!dropdownBtn.contains(e.target) && !dropdownMenu.contains(e.target)) {
                dropdownMenu.style.display = 'none';
            }
        });
    </script>
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