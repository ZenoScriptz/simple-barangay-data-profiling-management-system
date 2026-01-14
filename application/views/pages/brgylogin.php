<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Barangay Management System</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome for Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" />
    <!-- Google Fonts (Poppins) for consistency with the dashboard -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet" />

    <style>
        :root {
            --primary-green: #28a745; /* A slightly more vibrant green */
            --primary-green-hover: #218838;
            --text-color: #333;
            --text-secondary: #6c757d;
            --border-color: #ced4da;
            --background-light: #f8f9fa;
        }

        body, html {
            height: 100%;
            margin: 0;
            font-family: 'Poppins', sans-serif;
        }

        .login-container {
            display: flex;
            height: 100vh;
            width: 100%;
        }

        /* 1. The Form Panel on the Left */
        .login-form-panel {
            flex-basis: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
            background-color: var(--background-light);
            padding: 40px;
        }

        .login-wrapper {
            width: 100%;
            max-width: 400px; /* Increased max-width for better spacing */
        }

        /* 2. The Image Panel on the Right */
        .login-image-panel {
            flex-basis: 50%;
            background-image: url('<?= base_url("assets/image/login/PC_HILL_Cotabato_City.jpg"); ?>');
            background-size: cover;
            background-position: center;
        }

        /* 3. Branding & Typography */
        .login-logo {
            max-width: 80px; /* Control the size of your logo */
            margin-bottom: 20px;
        }

        .login-wrapper h1 {
            font-weight: 700;
            font-size: 2.5rem;
            color: var(--text-color);
            margin-bottom: 10px;
        }

        .login-wrapper p.welcome-message {
            color: var(--text-secondary);
            margin-bottom: 30px;
            font-size: 1rem;
        }

        /* 4. Enhanced Form Fields */
        .form-group {
            position: relative; /* Needed for icon positioning */
            margin-bottom: 1.5rem;
        }

        .form-icon {
            position: absolute;
            top: 50%;
            left: 15px;
            transform: translateY(-50%);
            color: #999;
        }

        .form-control {
            height: 50px;
            border-radius: 8px;
            border: 1px solid var(--border-color);
            padding-left: 45px; /* Make space for the icon */
            transition: border-color 0.2s ease, box-shadow 0.2s ease;
        }

        .form-control:focus {
            border-color: var(--primary-green);
            box-shadow: 0 0 0 0.2rem rgba(40, 167, 69, 0.25);
            outline: none;
        }

        /* 5. Login Button */
        .btn-login {
            background-color: var(--primary-green);
            color: white;
            padding: 12px 20px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 1rem;
            font-weight: 600;
            width: 100%;
            transition: background-color 0.2s ease-in-out;
        }

        .btn-login:hover {
            background-color: var(--primary-green-hover);
        }

        /* 6. Error Message Styling */
        .error-message {
            color: #721c24;
            background-color: #f8d7da;
            border: 1px solid #f5c6cb;
            padding: 10px 15px;
            margin-bottom: 20px;
            border-radius: 8px;
            text-align: left;
            font-size: 0.9rem;
        }

        /* 7. Responsive Design for Mobile */
        @media (max-width: 992px) {
            .login-image-panel {
                display: none; /* Hide the image on smaller screens */
            }
            .login-form-panel {
                flex-basis: 100%; /* The form takes the full width */
            }
        }

    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-form-panel">
            <div class="login-wrapper">
                <!-- Add your logo here -->
                <img src="<?= base_url('assets/image/sidebar/logo.png'); ?>" alt="City Logo" class="login-logo">

                <h1>Welcome Back!</h1>
                <p class="welcome-message">Please sign in to access the system.</p>

                <?php if ($this->session->flashdata('login_error')): ?>
                    <div class="error-message"><?= $this->session->flashdata('login_error'); ?></div>
                <?php endif; ?>
                <?php if (validation_errors()): ?>
                    <div class="error-message"><?= validation_errors(); ?></div>
                <?php endif; ?>

                <?= form_open('login'); ?>
                <div class="form-group">
                    <i class="fas fa-user form-icon"></i>
                    <input type="text" name="username" id="username" class="form-control" placeholder="Username" value="<?= set_value('username'); ?>" required>
                </div>
                <div class="form-group">
                    <i class="fas fa-lock form-icon"></i>
                    <input type="password" name="password" id="password" class="form-control" placeholder="Password" required>
                </div>
                <div class="form-group mt-4">
                    <button type="submit" class="btn btn-login">Login</button>
                </div>
                <?= form_close(); ?>
            </div>
        </div>
        <div class="login-image-panel"></div>
    </div>
</body>
</html>