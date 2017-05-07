jush.tr.htm = { php: jush.php, tag_css: /(<)(style)\b/i, tag_js: /(<)(script)\b/i, htm_com: /<!--/, tag: /(<)(\/?[-\w]+)/, ent: /&/ };
jush.tr.htm_com = { php: jush.php, _1: /-->/ };
jush.tr.ent = { php: jush.php, _1: /[;\s]/ };
jush.tr.tag = { php: jush.php, att_css: /(\s*)(style)(\s*=\s*|$)/i, att_js: /(\s*)(on[-\w]+)(\s*=\s*|$)/i, att_http: /(\s*)(http-equiv)(\s*=\s*|$)/i, att: /(\s*)([-\w]+)()/, _1: />/ };
jush.tr.tag_css = { php: jush.php, att: /(\s*)([-\w]+)()/, css: />/ };
jush.tr.tag_js = { php: jush.php, att: /(\s*)([-\w]+)()/, js: />/ };
jush.tr.att = { php: jush.php, att_quo: /\s*=\s*"/, att_apo: /\s*=\s*'/, att_val: /\s*=\s*/, _1: /()/ };
jush.tr.att_css = { php: jush.php, att_quo: /"/, att_apo: /'/, att_val: /\s*/ };
jush.tr.att_js = { php: jush.php, att_quo: /"/, att_apo: /'/, att_val: /\s*/ };
jush.tr.att_http = { php: jush.php, att_quo: /"/, att_apo: /'/, att_val: /\s*/ };
jush.tr.att_quo = { php: jush.php, _2: /"/ };
jush.tr.att_apo = { php: jush.php, _2: /'/ };
jush.tr.att_val = { php: jush.php, _2: /(?=>|\s)/ };
jush.tr.xml = { php: jush.php, htm_com: /<!--/, xml_tag: /(<)(\/?[-\w:]+)/, ent: /&/ };
jush.tr.xml_tag = { php: jush.php, xml_att: /(\s*)([-\w:]+)()/, _1: />/ };
jush.tr.xml_att = { php: jush.php, att_quo: /\s*=\s*"/, att_apo: /\s*=\s*'/, _1: /()/ };

jush.urls.tag = 'http://www.w3.org/TR/html4/$key.html#edef-$val';
jush.urls.tag_css = 'http://www.w3.org/TR/html4/$key.html#edef-$val';
jush.urls.tag_js = 'http://www.w3.org/TR/html4/$key.html#edef-$val';
jush.urls.att = 'http://www.w3.org/TR/html4/$key.html#adef-$val';
jush.urls.att_css = 'http://www.w3.org/TR/html4/$key.html#adef-$val';
jush.urls.att_js = 'http://www.w3.org/TR/html4/$key.html#adef-$val';
jush.urls.att_http = 'http://www.w3.org/TR/html4/$key.html#adef-$val';

jush.links.tag = {
	'interact/forms': /^(button|fieldset|form|input|isindex|label|legend|optgroup|option|select|textarea)$/i,
	'interact/scripts': /^(noscript)$/i,
	'present/frames': /^(frame|frameset|iframe|noframes)$/i,
	'present/graphics': /^(b|basefont|big|center|font|hr|i|s|small|strike|tt|u)$/i,
	'struct/dirlang': /^(bdo)$/i,
	'struct/global': /^(address|body|div|h1|h2|h3|h4|h5|h6|head|html|meta|span|title)$/i,
	'struct/links': /^(a|base|link)$/i,
	'struct/lists': /^(dd|dir|dl|dt|li|menu|ol|ul)$/i,
	'struct/objects': /^(applet|area|img|map|object|param)$/i,
	'struct/tables': /^(caption|col|colgroup|table|tbody|td|tfoot|th|thead|tr)$/i,
	'struct/text': /^(abbr|acronym|blockquote|br|cite|code|del|dfn|em|ins|kbd|p|pre|q|samp|strong|sub|sup|var)$/i,
	'http://whatwg.org/html/sections.html#the-$val-element': /^(section|article|aside|hgroup|header|footer|nav)$/i,
	'http://whatwg.org/html/grouping-content.html#the-$val-element': /^(main|figure|figcaption)$/i,
	'http://whatwg.org/html/the-video-element.html#the-$val-element': /^(video|audio|source|track)$/i,
	'http://whatwg.org/html/the-iframe-element.html#the-$val-element': /^(embed)$/i,
	'http://whatwg.org/html/text-level-semantics.html#the-$val-element': /^(mark|time|data|ruby|rt|rp|bdi|wbr)$/i,
	'http://whatwg.org/html/the-button-element.html#the-$val-element': /^(progress|meter|datalist|keygen|output)$/i,
	'http://whatwg.org/html/commands.html#the-$val-element': /^(dialog)$/i,
	'http://whatwg.org/html/the-canvas-element.html#the-$val-element': /^(canvas)$/i,
	'http://whatwg.org/html/interactive-elements.html#the-$val-element': /^(menuitem|details|summary)$/i
};
jush.links.tag_css = { 'present/styles': /^(style)$/i };
jush.links.tag_js = { 'interact/scripts': /^(script)$/i };
jush.links.att_css = { 'present/styles': /^(style)$/i };
jush.links.att_js = {
	'interact/scripts': /^(onblur|onchange|onclick|ondblclick|onfocus|onkeydown|onkeypress|onkeyup|onload|onmousedown|onmousemove|onmouseout|onmouseover|onmouseup|onreset|onselect|onsubmit|onunload|onunload)$/i,
	'http://whatwg.org/html/webappapis.html#handler-$val': /^(onabort|oncancel|oncanplay|oncanplaythrough|onclose|oncontextmenu|oncuechange|ondrag|ondragend|ondragenter|ondragexit|ondragleave|ondragover|ondragstart|ondrop|ondurationchange|onemptied|onended|oninput|oninvalid|onloadeddata|onloadedmetadata|onloadstart|onmouseenter|onmouseleave|onmousewheel|onpause|onplay|onplaying|onprogress|onratechange|onseeked|onseeking|onshow|onsort|onstalled|onsuspend|ontimeupdate|onvolumechange|onwaiting)$/i
};
jush.links.att_http = { 'struct/global': /^(http-equiv)$/i };
jush.links.att = {
	'interact/forms': /^(accept-charset|accept|accesskey|action|align-LEGEND|checked|cols-TEXTAREA|disabled|enctype|for|label-OPTION|label-OPTGROUP|maxlength|method|multiple|name-BUTTON|name-SELECT|name-FORM|name-INPUT|prompt|readonly|rows-TEXTAREA|selected|size-INPUT|size-SELECT|src|tabindex|type-INPUT|type-BUTTON|value-INPUT|value-OPTION|value-BUTTON)$/i,
	'interact/scripts': /^(defer|language|src-SCRIPT|type-SCRIPT)$/i,
	'present/frames': /^(cols-FRAMESET|frameborder|height-IFRAME|longdesc-FRAME|marginheight|marginwidth|name-FRAME|noresize|rows-FRAMESET|scrolling|src-FRAME|target|width-IFRAME)$/i,
	'present/graphics': /^(align-HR|align|bgcolor|clear|color-FONT|face-FONT|noshade|size-HR|size-FONT|size-BASEFONT|width-HR)$/i,
	'present/styles': /^(media|type-STYLE)$/i,
	'struct/dirlang': /^(dir|dir-BDO|lang)$/i,
	'struct/global': /^(alink|background|class|content|id|link|name-META|profile|scheme|text|title|version|vlink)$/i,
	'struct/links': /^(charset|href|href-BASE|hreflang|name-A|rel|rev|type-A)$/i,
	'struct/lists': /^(compact|start|type-LI|type-OL|type-UL|value-LI)$/i,
	'struct/objects': /^(align-IMG|alt|archive-APPLET|archive-OBJECT|border-IMG|classid|code|codebase-OBJECT|codebase-APPLET|codetype|coords|data|declare|height-IMG|height-APPLET|hspace|ismap|longdesc-IMG|name-APPLET|name-IMG|name-MAP|name-PARAM|nohref|object|shape|src-IMG|standby|type-OBJECT|type-PARAM|usemap|value-PARAM|valuetype|vspace|width-IMG|width-APPLET)$/i,
	'struct/tables': /^(abbr|align-CAPTION|align-TABLE|align-TD|axis|border-TABLE|cellpadding|cellspacing|char|charoff|colspan|frame|headers|height-TH|nowrap|rowspan|rules|scope|span-COL|span-COLGROUP|summary|valign|width-TABLE|width-TH|width-COL|width-COLGROUP)$/i,
	'struct/text': /^(cite-Q|cite-INS|datetime|width-PRE)$/i,
	'http://whatwg.org/html/links.html#attr-hyperlink-$val': /^(download)$/i,
	'http://whatwg.org/html/semantics.html#attr-meta-$val': /^(charset)$/i,
	'http://whatwg.org/html/tabular-data.html#attr-table-$val': /^(sortable)$/i,
	'http://whatwg.org/html/tabular-data.html#attr-th-$val': /^(sorted)$/i,
	'http://whatwg.org/html/association-of-controls-and-forms.html#attr-fe-$val': /^(autofocus|autocomplete|dirname|inputmode)$/i,
	'http://whatwg.org/html/common-input-element-attributes.html#attr-input-$val': /^(placeholder|required|min|max|pattern|step|list)$/i,
	'http://whatwg.org/html/association-of-controls-and-forms.html#attr-fae-$val': /^(form)$/i,
	'http://whatwg.org/html/the-button-element.html#attr-textarea-$val': /^(wrap)$/i,
	'http://whatwg.org/html/association-of-controls-and-forms.html#attr-fs-$val': /^(novalidate|formaction|formenctype|formmethod|formnovalidate|formtarget)$/i,
	'http://whatwg.org/html/interactive-elements.html#attr-$val': /^(contextmenu)$/i,
	'http://whatwg.org/html/the-button-element.html#attr-button-$val': /^(menu)$/i,
	'http://whatwg.org/html/semantics.html#attr-style-$val': /^(scoped)$/i,
	'http://whatwg.org/html/scripting-1.html#attr-script-$val': /^(async)$/i,
	'http://whatwg.org/html/semantics.html#attr-html-$val': /^(manifest)$/i,
	'http://whatwg.org/html/links.html#attr-link-$val': /^(sizes)$/i,
	'http://whatwg.org/html/grouping-content.html#attr-ol-$val': /^(reversed)$/i,
	'http://whatwg.org/html/the-iframe-element.html#attr-iframe-$val': /^(sandbox|seamless|srcdoc)$/i,
	'http://whatwg.org/html/the-iframe-element.html#attr-object-$val': /^(typemustmatch)$/i,
	'http://whatwg.org/html/embedded-content-1.html#attr-img-$val': /^(crossorigin|srcset)$/i,
	'http://whatwg.org/html/editing.html#attr-$val': /^(contenteditable|spellcheck)$/i,
	'http://whatwg.org/html/elements.html#attr-data-*': /^(data-.+)$/i,
	'http://whatwg.org/html/dnd.html#the-$val-attribute': /^(draggable|dropzone)$/i,
	'http://whatwg.org/html/editing.html#the-$val-attribute': /^(hidden|inert)$/i,
	'http://www.w3.org/WAI/PF/aria/states_and_properties#$val': /^(aria-.+)$/i,
	'http://whatwg.org/html/infrastructure.html#attr-aria-$val': /^(role)$/i,
	'http://whatwg.org/html/elements.html#attr-$val': /^(translate)$/i,
	'http://schema.org/docs/gs.html#microdata_itemscope_itemtype': /^(itemscope|itemtype)$/i,
	'http://schema.org/docs/gs.html#microdata_$val': /^(itemprop)$/i
};
