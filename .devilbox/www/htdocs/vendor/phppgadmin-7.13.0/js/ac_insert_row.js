var fkl_hasnext=false;
var fkl_hasprev=false;

/* hide the value list */
function hideAc() {
	jQuery.ppa.o=0;
	with (jQuery.ppa) {
		fklist.hide();
		fkbg.hide();
	}
}

/* enable/disable auto-complete feature */
function triggerAc(ac) {
	if (ac) {
		jQuery.ppa.attrs
			.on('keyup.ac_action', autocomplete)
			.on('focus.ac_action', autocomplete)
			.on('keydown.ac_action', move)
			.addClass('ac_field');
	}
	else {
		jQuery.ppa.attrs
			.removeClass('ac_field')
			.off('.ac_action');
	}
}

/* select the given index value and highlight it */
function selectVal(index) {
	if (index == jQuery.ppa.i)
		return;

	// we catch the header as well so it takes th index 0
	var trs = jQuery.ppa.fklist.find('tr');

	// change colors for unselected
	if (jQuery.ppa.i > 0)
		trs.eq(jQuery.ppa.i).find('*').css({
			'background-color': '#fff',
			'color': ''
		});
		
	// change colors for newly selected
	trs.eq(index).find('*').css({
		'background-color': '#3d80df',
		'color': '#fff'
	});

	jQuery.ppa.i = index;
}

function openlist(e) {
	var elt = jQuery(e);
	var attnum = elt.attr('id').match(/\d+/)[0];
	/* FIXME we only support the first FK constraint of the field */
	var conid = attrs['attr_'+attnum][0];

	var constr = constrs["constr_" + conid];

	// get the changed attribute position in the arrays 
	for (i=0; (constr.pattnums[i] != attnum);i++);
	
	var datas = {
		fattpos: i,
		fvalue: e.value,
		database: database,
		'keys[]': constr.pattnums,
		'keynames[]': constr.pattnames,
		'fkeynames[]': constr.fattnames,
		f_table: constr.f_table,
		f_schema: constr.f_schema,
		offset: jQuery.ppa.o
	};

	jQuery.ajax({
		url: 'ajax-ac-insert.php?server=' + server,
		type: 'post',
		data: datas,
		dataType: 'html',
		cache: false,
		contentType: 'application/x-www-form-urlencoded',
		success: function (ret) {
			jQuery.ppa.i = 0;
			jQuery.ppa.fkbg.show();
			with(jQuery.ppa.fklist) {
				html(ret);
				appendTo('#row_att_'+ attnum);
				css('width',elt.css('width'));
				show();
				jQuery.ppa.numrow = find('tr').length;
			}

		}
	});
}


/* move the cursor down or up,
 * load available next/prev values if going out of bound */
function move(event) {
	/* selecting next value down.
	 * if the list is closed, it will open next */
	if(event.keyCode == 40) {
		if (jQuery.ppa.fklist[0].style.display == 'block')  {
			if ((jQuery.ppa.i + 1) < jQuery.ppa.numrow) {
				selectVal(jQuery.ppa.i + 1);
			}
			else if (fkl_hasnext == true) {
				jQuery.ppa.o+=11;
				openlist(this);
			}
		}
		else {
			openlist(this);
		}
	}
	/* selecting prev value up */
	else if(event.keyCode == 38) {
		if ((jQuery.ppa.i - 1) > 0) {
			selectVal(jQuery.ppa.i - 1);
		}
		else if ((fkl_hasprev == true) && (jQuery.ppa.i == 1)) {
			jQuery.ppa.o-=11;
			openlist(this);
		}
		else {
			selectVal(jQuery.ppa.numrow -1);
		}
	}
}

/* open/update the value list */
function autocomplete(event) {

	/* if pressing enter, fire a click on the selected line */
	if (event.keyCode == 13) {
		if (jQuery.ppa.i > 0) {
			jQuery.ppa.fklist.find('tr').eq(jQuery.ppa.i).trigger('click');
		}
		return false;
	}
	/* ignoring 38:up and 40:down */
	else if ( event.keyCode == 38 || event.keyCode == 40 ) {
		return false;
	}
	/* ignoring 9:tab, 37:left, 39:right, 16:shift, ctrl: 17, alt:18, 20:lockmaj */
	else if ( event.keyCode == 9 || event.keyCode == 37	|| event.keyCode == 39
	|| event.keyCode == 16 || event.keyCode == 17
	|| event.keyCode == 18 || event.keyCode == 20) {
		return true;
	}
	/* esc */
	else if (event.keyCode == 27) {
		hideAc();
	}
	/* request the list of possible values asynchronously */
	else {
		/* if we refresh because of a value update, 
		 * we reset back to offset 0 so we catch values
		 * if list is smaller than 11 values */
		if (event.type == 'keyup')
			jQuery.ppa.o = 0;
		openlist(this);
	}

	return true;
}

$(document).on('mouseover', 'tr.acline', function() {
	selectVal(jQuery('table.ac_values tr').index(this));
});

$(document).on('click', 'tr.acline', function() {
	var a = jQuery(this).find('td > a.fkval');
	for (i=0; i < a.length; i++) {
               jQuery('input[name="values['+ a[i].name +']"]').val(jQuery(a[i]).text());
	}
	hideAc();
});

$(document).on('click', '#fkprev', function () {
	jQuery.ppa.o -= 11;
	/* get the field that is the previous html elt from the #fklist
	 * and trigger its focus to refresh the list AND actually 
	 * focus back on the field */
	jQuery('#fklist').prev().focus();
});

$(document).on('click', '#fknext', function () {
	jQuery.ppa.o += 11;
	/* get the field that is the previous html elt from the #fklist
	 * and trigger its focus to refresh the list AND actually 
	 * focus back on the field*/
	jQuery('#fklist').prev().focus();
});

jQuery(function () {
	/* register some global value in the ppa namespace */
	jQuery.ppa = {
		fklist: jQuery('#fklist'),
		attrs: jQuery('input[id^=attr_]'), // select fields with FK
		fkbg: jQuery('#fkbg'),
		i:0, // selected value indice
		o:0 // offset when navigating prev/next
	};

	/* close the list when clicking outside of it */
	jQuery.ppa.fkbg.on('click', function (e) {
		hideAc();
	});

	/* do not submit the form when selecting a value by pressing enter */
	jQuery.ppa.attrs
		.on('keydown', function (e) {
			if (e.keyCode == 13 && jQuery.ppa.fklist[0].style.display == 'block')
				return false;
		});
	
	/* enable/disable auto-complete according to the checkbox */
	triggerAc(
		jQuery('#no_ac').on('click', function () {
			triggerAc(this.checked);
		})[0].checked
	);
});
