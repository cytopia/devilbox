<?php $this->addHeader("<script src=\"{$this->router->baseUrl}/js/redmin/actions.js\" type=\"text/javascript\"></script>"); ?>
<div id='mainContainer'>
    <h3>Edit Set</h3>
    <?php echo $this->renderPartial('sets/add', array('oldkey' => $this->key))?>
    <h5><i class="icon icon-key"></i> <?php echo $this->key?></h5>
    <table class="table table-striped settable keys-table">
        <tr>
            <th>Value</th>
            <th>Edit</th>
            <th>Delete</th>
            <th></th>

        </tr>
        <?php foreach ($this->members as $member): ?>
            <tr>
                <td>
                    <?php echo $member?>
                </td>
                <td>
                    <a href="<?php echo $this->router->url?>/sets/edit/<?php echo $this->app->current['serverId'] . '/' . $this->app->current['database'] ?>/<?php echo urlencode($this->key)?>/<?php echo urlencode($member)?>" target="_blank" class="action">
                        <i class="icon icon-edit"></i>
                    </a>
                </td>
                <td>
                    <a href="#" class="action del">
                        <i class="icon icon-trash" id="<?php echo $member?>" keytype="sets" keyinfo="<?php echo $this->key?>"></i>
                    </a>
                </td>
                <td>
                    <input type="checkbox" name="keys[]" value="<?php echo $member?>" class="key-checkbox" />
                </td>
            </tr>
        <?php endforeach; ?>
        <?php if (!empty($this->members)): ?>
            <tr>
                <td colspan="3">
                </td>
                <td>
                    <input type="checkbox" name="checkall" id="checkall" class="all-key-checkbox" />
                    <a href="#" class="action moveall" style="margin-left: 10px;">
                        <i class="icon icon-move" keytype="sets" keyinfo="<?php echo $this->key?>" modaltitle="Move value to?" modaltip="Destination Set">
                        </i>
                    </a>
                    <a href="#" class="action delall" style="margin-left: 10px;">
                        <i class="icon icon-trash" keytype="sets" keyinfo="<?php echo $this->key?>"></i>
                    </a>
                </td>
            </tr>
        <?php endif; ?>
    </table>
</div>
