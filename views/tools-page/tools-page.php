<div class="wrap  easydebuginfo-wrap">
    <h2><?php _e('Easy Debug Info', 'easydebuginfo') ?></h2>

    <div class="easydebuginfo-header">
        <h2 class="easydebuginfo-header__heading"><?php _e('Welcome To Easy Debug Info', 'easydebuginfo') ?></h2>
        <p class="easydebuginfo-header__introduction">Making collecting debug info easy</p>

        <div class="easydebuginfo-header__column  easydebuginfo-header__column--explanation">
            <h3>What is this</h3>
            <p>Easy Debug Info makes in incredibly easy to collect various debug infos and statistics from your WordPress powered site. This includes, but is not limited to, various data point from your server environment, details about your WordPress installation and it's themes and plugins, database statistics, and much more. Extending the reports from your own plugins is easy, too!</p>
        </div>

        <div class="easydebuginfo-header__column">
            <h3>Tools</h3>
            <ul>
                <li><i class="easydebuginfo-icon-report"></i> <a class="js-easydebuginfo-generate-report" href="#">Generate Report</a></li>
            </ul>
        </div>

        <div class="easydebuginfo-header__column">
            <h3>Further Help</h3>
            <ul>
                <li><i class="easydebuginfo-icon-twitter"></i> <a class="" href="">Send a Tweet</a></li>
                <li><i class="easydebuginfo-icon-help"></i> <a class="" href="">Get Support</a></li>
                <li><i class="easydebuginfo-icon-contribute"></i> <a class="" href="">Contribute</a></li>
                <li><i class="easydebuginfo-icon-donate"></i> <a class="" href="">Support Development</a></li>
            </ul>
        </div>
    </div>

    <?php if(is_array($report)): ?>
        <p class="easydebuginfo-old-report-alert  js-easydebuginfo-old-report-alert">
            <strong>Please note:</strong> The following report has been generated <?php echo $report['created_at'] ?> and is shown only for reference. You probably want to generate a new one!
        </p>
    <?php endif ?>

    <div class="easydebuginfo-report">
        <?php if(is_array($report)): ?>
            <pre class="js-easydebuginfo-report-holder"><?php echo $this->renderReport($report['report']) ?></pre>
        <?php else: ?>
            <pre class="js-easydebuginfo-report-holder">No report generated, yet!</pre>
        <?php endif ?>
    </div>

</div>
