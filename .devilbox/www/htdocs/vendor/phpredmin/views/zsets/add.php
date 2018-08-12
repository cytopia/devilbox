<?php $this->addHeader("<script src=\"{$this->router->baseUrl}/js/redmin/zsets.js\" type=\"text/javascript\"></script>"); ?>
<form class="form">
    <legend><?php if (isset($this->oldkey)) {
    echo "";
} else {
    echo "Add Sorted Set";
} ?></legend>
    <div class="input-prepend">
        <span class="add-on"><i class="icon-key"></i></span>
        <?php if (isset($this->oldkey)): ?>
            <input type="text" value="<?php echo $this->oldkey?>" name="oldkey" disabled/>
            <input type="hidden" value="<?php echo $this->oldkey?>" name="key"/>
        <?php else: ?>
            <input type="text" placeholder="Key" name="key">
        <?php endif; ?>
    </div>
    <div class="input-prepend">
        <span class="add-on"><i class="icon-trophy"></i></span>
        <input type="text" placeholder="score" name="score">
    </div>
    <div>
        <textarea placeholder="Value" name="value"></textarea>
    </div>
    <button type="submit" class="btn" id="add_zset"><i class="icon-plus"></i> Add</button>
    <?php if (!isset($this->oldkey)): ?>
        <button type="submit" class="btn" id="add_edit_zset"><i class="icon-plus-sign"></i> Add & Edit</button>
    <?php endif; ?>
</form>
