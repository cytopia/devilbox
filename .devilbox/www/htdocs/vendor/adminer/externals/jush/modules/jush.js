/** JUSH - JavaScript Syntax Highlighter
* @link http://jush.sourceforge.net
* @author Jakub Vrana, http://www.vrana.cz
* @copyright 2007 Jakub Vrana
* @license http://www.apache.org/licenses/LICENSE-2.0 Apache License, Version 2.0
*/

/* Limitations:
<style> and <script> supposes CDATA or HTML comments
unnecessary escaping (e.g. echo "\'" or ='&quot;') is removed
*/

var jush = {
	create_links: true, // string for extra <a> parameters, e.g. ' target="_blank"'
	timeout: 1000, // milliseconds
	custom_links: { }, // { state: [ url, regexp ] }, for example { php : [ 'doc/$&.html', /\b(getData|setData)\b/g ] }
	api: { }, // { state: { function: description } }, for example { php: { array: 'Create an array' } }
	
	php: /<\?(?!xml)(?:php)?|<script\s+language\s*=\s*(?:"php"|'php'|php)\s*>/i, // asp_tags=0, short_open_tag=1
	num: /(?:0x[0-9a-f]+)|(?:\b[0-9]+\.?[0-9]*|\.[0-9]+)(?:e[+-]?[0-9]+)?/i,
	
	regexps: undefined,
	subpatterns: { },

	/** Link stylesheet
	* @param string
	*/
	style: function (href) {
		var link = document.createElement('link');
		link.rel = 'stylesheet';
		link.type = 'text/css';
		link.href = href;
		document.getElementsByTagName('head')[0].appendChild(link);
	},

	/** Highlight text
	* @param string
	* @param string
	* @return string
	*/
	highlight: function (language, text) {
		this.last_tag = '';
		this.last_class = '';
		return '<span class="jush">' + this.highlight_states([ language ], text.replace(/\r\n?/g, '\n'), !/^(htm|tag|xml|txt)$/.test(language))[0] + '</span>';
	},

	/** Highlight html
	* @param string
	* @param string
	* @return string
	*/
	highlight_html: function (language, html) {
		var original = html.replace(/<br(\s+[^>]*)?>/gi, '\n');
		var highlighted = jush.highlight(language, jush.html_entity_decode(original.replace(/<[^>]*>/g, ''))).replace(/(^|\n| ) /g, '$1&nbsp;');
		
		var inject = { };
		var pos = 0;
		var last_offset = 0;
		original.replace(/(&[^;]+;)|(?:<[^>]+>)+/g, function (str, entity, offset) {
			pos += (offset - last_offset) + (entity ? 1 : 0);
			if (!entity) {
				inject[pos] = str;
			}
			last_offset = offset + str.length;
		});
		
		pos = 0;
		highlighted = highlighted.replace(/([^&<]*)(?:(&[^;]+;)|(?:<[^>]+>)+|$)/g, function (str, text, entity) {
			for (var i = text.length; i >= 0; i--) {
				if (inject[pos + i]) {
					str = str.substr(0, i) + inject[pos + i] + str.substr(i);
					delete inject[pos + i];
				}
			}
			pos += text.length + (entity ? 1 : 0);
			return str;
		});
		return highlighted;
	},

	/** Highlight text in tags
	* @param mixed tag name or array of HTMLElement
	* @param number number of spaces for tab, 0 for tab itself, defaults to 4
	*/
	highlight_tag: function (tag, tab_width) {
		var pre = (typeof tag == 'string' ? document.getElementsByTagName(tag) : tag);
		var tab = '';
		for (var i = (tab_width !== undefined ? tab_width : 4); i--; ) {
			tab += ' ';
		}
		var i = 0;
		var highlight = function () {
			var start = new Date();
			while (i < pre.length) {
				var match = /(^|\s)(?:jush|language(?=-\S))($|\s|-(\S+))/.exec(pre[i].className); // http://www.w3.org/TR/html5/text-level-semantics.html#the-code-element
				if (match) {
					var language = match[3] ? match[3] : 'htm';
					var s = '<span class="jush-' + language + '">' + jush.highlight_html(language, pre[i].innerHTML.replace(/\t/g, tab.length ? tab : '\t')) + '</span>'; // span - enable style for class="language-"
					if (pre[i].outerHTML && /^pre$/i.test(pre[i].tagName)) {
						pre[i].outerHTML = pre[i].outerHTML.match(/[^>]+>/)[0] + s + '</' + pre[i].tagName + '>';
					} else {
						pre[i].innerHTML = s.replace(/\n/g, '<br />');
					}
				}
				i++;
				if (jush.timeout && window.setTimeout && (new Date() - start) > jush.timeout) {
					window.setTimeout(highlight, 100);
					break;
				}
			}
		};
		highlight();
	},
	
	link_manual: function (language, text) {
		var code = document.createElement('code');
		code.innerHTML = this.highlight(language, text);
		var as = code.getElementsByTagName('a');
		for (var i = 0; i < as.length; i++) {
			if (as[i].href) {
				return as[i].href;
			}
		}
		return '';
	},

	create_link: function (link, s, attrs) {
		return '<a'
			+ (this.create_links && link ? ' href="' + link + '" class="jush-help"' : '')
			+ (typeof this.create_links == 'string' ? this.create_links : '')
			+ (attrs || '')
			+ '>' + s + '</a>'
		;
	},

	keywords_links: function (state, s) {
		if (/^js(_write|_code)+$/.test(state)) {
			state = 'js';
		}
		if (/^(php_quo_var|php_php|php_sql|php_sqlite|php_pgsql|php_mssql|php_oracle|php_echo|php_phpini|php_http|php_mail)$/.test(state)) {
			state = 'php2';
		}
		if (state == 'sql_code') {
			state = 'sql';
		}
		if (this.links2 && this.links2[state]) {
			var url = this.urls[state];
			var links2 = this.links2[state];
			s = s.replace(links2, function (str, match1) {
				for (var i=arguments.length - 4; i > 1; i--) {
					if (arguments[i]) {
						var link = (/^http:/.test(url[i-1]) || !url[i-1] ? url[i-1] : url[0].replace(/\$key/g, url[i-1]));
						switch (state) {
							case 'php': link = link.replace(/\$1/g, arguments[i].toLowerCase()); break;
							case 'php_new': link = link.replace(/\$1/g, arguments[i].toLowerCase()); break; // toLowerCase() - case sensitive after #
							case 'phpini': link = link.replace(/\$1/g, (/^suhosin\./.test(arguments[i])) ? arguments[i] : arguments[i].toLowerCase().replace(/_/g, '-')); break;
							case 'php_doc': link = link.replace(/\$1/g, arguments[i].replace(/^\W+/, '')); break;
							case 'js_doc': link = link.replace(/\$1/g, arguments[i].replace(/^\W*(.)/, function (match, p1) { return p1.toUpperCase(); })); break;
							case 'http': link = link.replace(/\$1/g, arguments[i].toLowerCase()); break;
							case 'sql': link = link.replace(/\$1/g, arguments[i].replace(/\b(ALTER|CREATE|DROP|RENAME|SHOW)\s+SCHEMA\b/, '$1 DATABASE').toLowerCase().replace(/\s+|_/g, '-')); break;
							case 'sqlset': link = link.replace(/\$1/g, (links2.test(arguments[i].replace(/_/g, '-')) ? arguments[i].replace(/_/g, '-') : arguments[i]).toLowerCase()); break;
							case 'sqlite': link = link.replace(/\$1/g, arguments[i].toLowerCase().replace(/\s+/g, '')); break;
							case 'sqliteset': link = link.replace(/\$1/g, arguments[i].toLowerCase()); break;
							case 'sqlitestatus': link = link.replace(/\$1/g, arguments[i].toLowerCase()); break;
							case 'pgsql': link = link.replace(/\$1/g, arguments[i].toLowerCase().replace(/\s+/g, (i == 1 ? '-' : ''))); break;
							case 'pgsqlset': link = link.replace(/\$1/g, arguments[i].replace(/_/g, '-').toUpperCase()); break;
							case 'cnf': link = link.replace(/\$1/g, arguments[i].toLowerCase()); break;
							case 'js': link = link.replace(/\$1/g, arguments[i].replace(/\./g, '/')); break;
							default: link = link.replace(/\$1/g, arguments[i]);
						}
						var title = '';
						if (jush.api[state]) {
							title = jush.api[state][(state == 'js' ? arguments[i] : arguments[i].toLowerCase())];
						}
						return (match1 ? match1 : '') + jush.create_link(link, arguments[i], (title ? ' title="' + jush.htmlspecialchars_quo(title) + '"' : '')) + (arguments[arguments.length - 3] ? arguments[arguments.length - 3] : '');
					}
				}
			});
		}
		if (this.custom_links[state]) {
			s = s.replace(this.custom_links[state][1], function (str) {
				var offset = arguments[arguments.length - 2];
				if (/<[^>]*$/.test(s.substr(0, offset))) {
					return str; // don't create links inside tags
				}
				return '<a href="' + jush.htmlspecialchars_quo(jush.custom_links[state][0].replace('$&', encodeURIComponent(str))) + '" class="jush-custom">' + str + '</a>' // not create_link() - ignores create_links
			});
		}
		return s;
	},

	build_regexp: function (key, tr1) {
		var re = [ ];
		subpatterns = [ '' ];
		for (var k in tr1) {
			var in_bra = false;
			subpatterns.push(k);
			var s = tr1[k].source.replace(/\\.|\((?!\?)|\[|]|([a-z])(?:-([a-z]))?/gi, function (str, match1, match2) {
				// count capturing subpatterns
				if (str == (in_bra ? ']' : '[')) {
					in_bra = !in_bra;
				}
				if (str == '(') {
					subpatterns.push(k);
				}
				if (match1 && tr1[k].ignoreCase) {
					if (in_bra) {
						return str.toLowerCase() + str.toUpperCase();
					}
					return '[' + match1.toLowerCase() + match1.toUpperCase() + ']' + (match2 ? '-[' + match2.toLowerCase() + match2.toUpperCase() + ']' : '');
				}
				return str;
			});
			re.push('(' + s + ')');
		}
		this.subpatterns[key] = subpatterns;
		this.regexps[key] = new RegExp(re.join('|'), 'g');
	},
	
	highlight_states: function (states, text, in_php, escape) {
		if (!this.regexps) {
			this.regexps = { };
			for (var key in this.tr) {
				this.build_regexp(key, this.tr[key]);
			}
		} else {
			for (var key in this.tr) {
				this.regexps[key].lastIndex = 0;
			}
		}
		var state = states[states.length - 1];
		if (!this.tr[state]) {
			return [ this.htmlspecialchars(text), states ];
		}
		var ret = [ ]; // return
		for (var i=1; i < states.length; i++) {
			ret.push('<span class="jush-' + states[i] + '">');
		}
		var match;
		var child_states = [ ];
		var s_states;
		var start = 0;
		while (start < text.length && (match = this.regexps[state].exec(text))) {
			if (states[0] != 'htm' && /^<\/(script|style)>$/i.test(match[0])) {
				continue;
			}
			var key, m = [ ];
			for (var i = match.length; i--; ) {
				if (match[i] || !match[0].length) { // WScript returns empty string even for non matched subexpressions
					key = this.subpatterns[state][i];
					while (this.subpatterns[state][i - 1] == key) {
						i--;
					}
					while (this.subpatterns[state][i] == key) {
						m.push(match[i]);
						i++;
					}
					break;
				}
			}
			if (!key) {
				return [ 'regexp not found', [ ] ];
			}
			
			if (in_php && key == 'php') {
				continue;
			}
			//~ console.log(states + ' (' + key + '): ' + text.substring(start).replace(/\n/g, '\\n'));
			var out = (key.charAt(0) == '_');
			var division = match.index + (key == 'php_halt2' ? match[0].length : 0);
			var s = text.substring(start, division);
			
			// highlight children
			var prev_state = states[states.length - 2];
			if (/^(att_quo|att_apo|att_val)$/.test(state) && (/^(att_js|att_css|att_http)$/.test(prev_state) || /^\s*javascript:/i.test(s))) { // javascript: - easy but without own state //! should be checked only in %URI;
				child_states.unshift(prev_state == 'att_css' ? 'css_pro' : (prev_state == 'att_http' ? 'http' : 'js'));
				s_states = this.highlight_states(child_states, this.html_entity_decode(s), true, (state == 'att_apo' ? this.htmlspecialchars_apo : (state == 'att_quo' ? this.htmlspecialchars_quo : this.htmlspecialchars_quo_apo)));
			} else if (state == 'css_js' || state == 'cnf_http' || state == 'cnf_phpini' || state == 'sql_sqlset' || state == 'sqlite_sqliteset' || state == 'pgsql_pgsqlset') {
				child_states.unshift(state.replace(/^[^_]+_/, ''));
				s_states = this.highlight_states(child_states, s, true);
			} else if ((state == 'php_quo' || state == 'php_apo') && /^(php_php|php_sql|php_sqlite|php_pgsql|php_mssql|php_oracle|php_phpini|php_http|php_mail)$/.test(prev_state)) {
				child_states.unshift(prev_state.substr(4));
				s_states = this.highlight_states(child_states, this.stripslashes(s), true, (state == 'php_apo' ? this.addslashes_apo : this.addslashes_quo));
			} else if (key == 'php_halt2') {
				child_states.unshift('htm');
				s_states = this.highlight_states(child_states, s, true);
			} else if ((state == 'apo' || state == 'quo') && prev_state == 'js_write_code') {
				child_states.unshift('htm');
				s_states = this.highlight_states(child_states, s, true);
			} else if ((state == 'apo' || state == 'quo') && prev_state == 'js_http_code') {
				child_states.unshift('http');
				s_states = this.highlight_states(child_states, s, true);
			} else if (((state == 'php_quo' || state == 'php_apo') && prev_state == 'php_echo') || (state == 'php_eot2' && states[states.length - 3] == 'php_echo')) {
				var i;
				for (i=states.length; i--; ) {
					prev_state = states[i];
					if (prev_state.substring(0, 3) != 'php' && prev_state != 'att_quo' && prev_state != 'att_apo' && prev_state != 'att_val') {
						break;
					}
					prev_state = '';
				}
				var f = (state == 'php_eot2' ? this.addslashes : (state == 'php_apo' ? this.addslashes_apo : this.addslashes_quo));
				s = this.stripslashes(s);
				if (/^(att_js|att_css|att_http)$/.test(prev_state)) {
					var g = (states[i+1] == 'att_quo' ? this.htmlspecialchars_quo : (states[i+1] == 'att_apo' ? this.htmlspecialchars_apo : this.htmlspecialchars_quo_apo));
					child_states.unshift(prev_state == 'att_js' ? 'js' : prev_state.substr(4));
					s_states = this.highlight_states(child_states, this.html_entity_decode(s), true, function (string) { return f(g(string)); });
				} else if (prev_state && child_states) {
					child_states.unshift(prev_state);
					s_states = this.highlight_states(child_states, s, true, f);
				} else {
					s = this.htmlspecialchars(s);
					s_states = [ (escape ? escape(s) : s), (!out || !/^(att_js|att_css|att_http|css_js|js_write_code|js_http_code|php_php|php_sql|php_sqlite|php_pgsql|php_mssql|php_oracle|php_echo|php_phpini|php_http|php_mail)$/.test(state) ? child_states : [ ]) ];
				}
			} else {
				s = this.htmlspecialchars(s);
				s_states = [ (escape ? escape(s) : s), (!out || !/^(att_js|att_css|att_http|css_js|js_write_code|js_http_code|php_php|php_sql|php_sqlite|php_pgsql|php_mssql|php_oracle|php_echo|php_phpini|php_http|php_mail)$/.test(state) ? child_states : [ ]) ]; // reset child states when leaving construct
			}
			s = s_states[0];
			child_states = s_states[1];
			s = this.keywords_links(state, s);
			ret.push(s);
			
			s = text.substring(division, match.index + match[0].length);
			s = (m.length < 3 ? (s ? '<span class="jush-op">' + this.htmlspecialchars(escape ? escape(s) : s) + '</span>' : '') : (m[1] ? '<span class="jush-op">' + this.htmlspecialchars(escape ? escape(m[1]) : m[1]) + '</span>' : '') + this.htmlspecialchars(escape ? escape(m[2]) : m[2]) + (m[3] ? '<span class="jush-op">' + this.htmlspecialchars(escape ? escape(m[3]) : m[3]) + '</span>' : ''));
			if (!out) {
				if (this.links && this.links[key] && m[2]) {
					if (/^tag/.test(key)) {
						this.last_tag = m[2].toUpperCase();
					}
					var link = (/^tag/.test(key) && !/^(ins|del)$/i.test(m[2]) ? m[2].toUpperCase() : m[2].toLowerCase());
					var k_link = '';
					var att_tag = (this.att_mapping[link + '-' + this.last_tag] ? this.att_mapping[link + '-' + this.last_tag] : this.last_tag);
					for (var k in this.links[key]) {
						if (key == 'att' && this.links[key][k].test(link + '-' + att_tag) && !/^http:/.test(k)) {
							link += '-' + att_tag;
							k_link = k;
							break;
						} else {
							var m2 = this.links[key][k].exec(m[2]);
							if (m2) {
								if (m2[1]) {
									link = (/^tag/.test(key) && !/^(ins|del)$/i.test(m2[1]) ? m2[1].toUpperCase() : m2[1].toLowerCase());
								}
								k_link = k;
								if (key != 'att') {
									break;
								}
							}
						}
					}
					if (key == 'php_met') {
						this.last_class = (k_link && !/^(self|parent|static|dir)$/i.test(link) ? link : '');
					}
					if (k_link) {
						s = (m[1] ? '<span class="jush-op">' + this.htmlspecialchars(escape ? escape(m[1]) : m[1]) + '</span>' : '');
						s += this.create_link((/^http:/.test(k_link) ? k_link : this.urls[key].replace(/\$key/, k_link)).replace(/\$val/, (/^http:/.test(k_link) ? link.toLowerCase() : link)), this.htmlspecialchars(escape ? escape(m[2]) : m[2])); //! use jush.api
						s += (m[3] ? '<span class="jush-op">' + this.htmlspecialchars(escape ? escape(m[3]) : m[3]) + '</span>' : '');
					}
				}
				ret.push('<span class="jush-' + key + '">', s);
				states.push(key);
				if (state == 'php_eot') {
					this.tr.php_eot2._2 = new RegExp('(\n)(' + match[1] + ')(;?\n)');
					this.build_regexp('php_eot2', (match[2] == "'" ? { _2: this.tr.php_eot2._2 } : this.tr.php_eot2));
				} else if (state == 'pgsql_eot') {
					this.tr.pgsql_eot2._2 = new RegExp('\\$' + text.substring(start, match.index) + '\\$');
					this.build_regexp('pgsql_eot2', this.tr.pgsql_eot2);
				}
			} else {
				if (state == 'php_met' && this.last_class) {
					s = this.create_link(this.urls[state].replace(/\$key/, this.last_class) + '.' + s.toLowerCase(), s);
				}
				ret.push(s);
				for (var i = Math.min(states.length, +key.substr(1)); i--; ) {
					ret.push('</span>');
					states.pop();
				}
			}
			start = match.index + match[0].length;
			if (!states.length) { // out of states
				break;
			}
			state = states[states.length - 1];
			this.regexps[state].lastIndex = start;
		}
		ret.push(this.keywords_links(state, this.htmlspecialchars(text.substring(start))));
		for (var i=1; i < states.length; i++) {
			ret.push('</span>');
		}
		states.shift();
		return [ ret.join(''), states ];
	},

	att_mapping: {
		'align-APPLET': 'IMG', 'align-IFRAME': 'IMG', 'align-INPUT': 'IMG', 'align-OBJECT': 'IMG',
		'align-COL': 'TD', 'align-COLGROUP': 'TD', 'align-TBODY': 'TD', 'align-TFOOT': 'TD', 'align-TH': 'TD', 'align-THEAD': 'TD', 'align-TR': 'TD',
		'border-OBJECT': 'IMG',
		'cite-BLOCKQUOTE': 'Q',
		'cite-DEL': 'INS',
		'color-BASEFONT': 'FONT',
		'face-BASEFONT': 'FONT',
		'height-INPUT': 'IMG',
		'height-TD': 'TH',
		'height-OBJECT': 'IMG',
		'label-MENU': 'OPTION',
		'longdesc-IFRAME': 'FRAME',
		'name-FIELDSET': 'FORM',
		'name-TEXTAREA': 'BUTTON',
		'name-IFRAME': 'FRAME',
		'name-OBJECT': 'INPUT',
		'src-IFRAME': 'FRAME',
		'type-AREA': 'A',
		'type-LINK': 'A',
		'width-INPUT': 'IMG',
		'width-OBJECT': 'IMG',
		'width-TD': 'TH'
	},

	/** Replace <&> by HTML entities
	* @param string
	* @return string
	*/
	htmlspecialchars: function (string) {
		return string.replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;');
	},
	
	htmlspecialchars_quo: function (string) {
		return jush.htmlspecialchars(string).replace(/"/g, '&quot;'); // jush - this.htmlspecialchars_quo is passed as reference
	},
	
	htmlspecialchars_apo: function (string) {
		return jush.htmlspecialchars(string).replace(/'/g, '&#39;');
	},
	
	htmlspecialchars_quo_apo: function (string) {
		return jush.htmlspecialchars_quo(string).replace(/'/g, '&#39;');
	},
	
	/** Decode HTML entities
	* @param string
	* @return string
	*/
	html_entity_decode: function (string) {
		return string.replace(/&lt;/g, '<').replace(/&gt;/g, '>').replace(/&quot;/g, '"').replace(/&nbsp;/g, '\u00A0').replace(/&#(?:([0-9]+)|x([0-9a-f]+));/gi, function (str, p1, p2) { //! named entities
			return String.fromCharCode(p1 ? p1 : parseInt(p2, 16));
		}).replace(/&amp;/g, '&');
	},
	
	/** Add backslash before backslash
	* @param string
	* @return string
	*/
	addslashes: function (string) {
		return string.replace(/\\/g, '\\$&');
	},
	
	addslashes_apo: function (string) {
		return string.replace(/[\\']/g, '\\$&');
	},
	
	addslashes_quo: function (string) {
		return string.replace(/[\\"]/g, '\\$&');
	},
	
	/** Remove backslash before \"'
	* @param string
	* @return string
	*/
	stripslashes: function (string) {
		return string.replace(/\\([\\"'])/g, '$1');
	}
};



jush.tr = { // transitions - key: go inside this state, _2: go outside 2 levels (number alone is put to the beginning in Chrome)
	// regular expressions matching empty string could be used only in the last key
	quo: { php: jush.php, esc: /\\/, _1: /"/ },
	apo: { php: jush.php, esc: /\\/, _1: /'/ },
	com: { php: jush.php, _1: /\*\// },
	com_nest: { com_nest: /\/\*/, _1: /\*\// },
	php: { _1: /\?>/ }, // overwritten by jush-php.js
	esc: { _1: /./ }, //! php_quo allows [0-7]{1,3} and x[0-9A-Fa-f]{1,2}
	one: { _1: /(?=\n)/ },
	num: { _1: /()/ },
	
	sql_apo: { esc: /\\/, _0: /''/, _1: /'/ },
	sql_quo: { esc: /\\/, _0: /""/, _1: /"/ },
	sql_var: { _1: /(?=[^_.$a-zA-Z0-9])/ },
	sqlite_apo: { _0: /''/, _1: /'/ },
	sqlite_quo: { _0: /""/, _1: /"/ },
	bac: { _1: /`/ },
	bra: { _1: /]/ }
};

// string: $key stands for key in jush.links, $val stands for found string
// array: [0] is base, other elements correspond to () in jush.links2, $key stands for text of selected element, $1 stands for found string
jush.urls = { };
jush.links = { };
jush.links2 = { }; // first and last () is used as delimiter
