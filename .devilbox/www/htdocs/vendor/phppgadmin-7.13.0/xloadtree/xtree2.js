/*----------------------------------------------------------------------------\
|                            XTree 2 PRE RELEASE                              |
|                                                                             |
| This is a pre release and redistribution is discouraged.                    |
| Watch http://webfx.eae.net for the final version                            |
|                                                                             |
|-----------------------------------------------------------------------------|
|                   Created by Erik Arvidsson & Emil A Eklund                 |
|                  (http://webfx.eae.net/contact.html#erik)                   |
|                  (http://webfx.eae.net/contact.html#emil)                   |
|                      For WebFX (http://webfx.eae.net/)                      |
|-----------------------------------------------------------------------------|
| A tree menu system for IE 5.5+, Mozilla 1.4+, Opera 7, KHTML                |
|-----------------------------------------------------------------------------|
|         Copyright (c) 1999 - 2005 Erik Arvidsson & Emil A Eklund            |
|-----------------------------------------------------------------------------|
| This software is provided "as is", without warranty of any kind, express or |
| implied, including  but not limited  to the warranties of  merchantability, |
| fitness for a particular purpose and noninfringement. In no event shall the |
| authors or  copyright  holders be  liable for any claim,  damages or  other |
| liability, whether  in an  action of  contract, tort  or otherwise, arising |
| from,  out of  or in  connection with  the software or  the  use  or  other |
| dealings in the software.                                                   |
| - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - |
| This  software is  available under the  three different licenses  mentioned |
| below.  To use this software you must chose, and qualify, for one of those. |
| - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - |
| The WebFX Non-Commercial License          http://webfx.eae.net/license.html |
| Permits  anyone the right to use the  software in a  non-commercial context |
| free of charge.                                                             |
| - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - |
| The WebFX Commercial license           http://webfx.eae.net/commercial.html |
| Permits the  license holder the right to use  the software in a  commercial |
| context. Such license must be specifically obtained, however it's valid for |
| any number of  implementations of the licensed software.                    |
| - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - |
| GPL - The GNU General Public License    http://www.gnu.org/licenses/gpl.txt |
| Permits anyone the right to use and modify the software without limitations |
| as long as proper  credits are given  and the original  and modified source |
| code are included. Requires  that the final product, software derivate from |
| the original  source or any  software  utilizing a GPL  component, such  as |
| this, is also licensed under the GPL license.                               |
|-----------------------------------------------------------------------------|
| 2004-02-21 | Pre release distributed to a few selected tester               |
| 2005-06-06 | Added single tab index to improve keyboard navigation          |
|-----------------------------------------------------------------------------|
| Dependencies: xtree2.css Used to define the look and feel                   |
|-----------------------------------------------------------------------------|
| Created 2003-??-?? | All changes are in the log above. | Updated 2004-06-06 |
\----------------------------------------------------------------------------*/


//
// WebFXTreePersisitance
function WebFXTreePersistence() {}
var _p = WebFXTreePersistence.prototype;
_p.getExpanded = function (oNode) { return false; };
_p.setExpanded = function (oNode, bOpen) {};



// Cookie handling
function WebFXCookie() {}

_p = WebFXCookie.prototype;

_p.setCookie = function (sName, sValue, nDays) {
	var expires = "";
	if (typeof nDays == "number") {
		var d = new Date();
		d.setTime(d.getTime() + nDays * 24 * 60 * 60 * 1000);
		expires = "; expires=" + d.toGMTString();
	}

	document.cookie = sName + "=" + escape(sValue) + expires + "; path=/";
};

_p.getCookie = function (sName) {
	var re = new RegExp("(\;|^)[^;]*(" + sName + ")\=([^;]*)(;|$)");
	var res = re.exec(document.cookie);
	return res != null ? unescape(res[3]) : null;
};

_p.removeCookie = function (name) {
	this.setCookie(name, "", -1);
};


//
// persistence using cookies
//
// This is uses one cookie with the ids of the expanded nodes separated using '+'
//
function WebFXTreeCookiePersistence() {
	this._openedMap = {};
	this._cookies = new WebFXCookie;
	var s = this._cookies.getCookie(this.cookieName);
	if (s) {
		var a = s.split("+");
		for (var i = a.length - 1; i >= 0; i--)
			this._openedMap[a[i]] = true;
	}
}

_p = WebFXTreeCookiePersistence.prototype = new WebFXTreePersistence;

_p.cookieName = "webfx-tree-cookie-persistence"

_p.getExpanded = function (oNode) {
	return oNode.id in this._openedMap;
};

_p.setExpanded = function (oNode, bOpen) {
	var old = this.getExpanded(oNode);
	if (old != bOpen) {
		if (bOpen) {
			this._openedMap[oNode.id] = true;
		} else {
			delete this._openedMap[oNode.id];
		}

		var res = [];
		var i = 0;
		for (var id in this._openedMap)
			res[i++] = id;
		this._cookies.setCookie(this.cookieName, res.join("+"));
	}
};



// this object provides a few useful methods when working with arrays
var arrayHelper = {
	indexOf: function (a, o) {
		for (var i = 0; i < a.length; i++) {
			if (a[i] == o) {
				return i;
			}
		}
		return -1;
	},

	insertBefore: function (a, o, o2) {
		var i = this.indexOf(a, o2);
		if (i == -1) {
			a.push(o);
		} else {
			a.splice(i, 0, o);
		}
	},

	remove: function (a, o) {
		var i = this.indexOf(a, o);
		if (i != -1) {
			a.splice(i, 1);
		}
	}
};

///////////////////////////////////////////////////////////////////////////////
// WebFX Tree Config object                                                  //
///////////////////////////////////////////////////////////////////////////////
var webFXTreeConfig = {
	rootIcon        : "images/folder.png",
	openRootIcon    : "images/openfolder.png",
	folderIcon      : "images/folder.png",
	openFolderIcon  : "images/openfolder.png",
	fileIcon        : "images/file.png",
	iIcon           : "images/I.png",
	lIcon           : "images/L.png",
	lMinusIcon      : "images/Lminus.png",
	lPlusIcon       : "images/Lplus.png",
	tIcon           : "images/T.png",
	tMinusIcon      : "images/Tminus.png",
	tPlusIcon       : "images/Tplus.png",
	plusIcon        : "images/plus.png",
	minusIcon       : "images/minus.png",
	blankIcon       : "images/blank.png",
	defaultText     : "Tree Item",
	defaultAction   : null,
	defaultBehavior : "classic",
	usePersistence	: true
};

///////////////////////////////////////////////////////////////////////////////
// WebFX Tree Handler object                                                 //
///////////////////////////////////////////////////////////////////////////////

var webFXTreeHandler = {
	ie: /msie/i.test(navigator.userAgent),
	opera: /opera/i.test(navigator.userAgent),
	idCounter: 0,
	idPrefix: "wfxt-",
	getUniqueId: function () {
		return this.idPrefix + this.idCounter++;
	},
	all: {},
	getNodeById: function (sId) {
		return all[sId];
	},
	addNode: function (oNode) {
		this.all[oNode.id] = oNode;
	},
	removeNode:	function (oNode) {
		delete this.all[oNode.id];
	},

	handleEvent: function (e) {
		var el = e.target || e.srcElement;
		while (el != null && !this.all[el.id]) {
			el = el.parentNode;
		}

		if (el == null) {
			return false;
		}
		var node = this.all[el.id];
		if (typeof node["_on" + e.type] == "function") {
			return node["_on" + e.type](e);
		}
		return false;
	},

	dispose: function () {
		if (this.disposed) return;
		for (var id in this.all) {
			this.all[id].dispose();
		}
		this.disposed = true;
	},

	htmlToText: function (s) {
		return String(s).replace(/\s+|<([^>])+>|&amp;|&lt;|&gt;|&quot;|&nbsp;/gi, this._htmlToText);
	},

	_htmlToText: function (s) {
		switch (s) {
			case "&amp;":
				return "&";
			case "&lt;":
				return "<";
			case "&gt;":
				return ">";
			case "&quot;":
				return "\"";
			case "&nbsp;":
				return String.fromCharCode(160);
			default:
				if (/\s+/.test(s)) {
					return " ";
				}
				if (/^<BR/gi.test(s)) {
					return "\n";
				}
				return "";
		}
	},

	textToHtml: function (s) {
		return String(s).replace(/&|<|>|\n|\"|\u00A0/g, this._textToHtml);
	},

	_textToHtml: function (s) {
		switch (s) {
			case "&":
				return "&amp;";
			case "<":
				return "&lt;";
			case ">":
				return "&gt;";
			case "\n":
				return "<BR>";
			case "\"":
				return "&quot;";	// so we can use this in attributes
			default:
				return "&nbsp;";
		}
	},

	persistenceManager: new WebFXTreeCookiePersistence()
};


///////////////////////////////////////////////////////////////////////////////
// WebFXTreeAbstractNode
///////////////////////////////////////////////////////////////////////////////

function WebFXTreeAbstractNode(sText, oAction, oIconAction) {
	this.childNodes = [];
	if (sText) this.text = sText;
	if (oAction) this.action = oAction;
	if (oIconAction) this.iconAction = oIconAction;
	this.id = webFXTreeHandler.getUniqueId();
	if (webFXTreeConfig.usePersistence) {
		this.open = webFXTreeHandler.persistenceManager.getExpanded(this);
	}
	webFXTreeHandler.addNode(this);
}


_p = WebFXTreeAbstractNode.prototype;
_p._selected = false;
_p.indentWidth = 19;
_p.open = false;
_p.text = webFXTreeConfig.defaultText;
_p.action = null;
_p.iconAcion = null;
_p.target = null;
_p.toolTip = null;
_p._focused = false;

/* begin tree model */

_p.add = function (oChild, oBefore) {
	var oldLast;
	var emptyBefore = this.childNodes.length == 0;
	var p = oChild.parentNode;

	if (oBefore == null) { // append
		if (p != null)
			p.remove(oChild);
		oldLast = this.getLastChild();
		this.childNodes.push(oChild);
	} else { // insertBefore
		if (oBefore.parentNode != this) {
			throw new Error("Can only add nodes before siblings");
		}
		if (p != null) {
			p.remove(oChild);
		}

		arrayHelper.insertBefore(this.childNodes, oChild, oBefore);
	}

	if (oBefore) {
		if (oBefore == this.firstChild) {
			this.firstChild = oChild;
		}
		oChild.previousSibling = oBefore.previousSibling;
		oBefore.previousSibling = oChild;
		oChild.nextSibling = oBefore;
	} else {
		if (!this.firstChild) {
			this.firstChild = oChild;
		}
		if (this.lastChild) {
			this.lastChild.nextSibling = oChild;
		}
		oChild.previousSibling = this.lastChild;
		this.lastChild = oChild;
	}

	oChild.parentNode = this;
	var t = this.getTree();
	if (t) {
		oChild.tree = t;
	}
	var d = this.getDepth();
	if (d != null) {
		oChild.depth = d + 1;
	}

	if (this.getCreated() && !t.getSuspendRedraw()) {
		var el = this.getChildrenElement();
		var newEl = oChild.create();
		var refEl = oBefore ? oBefore.getElement() : null;
		el.insertBefore(newEl, refEl);

		if (oldLast) {
			oldLast.updateExpandIcon();
		}
		if (emptyBefore) {
			this.setExpanded(this.getExpanded());
			// if we are using classic expand will not update icon
			if (t && t.getBehavior() != "classic")
				this.updateIcon();
		}
	}

	return oChild;
};



_p.remove = function (oChild) {
	// backwards compatible. If no argument remove the node
	if (arguments.length == 0) {
		if (this.parentNode) {
			return this.parentNode.remove(this);
		}
		return null;
	}

	// if we remove selected or tree with the selected we should select this
	var t = this.getTree();
	var si = t ? t.getSelected() : null;
	if (si == oChild || oChild.contains(si)) {
		if (si.getFocused()) {
			this.select();
			window.setTimeout("WebFXTreeAbstractNode._onTimeoutFocus(\"" + this.id + "\")", 10);
		} else {
			this.select();
		}
	}

	if (oChild.parentNode != this) {
		throw new Error("Can only remove children");
	}
	arrayHelper.remove(this.childNodes, oChild);

	if (this.lastChild == oChild) {
		this.lastChild = oChild.previousSibling;
	}
	if (this.firstChild == oChild) {
		this.firstChild = oChild.nextSibling;
	}
	if (oChild.previousSibling) {
		oChild.previousSibling.nextSibling = oChild.nextSibling;
	}
	if (oChild.nextSibling) {
		oChild.nextSibling.previousSibling = oChild.previousSibling;
	}

	var wasLast = oChild.isLastSibling();

	oChild.parentNode = null;
	oChild.tree = null;
	oChild.depth = null;

	if (t && this.getCreated() && !t.getSuspendRedraw()) {
		var el = this.getChildrenElement();
		var childEl = oChild.getElement();
		el.removeChild(childEl);
		if (wasLast) {
			var newLast = this.getLastChild();
			if (newLast) {
				newLast.updateExpandIcon();
			}
		}
		if (!this.hasChildren()) {
			el.style.display = "none";
			this.updateExpandIcon();
			this.updateIcon();
		}
	}

	return oChild;
};

WebFXTreeAbstractNode._onTimeoutFocus = function (sId) {
	var jsNode = webFXTreeHandler.all[sId];
	jsNode.focus();
};

_p.getId = function () {
	return this.id;
};

_p.getTree = function () {
	throw new Error("getTree called on Abstract Node");
};

_p.getDepth = function () {
	throw new Error("getDepth called on Abstract Node");
};

_p.getCreated = function () {
	var t = this.getTree();
	return t && t.rendered;
};

_p.getParent = function () {
	return this.parentNode;
};

_p.contains = function (oDescendant) {
	if (oDescendant == null) return false;
	if (oDescendant == this) return true;
	var p = oDescendant.parentNode;
	return this.contains(p);
};

_p.getChildren = _p.getChildNodes = function () {
	return this.childNodes;
};

_p.getFirstChild = function () {
	return this.childNodes[0];
};

_p.getLastChild = function () {
	return this.childNodes[this.childNodes.length - 1];
};

_p.getPreviousSibling = function () {
	return this.previousSibling;
	//var p = this.parentNode;
	//if (p == null) return null;
	//var cs = p.childNodes;
	//return cs[arrayHelper.indexOf(cs, this) - 1]
};

_p.getNextSibling = function () {
	return this.nextSibling;
	//var p = this.parentNode;
	//if (p == null) return null;
	//var cs = p.childNodes;
	//return cs[arrayHelper.indexOf(cs, this) + 1]
};

_p.hasChildren = function () {
	return this.childNodes.length > 0;
};

_p.isLastSibling = function () {
	return this.nextSibling == null;
	//return this.parentNode && this == this.parentNode.getLastChild();
};

_p.findChildByText = function (s, n) {
	if (!n) {
		n = 0;
	}
	var isRe = s instanceof RegExp;
	for (var i = 0; i < this.childNodes.length; i++) {
		if (isRe && s.test(this.childNodes[i].getText()) ||
			this.childNodes[i].getText() == s) {
			if (n == 0) {
				return this.childNodes[i];
			}
			n--;
		}
	}
	return null;
};

_p.findNodeByText = function (s, n) {
	if (!n) {
		n = 0;
	}
	var isRe = s instanceof RegExp;
	if (isRe && s.test(this.getText()) || this.getText() == s) {
		if (n == 0) {
			return this.childNodes[i];
		}
		n--;
	}

	var res;
	for (var i = 0; i < this.childNodes.length; i++) {
		res = this.childNodes[i].findNodeByText(s, n);
		if (res) {
			return res;
		}
	}
	return null;
};

/* end tree model */

_p.setId = function (sId) {
	var el = this.getElement();
	webFXTreeHandler.removeNode(this);
	this.id = sId;
	if (el) {
		el.id = sId;
	}
	webFXTreeHandler.addNode(this);
};

_p.isSelected = function () {
	return this._selected;
};

_p.select = function () {
	this._setSelected(true);
};

_p.deselect = function () {
	this._setSelected(false);
};

_p._setSelected = function (b) {
	var t = this.getTree();
	if (!t) return;
	if (this._selected != b) {
		this._selected = b;

		var wasFocused = false;	// used to keep focus state
		var si = t.getSelected();
		if (b && si != null && si != this) {
			var oldFireChange = t._fireChange;
			wasFocused = si._focused;
			t._fireChange = false;
			si._setSelected(false);
			t._fireChange = oldFireChange;
		}

		var el = this.getRowElement();
		if (el) {
			el.className = this.getRowClassName();
		}
		if (b) {
			this._setTabIndex(t.tabIndex);
			t._selectedItem = this;
			t._fireOnChange();
			t.setSelected(this);
			if (wasFocused) {
				this.focus();
			}
		} else {
			this._setTabIndex(-1);
		}

		if (t.getBehavior() != "classic") {
			this.updateIcon();
		}
	}
};


_p.getExpanded = function () {
	return this.open;
};

_p.setExpanded = function (b) {
	var ce;
	this.open = b;
	var t = this.getTree();
	if (this.hasChildren()) {
		var si = t ? t.getSelected() : null;
		if (!b && this.contains(si)) {
			this.select();
		}

		var el = this.getElement();
		if (el) {
			ce = this.getChildrenElement();
			if (ce) {
				ce.style.display = b ? "block" : "none";
			}
			var eie = this.getExpandIconElement();
			if (eie) {
				eie.src = this.getExpandIconSrc();
			}
		}

		if (webFXTreeConfig.usePersistence) {
			webFXTreeHandler.persistenceManager.setExpanded(this, b);
		}
	} else {
		ce = this.getChildrenElement();
		if (ce)
			ce.style.display = "none";
	}
	if (t && t.getBehavior() == "classic") {
		this.updateIcon();
	}
};

_p.toggle = function () {
	this.setExpanded(!this.getExpanded());
};

_p.expand = function () {
	this.setExpanded(true);
};

_p.collapse = function () {
	this.setExpanded(false);
};

_p.collapseChildren = function () {
	var cs = this.childNodes;
	for (var i = 0; i < cs.length; i++) {
		cs[i].collapseAll();
	}
};

_p.collapseAll = function () {
	this.collapseChildren();
	this.collapse();
};

_p.expandChildren = function () {
	var cs = this.childNodes;
	for (var i = 0; i < cs.length; i++) {
		cs[i].expandAll();
	}
};

_p.expandAll = function () {
	this.expandChildren();
	this.expand();
};

_p.reveal = function () {
	var p = this.getParent();
	if (p) {
		p.setExpanded(true);
		p.reveal();
	}
};

_p.openPath = function (sPath, bSelect, bFocus) {
	if (sPath == "") {
		if (bSelect) {
			this.select();
		}
		if (bFocus) {
			window.setTimeout("WebFXTreeAbstractNode._onTimeoutFocus(\"" + this.id + "\")", 10);
		}
		return;
	}

	var parts = sPath.split("/");
	var remainingPath = parts.slice(1).join("/");
	var t = this.getTree();
	if (sPath.charAt(0) == "/") {
		if (t) {
			t.openPath(remainingPath, bSelect, bFocus);
		} else {
			throw "Invalid path";
		}
	} else {
		// open
		this.setExpanded(true);
		parts = sPath.split("/");
		var ti = this.findChildByText(parts[0]);
		if (!ti) {
			throw "Could not find child node with text \"" + parts[0] + "\"";
		}
		ti.openPath(remainingPath, bSelect, bFocus);
	}
};

_p.focus = function () {
	var el = this.getLabelElement();
	if (el) {
		el.focus();
	}
};

_p.getFocused = function () {
	return this._focused;
};

_p._setTabIndex = function (i) {
	var a = this.getLabelElement();
	if (a) {
		a.setAttribute("tabindex", i);
	}
};


// HTML generation

_p.toHtml = function () {
	var sb = [];
	var cs = this.childNodes;
	var l = cs.length;
	for (var y = 0; y < l; y++) {
		sb[y] = cs[y].toHtml();
	}

	var t = this.getTree();
	var hideLines = !t.getShowLines() || t == this.parentNode && !t.getShowRootLines();

	var childrenHtml = "<div class=\"webfx-tree-children" +
		(hideLines ? "-nolines" : "") + "\" style=\"" +
		this.getLineStyle() +
		(this.getExpanded() && this.hasChildren() ? "" : "display:none;") +
		"\">" +
		sb.join("") +
		"</div>";

	return "<div class=\"webfx-tree-item\" id=\"" +
		this.id + "\"" + this.getEventHandlersHtml() + ">" +
		this.getRowHtml() +
		childrenHtml +
		"</div>";
};

_p.getRowHtml = function () {
	var t = this.getTree();
	return "<div class=\"" + this.getRowClassName() + "\" style=\"padding-left:" +
		Math.max(0, (this.getDepth() - 1) * this.indentWidth) + "px\">" +
		this.getExpandIconHtml() +
		//"<span class=\"webfx-tree-icon-and-label\">" +
		this.getIconHtml() +
		this.getLabelHtml() +
		//"</span>" +
		"</div>";
};

_p.getRowClassName = function () {
	return "webfx-tree-row" + (this.isSelected() ? " selected" : "") +
		(this.action ? "" : " no-action");
};

_p.getLabelHtml = function () {
	var toolTip = this.getToolTip();
	var target = this.getTarget();
	var link = this._getHref();

	if (link == '#') {
		return "<span class=\"webfx-tree-item-label\" tabindex=\"-1\"" +
			(toolTip ? " title=\"" + webFXTreeHandler.textToHtml(toolTip) + "\"" : "") +
			" onfocus=\"webFXTreeHandler.handleEvent(event)\"" +
			" onblur=\"webFXTreeHandler.handleEvent(event)\">" +
			this.getHtml() + "</span>";
	}

	return "<a href=\"" + webFXTreeHandler.textToHtml(link) +
		"\" class=\"webfx-tree-item-label\" tabindex=\"-1\"" +
		(toolTip ? " title=\"" + webFXTreeHandler.textToHtml(toolTip) + "\"" : "") +
		(target ? " target=\"" + target + "\"" : "") +
		" onfocus=\"webFXTreeHandler.handleEvent(event)\"" +
		" onblur=\"webFXTreeHandler.handleEvent(event)\">" +
		this.getHtml() + "</a>";
};

_p._getHref = function () {
	if (typeof this.action == "string")
		return this.action;
	else
		return "#";
};

_p._getIconHref = function () {
	if (typeof this.iconAction == "string")
		return this.iconAction;
	else
		return this._getHref();
}

_p.getEventHandlersHtml = function () {
	return "";
};

_p.getIconHtml = function () {
	// here we are not using textToHtml since the file names rarerly contains
	// HTML...
	var link = this._getIconHref();
	if (link == '#')
		return "<img class=\"webfx-tree-icon\" src=\"" + this.getIconSrc() + "\">&nbsp;";

	return "<a href=\"" + webFXTreeHandler.textToHtml(link) +
		"\"><img class=\"webfx-tree-icon\" src=\"" + this.getIconSrc() + "\"></a>";
};

_p.getIconSrc = function () {
	throw new Error("getIconSrc called on Abstract Node");
};

_p.getExpandIconHtml = function () {
	// here we are not using textToHtml since the file names rarerly contains
	// HTML...
	return "<img class=\"webfx-tree-expand-icon\" src=\"" +
		this.getExpandIconSrc() + "\">";
};


_p.getExpandIconSrc = function () {
	var src;
	var t = this.getTree();
	var hideLines = !t.getShowLines() || t == this.parentNode && !t.getShowRootLines();

	if (this.hasChildren()) {
		var bits = 0;
		/*
			Bitmap used to determine which icon to use
			1  Plus
			2  Minus
			4  T Line
			8  L Line
		*/

		if (t && t.getShowExpandIcons()) {
			if (this.getExpanded()) {
				bits = 2;
			} else {
				bits = 1;
			}
		}

		if (t && !hideLines) {
			if (this.isLastSibling()) {
				bits += 4;
			} else {
				bits += 8;
			}
		}

		switch (bits) {
			case 1:
				return webFXTreeConfig.plusIcon;
			case 2:
				return webFXTreeConfig.minusIcon;
			case 4:
				return webFXTreeConfig.lIcon;
			case 5:
				return webFXTreeConfig.lPlusIcon;
			case 6:
				return webFXTreeConfig.lMinusIcon;
			case 8:
				return webFXTreeConfig.tIcon;
			case 9:
				return webFXTreeConfig.tPlusIcon;
			case 10:
				return webFXTreeConfig.tMinusIcon;
			default:	// 0
				return webFXTreeConfig.blankIcon;
		}
	} else {
		if (t && hideLines) {
			return webFXTreeConfig.blankIcon;
		} else if (this.isLastSibling()) {
			return webFXTreeConfig.lIcon;
		} else {
			return webFXTreeConfig.tIcon;
		}
	}
};

_p.getLineStyle = function () {
	return "background-position:" + this.getLineStyle2() + ";";
};

_p.getLineStyle2 = function () {
	return (this.isLastSibling() ? "-100" : (this.getDepth() - 1) * this.indentWidth) + "px 0";
};

// End HTML generation

// DOM
// this returns the div for the tree node
_p.getElement = function () {
	return document.getElementById(this.id);
};

// the row is the div that is used to draw the node without the children
_p.getRowElement = function () {
	var el = this.getElement();
	if (!el) return null;
	return el.firstChild;
};

// plus/minus image
_p.getExpandIconElement = function () {
	var el = this.getRowElement();
	if (!el) return null;
	return el.firstChild;
};

_p.getIconElement = function () {
	var el = this.getRowElement();
	if (!el) return null;
	return el.childNodes[1];
};

// anchor element
_p.getLabelElement = function () {
	var el = this.getRowElement();
	if (!el) return null;
	return el.lastChild;
};

// the div containing the children
_p.getChildrenElement = function () {
	var el = this.getElement();
	if (!el) return null;
	return el.lastChild;
};


// IE uses about:blank if not attached to document and this can cause Win2k3
// to fail
if (webFXTreeHandler.ie) {
	_p.create = function () {
		var dummy = document.createElement("div");
		dummy.style.display = "none";
		document.body.appendChild(dummy);
		dummy.innerHTML = this.toHtml();
		var res = dummy.removeChild(dummy.firstChild);
		document.body.removeChild(dummy);
		return res;
	};
} else {
	_p.create = function () {
		var dummy = document.createElement("div");
		dummy.innerHTML = this.toHtml();
		return dummy.removeChild(dummy.firstChild);
	};
}

// Getters and setters for some common fields

_p.setIcon = function (s) {
	this.icon = s;
	if (this.getCreated()) {
		this.updateIcon();
	}
};

_p.getIcon = function () {
	return this.icon;
};

_p.setOpenIcon = function (s) {
	this.openIcon = s;
	if (this.getCreated()) {
		this.updateIcon();
	}
};

_p.getOpenIcon = function () {
	return this.openIcon;
};

_p.setText = function (s) {
	this.setHtml(webFXTreeHandler.textToHtml(s));
};

_p.getText = function () {
	return webFXTreeHandler.htmlToText(this.getHtml());
};

_p.setHtml = function (s) {
	this.text = s;
	var el = this.getLabelElement();
	if (el) {
		el.innerHTML = s;
	}
};

_p.getHtml = function () {
	return this.text;
};

_p.setTarget = function (s) {
	this.target = s;
};

_p.getTarget = function () {
	return this.target;
};

_p.setToolTip = function (s) {
	this.toolTip = s;
	var el = this.getLabelElement();
	if (el) {
		el.title = s;
	}
};

_p.getToolTip = function () {
	return this.toolTip;
};

_p.setAction = function (oAction) {
	this.action = oAction;
	var el = this.getLabelElement();
	if (el) {
		el.href = this._getHref();
	}
	el = this.getRowElement();
	if (el) {
		el.className = this.getRowClassName();
	}
};

_p.getAction = function () {
	return this.action;
};

_p.setIconAction = function (oAction) {
	this.iconAction = oAction;
	var el = this.getIconElement();
	if (el) {
		el.href = this._getIconHref();
	}   
}   

_p.getIconAction = function () {
	if (this.iconAction)
		return this.iconAction;
	return _p.getAction();
};

// update methods

_p.update = function () {
	var t = this.getTree();
	if (t.suspendRedraw) return;
	var el = this.getElement();
	if (!el || !el.parentNode) return;
	var newEl = this.create();
	el.parentNode.replaceChild(newEl, el);
	this._setTabIndex(this.tabIndex); // in case root had the tab index
	var si = t.getSelected();
	if (si && si.getFocused()) {
		si.focus();
	}
};

_p.updateExpandIcon = function () {
	var t = this.getTree();
	if (t.suspendRedraw) return;
	var img = this.getExpandIconElement();
	img.src = this.getExpandIconSrc();
	var cel = this.getChildrenElement();
	cel.style.backgroundPosition = this.getLineStyle2();
};

_p.updateIcon = function () {
	var t = this.getTree();
	if (t.suspendRedraw) return;
	var img = this.getIconElement();
	img.src = this.getIconSrc();
};

// End DOM

_p._callSuspended = function (f) {
	var t = this.getTree();
	var sr = t.getSuspendRedraw();
	t.setSuspendRedraw(true);
	f.call(this);
	t.setSuspendRedraw(sr);
};

// Event handlers

_p._onmousedown = function (e) {
	var el = e.target || e.srcElement;
	// expand icon
	if (/webfx-tree-expand-icon/.test(el.className) && this.hasChildren()) {
		this.toggle();
		if (webFXTreeHandler.ie) {
			window.setTimeout("WebFXTreeAbstractNode._onTimeoutFocus(\"" + this.id + "\")", 10);
		}
		return false;
	}

	this.select();
	if (/*!/webfx-tree-item-label/.test(el.className) && */!webFXTreeHandler.opera)	{ // opera cancels the click if focus is called
		
		// in case we are not clicking on the label
		if (webFXTreeHandler.ie) {
			window.setTimeout("WebFXTreeAbstractNode._onTimeoutFocus(\"" + this.id + "\")", 10);
		} else {
			this.focus();
		}
	}
	var rowEl = this.getRowElement();
	if (rowEl) {
		rowEl.className = this.getRowClassName();
	}

	return false;
};

_p._onclick = function (e) {
	var el = e.target || e.srcElement;
	// expand icon
	if (/webfx-tree-expand-icon/.test(el.className) && this.hasChildren()) {
		return false;
	}

	var doAction = null;
	if (/webfx-tree-icon/.test(el.className) && this.iconAction) {
		doAction = this.iconAction; 
	} else {
		doAction = this.action;
	}

	if (typeof doAction == "function") {
		doAction();
	} else if (doAction != null) {
		window.open(doAction, this.target || "_self");
	}
	return false;
};


_p._ondblclick = function (e) {
	var el = e.target || e.srcElement;
	// expand icon
	if (/webfx-tree-expand-icon/.test(el.className) && this.hasChildren()) {
		return;
	}

	this.toggle();
};

_p._onfocus = function (e) {
	this.select();
	this._focused = true;
};

_p._onblur = function (e) {
	this._focused = false;
};

_p._onkeydown = function (e) {
	var n;
	var rv = true;
	switch (e.keyCode) {
		case 39:	// RIGHT
			if (e.altKey) {
				rv = true;
				break;
			}
			if (this.hasChildren()) {
				if (!this.getExpanded()) {
					this.setExpanded(true);
				} else {
					this.getFirstChild().focus();
				}
			}
			rv = false;
			break;
		case 37:	// LEFT
			if (e.altKey) {
				rv = true;
				break;
			}
			if (this.hasChildren() && this.getExpanded()) {
				this.setExpanded(false);
			} else {
				var p = this.getParent();
				var t = this.getTree();
				// don't go to root if hidden
				if (p && (t.showRootNode || p != t)) {
					p.focus();
				}
			}
			rv = false;
			break;

		case 40:	// DOWN
			n = this.getNextShownNode();
			if (n) {
				n.focus();
			}
			rv = false;
			break;
		case 38:	// UP
			n = this.getPreviousShownNode()
			if (n) {
				n.focus();
			}
			rv = false;
			break;
	}

	if (!rv && e.preventDefault) {
		e.preventDefault();
	}
	e.returnValue = rv;
	return rv;
};

_p._onkeypress = function (e) {
	if (!e.altKey && e.keyCode >= 37 && e.keyCode <= 40) {
		if (e.preventDefault) {
			e.preventDefault();
		}
		e.returnValue = false;
		return false;
	}
};

// End event handlers

_p.dispose = function () {
	if (this.disposed) return;
	for (var i = this.childNodes.length - 1; i >= 0; i--) {
		this.childNodes[i].dispose();
	}
	this.tree = null;
	this.parentNode = null;
	this.childNodes = null;
	this.disposed = true;
};

// Some methods that are usable when navigating the tree using the arrows
_p.getLastShownDescendant = function () {
	if (!this.getExpanded() || !this.hasChildren()) {
		return this;
	}
	// we know there is at least 1 child
	return this.getLastChild().getLastShownDescendant();
};

_p.getNextShownNode = function () {
	if (this.hasChildren() && this.getExpanded()) {
		return this.getFirstChild();
	} else {
		var p = this;
		var next;
		while (p != null) {
			next = p.getNextSibling();
			if (next != null) {
				return next;
			}
			p = p.getParent();
		}
		return null;
	}
};

_p.getPreviousShownNode = function () {
	var ps = this.getPreviousSibling();
	if (ps != null) {
		return ps.getLastShownDescendant();
	}
	var p = this.getParent();
	var t = this.getTree();
	if (!t.showRootNode && p == t) {
		return null;
	}
	return p;
};







///////////////////////////////////////////////////////////////////////////////
// WebFXTree
///////////////////////////////////////////////////////////////////////////////

function WebFXTree(sText, oAction, sBehavior, sIcon, oIconAction, sOpenIcon) {
	WebFXTreeAbstractNode.call(this, sText, oAction, oIconAction);
	if (sIcon) this.icon = sIcon;
	if (sOpenIcon) this.openIcon = sOpenIcon;
	if (sBehavior) this.behavior = sBehavior;
}

_p = WebFXTree.prototype = new WebFXTreeAbstractNode;
_p.indentWidth = 19;
_p.open = true;
_p._selectedItem = null;
_p._fireChange = true;
_p.rendered = false;
_p.suspendRedraw = false;
_p.showLines = true;
_p.showExpandIcons = true;
_p.showRootNode = true;
_p.showRootLines = true;

_p.getTree = function () {
	return this;
};

_p.getDepth = function () {
	return 0;
};

_p.getCreated = function () {
	return this.rendered;
};


/* end tree model */

_p.getExpanded = function () {
	return !this.showRootNode || WebFXTreeAbstractNode.prototype.getExpanded.call(this);
};

_p.setExpanded = function (b) {
	if (!this.showRootNode) {
		this.open = b;
	} else {
		WebFXTreeAbstractNode.prototype.setExpanded.call(this, b);
	}
};

_p.getExpandIconHtml = function () {
	return "";
};

// we don't have an expand icon here
_p.getIconElement = function () {
	var el = this.getRowElement();
	if (!el) return null;
	return el.firstChild;
};

// no expand icon for root element
_p.getExpandIconElement = function (oDoc) {
	return null;
};

_p.updateExpandIcon = function () {
	// no expand icon
};

_p.getRowClassName = function () {
	return WebFXTreeAbstractNode.prototype.getRowClassName.call(this) +
		(this.showRootNode ? "" : " webfx-tree-hide-root");
};


// if classic then the openIcon is used for expanded, otherwise openIcon is used
// for selected

_p.getIconSrc = function () {
	var behavior = this.getTree() ? this.getTree().getBehavior() : webFXTreeConfig.defaultBehavior;
	var open = behavior == "classic" && this.getExpanded() ||
			   behavior != "classic" && this.isSelected();
	if (open && this.openIcon) {
		return this.openIcon;
	}
	if (!open && this.icon) {
		return this.icon;
	}
	// fall back on default icons
	return open ? webFXTreeConfig.openRootIcon : webFXTreeConfig.rootIcon;
};

_p.getEventHandlersHtml = function () {
	return " onclick=\"return webFXTreeHandler.handleEvent(event)\" " +
		"onmousedown=\"return webFXTreeHandler.handleEvent(event)\" " +
		"ondblclick=\"return webFXTreeHandler.handleEvent(event)\" " +
		"onkeydown=\"return webFXTreeHandler.handleEvent(event)\" " +
		"onkeypress=\"return webFXTreeHandler.handleEvent(event)\"";
};

_p.setSelected = function (o) {
	if (this._selectedItem != o && o) {
		o._setSelected(true);
	}
};

_p._fireOnChange = function () {
	if (this._fireChange && typeof this.onchange == "function") {
		this.onchange();
	}
};

_p.getSelected = function () {
	return this._selectedItem;
};

_p.tabIndex = "";

_p.setTabIndex = function (i) {
	var n = this._selectedItem || (this.showRootNode ? this : this.firstChild);
	this.tabIndex = i;
	if (n) {
		n._setTabIndex(i);
	}	
};

_p.getTabIndex = function () {
	return this.tabIndex;
};

_p.setBehavior = function (s) {
	this.behavior = s;
};

_p.getBehavior = function () {
	return this.behavior || webFXTreeConfig.defaultBehavior;
};

_p.setShowLines = function (b) {
	if (this.showLines != b) {
		this.showLines = b;
		if (this.rendered) {
			this.update();
		}
	}
};

_p.getShowLines = function () {
	return this.showLines;
};

_p.setShowRootLines = function (b) {
	if (this.showRootLines != b) {
		this.showRootLines = b;
		if (this.rendered) {
			this.update();
		}
	}
};

_p.getShowRootLines = function () {
	return this.showRootLines;
};

_p.setShowExpandIcons = function (b) {
	if (this.showExpandIcons != b) {
		this.showExpandIcons = b;
		if (this.rendered) {
			this.getTree().update();
		}
	}
};

_p.getShowExpandIcons = function () {
	return this.showExpandIcons;
};

_p.setShowRootNode = function (b) {
	if (this.showRootNode != b) {
		this.showRootNode = b;
		if (this.rendered) {
			this.getTree().update();
		}
	}
};

_p.getShowRoootNode = function () {
	return this.showRootNode;
};

_p.onchange = function () {};

_p.create = function () {
	var el = WebFXTreeAbstractNode.prototype.create.call(this);
	this.setTabIndex(this.tabIndex);
	this.rendered = true;
	return el;
};

_p.write = function () {
	document.write(this.toHtml());
	this.setTabIndex(this.tabIndex);
	this.rendered = true;
};

_p.setSuspendRedraw = function (b) {
	this.suspendRedraw = b;
};

_p.getSuspendRedraw = function () {
	return this.suspendRedraw;
};



///////////////////////////////////////////////////////////////////////////////
// WebFXTreeItem
///////////////////////////////////////////////////////////////////////////////

function WebFXTreeItem(sText, oAction, eParent, sIcon, oIconAction, sOpenIcon) {
	WebFXTreeAbstractNode.call(this, sText, oAction, oIconAction);
	if (sIcon) this.icon = sIcon;
	if (sOpenIcon) this.openIcon = sOpenIcon;
	if (eParent) eParent.add(this);
}

_p = WebFXTreeItem.prototype = new WebFXTreeAbstractNode;
_p.tree = null;

/* tree model */

_p.getDepth = function () {
	if (this.depth != null) {
		return this.depth;
	}
	if (this.parentNode) {
		var pd = this.parentNode.getDepth();
		return this.depth = (pd != null ? pd + 1 : null);
	}
	return null;
};

_p.getTree = function () {
	if (this.tree) {
		return this.tree;
	}
	if (this.parentNode) {
		return this.tree = this.parentNode.getTree();
	}
	return null;
};

_p.getCreated = function () {
	var t = this.getTree();
	return t && t.getCreated();
};

// if classic then the openIcon is used for expanded, otherwise openIcon is used
// for selected
_p.getIconSrc = function () {
	var behavior = this.getTree() ? this.getTree().getBehavior() : webFXTreeConfig.defaultBehavior;
	var open = behavior == "classic" && this.getExpanded() ||
	           behavior != "classic" && this.isSelected();
	if (open && this.openIcon) {
		return this.openIcon;
	}
	if (!open && this.icon) {
		return this.icon;
	}

	// fall back on default icons
	if (this.hasChildren()) {
		return open ? webFXTreeConfig.openFolderIcon : webFXTreeConfig.folderIcon;
	}
	return webFXTreeConfig.fileIcon;
};

/* end tree model */




if (window.attachEvent) {
	window.attachEvent("onunload", function () {
		for (var id in webFXTreeHandler.all)
			webFXTreeHandler.all[id].dispose();
	});
}
