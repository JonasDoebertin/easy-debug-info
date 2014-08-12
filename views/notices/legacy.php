<?php if(!defined('ABSPATH')) die('Direct access is not allowed.') ?>

<div class="updated easydebuginfo-legacy">
    <div class="easydebuginfo-logo">
        <i class="easydebuginfo-icon-logo"></i>
        <p class="easydebuginfo-title"><?php _e('Easy Debug Info') ?>
    </div>
    <p class="easydebuginfo-message">
        <?php printf(__('Easy Debug Info requires <strong>PHP 5.3.0</strong> or newer. You are currently using PHP %s. Why don\'t you try contacting your hosting provider and ask for an upgrade?', 'easydebuginfo'), PHP_VERSION) ?>
    </p>
</div>
