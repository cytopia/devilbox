var modalPopup = {
    
    // link modals to the template ids
    _modals : {
        alert : 'generalmodal',
        confirm : 'confirmation',
        addDb : 'addDB',
        moveKeys : 'move_confirmation'
    },
            
    _alertDefault : {
        saved : {
            title : 'Saved',
            message : 'New value inserted',
            btn_class : 'btn-success'
        },
        error : {
            title : 'Error',
            message : 'There was a problem saving the value',
            btn_class : 'btn-danger'
        },
        invalid : {
            title : 'Invalid',
            message : 'Please enter a valid input',
            btn_class : 'btn-danger'
        }
    },
            
    _getModal : function(type) {
        return $('#' + this._modals[type]);
    },
    
    hide : function(type) {
        if (typeof type === 'undefined') {
            type = 'alert';
        }
        
        this._getModal(type).modal('hide');
    },
        
    alert : function(type, message, title) {
        if (typeof type === 'undefined') {
            type = 'saved';
        }
        if (typeof title === 'undefined') {
            title = this._alertDefault[type]['title'];
        }
        if (typeof message === 'undefined') {
            message = this._alertDefault[type]['message'];
        }
        
        this._getModal('alert').find('h3').text(title);
        this._getModal('alert').find('p').text(message);
        this._getModal('alert').find('button').attr('class', 'btn ' + this._alertDefault[type]['btn_class']);

        this._getModal('alert').modal('show');
    },
    
    confirm : function(action, message, title) {
        if (typeof title === 'undefined') {
            title = 'Are you sure?';
        }
        if (typeof message === 'undefined') {
            message = 'You can not undo this action';
        }

        this._getModal('confirm').find('.modal-footer .save').unbind();
        this._getModal('confirm').find('.modal-footer .save').click(action);
        this._getModal('confirm').find('h3').text(title);
        this._getModal('confirm').find('p').text(message);

        this._getModal('confirm').modal('show');
    },
    
    addDb : function(action) {
        this._getModal('addDb').find('.modal-footer .save').unbind();
        this._getModal('addDb').find('.modal-footer .save').click(action);

        this._getModal('addDb').modal('show');
    },
            
    moveKeys : function(action, title, tip) {
        this._getModal('moveKeys').find('.modal-footer .movekey').unbind();
        this._getModal('moveKeys').find('.modal-footer .movekey').click(action);
        this._getModal('moveKeys').find('h3').text(title);
        this._getModal('moveKeys').find('input').attr('placeholder', tip);

        this._getModal('moveKeys').modal('show');
    }    
    
}
