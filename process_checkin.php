<?php
// process_checkin.php
include 'db.php';

// Set correct timezone
date_default_timezone_set('Asia/Kolkata');

// Optional: log form reach (debug)
file_put_contents("test_log.txt", date('Y-m-d H:i:s') . " - Form reached process_checkin.php\n", FILE_APPEND);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $reason = trim($_POST['reason'] ?? '');
    $notes = trim($_POST['notes'] ?? '');

    // Basic validation
    if (empty($name) || empty($phone) || empty($reason)) {
        echo "<script>alert('Please fill in all required fields.'); window.history.back();</script>";
        exit;
    }

    // Prevent duplicate recent check-ins (within 5 mins)
    $checkQuery = $conn->prepare("SELECT id FROM visitors WHERE phone = ? AND checkin_time > (NOW() - INTERVAL 5 MINUTE) LIMIT 1");
    if (!$checkQuery) {
        echo "<script>alert('Database error.'); window.history.back();</script>";
        exit;
    }

    $checkQuery->bind_param("s", $phone);
    $checkQuery->execute();
    $result = $checkQuery->get_result();

    if ($result && $result->num_rows > 0) {
        echo "<script>alert('You have already checked in recently. Please wait 5 minutes before trying again.'); window.location.href='checkin.php';</script>";
        exit;
    }

    // Insert new visitor record
    $checkin_time = date('Y-m-d H:i:s');
    $stmt = $conn->prepare("INSERT INTO visitors (name, phone, reason, notes, checkin_time) VALUES (?, ?, ?, ?, ?)");
    if (!$stmt) {
        echo "<script>alert('Database error (prepare insert).'); window.history.back();</script>";
        exit;
    }

    $stmt->bind_param("sssss", $name, $phone, $reason, $notes, $checkin_time);

    if ($stmt->execute()) {
        echo "<script>alert('Check-in successful!'); window.location.href='success.php';</script>";
    } else {
        echo "<script>alert('Database error: " . addslashes($stmt->error) . "'); window.history.back();</script>";
    }

    $stmt->close();
    $checkQuery->close();
    $conn->close();
} else {
    header("Location: checkin.php");
    exit;
}
?>
