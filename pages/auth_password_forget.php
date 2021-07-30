<?php require_once '../static/functions/connect.php'; ?>
<!DOCTYPE html>
<html lang="th" prefix="og:http://ogp.me/ns#">
<head><?php require_once '../static/functions/head.php'; ?></head>
<body>
    <?php require_once '../static/functions/navbar.php'; ?>
    <?php if (isLogin()) home(); ?>
    <div class="container" id="container">
        <div class="center">
            <form id="resetForm" method="post" action="../pages/auth_password_lookup.php" enctype="multipart/form-data">
                <h1 class="display-5 font-weight-bold text-center text-md">Reset Password</h1>
                <div class="card">
                    <!--Body-->
                    <div class="card-body mb-1">
                        <?php if (isset($_SESSION['error'])) {echo '<div class="alert alert-danger" role="alert">'. $_SESSION['error'] .'</div>'; $_SESSION['error'] = null;} ?>
                        <h6 class="text-center">ส่งคำร้องรีเซ็ตรหัสผ่าน</h6>
                        <div class="md-form form-sm mb-2">
                            <i class="fas fa-users prefix"></i>
                            <input type="email" name="reset" id="reset"
                                class="form-control form-control-sm validate mb-2" required placeholder="กรุณาใส่อีเมลที่คุณใช้สมัครบัญชีผู้ใช้">
                            <label for="reset">รีเซ็ตรหัสผ่าน</label>
                        </div>
                        <div class="h-captcha text-center mb-1" data-sitekey="2b89c100-ff96-44d3-9f2c-8cc8fb4a5a79"></div>
                        <button class="btn btn-success btn-md btn-block" type="submit" name="resetPassword" value="รีเซ็ตรหัสผ่าน">รีเซ็ตรหัสผ่าน</button>
                        <div class="text-center">
                            <a href="../auth/login" class="text-md">ล็อกอิน</a> | <a href="../auth/register" class="text-danger">สมัครบัญชีผู้ใช้ใหม่</a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <script>
        document.querySelector("#resetForm").addEventListener("submit", function(event) {
            var hcaptchaVal = document.querySelector('[name="h-captcha-response"]').value;
            if (hcaptchaVal === "") {
                event.preventDefault();
                swal("Oops","Please complete captcha!", "error");
            }
        });
    </script>
    <?php require_once '../static/functions/popup.php'; ?>
    <div class='d-none'>
    <?php require_once '../static/functions/footer.php'; ?>
    </div>
</body>

</html>