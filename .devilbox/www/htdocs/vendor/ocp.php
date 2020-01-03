<?php
/*
OCP - Opcache Control Panel   (aka Zend Optimizer+ Control Panel for PHP)
Author: _ck_   (with contributions by GK, stasilok)
Version: 0.1.7
Free for any kind of use or modification, I am not responsible for anything, please share your improvements

* revision history
0.1.7  2015-09-01  regex fix for PHP7 phpinfo
0.1.6  2013-04-12  moved meta to footer so graphs can be higher and reduce clutter
0.1.5  2013-04-12  added graphs to visualize cache state, please report any browser/style bugs
0.1.4  2013-04-09  added "recheck" to update files when using large revalidate_freq (or validate_timestamps=Off)
0.1.3  2013-03-30  show host and php version, can bookmark with hashtag ie. #statistics - needs new layout asap
0.1.2  2013-03-25  show optimization levels, number formatting, support for start_time in 7.0.2
0.1.1  2013-03-18  today Zend completely renamed Optimizer+ to OPcache, adjusted OCP to keep working
0.1.0  2013-03-17  added group/sort indicators, replaced "accelerator_" functions with "opcache_"
0.0.6  2013-03-16  transition support as Zend renames product and functions for PHP 5.5 (stasilok)
0.0.5  2013-03-10  added refresh button (GK)
0.0.4  2013-02-18  added file grouping and sorting (click on headers) - code needs cleanup but gets the job done
0.0.2  2013-02-14  first public release

* known problems/limitations:
Unlike APC, the Zend OPcache API
 - cannot determine when a file was put into the cache
 - cannot change settings on the fly
 - cannot protect opcache functions by restricting execution to only specific scripts/paths

* todo:
Extract variables for prefered ordering and better layout instead of just dumping into tables
File list filter

*/

// ini_set('display_errors',1); error_reporting(-1);
if ( count(get_included_files())>1 || php_sapi_name()=='cli' || empty($_SERVER['REMOTE_ADDR']) ) { die; }  // weak block against indirect access

$time=time();
define('CACHEPREFIX',function_exists('opcache_reset')?'opcache_':(function_exists('accelerator_reset')?'accelerator_':''));

if ( !empty($_GET['RESET']) ) {	
	if ( function_exists(CACHEPREFIX.'reset') ) { call_user_func(CACHEPREFIX.'reset'); }
	header( 'Location: '.str_replace('?'.$_SERVER['QUERY_STRING'],'',$_SERVER['REQUEST_URI']) ); 
	exit;
}

if ( !empty($_GET['RECHECK']) ) {
	if ( function_exists(CACHEPREFIX.'invalidate') ) { 
		$recheck=trim($_GET['RECHECK']); $files=call_user_func(CACHEPREFIX.'get_status');
		if (!empty($files['scripts'])) { 
			foreach ($files['scripts'] as $file=>$value) { 
				if ( $recheck==='1' || strpos($file,$recheck)===0 )  call_user_func(CACHEPREFIX.'invalidate',$file); 
			} 
		}
		header( 'Location: '.str_replace('?'.$_SERVER['QUERY_STRING'],'',$_SERVER['REQUEST_URI']) ); 
	} else { echo 'Sorry, this feature requires Zend Opcache newer than April 8th 2013'; }
	exit;
}

?><!DOCTYPE html>
<html>
<head>
	<title>OCP - Opcache Control Panel</title>
	<meta name="ROBOTS" content="NOINDEX,NOFOLLOW,NOARCHIVE" />

<style type="text/css">
	body {background-color: #fff; color: #000;}
	body, td, th, h1, h2 {font-family: sans-serif;}
	pre {margin: 0px; font-family: monospace;}
	a:link,a:visited {color: #000099; text-decoration: none;}
	a:hover {text-decoration: underline;}
	table {border-collapse: collapse; width: 600px; }
	.center {text-align: center;}
	.center table { margin-left: auto; margin-right: auto; text-align: left;}
	.center th { text-align: center !important; }
	.middle {vertical-align:middle;}
	td, th { border: 1px solid #000; font-size: 75%; vertical-align: baseline; padding: 3px; } 
	h1 {font-size: 150%;}
	h2 {font-size: 125%;}
	.p {text-align: left;}
	.e {background-color: #ccccff; font-weight: bold; color: #000; width:50%; white-space:nowrap;}
	.h {background-color: #9999cc; font-weight: bold; color: #000;}
	.v {background-color: #cccccc; color: #000;}
	.vr {background-color: #cccccc; text-align: right; color: #000; white-space: nowrap;}
	.b {font-weight:bold;}
	.white, .white a {color:#fff;} 	
	img {float: right; border: 0px;}
	hr {width: 600px; background-color: #cccccc; border: 0px; height: 1px; color: #000;}
	.meta, .small {font-size: 75%; }
	.meta {margin: 2em 0;}
	.meta a, th a {padding: 10px; white-space:nowrap; }
	.buttons {margin:0 0 1em;}
	.buttons a {margin:0 15px; background-color: #9999cc; color:#fff; text-decoration:none; padding:1px; border:1px solid #000; display:inline-block; width:5em; text-align:center;}
	#files td.v a {font-weight:bold; color:#9999cc; margin:0 10px 0 5px; text-decoration:none; font-size:120%;}
	#files td.v a:hover {font-weight:bold; color:#ee0000;}
	.graph {display:inline-block; width:145px; margin:1em 0 1em 1px; border:0; vertical-align:top;}
	.graph table {width:100%; height:150px; border:0; padding:0; margin:5px 0 0 0; position:relative;}
	.graph td {vertical-align:middle; border:0; padding:0 0 0 5px;}
	.graph .bar {width:25px; text-align:right; padding:0 2px; color:#fff;}
	.graph .total {width:34px; text-align:center; padding:0 5px 0 0;}
	.graph .total div {border:1px dashed #888; border-right:0; height:99%; width:12px; position:absolute; bottom:0; left:17px; z-index:-1;}
	.graph .total span {background:#fff; font-weight:bold;}
	.graph .actual {text-align:right; font-weight:bold; padding:0 5px 0 0;} 
	.graph .red {background:#ee0000;}
	.graph .green {background:#00cc00;}
	.graph .brown {background:#8B4513;}
</style>
<!--[if lt IE 9]><script type="text/javascript" defer="defer">
window.onload=function(){var i,t=document.getElementsByTagName('table');for(i=0;i<t.length;i++){if(t[i].parentNode.className=='graph')t[i].style.height=150-(t[i].clientHeight-150)+'px';}}
</script><![endif]--> 
</head>

<body>
<div class="center">

<h1><a href="?">Opcache Control Panel</a></h1>

<div class="buttons">
	<a href="?ALL=1">Details</a>
	<a href="?FILES=1&GROUP=2&SORT=3">Files</a>
	<a href="?RESET=1" onclick="return confirm('RESET cache ?')">Reset</a>
	<?php if ( function_exists(CACHEPREFIX.'invalidate') ) { ?>
	<a href="?RECHECK=1" onclick="return confirm('Recheck all files in the cache ?')">Recheck</a>
	<?php } ?>
	<a href="?" onclick="window.location.reload(true); return false">Refresh</a>
</div>

<?php 

if ( !function_exists(CACHEPREFIX.'get_status') ) { echo '<h2>Opcache not detected?</h2>'; die; }

if ( !empty($_GET['FILES']) ) { echo '<h2>files cached</h2>'; files_display(); echo '</div></body></html>'; exit; }

if ( !(isset($_REQUEST['GRAPHS']) && !$_REQUEST['GRAPHS']) && CACHEPREFIX=='opcache_') { graphs_display(); if ( !empty($_REQUEST['GRAPHS']) ) { exit; } }

ob_start(); phpinfo(8); $phpinfo = ob_get_contents(); ob_end_clean(); 		 // some info is only available via phpinfo? sadly buffering capture has to be used
if ( !preg_match( '/module\_Zend.(Optimizer\+|OPcache).+?(\<table[^>]*\>.+?\<\/table\>).+?(\<table[^>]*\>.+?\<\/table\>)/is', $phpinfo, $opcache) ) { }  // todo

if ( function_exists(CACHEPREFIX.'get_configuration') ) { echo '<h2>general</h2>'; $configuration=call_user_func(CACHEPREFIX.'get_configuration'); }

$host=function_exists('gethostname')?@gethostname():@php_uname('n'); if (empty($host)) { $host=empty($_SERVER['SERVER_NAME'])?$_SERVER['HOST_NAME']:$_SERVER['SERVER_NAME']; }
$version=array('Host'=>$host);
$version['PHP Version']='PHP '.(defined('PHP_VERSION')?PHP_VERSION:'???').' '.(defined('PHP_SAPI')?PHP_SAPI:'').' '.(defined('PHP_OS')?' '.PHP_OS:'');
$version['Opcache Version']=empty($configuration['version']['version'])?'???':$configuration['version'][CACHEPREFIX.'product_name'].' '.$configuration['version']['version']; 
print_table($version);

if ( !empty($opcache[2]) ) { echo preg_replace('/\<tr\>\<td class\="e"\>[^>]+\<\/td\>\<td class\="v"\>[0-9\,\. ]+\<\/td\>\<\/tr\>/','',$opcache[2]); }

if ( function_exists(CACHEPREFIX.'get_status') && $status=call_user_func(CACHEPREFIX.'get_status') ) {
	$uptime=array();
	if ( !empty($status[CACHEPREFIX.'statistics']['start_time']) ) { 
		$uptime['uptime']=time_since($time,$status[CACHEPREFIX.'statistics']['start_time'],1,'');
	}
	if ( !empty($status[CACHEPREFIX.'statistics']['last_restart_time']) ) { 
		$uptime['last_restart']=time_since($time,$status[CACHEPREFIX.'statistics']['last_restart_time']); 		
	}
	if (!empty($uptime)) {print_table($uptime);}
	
	if ( !empty($status['cache_full']) ) { $status['memory_usage']['cache_full']=$status['cache_full']; }
	
	echo '<h2 id="memory">memory</h2>';
	print_table($status['memory_usage']);
	unset($status[CACHEPREFIX.'statistics']['start_time'],$status[CACHEPREFIX.'statistics']['last_restart_time']);
	echo '<h2 id="statistics">statistics</h2>';
	print_table($status[CACHEPREFIX.'statistics']);
}

if ( empty($_GET['ALL']) ) { meta_display(); exit; }
  
if ( !empty($configuration['blacklist']) ) { echo '<h2 id="blacklist">blacklist</h2>'; print_table($configuration['blacklist']); }

if ( !empty($opcache[3]) ) { echo '<h2 id="runtime">runtime</h2>'; echo $opcache[3]; }

$name='zend opcache'; $functions=get_extension_funcs($name); 
if (!$functions) { $name='zend optimizer+'; $functions=get_extension_funcs($name); }
if ($functions) { echo '<h2 id="functions">functions</h2>'; print_table($functions);  } else { $name=''; }

$level=trim(CACHEPREFIX,'_').'.optimization_level';
if (isset($configuration['directives'][$level])) {
	echo '<h2 id="optimization">optimization levels</h2>';		
	$levelset=strrev(base_convert($configuration['directives'][$level], 10, 2));
	$levels=array(
    	1=>'<a href="http://wikipedia.org/wiki/Common_subexpression_elimination">Constants subexpressions elimination</a> (CSE) true, false, null, etc.<br />Optimize series of ADD_STRING / ADD_CHAR<br />Convert CAST(IS_BOOL,x) into BOOL(x)<br />Convert <a href="http://www.php.net/manual/internals2.opcodes.init-fcall-by-name.php">INIT_FCALL_BY_NAME</a> + <a href="http://www.php.net/manual/internals2.opcodes.do-fcall-by-name.php">DO_FCALL_BY_NAME</a> into <a href="http://www.php.net/manual/internals2.opcodes.do-fcall.php">DO_FCALL</a>',
    	2=>'Convert constant operands to expected types<br />Convert conditional <a href="http://php.net/manual/internals2.opcodes.jmp.php">JMP</a>  with constant operands<br />Optimize static <a href="http://php.net/manual/internals2.opcodes.brk.php">BRK</a> and <a href="<a href="http://php.net/manual/internals2.opcodes.cont.php">CONT</a>',
    	3=>'Convert $a = $a + expr into $a += expr<br />Convert $a++ into ++$a<br />Optimize series of <a href="http://php.net/manual/internals2.opcodes.jmp.php">JMP</a>',
    	4=>'PRINT and ECHO optimization (<a href="https://github.com/zend-dev/ZendOptimizerPlus/issues/73">defunct</a>)',
	5=>'Block Optimization - most expensive pass<br />Performs many different optimization patterns based on <a href="http://wikipedia.org/wiki/Control_flow_graph">control flow graph</a> (CFG)',
	9=>'Optimize <a href="http://wikipedia.org/wiki/Register_allocation">register allocation</a> (allows re-usage of temporary variables)',
	10=>'Remove NOPs'
	);
	echo '<table width="600" border="0" cellpadding="3"><tbody><tr class="h"><th>Pass</th><th>Description</th></tr>';
	foreach ($levels as $pass=>$description) {
		$disabled=substr($levelset,$pass-1,1)!=='1' || $pass==4 ? ' white':'';
		echo '<tr><td class="v center middle'.$disabled.'">'.$pass.'</td><td class="v'.$disabled.'">'.$description.'</td></tr>';
	}
	echo '</table>';
}

if ( isset($_GET['DUMP']) ) { 
	if ($name) { echo '<h2 id="ini">ini</h2>'; print_table(ini_get_all($name,true)); } 
	foreach ($configuration as $key=>$value) { echo '<h2>',$key,'</h2>'; print_table($configuration[$key]); } 
	exit;
}

meta_display();

echo '</div></body></html>';

exit;

function time_since($time,$original,$extended=0,$text='ago') {	
	$time =  $time - $original; 
	$day = $extended? floor($time/86400) : round($time/86400,0); 
	$amount=0; $unit='';
	if ( $time < 86400) {
		if ( $time < 60)		{ $amount=$time; $unit='second'; }
		elseif ( $time < 3600) { $amount=floor($time/60); $unit='minute'; }
		else				{ $amount=floor($time/3600); $unit='hour'; }			
	} 
	elseif ( $day < 14) 	{ $amount=$day; $unit='day'; }
	elseif ( $day < 56) 	{ $amount=floor($day/7); $unit='week'; }
	elseif ( $day < 672) { $amount=floor($day/30); $unit='month'; }
	else {			  $amount=intval(2*($day/365))/2; $unit='year'; }
	
	if ( $amount!=1) {$unit.='s';}	
	if ($extended && $time>60) { $text=' and '.time_since($time,$time<86400?($time<3600?$amount*60:$amount*3600):$day*86400,0,'').$text; }
	
	return $amount.' '.$unit.' '.$text;
}

function print_table($array,$headers=false) {
	if ( empty($array) || !is_array($array) ) {return;} 
  	echo '<table border="0" cellpadding="3" width="600">';
  	if (!empty($headers)) {
  		if (!is_array($headers)) {$headers=array_keys(reset($array));}
  		echo '<tr class="h">';
  		foreach ($headers as $value) { echo '<th>',$value,'</th>'; }
  		echo '</tr>';  			
  	}
  	foreach ($array as $key=>$value) {
    		echo '<tr>';
    		if ( !is_numeric($key) ) { 
      			$key=ucwords(str_replace('_',' ',$key));
      			echo '<td class="e">',$key,'</td>'; 
      			if ( is_numeric($value) ) {
        				if ( $value>1048576) { $value=round($value/1048576,1).'M'; }
        				elseif ( is_float($value) ) { $value=round($value,1); }
      			}
    		}
    		if ( is_array($value) ) {
      			foreach ($value as $column) {
         			echo '<td class="v">',$column,'</td>';
      			}
      			echo '</tr>';
    		}
    		else { echo '<td class="v">',$value,'</td></tr>'; } 
	}
 	echo '</table>';
}

function files_display() {			
	$status=call_user_func(CACHEPREFIX.'get_status');
	if ( empty($status['scripts']) ) {return;}
	if ( isset($_GET['DUMP']) ) { print_table($status['scripts']); exit;}
    	$time=time(); $sort=0; 
	$nogroup=preg_replace('/\&?GROUP\=[\-0-9]+/','',$_SERVER['REQUEST_URI']);
	$nosort=preg_replace('/\&?SORT\=[\-0-9]+/','',$_SERVER['REQUEST_URI']);
	$group=empty($_GET['GROUP'])?0:intval($_GET['GROUP']); if ( $group<0 || $group>9) { $group=1;}
	$groupset=array_fill(0,9,''); $groupset[$group]=' class="b" ';
	
	echo '<div class="meta">	
		<a ',$groupset[0],'href="',$nogroup,'">ungroup</a> | 
		<a ',$groupset[1],'href="',$nogroup,'&GROUP=1">1</a> | 
		<a ',$groupset[2],'href="',$nogroup,'&GROUP=2">2</a> | 
		<a ',$groupset[3],'href="',$nogroup,'&GROUP=3">3</a> | 
		<a ',$groupset[4],'href="',$nogroup,'&GROUP=4">4</a> | 
		<a ',$groupset[5],'href="',$nogroup,'&GROUP=5">5</a> 
	</div>';
		
	if ( !$group ) { $files =& $status['scripts']; }
	else {		
		$files=array(); 
		foreach ($status['scripts'] as $data) { 
			if ( preg_match('@^[/]([^/]+[/]){'.$group.'}@',$data['full_path'],$path) ) { 
				if ( empty($files[$path[0]])) { $files[$path[0]]=array('full_path'=>'','files'=>0,'hits'=>0,'memory_consumption'=>0,'last_used_timestamp'=>'','timestamp'=>''); }
				$files[$path[0]]['full_path']=$path[0];
				$files[$path[0]]['files']++;
				$files[$path[0]]['memory_consumption']+=$data['memory_consumption'];						
				$files[$path[0]]['hits']+=$data['hits'];
				if ( $data['last_used_timestamp']>$files[$path[0]]['last_used_timestamp']) {$files[$path[0]]['last_used_timestamp']=$data['last_used_timestamp'];}
				if ( $data['timestamp']>$files[$path[0]]['timestamp']) {$files[$path[0]]['timestamp']=$data['timestamp'];}							
			}					
		}
	}
		
	if ( !empty($_GET['SORT']) ) {
		$keys=array(
			'full_path'=>SORT_STRING,
			'files'=>SORT_NUMERIC,
			'memory_consumption'=>SORT_NUMERIC,
			'hits'=>SORT_NUMERIC,
			'last_used_timestamp'=>SORT_NUMERIC,
			'timestamp'=>SORT_NUMERIC
		);
		$titles=array('','path',$group?'files':'','size','hits','last used','created');
		$offsets=array_keys($keys);
		$key=intval($_GET['SORT']);
		$direction=$key>0?1:-1;
		$key=abs($key)-1;
		$key=isset($offsets[$key])&&!($key==1&&empty($group))?$offsets[$key]:reset($offsets);
		$sort=array_search($key,$offsets)+1;
		$sortflip=range(0,7); $sortflip[$sort]=-$direction*$sort;
		if ( $keys[$key]==SORT_STRING) {$direction=-$direction; }
		$arrow=array_fill(0,7,''); $arrow[$sort]=$direction>0?' &#x25BC;':' &#x25B2;';
		$direction=$direction>0?SORT_DESC:SORT_ASC;
		$column=array(); foreach ($files as $data) { $column[]=$data[$key]; }
		array_multisort($column, $keys[$key], $direction, $files);
	}

	echo '<table border="0" cellpadding="3" width="960" id="files">
         		<tr class="h">';
         foreach ($titles as $column=>$title) {
         	if ($title) echo '<th><a href="',$nosort,'&SORT=',$sortflip[$column],'">',$title,$arrow[$column],'</a></th>';
         }
         echo '	</tr>';
    	foreach ($files as $data) {
    		echo '<tr>
    				<td class="v" nowrap><a title="recheck" href="?RECHECK=',rawurlencode($data['full_path']),'">x</a>',$data['full_path'],'</td>',
      				($group?'<td class="vr">'.number_format($data['files']).'</td>':''),
         			'<td class="vr">',number_format(round($data['memory_consumption']/1024)),'K</td>',
         			'<td class="vr">',number_format($data['hits']),'</td>',              					
         			'<td class="vr">',time_since($time,$data['last_used_timestamp']),'</td>',
         			'<td class="vr">',empty($data['timestamp'])?'':time_since($time,$data['timestamp']),'</td>
         		</tr>';
	}
	echo '</table>';
}

function graphs_display() {
	$graphs=array();
	$colors=array('green','brown','red');
	$primes=array(223, 463, 983, 1979, 3907, 7963, 16229, 32531, 65407, 130987);
	$configuration=call_user_func(CACHEPREFIX.'get_configuration'); 
	$status=call_user_func(CACHEPREFIX.'get_status');

	$graphs['memory']['total']=$configuration['directives']['opcache.memory_consumption'];
	$graphs['memory']['free']=$status['memory_usage']['free_memory'];
	$graphs['memory']['used']=$status['memory_usage']['used_memory'];
	$graphs['memory']['wasted']=$status['memory_usage']['wasted_memory'];

	$graphs['keys']['total']=$status[CACHEPREFIX.'statistics']['max_cached_keys'];	
	foreach ($primes as $prime) { if ($prime>=$graphs['keys']['total']) { $graphs['keys']['total']=$prime; break;} }
	$graphs['keys']['free']=$graphs['keys']['total']-$status[CACHEPREFIX.'statistics']['num_cached_keys'];
	$graphs['keys']['scripts']=$status[CACHEPREFIX.'statistics']['num_cached_scripts'];
	$graphs['keys']['wasted']=$status[CACHEPREFIX.'statistics']['num_cached_keys']-$status[CACHEPREFIX.'statistics']['num_cached_scripts'];

	$graphs['hits']['total']=0;
	$graphs['hits']['hits']=$status[CACHEPREFIX.'statistics']['hits'];
	$graphs['hits']['misses']=$status[CACHEPREFIX.'statistics']['misses'];
	$graphs['hits']['blacklist']=$status[CACHEPREFIX.'statistics']['blacklist_misses'];
	$graphs['hits']['total']=array_sum($graphs['hits']);

	$graphs['restarts']['total']=0;
	$graphs['restarts']['manual']=$status[CACHEPREFIX.'statistics']['manual_restarts'];
	$graphs['restarts']['keys']=$status[CACHEPREFIX.'statistics']['hash_restarts'];
	$graphs['restarts']['memory']=$status[CACHEPREFIX.'statistics']['oom_restarts'];
	$graphs['restarts']['total']=array_sum($graphs['restarts']);

	foreach ( $graphs as $caption=>$graph) {
	echo '<div class="graph"><div class="h">',$caption,'</div><table border="0" cellpadding="0" cellspacing="0">';	
	foreach ($graph as $label=>$value) {
		if ($label=='total') { $key=0; $total=$value; $totaldisplay='<td rowspan="3" class="total"><span>'.($total>999999?round($total/1024/1024).'M':($total>9999?round($total/1024).'K':$total)).'</span><div></div></td>'; continue;}
		$percent=$total?floor($value*100/$total):''; $percent=!$percent||$percent>99?'':$percent.'%';
		echo '<tr>',$totaldisplay,'<td class="actual">', ($value>999999?round($value/1024/1024).'M':($value>9999?round($value/1024).'K':$value)),'</td><td class="bar ',$colors[$key],'" height="',$percent,'">',$percent,'</td><td>',$label,'</td></tr>';
		$key++; $totaldisplay='';
	}
	echo '</table></div>',"\n";
	}
}

function meta_display() {
?>
<div class="meta">
	<a href="http://files.zend.com/help/Zend-Server-6/content/zendoptimizerplus.html">directives guide</a> | 
	<a href="http://files.zend.com/help/Zend-Server-6/content/zend_optimizer+_-_php_api.htm">functions guide</a> | 
	<a href="https://wiki.php.net/rfc/optimizerplus">wiki.php.net</a> |
	<a href="http://pecl.php.net/package/ZendOpcache">pecl</a> | 
	<a href="https://github.com/zend-dev/ZendOptimizerPlus/">Zend source</a> | 		
	<a href="https://gist.github.com/ck-on/4959032/?ocp.php">OCP latest</a>
</div>
<?php
}
