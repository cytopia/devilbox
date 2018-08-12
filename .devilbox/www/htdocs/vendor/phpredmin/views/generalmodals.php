<div id="generalmodal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="confirmation" aria-hidden="true">
    <div class="modal-header">
        <a type="button" class="close" data-dismiss="modal" aria-hidden="true">×</a>
        <h3></h3>
    </div>
    <div class="modal-body">
        <p></p>
    </div>
    <div class="modal-footer">
        <button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
    </div>
</div>

<div id="confirmation" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="confirmation" aria-hidden="true">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h3></h3>
    </div>
    <div class="modal-body">
        <p></p>
    </div>
    <div class="modal-footer">
        <button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
        <button class="btn btn-danger save">I am sure</button>
    </div>
</div>

<div id="move_confirmation" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="confirmation" aria-hidden="true">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h3></h3>
    </div>
    <div class="modal-body">
        <input type="text" placeholder="" name="destination">
    </div>
    <div class="modal-footer">
        <button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
        <button class="btn btn-primary movekey">Move</button>
    </div>
</div>

<div id="addDB" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="addDb" aria-hidden="true">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h3>Add Database</h3>
    </div>
    <div class="modal-body">
        <p>Databases are not created until data is added and they are initialized. To begin,
        specify the database index you want to initialize. You will be redirected and able to
        add data to the database</p>
        <hr />
        <form class="form-horizontal">
            <div class="control-group">
                <label class="control-label" for="inputEmail">Database: </label>
                <div class="controls">
                    <select name="dbIdx" id="dbIdx">
					<?php if (isset($this->app->current['dbs']) && !empty($this->app->current['dbs'])): ?>
                    <?php for ($x=0; $x < $this->app->current['max_databases']; $x++): ?>
                        <?php if (!array_key_exists($x, $this->app->current['dbs'])): ?>
                        <option value='<?php echo $x?>'>DB <?php echo $x?></option>
                        <?php endif; ?>
					<?php endfor; ?>
					<?php endif; ?>
                    </select>
                </div>
            </div>
        </form>
    </div>
    <div class="modal-footer">
        <button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
        <button class="btn btn-primary save">Create</button>
    </div>
</div>
