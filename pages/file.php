<?php 
    if (!isset($_GET['path'])) die();
    
    require_once '../static/functions/function.php';
    
    $path = fixPath($_GET['path']);
    $mode = isset($_GET['mode']) ? (int) $_GET['mode'] : 0;
    // 0 -> Default, can't lookup directory to root.
    // 1 -> Check Admin, if true, can lookup until root.

    $folder = isset($_GET['allowCreateFolder']) ? (int) $_GET['allowCreateFolder'] : 0;
    // 0 -> Default, allow.
    // 1 -> Deny.

    $upload = isset($_GET['upload']) ? (int) $_GET['upload'] : 0;
    // 0 -> No upload allow.
    // 1 -> Allow upload with permission.
    // 9 -> Allow upload without permission.

    $topPath = false;
    if ((preg_match("/^..\/file\/post\/[0-9+]\/attachment$/", $path) ||
        preg_match("/^..\/file\/upload$/", $path) ||
        preg_match("/^..\/static\/elements\/carousel\/banner$/", $path) ||
        preg_match("/^..\/static\/elements\/carousel\/event$/", $path))
        && ($mode == 0 || ($mode == 1 && !isAdmin())))
        $topPath = true;

    $files = array();
    $folders = array();
    $glob = (file_exists($path)) ? glob("$path/*") : array();
        
    $files = array_filter($glob, 'is_file');
    $folders = array_filter($glob, 'is_dir');

    $perm = isAdmin();

    
?>
<div id="tableRender" class="table-responsive">
    <?php if (($upload == 1 && $perm) || ($upload == 9)) { ?>
        <div class="mb-1 mt-3 d-flex justify-content-between">    
            <div class="flex-grow-1"><?php if (isset($_GET['title'])) { ?><?php echo createHeader($_GET['title']); ?><?php } ?></div>
            <?php if ($folder == 0) {?><a class="btn btn-info btn-md ml-0 mr-1" onclick='myCreateFolderFunction("<?php echo $path; ?>");'><i class='fas fa-folder'></i> Create folder</a><?php } ?>
            <a class="btn btn-success btn-md ml-0 mr-1"onclick='myUploadFileFunction();'><i class="fas fa-upload"></i> Upload file</a>
            <form id="formUploadFile" method="POST" action="../pages/file_IO.php?function=create" enctype="multipart/form-data" class="d-none">
                <input type="file" name="attachment[]" id="attachment" class="validate" multiple/>
                <input type="submit" value="อัพโหลด!"/>
                <input type="hidden" name="path" value="<?php echo $path; ?>"/>
            </form>
        </div>
    <?php } else if (isset($_GET['title'])) { ?>
        <div class="mb-2 mt-3 d-flex justify-content-end">
            <div class="flex-grow-1"><?php if (isset($_GET['title'])) { ?><?php echo createHeader($_GET['title']); ?><?php } ?></div>
        </div>
    <?php } ?>
    <table class="table table-sm table-hover w-100 d-block d-md-table text-nowrap" id="attachmentTable">
        <thead>
            <tr class="text-nowrap">
                <th scope="col">
                    <?php if (!$topPath) {
                        $explode_path = explode("/", $path); array_pop($explode_path); $implode_path = implode("/", $explode_path); ?>
                        <a class="attachmentDataTableFolderClick" data-target='<?php echo $implode_path; ?>'>
                            <section data-order="-1"><i class="fas fa-chevron-circle-left"></i> Back</section>
                        </a>
                    <?php } ?></th>
                <th scope="col">ไฟล์</th>
                <th scope="col">ขนาด</th>
                <th scope="col">แก้ไขล่าสุด</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($folders as $folder) {
                    $updated = filemtime($folder); 
                    $folder_name = pathinfo($folder, PATHINFO_FILENAME); ?>
            <tr>
                <td data-order="1">
                    <?php if ($perm) { ?>
                        <a class="text-warning" onclick='myEditFolderFunction("<?php echo $folder; ?>","<?php echo $folder_name; ?>")'><i class="fas fa-pencil-alt"></i></a>
                        <a class="text-danger" onclick='myDeleteFolderFunction("<?php echo $folder; ?>")'><i class="fas fa-trash"></i></a>
                    <?php } ?>
                </td>
                <td data-order="-1" class="attachmentDataTableFolderClick" data-target='<?php echo $folder ?>'> <?php echo icon($folder); ?></td>
                <td data-order="0" class="attachmentDataTableFolderClick" data-target='<?php echo $folder ?>'></td>
                <td data-order="<?php echo $updated; ?>" class="attachmentDataTableFolderClick" data-target='<?php echo $folder ?>'><?php echo fromThenToNow($updated); ?></td>
            </tr>
            <?php } ?>
            <?php foreach ($files as $file) {
                    $size = filesize($file);
                    $updated = filemtime($file);
                    $file_name = pathinfo($file, PATHINFO_FILENAME);
            ?>
            <tr>
                <td data-order="1">
                    <?php if ($perm) { ?>
                        <a class="text-warning" onclick='myEditFileFunction("<?php echo $file; ?>", "<?php echo $file_name; ?>")'><i class="fas fa-pencil-alt"></i></a>
                        <a class="text-danger" onclick='myDeleteFileFunction("<?php echo $file; ?>")'><i class="fas fa-trash"></i></a>
                    <?php } ?>
                </td>
                <td data-order="1" onclick='window.open("<?php echo $file ?>")'><?php echo icon($file); ?></td>
                <td data-order="<?php echo $size; ?>" onclick='window.open("<?php echo $file ?>")'><?php echo FileSizeConvert($size); ?></td>
                <td data-order="<?php echo $updated; ?>" onclick='window.open("<?php echo $file ?>")'><?php echo fromThenToNow($updated); ?></td>
            </tr>
            <?php } ?>
        </tbody>
    </table>

</div>
<script>
    $(document).ready(function () {
        dataTable();
    });

    function dataTable() {
        $('#attachmentTable').DataTable({
            "lengthMenu": [
                [15, 30, 100, -1],
                [15, 30, 100, "ทั้งหมด"]
            ],
            'columnDefs': [{
                'targets': [0, 1], // column index (start from 0)
                'orderable': false // set orderable false for selected columns
            }],
            "aoColumns": [{
                    "sWidth": "5%"
                }, // 1st column width 
                {
                    "sWidth": "60%"
                }, // 2nd column width 
                {
                    "sWidth": "15%"
                }, // 3rd column width and so on 
                {
                    "sWidth": "20%"
                }

            ]
        });
        $('.dataTables_length').addClass('bs-select');
    }
</script>
<?php if ($perm) { ?>
<script>
    function myDeleteFileFunction(f) {
        swal({
            title: "ลบไฟล์นี้หรือไม่",
            text: "หลังจากที่ลบแล้ว จะไม่สามารถกู้คืนได้!",
            icon: "warning",
            buttons: true,
            dangerMode: true
        }).then((willDelete) => {
            if (willDelete) {
                window.location = "../pages/file_IO.php?function=delete&method=file&name=" + f;
            }
        });
    }

    function myDeleteFolderFunction(f) {
        swal({
            title: "ลบไฟล์นี้หรือไม่",
            text: "หลังจากที่ลบแล้ว จะไม่สามารถกู้คืนได้!",
            icon: "warning",
            buttons: true,
            dangerMode: true
        }).then((willDelete) => {
            if (willDelete) {
                window.location = "../pages/file_IO.php?function=delete&method=dir&name=" + f;
            }
        });
    }

    function myEditFileFunction(targetFile, tempDisplayName) {
        var folderName = prompt("กรุณาระบุชื่อไฟล์ ห้ามใช้ \\ / : * ? \" < > |", tempDisplayName);
        if (folderName != null && folderName != "") {
            window.location = "../pages/file_IO.php?function=rename&old=" + targetFile + "&new=" + folderName;
        }
    }

    function myEditFolderFunction(targetFile, tempDisplayName) {
        var folderName = prompt("กรุณาระบุชื่อโฟลเดอร์ ห้ามใช้ \\ / : * ? \" < > |", tempDisplayName);
        if (folderName != null && folderName != "") {
            window.location = "../pages/file_IO.php?function=rename&old=" + targetFile + "&new=" + folderName;
        }
    }

    function myCreateFolderFunction(f) {
        var folderName = prompt("กรุณาระบุชื่อโฟลเดอร์ ห้ามใช้ \\ / : * ? \" < > |", "PondJa");
        if (folderName != null && folderName != "") {
            window.location = "../pages/file_IO.php?function=create&mkdir=" + folderName + "&path=" + f;
        }
    }

    function myUploadFileFunction() {
        $("#attachment").click();
    }

    $(document).ready(function () {
        $("#attachment").change(function() { 
            if ($(this)[0].files.length > 0) {
                swal({
                    title: "คำเตือน",
                    text: "คุณกำลังจะอัพโหลด " + $(this)[0].files.length + " ไฟล์เข้าสู่ระบบ",
                    icon: "warning",
                    buttons: true,
                    dangerMode: true
                }).then((willDelete) => {
                    if (willDelete) {
                        $("#formUploadFile").submit();
                    }
                });
            }
        });
    });

    
</script>
<?php } ?>
<script>
    $('.attachmentDataTableFolderClick').on('click', function () {
        var target = $(this).data('target');
        $.ajax({
            type: 'GET',
            url: '../pages/file_lookup.php',
            data: {
                'path': target,
                'title': "<?php if (isset($_GET['title'])) echo $_GET['title']; ?>",
                'mode': "<?php echo $mode; ?>",
                'upload': "<?php echo $upload; ?>"
            },
            success: function (data) {
                $('#attachmentTable').DataTable().destroy();
                $('#tableRender').html(data);
            }
        });
    });
</script>