<?php
$lifetime = 60 * 60 * 24 * 30; // 30 days

ini_set('session.gc_maxlifetime', $lifetime);

session_set_cookie_params([
    'lifetime' => $lifetime,
    'path' => '/',
    'domain' => $_SERVER['HTTP_HOST'],
    'secure' => isset($_SERVER['HTTPS']),
    'httponly' => true,
    'samesite' => 'Lax'
]);

session_start();


date_default_timezone_set('Asia/Colombo');
 ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <title>Login - Edulk</title>
    <meta content="" name="description">
    <meta content="" name="keywords">

    <?php include_once ("includes/css-links-inc.php"); ?>

    <style>
        /* Custom styles for success and error messages */
        .alert-success {
            background-color: green;
            color: white;
            padding: 5px;
            border-radius: 5px;
            margin-bottom: 15px;
            text-align: center;
        }

        .alert-danger {
            background-color: red;
            color: white;
            padding: 5px;
            border-radius: 5px;
            margin-bottom: 15px;
            text-align: center;
        }
    </style>

</head>

<body>

    <main>
        <div class="container">
            <section class="section register min-vh-100 d-flex flex-column align-items-center justify-content-center py-4">
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-lg-4 col-md-6 d-flex flex-column align-items-center justify-content-center">
                            <div class="d-flex justify-content-center py-4">
                                <a href="index.php" class="logo d-flex align-items-center w-auto">
                                    <img src="assets/images/logos/edulk-logo.png" alt="" style="max-height:130px;">
                                    <!-- <span class="d-none d-lg-block">MediQ</span> -->
                                </a>
                            </div><!-- End Logo -->

                            <div class="card mb-3">
                                <div class="card-body">
                                    <div class="pt-4 pb-2">
                                        <h5 class="card-title text-center pb-0 fs-4">Login to Your Account</h5>
                                        <!-- <p class="text-center small">Enter your username & password to login</p> -->
                                    </div>


                                    <!-- Display messages here -->
                                    <?php if (isset($_SESSION['error_message'])): ?>
                                        <div class="alert alert-danger" id="alert-message">
                                            <?php echo $_SESSION['error_message']; ?>
                                        </div>
                                        <?php unset($_SESSION['error_message']); // Clear the message after displaying ?>
                                    <?php elseif (isset($_SESSION['success_message'])): ?>
                                        <div class="alert alert-success" id="alert-message">
                                            <?php echo $_SESSION['success_message']; ?>
                                        </div>
                                        <?php unset($_SESSION['success_message']); // Clear the message after displaying ?>
                                    <?php endif; ?>


                                    <form action="login-process.php" method="POST" class="row g-3 needs-validation" novalidate>
                                        <div class="col-12">
                                            <label for="yourUsername" class="form-label">Email</label>
                                            <div class="input-group has-validation">
                                                <span class="input-group-text" id="inputGroupPrepend">@</span>
                                                <input type="text" name="email" class="form-control" id="yourUsername" required>
                                                <div class="invalid-feedback">Please enter your username.</div>
                                            </div>
                                        </div>

                                        <div class="col-12">
                                          <label for="yourPassword" class="form-label">Password</label>
                                          <div class="input-group">
                                                <input type="password" class="form-control" id="myPassword" name="password" required>
                                                <span class="input-group-text" id="inputGroupPrepend">
                                                    <i class="password-toggle-icon1 bx bxs-show" onclick="togglePasswordVisibility('myPassword', 'password-toggle-icon1')"></i>
                                                </span>
                                              <div class="invalid-feedback">Please enter your password!</div>
                                          </div>
                                        </div>
                                        <div class="col-12">
  <input type="checkbox" name="remember" id="remember">
  <label for="remember"> Remember Me</label>
</div>
                                        <div class="col-12 text-end">
                                    <a href="forgot-password.php" class="small">Forgot Password?</a>
                                </div>


                                        <!--div class="col-12">
                                          <p class="small mb-0" style="font-size:14px;"><a href="pages-forgotten-password.php">Forgotten password</a>
                                        </div-->

                                        <div class="col-12">
                                            <input type="submit" class="btn btn-primary w-100" id="submit" name="submit" value="Login">
                                        </div>

                                         <div class="col-12">
                                          <p class="small mb-0" style="font-size:14px;">Don't have Student account? <a href="pages-signup.php">Create an account</a></p>
                                        </div>

                                        <div class="col-12">
                                          <p class="small mb-0" style="font-size:14px;">Are you Batch Representer? <a href="pages-batchadmin-signup.php">Create an account</a></p>
                                        </div>

                                        <div class="col-12">
                                          <p class="small mb-0" style="font-size:14px;">Are you Lecturer? <a href="pages-lecture-signup.php">Create an account</a></p>
                                        </div>

                                        

                                        <div class="col-12">
                                         <p>Don't have an account? <a href="guest_view.php?semester=Semester+I&subject=" class="btn btn-outline-primary btn-sm">Continue as Guest</a></p>
                                        </div>

                                    </form>
                                </div>
                            </div>

                            <?php include_once ("includes/footer3.php") ?>

                        </div>
                    </div>
                </div>
            </section>
        </div>
    </main><!-- End #main -->

    <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

    <?php include_once ("includes/js-links-inc.php") ?>

    <script>
        // Set a timeout to hide the message after 10 seconds
        window.onload = function() {
            setTimeout(function() {
                var alertMessage = document.getElementById('alert-message');
                if (alertMessage) {
                    alertMessage.style.display = 'none';
                }
            }, 10000); // Hide after 10 seconds
        };
    </script>

</body>

</html>