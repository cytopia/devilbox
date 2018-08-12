<?php $this->addHeader("<script src=\"{$this->router->baseUrl}/js/redmin/lists.js\" type=\"text/javascript\"></script>"); ?>
<form class="form">
    <legend><?php echo isset($this->oldkey) ? "" : "Add List" ?></legend>
    <div class="input-prepend">
        <span class="add-on"><i class="icon-key"></i></span>
        <?php if (isset($this->oldkey)): ?>
            <input type="text" value="<?php echo $this->oldkey?>" name="oldkey" disabled/>
            <input type="hidden" value="<?php echo $this->oldkey?>" name="key"/>
        <?php else: ?>
            <input type="text" placeholder="Key" name="key">
        <?php endif; ?>
    </div>
    <div>
        <textarea placeholder="Value" name="value"></textarea>
    </div>
    <div>
        <select name="position" id="list_position">
            <option value="prepend">Prepend</option>
            <option value="append">Append</option>
            <option value="after">After</option>
            <option value="before">Before</option>
        </select>
    </div>
    <div id="list_type">
    </div>
    <button type="submit" class="btn" id="add_list"><i class="icon-plus"></i> Add</button>
    <?php if (!isset($this->oldkey)): ?>
        <button type="submit" class="btn" id="add_edit_list"><i class="icon-plus-sign"></i> Add & Edit</button>
    <?php endif; ?>
</form>
