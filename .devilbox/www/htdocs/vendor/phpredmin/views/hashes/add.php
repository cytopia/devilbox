<?php $this->addHeader("<script src=\"{$this->router->baseUrl}/js/redmin/hashes.js\" type=\"text/javascript\"></script>"); ?>
<form class="form">
    <legend><?php if (isset($this->oldkey)) {
    echo "";
} else {
    echo "Add Hash";
} ?></legend>
    <div class="input-prepend">
        <span class="add-on"><i class="icon-key"></i></span>
        <?php if (isset($this->oldkey)): ?>
            <input type="text" value="<?php echo $this->oldkey?>" name="oldkey" disabled/>
            <input type="hidden" value="<?php echo $this->oldkey?>" name="key"/>
        <?php else: ?>
            <input type="text" placeholder="Key" name="key"/>
        <?php endif; ?>
    </div>
    <div class="input-prepend">
        <span class="add-on"><i class="icon-key"></i></span>
        <input type="text" placeholder="Hash Key" name="hashkey">
    </div>
    <div>
        <textarea placeholder="Value" name="value"></textarea>
    </div>
    <button type="submit" class="btn" id="add_hash"><i class="icon-plus"></i> Add</button>
    <?php if (!isset($this->oldkey)): ?>
        <button type="submit" class="btn" id="add_edit_hash"><i class="icon-plus-sign"></i> Add & Edit</button>
    <?php endif; ?>
</form>
