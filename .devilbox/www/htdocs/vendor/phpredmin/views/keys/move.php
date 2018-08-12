<div id='mainContainer'>
    <h3>Move key</h3>
    <?php if (isset($this->moved) && $this->moved): ?>
        <div class="alert alert-info">
            <a class="close" data-dismiss="alert" href="#">×</a>
            Key moved successfuly
        </div>
    <?php elseif (isset($this->moved)): ?>
        <div class="alert alert-danger">
            <a class="close" data-dismiss="alert" href="#">×</a>
            There was a problem moving the key
        </div>
    <?php endif; ?>
    <?php if (!isset($this->moved) || (isset($this->moved) && !$this->moved)): ?>
        <form class="form-search" action="<?php echo $this->router->url?>/keys/move/<?php echo $this->app->current['serverId'] . '/' . $this->app->current['database'] ?>" method="post">
            <input name="key" value="<?php echo $this->key?>" type="hidden" />
            <div class="input-prepend">
                <span class="add-on"><i class="icon-book"></i></span>
                <input type="text" placeholder="DB Number" name="db">
            </div>
            <button type="submit" class="btn"><i class="icon-move"></i> Move</button>
        </form>
    <?php endif; ?>
</div>
