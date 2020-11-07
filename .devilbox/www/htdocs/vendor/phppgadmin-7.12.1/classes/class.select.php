<?php

/**
*  XHtmlSimpleElement 
* 
*  Used to generate Xhtml-Code for simple xhtml elements 
*  (i.e. elements, that can't contain child elements)
* 
* 
*  @author	Felix Meinhold
* 
*/
class XHtmlSimpleElement {
	var $_element;
	var $_siblings = array();
	var $_htmlcode;	
	var $_attributes = array();

	
	/**
	* Constructor
	* 
	* @param	string	The element's name. Defaults to name of the 
	* derived class
	* 
	*/
	function __construct($element = null) {

		$this->_element = $this->is_element();
		
	}

	function set_style($style) {
		$this->set_attribute('style', $style);
	}
	
	function set_class($class) {
		$this->set_attribute('class', $class);
	}

	
	function is_element() {
		return 
			str_replace('xhtml_', '', strtolower(get_class($this)));
	}

	/**
	* Private function generates xhtml
	* @access private	
	*/
	function _html() {
		$this->_htmlcode = "<";
		foreach ($this->_attributeCollection as $attribute => $value) {
			if (!empty($value)) $this->_htmlcode .= " {$attribute}=\"{$value}\"";
		}
		$this->_htmlcode .= "/>";
		
		return $this->_htmlcode;
	}
	
   /**
    * Returns xhtml code
    *  
    */
	function fetch() {
		return $this->_html();
	}
	/**
	* Echoes xhtml
	* 
	*/	
	function show()  {
		echo $this->fetch();
	}

	function set_attribute($attr, $value) {
		$this->_attributes[$attr] = $value;
	}


}

/**
*  XHtmlElement 
* 
*  Used to generate Xhtml-Code for xhtml elements 
*  that can contain child elements
* 
* 
*/
class XHtmlElement extends XHtmlSimpleElement {
	var $_text     = null;	
	var $_htmlcode = "";
	var $_siblings = array();

	function __construct($text = null) {
		parent::__construct();
		
		if ($text) $this->set_text($text);
	}

   /*
	* Adds an xhtml child to element
	* 
	* @param	XHtmlElement 	The element to become a child of element
	*/
	function add(&$object) {
		array_push($this->_siblings, $object);
	}


 	/*
	* The CDATA section of Element
	* 
	* @param	string	Text
	*/
	function set_text($text) {
		if ($text) $this->_text = htmlspecialchars($text);	
	}

	function fetch() {
		return $this->_html();
	}


	function _html() {

		$this->_htmlcode = "<{$this->_element}";
		foreach ($this->_attributes as $attribute =>$value) {
			if (!empty($value))	$this->_htmlcode .= " {$attribute} =\"{$value}\"";
		}
		$this->_htmlcode .= ">";

		
		if ($this->_text) { 
			$this->_htmlcode .= $this->_text;
		}
	
		foreach ($this->_siblings as $obj) {
			$this->_htmlcode .= $obj->fetch();
		}		

		$this->_htmlcode .= "</{$this->_element}>";
		
		return $this->_htmlcode;
	}

	/*
	* Returns siblings of Element
	* 
	*/
	function get_siblings() {
		return $this->_siblings;
	}
	
	function has_siblings() {
		return (count($this->_siblings) != 0);
	}
}

class XHTML_Button extends XHtmlElement {
	function __construct($name, $text = null) {
		parent::__construct();
		
		$this->set_attribute("name", $name);
		
		if ($text) $this->set_text($text);
	}
}


class XHTML_Option extends XHtmlElement {
	function __construct($text, $value = null) {
		parent::__construct(null);			
		$this->set_text($text);
	}
}


class XHTML_Select extends XHTMLElement {
	var $_data;

	function __construct($name, $multiple = false, $size = null) {
		parent::__construct();					

		$this->set_attribute("name", $name);
		if ($multiple) $this->set_attribute("multiple","multiple");
		if ($size) $this->set_attribute("size",$size);
		
		
	}
	
	function set_data(&$data, $delim = ",") {
		switch (gettype($data)) {
			case "string":
				$this->_data = explode($delim, $data);
				break;
			case "array":
				$this->_data = $data;
				break;
				
			default:
				break;
		}
	}
	
	function fetch() {
		if (isset($this->_data) && $this->_data) {
			foreach ($this->_data as $value) { $this->add(new XHTML_Option($value)); }
		}
		return parent::fetch();
	}
	
}


?>
