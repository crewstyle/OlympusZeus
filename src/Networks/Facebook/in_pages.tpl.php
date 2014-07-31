<?php if (!$keys): ?>
    <label for="tea-connection-facebook" class="tea-connection tea-facebook">
        <span class="fa-stack fa-2x">
            <input type="checkbox" name="tea-connections[]" value="facebook" id="tea-connection-facebook" />
        </span>
        <i class="fa fa-facebook fa-lg"></i> <?php _e('Facebook connection', TTO_I18N) ?>
    </label>
<?php else: ?>
    <a href="#" class="tea-connection tea-facebook">
        <span class="fa-stack fa-2x">
            <i class="fa fa-check fa-stack-1x fa-inverse"></i>
        </span>
        <i class="fa fa-facebook fa-lg"></i> <?php _e('Facebook connection', TTO_I18N) ?>
    </a>
<?php endif ?>