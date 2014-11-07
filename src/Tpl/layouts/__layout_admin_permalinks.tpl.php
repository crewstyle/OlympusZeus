<?php if ('tea_custom_permalink_flush' == $opt['name']): ?>
    <p><?php _e('The following structure uses the same rules than post\'s permalink structure that you can built with <code>%year%</code>, <code>%monthnum%</code>, <code>%day%</code>, <code>%hour%</code>, <code>%minute%</code>, <code>%second%</code>, <code>%post_id%</code>, <code>%category%</code>, <code>%author%</code> and <code>%pagename%</code>. If you need to display the <code>%postname%</code>, simply use the custom post type\'s slug instead.', TTO_I18N) ?></p>
    <input type="hidden" name="<?php echo $opt['name'] ?>" value="<?php echo $opt['value'] ?>" />
<?php else: ?>
    <code><?php echo TTO_HOME ?></code>
    <input type="text" name="<?php echo $opt['name'] ?>" value="<?php echo $opt['value'] ?>" class="regular-text code" />
<?php endif ?>
