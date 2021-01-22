$(function() {
	
	/* init some needed tags and values */
	
	$('table#data').wrap('<div id="fkcontainer" class="fk" />');
	$('#fkcontainer').append('<div id="root" />');
	
	jQuery.ppa = { 
		root: $('#root')
	};
	
	$("a.fk").on('click', function (event) {
		/* make the cursor being a waiting cursor */
		$('body').css('cursor','wait');

		query = $.ajax({
			type: 'GET',
			dataType: 'html',
			data: {action:'dobrowsefk'},
			url: $(this).attr('href'),
			cache: false,
			context: $(this),
			contentType: 'application/x-www-form-urlencoded',
			success: function(answer) {
				pdiv = this.closest('div.fk');
				divclass = this.attr('class').split(' ')[1];

				/* if we are clicking on a FK from the original table
				(level 0), we are using the #root div as parent-div */
				if (pdiv[0].id == 'fkcontainer') {
					/* computing top position, which is the topid as well */
					var top = this.position().top + 2 + this.height();
					/* if the requested top position is different than
					 the previous topid position of #root, empty and position it */
					if (top != jQuery.ppa.root.topid)
						jQuery.ppa.root.empty()
							.css({
								left: (pdiv.position().left) +'px',
								top: top +  'px'
							})
							/* this "topid" allows to track if we are 
							opening a FK from the same line in the original table */
							.topid = top;

					pdiv = jQuery.ppa.root;

					/* Remove equal rows in the root div */
					jQuery.ppa.root.children('.'+divclass).remove();
				}
				else {
					/* Remove equal rows in the pdiv */
					pdiv.children('div.'+divclass).remove();
				}

				/* creating the data div */
				newdiv = $('<div class="fk '+divclass+'">').html(answer);
				
				/* highlight referencing fields */
				newdiv.data('ref', this).data('refclass', $(this).attr('class').split(' ')[1])
					.mouseenter(function (event) {
						$(this).data('ref').closest('tr').find('a.'+$(this).data('refclass')).closest('div').addClass('highlight');
					})
					.mouseleave(function (event) {
						$(this).data('ref').closest('tr').find('a.'+$(this).data('refclass')).closest('div').removeClass('highlight');
					});

				/* appending it to the level-1 div */
				pdiv.append(newdiv);
			},

			error: function() {
				this.closest('div.fk').append('<p class="errmsg">'+Display.errmsg+'</p>');
			},

			complete: function () {
				$('body').css('cursor','auto');
			}
		});
		
		return false; // do not refresh the page
	});

	$(document).on('click', '.fk_delete', function (event) {
		with($(this).closest('div')) {
			data('ref').closest('tr').find('a.'+data('refclass')).closest('div').removeClass('highlight');
			remove();
		}
		return false; // do not refresh the page
	});
});
