<?php
// setari.php
include 'db_connect.php';
session_start();

// Redirecționare dacă nu e logat
if (!isset($_SESSION['is_logged_in']) || $_SESSION['is_logged_in'] !== true) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$message = '';
$error = false;

// Preluarea datelor curente
$sql_select = "SELECT nume_complet, daw_preferat, folder_facturi FROM utilizatori WHERE id = ?";
$stmt_select = $pdo->prepare($sql_select);
$stmt_select->execute([$user_id]);
$user = $stmt_select->fetch();

// --- Logica de Salvare a Setărilor ---
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $newName = trim($_POST['full_name']);
    $newDAW = $_POST['pref_daw'];
    $newFolder = trim($_POST['folder_facturi']);
    
    // Validare
    if (empty($newName)) {
        $error = true;
        $message = "Numele complet nu poate fi gol.";
    } else {
        $sql_update = "UPDATE utilizatori SET nume_complet = ?, daw_preferat = ?, folder_facturi = ? WHERE id = ?";
        $stmt_update = $pdo->prepare($sql_update);
        $stmt_update->execute([$newName, $newDAW, $newFolder, $user_id]);
        
        // Actualizăm sesiunea cu noul nume
        $_SESSION['user_name'] = $newName; 
        
        $message = "Setările au fost salvate cu succes!";
        
        // Re-încărcăm datele pentru a le afișa în formular (dacă nu facem redirect)
        $user['nume_complet'] = $newName;
        $user['daw_preferat'] = $newDAW;
        $user['folder_facturi'] = $newFolder;

        // Opțional: Redirecționează la profil după salvare
        // header("Location: profile.php?saved=1");
        // exit();
    }
}
?>
<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Neon Studio - Setări Cont</title>
    <link rel="stylesheet" href="style.css">
</head>
<body id="top">

<?php include 'header.php'; ?>

<main>
    <section class="section">
        <div class="container" style="max-width: 700px;">
            <h2 class="section-title">Setări Cont</h2>
            <p class="section-subtitle">
                Actualizează-ți datele personale și preferințele.
            </p>
            
            <?php if ($message): ?>
                <p style="color: <?php echo $error ? 'var(--neon-pink)' : 'var(--neon-cyan)'; ?>; text-align: center; font-weight: bold; margin-bottom: 20px;"><?php echo $message; ?></p>
            <?php endif; ?>

            <form id="settingsForm" method="POST">
                <div class="card" style="padding: 30px;">
                    <h3 style="color: var(--neon-purple); margin-top: 0;">Date Personale</h3>
                    <div class="form-group">
                        <label for="full-name">Nume Complet</label>
                        <input type="text" id="full-name" name="full_name" value="<?php echo htmlspecialchars($user['nume_complet']); ?>" required> 
                    </div>
                    <div class="form-group">
                        <label for="pref-daw">DAW Preferat (Pentru consultanță)</label>
                        <select id="pref-daw" name="pref_daw">
                            <option value="FL Studio" <?php echo ($user['daw_preferat'] == 'FL Studio') ? 'selected' : ''; ?>>FL Studio</option>
                            <option value="Ableton Live" <?php echo ($user['daw_preferat'] == 'Ableton Live') ? 'selected' : ''; ?>>Ableton Live</option>
                            <option value="Logic Pro X" <?php echo ($user['daw_preferat'] == 'Logic Pro X') ? 'selected' : ''; ?>>Logic Pro X</option>
                            <option value="Pro Tools" <?php echo ($user['daw_preferat'] == 'Pro Tools') ? 'selected' : ''; ?>>Pro Tools</option>
                            <option value="Necunoscut" <?php echo ($user['daw_preferat'] == 'Necunoscut') ? 'selected' : ''; ?>>Altul / Necunoscut</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="folder_facturi">Calea Către Folderul de Facturi (Memorată)</label>
                        <input type="text" id="folder_facturi" name="folder_facturi" value="<?php echo htmlspecialchars($user['folder_facturi']); ?>"> 
                    </div>
                </div>

                <div class="card" style="padding: 30px; margin-top: 30px;">
                    <h3 style="color: var(--neon-purple); margin-top: 0;">Securitate & Parolă</h3>
                    <p style="color: var(--text-gray);">Funcționalitatea de schimbare a parolei nu este implementată în acest demo.</p>
                </div>
                
                <div style="text-align: center; margin-top: 40px;">
                    <button type="submit" class="btn btn-purple">Salvează Modificările</button>
                </div>
            </form>
        </div>
    </section>
</main>

<?php include 'footer.php'; ?>

</body>
</html>