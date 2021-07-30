<?php require_once '../static/functions/connect.php'; ?>
<!DOCTYPE html>
<html lang="th" prefix="og:http://ogp.me/ns#">
<head><?php require_once '../static/functions/head.php'; ?></head>
<body>
    <nav class="d-none" id="nav" role="navigation">
        <?php require_once '../static/functions/navbar.php'; ?>
    </nav>
    <?php if (isLogin()) home(); ?>
    <div class="d-flex justify-content-center">
        <div class="container" id="container" style="padding-top: 5vh; width: 23rem;">
            <form id="loginForm" method="post" action="../static/functions/auth/login.php" enctype="multipart/form-data">
                <h1 class="display-5 text-center text-kku font-weight-bold">LOGIN <i class="fas fa-sign-in-alt"></i></h1>
                <div class="card">
                    <!--Body-->
                    <div class="card-body">
                        <?php if (isset($_SESSION['error'])) {echo '<div class="alert alert-danger" role="alert">'. $_SESSION['error'] .'</div>'; $_SESSION['error'] = null;} ?>
                        <div class="md-form form-sm mb-0">
                            <i class="fas fa-user prefix text-kku"></i>
                            <input type="text" name="login_username" id="login_username"
                                class="form-control form-control-sm validate" required>
                            <label for="login_username">Username</label>
                        </div>
                        <div class="md-form form-sm mb-0">
                            <i class="fas fa-lock prefix text-kku"></i>
                            <input type="password" name="login_password" id="login_password"
                                class="form-control form-control-sm validate" required>
                            <label for="login_password">Password</label>
                        </div>
                        <div class="h-captcha mb-2 text-center" data-sitekey="2b89c100-ff96-44d3-9f2c-8cc8fb4a5a79"></div>
                        <input type="hidden" name="method" value="loginPage">
                        <button class="btn btn-c-kku btn-block" type="submit" name="login_submit" value="ล็อกอิน">ล็อกอิน</button>
                        <div class="text-center mt-2">
                            <a href="../auth/forgetpassword" class="text-danger">ลืมรหัสผ่าน</a> | <a href="../auth/register" class="text-kku">สมัครบัญชีผู้ใช้ใหม่</a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <script>
        document.querySelector("#loginForm").addEventListener("submit", function(event) {
            var hcaptchaVal = document.querySelector('[name="h-captcha-response"]').value;
            if (hcaptchaVal === "") {
                event.preventDefault();
                swal("Oops","Please complete captcha!", "error");
            }
        });
    </script>
    <?php require_once '../static/functions/popup.php'; ?>
    <div class="d-none">
    <?php require_once '../static/functions/footer.php'; ?>
    </div>
</body>
</html>
