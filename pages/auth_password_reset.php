<?php require_once '../static/functions/connect.php'; ?>
<!DOCTYPE html>
<html lang="th" prefix="og:http://ogp.me/ns#">
<head><?php require_once '../static/functions/head.php'; ?></head>
<body>
    <?php require_once '../static/functions/navbar.php'; ?>
    <?php if (!isset($_SESSION['allowAccessResetpasswordPage']) || $_SESSION['allowAccessResetpasswordPage'] == false) back(); ?>
    <div class="container" id="container" style="padding-top: 88px; min-height:100vh;">
        <div class="center">
            <form id="resetForm" method="post" action="../pages/auth_password_resetpass.php" enctype="multipart/form-data">
                <h1 class="display-5 font-weight-bold text-center text-md">Setting New Password <i class="fas fa-lock"></i></h1>
                <div class="card">
                    <!--Body-->
                    <div class="card-body mb-1">
                        <?php if (isset($_SESSION['error'])) {echo '<div class="alert alert-danger" role="alert">'. $_SESSION['error'] .'</div>'; $_SESSION['error'] = null;} ?>
                        <div class="md-form form-sm mb-2 mt-0">
                            <i class="fas fa-key prefix"></i>
                            <input type="password" name="setNewPassword" id="setNewPassword"
                                class="form-control form-control-sm validate mt-0 mb-0" required>
                            <label for="setNewPassword">รหัสผ่านใหม่</label>
                        </div>
                        <div class="h-captcha text-center" data-sitekey="2b89c100-ff96-44d3-9f2c-8cc8fb4a5a79"></div>
                        <div class="d-flex justify-content-between mb-0">
                            <div class="align-self-center">
                                <a href="../auth/login" class="text-md">ล็อกอิน</a> | <a href="../auth/register" class="text-danger">สมัครบัญชีผู้ใช้ใหม่</a>
                            </div>
                            <div>
                                <input class="btn btn-success btn-md mr-0 ml-0" type="submit" name="resetPassword" value="รีเซ็ต"></input>
                            </div>
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