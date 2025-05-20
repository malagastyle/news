<nav>
    <a href="index.php">Главная</a>
    <a href="about.php">О районе</a>
    <a href="contacts.php">Контакты</a>
    <?php if (isset($_SESSION['user'])): ?>
        <a href="profile.php">Профиль</a>
    <?php else: ?>
        <a href="login.php">Вход</a>
    <?php endif; ?>
</nav>