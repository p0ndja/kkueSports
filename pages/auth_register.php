<?php require_once '../static/functions/connect.php'; ?>
<!DOCTYPE html>
<html lang="th" prefix="og:http://ogp.me/ns#">
<head><?php require_once '../static/functions/head.php'; ?></head>
<body>
    <?php require_once '../static/functions/navbar.php'; ?>
    <?php if (isLogin()) home(); ?>
    <div class="d-flex justify-content-center">
        <div class="container" id="container" style="padding-top: 5vh; width: 23rem;">
            <h1 class="display-5 text-center text-md font-weight-bold">Register <i class="far fa-edit"></i></h1>
            <div class="card">
                <form id="regForm" method="post" action="../static/functions/auth/login.php" enctype="multipart/form-data">
                    <!--Body-->
                    <div class="card-body">
                        <?php   if (isset($_SESSION['error'])) {
                                    echo '<div class="alert alert-danger" role="alert">'. $_SESSION['error'] .'</div>';
                                    $_SESSION['error'] = null;
                                } ?>
                        <div class="md-form form-sm mt-2 mb-0">
                            <i class="fas fa-id-badge prefix"></i>
                            <input type="text" name="register_username" id="register_username"
                                class="form-control form-control-sm validate mt-0 mb-0" required>
                            <label for="register_username">ชื่อผู้ใช้งาน / Username*</label>
                        </div>
                        <div class="md-form form-sm mt-3 mb-0">
                            <i class="fas fa-key prefix"></i>
                            <input type="password" name="register_password" id="register_password"
                                class="form-control form-control-sm validate mt-0 mb-0" required>
                            <label for="register_password">รหัสผ่าน / Password*</label>
                        </div>
                        <div class="md-form form-sm mt-3 mb-0">
                            <i class="fas fa-envelope prefix"></i>
                            <input type="email" name="register_email" id="register_email"
                                class="form-control form-control-sm validate mt-0 mb-0" required>
                            <label for="register_email">Email*</label>
                        </div>
                        <div class="md-form form-sm mt-3 mb-0">
                            <div class="form-row">
                                <div class="col">
                                    <input type="text" id="register_firstname" name="register_firstname"
                                        class="form-control form-control-sm validate mb-0 mt-0" required>
                                    <label for="register_firstname">ชื่อ / Firstname*</label>
                                </div>
                                <div class="col">
                                    <input type="text" id="register_lastname" name="register_lastname"
                                        class="form-control form-control-sm validate mb-0 mt-0" required>
                                    <label for="register_lastname">สกุล / Lastname*</label>
                                </div>
                            </div>
                        </div>
                        <div class="h-captcha text-center mt-3 mb-1"  data-sitekey="2b89c100-ff96-44d3-9f2c-8cc8fb4a5a79"></div>
                        <div class="d-flex justify-content-between">
                            <div class="mr-auto align-self-center"><a href="../auth/login" class="text-md">ล็อกอิน</a>
                            </div>
                            <div class="ml-auto">
                                <input class="btn btn-success btn-md" type="submit" name="register_submit" value="สมัคร"></input>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <?php require_once '../static/functions/popup.php'; ?>
    <div class="d-none">
    <?php require_once '../static/functions/footer.php'; ?>
    </div>
    <script>
        document.querySelector("#regForm").addEventListener("submit", function(event) {
            var hcaptchaVal = document.querySelector('[name="h-captcha-response"]').value;
            if (hcaptchaVal === "") {
                event.preventDefault();
                swal("Oops","Please complete captcha!", "error");
            }
        });
    </script>
    <script>
        $('.datepicker').pickadate({
            format: 'yyyy-mm-dd',
            formatSubmit: 'yyyy-mm-dd 00:00:00',
            max: new Date()
        });
    </script>
</body>

</html>