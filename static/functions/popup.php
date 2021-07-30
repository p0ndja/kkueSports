<!-- Announcement Modal -->
<!--
<div class="modal animated jackInTheBox fadeOut" id="announcementPopup" name="announcementPopup" tabindex="-1"
    role="dialog" aria-labelledby="announcementTitle" aria-hidden="true">
    <div class="modal-dialog modal-notify modal-warning modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="annoucementTitle">ข่าวประชาสัมพันธ์</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <img src="https://repository-images.githubusercontent.com/216790969/da52a000-7792-11ea-997b-7503371435f0"
                    class="img-fluid w-100 d-flex justify-content-center mb-3 z-depth-2">
                <div class="modal-text">
                    <p class="text-center">ทางผู้พัฒนาขอความร่วมมือจากผู้เข้าชมเว็บไซต์ทุก ๆ ท่าน
                        ร่วมตอบแบบสอบถามความพึงพอใจในการใช้งานเว็บไซต์ <a
                            href="https://smd.pondja.com">smd.pondja.com</a> / <a
                            href="https://smd.p0nd.ga">smd.p0nd.ga</a></p>
                    <a href="https://forms.gle/HfxaWmjVGKjARUR18" target="_blank" class="text-center text-md">
                        <h1 class="animated infinite pulse">ตอบแบบสอบถาม</h1>
                    </a>
                </div>
            </div>
            <div class="modal-footer">
                <a class="btn btn-md btn-warning" data-dismiss="modal">ปิดหน้าต่าง</a>
            </div>
        </div>
    </div>
</div>
-->
<!-- Mobile Cpanel Modal -->
<!-- Popup Modal -->
<div class="modal animated fade" id="modalPopup" name="modalPopup" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-notify modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitle"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="modalBodyBody">
                <div id="modalBody"></div>
            </div>
        </div>
    </div>
</div>
<!-- Popup Modal -->

<!-- Popup Modal -->
<div class="modal animated fade" id="modalPopupXL" name="modalPopupXL" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-notify modal-md modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitleXL"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="modalBodyBodyXL">
                <div id="modalBodyXL"></div>
            </div>
        </div>
    </div>
</div>
<!-- Popup Modal -->

<!-- Login Modal -->
<div class="modal animated fade" id="loginModal" name="loginModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-notify modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Authentication</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="post" action="../static/functions/auth/login.php" enctype="multipart/form-data">
                <h1 class="display-5 text-center text-md font-weight-bold">LOGIN <i class="fas fa-sign-in-alt"></i></h1>
                    <!--Body-->
                        <?php if (isset($_SESSION['error'])) {echo '<div class="alert alert-danger" role="alert">'. $_SESSION['error'] .'</div>'; $_SESSION['error'] = null;} ?>
                        <div class="md-form form-sm">
                            <i class="fas fa-user prefix text-md"></i>
                            <input type="text" name="login_username" id="login_username"
                                class="form-control form-control-sm validate mb-2" required>
                            <label for="login_username">Username</label>
                        </div>
                        <div class="md-form form-sm">
                            <i class="fas fa-lock prefix text-md"></i>
                            <input type="password" name="login_password" id="login_password"
                                class="form-control form-control-sm validate mb-2" required>
                            <label for="login_password">Password</label>
                        </div>
                        <input type="hidden" name="method" value="loginPage">
                        <button class="btn btn-c-md btn-block" type="submit" name="login_submit" value="ล็อกอิน">ล็อกอิน</button>
                    <!--Footer-->                
                </form>
                <div class="text-center mt-2"><a href="../auth/forgetpassword" class="text-danger">ลืมรหัสผ่าน</a> | <a href="../auth/register" class="text-md">สมัครบัญชีผู้ใช้ใหม่</a></div>
            </div>
        </div>
    </div>
</div>
<!-- Login Modal -->

<!-- Webstats Modal -->
<div class="modal animated fade" id="webstatsModal" name="webstatsModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-notify modal-md modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">สถิติเว็บไซต์</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <?php include '../vendor/stats/counter.php'; ?>
            </div>
        </div>
    </div>
</div>
<!-- Webstats Modal -->

<!-- Modal -->
<div class="modal fade" id="picPreviewModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <img class="imagepreview img img-fluid w-100" src="">
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(function() {
        $('.pop').on('click', function() {
            $('.imagepreview').attr('src', $(this).find('img').attr('src'));
        });
    });
</script>
<script>
    function VDOModal() {
        $('#modalTitle').html('แก้ไขรายการวิดีโอแนะนำ');
        $('#modalBody').html('');
        $.ajax({
            type: 'GET',
            url: '../pages/admin_vdo_query.php',
            success: function (data) {
                $('#modalBody').html(data);
            }
        });
    }

    function CarouselModal() {
        $('#modalTitleXL').html('แก้ไขรูปหน้าปก');
        $('#modalBodyXL').html('');
        $.ajax({
            type: 'GET',
            url: '../pages/admin_carousel_query.php',
            success: function (data) {
                $('#modalBodyXL').html(data);
            }
        });
    }
</script>
<!-- Mobile Cpanel Modal -->


<?php 
    if (isset($_SESSION['swal_error']) && isset($_SESSION['swal_error_msg'])) { 
        errorSwal($_SESSION['swal_error'],$_SESSION['swal_error_msg']);
        $_SESSION['swal_error'] = null;
        $_SESSION['swal_error_msg'] = null;
    }
?>
<?php 
    if (isset($_SESSION['swal_warning']) && isset($_SESSION['swal_warning_msg'])) { 
        warningSwal($_SESSION['swal_warning'],$_SESSION['swal_warning_msg']);
        $_SESSION['swal_warning'] = null;
        $_SESSION['swal_warning_msg'] = null;
    }
?>
<?php 
    if (isset($_SESSION['swal_success'])) { 
        successSwal($_SESSION['swal_success'],$_SESSION['swal_success_msg']);
        $_SESSION['swal_success'] = null;
        $_SESSION['swal_success_msg'] = null;
    }
?>
<script>
    $("#logoutBtn").click(function () {
        swal({
            title: "ออกจากระบบ ?",
            text: "คุณต้องการออกจากระบบหรือไม่?",
            icon: "warning",
            buttons: true,
            dangerMode: true
        }).then((willDelete) => {
            if (willDelete) {
                window.location = "../auth/logout";
            }
        });
    });
</script>