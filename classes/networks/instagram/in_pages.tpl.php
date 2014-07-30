<?php if (!$keys): ?>
    <label for="tea-connection-instagram" class="tea-connection tea-instagram">
        <span class="fa-stack fa-2x">
            <input type="checkbox" name="tea-connections[]" value="instagram" id="tea-connection-instagram" />
        </span>
        <i class="fa fa-instagram fa-lg"></i> <?php _e('Instagram connection', TTO_I18N) ?>
    </label>
<?php else: ?>
    <a href="#" class="tea-connection tea-instagram">
        <span class="fa-stack fa-2x">
            <i class="fa fa-check fa-stack-1x fa-inverse"></i>
        </span>
        <i class="fa fa-instagram fa-lg"></i> <?php _e('Instagram connection', TTO_I18N) ?>
    </a>
<?php endif ?>