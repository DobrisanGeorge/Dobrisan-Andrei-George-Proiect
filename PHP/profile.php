<?php
// profile.php
include 'db_connect.php';
session_start();

// Redirecționare dacă nu e logat
if (!isset($_SESSION['is_logged_in']) || $_SESSION['is_logged_in'] !== true) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Preluarea tuturor datelor utilizatorului din baza de date
$sql = "SELECT nume_complet, email, daw_preferat, folder_facturi, data_inregistrare FROM utilizatori WHERE id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$user_id]);
$user = $stmt->fetch();

// Formatare dată
$joinDate = new DateTime($user['data_inregistrare']);
$formattedJoinDate = $joinDate->format('d F Y'); // Exemplu: 15 Decembrie 2025
?>
<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Neon Studio - Profilul Meu</title>
    <link rel="stylesheet" href="style.css">
</head>
<body id="top">

<?php include 'header.php'; ?>

<main>
    <section class="section">
        <div class="container">
            <h2 class="section-title">Salut, <span id="profile-title-name"><?php echo htmlspecialchars($user['nume_complet']); ?></span>! 👋</h2>
            <p class="section-subtitle">
                Aici poți gestiona contul, programările și istoricul tău.
            </p>

            <div class="contact-grid">
                <div class="card contact-info">
                    <h3>Detalii Cont</h3>
                    <p>Nume: <span id="profile-name-display"><?php echo htmlspecialchars($user['nume_complet']); ?></span></p>
                    <p>Email: <span id="profile-email-display"><?php echo htmlspecialchars($user['email']); ?></span></p>
                    <p>DAW Preferat: <span id="profile-daw-display"><?php echo htmlspecialchars($user['daw_preferat']); ?></span></p>
                    <p>Folder Facturi: <span id="profile-folder-facturi"><?php echo htmlspecialchars($user['folder_facturi']); ?></span></p>
                    <p>Membru din: <span id="profile-join-date"><?php echo htmlspecialchars($formattedJoinDate); ?></span></p>
                    <p>Nivel Acces: **Avansat**</p>
                    <div style="margin-top: 25px;">
                        <a href="setari.php" class="btn btn-purple" style="width: auto;">Editează Profilul</a>
                    </div>
                </div>

                <div class="card">
                    <h3>Programările Mele</h3>
                    <div style="margin-top: 20px;">
                        <h4>📅 Următoarea Sesiune (Consultanță Mixaj)</h4>
                        <p style="color: var(--neon-cyan); font-weight: 600;">20 Noiembrie 2025, Ora 18:00 (Zoom)</p>
                        <p>Durată: 90 minute</p>
                    </div>
                    <div style="margin-top: 25px; border-top: 1px dashed var(--border-light); padding-top: 15px;">
                        <h4>🗓️ Istoric (Ultimele 2)</h4>
                        <p>• 10 Octombrie: Mastering (Urban Echoes) [Finalizat]</p>
                        <p>• 1 Septembrie: Sound Design (Custom Preset) [Finalizat]</p>
                    </div>
                    <div style="margin-top: 25px;">
                        <a href="contact.html" class="btn" style="width: auto;">Programează o Sesiune Nouă</a>
                    </div>
                </div>
                
            </div>
            
            <h2 class="section-title" style="margin-top: 70px;">Resurse Personalizate</h2>
            <div class="card-grid">
                <div class="card">
                    <h3>Tutoriale Salvate</h3>
                    <p>Accesează tutorialele pe care le-ai marcat ca favorite.</p>
                </div>
                <div class="card">
                    <h3>Reduceri Exclusive</h3>
                    <p>Vouchere și oferte la pluginuri partenere (Waves, iZotope).</p>
                </div>
            </div>

        </div>
    </section>
</main>

<?php include 'footer.php'; ?>

</body>
</html>