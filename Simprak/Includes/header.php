<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIMPRAK - <?php echo $title ?? 'Sistem Manajemen Praktikum'; ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: {
                            50: '#fef2f2',
                            100: '#fee2e2',
                            200: '#fecaca',
                            300: '#fca5a5',
                            400: '#f87171',
                            500: '#ef4444',
                            600: '#dc2626',
                            700: '#b91c1c',
                            800: '#991b1b',
                            900: '#7f1d1d',
                        }
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-gray-50 min-h-screen flex flex-col">
    <!-- Navigation -->
    <nav class="bg-white shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <div class="flex-shrink-0 flex items-center">
                        <span class="text-primary-600 font-bold text-xl">SIMPRAK</span>
                    </div>
                    <div class="hidden sm:ml-6 sm:flex sm:space-x-8">
                        <a href="../index.php" class="border-primary-500 text-gray-900 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                            Home
                        </a>
                        <?php if(isset($_SESSION['user_id'])): ?>
                            <?php if($role == 'mahasiswa'): ?>
                                <a href="mahasiswa/dashboard.php" class="border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                                    Dashboard
                                </a>
                            <?php else: ?>
                                <a href="asisten/dashboard.php" class="border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                                    Dashboard
                                </a>
                            <?php endif; ?>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="hidden sm:ml-6 sm:flex sm:items-center">
                    <?php if(isset($_SESSION['user_id'])): ?>
                        <span class="text-gray-500 text-sm mr-4"><?php echo htmlspecialchars($user['nama']); ?></span>
                        <a href="../logout.php" class="text-gray-500 hover:text-primary-500 px-3 py-2 rounded-md text-sm font-medium">
                            Logout
                        </a>
                    <?php else: ?>
                        <a href="../login.php" class="text-gray-500 hover:text-primary-500 px-3 py-2 rounded-md text-sm font-medium">
                            Login
                        </a>
                        <a href="../register.php" class="ml-4 bg-primary-500 hover:bg-primary-600 text-white px-3 py-2 rounded-md text-sm font-medium">
                            Register
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="flex-grow">