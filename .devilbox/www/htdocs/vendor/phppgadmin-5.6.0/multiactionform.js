function checkAll(bool) {

	var inputs = document.getElementById('multi_form').getElementsByTagName('input');

	for (var i=0; i<inputs.length; i++) {
		if (inputs[i].type == 'checkbox')
			inputs[i].checked = bool;
	}
}
