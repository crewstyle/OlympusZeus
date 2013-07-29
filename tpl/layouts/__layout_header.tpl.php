<!-- colorpicker -->
<style>
    <?php if (!empty($icon)): ?>.tea_icon div{background:transparent url(<?php echo $icon ?>) no-repeat 0 0;}<?php endif ?>
</style>

<div class="wrap tea_to">
    <div class="tea_icon">
        <?php screen_icon() ?>
    </div>

    <h2><?php echo empty($title) ? __('Tea Options Panel') : $title ?></h2>

    <ul class="subsubsub">
        <?php foreach ($links as $link): ?>
            <li class="all"><a href="admin.php?page=<?php echo $link['slug'] ?>"<?php echo $link['slug'] == $page ? ' class="current"' : '' ?>><?php echo $link['title'] ?></a> |</li>
        <?php endforeach ?>
            <li class="all"><?php _e('Made by <a href="http://takeatea.com" target="_blank">Take a tea</a>') ?></li>
    </ul>

    <br class="clear" />

    <?php if (isset($_GET['updated'])): ?>
        <div class="alert alert-success">
            <p><?php _e('Your theme is updated.') ?></p>
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
