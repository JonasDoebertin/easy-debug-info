<div class="wrap  easydebuginfo-wrap">
    <h2><?php _e('Easy Debug Info', 'easydebuginfo') ?></h2>

    <div class="easydebuginfo-header">
        <h2 class="easydebuginfo-header__heading"><?php _e('Welcome To Easy Debug Info', 'easydebuginfo') ?></h2>
        <p class="easydebuginfo-header__introduction"><?php _e('Making collecting debug info easy', 'easydebuginfo') ?></p>

        <div class="easydebuginfo-header__column  easydebuginfo-header__column--explanation">
            <h3><?php _e('What is this', 'easydebuginfo') ?></h3>
            <p><?php _e('Easy Debug Info makes in incredibly easy to collect various debug infos and statistics from your WordPress powered site. This includes, but is not limited to, various data point from your server environment, details about your WordPress installation and it\'s themes and plugins, database statistics, and much more. Extending the reports from your own plugins is easy, too!', 'easydebuginfo') ?></p>
        </div>

        <div class="easydebuginfo-header__column">
            <h3><?php _e('Tools', 'easydebuginfo') ?></h3>
            <ul>
                <li>
                    <i class="easydebuginfo-icon-report"></i>
                    <a class="js-easydebuginfo-generate-report" href="#"><?php _e('Generate Report', 'easydebuginfo') ?></a>
                </li>
                <li>
                    <i class="easydebuginfo-icon-download"></i>
                    <a class="js-easydebuginfo-download-report" href="<?php echo $downloadReportLink ?>"><?php _e('Download Latest Report', 'easydebuginfo') ?></a>
                </li>
            </ul>
        </div>

        <div class="easydebuginfo-header__column">
            <h3><?php _e('Further Help', 'easydebuginfo') ?></h3>
            <ul>
                <li>
                    <i class="easydebuginfo-icon-twitter"></i>
                    <a href="https://twitter.com/DieserJonas"><?php _e('Send a Tweet', 'easydebuginfo') ?></a>
                </li>
                <li>
                    <i class="easydebuginfo-icon-help"></i>
                    <a href="#"><?php _e('Get Support', 'easydebuginfo') ?></a>
                </li>
                <li>
                    <i class="easydebuginfo-icon-contribute"></i>
                    <a href="https://github.com/JonasDoebertin/easy-debug-info"><?php _e('Contribute', 'easydebuginfo') ?></a>
                </li>
                <li>
                    <i class="easydebuginfo-icon-donate"></i>
                    <a href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=V8PNBN3D3MRYU"><?php _e('Support Development', 'easydebuginfo') ?></a>
                </li>
            </ul>
        </div>
    </div>

    <?php if(is_array($report)): ?>
        <p class="easydebuginfo-old-report-alert  js-easydebuginfo-old-report-alert">
            <strong><?php _e('Please note', 'easydebuginfo') ?>:</strong> <?php printf(__('The following report has been generated %s and is shown only for reference. You probably want to generate a new one!', 'easydebuginfo'), $report['created_at']) ?>
        </p>
    <?php endif ?>

    <div class="easydebuginfo-report">
        <?php if(is_array($report)): ?>
            <pre class="js-easydebuginfo-report-holder"><?php echo $this->renderReport($report['report']) ?></pre>
        <?php else: ?>
            <pre class="js-easydebuginfo-report-holder"><?php _e('No report generated, yet!', 'easydebuginfo') ?></pre>
        <?php endif ?>
    </div>

</div>
