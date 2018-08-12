<?php $this->addHeader("<script src=\"{$this->router->baseUrl}/js/redmin/actions.js\" type=\"text/javascript\"></script>"); ?>
<div id='mainContainer'>
    <h3>Edit Sorted Set</h3>
    <?php echo $this->renderPartial('zsets/add', array('oldkey' => $this->key))?>
    <h5><i class="icon icon-key"></i> <?php echo $this->key?></h5>
    <table class="table table-striped settable keys-table">
        <tr>
            <th>Value</th>
            <th>Score</th>
            <th>Delete</th>
            <th></th>
        </tr>
        <?php foreach ($this->values as $member => $value): ?>
            <tr>
                <td>
                    <?php echo $member?>
                </td>
                <td>
                    <?php echo $value?>
                </td>
                <td>
                    <a href="#" class="action del">
                        <i class="icon icon-trash" id="<?php echo $member?>" keytype="zsets" keyinfo="<?php echo $this->key?>"></i>
                    </a>
                </td>
                <td>
                    <input type="checkbox" name="keys[]" value="<?php echo $member?>" class="key-checkbox" />
                </td>
            </tr>
        <?php endforeach; ?>
        <?php if (!empty($this->values)): ?>
            <tr>
                <td colspan="2">
                </td>
                <td>
                    <a href="#" class="action delall">
                        <i class="icon icon-trash" keytype="zsets" keyinfo="<?php echo $this->key?>"></i>
                    </a>
                </td>
                <td>
                    <input type="checkbox" name="checkall" id="checkall" class="all-key-checkbox" />
                </td>
            </tr>
        <?php endif; ?>
    </table>
    <?php if ($this->count > 30): ?>
        <ul class="pager">
            <li class="previous <?php if ($this->page == 0) {
    echo "disabled";
}?>">
                <a href="<?php echo $this->router->url?>/zsets/view/<?php echo $this->app->current['serverId'] . '/' . $this->app->current['database'] ?>/<?php echo urlencode($this->key)?>/<?php echo $this->page - 1?>">&larr; Previous</a>
            </li>
            <li class="next <?php if ($this->page == floor($this->count / 30)) {
    echo "disabled";
}?>">
                <a href="<?php echo $this->router->url?>/zsets/view/<?php echo $this->app->current['serverId'] . '/' . $this->app->current['database'] ?>/<?php echo urlencode($this->key)?>/<?php echo $this->page + 1?>">Next &rarr;</a>
            </li>
        </ul>
    <?php endif; ?>
</div>
