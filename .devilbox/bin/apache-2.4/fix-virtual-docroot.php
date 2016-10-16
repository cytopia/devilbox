<?php
// Fix DocumentRoot for VirtualDocumentRoot Hosts
$_SERVER['DOCUMENT_ROOT'] = str_replace($_SERVER['SCRIPT_NAME'], '', $_SERVER['SCRIPT_FILENAME']);
