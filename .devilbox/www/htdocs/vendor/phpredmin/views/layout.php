<!DOCTYPE html>
<html>
<head>
    <title>PHPRedmin</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<link rel="stylesheet" media="all" type="text/css" href="<?php echo $this->router->baseUrl?>/bootstrap/css/bootstrap.min.css" />
    <link rel="stylesheet" media="all" type="text/css" href="<?php echo $this->router->baseUrl?>/bootstrap/css/bootstrap-responsive.min.css">
    <link rel="stylesheet" media="all" type="text/css" href="<?php echo $this->router->baseUrl?>/font-awesome/css/font-awesome.min.css">
	<link rel="stylesheet" media="all" type="text/css" href="<?php echo $this->router->baseUrl?>/js/nvd3/src/nv.d3.css" />
	<link rel="stylesheet" media="all" type="text/css" href="<?php echo $this->router->baseUrl?>/css/custom.css" />
	<link rel="stylesheet" media="all" type="text/css" href="<?php echo $this->router->baseUrl?>/js/jquery-ui/css/jquery-ui.min.css" />
    <script type="text/javascript" src="<?php echo $this->router->baseUrl?>/js/jquery.min.js"></script>
    <script type="text/javascript" src="<?php echo $this->router->baseUrl?>/bootstrap/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="<?php echo $this->router->baseUrl?>/js/redmin/main.js"></script>
    <script type="text/javascript" src="<?php echo $this->router->baseUrl?>/js/redmin/modal.js"></script>
    <?php foreach ($this->getHeaders() as $header) {
    echo $header."\n";
} ?>
    <script type="text/javascript">
        baseurl = "<?php echo $this->router->url?>";
        currentHost = "<?php echo $this->app->current['host'] ?>";
        currentPort = "<?php echo $this->app->current['port'] ?>";
        currentServer = "<?php echo $this->app->current['serverId'] ?>";
        currentServerDb = "<?php echo $this->app->current['serverId'] . '/' . $this->app->current['database'] ?>";
    </script>
</head>
<body>
    <div class="container">
        <div class="row">
            <div class="navbar span12 navbar-inverse">
                <div class="navbar-inner">
                    <div class="container">
                        <a class="btn btn-navbar" data-toggle="collapse" data-target=".navbar-responsive-collapse">
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                        </a>
                        <a class="brand" href="<?php echo $this->router->url?>">PHPRedmin</a>
                        <div class="nav-collapse collapse navbar-responsive-collapse">
                            <ul class="nav">
                                <li<?php echo (strstr($this->router->request, "/welcome/index/") ? ' class="active"' :null)?>>
                                    <a href="<?php echo $this->router->url?>/welcome/index/<?php echo $this->app->current['serverId'] . '/' . $this->app->current['database'] ?>">
                                        <i class="icon-white icon-home"></i> Home
                                    </a>
                                </li>
                                <li<?php echo (strstr($this->router->request, "/welcome/info/") ? ' class="active"' :null)?>>
                                    <a href="<?php echo $this->router->url?>/welcome/info/<?php echo $this->app->current['serverId'] . '/' . $this->app->current['database'] ?>">
                                        <i class="icon-white icon-info-sign"></i> Info
                                    </a>
                                </li>
                                <li<?php echo (strstr($this->router->request, "/welcome/config/") ? ' class="active"' :null)?>>
                                    <a href="<?php echo $this->router->url?>/welcome/config/<?php echo $this->app->current['serverId'] . '/' . $this->app->current['database'] ?>">
                                        <i class="icon-white icon-cogs"></i> Configurations
                                    </a>
                                </li>
                                <li<?php echo (strstr($this->router->request, "/welcome/stats/") ? ' class="active"' :null)?>>
                                    <a href="<?php echo $this->router->url?>/welcome/stats/<?php echo $this->app->current['serverId'] . '/' . $this->app->current['database'] ?>">
                                        <i class="icon-white icon-bar-chart"></i> Stats
                                    </a>
                                </li>
                                <li<?php echo (strstr($this->router->request, "/welcome/slowlog/") ? ' class="active"' :null)?>>
                                    <a href="<?php echo $this->router->url?>/welcome/slowlog/<?php echo $this->app->current['serverId'] . '/' . $this->app->current['database'] ?>">
                                        <i class="icon-white icon-warning-sign"></i> Slow Log
                                    </a>
                                </li>
                                <?php if (App::instance()->config['terminal']['enable']): ?>
                                    <li<?php echo (strstr($this->router->request, "/terminal/") ? ' class="active"' :null)?>>
                                        <a href="<?php echo $this->router->url?>/terminal/index/<?php echo $this->app->current['serverId'] . '/' . $this->app->current['database'] ?>">
                                            <i class="icon-white icon-terminal"></i> Terminal
                                        </a>
                                    </li>
                                <?php endif; ?>
                                <li>
                                    <a href="https://github.com/sasanrose/phpredmin" target="_blank">
                                        <i class="icon-white icon-github"></i> GitHub
                                    </a>
                                </li>
                            </ul>
                            <ul class="nav pull-right">
                                <li class="divider-vertical"></li>
                                <li class="dropdown">
                                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">Actions <b class="caret"></b></a>
                                    <ul class="dropdown-menu">
                                        <li>
                                            <a class="danger-action" href="#" id="flush_db">
                                                <i class="icon-trash"></i> Flush Db
                                            </a>
                                        </li>
                                        <li>
                                            <a class="danger-action" href="#" id="flush_all">
                                                <i class="icon-remove"></i> Flush All
                                            </a>
                                        </li>
                                        <li class="divider"></li>
                                        <li>
                                            <a id="save_async" href="#">
                                                <i class="icon-save"></i> Asynchronous Save
                                            </a>
                                        </li>
                                        <li>
                                            <a class="warning-action" id="save_sync" href="#">
                                                <i class="icon-save"></i> Synchronous Save
                                            </a>
                                        </li>
                                        <li class="divider"></li>
                                        <li>
                                            <a class="warning-action" href="#" id="reset_stats">
                                                <i class="icon-refresh"></i> Reset Stats
                                            </a>
                                        </li>
                                    </ul>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="span12">
                <ul class="breadcrumb">
                    <li>
                        <?php echo isset($this->app->current['password']) ? '<i class="icon-lock" title="With password"></i>' : '<i class="icon-eye-open" title="No password"></i>' ?>
                        <?php echo $this->app->current['host'] ?>:<?php echo $this->app->current['port'] ?> <span class="divider">/</span>
                    </li>
                    <li>
                        <?php echo (isset($this->app->current['dbs'][$this->app->current['database']]['name']) ? $this->app->current['dbs'][$this->app->current['database']]['name'] . " (DB {$this->app->current['database']})" : "DB {$this->app->current['database']}") ?>
                    </li>
                </ul>
            </div>
        </div>
        <div class="row">
            <div class="span2">
                <?php echo $this->renderPartial('navigation') ?>
            </div>
            <div class="span10">
                <div class="row-fluid">
                    <?php echo $this->content ?>
                </div>
            </div>
        </div>
    </div>
    <?php echo $this->renderPartial('generalmodals') ?>
</body>
</html>
