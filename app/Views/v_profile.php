
<?= $this->extend('layout') ?>
<?= $this->section('content') ?>
<h4><strong>Profil Pengguna</strong></h4>
<ul>
    <li><strong>Username:</strong> <?= esc($username) ?></li>
    <li><strong>Role:</strong> <?= esc($role) ?></li>
    <li><strong>Email:</strong> <?= esc($email) ?></li>
    <li><strong>Waktu Login:</strong> <?= esc($waktu_login) ?></li>
    <li><strong>Status Login:</strong> <?= $isLoggedIn ? 'Sudah Login' : 'Belum Login' ?></li>
</ul>
<?= $this->endSection() ?>