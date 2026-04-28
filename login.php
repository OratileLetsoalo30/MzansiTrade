<?php
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/config/db.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = filter_var($_POST['email'] ?? '', FILTER_VALIDATE_EMAIL);
    $password = $_POST['password'] ?? '';
    if ($email && $password) {
        $stmt = $pdo->prepare('SELECT id, password, is_admin, name FROM users WHERE email = ? LIMIT 1');
        $stmt->execute([$email]);
        $user = $stmt->fetch();
        if ($user && password_verify($password, $user['password'])) {
            session_regenerate_id(true);
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['is_admin'] = !empty($user['is_admin']);
            header('Location: /mzansitrade/dashboard.php');
            exit;
        }
        $error = 'Invalid credentials.';
    } else {
        $error = 'Provide email and password.';
    }
}
?>
<h1>Login</h1>
<?php if (!empty($error)): ?><p class="error"><?= esc($error) ?></p><?php endif; ?>
<form method="post">
  <label>Email<br><input type="email" name="email" required></label><br>
  <label>Password<br><input type="password" name="password" required></label><br>
  <button type="submit">Login</button>
</form>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
