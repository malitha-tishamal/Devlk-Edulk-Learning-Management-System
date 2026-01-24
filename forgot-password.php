<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <title>Forgotten Password - MediQ</title>
    <meta content="" name="description">
    <meta content="" name="keywords">

    <?php include_once("includes/css-links-inc.php"); ?>
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
                                    <img src="assets/images/logos/mediq-logo.png" alt="" style="max-height:130px;">
                                </a>
                            </div><!-- End Logo -->

                            <div class="card mb-3">
                                <div class="card-body">
                                    <div class="pt-4 pb-2">
                                        <h5 class="card-title text-center pb-0 fs-4">Forgotten Password</h5>
                                    </div>

                                    <!-- Session Messages Display -->
                                    <?php if (isset($_SESSION['message'])): ?>
                                        <div class="alert alert-<?php echo ($_SESSION['status'] == 'success') ? 'success' : 'danger'; ?> text-center">
                                            <?php echo $_SESSION['message']; ?>
                                        </div>
                                        <?php unset($_SESSION['message']); unset($_SESSION['status']); ?>
                                    <?php endif; ?>

                                    <form action="reset-password.php" method="POST" class="row g-3 needs-validation" novalidate>
                                        <div class="col-12">
                                            <label for="email" class="form-label">Email</label>
                                            <input type="email" class="form-control" id="email" name="email" required>
                                            <div class="invalid-feedback">Please enter a valid email address!</div>
                                        </div>

                                        <div class="col-12">
                                            <p class="small mb-0" style="font-size:14px;">
                                                <a href="index.php">Back to Login page</a>
                                            </p>
                                        </div>

                                        <div class="col-12 mt-3">
                                            <input type="submit" class="btn btn-primary w-100" value="Send Email">
                                        </div>
                                    </form>

                                </div>
                            </div>

                            <?php include_once("includes/footer3.php"); ?>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </main> 

    <a href="#" class="back-to-top d-flex align-items-center justify-content-center">
        <i class="bi bi-arrow-up-short"></i>
    </a>

    <?php include_once("includes/js-links-inc.php"); ?>  
</body>
</html>
