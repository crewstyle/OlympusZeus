<?php if (!empty($title)): ?>
    <h3><?php echo $title ?></h3>
<?php endif ?>

<ul class="features-list">
    <?php foreach ($contents as $c): ?>
        <li>
            <h4><?php echo $c['title'] ?></h4>
            <p><?php echo $c['content'] ?></p>

            <?php if (!empty($c['code'])): ?>
                <pre><?php echo nl2br($c['code']) ?></pre>
                <a href="#"><?php _e('See how does it work', TTO_I18N) ?></a>
            <?php endif ?>

        </li>
    <?php endforeach ?>
</ul>

<div class="clearfix"></div>