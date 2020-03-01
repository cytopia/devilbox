<?php

/** This plugin replaces UNIX timestamps with human-readable dates in your local format.
* Mouse click on the date field reveals timestamp back.
*
* @link https://www.adminer.org/plugins/#use
* @author Anonymous
* @license http://www.apache.org/licenses/LICENSE-2.0 Apache License, Version 2.0
* @license http://www.gnu.org/licenses/gpl-2.0.html GNU General Public License, version 2 (one or other)
*/
class AdminerReadableDates {
	/** @access protected */
	var $prepend;

	function __construct() {
		$this->prepend = <<<EOT

document.addEventListener('DOMContentLoaded', function(event) {
	var date = new Date();
	var tds = document.querySelectorAll('td[id^="val"]');
	for (var i = 0; i < tds.length; i++) {
		var text = tds[i].innerHTML.trim();
		if (text.match(/^\d{10}$/)) {
			date.setTime(parseInt(text) * 1000);
			tds[i].oldDate = text;

			// tds[i].newDate = date.toUTCString().substr(5); // UTC format
			tds[i].newDate = date.toLocaleString();	// Local format
			// tds[i].newDate = date.toLocaleFormat('%e %b %Y %H:%M:%S'); // Custom format - works in Firefox only

			tds[i].newDate = '<span style="color: #009900">' + tds[i].newDate + '</span>';
			tds[i].innerHTML = tds[i].newDate;
			tds[i].dateIsNew = true;

			tds[i].addEventListener('click', function(event) {
				this.innerHTML = (this.dateIsNew ? this.oldDate : this.newDate);
				this.dateIsNew = !this.dateIsNew;
			});
		}
	}
});

EOT;
	}

	function head() {
		echo script($this->prepend);
	}
}
