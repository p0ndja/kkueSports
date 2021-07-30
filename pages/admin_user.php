<?php require_once '../static/functions/connect.php'; ?>
<!DOCTYPE html>
<html lang="th" prefix="og:http://ogp.me/ns#">
<head><?php require_once '../static/functions/head.php'; ?></head>
<body>
    <?php require_once '../static/functions/navbar.php'; ?>
    <?php needAdmin(); ?>
    <div class="container">
        <div class="card mb-3 card-body">
            <select class="mdb-select" searchable="กรุณาใส่ข้อมูล..." id="user_query" name="user_query">
                <option value="" disabled selected>กรุณาเลือก</option>
                <?php
                    if ($stmt = $conn->prepare("SELECT `id`,`firstname`,`lastname`,`profile` FROM `user`")) {
                        $stmt->execute();
                        $result = $stmt->get_result();
                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) { ?>
                                <option value="<?php echo $row['id']; ?>" data-icon="<?php echo (empty($row['profile']) ? "../static/elements/user.svg" : $row['profile']); ?>" class="rounded-circle">
                                    <?php echo $row['firstname'] . " " . $row['lastname'] . ' (' . $row['id'] . ')' ; ?>
                                </option>
                            <?php }
                        }
                    } 
                ?>
            </select>
        </div>
        <?php if (isset($_GET['id'])) { 
            $id = (int) $_GET['id'];
            $user = new User($id);
            if ($user->getID() == -1) header("Location: ../admin/user");
            ?>
            <div class="card mb-3">
                <form method="post" action="../pages/admin_user_save.php?id=<?php echo $id; ?>" enctype="multipart/form-data" id="userEditForm">
                    <input type="hidden" id="real_id" name="real_id" value="<?php echo $id; ?>">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12 col-md-4 mb-3">
                                <img class="img-fluid w-100" src="<?php echo $user->getProfile(); ?>" id="profile">
                                <input type="file" name="profile_upload" id="profile_upload"
                                        class="form-control-file validate mt-1 mb-1" accept="image/png, image/jpeg">
                                <h1 class="display-4 text-center">
                                    ID: <?php echo $id; ?>
                                </h1>
                                <a class="btn btn-success btn-block btn-lg" href="javascript:{}"
                                    onclick="document.getElementById('userEditForm').submit();">บันทึกข้อมูล <i
                                        class="fas fa-save"></i></a>
                            </div>
                            <div class="col-12 col-md-8">
                                <!-- Personal Zone -->
                                <h4 class="font-weight-bold">ข้อมูลส่วนตัว - Information <i class="fas fa-info-circle"></i></h4>
                                <hr>
                                <!-- name -->
                                <div class="form-inline">
                                    <div class="md-form input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text md-addon">ชื่อ</span>
                                        </div>
                                        <input type="text" class="form-control mr-sm-3" id="firstname"
                                            name="firstname"
                                            placeholder="<?php echo $user->getFirstname(); ?>"
                                            value="<?php echo $user->getFirstname(); ?>">
                                    </div>
                                    <div class="md-form input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text md-addon">สกุล</span>
                                        </div>
                                        <input type="text" class="form-control mr-sm-3" id="lastname"
                                            name="lastname"
                                            placeholder="<?php echo $user->getLastname(); ?>"
                                            value="<?php echo $user->getLastname(); ?>">
                                    </div>
                                </div>
                                
                                <!-- Personal Zone -->

                                <!-- Security Zone -->
                                <h4 class="mt-5 font-weight-bold">ความปลอดภัย - Security <i class="fas fa-lock"></i>
                                </h4>
                                <!-- Username -->
                                <div class="md-form input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text md-addon">ผู้ใช้งาน</span>
                                    </div>
                                    <input type="text" class="form-control mr-sm-3" id="username" name="username"
                                        placeholder="<?php echo $user->getUsername(); ?>"
                                        value="<?php echo $user->getUsername(); ?>" required>
                                </div>
                                <!-- Username -->
                                <!-- Email -->
                                <div class="md-form input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text md-addon">อีเมล</span>
                                    </div>
                                    <input type="text" class="form-control mr-sm-3" id="email" name="email"
                                        placeholder="<?php echo $user->getEmail(); ?>"
                                        value="<?php echo $user->getEmail(); ?>" required>
                                </div>
                                <!-- Email -->
                                <!-- Password -->
                                <div class="md-form input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text md-addon">รหัสผ่าน</span>
                                    </div>
                                    <input type="text" class="form-control mr-sm-3" id="password" name="password"
                                        placeholder="พิมพ์รหัสผ่านเพื่อตั้งรหัสผ่านใหม่... (การเว้นว่างจะถือว่าใช้รหัสผ่านเดิม)"
                                        value="">
                                </div>
                                <!-- Password -->
                                <!-- Security Zone -->

                                <!-- Status Zone -->
                                <!-- role -->
                                <!-- Group of material radios - option 1 -->
                                <h4 class="mt-5 font-weight-bold">สถานะ - Role <i class="fas fa-user-tag"></i></h4>
                                <hr>
                                <div class="form-check">
                                    <input type="radio" class="form-check-input" id="admin" name="role"
                                        <?php if ($user->isAdmin()) echo "checked"; ?> value="admin">
                                    <label class="form-check-label" for="admin">แอดมิน
                                        <a class="material-tooltip-main" data-html="true" data-toggle="tooltip"
                                            title="✔ เข้าถึงเมนูจัดการของแอดมิน<br>✔ แก้ไขโพสต์"><i
                                                class="fas fa-info-circle"></i></a>
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input type="radio" class="form-check-input" id="guest" name="role"
                                        <?php if (!$user->isAdmin()) echo "checked"; ?> value="guest">
                                    <label class="form-check-label" for="guest">ผู้เยี่ยมชม
                                        <a class="material-tooltip-main" data-html="true" data-toggle="tooltip"
                                            title="❌ เข้าถึงเมนูจัดการของแอดมิน<br>❌ แก้ไขโพสต์"><i
                                                class="fas fa-info-circle"></i></a>
                                    </label>
                                </div>
                                <!-- role -->
                                <!-- Status Zone -->

                                <!-- Delete Zone -->
                                <h4 class="mt-5 font-weight-bold">ลบแอคเค้าท์ - Delete Account <i
                                        class="fas fa-trash-alt"></i>
                                </h4>
                                <hr>

                                <a class="btn btn-outline-danger btn-lg" href="javascript:{}"
                                    onclick='swal({title: "ลบผู้ใช้นี้หรือไม่ ?",text: "หลังจากที่ลบแล้ว ข่าวนี้จะไม่สามารถกู้คืนได้!",icon: "warning",buttons: true,dangerMode: true}).then((willDelete) => { if (willDelete) { window.location = "../pages/admin_user_delete.php?id=<?php echo $id; ?>";}});''>ยืนยันการลบผู้ใช้นี้ <u><b>!! ไม่สามารถกู้คืนได้ !!</b></u></a>

                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            <?php } ?>
    </div>
    

    <?php require_once '../static/functions/popup.php'; ?>
    <?php require_once '../static/functions/footer.php'; ?>
    <script>
        $("#user_query").on('change', function (e) {
            console.log("s");
            window.location = "../admin/user-" + $("#user_query").val();
        });

        $('#user_query <?php if (isset($_GET['id']))echo "option[value=" . $_GET['id'] .']'; ?>').attr('selected', 'selected');

        document.getElementById("profile_upload").onchange = function () {
            var reader = new FileReader();
            reader.onload = function (e) {
                document.getElementById("profile").src = e.target.result;
            };
            reader.readAsDataURL(this.files[0]);
        };
    </script>
</body>

</html>