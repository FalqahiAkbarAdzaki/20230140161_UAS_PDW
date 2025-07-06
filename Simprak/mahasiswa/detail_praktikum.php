<?php
require_once '../includes/auth.php';
if ($role != 'mahasiswa') {
    header('Location: ../asisten/dashboard.php');
    exit();
}

$praktikum_id = sanitize($_GET['id']);

// Check if mahasiswa is registered in this praktikum
$sql = "SELECT * FROM praktikum_mahasiswa 
        WHERE praktikum_id = $praktikum_id AND mahasiswa_id = $user_id";
$result = $conn->query($sql);

if ($result->num_rows == 0) {
    header('Location: praktikum.php');
    exit();
}

// Get praktikum details
$sql = "SELECT * FROM praktikum WHERE id = $praktikum_id";
$praktikum = $conn->query($sql)->fetch_assoc();

// Get modul for this praktikum
$sql = "SELECT * FROM modul WHERE praktikum_id = $praktikum_id ORDER BY pertemuan";
$modul = $conn->query($sql);

// Handle file upload
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['upload_laporan'])) {
    $modul_id = sanitize($_POST['modul_id']);
    $file_name = $_FILES['laporan']['name'];
    $file_tmp = $_FILES['laporan']['tmp_name'];
    $file_size = $_FILES['laporan']['size'];
    
    $upload_dir = '../assets/uploads/laporan/';
    $file_path = $upload_dir . basename($file_name);
    
    if ($file_size > 5000000) { // 5MB max
        $_SESSION['error'] = "File terlalu besar. Maksimal 5MB.";
    } else {
        if (move_uploaded_file($file_tmp, $file_path)) {
            $sql = "INSERT INTO laporan (modul_id, mahasiswa_id, file_path, status) 
                    VALUES ($modul_id, $user_id, '$file_path', 'menunggu')";
            
            if ($conn->query($sql)) {
                $_SESSION['success'] = "Laporan berhasil diunggah";
            } else {
                $_SESSION['error'] = "Gagal menyimpan data laporan";
            }
        } else {
            $_SESSION['error'] = "Gagal mengunggah file";
        }
    }
    header("Location: detail_praktikum.php?id=$praktikum_id");
    exit();
}
?>

<?php include '../includes/header.php'; ?>

<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold"><?php echo $praktikum['nama']; ?></h1>
        <a href="praktikum.php" class="bg-gray-500 text-white py-2 px-4 rounded hover:bg-gray-600">
            Kembali
        </a>
    </div>
    
    <p class="text-gray-600 mb-8"><?php echo $praktikum['deskripsi']; ?></p>
    
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
    
    <div class="bg-white p-6 rounded-lg shadow">
        <h2 class="text-xl font-semibold mb-4">Modul Praktikum</h2>
        
        <div class="space-y-4">
            <?php while ($row = $modul->fetch_assoc()): ?>
            <div class="border rounded-lg p-4">
                <div class="flex justify-between items-center mb-2">
                    <h3 class="font-semibold">Pertemuan <?php echo $row['pertemuan']; ?>: <?php echo $row['judul']; ?></h3>
                    
                    <?php if (!empty($row['file_materi'])): ?>
                    <a href="<?php echo $row['file_materi']; ?>" download class="bg-blue-500 text-white py-1 px-3 rounded text-sm hover:bg-blue-600">
                        Unduh Materi
                    </a>
                    <?php endif; ?>
                </div>
                
                <p class="text-gray-600 mb-4"><?php echo $row['deskripsi']; ?></p>
                
                <!-- Laporan Section -->
                <div class="border-t pt-4">
                    <h4 class="font-medium mb-2">Laporan Praktikum</h4>
                    
                    <?php
                    // Check if laporan exists for this modul
                    $sql = "SELECT * FROM laporan 
                            WHERE modul_id = {$row['id']} AND mahasiswa_id = $user_id";
                    $laporan = $conn->query($sql);
                    
                    if ($laporan->num_rows > 0):
                        $laporan_data = $laporan->fetch_assoc();
                    ?>
                        <div class="mb-2">
                            <p class="text-sm">Status: 
                                <span class="<?php 
                                    echo $laporan_data['status'] == 'dinilai' ? 'text-green-500' : 'text-yellow-500';
                                ?>">
                                    <?php echo ucfirst($laporan_data['status']); ?>
                                </span>
                            </p>
                            
                            <?php if ($laporan_data['status'] == 'dinilai'): ?>
                                <p class="text-sm">Nilai: <?php echo $laporan_data['nilai']; ?></p>
                                <p class="text-sm">Feedback: <?php echo $laporan_data['feedback']; ?></p>
                            <?php endif; ?>
                        </div>
                        
                        <a href="<?php echo $laporan_data['file_path']; ?>" download class="text-blue-500 text-sm hover:underline mr-3">
                            Unduh Laporan Anda
                        </a>
                    <?php else: ?>
                        <form method="POST" enctype="multipart/form-data">
                            <input type="hidden" name="modul_id" value="<?php echo $row['id']; ?>">
                            
                            <div class="mb-2">
                                <label class="block text-sm text-gray-700 mb-1">Unggah Laporan</label>
                                <input type="file" name="laporan" class="text-sm" required>
                            </div>
                            
                            <button type="submit" name="upload_laporan" class="bg-green-500 text-white py-1 px-3 rounded text-sm hover:bg-green-600">
                                Kirim Laporan
                            </button>
                        </form>
                    <?php endif; ?>
                </div>
            </div>
            <?php endwhile; ?>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>