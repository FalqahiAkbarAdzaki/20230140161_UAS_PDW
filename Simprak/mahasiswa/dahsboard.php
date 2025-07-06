<?php
require_once '../includes/auth.php';
if ($role != 'mahasiswa') {
    header('Location: ../asisten/dashboard.php');
    exit();
}
?>

<?php include '../includes/header.php'; ?>

<div class="container mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold mb-6">Dashboard Mahasiswa</h1>
    
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white p-6 rounded-lg shadow">
            <h2 class="text-xl font-semibold mb-4">Praktikum Saya</h2>
            <p class="text-gray-600">Lihat daftar praktikum yang Anda ikuti</p>
            <a href="praktikum.php" class="mt-4 inline-block bg-blue-500 text-white py-2 px-4 rounded hover:bg-blue-600">
                Lihat Praktikum
            </a>
        </div>
        
        <div class="bg-white p-6 rounded-lg shadow">
            <h2 class="text-xl font-semibold mb-4">Daftar Praktikum</h2>
            <p class="text-gray-600">Cari dan daftar praktikum baru</p>
            <a href="daftar_praktikum.php" class="mt-4 inline-block bg-green-500 text-white py-2 px-4 rounded hover:bg-green-600">
                Cari Praktikum
            </a>
        </div>
        
        <div class="bg-white p-6 rounded-lg shadow">
            <h2 class="text-xl font-semibold mb-4">Profil Saya</h2>
            <p class="text-gray-600">Kelola informasi akun Anda</p>
            <a href="#" class="mt-4 inline-block bg-gray-500 text-white py-2 px-4 rounded hover:bg-gray-600">
                Edit Profil
            </a>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>