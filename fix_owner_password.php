<?php
/**
 * SCRIPT RESET PASSWORD OWNER
 * 
 * Cara pakai:
 *   1. Taruh file ini di folder root project (sejajar index.php)
 *   2. Buka browser → http://localhost:8080/fix_owner_password.php
 *   3. Setelah berhasil, HAPUS file ini dari server!
 * 
 * PERINGATAN: Hapus file ini setelah digunakan!
 */

// Koneksi database
$host   = 'localhost';
$user   = 'root';
$pass   = '';
$dbname = 'pos_showroom';

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    die('<h2 style="color:red">Gagal konek database: ' . $conn->connect_error . '</h2>');
}

$new_password_plain = 'owner123';
$new_md5 = md5($new_password_plain);

// Update password owner
$stmt = $conn->prepare("UPDATE user SET password = ? WHERE username = 'owner'");
$stmt->bind_param("s", $new_md5);
$stmt->execute();

$affected = $stmt->affected_rows;
$stmt->close();
$conn->close();
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Reset Password Owner</title>
<style>
  body { font-family: Arial, sans-serif; max-width: 500px; margin: 80px auto; padding: 20px; }
  .card { border: 1px solid #ddd; border-radius: 8px; padding: 24px; }
  .success { background: #f0fdf4; border-color: #86efac; }
  .info    { background: #eff6ff; border-color: #93c5fd; margin-top: 16px; border-radius: 6px; padding: 12px; }
  h2 { margin-top: 0; }
  code { background: #f3f4f6; padding: 2px 6px; border-radius: 4px; font-size: 14px; }
  .warning { background: #fef2f2; border: 1px solid #fca5a5; border-radius: 6px; padding: 12px; margin-top: 16px; color: #991b1b; }
</style>
</head>
<body>
<div class="card success">
  <h2> Password Owner Berhasil Direset</h2>
  <?php if ($affected > 0): ?>
    <p>Password akun <code>owner</code> telah diubah.</p>
  <?php else: ?>
    <p>Password owner sudah benar atau akun tidak ditemukan.</p>
  <?php endif; ?>

  <div class="info">
    <strong> Akun Login Sistem:</strong><br><br>
    <b>Admin:</b><br>
    &nbsp;&nbsp;Username: <code>admin</code><br>
    &nbsp;&nbsp;Password: <code>admin123</code><br><br>
    <b>Owner:</b><br>
    &nbsp;&nbsp;Username: <code>owner</code><br>
    &nbsp;&nbsp;Password: <code>owner123</code>
  </div>

  <div class="warning">
     <strong>PENTING:</strong> Segera hapus file <code>fix_owner_password.php</code> 
    ini dari server setelah berhasil login!
  </div>
</div>
</body>
</html>
