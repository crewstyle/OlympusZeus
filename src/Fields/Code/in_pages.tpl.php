<!-- Content code <?php echo $id ?> -->
<div id="<?php echo $id ?>_code_content" class="tea_to_wrap">
    <h3 class="tea_title">
        <label for="<?php echo $id ?>_code"><?php echo $title ?></label>
    </h3>

    <div class="inside tea-inside code">
        <div class="CodeMirror-all">
            <?php
                $modes = array(
                    'text/css' => 'CSS',
                    'text/x-diff' => 'Diff',
                    'text/html' => 'HTML Mixed',
                    'text/javascript' => 'JavaScript',
                    'application/json' => 'JSON',
                    'text/x-markdown' => 'Markdown',
                    'application/x-httpd-php' => 'PHP',
                    'text/x-python' => 'Python',
                    'text/x-ruby' => 'Ruby',
                    'text/x-sh' => 'Shell',
                    'text/x-mysql' => 'MySQL',
                    'text/x-mariadb' => 'MariaDB',
                    'application/xml' => 'XML',
                    'text/x-yaml' => 'YAML'
                );
            ?>
            <select name="<?php echo $id ?>[mode]" class="change-mode">
                <?php foreach ($modes as $k => $m): ?>
                    <option value="<?php echo $k ?>" <?php echo $k == $vals['mode'] ? 'selected="selected"' : '' ?>>
                        <?php echo $m ?>
                    </option>
                <?php endforeach ?>
            </select>

            <textarea name="<?php echo $id ?>[code]" id="<?php echo $id ?>_code" rows="<?php echo $rows ?>" class="code"><?php echo esc_textarea($vals['code']) ?></textarea>
        </div>

        <p><?php echo $description ?></p>
    </div>
</div>
<!-- /Content code <?php echo $id ?> -->
