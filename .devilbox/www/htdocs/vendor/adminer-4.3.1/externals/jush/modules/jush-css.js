jush.tr.css = { php: jush.php, quo: /"/, apo: /'/, com: /\/\*/, css_at: /(@)([^;\s{]+)/, css_pro: /\{/, _2: /(<)(\/style)(>)/i };
jush.tr.css_at = { php: jush.php, quo: /"/, apo: /'/, com: /\/\*/, css_at2: /\{/, _1: /;/ };
jush.tr.css_at2 = { php: jush.php, quo: /"/, apo: /'/, com: /\/\*/, css_at: /@/, css_pro: /\{/, _2: /}/ };
jush.tr.css_pro = { php: jush.php, com: /\/\*/, css_val: /(\s*)([-\w]+)(\s*:)/, _1: /}/ }; //! misses e.g. margin/*-left*/:
jush.tr.css_val = { php: jush.php, quo: /"/, apo: /'/, css_js: /expression\s*\(/i, com: /\/\*/, clr: /#/, num: /[-+]?[0-9]*\.?[0-9]+(?:em|ex|px|in|cm|mm|pt|pc|%)?/, _2: /}/, _1: /;|$/ };
jush.tr.css_js = { php: jush.php, css_js: /\(/, _1: /\)/ };
jush.tr.clr = { _1: /(?=[^a-fA-F0-9])/ };

jush.urls.css_val = 'http://www.w3.org/TR/CSS21/$key.html#propdef-$val';
jush.urls.css_at = 'http://www.w3.org/TR/CSS21/$key';
jush.urls.css = ['http://www.w3.org/TR/css3-selectors/#$key',
	'link', 'useraction-pseudos', '$1-pseudo', 'enableddisabled', '$1', '$1', 'gen-content'
];

jush.links.css_val = {
	'aural': /^(azimuth|cue-after|cue-before|cue|elevation|pause-after|pause-before|pause|pitch-range|pitch|play-during|richness|speak-header|speak-numeral|speak-punctuation|speak|speech-rate|stress|voice-family|volume)$/i,
	'box': /^(border(?:-top|-right|-bottom|-left)?(?:-color|-style|-width)?|margin(?:-top|-right|-bottom|-left)?|padding(?:-top|-right|-bottom|-left)?)$/i,
	'colors': /^(background-attachment|background-color|background-image|background-position|background-repeat|background|color)$/i,
	'fonts': /^(font-family|font-size|font-style|font-variant|font-weight|font)$/i,
	'generate': /^(content|counter-increment|counter-reset|list-style-image|list-style-position|list-style-type|list-style|quotes)$/i,
	'page': /^(orphans|page-break-after|page-break-before|page-break-inside|widows)$/i,
	'tables': /^(border-collapse|border-spacing|caption-side|empty-cells|table-layout)$/i,
	'text': /^(letter-spacing|text-align|text-decoration|text-indent|text-transform|white-space|word-spacing)$/i,
	'ui': /^(cursor|outline-color|outline-style|outline-width|outline)$/i,
	'visudet': /^(height|line-height|max-height|max-width|min-height|min-width|vertical-align|width)$/i,
	'visufx': /^(clip|overflow|visibility)$/i,
	'visuren': /^(bottom|clear|direction|display|float|left|position|right|top|unicode-bidi|z-index)$/i,
	'http://www.w3.org/TR/css3-cascade/#$val': /^(?:-[a-z]+-)?(all)$/i,
	'http://www.w3.org/TR/css3-writing-modes/#$val': /^(?:-[a-z]+-)?(text-combine-horizontal|text-orientation|writing-mode)$/i,
	'http://www.w3.org/TR/css3-break/#$val': /^(?:-[a-z]+-)?(break-after|break-before|break-inside)$/i,
	'http://www.w3.org/TR/css3-images/#$val': /^(?:-[a-z]+-)?(image-orientation|image-resolution|object-fit|object-position)$/i,
	'http://www.w3.org/TR/css3-marquee/#$val': /^(?:-[a-z]+-)?(marquee-direction|marquee-play-count|marquee-speed|marquee-style|overflow-style)$/i,
	'http://www.w3.org/TR/css3-grid/#$val': /^(?:-[a-z]+-)?(grid-columns|grid-rows|align-content|align-items|align-self|justify-content|justify-items|justify-self)$/i,
	'http://www.w3.org/TR/css-fonts-3/#$val-prop': /^(?:-[a-z]+-)?(font-feature-settings|font-kerning|font-language-override|font-size-adjust|font-stretch|font-synthesis|font-variant-alternates|font-variant-caps|font-variant-east-asian|font-variant-ligatures|font-variant-numeric|font-variant-position)$/i,
	'http://www.w3.org/TR/css-overflow-3/#$val': /^(?:-[a-z]+-)?(max-lines|overflow-x|overflow-y)$/i,
	'http://www.w3.org/TR/css3-ui/#$val': /^(?:-[a-z]+-)?(box-sizing|icon|ime-mode|nav-index|nav-up|nav-right|nav-down|nav-left|outline-offset|resize|text-overflow)$/i,
	'http://www.w3.org/TR/css3-background/#$val': /^(?:-[a-z]+-)?(background-clip|background-origin|background-size|border-image|border-image-outset|border-image-repeat|border-image-slice|border-image-source|border-image-width|border-radius|border-top-left-radius|border-top-right-radius|border-bottom-right-radius|border-bottom-left-radius|box-decoration-break|box-shadow)$/i
};
jush.links.css_at = {
	'page.html#page-box': /^page$/i,
	'media.html#at-media-rule': /^media$/i,
	'cascade.html#at-import': /^import$/i,
	'syndata.html#charset': /^charset$/i,
	'http://www.w3.org/TR/css3-conditional/#at-$val': /^supports$/i,
	'http://www.w3.org/TR/css-fonts-3/#at-$val-rule': /^(font-face|font-feature-values)$/i,
	'http://www.w3.org/TR/css-counter-styles-3/#the-$val-rule': /^counter-style$/i,
	'http://www.w3.org/TR/css3-namespace/#declaration': /namespace/
};

jush.links2.css = /(:)(link|visited|(hover|active|focus)|(target|lang|root|nth-child|nth-last-child|nth-of-type|nth-last-of-type|first-child|last-child|first-of-type|last-of-type|only-child|only-of-type|empty)|(enabled|disabled)|(checked|indeterminate|not)|(first-line|first-letter)|(before|after))(\b)/gi;
