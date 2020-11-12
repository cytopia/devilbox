/**
 * Function for updating browser frame and topbar frame so that sqledit window 
 * pops up with properly selected database.
 *
 * $Id: links.js,v 1.4 2004/07/13 15:24:41 jollytoad Exp $
 */
function updateLinks(getVars) {
	var topbarLink = 'topbar.php' + getVars;
	var browserLink = 'browser.php' + getVars;
	var detailLink = 'redirect.php' + getVars + 'section=database';
		
	parent.frames.topbar.location = topbarLink;
	parent.frames.detail.location = detailLink;
	parent.frames.browser.location = browserLink;
}

