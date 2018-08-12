<?php $this->addHeader("<script src=\"{$this->router->baseUrl}/js/redmin/actions.js\" type=\"text/javascript\"></script>"); ?>
<div id='mainContainer'>
    <h3>Search Results <small><?php echo count($this->keys)?> result(s) found</small></h3>

    <div class="alert alert-warning">
        <a class="close" data-dismiss="alert" href="#">×</a>
        Since this doesn't support pagination yet, try to limit your search. Otherwise your browser might crash
    </div>
    <?php if ($this->search && !count($this->keys)): ?>
    <div class="alert alert-warning">
        <a class="close" data-dismiss="alert" href="#">×</a>
        Your search did not match any keys
    </div>
    <?php endif; ?>
    <?php if (!$this->search): ?>
    <div class="alert alert-warning">
        <a class="close" data-dismiss="alert" href="#">×</a>
        Please enter valid search criteria
    </div>
    <?php endif; ?>
    <h5><i class="icon icon-key"></i> Redis Keys</h5>
    <form class="form-search" action="<?php echo $this->router->url?>/keys/search/<?php echo $this->app->current['serverId'] . '/' . $this->app->current['database'] ?>" method="get">
        <div class="input-prepend">
            <span class="add-on"><i class="icon icon-key"></i></span>
            <input type="text" value="<?php echo $this->search?>" name="key">
        </div>
        <button type="submit" class="btn"><i class="icon icon-search"></i> Search</button>
    </form>
    <?php if (count($this->keys)): ?>
    <table class="table table-striped keys-table">
        <tr>
            <th>Key</th>
            <th>Type</th>
            <th>TTL</th>
            <th>Encoding</th>
            <th>Size</th>
            <th>Expire</th>
            <th>Rename</th>
            <th>View</th>
            <th>Move</th>
            <th>Delete</th>
            <th></th>
        </tr>
        <?php foreach ($this->keys as $key): ?>
        <tr>
            <td>
                <?php echo $key?>
            </td>
            <td>
                <?php echo Redis_Helper::instance()->getType($key)?>
            </td>
            <td>
                <?php echo Redis_Helper::instance()->getTTL($key)?>
            </td>
            <td>
                <?php echo Redis_Helper::instance()->getEncoding($key)?>
            </td>
            <td>
                <?php echo Redis_Helper::instance()->getSize($key)?>
            </td>
            <td>
                <a href="<?php echo $this->router->url?>/keys/expire/<?php echo $this->app->current['serverId'] . '/' . $this->app->current['database'] ?>/<?php echo urlencode($key)?>" target="_blank" class="action">
                    <i class="icon icon-time"></i>
                </a>
            </td>
            <td>
                <a href="<?php echo $this->router->url?>/keys/rename/<?php echo $this->app->current['serverId'] . '/' . $this->app->current['database'] ?>/<?php echo urlencode($key)?>" target="_blank" class="action">
                    <i class="icon icon-edit"></i>
                </a>
            </td>
            <td>
                <a href="<?php echo $this->router->url?>/keys/view/<?php echo $this->app->current['serverId'] . '/' . $this->app->current['database'] ?>/<?php echo urlencode($key)?>" target="_blank" class="action">
                    <i class="icon icon-folder-open-alt"></i>
                </a>
            </td>
            <td>
                <a href="<?php echo $this->router->url?>/keys/move/<?php echo $this->app->current['serverId'] . '/' . $this->app->current['database'] ?>/<?php echo urlencode($key)?>" target="_blank" class="action">
                    <i class="icon icon-move"></i>
                </a>
            </td>
            <td>
                <a href="#" class="action del">
                    <i class="icon icon-trash" id="<?php echo $key?>" keytype="keys"></i>
                </a>
            </td>
            <td>
                <input type="checkbox" name="keys[]" value="<?php echo $key?>" class="key-checkbox" />
            </td>
        </tr>
        <?php endforeach; ?>
        <tr>
            <td colspan="8">
            </td>
            <td>
                <a href="#" class="action moveall">
                    <i class="icon icon-move" keytype="keys" modaltitle="Move key to?" modaltip="Database Number"></i>
                </a>
            </td>
            <td>
                <a href="#" class="action delall">
                    <i class="icon icon-trash" keytype="keys"></i>
                </a>
            </td>
            <td>
                <input type="checkbox" name="checkall" id="checkall" class="all-key-checkbox" />
            </td>
        </tr>
    </table>
    <?php endif; ?>
</div>
