<?php $this->addHeader("<script src=\"{$this->router->baseUrl}/js/redmin/strings.js\" type=\"text/javascript\"></script>"); ?>
<form class="form">
    <legend>Add String</legend>
    <div class="input-prepend">
        <span class="add-on"><i class="icon-key"></i></span>
        <input type="text" placeholder="Key" name="key">
    </div>
    <div>
        <textarea placeholder="Value" name="value"></textarea>
    </div>
    <button type="submit" class="btn" id="add_string"><i class="icon-plus"></i> Add</button>
</form>
