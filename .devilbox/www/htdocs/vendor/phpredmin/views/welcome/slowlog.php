<div id='mainContainer'>
    <h3>Redis Slow Log <small>(<?php echo $this->count?> most recent)</small></h3>
    <div class="alert alert-info">
        <a class="close" data-dismiss="alert" href="#">×</a>
        PHPRedmin uses Eval to fetch slowlogs
    </div>
    <?php if ($this->support): ?>
        <form class="form-inline" action="<?php echo $this->router->url?>/welcome/slowlog/<?php echo $this->app->current['serverId'] . '/' . $this->app->current['database'] ?>" method="post">
            <div class="input-prepend">
                <span class="add-on"><i class="icon-search"></i></span>
                <input value="<?php echo $this->count?>" name="count" type="text" />
            </div>
            <input type="submit" class="btn" value="Change Count" />
        </form>
        <table class="table table-striped">
            <tr>
                <th>
                    Time
                </th>
                <th>
                    Duration (Microseconds)
                </th>
                <th>
                    Info
                </th>
            </tr>
            <?php foreach ($this->slowlogs as $log): ?>
                    <tr>
                        <td><?php echo date('Y-m-d H:i:s', $log[1])?></td>
                        <td><?php echo $log[2]?></td>
                        <td><?php echo implode($log[3], ', ')?></td>
                    </tr>
            <?php endforeach; ?>
        </table>
    <?php else: ?>
        <div class="alert alert-danger">
            <a class="close" data-dismiss="alert" href="#">×</a>
            Eval has been available since redis version 2.6.0 but your redis version is <?php echo $this->version?>
        </div>
    <?php endif; ?>
</div>
