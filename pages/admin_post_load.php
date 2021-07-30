<?php
require_once '../static/functions/connect.php';

$current_page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
$item_per_page = 20;
$start_id = ($current_page - 1) * $item_per_page;
$category = isset($_GET['category']) ? $_GET['category'] : null;

if (!isAdmin()) back();

    $stmt;
    if (empty($category)) {
        $stmt = $conn->prepare("SELECT * FROM `post` ORDER BY JSON_EXTRACT(`properties`,'$.pin') DESC, `id` DESC LIMIT ?,?");
        $stmt->bind_param('ii',$start_id,$item_per_page);
    } else {
        $stmt = $conn->prepare("SELECT * FROM `post` WHERE JSON_EXTRACT(`properties`,'$.category') = ? ORDER BY JSON_EXTRACT(`properties`,'$.pin') DESC, `id` DESC LIMIT ?,?");
        $stmt->bind_param('sii',$category,$start_id,$item_per_page);
    }

    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
        $properties = json_decode($row['properties'], true);?>
        <tr class="<?php if ($properties['pin']) echo 'table-warning'; ?>">
            <th scope="row"><?php echo $row['id'];?></th>
            <td><?php echo $row['title']; ?></td>
            <td><?php echo generateCategoryBadgeForced($properties['category']); ?></td>
            <td><?php echo fromThenToNow($properties['updated']); ?></td>
            <td>
                <a href="../post/<?php echo $row['id']; ?>" class="z-depth-0 btn-sm btn-info btn-floating" target="_blank"><i class="fas fa-external-link-alt"></i></a>
                <a href="../post/edit-<?php echo $row['id']; ?>" class='z-depth-0 btn-sm btn-warning btn-floating'><i class='fa fa-edit'></i></a>
                <?php if ($properties['hide']) { ?>
                <a href="../pages/article_toggle.php?target=hide&id=<?php echo $row['id']; ?>" class='z-depth-0 btn-sm grey btn-floating'><i class='fa fa-eye-slash'></i></a>
                <?php } else { ?>
                <a href="../pages/article_toggle.php?target=hide&id=<?php echo $row['id']; ?>" class='z-depth-0 btn-sm btn-success btn-floating'><i class='fa fa-eye'></i></a>
                <?php } ?>
                <?php if ($properties['pin']) { ?>
                <a href="../pages/article_toggle.php?target=pin&id=<?php echo $row['id']; ?>" class='z-depth-0 btn-sm btn-success btn-floating'><i class='fas fa-thumbtack'></i></a>
                <?php } else { ?>
                <a href="../pages/article_toggle.php?target=pin&id=<?php echo $row['id']; ?>" class='z-depth-0 btn-sm grey btn-floating'><span class="fa-stack"><i class="fas fa-thumbtack fa-stack-1x"></i><i class="fas fa-slash fa-stack-2x"></i></span></a>
                <?php } ?>
                <?php if ($properties['allowDelete'] == true) { ?>
                <a href="#" class='z-depth-0 btn-sm btn-danger btn-floating' target="_blank"><i class='fa fa-trash'></i></a>
                <?php } ?>
            </td>
        </tr>
<?php
        }
        if ($result->num_rows < $item_per_page) { ?>
            <tr id="EOF"></tr>
<?php   }
    } else { ?>
                <tr id="EOF"></tr>

    <?php }

?>