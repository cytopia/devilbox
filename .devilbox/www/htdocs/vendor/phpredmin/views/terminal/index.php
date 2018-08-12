<?php if (App::instance()->config['terminal']['enable']): ?>
<div id='mainContainer'>
    <h3>Terminal<div class="pull-right"><a id="term_light" class="term_theme" title="Light Theme" href="#">Light</a><a id="term_dark" class="term_theme" title="Dark Theme" href="#">Dark</a></div></h3>
    <?php $this->addHeader("<script src=\"{$this->router->baseUrl}/js/redmin/terminal.js\" type=\"text/javascript\"></script>"); ?>
    <div class="terminal terminal-console">
        redis <?php echo $this->app->current['host'] ?>:<?php echo $this->app->current['port'] ?>>
    </div>
    <div class="clearfix"></div>
    <div>
        <div class="span6 terminal terminal-command-line">
            <div class="span1 terminal-prompt">&gt;</div><input class="span11" id="terminal-input" />
        </div>
        <div class="terminal-clear icon-eraser icon-2x"></div>
    </div>
    <div class="clearfix"></div>
    <br/>
    <div class="alert alert-warning">
        <a class="close" data-dismiss="alert" href="#">×</a>
        This functionaliy takes advantage of <a href="http://www.php.net/manual/en/function.exec.php" target="_blank">PHP's exec function</a>. Although, all the commands are escaped for security, you can disable terminal from configuration file.
    </div>
</div>
<?php else: ?>
    <div class="alert alert-danger">
        Terminal is not enabled.
    </div>
<?php endif; ?>
