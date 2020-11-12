$(function() {

	var timeid = query = null;
	var controlLink = $('#control');
	var errmsg = $('<p class="errmsg">'+Database.errmsg+'</p>')
		.insertBefore(controlLink)
		.hide();
	var loading = $('<img class="loading" alt="[loading]" src="'+ Database.load_icon +'" />')
		.insertAfter(controlLink)
		.hide();

	function refreshTable() {
		if (Database.ajax_time_refresh > 0) {
			loading.show();
			query = $.ajax({
				type: 'GET',
				dataType: 'html',
				data: {server: Database.server, database: Database.dbname, action: Database.action},
				url: 'database.php',
				cache: false,
				contentType: 'application/x-www-form-urlencoded',
				success: function(html) {
					$('#data_block').html(html);
					timeid = window.setTimeout(refreshTable, Database.ajax_time_refresh)
				},
				error: function() {
					controlLink.click();
					errmsg.show();
				},
				complete: function () {
					loading.hide();
				}
			});
		}
	}

	controlLink.on('click', function() {
		if (!timeid) {
			$(errmsg).hide();
			timeid = window.setTimeout(refreshTable, Database.ajax_time_refresh);
			controlLink.html('<img src="'+ Database.str_stop.icon +'" alt="" />&nbsp;'
				+ Database.str_stop.text + '&nbsp;&nbsp;&nbsp;'
			);
		} else {
			$(errmsg).hide();
			$(loading).hide();
			window.clearInterval(timeid);
			timeid = null;
			if (query) query.abort();
			controlLink.html('<img src="'+ Database.str_start.icon +'" alt="" />&nbsp;'
				+ Database.str_start.text
			);
		}
		return false;  /* disable event propagation */
	});

	/* preload images */
	$('#control img').hide()
		.attr('src', Database.str_start.icon)
		.attr('src', Database.str_stop.icon)
		.show();
	
	/* start refreshing */
	controlLink.trigger('click');
});
