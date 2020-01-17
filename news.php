<?php
if (count(get_included_files()) <= 1) die("Direct access forbidden");
if (!isset($_GET['id'])):
    $metaTitle = 'News';
    include ('layout/header.php');

    # COMMON NEWS PAGE
    ///////////////////////////////////////////////////////////////////////////////
    $news = getAllNews($active = true);
    echo '<h1>News';
    if (isset($role) && in_array('index', $permissions[$role]['news'])) { echo ' <a href="index.php?page=admin&section=news&action=index" title="List news"><img src="images/icons/edit.png" /></a>'; }
    if (isset($role) && in_array('add', $permissions[$role]['news'])) { echo ' <a href="index.php?page=admin&section=news&action=add" title="Add news"><img src="images/icons/add.png" /></a>'; }
    echo '</h1>';
?>


<?php
    foreach ($news as $item):
        $link  = 'index.php?page=news&id='.$item['id'];
        if ($item['images']) {
            list($imageId, $imageName) = explode(':', $item['images'][0]);
        }
        $image = ($item['images']) ? ('uploads/'.$item['id'].'/'.$imageName) : 'no-image.jpg';
?>
<div class="news-section">
    <a href="<?= $link ?>">
        <img src="images/news/<?= $image ?>" title="<?= $item['title'] ?>" /></a>
    <a href="<?= $link ?>"><h2><?= $item['title'] ?></h2></a>
    <p>
        <?= nl2br(trim($item['summary'])) ?>
    </p>
    <span class="date"><time datetime="2019-11-01">Published: <?= date("d M Y", strtotime($item['created'])) ?></time></span>
    <a href="<?= $link ?>">Read more &raquo;</a>
</div>
<?php
    endforeach;
?>


<?php
#DETAILED NEWS PAGE
else:
    $news = getNewsById($_GET['id']);
    if (!$news || !$news['active']) {
        header($_SERVER["SERVER_PROTOCOL"]." 404 Not Found", true, 404);
        include('404.php');exit;
    }
    else {
        $metaTitle = $news['title'];
        include ('layout/header.php');
    }
?>

<article>
    <h1><?php
        echo $news['title'];
        if (isset($role) && in_array('edit', $permissions[$role]['news'])) { echo ' <a href="index.php?page=admin&section=news&action=edit&id='.$news['id'].'" title="Edit"><img src="images/icons/edit.png" /></a>'; }
    ?></h1>
    <div class="date"><time datetime="2019-11-01">Published on <?= date("l, d M Y, H:i", strtotime($news['created'])) ?></time></div>
    <div class="description">
        <p><?= nl2br(trim($news['summary'])) ?></p>
    </div>

    <?php
    if ($news['images']) {
        list($imageId, $imageName) = explode(':', $news['images'][0]);
    ?>
    <section class="gallery">
        <figure>
            <img src="images/news/uploads/<?= $news['id'].'/'.$imageName ?>" alt="<?= $imageName ?>" />
            <figcaption><?= $imageName ?></figcaption>
        </figure> 

        <?php if (count($news['images']) > 1) { ?>
        <div class="thumbs">
            <?php
            foreach ($news['images'] as $id => $item) {
                list($imageId, $imageName) = explode(':', $item);
            ?>
            <img src="images/news/uploads/<?= $news['id'].'/'.$imageName ?>" alt="<?= $imageName ?>"<?php if ($id == 0) { echo ' class="active"'; } ?> />
            <?php } ?>
        </div>
        <?php } ?>
    </section>
    <?php } ?>
    
    <?= $news['description'] ?>

    <em><a href="index.php?page=news">&laquo; Back to all news</a></em>

</article>
<?php
endif;
include ('layout/footer.php');
?>