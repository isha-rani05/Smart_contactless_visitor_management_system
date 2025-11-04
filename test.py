import socket

def get_ipv4():
    s = socket.socket(socket.AF_INET, socket.SOCK_DGRAM)
    try:
        # doesn't need to be reachable
        s.connect(("8.8.8.8", 80))
        ip = s.getsockname()[0]
    finally:
        s.close()
    return ip

ipv4 = get_ipv4()

print(ipv4)

string = """
<?php
// index.php
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Smart Visitor Management System</title>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background: linear-gradient(45deg, #fce4ec, #f8bbd9, #f48fb1, #e1bee7);
      background-size: 400% 400%;
      animation: gradientShift 8s ease-in-out infinite;
      display: flex;
      justify-content: center;
      align-items: center;
      min-height: 100vh;
      padding: 20px;
    }

    @keyframes gradientShift {
      0% { background-position: 0% 50%; }
      25% { background-position: 100% 0%; }
      50% { background-position: 100% 100%; }
      75% { background-position: 0% 100%; }
      100% { background-position: 0% 50%; }
    }

    .container {
      background: rgba(255, 255, 255, 0.95);
      backdrop-filter: blur(10px);
      border: 1px solid rgba(255, 255, 255, 0.2);
      padding: 3rem 2.5rem;
      border-radius: 24px;
      box-shadow: 
        0 20px 60px rgba(244, 143, 177, 0.15),
        0 8px 30px rgba(244, 143, 177, 0.08),
        inset 0 1px 0 rgba(255, 255, 255, 0.8);
      text-align: center;
      max-width: 450px;
      width: 100%;
      position: relative;
      overflow: hidden;
      transition: all 0.3s ease;
    }

    .container::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      height: 4px;
      background: linear-gradient(90deg, #f48fb1, #e1bee7, #f8bbd9);
      border-radius: 24px 24px 0 0;
    }

    .container:hover {
      transform: translateY(-5px);
      box-shadow: 
        0 30px 80px rgba(244, 143, 177, 0.2),
        0 12px 40px rgba(244, 143, 177, 0.1),
        inset 0 1px 0 rgba(255, 255, 255, 0.8);
    }

    .header-icon {
      font-size: 3rem;
      color: #f48fb1;
      margin-bottom: 1rem;
      animation: pulse 2s ease-in-out infinite;
    }

    @keyframes pulse {
      0%, 100% { transform: scale(1); }
      50% { transform: scale(1.05); }
    }

    h2 {
      color: #2d3436;
      font-size: 2rem;
      font-weight: 700;
      margin-bottom: 0.5rem;
      line-height: 1.2;
    }

    .subtitle {
      color: #636e72;
      font-size: 1rem;
      margin-bottom: 2rem;
      font-weight: 400;
    }

    .qr-container {
      background: #fff;
      padding: 1.5rem;
      border-radius: 16px;
      box-shadow: 
        0 8px 25px rgba(244, 143, 177, 0.1),
        inset 0 1px 0 rgba(255, 255, 255, 0.9);
      margin: 1.5rem auto;
      display: inline-block;
      position: relative;
      transition: all 0.3s ease;
    }

    .qr-container:hover {
      transform: scale(1.02);
      box-shadow: 
        0 12px 35px rgba(244, 143, 177, 0.15),
        inset 0 1px 0 rgba(255, 255, 255, 0.9);
    }

    .qr-container::before {
      content: '';
      position: absolute;
      top: -2px;
      left: -2px;
      right: -2px;
      bottom: -2px;
      background: linear-gradient(45deg, #f48fb1, #e1bee7, #f8bbd9);
      border-radius: 18px;
      z-index: -1;
      opacity: 0;
      transition: opacity 0.3s ease;
    }

    .qr-container:hover::before {
      opacity: 0.3;
    }

    img.qr {
      width: 200px;
      height: 200px;
      border-radius: 8px;
      display: block;
    }

    .instructions {
      color: #636e72;
      font-size: 0.95rem;
      margin-bottom: 1.5rem;
      line-height: 1.5;
    }

    .scan-text {
      color: #f48fb1;
      font-weight: 600;
      font-size: 0.9rem;
      margin-top: 1rem;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 0.5rem;
    }

    .scan-text i {
      animation: bounce 2s ease-in-out infinite;
    }

    @keyframes bounce {
      0%, 20%, 50%, 80%, 100% { transform: translateY(0); }
      40% { transform: translateY(-5px); }
      60% { transform: translateY(-3px); }
    }

    .view-logs-btn {
      display: inline-block;
      margin-top: 2rem;
      padding: 12px 24px;
      background: linear-gradient(45deg, #f48fb1, #ec4899);
      color: white;
      font-weight: 600;
      border: none;
      border-radius: 12px;
      cursor: pointer;
      text-decoration: none;
      transition: all 0.3s ease;
      box-shadow: 0 6px 15px rgba(236, 72, 153, 0.3);
    }

    .view-logs-btn:hover {
      background: linear-gradient(45deg, #ec4899, #be185d);
      box-shadow: 0 10px 25px rgba(190, 24, 93, 0.4);
      transform: translateY(-2px);
    }

    @media (max-width: 480px) {
      .container {
        padding: 2rem 1.5rem;
        margin: 10px;
      }
      
      h2 {
        font-size: 1.5rem;
      }
      
      img.qr {
        width: 180px;
        height: 180px;
      }
    }

    .particle {
      position: fixed;
      pointer-events: none;
      opacity: 0.6;
      animation: float 6s ease-in-out infinite;
    }

    .particle:nth-child(1) { animation-delay: 0s; }
    .particle:nth-child(2) { animation-delay: 1s; }
    .particle:nth-child(3) { animation-delay: 2s; }

    @keyframes float {
      0%, 100% { transform: translateY(0px) rotate(0deg); }
      33% { transform: translateY(-20px) rotate(120deg); }
      66% { transform: translateY(10px) rotate(240deg); }
    }
  </style>
</head>
<body>
  <div class="particle" style="top: 20%; left: 10%; color: #f8bbd9; font-size: 1.5rem;">
    <i class="fas fa-circle"></i>
  </div>
  <div class="particle" style="top: 70%; right: 15%; color: #e1bee7; font-size: 1.2rem;">
    <i class="fas fa-circle"></i>
  </div>
  <div class="particle" style="bottom: 30%; left: 20%; color: #f48fb1; font-size: 1rem;">
    <i class="fas fa-circle"></i>
  </div>

  <div class="container">
    <div class="header-icon">
      <i class="fas fa-qrcode"></i>
    </div>
    
    <h2>Smart Visitor<br>Management System</h2>
    <p class="subtitle">Streamlined check-in experience</p>
    
    <p class="instructions">
      Welcome! Please scan the QR code below with your mobile device to begin the check-in process.
    </p>
    
    <?php
      $url = "http://"""+ipv4+"""/visitor_management_2/checkin.php";
    ?>
    
    <div class="qr-container">
      <img class="qr" src="https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=<?php echo urlencode($url); ?>" alt="QR Code for Check-in">
    </div>
    
    <p class="scan-text">
      <i class="fas fa-mobile-alt"></i>
      Point your camera here to scan
    </p>

    <!-- View Logs Button -->
    <a href="admin.php" class="view-logs-btn">
      <i class="fas fa-database"></i> View Logs
    </a>
  </div>
</body>
</html>
"""

with open("index.php", "w") as f:
    f.write(string)