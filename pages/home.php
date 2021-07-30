<?php require_once '../static/functions/connect.php'; ?>
<!DOCTYPE html>
<html lang="th" prefix="og:http://ogp.me/ns#">

<head>
<?php require_once '../static/functions/head.php'; ?>
<style>
    header {
        position: relative;
        background-color: black;
        height: 75vh;
        min-height: 25rem;
        width: 100%;
        overflow: hidden;
    }

    header video {
        position: absolute;
        top: 50%;
        left: 50%;
        min-width: 100%;
        min-height: 100%;
        width: auto;
        height: auto;
        z-index: 0;
        -ms-transform: translateX(-50%) translateY(-50%);
        -moz-transform: translateX(-50%) translateY(-50%);
        -webkit-transform: translateX(-50%) translateY(-50%);
        transform: translateX(-50%) translateY(-50%);
    }

    header .container {
        position: relative;
        z-index: 2;
    }

    header .overlay {
        position: absolute;
        top: 0;
        left: 0;
        height: 100%;
        width: 100%;
        background-color: black;
        opacity: 0.36;
        z-index: 1;
    }

    html,
    body,
    header {
        min-height: 100vh !important;
    }

    @media (pointer: coarse) and (hover: none) {
        header {
            background: url('../static/elements/trailer.mp4') black no-repeat center center scroll;
        }

        header video {
            display: url('../static/elements/trailer.mp4');
        }
    }
    </style>
</head>
<body id="home">
    <header id="header">
        <div class="overlay"></div>
        <video playsinline="playsinline" autoplay="autoplay" muted="muted" loop="loop" style="filter: blur(4px);
  -webkit-filter: blur(4px);">
            <source src="../static/elements/trailer.mp4" type="video/mp4">
        </video>
        <div class="container h-100">
            <div class="d-flex h-100 text-center align-items-center">
                <div class="w-100 text-white">
                    <div class="d-none d-md-block">
                        <img src="../static/elements/logo/logo.png" class="img-fluid" style="width: 15vw" alt="KKU Logo">
                    </div>
                    <div class="d-block d-md-none">
                        <img src="../static/elements/logo/logo.png" class="img-fluid" style="width: 50vw" alt="KKU Logo">
                    </div>
                    <h1 class="font-weight-bold display-4">KKU eSports</h1>
                    <h3>ชมรมกีฬาอิเล็กทรอนิกส์ มหาวิทยาลัยขอนแก่น</h3>
                    <div class="mb-5"></div>
                    <a class="scroll-btn" href="#nav"><img alt="Arrow Down Icon"
                            class="animated infinite pulse delay-3s" src="../static/elements/arrow-down.png"></a>
                </div>
            </div>
        </div>

    </header>
</body>
<nav class="navbar navbar-dark navbar-normal navbar-kku" id="nav" role="navigation">
    <?php require_once '../static/functions/navbar.php'; ?>
</nav>

<?php $isAdmin = isAdmin(); ?>

<body>
    <div class="container-fluid" id="home">
        
    </div>
    <div class="container-fluid" id="container">
        <section class="d-flex align-items-center p-3 p-lg-5" style="min-height: 100vh; min-width: 100%;" id="about">
            <div class="row w-100">
                <div class="col-6 col-md-3">
                    <div class="text-center">
                        <img src="../static/elements/logo/logo.png" class="img-fluid" alt="KKU Logo">
                    </div>  
                </div>
                <div class="col-12 col-md-9">
                    <h1 class="font-weight-bold display-4">About Us</h1>
                    <h5 class="text-kku">ชมรมเราคือชมรมอะไร? ทำหน้าที่อะไร?</h5>
                    <hr>
                    <p>
                        Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla tempor odio ornare, imperdiet felis vitae, facilisis risus. Aliquam suscipit risus vitae malesuada hendrerit. Proin venenatis, urna quis sodales cursus, arcu sapien pellentesque risus, quis lobortis dolor quam convallis arcu. Phasellus cursus at sem id finibus. Aliquam mollis fringilla orci dictum porta. Quisque rhoncus tellus sed egestas dictum. Sed imperdiet vehicula magna. Sed malesuada ultrices ex. Aenean et ipsum eget felis molestie faucibus.
                    </p>
                    <div class="row text-center text-white">
                        <div class="col-6 col-md-4 mb-2">
                            <img src="https://via.placeholder.com/1280x720" class="img-fluid">
                            ฝ่ายกรรมการจัดการแข่งขัน
                        </div>
                        <div class="col-6 col-md-4 mb-2">
                            <img src="https://via.placeholder.com/1280x720" class="img-fluid">
                            ฝ่ายโปรดักชั่น
                        </div>
                        <div class="col-6 col-md-4 mb-2">
                            <img src="https://via.placeholder.com/1280x720" class="img-fluid">
                            ฝ่ายธุรการและการเงิน
                        </div>
                        <div class="col-6 col-md-4 mb-2">
                            <img src="https://via.placeholder.com/1280x720" class="img-fluid">
                            ฝ่ายสถานที่
                        </div>
                        <div class="col-6 col-md-4 mb-2">
                            <img src="https://via.placeholder.com/1280x720" class="img-fluid">
                            ฝ่ายสื่อและประชาสัมพันธ์
                        </div>
                        <div class="col-6 col-md-4 mb-2">
                            <img src="https://via.placeholder.com/1280x720" class="img-fluid">
                            ฝ่ายกราฟฟิก
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <section class="d-flex align-items-center p-3 p-lg-5" style="min-height: 100vh; min-width: 100%;" id="news">
            <div class="row w-100">
                <div class="col-12 col-lg-9">
                    <h1 class="font-weight-bold display-4 text-right">News</h1>
                    <h5 class="text-kku text-right">ข่าวสารประชาสัมพันธ์จากชมรม KKU eSports</h5>
                    <hr>
                    <?php
                        global $conn;
                        if ($stmt = loadPostNormal('ประชาสัมพันธ์', "", 1,6)) {
                            $stmt->execute();
                            $result = $stmt->get_result();
                            if ($result->num_rows > 0) { ?>
                                <div class="row">
                                    <?php 
                                    while ($row = $result->fetch_assoc()) {
                                        $properties = json_decode($row["properties"], true);
                                            $properties_pin = isset($properties["pin"]) ? "border border-success z-depth-1" : ""; 
                                            $properties_link = isset($properties["hotlink"]) ? $properties["hotlink"] : "../post/" . $row['id'];
                                            $properties_cover = (isset($properties['cover']) && !empty($properties['cover'])) ? $properties["cover"] : "../static/elements/banner.jpg";
                                        ?>
                                        <div class="col-md-4">
                                        <a href="<?php echo $properties_link; ?>" class="text-dark">
                                            <div class="card mb-1 mt-1">
                                                <div class="view overlay zoom">
                                                    <img src="<?php echo $properties_cover; ?>" class="card-img-top" style="min-width: 100%; height: 210px; object-fit: cover;" >
                                                </div>
                                            </div>
                                            <div class="ml-1 mr-1 mt-2 mb-3">
                                                <a href="<?php echo $properties_link; ?>" class="kku-light"><text class='font-weight-bold display-6'><?php echo $row['title']; ?></text></a>
                                                <br><small class="mt-1 text-muted"><?php echo fromThenToNow($properties["updated"]); ?></small>
                                            </div>
                                        </a>
                                        </div>
                                    <?php } ?>
                                </div>
                                <a href="../post/" class="btn btn-c-kku"><i class="fas fa-arrow-circle-right"></i> อ่านเพิ่มเติม</a>
                                <?php if ($isAdmin) { ?>
                            <a href="../admin/post" class="btn-floating btn-md btn-warning z-depth-0 ml-0 mr-1"><i class='fas fa-pencil-alt'></i></a>
                            <a href="../post/create" class="btn-floating btn-md btn-info z-depth-0 ml-0 mr-1"><i class='fas fa-plus'></i></a>
                        <?php } ?>
                        <?php } ?>
                    <?php } ?>
                </div>
                <div class="col-12 col-lg-3 d-none d-lg-block">
                    <div class="text-center">
                        <img src="../static/elements/homepage/Asset 1.svg" class="img-fluid" alt="KKU Logo">
                    </div>  
                </div>
            </div>
        </section>
        <section class="d-flex align-items-center p-3 p-lg-5" style="min-height: 100vh; min-width: 100%;" id="event">
            <div class="row w-100">
                <div class="col-12 col-lg-3 d-none d-lg-block">
                    <div class="text-center">
                        <img src="../static/elements/homepage/Asset 2.svg" class="img-fluid" alt="KKU Logo">
                    </div>  
                </div>
                <div class="col-12 col-lg-9 text-white">
                    <h1 class="font-weight-bold display-4">Event</h1>
                    <h5 class="text-kku">กิจกรรมที่กำลังจะเกิดขึ้นในอนาคต</h5>
                    <hr>
                    <div class="row mb-2 mt-3">
                        <div class="col-12 col-md-2 text-lg-center text-left">
                            <div class="font-weight-bold mt-0 mb-2" style="padding-right: 30px;">
                                <h1 class="display-3 mt-0 mb-0">13</h1>
                                <h4 class="text-kku font-weight-bold mt-0 mb-0">August<br>2021</h4>
                            </div>
                        </div>
                        <div class="col-12 col-md-10">
                            <h1 class="display-5 mt-2">KKU Game</h1>
                            <p>Nullam sodales ornare ultrices. Cras id porta justo. Nullam eu dolor non purus rhoncus convallis vel in odio. Proin odio tellus, hendrerit in libero vitae, lacinia fringilla elit. Suspendisse auctor bibendum eros vitae blandit. Vestibulum eget nibh nec neque malesuada laoreet eget sed odio. Integer ac nisi elit. Vestibulum vestibulum ut mi nec laoreet. Suspendisse elementum justo et neque rhoncus suscipit.</p>
                        </div>
                    </div>
                    <div class="row mb-2 mt-3">
                        <div class="col-12 col-md-2 text-lg-center text-left">
                            <div class="font-weight-bold mt-0 mb-2" style="padding-right: 30px;">
                                <h1 class="display-3 mt-0 mb-0">???</h1>
                                <h4 class="text-kku font-weight-bold mt-0 mb-0">October<br>2021</h4>
                            </div>
                        </div>
                        <div class="col-12 col-md-10">
                            <h1 class="display-5">AIS eSports U Series</h1>
                            <p>Nullam sodales ornare ultrices. Cras id porta justo. Nullam eu dolor non purus rhoncus convallis vel in odio. Proin odio tellus, hendrerit in libero vitae, lacinia fringilla elit. Suspendisse auctor bibendum eros vitae blandit. Vestibulum eget nibh nec neque malesuada laoreet eget sed odio. Integer ac nisi elit. Vestibulum vestibulum ut mi nec laoreet. Suspendisse elementum justo et neque rhoncus suscipit.</p>
                        </div>
                    </div>
                    <div class="row mb-2 mt-3">
                        <div class="col-12 col-md-2 text-lg-center text-left">
                            <div class="font-weight-bold mt-0 mb-2" style="padding-right: 30px;">
                                <h1 class="display-3 mt-0 mb-0">1</h1>
                                <h4 class="text-kku font-weight-bold mt-0 mb-0">January<br>2077</h4>
                            </div>
                        </div>
                        <div class="col-12 col-md-10">
                            <h1 class="display-5">Cyberpunk 2077 officially playable on PS4</h1>
                            <p>Cyberpunk 2077 is a ambitious game from CD PROJEKT RED, released in 2020 with a gigantic number of hyped player. But it didn't go as expected. The game contained with many bugs, glitches and cut stories.</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
    <?php require_once '../static/functions/popup.php'; ?>
    <?php require_once '../static/functions/footer.php'; ?>
    <script>
        $(window).bind('scroll', function () {
            var stick = false;
            if ($(window).scrollTop() > $(window).height()) {
                $('#nav').removeClass('navbar-top');
                $('#nav').addClass('fixed-top');
                $('#nav').addClass('scrolling-navbar');
                document.getElementById("container").style.paddingTop = "79px";

                stick = true;

            } else {
                $('#nav').removeClass('fixed-top');
                $('#nav').removeClass('scrolling-navbar');
                $('#nav').addClass('navbar-top');
                document.getElementById("container").style.paddingTop = "18px";

                stick = false;
            }
        });
    </script>
</body>

</html>