<div class="wrap tea_to">
    <nav class="tea-main-nav" role="navigation">
        <ul>
            <li class="tea-logo"><img src="<?php echo $icon ?>" alt="" /> <?php _e('Tea T.O.', TTO_I18N) ?></li>
            <?php
                $count = count($links);

                foreach ($links as $key => $link):
                    $slug = $link['slug'];
                    $class = $slug == $page ? ' class="current"' : '';

                    //Check title
                    if (preg_match('/<span style=\"color\:\#([a-zA-Z0-9]{3,6})\">(.*)<\/span>/i', $link['title'], $matches)) {
                        $title = '<b style="border-bottom:1px solid #' . $matches[1] . '">' . $matches[2] . '</b>';
                    }
                    else {
                        $title = $link['title'];
                    }
            ?>
                <li class="tea-page">
                    <a href="admin.php?page=<?php echo $slug ?>"<?php echo $class ?>><?php echo $title ?></a>
                </li>
            <?php endforeach ?>
        </ul>
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
