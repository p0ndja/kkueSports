<?php 
require_once '../static/functions/connect.php';
$current_page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
$tag = $_GET['tags'];
$category = $_GET['category'];
$news_per_page = 10; //How many post per page
$start_id = ($current_page - 1) * $news_per_page;

$stmt = loadPostNormal($category, $tag, $current_page);;
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
            $properties = json_decode($row["properties"], true);
            $properties_pin = isset($properties["pin"]) ? "border border-success z-depth-1" : ""; 
            $properties_link = isset($properties["hotlink"]) ? $properties["hotlink"] : "../post/" . $row['id'];
            $properties_cover = (isset($properties['cover']) && !empty($properties['cover'])) ? $properties["cover"] : "../static/elements/banner.jpg";
            
            $writer = new User((int) $properties['author']);
            $properties_writer = ($writer->getID() != -1) ? ' โดย ' . $writer->getName() . ' ('.$writer->getUsername().')' : "";
        ?>
        <div class="col-12 col-md-4">
            <a href="<?php echo $properties_link; ?>" class="text-dark">
                <div class="card mb-1 mt-1">
                    <div class="view overlay zoom">
                        <img src="<?php echo $properties_cover; ?>" class="card-img-top" style="min-width: 100%; height: 210px; object-fit: cover;" >
                    </div>
                </div>
                <div class="ml-1 mr-1 mt-2 mb-3">
                    <a href="<?php echo $properties_link; ?>" class="md"><text class='font-weight-bold display-6'><?php echo $row['title']; ?></text></a>
                    <br><small class="mt-1 text-muted"><?php echo fromThenToNow($properties["updated"]) . $properties_writer; ?> </small>
                </div>
            </a>
        </div>
    <?php }
    if ($result->num_rows < $news_per_page) { ?>
    <div id="EOF"></div>
    <?php } 
} else { ?>
<div id="EOF"></div>
<?php } ?>