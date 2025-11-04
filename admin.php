<?php
// admin.php
include 'db.php';
session_start();

// Define your admin password here (you can change it)
$admin_password = "admin123"; // change this to something strong

// Handle Logout
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: admin.php");
    exit;
}

// --- Admin Login Logic ---
if (!isset($_SESSION['admin_logged_in'])) {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $entered_pass = trim($_POST['password'] ?? '');
        if ($entered_pass === $admin_password) {
            $_SESSION['admin_logged_in'] = true;
            header("Location: admin.php");
            exit;
        } else {
            $error = "Incorrect password. Please try again.";
        }
    }

    // --- Show Login Page ---
    echo '<!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Admin Login - Visitor Management System</title>
        <script src="https://cdn.tailwindcss.com"></script>
    </head>
    <body class="flex items-center justify-center min-h-screen bg-gradient-to-br from-pink-200 via-pink-300 to-rose-300">
        <form method="POST" class="bg-white/90 backdrop-blur-md p-8 rounded-2xl shadow-2xl w-80 text-center">
            <h2 class="text-2xl font-bold text-pink-700 mb-4">Admin Login</h2>
            <input type="password" name="password" placeholder="Enter Password"
                class="w-full px-4 py-2 border border-pink-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-pink-400 mb-4" required>
            <button type="submit"
                class="w-full bg-gradient-to-r from-pink-500 to-rose-500 text-white py-2 rounded-lg hover:opacity-90 transition">
                Login
            </button>';
            if (isset($error)) {
                echo '<p class="text-red-600 text-sm mt-3">' . htmlspecialchars($error) . '</p>';
            }
    echo '</form>
    </body>
    </html>';
    exit;
}

// --- Handle Delete All Visitors ---
if (isset($_POST['delete_all'])) {
    $conn->query("DELETE FROM visitors");
    header("Location: admin.php");
    exit;
}

// --- Fetch Visitor Data ---
$totalVisitors = 0;
$todayVisitors = 0;
$recentVisitors = [];

try {
    // total
    $result = $conn->query("SELECT COUNT(*) AS total FROM visitors");
    $totalVisitors = $result->fetch_assoc()['total'] ?? 0;

    // today's count
    $today = date('Y-m-d');
    $stmt = $conn->prepare("SELECT COUNT(*) AS today FROM visitors WHERE DATE(checkin_time)=?");
    $stmt->bind_param("s", $today);
    $stmt->execute();
    $res = $stmt->get_result();
    $todayVisitors = $res->fetch_assoc()['today'] ?? 0;
    $stmt->close();

    // recent visitors
    $recentVisitors = $conn->query("SELECT * FROM visitors ORDER BY checkin_time DESC LIMIT 5");
} catch (Exception $e) {
    die("Error fetching visitors: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Panel - Visitor Management System</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <style>
    body { box-sizing: border-box; }
    .gradient-bg {
      background: linear-gradient(45deg, #fce7f3, #fbcfe8, #f9a8d4, #f472b6, #ec4899, #be185d);
      background-size: 400% 400%;
      animation: gradientFlow 12s ease-in-out infinite;
    }
    @keyframes gradientFlow {
      0%, 100% { background-position: 0% 50%; }
      25% { background-position: 100% 50%; }
      50% { background-position: 100% 100%; }
      75% { background-position: 0% 100%; }
    }
  </style>
</head>
<body class="gradient-bg min-h-screen">

<!-- Header -->
<header class="bg-white/10 backdrop-blur-md border-b border-white/20">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="flex justify-between items-center py-4">
      <div class="flex items-center">
        <div class="inline-flex items-center justify-center w-10 h-10 bg-gradient-to-br from-pink-500 to-rose-500 rounded-xl mr-3 shadow-lg">
          <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
          </svg>
        </div>
        <h1 class="text-2xl font-bold text-white">Admin Dashboard</h1>
      </div>
      <div class="flex items-center space-x-4">
        <div class="flex items-center text-white">
          <div class="w-2 h-2 bg-green-400 rounded-full mr-2 animate-pulse"></div>
          <span class="text-sm">System Online</span>
        </div>
        <a href="?logout=1" class="bg-rose-500 text-white px-4 py-1.5 rounded-lg hover:bg-rose-600 transition">Logout</a>
      </div>
    </div>
  </div>
</header>

<!-- Main Content -->
<main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
  <!-- Stats -->
  <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <div class="bg-white/90 rounded-2xl p-6 shadow-md">
      <p class="text-sm text-slate-600">Total Visitors</p>
      <p class="text-3xl font-bold text-pink-700"><?= $totalVisitors ?></p>
    </div>
    <div class="bg-white/90 rounded-2xl p-6 shadow-md">
      <p class="text-sm text-slate-600">Check-ins Today</p>
      <p class="text-3xl font-bold text-pink-700"><?= $todayVisitors ?></p>
    </div>
    <div class="bg-white/90 rounded-2xl p-6 shadow-md">
      <p class="text-sm text-slate-600">Recent Visitors</p>
      <p class="text-3xl font-bold text-pink-700">
        <?= is_object($recentVisitors) ? $recentVisitors->num_rows : 0 ?>
      </p>
    </div>
    <div class="bg-white/90 rounded-2xl p-6 shadow-md">
      <p class="text-sm text-slate-600">Database Status</p>
      <p class="text-3xl font-bold text-green-600">Connected</p>
    </div>
  </div>

  <!-- Visitor List -->
  <div class="bg-white/90 rounded-3xl p-6 shadow-lg">
    <h2 class="text-xl font-bold text-pink-700 mb-4">Recent Visitors</h2>
    <div class="space-y-3">
      <?php if ($recentVisitors && $recentVisitors->num_rows > 0): ?>
        <?php while ($row = $recentVisitors->fetch_assoc()): ?>
          <div class="flex items-center justify-between p-4 bg-pink-50 rounded-xl">
            <div class="flex items-center">
              <div class="w-10 h-10 bg-gradient-to-br from-pink-400 to-pink-600 rounded-full flex items-center justify-center text-white font-medium">
                <?= strtoupper(substr($row['name'], 0, 2)) ?>
              </div>
              <div class="ml-3">
                <p class="font-medium text-slate-900"><?= htmlspecialchars($row['name']) ?></p>
                <p class="text-sm text-slate-500">
                  <?= htmlspecialchars($row['phone'] ?? $row['email']) ?> — <?= date('d M h:i A', strtotime($row['checkin_time'])) ?>
                </p>
              </div>
            </div>
          </div>
        <?php endwhile; ?>
      <?php else: ?>
        <p class="text-slate-600">No recent visitors found.</p>
      <?php endif; ?>
    </div>

    <!-- Delete All -->
    <form method="POST" onsubmit="return confirm('Are you sure you want to delete all visitor records?');" class="mt-6 text-center">
      <button type="submit" name="delete_all"
        class="bg-gradient-to-r from-pink-500 to-rose-600 text-white px-6 py-2 rounded-xl hover:opacity-90 transition">
        Delete All Records
      </button>
    </form>
  </div>
</main>
</body>
</html>
