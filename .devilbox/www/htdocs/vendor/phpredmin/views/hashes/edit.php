<div id='mainContainer'>
    <h3>Edit hash key</h3>
    <?php if (isset($this->edited) && $this->edited): ?>
        <div class="alert alert-info">
            <a class="close" data-dismiss="alert" href="#">×</a>
            Hash key edited successfuly
        </div>
    <?php elseif (isset($this->edited)): ?>
        <div class="alert alert-danger">
            <a class="close" data-dismiss="alert" href="#">×</a>
            There was a problem editing the hash key
        </div>
    <?php endif ?>
    <form class="form" action="<?php echo $this->router->url?>/hashes/edit/<?php echo $this->app->current['serverId'] . '/' . $this->app->current['database'] ?>" method="post">
        <h5><?php echo $this->key?> / <?php echo $this->member?></h5>
        <div>
            <textarea name="newvalue"><?php echo $this->value?></textarea>
        </div>
        <input name="key" value="<?php echo $this->key?>" type="hidden" />
        <input name="member" value="<?php echo $this->member?>" type="hidden" />
        <button type="submit" class="btn"><i class="icon-edit"></i> Edit</button>
    </form>
</div>
