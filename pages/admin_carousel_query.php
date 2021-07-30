    <div class="alert alert-warning"><text class='font-weight-bold'>คำเตือน:</text> ข้อมูลจะถูกบันทึกทีละส่วน ไม่สามารถบันทึกพร้อมกันทั้งหมดได้</div>
<?php 
    require_once '../static/functions/function.php';

    $carousel_path = "../static/elements/carousel/banner/";
    if (!file_exists($carousel_path)) make_directory($carousel_path); //First time load carousel (In case of didn't run mkdir.php)

    $carousel_file = glob($carousel_path . "*.{jpg,png,gif,PNG,JPG,GIF,JPEG,jpeg}", GLOB_BRACE);
    $carousel_count = count($carousel_file);

    for ($carousel_item = 0; $carousel_item < $carousel_count; $carousel_item++) { 
        $carousel_picture_name = pathinfo($carousel_file[$carousel_item], PATHINFO_FILENAME);
        $carousel_picture_ext = pathinfo($carousel_file[$carousel_item], PATHINFO_EXTENSION);
        $carousel_picture_text = readTxt2("$carousel_path$carousel_picture_name.$carousel_picture_ext.txt");
    ?>
    <form action="../pages/admin_carousel_save.php?name=<?php echo "$carousel_picture_name.$carousel_picture_ext";?>" method="post" enctype="multipart/form-data" id="carousel_form_<?php echo $carousel_item; ?>" name="carousel_form_<?php echo $carousel_item; ?>">
        <div class="row mb-3">
            <div class="col-12 col-md-4">
                <img src="<?php echo $carousel_file[$carousel_item]; ?>" class="w-100" id="carousel_<?php echo $carousel_item; ?>" name="carousel_<?php echo $carousel_item; ?>"/>
                <input type="file" accept="image/png, image/jpeg, image/gif" id="carousel_file_<?php echo $carousel_item;?>" name="carousel_file" class="mt-1">
            </div>
            <div class="col-12 col-md-8">
                <script>
                    document.getElementById("carousel_file_<?php echo $carousel_item;?>").onchange = function () {
                        var reader = new FileReader();
                        reader.onload = function (e) {
                            document.getElementById("carousel_<?php echo $carousel_item;?>").src = e.target.result;
                        };
                        reader.readAsDataURL(this.files[0]);
                    };
                </script>
                <h4 class="card-title"><input type="text" placeholder="หัวข้อ" class="form-control mr-sm-3" value="<?php echo $carousel_picture_text; ?>" id="carouselTitle" name="cTitle" required></input></h4>
                <div class="mb-1 mt-3 d-flex justify-content-between">
                    <div class="flex-grow-1"></div>
                    <a class="btn-floating btn-sm btn-success z-depth-0 ml-0 mr-1" onclick="checkForm('carousel_form_<?php echo $carousel_item; ?>')"><i class='fas fa-save'></i></a>
                    <a class="btn-floating btn-sm btn-danger z-depth-0 ml-0 mr-1" onclick='swal({title: "ลบรูปนี้หรือไม่",text: "หลังจากที่ลบแล้ว จะไม่สามารถกู้คืนได้!",icon: "warning",buttons: true,dangerMode: true}).then((willDelete) => { if (willDelete) { window.location = "../pages/admin_carousel_delete.php?target=<?php echo "$carousel_picture_name.$carousel_picture_ext"; ?>";}});'><i class="fas fa-trash"></i></a>
                </div>
            </div>
        </div>
        <hr>
    </form>
    <?php } ?>


    <form action="../pages/admin_carousel_save.php" method="post" enctype="multipart/form-data" id="carousel_form_new" name="carousel_form_new">
        <div class="row mb-3">
            <div class="col-12 col-md-4">
                <img src="../static/elements/1920x1080.jpg" class="w-100" id="carousel-preview" />
                <input type="file" accept="image/png, image/jpeg, image/gif" id="carousel_file_new" name="carousel_file" class="mt-1" required>
            </div>
            <div class="col-12 col-md-8">
                <script>
                    document.getElementById("carousel_file_new").onchange = function () {
                        var reader = new FileReader();
                        reader.onload = function (e) {
                            document.getElementById("carousel-preview").src = e.target.result;
                        };
                        reader.readAsDataURL(this.files[0]);
                    };
                </script>
                <h4 class="card-title"><input type="text" placeholder="หัวข้อ" class="form-control mr-sm-3" value="" id="carouselTitle" name="cTitle" required></input></h4>
                <div class="mb-1 mt-3 d-flex justify-content-between">
                    <div class="flex-grow-1"></div>
                    <a class="btn-floating btn-sm btn-success z-depth-0 ml-0 mr-1" onclick="checkForm('carousel_form_new')"><i class='fas fa-save'></i></a>
                </div>
            </div>
        </div>
    </form>
    <script>
    function checkForm(formName) {
        if (document.getElementById(formName).checkValidity()) {
            document.getElementById(formName).submit();
        } else {
            document.getElementById(formName).classList.add('was-validated');
        }
    }
    </script>