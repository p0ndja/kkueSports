<?php

    require_once 'init.php';
    require_once 'connect.php';

    function latestIncrement($dbdatabase, $db) {
        global $conn;
        return mysqli_fetch_array(mysqli_query($conn,"SELECT `AUTO_INCREMENT` FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA = '$dbdatabase' AND TABLE_NAME = '$db'"), MYSQLI_ASSOC)["AUTO_INCREMENT"];
    }

    function make_directory($p) {
        $path = explode("/", $p);
        $stackPath = "";
        for ($i = 0; $i < count($path); $i++) {            
            $stackPath .= $path[$i] . "/";
            if (file_exists($stackPath)) continue;
            mkdir($stackPath, 0777, true);
        }
        return file_exists($stackPath);
    }

    //Remove Directory (delTree) by nbari@dalmp.com
    function remove_directory($dir) {
        $files = array_diff(scandir($dir), array('.','..'));
        foreach ($files as $file) {
            (is_dir("$dir/$file")) ? remove_directory("$dir/$file") : unlink("$dir/$file");
        }
        return rmdir($dir);
    }

    function login(String $username, String $password) {
        global $conn;
        if ($stmt = $conn->prepare("SELECT `id` FROM `user` WHERE username = ? AND password = ? LIMIT 1")) {
            $stmt->bind_param('ss', $username, $password);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    return new User((int) $row['id']);
                }
            }
        }
        return null;
    }

    //checkTime is in second unit as UNIX TIME FORMAT.
    function checkAuthKey(String $authKey, int $checkTime = 0) {
        global $conn;
        if (!isLogin()) return false;
        $uid = $_SESSION['user']->getID();
        if ($stmt = $conn->prepare("SELECT `tempAuthKey` FROM `user` WHERE `id` = ?")) {
            $stmt->bind_param('i',$uid);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    if (empty($row['tempAuthKey']) || $row['tempAuthKey'] == null) return false;
                    $key = json_decode($row['tempAuthKey'], true);
                    if ($authKey == $key['key']) {
                        if ($checkTime > 0) return ((time() - ((int)$key['time'])) <= $checkTime);
                        else                return true;
                    } else {
                        return false;
                    }
                }
            }
        }
    }

    function generateAuthKey(int $uid) {
        global $conn;

        if (!isValidUserID($uid)) return false;

        $authKey = array(
            "key" => generateRandom(8),
            "time" => time()
        );
        $tempAuthKey = json_encode($authKey);

        if ($stmt = $conn->prepare("UPDATE `user` SET `tempAuthKey` = ? WHERE `id` = ?")) {
            $stmt->bind_param('si',$tempAuthKey,$uid);
            if ($stmt->execute()) {
                return $authKey['key'];
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    function useAuthKey(String $authKey, int $checkTime = 0) {
        global $conn;
        if (!isLogin()) return false;
        $uid = $_SESSION['user']->getID();
        if (checkAuthKey($authKey, $checkTime)) {
            if ($stmt = $conn->prepare("UPDATE `user` SET `tempAuthKey` = null WHERE `id` = ?")) {
                $stmt->bind_param('i',$uid);
                return $stmt->execute();
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    function listCategory(bool $excludeHardcodedCategory = false) {
        global $conn;
        $category = $excludeHardcodedCategory ? array() : HardcodedPostCategory::CATEGORIES;
        if ($stmt = $conn->prepare("SELECT DISTINCT JSON_EXTRACT(`properties`,'$.category') as category FROM post")) {
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    if ($row['category'] != "\"uncategorized\"" && !empty($row['category']))
                        array_push($category, substr($row['category'], 1, -1));
                }
                $category = array_unique($category);
                sort($category);
            }
        }
        return $excludeHardcodedCategory ? array_diff($category, HardcodedPostCategory::CATEGORIES) : $category;
    }

    function loadPostNormal(String $category = "~", String $tag = "", int $page = 1, int $limit = 10) {
        global $conn;
        $stmt;
        $start_id = ($page - 1) * $limit;
        if (!empty($tag)) {
            $json_tag = json_encode(array("tag"=>"$tag"));
            if ($category != "~") {
                $stmt = $conn->prepare("SELECT * FROM `post` WHERE JSON_EXTRACT(`properties`,'$.category') = ? AND JSON_CONTAINS(`properties`,?) AND JSON_EXTRACT(`properties`,'$.hide') = false ORDER BY JSON_EXTRACT(`properties`,'$.pin') DESC, JSON_EXTRACT(`properties`,'$.updated') DESC LIMIT $start_id, $limit");
                $stmt->bind_param('ss', $category, $json_tag);
            } else {
                $stmt = $conn->prepare("SELECT * FROM `post` WHERE JSON_CONTAINS(`properties`,?) AND JSON_EXTRACT(`properties`,'$.hide') = false ORDER BY JSON_EXTRACT(`properties`,'$.pin') DESC, JSON_EXTRACT(`properties`,'$.updated') DESC LIMIT $start_id, $limit");
                $stmt->bind_param('s', $category);
            }
        } else {
            if ($category != "~") {
                $stmt = $conn->prepare("SELECT * FROM `post` WHERE JSON_EXTRACT(`properties`,'$.category') = ? AND JSON_EXTRACT(`properties`,'$.hide') = false ORDER BY JSON_EXTRACT(`properties`,'$.pin') DESC, JSON_EXTRACT(`properties`,'$.updated') DESC LIMIT $start_id, $limit");
                $stmt->bind_param('s', $category);
            } else {
                $stmt = $conn->prepare("SELECT * FROM `post` WHERE JSON_EXTRACT(`properties`,'$.hide') = false ORDER BY JSON_EXTRACT(`properties`,'$.pin') DESC, JSON_EXTRACT(`properties`,'$.updated') DESC LIMIT $start_id, $limit");
            }
        }
        return $stmt;
    }

    function isLogin() {
        if (isset($_SESSION['user'])) return true;
        return false;
    }

    function isAdmin() {
        if (!isLogin()) return false;
        return $_SESSION['user']->isAdmin();
    }

    function isValidUserID($id) {
        global $conn;
        if ($stmt = $conn->prepare("SELECT `id` FROM `user` WHERE `id` = ? LIMIT 1")) {
            $stmt->bind_param('i', $id);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows == 1) {
                return true;
            }
        }
        return false;
    }

    function getPostData(int $id) {
        global $conn;
        if ($stmt = $conn->prepare('SELECT * FROM `post` WHERE id = ?')) {
            $stmt->bind_param('i', $id);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    return $row;
                }
            }
        }
        return null;
    }

    function getUserData(int $id) {
        global $conn;
        if ($stmt = $conn->prepare('SELECT * FROM `user` WHERE id = ?')) {
            $stmt->bind_param('i', $id);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    return $row;
                }
            }
        }
        return null;
    }

    function watchVDO() {
        $vdo = array();
        $txtFile = "../static/elements/video.txt";
        if (file_exists($txtFile)) {
            $file = fopen("../static/elements/video.txt", "r");
            while(!feof($file)) {
                array_push($vdo, fgets($file));
                # do same stuff with the $vdo
            }
            fclose($file);
        } else {
            $file = fopen("../static/elements/video.txt","w");
            if (!fwrite($file,"https://www.youtube.com/embed/VXZM6imLsw4"))
                die("CAN'T WRITE FILE");
            fclose($file);
        }
        return array_filter($vdo);
    }

    function readTxt($path) {
        $msg = array();
        if (file_exists($path)) {
            $file = fopen($path, "r");
            while(!feof($file)) {
                array_push($msg, fgets($file));
                # do same stuff with the $vdo
            }
            fclose($file);
        }
        return $msg;
    }

    function readTxt2($path) {
        $msg = "";
        if (file_exists($path)) {
            $file = fopen($path, "r");
            while(!feof($file)) {
                $msg .= fgets($file) . " ";
                # do same stuff with the $vdo
            }
            fclose($file);
        }
        return $msg;
    }

    //FileSizeConvert by Arseny Mogilev
    function FileSizeConvert($bytes) {
        $bytes = floatval($bytes);
        $arBytes = array(
            0 => array(
                "UNIT" => "TB",
                "VALUE" => 1099511627776
            ),
            1 => array(
                "UNIT" => "GB",
                "VALUE" => 1073741824
            ),
            2 => array(
                "UNIT" => "MB",
                "VALUE" => 1048576
            ),
            3 => array(
                "UNIT" => "KB",
                "VALUE" => 1024
            ),
            4 => array(
                "UNIT" => "B",
                "VALUE" => 1
            ),
        );

        foreach($arBytes as $arItem)
        {
            if($bytes >= $arItem["VALUE"])
            {
                $result = round($bytes / $arItem["VALUE"], 2) . " " . $arItem["UNIT"];
                break;
            }
        }
        return $result;
    }

    function createHeader($text) {
        return '<div class="c_header"><div class="c_title font-weight-bolder">'.$text.'<div class="c_tail"></div><div class="c_tail2"></div></div></div>';
    }
?>
<?php
    function getClientIP() {
        $targetIP;
        if(!empty($_SERVER['HTTP_CLIENT_IP'])) $targetIP = $_SERVER['HTTP_CLIENT_IP'];
        else if(!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) $targetIP = $_SERVER['HTTP_X_FORWARDED_FOR'];
        else $targetIP = $_SERVER['REMOTE_ADDR'];
        if ($targetIP == "::1") $targetIP = "127.0.0.1";
        return $targetIP;
    }

    function fixPath($path) {
        return pathinfo($path, PATHINFO_DIRNAME) . "/" . pathinfo($path, PATHINFO_FILENAME);
    }

    function fixFilePath($path) {
        return pathinfo($path, PATHINFO_DIRNAME) . "/" . pathinfo($path, PATHINFO_FILENAME) . "." . pathinfo($path, PATHINFO_EXTENSION);
    }

    function lazy($path, $size = 0.025) {
        $dir = pathinfo($path, PATHINFO_DIRNAME);
        $nam = pathinfo($path, PATHINFO_FILENAME);
        $ext = pathinfo($path, PATHINFO_EXTENSION);

        list($wid, $ht) = getimagesize($path);

        $dul = "";
        if (file_exists("$dir/$nam.lazy.$ext")) {
            return "$dir/$nam.lazy.$ext";
        } else {
            return imageResize($wid*$size, "$dir/$nam.lazy", $path);
        }
    }

    function createThumbnail($path, $size = 0.125) {
        $dir = pathinfo($path, PATHINFO_DIRNAME);
        $nam = pathinfo($path, PATHINFO_FILENAME);
        $ext = pathinfo($path, PATHINFO_EXTENSION);

        list($wid, $ht) = getimagesize($path);

        $dul = "";
        if (file_exists("$dir/$nam.thumbnail.$ext")) {
            $i = 1;
            while(file_exists("$dir/$nam.thumbnail"."_$i.$ext")) {
                $i++;
            }
            $dul = "_$i";
        }

        return imageResize($wid*$size, "$dir/$nam.thumbnail$dul", $path);
    }

    function imageResize($newWidth, $targetFile, $originalFile) {

        $info = getimagesize($originalFile);
        $mime = $info['mime'];
    
        switch ($mime) {
                case 'image/jpeg':
                        $image_create_func = 'imagecreatefromjpeg';
                        $image_save_func = 'imagejpeg';
                        $new_image_ext = 'jpg';
                        break;
    
                case 'image/png':
                        $image_create_func = 'imagecreatefrompng';
                        $image_save_func = 'imagepng';
                        $new_image_ext = 'png';
                        break;
    
                case 'image/gif':
                        $image_create_func = 'imagecreatefromgif';
                        $image_save_func = 'imagegif';
                        $new_image_ext = 'gif';
                        break;
    
                default: 
                        throw new Exception('Unknown image type.');
        }
    
        $img = $image_create_func($originalFile);
        list($width, $height) = getimagesize($originalFile);
    
        $newHeight = (int) floor(($height / $width) * $newWidth);
        $newWidth = (int) floor($newWidth);
        $tmp = imagecreatetruecolor($newWidth, $newHeight);
        imagecopyresampled($tmp, $img, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
    
        if (file_exists($targetFile)) {
                unlink($targetFile);
        }
        if (!$image_save_func($tmp, "$targetFile.$new_image_ext")) return 0;
        return "$targetFile.$new_image_ext";
    }

    function path_curTime() {
        date_default_timezone_set('Asia/Bangkok'); return date('Y/m/d', time());
    }

    function unformat_curTime() {
        date_default_timezone_set('Asia/Bangkok'); return date('YmdHis', time());
    }

    function curDate() {
        date_default_timezone_set('Asia/Bangkok'); return date('Y-m-d', time());
    }

    function curTime() {
        date_default_timezone_set('Asia/Bangkok'); return date('H:i:s', time());
    }

    function curFullTime() {
        date_default_timezone_set('Asia/Bangkok'); return date('Y-m-d H:i:s', time());
    }

    function sendFileToIMGHost($file) {
        $data = array(
            'img' => new CURLFile($file['tmp_name'],$file['type'], $file['name']),
        ); 
        
        //**Note :CURLFile class will work if you have PHP version >= 5**
        
         $ch = curl_init();
        
        curl_setopt($ch, CURLOPT_URL, 'https://img.p0nd.ga/upload.php');
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_TIMEOUT, 86400); // 1 Day Timeout
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 60000);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_REFERER, $_SERVER['HTTP_HOST']);
        
        $response = curl_exec($ch);
        if (curl_errno($ch)) {
            $msg = FALSE;
        } else {
            $msg = $response;
        }
        
        curl_close($ch);
        return $msg;
    }

    function isValidCategory($category) {
        if ($category == "~") return true;
        return in_array($category, listCategory());
    }

    function countCategory($category) {
        global $conn;
        if ($stmt = $conn-> prepare("SELECT count(id) AS cat FROM `post` WHERE JSON_EXTRACT(`properties`,'$.hide') = false AND JSON_EXTRACT(`properties`,'$.category') = ? ORDER BY JSON_EXTRACT(`properties`,'$.updated')")) {
            $stmt->bind_param('s', $category);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows == 1) {
                while ($row = $result->fetch_assoc()) {
                    return $row["cat"];
                }
            }
        }
    }

    function icon($file, $name = true) {
        $fext = pathinfo($file, PATHINFO_EXTENSION);
        $fname = pathinfo($file, PATHINFO_FILENAME);
        $type = mime_content_type($file);
        $ico = 'far fa-file';
        switch($type) {
            case "directory": 
                $ico = 'fas fa-folder text-warning';
                break;
            case in_array($fext, ['doc', 'docx', 'docm', 'dot', 'dotx', 'dotm', 'odt']):
                $ico = 'far fa-file-word text-primary';
                break;
            case in_array($fext, ['ppt', 'pptx', 'pps', 'ppsx', 'potx', 'potm', 'pot', 'ppsm', 'ppa', 'ppam', 'odp']):
                $ico = 'far fa-file-powerpoint text-warning';
                break;
            case in_array($fext, ['xlsx','xsl','xlsb','xlsm','csv','xltx', 'xltm', 'xlt','ods']):
                $ico = 'far fa-file-excel text-success';
                break;
            case startsWith($type, 'image/'):
                $ico = 'far fa-file-image text-secondary';
                break;
            case startsWith($type, 'text/'):
                $ico = 'far fa-file-alt text-info';
                break;
            case startsWith($type, 'video/'):
                $ico = 'far fa-file-video text-danger';
                break;
            case startsWith($type, 'application/pdf'):
                $ico = 'far fa-file-pdf text-danger';
                break;
            default:
                $ico = 'far fa-file';
        }
        if (!empty($fext)) $fext = ".$fext";
        if (!$name) {
            $fname = "";
            $fext = "";
        }
        return "<i class='$ico'></i> $fname$fext";
    }
    
    function icon_url($file) {    
        return "<a href='$file' target='_blank' class='md'>".icon($file)."</a>";
    }

    function generateCategoryBadge($category) {
        return ($category != "uncategorized" && !empty($category)) ? "<a href='../category/$category-1'><span class='badge badge-default font-weight-normal'>$category</span></a>" : null;
    }

    function generateCategoryBadgeForced($category) {
        return ($category != "uncategorized" && !empty($category)) ? "<a href='../category/$category-1'><span class='badge badge-default font-weight-normal'>$category</span></a>" : "<span class='badge badge-dark font-weight-normal'>ไม่ได้จัดหมวดหมู่</span>";
    }

    function generateCategoryTitle($category) {
        if ($category == "~") $category = "โพสต์ทั้งหมด";
        return createHeader($category);        
    }

    function WTFTime(int $dateString) {
        if($dateString > time()) {
            return 1;
        # date is in the future
        }
        if($dateString < time()) {
            return -1;
        # date is in the past
        }
        if($dateString == time()) {
            return 0;
        # date is right now
        }
    }

    function dateDifference($date_1 , $date_2 = 'now' , $differenceFormat = '%a')
    {
        date_default_timezone_set('Asia/Bangkok'); 
        $datetime1 = date_create(date("Y-m-d H:i:s", $date_1));
        $datetime2 = date_create('now');
        $interval = date_diff($datetime1, $datetime2);
        return $interval->format($differenceFormat);
    
    }

    function fromThenToNow($date_1, $differenceFormat = '%a')
    {
        $datetime1 = date_create(date("Y-m-d H:i:s", $date_1));
        $datetime2 = date_create(date("Y-m-d H:i:s", time()));
        
        $interval = date_diff($datetime1, $datetime2);

        $time = WTFTime($date_1);
    
        $days = $interval->format($differenceFormat);
        $msg = "";
        if ($days == 0) {
            if ($hour = dateDifference($date_1, 'now', '%h'))
                $msg = $hour . " ชั่วโมงที่แล้ว";
            else if ($min = dateDifference($date_1, 'now', '%i'))
                $msg = $min . " นาทีที่แล้ว";
            else
                $msg = "เมื่อสักครู่";
        }
        else if ($time > 0) {
            if ($days > 364)
                $msg = "อีก " . floor($days/365) . " ปี";
            else if ($days > 29)
                $msg = "อีก " . floor($days/30) . " เดือน";
            else
                $msg = "อีก $days วัน";
        } else {
            if ($days > 364)
                $msg = floor($days/365) . " ปีที่แล้ว";
            if ($days > 29)
                $msg = floor($days/30) . " เดือนที่แล้ว";
            else
                $msg = "$days วันที่แล้ว";
        }
        return "<a title='" . date("M d Y H:i:s", $date_1) . " ICT'>$msg</a>";
    }

    function generateOpenGraphMeta() {
        $host_url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]";
        $current_url = "$host_url$_SERVER[REQUEST_URI]";

        $title = "KKU eSports | ชมรมกีฬาอิเล็กทรอนิกส์ มหาวิทยาลัยขอนแก่น";
        $og = array(
            'logo'=> $current_url."/../../static/elements/logo/favicon-500x500.png",
            'height'=> 500,
            'width'=> 500
        );
        
        if (strpos($current_url, "/post/") && isset($_GET['id'])) {
            //Mean you're currently browsing in post page
            $post = new Post((int) $_GET['id']);
            if ($post->getID() != -1) {
                $title = $post->getTitle() . " | " . $title;
                $img = $post->getProperty("cover");
                if ($img != null && !empty($img)) {
                    list($ogwidth, $ogheight, $ogtype, $ogattr) = getimagesize($img);
                    $og = array(
                        'logo'=> $current_url."/../../".$img,
                        'height'=> $ogheight,
                        'width'=> $ogwidth
                    );
                }
            }
        } 
        ?>
<title><?php echo $title; ?></title>
    <meta property="og:title" content="<?php echo $title; ?>" />
    <meta property="og:image" content="<?php echo $og['logo']; ?>" />
    <meta property="og:image:width" content="<?php echo $og['width']; ?>" />
    <meta property="og:image:height" content="<?php echo $og['height']; ?>" />
    <meta name="twitter:card" content="summary"></meta>
    <link rel="image_src" href="<?php echo $og['logo']; ?>" />
    <meta property="og:type" content="website" />
    <meta property="og:url" content="<?php echo $current_url; ?>" />
    <?php }

    function generateRandom($length = 16, $string = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789") {
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $string[rand(0, strlen($string) - 1)];
        }
        return $randomString;
    }
?>

<?php
    function needLogin() {
    if (!isLogin()) {?>
<script>
    swal({
        title: "ACCESS DENIED",
        text: "You need to logged-in!",
        icon: "error"
    }).then(function () {
        <?php $_SESSION['error'] = "กรุณาเข้าสู่ระบบก่อนดำเนินการต่อ"; ?>
        window.location = "../auth/login";
    });
</script>
<?php die(); }} ?>
<?php
    function needAdmin() {
    if (!isLogin()) { needLogin(); die(); }
    if (!isAdmin()) { ?>
<script>
    swal({
        title: "ACCESS DENIED",
        text: "You don't have enough permission!",
        icon: "warning"
    }).then(function () {
        window.location = "../home/";
    });
</script>
<?php die();}
    }
?>
<?php function back() {
    if (isset($_SERVER["HTTP_REFERER"])) { ?>
        <script>window.history.back();</script>
    <?php } else {
        home();
    }
    die();
    } ?>
<?php function home() {
    header("Location: ../home/");
} ?>
<?php function logout() { ?>
    <script>
        swal({
            title: "ออกจากระบบ ?",
            text: "คุณต้องการออกจากระบบหรือไม่?",
            icon: "warning",
            buttons: true,
            dangerMode: true}).then((willDelete) => {
                if (willDelete) {
                    window.location = "../auth/logout";
                }
            });
</script>
<?php } ?>
<?php function deletePost($id) { ?>
    <script>
        swal({
            title: "ลบข่าวหรือไม่ ?",
            text: "หลังจากที่ลบแล้ว ข่าวนี้จะไม่สามารถกู้คืนได้!",
            icon: "warning",
            buttons: true,
            dangerMode: true}).then((willDelete) => {
                if (willDelete) {
                    window.location = "../post/delete.php?id=<?php echo $id; ?>";
                }
            });
    </script>
<?php } ?>
<?php function warningSwal($title,$name) { ?>
    <script>
    swal({
        title: "<?php echo $title; ?>",
        text: "<?php echo $name; ?>",
        icon: "warning"
    });
    </script>
<?php } ?>
<?php function errorSwal($title,$name) { ?>
    <script>
    swal({
        title: "<?php echo $title; ?>",
        text: "<?php echo $name; ?>",
        icon: "error"
    });
    </script>
<?php } ?>
<?php function successSwal($title,$name) { ?>
    <script>
    swal({
        title: "<?php echo $title; ?>",
        text: "<?php echo $name; ?>",
        icon: "success"
    });
    </script>
<?php } ?>
<?php function debug($message) { echo $message; } ?>

<?php
    function startsWith($haystack, $needle) {
        return substr_compare($haystack, $needle, 0, strlen($needle)) === 0;
    }
    function endsWith($haystack, $needle) {
        return substr_compare($haystack, $needle, -strlen($needle)) === 0;
    }
?>
