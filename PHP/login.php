<?php
// login.php
include 'db_connect.php';
session_start();

$message = '';
$error = false;

// Dacă utilizatorul este deja logat, redirecționează la profil
if (isset($_SESSION['is_logged_in']) && $_SESSION['is_logged_in'] === true) {
    header("Location: profile.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $parola = $_POST['parola'];
    
    $sql = "SELECT id, nume_complet, parola_hash FROM utilizatori WHERE email = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$email]);
    $user = $stmt->fetch();
    
    if ($user && password_verify($parola, $user['parola_hash'])) {
        // Autentificare reușită
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['nume_complet'];
        $_SESSION['is_logged_in'] = true;
        
        header("Location: profile.php");
        exit();
    } else {
        $error = true;
        $message = "Eroare: Email sau parolă incorectă.";
    }
}
?>
<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Neon Studio</title>
    <link rel="stylesheet" href="style.css">
</head>
<body id="top">

<?php include 'header.php'; ?>

<main>
    <section class="form-container">
        <div class="form-box">
            <h2>Autentificare</h2>

            <?php if (isset($_GET['success'])): ?>
                <p style="color: var(--neon-cyan); font-weight: bold;">Înregistrare reușită! Autentifică-te acum.</p>
            <?php endif; ?>
            <?php if (isset($_GET['logged_out'])): ?>
                <p style="color: var(--neon-cyan); font-weight: bold;">Ai fost delogat cu succes.</p>
            <?php endif; ?>
            <?php if ($message): ?>
                <p style="color: <?php echo $error ? 'var(--neon-pink)' : 'var(--neon-cyan)'; ?>; font-weight: bold;"><?php echo $message; ?></p>
            <?php endif; ?>
            
            <form method="POST">
                <label for="login-email" style="display:none;">Email</label>
                <input type="email" id="login-email" name="email" placeholder="Email" required>

                <label for="login-password" style="display:none;">Parolă</label>
                <input type="password" id="login-password" name="parola" placeholder="Parolă" required>

                <button type="submit" class="btn btn-purple">Login</button>
            </form>
            <p>Nu ai cont? <a href="register.php">Creează cont</a></p>
        </div>
    </section>
</main>

<?php include 'footer.php'; ?>

</body>
</html>