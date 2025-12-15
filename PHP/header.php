<?php
// header.php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
// Verifică starea de logare
$is_logged_in = isset($_SESSION['is_logged_in']) && $_SESSION['is_logged_in'] === true;

// Numele paginii curente pentru a seta clasa 'active'
$current_page = basename($_SERVER['PHP_SELF']); 
?>
<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Neon Studio - <?php echo (isset($page_title) ? htmlspecialchars($page_title) : 'Pagina Mea'); ?></title>
    <link rel="stylesheet" href="style.css">
</head>
<body id="top">

<header>
    <div class="container header-content">
        <a href="index.html" class="logo">NEON STUDIO</a>
        <nav id="mainNav">
            <ul>
                <li><a href="index.html" class="<?php echo ($current_page == 'index.html') ? 'active' : ''; ?>">Acasă</a></li>
                <li><a href="portofoliu.html" class="<?php echo ($current_page == 'portofoliu.html') ? 'active' : ''; ?>">Portofoliu</a></li>
                <li><a href="plugins.html" class="<?php echo ($current_page == 'plugins.html') ? 'active' : ''; ?>">Plugins</a></li>
                <li><a href="contact.html" class="<?php echo ($current_page == 'contact.html') ? 'active' : ''; ?>">Contact</a></li>
                <?php if ($is_logged_in): ?>
                    <li><a href="profile.php" class="<?php echo ($current_page == 'profile.php') ? 'active' : ''; ?>">Profil</a></li>
                <?php endif; ?>
            </ul>
        </nav>
        <button class="hamburger" id="hamburger" aria-label="Meniu">
            <span></span><span></span><span></span>
        </button>
        <div class="user-icon-container">
            <div class="user-icon">👤</div>
            <div class="user-dropdown" id="userDropdown">
                <?php if ($is_logged_in): ?>
                    <a href="profile.php">Profil</a>
                    <a href="setari.php">Setări</a>
                    <a href="logout.php">Logout</a>
                <?php else: ?>
                    <a href="login.php">Login / Creează Cont</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</header>