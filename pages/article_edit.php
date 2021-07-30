<?php 
    require_once '../static/functions/connect.php';
?>

<!DOCTYPE html>
<html lang="th" prefix="og:http://ogp.me/ns#">

<head>
    <?php require_once '../static/functions/head.php'; ?>
    <?php
        $id = isset($_GET['id']) ? (int) $_GET['id'] : -1;
        $post = new Post($id);
        $method = $post->validate() ? "update" : "create";
        $attachment = ""; 
        if ($post->validate()) {
            if (file_exists("../file/post/$id/attachment/")) {
                $files_in_attachment = glob("../file/post/$id/attachment/*");
                $i = 0;
                foreach($files_in_attachment as $atm) {
                    $attachment .= str_replace("../file/post/$id/attachment/", "", $atm);
                    if (++$i != count($files_in_attachment)) $attachment .= ", ";
                }
            }
        }
        
        ?>
        <script type="text/javascript">
        $(function () {

            $.ajax({
                url: 'https://api.github.com/emojis',
                async: false 
                }).then(function(data) {
                window.emojis = Object.keys(data);
                window.emojiUrls = data; 
            });;
            $('.summernote').summernote({
                minHeight: 500,
                fontNames: ['Times New Roman', 'MorKhor','Charmonman','Sarabun','Kanit', 'Mitr', 'Inter'],
                fontNamesIgnoreCheck: ['Times New Roman', 'MorKhor','Charmonman','Sarabun','Kanit', 'Mitr', 'Inter'],

                callbacks: {
                    onImageUpload: function(files, editor, welEditable) {
                        sendPicFile(files[0], this);
                    },
                    onFileUpload: function(file) {
                        sendRawFile(file[0], this);
                    }
                },
                toolbar: [
                    ['misc', ['undo', 'redo']],
                    ['style', ['style', 'height', 'fontname', 'fontsize']],
                    ['font', ['bold', 'italic', 'underline', 'strikethrough', 'superscript','subscript', 'clear']],
                    ['color', ['color']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['table', ['table']],
                    ['insert', ['link', 'picture', 'video', 'hr', 'file']],
                    ['view', ['fullscreen', 'codeview', 'help']],
                ],
                hint: {
                    match: /:([\-+\w]+)$/,
                    search: function (keyword, callback) {
                    callback($.grep(emojis, function (item) {
                        return item.indexOf(keyword)  === 0;
                    }));
                    },
                    template: function (item) {
                    var content = emojiUrls[item];
                    return '<img src="' + content + '" width="20" /> :' + item + ':';
                    },
                    content: function (item) {
                    var url = emojiUrls[item];
                    if (url) {
                        return $('<img />').attr('src', url).css('width', 20)[0];
                    }
                    return '';
                    }
                }
            });

            function sendRawFile(file) {
                let data = new FormData();
                data.append("file", file);
                $.ajax({
                    data: data,
                    type: "POST",
                    url: "../pages/article_upload.php", //Your own back-end uploader
                    cache: false,
                    contentType: false,
                    processData: false,
                    xhr: function() { //Handle progress upload
                        let myXhr = $.ajaxSettings.xhr();
                        if (myXhr.upload) myXhr.upload.addEventListener('progress', progressHandlingFunction, false);
                        return myXhr;
                    },
                    success: function(reponse) {
                            let listMimeImg = ['image/png', 'image/jpeg', 'image/webp', 'image/gif', 'image/svg'];
                            let listMimeAudio = ['audio/mpeg', 'audio/ogg'];
                            let listMimeVideo = ['video/mpeg', 'video/mp4', 'video/webm'];
                            let elem;

                            //Other file type
                            elem = document.createElement("a");
                            let linkText = document.createTextNode(file.name);
                            elem.appendChild(linkText);
                            elem.title = file.name;
                            elem.href = reponse;
                            $('.summernote').summernote('editor.insertNode', elem);
                    }
                });
            }

            function progressHandlingFunction(e) {
                if (e.lengthComputable) {
                    //Log current progress
                    console.log((e.loaded / e.total * 100) + '%');

                    //Reset progress on complete
                    if (e.loaded === e.total) {
                        console.log("Upload finished.");
                    }
                }
            }

            function sendPicFile(file, el) {
                data = new FormData();
                data.append("file", file);
                $.ajax({
                    data: data,
                    type: "POST",
                    url: "../pages/article_upload.php",
                    cache: false,
                    contentType: false,
                    processData: false,
                    success: function(url) {
                        $(el).summernote('editor.insertImage', url);
                    }
                });
            }

            $('.summernote').summernote('code', `<?php echo $post->getArticle(); ?>`);
        });
    </script>
    <style>
        .md-outline.select-wrapper+label {
            top: .5em !important;
            z-index: 2 !important;
        }
    </style>
</head>
<body>
    <?php require_once '../static/functions/navbar.php'; ?>
    <?php needAdmin(); ?>
    <div class="container mb-3" id="container" >
        <a onclick="window.history.back();" class="float-left"><i class="fas fa-arrow-left"></i> ย้อนกลับ</a><br>
        <form method="POST" action="../pages/article_save.php<?php if ($id != -1) echo "?news=$id"; ?>" enctype="multipart/form-data">
            <div class="md-form form-lg">
                <input type='text' class='form-control form-control-lg' style="font-weight: bold;" id='title' name='title' aria-label='title' required value='<?php echo $post->getTitle(); ?>'>
                <label for="title">หัวข้อ</label>
            </div>
            <div class="row mb-3">
                <div class="col-12 col-md-8">
                    <div class="form-group mb-1">
                        <textarea class="summernote" id="article" name="article"></textarea>
                    </div>
                    <div class="md-form file-field mb-3">
                        <div class="btn btn-primary btn-sm float-left" id="attachmentZone">
                            <span><i class="fas fa-file-upload"></i> Browse</span>
                            <input type="file" name="attachment[]" id="attachment" class="validate" multiple>
                        </div>
                        <div class="file-path-wrapper">
                            <input class="file-path validate disabled" type="text" id="attachmentURL" name="attachmentURL" placeholder="ไฟล์แนบท้าย" value="<?php echo $attachment;?>">
                        </div>
                        <div class="text-right"><small><a href="#" class="text-danger" onclick="clearAttachment();">ล้างไฟล์แนบท้าย</a></small></div>
                        <script>
                            function clearAttachment() {
                                $("#attachmentURL").val("");
                            }
                        </script>
                    </div>
                </div>
                <div class="col-12 col-md-4">
                    <div class="card card-body card-text mb-3">
                        <div class="md-form file-field mt-0">
                            <div class="btn btn-primary btn-sm float-left">
                                <span><i class="fas fa-file-upload"></i> Browse</span>
                                <input type="file" name="cover" id="cover" class="validate" accept="image/*">
                            </div>
                            <div class="file-path-wrapper">
                                <input class="file-path validate disabled" type="text" id="coverURL" name="coverURL"
                                    placeholder="รูปหน้าปก (Cover Image)" value="<?php echo $post->getProperty("cover"); ?>">
                            </div>
                            <img src="<?php echo empty($post->getProperty("cover")) ? "../static/elements/1280x720.jpg" : $post->getProperty("cover"); ?>" class="img-fluid w-100" id="coverThumb">
                            <input type="hidden" name="real_cover" id="real_cover" value="<?php echo $post->getProperty("cover"); ?>"/>
                            <div class="text-right"><small><a href="#" class="text-danger" onclick="clearCover()">ล้างรูปหน้าปก</a></small></div>
                            <script>
                                function clearCover() {
                                    $("#coverURL").val("");
                                    $("#real_cover").val("");
                                    $("#coverThumb").attr("src","../static/elements/1280x720.jpg");
                                }
                            </script>
                        </div>
                        <div class="switch switch-warning">
                            <label>
                                <input type="checkbox" name="isHidden" id="isHidden" <?php if ($post->getProperty("hide")) echo "checked"; ?>>
                                <span class="lever"></span>
                                <a class="material-tooltip-main" data-toggle="tooltip"
                                    title="การเปิดค่านี้จะทำให้โพสต์นี้สามารถเข้าได้ผ่าน Link โดยตรงเท่านั้น (จะไม่แสดงรวมกับโพสต์อื่น ๆ ในหน้าหลักและหน้าอื่น ๆ)">ซ่อนโพสต์นี้</a>
                            </label>
                        </div>
                        <div class="switch switch-warning">
                            <label>
                                <input type="checkbox" name="isPinned" <?php if ($post->getProperty("pin")) echo "checked"; ?>>
                                <span class="lever"></span>
                                <a class="material-tooltip-main" data-toggle="tooltip"
                                    title="การเปิดค่านี้จะเป็นการปักหมุดโพสต์นี้ไว้บนสุด (เรียงตามลำดับการอัพเดทของโพสต์ปักหมุดด้วย)">ปักหมุดโพสต์นี้</a>
                            </label>
                        </div>                    
                        <div class="row">
                            <div class="col">
                                <label for="group">หมวดหมู่ <a class="material-tooltip-main" data-toggle="tooltip"
                                    title="หมวดหมู่จะถูกลบออกเมื่อไม่มีโพสต์ใด ๆ ในหมวดหมู่นั้น ๆ"><i class='fas fa-question-circle'></i></a></label>
                                <select class="mdb-select md-form colorful-select dropdown-primary mb-0" id="group" name="group" required editable="true" searchable="🔎 พิมพ์เพื่อค้นหาหรือเพิ่มหมวดหมู่">
                                    <option value="uncategorized" selected>ไม่ได้จัดหมวดหมู่</option>
                                    <?php foreach(listCategory() as $l) { ?>
                                    <option value="<?php echo $l; ?>"><?php echo $l; ?></option>
                                    <?php } ?>
                                    <script>$('#group option[value=<?php echo $post->getProperty("category"); ?>]').attr('selected', 'selected');</script>
                                </select>
                            </div>
                        </div>
                        <div class="mb-3">
                        <small class="text-muted">แท็ก</small>
                            <textarea name="tags" class="font-weight-bold" id="tags"><?php if ($post->getProperty('tag')) { foreach($post->getProperty('tag') as $p) echo "$p,"; } ?></textarea>
                            <script>
                                $('#tags').tagEditor();
                            </script>
                        </div>
                        <input type="hidden" name="method" value="<?php echo $method; ?>"/>
                    </div>
                    <button type="submit" class="btn btn-success btn-block" name="submit" value="บันทึก">บันทึก</button>
                </div>
            </div>
        </form>
    </div>
    <script>
        // Material Select Initialization
        $(document).ready(function() {
            $('.select-wrapper.md-form.md-outline input.select-dropdown').bind('focus blur', function () {
                $(this).closest('.select-outline').find('label').toggleClass('active');
                $(this).closest('.select-outline').find('.caret').toggleClass('active');
            });
        });
        document.getElementById("cover").onchange = function () {
            var reader = new FileReader();
            reader.onload = function (e) {
                document.getElementById("coverThumb").src = e.target.result;
            };
            reader.readAsDataURL(this.files[0]);
        };        
        document.getElementById("makeHotlink").onchange = function (e) {
            if ($("#makeHotlink").is(":checked")) {
                $("#attachment").attr('disabled','disabled');
                $("#article").summernote('disable');
                $("#hotlinkField").css("display","block");
            } else {
                $("#attachment").removeAttr('disabled');
                $("#article").summernote('enable');
                $("#hotlinkField").css("display","none");
            }
        };
    </script>
    <?php require_once '../static/functions/popup.php'; ?>
    <?php require_once '../static/functions/footer.php'; ?>
</body>

</html>