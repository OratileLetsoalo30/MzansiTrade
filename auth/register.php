<?php
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/config/db.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = filter_var($_POST['email'] ?? '', FILTER_VALIDATE_EMAIL);
    $password = $_POST['password'] ?? '';
    if ($name && $email && $password) {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare('INSERT INTO users (name,email,password) VALUES (?, ?, ?)');
        try {
            $stmt->execute([$name, $email, $hash]);
            header('Location: /mzansitrade/login.php');
            exit;
        } catch (PDOException $e) {
            $error = 'Registration failed; email may already be used.';
        }
    } else {
        $error = 'Please fill all fields correctly.';
    }
}
?>
<h1>Register</h1>
<?php if (!empty($error)): ?><p class="error"><?= esc($error) ?></p><?php endif; ?>
<form method="post">
  <label>Name<br><input type="text" name="name" required></label><br>
  <label>Email<br><input type="email" name="email" required></label><br>
  <label>Password<br><input type="password" name="password" required></label><br>
  <button type="submit">Register</button>
</form>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
