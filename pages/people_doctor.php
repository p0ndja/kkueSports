<?php require_once '../static/functions/connect.php'; ?>
<!DOCTYPE html>
<html lang="th" prefix="og:http://ogp.me/ns#">

<head><?php require_once '../static/functions/head.php'; ?></head>

<?php $isAdmin = isAdmin(); ?>

<body>
    <?php require_once '../static/functions/navbar.php'; ?>
    <div class="container">
        <h2 class="font-weight-bold text-md">คณาจารย์ปัจจุบัน</h2>
        <div class="row mb-3">
            <div class="col-md-3">
                <div class="card mb-3">
                    <ul class="nav md-pills pills-success flex-column" role="tablist">
                        <?php
                            $fol = glob("../static/elements/people/doctor/*", GLOB_ONLYDIR);
                            for($f = 0; $f < count($fol); $f++) {
                                $folder_name = pathinfo($fol[$f], PATHINFO_FILENAME); ?>
                                <li class="nav-item">
                                    <a id="pill_<?php echo str_replace(" ", "_", pathinfo($fol[$f], PATHINFO_FILENAME)); ?>" class="nav-link text-left <?php if ($f == 0) echo 'active'; ?>" data-toggle="tab" href="#<?php echo str_replace(" ", "_", pathinfo($fol[$f], PATHINFO_FILENAME)); ?>" role="tab" onclick="window.location.hash = '<?php echo $folder_name;?>'">
                                        <div class="d-flex">
                                            <div class="flex-grow-1">
                                                <?php echo $folder_name; ?>
                                            </div>
                                            <?php if ($isAdmin) { ?>
                                            <i class="fas fa-pencil-alt mt-1 text-warning mr-1" onclick="editCategory('<?php echo $fol[$f]; ?>','<?php echo $folder_name; ?>')"></i>
                                            <i class="fas fa-trash mt-1 text-danger" onclick="myDeleteFolderFunction('<?php echo $fol[$f]; ?>');"></i>
                                            <?php } ?>
                                        </div>
                                    </a>
                                </li>      
                            <?php }
                        ?>
                        <?php if ($isAdmin) {?>
                        <li class="nav-item">
                            <a class="nav-link text-left" onclick="createNewCategory();">เพิ่มหมวดหมู่ใหม่ <i class="fas fa-plus ml-auto"></i></a>
                            <script>
                                function createNewCategory() {
                                    var folderName = prompt("กรุณาระบุชื่อหมวดหมู่ ห้ามใช้อักขระดังต่อไปนี้ \\ / : * ? \" < > |");
                                    if (folderName != null && folderName != "") {
                                        window.location = "../pages/people_IO.php?function=create&top=doctor&mkdir=" + folderName;
                                    }
                                }

                                function editCategory(targetFolder, tempOldName) {
                                    var folderName = prompt("กรุณาระบุชื่อหมวดหมู่ใหม่ ห้ามใช้อักขระดังต่อไปนี้ \\ / : * ? \" < > |", tempOldName);
                                    if (folderName != null && folderName != "") {
                                        window.location = "../pages/people_IO.php?function=rename&old=" + targetFolder + "&new=" + folderName;
                                    }
                                }

                                function myDeleteFileFunction(f) {
                                    swal({
                                        title: "ลบรูปนี้หรือไม่",
                                        text: "หลังจากที่ลบแล้ว จะไม่สามารถกู้คืนได้!",
                                        icon: "warning",
                                        buttons: true,
                                        dangerMode: true
                                    }).then((willDelete) => {
                                        if (willDelete) {
                                            window.location = "../pages/people_IO.php?function=delete&method=file&name=" + f;
                                        }
                                    });
                                }

                                function myDeleteFolderFunction(f) {
                                    swal({
                                        title: "ลบหมวดหมู่นี้หรือไม่",
                                        text: "หลังจากที่ลบแล้ว จะไม่สามารถกู้คืนได้!",
                                        icon: "warning",
                                        buttons: true,
                                        dangerMode: true
                                    }).then((willDelete) => {
                                        if (willDelete) {
                                            window.location = "../pages/people_IO.php?function=delete&method=dir&name=" + f;
                                        }
                                    });
                                }

                                
                                function myEditFileFunction(targetFile, tempDisplayName) {
                                    var folderName = prompt("กรุณาระบุชื่อรูป ห้ามใช้ \\ / : * ? \" < > |", tempDisplayName);
                                    if (folderName != null && folderName != "") {
                                        window.location = "../pages/people_IO.php?function=rename&old=" + targetFile + "&new=" + folderName;
                                    }
                                }

                                function myEditFolderFunction(targetFile, tempDisplayName) {
                                    var folderName = prompt("กรุณาระบุชื่อหมวดหมู่ ห้ามใช้ \\ / : * ? \" < > |", tempDisplayName);
                                    if (folderName != null && folderName != "") {
                                        window.location = "../pages/people_IO.php?function=rename&old=" + targetFile + "&new=" + folderName;
                                    }
                                }
                                
                                function editField(targetEditZone) {
                                    document.getElementById("targetZone_" + targetEditZone).style.display = "none";
                                    document.getElementById("targetEditZone_" + targetEditZone).style.display = "block";
                                }
                            </script>
                        </li>
                        <?php } ?>
                    </ul>
                </div>
            </div>
            <div class="col-md-9">
                <!-- Tab panels -->
                <div class="tab-content vertical">
                    <?php for($f = 0; $f < count($fol); $f++) {
                        $folder_name = pathinfo($fol[$f], PATHINFO_FILENAME); ?>
                        <div class="tab-pane fade in show <?php if ($f == 0) echo 'active'; ?>" id="<?php echo str_replace(" ", "_", pathinfo($fol[$f], PATHINFO_FILENAME)); ?>" role="tabpanel">
                            <h4 class="font-weight-bold"><?php echo $folder_name; ?></h4>
                            <div class="row">
                            <?php
                                $file = glob("../static/elements/people/colleague/$folder_name/*.{jpg,png,gif,PNG,JPG,GIF,JPEG,jpeg}", GLOB_BRACE);
                                $arr = array();
                                foreach($file as $ff) {
                                    $txt = readTxt("$ff.txt");
                                    array_push($arr, array("name"=>array_shift($txt),"file"=>$ff,"text"=>$txt));
                                }
                                array_multisort($arr);
                                foreach($arr as $a) {
                                    $fi = $a["file"];
                                    $file_name = pathinfo($fi, PATHINFO_FILENAME).".".pathinfo($fi, PATHINFO_EXTENSION);
                                    $title = $a["name"];
                                    $description = $a["text"]; ?>
                                    <div class="col-12 col-md-6 col-xl-4 d-flex align-items-stretch">
                                        <div class="card card-cascade wider mb-4">
                                            <div style="display: block;" id="targetZone_<?php echo $file_name;?>">
                                                <div class="view view-cascade overlay">
                                                    <img src="<?php echo $fi; ?>" class="card-img-top" style="min-width: 100%; height: 269px; object-fit: cover;"/>
                                                </div>
                                                <div class="card-body card-body-cascade">
                                                    <h5 class='font-weight-bold'><?php echo $title; ?>
                                                        <?php if ($isAdmin) { ?>
                                                        <small>
                                                            <a class="text-warning mt-0 mr-1 ml-0 mb-0 z-depth-0" style="border:0;" onclick="editField('<?php echo $file_name; ?>');"><i class="fa fa-pencil-alt"></i></a>
                                                            <a class="text-danger mt-0 mr-0 ml-0 mb-0 z-depth-0" style="border:0;" onclick="myDeleteFileFunction('<?php echo $fi; ?>');"><i class="fas fa-trash"></i></a>
                                                        </small>
                                                        <?php } ?>
                                                    </h5>
                                                    <p>
                                                        <?php foreach($description as $d) {
                                                                echo $d."<br>";
                                                        } ?>
                                                    </p>
                                                </div>
                                            </div>
                                            <?php if ($isAdmin) { ?>
                                            <div style="display: none;" id="targetEditZone_<?php echo $file_name;?>">
                                                <form method="POST" action="../pages/people_IO.php?function=update&id=<?php echo $f; ?>&target=<?php echo $fi; ?>" enctype="multipart/form-data">
                                                    <input type="hidden" name="path" value="../static/elements/people/doctor/<?php echo $folder_name; ?>/">
                                                    <div class="view view-cascade overlay">
                                                        <div class="view overlay">
                                                            <input type="hidden" name="id" id="id" value="<?php echo $f; ?>"/>
                                                            <img src="<?php echo $fi; ?>" class="card-img-top" id="demoImg_<?php echo $f; ?>" style="min-width: 100%; height: 269px; object-fit: cover;"/>
                                                            <input type="file" style="display: none;" id="img_<?php echo $f; ?>" name="img_<?php echo $f; ?>" aria-describedby="img" accept="image/jpeg,image/png,image/gif">
                                                            <div class="mask flex-center rgba-black-light text-white" style="cursor: pointer;" onclick="$('#img_<?php echo $f; ?>').click();">
                                                                <i class="fa fa-upload"></i>
                                                            </div>
                                                            <script>
                                                                document.getElementById("img_<?php echo $f; ?>").onchange = function () {
                                                                    var reader = new FileReader();
                                                                    reader.onload = function (e) { document.getElementById("demoImg_<?php echo $f; ?>").src = e.target.result; };
                                                                    reader.readAsDataURL(this.files[0]);
                                                                };        
                                                            </script>
                                                        </div>
                                                    </div>
                                                    <div class="card-body card-body-cascade">
                                                        <div class="d-flex justify-content-between">
                                                            <div class="flex-grow-1">
                                                                <h5 class='font-weight-bold'><input type="text" placeholder="ชื่อ-สกุล" class="form-control mb-1" value="<?php echo $title; ?>" id="name_<?php echo $f; ?>" name="name_<?php echo $f; ?>" required></input></h5>
                                                            </div>
                                                            <div class="flex-shrink-1 ml-auto">                                                
                                                                <button type="submit" value="update" name="method" class="btn-floating btn-sm btn-success mt-0 mb-0 z-depth-0" style="border:0;"><i class="fa fa-save"></i></button>
                                                            </div>
                                                        </div>
                                                        <textarea rows="3" class="form-control" name="description_<?php echo $f; ?>" id="description_<?php echo $f; ?>" placeholder="คำอธิบาย" style="resize:none;"><?php for($d = 0; $d < count($description); $d++) { echo $description[$d]; if ($d != count($description)-1) echo "\n";} ?></textarea>
                                                    </div>
                                                </form>
                                            </div>
                                            <?php } ?>
                                        </div>
                                    </div>
                                    <?php 
                                }
                            ?>
                            <?php if ($isAdmin) {
                                $temp = generateRandom(8);?>
                                <div class="col-12 col-md-6 col-xl-4 d-flex align-items-stretch">
                                    <form method="POST" action="../pages/people_IO.php?function=create" enctype="multipart/form-data">
                                        <input type="hidden" name="path" value="../static/elements/people/doctor/<?php echo $folder_name; ?>/">
                                        <div class="card card-cascade wider mb-4">
                                            <div class="view view-cascade overlay">
                                                <div class="view overlay">
                                                    <img src="../static/elements/people.jpg" class="card-img-top" id="demoImg_<?php echo $temp; ?>" style="min-width: 100%; height: 269px; object-fit: cover;"/>
                                                    <input required type="file" style="display: none;" id="img_<?php echo $temp; ?>" name="img" aria-describedby="img" accept="image/jpeg,image/png,image/gif">
                                                    <div class="mask flex-center rgba-black-light text-white" style="cursor: pointer;" onclick="$('#img_<?php echo $temp; ?>').click();">
                                                        <i class="fa fa-upload"></i>
                                                    </div>
                                                    <script>
                                                        document.getElementById("img_<?php echo $temp; ?>").onchange = function () {
                                                            var reader = new FileReader();
                                                            reader.onload = function (e) { document.getElementById("demoImg_<?php echo $temp; ?>").src = e.target.result; };
                                                            reader.readAsDataURL(this.files[0]);
                                                        };        
                                                    </script>
                                                </div>
                                            </div>
                                            <div class="card-body card-body-cascade">
                                                <div class="d-flex justify-content-between">
                                                    <div class="flex-grow-1">
                                                        <h5 class='font-weight-bold'><input type="text" placeholder="ชื่อ-สกุล" class="form-control mb-1" value="" id="name" name="name" required></input></h5>
                                                    </div>
                                                    <div class="flex-shrink-1 ml-auto">                                                
                                                        <button type="submit" value="create" name="method" class="btn-floating btn-sm btn-success mt-0 mb-0 z-depth-0" style="border:0;"><i class="fa fa-save"></i></button>
                                                    </div>
                                                </div>
                                                <textarea rows="3" class="form-control" name="description" id="description" placeholder="คำอธิบาย" style="resize:none;"></textarea>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            <?php } ?>
                            </div>
                        </div>    
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
    <?php require_once '../static/functions/popup.php'; ?>
    <?php require_once '../static/functions/footer.php'; ?>
    <script>
    if (hashtag()) {
        document.getElementById("pill_" + hashtag()).click();
        backToTop();
    }
    </script>
</body>

</html>