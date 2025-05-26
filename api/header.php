<<<<<<< HEAD
<?php session_start(); ?>
=======
>>>>>>> 42b84d3269b121dcbccbbe9dbe47458d3b86c3e6
<nav>
    <a href="index.php">Главная</a>
    <a href="about.php">О районе</a>
    <a href="contacts.php">Контакты</a>
<<<<<<< HEAD
    
    <?php if (isset($_SESSION['user'])): ?>
        <?php if ($_SESSION['user']['is_admin']): ?>
            <a href="admin-panel.html" class="auth-btn admin-btn">
                <i class="fas fa-cog"></i> Админка
            </a>
        <?php else: ?>
            <a href="profile.html" class="auth-btn profile-btn">
                <i class="fas fa-user"></i> Профиль
            </a>
        <?php endif; ?>
        <a href="logout.php" class="auth-btn logout-btn">
            <i class="fas fa-sign-out-alt"></i> Выйти
        </a>
    <?php else: ?>
        <a href="login.html" class="auth-btn login-btn">
            <i class="fas fa-sign-in-alt"></i> Вход
        </a>
=======
    <?php if (isset($_SESSION['user'])): ?>
        <a href="profile.php">Профиль</a>
    <?php else: ?>
        <a href="login.php">Вход</a>
>>>>>>> 42b84d3269b121dcbccbbe9dbe47458d3b86c3e6
    <?php endif; ?>
</nav>