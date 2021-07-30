<?php 
    require_once '../static/functions/connect.php';
?>

<!DOCTYPE html>
<html lang="th" prefix="og:http://ogp.me/ns#">

<head>
    <?php require_once '../static/functions/head.php'; ?>
</head>
<body>
    <?php needAdmin(); ?>
    <?php require_once '../static/functions/navbar.php'; ?>
    <div class="container">
    <div class="mb-1 mt-3 d-flex justify-content-between">
        <div class="flex-grow-1"><?php echo createHeader("Post Management"); ?></div>
        <a href="../post/create" class="btn-floating btn-sm btn-info z-depth-0 ml-0 mr-1"><i class='fas fa-plus'></i></a>
    </div>
        <div class="card card-body mb-3">
            <div class="table-responsive">
                <table class="table table-hover table-sm mb-3">
                    <thead>
                        <tr>
                            <th scope="col">ID</th>
                            <th scope="col">Title</th>
                            <th scope="col">Category</th>
                            <th scope="col">Last Update</th>
                            <th scope="col"></th>
                        </tr>
                    </thead>
                    <tbody id="loadMoreZone">        
                    <?php
                        $_GET['page'] = 1;
                        $_GET['category'] = isset($_GET['c']) ? ($_GET['c'] == '~' ? null : $_GET['c']) : null;
                        include '../pages/admin_post_load.php';
                    ?>
                    </tbody>
                </table>
                <div class="d-flex justify-content-center">
                    <a onclick="loadMore();" class="btn btn-success text-center" id="loadMoreButton">Load More</a>
                </div>
                <script>
                    if ($('#EOF').length > 0) $("#loadMoreButton").remove();
                </script>
                <script>
                    var currentPage = 1;
                    $(window).scroll(function() {
                        if(($(window).scrollTop() == $(document).height() - $(window).height()) && $("#loadMoreButton").length > 0) {
                            loadMore();
                        }
                    });
                    function loadMore() {
                        $.ajax({
                        type: 'GET',
                        url: '../pages/admin_post_load.php',
                        data: {
                            'page': ++currentPage,
                            'category': "<?php echo isset($_GET['c']) ? $_GET['c'] : null; ?>"
                        },
                        success: function (data) {
                            if (data.trim() == '') {
                                $("#loadMoreButton").remove();
                            } else {
                                $('#loadMoreZone').append(data);
                                if ($('#EOF').length > 0) $("#loadMoreButton").remove();
                            }
                        }
                    });
                    }
                </script>
            </div>
        </div>
    </div>
    <?php require_once '../static/functions/popup.php'; ?>
    <?php require_once '../static/functions/footer.php'; ?>
</body>

</html>