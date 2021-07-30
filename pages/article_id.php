<?php 
    require_once '../static/functions/connect.php';
?>

<!DOCTYPE html>
<html lang="th" prefix="og:http://ogp.me/ns#">

<head>
    <?php require_once '../static/functions/head.php'; ?>
</head>
<body>
    <?php require_once '../static/functions/navbar.php'; ?>
    <?php
        if (isset($_GET['id'])) {
            $post = new Post((int) $_GET['id']);
            if ($post->getID() == -1) header("Location: ../");
            $files_in_attachment = array();
            $id = $post->getID();
            if (file_exists("../file/post/$id/attachment/"))
                $files_in_attachment = glob("../file/post/$id/attachment/*");
            
        } else header("Location: ../");
    ?>

    <div class="container mb-3" id="container" >
        <a onclick="window.history.back();" class="float-left"><i class="fas fa-arrow-left"></i> ย้อนกลับ</a><br>
        <div class="row">
            <div class="col-md-8 col-12">
                <?php if (!empty($post->getProperty('cover'))) { ?><img class="img-fluid w-100 z-depth-1" src="<?php echo $post->getProperty('cover'); ?>" style="min-width: 100%; height: 40vh; object-fit: cover;"><?php } ?>
                <div class="ml-2 mt-2 mb-2 mr-2">
                    <h3 class="font-weight-bold">
                        <?php echo generateCategoryBadge($post->getProperty('category')) . " " . $post->getTitle(); ?>
                        <?php if (isAdmin()) { ?>
                            <small><a
                            href="../post/edit-<?php echo $post->getID(); ?>" class="z-depth-0 btn-sm btn-floating btn-info mr-0 ml-0"><i class='fas fa-edit'></i></a>
                            <?php if ($post->getProperty('hide')) { ?>
                            <a href="../pages/article_toggle.php?target=hide&id=<?php echo $id; ?>" class='z-depth-0 btn-sm grey btn-floating mr-0 ml-0'><i class='fa fa-eye-slash'></i></a>
                            <?php } else { ?>
                            <a href="../pages/article_toggle.php?target=hide&id=<?php echo $id; ?>" class='z-depth-0 btn-sm btn-success btn-floating mr-0 ml-0'><i class='fa fa-eye'></i></a>
                            <?php } ?>
                            <?php if ($post->getProperty('pin')) { ?>
                            <a href="../pages/article_toggle.php?target=pin&id=<?php echo $row['id']; ?>" class='z-depth-0 btn-sm btn-success btn-floating mr-0 ml-0'><i class='fas fa-thumbtack'></i></a>
                            <?php } else { ?>
                            <a href="../pages/article_toggle.php?target=pin&id=<?php echo $row['id']; ?>" class='z-depth-0 btn-sm grey btn-floating mr-0 ml-0'><span class="fa-stack"><i class="fas fa-thumbtack fa-stack-1x"></i><i class="fas fa-slash fa-stack-2x"></i></span></a>
                            <?php } ?>
                            <?php if ($post->getProperty('allowDelete') == true) { ?>
                            <a class='z-depth-0 btn-sm btn-danger btn-floating mr-0 ml-0'
                            onclick='
                                    swal({title: "ลบข่าวหรือไม่ ?",text: "หลังจากที่ลบแล้ว ข่าวนี้จะไม่สามารถกู้คืนได้!",icon: "warning",buttons: true,dangerMode: true}).then((willDelete) => { if (willDelete) { window.location = "../pages/article_delete.php?id=<?php echo $post->getID(); ?>&category=<?php echo $post->getProperty("category"); ?>";}});'>
                            <i class="fas fa-trash-alt"></i></a>
                            <?php } ?>
                            </small><?php } ?>
                    </h3>
                        <?php if (!empty($post->getProperty('tag'))) { ?>
                            <?php foreach ($post->getProperty('tag') as $s) { if (!empty($s)) { ?>
                                <div class="badge grey lighten-1"><a href="../category/<?php echo $post->getProperty('category')."-1-$s"; ?>" class="md"><?php echo $s; ?></a></div>
                            <?php } } ?>
                        <?php }?>
                    

                <!-- Case post reader -->
                <?php if ($post->getArticle() != null) { ?>
                <hr>
                <p>
                    <?php
                        $article = $post->getArticle();
                        $article = str_replace("font-weight: bolder;", "font-weight: bold;", $article);
                        //$article = str_replace("<b>", "<strong>", $article);
                        //$article = str_replace("</b>", "</strong>", $article);
                        echo $article;
                    ?>
                </p>
                <?php } ?>
                <?php if (count($files_in_attachment) > 0) {?>
                    <hr>
                    <?php if (count($files_in_attachment) == 1 && pathinfo($files_in_attachment[0], PATHINFO_EXTENSION) == "pdf") { ?>
                    <iframe
                        src="../vendor/pdf.js/web/viewer.html?file=../../<?php echo $files_in_attachment[0]; ?>"
                        width="100%" height="750"></iframe>
                    <?php } else {?>
                    <?php
                        $_GET['path'] = "../file/post/$id/attachment/"; 
                        include '../pages/file.php'; 
                    ?>
                    <?php } ?>
                <?php } ?>
                <i class="far fa-clock"></i>
                <small class="text-muted">
                <?php
                    $writer = new User((int) $post->getProperty('author'));
                    echo fromThenToNow($post->getProperty('updated')) . ' โดย ' . $writer->getName() . ' ('.$writer->getUsername().')'; 
                ?>
                </small>


                </div>
            </div>
            <div class="col-md-4 col-12">
                
                <div class="card mb-3">
                    <div class="card-body">
                        <h4 class="font-weight-bold text-md">Latest</h4>
                        <p>
                                <?php
                                if ($stmt=$conn->prepare("SELECT title,id FROM `post` WHERE JSON_EXTRACT(`properties`,'$.hide') = false ORDER BY JSON_EXTRACT(`properties`,'$.updated') DESC LIMIT 5")) {
                                    $stmt->execute();
                                    $result = $stmt->get_result();
                                    if ($result->num_rows > 0) {
                                        echo '<ul>';
                                        while ($row = $result->fetch_assoc()) {
                                            $postid = $row['id'];
                                            $posttitle = $row['title'];
                                            echo "<li><a class=\"md\" href=\"../post/$postid\">$posttitle</a></li>";
                                        }
                                        echo '</ul>';
                                    } else {
                                        echo "<i>No recent article</i> 😢";
                                    }
                                }
                                ?>
                        </p>
                    </div>
                </div>
                <div class="card d-none">
                    <div class="card-body card-text">
                        <h4 class="font-weight-bold text-md">Category</h4>
                        <ul>
                            <li style="color: green"><a class="md" href="../category/ประชาสัมพันธ์-1">ประชาสัมพันธ์</a></li>
                            <li style="color: green"><a class="md" href="../category/ประกาศ-1">ประกาศ</a></li>
                            <li style="color: green"><a class="md" href="../category/announce-1">ประกาศ</a></li>
                            <li style="color: green"><a class="md" href="../category/guideline-1">ระเบียบ - แนวทางปฏิบัติ</a></li>
                            <li style="color: green"><a class="md" href="../category/manual-1">คู่มือการใช้ยา</a></li>
                            <li style="color: green"><a class="md" href="../category/research-1">ผลงานวิจัยและ R2R</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>    
    <?php require_once '../static/functions/popup.php'; ?>
    <?php require_once '../static/functions/footer.php'; ?>
</body>

</html>