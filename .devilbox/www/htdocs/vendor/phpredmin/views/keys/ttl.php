<div id='mainContainer'>
    <h3>Key Expiration</h3>
    <div class="alert alert-success">
        <a class="close" data-dismiss="alert" href="#">×</a>
        0 means no ttl (Values lower than 0, make the key persistant)
    </div>
    <?php if (isset($this->updated) && $this->updated): ?>
        <div class="alert alert-info">
            <a class="close" data-dismiss="alert" href="#">×</a>
            Key updated successfuly
        </div>
    <?php elseif (isset($this->updated)): ?>
        <div class="alert alert-danger">
            <a class="close" data-dismiss="alert" href="#">×</a>
            There was a problem updating the key
        </div>
    <?php endif; ?>
    <?php if (!isset($this->updated) || (isset($this->updated) && !$this->updated)): ?>
        <form class="form-search" action="<?php echo $this->router->url?>/keys/expire/<?php echo $this->app->current['serverId'] . '/' . $this->app->current['database'] ?>" method="post">
            <?php if ($this->ttl !== false && $this->ttl > 0): ?>
                <div>
                    Time in seconds
                </div>
            <?php endif; ?>
            <div class="input-prepend">
                <span class="add-on"><i class="icon-time"></i></span>
                <input type="text" value="<?php echo ($this->ttl > 0) ? $this->ttl : "0" ?>" name="ttl">
            </div>
            <input name="key" value="<?php echo $this->key?>" type="hidden" />
            <button type="submit" class="btn"><i class="icon-pencil"></i> Update</button>
        </form>
    <?php endif; ?>
</div>
