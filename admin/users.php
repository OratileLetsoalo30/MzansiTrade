<?php
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../config/db.php';
if (empty($_SESSION['user_id']) || empty($_SESSION['is_admin'])) { header('Location: /mzansitrade/login.php'); exit; }
$stmt = $pdo->query('SELECT id, name, email, is_admin, created_at FROM users ORDER BY id DESC');
$users = $stmt->fetchAll();
?>
<h1>Users</h1>
<table>
  <thead><tr><th>ID</th><th>Name</th><th>Email</th><th>Admin</th><th>Joined</th></tr></thead>
  <tbody>
  <?php foreach ($users as $u): ?>
    <tr>
      <td><?= esc($u['id']) ?></td>
      <td><?= esc($u['name']) ?></td>
      <td><?= esc($u['email']) ?></td>
      <td><?= $u['is_admin'] ? 'Yes' : 'No' ?></td>
      <td><?= esc($u['created_at']) ?></td>
    </tr>
  <?php endforeach; ?>
  </tbody>
</table>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
