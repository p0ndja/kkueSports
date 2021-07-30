<?php 
    require_once '../static/functions/connect.php';
?>

<!DOCTYPE html>
<html lang="th" prefix="og:http://ogp.me/ns#">

<head>
    <?php require_once '../static/functions/head.php'; ?>
    <style>
        @media (min-width: 960px) {
            .card-columns {
                -webkit-column-count: 3;
                -moz-column-count: 3;
                column-count: 3;
            }
        }

        @media (max-width: 960px) {
            .card-columns {
                -webkit-column-count: 1;
                -moz-column-count: 1;
                column-count: 1;
            }
        }
    </style>
</head>
<body>
    <?php require_once '../static/functions/navbar.php'; ?>
    <?php
        if (isset($_GET['category']) && isValidCategory($_GET['category'], $conn)) $category = $_GET['category'];
        else header("Location: ../category/~-1");        

        $tag = isset($_GET['tags']) ? $_GET['tags'] : "";

        $title = isset($category) ? $category : $tag;
        $permPost = isAdmin();
    ?>

    <div class="container mb-3" id="container" >
        <div class="mb-1 mt-3 d-flex justify-content-between">
            <div class="flex-grow-1"><?php echo generateCategoryTitle($title); ?></div>
            <?php if ($permPost) { ?>
                <a href="../admin/post?c=<?php echo $_GET['category']; ?>" class="btn-floating btn-sm btn-warning z-depth-0 ml-0 mr-1"><i class='fas fa-pencil-alt'></i></a>
                <a href="../post/create" class="btn-floating btn-sm btn-info z-depth-0 ml-0 mr-1"><i class='fas fa-plus'></i></a>
            <?php } ?>
        </div>
        <div class='row' id="loadMoreZone">
            <?php
                $_GET['page'] = isset($_GET['page']) ? (int) $_GET['page'] : 1;
                $_GET['category'] = $category;
                $_GET['tags'] = $tag;
                include 'article_load.php';
            ?>
        </div>
        <div class="d-flex justify-content-center">
            <a onclick="loadMore();" class="btn btn-success text-center" id="loadMoreButton">Load More</a>
        </div>
        <script>
            if ($('#EOF').length > 0) { 
                $("#loadMoreButton").remove();
            }
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
                url: '../pages/article_load.php',
                data: {
                    'page': ++currentPage,
                    'category': "<?php echo $category; ?>",
                    "tags": "<?php echo $tag; ?>"
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
    <?php require_once '../static/functions/popup.php'; ?>
    <?php require_once '../static/functions/footer.php'; ?>
</body>

</html>