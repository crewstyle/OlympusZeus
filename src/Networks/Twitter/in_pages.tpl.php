<?php if (!$keys): ?>
    <label for="tea-connection-twitter" class="tea-connection tea-twitter">
        <span class="fa-stack fa-2x">
            <input type="checkbox" name="tea-connections[]" value="twitter" id="tea-connection-twitter" />
        </span>
        <i class="fa fa-twitter fa-lg"></i> <?php _e('Twitter connection', TTO_I18N) ?>
    </label>
<?php else: ?>
    <a href="#" class="tea-connection tea-twitter">
        <span class="fa-stack fa-2x">
            <i class="fa fa-check fa-stack-1x fa-inverse"></i>
        </span>
        <i class="fa fa-twitter fa-lg"></i> <?php _e('Twitter connection', TTO_I18N) ?>
    </a>
<?php endif ?>