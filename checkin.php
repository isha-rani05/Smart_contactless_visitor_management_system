<?php
include 'db.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
  <title>Visitor Registration</title>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
  <style>
    /* ORIGINAL STYLES KEPT EXACTLY AS BEFORE */
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      -webkit-tap-highlight-color: transparent;
    }

    body {
      font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
      background: linear-gradient(45deg, #ffc2e1, #ffb5db, #ffa8d5, #ff9bcf, #f48fb1, #ed7fa3, #e67295, #ff85b3);
      background-size: 400% 400%;
      animation: gradientFlow 12s ease-in-out infinite;
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 20px 16px;
      position: relative;
      overflow: hidden;
    }

    body::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      background: linear-gradient(135deg, rgba(255, 194, 225, 0.4), rgba(255, 181, 219, 0.3), rgba(255, 168, 213, 0.5), rgba(255, 155, 207, 0.3));
      background-size: 600% 600%;
      animation: overlayShift 15s ease-in-out infinite;
      z-index: 0;
    }

    @keyframes gradientFlow {
      0% { background-position: 0% 50%; }
      25% { background-position: 100% 0%; }
      50% { background-position: 100% 100%; }
      75% { background-position: 0% 100%; }
      100% { background-position: 0% 50%; }
    }

    @keyframes overlayShift {
      0%, 100% { background-position: 0% 0%; }
      33% { background-position: 100% 50%; }
      66% { background-position: 50% 100%; }
    }

    .container {
      background: #ffffff;
      border-radius: 24px;
      box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
      width: 100%;
      max-width: 400px;
      padding: 32px 24px;
      position: relative;
      z-index: 1;
    }

    .header {
      text-align: center;
      margin-bottom: 32px;
    }

    .icon-circle {
      width: 64px;
      height: 64px;
      background: linear-gradient(135deg, #e891b8, #d06ca9);
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      margin: 0 auto 20px;
    }

    .icon-circle i {
      color: white;
      font-size: 24px;
    }

    h1 {
      color: #2c2c2c;
      font-size: 24px;
      font-weight: 600;
      margin-bottom: 8px;
      line-height: 1.2;
    }

    .subtitle {
      color: #6b7280;
      font-size: 14px;
      line-height: 1.4;
    }

    .form-group {
      margin-bottom: 20px;
    }

    label {
      display: block;
      color: #374151;
      font-size: 14px;
      font-weight: 500;
      margin-bottom: 6px;
    }

    .required {
      color: #ef4444;
    }

    .input-wrapper {
      position: relative;
    }

    input[type="text"],
    input[type="tel"],
    input[type="email"],
    select,
    textarea {
      width: 100%;
      padding: 12px 16px;
      border: 1px solid #d1d5db;
      border-radius: 12px;
      font-size: 16px;
      background: #ffffff;
      transition: all 0.2s ease;
      -webkit-appearance: none;
      appearance: none;
    }

    input[type="text"]:focus,
    input[type="tel"]:focus,
    input[type="email"]:focus,
    select:focus,
    textarea:focus {
      outline: none;
      border-color: #e891b8;
      box-shadow: 0 0 0 3px rgba(232, 145, 184, 0.1);
    }

    .input-icon {
      position: absolute;
      right: 16px;
      top: 50%;
      transform: translateY(-50%);
      color: #e891b8;
      font-size: 16px;
      pointer-events: none;
    }

    select {
      padding-right: 40px;
      cursor: pointer;
      color: #6b7280;
    }

    select:valid {
      color: #374151;
    }

    .select-wrapper {
      position: relative;
    }

    .select-wrapper::after {
      content: '\f107';
      font-family: 'Font Awesome 6 Free';
      font-weight: 900;
      position: absolute;
      right: 16px;
      top: 50%;
      transform: translateY(-50%);
      color: #e891b8;
      pointer-events: none;
    }

    textarea {
      min-height: 80px;
      resize: vertical;
      font-family: inherit;
    }

    .submit-btn {
      width: 100%;
      background: linear-gradient(135deg, #e891b8, #d06ca9);
      color: white;
      border: none;
      padding: 16px;
      border-radius: 12px;
      font-size: 16px;
      font-weight: 600;
      cursor: pointer;
      transition: all 0.2s ease;
      margin-top: 8px;
    }

    .submit-btn:hover {
      transform: translateY(-1px);
      box-shadow: 0 4px 12px rgba(232, 145, 184, 0.4);
    }

    .submit-btn:active {
      transform: translateY(0);
    }

    .privacy-notice {
      text-align: center;
      color: #6b7280;
      font-size: 12px;
      line-height: 1.4;
      margin-top: 16px;
    }

    /* Loading state */
    .submit-btn.loading {
      pointer-events: none;
      opacity: 0.7;
    }

    .submit-btn.loading::after {
      content: '';
      width: 16px;
      height: 16px;
      border: 2px solid rgba(255,255,255,0.3);
      border-top: 2px solid white;
      border-radius: 50%;
      animation: spin 1s linear infinite;
      display: inline-block;
      margin-left: 8px;
    }

    @keyframes spin {
      0% { transform: rotate(0deg); }
      100% { transform: rotate(360deg); }
    }

    /* Responsive adjustments */
    @media (max-height: 700px) {
      .container {
        padding: 24px 20px;
      }

      .header {
        margin-bottom: 24px;
      }

      .icon-circle {
        width: 56px;
        height: 56px;
        margin-bottom: 16px;
      }

      .icon-circle i {
        font-size: 20px;
      }

      h1 {
        font-size: 20px;
      }
    }

    @media (max-width: 380px) {
      body {
        padding: 16px 12px;
      }

      .container {
        padding: 24px 20px;
      }
    }
  </style>
</head>
<body>
  <div class="container">
    <div class="header">
      <div class="icon-circle">
        <i class="fas fa-user"></i>
      </div>
      <h1>Visitor Registration</h1>
      <p class="subtitle">Please fill in your details to register your visit</p>
    </div>

  <!-- Added id so your JS finds the form -->
  <form id="registrationForm" method="POST" action="process_checkin.php">
      <div class="form-group">
        <label for="name">Full Name <span class="required">*</span></label>
        <div class="input-wrapper">
          <input type="text" id="name" name="name" placeholder="Enter your full name" required>
          <i class="fas fa-user input-icon"></i>
        </div>
      </div>

      <div class="form-group">
        <label for="phone">Phone Number <span class="required">*</span></label>
        <div class="input-wrapper">
          <input type="tel" id="phone" name="phone" placeholder="Enter your phone number" required>
          <i class="fas fa-phone input-icon"></i>
        </div>
      </div>

      <div class="form-group">
        <label for="reason">Reason for Visit <span class="required">*</span></label>
        <div class="select-wrapper">
          <select id="reason" name="reason" required>
            <option value="">Select reason for visit</option>
            <option value="business">Business Meeting</option>
            <option value="interview">Job Interview</option>
            <option value="delivery">Delivery</option>
            <option value="appointment">Appointment</option>
            <option value="maintenance">Maintenance</option>
            <option value="other">Other</option>
          </select>
        </div>
      </div>

      <div class="form-group">
        <label for="notes">Additional Notes (Optional)</label>
        <textarea id="notes" name="notes" placeholder="Any additional information..."></textarea>
      </div>

      <button type="submit" class="submit-btn" id="submitButton">
        Register Visit
      </button>

      <p class="privacy-notice">
        Your information is secure and will only be used for visitor management purposes.
      </p>
    </form>
  </div>

  <script>
    document.addEventListener("DOMContentLoaded", () => {
      const form = document.getElementById("registrationForm");
      const submitButton = document.getElementById("submitButton");

      // Enhanced form validation
      const inputs = document.querySelectorAll('input, select, textarea');
      inputs.forEach(input => {
        input.addEventListener('blur', () => {
          validateField(input);
        });

        input.addEventListener('input', () => {
          if (input.classList.contains('error')) {
            validateField(input);
          }
        });
      });

      // Form submission
      form.addEventListener("submit", (e) => {
        e.preventDefault();

        // Validate all fields
        let isValid = true;
        inputs.forEach(input => {
          if (!validateField(input)) {
            isValid = false;
          }
        });

        if (!isValid) {
          return;
        }

        // Show loading state
        submitButton.classList.add('loading');
        submitButton.textContent = 'Registering...';

        // Submit the form via normal POST after short UX delay
        setTimeout(() => {
          // Actually submit the form to server
          form.submit();
        }, 600);
      });
    });

    function validateField(field) {
      const value = field.value.trim();
      let isValid = true;

      // Remove existing error styles
      field.style.borderColor = '#d1d5db';
      field.classList.remove('error');

      if (field.hasAttribute('required') && !value) {
        isValid = false;
      } else if (field.type === 'tel' && value) {
        // Basic phone validation
        const phoneRegex = /^[\+]?[\d\s\-\(\)]{10,}$/;
        isValid = phoneRegex.test(value);
      } else if (field.type === 'email' && value) {
        // Email validation
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        isValid = emailRegex.test(value);
      }

      if (!isValid) {
        field.style.borderColor = '#ef4444';
        field.classList.add('error');
      }

      return isValid;
    }
  </script>
</body>
</html>
