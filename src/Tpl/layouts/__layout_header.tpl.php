<div class="wrap tea_to">
    <nav class="tea-main-nav" role="navigation">
        <a href="#" class="tea-menu-resp" title=""></a>
        <a href="admin.php?page=<?php echo $identifier ?>" class="tea-logo">
            <img src="<?php echo $icon ?>" alt="" /> <?php _e('Tea T.O.', TTO_I18N) ?>
        </a>
        <?php if ($identifier != $page): ?>
            <span class="tea-breadcrumb"><i class="fa fa-long-arrow-right fa-lg"></i> <?php echo $title ?></span>
        <?php endif ?>

        <ul class="tea-pages">
            <?php
                $count = count($links);

                foreach ($links as $key => $link):
                    $slug = $link['slug'];
                    $class = $slug == $page ? ' class="current"' : '';
                    $title = $link['title'];

                    //Check title
                    if (preg_match('/<span style=\"color\:\#([a-zA-Z0-9]{3,6})\">(.*)<\/span>/i', $title, $matches)) {
                        $title = '<b style="color:#' . $matches[1] . '">' . $matches[2] . '</b>';
                    }
            ?>
                <li><a href="admin.php?page=<?php echo $slug ?>"<?php echo $class ?>><?php echo $title ?></a></li>
            <?php endforeach ?>
        </ul>
        <a href="#" title="" class="fallback"></a>
    </nav>

    <?php if ($updated): ?>
        <div class="alert alert-success">
            <p><?php _e('The Tea Theme Options is updated.', TTO_I18N) ?></p>
        </div>
    <?php endif ?>

    <?php if (!empty($description)): ?><p><?php echo $description ?></p><?php endif ?>

    <?php if ($submit): ?>
    <form method="post" action="admin.php?page=<?php echo $page ?>&action=tea_action&for=settings" enctype="multipart/form-data">
        <input type="hidden" name="updated" id="updated" value="true" />
    <?php endif ?>

        <div id="poststuff">
            <div id="post-body" class="metabox-holder">

                <!-- Content block -->
                <div id="post-body-content">
