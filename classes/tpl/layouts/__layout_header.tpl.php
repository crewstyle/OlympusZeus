<!-- colorpicker -->
<style>
    <?php if (!empty($icon)): ?>.tea_icon div{background:transparent url(<?php echo $icon ?>) no-repeat 0 0;}<?php endif ?>
</style>

<div class="wrap tea_to">
    <div class="tea_icon">
        <?php screen_icon() ?>
    </div>

    <h2><?php echo $title ?></h2>

    <ul class="subsubsub">
        <?php
            $count = count($links);

            foreach ($links as $key => $link):
                $slug = $link['slug'];
                $title = $link['title'];
                $class = $slug == $page ? ' class="current"' : '';
                $sep = $key+1 < $count ? ' |' : '';
        ?>
            <li class="all">
                <a href="admin.php?page=<?php echo $slug ?>"<?php echo $class ?>><?php echo $title ?></a>
                <?php echo $sep ?>
            </li>
        <?php endforeach ?>
    </ul>

    <br class="clear" />

    <?php if (isset($_GET['updated'])): ?>
        <div class="alert alert-success">
            <p><?php _e('The Tea Theme Options is updated.', TTO_I18N) ?></p>
        </div>

        <br class="clear" />
    <?php endif ?>

    <?php if (!empty($description)): ?><p><?php echo $description ?></p><?php endif ?>

    <?php if ($submit): ?>
    <form method="post" action="admin.php?page=<?php echo $page ?>&updated=true" enctype="multipart/form-data">
        <input type="hidden" name="tea_to_settings" id="tea_to_settings" value="true" />
    <?php endif ?>

        <div id="poststuff">
            <div id="post-body" class="metabox-holder columns-2">

                <!-- Content block -->
                <div id="post-body-content">
