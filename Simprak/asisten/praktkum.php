<?php
require_once '../includes/auth.php';
if ($role != 'asisten') {
    header('Location: ../mahasiswa/dashboard.php');
    exit();
}

// Create new praktikum
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_praktikum'])) {
    $nama = sanitize($_POST['nama']);
    $deskripsi = sanitize($_POST['deskripsi']);
    
    $sql = "INSERT INTO praktikum (nama, deskripsi) VALUES ('$nama', '$deskripsi')";
    if ($conn->query($sql) {
        $_SESSION['success'] = "Praktikum berhasil ditambahkan";
    } else {
        $_SESSION['error'] = "Gagal menambahkan praktikum";
    }
    header('Location: praktikum.php');
    exit();
}

// Update praktikum
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_praktikum'])) {
    $id = sanitize($_POST['id']);
    $nama = sanitize($_POST['nama']);
    $deskripsi = sanitize($_POST['deskripsi']);
    
    $sql = "UPDATE praktikum SET nama='$nama', deskripsi='$deskripsi' WHERE id=$id";
    if ($conn->query($sql)) {
        $_SESSION['success'] = "Praktikum berhasil diperbarui";
    } else {
        $_SESSION['error'] = "Gagal memperbarui praktikum";
    }
    header('Location: praktikum.php');
    exit();
}

// Delete praktikum
if (isset($_GET['delete'])) {
    $id = sanitize($_GET['delete']);
    
    $sql = "DELETE FROM praktikum WHERE id=$id";
    if ($conn->query($sql)) {
        $_SESSION['success'] = "Praktikum berhasil dihapus";
    } else {
        $_SESSION['error'] = "Gagal menghapus praktikum";
    }
    header('Location: praktikum.php');
    exit();
}

// Get all praktikum
$sql = "SELECT * FROM praktikum";
$praktikum = $conn->query($sql);
?>

<?php include '../includes/header.php'; ?>

<div class="container mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold mb-6">Kelola Praktikum</h1>
    
    <?php if (isset($_SESSION['success'])): ?>
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
        </div>
    <?php endif; ?>
    
    <?php if (isset($_SESSION['error'])): ?>
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
        </div>
    <?php endif; ?>
    
    <!-- Add Praktikum Form -->
    <div class="bg-white p-6 rounded-lg shadow mb-8">
        <h2 class="text-xl font-semibold mb-4">Tambah Praktikum Baru</h2>
        <form method="POST">
            <div class="mb-4">
                <label class="block text-gray-700 mb-2" for="nama">Nama Praktikum</label>
                <input type="text" id="nama" name="nama" class="w-full px-3 py-2 border rounded" required>
            </div>
            
            <div class="mb-4">
                <label class="block text-gray-700 mb-2" for="deskripsi">Deskripsi</label>
                <textarea id="deskripsi" name="deskripsi" class="w-full px-3 py-2 border rounded" rows="3" required></textarea>
            </div>
            
            <button type="submit" name="add_praktikum" class="bg-blue-500 text-white py-2 px-4 rounded hover:bg-blue-600">
                Tambah Praktikum
            </button>
        </form>
    </div>
    
    <!-- Praktikum List -->
    <div class="bg-white p-6 rounded-lg shadow">
        <h2 class="text-xl font-semibold mb-4">Daftar Praktikum</h2>
        
        <div class="overflow-x-auto">
            <table class="min-w-full bg-white">
                <thead>
                    <tr>
                        <th class="py-2 px-4 border-b">ID</th>
                        <th class="py-2 px-4 border-b">Nama</th>
                        <th class="py-2 px-4 border-b">Deskripsi</th>
                        <th class="py-2 px-4 border-b">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $praktikum->fetch_assoc()): ?>
                    <tr>
                        <td class="py-2 px-4 border-b"><?php echo $row['id']; ?></td>
                        <td class="py-2 px-4 border-b"><?php echo $row['nama']; ?></td>
                        <td class="py-2 px-4 border-b"><?php echo $row['deskripsi']; ?></td>
                        <td class="py-2 px-4 border-b">
                            <a href="modul.php?praktikum_id=<?php echo $row['id']; ?>" class="text-blue-500 hover:text-blue-700 mr-2">
                                Modul
                            </a>
                            <a href="#" onclick="editPraktikum(<?php echo $row['id']; ?>, '<?php echo $row['nama']; ?>', '<?php echo $row['deskripsi']; ?>')" class="text-green-500 hover:text-green-700 mr-2">
                                Edit
                            </a>
                            <a href="praktikum.php?delete=<?php echo $row['id']; ?>" class="text-red-500 hover:text-red-700" onclick="return confirm('Yakin ingin menghapus?')">
                                Hapus
                            </a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Edit Modal -->
<div id="editModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden">
    <div class="bg-white p-6 rounded-lg shadow-lg w-full max-w-md">
        <h2 class="text-xl font-semibold mb-4">Edit Praktikum</h2>
        <form method="POST">
            <input type="hidden" id="edit_id" name="id">
            
            <div class="mb-4">
                <label class="block text-gray-700 mb-2" for="edit_nama">Nama Praktikum</label>
                <input type="text" id="edit_nama" name="nama" class="w-full px-3 py-2 border rounded" required>
            </div>
            
            <div class="mb-4">
                <label class="block text-gray-700 mb-2" for="edit_deskripsi">Deskripsi</label>
                <textarea id="edit_deskripsi" name="deskripsi" class="w-full px-3 py-2 border rounded" rows="3" required></textarea>
            </div>
            
            <div class="flex justify-end">
                <button type="button" onclick="document.getElementById('editModal').classList.add('hidden')" class="bg-gray-500 text-white py-2 px-4 rounded hover:bg-gray-600 mr-2">
                    Batal
                </button>
                <button type="submit" name="update_praktikum" class="bg-blue-500 text-white py-2 px-4 rounded hover:bg-blue-600">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function editPraktikum(id, nama, deskripsi) {
    document.getElementById('edit_id').value = id;
    document.getElementById('edit_nama').value = nama;
    document.getElementById('edit_deskripsi').value = deskripsi;
    document.getElementById('editModal').classList.remove('hidden');
}
</script>

<?php include '../includes/footer.php'; ?>