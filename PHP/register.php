<?php
// register.php
include 'db_connect.php';

$message = '';
$error = false;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nume = trim($_POST['nume']);
    $email = trim($_POST['email']);
    $parola = $_POST['parola'];
    
    if (empty($nume) || empty($email) || empty($parola)) {
        $error = true;
        $message = "Toate câmpurile sunt obligatorii.";
    } else {
        $parola_hash = password_hash($parola, PASSWORD_DEFAULT);
        
        $sql = "INSERT INTO utilizatori (nume_complet, email, parola_hash) VALUES (?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        
        try {
            $stmt->execute([$nume, $email, $parola_hash]);
            header("Location: login.php?success=1"); // Redirecționare la login
            exit();
        } catch (\PDOException $e) {
            $error = true;
            if ($e->getCode() == '23000') {
                $message = "Acest email este deja înregistrat.";
            } else {
                $message = "A apărut o eroare la înregistrare. Vă rugăm reîncercați.";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Înregistrare - Neon Studio</title>
    <link rel="stylesheet" href="style.css">
</head>
<body id="top">

<?php include 'header.php'; // Vom folosi un header modular ?> 

<main>
    <section class="form-container">
        <div class="form-box" id="register">
            <h2>Creare Cont</h2>
            
            <?php if ($message): ?>
                <p style="color: <?php echo $error ? 'var(--neon-pink)' : 'var(--neon-cyan)'; ?>; font-weight: bold;"><?php echo $message; ?></p>
            <?php endif; ?>
            
            <form method="POST">
                <label for="register-name" style="display:none;">Nume complet</label>
                <input type="text" id="register-name" name="nume" placeholder="Nume complet" required value="<?php echo isset($_POST['nume']) ? htmlspecialchars($_POST['nume']) : ''; ?>">

                <label for="register-email" style="display:none;">Email</label>
                <input type="email" id="register-email" name="email" placeholder="Email" required value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">

                <label for="register-password" style="display:none;">Parolă</label>
                <input type="password" id="register-password" name="parola" placeholder="Parolă" required>

                <button type="submit" class="btn btn-purple">Înregistrează-te</button>
            </form>
            <p>Ai deja cont? <a href="login.php">Autentificare</a></p>
        </div>
    </section>
</main>

<?php include 'footer.php'; ?>

</body>
</html>