jush.textarea = (function () {
	//! IE sometimes inserts empty <p> in start of a string when newline is entered inside
	
	function findPosition(el, container, offset) {
		var pos = { pos: 0 };
		findPositionRecurse(el, container, offset, pos);
		return pos.pos;
	}

	function findPositionRecurse(child, container, offset, pos) {
		if (child.nodeType == 3) {
			if (child == container) {
				pos.pos += offset;
				return true;
			}
			pos.pos += child.textContent.length;
		} else if (child == container) {
			for (var i = 0; i < offset; i++) {
				findPositionRecurse(child.childNodes[i], container, offset, pos);
			}
			return true;
		} else {
			if (/^(br|div)$/i.test(child.tagName)) {
				pos.pos++;
			}
			for (var i = 0; i < child.childNodes.length; i++) {
				if (findPositionRecurse(child.childNodes[i], container, offset, pos)) {
					return true;
				}
			}
			if (/^p$/i.test(child.tagName)) {
				pos.pos++;
			}
		}
	}
	
	function findOffset(el, pos) {
		return findOffsetRecurse(el, { pos: pos });
	}
	
	function findOffsetRecurse(child, pos) {
		if (child.nodeType == 3) { // 3 - TEXT_NODE
			if (child.textContent.length >= pos.pos) {
				return { container: child, offset: pos.pos };
			}
			pos.pos -= child.textContent.length;
		} else {
			for (var i = 0; i < child.childNodes.length; i++) {
				if (/^br$/i.test(child.childNodes[i].tagName)) {
					if (!pos.pos) {
						return { container: child, offset: i };
					}
					pos.pos--;
					if (!pos.pos && i == child.childNodes.length - 1) { // last invisible <br>
						return { container: child, offset: i };
					}
				} else {
					var result = findOffsetRecurse(child.childNodes[i], pos);
					if (result) {
						return result;
					}
				}
			}
		}
	}
	
	function setText(pre, text, end) {
		var lang = 'txt';
		if (text.length < 1e4) { // highlighting is slow with most languages
			var match = /(^|\s)(?:jush|language)-(\S+)/.exec(pre.jushTextarea.className);
			lang = (match ? match[2] : 'htm');
		}
		var html = jush.highlight(lang, text).replace(/\n/g, '<br>');
		setHTML(pre, html, text, end);
	}
	
	function setHTML(pre, html, text, pos) {
		pre.innerHTML = html;
		pre.lastHTML = pre.innerHTML; // not html because IE reformats the string
		pre.jushTextarea.value = text;
		if (pos) {
			var start = findOffset(pre, pos);
			if (start) {
				var range = document.createRange();
				range.setStart(start.container, start.offset);
				var sel = getSelection();
				sel.removeAllRanges();
				sel.addRange(range);
			}
		}
	}
	
	function keydown(event) {
		event = event || window.event;
		if ((event.ctrlKey || event.metaKey) && !event.altKey) {
			var isUndo = (event.keyCode == 90); // 90 - z
			var isRedo = (event.keyCode == 89); // 89 - y
			if (isUndo || isRedo) {
				if (isRedo) {
					if (this.jushUndoPos + 1 < this.jushUndo.length) {
						this.jushUndoPos++;
						var undo = this.jushUndo[this.jushUndoPos];
						setText(this, undo.text, undo.end)
					}
				} else if (this.jushUndoPos >= 0) {
					this.jushUndoPos--;
					var undo = this.jushUndo[this.jushUndoPos] || { html: '', text: '' };
					setText(this, undo.text, this.jushUndo[this.jushUndoPos + 1].start);
				}
				return false;
			}
		} else {
			setLastPos(this);
		}
	}
	
	function setLastPos(pre) {
		var sel = getSelection();
		if (sel.rangeCount) {
			var range = sel.getRangeAt(0);
			if (pre.lastPos === undefined) {
				pre.lastPos = findPosition(pre, range.endContainer, range.endOffset);
			}
		}
	}
	
	function highlight(pre, forceNewUndo) {
		var start = pre.lastPos;
		pre.lastPos = undefined;
		var innerHTML = pre.innerHTML;
		if (innerHTML != pre.lastHTML) {
			var end;
			var sel = getSelection();
			if (sel.rangeCount) {
				var range = sel.getRangeAt(0);
				end = findPosition(pre, range.startContainer, range.startOffset);
			}
			innerHTML = innerHTML.replace(/<br>((<\/[^>]+>)*<\/?div>)(?!$)/gi, function (all, rest) {
				if (end) {
					end--;
				}
				return rest;
			});
			pre.innerHTML = innerHTML
				.replace(/<(br|div)\b[^>]*>/gi, '\n') // Firefox, Chrome
				.replace(/&nbsp;(<\/[pP]\b)/g, '$1') // IE
				.replace(/<\/p\b[^>]*>($|<p\b[^>]*>)/gi, '\n') // IE
				.replace(/(&nbsp;)+$/gm, '') // Chrome for some users
			;
			setText(pre, pre.textContent, end);
			pre.jushUndo.length = pre.jushUndoPos + 1;
			if (forceNewUndo || !pre.jushUndo.length || pre.jushUndo[pre.jushUndoPos].end !== start) {
				pre.jushUndo.push({ text: pre.jushTextarea.value, start: start, end: (forceNewUndo ? undefined : end) });
				pre.jushUndoPos++;
			} else {
				pre.jushUndo[pre.jushUndoPos].text = pre.jushTextarea.value;
				pre.jushUndo[pre.jushUndoPos].end = end;
			}
		}
	}
	
	function keyup() {
		highlight(this);
	}
	
	function paste(event) {
		event = event || window.event;
		if (event.clipboardData) {
			setLastPos(this);
			if (document.execCommand('insertHTML', false, jush.htmlspecialchars(event.clipboardData.getData('text')))) { // Opera doesn't support insertText
				event.preventDefault();
			}
			highlight(this, true);
		}
	}
	
	return function textarea(el) {
		if (!window.getSelection) {
			return;
		}
		var pre = document.createElement('pre');
		pre.contentEditable = true;
		pre.className = el.className + ' jush';
		pre.style.border = '1px inset #ccc';
		pre.style.width = el.clientWidth + 'px';
		pre.style.height = el.clientHeight + 'px';
		pre.style.padding = '3px';
		pre.style.overflow = 'auto';
		pre.style.resize = 'both';
		if (el.wrap != 'off') {
			pre.style.whiteSpace = 'pre-wrap';
		}
		pre.jushTextarea = el;
		pre.jushUndo = [ ];
		pre.jushUndoPos = -1;
		pre.onkeydown = keydown;
		pre.onkeyup = keyup;
		pre.onpaste = paste;
		pre.appendChild(document.createTextNode(el.value));
		highlight(pre);
		if (el.spellcheck === false) {
			document.documentElement.spellcheck = false; // doesn't work when set on pre or its parent in Firefox
		}
		el.parentNode.insertBefore(pre, el);
		if (document.activeElement === el && !/firefox/i.test(navigator.userAgent)) { // clicking on focused element makes Firefox to lose focus
			pre.focus();
		}
		el.style.display = 'none';
		return pre;
	};
})();
