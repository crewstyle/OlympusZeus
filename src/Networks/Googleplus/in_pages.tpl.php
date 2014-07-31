<?php if (!$keys): ?>
    <label for="tea-connection-google-plus" class="tea-connection tea-google-plus">
        <span class="fa-stack fa-2x">
            <input type="checkbox" name="tea-connections[]" value="google-plus" id="tea-connection-google-plus" />
        </span>
        <i class="fa fa-google-plus fa-lg"></i> <?php _e('Google+ connection', TTO_I18N) ?>
    </label>
<?php else: ?>
    <a href="#" class="tea-connection tea-google-plus">
        <span class="fa-stack fa-2x">
            <i class="fa fa-check fa-stack-1x fa-inverse"></i>
        </span>
        <i class="fa fa-google-plus fa-lg"></i> <?php _e('Google+ connection', TTO_I18N) ?>
    </a>
<?php endif ?>