var g_args = 0;
var g_no_args = new Boolean(false);
/*
function for adding arguments
*/

function addArg() {
	var baseTR = baseArgTR();
	if(document.getElementById("args_table").insertBefore(baseTR,document.getElementById("parent_add_tr"))) {
		g_args++;
		return baseTR;
	}
}

function buildArgImages(orig_td) {
	var table = document.createElement("table");
	var tbody = document.createElement("tbody");
	var tr = document.createElement("tr");
	var td = document.createElement("td");
	var img = document.createElement("img");
	img.src="images/themes/default/RaiseArgument.png";
	td.onmouseover=function() { this.style.cursor='pointer';this.title=g_lang_strargraise;  }
	td.onclick=function() { swapArgTR(this.parentNode.parentNode.parentNode.parentNode.parentNode.previousSibling,this.parentNode.parentNode.parentNode.parentNode.parentNode);  }
	img.className='arg_icon';
	td.appendChild(img);
	td.className="data1";
	tr.appendChild(td);
	var img = document.createElement("img");
	var td = document.createElement("td");
	img.src="images/themes/default/LowerArgument.png";
	img.className='arg_icon';
	td.appendChild(img);
	td.className="data1";
	td.onmouseover=function() { this.style.cursor='pointer';this.title=g_lang_strarglower;  }
	td.onclick=function() { swapArgTR(this.parentNode.parentNode.parentNode.parentNode.parentNode,this.parentNode.parentNode.parentNode.parentNode.parentNode.nextSibling);  }
	tr.appendChild(td);
	var img = document.createElement("img");
	var td = document.createElement("td");
	img.src="images/themes/default/RemoveArgument.png";
	img.title=g_lang_strargremove;
	img.className='arg_icon';
	td.appendChild(img);
	td.className="data1";
	td.onmouseover=function() { this.style.cursor='pointer';this.title='Remove';  }
	td.onclick=function() { if(g_args>1) { if(confirm(g_lang_strargremoveconfirm)) document.getElementById("args_table").removeChild(this.parentNode.parentNode.parentNode.parentNode.parentNode);g_args--; } else { 
		if(g_no_args==false) {
			disableArgTR(this.parentNode.parentNode.parentNode.parentNode.parentNode);
			this.childNodes[0].src='images/themes/default/EnableArgument.png';
			this.childNodes[0].title=g_lang_strargenableargs;
			this.childNodes[0].id="1st_arg_iag";
			alert(g_lang_strargnoargs);
			g_no_args = true;
			g_args--;
		} else {
			enableArgTR(this.parentNode.parentNode.parentNode.parentNode.parentNode);
			this.childNodes[0].src='images/themes/default/RemoveArgument.png';
			this.childNodes[0].title=g_lang_strargremove;
			g_args++;
			g_no_args = false;
		}
	}
	}
	td.onmouseout=function() { }
	if(g_args==0) {
		td.id="1st_arg_td";
	}
	tr.className='arg_tr_pc';
	tr.appendChild(td);
	tbody.appendChild(tr);
	table.appendChild(tbody);
	orig_td.appendChild(table);
	return orig_td;
}

function noArgsRebuild(tr) {
	disableArgTR(tr);
	var td = document.getElementById("1st_arg_td");
	td.childNodes[0].src='images/themes/default/EnableArgument.png';
	td.childNodes[0].title=g_lang_strargenableargs;
	td.childNodes[0].id="1st_arg_iag";
	g_no_args = true;
	g_args--;
}

function swapArgTR(first,second) {
	var tmp = null;
	tmp = second;
	second = first;
	first = tmp;
	if(first.className=='arg_tr_pc' && second.className=='arg_tr_pc') {
		document.getElementById("args_table").insertBefore(first,second);
	} else if(first.className=='arg_tr_pc' && second.className!='arg_tr_pc') {
		alert(g_lang_strargnorowabove);
	} else if(first.className!='arg_tr_pc' && second.className=='arg_tr_pc') {
		alert(g_lang_strargnorowbelow);
	}
}

function disableArgTR(tr) {
	var children = (tr.childNodes);
	for(i in children) {
		var secondary_children = children[i].childNodes;
		for(i2 in secondary_children) {
			secondary_children[i2].disabled=true;
		}
	}
}

function enableArgTR(tr) {
	var children = (tr.childNodes);
	for(i in children) {
		var secondary_children = children[i].childNodes;
		for(i2 in secondary_children) {
			secondary_children[i2].disabled=false;
		}
	}
}

function RebuildArgTR(mode,arg_name,arg_type,arg_array) {
	var tr = document.createElement("tr");
	var td = document.createElement("td");
	var modes_select = buildSelect("formArgModes[]",g_main_modes,mode);
	modes_select.style.width='100%';
	td.appendChild(modes_select);
	tr.appendChild(td);
	var arg_txt = document.createElement("input");
	arg_txt.type='text';
	arg_txt.name='formArgName[]';
	arg_txt.style.width='100%';
	arg_txt.value=arg_name;
	var td = document.createElement("td");
	td.appendChild(arg_txt);
	tr.appendChild(td);
	var td = document.createElement("td");
	td.appendChild(buildSelect("formArgType[]",g_main_types,arg_type));
	if(arg_array==true) {
		var szArr = "[]";
	} else {
		var szArr = "";
	}
	td.appendChild(buildSelect("formArgArray[]",new Array("","[]"),szArr));
	tr.appendChild(td);
	var td = document.createElement("td");
	td = buildArgImages(td);
	td.className="data3";
	tr.className='arg_tr_pc';
	tr.appendChild(td);
	if(document.getElementById("args_table").insertBefore(tr,document.getElementById("parent_add_tr"))) {
		g_args++;
	}
}

function buildSelect(name,options,selected) {
	var s = document.createElement('select');
	for(i=0;i<options.length;i++) {
		var o = document.createElement('option');
		o.value=options[i];
		o.text=options[i];
		if(options[i].toLowerCase()==selected.toLowerCase()) {
			o.selected="selected";
		}
		s.appendChild(o);
	}
	s.name=name;
	return s;
}

function baseArgTR() {
	if(g_no_args==false) {
		var tr = document.createElement("tr");
		var td = document.createElement("td");
		var txt = document.createElement("input");
		tr.className='arg_tr_pc';
		td.className='data3';
		td.innerHTML = g_modes_select;
		tr.appendChild(td);
		txt.type='text';
		txt.name='formArgName[]';
		txt.style.width='100%';
		txt.value=g_name;
		var td = document.createElement("td");
		td.className='data3';
		td.appendChild(txt);
		tr.appendChild(td);
		var td = document.createElement("td");
		td.className='data3';
		td.innerHTML = g_types_select;
		tr.appendChild(td);
		var td = document.createElement("td");
		td = buildArgImages(td);
		td.className="data3";
		tr.appendChild(td);
		if(g_args==0) {
			tr.id="1st_arg_tr";
		}
		return tr;
	} else {
		var p_tr = document.getElementById("1st_arg_tr");
		enableArgTR(p_tr.childNodes[4]);
		document.getElementById("1st_arg_iag").src='images/themes/default/RemoveArgument.png';
		g_args++;
		g_no_args = false;
	}
}
