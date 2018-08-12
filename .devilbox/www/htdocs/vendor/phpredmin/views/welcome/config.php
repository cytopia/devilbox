<div id='mainContainer'>
    <h3>Redis Config</h3>
    <table class="table table-striped">
        <?php foreach ($this->config as $key => $value): ?>
            <tr>
                <td>
                    <?php echo $key?>
                </td>
                <td>
                    <?php echo is_numeric($value) ? number_format($value) : $value?>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
</div>
