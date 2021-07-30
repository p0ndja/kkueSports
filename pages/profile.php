<?php 
    require_once '../static/functions/connect.php';
?>

<?php if (!isLogin()) header("Location: ../"); $id = $_SESSION['user']->getID(); ?>

<!DOCTYPE html>
<html lang="th" prefix="og:http://ogp.me/ns#">

<head>
    <?php require_once '../static/functions/head.php'; ?>
</head>
<body>
    <?php require_once '../static/functions/navbar.php'; ?>
    <div class="container">
        <div class="card mb-3">
            <form method="post" action="../saveProfile/" enctype="multipart/form-data" id="userEditForm">
                <input type="hidden" id="id" name="id" value="<?php echo $id; ?>">
                <div class="card-body">
                    <div class="row">
                        <div class="col-12 col-md-4 mb-3">
                            <img class="img-fluid w-100" src="<?php echo $_SESSION['user']->getProfile(); ?>" id="profile_preview">
                            <input type="file" name="profile_upload" id="profile_upload"
                                    class="form-control-file validate mt-1 mb-1" accept="image/png, image/jpeg">
                            <input type="hidden" id="profile_final" name="profile_final" value="<?php echo $profile_image; ?>">
                            <button type="submit" class="btn btn-success btn-block btn-lg font-weight-bold text-dark">บันทึก</button>
                        </div>
                        <div class="col-12 col-md-8">
                            <!-- Personal Zone -->
                            <h4 class="font-weight-bold">ข้อมูลส่วนตัว - Information <i
                                    class="fas fa-info-circle"></i></h4>
                            <hr>
                            <!-- name -->
                            <div class="form-inline mb-2 mt-0">
                                <div class="md-form input-group mb-0 mt-0">
                                    <div class="input-group-prepend mb-0 mt-0">
                                        <span class="input-group-text md-addon font-weight-bold">ชื่อ</span>
                                    </div>
                                    <input type="text" class="form-control mr-sm-3" id="firstname"
                                        name="firstname"
                                        placeholder="<?php echo $_SESSION['user']->getFirstname(); ?>"
                                        value="<?php echo $_SESSION['user']->getFirstname(); ?>">
                                </div>
                                <div class="md-form input-group mb-0 mt-0">
                                    <div class="input-group-prepend mb-0 mt-0">
                                        <span class="input-group-text md-addon font-weight-bold">สกุล</span>
                                    </div>
                                    <input type="text" class="form-control mr-sm-3" id="lastname"
                                        name="lastname"
                                        placeholder="<?php echo $_SESSION['user']->getLastname(); ?>"
                                        value="<?php echo $_SESSION['user']->getLastname(); ?>">
                                </div>
                            </div>
                            <!-- name -->                            

                            <!-- Security Zone -->
                            <h4 class="mt-5 font-weight-bold">ความปลอดภัย - Security <i class="fas fa-lock"></i>
                            </h4>
                            <hr>
                            <!-- Email -->
                            <div class="md-form input-group mt-0 mb-2">
                                <div class="input-group-prepend mt-0 mb-0">
                                    <span class="input-group-text md-addon font-weight-bold">อีเมล</span>
                                </div>
                                <input type="hidden" id="real_email" name="real_email" value="<?php echo $_SESSION['user']->getEmail(); ?>">
                                <input type="text" class="form-control mr-sm-3" id="email" name="email"
                                    placeholder="<?php echo $_SESSION['user']->getEmail(); ?>"
                                    value="<?php echo $_SESSION['user']->getEmail(); ?>" required>
                            </div>
                            <!-- Email -->
                            <!-- Password -->
                            <div class="md-form input-group mt-0 mb-0">
                                <div class="input-group-prepend mb-0">
                                    <span class="input-group-text md-addon font-weight-bold">เปลี่ยนรหัสผ่าน</span>
                                </div>
                            </div>
                            <div class="md-form input-group mb-0 mt-0">
                                <div class="input-group-prepend mb-0 mt-0">
                                    <span class="input-group-text md-addon mb-0 mt-0 ml-5">รหัสผ่านใหม่</span>
                                </div>
                                <input type="text" class="form-control mr-sm-3" id="password" name="password" value="">
                            </div>
                            <div class="md-form input-group mb-0 mt-0">
                                <div class="input-group-prepend mb-0 mt-0">
                                    <span class="input-group-text md-addon mb-0 mt-0">รหัสผ่านใหม่อีกครั้ง</span>
                                </div>
                                <input type="text" class="form-control mr-sm-3" id="newPassword" name="newPassword" value="">
                            </div>
                            <small id="al" class="text-danger text-center" style="display: none;">โปรดตรวจสอบความถูกต้อง</small>
                            <!-- Password -->
                            <!-- Security Zone -->
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div id="uploadimageModal" class="modal" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Upload & Crop Image</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col text-center">
                            <div id="image_demo" style="width:100%; margin-top:30px"></div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-success crop_image">Crop & Upload Image</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>


    <?php require_once '../static/functions/popup.php'; ?>
    <?php require_once '../static/functions/footer.php'; ?>
    <script>
        var pw = document.getElementById("password");
        var pwc = document.getElementById("newPassword");
        var al = document.getElementById("al");
        var validPassword = true;
        pwc.addEventListener("change", function() {
            if (pw.value !== "") {
                if (pw.value != pwc.value) {
                    pw.classList.add("invalid");
                    pwc.classList.add("invalid");
                    pw.classList.remove("valid");
                    pwc.classList.remove("valid");
                    al.style.display = "block";
                    validPassword = false;
                } else {
                    pw.classList.add("valid");
                    pwc.classList.add("valid");
                    pw.classList.remove("invalid");
                    pwc.classList.remove("invalid");
                    al.style.display = "none";
                    validPassword = true;
                }
            }
        });
        document.querySelector("#userEditForm").addEventListener("submit", function(event) {
            if (!validPassword) {
                event.preventDefault();
                swal("Oops","โปรดตรวจสอบรหัสผ่านที่กรอกอีกครั้ง", "error");
            }
        });
    </script>
    <script>

        $(document).ready(function () {
            $image_crop = $('#image_demo').croppie({
                enableExif: true,
                viewport: {
                    width: 325,
                    height: 325,
                    type: 'square' //circle
                },
                boundary: {
                    width: 333,
                    height: 333
                }
            });

            $('#profile_upload').on('change', function () {
                var reader = new FileReader();
                reader.onload = function (event) {
                    $image_crop.croppie('bind', {
                        url: event.target.result
                    }).then(function () {
                        console.log('jQuery bind complete');
                    });
                }
                reader.readAsDataURL(this.files[0]);
                $('#uploadimageModal').modal('show');
            });

            $('.crop_image').click(function (event) {
                $image_crop.croppie('result', {
                    type: 'canvas',
                    size: 'viewport'
                }).then(function (response) {
                    $.ajax({
                        url: "../pages/profile_upload.php",
                        type: "POST",
                        data: {
                            "userID": <?php echo $id; ?>,
                            "image": response
                        },
                        success: function (data) {
                            $('#uploadimageModal').modal('hide');
                            $('#profile_preview').attr('src',data);
                            $('#profile_final').val(data);
                            console.log($('#profile_final').val());
                        }
                    });
                })
            });

        });
        $("input[type=radio]").change(function () {
            if (this.id == "student") {
                $('#studentZone').css('display', 'block');
            } else {
                $('#studentZone').css('display', 'none');
            }
        });

        document.getElementById("profile_upload").onchange = function () {
            var reader = new FileReader();
            reader.onload = function (e) {
                document.getElementById("profile_preview").src = e.target.result;
            };
            reader.readAsDataURL(this.files[0]);
        };
    </script>
</body>

</html>