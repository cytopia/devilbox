<?php
/** Adminer - Compact database management
* @link https://www.adminer.org/
* @author Jakub Vrana, https://www.vrana.cz/
* @copyright 2007 Jakub Vrana
* @license https://www.apache.org/licenses/LICENSE-2.0 Apache License, Version 2.0
* @license https://www.gnu.org/licenses/gpl-2.0.html GNU General Public License, version 2 (one or other)
* @version 4.7.7
*/error_reporting(6135);$Xc=!preg_match('~^(unsafe_raw)?$~',ini_get("filter.default"));if($Xc||ini_get("filter.default_flags")){foreach(array('_GET','_POST','_COOKIE','_SERVER')as$X){$Ii=filter_input_array(constant("INPUT$X"),FILTER_UNSAFE_RAW);if($Ii)$$X=$Ii;}}if(function_exists("mb_internal_encoding"))mb_internal_encoding("8bit");function
connection(){global$g;return$g;}function
adminer(){global$b;return$b;}function
version(){global$ia;return$ia;}function
idf_unescape($u){$pe=substr($u,-1);return
str_replace($pe.$pe,$pe,substr($u,1,-1));}function
escape_string($X){return
substr(q($X),1,-1);}function
number($X){return
preg_replace('~[^0-9]+~','',$X);}function
number_type(){return'((?<!o)int(?!er)|numeric|real|float|double|decimal|money)';}function
remove_slashes($sg,$Xc=false){if(0){while(list($y,$X)=each($sg)){foreach($X
as$fe=>$W){unset($sg[$y][$fe]);if(is_array($W)){$sg[$y][stripslashes($fe)]=$W;$sg[]=&$sg[$y][stripslashes($fe)];}else$sg[$y][stripslashes($fe)]=($Xc?$W:stripslashes($W));}}}}function
bracket_escape($u,$Oa=false){static$ui=array(':'=>':1',']'=>':2','['=>':3','"'=>':4');return
strtr($u,($Oa?array_flip($ui):$ui));}function
min_version($aj,$De="",$h=null){global$g;if(!$h)$h=$g;$nh=$h->server_info;if($De&&preg_match('~([\d.]+)-MariaDB~',$nh,$A)){$nh=$A[1];$aj=$De;}return(version_compare($nh,$aj)>=0);}function
charset($g){return(min_version("5.5.3",0,$g)?"utf8mb4":"utf8");}function
script($yh,$ti="\n"){return"<script".nonce().">$yh</script>$ti";}function
script_src($Ni){return"<script src='".h($Ni)."'".nonce()."></script>\n";}function
nonce(){return' nonce="'.get_nonce().'"';}function
target_blank(){return' target="_blank" rel="noreferrer noopener"';}function
h($P){return
str_replace("\0","&#0;",htmlspecialchars($P,ENT_QUOTES,'utf-8'));}function
nl_br($P){return
str_replace("\n","<br>",$P);}function
checkbox($B,$Y,$fb,$me="",$uf="",$kb="",$ne=""){$H="<input type='checkbox' name='$B' value='".h($Y)."'".($fb?" checked":"").($ne?" aria-labelledby='$ne'":"").">".($uf?script("qsl('input').onclick = function () { $uf };",""):"");return($me!=""||$kb?"<label".($kb?" class='$kb'":"").">$H".h($me)."</label>":$H);}function
optionlist($_f,$hh=null,$Si=false){$H="";foreach($_f
as$fe=>$W){$Af=array($fe=>$W);if(is_array($W)){$H.='<optgroup label="'.h($fe).'">';$Af=$W;}foreach($Af
as$y=>$X)$H.='<option'.($Si||is_string($y)?' value="'.h($y).'"':'').(($Si||is_string($y)?(string)$y:$X)===$hh?' selected':'').'>'.h($X);if(is_array($W))$H.='</optgroup>';}return$H;}function
html_select($B,$_f,$Y="",$tf=true,$ne=""){if($tf)return"<select name='".h($B)."'".($ne?" aria-labelledby='$ne'":"").">".optionlist($_f,$Y)."</select>".(is_string($tf)?script("qsl('select').onchange = function () { $tf };",""):"");$H="";foreach($_f
as$y=>$X)$H.="<label><input type='radio' name='".h($B)."' value='".h($y)."'".($y==$Y?" checked":"").">".h($X)."</label>";return$H;}function
select_input($Ja,$_f,$Y="",$tf="",$eg=""){$Yh=($_f?"select":"input");return"<$Yh$Ja".($_f?"><option value=''>$eg".optionlist($_f,$Y,true)."</select>":" size='10' value='".h($Y)."' placeholder='$eg'>").($tf?script("qsl('$Yh').onchange = $tf;",""):"");}function
confirm($Ne="",$ih="qsl('input')"){return
script("$ih.onclick = function () { return confirm('".($Ne?js_escape($Ne):'Are you sure?')."'); };","");}function
print_fieldset($t,$ue,$dj=false){echo"<fieldset><legend>","<a href='#fieldset-$t'>$ue</a>",script("qsl('a').onclick = partial(toggle, 'fieldset-$t');",""),"</legend>","<div id='fieldset-$t'".($dj?"":" class='hidden'").">\n";}function
bold($Wa,$kb=""){return($Wa?" class='active $kb'":($kb?" class='$kb'":""));}function
odd($H=' class="odd"'){static$s=0;if(!$H)$s=-1;return($s++%2?$H:'');}function
js_escape($P){return
addcslashes($P,"\r\n'\\/");}function
json_row($y,$X=null){static$Yc=true;if($Yc)echo"{";if($y!=""){echo($Yc?"":",")."\n\t\"".addcslashes($y,"\r\n\t\"\\/").'": '.($X!==null?'"'.addcslashes($X,"\r\n\"\\/").'"':'null');$Yc=false;}else{echo"\n}\n";$Yc=true;}}function
ini_bool($Sd){$X=ini_get($Sd);return(preg_match('~^(on|true|yes)$~i',$X)||(int)$X);}function
sid(){static$H;if($H===null)$H=(SID&&!($_COOKIE&&ini_bool("session.use_cookies")));return$H;}function
set_password($Zi,$M,$V,$E){$_SESSION["pwds"][$Zi][$M][$V]=($_COOKIE["adminer_key"]&&is_string($E)?array(encrypt_string($E,$_COOKIE["adminer_key"])):$E);}function
get_password(){$H=get_session("pwds");if(is_array($H))$H=($_COOKIE["adminer_key"]?decrypt_string($H[0],$_COOKIE["adminer_key"]):false);return$H;}function
q($P){global$g;return$g->quote($P);}function
get_vals($F,$e=0){global$g;$H=array();$G=$g->query($F);if(is_object($G)){while($I=$G->fetch_row())$H[]=$I[$e];}return$H;}function
get_key_vals($F,$h=null,$qh=true){global$g;if(!is_object($h))$h=$g;$H=array();$G=$h->query($F);if(is_object($G)){while($I=$G->fetch_row()){if($qh)$H[$I[0]]=$I[1];else$H[]=$I[0];}}return$H;}function
get_rows($F,$h=null,$n="<p class='error'>"){global$g;$yb=(is_object($h)?$h:$g);$H=array();$G=$yb->query($F);if(is_object($G)){while($I=$G->fetch_assoc())$H[]=$I;}elseif(!$G&&!is_object($h)&&$n&&defined("PAGE_HEADER"))echo$n.error()."\n";return$H;}function
unique_array($I,$w){foreach($w
as$v){if(preg_match("~PRIMARY|UNIQUE~",$v["type"])){$H=array();foreach($v["columns"]as$y){if(!isset($I[$y]))continue
2;$H[$y]=$I[$y];}return$H;}}}function
escape_key($y){if(preg_match('(^([\w(]+)('.str_replace("_",".*",preg_quote(idf_escape("_"))).')([ \w)]+)$)',$y,$A))return$A[1].idf_escape(idf_unescape($A[2])).$A[3];return
idf_escape($y);}function
where($Z,$p=array()){global$g,$x;$H=array();foreach((array)$Z["where"]as$y=>$X){$y=bracket_escape($y,1);$e=escape_key($y);$H[]=$e.($x=="sql"&&is_numeric($X)&&preg_match('~\.~',$X)?" LIKE ".q($X):($x=="mssql"?" LIKE ".q(preg_replace('~[_%[]~','[\0]',$X)):" = ".unconvert_field($p[$y],q($X))));if($x=="sql"&&preg_match('~char|text~',$p[$y]["type"])&&preg_match("~[^ -@]~",$X))$H[]="$e = ".q($X)." COLLATE ".charset($g)."_bin";}foreach((array)$Z["null"]as$y)$H[]=escape_key($y)." IS NULL";return
implode(" AND ",$H);}function
where_check($X,$p=array()){parse_str($X,$db);remove_slashes(array(&$db));return
where($db,$p);}function
where_link($s,$e,$Y,$wf="="){return"&where%5B$s%5D%5Bcol%5D=".urlencode($e)."&where%5B$s%5D%5Bop%5D=".urlencode(($Y!==null?$wf:"IS NULL"))."&where%5B$s%5D%5Bval%5D=".urlencode($Y);}function
convert_fields($f,$p,$K=array()){$H="";foreach($f
as$y=>$X){if($K&&!in_array(idf_escape($y),$K))continue;$Ga=convert_field($p[$y]);if($Ga)$H.=", $Ga AS ".idf_escape($y);}return$H;}function
cookie($B,$Y,$xe=2592000){global$ba;return
header("Set-Cookie: $B=".urlencode($Y).($xe?"; expires=".gmdate("D, d M Y H:i:s",time()+$xe)." GMT":"")."; path=".preg_replace('~\?.*~','',$_SERVER["REQUEST_URI"]).($ba?"; secure":"")."; HttpOnly; SameSite=lax",false);}function
restart_session(){if(!ini_bool("session.use_cookies"))session_start();}function
stop_session($dd=false){$Ri=ini_bool("session.use_cookies");if(!$Ri||$dd){session_write_close();if($Ri&&@ini_set("session.use_cookies",false)===false)session_start();}}function&get_session($y){return$_SESSION[$y][DRIVER][SERVER][$_GET["username"]];}function
set_session($y,$X){$_SESSION[$y][DRIVER][SERVER][$_GET["username"]]=$X;}function
auth_url($Zi,$M,$V,$l=null){global$gc;preg_match('~([^?]*)\??(.*)~',remove_from_uri(implode("|",array_keys($gc))."|username|".($l!==null?"db|":"").session_name()),$A);return"$A[1]?".(sid()?SID."&":"").($Zi!="server"||$M!=""?urlencode($Zi)."=".urlencode($M)."&":"")."username=".urlencode($V).($l!=""?"&db=".urlencode($l):"").($A[2]?"&$A[2]":"");}function
is_ajax(){return($_SERVER["HTTP_X_REQUESTED_WITH"]=="XMLHttpRequest");}function
redirect($ze,$Ne=null){if($Ne!==null){restart_session();$_SESSION["messages"][preg_replace('~^[^?]*~','',($ze!==null?$ze:$_SERVER["REQUEST_URI"]))][]=$Ne;}if($ze!==null){if($ze=="")$ze=".";header("Location: $ze");exit;}}function
query_redirect($F,$ze,$Ne,$Dg=true,$Ec=true,$Pc=false,$gi=""){global$g,$n,$b;if($Ec){$Fh=microtime(true);$Pc=!$g->query($F);$gi=format_time($Fh);}$Ah="";if($F)$Ah=$b->messageQuery($F,$gi,$Pc);if($Pc){$n=error().$Ah.script("messagesPrint();");return
false;}if($Dg)redirect($ze,$Ne.$Ah);return
true;}function
queries($F){global$g;static$xg=array();static$Fh;if(!$Fh)$Fh=microtime(true);if($F===null)return
array(implode("\n",$xg),format_time($Fh));$xg[]=(preg_match('~;$~',$F)?"DELIMITER ;;\n$F;\nDELIMITER ":$F).";";return$g->query($F);}function
apply_queries($F,$S,$Ac='table'){foreach($S
as$Q){if(!queries("$F ".$Ac($Q)))return
false;}return
true;}function
queries_redirect($ze,$Ne,$Dg){list($xg,$gi)=queries(null);return
query_redirect($xg,$ze,$Ne,$Dg,false,!$Dg,$gi);}function
format_time($Fh){return
sprintf('%.3f s',max(0,microtime(true)-$Fh));}function
relative_uri(){return
preg_replace('~^[^?]*/([^?]*)~','\1',$_SERVER["REQUEST_URI"]);}function
remove_from_uri($Pf=""){return
substr(preg_replace("~(?<=[?&])($Pf".(SID?"":"|".session_name()).")=[^&]*&~",'',relative_uri()."&"),0,-1);}function
pagination($D,$Lb){return" ".($D==$Lb?$D+1:'<a href="'.h(remove_from_uri("page").($D?"&page=$D".($_GET["next"]?"&next=".urlencode($_GET["next"]):""):"")).'">'.($D+1)."</a>");}function
get_file($y,$Tb=false){$Vc=$_FILES[$y];if(!$Vc)return
null;foreach($Vc
as$y=>$X)$Vc[$y]=(array)$X;$H='';foreach($Vc["error"]as$y=>$n){if($n)return$n;$B=$Vc["name"][$y];$oi=$Vc["tmp_name"][$y];$Ab=file_get_contents($Tb&&preg_match('~\.gz$~',$B)?"compress.zlib://$oi":$oi);if($Tb){$Fh=substr($Ab,0,3);if(function_exists("iconv")&&preg_match("~^\xFE\xFF|^\xFF\xFE~",$Fh,$Jg))$Ab=iconv("utf-16","utf-8",$Ab);elseif($Fh=="\xEF\xBB\xBF")$Ab=substr($Ab,3);$H.=$Ab."\n\n";}else$H.=$Ab;}return$H;}function
upload_error($n){$Ke=($n==UPLOAD_ERR_INI_SIZE?ini_get("upload_max_filesize"):0);return($n?'Unable to upload a file.'.($Ke?" ".sprintf('Maximum allowed file size is %sB.',$Ke):""):'File does not exist.');}function
repeat_pattern($cg,$ve){return
str_repeat("$cg{0,65535}",$ve/65535)."$cg{0,".($ve%65535)."}";}function
is_utf8($X){return(preg_match('~~u',$X)&&!preg_match('~[\0-\x8\xB\xC\xE-\x1F]~',$X));}function
shorten_utf8($P,$ve=80,$Mh=""){if(!preg_match("(^(".repeat_pattern("[\t\r\n -\x{10FFFF}]",$ve).")($)?)u",$P,$A))preg_match("(^(".repeat_pattern("[\t\r\n -~]",$ve).")($)?)",$P,$A);return
h($A[1]).$Mh.(isset($A[2])?"":"<i>â¦</i>");}function
format_number($X){return
strtr(number_format($X,0,".",','),preg_split('~~u','0123456789',-1,PREG_SPLIT_NO_EMPTY));}function
friendly_url($X){return
preg_replace('~[^a-z0-9_]~i','-',$X);}function
hidden_fields($sg,$Hd=array()){$H=false;while(list($y,$X)=each($sg)){if(!in_array($y,$Hd)){if(is_array($X)){foreach($X
as$fe=>$W)$sg[$y."[$fe]"]=$W;}else{$H=true;echo'<input type="hidden" name="'.h($y).'" value="'.h($X).'">';}}}return$H;}function
hidden_fields_get(){echo(sid()?'<input type="hidden" name="'.session_name().'" value="'.h(session_id()).'">':''),(SERVER!==null?'<input type="hidden" name="'.DRIVER.'" value="'.h(SERVER).'">':""),'<input type="hidden" name="username" value="'.h($_GET["username"]).'">';}function
table_status1($Q,$Qc=false){$H=table_status($Q,$Qc);return($H?$H:array("Name"=>$Q));}function
column_foreign_keys($Q){global$b;$H=array();foreach($b->foreignKeys($Q)as$q){foreach($q["source"]as$X)$H[$X][]=$q;}return$H;}function
enum_input($T,$Ja,$o,$Y,$vc=null){global$b;preg_match_all("~'((?:[^']|'')*)'~",$o["length"],$Fe);$H=($vc!==null?"<label><input type='$T'$Ja value='$vc'".((is_array($Y)?in_array($vc,$Y):$Y===0)?" checked":"")."><i>".'empty'."</i></label>":"");foreach($Fe[1]as$s=>$X){$X=stripcslashes(str_replace("''","'",$X));$fb=(is_int($Y)?$Y==$s+1:(is_array($Y)?in_array($s+1,$Y):$Y===$X));$H.=" <label><input type='$T'$Ja value='".($s+1)."'".($fb?' checked':'').'>'.h($b->editVal($X,$o)).'</label>';}return$H;}function
input($o,$Y,$r){global$U,$b,$x;$B=h(bracket_escape($o["field"]));echo"<td class='function'>";if(is_array($Y)&&!$r){$Ea=array($Y);if(version_compare(PHP_VERSION,5.4)>=0)$Ea[]=JSON_PRETTY_PRINT;$Y=call_user_func_array('json_encode',$Ea);$r="json";}$Ng=($x=="mssql"&&$o["auto_increment"]);if($Ng&&!$_POST["save"])$r=null;$md=(isset($_GET["select"])||$Ng?array("orig"=>'original'):array())+$b->editFunctions($o);$Ja=" name='fields[$B]'";if($o["type"]=="enum")echo
h($md[""])."<td>".$b->editInput($_GET["edit"],$o,$Ja,$Y);else{$wd=(in_array($r,$md)||isset($md[$r]));echo(count($md)>1?"<select name='function[$B]'>".optionlist($md,$r===null||$wd?$r:"")."</select>".on_help("getTarget(event).value.replace(/^SQL\$/, '')",1).script("qsl('select').onchange = functionChange;",""):h(reset($md))).'<td>';$Ud=$b->editInput($_GET["edit"],$o,$Ja,$Y);if($Ud!="")echo$Ud;elseif(preg_match('~bool~',$o["type"]))echo"<input type='hidden'$Ja value='0'>"."<input type='checkbox'".(preg_match('~^(1|t|true|y|yes|on)$~i',$Y)?" checked='checked'":"")."$Ja value='1'>";elseif($o["type"]=="set"){preg_match_all("~'((?:[^']|'')*)'~",$o["length"],$Fe);foreach($Fe[1]as$s=>$X){$X=stripcslashes(str_replace("''","'",$X));$fb=(is_int($Y)?($Y>>$s)&1:in_array($X,explode(",",$Y),true));echo" <label><input type='checkbox' name='fields[$B][$s]' value='".(1<<$s)."'".($fb?' checked':'').">".h($b->editVal($X,$o)).'</label>';}}elseif(preg_match('~blob|bytea|raw|file~',$o["type"])&&ini_bool("file_uploads"))echo"<input type='file' name='fields-$B'>";elseif(($ei=preg_match('~text|lob|memo~i',$o["type"]))||preg_match("~\n~",$Y)){if($ei&&$x!="sqlite")$Ja.=" cols='50' rows='12'";else{$J=min(12,substr_count($Y,"\n")+1);$Ja.=" cols='30' rows='$J'".($J==1?" style='height: 1.2em;'":"");}echo"<textarea$Ja>".h($Y).'</textarea>';}elseif($r=="json"||preg_match('~^jsonb?$~',$o["type"]))echo"<textarea$Ja cols='50' rows='12' class='jush-js'>".h($Y).'</textarea>';else{$Me=(!preg_match('~int~',$o["type"])&&preg_match('~^(\d+)(,(\d+))?$~',$o["length"],$A)?((preg_match("~binary~",$o["type"])?2:1)*$A[1]+($A[3]?1:0)+($A[2]&&!$o["unsigned"]?1:0)):($U[$o["type"]]?$U[$o["type"]]+($o["unsigned"]?0:1):0));if($x=='sql'&&min_version(5.6)&&preg_match('~time~',$o["type"]))$Me+=7;echo"<input".((!$wd||$r==="")&&preg_match('~(?<!o)int(?!er)~',$o["type"])&&!preg_match('~\[\]~',$o["full_type"])?" type='number'":"")." value='".h($Y)."'".($Me?" data-maxlength='$Me'":"").(preg_match('~char|binary~',$o["type"])&&$Me>20?" size='40'":"")."$Ja>";}echo$b->editHint($_GET["edit"],$o,$Y);$Yc=0;foreach($md
as$y=>$X){if($y===""||!$X)break;$Yc++;}if($Yc)echo
script("mixin(qsl('td'), {onchange: partial(skipOriginal, $Yc), oninput: function () { this.onchange(); }});");}}function
process_input($o){global$b,$m;$u=bracket_escape($o["field"]);$r=$_POST["function"][$u];$Y=$_POST["fields"][$u];if($o["type"]=="enum"){if($Y==-1)return
false;if($Y=="")return"NULL";return+$Y;}if($o["auto_increment"]&&$Y=="")return
null;if($r=="orig")return(preg_match('~^CURRENT_TIMESTAMP~i',$o["on_update"])?idf_escape($o["field"]):false);if($r=="NULL")return"NULL";if($o["type"]=="set")return
array_sum((array)$Y);if($r=="json"){$r="";$Y=json_decode($Y,true);if(!is_array($Y))return
false;return$Y;}if(preg_match('~blob|bytea|raw|file~',$o["type"])&&ini_bool("file_uploads")){$Vc=get_file("fields-$u");if(!is_string($Vc))return
false;return$m->quoteBinary($Vc);}return$b->processInput($o,$Y,$r);}function
fields_from_edit(){global$m;$H=array();foreach((array)$_POST["field_keys"]as$y=>$X){if($X!=""){$X=bracket_escape($X);$_POST["function"][$X]=$_POST["field_funs"][$y];$_POST["fields"][$X]=$_POST["field_vals"][$y];}}foreach((array)$_POST["fields"]as$y=>$X){$B=bracket_escape($y,1);$H[$B]=array("field"=>$B,"privileges"=>array("insert"=>1,"update"=>1),"null"=>1,"auto_increment"=>($y==$m->primary),);}return$H;}function
search_tables(){global$b,$g;$_GET["where"][0]["val"]=$_POST["query"];$kh="<ul>\n";foreach(table_status('',true)as$Q=>$R){$B=$b->tableName($R);if(isset($R["Engine"])&&$B!=""&&(!$_POST["tables"]||in_array($Q,$_POST["tables"]))){$G=$g->query("SELECT".limit("1 FROM ".table($Q)," WHERE ".implode(" AND ",$b->selectSearchProcess(fields($Q),array())),1));if(!$G||$G->fetch_row()){$og="<a href='".h(ME."select=".urlencode($Q)."&where[0][op]=".urlencode($_GET["where"][0]["op"])."&where[0][val]=".urlencode($_GET["where"][0]["val"]))."'>$B</a>";echo"$kh<li>".($G?$og:"<p class='error'>$og: ".error())."\n";$kh="";}}}echo($kh?"<p class='message'>".'No tables.':"</ul>")."\n";}function
dump_headers($Ed,$We=false){global$b;$H=$b->dumpHeaders($Ed,$We);$Mf=$_POST["output"];if($Mf!="text")header("Content-Disposition: attachment; filename=".$b->dumpFilename($Ed).".$H".($Mf!="file"&&!preg_match('~[^0-9a-z]~',$Mf)?".$Mf":""));session_write_close();ob_flush();flush();return$H;}function
dump_csv($I){foreach($I
as$y=>$X){if(preg_match("~[\"\n,;\t]~",$X)||$X==="")$I[$y]='"'.str_replace('"','""',$X).'"';}echo
implode(($_POST["format"]=="csv"?",":($_POST["format"]=="tsv"?"\t":";")),$I)."\r\n";}function
apply_sql_function($r,$e){return($r?($r=="unixepoch"?"DATETIME($e, '$r')":($r=="count distinct"?"COUNT(DISTINCT ":strtoupper("$r("))."$e)"):$e);}function
get_temp_dir(){$H=ini_get("upload_tmp_dir");if(!$H){if(function_exists('sys_get_temp_dir'))$H=sys_get_temp_dir();else{$Wc=@tempnam("","");if(!$Wc)return
false;$H=dirname($Wc);unlink($Wc);}}return$H;}function
file_open_lock($Wc){$kd=@fopen($Wc,"r+");if(!$kd){$kd=@fopen($Wc,"w");if(!$kd)return;chmod($Wc,0660);}flock($kd,LOCK_EX);return$kd;}function
file_write_unlock($kd,$Nb){rewind($kd);fwrite($kd,$Nb);ftruncate($kd,strlen($Nb));flock($kd,LOCK_UN);fclose($kd);}function
password_file($i){$Wc=get_temp_dir()."/adminer.key";$H=@file_get_contents($Wc);if($H||!$i)return$H;$kd=@fopen($Wc,"w");if($kd){chmod($Wc,0660);$H=rand_string();fwrite($kd,$H);fclose($kd);}return$H;}function
rand_string(){return
md5(uniqid(mt_rand(),true));}function
select_value($X,$_,$o,$fi){global$b;if(is_array($X)){$H="";foreach($X
as$fe=>$W)$H.="<tr>".($X!=array_values($X)?"<th>".h($fe):"")."<td>".select_value($W,$_,$o,$fi);return"<table cellspacing='0'>$H</table>";}if(!$_)$_=$b->selectLink($X,$o);if($_===null){if(is_mail($X))$_="mailto:$X";if(is_url($X))$_=$X;}$H=$b->editVal($X,$o);if($H!==null){if(!is_utf8($H))$H="\0";elseif($fi!=""&&is_shortable($o))$H=shorten_utf8($H,max(0,+$fi));else$H=h($H);}return$b->selectVal($H,$_,$o,$X);}function
is_mail($sc){$Ha='[-a-z0-9!#$%&\'*+/=?^_`{|}~]';$fc='[a-z0-9]([-a-z0-9]{0,61}[a-z0-9])';$cg="$Ha+(\\.$Ha+)*@($fc?\\.)+$fc";return
is_string($sc)&&preg_match("(^$cg(,\\s*$cg)*\$)i",$sc);}function
is_url($P){$fc='[a-z0-9]([-a-z0-9]{0,61}[a-z0-9])';return
preg_match("~^(https?)://($fc?\\.)+$fc(:\\d+)?(/.*)?(\\?.*)?(#.*)?\$~i",$P);}function
is_shortable($o){return
preg_match('~char|text|json|lob|geometry|point|linestring|polygon|string|bytea~',$o["type"]);}function
count_rows($Q,$Z,$ae,$pd){global$x;$F=" FROM ".table($Q).($Z?" WHERE ".implode(" AND ",$Z):"");return($ae&&($x=="sql"||count($pd)==1)?"SELECT COUNT(DISTINCT ".implode(", ",$pd).")$F":"SELECT COUNT(*)".($ae?" FROM (SELECT 1$F GROUP BY ".implode(", ",$pd).") x":$F));}function
slow_query($F){global$b,$qi,$m;$l=$b->database();$hi=$b->queryTimeout();$vh=$m->slowQuery($F,$hi);if(!$vh&&support("kill")&&is_object($h=connect())&&($l==""||$h->select_db($l))){$ke=$h->result(connection_id());echo'<script',nonce(),'>
var timeout = setTimeout(function () {
	ajax(\'',js_escape(ME),'script=kill\', function () {
	}, \'kill=',$ke,'&token=',$qi,'\');
}, ',1000*$hi,');
</script>
';}else$h=null;ob_flush();flush();$H=@get_key_vals(($vh?$vh:$F),$h,false);if($h){echo
script("clearTimeout(timeout);");ob_flush();flush();}return$H;}function
get_token(){$_g=rand(1,1e6);return($_g^$_SESSION["token"]).":$_g";}function
verify_token(){list($qi,$_g)=explode(":",$_POST["token"]);return($_g^$_SESSION["token"])==$qi;}function
lzw_decompress($Sa){$cc=256;$Ta=8;$mb=array();$Pg=0;$Qg=0;for($s=0;$s<strlen($Sa);$s++){$Pg=($Pg<<8)+ord($Sa[$s]);$Qg+=8;if($Qg>=$Ta){$Qg-=$Ta;$mb[]=$Pg>>$Qg;$Pg&=(1<<$Qg)-1;$cc++;if($cc>>$Ta)$Ta++;}}$bc=range("\0","\xFF");$H="";foreach($mb
as$s=>$lb){$rc=$bc[$lb];if(!isset($rc))$rc=$oj.$oj[0];$H.=$rc;if($s)$bc[]=$oj.$rc[0];$oj=$rc;}return$H;}function
on_help($sb,$sh=0){return
script("mixin(qsl('select, input'), {onmouseover: function (event) { helpMouseover.call(this, event, $sb, $sh) }, onmouseout: helpMouseout});","");}function
edit_form($a,$p,$I,$Li){global$b,$x,$qi,$n;$Rh=$b->tableName(table_status1($a,true));page_header(($Li?'Edit':'Insert'),$n,array("select"=>array($a,$Rh)),$Rh);if($I===false)echo"<p class='error'>".'No rows.'."\n";echo'<form action="" method="post" enctype="multipart/form-data" id="form">
';if(!$p)echo"<p class='error'>".'You have no privileges to update this table.'."\n";else{echo"<table cellspacing='0' class='layout'>".script("qsl('table').onkeydown = editingKeydown;");foreach($p
as$B=>$o){echo"<tr><th>".$b->fieldName($o);$Ub=$_GET["set"][bracket_escape($B)];if($Ub===null){$Ub=$o["default"];if($o["type"]=="bit"&&preg_match("~^b'([01]*)'\$~",$Ub,$Jg))$Ub=$Jg[1];}$Y=($I!==null?($I[$B]!=""&&$x=="sql"&&preg_match("~enum|set~",$o["type"])?(is_array($I[$B])?array_sum($I[$B]):+$I[$B]):$I[$B]):(!$Li&&$o["auto_increment"]?"":(isset($_GET["select"])?false:$Ub)));if(!$_POST["save"]&&is_string($Y))$Y=$b->editVal($Y,$o);$r=($_POST["save"]?(string)$_POST["function"][$B]:($Li&&preg_match('~^CURRENT_TIMESTAMP~i',$o["on_update"])?"now":($Y===false?null:($Y!==null?'':'NULL'))));if(preg_match("~time~",$o["type"])&&preg_match('~^CURRENT_TIMESTAMP~i',$Y)){$Y="";$r="now";}input($o,$Y,$r);echo"\n";}if(!support("table"))echo"<tr>"."<th><input name='field_keys[]'>".script("qsl('input').oninput = fieldChange;")."<td class='function'>".html_select("field_funs[]",$b->editFunctions(array("null"=>isset($_GET["select"]))))."<td><input name='field_vals[]'>"."\n";echo"</table>\n";}echo"<p>\n";if($p){echo"<input type='submit' value='".'Save'."'>\n";if(!isset($_GET["select"])){echo"<input type='submit' name='insert' value='".($Li?'Save and continue edit':'Save and insert next')."' title='Ctrl+Shift+Enter'>\n",($Li?script("qsl('input').onclick = function () { return !ajaxForm(this.form, '".'Saving'."â¦', this); };"):"");}}echo($Li?"<input type='submit' name='delete' value='".'Delete'."'>".confirm()."\n":($_POST||!$p?"":script("focus(qsa('td', qs('#form'))[1].firstChild);")));if(isset($_GET["select"]))hidden_fields(array("check"=>(array)$_POST["check"],"clone"=>$_POST["clone"],"all"=>$_POST["all"]));echo'<input type="hidden" name="referer" value="',h(isset($_POST["referer"])?$_POST["referer"]:$_SERVER["HTTP_REFERER"]),'">
<input type="hidden" name="save" value="1">
<input type="hidden" name="token" value="',$qi,'">
</form>
';}if(isset($_GET["file"])){if($_SERVER["HTTP_IF_MODIFIED_SINCE"]){header("HTTP/1.1 304 Not Modified");exit;}header("Expires: ".gmdate("D, d M Y H:i:s",time()+365*24*60*60)." GMT");header("Last-Modified: ".gmdate("D, d M Y H:i:s")." GMT");header("Cache-Control: immutable");if($_GET["file"]=="favicon.ico"){header("Content-Type: image/x-icon");echo
lzw_decompress("\0\0\0` \0\0\n @\0´Cè\"\0`EãQ¸àÿ?ÀtvM'JdÁd\\b0\0Ä\"ÀfÓ¤îs5ÏçÑAXPaJ0¥8#RT©z`#.©ÇcíXÃþÈ?À-\0¡Im? .«M¶\0È¯(ÌýÀ/(%\0");}elseif($_GET["file"]=="default.css"){header("Content-Type: text/css; charset=utf-8");echo
lzw_decompress("\n1ÌÙÞl7B14vb0Ífs¼ên2BÌÑ±ÙÞn:#(¼b.\rDc)ÈÈa7E¤Âl¦Ã±èi1Ìs´ç-4fÓ	ÈÎi7³¹¤Èt4¦ÓyèZf4°iAT«VVéf:Ï¦,:1¦QÝ¼ñb2`Ç#þ>:7Gï1ÑØÒs°LXD*bv<Ü#£e@Ö:4ç§!fo·Æt:<¥Üå¾oâÜ\niÃÅð',é»a_¤:¹iï´ÁBvø|Nû4.5Nfi¢vpÐh¸°l¨ê¡ÖÜO¦î= £OFQÐÄk\$¥ÓiõÀÂd2Tã¡pàÊ6þ¡-ØZ Þ6½£ðh:¬aÌ,£ëî2#8Ð±#6nâîñJ¢h«t±ä4O42ô½okÞ¾*r ©@p@!Ä¾ÏÃôþ?Ð6Àr[ðLÁð:2Bj§!HbóÃPä=!1V\"²0¿\nSÆÆÏD7ÃìDÚÃC!!à¦GÊ§ È+=tCæ©.C¤À:+ÈÊ=ªªº²¡±å%ªcí1MR/EÈ4© 2°ä± ã`Â8(áÓ¹[WäÑ=ySb°=Ö-Ü¹BS+É¯ÈÜý¥ø@pL4Ydãqøã¦ðê¢6£3Ä¬¯¸AcÜèÎ¨k[&>ö¨ZÁpkm]u-c:Ø¸NtæÎ´pÒ8è=¿#á[.ðÜÞ¯~ mËyPPá|IÖùÀìQª9v[Q\nÙrô'g+áTÑ2­VÁõzä4£8÷(	¾Ey*#j¬2]­RÒÁ¥)À[N­R\$<>:ó­>\$;> Ì\r»ÎHÍÃTÈ\nw¡N åwØ£¦ì<ïËGwàöö¹\\Yó_ Rt^>\r}ÙS\rzé4=µ\nL%Jã\",Z 8¸i÷0u©?¨ûÑô¡s3#¨Ù :ó¦ûã½ÈÞE]xÝÒs^8£K^É÷*0ÑÞwÞàÈÞ~ãö:íÑiØþv2w½ÿ±û^7ãò7£cÝÑu+U%{PÜ*4Ì¼éLX./!¼1CÅßqx!H¹ãFdù­L¨¤¨Ä Ï`6ëè5®f¸Ä¨=Høl V1\0a2×;Ô6àöþ_ÙÄ\0&ôZÜS d)KE'nµ[X©³\0ZÉÔF[PÞ@àß!ñYÂ,`É\"Ú·Â0Ee9yF>ËÔ9bºæF5:ü\0}Ä´(\$Óë37Hö£è M¾A°²6Rú{MqÝ7G ÚCCêm2¢(Ct>[ì-tÀ/&C]êetGôÌ¬4@r>ÇÂå<Sq/åúQëhmÀÐÆôãôLÀÜ#èôKË|®6fKPÝ\r%tÔÓV=\" SH\$} ¸)w¡,W\0F³ªu@Øb¦9\rr°2Ã#¬DX³ÚyOIù>»nÇ¢%ãù'Ý_Át\rÏzÄ\\1hl¼]Q5Mp6kÐÄqhÃ\$£H~Í|ÒÝ!*4ñòÛ`Sëý²S tíPP\\g±è7\n-:è¢ªp´lB¦î7Ó¨c(wO0\\:ÐwÁp4ò{TÚújO¤6HÃ¶rÕ¥q\n¦É%%¶y']\$aZÓ.fcÕq*-êFWºúkz°µj°lgá:\$\"ÞN¼\r#ÉdâÃÂÿÐscá¬Ì \"jª\rÀ¶¦Õ¼Ph1/DA) ²Ý[ÀknÁp76ÁY´R{áM¤Pû°ò@\n-¸a·6þß[»zJH,dl B£ho³ìò¬+#Dr^µ^µÙe¼E½½ ÄaPôõJG£zàñtñ 2ÇXÙ¢´Á¿V¶×ßàÞÈ³ÑB_%K=E©¸bå¼¾ßÂ§kU(.!Ü®8¸üÉI.@KÍxnþ¬ü:ÃPó32«míH		C*ì:vâTÅ\nR¹µ0uÂíæîÒ§]Î¯P/µJQd¥{LÞ³:YÁ2b¼T ñÊ3Ó4äcê¥V=¿L4ÎÐrÄ!ßBðY³6Í­MeLªÜçöùiÀoÐ9< G¤ÆÐMhm^¯UÛNÀ·òTr5HiM/¬ní³T [-<__î3/Xr(<¯®ÉôÌuÒGNX20å\r\$^:'9è¶Oí;×k¼µf N'a¶Ç­bÅ,ËV¤ô«1µïHI!%6@úÏ\$ÒEGÚ¬1(mUªårÕ½ïßå`¡ÐiN+Ãñ)ä0lØÒf0Ã½[UâøVÊè-:I^ \$Øs«b\reugÉhª~9ÛßbµôÂÈfä+0¬Ô hXrÝ¬©!\$e,±w+÷ë3Ì_âAkù\nkÃrõÊcuWdYÿ\\×={.óÄ¢g»p8t\rRZ¿vJ:²>þ£Y|+Å@ÀÛCt\rjt½6²ð%Â?àôÇñ>ù/¥ÍÇðÎ9F`×äòv~K¤áöÑRÐWðzêlmªwLÇ9Y*q¬xÄzñèSe®Ý³è÷£~DàÍá÷x¾ëÉi72ÄøÑOÝ»û_{ñú53âút_õzÔ3ùd)C¯Â\$?KÓªP%ÏÏT&þ&\0P×NA^­~¢ pÆ öÏÔõ\r\$ÞïÐÖìb*+D6ê¶¦ÏÞíJ\$(ÈolÞÍh&ìKBS>¸ö;z¶¦xÅoz>íÚoÄZð\nÊ[ÏvõËÈµ°2õOxÙVø0fûú¯Þ2BlÉbkÐ6ZkµhXcdê0*ÂKTâ¯H=­Ïp0lVéõèâ\r¼¥nm¦ï)((ô:#¦âòEÜ:C¨CàÚâ\r¨G\rÃ©0÷iæÚ°þ:`Z1Q\n:à\r\0àçÈq±°ü:`¿-ÈM#}1;èþ¹q#|ñS¾¢hlDÄ\0fiDpëL ``°çÑ0yß1ê\rñ=MQ\\¤³%oq­\0Øñ£1¨21¬1°­ ¿±§Ñbi:í\r±/Ñ¢ `)Ä0ù@¾Â±ÃI1«NàCØàµñO±¢Zñã1±ïq1 òÑüà,å\rdIÇ¦väjí1 tÚBø°â0:0ðð1 A2Vñâ0 éñ%²fi3!&Q·Rc%Òq&w%Ñì\ràVÈ#ÊøQw`% ¾Òm*rÒy&iß+r{*²»(rg(±#(2­(ðå)R@i-  1\"\0Û²Rêÿ.e.rëÄ,¡ry(2ªCàè²bì!BÞ3%Òµ,R¿1²Æ&èþtäbèa\rL³-3á Ö ó\0æóBp1ñ94³O'R°3*²³=\$à[£^iI;/3i©5Ò&}17²# Ñ¹8 ¿\"ß7Ñå8ñ9*Ò23!ó!1\\\0Ï8­rk9±;S23¶àÚ*Ó:q]5S<³Á#383Ý#eÑ=¹>~9Sè³rÕ)T*a@ÑÙbesÙÔ£:-óéÇ*;, Ø3!i´LÒ²ð#1 +nÀ «*²ã@³3i7´1©´_FS;3ÏF±\rA¯é3õ>´x: \r³0ÎÔ@-Ô/¬ÓwÓÛ7ñÓSJ3 ç.Fé\$O¤B±%4©+tÃ'góLq\rJtJôËM2\rôÍ7ñÆT@£¾)â£dÉ2P>Î°Fià²´þ\nr\0¸bçk(´D¶¿ãKQ¤´ã1ã\"2tôôºPè\rÃÀ,\$KCtò5ôö#ôú)¢áP#Pi.ÎU2µCæ~Þ\"ä");}elseif($_GET["file"]=="functions.js"){header("Content-Type: text/javascript; charset=utf-8");echo
lzw_decompress("f:gCI¼Ü\n8Å3)°Ë781ÐÊx:\nOg#)Ðêr7\n\"è´`ø|2ÌgSiH)N¦Sä§\r\"0¹Ä@ä)`(\$s6O!ÓèV/=' T4æ=iS6IO G#ÒX·VCÆs¡ Z1.Ðhp8,³[¦Häµ~Cz§Éå2¹l¾c3Íés£ÙIbâ4\néF8TàIÝ©U*fz¹är0EÆÀØy¸ñfY.:æIÊ(Øc·áÎ!_lí^·^(¶N{S)rËqÁYlÙ¦33Ú\n+G¥ÓêyºíËi¶ÂîxV3w³uhã^rØÀº´aÛú¹cØè\r¨ë(.ÂºChÒ<\r)èÑ£¡`æ7£íò43'm5£È\nPÜ:2£P»ªq òÿÅC}Ä«úÊÁê38BØ0hRÈr(0¥¡b\\0Hr44ÁB!¡pÇ\$rZZË2Ü.É(\\5Ã|\nC(Î\"Pðø.ÐNÌRTÊÎÀæ>HN8HPá\\¬7Jp~Üû2%¡ÐOC¨1ã.§C8ÎHÈò*j°á÷S(¹/¡ì¬6KUÊ¡<2pOIôÕ`Ôäâ³dOH Þ5-üÆ4ãpX25-Ò¢òÛ°z7£¸\"(°P \\32:]UÚèíâß!]¸<·AÛÛ¤ÐßiÚ°l\rÔ\0v²Î#J8«ÏwmíÉ¤¨<É æü%m;p#ã`XDø÷iZøN0È9ø¨å Áè`wJD¿¾2Ò9t¢*øÎyìËNiIh\\9ÆÕèÐ:æáxï­µyl*ÈÎæY Üøê8W³â?µÞ3ÙðÊ!\"6ån[¬Ê\r­*\$¶Æ§¾nzxÆ9\rì|*3×£pÞï»¶:(p\\;ÔËmz¢ü§9óÐÑÂü8NÁj2½«Î\rÉHîH&²(ÃzÁ7iÛk£ ¤c¤eòý§tÌÌ2:SHóÈ Ã/)xÞ@éåtri9¥½õë8ÏÀËïyÒ·½°VÄ+^WÚ¦­¬kZæYl·Ê£4ÖÈÆª¶À¬ð\\EÈ{î7\0¹pDi-TæþÚû0l°%=Á ÐË9(5ð\n\nn,4\0èa}Ü.°öRsïª\02B\\Ûb1S±\0003,ÔXPHJspådK CA!°2*WÔñÚ2\$ä+Âf^\n1´òzE Iv¤\\ä2É .*A°E(d±á°ÃbêÂÜÆ9âÁDh&­ª?ÄH°sQ2x~nÃJT2ù&ãàeR½GÒQTwêÝ»õPâã\\ )6¦ôâÂòsh\\3¨\0R	À'\r+*;RðHà.!Ñ[Í'~­%t< çpÜK#Âæ!ñlßÌðLe³Ù,ÄÀ®&á\$	Á½`CXÓ0Ö­å¼û³Ä:Méh	çÚGäÑ!&3 D<!è23Ã?h¤J©e Úðhá\r¡mðNi¸£´ÊNØHl7¡®vêWIå.´Á-Ó5Ö§ey\rEJ\ni*¼\$@ÚRU0,\$U¿E¦ÔÔÂªu)@(tÎSJkáp!~­àd`Ì>¯\nÃ;#\rp9jÉ¹Ü]&Nc(rTQUª½S·Ú\08n`«yb¤ÅLÜO5î,¤ò>xââ±fä´âØ+\"ÑI{kMÈ[\r%Æ[	¤eôaÔ1! èÿí³Ô®©F@«b)R£72î0¡\nW¨±L²ÜÒ®tdÕ+íÜ0wglø0n@òêÉ¢ÕiíM«\nA§M5nì\$E³×±NÛál©Ý×ì%ª1 AÜûºú÷ÝkñrîiFB÷Ïùol,muNx-Í_ Ö¤C( fél\r1p[9x(i´BÒ²ÛzQlüº8CÔ	´©XU Tb£ÝIÝ`p+V\0îÑ;CbÎÀXñ+Ïsïü]H÷Ò[ákx¬G*ô]·awnú!Å6òâÛÐmSí¾IÞÍKË~/Ó¥7ÞùeeNÉòªS«/;dåA>}l~Ïê ¨%^´fçØ¢pÚDEîÃa·t\nx=ÃkÐ*dºêðTºüûj2Éj\n É ,e=M84ôûÔaj@îTÃsÔänf©Ý\nî6ª\rd¼0ÞíôY'%ÔíÞ~	Ò¨<ÖËAîH¿G8ñ¿Î\$z«ð{¶»²u2*àaÀ>»(wK.bP{oýÂ´«zµ#ë2ö8=É8>ª¤³A,°e°À+ìCè§xõ*ÃáÒ-b=m,aÃlzkï\$Wõ,mJiæÊ§á÷+èý0°[¯ÿ.RÊsKùÇäXçÝZLËç2`Ì(ïCàvZ¡ÜÝÀ¶è\$×¹,åD?H±ÖNxXôó)îM¨\$ó,Í*\nÑ£\$<qÿÅh!¿¹SâÀxsA!:´K¥Á}Á²ù¬£RþA2k·Xp\n<÷þ¦ýëlì§Ù3¯ø¦ÈVV¬}£g&YÝ!+ó;<¸YÇóYE3r³ÙñCío5¦Åù¢Õ³Ïkkþø°ÖÛ£«Ït÷Uø­)û[ýßÁî}ïØu´«lç¢:Dø+Ï _oãäh140ÖáÊ0ø¯bäKã¬ öþé»lGª#ª©ê¦©ì|Udæ¶IK«êÂ7à^ìà¸@º®O\0HÅðHi6\rÛ©Ü\\cg\0öãë2BÄ*eà\n	zr!nWz& {Hð'\$X  w@Ò8ëDGr*ëÄÝHå'p#Ä®¦Ô\ndü÷,ô¥,ü;g~¯\0Ð#Ì²EÂ\rÖI`î'ð%EÒ. ]`ÊÐî%&Ðîm°ý\râÞ%4Svð#\n fH\$%ë-Â#­ÆÑqBâíæ ÀÂQ-ôc2§&ÂÀÌ]à èqh\rñl]à®s ÐÑhä7±n#±Ú-àjE¯Frç¤l&dÀØÙåzìF6¸Á\" |¿§¢s@ß±®åz)0rpÚ\0X\0¤Ùè|DL<!°ôo*D¶{.B<Eª0nB(ï |\r\nì^©à h³!Öêr\$§(^ª~èÞÂ/pq²ÌB¨ÅOðú,\\µ¨#RRÎ%ëäÍdÐHjÄ`Â ô®Ì­ Vå bSd§iEøïoh´r<i/k\$-\$o¼+ÆÅÎúlÒÞO³&evÆ¼iÒjMPA'u'Î( M(h/+«òWD¾So·.n·.ðn¸ìê((\"­À§hö&p¨/Ë/1DÌçjå¨¸EèÞ&â¦,'l\$/.,Äd¨WbbO3óB³sH :J`!.ªÀû¥ ,FÀÑ7(ÈÔ¿³û1lås ÖÒ²Å¢q¢X\rÀ®~Ré°±`®Òó®Y*ä:R¨ùrJ´·%LÏ+n¸\"ø\r¦ÎÍH!qb¾2âLi±%ÓÞÎ¨Wj#9ÓÔObE.I:6Á7\0Ë6+¤%°.ÈÞ³a7E8VSå?(DG¨Ó³Bë%;ò¬ùÔ/<´ú¥À\r ì´>ûMÀ°@¶¾H DsÐ°Z[tH£Enx(ð©R xñû@¯þGkjW>ÌÂÚ#T/8®c8éQ0Ëè_ÔIIGII!¥ðYEdËE´^tdéthÂ`DV!Cæ8¥\r­´b3©!3â@Ù33N}âZBó3	Ï3ä30ÚÜM(ê>Ê}ä\\Ñtêf fËâI\r®ó337 XÔ\"tdÎ,\nbtNO`Pâ;­ÜÒ­ÀÔ¯\$\nßäZÑ­5U5WUµ^hoýàætÙPM/5K4Ej³KQ&53GXXx)Ò<5D\rûVô\nßr¢5bÜ\\J\">§è1S\r[-¦ÊDuÀ\rÒâ§Ã)00óYõÈË¢·k{\nµÄ#µÞ\r³^·|èuÜ»Uå_nïU4ÉU~YtÓ\rIÃ@ä³R ó3:ÒuePMSè0TµwW¯XÈòòD¨ò¤KOUÜà;Uõ\n OYéYÍQ,M[\0÷_ªDÍÈW ¾J*ì\rg(]à¨\r\"ZC©6uê+µYóY6Ã´0ªqõ(Ùó8}ó3AX3T h9j¶jàfõMtåPJbqMP5>ðÈø¶©Yk%&\\1d¢ØE4À µYnÊí\$<¥U]Ó1mbÖ¶^Òõ ê\"NVéßp¶ëpõ±eMÚÞ×WéÜ¢î\\ä)\n Ë\nf7\n×2´õr8=Ek7tVµ7P¦¶LÉía6òòv@'6iàïj&>±â;­ã`Òÿa	\0pÚ¨(µJÑë)«\\¿ªnûòÄ¬m\0¼¨2ôeqJö­Pôtë±fjüÂ\"[\0¨·¢X,<\\î¶×â÷æ·+mdå~âàÑs%o°´mn×),×æÔ²\r4¶Â8\r±Î¸×mEH]¦üÖHW­M0Dïßå~ËKîE}ø¸´à|fØ^Ü×\r>Ô-z]2sxDd[stS¢¶\0Qf-K`­¢tàØwT¯9æZà	ø\nB£9 Nbã<ÚBþI5o×oJñpÀÏJNdåË\rhÞÃ2\"àxæHCàÝ:øý9Yn16Æôzr+z±ùþ\\÷ôm Þ±T öò ÷@Y2lQ<2O+¥%Í.Óhù0AÞñ¸ÃZ2R¦À1£/¯hH\r¨XÈaNB&§ ÄM@Ö[xÊ®¥êâ8&LÚVÍvà±*j¤ÛGHåÈ\\Ù®	²¶&sÛ\0Q \\\"èb °	àÄ\rBsÉw	ÙáBN`7§Co(ÙÃà¨\nÃ¨¨19Ì*E ñSÓU0Uº t'|m°Þ?h[¢\$.#É5	 å	pàyBà@Rô]£ê@|§{ÀÊP\0xô/¦ w¢%¤EsBd¿§CU~O×·àPà@Xâ]Ô¨Z3¨¥1¦¥{©eLY¡Ú¢\\(*R` 	à¦\nàºÌQCFÈ*¹¹àé¬ÚpX|`N¨¾\$[@ÍU¢àð¦¶àZ¥`Zd\"\\\"¢£)«I:ètìoDæ\0[²¨à±-© gí³®*`hu%£,¬ãIµ7Ä«²Hóµm¤6Þ}®ºNÖÍ³\$»MµUYf&1ùÀe]pz¥§ÚI¤Åm¶G/£ ºw Ü!\\#5¥4I¥d¹EÂhqå¦÷Ñ¬kçx|Úk¥qDbz?§º>ú¾:[èLÒÆ¬Z°X®:¹·ÚÇjßw5	¶Y¾0 ©Â­¯\$\0C¢dSg¸ë {@\n`	ÀÃüC ¢·»Mºµâ»²# t}xÎN÷º{ºÛ°)êûCÊFKZÞjÂ\0PFYBäpFk0<Ú>ÊD<JEg\rõ.2ü8éU@*Î5fkªÌJDìÈÉ4TDU76É/´è¯@·K+ÃöJ®ºÃÂí@Ó=ÜWIOD³85MNº\$Rô\0ø5¨\ràù_ðªìEñÏI«Ï³Nçl£Òåy\\ôÇqUÐQû ª\n@¨ÛºÃp¬¨PÛ±«7Ô½N\rýR{*qmÝ\$\0R×ÔÅåqÐÃ+U@ÞB¤çOf*CË¬ºMCä`_ èüò½ËµNêæTâ5Ù¦C×»© ¸à\\WÃe&_X_ØhåÂÆB3ÀÛ%ÜFW£û|GÞ'Å[¯ÅÀ°ÙÕV Ð#^\rç¦GR¾P±ÝFg¢ûî¯ÀYi û¥Çz\nâ¨Þ+ß^/¨¼¥½\\6èßb¼dmh×â@qíÕAhÖ),J­×WÇcm÷em]ÓeÏkZb0ßåþYñ]ymèfØe¹B;¹ÓêOÉÀwapDWûÉÜÓ{\0À-2/bN¬sÖ½Þ¾RaÏ®h&qt\n\"ÕiöRmühzÏeøàÜFS7µÐPPòä¤âÜ:B§âÕsm¶­Y düÞò7}3?*túòéÏlTÚ}~ä=cý¬ÖÞÇ	Ú3;T²LÞ5*	ñ~#µA¾sx-7÷f5`Ø#\"NÓb÷¯Gõ@Üeü[ïø¤Ìs¸-§M6§£qq he5\0Ò¢À±ú*àbøISÜÉÜFÎ®9}ýpÓ-øý`{ý±ÉkP0T<©Z9ä0<Õ\r­;!Ãgº\r\nKÔ\n\0Á°*½\nb7(À_¸@,îe2\rÀ]K+\0Éÿp C\\Ñ¢,0¬^îMÐ§º©@;X\rð?\$\rj+ö/´¬BöæP ½ù¨J{\"aÍ6ä¹|å£\n\0»à\\5Ð	156ÿ .Ý[ÂUØ¯\0dè²8Yç:!Ñ²=ºÀX.²uCªö!Sº¸opÓBÝüÛ7¸­Å¯¡Rh­\\hE=úy:< :u³ó2µ80si¦TsBÛ@\$ Íé@Çu	ÈQº¦.ôT0M\\/êd+Æ\n¡=Ô°dÅëA¢¸¢)\r@@Âh3Ù8.eZa|.â7YkÐcÀñ'D#¨Yò@Xq=M¡ï44B AM¤¯dU\"Hw4î(>¬8¨²ÃC¸?e_`ÐÅX:ÄA9Ã¸ôp«GÐäGy6½ÃFXr¡l÷1¡½Ø»B¢Ã9Rz©õhB{\0ëå^Ã-â0©%D5F\"\"àÚÜÊÂúiÄ`ËÙnAf¨ \"tDZ\"_àV\$ª!/Dáð¿µ´Ù¦¡ÌF,25ÉjTëáy\0N¼x\rçYl¦#ÆEq\nÍÈB2\nìà6·Ä4Ó×!/Â\nóQ¸½*®;)bR¸Z0\0ÄCDoË48À´µÐe\nã¦S%\\úPIk(0Áu/G²Æ¹¼\\Ë} 4FpGû_÷G?)gÈotº[vÖ\0°¸?bÀ;ªË`(Ûà¶NS)\nãx=èÐ+@êÜ7jú0,ð1Ãz­>0GcðãLVXô±ÛðÊ%ÀÁQ+øéoÆFõÈéÜ¶Ð>Q-ãcÚÇl¡³¤wàÌz5Gê@(hcÓHõÇr?Nbþ@É¨öÇø°îlx3U`rwª©ÔUÃÔôtØ8Ô=Àl#òõlÿä¨8¥E\"O6\nÂ1e£`\\hKfV/Ð·PaYKçOÌý éàx	Ojór7¥F;´êB»ê£íÌ¼>æÐ¦²V\rÄÄ|©'Jµz«¼#PBäY5\0NC¤^\n~LrRÔ[ÌRÃ¬ñgÀeZ\0x^»i<Qã/)Ó%@ÊfB²HfÊ{%Pà\"\"½ø@ªþ)òDE(iM2S*yòSÁ\"âñÊeÌ1«×\n4`Ê©>¦Q*¦Üy°n¥TäuÔâäÑ~%+W²XK£Q¡[ÊàlPYy#DÙ¬D<«FLú³Õ@Á6']Æû\rFÄ`±!%\n0cÐôÀË©%c8WrpG.TDo¾UL2Ø*é|\$¬:çXt5ÆXYâIp#ñ ²^\nê:#Dú@Ö1\r*ÈK7à@D\0¸CC£xBhÉEnKè,1\"õ*y[á#!ó×âÙ©Ê°l_¢/öxË\0àÉÚ5ÐZÇÿ4\0005JÆh\"2%Y¦a®a1SûO4Ê%niøPàß´qî_Ê½6¤Ä6ãñ\n@PjUú\0µ`r;¹H´¢:÷âð¶¨4 _w*ø@F@%¸s[d×eôÓbh¿\0âÉ±P\r \\iÀJ§99P9Î^s.âP29©\nNj#,ÀÚð5íM)ÿB¦³\ni%~¸§:9ÏÎX\reÐè8³îeÓ½+ïÀç9Áµâx*ÙW2áNbaçSàE¼ð2è\r³¬Åæpê	îÌ\\(/	LfàÊðòY§äX#8ZJÄHÊ+Pà-I1xÉ¢36àN¢w\rÓÁ[x3ý>\rTObá>sÉ²0êjA8;Ø#Ñ¤³àËÂjPdqRJÒ\"(x¡hµ*Äó	T¦éaVã®YÆÆë\$Àî7Z9Ä¸1ÌXJàéaïAOk8fDCð96@áÂéMê(H§ÍãÐBºà?i¼TAPÜ­^0´PÀµaf/ÏP0ÍMH)\"¡dU@¹r1\\Ñ\rÙoH| àÇÅÉh×8@?PZ,A>Â®ÊúE(&¿eÍ]åQ\$¸åÐªZ¡}a¿¤Ì:P¹w:Ä(è¢ZÊ!8°´«­àn@9\$Þ£(K\"þî%Å¦Í@2ç\$P°<Çº\0õç¦JtUXP\"-AðÔÉ¦YkÖ2óÑö4ÏC\n«\0¶½ 2ý~Äs_Éþ\0÷N5¼ÒèÑ/ ÓIÉ;Âi¸¦ÄÖefkF<ÇrEì,Î6%?¨Ij;'S)MÁ§4)ÍN.~èùéï\0JÓõ3©ãQzz	?õ§m1¡ªºq	cQHÜ¯yL\"OÏ0|c\$PÊ\"ÏðÅr0eLm#dÂpx.uA¨^éB76¬ÂqnÛ×BønæiZvR@ï)*ãqÆÿ)ôý7^Iµ¡jIÒS53¤éªê8Úº×Äx9	LqÐLÄOAÚA\0001¢ª%!1-â·WÒ%#!5+³¥®¡÷!vue(¨Bp¸\nKÅ/ÙÐã×Æ\\ÛiÏæ\0^À\$, |ZÒ(R+kà\n++ÚØVÊG¤{/ðTÍ<ÖM¦ÃªÃÂ¢©°\$ä{Ð´êÌyìVtä +¡SÑZÂ¤(u x\"HC·Jä? v8J÷PÂ Q\0ùV1Àá# '_á\nº4%Ç¥\nza_²ÃPDD{¬+\$SzÊÖ? l¬Ê«¨2z´!=ÁODÐëÞ[ñb\0éKÊÄ®Ítj+ª(Ò5è.âk£ZFÖ­=Aº®­U×£ð0©CÖËêÐÇ×~Æv.­8+Rx[¬ÂºËØ²Å¦·AuÞáI8ä¬3ß®Ä '	ðifÿà.JÊT¢ïÜX11¤ø&3ì6ª	òÐf@|O`b®g\0»>ÏÖxkkMDÖQ\n¬µñh§ÑøaÀy\$tÀÈ`\"Ì5¿ð²É56| `&´À:TÅA\n­Ë¥ ©pjRùÒI*çQ¦¨±£aNå®Zæ_Z qâ´©G9\0¿±ìå(Ä°=Jú dGíí9rÕê,QpØ+kZ¡\$×I+(Ç5Ì{2íÜ_mÇË8¬e¯Àénõ¦®\\6Å¶\${XÖK\$·£#kUÚí+vævE¯m×nØêvOè	!Adt£_/´(6õ1Ú­ñm[ã¦¼Üî\$øTÎ±hÖdÜÍXøðÀÖ/7ê ¡B¢ ä-\$À®UrÉ>b*)Ì¶ZÞXnbÄ\nªæESÎpoe¶p\\¸¢D ¨EÍ#Á,¤T~ê.Pèçm)aº=Ã³Rô·E¶<rõ6gHE-t»ë´ºRívðZtF+m[¸ÒîuÏ:à7w÷]îß,`Ýà-®w«Â9ÒðÑa¯ØãoÛËÅ[DM°ýÝÛïoeñrq6³HÒâÈ!*tehíø^èÊ¹IÉM×Ä\"DAåØ\$\0oHÌApúEÙZL¢}\"öö:ó|àå¯6è|=n¥ªëf¶cðÎÐv§J]A5cHø8óó¶-«¾âíOËVBV¥#Ð´ò`ÓÒ\rý -¼	ØKBdG^ô+ýÀ.·ðªElöË\$\$(qé0|9(h{\n4a7BÜP\0n@-hÉoWà¢¼ `Á+^jÄàdÎ9cPòq1ÚàH\"ÊÌæ\\ÊÐÁ±!µ°\".Ú¤¿¾Ôµ¢E<Õ/z}±(¶XD.6?Nxk*,)ËlÃW§9	j\\IæÎ(JÂøæ­@;Ü1¯àÀ\nIxÔÃ¯àh\rI[:ú¬ËH5/vBuPfuðÁ6«!4³xlâÌ2ÑÛ¼³^ ìÝg\0¤ÙË_qø°~4IÑO\"í-xðDºÓb\\\"Â-_£rÈ¤§G\"Àba{OªßRÚvÕrqK\0\$úmÓbÅÐðNAt@)Uð£°®Ðpjò£ývÈ¼,9ÊêÔ*T~ÝL§½dÑ»ðKgÀªPÉLýª¼Fû2ßúP*,uWÑû*Z¶ÏúUpUi\0d]Ïÿ\rGw\n@`Ð¸©k!qÖgäâ§EòÈHEà£@©ü]y2sÿÇe¿ò%Ã\"ÁÃ\\ÿO?üz+¶Ô4¢;uzÐ0d7±þFËäÊÐ<dÉö2uÎ9âÂW\$y9ý¨\0PÜdÀ,È-öÀ·[æÆÕñh|BQ §á5ÒÉåøØ©<r\0®t;2ûîf9Tª=@çs:äÖÉúñLávË÷©X@WoN Wú\$DD7øïeÛÖåÖ:(Ùvð·°/©Âr\rAÆ \nÅz3|¹Ùªz^ev/Ûy¡Ø^5Gµí0B¶ÿm`À¼vlànçn¾R>\nYTcÄÔb¬·P\\rPcßcx7c¥õD={*dr8å©ï©wëÎÜ=R6_ÆNy¥¾`&·á\$H°ÔGîkË4Y|»Ó/ËÙ³Æ@éåÒ¤àsÎ­ùÂÖ¬îçR\"yÖ[îzGo%GgÒýø{Ïº.ïÀ9rï£c¾\\UÎ5âîCÈé\"®)L×ËIßßk¿Ø\r¯üi(íÏ¹-´åú\\dÜ&rö|åfæÃîÐPÞeMéIÚbc0MléC¾°ÑOZ9&ôz¸µ¼HKXèÐé%·AauRÅ¤ñwéI=ºKYò´De¸üÍ\rÞ1¥D¼\"OmuLoÅC\\m!sËT\0ètº¥|¢uKµ)ôÈè²Z2¸XoM|Cå©Ðh/è¸ôâ!FÔ¨(íJñ\0HSz3ò´Ý(füJØ4Þ£Ý8cbÙ\$¤åÛ©Rê` iÞº.\0üä?àl[6ÇD¨ºHÖÃòR[Àe<q³®É;©êñú§ÔpKtf`/À»ÿÔ¤z\rÝ«-MièÍ¢LJ®,±ëJCÚÔÔ õ±f°Ó§[¯Öö¥Ú²,-YÚ]!y nTÅ×ÊBl·Þ\$zUcu¡\$¦j>72Õ,4.Ôæ!£íQ¶óD+ìFàó×ç¡Í[\n6ÁSo8ëM)®LeÙ´¯ª\r,ìe=»\rù¦ïÊÇ-h#ºM´*=O¶Õ\n¶#DÁ«êQ+aäO»-Ss1+[@(äÍá3|ìr¨Fæ=iJ¹£Ú2&Ñs\rOí\$!lÐ®DìÀäBtÉþiÀ¸Rq;Í@P¡¶äWP>?=rÓ×nCs,À;BàoêüMÄm¬}­æyÁM¤ðÿË¹-ÛðÝ>y,g6 qãñ\"¸q3|dîå;ìbîF7Ð	ë«@éö?Æv@	À¸ERUì û&I\\}-X º§gG4°]g6Ô>èë·\0Í:º³\"jWPä{±gÀO\\3ÌÝø\nðÒ\rÒ ,ßDß¢9Ç\0	àO}jCÚ·ÔLç|	H¼6¿ý°írTFÿö±­!·S+rìÔôÒc3ÁB@XdT6&÷ÁÇGÆgn8±Æz|)ÊVû^éÜ	©-\0î8ôº¶-«8b»7Ê-/@Ö>VÁ¬¶+uî¤\0B½zl%5×¶á¾OJîî!ÇáÖ²@øx¤hä7 ¼!18SR\0Q*o÷8¾n*?_è×Ø\nxÎìÄTÓ9¨þ¡åün®4,7oÞ^ÈN]´dºqá1#e¡(v¬²ìØ,½¸ms.8÷TÅWgB>`ÏLë@øÞÕ\\­yäÀn\nNq´ð1E=h4<Ó¾\$ÈsAñâu3ÊB±æ:§@áu2A=³Ñ\\B-uMÑÑDnWßdñVÖTlrRÀ²ëÒÜUgÈ\r¤§õÓ{Fë>AÇCð'§	Õå2´µ¥b¡bÍÐd§Y/|nr\rSäSk*øAO¦ÒR)Æ;sÁÔ\$w\$)EïAi¾é° Q 1ÝªëÆD3%âï ¦Ë*2rÛPLs,;Þug+th°bñ¶LóÈø%ýÅrC|Z®çáÇN*ÝÐ*5;Û¡ùU¯A²{Ð¤ô~yéiKX¢ÚDä#¢2CJYõ²Öù>zS²CU£õc§ûõêORÔ¾¡0)Ø+Òú:-IN¯£|eÏG;ÛbØÈ\$,p0ô_L.ÅÌ\$Äòv±ÑSÜF1&U°Ë(	nxt§¢ædï@0ù¨Êå±õä/wcñö_RÄ2·fÑ­eÄªè\0=õãsî¡ÁbsCO4×t~§h(¢o}OUòí®_hÔìpÔÔÑòëxí§×\$?!ÐBw³GÄ9ÊGìæ¸¦÷ÃíV?{Xîn×S~¦_1Ø÷Å¢qU{#x\nN \$8EÀqÝ~¥7 !Ài!ñ¥nöqi\r\$ékð¨£ôóºQ×ÃLd	ÒSÏÜtpA9öá/[úsß\0Ø6Vv,ÿõÔ±¥¡'Ý`ê?CshctH\"éK¾}n¦åó¥'®üë»^§3ª¢Ä_M£%Õoø¤éçVOÍÜÙ¿£«ÝEë\n£rpT¼Lð|`eñÑºÊõA²jä:d|[áÛâ½Jòúò4l N±u4]l´M³H&µ¤\$ä\0YRÀqzWÄ@Üÿ±¢íe3¡'t|·¿.ºÒÞ`(ñI<Ä2¤_5)%¢GÐÃm\0P\nïmèo@Í>½³xB\"ñÒEm|ù2\$},3LYXgo¡\$ß¶ <Óþ¿IE\"`×ú¨4ág©8^£]\n¡ð:øqVTÔ£Òm°mù7&ÒÄ¤ÃmÓÿ&À¨ÀQzÃÑ½·³Å±íHÔëöyOçfý«\rÙ£.¢¸¶®@¾JW&ßq×50	Ô5ÀîPËG\n½³í¸ÆF­{\0\r²m@ @ P  x4i4+@\0,Í\\C1Óè\nLêÅÓ>n\0ÿââ	 #ÇÞéÄÒ#@]/4JR IR²ïpè¹< Ç¯òaj?)Mví 2X|@v\0aºç\"­Ïkø¨é-ÂyA[|À7\r\$ìÀÚóZÇ­Ràtù>ªÏáCErL	öÆrÓOªe R/à¢J·ä~%Xo4áµdU\"¦QrºIêºQDåò¤ÐèQQM}àQ¿{)Ø©­\",fÐ_(,½6àQ+c¯®&SñùÝ~OípáCº¯ÍÚ©Äù´VþñÍñ@1è[Ù<H/Ê~Ô\0^C ³TÒõq_gPÁpeþ@BÁ×ÑÀéúÇë pÈ¿º)Xßã\0§õßñ{ü`\0vü§Ù³Q¨«Ò@~ ç¿¡ú¿íÅTÆWòûÿ¿ÎôÛü®úßìÿO÷>â8&ÞÿCLÝ¦ÿ(¯ó(ÿ§Ç2ûì\r%;àkæ4û¨_OÍ¾ø5³ö`@<ý²¼/Ü7Ì_	6'AY«ÿ\"¶ýaS°¿z£kpï¾®4+h@ZÿÃô 8>®ýâoßLÿû¿¥ÀjÌsùÀÿ\rJØm±À\0L\0cå?Â³ümªN(¯÷ ÚTp#à| >Àþ©A[?[ûÅ¿·Hkïü¨Â\n¡t¿p:G¬Ïõ>¾TÊ{*¨Ø-¡tÀÔÿÙPÀúXëj¥N4Ü¦0\n\$ø:H,¦H}°A¾©cè¦*ün?ãë¢\nþÊ;éO\0Zú°v©AB£é`o¡ª8_ÒR--nT#DIs1ÎÝ\0VPM\0Vÿr¬¿0\$Bi`TdX|e\08\\ð7),_º°K¿3(.cÁ\\°d2ÛÎçR<òu¨\\£	4ÐÂNÀ(|gïþ|¡N&,³ñðy¡ÜÍ(À²ß8bï:P½1Y'!Ä \0fxÒËë\0ä1àH[,½>çäé&æT°/a\rLCÁbE¹§	7çô¸ÖbðèkÈÒ|bíç0¹T\"þ.ÀàÅÙ5sËD¹Sgë8¹Rh*4¢}»¦<-9B\$¬ÓÞd9B\$åi«H8cj\\`ð_»æ	É#`ò¢hHÎ¨p \$0`1ïW\n%NZ\\#àÂbÙ¦P%m7l\"¢d¹ô\"P¼!Ø#/ÅÌ¤,Íª¿­J#0µcå]Âà-(ò¹6ð 7l~ð\r\0Bî0À:CAé\\pÏ[òÎåÐ(Ð®JGå0B\"8¼PB*%Ê<#BF72ÊB¤öéÂ5Bp	t&ð6\0bøñ4<\$í¶¥K¡V\0G	ómY ");}elseif($_GET["file"]=="jush.js"){header("Content-Type: text/javascript; charset=utf-8");echo
lzw_decompress("v0F£©ÌÐ==ÎFS	ÐÊ_6MÆ³èèr:ECI´Êo:CXc\ræØJ(:=E¦a28¡xð¸?Ä'i°SANNùðxsNBáÌVl0çS	ËUl(D|ÒçÊP¦À>Eã©¶yHchäÂ-3Ebå ¸b½ßpEÁpÿ9.Ì~\n?Kb±iw|È`Ç÷d.¼x8EN¦ã!Í23©á\rÑYÌèy6GFmY8o7\n\r³0¤÷\0DbcÓ!¾Q7Ð¨d8Áì~¬N)ùEÐ³`ôNsßð`ÆS)ÐOé·ç/º<xÆ9o»ÔåµÁì3n«®2»!r¼:;ã+Â9CÈ¨®Ã\n<ñ`Èó¯bè\\?`4\r#`È<¯BeãB#¤N Üã\r.D`¬«jê4ÿpéar°øã¢º÷>ò8Ó\$Éc ¾1Éc ¡c êÝê{n7ÀÃ¡AðNÊRLi\r1À¾ø!£(æjÂ´®+Âê62ÀXÊ8+Êâàä.\rÍÎôÎ!x¼åhù'ãâ6Sð\0RïÔôñOÒ\n¼1(W0ãÇ7që:NÃE:68n+äÕ´5_(®s \rãê/m6PÔ@ÃEQàÄ9\n¨V-Áó\"¦.:åJÏ8weÎq½|Ø³XÐ]µÝY XÁeåzWâü 7âûZ1íhQfÙãu£jÑ4Z{p\\AUËJ<õkáÁ@¼ÉÃà@}&L7U°wuYhÔ2¸È@ûu  Pà7ËAhèÌò°Þ3ÃêçXEÍZ]­lá@MplvÂ)æ ÁÁHWÔy>Y-øYè/«ªÁî hC [*ûFã­#~!Ð`ô\r#0PïCËf ·¶¡îÃ\\î¶É^Ã%B<\\½fÞ±ÅáÐÝã&/¦OðL\\jF¨jZ£1«\\:Æ´>N¹¯XaFÃAÀ³²ðÃØÍfh{\"s\n×64ÜøÒ¼?Ä8Ü^p\"ë°ñÈ¸\\Úe(¸PNµìq[g¸Árÿ&Â}PhÊà¡ÀWÙí*Þír_sËPhà¼àÐ\nÛËÃomõ¿¥ÃêÓ#§¡.Á\0@épdW ²\$Òº°QÛ½Tl0 ¾ÃHdHë)ÛÙÀ)PÓÜØHgàýUþªBèe\rt:Õ\0)\"Åtô,´ÛÇ[(DøO\nR8!Æ¬ÖðÜlAüV¨4 hà£Sq<à@}ÃëÊgK±]®àè]â=90°'åâøwA<ÐÑaÁ~òWæD|A´2ÓXÙU2àéyÅ=¡p)«\0P	sµn3îrf\0¢F·ºvÒÌG®ÁI@é%¤+Àö_I`¶ÌôÅ\r. N²ºËKI[ÊSJò©¾aUfSzû«M§ô%¬·\"Q|9¨Bc§aÁq\0©8#Ò<a³:z1Ufª·>îZ¹l¹ÓÀe5#U@iUGÂ©n¨%Ò°s¦Ë;gxL´pP?BçÊQ\\bÿé¾Q=7:¸¯Ý¡Qº\r:tì¥:y(Å ×\nÛd)¹ÐÒ\nÁX; ìêCaA¬\ráÝñP¨GHù!¡ ¢@È9\n\nAl~H úªV\nsªÉÕ«Æ¯ÕbBr£ªö­²ßû3\rP¿%¢Ñ\r}b/Î\$5§PëCä\"wÌB_çÉUÕgAtë¤ôå¤é^QÄåUÉÄÖjÁí Bvhì¡4)¹ã+ª)<j^<Lóà4U* õBg ëÐæè*nÊè-ÿÜõÓ	9O\$´Ø·zyM3\\9Üè.o¶Ìë¸E(iåàÄÓ7	tßé-&¢\nj!\rÀyyàD1gðÒö]«ÜyRÔ7\"ðæ§·~ÀíàÜ)TZ0E9MåYZtXe!Ýf@ç{È¬yl	8;¦R{ë8Ä®ÁeØ+ULñ'F²1ýøæ8PE5-	Ð_!Ô7ó [2JËÁ;HR²éÇ¹8pç²Ý@£0,Õ®psK0\r¿4¢\$sJ¾Ã4ÉDZ©ÕI¢'\$cLRMpY&ü½Íiçz3GÍzÒJ%ÁÌPÜ-[É/xç³T¾{p¶§zCÖvµ¥Ó:V'\\KJa¨ÃM&º°£Ó¾\"à²eo^Q+h^âÐiTð1ªORäl«,5[Ý\$¹·)¬ôjLÆU`£SË`Z^ð|r½=Ð÷nç»TU	1HykÇt+\0váD¿\r	<àÆìñjG­tÆ*3%kYÜ²T*Ý|\"CülhE§(È\rÃ8r×{Üñ0å²×þÙDÜ_.6Ð¸è;ãürBjO'Û¥¥Ï>\$¤Ô`^6Ì9#¸¨§æ4Xþ¥mh8:êûcþ0ø×;Ø/Ô·¿¹Ø;ä\\'( îtú'+òý¯Ì·°^]­±NÑv¹ç#Ç,ëvð×ÃOÏiÏ©>·Þ<SïA\\\\îµü!Ø3*tl`÷u\0p'è7Pà9·bs{Àv®{·ü7\"{ÛÆrîaÖ(¿^æ¼ÝE÷úÿë¹gÒÜ/¡øUÄ9g¶î÷/ÈÔ`Ä\nL\n)À(Aúað\" çØ	Á&PøÂ@O\nå¸«0(M&©FJ'Ú! 0<ïHëîÂçÆù¥*Ì|ìÆ*çOZím*n/bî/ö®Ô¹.ìâ©o\0ÎÊdnÎ)ùi:RÎëP2êmµ\0/vìOX÷ðøFÊ³Ïîè®\"ñ®êöî¸÷0õ0ö¬©í0bËÐgjðð\$ñné0}°	î@ø=MÆ0nîP/pæotì÷°¨ð.ÌÌ½g\0Ð)o\n0È÷\rF¶é b¾i¶Ão}\n°Ì¯	NQ°'ðxòFaÐJîÎôLõéðÐàÆ\rÀÍ\rÖö0Åñ'ð¬Éd	oepÝ°4DÐÜÊ¦q(~ÀÌ ê\rE°ÛprùQVFHl£Kj¦¿äN&­j!ÍH`_bh\r1 ºn!ÍÉ­z°¡ð¥Í\\«¬\ríÃ`V_kÚÃ\"\\×'V«\0Ê¾`ACúÀ±Ï¦VÆ`\r%¢ÂÅì¦\rñâk@NÀ°üBñí¯ ·!È\n\0Z6°\$d ,%à%laíH×\n#¢S\$!\$@¶Ý2±I\$r{!±°J2HàZM\\ÉÇhb,'||cj~gÐr`¼Ä¼º\$ºÄÂ+êA1ðEÇÀÙ <ÊL¨Ñ\$âY%-FDªdLç³ ª\n@bVfè¾;2_(ëôLÄÐ¿Â²<%@Ú,\"êdÄÀNerô\0æ`Ä¤Z¾4Å'ld9-ò#`äóÅà¶Öãj6ëÆ£ãv ¶àNÕÍf Ö@Ü&B\$å¶(ðZ&ßó278I à¿àP\rk\\§2`¶\rdLb@Eö2`P( B'ã¶º0²& ô{Â§:®ªdBå1ò^Ø*\r\0c<K|Ý5sZ¾`ºÀÀO3ê5=@å5ÀC>@ÂW*	=\0N<g¿6s67Sm7u?	{<&LÂ.3~DÄê\rÅ¯x¹í),rîinÅ/ åO\0o{0kÎ]3>m1\0I@Ô9T34+Ô@eGFMCÉ\rE3ËEtm!Û#1ÁD @H(Ón ÃÆ<g,V`R]@úÂÇÉ3Cr7s~ÅGIói@\0vÂÓ5\rVß'¬ ¤ Î£PÀÔ\râ\$<bÐ%(DdPWÄîÐÌbØfO æx\0è} Üâlb &vj4µLS¼¨Ö´Ô¶5&dsF Mó4ÌÓ\".HËM0ó1uL³\"ÂÂ/J`ò{Çþ§ÊxÇYu*\"U.I53Q­3Qô»Jg 5sàú&jÑÕuÙ­ÐªGQMTmGBtl-cù*±þ\r«Z7Ôõó*hs/RUV·ðôªBNË¸ÃóãêÔài¨Lk÷.©´Ätì é¾©rYiÕé-Sµ3Í\\TëOM^­G>ZQjÔ\"¤¬iÖMsSãS\$Ib	f²âÑuæ¦´å:êSB|i¢ YÂ¦à8	vÊ#éDª4`.Ë^óHÅM_Õ¼uÀUÊz`ZJ	eçºÝ@Ceíëa\"mób6Ô¯JRÂÖT?Ô£XMZÜÍÐÍòpèÒ¶ªQv¯jÿjV¶{¶¼ÅC\rµÕ7TÊª úí5{Pö¿]\rÓ?QàAAÀèÍ2ñ¾ V)Ji£Ü-N99fl JmÍò;u¨@<FþÑ ¾ejÒÄ¦I<+CW@ðçÀ¿ZlÑ1É<2ÅiFý7`KG~L&+NàYtWHé£w	ÖòlÒs'gÉãq+Lézbiz«ÆÊÅ¢Ð.ÐÇzW²Ç ùzdW¦Û÷¹(y)vÝE4,\0Ô\"d¢¤\$Bã{²!)1U5bp#Å}m=×È@wÄ	P\0ä\rì¢·`O|ëÆö	ÉüÅõûYôæJÕöE×ÙOu_§\n`F`È}MÂ.#1á¬fì*´Õ¡µ§  ¿zàucû³ xfÓ8kZR¯s2Ê-§Z2­+Ê·¯(åsUõcDòÑ·ÊìÝX!àÍuø&-vPÐØ±\0'LïX øLÃ¹o	Ýô>¸ÕÓ\r@ÙPõ\rxF×üEÌÈ­ï%Àãì®ü=5NÖ¸?7ùNËÃ©w`ØhX«98 Ìø¯q¬£zãÏd%6ÌtÍ/ä¬ëLúÍl¾Ê,ÜKaN~ÏÀÛìú,ÿ'íÇM\rf9£w!x÷x[ÏØG8;xAù-IÌ&5\$D\$ö¼³%ØxÑ¬ÁÈÂ´ÀÂ]¤õ&o-39ÖLù½zü§y6¹;u¹zZ èÑ8ÿ_Éx\0D?X7«y±OY.#38 ÇeQ¨=Ø*Gwm ³ÚYù ÀÚ]YOY¨F¨íÙ)z#\$e)/z?£z;Ù¬^ÛúFÒZg¤ù Ì÷¥§`^Úe¡­¦º#§Øñ©ú?¸e£M£Ú3uÌå0¹>Ê\"?ö@×Xv\"ç¹¬¦*Ô¢\r6v~ÃOV~&×¨^gü ÄÙ'Îf6:-Z~¹O6;zx²;&!Û+{9M³Ù³d¬ \r,9Öí°ä·WÂÆÝ­:ê\rúÙùã@ç+¢·]Ì-[gÛ[s¶[iÙiÈqyéxé+|7Í{7Ë|w³}¢£EûW°Wk¸|JØ¶åxm¸q xwyj»#³e¼ø(²©¸ÀßÃ¾ò³ {èßÚ y »M»¸´@«æÉ°Y(gÍ-ÿ©º©äí¡¡ØJ(¥ü@ó;yÂ#S¼µYÈp@Ï%èsúo9;°ê¿ôõ¤¹+¯Ú	¥;«ÁúZNÙ¯Âº§ k¼V§·u[ñ¼x|q¤ON?ÉÕ	`u¡6|­|X¹¤­Ø³|Oìx!ë:¨ÏY]¬¹c¬À\r¹hÍ9nÎÁ¬¬ëÏ8'ùêà Æ\rS.1¿¢USÈ¸¼XÉ+ËÉz]ÉµÊ¤?©ÊÀCË\r×Ë\\º­¹ø\$Ï`ùÌ)UÌ|Ë¤|Ñ¨x'ÕØÌäÊ<àÌeÎ|êÍ³çâÌéLïÏÝMÎy(Û§ÐlÐº¤O]{Ñ¾×FD®ÕÙ}¡yuÑÄß,XL\\ÆxÆÈ;U×ÉWtvÄ\\OxWJ9È×R5·WiMi[Kf(\0æ¾dÄÒè¿©´\rìMÄáÈÙ7¿;ÈÃÆóÒñçÓ6KÊ¦Iª\rÄÜÃxv\r²V3ÕÛßÉ±.ÌàRùÂþÉá|á¾^2^0ß¾\$ QÍä[ã¿D÷áÜ£å>1'^X~t1\"6Lþ+þ¾AàeáæÞåIç~åâ³â³@ßÕ­õpM>Óm<´ÒSKÊç-HÉÀ¼T76ÙSMfg¨=»ÅGPÊ°PÖ\r¸é>Íö¾¡¥2Sb\$C[Ø×ï(Ä)Þ%Q#G`uð°ÇGwp\rkÞKezhjÓzi(ôèrO«óÄÞÓþØT=·7³òî~ÿ4\"ef~ídôíVÿZ÷U-ëb'VµJ¹Z7ÛöÂ)T£8.<¿RMÿ\$ôÛØ'ßbyï\n5øÝõ_àwñÎ°íUð`eiÞ¿Jb©gðuSÍë?Íå`öáì+¾Ïï Mïgè7`ùïí\0¢_Ô-ûõ_÷?õF°\0õ¸Xå´[²¯J8&~D#Áö{PØô4Ü½ù\"\0ÌÀý§ý@Ò¥\0F ?* ^ñï¹å¯wëÐ:ð¾uàÏ3xKÍ^ów¼¨ß¯y[Ô(æµ#¦/zr_g·æ?¾\0?1wMR&M¿ù?¬StT]Ý´Gõ:I·à¢÷)©Bï vô§½1ç<ôtÈâ6½:W{Àôx:=ÈîÞóø:Â!!\0xÕ£÷q&áè0}z\"]ÄÞoz¥ÒjÃw×ßÊÚÁ6¸ÒJ¢PÛ[\\ }ûª`S\0à¤qHMë/7BP°ÂÄ]FTã8S5±/IÑ\r\n îO¯0aQ\n >Ã2­j;=Ú¬ÛdA=­p£VL)Xõ\nÂ¦`e\$TÆ¦QJÍó®ælJïÔîÑyIÞ	ä:ÑÄÄBùbPÀûZÍ¸n«ª°ÕU;>_Ñ\n	¾õëÐÌ`ÔuMòÂÖm³ÕóÂLwúB\0\\b8¢MÜ[z&©1ý\0ô	¡\rTÖ× +\\»3ÀPlb4-)%Wd#\nÈårÞåMX\"Ï¡ä(Ei11(b`@fÒ´­SÒójåDbf£}rï¾ýDR1´bÓAÛïIy\"µWvàÁgC¸IÄJ8z\"P\\i¥\\m~ZR¹¢vî1ZB5IÃi@x·°-uM\njKÕU°h\$oJÏ¤!ÈL\"#p7\0´ P\0D÷\$	 GK4eÔÐ\$\nGä?ù3£EAJF4àIp\0«×F4±²<f@ %q¸<kãw	àLOp\0xÓÇ(	G>ð@¡ØçÆÆ9\0TÀìGB7 - øâG:<Q #Ã¨ÓÇ´û1Ï&tz£á0*J=à'J>ØßÇ8q¡Ð¥ªà	OÀ¢XôF´àQ,ÀÊÐ\"9®pä*ð66A'ý,yIFR³TÏý\"÷HÀR!´j#kyFÀàe¬z£ëéÈðG\0p£aJ`C÷iù@T÷|\nIx£K\"­´*¨Tk\$c³òÆaAh! \"úE\0OdÄSxò\0T	ö\0à!FÜ\nU|#S&		IvL\"ä\$hÐÈÞEAïN\$%%ù/\nP1²{¤ï) <ð L å-R1¤â6¶<@O*\0J@q¹Ôª#É@Çµ0\$t|]ã`»¡ÄA]èÍìPáCÀp\\pÒ¤\0ÒÅ7°ÄÖ@9©bmr¶oÛC+Ù]¥JrÔfü¶\rì)d¤Ñ­^hßI\\Î. gÊ>¥Í×8ÞÀ'HÀfrJÒ[rçoã¥¯.¹v½ï##yR·+©yËÖ^òùF\0á±]!ÉÒÞ++Ù_Ë,©\0<@M-¤2WòâÙR,ce2Ä*@\0êP Âc°a0Ç\\PÁO ø`I_2Qs\$´w£¿=:Îz\0)Ì`ÌhÂÁç¢\nJ@@Ê«\0ø 6qT¯å4J%N-ºm¤Äåã.É%*cnäËNç6\"\rÍ¸òèûfÒAµÁpõMÛI7\0MÈ>lO4ÅS	7cÍì\"ìß§\0å6îpsÄÝåy.´ã	ò¦ñRKðPAo1FÂtIÄb*ÉÁ<©ý@¾7ÐËp,ï0NÅ÷: ¨N²m ,xO%è!Úv³¨ gz(ÐM´óÀIÃà	à~yËöh\0U:éØOZyA8<2§²ð¸ÊusÞ~lòÆÎEðO0±0]'>¡ÝÉ:ÜêÅ;°/ÂwÒôäì'~3GÎ~Ó­äþ§c.	þòvT\0cØt'Ó;P²\$À\$øÐ-s³òe|º!@dÐObwÓæc¢õ'Ó@`P\"xôµèÀ0O5´/|ãU{:b©R\"û0ÑkÐâ`BD\nkPãc©á4ä^ p6S`Ü\$ëf;Î7µ?lsÅÀßgDÊ'4Xja	AE%	86b¡:qr\r±]C8ÊcÀF\n'Ñf_9Ã%(¦*~ãiSèÛÉ@(85 TË[þJÚ4Il=°QÜ\$dÀ®hä@D	-Ù!ü_]ÉÚHÆk6:·Úò\\M-ÌØðò£\rFJ>\n.qeGú5QZ´' É¢½Û0îzPà#Å¤øöÖéràÒít½ÒÏËþ<QT¸£3D\\¹ÄÓpOE¦%)77Wt[ºô@¼\$F)½5qG0«-ÑW´v¢`è°*)RrÕ¨=9qE*K\$g	íA!åPjBT:Kû§!×÷H R0?6¤yA)B@:Q8B+J5U]`Ò¬:£ðå*%Ip9Ìÿ`KcQúQ.B±LtbªyJñEêTé¥õ7ÎöAmÓä¢Ku:ðSji 5.q%LiFºTr¦Ài©ÕKÒ¨z55T%UUÚIÕ¦µÕY\"\nSÕmÑÄx¨½Ch÷NZ¶UZÄ( Bêô\$YËV²ãu@è»¯¢ª|	\$\0ÿ\0 oZw2Òx2ûk\$Á*I6IÒn ¡I,ÆQU4ü\n¢).øQôÖaIá]À èLâh\"øf¢Ó>:Z¥>L¡`nØ¶Õì7VLZue¨ëXúèºB¿¬¥Bº¡Z`;®øJ]òÑäS8¼«f \nÚ¶#\$ùjM(¹Þ¡¬a­Gí§Ì+Aý!èxL/\0)	Cö\nñW@é4ºáÛ© ÔRZ®â =Çî8`²8~âhÀìP °\r	°ìD-FyX°+Êf°QSj+Xó|È9-øs¬xØüê+VÉcbpì¿o6HÐq °³ªÈ@.l 8g½YMÖWMPÀªU¡·YLß3PaèH2Ð9©:¶a²`¬Æd\0à&ê²YìÞY0Ù¡¶S-%;/TÝBS³PÔ%fØÚý @ßFí¬(´Ö*Ñq +[Z:ÒQY\0Þ´ëJUYÖ/ý¦pkzÈò,´ðªjÚê¥W°×´e©JµFèýVBIµ\r£ÆpFNÙÖ¶*Õ¨Í3kÚ0§D{Ôø`qÒ²Bqµe¥DcÚÚÔVÃE©¬nñ×äFG E>jîèÐú0g´a|¡Shì7uÂÝ\$ì;aô7&¡ë°R[WXÊØ(qÖ#¬P¹Æä×Ýc8!°H¸àØVX§Ä­jøÊZô¡¥°Q,DUaQ±X0ÕÕ¨ÀÝËGbÁÜlBt9-oZüL÷£¥Â­åpËx6&¯¯MyÔÏsÒ¿èð\"ÕÍèRIWU`c÷°à}l<|Â~Äw\"·ðvI%r+Rà¶\n\\ØùÃÑ][Ñ6&Á¸ÝÈ­ÃaÓºìÅj¹(ÚðTÑÀ·C'´ '%de,È\nFCÅÑe9C¹NäÐ-6UeÈµýCX¶ÐV±¹ýÜ+ÔR+ºØË3BÜÚJð¢è±æT2 ]ì\0PèaÇt29Ï×(i#aÆ®1\"S:ö· ÖoF)kÙfôòÄÐª\0ÎÓ¿þÕ,ËÕwêJ@ìÖVòµéq.e}KmZúÛïå¹XnZ{G-»÷ÕZQº¯Ç}Å×¶û6É¸ðµÄ_ØÕà\nÖ@7ß` ÕïC\0]_ ©Êµù¬«ï»}ûGÁWW: fCYk+éÚbÛ¶·¦µ2S,	ÚÞ9\0ï¯+þWÄZ!¯eþ°2ûôàí²k.OcÖ(vÌ®8DeG`ÛÂöL±õ,dË\"CÊÈÖB-Ä°(þp÷íÓp±=àÙü¶!ýkØÒÄ¼ï}(ýÑÊBkr_RîÜ¼08a%ÛL	\0éÀñb¥²ñÅþ@×\"ÑÏr,µ0TÛrV>ÚÈQÐ\"rÞ÷P&3báP²æ- xÒ±uW~\"ÿ*èNâh%7²µþK¡Y^A÷®úÊCèþ»p£áî\0ð..`cÅæ+ÏâGJ£¤¸H¿À®E¤¾l@|I#AcâÿD|+<[c2Ü+*WS<ràãg¸ÛÅ}>iÝ!`f8ñ(c¦èÉQý=fñ\nç2Ñc£h4+q8\na·RãBÜ|°R×ê¿Ýmµ\\qÚõgXÀ Ï0äXä«`nîFîìO pÈîHòCjd¡fµßEuDVbJÉ¦¿å:±ï\\¤!mÉ±?,TIaØaT.L],J??ÏFMct!aÙ§RêFGð!¹Aõ»rr-pX·\r»òC^À7áð&ãRé\0ÎÑf²*àA\nõÕHáã¤yîY=Çúèl<¹AÄ_¹è	+ÎtAú\0B<Ay(fy1Îc§O;pèÅá¦`ç4Ð¡Mìà*îfê 5fvy {?©àË:yøÑ^câÍu'8\0±¼Ó±?«gÓ 8BÎ&p9ÖO\"zÇõrs0ºæB!uÍ3f{×\0£:Á\n@\0ÜÀ£pÙÆ6þv.;àú©Êb«Æ«:J>Ëé-ÃBÏhkR`-ÜñÎðawæxEj©÷Ár8¸\0\\Áïô\\¸Uhm ý(mÕH3Ì´í§SÁæq\0ùNVh³Hy	»5ãMÍe\\g½\nçIP:Sj¦Û¡Ù¶è<¯Ñxó&LÚ¿;nfÍ¶cóq¦\$fð&lïÍþi³àç0%yÎ¾tì/¹÷gUÌ³¬dï\0e:ÃÌhïZ	Ð^@ç ý1Ïm#ÑNów@ßOððzGÎ\$ò¨¦m6é6}ÙÒÒX'¥I×i\\QºY¸4k-.è:yzÑÈÝH¿¦]ææxåGÏÖ3ü¿M\0£@z7¢³6¦-DO34Þ\0ÎÄùÎ°t\"Î\"vC\"JfÏRÊÔúku3MÎæ~ú¤Ó5V àj/3úÓ@gG}Dé¾ºBÓNq´Ù=]\$é¿IõÓ3¨x=_jXÙ¨fk(C]^jÙMÁÍF«ÕÕ¡àÏ£CzÈÒVÁ=]&\r´A<	æµÂÀÜãç6ÙÔ®¶×´Ý`jk7:gÍî4Õ®áëYZqÖftu|hÈZÒÒ6µ­iã°0 ?éõéª­{-7_:°×ÞtÑ¯íck`YÍØ&´éIõlP`:íô j­{hì=Ðf	àÃ[by¢ÊoÐB°RS¼B6°À^@'4æø1UÛDq}ìÃNÚ(Xô6j}¬cà{@8ãòð,À	ÏPFCàðBà\$mv¨Pæ\"ºÛLöÕCS³]ÝàEÙÞÏlUÑfíwh{o(ä)è\0@*a1GÄ ( D4-cØóP8£N|RâVM¸°×n8G`e}!}¥Çp»Üòý@_¸ÍÑnCtÂ9Ñ\0]»u±î¯s»Ý~èr§»#Cn p;·%>wu¸ÞnÃwû¤Ýê.âà[ÇÝhT÷{¸Ýå¼	ç¨Ë·JðÔÆiJÊ6æO¾=¡ûæßE÷Ù´ImÛïÚV'É¿@â&{ªòö¯µ;íop;^Ø6Å¶@2ç¯lûÔÞNï·ºMÉ¿r_Ü°ËÃ´` ì( yß6ç7¹ýëîÇ7/Ápðe>|ßà	ø=½]Ðocûá&åxNm£ç»¬ào·GÃN	p»x¨Ã½Ýðy\\3àøÂ'ÖI`râG÷]Ä¾ñ7\\7Ú49¡]Å^p{<Zá·¸q4uÎ|ÕÛQÛàõpýi\$¶@oxñ_<Àæ9pBU\"\0005 iä×»¸Cûp´\nôi@[ãÆ4¼jÐ6bæP\0&F2~Àù£¼ïU&}¾½¿É	ÌDa<æzx¶k£=ùñ°r3éË(l_FeF4ä1K	\\Óldî	ä1H\r½ùp!%bGæXfÌÀ'\0ÈØ	'6Àps_á\$?0\0~p(H\n1W:9ÕÍ¢¯`æ:hÇBègBk©ÆpÄÆót¼ìEBI@<ò%Ã¸Àù` êyd\\Y@DP?|+!áWÀø.:Lev,Ð>qóAÈçº:îbYé@8d>r/)ÂBç4ÀÐÎ(·`|é¸:t±!«Á¨?<¯@ø«/¥ S¯P\0Âà>\\æâ |é3ï:VÑuw¥ëçx°(®²4ÇZjD^´¥¦Lý'¼ìÄC[×'ú°§®éjÂº[ E¸ó uã°{KZ[s6S1Ìz%1õc£B4B\n3M`0§;çòÌÂ3Ð.&?¡ê!YAÀI,)ðålW['ÆÊIÂTjè>F©¼÷S§ BÐ±Pá»caþÇuï¢NÝÏÀøHÔ	LSôî0ÕY`ÂÆÈ\"il\rçB²ëã/ôãø%PÏÝNGô0JÆX\n?aë!Ï3@MæF&Ã³Öþ¿,°\"îèlbô:KJ\rï`k_êb÷üAáÙÄ¯Ìü1ÑI,ÅÝîü;B,×:ó¾ìY%¼J #v'{ßÑÀã	wx:\ni°¶³}cÀ°eN®Ñï`!wÆ\0ÄBRU#ØSý!à<`&v¬<¾&íqOÒ+Î£¥sfL9QÒBÊÉóäbÓà_+ï«*Su>%0©8@l±?L1po.ÄC&½íÉ BÀÊqh¦ó­Áz\0±`1á_9ð\"è!\$ø¶~~-±.¼*3r?øÃ²Àds\0ÌõÈ>z\nÈ\00 1Ä~ôJð³ðú|SÞô k7gé\0úKÔ d¶ÙaÉîPgº%ãwDôêzmÒûÈõ·)¿ñjÛ×Âÿ`k»ÒQà^ÃÎ1üº+Îå>/wbüGwOkÃÞÓ_Ù'¬-CJ¸å7&¨¢ºðEñ\0L\r>!ÏqÌîÒ7ÝÁ­õo`9O`àö+!}÷P~EåNÈcöQ)ìá#ûï#åòìÌÑøÀ¡¯èJñÄz_u{³ÛK%\0=óáOX«ß¶Cù>\n²|wá?ÆFÅêÕaÏ©UÙåÖb	N¥YïÉh½»é/úû)ÞGÎ2ü¢K|ã±y/\0éä¿Z{éßP÷YG¤;õ?Z}T!Þ0Õ=mN¯«úÃfØ\"%4aö\"!Þúºµ\0çõï©}»î[òçÜ¾³ëbU}»ÚmõÖ2± ö/tþî%#.ÑØÄÿseBÿp&}[ËÇ7ã<aùKýïñ8æúP\0ó¡g¼ò?ù,Ö\0ßßr, >¿ýWÓþïù/Öþ[qýk~®CÓ4ÛûG¯:X÷Gúr\0Ééâ¯÷L%VFLUc¯Þä¢þHÿybPÚ'#ÿ×	\0Ð¿ýÏì¹`9Ø9¿~ïò_¼¬0qä5K-ÙE0àbôÏ­ü¡t`lmêíËÿbàÆ; ,= 'S.bÊçS¾øCcêëÊAR,íÆX@à'8Z0&ìXnc<<È£ð3\0(ü+*À3·@&\r¸+Ð@h, öò\$O¸\0Åèt+>¬¢bªÊ°\r£><]#õ%;Nìsó®Å¢Êð*»ïcû0-@®ªLì >½Yp#Ð-f0îÃÊ±aª,>»Ü`ÆÅàPà:9o·ð°ov¹R)e\0Ú¢\\²°Áµ\nr{Ã®XÒøÎ:A*ÛÇ.Dõº7»¼ò#,ûN¸\rEÔ÷hQK2»Ý©¥½zÀ>P@°°¦	T<ÒÊ=¡:òÀ°XÁGJ<°GAfõ&×A^pã`©ÀÐ{ûÔ0`¼:ûð);U !Ðe\0î£½Ïcp\r³ ¾:(ø@%2	S¯\$Y«Ý3é¯hCÖì:O#ÏÁLóï/éç¬k,¯Kåoo7¥BD0{¡jó ìj&X2Ú«{¯}RÏx¤ÂvÁä÷Ø£À9Aë¸¶¾0;0õáà-5/<Üç° ¾NÜ8E¯Ç	+ãÐÂPd¡;ªÃÀ*n¼&²8/jX°\r>	PÏW>KàO¢VÄ/¬U\n<°¥\0Ù\nIk@ºã¦[àÈÏ¦Â²#?Ùã%ñèË.\0001\0ø¡kè`1T· ©¾ëÉl¼À£îÅp®¢°Á¤³¬³< .£>íØ5Ð\0ä»	O¬>k@Bn¾<\"i%>ºzÄçñáºÇ3ÙP!ð\rÀ\"¬ã¬\r >adàöó¢U?ÚÇ3P×Áj3£ä°>;Óä¡¿>t6Ë2ä[ÂðÞ¾M\r >°º\0äìP®·Bè«Oe*Rn¬§y;« 8\0ÈËÕoæ½0ýÓøiÂøþ3Ê2@Êýà£î¯?xô[÷ÛÃLÿa¯w\ns÷A²¿x\r[Ñaª6Âclc=¶Ê¼X0§z/>+ªøW[´o2Âø)eî2þHQPéDYzG4#YDöºp)	ºHúp&â4*@/:	áT	­¦aH5ëh.A>ï`;.­îYÁa	Âòút/ =3°BnhD?(\n!ÄBús\0ØÌDÑ&DJ)\0jÅQÄyhDh(ôK/!Ð>®h,=Ûõ±ãtJ+¡Sõ±,\"M¸Ä¿´NÑ1¿[;øÐ¢¼+õ±#<ìI¤ZÄP)ÄáLJñDéìP1\$Äîõ¼Q>dO¼vé#/mh8881N:øZ0ZÁèT BóCÇq3%°¤@¡\0Øï\"ñXD	à3\0!\\ì8#h¼vìibÏT!dªÎüV\\2óÀSëÅÅ\nA+Í½pxÈiD(ìº(à<*öÚ+ÅÕE·ÌT®¾ BèS·CÈ¿T´æÙÄ eAï\"á|©u¼v8ÄT\0002@8D^ooø÷|Nùô¥ÊJ8[¬Ï3ÄÂõîJz×³WL\0¶\0È8×:y,Ï6&@À E£Ê¯Ýh;¼!f¼.Bþ;:ÃÊÎ[Z3¥Â«ðn»ìëÈ­éA¨ÓqP4,óºXc8^»Ä`×ôl.®üº¢S±hÞ°O+ª%P#Î¡\n?ÛÜIB½ÊeËO\\]ÎÂ6ö#û¦Û½Ø(!c) Nõ¸ºÑ?EØB##D íDdo½åPAª\0:ÜnÂÆ`  ÚèQ³>!\r6¨\0V%cbHF×)¤m&\0B¨2Ií5Ù#]úØD>¬ì3<\n:MLðÉ9CñÊ0ãë\0¨(á©H\nþ¦ºM\"GR\n@éø`[Ãó\ni*\0ð)üìu©)¤«Hp\0N	À\"®N:9qÛ.\r!´JÖÔ{,Û'æÙ4BúÇlqÅ¨Xc«Â4ßN1É¨5«WmÇ3\nÁF`­'Òxà&>z>N¬\$4?óÃïÂ(\nì¨>à	ëÏµPÔ!CqÍ¼p­qGLqqöG²yÍH.«^à\0zÕ\$AT9FsÐ¢D{ía§øcc_GÈz)ó³ Ü}QÆÅhóÌHBÖ¸<y!L­Û!\\²î ø'H(ä-µ\"in]Ä³­\\¨!Ú`MH,gÈí»*ÒKfë*\0ò>Â6¶à6ÈÖ2óhJæ7Ù{nqÂ8àßôÉHÕ#cHã#\r:¶7Ê8àÜZ²ZrD£þß²`rG\0äl\n®Ii\0<±äãô\0Lg~¨ÃE¬Û\$¹ÒP\$@ÒPÆ¼T03ÉHGH±lÉQ%*\"N?ë%	Î\nñCrWÉC\$¬pñ%uR`ÀË%³òR\$<`ÖIfxª¯÷\$/\$¥\$O(Ë\0æË\0RY*Ù/	ê\rÜC9ï&hhá=IÓ'\$RRIÇ'\\a=EÔòuÂ·'ÌwIå'Tüÿ©¾ãK9%d¢´·!üÀÊÊÀÒjì¡íÓÊ&ÐævÌ²\\=<,Eù`ÛYÁò\\²¤*b0>²r®à,dpdÌ0DD Ì`â,T ­1Ý% P¤/ø\ròb¹(£õJÑèÍîT0ò``Æ¾ÞèíóJt©©Ê((dÇÊªáh+ <É+H%iÈô²#´`­ ÚÊÑ'ô£B>t¯JZ\\`<Jç+hR·ÊÔ8îàhR±,J]gò¨Iäè0\n%J¹*ÐY²¯£JwD°&ÊD±®ÉÐªR§K\"ß1Qò¨Ë ²AJKC,ä´mV»²ÊÙ-±òÏKI*±r¨\0ÇL³\"ÆKb(üªóJ:qKr·dùÊ-)ÁË#Ô¸²Þ¸[ºA»@.[Ò¨Ê¼ß4º¡¯.1ò®J½.Ì®¦u#JÁg\0Æãò§£<Ë&ðK¤+½	M?Í/d£Ê%'/¿2YÈä>­\$Í¬lº\0©+øÁ}-tºÍ*êRä\$ßòÌK».´Á­óJHûÊ2\r¿B½(PÍÓÌ6\"ünf\0#Ð ®Í%\$ÄÊ[\nÐnoLJ°ÅÓÂe'<¯ó1KíÁyÌY1¤Çs¥0À&zLf#üÆ³/%y-²Ë£3-ÂÍK£L¶ÎÉ×0³ë¸[,¤ËÌµ,±«§0±Ó(.DÀ¡@ÏÁ2ïL+.|£÷¤É2è(³L¥*´¹S:\0Ù3´ÌíóG3lÌÁaËl³@L³3z4­Ç½%ÌÍLÝ3»³¼!033=Lù4|È¡à+\"°Êé4´Ëå7Ë,\$¬SPM\\±Î?JYÌ¡¹½+(Âa=K¨ì4¤³CÌ¤<Ð=\$,»³UJ]5h³W &tÖI%é5¬Ò³\\M38g¢Í5HN?W1H±^ÊÙÔ¸YÍØ Í.N3M4Ã³`i/P7ÖdM>d¯/LRÎÜâ=K60>¯I\0[ðõ\0ßÍ\r2ôÔòZ@Ï1Û2ÿ°7È9äFG+ä¯ÒÅ\r)àhQtL}8\$ÊBeC#Ár*HÈÛ«-Hý/ØËÒ6Èß\$øRC9ÂØ¨!Å7ük/PË0Xr5¡3D¼<TÁÔq¯Kô©³nÎH§<µFÿ:1SLÎrÀ%(ÿu)¸Xr1ÑnJÃIÌ´S£\$\$é.Î9Ôé²IÎÒ3 ¨LÃl¯Î9äÅCN #Ô¡ó\$µ/ÔésÉ9«@6Êt²®Nñ9¼´·NÉ:¹Â¡7ó Ó¬Í:DáÓÁM)<#ÓÃM}+ñ2ÎNþñ²O&ð¢JNy*òòÙ¸[;ñóÎO\"mÚÄóÅMõ<c Â´°±8¬K²,´ÓÇN£=07s×JE=Tá³ÆO<Ôô³£Jé=DÓ:ÏC<ÌàË=äèó®KÊ»Ì³ÈL3¬÷­LTÐ3ÊS,.¨ÿÏq-ñsç7Í>?ó¼7O;Ü `ùOA9´óñÏ»\$üÁOÑ;ìý`9ÎnÇIAxpÜöE=O¹<ü²5ÏÎý2¸O?d´´`NòiOÿ>þ3½P	?¤òÔOmúSðMôË¬·=¹(ãdã¤AÈ­9\0í#üä²@­9DÁÉ&Üýò? Ði9»\nà/ñAÝóòÈ­A¤ýSËPo?kuN5¨~4ÜãÆ6Ø=ò*@(®N\0\\ÛdGåüp#è¤> 0À«\$24z )À`ÂWð +\080£è¦ ¤ªäz\"TÐä0Ô:\0\ne \$rM=¡r\n²NP÷Cmt80ðú #¤ØJ= &ÐÆ3\0*Bú6\"éèú#Ì>	 (Q\nðê´8Ñ1C\rt2EC\n`(Çx?j8N¹\0¨È[À¤QN>£©à'\0¬x	cêªð\nÉ3×Chü`&\0²Ð´8Ñ\0ø\näµ¦úO`/¢A`#ÐìXcèÐÏD ÿtR\n>¼ÔdÑBòD´LÐÄÌõäÐÍDt4ÐÖ jpµGAoQoG8,-sÑÖðÔK#);§E5´TQÑGÐ4Ao\0 >ðtMÓD8yRG@'PõC°	ô<PõCå\"K\0xüÔ~\0ªei9Ðìv))ÑµGb6±H\r48Ñ@M:³FØtQÒ!H{R} ôURpÍÔO\0¥It8¤ØðûÎÇ[D4FÑD#ÊÑ+D½'ôMÊÀ>RgIÕ´QïJ¨UÒ)EmàüTZ­Eµ'ãê£iEÝ´£ÒqFzAªº>ý)TQ3HÅ#TLÒqIjNT½¼&CøÒhX\nTÑÙK\0000´5¢JHÑ\0FE@'ÑFp´hS5F\"ÎoÑ®e%aoS E)  DU «QFmÎÑ£M´ÑÑ²e(tnÒ U1Ü£~>\$ñßÇ­(hÕÇGüy`«\0ê 	íGò3Ô5Sp(ýõPãGí\$#¤¨	©©N¨\nôV\$ö]ÔPÖ=\"RÓ¨?Lzt·1L\$\0ÔøG~å ,KNý=ëÒGMÅ¤NS)ÑáO]:ÔS}Ý81àRGe@Cí\0«OPðSõNÍ1ôÝT!P@ÑÝSðÿÕSG`\nÉ:P°j7R @3üÑ\n üã÷â£DÓ æúLÈÏ¼ 	èë\0ùQ5ôµ©CPúµSMP´v4º?h	hëTD0úÑÖàõ>&ÒITxôO¼?@U¤÷R8@%ÔõK§NåKãóRyE­E#ýù @ýÃøä%Là«Q«Q¨µ£ª?N5\0¥R\0úÔTëFåÔRSí!oTEÂC(Ï¶ÈýÄµ\0?3iîSS@U÷QeMµ	KØ\n4PÕCeS\0NC«P­Oõ! \"RTûõS¥NÕÁU5OU>UiIÕPU#UnKPô£UYTè*ÕC«U¥/\0+º¸Å)ÈÚ:ReAà\$\0ø¤xòÇWDº3Ãêà`üÚüçU5ÒIHUYô:°P	õe\0MJiµÃýQø>õ@«T±C{ÕuÑì?Õ^µv\0WR]U}Cöê1-5+Uä?í\rõW<¸?5JU-SXüÕLÔß \\tÕ?ÒsMÕbÕVÜt§T>ÂMU+Ö	EÅcÏÔ9Nm\rRÇCý8SÇX'RÒéXjCI#G|¥!QÙGhtðQ¸ý )<¹YÐ*ÔÐRmX0üôö½M£õOQßYýhÀ«ßduÕ¤ÕZ(ýAo#¥NlyN¬VZ9IÕºM¦V«ZuOÕTÕTÅEÕÖ·SÍeµµÖÊ\nµXµªSÛQERµ³ÔÙ[MF±VçO=/õ­¨>õgÕ¹TíVoUT³ZN*T\\*ÃïÐ×S-pµSÕÃVÕqÒM(ÏQ=\\-UUUV­CÄ×ZØ\nuV\$?M@UÎWJ\r\rUÐÔ\\å'U×W]W£W8ºN '#h=oCóÐýF(üé:9ÕYu¤÷V-UÓ9]ÒC©:U¿\\\nµqWà(TT?5Páª\$ R3ÕâºC}`>\0®E]#Rêà	ÿ#R¥)²W:`#óGõ)4RÀý;õáViD%8À)Ç^¥Qõé#h	´HÂX	þ\$Nýx´#i xûÔXRõ'Ô9`m\\©¨\nEÀ¦Q±`¥bu@×ñN¥dT×#YYýµ®GV]j5#?L¤xt/#¬å#é½O­PÕëQæ¢6££Ï^í ðüÖØM\\R5t´Ópà*XV\"WÅD	oRALm\rdGN	ÕÖÀú6p\$PåºE5Ôý©Tx\n+C[¨ôVýÖ8UDu}Ø»F\$.ªËQ-;4È±NX\n.XñbÍ\0¯b¥)#­NýG4KØÐZS^×´M¶8Øód­\"C¬>ÅÕdHe\nöY8¥Ñ.ê ú°ÒFúD½W1cZ6QâKHü@*\0¿^¸úÖ\\QßF4U3Y|=Ó¤éEÔÛ¤¦?-47YPmhYw_\rVe×±M±ßÙe(0¶ÔFÕ\r !ÒPUIuÑ7QåCèÑ?0ÿµÝgu\rqà¤§Y-Qèó°èú=g\0\0M#÷U×S5Zt®Öae^\$>²ArV¯_\r;tî¬¨HW©Zí@HÕØhzDèÚ\0«S2Jµ HIåO 'ÇeígÉ6¹[µR<¸?È /ÒKM¤öØ\n>½¤HáZ!iö¤TX6Ò×iºC !Óg½à ÒG }Q6Ñ4>äwà!ÚC}§VBÖ>åªUQÚjª8cïUTàû'<>ÈýõôHC]¨VÑ7jj3v¥¤å`0ÃèÈ23ö°Ðòxû@Uk \n:Si5Õ#Yì-wîÕàéM?céÒMQÅGQÕÑb`ò\0@õËÒ§\0M¥à)ZrKXûÖÙWl­²öÍlå³TM×D\r4QsS¥40ÑsQÌõmYãhd¶ÂC`{VgEÈ\n»XkÕà'Óè,4ú¼¹^í¢6Æ#<4éNXnM):¹·OM_6dæõ¸Ãõ[\"KU²nÖ?l´x\0&\0¿R56T~> ôÕ¸?Jn ÏZ/iÒ6ôÎÚglÍ¦ÖUÛáF}´.£¼JLöCTbM4ÍÓcLõTjSD}JtZªµÇ:±L­´d:EzÊ¤ª>ÖV\$2>­µ¢[ãpâ6öÔR9uêW.?1®£RHuèÛR¸?58Ô®¤íDÝÆu£çpûcìZà?r×» Eaf°}5wY´ëåÏÒêÅWwT[Sp7'Ô_aEk \"[/i¥¿#ÿ\$;mfØ£WOüôÔFò\r%\$Íju-t#<Å!·\n:«KEA£íÒÑ]À\nUæQ­KEÀ #¿Xå¨÷5[Ê>`/£ÍDµÊÖ­VEpà)åI%ÏqßÜûníx):¤§le¢´Õ[eÕ\\eV[j£éÑ7 -+ÖßGWEwt¯WkEÅ~uìQ/mõ#ÔW`ýyuÇ£DÝAö'×±\r±ÕOD )ZM^³u-|v8]g½hö×ÅLàW\0øÈû6ËX=YÔd½Q­7ÏÏ9£çÍ²r <ÃÖêD³ºB`c 9¿È`D¬=wx©I%ä,á¬è²àêj[ÑÖíßOÿ´ ``Å|¸òòÆÞø¤¼í.Ì	AOÀÄ	·@å@ 0h2í\\âÐM{eã9^>ôâ@7\0òôËWò\$,íÉÅ¡@ØÒâå×w^fmå,\0ÏyD,×^X.¯Ö©7ã·Ã×2ÝÅf;¥6«\n¤^zC©×§mzén^ô&LFFê,°ö[¥eÈõaXy9h!:zÍ9còQ9bÅ !¦µGw_WÉg¥9©ÓS+t®ÚápÝtÉ\nm+ÞÙ_ð	¡ª\\¼k5£ÒÜ]Æ4_h9 Ù÷NÅ]%|¥7ËÖ];ï|ñµ ßXýÍ9Õ|åñ×ÌG¢¨[×Ô\0}UñçßMCI:ÒqO¨VÔa\0\rñRÍ6ÏÃ\0ø@H¢ÅP+rìS¤Wãèøp7äI~p/ø HÏ^Ýê²ü¤¬E§-%û¥Ì»Í&.ÎÄ+¸JÑ;:³¶«!ýÐNð	Æ~öª/WÄÂ!BèL+Â\$ðíq§=ü¿+Ñ`/Æe\\±ÒÏxÀpElpSÂJSÝ¢½ö6à_¹(Å¯©Äéb\\OÆÊ&ì¼\\Ð59\0ûÂ9nñøD¸{¡\$á¸Kv2	d]èvCÕþÅÕ?tf|WÜ:£Ô¨p&¿àLnÎè³î{;çÚGR9øT.y¹üïI8¹´\rl° ú	Tè n3¼öðT.9´è3 ¼Zès¡¯ÑÒGñþ:	0£¦£zè­Ý.]ÀçÄ£Q?àgT»%ñÕxÕ.ÔÇn<ì£-â8BË³,BòìrgQþ¢íßóÉ`Úá2é:îµ½{gëÄsøgóZ¿ ×<æ×w{¦bU9	`5`4\0BxMpð8qnahé@Ø¼í-â(>S|0®¾¥3á8h\0Ñ«µCÔzLQ@¶\n?¸`AÀ >2Â,÷áñN&«xl8sah1è|BÉDxBÞ#VV×`Wâa'@¬	X_?\nì¾  _â. ØP¼r2®bUarÀI¸~áñSàú\0×\" 2ÖþÀ>b;vPh{[°7a`Ë\0êË²jo~·ûþvÍÙ|fv4[½\$¶«{ó¯P\rvæBKGbpëÈÅøO5Ý 2\0j÷ÙLî)ÇmáÈV¡ejBB.'R{C¤ïV'`Ø %­ÇÐ\$ Oå\0`«4 ÌNò>;4£³¢/ÌÏ´À*Âø\\5ÅÁ!û`X*Þ%îÄNÍ3SõAMôþËÆ,þ1¬²®í\\¯²caÏ§ ³ù@Ø¬Ë¸B/¬Íø0`óv2ï¡§`hDÅJO\$ç@p!9!¥\n1ø7pB,>8F4¯åf Ï:ñ7Âî3£3¿à°T8=+~Øn«Îâ\\Äe¸<br·þ øFØ²° ¹C¡N:c:Ôl<\rã\\3à>ñÀ6ONnä!;áñ@twë^FéLà;×º,^aÈ\ra\"ÞÀÚ®'ú:vàJe4Ã×;ñ_d\r4\rÌ:ÛüÀ¬Sà2[cXÿÊ¦Pl\$¹Þ£iwåd#B bÎ×¤õ`:Ï~ <\0Ñ2Ù·RÂÆPÈ\r¸J8D¡t@ìEè\0\rÍ6öóäÞ7½äYÏ£ú\"åäÀ\rü¦À3¡.+«z3±;_ÊvLÝäÓwJ¿94ÀIJa,A¦ñ¯;s?ÖN\nR!§ÝOmsÈ_æà-zÛ­wÛzÜ­7¡ÍÅzî÷Mo¿¥æ\0¢aÅÝ¹4å8èPfñYå?òieBÎSà1\0ÉjDTeK®UYSå?66R	¦cõ6Ry[c÷°5Ù]BÍÖRù_eA)&ù[åXYRW6VYaeUfYeåwU¹båwEë°Ê;z¤^W«9ä×§äÝõë\0<Þèeê9SåÎ¤daª	_-îáL×8ÇÍQöèTH[!<p\0£Py5|#êP³	×9và2Â|Ç¸áfaoá,j8×\$A@kñ¿aË½bócñÈf4!4¨¶cr,;æöbÆ=Â;\0°øÅºcdÃæX¾bìxaRx0Aãh£+wðxN[ÜB·pÚ¿wTÀ8T%Ml2à½¡ð}¡Ès.kY0\$/èfU=þØsgKÃ¡M õ?ÿç`4c.Ôø!¡&åg°ûfà/þf1=¯V AE<#Ì¹¡f\n») ëNpòã`.\"\"»Aç¤ãüq¸X Ù¬:aÉ8¹f¯VsóGÞr:æVÞÆcÔgVlg=`ãWËýyÒgUÀËªáº¼îeT= ãáÆx 0â M¼@»Â%Îºb½þwÆfÛÙOøç­Ü*0¯®|tá°%±PÈÍpæúgKù¬?pô@JÀ<BÙ#­`1î9þ2çg¶!3~ØÜçînläÅfØVhù¬.ÑàaCÑù?³û-à168>A¤aÈ\r¦y0 ÖiJ«} à¹© Ðz:\r¡)Sþ¡@¢åh@äöY¹ã´mCEg¡cyÏ<õàÍh@¼@«zh<WÙÄ`Â¨±:zOãÎÖ\rÍêW«°V08Ùf7(Gy²`St#ïf#²C(9ÈÂØdùææ8T:¯»0ºè qµ  79·á£phAgÜ6.ãæ7Frbä ÈjèA5îá¡a1úÚhZCh:%¹ÎgU¢ðD9ÖÅÉ×¹Ïé0~vTi;VvSwØ\rÎ?àÇf²£ÿ¥nÏiYìaº¬3 Î9Õ,\nÃr,/,@.:èY>&FÑ)ú¶}b£èiOÝiæ:dèAnc=¤L9Oh{¦ 8hY.ÙÀ®¾®üÇ\r¬Ö£Àé1Q¯U	ChôeÿO°+2oÌÎìÞN÷§øzpè¢(þ]Óhå¢Z|¬O¡cÑzDáþ;õT\0j¡\08#>ÎÁ=bZ8Fjóìé;íÞºTé¡w®Í)¦ýøN`æë¨¤ÃB{ûz\ró¡cÓè|dTGi/ûú!iÊ0±¼ø'`Z:CHï(8Âê`V¥Úãöª\0Üê§©£WïßÇªÕzgG¾½²-[ÃÐ	iêN\rqºé«no	Æ¥fEJý¡apb¹ê}6£Õ=o¤,tèY+ö®EC\rÖPx4=¼¾Ù@¦.F£[¡zqçÜèX6:FG¨ #°û\$@&­ab¤þhE:²å¬ä`¶S­11g1©þ2uhY¬_:Bß¡dcï*ÿ­\0úÆFYF:Ë£ªnØÌ=Û¨H*Z¼Mhk/ë¡zÙ¹ï´]Áh@ôæ©Øã1\0øZKù¢ëÎÆè^+º,vfós®>¤Oã|èÀÊsÃ\0Ö5öXéîÑ¯F÷n¿Ar]|ÏIi4èþ ØÂC° h@Ø¹´cß¥¨6smOÃågX¬V2¦6g?~ÖÃYÕÑ°súcl \\R\0¨cA+1°ùÌé\n(ÑúÃÌ^368cz:=z÷(äø ;è£¨ñsüF¶@`;ì,>yTßï&d½L×ÿ%Ò-ëCHL8\rÇbû°°£úMj]4Ym9üÛüÐZÚBøïP}<ûàX²¯Ì¥á+gÅ^ØMÞ + B_Fd¬XølówÈ~î\râ½è\":ÔêqA1X¾ìæ²Ðø¯3ÖÎEáh±4ßZZÂó¸& ææ1~!Nfã´öo\nMeÜà¬îëXIÎíG@V*X¯;µY5{V\nè»ÏTéz\rF 3}m¶Ôp1í[>©tèe¶wæë@VÖz#2Äï	iôôÎ{ã9pÌ»ghæ+[elU¦ÛAßÙ¶Ó¼i1Ä!¾ommµ*Kàê}¶°!íÆ³í¡®Ý{me·f`mèCÛz=nÞ:}g° TmLu1FÜÚ}=8¸ZáíèOÛmFFMf¤OOðîáÀèøß/¼éõ¸ÞåþVoqj³²èn!+½òµüZ¨ËI¹.Ì9!nG¹\\3a¹~O+Îå::îK@\nÚ@¤Hph´\\BÄõdmfvCèÓPÛ\" æ½Û.nW&ên¢øHYþ+\r¶Äz÷i>MfqÛ¤î­ºùÝQc[­H+æÀo¤Ñ*ú1'¤÷#ÄEwD_Xí)>Ðs£-~\rT=½£à÷à- íy§m§¹æð{hóÌjÚMè)^¹ïÀ'@Vå¡+iÈîÎòåµÉ;F D[Îb!¼¾´B	¦¤:MPîóÛ­oC¼vAE?éC²IiYÍ#þp¶P\$kâJÞq½.É07þöxl¦sC|ï½¾bo2äXª>Mô\rl&»Ç:2ã~ÛÑcQ²îò²æoÑÞdá-þèUÜRoYnM;n©#ß\0P¾fðÚPo×¿(CÚv<Ê¬ø[òoÛ¸û×fÑ¿ÖüÁ;ßáºõ[úY.o®Up¿®pUø. ©B!'\0òã<Tñ:1±À¾ ã¤î<ðnîF³ðI¢Ç´V0ÊÇRO8wøÎ,aFú¼É¥¹[´ÎñYOù«/\0Ùox÷ÇQð?§°:ÙëÆè`h@:«¿öÑ/Mím¼x:Û°c1¤Öàû¯ív²;è^æØÆ@®õ@£úð½ÂÇ\n{¯¼Âîà;ç´B¼í¸8º gåä\\*gåyC)ÛE^ýOÄh	¡³¦Au>Æèü@àDÌYæ¼íâ`o»<>ÀpÄ·q,Y1Q¨Áß¸/qg\0+\0âæåDÿç?¶þ î©Úßîk:ù\$©û¬í×¥6~I¥=@íÑ!¾ùvÚzOñ²â+ÍõÆ9Çi³¼aïðêûgòðôî¿¹ÿ?0Gnq²]{Ò¸,FáÃøO¡âÞ <_>f+¢,ñÌ	»Ôñ±&ôðíÂ·¼yêÇ©Oü:¬UÂ¯LÆ\nÃÃºI:2³¿-;_Ä¢È|%éå´¿!Îõf\$¦Xr\"KniîñÀÐ\$8#g¤t-r@LÓåè@S£<rN\nD/rLdQkà£ªõÄîeðåäãÐ­åø\n=4)BË×ôÌZ-|Hb¡HkÊ*	ÖQ!Ð'êG Ybt!¿Ê(n,ìP³OfqÑ+XY±ÿë\"b F6ÖÌr fò\"ÒÜ³!N¡ó^¼¦r±B_(í\"¨KÊ_-<µò *Q÷ò¨Ù/,)H\0²rç\"z2(¹tÙ.F>#3â®Ø¦268shÙ þ¨ÆI1Sn20¶çÊ-«4ÚÇ2As(¬4ä¼Ë¶\0ÆÝ#årþK'ËÍ·G'7&\n>xßüÜJØGO8,ó0¼âù8ÑÓ\0óW9ÝI?:3nº\r-w:³ÂÌÅ×;3È!Ï;³ÜêZRM+>ÖÜðÊé0/=R'1Ï4Õ8ûÑÏmÿ%È¥}Ï9»;=ÏnQöã=ÏhhLõ·GÏkWÎ\rô	%Ø4ÒsñÎJ3sÛ4@U%\$ÜÑN;Ì?4­»óNÚÏ2|ÊóZÚ3Øh\0Ï35^Àxi2d\r|ûM·Ê£bh|Ý#vÇ` \0ê®äàû\$\r2h#ú¤?³I\n¼+o-?6`á¹½¿.\$µøKY%ØÂJ?¦c°RN#K:°KáELÁ>:Á¥@ãjPÌn_t&slm'æÐ©É¸Ó²½ã;6ÛHU5#ìQ7U ýWYÜU bNµWû_ûª©;TCø[Ý<Ú>ÅÇõWýCUÔ6X#`MI:tùÓµö	u#`­fu«\$«t­öXó`f<Ô;båghöÑÕ9×7ØS58õ¬Ý#^-õ\0êÀúîÕ¹R*Ö'£¨(õðõqZå££êX¹QÝFUvÔW GWíñÓTêÇWô~Ú­^§WöÄÁÕýJ=_ØbmÖÝbV\\l·/ÚMÕÿTmTOXuÊ=_ýITvvua\rL_ÕqR/]]mÒsu=H=uÑg o\\UÕgM×	XVU À%õhý¡53U\\=¡öQßØM¹v¡gåmàõue¡ÙûhÿbÝMÝGCeO5®ÔÖO5ÔYÙi=eÕ	GTURvOa°*ÝivWXJ5<õ¯bu ]×Öðúµ<õÃÙÕ\$u3v#×'eöuÑR5mvD5.võW=U_å(´\\VØÏ_<õ÷SÍn)Ü1M%QháZTf5EÕ'ÕÍW½vÅUmiÕUÔÕ]aW©U§dRváÙ-YUZuÙUVUiRVõ³ÓÇ[£íZMU§\\=Âv{ÛXýµ¼wQ÷huHvÇ×gqÝ´w!Úoqt¢U{TGqý{÷#^G_ubQêåi9Qb>ÚNUdº±k½5hPÙmu[\0¦êÅ_¶é[õY-ðô÷rõÈÕ(ÖCrMeýJõ!h?QrX3 xÿÈÏ#÷xÖ<Û{u5~íÑ-ÝuëYyQ\r-î\0ùuÕ£uuÙ¿pUÚ)PåÜ\r<u«S0ÝÉw¹ß-iÝóÔ!ÌÖøB÷áÆd]ùèÅÔÆEêðvlmQÝ6k¼ÒJ´wí¦ÄØÃãED¶UÙRev:XßcØNW}`-¨tÓH#ebº±uãó	~B7ê ?	OPCWµ×SEÍV>¶×UÛ7ßçÔám»Ó¬zÿ=µÍØ1º+ ¹mÃI,>µX7àä] .½*	^îã°Nº.èÎ/\")Ð	¯s®|à¤çÓÐlÁ}ã¸Íç!óî5n±pj£¾h}½èðmEázHÂaO0d=A|wëß³ãë×Îìu²vùØ¼Gx#®bcSðo-ùtOm`Cò^MÅ@ë´h­n\$k´`þ`HD^PEà[ä]¹¨rR¸m=.ñÙ>Ayi \"úò	Ö·oã-,.\nq+À¥åfXd«¶ã*ß½KÎØ'Üê Ð%aôÿù9pûæøKLMà!þ,èÊË¨zX#VáuH%!À63J¾ryÕíùq_èu	úWù±Æ|@3b1åÈ7|~wï±³þíA7ÒÂè	¼9cS&{ãäÒ%VxðïkZO×wUr?®ªN Î|CÉ#Å°õåÕ¯ ¹/ú9ftEw¸CÁºa¦^\0øO<þW¦{Yã=éeëýnÉígyf0h@ìSÝ\0:C©´^¸VgpE9:85Ã3æÞ§áºð@»áj_ª[Þ+«êÇ©x^ê®~@ÑWª¸ãã9xFC¿­.ãçöük^Iû¡pU9üØSØ÷½\$óóø\r4´ù\0ÎèO°ãÄ)L[Âp?ì.PECSìI1nm{Å?PîWAß²Á;ñìD°;SºaKføò%?´XõÞ+¤B>½ù9¿¯ÙGjczAÍ÷:êa³n0bJ{o¥·!3À­!'ØKÃÅíùÔ}ã\\èÎ3Wøê5îxÏÉÁL;2Î¶na;²í×ºXÓ]Éoºxû{ä¦5ÞjX÷ð¶vÓéãqÞÊEE{Ñ4Á¾öÄ{íÙç	Ì\nöÊ>ùaï¯·¾üì§ïØLûÔûåïÿ½ûìñ'ð½Þé{ë\n>Jøßá¸Ó÷YÏ\rOÊ½ðt¯ÿû¥-OÃ¦ü4Ôÿ9Fü;ð§Á»ÔüGðøIªFßì1ÂoÿßóñO²¾éa{w0Ó»ï¤Æ¯;ñlüoñàJÐTb\rwÇ2®Jµþ=D#ònÁ:ÉyñûSø^ã,.¿?(ÈI\$¯ÊÆ¯í¨á3÷Ãsð4MÊaCRÉÆÍGÌúIß°n<ûzyÑXN¾ð?õâ.Ãî=àñ´DÇ¼\rØé\nÕó¨\roõý\nÐCl%ÁÍYÎû¥ß°ÏàGÑþÚ}#VÐ%ý(ÔÿÒà3æÉrð};ôû×¿GÉÌnö[ª{¥¹_<m4[	I¥¢À¼q°µ?ð0cVýnms³nMõõ\"Nj1õw?@ì\$1¦þ>ðÒ^øÕû¥ö\\Ì{nÂ\\Ìé7¿Ùic1ïÚÿhooê·?j<GöxlÏù©Sèr}ÍÃÚ|\"}÷/Ú?sç¬tIäåê¼&^ý1eóÓtãô,*'F¸ß=/Fkþ,95rVâáøàÀºìÛo9Íø/FÀ_~*^×ã{ÐIÆö¯ã_²^nøþN~øáÅAí¦d©åñþUøwäqY±åî´T¸2ÀéGä?&§æô:yùè%XçJÛCþd	Wèß~úG!´J}¤úìùõÄB-Óï±;îûhÃ*ó¼R´ìöE¶ ~âæó.«~Éçæ SAqDVxÂîÍ='íÉEÙ(^û¢~ùø¿çòéçïo7~M[§Qãî(³Üy¸ùnPÑ>[WX{qÔaÏ¤ÆÉý.&NÚ3]ñúHYïÝûëÛ[¶ÁÙ&ü8?Ñ3¦¶§ÝÚ»¶á#¦ÎBðe6ë@[°¤£ûàÐG\rÎ+ý§}ü÷ÁÿÏ_Ýç7|N§«Þ4~(zÁ~»¹ï§%?±ßÓÈ[¹ø1Sª]xØköÑKxO^éArZ+ºÿ»½*ÂWö¯kþwD(¹ø»R:æý\0§íù'¤óm!OÐ\näÅuèÆó.[ PÆ!¹²}×Ïm Ûï1pñuüâ,T©çL 	Â0}â&PÙ¥\n=Dÿ=¾ñÐ\rÂA/·o@äü2ãt 6àDK³¶\0ÈÂq7l ¼ðBêúÌ(;[ñkr\r;#ÃälÅ\r³<}zb+ÔÐOñ[WrX`Z Å£Pm'Fn ¼îSpß-°\0005À`d¨Ø÷PÁÚÇ¾·Û;²Ìn\05fïP¿EJäwûÛ ¹.?À;¶§NòÞ¥,;Æ¦Ï-[7·ÞeþÚiÅâ-ÖîdÙ<[~6k:&Ð.7]\0ó©ûëù/µ59 ñÁ@eT:ç¯3ÅdsÝú5ä5f\0ÐPµöHBí°½º8JÔLS\0vI\0Ç7DmÆa3e×í?B³ª\$´.EÐfË@ªnúbòGbÁÏq3|üPaËøÏ¯X7Tg>Â.ÚpØï5¸«AHÅµ3Sð,Á@Ô#&wµî3ôm[ÏÀòIíÑ¥Ó^Ì¤J1?©gTá½#ÏS±=__±	«£ÉVq/CÛ¾·ÝÎ|ËôáþD g>Üõëé 6\r7}qÆÅ¤JGïB^î\\g´Ýõü&%­Ø[ª2IxÃ¬ªñ6\03]Á3{É@RUàÙMö v<å1¿¾sz±uP5ªF:Òiî|À`­qÓ÷V| »¦\nkâ}Ð'|gd!¨8¦ <,ëP7m¦»||»ÿ¶IAÓ]BB ÏFö0XÏú³	DÖß`W µÁqm¦OL	ì¸.Í(Áp¼Òä¶\"!ýª\0âÍAïÃôÁV7kM¸\$ÓN0\\Õ§\"fá Çëñ È\0uq, 5ÆãA6×pÎÎÈ\nðÎjY³7[pK°ð4;l5n©Á@â\\fûÐl	¦MöùûPÁç3®C HbÐ©¸cEpPÚÐ4eooeù{\r-à2.ÔÖ¥½P50uÁ²°G}Äâ\0îËõ¨<\rö!¸~Êýµ¾óñ¹\n7F®d¶ýà>·Ôa¢Ù%ºc6Ô§õMÀ¥|òàdû·ìOÓ_¨?JæªC0Ä>ÐÁ&7kM4ª`%fílðÎB~¢wxÑÚZGéP2¯à0ü=*pð@BeÈØÏ|2Ä\r³?q¸Ð8í¸ë±ñÍÐ(·yráö 0àî>>ÀE?wÜ|r]Ö%AvàýÁÅä@+ÝXÁªAgâÉÛÿsû®CÐûAXmNÒú4\0\rÚÍ½8JÝJðÇ¸DÒó´:=	ðóëÆS4¯ñF;	¬\\&ÖèP!6%\$iäxi4c½0Bá;62=ÚÛ1ÂùÌPCØåÂmËÍdpc+Ò5å\$/rCR`£MQ¤6(\\á2A ¦¹\\ªlGòl¬\0Bq°¤P¯r²ûøBµêÑ¹_6LlË!BQIÂGÀåÜØðXRbs¡]BHrã`ÎXä\$på±8ð	nbR,Â±L \"ÂE%\0aYB¦sÍD,!Æ×ÏpN9RbG·4ÆþM¬t¸¬jUô¤À§y\0ìÝ%\$.iL!xÂìÒÅ(Ä.)6T(Iìa%ÒKÈ]mÄt¥ôú&óG7ÇITMóBú\rzaÂØ])va%²41TÁjÍ¹(!¬Þ¡¨\\\\ÆWÂÜ\\t\$¤0Åæ%á\0aK\$èTF(YàC@ºHÏÐHãnDdÃWpÉhZ¯'áZC,/¡\$û¦£J¡FB¨uÜ¬Q:Î¥ÂAö:-a#ì=jb¨§lÕUg;{R°Uº±EWnÔUa»VâîNj¬§uGÉ*¨yÖ¹%ÝÒ@Åï*Ìä«ÕYxê±_ó²§z]ë)v\"£çRÕåL¯VIvê=`¾'ª°UÝ) S\r~R\niÅ)5S¦åD49~Êb;)3,¦9M3¯HsJkTÃ(¢úuJ][\$uf¨íob£µ¹\n.,îYÜµ9j1'µ!ö1\$J¶gÚ¤ÕÄU0­ÓZuah£±·cH¥,ÃYt²ñKbö5ë5/dY¬³AUÒ©[W>¨_Vÿ\r*·õ©j£§-T± zÖYÊdc®mÒ¹±Ø:¹üË[Ut-{ªµýl	£i+a)».[º_:Ú5ähò­WÂ§Ém»¥%JI´[T«h>®µ·°;ËXÌºdêÂSdVæ;\rÆ±!NK&AJu4BÁdgÎ¢.Vp¢ámb)ÇV!U\0Gä¸¨`Ð­\\qâ7Qöb«VL¥Þ:äÕúó¬Z.­NòÄ*ÔU]Z´læzëÎöù®ÇR D1IåÂ£Ñr:\0<1~;#ÀJbà¦ÊMyÝ+Û/\"Ïj<3æ#Ìêñ¡:P.}êe÷ïòD\"qÙyJýGû·sop¯²þX\rÝ³dÞ\rxJ%íÏÆ¼O:%yyãÅ,%{Î3<îXÃ¸ÏÌ÷¯zÂEÎz(\0 D_÷½.2+Ög®bºcÚxìpgÞ¨Áß|9CPûî48U	Q§/Aq®ÝQ¼(4 7e\$Dv:V¡b×ûN4[ùiv°Àê2ñ\rX1¼AJ(<PlFÐ\0¾¨\\zÝ)ÑçW(ü4ôÈÃÚï¢ pÓõÊ`µÇ\r³da6¯üOÖímña´}qÅ`ÂÀ6P'hàç3§|îÃf jÈÿAæzø£+DUWøDíþÞ5ÅÄ%#é°x3{«¶L\r-Í]:jd×P	jüf½q:Z÷\"sadÒ)óGØ3	¤+ðrNKö1Qþ½çx=>û\"¤°-á:ÊFÍõIÙ*í@ÔÇy»Tí\\Uè¨ãY~Âäâ3DåÁã¨f,s¢8HV¯'Ét9v(:ÖB9ñ\\Z¡(&E8¯ÍW\$X\0»\n9«WBÀbÁÃ66j9Ð âÊ?,¬| ùa¾g1²\nPs \0@%#K¸ \r\0Å§\0çÀ0ä?ÀÅ¡,ä\0ÔhµÑh\08\0l\0Ö-ÜZ±jbàÅ¬\0p\0Þ-Ùf`ql¢ä0\0i-Ü\\ps¢è7e\"-ZðlbßEÑ,ä\0ÈÌ]P ¢ÚE¶b\0Ú/,Zðà\rÀ\0000[f-@\rÓ¯EÚÏ/Z8½~\"ÚÅÚ­ö.^ÒÎQwÅÏ\0Ö/t_È¼ÀâèEðÖ\0æ0d]µbúÅ¤|\0ÈÄ\\Ø¼¢íE¤\0af0tZÀÑnJô\0l\0Î0L^´Qj@ÅáJ´^¸¹q#F(1º/ì[µ1¢ãÆIæ.Ü^8»\0[qØÌ[Ãl\"åÆ \0æ0,dè¶ÀÆ\rÌcøµ{cEÁ\0oâ0¬]°\0\rc%ÅÛð8½w¢åÆZµ-Ä\\ºñ{ãÅÖGª/\\bp@1Æ\0a²1ùÈÏÑsã!Å¨/î/Ì]8¹~c\"ÅÛÅþ2ôcÎm£\"9q/\\^fQ~cÆ_£Î-\$i\"Ö\0003Ë¬¤fXºqx#\09Z.´i¸È@F3tZHÉ \rcKb\0j/DjøÉ1¨ââÆIh´aÈñvÆ©OZ4ZòÌÑ#YE¨\0i.hHÒÑsX/F<Ï.äjøËñ­bèÆÍ\0mV/d\\èØñb÷E³£3T^(ÝÑcKFRÕùô]X¶q½¢øÅà6Ô]hÓñc6EÄó66Ühãn\0005sn/dn¸Ô`\r\"ÑF³Ú-D`ÈÕãN2Y¤bxÀñ#\\ÅëV3x·1xFx¾\0Ê6b°q£Ç!8|^ÌÑubåÆàÕ-ôrØäq¼ã:Æé%ö0ppñ#Ç¢\0Æ6ÔfÕÑÇ¢âÅ¬dÒ0qH´±¾£\$Ç@qò-¼^B4±¦\"ú\081ª/lnxÏ âêG3:0tjhÒ~@Æ¼¥¦3¤vHÆñ¹bÜG(e4gØºqÂã2Æ1É-nXËñº\"ãF<Q1\\j¸¸1®ãÈEÇÇä³4m¨Õñªã[ônÁz7üyhÞ1§#ÆÞ/3\\xÐqÍKGÿÆ6äoÑ1{£°FJ×6¼lXéqâ£Æu©Þ9r(¿1ÒãGc\0Åf:rX½ #ÐÅ½\0iÞ<\\}×ñåbîF½\0sÖ7Üy2ÌÑæ#uFe\">4iØÅ¿âÔÆçé\n<{¸ã£âÆJ;¬]ØÄ1Å#ÎÆ0ÙJ;4^èÂD½ãóÇ®¨³4i¨À(H#ÚÆEx/¤nøû1ðã/Ç¡åj6,lÛ1tã/\0005%ï0]xü¶£GG5!0¤¨×ñÚâérq¢2Ì¨ÞÎãNFPo\"4ô_·1×dÇ%e ²3¬s8éüãG5 æ6Ô[HëcØHjY;ô[è¾bë! yò@Ä\\¸½qØ#WHN;ÌcÆQèã:Ç-%ª.kXÆý£ÚGÍÏ1Df¨ßºcWFl¡!0ü²c EÜ©;lÑq\"ëF©ß¢7\\\\¨ùñâ£ÔÆOqþ.T|\"?ñãÆE³f9TyYÑ©ãSG1ûÂA\$f9R\n\"ÞÆx¹>BHÚñß¤\0Ç¶:\$e¹1£³F?=º3Tu)\nq¹béÇ~ËÎ<TøÎ±ÐcH.m~CôwHÊ±¸#/ÈI]~3ä^ºÑ#§Æ>Y®4^¸ÎQjcÊÇK1\"Ò8¬|6Ñåc\"ÇBµ\"b4ãèæ%¢ÔÈG\0e\"/t¨´1r£1Æe!v2yÀ±õä<Ç 8\\o¨ÊÑ#tÅÑ\rz@´}HÂèbïÆèy î1Ì\\¨ðëdeGÁZ3~ér)ã1È¿ÛBl~H½²:£dF£-Î?k8´qèc(FÍKÞ5|myñc1Æ<*@´jØáò1ãÛÅ¾>I´ZèÍQjäÈ2É\$0¤hµQäVFT	\$ÆAl~öqÚ£È±\$Ö>\\pÙ\rq\$/Èu%ï!®Jq \$ ãtE²GN-Tq)ò\"¢ÛHÊË¦=ìXÉ2-£H«8\\nµRW\$Hë\"¢C\\_¹\0»d\$Çf³\".Du	'Q£zEíÙ&0toóqjãúÆ¿³R@døÉä£ùÇu##¶LLkÉ*qó\$*GÄiÎ@TilãòEªÎ5¾r\\dIµ\"/ÌZÉ0j\$TÅþz5Ld3£ëÉoÂ.Tq¹!1{£ÆåÖ9Z¸¾QÕbÓFwJ94nÒÄÖä{É(-8·2h¤uÈé;\$-Dkøårs£H#¡ôY7ò\"Ø/E¿Ó 	\$j¢^ò-£]Ç7[\"N\$èÂ¤WÈ¯Ö/]à\$²+1Ga/&IDnøÂ@\$åÆ!ç\$Î-k!Q¨âùÊ)(N/\$t¸Ý¹äëÆOKzP´tXÜò[\0Gw(*K\$vË1ócÉ'ÞGÌIòxd­È\nAÒ8\\rX·Òa£÷IiNI%\$½ãÆ_÷ª6¤fçQþ#ÈI5#F´ØºñÏ#³Eâ\"î3\$¢IÜcHÝvR|ùQ¤cE¸ñ:Reº±hä¶EÎfK`8þr.#·E³s®0LüRäF©·!\nC\$`Èöñ´\$ôH?ËnPÜe!ñ¥@F'¿/¸¶ÄÖäÿÊ¯%ÂN,hÈÌrF\$öÈþÇ3´tøæÒ¥Åæ!1<ÉCQÏ%ÉÃ¹æJäZØf.Ý6Å·±C¥ÊÔ.²[þBÒ¿xëàè\0NRn`ÈùY\n%+N¨IMs:Ã¹Ydef¬B[¶°ÝnÆ¹Yòm¨ÁR®×ûÉY¯ÚCXëÛj³çU+Vk,¯\0Pëýb@e²¹¥x¬V¾ºyT¤7uî«[JïÈ±\nD¯§eR¿¬mx&°lÀ\0)}ÚJ¼,\0IØZÆµ\$k!µ¨ñYb²Á°RÂe/Q¾Àk°5.Áe­5À¨W`ª¥\0)Yv\"VÂ\0Ã\n%å`Yn¯Õ¡aôÔxÃQ!,õ`\"	_.å©Ætm\$\"²J«¤ÖÀ§vÆ%M9j°	æ§Ä*³KpÖ;\\R ¼ü3(§õ^¯:}Èï|>Âµa-'U%w*#>¤@Ì¬eJÿ¤;Pw/+¹á5E\rjn¡ÐÃdô¢^[ú¯§cÎ°¥uËz\\Ø1mi\"xpåÃ;£ÌîæP)äøªÇ#±Ø¡Ë!Aª;¨ß	4ì³a{`aV{KUàÊ8ã¨0''o2¨¢ycÌ¸9]Ké@ºÒ^ðlBâOrëÔã,du¤¾8¤?õÕ%¼gB»îÆYn+ã%c¬e\0°ñà¤±Yr@fì(]Ö¼¨\nbizîÖnSS2£ÁGdBPj¹Ö@(È¥¦!à-çv²´eÚ*c\0ª4JæçùÕÙ,UÈ	dºÉeðj'TH]ÔÔG!)uÕÖ¯Ò¯ùZËB5ûÌW0\n±á¡ÔR«ÁW\\¦Q jÄ^rÊ%lÌ3,ÒYy×Éf3&ÌÜÕQ:Ïµ2mÉR)T¾(KRÁ 0ªÊ@«ìY´¢Y:£Ùe3\r%´¨°Tö%­XÁ¹STÔ.J\\ë0ÙhôÄD!Ä:uæêÉU\"¾ÅÁo+7\"µf'º­R\0°ÞJõ2S2è#nm »ÁIåý\"Xü³²[ÖÑì} J¨¯c¼9p0ªüÕQ»(U\0£xDEW.LõÁ=<BÔ0+½)ZS V;â\\âµI{5IAôÖÃ,dW²uè5Ew\n\$%Ò½2i_\$ÈÙ+ìæO,¬íX´ÕJg&J¡úGº%\\J·b.ÄÝ^LTòFlè¹]k#f@L·GÄT¼ÙÒÍHÏÌ\"q1SÌ°ùjVÉ(ÎìZVzßÅ³,§ÊèG.1Fû±gNÊ;×1ÃV¬¦5EÍò5`ò\0Ctè=F\ná¹Î±KþÖ\0­Û±%¨ËD]Q\$\r\03J\\,Í³<T4*£Á.ÒYK²D«QéLïS%,gÔÇåª§Ö<Ëëu0ôÍUÄÖ*x(©åNÂYv!þ¥yÍ	wÅ4fdª¥rGM \$äê^;ºéîÝæ)<Pã]DÒ%%Ó;ÔjÊåI0æaÓu^Jp[)¦v©3RhRúEöÀ\næL_#5|Ü¾Õm3Pñ*¨\\Y51X	i³NÈñ\$\"°ºaü­õh*KUÝÌïV8¨åuò±%&ræ¯Ë ²5oÕçg³;ÝrMl[Æ¨ög³ùª·UÍqê¹h|ÔeO2·f MlW2AP×¹ÍÀÍv~eD¬eñ3UÓ«lE62iüÎõìÓUbÌï¬«õU¬©¨îøýªVðêiI!\$i¨Ê­&Z:½xm!Å.ÖOÍfwÒ¯!ÌÓkÝ¤Í6b\"«IJ]]:T6ÒVrú¹}ÜÇ«]®±U¢	ys7fÔMÅÿ3ÜÎYó:T_MÍw%3ÆnÏ¥\nÎæz*í3âh·	»`U²Lÿ,¥ÛÐ5¨óvf»ÃÙ42_Q¼hÝÇÍuD§\no£¹)¤ÄÕ«M9¿7foÛ¼©¤rÖÝÇÎWB~iTÝeyQTâN\nd¦pr§#óM§;4æpª¼têÿ(;³5	|¬àÇ­',AV7ÜÔåUAö&ìÍRP¯\"äÕyÒ·) [nÌÕñ-3VË,?s6ºpù3fµÎAÛ9k|ÝÉ®Sf¬*@5Þg¼¾É¿2·Í}®þUüÝðùæHÎFl%®pÂ«Ie³beMÙSO\r[¼æi²3fÉÎLVá®rÙu®¾¥ÛNA:î%rÚy3Q_Ì¸W.ÑÕÈ^Sl@&ÌÁ5ÖYlÂÌ1åæÎ}VxêgÊ§^SnÕÌÍQ!:5×ZÞiZCÔ:¿3qgé%DáõÝª{U¡3tZ¹`ûÓu%w:ÉZQ:QìÏÇW fîí¿9Jplê)Ö3xÔvÌþK7b#«ù½«çX+J(¢Âh´ìP*Ó´«Îþ¢!×ìÅSLçh*'¤¨\npBùÚªgNÊ§8BuÒªéÂ¯çÎ½8niêIÍs¸USÍI;vvÚ³UõsR7Nu×8©H|íéÅÓ·§Ì«8òq´ÕÙÞ+'ÑßÍ`x¢9R	Õ®ºçMaR8úxä)¸'!Ï;±U¬×YÖÝsNIg:ÕKTëy¯3®gÍYìëÊkäãÉÜ³n'LO(¿3w4ñ4î»¦ÇÏÚêþl¬ñÎJ½ªw½9Ý\\ìçóóhf(¢_~ìòà}9Nö¦Õ\0´åb\"¢Yé¤Th,Ú¤@ú±D¡û\$I·;eüèUÊn¨³·,¹OªÆ	Xÿg´-ÀÉ+>ti'Gölª%\0­8âVBËU1«ye\0KTÆ4ûÁÈmºV2)\r]I/\rFùÔX×Àß¨ña·­GÂ¹ò*§»ÿ>ERì÷ðî®¥ÑZ-)I\$®¹íç:¦aË\0¾FybaÙg«w§­(ß_@§v}öiõÊ³îS^Ë25DÔ³Ð	ÈôURO±JHÖ\\ØisðfÆËKN±qi÷Sg×OÂ\n²F~|«µÏ*@gR_Q<9sÜ¬3i+Ø².Cw²²ê|øyË6aìOÜY9¶¶É\nëÔ½-([®±_}íSû]c¤S=Â¤ÎÙþÎÍÔYÎàU-> <ú©µ\n<ÖsOôQ4F¦^}\0007uäk(/Û/5{Lÿ9µ\0§¬Ð &³[<ÏõsÛ\0&Íè#@hÌéª3©V}ÐH¢*Üw+]'DÐ& @§Ö])µè;TGe3\\Îên®ÑßËd\$:¦uN4Åyktê-dR!7­Ée4(P!-þ9À4ç_PMGbÄ±w«ØÉ6O§S¦Fâí)§yh0+²§qT|·+uÔÿÎ+ A¬?òÞ	öTè3.q 41T´¸e\n:P ø¯{Tî\n³ëh?«TïAùS£­*«åÒ+åu¥>ú\\ê¾ZéíÊîYì·¢wEJö%·sL±¾dªyÀ+\rCèß¡'Añl,Òyå3þç²ËÍ`º	_*ÑPû ThKDV²·~5	à0´+á¼,-?­]ºò3ëÖKå`¯^¸¤I42(]ªw.ærÄÊËê]¬\nYÆ¨B£­Ð	³í}ÐR ¾ÉgØ}:H§ðJÄWP²ê\"ÞµðôV\\¬<? >½åáÿ§Ü¬Ý¿=¦:\n0×è\\+ñS´æfÝU³íU,WCÖèOn¨òÎ¢§.e9|R÷I'©[×/º²ÄÙü2ù«QÓBn:ÆIõ\nö§g¼9Æ\rü,ÓR6³ýçÒQ\$XÝ+¸>©±`\nù)/_8QiÔùµê=êv?5v\0 \n¨çÉLG¥Dmw\\ëFÖÑ¢¯Ádêµ}s\"ÃYv¤|âJ*´9h­¡Ñ@XEUÑ*Þ(oQ]\$B,ûéÜKTv¤AptCÉ\n×C,/<¡­ÚEW-VïP¡¢=Wÿ*%Kê-Q`9	(Êú59Óèm)ËX¸¨@ç2ø ýT@Û\nS¯bd×EÎ´a+DXîá|UÚ		¡F® 2ú%5\njm«WÙ+xêKæVÌ3#¶CTÃek¤&Î,£l¬jbd7)Ó\"\n+ìPüºbèI@è3ÑÜµjUÒÌEsÞÔ)D¢fëõûÇPZ3AÎÕ\nwThð²ªÛÅ4Zäª<Êuß©ßdqâËu(÷bKG±à¥éÀnÓTï®]z¨f%#3IËfS¨®&}µ@D@++ù¤Aíhª¿\nªïUÞ¥|B¡;UmÑÙUEN¥!ôx2±1Ò\0§GmvH~õÁHèTê)öW®³YNý\"åk5©ÑvT#=µÚ¥Ê<\n}#R3YHÅRÍIÍ³Ü¦;ÌÑRl£1léuB%TQJî*ºêÙ'ºEë0i¬dw,¥zÊÍ¥:\$¦;Í? üîj¿)§ô)ÔÊ\$32J}Å&[³\$¨õÌ¤;DnýE×´À+0ÛaZ{¨èC èû(¤ê:¸ ÚO@hø²D£æ\0¡`PTou³ÄïF®\rQvû¨o½Ü¡\$Sîö+Ò#7À¤Izrpk DWFsÍ9 Qê  Ð°1gÀÅ#\0\\Là\$Ø 3g©Xyôy -3hÀþÃ!nXèô]+±	Éc\0È\0¼bØÅ\0\rü-{\0ºQ(ðQÔ\$s0ºém(°[RuòVÆ÷ÒØ>Æ¼+àJ[©6àÒàJ\0Öú\\´¶ã,ÒéK3ý.ê]a_\0RòJ Æ`^Ô¶ClRÛIKîù\n \$®nÅÒä¥ïKj©\nÁ©~/¥ªmn].ª`ô¿ijÒâ¦#K¾f:`\0é6¦7Kâ¨zcôÂ\0Òõ¦/K®­/ªdôÄéFE\0aL¤dZ`JéSÏÊ2ØÍ4Î@/Æ(Lòõ0ª`´Ä©_Lþ]4ZhôÐ©SD¦M4:cÑéSR¥×ME4iòéSG¦EMjå4zdÔÕ©SFKLª%4ªeÔÏ%\$ÓlKM2õ1ÈÚÔi¦Ó©MV­.¸ÚÖi´Ó©Lz/÷ôÛ£Ó¦ÑMæ,`_ôàimS¦gMÆjgòéÇÓ5¦9.9j_òéºS¥µ.Å9ê_±òé¾S¦.7Úrò)ÉÓ%§[2m8ºuTæéS±§3M:]3ºqèänÓ±§KN1|^ÒktÏ\"ÒÓH§gKj-;zcñiÎÓ§\r<ê_²-iÊÓ¸¥ñ\"ÖU.¹´óiëRÚkOFí=:\\ôÏ\$ZÓ©§MLE­5úxôø©ÂÓ»_\"Ö=<\0ñtéÙSç¦9OÒ­1~öi²Óô§¹Oêí>ê~q)òF¸¨ =6:~ÔõãJÔÏP:Í=¨åTÿ)¢Æ«§ÿPJ8õ@êwôô©÷Ç*§ÍOÊ5]>ªt÷£T\n§å!\" 6Y	)ÈH¨/Pª3É	éð/P~ àù	ªÓ®¨!\"CÌÔýj¡ ¨eNJ¡üêñÔ*%Ô4¦1Q¡ÅCZQjTBQ.¢\rE)\0004Ëê\$2¨SM+å<jt¿j0Ô,¦9Q¡}F\0\$±s©Ta¨KÎ£]Ecj*'K»M¾MGx½ÕRÇT1¦#Qê¡¥Gª5ª:Ôz¨L¡4u6z\"j\"TKuNÖ£ýGÚg\$jFSÜ¨ïQ2¤¥Høîµ\"êMT©%R¤HzÕ\$ª,Ôw¨Re.\$rªzµ)©ÛÔ¦©-Qö ÍJ¹Êª@Ô°©=R&/IÊ1*]T³À7¼¾QÒåD&Ó©qN¦_(´q²c[TwQRôå´J\0nâ÷T­¨û.¦956cÔÜÕSz¥HÁ7ªRÔ}Sr8¥NÕ\"bÖTè§ÁQÞ5MNõ#ãçÔè©ESÂ§-HÁ7\"ÜTü©_Sê§}GØÌ?*yÔ©Sò§½P*5#âöÔÜÏT:§]PÊõC*ÔT:¨-K8Æ5CªÕªR¦--MÈ¾HªÕ ª'T¨­HøËõHªÔÑ×T¨íRª£õ,âéÔÜGTÚ©-SJ¤õM*Ô©UTÚ©mMH¸õMªÕ>ªgSD³5MÈÂRªÕHªwU\"©íK8ÕÕRª ÔÚ¡U*ª-U*¨ànÂ¾TÙIR­,t¢Z«ÕêY¶IUF«51ª¬µW)vÕk_KÆ«pJ«5Zj­Å¯©R4r\n¬^jIÓCKºª}UÊ_ª°ÔªãO¬=N·R*¯F-ª½R¬%WÕcê¦Õ\\aV>«EYjµdªªÔÃ«UÎ¬µWXÍ5*ÈÕ¹UyõZ°1kãÕ¨«7V¬R\\HÍ5h*ÖU¢©ÏUÆ§M[²±kêvÕ¸«3Vò­}[(ä5WªzÕ¸«iB­Oº®1¯ê¯Tý«V®;­[øîµpRæGu«;T@0>\0ê/I³ªÿW`í]¦ô\0ªîÆ8«¿P¯]ÈÍ1m*ïÕÇyUz¨mW¡õ|ªÝ[«¡Ö¯]J¬ÑêøU±««ö¯Z*¤5\\jÖ«ëZªô`ZÁ5~ª®Eì¬Wú«4ZÁ5h£QÕ^cXZ®Sú®1o«Vª¹U&«TºÄ5}cU^X°dm*³±kUu¥«SfG=[¹õjäsÕ¿ÏX¦Kc\n®iRâHç«i#±uWt»µª½¥º«»XÂÕcÄ¹«U¬rÚ¢õUZÕNE¢¬Xº¬4ÚÈudê·Eä¬eV^²íKÉànâòV8sXÂ¥ÍfÇõ/ÂhJ³-J]ÓÓÎÁÕzO±<Eh\$å·¡ó\0Kë<bwñ>·øN\")]b£	â+zê.cS.¢iFç	ã£µQNQ«éV*ªéÛÎúÞO[X¤nx¤P	k­§oNø£}<aOò§IßÁh·ºT;òrñ¤VD6Qß;z]j×~':ë[Ivôó7^Ê§ÖÁjëºw[«ùæîºçÊÅ¥:u ÅDs#¦¿Î\\wµ<n|*áhëmÎKv;YÒ±Ú3á]«^#Zªj¥gy³jÄ§Y,%;3¾³ÊÚù×.ÈW\"Ã\$Ù3>gÚºÏÓÏ¦ªVTóZj¥hYÝjkD*!h&XzËiª¥+GV­\"¥æ¸Z:Ò¤§+NoG¥Zjj¥iÉ]ÊkOÐ_­Ö¬ÔmjIª¨§t¯#½[âj\rnãê©×ÐnßZ¥_,ÕéógÎÄ©:¹¼Å9Áÿ«[L2®W=TÔ×0®ãf¶\0P®U6\ns%7isYæ?£¿uá3¾½nb5¡«»X|G~l&×k¤¥·M§ ¯ú¶Ïy¡SÉ)Î]Ü­r·¶Ù¸µ¸æìÖêÅ?Õ}u'n0W-Î¹®æb·´Çªìõk?»vQý7Ü}p\nìõÀÍÙ®Z*»9)Êá5ÞZW­-ZB¸²:ìõã«W\0WZfpGpõîÍÙ®:Fpú¤äUÙëSN/Ï\\©Ü%s9¬S{§ ×8®ÏZÍasÊÛ+¢N^®9MÕ{P5Óç ×Q®ÔîJº¢«y§õÕè;Úîz¸ÂÕYÚV Ä3:ïDÅIÃ+çý¯£19M;º¥ô¨V´®\rQ{êÉÕ®¶Å+£FCLÄ¹N¥©Ô\\ùÞ)\$iÛN'\0¦°PÂõÊÇ]XÌ^s1òf&\"'<OøóÌ¡ËL\0¹\"@Ö¥%ä6úÂUAõ1ýi(zÌèÝ\rÒÕä±ÈbZÀ+IQOï3ºË\r=*Ä )ñ¨!Á Ð`ª¼h°,Ð«mGPCËA Ù²íA(ZÅ°%tì,h/ÁiÈk¬«¡XEJ6ð±IDèÈ¬\"\nïaU- «\nvy°_ÄÂÂÚ«¯k	a½B<ÇVÂÛD»/P»ôaîÁ)9Lã¶(Z°8êvvÃ¹Øk	§oÐZXkäÑå§|´&°.Âæ±C¹Øá°`1]7&Ä+H¤CBcXB7xXó|10¦ãa6°ubpJLÇ(·÷mbl8I¶*Rö@tk0¡¯ÅxXÛÁÓ;ÁÅ al]4s°t¿íÅªð0§c'´ælß`8M8ÀÃD4w`p?@706gÌ~K±\rÛ P´Ùbh\"&¯\nìqPDÈÐÎó\$Ð(Í0QP<÷°àÀã¬Q!X´xúÔ5R·`w/2°2#À¸ `¬»1/Ü\r¡Ö:Â²±¢£B7öV7ZgMYúH3È ÙbÎ	ZÁÓJÅöGâwÙgl^Æ-R-!Íl7Ì²LõÆ°<1 íQC/Õ²h¼à)ÏW6C	÷*dþ6]VK!mìØÜã05G\$Rµ4¯±=Cw&[æ«YP²dÉ³')VK,¨5eÈ\rÞÊèK+ï1X)bÛe)ÄâuF2A#EÑ&g~e¡yfp5¨lYl²Ô5õö¿Ö\nÂÙm}`(¬M Pl9Yÿfø±ýÖ]Vl-4Ã©¦«ÂÁ>`À/û³fPEi\0kvÆ\0ßfhS0±&ÍÂ¦lÍ¼¢#fuåÌû5	i%ÿ:Fdö9ØG<ä	{ö}ìÂs[7\0á¬Î3íft:+.Èp >ØÕ±£@!Pas6q,À³1bÇ¬ÅãZK°ê±Ü-úar`?RxXÁé¡ÏVïú#Ä¤ÔzÂ; ÀD¾H²Á1¥6D`þYê`÷RÅPÖ>-Æ!\$Ùù³ì×~ÏÐÅà`>Ùï³õhÔ0ô1À¬&\0ÃhëûIwlûZ\$\\\r¡8¶~,\nºo_áÀB2D´a1ê³àÇ©=¢v<ÏkF´p``kBF¶6 ÄÖ²hÆÉT TÖ	@?drÑåJÀH@1°G´dnÁÒwÆ%äÚJGÒ0bðTf]m(Øk´qg\\í½ó¸¬ë°ê ÈÑ3vk'ý^d´¨AXÿ~ÇWVsÂ*¼Ê±æd´ûM À¬@?²ÄÓ}§6\\m9<Î±iÝ§Ô¬h½^s}æ-¦[Ks±qãbÎÓ-öOORm8\$ÞywÄì##°@â·\0ôÒØ¤ 5F7ö¨ X\nÓÀ|JË/-SW!fÇ 0¶,w½¨D4Ù¡RU¥T´îÕðZXÇ=í`W\$@âÔ¥(XG§Òµa>Ö*ûY¶²\n³ü\nì!«[mjµ0,mu¬W@ FXúÚÎòðü=­ (¦ý­b¿ý<!\n\"ª83Ã'¦(RÝ\n>ù@¨W¦r!L£HÅkÌ\rE\nWÆÞ\r¢'FH\$£ääÀmÈ=ÔÛ¥{LY&ÑÜ£_\0ÆüÝ#¢ä[9\0¤\"ÔÒ@8ÄiKª¹ö0ÙlÑÐp\ngîÛ'qbFØyá«cl@9Û(#JU«Ý²{io­¥.{ÔÍ³4ÞVÍVnFÉxðÑüzÎ QàÞ\$kSa~Ê¨0s@£À«%y@À5HNÎÍ¦´@x#	Ü« /\\¥Ö?<hÚù¼IT :3Ã\n%¸");}else{header("Content-Type: image/gif");switch($_GET["file"]){case"plus.gif":echo"GIF89a\0\0\0001îîî\0\0\0\0\0!ù\0\0\0,\0\0\0\0\0\0!©ËíMñÌ*)¾oú¯) q¡eµî#ÄòLË\0;";break;case"cross.gif":echo"GIF89a\0\0\0001îîî\0\0\0\0\0!ù\0\0\0,\0\0\0\0\0\0#©Ëí#\naÖFo~yÃ._waá1ç±JîGÂL×6]\0\0;";break;case"up.gif":echo"GIF89a\0\0\0001îîî\0\0\0\0\0!ù\0\0\0,\0\0\0\0\0\0 ©ËíMQN\nï}ôa8yaÅ¶®\0Çò\0;";break;case"down.gif":echo"GIF89a\0\0\0001îîî\0\0\0\0\0!ù\0\0\0,\0\0\0\0\0\0 ©ËíMñÌ*)¾[Wþ\\¢ÇL&ÙÆ¶\0Çò\0;";break;case"arrow.gif":echo"GIF89a\0\n\0\0\0ÿÿÿ!ù\0\0\0,\0\0\0\0\0\n\0\0i±ªÓ²Þ»\0\0;";break;}}exit;}if($_GET["script"]=="version"){$kd=file_open_lock(get_temp_dir()."/adminer.version");if($kd)file_write_unlock($kd,serialize(array("signature"=>$_POST["signature"],"version"=>$_POST["version"])));exit;}global$b,$g,$m,$gc,$oc,$yc,$n,$md,$sd,$ba,$Td,$x,$ca,$oe,$sf,$dg,$Jh,$xd,$qi,$wi,$U,$Ki,$ia;if(!$_SERVER["REQUEST_URI"])$_SERVER["REQUEST_URI"]=$_SERVER["ORIG_PATH_INFO"];if(!strpos($_SERVER["REQUEST_URI"],'?')&&$_SERVER["QUERY_STRING"]!="")$_SERVER["REQUEST_URI"].="?$_SERVER[QUERY_STRING]";if($_SERVER["HTTP_X_FORWARDED_PREFIX"])$_SERVER["REQUEST_URI"]=$_SERVER["HTTP_X_FORWARDED_PREFIX"].$_SERVER["REQUEST_URI"];$ba=($_SERVER["HTTPS"]&&strcasecmp($_SERVER["HTTPS"],"off"))||ini_bool("session.cookie_secure");@ini_set("session.use_trans_sid",false);if(!defined("SID")){session_cache_limiter("");session_name("adminer_sid");$Qf=array(0,preg_replace('~\?.*~','',$_SERVER["REQUEST_URI"]),"",$ba);if(version_compare(PHP_VERSION,'5.2.0')>=0)$Qf[]=true;call_user_func_array('session_set_cookie_params',$Qf);session_start();}remove_slashes(array(&$_GET,&$_POST,&$_COOKIE),$Xc);if(0)set_magic_quotes_runtime(false);@set_time_limit(0);@ini_set("zend.ze1_compatibility_mode",false);@ini_set("precision",15);function
get_lang(){return'en';}function
lang($vi,$jf=null){if(is_array($vi)){$gg=($jf==1?0:1);$vi=$vi[$gg];}$vi=str_replace("%d","%s",$vi);$jf=format_number($jf);return
sprintf($vi,$jf);}if(extension_loaded('pdo')){class
Min_PDO
extends
PDO{var$_result,$server_info,$affected_rows,$errno,$error;function
__construct(){global$b;$gg=array_search("SQL",$b->operators);if($gg!==false)unset($b->operators[$gg]);}function
dsn($lc,$V,$E,$_f=array()){try{parent::__construct($lc,$V,$E,$_f);}catch(Exception$Cc){auth_error(h($Cc->getMessage()));}$this->setAttribute(13,array('Min_PDOStatement'));$this->server_info=@$this->getAttribute(4);}function
query($F,$Ei=false){$G=parent::query($F);$this->error="";if(!$G){list(,$this->errno,$this->error)=$this->errorInfo();if(!$this->error)$this->error='Unknown error.';return
false;}$this->store_result($G);return$G;}function
multi_query($F){return$this->_result=$this->query($F);}function
store_result($G=null){if(!$G){$G=$this->_result;if(!$G)return
false;}if($G->columnCount()){$G->num_rows=$G->rowCount();return$G;}$this->affected_rows=$G->rowCount();return
true;}function
next_result(){if(!$this->_result)return
false;$this->_result->_offset=0;return@$this->_result->nextRowset();}function
result($F,$o=0){$G=$this->query($F);if(!$G)return
false;$I=$G->fetch();return$I[$o];}}class
Min_PDOStatement
extends
PDOStatement{var$_offset=0,$num_rows;function
fetch_assoc(){return$this->fetch(2);}function
fetch_row(){return$this->fetch(3);}function
fetch_field(){$I=(object)$this->getColumnMeta($this->_offset++);$I->orgtable=$I->table;$I->orgname=$I->name;$I->charsetnr=(in_array("blob",(array)$I->flags)?63:0);return$I;}}}$gc=array();class
Min_SQL{var$_conn;function
__construct($g){$this->_conn=$g;}function
select($Q,$K,$Z,$pd,$Bf=array(),$z=1,$D=0,$og=false){global$b,$x;$ae=(count($pd)<count($K));$F=$b->selectQueryBuild($K,$Z,$pd,$Bf,$z,$D);if(!$F)$F="SELECT".limit(($_GET["page"]!="last"&&$z!=""&&$pd&&$ae&&$x=="sql"?"SQL_CALC_FOUND_ROWS ":"").implode(", ",$K)."\nFROM ".table($Q),($Z?"\nWHERE ".implode(" AND ",$Z):"").($pd&&$ae?"\nGROUP BY ".implode(", ",$pd):"").($Bf?"\nORDER BY ".implode(", ",$Bf):""),($z!=""?+$z:null),($D?$z*$D:0),"\n");$Fh=microtime(true);$H=$this->_conn->query($F);if($og)echo$b->selectQuery($F,$Fh,!$H);return$H;}function
delete($Q,$yg,$z=0){$F="FROM ".table($Q);return
queries("DELETE".($z?limit1($Q,$F,$yg):" $F$yg"));}function
update($Q,$N,$yg,$z=0,$L="\n"){$Xi=array();foreach($N
as$y=>$X)$Xi[]="$y = $X";$F=table($Q)." SET$L".implode(",$L",$Xi);return
queries("UPDATE".($z?limit1($Q,$F,$yg,$L):" $F$yg"));}function
insert($Q,$N){return
queries("INSERT INTO ".table($Q).($N?" (".implode(", ",array_keys($N)).")\nVALUES (".implode(", ",$N).")":" DEFAULT VALUES"));}function
insertUpdate($Q,$J,$mg){return
false;}function
begin(){return
queries("BEGIN");}function
commit(){return
queries("COMMIT");}function
rollback(){return
queries("ROLLBACK");}function
slowQuery($F,$hi){}function
convertSearch($u,$X,$o){return$u;}function
value($X,$o){return(method_exists($this->_conn,'value')?$this->_conn->value($X,$o):(is_resource($X)?stream_get_contents($X):$X));}function
quoteBinary($ah){return
q($ah);}function
warnings(){return'';}function
tableHelp($B){}}$gc["sqlite"]="SQLite 3";$gc["sqlite2"]="SQLite 2";if(isset($_GET["sqlite"])||isset($_GET["sqlite2"])){$jg=array((isset($_GET["sqlite"])?"SQLite3":"SQLite"),"PDO_SQLite");define("DRIVER",(isset($_GET["sqlite"])?"sqlite":"sqlite2"));if(class_exists(isset($_GET["sqlite"])?"SQLite3":"SQLiteDatabase")){if(isset($_GET["sqlite"])){class
Min_SQLite{var$extension="SQLite3",$server_info,$affected_rows,$errno,$error,$_link;function
__construct($Wc){$this->_link=new
SQLite3($Wc);$aj=$this->_link->version();$this->server_info=$aj["versionString"];}function
query($F){$G=@$this->_link->query($F);$this->error="";if(!$G){$this->errno=$this->_link->lastErrorCode();$this->error=$this->_link->lastErrorMsg();return
false;}elseif($G->numColumns())return
new
Min_Result($G);$this->affected_rows=$this->_link->changes();return
true;}function
quote($P){return(is_utf8($P)?"'".$this->_link->escapeString($P)."'":"x'".reset(unpack('H*',$P))."'");}function
store_result(){return$this->_result;}function
result($F,$o=0){$G=$this->query($F);if(!is_object($G))return
false;$I=$G->_result->fetchArray();return$I[$o];}}class
Min_Result{var$_result,$_offset=0,$num_rows;function
__construct($G){$this->_result=$G;}function
fetch_assoc(){return$this->_result->fetchArray(SQLITE3_ASSOC);}function
fetch_row(){return$this->_result->fetchArray(SQLITE3_NUM);}function
fetch_field(){$e=$this->_offset++;$T=$this->_result->columnType($e);return(object)array("name"=>$this->_result->columnName($e),"type"=>$T,"charsetnr"=>($T==SQLITE3_BLOB?63:0),);}function
__desctruct(){return$this->_result->finalize();}}}else{class
Min_SQLite{var$extension="SQLite",$server_info,$affected_rows,$error,$_link;function
__construct($Wc){$this->server_info=sqlite_libversion();$this->_link=new
SQLiteDatabase($Wc);}function
query($F,$Ei=false){$Te=($Ei?"unbufferedQuery":"query");$G=@$this->_link->$Te($F,SQLITE_BOTH,$n);$this->error="";if(!$G){$this->error=$n;return
false;}elseif($G===true){$this->affected_rows=$this->changes();return
true;}return
new
Min_Result($G);}function
quote($P){return"'".sqlite_escape_string($P)."'";}function
store_result(){return$this->_result;}function
result($F,$o=0){$G=$this->query($F);if(!is_object($G))return
false;$I=$G->_result->fetch();return$I[$o];}}class
Min_Result{var$_result,$_offset=0,$num_rows;function
__construct($G){$this->_result=$G;if(method_exists($G,'numRows'))$this->num_rows=$G->numRows();}function
fetch_assoc(){$I=$this->_result->fetch(SQLITE_ASSOC);if(!$I)return
false;$H=array();foreach($I
as$y=>$X)$H[($y[0]=='"'?idf_unescape($y):$y)]=$X;return$H;}function
fetch_row(){return$this->_result->fetch(SQLITE_NUM);}function
fetch_field(){$B=$this->_result->fieldName($this->_offset++);$cg='(\[.*]|"(?:[^"]|"")*"|(.+))';if(preg_match("~^($cg\\.)?$cg\$~",$B,$A)){$Q=($A[3]!=""?$A[3]:idf_unescape($A[2]));$B=($A[5]!=""?$A[5]:idf_unescape($A[4]));}return(object)array("name"=>$B,"orgname"=>$B,"orgtable"=>$Q,);}}}}elseif(extension_loaded("pdo_sqlite")){class
Min_SQLite
extends
Min_PDO{var$extension="PDO_SQLite";function
__construct($Wc){$this->dsn(DRIVER.":$Wc","","");}}}if(class_exists("Min_SQLite")){class
Min_DB
extends
Min_SQLite{function
__construct(){parent::__construct(":memory:");$this->query("PRAGMA foreign_keys = 1");}function
select_db($Wc){if(is_readable($Wc)&&$this->query("ATTACH ".$this->quote(preg_match("~(^[/\\\\]|:)~",$Wc)?$Wc:dirname($_SERVER["SCRIPT_FILENAME"])."/$Wc")." AS a")){parent::__construct($Wc);$this->query("PRAGMA foreign_keys = 1");return
true;}return
false;}function
multi_query($F){return$this->_result=$this->query($F);}function
next_result(){return
false;}}}class
Min_Driver
extends
Min_SQL{function
insertUpdate($Q,$J,$mg){$Xi=array();foreach($J
as$N)$Xi[]="(".implode(", ",$N).")";return
queries("REPLACE INTO ".table($Q)." (".implode(", ",array_keys(reset($J))).") VALUES\n".implode(",\n",$Xi));}function
tableHelp($B){if($B=="sqlite_sequence")return"fileformat2.html#seqtab";if($B=="sqlite_master")return"fileformat2.html#$B";}}function
idf_escape($u){return'"'.str_replace('"','""',$u).'"';}function
table($u){return
idf_escape($u);}function
connect(){global$b;list(,,$E)=$b->credentials();if($E!="")return'Database does not support password.';return
new
Min_DB;}function
get_databases(){return
array();}function
limit($F,$Z,$z,$C=0,$L=" "){return" $F$Z".($z!==null?$L."LIMIT $z".($C?" OFFSET $C":""):"");}function
limit1($Q,$F,$Z,$L="\n"){global$g;return(preg_match('~^INTO~',$F)||$g->result("SELECT sqlite_compileoption_used('ENABLE_UPDATE_DELETE_LIMIT')")?limit($F,$Z,1,0,$L):" $F WHERE rowid = (SELECT rowid FROM ".table($Q).$Z.$L."LIMIT 1)");}function
db_collation($l,$pb){global$g;return$g->result("PRAGMA encoding");}function
engines(){return
array();}function
logged_user(){return
get_current_user();}function
tables_list(){return
get_key_vals("SELECT name, type FROM sqlite_master WHERE type IN ('table', 'view') ORDER BY (name = 'sqlite_sequence'), name");}function
count_tables($k){return
array();}function
table_status($B=""){global$g;$H=array();foreach(get_rows("SELECT name AS Name, type AS Engine, 'rowid' AS Oid, '' AS Auto_increment FROM sqlite_master WHERE type IN ('table', 'view') ".($B!=""?"AND name = ".q($B):"ORDER BY name"))as$I){$I["Rows"]=$g->result("SELECT COUNT(*) FROM ".idf_escape($I["Name"]));$H[$I["Name"]]=$I;}foreach(get_rows("SELECT * FROM sqlite_sequence",null,"")as$I)$H[$I["name"]]["Auto_increment"]=$I["seq"];return($B!=""?$H[$B]:$H);}function
is_view($R){return$R["Engine"]=="view";}function
fk_support($R){global$g;return!$g->result("SELECT sqlite_compileoption_used('OMIT_FOREIGN_KEY')");}function
fields($Q){global$g;$H=array();$mg="";foreach(get_rows("PRAGMA table_info(".table($Q).")")as$I){$B=$I["name"];$T=strtolower($I["type"]);$Ub=$I["dflt_value"];$H[$B]=array("field"=>$B,"type"=>(preg_match('~int~i',$T)?"integer":(preg_match('~char|clob|text~i',$T)?"text":(preg_match('~blob~i',$T)?"blob":(preg_match('~real|floa|doub~i',$T)?"real":"numeric")))),"full_type"=>$T,"default"=>(preg_match("~'(.*)'~",$Ub,$A)?str_replace("''","'",$A[1]):($Ub=="NULL"?null:$Ub)),"null"=>!$I["notnull"],"privileges"=>array("select"=>1,"insert"=>1,"update"=>1),"primary"=>$I["pk"],);if($I["pk"]){if($mg!="")$H[$mg]["auto_increment"]=false;elseif(preg_match('~^integer$~i',$T))$H[$B]["auto_increment"]=true;$mg=$B;}}$Ah=$g->result("SELECT sql FROM sqlite_master WHERE type = 'table' AND name = ".q($Q));preg_match_all('~(("[^"]*+")+|[a-z0-9_]+)\s+text\s+COLLATE\s+(\'[^\']+\'|\S+)~i',$Ah,$Fe,PREG_SET_ORDER);foreach($Fe
as$A){$B=str_replace('""','"',preg_replace('~^"|"$~','',$A[1]));if($H[$B])$H[$B]["collation"]=trim($A[3],"'");}return$H;}function
indexes($Q,$h=null){global$g;if(!is_object($h))$h=$g;$H=array();$Ah=$h->result("SELECT sql FROM sqlite_master WHERE type = 'table' AND name = ".q($Q));if(preg_match('~\bPRIMARY\s+KEY\s*\((([^)"]+|"[^"]*"|`[^`]*`)++)~i',$Ah,$A)){$H[""]=array("type"=>"PRIMARY","columns"=>array(),"lengths"=>array(),"descs"=>array());preg_match_all('~((("[^"]*+")+|(?:`[^`]*+`)+)|(\S+))(\s+(ASC|DESC))?(,\s*|$)~i',$A[1],$Fe,PREG_SET_ORDER);foreach($Fe
as$A){$H[""]["columns"][]=idf_unescape($A[2]).$A[4];$H[""]["descs"][]=(preg_match('~DESC~i',$A[5])?'1':null);}}if(!$H){foreach(fields($Q)as$B=>$o){if($o["primary"])$H[""]=array("type"=>"PRIMARY","columns"=>array($B),"lengths"=>array(),"descs"=>array(null));}}$Dh=get_key_vals("SELECT name, sql FROM sqlite_master WHERE type = 'index' AND tbl_name = ".q($Q),$h);foreach(get_rows("PRAGMA index_list(".table($Q).")",$h)as$I){$B=$I["name"];$v=array("type"=>($I["unique"]?"UNIQUE":"INDEX"));$v["lengths"]=array();$v["descs"]=array();foreach(get_rows("PRAGMA index_info(".idf_escape($B).")",$h)as$Zg){$v["columns"][]=$Zg["name"];$v["descs"][]=null;}if(preg_match('~^CREATE( UNIQUE)? INDEX '.preg_quote(idf_escape($B).' ON '.idf_escape($Q),'~').' \((.*)\)$~i',$Dh[$B],$Jg)){preg_match_all('/("[^"]*+")+( DESC)?/',$Jg[2],$Fe);foreach($Fe[2]as$y=>$X){if($X)$v["descs"][$y]='1';}}if(!$H[""]||$v["type"]!="UNIQUE"||$v["columns"]!=$H[""]["columns"]||$v["descs"]!=$H[""]["descs"]||!preg_match("~^sqlite_~",$B))$H[$B]=$v;}return$H;}function
foreign_keys($Q){$H=array();foreach(get_rows("PRAGMA foreign_key_list(".table($Q).")")as$I){$q=&$H[$I["id"]];if(!$q)$q=$I;$q["source"][]=$I["from"];$q["target"][]=$I["to"];}return$H;}function
view($B){global$g;return
array("select"=>preg_replace('~^(?:[^`"[]+|`[^`]*`|"[^"]*")* AS\s+~iU','',$g->result("SELECT sql FROM sqlite_master WHERE name = ".q($B))));}function
collations(){return(isset($_GET["create"])?get_vals("PRAGMA collation_list",1):array());}function
information_schema($l){return
false;}function
error(){global$g;return
h($g->error);}function
check_sqlite_name($B){global$g;$Mc="db|sdb|sqlite";if(!preg_match("~^[^\\0]*\\.($Mc)\$~",$B)){$g->error=sprintf('Please use one of the extensions %s.',str_replace("|",", ",$Mc));return
false;}return
true;}function
create_database($l,$d){global$g;if(file_exists($l)){$g->error='File exists.';return
false;}if(!check_sqlite_name($l))return
false;try{$_=new
Min_SQLite($l);}catch(Exception$Cc){$g->error=$Cc->getMessage();return
false;}$_->query('PRAGMA encoding = "UTF-8"');$_->query('CREATE TABLE adminer (i)');$_->query('DROP TABLE adminer');return
true;}function
drop_databases($k){global$g;$g->__construct(":memory:");foreach($k
as$l){if(!@unlink($l)){$g->error='File exists.';return
false;}}return
true;}function
rename_database($B,$d){global$g;if(!check_sqlite_name($B))return
false;$g->__construct(":memory:");$g->error='File exists.';return@rename(DB,$B);}function
auto_increment(){return" PRIMARY KEY".(DRIVER=="sqlite"?" AUTOINCREMENT":"");}function
alter_table($Q,$B,$p,$ed,$ub,$wc,$d,$Ma,$Wf){global$g;$Qi=($Q==""||$ed);foreach($p
as$o){if($o[0]!=""||!$o[1]||$o[2]){$Qi=true;break;}}$c=array();$Kf=array();foreach($p
as$o){if($o[1]){$c[]=($Qi?$o[1]:"ADD ".implode($o[1]));if($o[0]!="")$Kf[$o[0]]=$o[1][0];}}if(!$Qi){foreach($c
as$X){if(!queries("ALTER TABLE ".table($Q)." $X"))return
false;}if($Q!=$B&&!queries("ALTER TABLE ".table($Q)." RENAME TO ".table($B)))return
false;}elseif(!recreate_table($Q,$B,$c,$Kf,$ed,$Ma))return
false;if($Ma){queries("BEGIN");queries("UPDATE sqlite_sequence SET seq = $Ma WHERE name = ".q($B));if(!$g->affected_rows)queries("INSERT INTO sqlite_sequence (name, seq) VALUES (".q($B).", $Ma)");queries("COMMIT");}return
true;}function
recreate_table($Q,$B,$p,$Kf,$ed,$Ma,$w=array()){global$g;if($Q!=""){if(!$p){foreach(fields($Q)as$y=>$o){if($w)$o["auto_increment"]=0;$p[]=process_field($o,$o);$Kf[$y]=idf_escape($y);}}$ng=false;foreach($p
as$o){if($o[6])$ng=true;}$jc=array();foreach($w
as$y=>$X){if($X[2]=="DROP"){$jc[$X[1]]=true;unset($w[$y]);}}foreach(indexes($Q)as$ie=>$v){$f=array();foreach($v["columns"]as$y=>$e){if(!$Kf[$e])continue
2;$f[]=$Kf[$e].($v["descs"][$y]?" DESC":"");}if(!$jc[$ie]){if($v["type"]!="PRIMARY"||!$ng)$w[]=array($v["type"],$ie,$f);}}foreach($w
as$y=>$X){if($X[0]=="PRIMARY"){unset($w[$y]);$ed[]="  PRIMARY KEY (".implode(", ",$X[2]).")";}}foreach(foreign_keys($Q)as$ie=>$q){foreach($q["source"]as$y=>$e){if(!$Kf[$e])continue
2;$q["source"][$y]=idf_unescape($Kf[$e]);}if(!isset($ed[" $ie"]))$ed[]=" ".format_foreign_key($q);}queries("BEGIN");}foreach($p
as$y=>$o)$p[$y]="  ".implode($o);$p=array_merge($p,array_filter($ed));$bi=($Q==$B?"adminer_$B":$B);if(!queries("CREATE TABLE ".table($bi)." (\n".implode(",\n",$p)."\n)"))return
false;if($Q!=""){if($Kf&&!queries("INSERT INTO ".table($bi)." (".implode(", ",$Kf).") SELECT ".implode(", ",array_map('idf_escape',array_keys($Kf)))." FROM ".table($Q)))return
false;$Bi=array();foreach(triggers($Q)as$_i=>$ii){$zi=trigger($_i);$Bi[]="CREATE TRIGGER ".idf_escape($_i)." ".implode(" ",$ii)." ON ".table($B)."\n$zi[Statement]";}$Ma=$Ma?0:$g->result("SELECT seq FROM sqlite_sequence WHERE name = ".q($Q));if(!queries("DROP TABLE ".table($Q))||($Q==$B&&!queries("ALTER TABLE ".table($bi)." RENAME TO ".table($B)))||!alter_indexes($B,$w))return
false;if($Ma)queries("UPDATE sqlite_sequence SET seq = $Ma WHERE name = ".q($B));foreach($Bi
as$zi){if(!queries($zi))return
false;}queries("COMMIT");}return
true;}function
index_sql($Q,$T,$B,$f){return"CREATE $T ".($T!="INDEX"?"INDEX ":"").idf_escape($B!=""?$B:uniqid($Q."_"))." ON ".table($Q)." $f";}function
alter_indexes($Q,$c){foreach($c
as$mg){if($mg[0]=="PRIMARY")return
recreate_table($Q,$Q,array(),array(),array(),0,$c);}foreach(array_reverse($c)as$X){if(!queries($X[2]=="DROP"?"DROP INDEX ".idf_escape($X[1]):index_sql($Q,$X[0],$X[1],"(".implode(", ",$X[2]).")")))return
false;}return
true;}function
truncate_tables($S){return
apply_queries("DELETE FROM",$S);}function
drop_views($cj){return
apply_queries("DROP VIEW",$cj);}function
drop_tables($S){return
apply_queries("DROP TABLE",$S);}function
move_tables($S,$cj,$Zh){return
false;}function
trigger($B){global$g;if($B=="")return
array("Statement"=>"BEGIN\n\t;\nEND");$u='(?:[^`"\s]+|`[^`]*`|"[^"]*")+';$Ai=trigger_options();preg_match("~^CREATE\\s+TRIGGER\\s*$u\\s*(".implode("|",$Ai["Timing"]).")\\s+([a-z]+)(?:\\s+OF\\s+($u))?\\s+ON\\s*$u\\s*(?:FOR\\s+EACH\\s+ROW\\s)?(.*)~is",$g->result("SELECT sql FROM sqlite_master WHERE type = 'trigger' AND name = ".q($B)),$A);$lf=$A[3];return
array("Timing"=>strtoupper($A[1]),"Event"=>strtoupper($A[2]).($lf?" OF":""),"Of"=>($lf[0]=='`'||$lf[0]=='"'?idf_unescape($lf):$lf),"Trigger"=>$B,"Statement"=>$A[4],);}function
triggers($Q){$H=array();$Ai=trigger_options();foreach(get_rows("SELECT * FROM sqlite_master WHERE type = 'trigger' AND tbl_name = ".q($Q))as$I){preg_match('~^CREATE\s+TRIGGER\s*(?:[^`"\s]+|`[^`]*`|"[^"]*")+\s*('.implode("|",$Ai["Timing"]).')\s*(.*?)\s+ON\b~i',$I["sql"],$A);$H[$I["name"]]=array($A[1],$A[2]);}return$H;}function
trigger_options(){return
array("Timing"=>array("BEFORE","AFTER","INSTEAD OF"),"Event"=>array("INSERT","UPDATE","UPDATE OF","DELETE"),"Type"=>array("FOR EACH ROW"),);}function
begin(){return
queries("BEGIN");}function
last_id(){global$g;return$g->result("SELECT LAST_INSERT_ROWID()");}function
explain($g,$F){return$g->query("EXPLAIN QUERY PLAN $F");}function
found_rows($R,$Z){}function
types(){return
array();}function
schemas(){return
array();}function
get_schema(){return"";}function
set_schema($dh){return
true;}function
create_sql($Q,$Ma,$Kh){global$g;$H=$g->result("SELECT sql FROM sqlite_master WHERE type IN ('table', 'view') AND name = ".q($Q));foreach(indexes($Q)as$B=>$v){if($B=='')continue;$H.=";\n\n".index_sql($Q,$v['type'],$B,"(".implode(", ",array_map('idf_escape',$v['columns'])).")");}return$H;}function
truncate_sql($Q){return"DELETE FROM ".table($Q);}function
use_sql($j){}function
trigger_sql($Q){return
implode(get_vals("SELECT sql || ';;\n' FROM sqlite_master WHERE type = 'trigger' AND tbl_name = ".q($Q)));}function
show_variables(){global$g;$H=array();foreach(array("auto_vacuum","cache_size","count_changes","default_cache_size","empty_result_callbacks","encoding","foreign_keys","full_column_names","fullfsync","journal_mode","journal_size_limit","legacy_file_format","locking_mode","page_size","max_page_count","read_uncommitted","recursive_triggers","reverse_unordered_selects","secure_delete","short_column_names","synchronous","temp_store","temp_store_directory","schema_version","integrity_check","quick_check")as$y)$H[$y]=$g->result("PRAGMA $y");return$H;}function
show_status(){$H=array();foreach(get_vals("PRAGMA compile_options")as$zf){list($y,$X)=explode("=",$zf,2);$H[$y]=$X;}return$H;}function
convert_field($o){}function
unconvert_field($o,$H){return$H;}function
support($Rc){return
preg_match('~^(columns|database|drop_col|dump|indexes|descidx|move_col|sql|status|table|trigger|variables|view|view_trigger)$~',$Rc);}$x="sqlite";$U=array("integer"=>0,"real"=>0,"numeric"=>0,"text"=>0,"blob"=>0);$Jh=array_keys($U);$Ki=array();$xf=array("=","<",">","<=",">=","!=","LIKE","LIKE %%","IN","IS NULL","NOT LIKE","NOT IN","IS NOT NULL","SQL");$md=array("hex","length","lower","round","unixepoch","upper");$sd=array("avg","count","count distinct","group_concat","max","min","sum");$oc=array(array(),array("integer|real|numeric"=>"+/-","text"=>"||",));}$gc["pgsql"]="PostgreSQL";if(isset($_GET["pgsql"])){$jg=array("PgSQL","PDO_PgSQL");define("DRIVER","pgsql");if(extension_loaded("pgsql")){class
Min_DB{var$extension="PgSQL",$_link,$_result,$_string,$_database=true,$server_info,$affected_rows,$error,$timeout;function
_error($zc,$n){if(ini_bool("html_errors"))$n=html_entity_decode(strip_tags($n));$n=preg_replace('~^[^:]*: ~','',$n);$this->error=$n;}function
connect($M,$V,$E){global$b;$l=$b->database();set_error_handler(array($this,'_error'));$this->_string="host='".str_replace(":","' port='",addcslashes($M,"'\\"))."' user='".addcslashes($V,"'\\")."' password='".addcslashes($E,"'\\")."'";$this->_link=@pg_connect("$this->_string dbname='".($l!=""?addcslashes($l,"'\\"):"postgres")."'",PGSQL_CONNECT_FORCE_NEW);if(!$this->_link&&$l!=""){$this->_database=false;$this->_link=@pg_connect("$this->_string dbname='postgres'",PGSQL_CONNECT_FORCE_NEW);}restore_error_handler();if($this->_link){$aj=pg_version($this->_link);$this->server_info=$aj["server"];pg_set_client_encoding($this->_link,"UTF8");}return(bool)$this->_link;}function
quote($P){return"'".pg_escape_string($this->_link,$P)."'";}function
value($X,$o){return($o["type"]=="bytea"?pg_unescape_bytea($X):$X);}function
quoteBinary($P){return"'".pg_escape_bytea($this->_link,$P)."'";}function
select_db($j){global$b;if($j==$b->database())return$this->_database;$H=@pg_connect("$this->_string dbname='".addcslashes($j,"'\\")."'",PGSQL_CONNECT_FORCE_NEW);if($H)$this->_link=$H;return$H;}function
close(){$this->_link=@pg_connect("$this->_string dbname='postgres'");}function
query($F,$Ei=false){$G=@pg_query($this->_link,$F);$this->error="";if(!$G){$this->error=pg_last_error($this->_link);$H=false;}elseif(!pg_num_fields($G)){$this->affected_rows=pg_affected_rows($G);$H=true;}else$H=new
Min_Result($G);if($this->timeout){$this->timeout=0;$this->query("RESET statement_timeout");}return$H;}function
multi_query($F){return$this->_result=$this->query($F);}function
store_result(){return$this->_result;}function
next_result(){return
false;}function
result($F,$o=0){$G=$this->query($F);if(!$G||!$G->num_rows)return
false;return
pg_fetch_result($G->_result,0,$o);}function
warnings(){return
h(pg_last_notice($this->_link));}}class
Min_Result{var$_result,$_offset=0,$num_rows;function
__construct($G){$this->_result=$G;$this->num_rows=pg_num_rows($G);}function
fetch_assoc(){return
pg_fetch_assoc($this->_result);}function
fetch_row(){return
pg_fetch_row($this->_result);}function
fetch_field(){$e=$this->_offset++;$H=new
stdClass;if(function_exists('pg_field_table'))$H->orgtable=pg_field_table($this->_result,$e);$H->name=pg_field_name($this->_result,$e);$H->orgname=$H->name;$H->type=pg_field_type($this->_result,$e);$H->charsetnr=($H->type=="bytea"?63:0);return$H;}function
__destruct(){pg_free_result($this->_result);}}}elseif(extension_loaded("pdo_pgsql")){class
Min_DB
extends
Min_PDO{var$extension="PDO_PgSQL",$timeout;function
connect($M,$V,$E){global$b;$l=$b->database();$P="pgsql:host='".str_replace(":","' port='",addcslashes($M,"'\\"))."' options='-c client_encoding=utf8'";$this->dsn("$P dbname='".($l!=""?addcslashes($l,"'\\"):"postgres")."'",$V,$E);return
true;}function
select_db($j){global$b;return($b->database()==$j);}function
quoteBinary($ah){return
q($ah);}function
query($F,$Ei=false){$H=parent::query($F,$Ei);if($this->timeout){$this->timeout=0;parent::query("RESET statement_timeout");}return$H;}function
warnings(){return'';}function
close(){}}}class
Min_Driver
extends
Min_SQL{function
insertUpdate($Q,$J,$mg){global$g;foreach($J
as$N){$Li=array();$Z=array();foreach($N
as$y=>$X){$Li[]="$y = $X";if(isset($mg[idf_unescape($y)]))$Z[]="$y = $X";}if(!(($Z&&queries("UPDATE ".table($Q)." SET ".implode(", ",$Li)." WHERE ".implode(" AND ",$Z))&&$g->affected_rows)||queries("INSERT INTO ".table($Q)." (".implode(", ",array_keys($N)).") VALUES (".implode(", ",$N).")")))return
false;}return
true;}function
slowQuery($F,$hi){$this->_conn->query("SET statement_timeout = ".(1000*$hi));$this->_conn->timeout=1000*$hi;return$F;}function
convertSearch($u,$X,$o){return(preg_match('~char|text'.(!preg_match('~LIKE~',$X["op"])?'|date|time(stamp)?|boolean|uuid|'.number_type():'').'~',$o["type"])?$u:"CAST($u AS text)");}function
quoteBinary($ah){return$this->_conn->quoteBinary($ah);}function
warnings(){return$this->_conn->warnings();}function
tableHelp($B){$ye=array("information_schema"=>"infoschema","pg_catalog"=>"catalog",);$_=$ye[$_GET["ns"]];if($_)return"$_-".str_replace("_","-",$B).".html";}}function
idf_escape($u){return'"'.str_replace('"','""',$u).'"';}function
table($u){return
idf_escape($u);}function
connect(){global$b,$U,$Jh;$g=new
Min_DB;$Ib=$b->credentials();if($g->connect($Ib[0],$Ib[1],$Ib[2])){if(min_version(9,0,$g)){$g->query("SET application_name = 'Adminer'");if(min_version(9.2,0,$g)){$Jh['Strings'][]="json";$U["json"]=4294967295;if(min_version(9.4,0,$g)){$Jh['Strings'][]="jsonb";$U["jsonb"]=4294967295;}}}return$g;}return$g->error;}function
get_databases(){return
get_vals("SELECT datname FROM pg_database WHERE has_database_privilege(datname, 'CONNECT') ORDER BY datname");}function
limit($F,$Z,$z,$C=0,$L=" "){return" $F$Z".($z!==null?$L."LIMIT $z".($C?" OFFSET $C":""):"");}function
limit1($Q,$F,$Z,$L="\n"){return(preg_match('~^INTO~',$F)?limit($F,$Z,1,0,$L):" $F".(is_view(table_status1($Q))?$Z:" WHERE ctid = (SELECT ctid FROM ".table($Q).$Z.$L."LIMIT 1)"));}function
db_collation($l,$pb){global$g;return$g->result("SHOW LC_COLLATE");}function
engines(){return
array();}function
logged_user(){global$g;return$g->result("SELECT user");}function
tables_list(){$F="SELECT table_name, table_type FROM information_schema.tables WHERE table_schema = current_schema()";if(support('materializedview'))$F.="
UNION ALL
SELECT matviewname, 'MATERIALIZED VIEW'
FROM pg_matviews
WHERE schemaname = current_schema()";$F.="
ORDER BY 1";return
get_key_vals($F);}function
count_tables($k){return
array();}function
table_status($B=""){$H=array();foreach(get_rows("SELECT c.relname AS \"Name\", CASE c.relkind WHEN 'r' THEN 'table' WHEN 'm' THEN 'materialized view' ELSE 'view' END AS \"Engine\", pg_relation_size(c.oid) AS \"Data_length\", pg_total_relation_size(c.oid) - pg_relation_size(c.oid) AS \"Index_length\", obj_description(c.oid, 'pg_class') AS \"Comment\", ".(min_version(12)?"''":"CASE WHEN c.relhasoids THEN 'oid' ELSE '' END")." AS \"Oid\", c.reltuples as \"Rows\", n.nspname
FROM pg_class c
JOIN pg_namespace n ON(n.nspname = current_schema() AND n.oid = c.relnamespace)
WHERE relkind IN ('r', 'm', 'v', 'f')
".($B!=""?"AND relname = ".q($B):"ORDER BY relname"))as$I)$H[$I["Name"]]=$I;return($B!=""?$H[$B]:$H);}function
is_view($R){return
in_array($R["Engine"],array("view","materialized view"));}function
fk_support($R){return
true;}function
fields($Q){$H=array();$Ca=array('timestamp without time zone'=>'timestamp','timestamp with time zone'=>'timestamptz',);$Fd=min_version(10)?"(a.attidentity = 'd')::int":'0';foreach(get_rows("SELECT a.attname AS field, format_type(a.atttypid, a.atttypmod) AS full_type, pg_get_expr(d.adbin, d.adrelid) AS default, a.attnotnull::int, col_description(c.oid, a.attnum) AS comment, $Fd AS identity
FROM pg_class c
JOIN pg_namespace n ON c.relnamespace = n.oid
JOIN pg_attribute a ON c.oid = a.attrelid
LEFT JOIN pg_attrdef d ON c.oid = d.adrelid AND a.attnum = d.adnum
WHERE c.relname = ".q($Q)."
AND n.nspname = current_schema()
AND NOT a.attisdropped
AND a.attnum > 0
ORDER BY a.attnum")as$I){preg_match('~([^([]+)(\((.*)\))?([a-z ]+)?((\[[0-9]*])*)$~',$I["full_type"],$A);list(,$T,$ve,$I["length"],$wa,$Fa)=$A;$I["length"].=$Fa;$eb=$T.$wa;if(isset($Ca[$eb])){$I["type"]=$Ca[$eb];$I["full_type"]=$I["type"].$ve.$Fa;}else{$I["type"]=$T;$I["full_type"]=$I["type"].$ve.$wa.$Fa;}if($I['identity'])$I['default']='GENERATED BY DEFAULT AS IDENTITY';$I["null"]=!$I["attnotnull"];$I["auto_increment"]=$I['identity']||preg_match('~^nextval\(~i',$I["default"]);$I["privileges"]=array("insert"=>1,"select"=>1,"update"=>1);if(preg_match('~(.+)::[^)]+(.*)~',$I["default"],$A))$I["default"]=($A[1]=="NULL"?null:(($A[1][0]=="'"?idf_unescape($A[1]):$A[1]).$A[2]));$H[$I["field"]]=$I;}return$H;}function
indexes($Q,$h=null){global$g;if(!is_object($h))$h=$g;$H=array();$Sh=$h->result("SELECT oid FROM pg_class WHERE relnamespace = (SELECT oid FROM pg_namespace WHERE nspname = current_schema()) AND relname = ".q($Q));$f=get_key_vals("SELECT attnum, attname FROM pg_attribute WHERE attrelid = $Sh AND attnum > 0",$h);foreach(get_rows("SELECT relname, indisunique::int, indisprimary::int, indkey, indoption , (indpred IS NOT NULL)::int as indispartial FROM pg_index i, pg_class ci WHERE i.indrelid = $Sh AND ci.oid = i.indexrelid",$h)as$I){$Kg=$I["relname"];$H[$Kg]["type"]=($I["indispartial"]?"INDEX":($I["indisprimary"]?"PRIMARY":($I["indisunique"]?"UNIQUE":"INDEX")));$H[$Kg]["columns"]=array();foreach(explode(" ",$I["indkey"])as$Pd)$H[$Kg]["columns"][]=$f[$Pd];$H[$Kg]["descs"]=array();foreach(explode(" ",$I["indoption"])as$Qd)$H[$Kg]["descs"][]=($Qd&1?'1':null);$H[$Kg]["lengths"]=array();}return$H;}function
foreign_keys($Q){global$sf;$H=array();foreach(get_rows("SELECT conname, condeferrable::int AS deferrable, pg_get_constraintdef(oid) AS definition
FROM pg_constraint
WHERE conrelid = (SELECT pc.oid FROM pg_class AS pc INNER JOIN pg_namespace AS pn ON (pn.oid = pc.relnamespace) WHERE pc.relname = ".q($Q)." AND pn.nspname = current_schema())
AND contype = 'f'::char
ORDER BY conkey, conname")as$I){if(preg_match('~FOREIGN KEY\s*\((.+)\)\s*REFERENCES (.+)\((.+)\)(.*)$~iA',$I['definition'],$A)){$I['source']=array_map('trim',explode(',',$A[1]));if(preg_match('~^(("([^"]|"")+"|[^"]+)\.)?"?("([^"]|"")+"|[^"]+)$~',$A[2],$Ee)){$I['ns']=str_replace('""','"',preg_replace('~^"(.+)"$~','\1',$Ee[2]));$I['table']=str_replace('""','"',preg_replace('~^"(.+)"$~','\1',$Ee[4]));}$I['target']=array_map('trim',explode(',',$A[3]));$I['on_delete']=(preg_match("~ON DELETE ($sf)~",$A[4],$Ee)?$Ee[1]:'NO ACTION');$I['on_update']=(preg_match("~ON UPDATE ($sf)~",$A[4],$Ee)?$Ee[1]:'NO ACTION');$H[$I['conname']]=$I;}}return$H;}function
view($B){global$g;return
array("select"=>trim($g->result("SELECT pg_get_viewdef(".$g->result("SELECT oid FROM pg_class WHERE relname = ".q($B)).")")));}function
collations(){return
array();}function
information_schema($l){return($l=="information_schema");}function
error(){global$g;$H=h($g->error);if(preg_match('~^(.*\n)?([^\n]*)\n( *)\^(\n.*)?$~s',$H,$A))$H=$A[1].preg_replace('~((?:[^&]|&[^;]*;){'.strlen($A[3]).'})(.*)~','\1<b>\2</b>',$A[2]).$A[4];return
nl_br($H);}function
create_database($l,$d){return
queries("CREATE DATABASE ".idf_escape($l).($d?" ENCODING ".idf_escape($d):""));}function
drop_databases($k){global$g;$g->close();return
apply_queries("DROP DATABASE",$k,'idf_escape');}function
rename_database($B,$d){return
queries("ALTER DATABASE ".idf_escape(DB)." RENAME TO ".idf_escape($B));}function
auto_increment(){return"";}function
alter_table($Q,$B,$p,$ed,$ub,$wc,$d,$Ma,$Wf){$c=array();$xg=array();if($Q!=""&&$Q!=$B)$xg[]="ALTER TABLE ".table($Q)." RENAME TO ".table($B);foreach($p
as$o){$e=idf_escape($o[0]);$X=$o[1];if(!$X)$c[]="DROP $e";else{$Wi=$X[5];unset($X[5]);if(isset($X[6])&&$o[0]=="")$X[1]=($X[1]=="bigint"?" big":" ")."serial";if($o[0]=="")$c[]=($Q!=""?"ADD ":"  ").implode($X);else{if($e!=$X[0])$xg[]="ALTER TABLE ".table($B)." RENAME $e TO $X[0]";$c[]="ALTER $e TYPE$X[1]";if(!$X[6]){$c[]="ALTER $e ".($X[3]?"SET$X[3]":"DROP DEFAULT");$c[]="ALTER $e ".($X[2]==" NULL"?"DROP NOT":"SET").$X[2];}}if($o[0]!=""||$Wi!="")$xg[]="COMMENT ON COLUMN ".table($B).".$X[0] IS ".($Wi!=""?substr($Wi,9):"''");}}$c=array_merge($c,$ed);if($Q=="")array_unshift($xg,"CREATE TABLE ".table($B)." (\n".implode(",\n",$c)."\n)");elseif($c)array_unshift($xg,"ALTER TABLE ".table($Q)."\n".implode(",\n",$c));if($Q!=""||$ub!="")$xg[]="COMMENT ON TABLE ".table($B)." IS ".q($ub);if($Ma!=""){}foreach($xg
as$F){if(!queries($F))return
false;}return
true;}function
alter_indexes($Q,$c){$i=array();$hc=array();$xg=array();foreach($c
as$X){if($X[0]!="INDEX")$i[]=($X[2]=="DROP"?"\nDROP CONSTRAINT ".idf_escape($X[1]):"\nADD".($X[1]!=""?" CONSTRAINT ".idf_escape($X[1]):"")." $X[0] ".($X[0]=="PRIMARY"?"KEY ":"")."(".implode(", ",$X[2]).")");elseif($X[2]=="DROP")$hc[]=idf_escape($X[1]);else$xg[]="CREATE INDEX ".idf_escape($X[1]!=""?$X[1]:uniqid($Q."_"))." ON ".table($Q)." (".implode(", ",$X[2]).")";}if($i)array_unshift($xg,"ALTER TABLE ".table($Q).implode(",",$i));if($hc)array_unshift($xg,"DROP INDEX ".implode(", ",$hc));foreach($xg
as$F){if(!queries($F))return
false;}return
true;}function
truncate_tables($S){return
queries("TRUNCATE ".implode(", ",array_map('table',$S)));return
true;}function
drop_views($cj){return
drop_tables($cj);}function
drop_tables($S){foreach($S
as$Q){$O=table_status($Q);if(!queries("DROP ".strtoupper($O["Engine"])." ".table($Q)))return
false;}return
true;}function
move_tables($S,$cj,$Zh){foreach(array_merge($S,$cj)as$Q){$O=table_status($Q);if(!queries("ALTER ".strtoupper($O["Engine"])." ".table($Q)." SET SCHEMA ".idf_escape($Zh)))return
false;}return
true;}function
trigger($B,$Q=null){if($B=="")return
array("Statement"=>"EXECUTE PROCEDURE ()");if($Q===null)$Q=$_GET['trigger'];$J=get_rows('SELECT t.trigger_name AS "Trigger", t.action_timing AS "Timing", (SELECT STRING_AGG(event_manipulation, \' OR \') FROM information_schema.triggers WHERE event_object_table = t.event_object_table AND trigger_name = t.trigger_name ) AS "Events", t.event_manipulation AS "Event", \'FOR EACH \' || t.action_orientation AS "Type", t.action_statement AS "Statement" FROM information_schema.triggers t WHERE t.event_object_table = '.q($Q).' AND t.trigger_name = '.q($B));return
reset($J);}function
triggers($Q){$H=array();foreach(get_rows("SELECT * FROM information_schema.triggers WHERE event_object_table = ".q($Q))as$I)$H[$I["trigger_name"]]=array($I["action_timing"],$I["event_manipulation"]);return$H;}function
trigger_options(){return
array("Timing"=>array("BEFORE","AFTER"),"Event"=>array("INSERT","UPDATE","DELETE"),"Type"=>array("FOR EACH ROW","FOR EACH STATEMENT"),);}function
routine($B,$T){$J=get_rows('SELECT routine_definition AS definition, LOWER(external_language) AS language, *
FROM information_schema.routines
WHERE routine_schema = current_schema() AND specific_name = '.q($B));$H=$J[0];$H["returns"]=array("type"=>$H["type_udt_name"]);$H["fields"]=get_rows('SELECT parameter_name AS field, data_type AS type, character_maximum_length AS length, parameter_mode AS inout
FROM information_schema.parameters
WHERE specific_schema = current_schema() AND specific_name = '.q($B).'
ORDER BY ordinal_position');return$H;}function
routines(){return
get_rows('SELECT specific_name AS "SPECIFIC_NAME", routine_type AS "ROUTINE_TYPE", routine_name AS "ROUTINE_NAME", type_udt_name AS "DTD_IDENTIFIER"
FROM information_schema.routines
WHERE routine_schema = current_schema()
ORDER BY SPECIFIC_NAME');}function
routine_languages(){return
get_vals("SELECT LOWER(lanname) FROM pg_catalog.pg_language");}function
routine_id($B,$I){$H=array();foreach($I["fields"]as$o)$H[]=$o["type"];return
idf_escape($B)."(".implode(", ",$H).")";}function
last_id(){return
0;}function
explain($g,$F){return$g->query("EXPLAIN $F");}function
found_rows($R,$Z){global$g;if(preg_match("~ rows=([0-9]+)~",$g->result("EXPLAIN SELECT * FROM ".idf_escape($R["Name"]).($Z?" WHERE ".implode(" AND ",$Z):"")),$Jg))return$Jg[1];return
false;}function
types(){return
get_vals("SELECT typname
FROM pg_type
WHERE typnamespace = (SELECT oid FROM pg_namespace WHERE nspname = current_schema())
AND typtype IN ('b','d','e')
AND typelem = 0");}function
schemas(){return
get_vals("SELECT nspname FROM pg_namespace ORDER BY nspname");}function
get_schema(){global$g;return$g->result("SELECT current_schema()");}function
set_schema($ch,$h=null){global$g,$U,$Jh;if(!$h)$h=$g;$H=$h->query("SET search_path TO ".idf_escape($ch));foreach(types()as$T){if(!isset($U[$T])){$U[$T]=0;$Jh['User types'][]=$T;}}return$H;}function
create_sql($Q,$Ma,$Kh){global$g;$H='';$Sg=array();$mh=array();$O=table_status($Q);if(is_view($O)){$bj=view($Q);return
rtrim("CREATE VIEW ".idf_escape($Q)." AS $bj[select]",";");}$p=fields($Q);$w=indexes($Q);ksort($w);$bd=foreign_keys($Q);ksort($bd);if(!$O||empty($p))return
false;$H="CREATE TABLE ".idf_escape($O['nspname']).".".idf_escape($O['Name'])." (\n    ";foreach($p
as$Tc=>$o){$Tf=idf_escape($o['field']).' '.$o['full_type'].default_value($o).($o['attnotnull']?" NOT NULL":"");$Sg[]=$Tf;if(preg_match('~nextval\(\'([^\']+)\'\)~',$o['default'],$Fe)){$lh=$Fe[1];$_h=reset(get_rows(min_version(10)?"SELECT *, cache_size AS cache_value FROM pg_sequences WHERE schemaname = current_schema() AND sequencename = ".q($lh):"SELECT * FROM $lh"));$mh[]=($Kh=="DROP+CREATE"?"DROP SEQUENCE IF EXISTS $lh;\n":"")."CREATE SEQUENCE $lh INCREMENT $_h[increment_by] MINVALUE $_h[min_value] MAXVALUE $_h[max_value] START ".($Ma?$_h['last_value']:1)." CACHE $_h[cache_value];";}}if(!empty($mh))$H=implode("\n\n",$mh)."\n\n$H";foreach($w
as$Kd=>$v){switch($v['type']){case'UNIQUE':$Sg[]="CONSTRAINT ".idf_escape($Kd)." UNIQUE (".implode(', ',array_map('idf_escape',$v['columns'])).")";break;case'PRIMARY':$Sg[]="CONSTRAINT ".idf_escape($Kd)." PRIMARY KEY (".implode(', ',array_map('idf_escape',$v['columns'])).")";break;}}foreach($bd
as$ad=>$Zc)$Sg[]="CONSTRAINT ".idf_escape($ad)." $Zc[definition] ".($Zc['deferrable']?'DEFERRABLE':'NOT DEFERRABLE');$H.=implode(",\n    ",$Sg)."\n) WITH (oids = ".($O['Oid']?'true':'false').");";foreach($w
as$Kd=>$v){if($v['type']=='INDEX'){$f=array();foreach($v['columns']as$y=>$X)$f[]=idf_escape($X).($v['descs'][$y]?" DESC":"");$H.="\n\nCREATE INDEX ".idf_escape($Kd)." ON ".idf_escape($O['nspname']).".".idf_escape($O['Name'])." USING btree (".implode(', ',$f).");";}}if($O['Comment'])$H.="\n\nCOMMENT ON TABLE ".idf_escape($O['nspname']).".".idf_escape($O['Name'])." IS ".q($O['Comment']).";";foreach($p
as$Tc=>$o){if($o['comment'])$H.="\n\nCOMMENT ON COLUMN ".idf_escape($O['nspname']).".".idf_escape($O['Name']).".".idf_escape($Tc)." IS ".q($o['comment']).";";}return
rtrim($H,';');}function
truncate_sql($Q){return"TRUNCATE ".table($Q);}function
trigger_sql($Q){$O=table_status($Q);$H="";foreach(triggers($Q)as$yi=>$xi){$zi=trigger($yi,$O['Name']);$H.="\nCREATE TRIGGER ".idf_escape($zi['Trigger'])." $zi[Timing] $zi[Events] ON ".idf_escape($O["nspname"]).".".idf_escape($O['Name'])." $zi[Type] $zi[Statement];;\n";}return$H;}function
use_sql($j){return"\connect ".idf_escape($j);}function
show_variables(){return
get_key_vals("SHOW ALL");}function
process_list(){return
get_rows("SELECT * FROM pg_stat_activity ORDER BY ".(min_version(9.2)?"pid":"procpid"));}function
show_status(){}function
convert_field($o){}function
unconvert_field($o,$H){return$H;}function
support($Rc){return
preg_match('~^(database|table|columns|sql|indexes|descidx|comment|view|'.(min_version(9.3)?'materializedview|':'').'scheme|routine|processlist|sequence|trigger|type|variables|drop_col|kill|dump)$~',$Rc);}function
kill_process($X){return
queries("SELECT pg_terminate_backend(".number($X).")");}function
connection_id(){return"SELECT pg_backend_pid()";}function
max_connections(){global$g;return$g->result("SHOW max_connections");}$x="pgsql";$U=array();$Jh=array();foreach(array('Numbers'=>array("smallint"=>5,"integer"=>10,"bigint"=>19,"boolean"=>1,"numeric"=>0,"real"=>7,"double precision"=>16,"money"=>20),'Date and time'=>array("date"=>13,"time"=>17,"timestamp"=>20,"timestamptz"=>21,"interval"=>0),'Strings'=>array("character"=>0,"character varying"=>0,"text"=>0,"tsquery"=>0,"tsvector"=>0,"uuid"=>0,"xml"=>0),'Binary'=>array("bit"=>0,"bit varying"=>0,"bytea"=>0),'Network'=>array("cidr"=>43,"inet"=>43,"macaddr"=>17,"txid_snapshot"=>0),'Geometry'=>array("box"=>0,"circle"=>0,"line"=>0,"lseg"=>0,"path"=>0,"point"=>0,"polygon"=>0),)as$y=>$X){$U+=$X;$Jh[$y]=array_keys($X);}$Ki=array();$xf=array("=","<",">","<=",">=","!=","~","!~","LIKE","LIKE %%","ILIKE","ILIKE %%","IN","IS NULL","NOT LIKE","NOT IN","IS NOT NULL");$md=array("char_length","lower","round","to_hex","to_timestamp","upper");$sd=array("avg","count","count distinct","max","min","sum");$oc=array(array("char"=>"md5","date|time"=>"now",),array(number_type()=>"+/-","date|time"=>"+ interval/- interval","char|text"=>"||",));}$gc["oracle"]="Oracle (beta)";if(isset($_GET["oracle"])){$jg=array("OCI8","PDO_OCI");define("DRIVER","oracle");if(extension_loaded("oci8")){class
Min_DB{var$extension="oci8",$_link,$_result,$server_info,$affected_rows,$errno,$error;function
_error($zc,$n){if(ini_bool("html_errors"))$n=html_entity_decode(strip_tags($n));$n=preg_replace('~^[^:]*: ~','',$n);$this->error=$n;}function
connect($M,$V,$E){$this->_link=@oci_new_connect($V,$E,$M,"AL32UTF8");if($this->_link){$this->server_info=oci_server_version($this->_link);return
true;}$n=oci_error();$this->error=$n["message"];return
false;}function
quote($P){return"'".str_replace("'","''",$P)."'";}function
select_db($j){return
true;}function
query($F,$Ei=false){$G=oci_parse($this->_link,$F);$this->error="";if(!$G){$n=oci_error($this->_link);$this->errno=$n["code"];$this->error=$n["message"];return
false;}set_error_handler(array($this,'_error'));$H=@oci_execute($G);restore_error_handler();if($H){if(oci_num_fields($G))return
new
Min_Result($G);$this->affected_rows=oci_num_rows($G);}return$H;}function
multi_query($F){return$this->_result=$this->query($F);}function
store_result(){return$this->_result;}function
next_result(){return
false;}function
result($F,$o=1){$G=$this->query($F);if(!is_object($G)||!oci_fetch($G->_result))return
false;return
oci_result($G->_result,$o);}}class
Min_Result{var$_result,$_offset=1,$num_rows;function
__construct($G){$this->_result=$G;}function
_convert($I){foreach((array)$I
as$y=>$X){if(is_a($X,'OCI-Lob'))$I[$y]=$X->load();}return$I;}function
fetch_assoc(){return$this->_convert(oci_fetch_assoc($this->_result));}function
fetch_row(){return$this->_convert(oci_fetch_row($this->_result));}function
fetch_field(){$e=$this->_offset++;$H=new
stdClass;$H->name=oci_field_name($this->_result,$e);$H->orgname=$H->name;$H->type=oci_field_type($this->_result,$e);$H->charsetnr=(preg_match("~raw|blob|bfile~",$H->type)?63:0);return$H;}function
__destruct(){oci_free_statement($this->_result);}}}elseif(extension_loaded("pdo_oci")){class
Min_DB
extends
Min_PDO{var$extension="PDO_OCI";function
connect($M,$V,$E){$this->dsn("oci:dbname=//$M;charset=AL32UTF8",$V,$E);return
true;}function
select_db($j){return
true;}}}class
Min_Driver
extends
Min_SQL{function
begin(){return
true;}}function
idf_escape($u){return'"'.str_replace('"','""',$u).'"';}function
table($u){return
idf_escape($u);}function
connect(){global$b;$g=new
Min_DB;$Ib=$b->credentials();if($g->connect($Ib[0],$Ib[1],$Ib[2]))return$g;return$g->error;}function
get_databases(){return
get_vals("SELECT tablespace_name FROM user_tablespaces");}function
limit($F,$Z,$z,$C=0,$L=" "){return($C?" * FROM (SELECT t.*, rownum AS rnum FROM (SELECT $F$Z) t WHERE rownum <= ".($z+$C).") WHERE rnum > $C":($z!==null?" * FROM (SELECT $F$Z) WHERE rownum <= ".($z+$C):" $F$Z"));}function
limit1($Q,$F,$Z,$L="\n"){return" $F$Z";}function
db_collation($l,$pb){global$g;return$g->result("SELECT value FROM nls_database_parameters WHERE parameter = 'NLS_CHARACTERSET'");}function
engines(){return
array();}function
logged_user(){global$g;return$g->result("SELECT USER FROM DUAL");}function
tables_list(){return
get_key_vals("SELECT table_name, 'table' FROM all_tables WHERE tablespace_name = ".q(DB)."
UNION SELECT view_name, 'view' FROM user_views
ORDER BY 1");}function
count_tables($k){return
array();}function
table_status($B=""){$H=array();$eh=q($B);foreach(get_rows('SELECT table_name "Name", \'table\' "Engine", avg_row_len * num_rows "Data_length", num_rows "Rows" FROM all_tables WHERE tablespace_name = '.q(DB).($B!=""?" AND table_name = $eh":"")."
UNION SELECT view_name, 'view', 0, 0 FROM user_views".($B!=""?" WHERE view_name = $eh":"")."
ORDER BY 1")as$I){if($B!="")return$I;$H[$I["Name"]]=$I;}return$H;}function
is_view($R){return$R["Engine"]=="view";}function
fk_support($R){return
true;}function
fields($Q){$H=array();foreach(get_rows("SELECT * FROM all_tab_columns WHERE table_name = ".q($Q)." ORDER BY column_id")as$I){$T=$I["DATA_TYPE"];$ve="$I[DATA_PRECISION],$I[DATA_SCALE]";if($ve==",")$ve=$I["DATA_LENGTH"];$H[$I["COLUMN_NAME"]]=array("field"=>$I["COLUMN_NAME"],"full_type"=>$T.($ve?"($ve)":""),"type"=>strtolower($T),"length"=>$ve,"default"=>$I["DATA_DEFAULT"],"null"=>($I["NULLABLE"]=="Y"),"privileges"=>array("insert"=>1,"select"=>1,"update"=>1),);}return$H;}function
indexes($Q,$h=null){$H=array();foreach(get_rows("SELECT uic.*, uc.constraint_type
FROM user_ind_columns uic
LEFT JOIN user_constraints uc ON uic.index_name = uc.constraint_name AND uic.table_name = uc.table_name
WHERE uic.table_name = ".q($Q)."
ORDER BY uc.constraint_type, uic.column_position",$h)as$I){$Kd=$I["INDEX_NAME"];$H[$Kd]["type"]=($I["CONSTRAINT_TYPE"]=="P"?"PRIMARY":($I["CONSTRAINT_TYPE"]=="U"?"UNIQUE":"INDEX"));$H[$Kd]["columns"][]=$I["COLUMN_NAME"];$H[$Kd]["lengths"][]=($I["CHAR_LENGTH"]&&$I["CHAR_LENGTH"]!=$I["COLUMN_LENGTH"]?$I["CHAR_LENGTH"]:null);$H[$Kd]["descs"][]=($I["DESCEND"]?'1':null);}return$H;}function
view($B){$J=get_rows('SELECT text "select" FROM user_views WHERE view_name = '.q($B));return
reset($J);}function
collations(){return
array();}function
information_schema($l){return
false;}function
error(){global$g;return
h($g->error);}function
explain($g,$F){$g->query("EXPLAIN PLAN FOR $F");return$g->query("SELECT * FROM plan_table");}function
found_rows($R,$Z){}function
alter_table($Q,$B,$p,$ed,$ub,$wc,$d,$Ma,$Wf){$c=$hc=array();foreach($p
as$o){$X=$o[1];if($X&&$o[0]!=""&&idf_escape($o[0])!=$X[0])queries("ALTER TABLE ".table($Q)." RENAME COLUMN ".idf_escape($o[0])." TO $X[0]");if($X)$c[]=($Q!=""?($o[0]!=""?"MODIFY (":"ADD ("):"  ").implode($X).($Q!=""?")":"");else$hc[]=idf_escape($o[0]);}if($Q=="")return
queries("CREATE TABLE ".table($B)." (\n".implode(",\n",$c)."\n)");return(!$c||queries("ALTER TABLE ".table($Q)."\n".implode("\n",$c)))&&(!$hc||queries("ALTER TABLE ".table($Q)." DROP (".implode(", ",$hc).")"))&&($Q==$B||queries("ALTER TABLE ".table($Q)." RENAME TO ".table($B)));}function
foreign_keys($Q){$H=array();$F="SELECT c_list.CONSTRAINT_NAME as NAME,
c_src.COLUMN_NAME as SRC_COLUMN,
c_dest.OWNER as DEST_DB,
c_dest.TABLE_NAME as DEST_TABLE,
c_dest.COLUMN_NAME as DEST_COLUMN,
c_list.DELETE_RULE as ON_DELETE
FROM ALL_CONSTRAINTS c_list, ALL_CONS_COLUMNS c_src, ALL_CONS_COLUMNS c_dest
WHERE c_list.CONSTRAINT_NAME = c_src.CONSTRAINT_NAME
AND c_list.R_CONSTRAINT_NAME = c_dest.CONSTRAINT_NAME
AND c_list.CONSTRAINT_TYPE = 'R'
AND c_src.TABLE_NAME = ".q($Q);foreach(get_rows($F)as$I)$H[$I['NAME']]=array("db"=>$I['DEST_DB'],"table"=>$I['DEST_TABLE'],"source"=>array($I['SRC_COLUMN']),"target"=>array($I['DEST_COLUMN']),"on_delete"=>$I['ON_DELETE'],"on_update"=>null,);return$H;}function
truncate_tables($S){return
apply_queries("TRUNCATE TABLE",$S);}function
drop_views($cj){return
apply_queries("DROP VIEW",$cj);}function
drop_tables($S){return
apply_queries("DROP TABLE",$S);}function
last_id(){return
0;}function
schemas(){return
get_vals("SELECT DISTINCT owner FROM dba_segments WHERE owner IN (SELECT username FROM dba_users WHERE default_tablespace NOT IN ('SYSTEM','SYSAUX'))");}function
get_schema(){global$g;return$g->result("SELECT sys_context('USERENV', 'SESSION_USER') FROM dual");}function
set_schema($dh,$h=null){global$g;if(!$h)$h=$g;return$h->query("ALTER SESSION SET CURRENT_SCHEMA = ".idf_escape($dh));}function
show_variables(){return
get_key_vals('SELECT name, display_value FROM v$parameter');}function
process_list(){return
get_rows('SELECT sess.process AS "process", sess.username AS "user", sess.schemaname AS "schema", sess.status AS "status", sess.wait_class AS "wait_class", sess.seconds_in_wait AS "seconds_in_wait", sql.sql_text AS "sql_text", sess.machine AS "machine", sess.port AS "port"
FROM v$session sess LEFT OUTER JOIN v$sql sql
ON sql.sql_id = sess.sql_id
WHERE sess.type = \'USER\'
ORDER BY PROCESS
');}function
show_status(){$J=get_rows('SELECT * FROM v$instance');return
reset($J);}function
convert_field($o){}function
unconvert_field($o,$H){return$H;}function
support($Rc){return
preg_match('~^(columns|database|drop_col|indexes|descidx|processlist|scheme|sql|status|table|variables|view|view_trigger)$~',$Rc);}$x="oracle";$U=array();$Jh=array();foreach(array('Numbers'=>array("number"=>38,"binary_float"=>12,"binary_double"=>21),'Date and time'=>array("date"=>10,"timestamp"=>29,"interval year"=>12,"interval day"=>28),'Strings'=>array("char"=>2000,"varchar2"=>4000,"nchar"=>2000,"nvarchar2"=>4000,"clob"=>4294967295,"nclob"=>4294967295),'Binary'=>array("raw"=>2000,"long raw"=>2147483648,"blob"=>4294967295,"bfile"=>4294967296),)as$y=>$X){$U+=$X;$Jh[$y]=array_keys($X);}$Ki=array();$xf=array("=","<",">","<=",">=","!=","LIKE","LIKE %%","IN","IS NULL","NOT LIKE","NOT REGEXP","NOT IN","IS NOT NULL","SQL");$md=array("length","lower","round","upper");$sd=array("avg","count","count distinct","max","min","sum");$oc=array(array("date"=>"current_date","timestamp"=>"current_timestamp",),array("number|float|double"=>"+/-","date|timestamp"=>"+ interval/- interval","char|clob"=>"||",));}$gc["mssql"]="MS SQL (beta)";if(isset($_GET["mssql"])){$jg=array("SQLSRV","MSSQL","PDO_DBLIB");define("DRIVER","mssql");if(extension_loaded("sqlsrv")){class
Min_DB{var$extension="sqlsrv",$_link,$_result,$server_info,$affected_rows,$errno,$error;function
_get_error(){$this->error="";foreach(sqlsrv_errors()as$n){$this->errno=$n["code"];$this->error.="$n[message]\n";}$this->error=rtrim($this->error);}function
connect($M,$V,$E){global$b;$l=$b->database();$zb=array("UID"=>$V,"PWD"=>$E,"CharacterSet"=>"UTF-8");if($l!="")$zb["Database"]=$l;$this->_link=@sqlsrv_connect(preg_replace('~:~',',',$M),$zb);if($this->_link){$Rd=sqlsrv_server_info($this->_link);$this->server_info=$Rd['SQLServerVersion'];}else$this->_get_error();return(bool)$this->_link;}function
quote($P){return"'".str_replace("'","''",$P)."'";}function
select_db($j){return$this->query("USE ".idf_escape($j));}function
query($F,$Ei=false){$G=sqlsrv_query($this->_link,$F);$this->error="";if(!$G){$this->_get_error();return
false;}return$this->store_result($G);}function
multi_query($F){$this->_result=sqlsrv_query($this->_link,$F);$this->error="";if(!$this->_result){$this->_get_error();return
false;}return
true;}function
store_result($G=null){if(!$G)$G=$this->_result;if(!$G)return
false;if(sqlsrv_field_metadata($G))return
new
Min_Result($G);$this->affected_rows=sqlsrv_rows_affected($G);return
true;}function
next_result(){return$this->_result?sqlsrv_next_result($this->_result):null;}function
result($F,$o=0){$G=$this->query($F);if(!is_object($G))return
false;$I=$G->fetch_row();return$I[$o];}}class
Min_Result{var$_result,$_offset=0,$_fields,$num_rows;function
__construct($G){$this->_result=$G;}function
_convert($I){foreach((array)$I
as$y=>$X){if(is_a($X,'DateTime'))$I[$y]=$X->format("Y-m-d H:i:s");}return$I;}function
fetch_assoc(){return$this->_convert(sqlsrv_fetch_array($this->_result,SQLSRV_FETCH_ASSOC));}function
fetch_row(){return$this->_convert(sqlsrv_fetch_array($this->_result,SQLSRV_FETCH_NUMERIC));}function
fetch_field(){if(!$this->_fields)$this->_fields=sqlsrv_field_metadata($this->_result);$o=$this->_fields[$this->_offset++];$H=new
stdClass;$H->name=$o["Name"];$H->orgname=$o["Name"];$H->type=($o["Type"]==1?254:0);return$H;}function
seek($C){for($s=0;$s<$C;$s++)sqlsrv_fetch($this->_result);}function
__destruct(){sqlsrv_free_stmt($this->_result);}}}elseif(extension_loaded("mssql")){class
Min_DB{var$extension="MSSQL",$_link,$_result,$server_info,$affected_rows,$error;function
connect($M,$V,$E){$this->_link=@mssql_connect($M,$V,$E);if($this->_link){$G=$this->query("SELECT SERVERPROPERTY('ProductLevel'), SERVERPROPERTY('Edition')");if($G){$I=$G->fetch_row();$this->server_info=$this->result("sp_server_info 2",2)." [$I[0]] $I[1]";}}else$this->error=mssql_get_last_message();return(bool)$this->_link;}function
quote($P){return"'".str_replace("'","''",$P)."'";}function
select_db($j){return
mssql_select_db($j);}function
query($F,$Ei=false){$G=@mssql_query($F,$this->_link);$this->error="";if(!$G){$this->error=mssql_get_last_message();return
false;}if($G===true){$this->affected_rows=mssql_rows_affected($this->_link);return
true;}return
new
Min_Result($G);}function
multi_query($F){return$this->_result=$this->query($F);}function
store_result(){return$this->_result;}function
next_result(){return
mssql_next_result($this->_result->_result);}function
result($F,$o=0){$G=$this->query($F);if(!is_object($G))return
false;return
mssql_result($G->_result,0,$o);}}class
Min_Result{var$_result,$_offset=0,$_fields,$num_rows;function
__construct($G){$this->_result=$G;$this->num_rows=mssql_num_rows($G);}function
fetch_assoc(){return
mssql_fetch_assoc($this->_result);}function
fetch_row(){return
mssql_fetch_row($this->_result);}function
num_rows(){return
mssql_num_rows($this->_result);}function
fetch_field(){$H=mssql_fetch_field($this->_result);$H->orgtable=$H->table;$H->orgname=$H->name;return$H;}function
seek($C){mssql_data_seek($this->_result,$C);}function
__destruct(){mssql_free_result($this->_result);}}}elseif(extension_loaded("pdo_dblib")){class
Min_DB
extends
Min_PDO{var$extension="PDO_DBLIB";function
connect($M,$V,$E){$this->dsn("dblib:charset=utf8;host=".str_replace(":",";unix_socket=",preg_replace('~:(\d)~',';port=\1',$M)),$V,$E);return
true;}function
select_db($j){return$this->query("USE ".idf_escape($j));}}}class
Min_Driver
extends
Min_SQL{function
insertUpdate($Q,$J,$mg){foreach($J
as$N){$Li=array();$Z=array();foreach($N
as$y=>$X){$Li[]="$y = $X";if(isset($mg[idf_unescape($y)]))$Z[]="$y = $X";}if(!queries("MERGE ".table($Q)." USING (VALUES(".implode(", ",$N).")) AS source (c".implode(", c",range(1,count($N))).") ON ".implode(" AND ",$Z)." WHEN MATCHED THEN UPDATE SET ".implode(", ",$Li)." WHEN NOT MATCHED THEN INSERT (".implode(", ",array_keys($N)).") VALUES (".implode(", ",$N).");"))return
false;}return
true;}function
begin(){return
queries("BEGIN TRANSACTION");}}function
idf_escape($u){return"[".str_replace("]","]]",$u)."]";}function
table($u){return($_GET["ns"]!=""?idf_escape($_GET["ns"]).".":"").idf_escape($u);}function
connect(){global$b;$g=new
Min_DB;$Ib=$b->credentials();if($g->connect($Ib[0],$Ib[1],$Ib[2]))return$g;return$g->error;}function
get_databases(){return
get_vals("SELECT name FROM sys.databases WHERE name NOT IN ('master', 'tempdb', 'model', 'msdb')");}function
limit($F,$Z,$z,$C=0,$L=" "){return($z!==null?" TOP (".($z+$C).")":"")." $F$Z";}function
limit1($Q,$F,$Z,$L="\n"){return
limit($F,$Z,1,0,$L);}function
db_collation($l,$pb){global$g;return$g->result("SELECT collation_name FROM sys.databases WHERE name = ".q($l));}function
engines(){return
array();}function
logged_user(){global$g;return$g->result("SELECT SUSER_NAME()");}function
tables_list(){return
get_key_vals("SELECT name, type_desc FROM sys.all_objects WHERE schema_id = SCHEMA_ID(".q(get_schema()).") AND type IN ('S', 'U', 'V') ORDER BY name");}function
count_tables($k){global$g;$H=array();foreach($k
as$l){$g->select_db($l);$H[$l]=$g->result("SELECT COUNT(*) FROM INFORMATION_SCHEMA.TABLES");}return$H;}function
table_status($B=""){$H=array();foreach(get_rows("SELECT ao.name AS Name, ao.type_desc AS Engine, (SELECT value FROM fn_listextendedproperty(default, 'SCHEMA', schema_name(schema_id), 'TABLE', ao.name, null, null)) AS Comment FROM sys.all_objects AS ao WHERE schema_id = SCHEMA_ID(".q(get_schema()).") AND type IN ('S', 'U', 'V') ".($B!=""?"AND name = ".q($B):"ORDER BY name"))as$I){if($B!="")return$I;$H[$I["Name"]]=$I;}return$H;}function
is_view($R){return$R["Engine"]=="VIEW";}function
fk_support($R){return
true;}function
fields($Q){$wb=get_key_vals("SELECT objname, cast(value as varchar) FROM fn_listextendedproperty('MS_DESCRIPTION', 'schema', ".q(get_schema()).", 'table', ".q($Q).", 'column', NULL)");$H=array();foreach(get_rows("SELECT c.max_length, c.precision, c.scale, c.name, c.is_nullable, c.is_identity, c.collation_name, t.name type, CAST(d.definition as text) [default]
FROM sys.all_columns c
JOIN sys.all_objects o ON c.object_id = o.object_id
JOIN sys.types t ON c.user_type_id = t.user_type_id
LEFT JOIN sys.default_constraints d ON c.default_object_id = d.parent_column_id
WHERE o.schema_id = SCHEMA_ID(".q(get_schema()).") AND o.type IN ('S', 'U', 'V') AND o.name = ".q($Q))as$I){$T=$I["type"];$ve=(preg_match("~char|binary~",$T)?$I["max_length"]:($T=="decimal"?"$I[precision],$I[scale]":""));$H[$I["name"]]=array("field"=>$I["name"],"full_type"=>$T.($ve?"($ve)":""),"type"=>$T,"length"=>$ve,"default"=>$I["default"],"null"=>$I["is_nullable"],"auto_increment"=>$I["is_identity"],"collation"=>$I["collation_name"],"privileges"=>array("insert"=>1,"select"=>1,"update"=>1),"primary"=>$I["is_identity"],"comment"=>$wb[$I["name"]],);}return$H;}function
indexes($Q,$h=null){$H=array();foreach(get_rows("SELECT i.name, key_ordinal, is_unique, is_primary_key, c.name AS column_name, is_descending_key
FROM sys.indexes i
INNER JOIN sys.index_columns ic ON i.object_id = ic.object_id AND i.index_id = ic.index_id
INNER JOIN sys.columns c ON ic.object_id = c.object_id AND ic.column_id = c.column_id
WHERE OBJECT_NAME(i.object_id) = ".q($Q),$h)as$I){$B=$I["name"];$H[$B]["type"]=($I["is_primary_key"]?"PRIMARY":($I["is_unique"]?"UNIQUE":"INDEX"));$H[$B]["lengths"]=array();$H[$B]["columns"][$I["key_ordinal"]]=$I["column_name"];$H[$B]["descs"][$I["key_ordinal"]]=($I["is_descending_key"]?'1':null);}return$H;}function
view($B){global$g;return
array("select"=>preg_replace('~^(?:[^[]|\[[^]]*])*\s+AS\s+~isU','',$g->result("SELECT VIEW_DEFINITION FROM INFORMATION_SCHEMA.VIEWS WHERE TABLE_SCHEMA = SCHEMA_NAME() AND TABLE_NAME = ".q($B))));}function
collations(){$H=array();foreach(get_vals("SELECT name FROM fn_helpcollations()")as$d)$H[preg_replace('~_.*~','',$d)][]=$d;return$H;}function
information_schema($l){return
false;}function
error(){global$g;return
nl_br(h(preg_replace('~^(\[[^]]*])+~m','',$g->error)));}function
create_database($l,$d){return
queries("CREATE DATABASE ".idf_escape($l).(preg_match('~^[a-z0-9_]+$~i',$d)?" COLLATE $d":""));}function
drop_databases($k){return
queries("DROP DATABASE ".implode(", ",array_map('idf_escape',$k)));}function
rename_database($B,$d){if(preg_match('~^[a-z0-9_]+$~i',$d))queries("ALTER DATABASE ".idf_escape(DB)." COLLATE $d");queries("ALTER DATABASE ".idf_escape(DB)." MODIFY NAME = ".idf_escape($B));return
true;}function
auto_increment(){return" IDENTITY".($_POST["Auto_increment"]!=""?"(".number($_POST["Auto_increment"]).",1)":"")." PRIMARY KEY";}function
alter_table($Q,$B,$p,$ed,$ub,$wc,$d,$Ma,$Wf){$c=array();$wb=array();foreach($p
as$o){$e=idf_escape($o[0]);$X=$o[1];if(!$X)$c["DROP"][]=" COLUMN $e";else{$X[1]=preg_replace("~( COLLATE )'(\\w+)'~",'\1\2',$X[1]);$wb[$o[0]]=$X[5];unset($X[5]);if($o[0]=="")$c["ADD"][]="\n  ".implode("",$X).($Q==""?substr($ed[$X[0]],16+strlen($X[0])):"");else{unset($X[6]);if($e!=$X[0])queries("EXEC sp_rename ".q(table($Q).".$e").", ".q(idf_unescape($X[0])).", 'COLUMN'");$c["ALTER COLUMN ".implode("",$X)][]="";}}}if($Q=="")return
queries("CREATE TABLE ".table($B)." (".implode(",",(array)$c["ADD"])."\n)");if($Q!=$B)queries("EXEC sp_rename ".q(table($Q)).", ".q($B));if($ed)$c[""]=$ed;foreach($c
as$y=>$X){if(!queries("ALTER TABLE ".idf_escape($B)." $y".implode(",",$X)))return
false;}foreach($wb
as$y=>$X){$ub=substr($X,9);queries("EXEC sp_dropextendedproperty @name = N'MS_Description', @level0type = N'Schema', @level0name = ".q(get_schema()).", @level1type = N'Table',  @level1name = ".q($B).", @level2type = N'Column', @level2name = ".q($y));queries("EXEC sp_addextendedproperty @name = N'MS_Description', @value = ".$ub.", @level0type = N'Schema', @level0name = ".q(get_schema()).", @level1type = N'Table',  @level1name = ".q($B).", @level2type = N'Column', @level2name = ".q($y));}return
true;}function
alter_indexes($Q,$c){$v=array();$hc=array();foreach($c
as$X){if($X[2]=="DROP"){if($X[0]=="PRIMARY")$hc[]=idf_escape($X[1]);else$v[]=idf_escape($X[1])." ON ".table($Q);}elseif(!queries(($X[0]!="PRIMARY"?"CREATE $X[0] ".($X[0]!="INDEX"?"INDEX ":"").idf_escape($X[1]!=""?$X[1]:uniqid($Q."_"))." ON ".table($Q):"ALTER TABLE ".table($Q)." ADD PRIMARY KEY")." (".implode(", ",$X[2]).")"))return
false;}return(!$v||queries("DROP INDEX ".implode(", ",$v)))&&(!$hc||queries("ALTER TABLE ".table($Q)." DROP ".implode(", ",$hc)));}function
last_id(){global$g;return$g->result("SELECT SCOPE_IDENTITY()");}function
explain($g,$F){$g->query("SET SHOWPLAN_ALL ON");$H=$g->query($F);$g->query("SET SHOWPLAN_ALL OFF");return$H;}function
found_rows($R,$Z){}function
foreign_keys($Q){$H=array();foreach(get_rows("EXEC sp_fkeys @fktable_name = ".q($Q))as$I){$q=&$H[$I["FK_NAME"]];$q["db"]=$I["PKTABLE_QUALIFIER"];$q["table"]=$I["PKTABLE_NAME"];$q["source"][]=$I["FKCOLUMN_NAME"];$q["target"][]=$I["PKCOLUMN_NAME"];}return$H;}function
truncate_tables($S){return
apply_queries("TRUNCATE TABLE",$S);}function
drop_views($cj){return
queries("DROP VIEW ".implode(", ",array_map('table',$cj)));}function
drop_tables($S){return
queries("DROP TABLE ".implode(", ",array_map('table',$S)));}function
move_tables($S,$cj,$Zh){return
apply_queries("ALTER SCHEMA ".idf_escape($Zh)." TRANSFER",array_merge($S,$cj));}function
trigger($B){if($B=="")return
array();$J=get_rows("SELECT s.name [Trigger],
CASE WHEN OBJECTPROPERTY(s.id, 'ExecIsInsertTrigger') = 1 THEN 'INSERT' WHEN OBJECTPROPERTY(s.id, 'ExecIsUpdateTrigger') = 1 THEN 'UPDATE' WHEN OBJECTPROPERTY(s.id, 'ExecIsDeleteTrigger') = 1 THEN 'DELETE' END [Event],
CASE WHEN OBJECTPROPERTY(s.id, 'ExecIsInsteadOfTrigger') = 1 THEN 'INSTEAD OF' ELSE 'AFTER' END [Timing],
c.text
FROM sysobjects s
JOIN syscomments c ON s.id = c.id
WHERE s.xtype = 'TR' AND s.name = ".q($B));$H=reset($J);if($H)$H["Statement"]=preg_replace('~^.+\s+AS\s+~isU','',$H["text"]);return$H;}function
triggers($Q){$H=array();foreach(get_rows("SELECT sys1.name,
CASE WHEN OBJECTPROPERTY(sys1.id, 'ExecIsInsertTrigger') = 1 THEN 'INSERT' WHEN OBJECTPROPERTY(sys1.id, 'ExecIsUpdateTrigger') = 1 THEN 'UPDATE' WHEN OBJECTPROPERTY(sys1.id, 'ExecIsDeleteTrigger') = 1 THEN 'DELETE' END [Event],
CASE WHEN OBJECTPROPERTY(sys1.id, 'ExecIsInsteadOfTrigger') = 1 THEN 'INSTEAD OF' ELSE 'AFTER' END [Timing]
FROM sysobjects sys1
JOIN sysobjects sys2 ON sys1.parent_obj = sys2.id
WHERE sys1.xtype = 'TR' AND sys2.name = ".q($Q))as$I)$H[$I["name"]]=array($I["Timing"],$I["Event"]);return$H;}function
trigger_options(){return
array("Timing"=>array("AFTER","INSTEAD OF"),"Event"=>array("INSERT","UPDATE","DELETE"),"Type"=>array("AS"),);}function
schemas(){return
get_vals("SELECT name FROM sys.schemas");}function
get_schema(){global$g;if($_GET["ns"]!="")return$_GET["ns"];return$g->result("SELECT SCHEMA_NAME()");}function
set_schema($ch){return
true;}function
use_sql($j){return"USE ".idf_escape($j);}function
show_variables(){return
array();}function
show_status(){return
array();}function
convert_field($o){}function
unconvert_field($o,$H){return$H;}function
support($Rc){return
preg_match('~^(comment|columns|database|drop_col|indexes|descidx|scheme|sql|table|trigger|view|view_trigger)$~',$Rc);}$x="mssql";$U=array();$Jh=array();foreach(array('Numbers'=>array("tinyint"=>3,"smallint"=>5,"int"=>10,"bigint"=>20,"bit"=>1,"decimal"=>0,"real"=>12,"float"=>53,"smallmoney"=>10,"money"=>20),'Date and time'=>array("date"=>10,"smalldatetime"=>19,"datetime"=>19,"datetime2"=>19,"time"=>8,"datetimeoffset"=>10),'Strings'=>array("char"=>8000,"varchar"=>8000,"text"=>2147483647,"nchar"=>4000,"nvarchar"=>4000,"ntext"=>1073741823),'Binary'=>array("binary"=>8000,"varbinary"=>8000,"image"=>2147483647),)as$y=>$X){$U+=$X;$Jh[$y]=array_keys($X);}$Ki=array();$xf=array("=","<",">","<=",">=","!=","LIKE","LIKE %%","IN","IS NULL","NOT LIKE","NOT IN","IS NOT NULL");$md=array("len","lower","round","upper");$sd=array("avg","count","count distinct","max","min","sum");$oc=array(array("date|time"=>"getdate",),array("int|decimal|real|float|money|datetime"=>"+/-","char|text"=>"+",));}$gc['firebird']='Firebird (alpha)';if(isset($_GET["firebird"])){$jg=array("interbase");define("DRIVER","firebird");if(extension_loaded("interbase")){class
Min_DB{var$extension="Firebird",$server_info,$affected_rows,$errno,$error,$_link,$_result;function
connect($M,$V,$E){$this->_link=ibase_connect($M,$V,$E);if($this->_link){$Oi=explode(':',$M);$this->service_link=ibase_service_attach($Oi[0],$V,$E);$this->server_info=ibase_server_info($this->service_link,IBASE_SVC_SERVER_VERSION);}else{$this->errno=ibase_errcode();$this->error=ibase_errmsg();}return(bool)$this->_link;}function
quote($P){return"'".str_replace("'","''",$P)."'";}function
select_db($j){return($j=="domain");}function
query($F,$Ei=false){$G=ibase_query($F,$this->_link);if(!$G){$this->errno=ibase_errcode();$this->error=ibase_errmsg();return
false;}$this->error="";if($G===true){$this->affected_rows=ibase_affected_rows($this->_link);return
true;}return
new
Min_Result($G);}function
multi_query($F){return$this->_result=$this->query($F);}function
store_result(){return$this->_result;}function
next_result(){return
false;}function
result($F,$o=0){$G=$this->query($F);if(!$G||!$G->num_rows)return
false;$I=$G->fetch_row();return$I[$o];}}class
Min_Result{var$num_rows,$_result,$_offset=0;function
__construct($G){$this->_result=$G;}function
fetch_assoc(){return
ibase_fetch_assoc($this->_result);}function
fetch_row(){return
ibase_fetch_row($this->_result);}function
fetch_field(){$o=ibase_field_info($this->_result,$this->_offset++);return(object)array('name'=>$o['name'],'orgname'=>$o['name'],'type'=>$o['type'],'charsetnr'=>$o['length'],);}function
__destruct(){ibase_free_result($this->_result);}}}class
Min_Driver
extends
Min_SQL{}function
idf_escape($u){return'"'.str_replace('"','""',$u).'"';}function
table($u){return
idf_escape($u);}function
connect(){global$b;$g=new
Min_DB;$Ib=$b->credentials();if($g->connect($Ib[0],$Ib[1],$Ib[2]))return$g;return$g->error;}function
get_databases($cd){return
array("domain");}function
limit($F,$Z,$z,$C=0,$L=" "){$H='';$H.=($z!==null?$L."FIRST $z".($C?" SKIP $C":""):"");$H.=" $F$Z";return$H;}function
limit1($Q,$F,$Z,$L="\n"){return
limit($F,$Z,1,0,$L);}function
db_collation($l,$pb){}function
engines(){return
array();}function
logged_user(){global$b;$Ib=$b->credentials();return$Ib[1];}function
tables_list(){global$g;$F='SELECT RDB$RELATION_NAME FROM rdb$relations WHERE rdb$system_flag = 0';$G=ibase_query($g->_link,$F);$H=array();while($I=ibase_fetch_assoc($G))$H[$I['RDB$RELATION_NAME']]='table';ksort($H);return$H;}function
count_tables($k){return
array();}function
table_status($B="",$Qc=false){global$g;$H=array();$Nb=tables_list();foreach($Nb
as$v=>$X){$v=trim($v);$H[$v]=array('Name'=>$v,'Engine'=>'standard',);if($B==$v)return$H[$v];}return$H;}function
is_view($R){return
false;}function
fk_support($R){return
preg_match('~InnoDB|IBMDB2I~i',$R["Engine"]);}function
fields($Q){global$g;$H=array();$F='SELECT r.RDB$FIELD_NAME AS field_name,
r.RDB$DESCRIPTION AS field_description,
r.RDB$DEFAULT_VALUE AS field_default_value,
r.RDB$NULL_FLAG AS field_not_null_constraint,
f.RDB$FIELD_LENGTH AS field_length,
f.RDB$FIELD_PRECISION AS field_precision,
f.RDB$FIELD_SCALE AS field_scale,
CASE f.RDB$FIELD_TYPE
WHEN 261 THEN \'BLOB\'
WHEN 14 THEN \'CHAR\'
WHEN 40 THEN \'CSTRING\'
WHEN 11 THEN \'D_FLOAT\'
WHEN 27 THEN \'DOUBLE\'
WHEN 10 THEN \'FLOAT\'
WHEN 16 THEN \'INT64\'
WHEN 8 THEN \'INTEGER\'
WHEN 9 THEN \'QUAD\'
WHEN 7 THEN \'SMALLINT\'
WHEN 12 THEN \'DATE\'
WHEN 13 THEN \'TIME\'
WHEN 35 THEN \'TIMESTAMP\'
WHEN 37 THEN \'VARCHAR\'
ELSE \'UNKNOWN\'
END AS field_type,
f.RDB$FIELD_SUB_TYPE AS field_subtype,
coll.RDB$COLLATION_NAME AS field_collation,
cset.RDB$CHARACTER_SET_NAME AS field_charset
FROM RDB$RELATION_FIELDS r
LEFT JOIN RDB$FIELDS f ON r.RDB$FIELD_SOURCE = f.RDB$FIELD_NAME
LEFT JOIN RDB$COLLATIONS coll ON f.RDB$COLLATION_ID = coll.RDB$COLLATION_ID
LEFT JOIN RDB$CHARACTER_SETS cset ON f.RDB$CHARACTER_SET_ID = cset.RDB$CHARACTER_SET_ID
WHERE r.RDB$RELATION_NAME = '.q($Q).'
ORDER BY r.RDB$FIELD_POSITION';$G=ibase_query($g->_link,$F);while($I=ibase_fetch_assoc($G))$H[trim($I['FIELD_NAME'])]=array("field"=>trim($I["FIELD_NAME"]),"full_type"=>trim($I["FIELD_TYPE"]),"type"=>trim($I["FIELD_SUB_TYPE"]),"default"=>trim($I['FIELD_DEFAULT_VALUE']),"null"=>(trim($I["FIELD_NOT_NULL_CONSTRAINT"])=="YES"),"auto_increment"=>'0',"collation"=>trim($I["FIELD_COLLATION"]),"privileges"=>array("insert"=>1,"select"=>1,"update"=>1),"comment"=>trim($I["FIELD_DESCRIPTION"]),);return$H;}function
indexes($Q,$h=null){$H=array();return$H;}function
foreign_keys($Q){return
array();}function
collations(){return
array();}function
information_schema($l){return
false;}function
error(){global$g;return
h($g->error);}function
types(){return
array();}function
schemas(){return
array();}function
get_schema(){return"";}function
set_schema($ch){return
true;}function
support($Rc){return
preg_match("~^(columns|sql|status|table)$~",$Rc);}$x="firebird";$xf=array("=");$md=array();$sd=array();$oc=array();}$gc["simpledb"]="SimpleDB";if(isset($_GET["simpledb"])){$jg=array("SimpleXML + allow_url_fopen");define("DRIVER","simpledb");if(class_exists('SimpleXMLElement')&&ini_bool('allow_url_fopen')){class
Min_DB{var$extension="SimpleXML",$server_info='2009-04-15',$error,$timeout,$next,$affected_rows,$_result;function
select_db($j){return($j=="domain");}function
query($F,$Ei=false){$Qf=array('SelectExpression'=>$F,'ConsistentRead'=>'true');if($this->next)$Qf['NextToken']=$this->next;$G=sdb_request_all('Select','Item',$Qf,$this->timeout);$this->timeout=0;if($G===false)return$G;if(preg_match('~^\s*SELECT\s+COUNT\(~i',$F)){$Nh=0;foreach($G
as$de)$Nh+=$de->Attribute->Value;$G=array((object)array('Attribute'=>array((object)array('Name'=>'Count','Value'=>$Nh,))));}return
new
Min_Result($G);}function
multi_query($F){return$this->_result=$this->query($F);}function
store_result(){return$this->_result;}function
next_result(){return
false;}function
quote($P){return"'".str_replace("'","''",$P)."'";}}class
Min_Result{var$num_rows,$_rows=array(),$_offset=0;function
__construct($G){foreach($G
as$de){$I=array();if($de->Name!='')$I['itemName()']=(string)$de->Name;foreach($de->Attribute
as$Ia){$B=$this->_processValue($Ia->Name);$Y=$this->_processValue($Ia->Value);if(isset($I[$B])){$I[$B]=(array)$I[$B];$I[$B][]=$Y;}else$I[$B]=$Y;}$this->_rows[]=$I;foreach($I
as$y=>$X){if(!isset($this->_rows[0][$y]))$this->_rows[0][$y]=null;}}$this->num_rows=count($this->_rows);}function
_processValue($rc){return(is_object($rc)&&$rc['encoding']=='base64'?base64_decode($rc):(string)$rc);}function
fetch_assoc(){$I=current($this->_rows);if(!$I)return$I;$H=array();foreach($this->_rows[0]as$y=>$X)$H[$y]=$I[$y];next($this->_rows);return$H;}function
fetch_row(){$H=$this->fetch_assoc();if(!$H)return$H;return
array_values($H);}function
fetch_field(){$je=array_keys($this->_rows[0]);return(object)array('name'=>$je[$this->_offset++]);}}}class
Min_Driver
extends
Min_SQL{public$mg="itemName()";function
_chunkRequest($Gd,$va,$Qf,$Gc=array()){global$g;foreach(array_chunk($Gd,25)as$ib){$Rf=$Qf;foreach($ib
as$s=>$t){$Rf["Item.$s.ItemName"]=$t;foreach($Gc
as$y=>$X)$Rf["Item.$s.$y"]=$X;}if(!sdb_request($va,$Rf))return
false;}$g->affected_rows=count($Gd);return
true;}function
_extractIds($Q,$yg,$z){$H=array();if(preg_match_all("~itemName\(\) = (('[^']*+')+)~",$yg,$Fe))$H=array_map('idf_unescape',$Fe[1]);else{foreach(sdb_request_all('Select','Item',array('SelectExpression'=>'SELECT itemName() FROM '.table($Q).$yg.($z?" LIMIT 1":"")))as$de)$H[]=$de->Name;}return$H;}function
select($Q,$K,$Z,$pd,$Bf=array(),$z=1,$D=0,$og=false){global$g;$g->next=$_GET["next"];$H=parent::select($Q,$K,$Z,$pd,$Bf,$z,$D,$og);$g->next=0;return$H;}function
delete($Q,$yg,$z=0){return$this->_chunkRequest($this->_extractIds($Q,$yg,$z),'BatchDeleteAttributes',array('DomainName'=>$Q));}function
update($Q,$N,$yg,$z=0,$L="\n"){$Xb=array();$Vd=array();$s=0;$Gd=$this->_extractIds($Q,$yg,$z);$t=idf_unescape($N["`itemName()`"]);unset($N["`itemName()`"]);foreach($N
as$y=>$X){$y=idf_unescape($y);if($X=="NULL"||($t!=""&&array($t)!=$Gd))$Xb["Attribute.".count($Xb).".Name"]=$y;if($X!="NULL"){foreach((array)$X
as$fe=>$W){$Vd["Attribute.$s.Name"]=$y;$Vd["Attribute.$s.Value"]=(is_array($X)?$W:idf_unescape($W));if(!$fe)$Vd["Attribute.$s.Replace"]="true";$s++;}}}$Qf=array('DomainName'=>$Q);return(!$Vd||$this->_chunkRequest(($t!=""?array($t):$Gd),'BatchPutAttributes',$Qf,$Vd))&&(!$Xb||$this->_chunkRequest($Gd,'BatchDeleteAttributes',$Qf,$Xb));}function
insert($Q,$N){$Qf=array("DomainName"=>$Q);$s=0;foreach($N
as$B=>$Y){if($Y!="NULL"){$B=idf_unescape($B);if($B=="itemName()")$Qf["ItemName"]=idf_unescape($Y);else{foreach((array)$Y
as$X){$Qf["Attribute.$s.Name"]=$B;$Qf["Attribute.$s.Value"]=(is_array($Y)?$X:idf_unescape($Y));$s++;}}}}return
sdb_request('PutAttributes',$Qf);}function
insertUpdate($Q,$J,$mg){foreach($J
as$N){if(!$this->update($Q,$N,"WHERE `itemName()` = ".q($N["`itemName()`"])))return
false;}return
true;}function
begin(){return
false;}function
commit(){return
false;}function
rollback(){return
false;}function
slowQuery($F,$hi){$this->_conn->timeout=$hi;return$F;}}function
connect(){global$b;list(,,$E)=$b->credentials();if($E!="")return'Database does not support password.';return
new
Min_DB;}function
support($Rc){return
preg_match('~sql~',$Rc);}function
logged_user(){global$b;$Ib=$b->credentials();return$Ib[1];}function
get_databases(){return
array("domain");}function
collations(){return
array();}function
db_collation($l,$pb){}function
tables_list(){global$g;$H=array();foreach(sdb_request_all('ListDomains','DomainName')as$Q)$H[(string)$Q]='table';if($g->error&&defined("PAGE_HEADER"))echo"<p class='error'>".error()."\n";return$H;}function
table_status($B="",$Qc=false){$H=array();foreach(($B!=""?array($B=>true):tables_list())as$Q=>$T){$I=array("Name"=>$Q,"Auto_increment"=>"");if(!$Qc){$Se=sdb_request('DomainMetadata',array('DomainName'=>$Q));if($Se){foreach(array("Rows"=>"ItemCount","Data_length"=>"ItemNamesSizeBytes","Index_length"=>"AttributeValuesSizeBytes","Data_free"=>"AttributeNamesSizeBytes",)as$y=>$X)$I[$y]=(string)$Se->$X;}}if($B!="")return$I;$H[$Q]=$I;}return$H;}function
explain($g,$F){}function
error(){global$g;return
h($g->error);}function
information_schema(){}function
is_view($R){}function
indexes($Q,$h=null){return
array(array("type"=>"PRIMARY","columns"=>array("itemName()")),);}function
fields($Q){return
fields_from_edit();}function
foreign_keys($Q){return
array();}function
table($u){return
idf_escape($u);}function
idf_escape($u){return"`".str_replace("`","``",$u)."`";}function
limit($F,$Z,$z,$C=0,$L=" "){return" $F$Z".($z!==null?$L."LIMIT $z":"");}function
unconvert_field($o,$H){return$H;}function
fk_support($R){}function
engines(){return
array();}function
alter_table($Q,$B,$p,$ed,$ub,$wc,$d,$Ma,$Wf){return($Q==""&&sdb_request('CreateDomain',array('DomainName'=>$B)));}function
drop_tables($S){foreach($S
as$Q){if(!sdb_request('DeleteDomain',array('DomainName'=>$Q)))return
false;}return
true;}function
count_tables($k){foreach($k
as$l)return
array($l=>count(tables_list()));}function
found_rows($R,$Z){return($Z?null:$R["Rows"]);}function
last_id(){}function
hmac($Ba,$Nb,$y,$Bg=false){$Va=64;if(strlen($y)>$Va)$y=pack("H*",$Ba($y));$y=str_pad($y,$Va,"\0");$ge=$y^str_repeat("\x36",$Va);$he=$y^str_repeat("\x5C",$Va);$H=$Ba($he.pack("H*",$Ba($ge.$Nb)));if($Bg)$H=pack("H*",$H);return$H;}function
sdb_request($va,$Qf=array()){global$b,$g;list($Cd,$Qf['AWSAccessKeyId'],$fh)=$b->credentials();$Qf['Action']=$va;$Qf['Timestamp']=gmdate('Y-m-d\TH:i:s+00:00');$Qf['Version']='2009-04-15';$Qf['SignatureVersion']=2;$Qf['SignatureMethod']='HmacSHA1';ksort($Qf);$F='';foreach($Qf
as$y=>$X)$F.='&'.rawurlencode($y).'='.rawurlencode($X);$F=str_replace('%7E','~',substr($F,1));$F.="&Signature=".urlencode(base64_encode(hmac('sha1',"POST\n".preg_replace('~^https?://~','',$Cd)."\n/\n$F",$fh,true)));@ini_set('track_errors',1);$Vc=@file_get_contents((preg_match('~^https?://~',$Cd)?$Cd:"http://$Cd"),false,stream_context_create(array('http'=>array('method'=>'POST','content'=>$F,'ignore_errors'=>1,))));if(!$Vc){$g->error=$php_errormsg;return
false;}libxml_use_internal_errors(true);$pj=simplexml_load_string($Vc);if(!$pj){$n=libxml_get_last_error();$g->error=$n->message;return
false;}if($pj->Errors){$n=$pj->Errors->Error;$g->error="$n->Message ($n->Code)";return
false;}$g->error='';$Yh=$va."Result";return($pj->$Yh?$pj->$Yh:true);}function
sdb_request_all($va,$Yh,$Qf=array(),$hi=0){$H=array();$Fh=($hi?microtime(true):0);$z=(preg_match('~LIMIT\s+(\d+)\s*$~i',$Qf['SelectExpression'],$A)?$A[1]:0);do{$pj=sdb_request($va,$Qf);if(!$pj)break;foreach($pj->$Yh
as$rc)$H[]=$rc;if($z&&count($H)>=$z){$_GET["next"]=$pj->NextToken;break;}if($hi&&microtime(true)-$Fh>$hi)return
false;$Qf['NextToken']=$pj->NextToken;if($z)$Qf['SelectExpression']=preg_replace('~\d+\s*$~',$z-count($H),$Qf['SelectExpression']);}while($pj->NextToken);return$H;}$x="simpledb";$xf=array("=","<",">","<=",">=","!=","LIKE","LIKE %%","IN","IS NULL","NOT LIKE","IS NOT NULL");$md=array();$sd=array("count");$oc=array(array("json"));}$gc["mongo"]="MongoDB";if(isset($_GET["mongo"])){$jg=array("mongo","mongodb");define("DRIVER","mongo");if(class_exists('MongoDB')){class
Min_DB{var$extension="Mongo",$server_info=MongoClient::VERSION,$error,$last_id,$_link,$_db;function
connect($Mi,$_f){return@new
MongoClient($Mi,$_f);}function
query($F){return
false;}function
select_db($j){try{$this->_db=$this->_link->selectDB($j);return
true;}catch(Exception$Cc){$this->error=$Cc->getMessage();return
false;}}function
quote($P){return$P;}}class
Min_Result{var$num_rows,$_rows=array(),$_offset=0,$_charset=array();function
__construct($G){foreach($G
as$de){$I=array();foreach($de
as$y=>$X){if(is_a($X,'MongoBinData'))$this->_charset[$y]=63;$I[$y]=(is_a($X,'MongoId')?'ObjectId("'.strval($X).'")':(is_a($X,'MongoDate')?gmdate("Y-m-d H:i:s",$X->sec)." GMT":(is_a($X,'MongoBinData')?$X->bin:(is_a($X,'MongoRegex')?strval($X):(is_object($X)?get_class($X):$X)))));}$this->_rows[]=$I;foreach($I
as$y=>$X){if(!isset($this->_rows[0][$y]))$this->_rows[0][$y]=null;}}$this->num_rows=count($this->_rows);}function
fetch_assoc(){$I=current($this->_rows);if(!$I)return$I;$H=array();foreach($this->_rows[0]as$y=>$X)$H[$y]=$I[$y];next($this->_rows);return$H;}function
fetch_row(){$H=$this->fetch_assoc();if(!$H)return$H;return
array_values($H);}function
fetch_field(){$je=array_keys($this->_rows[0]);$B=$je[$this->_offset++];return(object)array('name'=>$B,'charsetnr'=>$this->_charset[$B],);}}class
Min_Driver
extends
Min_SQL{public$mg="_id";function
select($Q,$K,$Z,$pd,$Bf=array(),$z=1,$D=0,$og=false){$K=($K==array("*")?array():array_fill_keys($K,true));$xh=array();foreach($Bf
as$X){$X=preg_replace('~ DESC$~','',$X,1,$Fb);$xh[$X]=($Fb?-1:1);}return
new
Min_Result($this->_conn->_db->selectCollection($Q)->find(array(),$K)->sort($xh)->limit($z!=""?+$z:0)->skip($D*$z));}function
insert($Q,$N){try{$H=$this->_conn->_db->selectCollection($Q)->insert($N);$this->_conn->errno=$H['code'];$this->_conn->error=$H['err'];$this->_conn->last_id=$N['_id'];return!$H['err'];}catch(Exception$Cc){$this->_conn->error=$Cc->getMessage();return
false;}}}function
get_databases($cd){global$g;$H=array();$Sb=$g->_link->listDBs();foreach($Sb['databases']as$l)$H[]=$l['name'];return$H;}function
count_tables($k){global$g;$H=array();foreach($k
as$l)$H[$l]=count($g->_link->selectDB($l)->getCollectionNames(true));return$H;}function
tables_list(){global$g;return
array_fill_keys($g->_db->getCollectionNames(true),'table');}function
drop_databases($k){global$g;foreach($k
as$l){$Og=$g->_link->selectDB($l)->drop();if(!$Og['ok'])return
false;}return
true;}function
indexes($Q,$h=null){global$g;$H=array();foreach($g->_db->selectCollection($Q)->getIndexInfo()as$v){$ac=array();foreach($v["key"]as$e=>$T)$ac[]=($T==-1?'1':null);$H[$v["name"]]=array("type"=>($v["name"]=="_id_"?"PRIMARY":($v["unique"]?"UNIQUE":"INDEX")),"columns"=>array_keys($v["key"]),"lengths"=>array(),"descs"=>$ac,);}return$H;}function
fields($Q){return
fields_from_edit();}function
found_rows($R,$Z){global$g;return$g->_db->selectCollection($_GET["select"])->count($Z);}$xf=array("=");}elseif(class_exists('MongoDB\Driver\Manager')){class
Min_DB{var$extension="MongoDB",$server_info=MONGODB_VERSION,$error,$last_id;var$_link;var$_db,$_db_name;function
connect($Mi,$_f){$kb='MongoDB\Driver\Manager';return
new$kb($Mi,$_f);}function
query($F){return
false;}function
select_db($j){$this->_db_name=$j;return
true;}function
quote($P){return$P;}}class
Min_Result{var$num_rows,$_rows=array(),$_offset=0,$_charset=array();function
__construct($G){foreach($G
as$de){$I=array();foreach($de
as$y=>$X){if(is_a($X,'MongoDB\BSON\Binary'))$this->_charset[$y]=63;$I[$y]=(is_a($X,'MongoDB\BSON\ObjectID')?'MongoDB\BSON\ObjectID("'.strval($X).'")':(is_a($X,'MongoDB\BSON\UTCDatetime')?$X->toDateTime()->format('Y-m-d H:i:s'):(is_a($X,'MongoDB\BSON\Binary')?$X->bin:(is_a($X,'MongoDB\BSON\Regex')?strval($X):(is_object($X)?json_encode($X,256):$X)))));}$this->_rows[]=$I;foreach($I
as$y=>$X){if(!isset($this->_rows[0][$y]))$this->_rows[0][$y]=null;}}$this->num_rows=$G->count;}function
fetch_assoc(){$I=current($this->_rows);if(!$I)return$I;$H=array();foreach($this->_rows[0]as$y=>$X)$H[$y]=$I[$y];next($this->_rows);return$H;}function
fetch_row(){$H=$this->fetch_assoc();if(!$H)return$H;return
array_values($H);}function
fetch_field(){$je=array_keys($this->_rows[0]);$B=$je[$this->_offset++];return(object)array('name'=>$B,'charsetnr'=>$this->_charset[$B],);}}class
Min_Driver
extends
Min_SQL{public$mg="_id";function
select($Q,$K,$Z,$pd,$Bf=array(),$z=1,$D=0,$og=false){global$g;$K=($K==array("*")?array():array_fill_keys($K,1));if(count($K)&&!isset($K['_id']))$K['_id']=0;$Z=where_to_query($Z);$xh=array();foreach($Bf
as$X){$X=preg_replace('~ DESC$~','',$X,1,$Fb);$xh[$X]=($Fb?-1:1);}if(isset($_GET['limit'])&&is_numeric($_GET['limit'])&&$_GET['limit']>0)$z=$_GET['limit'];$z=min(200,max(1,(int)$z));$uh=$D*$z;$kb='MongoDB\Driver\Query';$F=new$kb($Z,array('projection'=>$K,'limit'=>$z,'skip'=>$uh,'sort'=>$xh));$Rg=$g->_link->executeQuery("$g->_db_name.$Q",$F);return
new
Min_Result($Rg);}function
update($Q,$N,$yg,$z=0,$L="\n"){global$g;$l=$g->_db_name;$Z=sql_query_where_parser($yg);$kb='MongoDB\Driver\BulkWrite';$Za=new$kb(array());if(isset($N['_id']))unset($N['_id']);$Lg=array();foreach($N
as$y=>$Y){if($Y=='NULL'){$Lg[$y]=1;unset($N[$y]);}}$Li=array('$set'=>$N);if(count($Lg))$Li['$unset']=$Lg;$Za->update($Z,$Li,array('upsert'=>false));$Rg=$g->_link->executeBulkWrite("$l.$Q",$Za);$g->affected_rows=$Rg->getModifiedCount();return
true;}function
delete($Q,$yg,$z=0){global$g;$l=$g->_db_name;$Z=sql_query_where_parser($yg);$kb='MongoDB\Driver\BulkWrite';$Za=new$kb(array());$Za->delete($Z,array('limit'=>$z));$Rg=$g->_link->executeBulkWrite("$l.$Q",$Za);$g->affected_rows=$Rg->getDeletedCount();return
true;}function
insert($Q,$N){global$g;$l=$g->_db_name;$kb='MongoDB\Driver\BulkWrite';$Za=new$kb(array());if(isset($N['_id'])&&empty($N['_id']))unset($N['_id']);$Za->insert($N);$Rg=$g->_link->executeBulkWrite("$l.$Q",$Za);$g->affected_rows=$Rg->getInsertedCount();return
true;}}function
get_databases($cd){global$g;$H=array();$kb='MongoDB\Driver\Command';$sb=new$kb(array('listDatabases'=>1));$Rg=$g->_link->executeCommand('admin',$sb);foreach($Rg
as$Sb){foreach($Sb->databases
as$l)$H[]=$l->name;}return$H;}function
count_tables($k){$H=array();return$H;}function
tables_list(){global$g;$kb='MongoDB\Driver\Command';$sb=new$kb(array('listCollections'=>1));$Rg=$g->_link->executeCommand($g->_db_name,$sb);$qb=array();foreach($Rg
as$G)$qb[$G->name]='table';return$qb;}function
drop_databases($k){return
false;}function
indexes($Q,$h=null){global$g;$H=array();$kb='MongoDB\Driver\Command';$sb=new$kb(array('listIndexes'=>$Q));$Rg=$g->_link->executeCommand($g->_db_name,$sb);foreach($Rg
as$v){$ac=array();$f=array();foreach(get_object_vars($v->key)as$e=>$T){$ac[]=($T==-1?'1':null);$f[]=$e;}$H[$v->name]=array("type"=>($v->name=="_id_"?"PRIMARY":(isset($v->unique)?"UNIQUE":"INDEX")),"columns"=>$f,"lengths"=>array(),"descs"=>$ac,);}return$H;}function
fields($Q){$p=fields_from_edit();if(!count($p)){global$m;$G=$m->select($Q,array("*"),null,null,array(),10);while($I=$G->fetch_assoc()){foreach($I
as$y=>$X){$I[$y]=null;$p[$y]=array("field"=>$y,"type"=>"string","null"=>($y!=$m->primary),"auto_increment"=>($y==$m->primary),"privileges"=>array("insert"=>1,"select"=>1,"update"=>1,),);}}}return$p;}function
found_rows($R,$Z){global$g;$Z=where_to_query($Z);$kb='MongoDB\Driver\Command';$sb=new$kb(array('count'=>$R['Name'],'query'=>$Z));$Rg=$g->_link->executeCommand($g->_db_name,$sb);$pi=$Rg->toArray();return$pi[0]->n;}function
sql_query_where_parser($yg){$yg=trim(preg_replace('/WHERE[\s]?[(]?\(?/','',$yg));$yg=preg_replace('/\)\)\)$/',')',$yg);$mj=explode(' AND ',$yg);$nj=explode(') OR (',$yg);$Z=array();foreach($mj
as$kj)$Z[]=trim($kj);if(count($nj)==1)$nj=array();elseif(count($nj)>1)$Z=array();return
where_to_query($Z,$nj);}function
where_to_query($ij=array(),$jj=array()){global$b;$Nb=array();foreach(array('and'=>$ij,'or'=>$jj)as$T=>$Z){if(is_array($Z)){foreach($Z
as$Jc){list($nb,$vf,$X)=explode(" ",$Jc,3);if($nb=="_id"){$X=str_replace('MongoDB\BSON\ObjectID("',"",$X);$X=str_replace('")',"",$X);$kb='MongoDB\BSON\ObjectID';$X=new$kb($X);}if(!in_array($vf,$b->operators))continue;if(preg_match('~^\(f\)(.+)~',$vf,$A)){$X=(float)$X;$vf=$A[1];}elseif(preg_match('~^\(date\)(.+)~',$vf,$A)){$Pb=new
DateTime($X);$kb='MongoDB\BSON\UTCDatetime';$X=new$kb($Pb->getTimestamp()*1000);$vf=$A[1];}switch($vf){case'=':$vf='$eq';break;case'!=':$vf='$ne';break;case'>':$vf='$gt';break;case'<':$vf='$lt';break;case'>=':$vf='$gte';break;case'<=':$vf='$lte';break;case'regex':$vf='$regex';break;default:continue
2;}if($T=='and')$Nb['$and'][]=array($nb=>array($vf=>$X));elseif($T=='or')$Nb['$or'][]=array($nb=>array($vf=>$X));}}}return$Nb;}$xf=array("=","!=",">","<",">=","<=","regex","(f)=","(f)!=","(f)>","(f)<","(f)>=","(f)<=","(date)=","(date)!=","(date)>","(date)<","(date)>=","(date)<=",);}function
table($u){return$u;}function
idf_escape($u){return$u;}function
table_status($B="",$Qc=false){$H=array();foreach(tables_list()as$Q=>$T){$H[$Q]=array("Name"=>$Q);if($B==$Q)return$H[$Q];}return$H;}function
create_database($l,$d){return
true;}function
last_id(){global$g;return$g->last_id;}function
error(){global$g;return
h($g->error);}function
collations(){return
array();}function
logged_user(){global$b;$Ib=$b->credentials();return$Ib[1];}function
connect(){global$b;$g=new
Min_DB;list($M,$V,$E)=$b->credentials();$_f=array();if($V.$E!=""){$_f["username"]=$V;$_f["password"]=$E;}$l=$b->database();if($l!="")$_f["db"]=$l;if(($La=getenv("MONGO_AUTH_SOURCE")))$_f["authSource"]=$La;try{$g->_link=$g->connect("mongodb://$M",$_f);if($E!=""){$_f["password"]="";try{$g->connect("mongodb://$M",$_f);return'Database does not support password.';}catch(Exception$Cc){}}return$g;}catch(Exception$Cc){return$Cc->getMessage();}}function
alter_indexes($Q,$c){global$g;foreach($c
as$X){list($T,$B,$N)=$X;if($N=="DROP")$H=$g->_db->command(array("deleteIndexes"=>$Q,"index"=>$B));else{$f=array();foreach($N
as$e){$e=preg_replace('~ DESC$~','',$e,1,$Fb);$f[$e]=($Fb?-1:1);}$H=$g->_db->selectCollection($Q)->ensureIndex($f,array("unique"=>($T=="UNIQUE"),"name"=>$B,));}if($H['errmsg']){$g->error=$H['errmsg'];return
false;}}return
true;}function
support($Rc){return
preg_match("~database|indexes|descidx~",$Rc);}function
db_collation($l,$pb){}function
information_schema(){}function
is_view($R){}function
convert_field($o){}function
unconvert_field($o,$H){return$H;}function
foreign_keys($Q){return
array();}function
fk_support($R){}function
engines(){return
array();}function
alter_table($Q,$B,$p,$ed,$ub,$wc,$d,$Ma,$Wf){global$g;if($Q==""){$g->_db->createCollection($B);return
true;}}function
drop_tables($S){global$g;foreach($S
as$Q){$Og=$g->_db->selectCollection($Q)->drop();if(!$Og['ok'])return
false;}return
true;}function
truncate_tables($S){global$g;foreach($S
as$Q){$Og=$g->_db->selectCollection($Q)->remove();if(!$Og['ok'])return
false;}return
true;}$x="mongo";$md=array();$sd=array();$oc=array(array("json"));}$gc["elastic"]="Elasticsearch (beta)";if(isset($_GET["elastic"])){$jg=array("json + allow_url_fopen");define("DRIVER","elastic");if(function_exists('json_decode')&&ini_bool('allow_url_fopen')){class
Min_DB{var$extension="JSON",$server_info,$errno,$error,$_url;function
rootQuery($ag,$Ab=array(),$Te='GET'){@ini_set('track_errors',1);$Vc=@file_get_contents("$this->_url/".ltrim($ag,'/'),false,stream_context_create(array('http'=>array('method'=>$Te,'content'=>$Ab===null?$Ab:json_encode($Ab),'header'=>'Content-Type: application/json','ignore_errors'=>1,))));if(!$Vc){$this->error=$php_errormsg;return$Vc;}if(!preg_match('~^HTTP/[0-9.]+ 2~i',$http_response_header[0])){$this->error=$Vc;return
false;}$H=json_decode($Vc,true);if($H===null){$this->errno=json_last_error();if(function_exists('json_last_error_msg'))$this->error=json_last_error_msg();else{$_b=get_defined_constants(true);foreach($_b['json']as$B=>$Y){if($Y==$this->errno&&preg_match('~^JSON_ERROR_~',$B)){$this->error=$B;break;}}}}return$H;}function
query($ag,$Ab=array(),$Te='GET'){return$this->rootQuery(($this->_db!=""?"$this->_db/":"/").ltrim($ag,'/'),$Ab,$Te);}function
connect($M,$V,$E){preg_match('~^(https?://)?(.*)~',$M,$A);$this->_url=($A[1]?$A[1]:"http://")."$V:$E@$A[2]";$H=$this->query('');if($H)$this->server_info=$H['version']['number'];return(bool)$H;}function
select_db($j){$this->_db=$j;return
true;}function
quote($P){return$P;}}class
Min_Result{var$num_rows,$_rows;function
__construct($J){$this->num_rows=count($J);$this->_rows=$J;reset($this->_rows);}function
fetch_assoc(){$H=current($this->_rows);next($this->_rows);return$H;}function
fetch_row(){return
array_values($this->fetch_assoc());}}}class
Min_Driver
extends
Min_SQL{function
select($Q,$K,$Z,$pd,$Bf=array(),$z=1,$D=0,$og=false){global$b;$Nb=array();$F="$Q/_search";if($K!=array("*"))$Nb["fields"]=$K;if($Bf){$xh=array();foreach($Bf
as$nb){$nb=preg_replace('~ DESC$~','',$nb,1,$Fb);$xh[]=($Fb?array($nb=>"desc"):$nb);}$Nb["sort"]=$xh;}if($z){$Nb["size"]=+$z;if($D)$Nb["from"]=($D*$z);}foreach($Z
as$X){list($nb,$vf,$X)=explode(" ",$X,3);if($nb=="_id")$Nb["query"]["ids"]["values"][]=$X;elseif($nb.$X!=""){$ci=array("term"=>array(($nb!=""?$nb:"_all")=>$X));if($vf=="=")$Nb["query"]["filtered"]["filter"]["and"][]=$ci;else$Nb["query"]["filtered"]["query"]["bool"]["must"][]=$ci;}}if($Nb["query"]&&!$Nb["query"]["filtered"]["query"]&&!$Nb["query"]["ids"])$Nb["query"]["filtered"]["query"]=array("match_all"=>array());$Fh=microtime(true);$eh=$this->_conn->query($F,$Nb);if($og)echo$b->selectQuery("$F: ".json_encode($Nb),$Fh,!$eh);if(!$eh)return
false;$H=array();foreach($eh['hits']['hits']as$Bd){$I=array();if($K==array("*"))$I["_id"]=$Bd["_id"];$p=$Bd['_source'];if($K!=array("*")){$p=array();foreach($K
as$y)$p[$y]=$Bd['fields'][$y];}foreach($p
as$y=>$X){if($Nb["fields"])$X=$X[0];$I[$y]=(is_array($X)?json_encode($X):$X);}$H[]=$I;}return
new
Min_Result($H);}function
update($T,$Cg,$yg,$z=0,$L="\n"){$Yf=preg_split('~ *= *~',$yg);if(count($Yf)==2){$t=trim($Yf[1]);$F="$T/$t";return$this->_conn->query($F,$Cg,'POST');}return
false;}function
insert($T,$Cg){$t="";$F="$T/$t";$Og=$this->_conn->query($F,$Cg,'POST');$this->_conn->last_id=$Og['_id'];return$Og['created'];}function
delete($T,$yg,$z=0){$Gd=array();if(is_array($_GET["where"])&&$_GET["where"]["_id"])$Gd[]=$_GET["where"]["_id"];if(is_array($_POST['check'])){foreach($_POST['check']as$db){$Yf=preg_split('~ *= *~',$db);if(count($Yf)==2)$Gd[]=trim($Yf[1]);}}$this->_conn->affected_rows=0;foreach($Gd
as$t){$F="{$T}/{$t}";$Og=$this->_conn->query($F,'{}','DELETE');if(is_array($Og)&&$Og['found']==true)$this->_conn->affected_rows++;}return$this->_conn->affected_rows;}}function
connect(){global$b;$g=new
Min_DB;list($M,$V,$E)=$b->credentials();if($E!=""&&$g->connect($M,$V,""))return'Database does not support password.';if($g->connect($M,$V,$E))return$g;return$g->error;}function
support($Rc){return
preg_match("~database|table|columns~",$Rc);}function
logged_user(){global$b;$Ib=$b->credentials();return$Ib[1];}function
get_databases(){global$g;$H=$g->rootQuery('_aliases');if($H){$H=array_keys($H);sort($H,SORT_STRING);}return$H;}function
collations(){return
array();}function
db_collation($l,$pb){}function
engines(){return
array();}function
count_tables($k){global$g;$H=array();$G=$g->query('_stats');if($G&&$G['indices']){$Od=$G['indices'];foreach($Od
as$Nd=>$Gh){$Md=$Gh['total']['indexing'];$H[$Nd]=$Md['index_total'];}}return$H;}function
tables_list(){global$g;$H=$g->query('_mapping');if($H)$H=array_fill_keys(array_keys($H[$g->_db]["mappings"]),'table');return$H;}function
table_status($B="",$Qc=false){global$g;$eh=$g->query("_search",array("size"=>0,"aggregations"=>array("count_by_type"=>array("terms"=>array("field"=>"_type")))),"POST");$H=array();if($eh){$S=$eh["aggregations"]["count_by_type"]["buckets"];foreach($S
as$Q){$H[$Q["key"]]=array("Name"=>$Q["key"],"Engine"=>"table","Rows"=>$Q["doc_count"],);if($B!=""&&$B==$Q["key"])return$H[$B];}}return$H;}function
error(){global$g;return
h($g->error);}function
information_schema(){}function
is_view($R){}function
indexes($Q,$h=null){return
array(array("type"=>"PRIMARY","columns"=>array("_id")),);}function
fields($Q){global$g;$G=$g->query("$Q/_mapping");$H=array();if($G){$Be=$G[$Q]['properties'];if(!$Be)$Be=$G[$g->_db]['mappings'][$Q]['properties'];if($Be){foreach($Be
as$B=>$o){$H[$B]=array("field"=>$B,"full_type"=>$o["type"],"type"=>$o["type"],"privileges"=>array("insert"=>1,"select"=>1,"update"=>1),);if($o["properties"]){unset($H[$B]["privileges"]["insert"]);unset($H[$B]["privileges"]["update"]);}}}}return$H;}function
foreign_keys($Q){return
array();}function
table($u){return$u;}function
idf_escape($u){return$u;}function
convert_field($o){}function
unconvert_field($o,$H){return$H;}function
fk_support($R){}function
found_rows($R,$Z){return
null;}function
create_database($l){global$g;return$g->rootQuery(urlencode($l),null,'PUT');}function
drop_databases($k){global$g;return$g->rootQuery(urlencode(implode(',',$k)),array(),'DELETE');}function
alter_table($Q,$B,$p,$ed,$ub,$wc,$d,$Ma,$Wf){global$g;$ug=array();foreach($p
as$Oc){$Tc=trim($Oc[1][0]);$Uc=trim($Oc[1][1]?$Oc[1][1]:"text");$ug[$Tc]=array('type'=>$Uc);}if(!empty($ug))$ug=array('properties'=>$ug);return$g->query("_mapping/{$B}",$ug,'PUT');}function
drop_tables($S){global$g;$H=true;foreach($S
as$Q)$H=$H&&$g->query(urlencode($Q),array(),'DELETE');return$H;}function
last_id(){global$g;return$g->last_id;}$x="elastic";$xf=array("=","query");$md=array();$sd=array();$oc=array(array("json"));$U=array();$Jh=array();foreach(array('Numbers'=>array("long"=>3,"integer"=>5,"short"=>8,"byte"=>10,"double"=>20,"float"=>66,"half_float"=>12,"scaled_float"=>21),'Date and time'=>array("date"=>10),'Strings'=>array("string"=>65535,"text"=>65535),'Binary'=>array("binary"=>255),)as$y=>$X){$U+=$X;$Jh[$y]=array_keys($X);}}$gc["clickhouse"]="ClickHouse (alpha)";if(isset($_GET["clickhouse"])){define("DRIVER","clickhouse");class
Min_DB{var$extension="JSON",$server_info,$errno,$_result,$error,$_url;var$_db='default';function
rootQuery($l,$F){@ini_set('track_errors',1);$Vc=@file_get_contents("$this->_url/?database=$l",false,stream_context_create(array('http'=>array('method'=>'POST','content'=>$this->isQuerySelectLike($F)?"$F FORMAT JSONCompact":$F,'header'=>'Content-type: application/x-www-form-urlencoded','ignore_errors'=>1,))));if($Vc===false){$this->error=$php_errormsg;return$Vc;}if(!preg_match('~^HTTP/[0-9.]+ 2~i',$http_response_header[0])){$this->error=$Vc;return
false;}$H=json_decode($Vc,true);if($H===null){if(!$this->isQuerySelectLike($F)&&$Vc==='')return
true;$this->errno=json_last_error();if(function_exists('json_last_error_msg'))$this->error=json_last_error_msg();else{$_b=get_defined_constants(true);foreach($_b['json']as$B=>$Y){if($Y==$this->errno&&preg_match('~^JSON_ERROR_~',$B)){$this->error=$B;break;}}}}return
new
Min_Result($H);}function
isQuerySelectLike($F){return(bool)preg_match('~^(select|show)~i',$F);}function
query($F){return$this->rootQuery($this->_db,$F);}function
connect($M,$V,$E){preg_match('~^(https?://)?(.*)~',$M,$A);$this->_url=($A[1]?$A[1]:"http://")."$V:$E@$A[2]";$H=$this->query('SELECT 1');return(bool)$H;}function
select_db($j){$this->_db=$j;return
true;}function
quote($P){return"'".addcslashes($P,"\\'")."'";}function
multi_query($F){return$this->_result=$this->query($F);}function
store_result(){return$this->_result;}function
next_result(){return
false;}function
result($F,$o=0){$G=$this->query($F);return$G['data'];}}class
Min_Result{var$num_rows,$_rows,$columns,$meta,$_offset=0;function
__construct($G){$this->num_rows=$G['rows'];$this->_rows=$G['data'];$this->meta=$G['meta'];$this->columns=array_column($this->meta,'name');reset($this->_rows);}function
fetch_assoc(){$I=current($this->_rows);next($this->_rows);return$I===false?false:array_combine($this->columns,$I);}function
fetch_row(){$I=current($this->_rows);next($this->_rows);return$I;}function
fetch_field(){$e=$this->_offset++;$H=new
stdClass;if($e<count($this->columns)){$H->name=$this->meta[$e]['name'];$H->orgname=$H->name;$H->type=$this->meta[$e]['type'];}return$H;}}class
Min_Driver
extends
Min_SQL{function
delete($Q,$yg,$z=0){if($yg==='')$yg='WHERE 1=1';return
queries("ALTER TABLE ".table($Q)." DELETE $yg");}function
update($Q,$N,$yg,$z=0,$L="\n"){$Xi=array();foreach($N
as$y=>$X)$Xi[]="$y = $X";$F=$L.implode(",$L",$Xi);return
queries("ALTER TABLE ".table($Q)." UPDATE $F$yg");}}function
idf_escape($u){return"`".str_replace("`","``",$u)."`";}function
table($u){return
idf_escape($u);}function
explain($g,$F){return'';}function
found_rows($R,$Z){$J=get_vals("SELECT COUNT(*) FROM ".idf_escape($R["Name"]).($Z?" WHERE ".implode(" AND ",$Z):""));return
empty($J)?false:$J[0];}function
alter_table($Q,$B,$p,$ed,$ub,$wc,$d,$Ma,$Wf){$c=$Bf=array();foreach($p
as$o){if($o[1][2]===" NULL")$o[1][1]=" Nullable({$o[1][1]})";elseif($o[1][2]===' NOT NULL')$o[1][2]='';if($o[1][3])$o[1][3]='';$c[]=($o[1]?($Q!=""?($o[0]!=""?"MODIFY COLUMN ":"ADD COLUMN "):" ").implode($o[1]):"DROP COLUMN ".idf_escape($o[0]));$Bf[]=$o[1][0];}$c=array_merge($c,$ed);$O=($wc?" ENGINE ".$wc:"");if($Q=="")return
queries("CREATE TABLE ".table($B)." (\n".implode(",\n",$c)."\n)$O$Wf".' ORDER BY ('.implode(',',$Bf).')');if($Q!=$B){$G=queries("RENAME TABLE ".table($Q)." TO ".table($B));if($c)$Q=$B;else
return$G;}if($O)$c[]=ltrim($O);return($c||$Wf?queries("ALTER TABLE ".table($Q)."\n".implode(",\n",$c).$Wf):true);}function
truncate_tables($S){return
apply_queries("TRUNCATE TABLE",$S);}function
drop_views($cj){return
drop_tables($cj);}function
drop_tables($S){return
apply_queries("DROP TABLE",$S);}function
connect(){global$b;$g=new
Min_DB;$Ib=$b->credentials();if($g->connect($Ib[0],$Ib[1],$Ib[2]))return$g;return$g->error;}function
get_databases($cd){global$g;$G=get_rows('SHOW DATABASES');$H=array();foreach($G
as$I)$H[]=$I['name'];sort($H);return$H;}function
limit($F,$Z,$z,$C=0,$L=" "){return" $F$Z".($z!==null?$L."LIMIT $z".($C?", $C":""):"");}function
limit1($Q,$F,$Z,$L="\n"){return
limit($F,$Z,1,0,$L);}function
db_collation($l,$pb){}function
engines(){return
array('MergeTree');}function
logged_user(){global$b;$Ib=$b->credentials();return$Ib[1];}function
tables_list(){$G=get_rows('SHOW TABLES');$H=array();foreach($G
as$I)$H[$I['name']]='table';ksort($H);return$H;}function
count_tables($k){return
array();}function
table_status($B="",$Qc=false){global$g;$H=array();$S=get_rows("SELECT name, engine FROM system.tables WHERE database = ".q($g->_db));foreach($S
as$Q){$H[$Q['name']]=array('Name'=>$Q['name'],'Engine'=>$Q['engine'],);if($B===$Q['name'])return$H[$Q['name']];}return$H;}function
is_view($R){return
false;}function
fk_support($R){return
false;}function
convert_field($o){}function
unconvert_field($o,$H){if(in_array($o['type'],array("Int8","Int16","Int32","Int64","UInt8","UInt16","UInt32","UInt64","Float32","Float64")))return"to$o[type]($H)";return$H;}function
fields($Q){$H=array();$G=get_rows("SELECT name, type, default_expression FROM system.columns WHERE ".idf_escape('table')." = ".q($Q));foreach($G
as$I){$T=trim($I['type']);$hf=strpos($T,'Nullable(')===0;$H[trim($I['name'])]=array("field"=>trim($I['name']),"full_type"=>$T,"type"=>$T,"default"=>trim($I['default_expression']),"null"=>$hf,"auto_increment"=>'0',"privileges"=>array("insert"=>1,"select"=>1,"update"=>0),);}return$H;}function
indexes($Q,$h=null){return
array();}function
foreign_keys($Q){return
array();}function
collations(){return
array();}function
information_schema($l){return
false;}function
error(){global$g;return
h($g->error);}function
types(){return
array();}function
schemas(){return
array();}function
get_schema(){return"";}function
set_schema($ch){return
true;}function
auto_increment(){return'';}function
last_id(){return
0;}function
support($Rc){return
preg_match("~^(columns|sql|status|table|drop_col)$~",$Rc);}$x="clickhouse";$U=array();$Jh=array();foreach(array('Numbers'=>array("Int8"=>3,"Int16"=>5,"Int32"=>10,"Int64"=>19,"UInt8"=>3,"UInt16"=>5,"UInt32"=>10,"UInt64"=>20,"Float32"=>7,"Float64"=>16,'Decimal'=>38,'Decimal32'=>9,'Decimal64'=>18,'Decimal128'=>38),'Date and time'=>array("Date"=>13,"DateTime"=>20),'Strings'=>array("String"=>0),'Binary'=>array("FixedString"=>0),)as$y=>$X){$U+=$X;$Jh[$y]=array_keys($X);}$Ki=array();$xf=array("=","<",">","<=",">=","!=","~","!~","LIKE","LIKE %%","IN","IS NULL","NOT LIKE","NOT IN","IS NOT NULL","SQL");$md=array();$sd=array("avg","count","count distinct","max","min","sum");$oc=array();}$gc=array("server"=>"MySQL")+$gc;if(!defined("DRIVER")){$jg=array("MySQLi","MySQL","PDO_MySQL");define("DRIVER","server");if(extension_loaded("mysqli")){class
Min_DB
extends
MySQLi{var$extension="MySQLi";function
__construct(){parent::init();}function
connect($M="",$V="",$E="",$j=null,$fg=null,$wh=null){global$b;mysqli_report(MYSQLI_REPORT_OFF);list($Cd,$fg)=explode(":",$M,2);$Eh=$b->connectSsl();if($Eh)$this->ssl_set($Eh['key'],$Eh['cert'],$Eh['ca'],'','');$H=@$this->real_connect(($M!=""?$Cd:ini_get("mysqli.default_host")),($M.$V!=""?$V:ini_get("mysqli.default_user")),($M.$V.$E!=""?$E:ini_get("mysqli.default_pw")),$j,(is_numeric($fg)?$fg:ini_get("mysqli.default_port")),(!is_numeric($fg)?$fg:$wh),($Eh?64:0));$this->options(MYSQLI_OPT_LOCAL_INFILE,false);return$H;}function
set_charset($cb){if(parent::set_charset($cb))return
true;parent::set_charset('utf8');return$this->query("SET NAMES $cb");}function
result($F,$o=0){$G=$this->query($F);if(!$G)return
false;$I=$G->fetch_array();return$I[$o];}function
quote($P){return"'".$this->escape_string($P)."'";}}}elseif(extension_loaded("mysql")&&!((ini_bool("sql.safe_mode")||ini_bool("mysql.allow_local_infile"))&&extension_loaded("pdo_mysql"))){class
Min_DB{var$extension="MySQL",$server_info,$affected_rows,$errno,$error,$_link,$_result;function
connect($M,$V,$E){if(ini_bool("mysql.allow_local_infile")){$this->error=sprintf('Disable %s or enable %s or %s extensions.',"'mysql.allow_local_infile'","MySQLi","PDO_MySQL");return
false;}$this->_link=@mysql_connect(($M!=""?$M:ini_get("mysql.default_host")),("$M$V"!=""?$V:ini_get("mysql.default_user")),("$M$V$E"!=""?$E:ini_get("mysql.default_password")),true,131072);if($this->_link)$this->server_info=mysql_get_server_info($this->_link);else$this->error=mysql_error();return(bool)$this->_link;}function
set_charset($cb){if(function_exists('mysql_set_charset')){if(mysql_set_charset($cb,$this->_link))return
true;mysql_set_charset('utf8',$this->_link);}return$this->query("SET NAMES $cb");}function
quote($P){return"'".mysql_real_escape_string($P,$this->_link)."'";}function
select_db($j){return
mysql_select_db($j,$this->_link);}function
query($F,$Ei=false){$G=@($Ei?mysql_unbuffered_query($F,$this->_link):mysql_query($F,$this->_link));$this->error="";if(!$G){$this->errno=mysql_errno($this->_link);$this->error=mysql_error($this->_link);return
false;}if($G===true){$this->affected_rows=mysql_affected_rows($this->_link);$this->info=mysql_info($this->_link);return
true;}return
new
Min_Result($G);}function
multi_query($F){return$this->_result=$this->query($F);}function
store_result(){return$this->_result;}function
next_result(){return
false;}function
result($F,$o=0){$G=$this->query($F);if(!$G||!$G->num_rows)return
false;return
mysql_result($G->_result,0,$o);}}class
Min_Result{var$num_rows,$_result,$_offset=0;function
__construct($G){$this->_result=$G;$this->num_rows=mysql_num_rows($G);}function
fetch_assoc(){return
mysql_fetch_assoc($this->_result);}function
fetch_row(){return
mysql_fetch_row($this->_result);}function
fetch_field(){$H=mysql_fetch_field($this->_result,$this->_offset++);$H->orgtable=$H->table;$H->orgname=$H->name;$H->charsetnr=($H->blob?63:0);return$H;}function
__destruct(){mysql_free_result($this->_result);}}}elseif(extension_loaded("pdo_mysql")){class
Min_DB
extends
Min_PDO{var$extension="PDO_MySQL";function
connect($M,$V,$E){global$b;$_f=array(PDO::MYSQL_ATTR_LOCAL_INFILE=>false);$Eh=$b->connectSsl();if($Eh){if(!empty($Eh['key']))$_f[PDO::MYSQL_ATTR_SSL_KEY]=$Eh['key'];if(!empty($Eh['cert']))$_f[PDO::MYSQL_ATTR_SSL_CERT]=$Eh['cert'];if(!empty($Eh['ca']))$_f[PDO::MYSQL_ATTR_SSL_CA]=$Eh['ca'];}$this->dsn("mysql:charset=utf8;host=".str_replace(":",";unix_socket=",preg_replace('~:(\d)~',';port=\1',$M)),$V,$E,$_f);return
true;}function
set_charset($cb){$this->query("SET NAMES $cb");}function
select_db($j){return$this->query("USE ".idf_escape($j));}function
query($F,$Ei=false){$this->setAttribute(1000,!$Ei);return
parent::query($F,$Ei);}}}class
Min_Driver
extends
Min_SQL{function
insert($Q,$N){return($N?parent::insert($Q,$N):queries("INSERT INTO ".table($Q)." ()\nVALUES ()"));}function
insertUpdate($Q,$J,$mg){$f=array_keys(reset($J));$kg="INSERT INTO ".table($Q)." (".implode(", ",$f).") VALUES\n";$Xi=array();foreach($f
as$y)$Xi[$y]="$y = VALUES($y)";$Mh="\nON DUPLICATE KEY UPDATE ".implode(", ",$Xi);$Xi=array();$ve=0;foreach($J
as$N){$Y="(".implode(", ",$N).")";if($Xi&&(strlen($kg)+$ve+strlen($Y)+strlen($Mh)>1e6)){if(!queries($kg.implode(",\n",$Xi).$Mh))return
false;$Xi=array();$ve=0;}$Xi[]=$Y;$ve+=strlen($Y)+2;}return
queries($kg.implode(",\n",$Xi).$Mh);}function
slowQuery($F,$hi){if(min_version('5.7.8','10.1.2')){if(preg_match('~MariaDB~',$this->_conn->server_info))return"SET STATEMENT max_statement_time=$hi FOR $F";elseif(preg_match('~^(SELECT\b)(.+)~is',$F,$A))return"$A[1] /*+ MAX_EXECUTION_TIME(".($hi*1000).") */ $A[2]";}}function
convertSearch($u,$X,$o){return(preg_match('~char|text|enum|set~',$o["type"])&&!preg_match("~^utf8~",$o["collation"])&&preg_match('~[\x80-\xFF]~',$X['val'])?"CONVERT($u USING ".charset($this->_conn).")":$u);}function
warnings(){$G=$this->_conn->query("SHOW WARNINGS");if($G&&$G->num_rows){ob_start();select($G);return
ob_get_clean();}}function
tableHelp($B){$Ce=preg_match('~MariaDB~',$this->_conn->server_info);if(information_schema(DB))return
strtolower(($Ce?"information-schema-$B-table/":str_replace("_","-",$B)."-table.html"));if(DB=="mysql")return($Ce?"mysql$B-table/":"system-database.html");}}function
idf_escape($u){return"`".str_replace("`","``",$u)."`";}function
table($u){return
idf_escape($u);}function
connect(){global$b,$U,$Jh;$g=new
Min_DB;$Ib=$b->credentials();if($g->connect($Ib[0],$Ib[1],$Ib[2])){$g->set_charset(charset($g));$g->query("SET sql_quote_show_create = 1, autocommit = 1");if(min_version('5.7.8',10.2,$g)){$Jh['Strings'][]="json";$U["json"]=4294967295;}return$g;}$H=$g->error;if(function_exists('iconv')&&!is_utf8($H)&&strlen($ah=iconv("windows-1250","utf-8",$H))>strlen($H))$H=$ah;return$H;}function
get_databases($cd){$H=get_session("dbs");if($H===null){$F=(min_version(5)?"SELECT SCHEMA_NAME FROM information_schema.SCHEMATA ORDER BY SCHEMA_NAME":"SHOW DATABASES");$H=($cd?slow_query($F):get_vals($F));restart_session();set_session("dbs",$H);stop_session();}return$H;}function
limit($F,$Z,$z,$C=0,$L=" "){return" $F$Z".($z!==null?$L."LIMIT $z".($C?" OFFSET $C":""):"");}function
limit1($Q,$F,$Z,$L="\n"){return
limit($F,$Z,1,0,$L);}function
db_collation($l,$pb){global$g;$H=null;$i=$g->result("SHOW CREATE DATABASE ".idf_escape($l),1);if(preg_match('~ COLLATE ([^ ]+)~',$i,$A))$H=$A[1];elseif(preg_match('~ CHARACTER SET ([^ ]+)~',$i,$A))$H=$pb[$A[1]][-1];return$H;}function
engines(){$H=array();foreach(get_rows("SHOW ENGINES")as$I){if(preg_match("~YES|DEFAULT~",$I["Support"]))$H[]=$I["Engine"];}return$H;}function
logged_user(){global$g;return$g->result("SELECT USER()");}function
tables_list(){return
get_key_vals(min_version(5)?"SELECT TABLE_NAME, TABLE_TYPE FROM information_schema.TABLES WHERE TABLE_SCHEMA = DATABASE() ORDER BY TABLE_NAME":"SHOW TABLES");}function
count_tables($k){$H=array();foreach($k
as$l)$H[$l]=count(get_vals("SHOW TABLES IN ".idf_escape($l)));return$H;}function
table_status($B="",$Qc=false){$H=array();foreach(get_rows($Qc&&min_version(5)?"SELECT TABLE_NAME AS Name, ENGINE AS Engine, TABLE_COMMENT AS Comment FROM information_schema.TABLES WHERE TABLE_SCHEMA = DATABASE() ".($B!=""?"AND TABLE_NAME = ".q($B):"ORDER BY Name"):"SHOW TABLE STATUS".($B!=""?" LIKE ".q(addcslashes($B,"%_\\")):""))as$I){if($I["Engine"]=="InnoDB")$I["Comment"]=preg_replace('~(?:(.+); )?InnoDB free: .*~','\1',$I["Comment"]);if(!isset($I["Engine"]))$I["Comment"]="";if($B!="")return$I;$H[$I["Name"]]=$I;}return$H;}function
is_view($R){return$R["Engine"]===null;}function
fk_support($R){return
preg_match('~InnoDB|IBMDB2I~i',$R["Engine"])||(preg_match('~NDB~i',$R["Engine"])&&min_version(5.6));}function
fields($Q){$H=array();foreach(get_rows("SHOW FULL COLUMNS FROM ".table($Q))as$I){preg_match('~^([^( ]+)(?:\((.+)\))?( unsigned)?( zerofill)?$~',$I["Type"],$A);$H[$I["Field"]]=array("field"=>$I["Field"],"full_type"=>$I["Type"],"type"=>$A[1],"length"=>$A[2],"unsigned"=>ltrim($A[3].$A[4]),"default"=>($I["Default"]!=""||preg_match("~char|set~",$A[1])?$I["Default"]:null),"null"=>($I["Null"]=="YES"),"auto_increment"=>($I["Extra"]=="auto_increment"),"on_update"=>(preg_match('~^on update (.+)~i',$I["Extra"],$A)?$A[1]:""),"collation"=>$I["Collation"],"privileges"=>array_flip(preg_split('~, *~',$I["Privileges"])),"comment"=>$I["Comment"],"primary"=>($I["Key"]=="PRI"),"generated"=>preg_match('~^(VIRTUAL|PERSISTENT|STORED)~',$I["Extra"]),);}return$H;}function
indexes($Q,$h=null){$H=array();foreach(get_rows("SHOW INDEX FROM ".table($Q),$h)as$I){$B=$I["Key_name"];$H[$B]["type"]=($B=="PRIMARY"?"PRIMARY":($I["Index_type"]=="FULLTEXT"?"FULLTEXT":($I["Non_unique"]?($I["Index_type"]=="SPATIAL"?"SPATIAL":"INDEX"):"UNIQUE")));$H[$B]["columns"][]=$I["Column_name"];$H[$B]["lengths"][]=($I["Index_type"]=="SPATIAL"?null:$I["Sub_part"]);$H[$B]["descs"][]=null;}return$H;}function
foreign_keys($Q){global$g,$sf;static$cg='(?:`(?:[^`]|``)+`|"(?:[^"]|"")+")';$H=array();$Gb=$g->result("SHOW CREATE TABLE ".table($Q),1);if($Gb){preg_match_all("~CONSTRAINT ($cg) FOREIGN KEY ?\\(((?:$cg,? ?)+)\\) REFERENCES ($cg)(?:\\.($cg))? \\(((?:$cg,? ?)+)\\)(?: ON DELETE ($sf))?(?: ON UPDATE ($sf))?~",$Gb,$Fe,PREG_SET_ORDER);foreach($Fe
as$A){preg_match_all("~$cg~",$A[2],$yh);preg_match_all("~$cg~",$A[5],$Zh);$H[idf_unescape($A[1])]=array("db"=>idf_unescape($A[4]!=""?$A[3]:$A[4]),"table"=>idf_unescape($A[4]!=""?$A[4]:$A[3]),"source"=>array_map('idf_unescape',$yh[0]),"target"=>array_map('idf_unescape',$Zh[0]),"on_delete"=>($A[6]?$A[6]:"RESTRICT"),"on_update"=>($A[7]?$A[7]:"RESTRICT"),);}}return$H;}function
view($B){global$g;return
array("select"=>preg_replace('~^(?:[^`]|`[^`]*`)*\s+AS\s+~isU','',$g->result("SHOW CREATE VIEW ".table($B),1)));}function
collations(){$H=array();foreach(get_rows("SHOW COLLATION")as$I){if($I["Default"])$H[$I["Charset"]][-1]=$I["Collation"];else$H[$I["Charset"]][]=$I["Collation"];}ksort($H);foreach($H
as$y=>$X)asort($H[$y]);return$H;}function
information_schema($l){return(min_version(5)&&$l=="information_schema")||(min_version(5.5)&&$l=="performance_schema");}function
error(){global$g;return
h(preg_replace('~^You have an error.*syntax to use~U',"Syntax error",$g->error));}function
create_database($l,$d){return
queries("CREATE DATABASE ".idf_escape($l).($d?" COLLATE ".q($d):""));}function
drop_databases($k){$H=apply_queries("DROP DATABASE",$k,'idf_escape');restart_session();set_session("dbs",null);return$H;}function
rename_database($B,$d){$H=false;if(create_database($B,$d)){$Mg=array();foreach(tables_list()as$Q=>$T)$Mg[]=table($Q)." TO ".idf_escape($B).".".table($Q);$H=(!$Mg||queries("RENAME TABLE ".implode(", ",$Mg)));if($H)queries("DROP DATABASE ".idf_escape(DB));restart_session();set_session("dbs",null);}return$H;}function
auto_increment(){$Na=" PRIMARY KEY";if($_GET["create"]!=""&&$_POST["auto_increment_col"]){foreach(indexes($_GET["create"])as$v){if(in_array($_POST["fields"][$_POST["auto_increment_col"]]["orig"],$v["columns"],true)){$Na="";break;}if($v["type"]=="PRIMARY")$Na=" UNIQUE";}}return" AUTO_INCREMENT$Na";}function
alter_table($Q,$B,$p,$ed,$ub,$wc,$d,$Ma,$Wf){$c=array();foreach($p
as$o)$c[]=($o[1]?($Q!=""?($o[0]!=""?"CHANGE ".idf_escape($o[0]):"ADD"):" ")." ".implode($o[1]).($Q!=""?$o[2]:""):"DROP ".idf_escape($o[0]));$c=array_merge($c,$ed);$O=($ub!==null?" COMMENT=".q($ub):"").($wc?" ENGINE=".q($wc):"").($d?" COLLATE ".q($d):"").($Ma!=""?" AUTO_INCREMENT=$Ma":"");if($Q=="")return
queries("CREATE TABLE ".table($B)." (\n".implode(",\n",$c)."\n)$O$Wf");if($Q!=$B)$c[]="RENAME TO ".table($B);if($O)$c[]=ltrim($O);return($c||$Wf?queries("ALTER TABLE ".table($Q)."\n".implode(",\n",$c).$Wf):true);}function
alter_indexes($Q,$c){foreach($c
as$y=>$X)$c[$y]=($X[2]=="DROP"?"\nDROP INDEX ".idf_escape($X[1]):"\nADD $X[0] ".($X[0]=="PRIMARY"?"KEY ":"").($X[1]!=""?idf_escape($X[1])." ":"")."(".implode(", ",$X[2]).")");return
queries("ALTER TABLE ".table($Q).implode(",",$c));}function
truncate_tables($S){return
apply_queries("TRUNCATE TABLE",$S);}function
drop_views($cj){return
queries("DROP VIEW ".implode(", ",array_map('table',$cj)));}function
drop_tables($S){return
queries("DROP TABLE ".implode(", ",array_map('table',$S)));}function
move_tables($S,$cj,$Zh){$Mg=array();foreach(array_merge($S,$cj)as$Q)$Mg[]=table($Q)." TO ".idf_escape($Zh).".".table($Q);return
queries("RENAME TABLE ".implode(", ",$Mg));}function
copy_tables($S,$cj,$Zh){queries("SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO'");foreach($S
as$Q){$B=($Zh==DB?table("copy_$Q"):idf_escape($Zh).".".table($Q));if(($_POST["overwrite"]&&!queries("\nDROP TABLE IF EXISTS $B"))||!queries("CREATE TABLE $B LIKE ".table($Q))||!queries("INSERT INTO $B SELECT * FROM ".table($Q)))return
false;foreach(get_rows("SHOW TRIGGERS LIKE ".q(addcslashes($Q,"%_\\")))as$I){$zi=$I["Trigger"];if(!queries("CREATE TRIGGER ".($Zh==DB?idf_escape("copy_$zi"):idf_escape($Zh).".".idf_escape($zi))." $I[Timing] $I[Event] ON $B FOR EACH ROW\n$I[Statement];"))return
false;}}foreach($cj
as$Q){$B=($Zh==DB?table("copy_$Q"):idf_escape($Zh).".".table($Q));$bj=view($Q);if(($_POST["overwrite"]&&!queries("DROP VIEW IF EXISTS $B"))||!queries("CREATE VIEW $B AS $bj[select]"))return
false;}return
true;}function
trigger($B){if($B=="")return
array();$J=get_rows("SHOW TRIGGERS WHERE `Trigger` = ".q($B));return
reset($J);}function
triggers($Q){$H=array();foreach(get_rows("SHOW TRIGGERS LIKE ".q(addcslashes($Q,"%_\\")))as$I)$H[$I["Trigger"]]=array($I["Timing"],$I["Event"]);return$H;}function
trigger_options(){return
array("Timing"=>array("BEFORE","AFTER"),"Event"=>array("INSERT","UPDATE","DELETE"),"Type"=>array("FOR EACH ROW"),);}function
routine($B,$T){global$g,$yc,$Td,$U;$Ca=array("bool","boolean","integer","double precision","real","dec","numeric","fixed","national char","national varchar");$zh="(?:\\s|/\\*[\s\S]*?\\*/|(?:#|-- )[^\n]*\n?|--\r?\n)";$Di="((".implode("|",array_merge(array_keys($U),$Ca)).")\\b(?:\\s*\\(((?:[^'\")]|$yc)++)\\))?\\s*(zerofill\\s*)?(unsigned(?:\\s+zerofill)?)?)(?:\\s*(?:CHARSET|CHARACTER\\s+SET)\\s*['\"]?([^'\"\\s,]+)['\"]?)?";$cg="$zh*(".($T=="FUNCTION"?"":$Td).")?\\s*(?:`((?:[^`]|``)*)`\\s*|\\b(\\S+)\\s+)$Di";$i=$g->result("SHOW CREATE $T ".idf_escape($B),2);preg_match("~\\(((?:$cg\\s*,?)*)\\)\\s*".($T=="FUNCTION"?"RETURNS\\s+$Di\\s+":"")."(.*)~is",$i,$A);$p=array();preg_match_all("~$cg\\s*,?~is",$A[1],$Fe,PREG_SET_ORDER);foreach($Fe
as$Pf)$p[]=array("field"=>str_replace("``","`",$Pf[2]).$Pf[3],"type"=>strtolower($Pf[5]),"length"=>preg_replace_callback("~$yc~s",'normalize_enum',$Pf[6]),"unsigned"=>strtolower(preg_replace('~\s+~',' ',trim("$Pf[8] $Pf[7]"))),"null"=>1,"full_type"=>$Pf[4],"inout"=>strtoupper($Pf[1]),"collation"=>strtolower($Pf[9]),);if($T!="FUNCTION")return
array("fields"=>$p,"definition"=>$A[11]);return
array("fields"=>$p,"returns"=>array("type"=>$A[12],"length"=>$A[13],"unsigned"=>$A[15],"collation"=>$A[16]),"definition"=>$A[17],"language"=>"SQL",);}function
routines(){return
get_rows("SELECT ROUTINE_NAME AS SPECIFIC_NAME, ROUTINE_NAME, ROUTINE_TYPE, DTD_IDENTIFIER FROM information_schema.ROUTINES WHERE ROUTINE_SCHEMA = ".q(DB));}function
routine_languages(){return
array();}function
routine_id($B,$I){return
idf_escape($B);}function
last_id(){global$g;return$g->result("SELECT LAST_INSERT_ID()");}function
explain($g,$F){return$g->query("EXPLAIN ".(min_version(5.1)?"PARTITIONS ":"").$F);}function
found_rows($R,$Z){return($Z||$R["Engine"]!="InnoDB"?null:$R["Rows"]);}function
types(){return
array();}function
schemas(){return
array();}function
get_schema(){return"";}function
set_schema($ch,$h=null){return
true;}function
create_sql($Q,$Ma,$Kh){global$g;$H=$g->result("SHOW CREATE TABLE ".table($Q),1);if(!$Ma)$H=preg_replace('~ AUTO_INCREMENT=\d+~','',$H);return$H;}function
truncate_sql($Q){return"TRUNCATE ".table($Q);}function
use_sql($j){return"USE ".idf_escape($j);}function
trigger_sql($Q){$H="";foreach(get_rows("SHOW TRIGGERS LIKE ".q(addcslashes($Q,"%_\\")),null,"-- ")as$I)$H.="\nCREATE TRIGGER ".idf_escape($I["Trigger"])." $I[Timing] $I[Event] ON ".table($I["Table"])." FOR EACH ROW\n$I[Statement];;\n";return$H;}function
show_variables(){return
get_key_vals("SHOW VARIABLES");}function
process_list(){return
get_rows("SHOW FULL PROCESSLIST");}function
show_status(){return
get_key_vals("SHOW STATUS");}function
convert_field($o){if(preg_match("~binary~",$o["type"]))return"HEX(".idf_escape($o["field"]).")";if($o["type"]=="bit")return"BIN(".idf_escape($o["field"])." + 0)";if(preg_match("~geometry|point|linestring|polygon~",$o["type"]))return(min_version(8)?"ST_":"")."AsWKT(".idf_escape($o["field"]).")";}function
unconvert_field($o,$H){if(preg_match("~binary~",$o["type"]))$H="UNHEX($H)";if($o["type"]=="bit")$H="CONV($H, 2, 10) + 0";if(preg_match("~geometry|point|linestring|polygon~",$o["type"]))$H=(min_version(8)?"ST_":"")."GeomFromText($H, SRID($o[field]))";return$H;}function
support($Rc){return!preg_match("~scheme|sequence|type|view_trigger|materializedview".(min_version(8)?"":"|descidx".(min_version(5.1)?"":"|event|partitioning".(min_version(5)?"":"|routine|trigger|view")))."~",$Rc);}function
kill_process($X){return
queries("KILL ".number($X));}function
connection_id(){return"SELECT CONNECTION_ID()";}function
max_connections(){global$g;return$g->result("SELECT @@max_connections");}$x="sql";$U=array();$Jh=array();foreach(array('Numbers'=>array("tinyint"=>3,"smallint"=>5,"mediumint"=>8,"int"=>10,"bigint"=>20,"decimal"=>66,"float"=>12,"double"=>21),'Date and time'=>array("date"=>10,"datetime"=>19,"timestamp"=>19,"time"=>10,"year"=>4),'Strings'=>array("char"=>255,"varchar"=>65535,"tinytext"=>255,"text"=>65535,"mediumtext"=>16777215,"longtext"=>4294967295),'Lists'=>array("enum"=>65535,"set"=>64),'Binary'=>array("bit"=>20,"binary"=>255,"varbinary"=>65535,"tinyblob"=>255,"blob"=>65535,"mediumblob"=>16777215,"longblob"=>4294967295),'Geometry'=>array("geometry"=>0,"point"=>0,"linestring"=>0,"polygon"=>0,"multipoint"=>0,"multilinestring"=>0,"multipolygon"=>0,"geometrycollection"=>0),)as$y=>$X){$U+=$X;$Jh[$y]=array_keys($X);}$Ki=array("unsigned","zerofill","unsigned zerofill");$xf=array("=","<",">","<=",">=","!=","LIKE","LIKE %%","REGEXP","IN","FIND_IN_SET","IS NULL","NOT LIKE","NOT REGEXP","NOT IN","IS NOT NULL","SQL");$md=array("char_length","date","from_unixtime","lower","round","floor","ceil","sec_to_time","time_to_sec","upper");$sd=array("avg","count","count distinct","group_concat","max","min","sum");$oc=array(array("char"=>"md5/sha1/password/encrypt/uuid","binary"=>"md5/sha1","date|time"=>"now",),array(number_type()=>"+/-","date"=>"+ interval/- interval","time"=>"addtime/subtime","char|text"=>"concat",));}define("SERVER",$_GET[DRIVER]);define("DB",$_GET["db"]);define("ME",str_replace(":","%3a",preg_replace('~\?.*~','',relative_uri())).'?'.(sid()?SID.'&':'').(SERVER!==null?DRIVER."=".urlencode(SERVER).'&':'').(isset($_GET["username"])?"username=".urlencode($_GET["username"]).'&':'').(DB!=""?'db='.urlencode(DB).'&'.(isset($_GET["ns"])?"ns=".urlencode($_GET["ns"])."&":""):''));$ia="4.7.7";class
Adminer{var$operators;function
name(){return"<a href='https://www.adminer.org/'".target_blank()." id='h1'>Adminer</a>";}function
credentials(){return
array(SERVER,$_GET["username"],get_password());}function
connectSsl(){}function
permanentLogin($i=false){return
password_file($i);}function
bruteForceKey(){return$_SERVER["REMOTE_ADDR"];}function
serverName($M){return
h($M);}function
database(){return
DB;}function
databases($cd=true){return
get_databases($cd);}function
schemas(){return
schemas();}function
queryTimeout(){return
2;}function
headers(){}function
csp(){return
csp();}function
head(){return
true;}function
css(){$H=array();$Wc="adminer.css";if(file_exists($Wc))$H[]="$Wc?v=".crc32(file_get_contents($Wc));return$H;}function
loginForm(){global$gc;echo"<table cellspacing='0' class='layout'>\n",$this->loginFormField('driver','<tr><th>'.'System'.'<td>',html_select("auth[driver]",$gc,DRIVER,"loginDriver(this);")."\n"),$this->loginFormField('server','<tr><th>'.'Server'.'<td>','<input name="auth[server]" value="'.h(SERVER).'" title="hostname[:port]" placeholder="localhost" autocapitalize="off">'."\n"),$this->loginFormField('username','<tr><th>'.'Username'.'<td>','<input name="auth[username]" id="username" value="'.h($_GET["username"]).'" autocomplete="username" autocapitalize="off">'.script("focus(qs('#username')); qs('#username').form['auth[driver]'].onchange();")),$this->loginFormField('password','<tr><th>'.'Password'.'<td>','<input type="password" name="auth[password]" autocomplete="current-password">'."\n"),$this->loginFormField('db','<tr><th>'.'Database'.'<td>','<input name="auth[db]" value="'.h($_GET["db"]).'" autocapitalize="off">'."\n"),"</table>\n","<p><input type='submit' value='".'Login'."'>\n",checkbox("auth[permanent]",1,$_COOKIE["adminer_permanent"],'Permanent login')."\n";}function
loginFormField($B,$zd,$Y){return$zd.$Y;}function
login($_e,$E){if($E=="")return
sprintf('Adminer does not support accessing a database without a password, <a href="https://www.adminer.org/en/password/"%s>more information</a>.',target_blank());return
true;}function
tableName($Qh){return
h($Qh["Name"]);}function
fieldName($o,$Bf=0){return'<span title="'.h($o["full_type"]).'">'.h($o["field"]).'</span>';}function
selectLinks($Qh,$N=""){global$x,$m;echo'<p class="links">';$ye=array("select"=>'Select data');if(support("table")||support("indexes"))$ye["table"]='Show structure';if(support("table")){if(is_view($Qh))$ye["view"]='Alter view';else$ye["create"]='Alter table';}if($N!==null)$ye["edit"]='New item';$B=$Qh["Name"];foreach($ye
as$y=>$X)echo" <a href='".h(ME)."$y=".urlencode($B).($y=="edit"?$N:"")."'".bold(isset($_GET[$y])).">$X</a>";echo
doc_link(array($x=>$m->tableHelp($B)),"?"),"\n";}function
foreignKeys($Q){return
foreign_keys($Q);}function
backwardKeys($Q,$Ph){return
array();}function
backwardKeysPrint($Pa,$I){}function
selectQuery($F,$Fh,$Pc=false){global$x,$m;$H="</p>\n";if(!$Pc&&($fj=$m->warnings())){$t="warnings";$H=", <a href='#$t'>".'Warnings'."</a>".script("qsl('a').onclick = partial(toggle, '$t');","")."$H<div id='$t' class='hidden'>\n$fj</div>\n";}return"<p><code class='jush-$x'>".h(str_replace("\n"," ",$F))."</code> <span class='time'>(".format_time($Fh).")</span>".(support("sql")?" <a href='".h(ME)."sql=".urlencode($F)."'>".'Edit'."</a>":"").$H;}function
sqlCommandQuery($F){return
shorten_utf8(trim($F),1000);}function
rowDescription($Q){return"";}function
rowDescriptions($J,$fd){return$J;}function
selectLink($X,$o){}function
selectVal($X,$_,$o,$Jf){$H=($X===null?"<i>NULL</i>":(preg_match("~char|binary|boolean~",$o["type"])&&!preg_match("~var~",$o["type"])?"<code>$X</code>":$X));if(preg_match('~blob|bytea|raw|file~',$o["type"])&&!is_utf8($X))$H="<i>".lang(array('%d byte','%d bytes'),strlen($Jf))."</i>";if(preg_match('~json~',$o["type"]))$H="<code class='jush-js'>$H</code>";return($_?"<a href='".h($_)."'".(is_url($_)?target_blank():"").">$H</a>":$H);}function
editVal($X,$o){return$X;}function
tableStructurePrint($p){echo"<div class='scrollable'>\n","<table cellspacing='0' class='nowrap'>\n","<thead><tr><th>".'Column'."<td>".'Type'.(support("comment")?"<td>".'Comment':"")."</thead>\n";foreach($p
as$o){echo"<tr".odd()."><th>".h($o["field"]),"<td><span title='".h($o["collation"])."'>".h($o["full_type"])."</span>",($o["null"]?" <i>NULL</i>":""),($o["auto_increment"]?" <i>".'Auto Increment'."</i>":""),(isset($o["default"])?" <span title='".'Default value'."'>[<b>".h($o["default"])."</b>]</span>":""),(support("comment")?"<td>".h($o["comment"]):""),"\n";}echo"</table>\n","</div>\n";}function
tableIndexesPrint($w){echo"<table cellspacing='0'>\n";foreach($w
as$B=>$v){ksort($v["columns"]);$og=array();foreach($v["columns"]as$y=>$X)$og[]="<i>".h($X)."</i>".($v["lengths"][$y]?"(".$v["lengths"][$y].")":"").($v["descs"][$y]?" DESC":"");echo"<tr title='".h($B)."'><th>$v[type]<td>".implode(", ",$og)."\n";}echo"</table>\n";}function
selectColumnsPrint($K,$f){global$md,$sd;print_fieldset("select",'Select',$K);$s=0;$K[""]=array();foreach($K
as$y=>$X){$X=$_GET["columns"][$y];$e=select_input(" name='columns[$s][col]'",$f,$X["col"],($y!==""?"selectFieldChange":"selectAddRow"));echo"<div>".($md||$sd?"<select name='columns[$s][fun]'>".optionlist(array(-1=>"")+array_filter(array('Functions'=>$md,'Aggregation'=>$sd)),$X["fun"])."</select>".on_help("getTarget(event).value && getTarget(event).value.replace(/ |\$/, '(') + ')'",1).script("qsl('select').onchange = function () { helpClose();".($y!==""?"":" qsl('select, input', this.parentNode).onchange();")." };","")."($e)":$e)."</div>\n";$s++;}echo"</div></fieldset>\n";}function
selectSearchPrint($Z,$f,$w){print_fieldset("search",'Search',$Z);foreach($w
as$s=>$v){if($v["type"]=="FULLTEXT"){echo"<div>(<i>".implode("</i>, <i>",array_map('h',$v["columns"]))."</i>) AGAINST"," <input type='search' name='fulltext[$s]' value='".h($_GET["fulltext"][$s])."'>",script("qsl('input').oninput = selectFieldChange;",""),checkbox("boolean[$s]",1,isset($_GET["boolean"][$s]),"BOOL"),"</div>\n";}}$bb="this.parentNode.firstChild.onchange();";foreach(array_merge((array)$_GET["where"],array(array()))as$s=>$X){if(!$X||("$X[col]$X[val]"!=""&&in_array($X["op"],$this->operators))){echo"<div>".select_input(" name='where[$s][col]'",$f,$X["col"],($X?"selectFieldChange":"selectAddRow"),"(".'anywhere'.")"),html_select("where[$s][op]",$this->operators,$X["op"],$bb),"<input type='search' name='where[$s][val]' value='".h($X["val"])."'>",script("mixin(qsl('input'), {oninput: function () { $bb }, onkeydown: selectSearchKeydown, onsearch: selectSearchSearch});",""),"</div>\n";}}echo"</div></fieldset>\n";}function
selectOrderPrint($Bf,$f,$w){print_fieldset("sort",'Sort',$Bf);$s=0;foreach((array)$_GET["order"]as$y=>$X){if($X!=""){echo"<div>".select_input(" name='order[$s]'",$f,$X,"selectFieldChange"),checkbox("desc[$s]",1,isset($_GET["desc"][$y]),'descending')."</div>\n";$s++;}}echo"<div>".select_input(" name='order[$s]'",$f,"","selectAddRow"),checkbox("desc[$s]",1,false,'descending')."</div>\n","</div></fieldset>\n";}function
selectLimitPrint($z){echo"<fieldset><legend>".'Limit'."</legend><div>";echo"<input type='number' name='limit' class='size' value='".h($z)."'>",script("qsl('input').oninput = selectFieldChange;",""),"</div></fieldset>\n";}function
selectLengthPrint($fi){if($fi!==null){echo"<fieldset><legend>".'Text length'."</legend><div>","<input type='number' name='text_length' class='size' value='".h($fi)."'>","</div></fieldset>\n";}}function
selectActionPrint($w){echo"<fieldset><legend>".'Action'."</legend><div>","<input type='submit' value='".'Select'."'>"," <span id='noindex' title='".'Full table scan'."'></span>","<script".nonce().">\n","var indexColumns = ";$f=array();foreach($w
as$v){$Mb=reset($v["columns"]);if($v["type"]!="FULLTEXT"&&$Mb)$f[$Mb]=1;}$f[""]=1;foreach($f
as$y=>$X)json_row($y);echo";\n","selectFieldChange.call(qs('#form')['select']);\n","</script>\n","</div></fieldset>\n";}function
selectCommandPrint(){return!information_schema(DB);}function
selectImportPrint(){return!information_schema(DB);}function
selectEmailPrint($tc,$f){}function
selectColumnsProcess($f,$w){global$md,$sd;$K=array();$pd=array();foreach((array)$_GET["columns"]as$y=>$X){if($X["fun"]=="count"||($X["col"]!=""&&(!$X["fun"]||in_array($X["fun"],$md)||in_array($X["fun"],$sd)))){$K[$y]=apply_sql_function($X["fun"],($X["col"]!=""?idf_escape($X["col"]):"*"));if(!in_array($X["fun"],$sd))$pd[]=$K[$y];}}return
array($K,$pd);}function
selectSearchProcess($p,$w){global$g,$m;$H=array();foreach($w
as$s=>$v){if($v["type"]=="FULLTEXT"&&$_GET["fulltext"][$s]!="")$H[]="MATCH (".implode(", ",array_map('idf_escape',$v["columns"])).") AGAINST (".q($_GET["fulltext"][$s]).(isset($_GET["boolean"][$s])?" IN BOOLEAN MODE":"").")";}foreach((array)$_GET["where"]as$y=>$X){if("$X[col]$X[val]"!=""&&in_array($X["op"],$this->operators)){$kg="";$xb=" $X[op]";if(preg_match('~IN$~',$X["op"])){$Jd=process_length($X["val"]);$xb.=" ".($Jd!=""?$Jd:"(NULL)");}elseif($X["op"]=="SQL")$xb=" $X[val]";elseif($X["op"]=="LIKE %%")$xb=" LIKE ".$this->processInput($p[$X["col"]],"%$X[val]%");elseif($X["op"]=="ILIKE %%")$xb=" ILIKE ".$this->processInput($p[$X["col"]],"%$X[val]%");elseif($X["op"]=="FIND_IN_SET"){$kg="$X[op](".q($X["val"]).", ";$xb=")";}elseif(!preg_match('~NULL$~',$X["op"]))$xb.=" ".$this->processInput($p[$X["col"]],$X["val"]);if($X["col"]!="")$H[]=$kg.$m->convertSearch(idf_escape($X["col"]),$X,$p[$X["col"]]).$xb;else{$rb=array();foreach($p
as$B=>$o){if((preg_match('~^[-\d.'.(preg_match('~IN$~',$X["op"])?',':'').']+$~',$X["val"])||!preg_match('~'.number_type().'|bit~',$o["type"]))&&(!preg_match("~[\x80-\xFF]~",$X["val"])||preg_match('~char|text|enum|set~',$o["type"])))$rb[]=$kg.$m->convertSearch(idf_escape($B),$X,$o).$xb;}$H[]=($rb?"(".implode(" OR ",$rb).")":"1 = 0");}}}return$H;}function
selectOrderProcess($p,$w){$H=array();foreach((array)$_GET["order"]as$y=>$X){if($X!="")$H[]=(preg_match('~^((COUNT\(DISTINCT |[A-Z0-9_]+\()(`(?:[^`]|``)+`|"(?:[^"]|"")+")\)|COUNT\(\*\))$~',$X)?$X:idf_escape($X)).(isset($_GET["desc"][$y])?" DESC":"");}return$H;}function
selectLimitProcess(){return(isset($_GET["limit"])?$_GET["limit"]:"50");}function
selectLengthProcess(){return(isset($_GET["text_length"])?$_GET["text_length"]:"100");}function
selectEmailProcess($Z,$fd){return
false;}function
selectQueryBuild($K,$Z,$pd,$Bf,$z,$D){return"";}function
messageQuery($F,$gi,$Pc=false){global$x,$m;restart_session();$_d=&get_session("queries");if(!$_d[$_GET["db"]])$_d[$_GET["db"]]=array();if(strlen($F)>1e6)$F=preg_replace('~[\x80-\xFF]+$~','',substr($F,0,1e6))."\nâ¦";$_d[$_GET["db"]][]=array($F,time(),$gi);$Ch="sql-".count($_d[$_GET["db"]]);$H="<a href='#$Ch' class='toggle'>".'SQL command'."</a>\n";if(!$Pc&&($fj=$m->warnings())){$t="warnings-".count($_d[$_GET["db"]]);$H="<a href='#$t' class='toggle'>".'Warnings'."</a>, $H<div id='$t' class='hidden'>\n$fj</div>\n";}return" <span class='time'>".@date("H:i:s")."</span>"." $H<div id='$Ch' class='hidden'><pre><code class='jush-$x'>".shorten_utf8($F,1000)."</code></pre>".($gi?" <span class='time'>($gi)</span>":'').(support("sql")?'<p><a href="'.h(str_replace("db=".urlencode(DB),"db=".urlencode($_GET["db"]),ME).'sql=&history='.(count($_d[$_GET["db"]])-1)).'">'.'Edit'.'</a>':'').'</div>';}function
editFunctions($o){global$oc;$H=($o["null"]?"NULL/":"");foreach($oc
as$y=>$md){if(!$y||(!isset($_GET["call"])&&(isset($_GET["select"])||where($_GET)))){foreach($md
as$cg=>$X){if(!$cg||preg_match("~$cg~",$o["type"]))$H.="/$X";}if($y&&!preg_match('~set|blob|bytea|raw|file~',$o["type"]))$H.="/SQL";}}if($o["auto_increment"]&&!isset($_GET["select"])&&!where($_GET))$H='Auto Increment';return
explode("/",$H);}function
editInput($Q,$o,$Ja,$Y){if($o["type"]=="enum")return(isset($_GET["select"])?"<label><input type='radio'$Ja value='-1' checked><i>".'original'."</i></label> ":"").($o["null"]?"<label><input type='radio'$Ja value=''".($Y!==null||isset($_GET["select"])?"":" checked")."><i>NULL</i></label> ":"").enum_input("radio",$Ja,$o,$Y,0);return"";}function
editHint($Q,$o,$Y){return"";}function
processInput($o,$Y,$r=""){if($r=="SQL")return$Y;$B=$o["field"];$H=q($Y);if(preg_match('~^(now|getdate|uuid)$~',$r))$H="$r()";elseif(preg_match('~^current_(date|timestamp)$~',$r))$H=$r;elseif(preg_match('~^([+-]|\|\|)$~',$r))$H=idf_escape($B)." $r $H";elseif(preg_match('~^[+-] interval$~',$r))$H=idf_escape($B)." $r ".(preg_match("~^(\\d+|'[0-9.: -]') [A-Z_]+\$~i",$Y)?$Y:$H);elseif(preg_match('~^(addtime|subtime|concat)$~',$r))$H="$r(".idf_escape($B).", $H)";elseif(preg_match('~^(md5|sha1|password|encrypt)$~',$r))$H="$r($H)";return
unconvert_field($o,$H);}function
dumpOutput(){$H=array('text'=>'open','file'=>'save');if(function_exists('gzencode'))$H['gz']='gzip';return$H;}function
dumpFormat(){return
array('sql'=>'SQL','csv'=>'CSV,','csv;'=>'CSV;','tsv'=>'TSV');}function
dumpDatabase($l){}function
dumpTable($Q,$Kh,$ce=0){if($_POST["format"]!="sql"){echo"\xef\xbb\xbf";if($Kh)dump_csv(array_keys(fields($Q)));}else{if($ce==2){$p=array();foreach(fields($Q)as$B=>$o)$p[]=idf_escape($B)." $o[full_type]";$i="CREATE TABLE ".table($Q)." (".implode(", ",$p).")";}else$i=create_sql($Q,$_POST["auto_increment"],$Kh);set_utf8mb4($i);if($Kh&&$i){if($Kh=="DROP+CREATE"||$ce==1)echo"DROP ".($ce==2?"VIEW":"TABLE")." IF EXISTS ".table($Q).";\n";if($ce==1)$i=remove_definer($i);echo"$i;\n\n";}}}function
dumpData($Q,$Kh,$F){global$g,$x;$He=($x=="sqlite"?0:1048576);if($Kh){if($_POST["format"]=="sql"){if($Kh=="TRUNCATE+INSERT")echo
truncate_sql($Q).";\n";$p=fields($Q);}$G=$g->query($F,1);if($G){$Vd="";$Ya="";$je=array();$Mh="";$Sc=($Q!=''?'fetch_assoc':'fetch_row');while($I=$G->$Sc()){if(!$je){$Xi=array();foreach($I
as$X){$o=$G->fetch_field();$je[]=$o->name;$y=idf_escape($o->name);$Xi[]="$y = VALUES($y)";}$Mh=($Kh=="INSERT+UPDATE"?"\nON DUPLICATE KEY UPDATE ".implode(", ",$Xi):"").";\n";}if($_POST["format"]!="sql"){if($Kh=="table"){dump_csv($je);$Kh="INSERT";}dump_csv($I);}else{if(!$Vd)$Vd="INSERT INTO ".table($Q)." (".implode(", ",array_map('idf_escape',$je)).") VALUES";foreach($I
as$y=>$X){$o=$p[$y];$I[$y]=($X!==null?unconvert_field($o,preg_match(number_type(),$o["type"])&&!preg_match('~\[~',$o["full_type"])&&is_numeric($X)?$X:q(($X===false?0:$X))):"NULL");}$ah=($He?"\n":" ")."(".implode(",\t",$I).")";if(!$Ya)$Ya=$Vd.$ah;elseif(strlen($Ya)+4+strlen($ah)+strlen($Mh)<$He)$Ya.=",$ah";else{echo$Ya.$Mh;$Ya=$Vd.$ah;}}}if($Ya)echo$Ya.$Mh;}elseif($_POST["format"]=="sql")echo"-- ".str_replace("\n"," ",$g->error)."\n";}}function
dumpFilename($Ed){return
friendly_url($Ed!=""?$Ed:(SERVER!=""?SERVER:"localhost"));}function
dumpHeaders($Ed,$We=false){$Mf=$_POST["output"];$Kc=(preg_match('~sql~',$_POST["format"])?"sql":($We?"tar":"csv"));header("Content-Type: ".($Mf=="gz"?"application/x-gzip":($Kc=="tar"?"application/x-tar":($Kc=="sql"||$Mf!="file"?"text/plain":"text/csv")."; charset=utf-8")));if($Mf=="gz")ob_start('ob_gzencode',1e6);return$Kc;}function
importServerPath(){return"adminer.sql";}function
homepage(){echo'<p class="links">'.($_GET["ns"]==""&&support("database")?'<a href="'.h(ME).'database=">'.'Alter database'."</a>\n":""),(support("scheme")?"<a href='".h(ME)."scheme='>".($_GET["ns"]!=""?'Alter schema':'Create schema')."</a>\n":""),($_GET["ns"]!==""?'<a href="'.h(ME).'schema=">'.'Database schema'."</a>\n":""),(support("privileges")?"<a href='".h(ME)."privileges='>".'Privileges'."</a>\n":"");return
true;}function
navigation($Ve){global$ia,$x,$gc,$g;echo'<h1>
',$this->name(),' <span class="version">',$ia,'</span>
<a href="https://www.adminer.org/#download"',target_blank(),' id="version">',(version_compare($ia,$_COOKIE["adminer_version"])<0?h($_COOKIE["adminer_version"]):""),'</a>
</h1>
';if($Ve=="auth"){$Mf="";foreach((array)$_SESSION["pwds"]as$Zi=>$oh){foreach($oh
as$M=>$Ui){foreach($Ui
as$V=>$E){if($E!==null){$Sb=$_SESSION["db"][$Zi][$M][$V];foreach(($Sb?array_keys($Sb):array(""))as$l)$Mf.="<li><a href='".h(auth_url($Zi,$M,$V,$l))."'>($gc[$Zi]) ".h($V.($M!=""?"@".$this->serverName($M):"").($l!=""?" - $l":""))."</a>\n";}}}}if($Mf)echo"<ul id='logins'>\n$Mf</ul>\n".script("mixin(qs('#logins'), {onmouseover: menuOver, onmouseout: menuOut});");}else{if($_GET["ns"]!==""&&!$Ve&&DB!=""){$g->select_db(DB);$S=table_status('',true);}echo
script_src(preg_replace("~\\?.*~","",ME)."?file=jush.js&version=4.7.7");if(support("sql")){echo'<script',nonce(),'>
';if($S){$ye=array();foreach($S
as$Q=>$T)$ye[]=preg_quote($Q,'/');echo"var jushLinks = { $x: [ '".js_escape(ME).(support("table")?"table=":"select=")."\$&', /\\b(".implode("|",$ye).")\\b/g ] };\n";foreach(array("bac","bra","sqlite_quo","mssql_bra")as$X)echo"jushLinks.$X = jushLinks.$x;\n";}$nh=$g->server_info;echo'bodyLoad(\'',(is_object($g)?preg_replace('~^(\d\.?\d).*~s','\1',$nh):""),'\'',(preg_match('~MariaDB~',$nh)?", true":""),');
</script>
';}$this->databasesPrint($Ve);if(DB==""||!$Ve){echo"<p class='links'>".(support("sql")?"<a href='".h(ME)."sql='".bold(isset($_GET["sql"])&&!isset($_GET["import"])).">".'SQL command'."</a>\n<a href='".h(ME)."import='".bold(isset($_GET["import"])).">".'Import'."</a>\n":"")."";if(support("dump"))echo"<a href='".h(ME)."dump=".urlencode(isset($_GET["table"])?$_GET["table"]:$_GET["select"])."' id='dump'".bold(isset($_GET["dump"])).">".'Export'."</a>\n";}if($_GET["ns"]!==""&&!$Ve&&DB!=""){echo'<a href="'.h(ME).'create="'.bold($_GET["create"]==="").">".'Create table'."</a>\n";if(!$S)echo"<p class='message'>".'No tables.'."\n";else$this->tablesPrint($S);}}}function
databasesPrint($Ve){global$b,$g;$k=$this->databases();if($k&&!in_array(DB,$k))array_unshift($k,DB);echo'<form action="">
<p id="dbs">
';hidden_fields_get();$Qb=script("mixin(qsl('select'), {onmousedown: dbMouseDown, onchange: dbChange});");echo"<span title='".'database'."'>".'DB'."</span>: ".($k?"<select name='db'>".optionlist(array(""=>"")+$k,DB)."</select>$Qb":"<input name='db' value='".h(DB)."' autocapitalize='off'>\n"),"<input type='submit' value='".'Use'."'".($k?" class='hidden'":"").">\n";if($Ve!="db"&&DB!=""&&$g->select_db(DB)){if(support("scheme")){echo"<br>".'Schema'.": <select name='ns'>".optionlist(array(""=>"")+$b->schemas(),$_GET["ns"])."</select>$Qb";if($_GET["ns"]!="")set_schema($_GET["ns"]);}}foreach(array("import","sql","schema","dump","privileges")as$X){if(isset($_GET[$X])){echo"<input type='hidden' name='$X' value=''>";break;}}echo"</p></form>\n";}function
tablesPrint($S){echo"<ul id='tables'>".script("mixin(qs('#tables'), {onmouseover: menuOver, onmouseout: menuOut});");foreach($S
as$Q=>$O){$B=$this->tableName($O);if($B!=""){echo'<li><a href="'.h(ME).'select='.urlencode($Q).'"'.bold($_GET["select"]==$Q||$_GET["edit"]==$Q,"select").">".'select'."</a> ",(support("table")||support("indexes")?'<a href="'.h(ME).'table='.urlencode($Q).'"'.bold(in_array($Q,array($_GET["table"],$_GET["create"],$_GET["indexes"],$_GET["foreign"],$_GET["trigger"])),(is_view($O)?"view":"structure"))." title='".'Show structure'."'>$B</a>":"<span>$B</span>")."\n";}}echo"</ul>\n";}}$b=(function_exists('adminer_object')?adminer_object():new
Adminer);if($b->operators===null)$b->operators=$xf;function
page_header($ji,$n="",$Xa=array(),$ki=""){global$ca,$ia,$b,$gc,$x;page_headers();if(is_ajax()&&$n){page_messages($n);exit;}$li=$ji.($ki!=""?": $ki":"");$mi=strip_tags($li.(SERVER!=""&&SERVER!="localhost"?h(" - ".SERVER):"")." - ".$b->name());echo'<!DOCTYPE html>
<html lang="en" dir="ltr">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta name="robots" content="noindex">
<title>',$mi,'</title>
<link rel="stylesheet" type="text/css" href="',h(preg_replace("~\\?.*~","",ME)."?file=default.css&version=4.7.7"),'">
',script_src(preg_replace("~\\?.*~","",ME)."?file=functions.js&version=4.7.7");if($b->head()){echo'<link rel="shortcut icon" type="image/x-icon" href="',h(preg_replace("~\\?.*~","",ME)."?file=favicon.ico&version=4.7.7"),'">
<link rel="apple-touch-icon" href="',h(preg_replace("~\\?.*~","",ME)."?file=favicon.ico&version=4.7.7"),'">
';foreach($b->css()as$Kb){echo'<link rel="stylesheet" type="text/css" href="',h($Kb),'">
';}}echo'
<body class="ltr nojs">
';$Wc=get_temp_dir()."/adminer.version";if(!$_COOKIE["adminer_version"]&&function_exists('openssl_verify')&&file_exists($Wc)&&filemtime($Wc)+86400>time()){$aj=unserialize(file_get_contents($Wc));$vg="-----BEGIN PUBLIC KEY-----
MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAwqWOVuF5uw7/+Z70djoK
RlHIZFZPO0uYRezq90+7Amk+FDNd7KkL5eDve+vHRJBLAszF/7XKXe11xwliIsFs
DFWQlsABVZB3oisKCBEuI71J4kPH8dKGEWR9jDHFw3cWmoH3PmqImX6FISWbG3B8
h7FIx3jEaw5ckVPVTeo5JRm/1DZzJxjyDenXvBQ/6o9DgZKeNDgxwKzH+sw9/YCO
jHnq1cFpOIISzARlrHMa/43YfeNRAm/tsBXjSxembBPo7aQZLAWHmaj5+K19H10B
nCpz9Y++cipkVEiKRGih4ZEvjoFysEOdRLj6WiD/uUNky4xGeA6LaJqh5XpkFkcQ
fQIDAQAB
-----END PUBLIC KEY-----
";if(openssl_verify($aj["version"],base64_decode($aj["signature"]),$vg)==1)$_COOKIE["adminer_version"]=$aj["version"];}echo'<script',nonce(),'>
mixin(document.body, {onkeydown: bodyKeydown, onclick: bodyClick',(isset($_COOKIE["adminer_version"])?"":", onload: partial(verifyVersion, '$ia', '".js_escape(ME)."', '".get_token()."')");?>});
document.body.className = document.body.className.replace(/ nojs/, ' js');
var offlineMessage = '<?php echo
js_escape('You are offline.'),'\';
var thousandsSeparator = \'',js_escape(','),'\';
</script>

<div id="help" class="jush-',$x,' jsonly hidden"></div>
',script("mixin(qs('#help'), {onmouseover: function () { helpOpen = 1; }, onmouseout: helpMouseout});"),'
<div id="content">
';if($Xa!==null){$_=substr(preg_replace('~\b(username|db|ns)=[^&]*&~','',ME),0,-1);echo'<p id="breadcrumb"><a href="'.h($_?$_:".").'">'.$gc[DRIVER].'</a> &raquo; ';$_=substr(preg_replace('~\b(db|ns)=[^&]*&~','',ME),0,-1);$M=$b->serverName(SERVER);$M=($M!=""?$M:'Server');if($Xa===false)echo"$M\n";else{echo"<a href='".($_?h($_):".")."' accesskey='1' title='Alt+Shift+1'>$M</a> &raquo; ";if($_GET["ns"]!=""||(DB!=""&&is_array($Xa)))echo'<a href="'.h($_."&db=".urlencode(DB).(support("scheme")?"&ns=":"")).'">'.h(DB).'</a> &raquo; ';if(is_array($Xa)){if($_GET["ns"]!="")echo'<a href="'.h(substr(ME,0,-1)).'">'.h($_GET["ns"]).'</a> &raquo; ';foreach($Xa
as$y=>$X){$Zb=(is_array($X)?$X[1]:h($X));if($Zb!="")echo"<a href='".h(ME."$y=").urlencode(is_array($X)?$X[0]:$X)."'>$Zb</a> &raquo; ";}}echo"$ji\n";}}echo"<h2>$li</h2>\n","<div id='ajaxstatus' class='jsonly hidden'></div>\n";restart_session();page_messages($n);$k=&get_session("dbs");if(DB!=""&&$k&&!in_array(DB,$k,true))$k=null;stop_session();define("PAGE_HEADER",1);}function
page_headers(){global$b;header("Content-Type: text/html; charset=utf-8");header("Cache-Control: no-cache");header("X-Frame-Options: deny");header("X-XSS-Protection: 0");header("X-Content-Type-Options: nosniff");header("Referrer-Policy: origin-when-cross-origin");foreach($b->csp()as$Jb){$yd=array();foreach($Jb
as$y=>$X)$yd[]="$y $X";header("Content-Security-Policy: ".implode("; ",$yd));}$b->headers();}function
csp(){return
array(array("script-src"=>"'self' 'unsafe-inline' 'nonce-".get_nonce()."' 'strict-dynamic'","connect-src"=>"'self'","frame-src"=>"https://www.adminer.org","object-src"=>"'none'","base-uri"=>"'none'","form-action"=>"'self'",),);}function
get_nonce(){static$ff;if(!$ff)$ff=base64_encode(rand_string());return$ff;}function
page_messages($n){$Mi=preg_replace('~^[^?]*~','',$_SERVER["REQUEST_URI"]);$Re=$_SESSION["messages"][$Mi];if($Re){echo"<div class='message'>".implode("</div>\n<div class='message'>",$Re)."</div>".script("messagesPrint();");unset($_SESSION["messages"][$Mi]);}if($n)echo"<div class='error'>$n</div>\n";}function
page_footer($Ve=""){global$b,$qi;echo'</div>

';if($Ve!="auth"){echo'<form action="" method="post">
<p class="logout">
<input type="submit" name="logout" value="Logout" id="logout">
<input type="hidden" name="token" value="',$qi,'">
</p>
</form>
';}echo'<div id="menu">
';$b->navigation($Ve);echo'</div>
',script("setupSubmitHighlight(document);");}function
int32($Ye){while($Ye>=2147483648)$Ye-=4294967296;while($Ye<=-2147483649)$Ye+=4294967296;return(int)$Ye;}function
long2str($W,$ej){$ah='';foreach($W
as$X)$ah.=pack('V',$X);if($ej)return
substr($ah,0,end($W));return$ah;}function
str2long($ah,$ej){$W=array_values(unpack('V*',str_pad($ah,4*ceil(strlen($ah)/4),"\0")));if($ej)$W[]=strlen($ah);return$W;}function
xxtea_mx($rj,$qj,$Nh,$fe){return
int32((($rj>>5&0x7FFFFFF)^$qj<<2)+(($qj>>3&0x1FFFFFFF)^$rj<<4))^int32(($Nh^$qj)+($fe^$rj));}function
encrypt_string($Ih,$y){if($Ih=="")return"";$y=array_values(unpack("V*",pack("H*",md5($y))));$W=str2long($Ih,true);$Ye=count($W)-1;$rj=$W[$Ye];$qj=$W[0];$wg=floor(6+52/($Ye+1));$Nh=0;while($wg-->0){$Nh=int32($Nh+0x9E3779B9);$nc=$Nh>>2&3;for($Nf=0;$Nf<$Ye;$Nf++){$qj=$W[$Nf+1];$Xe=xxtea_mx($rj,$qj,$Nh,$y[$Nf&3^$nc]);$rj=int32($W[$Nf]+$Xe);$W[$Nf]=$rj;}$qj=$W[0];$Xe=xxtea_mx($rj,$qj,$Nh,$y[$Nf&3^$nc]);$rj=int32($W[$Ye]+$Xe);$W[$Ye]=$rj;}return
long2str($W,false);}function
decrypt_string($Ih,$y){if($Ih=="")return"";if(!$y)return
false;$y=array_values(unpack("V*",pack("H*",md5($y))));$W=str2long($Ih,false);$Ye=count($W)-1;$rj=$W[$Ye];$qj=$W[0];$wg=floor(6+52/($Ye+1));$Nh=int32($wg*0x9E3779B9);while($Nh){$nc=$Nh>>2&3;for($Nf=$Ye;$Nf>0;$Nf--){$rj=$W[$Nf-1];$Xe=xxtea_mx($rj,$qj,$Nh,$y[$Nf&3^$nc]);$qj=int32($W[$Nf]-$Xe);$W[$Nf]=$qj;}$rj=$W[$Ye];$Xe=xxtea_mx($rj,$qj,$Nh,$y[$Nf&3^$nc]);$qj=int32($W[0]-$Xe);$W[0]=$qj;$Nh=int32($Nh-0x9E3779B9);}return
long2str($W,true);}$g='';$xd=$_SESSION["token"];if(!$xd)$_SESSION["token"]=rand(1,1e6);$qi=get_token();$dg=array();if($_COOKIE["adminer_permanent"]){foreach(explode(" ",$_COOKIE["adminer_permanent"])as$X){list($y)=explode(":",$X);$dg[$y]=$X;}}function
add_invalid_login(){global$b;$kd=file_open_lock(get_temp_dir()."/adminer.invalid");if(!$kd)return;$Yd=unserialize(stream_get_contents($kd));$gi=time();if($Yd){foreach($Yd
as$Zd=>$X){if($X[0]<$gi)unset($Yd[$Zd]);}}$Xd=&$Yd[$b->bruteForceKey()];if(!$Xd)$Xd=array($gi+30*60,0);$Xd[1]++;file_write_unlock($kd,serialize($Yd));}function
check_invalid_login(){global$b;$Yd=unserialize(@file_get_contents(get_temp_dir()."/adminer.invalid"));$Xd=$Yd[$b->bruteForceKey()];$ef=($Xd[1]>29?$Xd[0]-time():0);if($ef>0)auth_error(lang(array('Too many unsuccessful logins, try again in %d minute.','Too many unsuccessful logins, try again in %d minutes.'),ceil($ef/60)));}$Ka=$_POST["auth"];if($Ka){session_regenerate_id();$Zi=$Ka["driver"];$M=$Ka["server"];$V=$Ka["username"];$E=(string)$Ka["password"];$l=$Ka["db"];set_password($Zi,$M,$V,$E);$_SESSION["db"][$Zi][$M][$V][$l]=true;if($Ka["permanent"]){$y=base64_encode($Zi)."-".base64_encode($M)."-".base64_encode($V)."-".base64_encode($l);$pg=$b->permanentLogin(true);$dg[$y]="$y:".base64_encode($pg?encrypt_string($E,$pg):"");cookie("adminer_permanent",implode(" ",$dg));}if(count($_POST)==1||DRIVER!=$Zi||SERVER!=$M||$_GET["username"]!==$V||DB!=$l)redirect(auth_url($Zi,$M,$V,$l));}elseif($_POST["logout"]){if($xd&&!verify_token()){page_header('Logout','Invalid CSRF token. Send the form again.');page_footer("db");exit;}else{foreach(array("pwds","db","dbs","queries")as$y)set_session($y,null);unset_permanent();redirect(substr(preg_replace('~\b(username|db|ns)=[^&]*&~','',ME),0,-1),'Logout successful.'.' '.'Thanks for using Adminer, consider <a href="https://www.adminer.org/en/donation/">donating</a>.');}}elseif($dg&&!$_SESSION["pwds"]){session_regenerate_id();$pg=$b->permanentLogin();foreach($dg
as$y=>$X){list(,$jb)=explode(":",$X);list($Zi,$M,$V,$l)=array_map('base64_decode',explode("-",$y));set_password($Zi,$M,$V,decrypt_string(base64_decode($jb),$pg));$_SESSION["db"][$Zi][$M][$V][$l]=true;}}function
unset_permanent(){global$dg;foreach($dg
as$y=>$X){list($Zi,$M,$V,$l)=array_map('base64_decode',explode("-",$y));if($Zi==DRIVER&&$M==SERVER&&$V==$_GET["username"]&&$l==DB)unset($dg[$y]);}cookie("adminer_permanent",implode(" ",$dg));}function
auth_error($n){global$b,$xd;$ph=session_name();if(isset($_GET["username"])){header("HTTP/1.1 403 Forbidden");if(($_COOKIE[$ph]||$_GET[$ph])&&!$xd)$n='Session expired, please login again.';else{restart_session();add_invalid_login();$E=get_password();if($E!==null){if($E===false)$n.='<br>'.sprintf('Master password expired. <a href="https://www.adminer.org/en/extension/"%s>Implement</a> %s method to make it permanent.',target_blank(),'<code>permanentLogin()</code>');set_password(DRIVER,SERVER,$_GET["username"],null);}unset_permanent();}}if(!$_COOKIE[$ph]&&$_GET[$ph]&&ini_bool("session.use_only_cookies"))$n='Session support must be enabled.';$Qf=session_get_cookie_params();cookie("adminer_key",($_COOKIE["adminer_key"]?$_COOKIE["adminer_key"]:rand_string()),$Qf["lifetime"]);page_header('Login',$n,null);echo"<form action='' method='post'>\n","<div>";if(hidden_fields($_POST,array("auth")))echo"<p class='message'>".'The action will be performed after successful login with the same credentials.'."\n";echo"</div>\n";$b->loginForm();echo"</form>\n";page_footer("auth");exit;}if(isset($_GET["username"])&&!class_exists("Min_DB")){unset($_SESSION["pwds"][DRIVER]);unset_permanent();page_header('No extension',sprintf('None of the supported PHP extensions (%s) are available.',implode(", ",$jg)),false);page_footer("auth");exit;}stop_session(true);if(isset($_GET["username"])&&is_string(get_password())){list($Cd,$fg)=explode(":",SERVER,2);if(is_numeric($fg)&&($fg<1024||$fg>65535))auth_error('Connecting to privileged ports is not allowed.');check_invalid_login();$g=connect();$m=new
Min_Driver($g);}$_e=null;if(!is_object($g)||($_e=$b->login($_GET["username"],get_password()))!==true){$n=(is_string($g)?h($g):(is_string($_e)?$_e:'Invalid credentials.'));auth_error($n.(preg_match('~^ | $~',get_password())?'<br>'.'There is a space in the input password which might be the cause.':''));}if($Ka&&$_POST["token"])$_POST["token"]=$qi;$n='';if($_POST){if(!verify_token()){$Sd="max_input_vars";$Le=ini_get($Sd);if(extension_loaded("suhosin")){foreach(array("suhosin.request.max_vars","suhosin.post.max_vars")as$y){$X=ini_get($y);if($X&&(!$Le||$X<$Le)){$Sd=$y;$Le=$X;}}}$n=(!$_POST["token"]&&$Le?sprintf('Maximum number of allowed fields exceeded. Please increase %s.',"'$Sd'"):'Invalid CSRF token. Send the form again.'.' '.'If you did not send this request from Adminer then close this page.');}}elseif($_SERVER["REQUEST_METHOD"]=="POST"){$n=sprintf('Too big POST data. Reduce the data or increase the %s configuration directive.',"'post_max_size'");if(isset($_GET["sql"]))$n.=' '.'You can upload a big SQL file via FTP and import it from server.';}function
select($G,$h=null,$Ef=array(),$z=0){global$x;$ye=array();$w=array();$f=array();$Ua=array();$U=array();$H=array();odd('');for($s=0;(!$z||$s<$z)&&($I=$G->fetch_row());$s++){if(!$s){echo"<div class='scrollable'>\n","<table cellspacing='0' class='nowrap'>\n","<thead><tr>";for($ee=0;$ee<count($I);$ee++){$o=$G->fetch_field();$B=$o->name;$Df=$o->orgtable;$Cf=$o->orgname;$H[$o->table]=$Df;if($Ef&&$x=="sql")$ye[$ee]=($B=="table"?"table=":($B=="possible_keys"?"indexes=":null));elseif($Df!=""){if(!isset($w[$Df])){$w[$Df]=array();foreach(indexes($Df,$h)as$v){if($v["type"]=="PRIMARY"){$w[$Df]=array_flip($v["columns"]);break;}}$f[$Df]=$w[$Df];}if(isset($f[$Df][$Cf])){unset($f[$Df][$Cf]);$w[$Df][$Cf]=$ee;$ye[$ee]=$Df;}}if($o->charsetnr==63)$Ua[$ee]=true;$U[$ee]=$o->type;echo"<th".($Df!=""||$o->name!=$Cf?" title='".h(($Df!=""?"$Df.":"").$Cf)."'":"").">".h($B).($Ef?doc_link(array('sql'=>"explain-output.html#explain_".strtolower($B),'mariadb'=>"explain/#the-columns-in-explain-select",)):"");}echo"</thead>\n";}echo"<tr".odd().">";foreach($I
as$y=>$X){if($X===null)$X="<i>NULL</i>";elseif($Ua[$y]&&!is_utf8($X))$X="<i>".lang(array('%d byte','%d bytes'),strlen($X))."</i>";else{$X=h($X);if($U[$y]==254)$X="<code>$X</code>";}if(isset($ye[$y])&&!$f[$ye[$y]]){if($Ef&&$x=="sql"){$Q=$I[array_search("table=",$ye)];$_=$ye[$y].urlencode($Ef[$Q]!=""?$Ef[$Q]:$Q);}else{$_="edit=".urlencode($ye[$y]);foreach($w[$ye[$y]]as$nb=>$ee)$_.="&where".urlencode("[".bracket_escape($nb)."]")."=".urlencode($I[$ee]);}$X="<a href='".h(ME.$_)."'>$X</a>";}echo"<td>$X";}}echo($s?"</table>\n</div>":"<p class='message'>".'No rows.')."\n";return$H;}function
referencable_primary($jh){$H=array();foreach(table_status('',true)as$Rh=>$Q){if($Rh!=$jh&&fk_support($Q)){foreach(fields($Rh)as$o){if($o["primary"]){if($H[$Rh]){unset($H[$Rh]);break;}$H[$Rh]=$o;}}}}return$H;}function
adminer_settings(){parse_str($_COOKIE["adminer_settings"],$rh);return$rh;}function
adminer_setting($y){$rh=adminer_settings();return$rh[$y];}function
set_adminer_settings($rh){return
cookie("adminer_settings",http_build_query($rh+adminer_settings()));}function
textarea($B,$Y,$J=10,$rb=80){global$x;echo"<textarea name='$B' rows='$J' cols='$rb' class='sqlarea jush-$x' spellcheck='false' wrap='off'>";if(is_array($Y)){foreach($Y
as$X)echo
h($X[0])."\n\n\n";}else
echo
h($Y);echo"</textarea>";}function
edit_type($y,$o,$pb,$gd=array(),$Nc=array()){global$Jh,$U,$Ki,$sf;$T=$o["type"];echo'<td><select name="',h($y),'[type]" class="type" aria-labelledby="label-type">';if($T&&!isset($U[$T])&&!isset($gd[$T])&&!in_array($T,$Nc))$Nc[]=$T;if($gd)$Jh['Foreign keys']=$gd;echo
optionlist(array_merge($Nc,$Jh),$T),'</select><td><input name="',h($y),'[length]" value="',h($o["length"]),'" size="3"',(!$o["length"]&&preg_match('~var(char|binary)$~',$T)?" class='required'":"");echo' aria-labelledby="label-length"><td class="options">',"<select name='".h($y)."[collation]'".(preg_match('~(char|text|enum|set)$~',$T)?"":" class='hidden'").'><option value="">('.'collation'.')'.optionlist($pb,$o["collation"]).'</select>',($Ki?"<select name='".h($y)."[unsigned]'".(!$T||preg_match(number_type(),$T)?"":" class='hidden'").'><option>'.optionlist($Ki,$o["unsigned"]).'</select>':''),(isset($o['on_update'])?"<select name='".h($y)."[on_update]'".(preg_match('~timestamp|datetime~',$T)?"":" class='hidden'").'>'.optionlist(array(""=>"(".'ON UPDATE'.")","CURRENT_TIMESTAMP"),(preg_match('~^CURRENT_TIMESTAMP~i',$o["on_update"])?"CURRENT_TIMESTAMP":$o["on_update"])).'</select>':''),($gd?"<select name='".h($y)."[on_delete]'".(preg_match("~`~",$T)?"":" class='hidden'")."><option value=''>(".'ON DELETE'.")".optionlist(explode("|",$sf),$o["on_delete"])."</select> ":" ");}function
process_length($ve){global$yc;return(preg_match("~^\\s*\\(?\\s*$yc(?:\\s*,\\s*$yc)*+\\s*\\)?\\s*\$~",$ve)&&preg_match_all("~$yc~",$ve,$Fe)?"(".implode(",",$Fe[0]).")":preg_replace('~^[0-9].*~','(\0)',preg_replace('~[^-0-9,+()[\]]~','',$ve)));}function
process_type($o,$ob="COLLATE"){global$Ki;return" $o[type]".process_length($o["length"]).(preg_match(number_type(),$o["type"])&&in_array($o["unsigned"],$Ki)?" $o[unsigned]":"").(preg_match('~char|text|enum|set~',$o["type"])&&$o["collation"]?" $ob ".q($o["collation"]):"");}function
process_field($o,$Ci){return
array(idf_escape(trim($o["field"])),process_type($Ci),($o["null"]?" NULL":" NOT NULL"),default_value($o),(preg_match('~timestamp|datetime~',$o["type"])&&$o["on_update"]?" ON UPDATE $o[on_update]":""),(support("comment")&&$o["comment"]!=""?" COMMENT ".q($o["comment"]):""),($o["auto_increment"]?auto_increment():null),);}function
default_value($o){$Ub=$o["default"];return($Ub===null?"":" DEFAULT ".(preg_match('~char|binary|text|enum|set~',$o["type"])||preg_match('~^(?![a-z])~i',$Ub)?q($Ub):$Ub));}function
type_class($T){foreach(array('char'=>'text','date'=>'time|year','binary'=>'blob','enum'=>'set',)as$y=>$X){if(preg_match("~$y|$X~",$T))return" class='$y'";}}function
edit_fields($p,$pb,$T="TABLE",$gd=array()){global$Td;$p=array_values($p);$Vb=(($_POST?$_POST["defaults"]:adminer_setting("defaults"))?"":" class='hidden'");$vb=(($_POST?$_POST["comments"]:adminer_setting("comments"))?"":" class='hidden'");echo'<thead><tr>
';if($T=="PROCEDURE"){echo'<td>';}echo'<th id="label-name">',($T=="TABLE"?'Column name':'Parameter name'),'<td id="label-type">Type<textarea id="enum-edit" rows="4" cols="12" wrap="off" style="display: none;"></textarea>',script("qs('#enum-edit').onblur = editingLengthBlur;"),'<td id="label-length">Length
<td>','Options';if($T=="TABLE"){echo'<td id="label-null">NULL
<td><input type="radio" name="auto_increment_col" value=""><acronym id="label-ai" title="Auto Increment">AI</acronym>',doc_link(array('sql'=>"example-auto-increment.html",'mariadb'=>"auto_increment/",'sqlite'=>"autoinc.html",'pgsql'=>"datatype.html#DATATYPE-SERIAL",'mssql'=>"ms186775.aspx",)),'<td id="label-default"',$Vb,'>Default value
',(support("comment")?"<td id='label-comment'$vb>".'Comment':"");}echo'<td>',"<input type='image' class='icon' name='add[".(support("move_col")?0:count($p))."]' src='".h(preg_replace("~\\?.*~","",ME)."?file=plus.gif&version=4.7.7")."' alt='+' title='".'Add next'."'>".script("row_count = ".count($p).";"),'</thead>
<tbody>
',script("mixin(qsl('tbody'), {onclick: editingClick, onkeydown: editingKeydown, oninput: editingInput});");foreach($p
as$s=>$o){$s++;$Ff=$o[($_POST?"orig":"field")];$dc=(isset($_POST["add"][$s-1])||(isset($o["field"])&&!$_POST["drop_col"][$s]))&&(support("drop_col")||$Ff=="");echo'<tr',($dc?"":" style='display: none;'"),'>
',($T=="PROCEDURE"?"<td>".html_select("fields[$s][inout]",explode("|",$Td),$o["inout"]):""),'<th>';if($dc){echo'<input name="fields[',$s,'][field]" value="',h($o["field"]),'" data-maxlength="64" autocapitalize="off" aria-labelledby="label-name">';}echo'<input type="hidden" name="fields[',$s,'][orig]" value="',h($Ff),'">';edit_type("fields[$s]",$o,$pb,$gd);if($T=="TABLE"){echo'<td>',checkbox("fields[$s][null]",1,$o["null"],"","","block","label-null"),'<td><label class="block"><input type="radio" name="auto_increment_col" value="',$s,'"';if($o["auto_increment"]){echo' checked';}echo' aria-labelledby="label-ai"></label><td',$Vb,'>',checkbox("fields[$s][has_default]",1,$o["has_default"],"","","","label-default"),'<input name="fields[',$s,'][default]" value="',h($o["default"]),'" aria-labelledby="label-default">',(support("comment")?"<td$vb><input name='fields[$s][comment]' value='".h($o["comment"])."' data-maxlength='".(min_version(5.5)?1024:255)."' aria-labelledby='label-comment'>":"");}echo"<td>",(support("move_col")?"<input type='image' class='icon' name='add[$s]' src='".h(preg_replace("~\\?.*~","",ME)."?file=plus.gif&version=4.7.7")."' alt='+' title='".'Add next'."'> "."<input type='image' class='icon' name='up[$s]' src='".h(preg_replace("~\\?.*~","",ME)."?file=up.gif&version=4.7.7")."' alt='â' title='".'Move up'."'> "."<input type='image' class='icon' name='down[$s]' src='".h(preg_replace("~\\?.*~","",ME)."?file=down.gif&version=4.7.7")."' alt='â' title='".'Move down'."'> ":""),($Ff==""||support("drop_col")?"<input type='image' class='icon' name='drop_col[$s]' src='".h(preg_replace("~\\?.*~","",ME)."?file=cross.gif&version=4.7.7")."' alt='x' title='".'Remove'."'>":"");}}function
process_fields(&$p){$C=0;if($_POST["up"]){$pe=0;foreach($p
as$y=>$o){if(key($_POST["up"])==$y){unset($p[$y]);array_splice($p,$pe,0,array($o));break;}if(isset($o["field"]))$pe=$C;$C++;}}elseif($_POST["down"]){$id=false;foreach($p
as$y=>$o){if(isset($o["field"])&&$id){unset($p[key($_POST["down"])]);array_splice($p,$C,0,array($id));break;}if(key($_POST["down"])==$y)$id=$o;$C++;}}elseif($_POST["add"]){$p=array_values($p);array_splice($p,key($_POST["add"]),0,array(array()));}elseif(!$_POST["drop_col"])return
false;return
true;}function
normalize_enum($A){return"'".str_replace("'","''",addcslashes(stripcslashes(str_replace($A[0][0].$A[0][0],$A[0][0],substr($A[0],1,-1))),'\\'))."'";}function
grant($nd,$rg,$f,$rf){if(!$rg)return
true;if($rg==array("ALL PRIVILEGES","GRANT OPTION"))return($nd=="GRANT"?queries("$nd ALL PRIVILEGES$rf WITH GRANT OPTION"):queries("$nd ALL PRIVILEGES$rf")&&queries("$nd GRANT OPTION$rf"));return
queries("$nd ".preg_replace('~(GRANT OPTION)\([^)]*\)~','\1',implode("$f, ",$rg).$f).$rf);}function
drop_create($hc,$i,$ic,$di,$kc,$ze,$Qe,$Oe,$Pe,$of,$bf){if($_POST["drop"])query_redirect($hc,$ze,$Qe);elseif($of=="")query_redirect($i,$ze,$Pe);elseif($of!=$bf){$Hb=queries($i);queries_redirect($ze,$Oe,$Hb&&queries($hc));if($Hb)queries($ic);}else
queries_redirect($ze,$Oe,queries($di)&&queries($kc)&&queries($hc)&&queries($i));}function
create_trigger($rf,$I){global$x;$ii=" $I[Timing] $I[Event]".($I["Event"]=="UPDATE OF"?" ".idf_escape($I["Of"]):"");return"CREATE TRIGGER ".idf_escape($I["Trigger"]).($x=="mssql"?$rf.$ii:$ii.$rf).rtrim(" $I[Type]\n$I[Statement]",";").";";}function
create_routine($Wg,$I){global$Td,$x;$N=array();$p=(array)$I["fields"];ksort($p);foreach($p
as$o){if($o["field"]!="")$N[]=(preg_match("~^($Td)\$~",$o["inout"])?"$o[inout] ":"").idf_escape($o["field"]).process_type($o,"CHARACTER SET");}$Wb=rtrim("\n$I[definition]",";");return"CREATE $Wg ".idf_escape(trim($I["name"]))." (".implode(", ",$N).")".(isset($_GET["function"])?" RETURNS".process_type($I["returns"],"CHARACTER SET"):"").($I["language"]?" LANGUAGE $I[language]":"").($x=="pgsql"?" AS ".q($Wb):"$Wb;");}function
remove_definer($F){return
preg_replace('~^([A-Z =]+) DEFINER=`'.preg_replace('~@(.*)~','`@`(%|\1)',logged_user()).'`~','\1',$F);}function
format_foreign_key($q){global$sf;$l=$q["db"];$gf=$q["ns"];return" FOREIGN KEY (".implode(", ",array_map('idf_escape',$q["source"])).") REFERENCES ".($l!=""&&$l!=$_GET["db"]?idf_escape($l).".":"").($gf!=""&&$gf!=$_GET["ns"]?idf_escape($gf).".":"").table($q["table"])." (".implode(", ",array_map('idf_escape',$q["target"])).")".(preg_match("~^($sf)\$~",$q["on_delete"])?" ON DELETE $q[on_delete]":"").(preg_match("~^($sf)\$~",$q["on_update"])?" ON UPDATE $q[on_update]":"");}function
tar_file($Wc,$ni){$H=pack("a100a8a8a8a12a12",$Wc,644,0,0,decoct($ni->size),decoct(time()));$hb=8*32;for($s=0;$s<strlen($H);$s++)$hb+=ord($H[$s]);$H.=sprintf("%06o",$hb)."\0 ";echo$H,str_repeat("\0",512-strlen($H));$ni->send();echo
str_repeat("\0",511-($ni->size+511)%512);}function
ini_bytes($Sd){$X=ini_get($Sd);switch(strtolower(substr($X,-1))){case'g':$X*=1024;case'm':$X*=1024;case'k':$X*=1024;}return$X;}function
doc_link($bg,$ei="<sup>?</sup>"){global$x,$g;$nh=$g->server_info;$aj=preg_replace('~^(\d\.?\d).*~s','\1',$nh);$Pi=array('sql'=>"https://dev.mysql.com/doc/refman/$aj/en/",'sqlite'=>"https://www.sqlite.org/",'pgsql'=>"https://www.postgresql.org/docs/$aj/",'mssql'=>"https://msdn.microsoft.com/library/",'oracle'=>"https://www.oracle.com/pls/topic/lookup?ctx=db".preg_replace('~^.* (\d+)\.(\d+)\.\d+\.\d+\.\d+.*~s','\1\2',$nh)."&id=",);if(preg_match('~MariaDB~',$nh)){$Pi['sql']="https://mariadb.com/kb/en/library/";$bg['sql']=(isset($bg['mariadb'])?$bg['mariadb']:str_replace(".html","/",$bg['sql']));}return($bg[$x]?"<a href='$Pi[$x]$bg[$x]'".target_blank().">$ei</a>":"");}function
ob_gzencode($P){return
gzencode($P);}function
db_size($l){global$g;if(!$g->select_db($l))return"?";$H=0;foreach(table_status()as$R)$H+=$R["Data_length"]+$R["Index_length"];return
format_number($H);}function
set_utf8mb4($i){global$g;static$N=false;if(!$N&&preg_match('~\butf8mb4~i',$i)){$N=true;echo"SET NAMES ".charset($g).";\n\n";}}function
connect_error(){global$b,$g,$qi,$n,$gc;if(DB!=""){header("HTTP/1.1 404 Not Found");page_header('Database'.": ".h(DB),'Invalid database.',true);}else{if($_POST["db"]&&!$n)queries_redirect(substr(ME,0,-1),'Databases have been dropped.',drop_databases($_POST["db"]));page_header('Select database',$n,false);echo"<p class='links'>\n";foreach(array('database'=>'Create database','privileges'=>'Privileges','processlist'=>'Process list','variables'=>'Variables','status'=>'Status',)as$y=>$X){if(support($y))echo"<a href='".h(ME)."$y='>$X</a>\n";}echo"<p>".sprintf('%s version: %s through PHP extension %s',$gc[DRIVER],"<b>".h($g->server_info)."</b>","<b>$g->extension</b>")."\n","<p>".sprintf('Logged as: %s',"<b>".h(logged_user())."</b>")."\n";$k=$b->databases();if($k){$dh=support("scheme");$pb=collations();echo"<form action='' method='post'>\n","<table cellspacing='0' class='checkable'>\n",script("mixin(qsl('table'), {onclick: tableClick, ondblclick: partialArg(tableClick, true)});"),"<thead><tr>".(support("database")?"<td>":"")."<th>".'Database'." - <a href='".h(ME)."refresh=1'>".'Refresh'."</a>"."<td>".'Collation'."<td>".'Tables'."<td>".'Size'." - <a href='".h(ME)."dbsize=1'>".'Compute'."</a>".script("qsl('a').onclick = partial(ajaxSetHtml, '".js_escape(ME)."script=connect');","")."</thead>\n";$k=($_GET["dbsize"]?count_tables($k):array_flip($k));foreach($k
as$l=>$S){$Vg=h(ME)."db=".urlencode($l);$t=h("Db-".$l);echo"<tr".odd().">".(support("database")?"<td>".checkbox("db[]",$l,in_array($l,(array)$_POST["db"]),"","","",$t):""),"<th><a href='$Vg' id='$t'>".h($l)."</a>";$d=h(db_collation($l,$pb));echo"<td>".(support("database")?"<a href='$Vg".($dh?"&amp;ns=":"")."&amp;database=' title='".'Alter database'."'>$d</a>":$d),"<td align='right'><a href='$Vg&amp;schema=' id='tables-".h($l)."' title='".'Database schema'."'>".($_GET["dbsize"]?$S:"?")."</a>","<td align='right' id='size-".h($l)."'>".($_GET["dbsize"]?db_size($l):"?"),"\n";}echo"</table>\n",(support("database")?"<div class='footer'><div>\n"."<fieldset><legend>".'Selected'." <span id='selected'></span></legend><div>\n"."<input type='hidden' name='all' value=''>".script("qsl('input').onclick = function () { selectCount('selected', formChecked(this, /^db/)); };")."<input type='submit' name='drop' value='".'Drop'."'>".confirm()."\n"."</div></fieldset>\n"."</div></div>\n":""),"<input type='hidden' name='token' value='$qi'>\n","</form>\n",script("tableCheck();");}}page_footer("db");}if(isset($_GET["status"]))$_GET["variables"]=$_GET["status"];if(isset($_GET["import"]))$_GET["sql"]=$_GET["import"];if(!(DB!=""?$g->select_db(DB):isset($_GET["sql"])||isset($_GET["dump"])||isset($_GET["database"])||isset($_GET["processlist"])||isset($_GET["privileges"])||isset($_GET["user"])||isset($_GET["variables"])||$_GET["script"]=="connect"||$_GET["script"]=="kill")){if(DB!=""||$_GET["refresh"]){restart_session();set_session("dbs",null);}connect_error();exit;}if(support("scheme")&&DB!=""&&$_GET["ns"]!==""){if(!isset($_GET["ns"]))redirect(preg_replace('~ns=[^&]*&~','',ME)."ns=".get_schema());if(!set_schema($_GET["ns"])){header("HTTP/1.1 404 Not Found");page_header('Schema'.": ".h($_GET["ns"]),'Invalid schema.',true);page_footer("ns");exit;}}$sf="RESTRICT|NO ACTION|CASCADE|SET NULL|SET DEFAULT";class
TmpFile{var$handler;var$size;function
__construct(){$this->handler=tmpfile();}function
write($Bb){$this->size+=strlen($Bb);fwrite($this->handler,$Bb);}function
send(){fseek($this->handler,0);fpassthru($this->handler);fclose($this->handler);}}$yc="'(?:''|[^'\\\\]|\\\\.)*'";$Td="IN|OUT|INOUT";if(isset($_GET["select"])&&($_POST["edit"]||$_POST["clone"])&&!$_POST["save"])$_GET["edit"]=$_GET["select"];if(isset($_GET["callf"]))$_GET["call"]=$_GET["callf"];if(isset($_GET["function"]))$_GET["procedure"]=$_GET["function"];if(isset($_GET["download"])){$a=$_GET["download"];$p=fields($a);header("Content-Type: application/octet-stream");header("Content-Disposition: attachment; filename=".friendly_url("$a-".implode("_",$_GET["where"])).".".friendly_url($_GET["field"]));$K=array(idf_escape($_GET["field"]));$G=$m->select($a,$K,array(where($_GET,$p)),$K);$I=($G?$G->fetch_row():array());echo$m->value($I[0],$p[$_GET["field"]]);exit;}elseif(isset($_GET["table"])){$a=$_GET["table"];$p=fields($a);if(!$p)$n=error();$R=table_status1($a,true);$B=$b->tableName($R);page_header(($p&&is_view($R)?$R['Engine']=='materialized view'?'Materialized view':'View':'Table').": ".($B!=""?$B:h($a)),$n);$b->selectLinks($R);$ub=$R["Comment"];if($ub!="")echo"<p class='nowrap'>".'Comment'.": ".h($ub)."\n";if($p)$b->tableStructurePrint($p);if(!is_view($R)){if(support("indexes")){echo"<h3 id='indexes'>".'Indexes'."</h3>\n";$w=indexes($a);if($w)$b->tableIndexesPrint($w);echo'<p class="links"><a href="'.h(ME).'indexes='.urlencode($a).'">'.'Alter indexes'."</a>\n";}if(fk_support($R)){echo"<h3 id='foreign-keys'>".'Foreign keys'."</h3>\n";$gd=foreign_keys($a);if($gd){echo"<table cellspacing='0'>\n","<thead><tr><th>".'Source'."<td>".'Target'."<td>".'ON DELETE'."<td>".'ON UPDATE'."<td></thead>\n";foreach($gd
as$B=>$q){echo"<tr title='".h($B)."'>","<th><i>".implode("</i>, <i>",array_map('h',$q["source"]))."</i>","<td><a href='".h($q["db"]!=""?preg_replace('~db=[^&]*~',"db=".urlencode($q["db"]),ME):($q["ns"]!=""?preg_replace('~ns=[^&]*~',"ns=".urlencode($q["ns"]),ME):ME))."table=".urlencode($q["table"])."'>".($q["db"]!=""?"<b>".h($q["db"])."</b>.":"").($q["ns"]!=""?"<b>".h($q["ns"])."</b>.":"").h($q["table"])."</a>","(<i>".implode("</i>, <i>",array_map('h',$q["target"]))."</i>)","<td>".h($q["on_delete"])."\n","<td>".h($q["on_update"])."\n",'<td><a href="'.h(ME.'foreign='.urlencode($a).'&name='.urlencode($B)).'">'.'Alter'.'</a>';}echo"</table>\n";}echo'<p class="links"><a href="'.h(ME).'foreign='.urlencode($a).'">'.'Add foreign key'."</a>\n";}}if(support(is_view($R)?"view_trigger":"trigger")){echo"<h3 id='triggers'>".'Triggers'."</h3>\n";$Bi=triggers($a);if($Bi){echo"<table cellspacing='0'>\n";foreach($Bi
as$y=>$X)echo"<tr valign='top'><td>".h($X[0])."<td>".h($X[1])."<th>".h($y)."<td><a href='".h(ME.'trigger='.urlencode($a).'&name='.urlencode($y))."'>".'Alter'."</a>\n";echo"</table>\n";}echo'<p class="links"><a href="'.h(ME).'trigger='.urlencode($a).'">'.'Add trigger'."</a>\n";}}elseif(isset($_GET["schema"])){page_header('Database schema',"",array(),h(DB.($_GET["ns"]?".$_GET[ns]":"")));$Th=array();$Uh=array();$ea=($_GET["schema"]?$_GET["schema"]:$_COOKIE["adminer_schema-".str_replace(".","_",DB)]);preg_match_all('~([^:]+):([-0-9.]+)x([-0-9.]+)(_|$)~',$ea,$Fe,PREG_SET_ORDER);foreach($Fe
as$s=>$A){$Th[$A[1]]=array($A[2],$A[3]);$Uh[]="\n\t'".js_escape($A[1])."': [ $A[2], $A[3] ]";}$ri=0;$Ra=-1;$ch=array();$Hg=array();$te=array();foreach(table_status('',true)as$Q=>$R){if(is_view($R))continue;$gg=0;$ch[$Q]["fields"]=array();foreach(fields($Q)as$B=>$o){$gg+=1.25;$o["pos"]=$gg;$ch[$Q]["fields"][$B]=$o;}$ch[$Q]["pos"]=($Th[$Q]?$Th[$Q]:array($ri,0));foreach($b->foreignKeys($Q)as$X){if(!$X["db"]){$re=$Ra;if($Th[$Q][1]||$Th[$X["table"]][1])$re=min(floatval($Th[$Q][1]),floatval($Th[$X["table"]][1]))-1;else$Ra-=.1;while($te[(string)$re])$re-=.0001;$ch[$Q]["references"][$X["table"]][(string)$re]=array($X["source"],$X["target"]);$Hg[$X["table"]][$Q][(string)$re]=$X["target"];$te[(string)$re]=true;}}$ri=max($ri,$ch[$Q]["pos"][0]+2.5+$gg);}echo'<div id="schema" style="height: ',$ri,'em;">
<script',nonce(),'>
qs(\'#schema\').onselectstart = function () { return false; };
var tablePos = {',implode(",",$Uh)."\n",'};
var em = qs(\'#schema\').offsetHeight / ',$ri,';
document.onmousemove = schemaMousemove;
document.onmouseup = partialArg(schemaMouseup, \'',js_escape(DB),'\');
</script>
';foreach($ch
as$B=>$Q){echo"<div class='table' style='top: ".$Q["pos"][0]."em; left: ".$Q["pos"][1]."em;'>",'<a href="'.h(ME).'table='.urlencode($B).'"><b>'.h($B)."</b></a>",script("qsl('div').onmousedown = schemaMousedown;");foreach($Q["fields"]as$o){$X='<span'.type_class($o["type"]).' title="'.h($o["full_type"].($o["null"]?" NULL":'')).'">'.h($o["field"]).'</span>';echo"<br>".($o["primary"]?"<i>$X</i>":$X);}foreach((array)$Q["references"]as$ai=>$Ig){foreach($Ig
as$re=>$Eg){$se=$re-$Th[$B][1];$s=0;foreach($Eg[0]as$yh)echo"\n<div class='references' title='".h($ai)."' id='refs$re-".($s++)."' style='left: $se"."em; top: ".$Q["fields"][$yh]["pos"]."em; padding-top: .5em;'><div style='border-top: 1px solid Gray; width: ".(-$se)."em;'></div></div>";}}foreach((array)$Hg[$B]as$ai=>$Ig){foreach($Ig
as$re=>$f){$se=$re-$Th[$B][1];$s=0;foreach($f
as$Zh)echo"\n<div class='references' title='".h($ai)."' id='refd$re-".($s++)."' style='left: $se"."em; top: ".$Q["fields"][$Zh]["pos"]."em; height: 1.25em; background: url(".h(preg_replace("~\\?.*~","",ME)."?file=arrow.gif) no-repeat right center;&version=4.7.7")."'><div style='height: .5em; border-bottom: 1px solid Gray; width: ".(-$se)."em;'></div></div>";}}echo"\n</div>\n";}foreach($ch
as$B=>$Q){foreach((array)$Q["references"]as$ai=>$Ig){foreach($Ig
as$re=>$Eg){$Ue=$ri;$Je=-10;foreach($Eg[0]as$y=>$yh){$hg=$Q["pos"][0]+$Q["fields"][$yh]["pos"];$ig=$ch[$ai]["pos"][0]+$ch[$ai]["fields"][$Eg[1][$y]]["pos"];$Ue=min($Ue,$hg,$ig);$Je=max($Je,$hg,$ig);}echo"<div class='references' id='refl$re' style='left: $re"."em; top: $Ue"."em; padding: .5em 0;'><div style='border-right: 1px solid Gray; margin-top: 1px; height: ".($Je-$Ue)."em;'></div></div>\n";}}}echo'</div>
<p class="links"><a href="',h(ME."schema=".urlencode($ea)),'" id="schema-link">Permanent link</a>
';}elseif(isset($_GET["dump"])){$a=$_GET["dump"];if($_POST&&!$n){$Eb="";foreach(array("output","format","db_style","routines","events","table_style","auto_increment","triggers","data_style")as$y)$Eb.="&$y=".urlencode($_POST[$y]);cookie("adminer_export",substr($Eb,1));$S=array_flip((array)$_POST["tables"])+array_flip((array)$_POST["data"]);$Kc=dump_headers((count($S)==1?key($S):DB),(DB==""||count($S)>1));$be=preg_match('~sql~',$_POST["format"]);if($be){echo"-- Adminer $ia ".$gc[DRIVER]." dump\n\n";if($x=="sql"){echo"SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
".($_POST["data_style"]?"SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';
":"")."
";$g->query("SET time_zone = '+00:00';");}}$Kh=$_POST["db_style"];$k=array(DB);if(DB==""){$k=$_POST["databases"];if(is_string($k))$k=explode("\n",rtrim(str_replace("\r","",$k),"\n"));}foreach((array)$k
as$l){$b->dumpDatabase($l);if($g->select_db($l)){if($be&&preg_match('~CREATE~',$Kh)&&($i=$g->result("SHOW CREATE DATABASE ".idf_escape($l),1))){set_utf8mb4($i);if($Kh=="DROP+CREATE")echo"DROP DATABASE IF EXISTS ".idf_escape($l).";\n";echo"$i;\n";}if($be){if($Kh)echo
use_sql($l).";\n\n";$Lf="";if($_POST["routines"]){foreach(array("FUNCTION","PROCEDURE")as$Wg){foreach(get_rows("SHOW $Wg STATUS WHERE Db = ".q($l),null,"-- ")as$I){$i=remove_definer($g->result("SHOW CREATE $Wg ".idf_escape($I["Name"]),2));set_utf8mb4($i);$Lf.=($Kh!='DROP+CREATE'?"DROP $Wg IF EXISTS ".idf_escape($I["Name"]).";;\n":"")."$i;;\n\n";}}}if($_POST["events"]){foreach(get_rows("SHOW EVENTS",null,"-- ")as$I){$i=remove_definer($g->result("SHOW CREATE EVENT ".idf_escape($I["Name"]),3));set_utf8mb4($i);$Lf.=($Kh!='DROP+CREATE'?"DROP EVENT IF EXISTS ".idf_escape($I["Name"]).";;\n":"")."$i;;\n\n";}}if($Lf)echo"DELIMITER ;;\n\n$Lf"."DELIMITER ;\n\n";}if($_POST["table_style"]||$_POST["data_style"]){$cj=array();foreach(table_status('',true)as$B=>$R){$Q=(DB==""||in_array($B,(array)$_POST["tables"]));$Nb=(DB==""||in_array($B,(array)$_POST["data"]));if($Q||$Nb){if($Kc=="tar"){$ni=new
TmpFile;ob_start(array($ni,'write'),1e5);}$b->dumpTable($B,($Q?$_POST["table_style"]:""),(is_view($R)?2:0));if(is_view($R))$cj[]=$B;elseif($Nb){$p=fields($B);$b->dumpData($B,$_POST["data_style"],"SELECT *".convert_fields($p,$p)." FROM ".table($B));}if($be&&$_POST["triggers"]&&$Q&&($Bi=trigger_sql($B)))echo"\nDELIMITER ;;\n$Bi\nDELIMITER ;\n";if($Kc=="tar"){ob_end_flush();tar_file((DB!=""?"":"$l/")."$B.csv",$ni);}elseif($be)echo"\n";}}foreach($cj
as$bj)$b->dumpTable($bj,$_POST["table_style"],1);if($Kc=="tar")echo
pack("x512");}}}if($be)echo"-- ".$g->result("SELECT NOW()")."\n";exit;}page_header('Export',$n,($_GET["export"]!=""?array("table"=>$_GET["export"]):array()),h(DB));echo'
<form action="" method="post">
<table cellspacing="0" class="layout">
';$Rb=array('','USE','DROP+CREATE','CREATE');$Vh=array('','DROP+CREATE','CREATE');$Ob=array('','TRUNCATE+INSERT','INSERT');if($x=="sql")$Ob[]='INSERT+UPDATE';parse_str($_COOKIE["adminer_export"],$I);if(!$I)$I=array("output"=>"text","format"=>"sql","db_style"=>(DB!=""?"":"CREATE"),"table_style"=>"DROP+CREATE","data_style"=>"INSERT");if(!isset($I["events"])){$I["routines"]=$I["events"]=($_GET["dump"]=="");$I["triggers"]=$I["table_style"];}echo"<tr><th>".'Output'."<td>".html_select("output",$b->dumpOutput(),$I["output"],0)."\n";echo"<tr><th>".'Format'."<td>".html_select("format",$b->dumpFormat(),$I["format"],0)."\n";echo($x=="sqlite"?"":"<tr><th>".'Database'."<td>".html_select('db_style',$Rb,$I["db_style"]).(support("routine")?checkbox("routines",1,$I["routines"],'Routines'):"").(support("event")?checkbox("events",1,$I["events"],'Events'):"")),"<tr><th>".'Tables'."<td>".html_select('table_style',$Vh,$I["table_style"]).checkbox("auto_increment",1,$I["auto_increment"],'Auto Increment').(support("trigger")?checkbox("triggers",1,$I["triggers"],'Triggers'):""),"<tr><th>".'Data'."<td>".html_select('data_style',$Ob,$I["data_style"]),'</table>
<p><input type="submit" value="Export">
<input type="hidden" name="token" value="',$qi,'">

<table cellspacing="0">
',script("qsl('table').onclick = dumpClick;");$lg=array();if(DB!=""){$fb=($a!=""?"":" checked");echo"<thead><tr>","<th style='text-align: left;'><label class='block'><input type='checkbox' id='check-tables'$fb>".'Tables'."</label>".script("qs('#check-tables').onclick = partial(formCheck, /^tables\\[/);",""),"<th style='text-align: right;'><label class='block'>".'Data'."<input type='checkbox' id='check-data'$fb></label>".script("qs('#check-data').onclick = partial(formCheck, /^data\\[/);",""),"</thead>\n";$cj="";$Wh=tables_list();foreach($Wh
as$B=>$T){$kg=preg_replace('~_.*~','',$B);$fb=($a==""||$a==(substr($a,-1)=="%"?"$kg%":$B));$og="<tr><td>".checkbox("tables[]",$B,$fb,$B,"","block");if($T!==null&&!preg_match('~table~i',$T))$cj.="$og\n";else
echo"$og<td align='right'><label class='block'><span id='Rows-".h($B)."'></span>".checkbox("data[]",$B,$fb)."</label>\n";$lg[$kg]++;}echo$cj;if($Wh)echo
script("ajaxSetHtml('".js_escape(ME)."script=db');");}else{echo"<thead><tr><th style='text-align: left;'>","<label class='block'><input type='checkbox' id='check-databases'".($a==""?" checked":"").">".'Database'."</label>",script("qs('#check-databases').onclick = partial(formCheck, /^databases\\[/);",""),"</thead>\n";$k=$b->databases();if($k){foreach($k
as$l){if(!information_schema($l)){$kg=preg_replace('~_.*~','',$l);echo"<tr><td>".checkbox("databases[]",$l,$a==""||$a=="$kg%",$l,"","block")."\n";$lg[$kg]++;}}}else
echo"<tr><td><textarea name='databases' rows='10' cols='20'></textarea>";}echo'</table>
</form>
';$Yc=true;foreach($lg
as$y=>$X){if($y!=""&&$X>1){echo($Yc?"<p>":" ")."<a href='".h(ME)."dump=".urlencode("$y%")."'>".h($y)."</a>";$Yc=false;}}}elseif(isset($_GET["privileges"])){page_header('Privileges');echo'<p class="links"><a href="'.h(ME).'user=">'.'Create user'."</a>";$G=$g->query("SELECT User, Host FROM mysql.".(DB==""?"user":"db WHERE ".q(DB)." LIKE Db")." ORDER BY Host, User");$nd=$G;if(!$G)$G=$g->query("SELECT SUBSTRING_INDEX(CURRENT_USER, '@', 1) AS User, SUBSTRING_INDEX(CURRENT_USER, '@', -1) AS Host");echo"<form action=''><p>\n";hidden_fields_get();echo"<input type='hidden' name='db' value='".h(DB)."'>\n",($nd?"":"<input type='hidden' name='grant' value=''>\n"),"<table cellspacing='0'>\n","<thead><tr><th>".'Username'."<th>".'Server'."<th></thead>\n";while($I=$G->fetch_assoc())echo'<tr'.odd().'><td>'.h($I["User"])."<td>".h($I["Host"]).'<td><a href="'.h(ME.'user='.urlencode($I["User"]).'&host='.urlencode($I["Host"])).'">'.'Edit'."</a>\n";if(!$nd||DB!="")echo"<tr".odd()."><td><input name='user' autocapitalize='off'><td><input name='host' value='localhost' autocapitalize='off'><td><input type='submit' value='".'Edit'."'>\n";echo"</table>\n","</form>\n";}elseif(isset($_GET["sql"])){if(!$n&&$_POST["export"]){dump_headers("sql");$b->dumpTable("","");$b->dumpData("","table",$_POST["query"]);exit;}restart_session();$Ad=&get_session("queries");$_d=&$Ad[DB];if(!$n&&$_POST["clear"]){$_d=array();redirect(remove_from_uri("history"));}page_header((isset($_GET["import"])?'Import':'SQL command'),$n);if(!$n&&$_POST){$kd=false;if(!isset($_GET["import"]))$F=$_POST["query"];elseif($_POST["webfile"]){$Bh=$b->importServerPath();$kd=@fopen((file_exists($Bh)?$Bh:"compress.zlib://$Bh.gz"),"rb");$F=($kd?fread($kd,1e6):false);}else$F=get_file("sql_file",true);if(is_string($F)){if(function_exists('memory_get_usage'))@ini_set("memory_limit",max(ini_bytes("memory_limit"),2*strlen($F)+memory_get_usage()+8e6));if($F!=""&&strlen($F)<1e6){$wg=$F.(preg_match("~;[ \t\r\n]*\$~",$F)?"":";");if(!$_d||reset(end($_d))!=$wg){restart_session();$_d[]=array($wg,time());set_session("queries",$Ad);stop_session();}}$zh="(?:\\s|/\\*[\s\S]*?\\*/|(?:#|-- )[^\n]*\n?|--\r?\n)";$Yb=";";$C=0;$vc=true;$h=connect();if(is_object($h)&&DB!=""){$h->select_db(DB);if($_GET["ns"]!="")set_schema($_GET["ns"],$h);}$tb=0;$_c=array();$Sf='[\'"'.($x=="sql"?'`#':($x=="sqlite"?'`[':($x=="mssql"?'[':''))).']|/\*|-- |$'.($x=="pgsql"?'|\$[^$]*\$':'');$si=microtime(true);parse_str($_COOKIE["adminer_export"],$xa);$mc=$b->dumpFormat();unset($mc["sql"]);while($F!=""){if(!$C&&preg_match("~^$zh*+DELIMITER\\s+(\\S+)~i",$F,$A)){$Yb=$A[1];$F=substr($F,strlen($A[0]));}else{preg_match('('.preg_quote($Yb)."\\s*|$Sf)",$F,$A,PREG_OFFSET_CAPTURE,$C);list($id,$gg)=$A[0];if(!$id&&$kd&&!feof($kd))$F.=fread($kd,1e5);else{if(!$id&&rtrim($F)=="")break;$C=$gg+strlen($id);if($id&&rtrim($id)!=$Yb){while(preg_match('('.($id=='/*'?'\*/':($id=='['?']':(preg_match('~^-- |^#~',$id)?"\n":preg_quote($id)."|\\\\."))).'|$)s',$F,$A,PREG_OFFSET_CAPTURE,$C)){$ah=$A[0][0];if(!$ah&&$kd&&!feof($kd))$F.=fread($kd,1e5);else{$C=$A[0][1]+strlen($ah);if($ah[0]!="\\")break;}}}else{$vc=false;$wg=substr($F,0,$gg);$tb++;$og="<pre id='sql-$tb'><code class='jush-$x'>".$b->sqlCommandQuery($wg)."</code></pre>\n";if($x=="sqlite"&&preg_match("~^$zh*+ATTACH\\b~i",$wg,$A)){echo$og,"<p class='error'>".'ATTACH queries are not supported.'."\n";$_c[]=" <a href='#sql-$tb'>$tb</a>";if($_POST["error_stops"])break;}else{if(!$_POST["only_errors"]){echo$og;ob_flush();flush();}$Fh=microtime(true);if($g->multi_query($wg)&&is_object($h)&&preg_match("~^$zh*+USE\\b~i",$wg))$h->query($wg);do{$G=$g->store_result();if($g->error){echo($_POST["only_errors"]?$og:""),"<p class='error'>".'Error in query'.($g->errno?" ($g->errno)":"").": ".error()."\n";$_c[]=" <a href='#sql-$tb'>$tb</a>";if($_POST["error_stops"])break
2;}else{$gi=" <span class='time'>(".format_time($Fh).")</span>".(strlen($wg)<1000?" <a href='".h(ME)."sql=".urlencode(trim($wg))."'>".'Edit'."</a>":"");$za=$g->affected_rows;$fj=($_POST["only_errors"]?"":$m->warnings());$gj="warnings-$tb";if($fj)$gi.=", <a href='#$gj'>".'Warnings'."</a>".script("qsl('a').onclick = partial(toggle, '$gj');","");$Hc=null;$Ic="explain-$tb";if(is_object($G)){$z=$_POST["limit"];$Ef=select($G,$h,array(),$z);if(!$_POST["only_errors"]){echo"<form action='' method='post'>\n";$if=$G->num_rows;echo"<p>".($if?($z&&$if>$z?sprintf('%d / ',$z):"").lang(array('%d row','%d rows'),$if):""),$gi;if($h&&preg_match("~^($zh|\\()*+SELECT\\b~i",$wg)&&($Hc=explain($h,$wg)))echo", <a href='#$Ic'>Explain</a>".script("qsl('a').onclick = partial(toggle, '$Ic');","");$t="export-$tb";echo", <a href='#$t'>".'Export'."</a>".script("qsl('a').onclick = partial(toggle, '$t');","")."<span id='$t' class='hidden'>: ".html_select("output",$b->dumpOutput(),$xa["output"])." ".html_select("format",$mc,$xa["format"])."<input type='hidden' name='query' value='".h($wg)."'>"." <input type='submit' name='export' value='".'Export'."'><input type='hidden' name='token' value='$qi'></span>\n"."</form>\n";}}else{if(preg_match("~^$zh*+(CREATE|DROP|ALTER)$zh++(DATABASE|SCHEMA)\\b~i",$wg)){restart_session();set_session("dbs",null);stop_session();}if(!$_POST["only_errors"])echo"<p class='message' title='".h($g->info)."'>".lang(array('Query executed OK, %d row affected.','Query executed OK, %d rows affected.'),$za)."$gi\n";}echo($fj?"<div id='$gj' class='hidden'>\n$fj</div>\n":"");if($Hc){echo"<div id='$Ic' class='hidden'>\n";select($Hc,$h,$Ef);echo"</div>\n";}}$Fh=microtime(true);}while($g->next_result());}$F=substr($F,$C);$C=0;}}}}if($vc)echo"<p class='message'>".'No commands to execute.'."\n";elseif($_POST["only_errors"]){echo"<p class='message'>".lang(array('%d query executed OK.','%d queries executed OK.'),$tb-count($_c))," <span class='time'>(".format_time($si).")</span>\n";}elseif($_c&&$tb>1)echo"<p class='error'>".'Error in query'.": ".implode("",$_c)."\n";}else
echo"<p class='error'>".upload_error($F)."\n";}echo'
<form action="" method="post" enctype="multipart/form-data" id="form">
';$Ec="<input type='submit' value='".'Execute'."' title='Ctrl+Enter'>";if(!isset($_GET["import"])){$wg=$_GET["sql"];if($_POST)$wg=$_POST["query"];elseif($_GET["history"]=="all")$wg=$_d;elseif($_GET["history"]!="")$wg=$_d[$_GET["history"]][0];echo"<p>";textarea("query",$wg,20);echo
script(($_POST?"":"qs('textarea').focus();\n")."qs('#form').onsubmit = partial(sqlSubmit, qs('#form'), '".remove_from_uri("sql|limit|error_stops|only_errors")."');"),"<p>$Ec\n",'Limit rows'.": <input type='number' name='limit' class='size' value='".h($_POST?$_POST["limit"]:$_GET["limit"])."'>\n";}else{echo"<fieldset><legend>".'File upload'."</legend><div>";$td=(extension_loaded("zlib")?"[.gz]":"");echo(ini_bool("file_uploads")?"SQL$td (&lt; ".ini_get("upload_max_filesize")."B): <input type='file' name='sql_file[]' multiple>\n$Ec":'File uploads are disabled.'),"</div></fieldset>\n";$Id=$b->importServerPath();if($Id){echo"<fieldset><legend>".'From server'."</legend><div>",sprintf('Webserver file %s',"<code>".h($Id)."$td</code>"),' <input type="submit" name="webfile" value="'.'Run file'.'">',"</div></fieldset>\n";}echo"<p>";}echo
checkbox("error_stops",1,($_POST?$_POST["error_stops"]:isset($_GET["import"])),'Stop on error')."\n",checkbox("only_errors",1,($_POST?$_POST["only_errors"]:isset($_GET["import"])),'Show only errors')."\n","<input type='hidden' name='token' value='$qi'>\n";if(!isset($_GET["import"])&&$_d){print_fieldset("history",'History',$_GET["history"]!="");for($X=end($_d);$X;$X=prev($_d)){$y=key($_d);list($wg,$gi,$qc)=$X;echo'<a href="'.h(ME."sql=&history=$y").'">'.'Edit'."</a>"." <span class='time' title='".@date('Y-m-d',$gi)."'>".@date("H:i:s",$gi)."</span>"." <code class='jush-$x'>".shorten_utf8(ltrim(str_replace("\n"," ",str_replace("\r","",preg_replace('~^(#|-- ).*~m','',$wg)))),80,"</code>").($qc?" <span class='time'>($qc)</span>":"")."<br>\n";}echo"<input type='submit' name='clear' value='".'Clear'."'>\n","<a href='".h(ME."sql=&history=all")."'>".'Edit all'."</a>\n","</div></fieldset>\n";}echo'</form>
';}elseif(isset($_GET["edit"])){$a=$_GET["edit"];$p=fields($a);$Z=(isset($_GET["select"])?($_POST["check"]&&count($_POST["check"])==1?where_check($_POST["check"][0],$p):""):where($_GET,$p));$Li=(isset($_GET["select"])?$_POST["edit"]:$Z);foreach($p
as$B=>$o){if(!isset($o["privileges"][$Li?"update":"insert"])||$b->fieldName($o)==""||$o["generated"])unset($p[$B]);}if($_POST&&!$n&&!isset($_GET["select"])){$ze=$_POST["referer"];if($_POST["insert"])$ze=($Li?null:$_SERVER["REQUEST_URI"]);elseif(!preg_match('~^.+&select=.+$~',$ze))$ze=ME."select=".urlencode($a);$w=indexes($a);$Gi=unique_array($_GET["where"],$w);$zg="\nWHERE $Z";if(isset($_POST["delete"]))queries_redirect($ze,'Item has been deleted.',$m->delete($a,$zg,!$Gi));else{$N=array();foreach($p
as$B=>$o){$X=process_input($o);if($X!==false&&$X!==null)$N[idf_escape($B)]=$X;}if($Li){if(!$N)redirect($ze);queries_redirect($ze,'Item has been updated.',$m->update($a,$N,$zg,!$Gi));if(is_ajax()){page_headers();page_messages($n);exit;}}else{$G=$m->insert($a,$N);$qe=($G?last_id():0);queries_redirect($ze,sprintf('Item%s has been inserted.',($qe?" $qe":"")),$G);}}}$I=null;if($_POST["save"])$I=(array)$_POST["fields"];elseif($Z){$K=array();foreach($p
as$B=>$o){if(isset($o["privileges"]["select"])){$Ga=convert_field($o);if($_POST["clone"]&&$o["auto_increment"])$Ga="''";if($x=="sql"&&preg_match("~enum|set~",$o["type"]))$Ga="1*".idf_escape($B);$K[]=($Ga?"$Ga AS ":"").idf_escape($B);}}$I=array();if(!support("table"))$K=array("*");if($K){$G=$m->select($a,$K,array($Z),$K,array(),(isset($_GET["select"])?2:1));if(!$G)$n=error();else{$I=$G->fetch_assoc();if(!$I)$I=false;}if(isset($_GET["select"])&&(!$I||$G->fetch_assoc()))$I=null;}}if(!support("table")&&!$p){if(!$Z){$G=$m->select($a,array("*"),$Z,array("*"));$I=($G?$G->fetch_assoc():false);if(!$I)$I=array($m->primary=>"");}if($I){foreach($I
as$y=>$X){if(!$Z)$I[$y]=null;$p[$y]=array("field"=>$y,"null"=>($y!=$m->primary),"auto_increment"=>($y==$m->primary));}}}edit_form($a,$p,$I,$Li);}elseif(isset($_GET["create"])){$a=$_GET["create"];$Uf=array();foreach(array('HASH','LINEAR HASH','KEY','LINEAR KEY','RANGE','LIST')as$y)$Uf[$y]=$y;$Gg=referencable_primary($a);$gd=array();foreach($Gg
as$Rh=>$o)$gd[str_replace("`","``",$Rh)."`".str_replace("`","``",$o["field"])]=$Rh;$Hf=array();$R=array();if($a!=""){$Hf=fields($a);$R=table_status($a);if(!$R)$n='No tables.';}$I=$_POST;$I["fields"]=(array)$I["fields"];if($I["auto_increment_col"])$I["fields"][$I["auto_increment_col"]]["auto_increment"]=true;if($_POST)set_adminer_settings(array("comments"=>$_POST["comments"],"defaults"=>$_POST["defaults"]));if($_POST&&!process_fields($I["fields"])&&!$n){if($_POST["drop"])queries_redirect(substr(ME,0,-1),'Table has been dropped.',drop_tables(array($a)));else{$p=array();$Da=array();$Qi=false;$ed=array();$Gf=reset($Hf);$Aa=" FIRST";foreach($I["fields"]as$y=>$o){$q=$gd[$o["type"]];$Ci=($q!==null?$Gg[$q]:$o);if($o["field"]!=""){if(!$o["has_default"])$o["default"]=null;if($y==$I["auto_increment_col"])$o["auto_increment"]=true;$tg=process_field($o,$Ci);$Da[]=array($o["orig"],$tg,$Aa);if($tg!=process_field($Gf,$Gf)){$p[]=array($o["orig"],$tg,$Aa);if($o["orig"]!=""||$Aa)$Qi=true;}if($q!==null)$ed[idf_escape($o["field"])]=($a!=""&&$x!="sqlite"?"ADD":" ").format_foreign_key(array('table'=>$gd[$o["type"]],'source'=>array($o["field"]),'target'=>array($Ci["field"]),'on_delete'=>$o["on_delete"],));$Aa=" AFTER ".idf_escape($o["field"]);}elseif($o["orig"]!=""){$Qi=true;$p[]=array($o["orig"]);}if($o["orig"]!=""){$Gf=next($Hf);if(!$Gf)$Aa="";}}$Wf="";if($Uf[$I["partition_by"]]){$Xf=array();if($I["partition_by"]=='RANGE'||$I["partition_by"]=='LIST'){foreach(array_filter($I["partition_names"])as$y=>$X){$Y=$I["partition_values"][$y];$Xf[]="\n  PARTITION ".idf_escape($X)." VALUES ".($I["partition_by"]=='RANGE'?"LESS THAN":"IN").($Y!=""?" ($Y)":" MAXVALUE");}}$Wf.="\nPARTITION BY $I[partition_by]($I[partition])".($Xf?" (".implode(",",$Xf)."\n)":($I["partitions"]?" PARTITIONS ".(+$I["partitions"]):""));}elseif(support("partitioning")&&preg_match("~partitioned~",$R["Create_options"]))$Wf.="\nREMOVE PARTITIONING";$Ne='Table has been altered.';if($a==""){cookie("adminer_engine",$I["Engine"]);$Ne='Table has been created.';}$B=trim($I["name"]);queries_redirect(ME.(support("table")?"table=":"select=").urlencode($B),$Ne,alter_table($a,$B,($x=="sqlite"&&($Qi||$ed)?$Da:$p),$ed,($I["Comment"]!=$R["Comment"]?$I["Comment"]:null),($I["Engine"]&&$I["Engine"]!=$R["Engine"]?$I["Engine"]:""),($I["Collation"]&&$I["Collation"]!=$R["Collation"]?$I["Collation"]:""),($I["Auto_increment"]!=""?number($I["Auto_increment"]):""),$Wf));}}page_header(($a!=""?'Alter table':'Create table'),$n,array("table"=>$a),h($a));if(!$_POST){$I=array("Engine"=>$_COOKIE["adminer_engine"],"fields"=>array(array("field"=>"","type"=>(isset($U["int"])?"int":(isset($U["integer"])?"integer":"")),"on_update"=>"")),"partition_names"=>array(""),);if($a!=""){$I=$R;$I["name"]=$a;$I["fields"]=array();if(!$_GET["auto_increment"])$I["Auto_increment"]="";foreach($Hf
as$o){$o["has_default"]=isset($o["default"]);$I["fields"][]=$o;}if(support("partitioning")){$ld="FROM information_schema.PARTITIONS WHERE TABLE_SCHEMA = ".q(DB)." AND TABLE_NAME = ".q($a);$G=$g->query("SELECT PARTITION_METHOD, PARTITION_ORDINAL_POSITION, PARTITION_EXPRESSION $ld ORDER BY PARTITION_ORDINAL_POSITION DESC LIMIT 1");list($I["partition_by"],$I["partitions"],$I["partition"])=$G->fetch_row();$Xf=get_key_vals("SELECT PARTITION_NAME, PARTITION_DESCRIPTION $ld AND PARTITION_NAME != '' ORDER BY PARTITION_ORDINAL_POSITION");$Xf[""]="";$I["partition_names"]=array_keys($Xf);$I["partition_values"]=array_values($Xf);}}}$pb=collations();$xc=engines();foreach($xc
as$wc){if(!strcasecmp($wc,$I["Engine"])){$I["Engine"]=$wc;break;}}echo'
<form action="" method="post" id="form">
<p>
';if(support("columns")||$a==""){echo'Table name: <input name="name" data-maxlength="64" value="',h($I["name"]),'" autocapitalize="off">
';if($a==""&&!$_POST)echo
script("focus(qs('#form')['name']);");echo($xc?"<select name='Engine'>".optionlist(array(""=>"(".'engine'.")")+$xc,$I["Engine"])."</select>".on_help("getTarget(event).value",1).script("qsl('select').onchange = helpClose;"):""),' ',($pb&&!preg_match("~sqlite|mssql~",$x)?html_select("Collation",array(""=>"(".'collation'.")")+$pb,$I["Collation"]):""),' <input type="submit" value="Save">
';}echo'
';if(support("columns")){echo'<div class="scrollable">
<table cellspacing="0" id="edit-fields" class="nowrap">
';edit_fields($I["fields"],$pb,"TABLE",$gd);echo'</table>
',script("editFields();"),'</div>
<p>
Auto Increment: <input type="number" name="Auto_increment" size="6" value="',h($I["Auto_increment"]),'">
',checkbox("defaults",1,($_POST?$_POST["defaults"]:adminer_setting("defaults")),'Default values',"columnShow(this.checked, 5)","jsonly"),(support("comment")?checkbox("comments",1,($_POST?$_POST["comments"]:adminer_setting("comments")),'Comment',"editingCommentsClick(this, true);","jsonly").' <input name="Comment" value="'.h($I["Comment"]).'" data-maxlength="'.(min_version(5.5)?2048:60).'">':''),'<p>
<input type="submit" value="Save">
';}echo'
';if($a!=""){echo'<input type="submit" name="drop" value="Drop">',confirm(sprintf('Drop %s?',$a));}if(support("partitioning")){$Vf=preg_match('~RANGE|LIST~',$I["partition_by"]);print_fieldset("partition",'Partition by',$I["partition_by"]);echo'<p>
',"<select name='partition_by'>".optionlist(array(""=>"")+$Uf,$I["partition_by"])."</select>".on_help("getTarget(event).value.replace(/./, 'PARTITION BY \$&')",1).script("qsl('select').onchange = partitionByChange;"),'(<input name="partition" value="',h($I["partition"]),'">)
Partitions: <input type="number" name="partitions" class="size',($Vf||!$I["partition_by"]?" hidden":""),'" value="',h($I["partitions"]),'">
<table cellspacing="0" id="partition-table"',($Vf?"":" class='hidden'"),'>
<thead><tr><th>Partition name<th>Values</thead>
';foreach($I["partition_names"]as$y=>$X){echo'<tr>','<td><input name="partition_names[]" value="'.h($X).'" autocapitalize="off">',($y==count($I["partition_names"])-1?script("qsl('input').oninput = partitionNameChange;"):''),'<td><input name="partition_values[]" value="'.h($I["partition_values"][$y]).'">';}echo'</table>
</div></fieldset>
';}echo'<input type="hidden" name="token" value="',$qi,'">
</form>
';}elseif(isset($_GET["indexes"])){$a=$_GET["indexes"];$Ld=array("PRIMARY","UNIQUE","INDEX");$R=table_status($a,true);if(preg_match('~MyISAM|M?aria'.(min_version(5.6,'10.0.5')?'|InnoDB':'').'~i',$R["Engine"]))$Ld[]="FULLTEXT";if(preg_match('~MyISAM|M?aria'.(min_version(5.7,'10.2.2')?'|InnoDB':'').'~i',$R["Engine"]))$Ld[]="SPATIAL";$w=indexes($a);$mg=array();if($x=="mongo"){$mg=$w["_id_"];unset($Ld[0]);unset($w["_id_"]);}$I=$_POST;if($_POST&&!$n&&!$_POST["add"]&&!$_POST["drop_col"]){$c=array();foreach($I["indexes"]as$v){$B=$v["name"];if(in_array($v["type"],$Ld)){$f=array();$we=array();$ac=array();$N=array();ksort($v["columns"]);foreach($v["columns"]as$y=>$e){if($e!=""){$ve=$v["lengths"][$y];$Zb=$v["descs"][$y];$N[]=idf_escape($e).($ve?"(".(+$ve).")":"").($Zb?" DESC":"");$f[]=$e;$we[]=($ve?$ve:null);$ac[]=$Zb;}}if($f){$Fc=$w[$B];if($Fc){ksort($Fc["columns"]);ksort($Fc["lengths"]);ksort($Fc["descs"]);if($v["type"]==$Fc["type"]&&array_values($Fc["columns"])===$f&&(!$Fc["lengths"]||array_values($Fc["lengths"])===$we)&&array_values($Fc["descs"])===$ac){unset($w[$B]);continue;}}$c[]=array($v["type"],$B,$N);}}}foreach($w
as$B=>$Fc)$c[]=array($Fc["type"],$B,"DROP");if(!$c)redirect(ME."table=".urlencode($a));queries_redirect(ME."table=".urlencode($a),'Indexes have been altered.',alter_indexes($a,$c));}page_header('Indexes',$n,array("table"=>$a),h($a));$p=array_keys(fields($a));if($_POST["add"]){foreach($I["indexes"]as$y=>$v){if($v["columns"][count($v["columns"])]!="")$I["indexes"][$y]["columns"][]="";}$v=end($I["indexes"]);if($v["type"]||array_filter($v["columns"],'strlen'))$I["indexes"][]=array("columns"=>array(1=>""));}if(!$I){foreach($w
as$y=>$v){$w[$y]["name"]=$y;$w[$y]["columns"][]="";}$w[]=array("columns"=>array(1=>""));$I["indexes"]=$w;}echo'
<form action="" method="post">
<div class="scrollable">
<table cellspacing="0" class="nowrap">
<thead><tr>
<th id="label-type">Index Type
<th><input type="submit" class="wayoff">Column (length)
<th id="label-name">Name
<th><noscript>',"<input type='image' class='icon' name='add[0]' src='".h(preg_replace("~\\?.*~","",ME)."?file=plus.gif&version=4.7.7")."' alt='+' title='".'Add next'."'>",'</noscript>
</thead>
';if($mg){echo"<tr><td>PRIMARY<td>";foreach($mg["columns"]as$y=>$e){echo
select_input(" disabled",$p,$e),"<label><input disabled type='checkbox'>".'descending'."</label> ";}echo"<td><td>\n";}$ee=1;foreach($I["indexes"]as$v){if(!$_POST["drop_col"]||$ee!=key($_POST["drop_col"])){echo"<tr><td>".html_select("indexes[$ee][type]",array(-1=>"")+$Ld,$v["type"],($ee==count($I["indexes"])?"indexesAddRow.call(this);":1),"label-type"),"<td>";ksort($v["columns"]);$s=1;foreach($v["columns"]as$y=>$e){echo"<span>".select_input(" name='indexes[$ee][columns][$s]' title='".'Column'."'",($p?array_combine($p,$p):$p),$e,"partial(".($s==count($v["columns"])?"indexesAddColumn":"indexesChangeColumn").", '".js_escape($x=="sql"?"":$_GET["indexes"]."_")."')"),($x=="sql"||$x=="mssql"?"<input type='number' name='indexes[$ee][lengths][$s]' class='size' value='".h($v["lengths"][$y])."' title='".'Length'."'>":""),(support("descidx")?checkbox("indexes[$ee][descs][$s]",1,$v["descs"][$y],'descending'):"")," </span>";$s++;}echo"<td><input name='indexes[$ee][name]' value='".h($v["name"])."' autocapitalize='off' aria-labelledby='label-name'>\n","<td><input type='image' class='icon' name='drop_col[$ee]' src='".h(preg_replace("~\\?.*~","",ME)."?file=cross.gif&version=4.7.7")."' alt='x' title='".'Remove'."'>".script("qsl('input').onclick = partial(editingRemoveRow, 'indexes\$1[type]');");}$ee++;}echo'</table>
</div>
<p>
<input type="submit" value="Save">
<input type="hidden" name="token" value="',$qi,'">
</form>
';}elseif(isset($_GET["database"])){$I=$_POST;if($_POST&&!$n&&!isset($_POST["add_x"])){$B=trim($I["name"]);if($_POST["drop"]){$_GET["db"]="";queries_redirect(remove_from_uri("db|database"),'Database has been dropped.',drop_databases(array(DB)));}elseif(DB!==$B){if(DB!=""){$_GET["db"]=$B;queries_redirect(preg_replace('~\bdb=[^&]*&~','',ME)."db=".urlencode($B),'Database has been renamed.',rename_database($B,$I["collation"]));}else{$k=explode("\n",str_replace("\r","",$B));$Lh=true;$pe="";foreach($k
as$l){if(count($k)==1||$l!=""){if(!create_database($l,$I["collation"]))$Lh=false;$pe=$l;}}restart_session();set_session("dbs",null);queries_redirect(ME."db=".urlencode($pe),'Database has been created.',$Lh);}}else{if(!$I["collation"])redirect(substr(ME,0,-1));query_redirect("ALTER DATABASE ".idf_escape($B).(preg_match('~^[a-z0-9_]+$~i',$I["collation"])?" COLLATE $I[collation]":""),substr(ME,0,-1),'Database has been altered.');}}page_header(DB!=""?'Alter database':'Create database',$n,array(),h(DB));$pb=collations();$B=DB;if($_POST)$B=$I["name"];elseif(DB!="")$I["collation"]=db_collation(DB,$pb);elseif($x=="sql"){foreach(get_vals("SHOW GRANTS")as$nd){if(preg_match('~ ON (`(([^\\\\`]|``|\\\\.)*)%`\.\*)?~',$nd,$A)&&$A[1]){$B=stripcslashes(idf_unescape("`$A[2]`"));break;}}}echo'
<form action="" method="post">
<p>
',($_POST["add_x"]||strpos($B,"\n")?'<textarea id="name" name="name" rows="10" cols="40">'.h($B).'</textarea><br>':'<input name="name" id="name" value="'.h($B).'" data-maxlength="64" autocapitalize="off">')."\n".($pb?html_select("collation",array(""=>"(".'collation'.")")+$pb,$I["collation"]).doc_link(array('sql'=>"charset-charsets.html",'mariadb'=>"supported-character-sets-and-collations/",'mssql'=>"ms187963.aspx",)):""),script("focus(qs('#name'));"),'<input type="submit" value="Save">
';if(DB!="")echo"<input type='submit' name='drop' value='".'Drop'."'>".confirm(sprintf('Drop %s?',DB))."\n";elseif(!$_POST["add_x"]&&$_GET["db"]=="")echo"<input type='image' class='icon' name='add' src='".h(preg_replace("~\\?.*~","",ME)."?file=plus.gif&version=4.7.7")."' alt='+' title='".'Add next'."'>\n";echo'<input type="hidden" name="token" value="',$qi,'">
</form>
';}elseif(isset($_GET["scheme"])){$I=$_POST;if($_POST&&!$n){$_=preg_replace('~ns=[^&]*&~','',ME)."ns=";if($_POST["drop"])query_redirect("DROP SCHEMA ".idf_escape($_GET["ns"]),$_,'Schema has been dropped.');else{$B=trim($I["name"]);$_.=urlencode($B);if($_GET["ns"]=="")query_redirect("CREATE SCHEMA ".idf_escape($B),$_,'Schema has been created.');elseif($_GET["ns"]!=$B)query_redirect("ALTER SCHEMA ".idf_escape($_GET["ns"])." RENAME TO ".idf_escape($B),$_,'Schema has been altered.');else
redirect($_);}}page_header($_GET["ns"]!=""?'Alter schema':'Create schema',$n);if(!$I)$I["name"]=$_GET["ns"];echo'
<form action="" method="post">
<p><input name="name" id="name" value="',h($I["name"]),'" autocapitalize="off">
',script("focus(qs('#name'));"),'<input type="submit" value="Save">
';if($_GET["ns"]!="")echo"<input type='submit' name='drop' value='".'Drop'."'>".confirm(sprintf('Drop %s?',$_GET["ns"]))."\n";echo'<input type="hidden" name="token" value="',$qi,'">
</form>
';}elseif(isset($_GET["call"])){$da=($_GET["name"]?$_GET["name"]:$_GET["call"]);page_header('Call'.": ".h($da),$n);$Wg=routine($_GET["call"],(isset($_GET["callf"])?"FUNCTION":"PROCEDURE"));$Jd=array();$Lf=array();foreach($Wg["fields"]as$s=>$o){if(substr($o["inout"],-3)=="OUT")$Lf[$s]="@".idf_escape($o["field"])." AS ".idf_escape($o["field"]);if(!$o["inout"]||substr($o["inout"],0,2)=="IN")$Jd[]=$s;}if(!$n&&$_POST){$ab=array();foreach($Wg["fields"]as$y=>$o){if(in_array($y,$Jd)){$X=process_input($o);if($X===false)$X="''";if(isset($Lf[$y]))$g->query("SET @".idf_escape($o["field"])." = $X");}$ab[]=(isset($Lf[$y])?"@".idf_escape($o["field"]):$X);}$F=(isset($_GET["callf"])?"SELECT":"CALL")." ".table($da)."(".implode(", ",$ab).")";$Fh=microtime(true);$G=$g->multi_query($F);$za=$g->affected_rows;echo$b->selectQuery($F,$Fh,!$G);if(!$G)echo"<p class='error'>".error()."\n";else{$h=connect();if(is_object($h))$h->select_db(DB);do{$G=$g->store_result();if(is_object($G))select($G,$h);else
echo"<p class='message'>".lang(array('Routine has been called, %d row affected.','Routine has been called, %d rows affected.'),$za)." <span class='time'>".@date("H:i:s")."</span>\n";}while($g->next_result());if($Lf)select($g->query("SELECT ".implode(", ",$Lf)));}}echo'
<form action="" method="post">
';if($Jd){echo"<table cellspacing='0' class='layout'>\n";foreach($Jd
as$y){$o=$Wg["fields"][$y];$B=$o["field"];echo"<tr><th>".$b->fieldName($o);$Y=$_POST["fields"][$B];if($Y!=""){if($o["type"]=="enum")$Y=+$Y;if($o["type"]=="set")$Y=array_sum($Y);}input($o,$Y,(string)$_POST["function"][$B]);echo"\n";}echo"</table>\n";}echo'<p>
<input type="submit" value="Call">
<input type="hidden" name="token" value="',$qi,'">
</form>
';}elseif(isset($_GET["foreign"])){$a=$_GET["foreign"];$B=$_GET["name"];$I=$_POST;if($_POST&&!$n&&!$_POST["add"]&&!$_POST["change"]&&!$_POST["change-js"]){$Ne=($_POST["drop"]?'Foreign key has been dropped.':($B!=""?'Foreign key has been altered.':'Foreign key has been created.'));$ze=ME."table=".urlencode($a);if(!$_POST["drop"]){$I["source"]=array_filter($I["source"],'strlen');ksort($I["source"]);$Zh=array();foreach($I["source"]as$y=>$X)$Zh[$y]=$I["target"][$y];$I["target"]=$Zh;}if($x=="sqlite")queries_redirect($ze,$Ne,recreate_table($a,$a,array(),array(),array(" $B"=>($_POST["drop"]?"":" ".format_foreign_key($I)))));else{$c="ALTER TABLE ".table($a);$hc="\nDROP ".($x=="sql"?"FOREIGN KEY ":"CONSTRAINT ").idf_escape($B);if($_POST["drop"])query_redirect($c.$hc,$ze,$Ne);else{query_redirect($c.($B!=""?"$hc,":"")."\nADD".format_foreign_key($I),$ze,$Ne);$n='Source and target columns must have the same data type, there must be an index on the target columns and referenced data must exist.'."<br>$n";}}}page_header('Foreign key',$n,array("table"=>$a),h($a));if($_POST){ksort($I["source"]);if($_POST["add"])$I["source"][]="";elseif($_POST["change"]||$_POST["change-js"])$I["target"]=array();}elseif($B!=""){$gd=foreign_keys($a);$I=$gd[$B];$I["source"][]="";}else{$I["table"]=$a;$I["source"]=array("");}echo'
<form action="" method="post">
';$yh=array_keys(fields($a));if($I["db"]!="")$g->select_db($I["db"]);if($I["ns"]!="")set_schema($I["ns"]);$Fg=array_keys(array_filter(table_status('',true),'fk_support'));$Zh=($a===$I["table"]?$yh:array_keys(fields(in_array($I["table"],$Fg)?$I["table"]:reset($Fg))));$tf="this.form['change-js'].value = '1'; this.form.submit();";echo"<p>".'Target table'.": ".html_select("table",$Fg,$I["table"],$tf)."\n";if($x=="pgsql")echo'Schema'.": ".html_select("ns",$b->schemas(),$I["ns"]!=""?$I["ns"]:$_GET["ns"],$tf);elseif($x!="sqlite"){$Sb=array();foreach($b->databases()as$l){if(!information_schema($l))$Sb[]=$l;}echo'DB'.": ".html_select("db",$Sb,$I["db"]!=""?$I["db"]:$_GET["db"],$tf);}echo'<input type="hidden" name="change-js" value="">
<noscript><p><input type="submit" name="change" value="Change"></noscript>
<table cellspacing="0">
<thead><tr><th id="label-source">Source<th id="label-target">Target</thead>
';$ee=0;foreach($I["source"]as$y=>$X){echo"<tr>","<td>".html_select("source[".(+$y)."]",array(-1=>"")+$yh,$X,($ee==count($I["source"])-1?"foreignAddRow.call(this);":1),"label-source"),"<td>".html_select("target[".(+$y)."]",$Zh,$I["target"][$y],1,"label-target");$ee++;}echo'</table>
<p>
ON DELETE: ',html_select("on_delete",array(-1=>"")+explode("|",$sf),$I["on_delete"]),' ON UPDATE: ',html_select("on_update",array(-1=>"")+explode("|",$sf),$I["on_update"]),doc_link(array('sql'=>"innodb-foreign-key-constraints.html",'mariadb'=>"foreign-keys/",'pgsql'=>"sql-createtable.html#SQL-CREATETABLE-REFERENCES",'mssql'=>"ms174979.aspx",'oracle'=>"https://docs.oracle.com/cd/B19306_01/server.102/b14200/clauses002.htm#sthref2903",)),'<p>
<input type="submit" value="Save">
<noscript><p><input type="submit" name="add" value="Add column"></noscript>
';if($B!=""){echo'<input type="submit" name="drop" value="Drop">',confirm(sprintf('Drop %s?',$B));}echo'<input type="hidden" name="token" value="',$qi,'">
</form>
';}elseif(isset($_GET["view"])){$a=$_GET["view"];$I=$_POST;$If="VIEW";if($x=="pgsql"&&$a!=""){$O=table_status($a);$If=strtoupper($O["Engine"]);}if($_POST&&!$n){$B=trim($I["name"]);$Ga=" AS\n$I[select]";$ze=ME."table=".urlencode($B);$Ne='View has been altered.';$T=($_POST["materialized"]?"MATERIALIZED VIEW":"VIEW");if(!$_POST["drop"]&&$a==$B&&$x!="sqlite"&&$T=="VIEW"&&$If=="VIEW")query_redirect(($x=="mssql"?"ALTER":"CREATE OR REPLACE")." VIEW ".table($B).$Ga,$ze,$Ne);else{$bi=$B."_adminer_".uniqid();drop_create("DROP $If ".table($a),"CREATE $T ".table($B).$Ga,"DROP $T ".table($B),"CREATE $T ".table($bi).$Ga,"DROP $T ".table($bi),($_POST["drop"]?substr(ME,0,-1):$ze),'View has been dropped.',$Ne,'View has been created.',$a,$B);}}if(!$_POST&&$a!=""){$I=view($a);$I["name"]=$a;$I["materialized"]=($If!="VIEW");if(!$n)$n=error();}page_header(($a!=""?'Alter view':'Create view'),$n,array("table"=>$a),h($a));echo'
<form action="" method="post">
<p>Name: <input name="name" value="',h($I["name"]),'" data-maxlength="64" autocapitalize="off">
',(support("materializedview")?" ".checkbox("materialized",1,$I["materialized"],'Materialized view'):""),'<p>';textarea("select",$I["select"]);echo'<p>
<input type="submit" value="Save">
';if($a!=""){echo'<input type="submit" name="drop" value="Drop">',confirm(sprintf('Drop %s?',$a));}echo'<input type="hidden" name="token" value="',$qi,'">
</form>
';}elseif(isset($_GET["event"])){$aa=$_GET["event"];$Wd=array("YEAR","QUARTER","MONTH","DAY","HOUR","MINUTE","WEEK","SECOND","YEAR_MONTH","DAY_HOUR","DAY_MINUTE","DAY_SECOND","HOUR_MINUTE","HOUR_SECOND","MINUTE_SECOND");$Hh=array("ENABLED"=>"ENABLE","DISABLED"=>"DISABLE","SLAVESIDE_DISABLED"=>"DISABLE ON SLAVE");$I=$_POST;if($_POST&&!$n){if($_POST["drop"])query_redirect("DROP EVENT ".idf_escape($aa),substr(ME,0,-1),'Event has been dropped.');elseif(in_array($I["INTERVAL_FIELD"],$Wd)&&isset($Hh[$I["STATUS"]])){$bh="\nON SCHEDULE ".($I["INTERVAL_VALUE"]?"EVERY ".q($I["INTERVAL_VALUE"])." $I[INTERVAL_FIELD]".($I["STARTS"]?" STARTS ".q($I["STARTS"]):"").($I["ENDS"]?" ENDS ".q($I["ENDS"]):""):"AT ".q($I["STARTS"]))." ON COMPLETION".($I["ON_COMPLETION"]?"":" NOT")." PRESERVE";queries_redirect(substr(ME,0,-1),($aa!=""?'Event has been altered.':'Event has been created.'),queries(($aa!=""?"ALTER EVENT ".idf_escape($aa).$bh.($aa!=$I["EVENT_NAME"]?"\nRENAME TO ".idf_escape($I["EVENT_NAME"]):""):"CREATE EVENT ".idf_escape($I["EVENT_NAME"]).$bh)."\n".$Hh[$I["STATUS"]]." COMMENT ".q($I["EVENT_COMMENT"]).rtrim(" DO\n$I[EVENT_DEFINITION]",";").";"));}}page_header(($aa!=""?'Alter event'.": ".h($aa):'Create event'),$n);if(!$I&&$aa!=""){$J=get_rows("SELECT * FROM information_schema.EVENTS WHERE EVENT_SCHEMA = ".q(DB)." AND EVENT_NAME = ".q($aa));$I=reset($J);}echo'
<form action="" method="post">
<table cellspacing="0" class="layout">
<tr><th>Name<td><input name="EVENT_NAME" value="',h($I["EVENT_NAME"]),'" data-maxlength="64" autocapitalize="off">
<tr><th title="datetime">Start<td><input name="STARTS" value="',h("$I[EXECUTE_AT]$I[STARTS]"),'">
<tr><th title="datetime">End<td><input name="ENDS" value="',h($I["ENDS"]),'">
<tr><th>Every<td><input type="number" name="INTERVAL_VALUE" value="',h($I["INTERVAL_VALUE"]),'" class="size"> ',html_select("INTERVAL_FIELD",$Wd,$I["INTERVAL_FIELD"]),'<tr><th>Status<td>',html_select("STATUS",$Hh,$I["STATUS"]),'<tr><th>Comment<td><input name="EVENT_COMMENT" value="',h($I["EVENT_COMMENT"]),'" data-maxlength="64">
<tr><th><td>',checkbox("ON_COMPLETION","PRESERVE",$I["ON_COMPLETION"]=="PRESERVE",'On completion preserve'),'</table>
<p>';textarea("EVENT_DEFINITION",$I["EVENT_DEFINITION"]);echo'<p>
<input type="submit" value="Save">
';if($aa!=""){echo'<input type="submit" name="drop" value="Drop">',confirm(sprintf('Drop %s?',$aa));}echo'<input type="hidden" name="token" value="',$qi,'">
</form>
';}elseif(isset($_GET["procedure"])){$da=($_GET["name"]?$_GET["name"]:$_GET["procedure"]);$Wg=(isset($_GET["function"])?"FUNCTION":"PROCEDURE");$I=$_POST;$I["fields"]=(array)$I["fields"];if($_POST&&!process_fields($I["fields"])&&!$n){$Ff=routine($_GET["procedure"],$Wg);$bi="$I[name]_adminer_".uniqid();drop_create("DROP $Wg ".routine_id($da,$Ff),create_routine($Wg,$I),"DROP $Wg ".routine_id($I["name"],$I),create_routine($Wg,array("name"=>$bi)+$I),"DROP $Wg ".routine_id($bi,$I),substr(ME,0,-1),'Routine has been dropped.','Routine has been altered.','Routine has been created.',$da,$I["name"]);}page_header(($da!=""?(isset($_GET["function"])?'Alter function':'Alter procedure').": ".h($da):(isset($_GET["function"])?'Create function':'Create procedure')),$n);if(!$_POST&&$da!=""){$I=routine($_GET["procedure"],$Wg);$I["name"]=$da;}$pb=get_vals("SHOW CHARACTER SET");sort($pb);$Xg=routine_languages();echo'
<form action="" method="post" id="form">
<p>Name: <input name="name" value="',h($I["name"]),'" data-maxlength="64" autocapitalize="off">
',($Xg?'Language'.": ".html_select("language",$Xg,$I["language"])."\n":""),'<input type="submit" value="Save">
<div class="scrollable">
<table cellspacing="0" class="nowrap">
';edit_fields($I["fields"],$pb,$Wg);if(isset($_GET["function"])){echo"<tr><td>".'Return type';edit_type("returns",$I["returns"],$pb,array(),($x=="pgsql"?array("void","trigger"):array()));}echo'</table>
',script("editFields();"),'</div>
<p>';textarea("definition",$I["definition"]);echo'<p>
<input type="submit" value="Save">
';if($da!=""){echo'<input type="submit" name="drop" value="Drop">',confirm(sprintf('Drop %s?',$da));}echo'<input type="hidden" name="token" value="',$qi,'">
</form>
';}elseif(isset($_GET["sequence"])){$fa=$_GET["sequence"];$I=$_POST;if($_POST&&!$n){$_=substr(ME,0,-1);$B=trim($I["name"]);if($_POST["drop"])query_redirect("DROP SEQUENCE ".idf_escape($fa),$_,'Sequence has been dropped.');elseif($fa=="")query_redirect("CREATE SEQUENCE ".idf_escape($B),$_,'Sequence has been created.');elseif($fa!=$B)query_redirect("ALTER SEQUENCE ".idf_escape($fa)." RENAME TO ".idf_escape($B),$_,'Sequence has been altered.');else
redirect($_);}page_header($fa!=""?'Alter sequence'.": ".h($fa):'Create sequence',$n);if(!$I)$I["name"]=$fa;echo'
<form action="" method="post">
<p><input name="name" value="',h($I["name"]),'" autocapitalize="off">
<input type="submit" value="Save">
';if($fa!="")echo"<input type='submit' name='drop' value='".'Drop'."'>".confirm(sprintf('Drop %s?',$fa))."\n";echo'<input type="hidden" name="token" value="',$qi,'">
</form>
';}elseif(isset($_GET["type"])){$ga=$_GET["type"];$I=$_POST;if($_POST&&!$n){$_=substr(ME,0,-1);if($_POST["drop"])query_redirect("DROP TYPE ".idf_escape($ga),$_,'Type has been dropped.');else
query_redirect("CREATE TYPE ".idf_escape(trim($I["name"]))." $I[as]",$_,'Type has been created.');}page_header($ga!=""?'Alter type'.": ".h($ga):'Create type',$n);if(!$I)$I["as"]="AS ";echo'
<form action="" method="post">
<p>
';if($ga!="")echo"<input type='submit' name='drop' value='".'Drop'."'>".confirm(sprintf('Drop %s?',$ga))."\n";else{echo"<input name='name' value='".h($I['name'])."' autocapitalize='off'>\n";textarea("as",$I["as"]);echo"<p><input type='submit' value='".'Save'."'>\n";}echo'<input type="hidden" name="token" value="',$qi,'">
</form>
';}elseif(isset($_GET["trigger"])){$a=$_GET["trigger"];$B=$_GET["name"];$Ai=trigger_options();$I=(array)trigger($B)+array("Trigger"=>$a."_bi");if($_POST){if(!$n&&in_array($_POST["Timing"],$Ai["Timing"])&&in_array($_POST["Event"],$Ai["Event"])&&in_array($_POST["Type"],$Ai["Type"])){$rf=" ON ".table($a);$hc="DROP TRIGGER ".idf_escape($B).($x=="pgsql"?$rf:"");$ze=ME."table=".urlencode($a);if($_POST["drop"])query_redirect($hc,$ze,'Trigger has been dropped.');else{if($B!="")queries($hc);queries_redirect($ze,($B!=""?'Trigger has been altered.':'Trigger has been created.'),queries(create_trigger($rf,$_POST)));if($B!="")queries(create_trigger($rf,$I+array("Type"=>reset($Ai["Type"]))));}}$I=$_POST;}page_header(($B!=""?'Alter trigger'.": ".h($B):'Create trigger'),$n,array("table"=>$a));echo'
<form action="" method="post" id="form">
<table cellspacing="0" class="layout">
<tr><th>Time<td>',html_select("Timing",$Ai["Timing"],$I["Timing"],"triggerChange(/^".preg_quote($a,"/")."_[ba][iud]$/, '".js_escape($a)."', this.form);"),'<tr><th>Event<td>',html_select("Event",$Ai["Event"],$I["Event"],"this.form['Timing'].onchange();"),(in_array("UPDATE OF",$Ai["Event"])?" <input name='Of' value='".h($I["Of"])."' class='hidden'>":""),'<tr><th>Type<td>',html_select("Type",$Ai["Type"],$I["Type"]),'</table>
<p>Name: <input name="Trigger" value="',h($I["Trigger"]),'" data-maxlength="64" autocapitalize="off">
',script("qs('#form')['Timing'].onchange();"),'<p>';textarea("Statement",$I["Statement"]);echo'<p>
<input type="submit" value="Save">
';if($B!=""){echo'<input type="submit" name="drop" value="Drop">',confirm(sprintf('Drop %s?',$B));}echo'<input type="hidden" name="token" value="',$qi,'">
</form>
';}elseif(isset($_GET["user"])){$ha=$_GET["user"];$rg=array(""=>array("All privileges"=>""));foreach(get_rows("SHOW PRIVILEGES")as$I){foreach(explode(",",($I["Privilege"]=="Grant option"?"":$I["Context"]))as$Cb)$rg[$Cb][$I["Privilege"]]=$I["Comment"];}$rg["Server Admin"]+=$rg["File access on server"];$rg["Databases"]["Create routine"]=$rg["Procedures"]["Create routine"];unset($rg["Procedures"]["Create routine"]);$rg["Columns"]=array();foreach(array("Select","Insert","Update","References")as$X)$rg["Columns"][$X]=$rg["Tables"][$X];unset($rg["Server Admin"]["Usage"]);foreach($rg["Tables"]as$y=>$X)unset($rg["Databases"][$y]);$af=array();if($_POST){foreach($_POST["objects"]as$y=>$X)$af[$X]=(array)$af[$X]+(array)$_POST["grants"][$y];}$od=array();$pf="";if(isset($_GET["host"])&&($G=$g->query("SHOW GRANTS FOR ".q($ha)."@".q($_GET["host"])))){while($I=$G->fetch_row()){if(preg_match('~GRANT (.*) ON (.*) TO ~',$I[0],$A)&&preg_match_all('~ *([^(,]*[^ ,(])( *\([^)]+\))?~',$A[1],$Fe,PREG_SET_ORDER)){foreach($Fe
as$X){if($X[1]!="USAGE")$od["$A[2]$X[2]"][$X[1]]=true;if(preg_match('~ WITH GRANT OPTION~',$I[0]))$od["$A[2]$X[2]"]["GRANT OPTION"]=true;}}if(preg_match("~ IDENTIFIED BY PASSWORD '([^']+)~",$I[0],$A))$pf=$A[1];}}if($_POST&&!$n){$qf=(isset($_GET["host"])?q($ha)."@".q($_GET["host"]):"''");if($_POST["drop"])query_redirect("DROP USER $qf",ME."privileges=",'User has been dropped.');else{$cf=q($_POST["user"])."@".q($_POST["host"]);$Zf=$_POST["pass"];if($Zf!=''&&!$_POST["hashed"]&&!min_version(8)){$Zf=$g->result("SELECT PASSWORD(".q($Zf).")");$n=!$Zf;}$Hb=false;if(!$n){if($qf!=$cf){$Hb=queries((min_version(5)?"CREATE USER":"GRANT USAGE ON *.* TO")." $cf IDENTIFIED BY ".(min_version(8)?"":"PASSWORD ").q($Zf));$n=!$Hb;}elseif($Zf!=$pf)queries("SET PASSWORD FOR $cf = ".q($Zf));}if(!$n){$Tg=array();foreach($af
as$kf=>$nd){if(isset($_GET["grant"]))$nd=array_filter($nd);$nd=array_keys($nd);if(isset($_GET["grant"]))$Tg=array_diff(array_keys(array_filter($af[$kf],'strlen')),$nd);elseif($qf==$cf){$nf=array_keys((array)$od[$kf]);$Tg=array_diff($nf,$nd);$nd=array_diff($nd,$nf);unset($od[$kf]);}if(preg_match('~^(.+)\s*(\(.*\))?$~U',$kf,$A)&&(!grant("REVOKE",$Tg,$A[2]," ON $A[1] FROM $cf")||!grant("GRANT",$nd,$A[2]," ON $A[1] TO $cf"))){$n=true;break;}}}if(!$n&&isset($_GET["host"])){if($qf!=$cf)queries("DROP USER $qf");elseif(!isset($_GET["grant"])){foreach($od
as$kf=>$Tg){if(preg_match('~^(.+)(\(.*\))?$~U',$kf,$A))grant("REVOKE",array_keys($Tg),$A[2]," ON $A[1] FROM $cf");}}}queries_redirect(ME."privileges=",(isset($_GET["host"])?'User has been altered.':'User has been created.'),!$n);if($Hb)$g->query("DROP USER $cf");}}page_header((isset($_GET["host"])?'Username'.": ".h("$ha@$_GET[host]"):'Create user'),$n,array("privileges"=>array('','Privileges')));if($_POST){$I=$_POST;$od=$af;}else{$I=$_GET+array("host"=>$g->result("SELECT SUBSTRING_INDEX(CURRENT_USER, '@', -1)"));$I["pass"]=$pf;if($pf!="")$I["hashed"]=true;$od[(DB==""||$od?"":idf_escape(addcslashes(DB,"%_\\"))).".*"]=array();}echo'<form action="" method="post">
<table cellspacing="0" class="layout">
<tr><th>Server<td><input name="host" data-maxlength="60" value="',h($I["host"]),'" autocapitalize="off">
<tr><th>Username<td><input name="user" data-maxlength="80" value="',h($I["user"]),'" autocapitalize="off">
<tr><th>Password<td><input name="pass" id="pass" value="',h($I["pass"]),'" autocomplete="new-password">
';if(!$I["hashed"])echo
script("typePassword(qs('#pass'));");echo(min_version(8)?"":checkbox("hashed",1,$I["hashed"],'Hashed',"typePassword(this.form['pass'], this.checked);")),'</table>

';echo"<table cellspacing='0'>\n","<thead><tr><th colspan='2'>".'Privileges'.doc_link(array('sql'=>"grant.html#priv_level"));$s=0;foreach($od
as$kf=>$nd){echo'<th>'.($kf!="*.*"?"<input name='objects[$s]' value='".h($kf)."' size='10' autocapitalize='off'>":"<input type='hidden' name='objects[$s]' value='*.*' size='10'>*.*");$s++;}echo"</thead>\n";foreach(array(""=>"","Server Admin"=>'Server',"Databases"=>'Database',"Tables"=>'Table',"Columns"=>'Column',"Procedures"=>'Routine',)as$Cb=>$Zb){foreach((array)$rg[$Cb]as$qg=>$ub){echo"<tr".odd()."><td".($Zb?">$Zb<td":" colspan='2'").' lang="en" title="'.h($ub).'">'.h($qg);$s=0;foreach($od
as$kf=>$nd){$B="'grants[$s][".h(strtoupper($qg))."]'";$Y=$nd[strtoupper($qg)];if($Cb=="Server Admin"&&$kf!=(isset($od["*.*"])?"*.*":".*"))echo"<td>";elseif(isset($_GET["grant"]))echo"<td><select name=$B><option><option value='1'".($Y?" selected":"").">".'Grant'."<option value='0'".($Y=="0"?" selected":"").">".'Revoke'."</select>";else{echo"<td align='center'><label class='block'>","<input type='checkbox' name=$B value='1'".($Y?" checked":"").($qg=="All privileges"?" id='grants-$s-all'>":">".($qg=="Grant option"?"":script("qsl('input').onclick = function () { if (this.checked) formUncheck('grants-$s-all'); };"))),"</label>";}$s++;}}}echo"</table>\n",'<p>
<input type="submit" value="Save">
';if(isset($_GET["host"])){echo'<input type="submit" name="drop" value="Drop">',confirm(sprintf('Drop %s?',"$ha@$_GET[host]"));}echo'<input type="hidden" name="token" value="',$qi,'">
</form>
';}elseif(isset($_GET["processlist"])){if(support("kill")&&$_POST&&!$n){$le=0;foreach((array)$_POST["kill"]as$X){if(kill_process($X))$le++;}queries_redirect(ME."processlist=",lang(array('%d process has been killed.','%d processes have been killed.'),$le),$le||!$_POST["kill"]);}page_header('Process list',$n);echo'
<form action="" method="post">
<div class="scrollable">
<table cellspacing="0" class="nowrap checkable">
',script("mixin(qsl('table'), {onclick: tableClick, ondblclick: partialArg(tableClick, true)});");$s=-1;foreach(process_list()as$s=>$I){if(!$s){echo"<thead><tr lang='en'>".(support("kill")?"<th>":"");foreach($I
as$y=>$X)echo"<th>$y".doc_link(array('sql'=>"show-processlist.html#processlist_".strtolower($y),'pgsql'=>"monitoring-stats.html#PG-STAT-ACTIVITY-VIEW",'oracle'=>"REFRN30223",));echo"</thead>\n";}echo"<tr".odd().">".(support("kill")?"<td>".checkbox("kill[]",$I[$x=="sql"?"Id":"pid"],0):"");foreach($I
as$y=>$X)echo"<td>".(($x=="sql"&&$y=="Info"&&preg_match("~Query|Killed~",$I["Command"])&&$X!="")||($x=="pgsql"&&$y=="current_query"&&$X!="<IDLE>")||($x=="oracle"&&$y=="sql_text"&&$X!="")?"<code class='jush-$x'>".shorten_utf8($X,100,"</code>").' <a href="'.h(ME.($I["db"]!=""?"db=".urlencode($I["db"])."&":"")."sql=".urlencode($X)).'">'.'Clone'.'</a>':h($X));echo"\n";}echo'</table>
</div>
<p>
';if(support("kill")){echo($s+1)."/".sprintf('%d in total',max_connections()),"<p><input type='submit' value='".'Kill'."'>\n";}echo'<input type="hidden" name="token" value="',$qi,'">
</form>
',script("tableCheck();");}elseif(isset($_GET["select"])){$a=$_GET["select"];$R=table_status1($a);$w=indexes($a);$p=fields($a);$gd=column_foreign_keys($a);$mf=$R["Oid"];parse_str($_COOKIE["adminer_import"],$ya);$Ug=array();$f=array();$fi=null;foreach($p
as$y=>$o){$B=$b->fieldName($o);if(isset($o["privileges"]["select"])&&$B!=""){$f[$y]=html_entity_decode(strip_tags($B),ENT_QUOTES);if(is_shortable($o))$fi=$b->selectLengthProcess();}$Ug+=$o["privileges"];}list($K,$pd)=$b->selectColumnsProcess($f,$w);$ae=count($pd)<count($K);$Z=$b->selectSearchProcess($p,$w);$Bf=$b->selectOrderProcess($p,$w);$z=$b->selectLimitProcess();if($_GET["val"]&&is_ajax()){header("Content-Type: text/plain; charset=utf-8");foreach($_GET["val"]as$Hi=>$I){$Ga=convert_field($p[key($I)]);$K=array($Ga?$Ga:idf_escape(key($I)));$Z[]=where_check($Hi,$p);$H=$m->select($a,$K,$Z,$K);if($H)echo
reset($H->fetch_row());}exit;}$mg=$Ji=null;foreach($w
as$v){if($v["type"]=="PRIMARY"){$mg=array_flip($v["columns"]);$Ji=($K?$mg:array());foreach($Ji
as$y=>$X){if(in_array(idf_escape($y),$K))unset($Ji[$y]);}break;}}if($mf&&!$mg){$mg=$Ji=array($mf=>0);$w[]=array("type"=>"PRIMARY","columns"=>array($mf));}if($_POST&&!$n){$lj=$Z;if(!$_POST["all"]&&is_array($_POST["check"])){$gb=array();foreach($_POST["check"]as$db)$gb[]=where_check($db,$p);$lj[]="((".implode(") OR (",$gb)."))";}$lj=($lj?"\nWHERE ".implode(" AND ",$lj):"");if($_POST["export"]){cookie("adminer_import","output=".urlencode($_POST["output"])."&format=".urlencode($_POST["format"]));dump_headers($a);$b->dumpTable($a,"");$ld=($K?implode(", ",$K):"*").convert_fields($f,$p,$K)."\nFROM ".table($a);$rd=($pd&&$ae?"\nGROUP BY ".implode(", ",$pd):"").($Bf?"\nORDER BY ".implode(", ",$Bf):"");if(!is_array($_POST["check"])||$mg)$F="SELECT $ld$lj$rd";else{$Fi=array();foreach($_POST["check"]as$X)$Fi[]="(SELECT".limit($ld,"\nWHERE ".($Z?implode(" AND ",$Z)." AND ":"").where_check($X,$p).$rd,1).")";$F=implode(" UNION ALL ",$Fi);}$b->dumpData($a,"table",$F);exit;}if(!$b->selectEmailProcess($Z,$gd)){if($_POST["save"]||$_POST["delete"]){$G=true;$za=0;$N=array();if(!$_POST["delete"]){foreach($f
as$B=>$X){$X=process_input($p[$B]);if($X!==null&&($_POST["clone"]||$X!==false))$N[idf_escape($B)]=($X!==false?$X:idf_escape($B));}}if($_POST["delete"]||$N){if($_POST["clone"])$F="INTO ".table($a)." (".implode(", ",array_keys($N)).")\nSELECT ".implode(", ",$N)."\nFROM ".table($a);if($_POST["all"]||($mg&&is_array($_POST["check"]))||$ae){$G=($_POST["delete"]?$m->delete($a,$lj):($_POST["clone"]?queries("INSERT $F$lj"):$m->update($a,$N,$lj)));$za=$g->affected_rows;}else{foreach((array)$_POST["check"]as$X){$hj="\nWHERE ".($Z?implode(" AND ",$Z)." AND ":"").where_check($X,$p);$G=($_POST["delete"]?$m->delete($a,$hj,1):($_POST["clone"]?queries("INSERT".limit1($a,$F,$hj)):$m->update($a,$N,$hj,1)));if(!$G)break;$za+=$g->affected_rows;}}}$Ne=lang(array('%d item has been affected.','%d items have been affected.'),$za);if($_POST["clone"]&&$G&&$za==1){$qe=last_id();if($qe)$Ne=sprintf('Item%s has been inserted.'," $qe");}queries_redirect(remove_from_uri($_POST["all"]&&$_POST["delete"]?"page":""),$Ne,$G);if(!$_POST["delete"]){edit_form($a,$p,(array)$_POST["fields"],!$_POST["clone"]);page_footer();exit;}}elseif(!$_POST["import"]){if(!$_POST["val"])$n='Ctrl+click on a value to modify it.';else{$G=true;$za=0;foreach($_POST["val"]as$Hi=>$I){$N=array();foreach($I
as$y=>$X){$y=bracket_escape($y,1);$N[idf_escape($y)]=(preg_match('~char|text~',$p[$y]["type"])||$X!=""?$b->processInput($p[$y],$X):"NULL");}$G=$m->update($a,$N," WHERE ".($Z?implode(" AND ",$Z)." AND ":"").where_check($Hi,$p),!$ae&&!$mg," ");if(!$G)break;$za+=$g->affected_rows;}queries_redirect(remove_from_uri(),lang(array('%d item has been affected.','%d items have been affected.'),$za),$G);}}elseif(!is_string($Vc=get_file("csv_file",true)))$n=upload_error($Vc);elseif(!preg_match('~~u',$Vc))$n='File must be in UTF-8 encoding.';else{cookie("adminer_import","output=".urlencode($ya["output"])."&format=".urlencode($_POST["separator"]));$G=true;$rb=array_keys($p);preg_match_all('~(?>"[^"]*"|[^"\r\n]+)+~',$Vc,$Fe);$za=count($Fe[0]);$m->begin();$L=($_POST["separator"]=="csv"?",":($_POST["separator"]=="tsv"?"\t":";"));$J=array();foreach($Fe[0]as$y=>$X){preg_match_all("~((?>\"[^\"]*\")+|[^$L]*)$L~",$X.$L,$Ge);if(!$y&&!array_diff($Ge[1],$rb)){$rb=$Ge[1];$za--;}else{$N=array();foreach($Ge[1]as$s=>$nb)$N[idf_escape($rb[$s])]=($nb==""&&$p[$rb[$s]]["null"]?"NULL":q(str_replace('""','"',preg_replace('~^"|"$~','',$nb))));$J[]=$N;}}$G=(!$J||$m->insertUpdate($a,$J,$mg));if($G)$G=$m->commit();queries_redirect(remove_from_uri("page"),lang(array('%d row has been imported.','%d rows have been imported.'),$za),$G);$m->rollback();}}}$Rh=$b->tableName($R);if(is_ajax()){page_headers();ob_start();}else
page_header('Select'.": $Rh",$n);$N=null;if(isset($Ug["insert"])||!support("table")){$N="";foreach((array)$_GET["where"]as$X){if($gd[$X["col"]]&&count($gd[$X["col"]])==1&&($X["op"]=="="||(!$X["op"]&&!preg_match('~[_%]~',$X["val"]))))$N.="&set".urlencode("[".bracket_escape($X["col"])."]")."=".urlencode($X["val"]);}}$b->selectLinks($R,$N);if(!$f&&support("table"))echo"<p class='error'>".'Unable to select the table'.($p?".":": ".error())."\n";else{echo"<form action='' id='form'>\n","<div style='display: none;'>";hidden_fields_get();echo(DB!=""?'<input type="hidden" name="db" value="'.h(DB).'">'.(isset($_GET["ns"])?'<input type="hidden" name="ns" value="'.h($_GET["ns"]).'">':""):"");echo'<input type="hidden" name="select" value="'.h($a).'">',"</div>\n";$b->selectColumnsPrint($K,$f);$b->selectSearchPrint($Z,$f,$w);$b->selectOrderPrint($Bf,$f,$w);$b->selectLimitPrint($z);$b->selectLengthPrint($fi);$b->selectActionPrint($w);echo"</form>\n";$D=$_GET["page"];if($D=="last"){$jd=$g->result(count_rows($a,$Z,$ae,$pd));$D=floor(max(0,$jd-1)/$z);}$gh=$K;$qd=$pd;if(!$gh){$gh[]="*";$Db=convert_fields($f,$p,$K);if($Db)$gh[]=substr($Db,2);}foreach($K
as$y=>$X){$o=$p[idf_unescape($X)];if($o&&($Ga=convert_field($o)))$gh[$y]="$Ga AS $X";}if(!$ae&&$Ji){foreach($Ji
as$y=>$X){$gh[]=idf_escape($y);if($qd)$qd[]=idf_escape($y);}}$G=$m->select($a,$gh,$Z,$qd,$Bf,$z,$D,true);if(!$G)echo"<p class='error'>".error()."\n";else{if($x=="mssql"&&$D)$G->seek($z*$D);$uc=array();echo"<form action='' method='post' enctype='multipart/form-data'>\n";$J=array();while($I=$G->fetch_assoc()){if($D&&$x=="oracle")unset($I["RNUM"]);$J[]=$I;}if($_GET["page"]!="last"&&$z!=""&&$pd&&$ae&&$x=="sql")$jd=$g->result(" SELECT FOUND_ROWS()");if(!$J)echo"<p class='message'>".'No rows.'."\n";else{$Qa=$b->backwardKeys($a,$Rh);echo"<div class='scrollable'>","<table id='table' cellspacing='0' class='nowrap checkable'>",script("mixin(qs('#table'), {onclick: tableClick, ondblclick: partialArg(tableClick, true), onkeydown: editingKeydown});"),"<thead><tr>".(!$pd&&$K?"":"<td><input type='checkbox' id='all-page' class='jsonly'>".script("qs('#all-page').onclick = partial(formCheck, /check/);","")." <a href='".h($_GET["modify"]?remove_from_uri("modify"):$_SERVER["REQUEST_URI"]."&modify=1")."'>".'Modify'."</a>");$Ze=array();$md=array();reset($K);$Ag=1;foreach($J[0]as$y=>$X){if(!isset($Ji[$y])){$X=$_GET["columns"][key($K)];$o=$p[$K?($X?$X["col"]:current($K)):$y];$B=($o?$b->fieldName($o,$Ag):($X["fun"]?"*":$y));if($B!=""){$Ag++;$Ze[$y]=$B;$e=idf_escape($y);$Dd=remove_from_uri('(order|desc)[^=]*|page').'&order%5B0%5D='.urlencode($y);$Zb="&desc%5B0%5D=1";echo"<th>".script("mixin(qsl('th'), {onmouseover: partial(columnMouse), onmouseout: partial(columnMouse, ' hidden')});",""),'<a href="'.h($Dd.($Bf[0]==$e||$Bf[0]==$y||(!$Bf&&$ae&&$pd[0]==$e)?$Zb:'')).'">';echo
apply_sql_function($X["fun"],$B)."</a>";echo"<span class='column hidden'>","<a href='".h($Dd.$Zb)."' title='".'descending'."' class='text'> â</a>";if(!$X["fun"]){echo'<a href="#fieldset-search" title="'.'Search'.'" class="text jsonly"> =</a>',script("qsl('a').onclick = partial(selectSearch, '".js_escape($y)."');");}echo"</span>";}$md[$y]=$X["fun"];next($K);}}$we=array();if($_GET["modify"]){foreach($J
as$I){foreach($I
as$y=>$X)$we[$y]=max($we[$y],min(40,strlen(utf8_decode($X))));}}echo($Qa?"<th>".'Relations':"")."</thead>\n";if(is_ajax()){if($z%2==1&&$D%2==1)odd();ob_end_clean();}foreach($b->rowDescriptions($J,$gd)as$Ye=>$I){$Gi=unique_array($J[$Ye],$w);if(!$Gi){$Gi=array();foreach($J[$Ye]as$y=>$X){if(!preg_match('~^(COUNT\((\*|(DISTINCT )?`(?:[^`]|``)+`)\)|(AVG|GROUP_CONCAT|MAX|MIN|SUM)\(`(?:[^`]|``)+`\))$~',$y))$Gi[$y]=$X;}}$Hi="";foreach($Gi
as$y=>$X){if(($x=="sql"||$x=="pgsql")&&preg_match('~char|text|enum|set~',$p[$y]["type"])&&strlen($X)>64){$y=(strpos($y,'(')?$y:idf_escape($y));$y="MD5(".($x!='sql'||preg_match("~^utf8~",$p[$y]["collation"])?$y:"CONVERT($y USING ".charset($g).")").")";$X=md5($X);}$Hi.="&".($X!==null?urlencode("where[".bracket_escape($y)."]")."=".urlencode($X):"null%5B%5D=".urlencode($y));}echo"<tr".odd().">".(!$pd&&$K?"":"<td>".checkbox("check[]",substr($Hi,1),in_array(substr($Hi,1),(array)$_POST["check"])).($ae||information_schema(DB)?"":" <a href='".h(ME."edit=".urlencode($a).$Hi)."' class='edit'>".'edit'."</a>"));foreach($I
as$y=>$X){if(isset($Ze[$y])){$o=$p[$y];$X=$m->value($X,$o);if($X!=""&&(!isset($uc[$y])||$uc[$y]!=""))$uc[$y]=(is_mail($X)?$Ze[$y]:"");$_="";if(preg_match('~blob|bytea|raw|file~',$o["type"])&&$X!="")$_=ME.'download='.urlencode($a).'&field='.urlencode($y).$Hi;if(!$_&&$X!==null){foreach((array)$gd[$y]as$q){if(count($gd[$y])==1||end($q["source"])==$y){$_="";foreach($q["source"]as$s=>$yh)$_.=where_link($s,$q["target"][$s],$J[$Ye][$yh]);$_=($q["db"]!=""?preg_replace('~([?&]db=)[^&]+~','\1'.urlencode($q["db"]),ME):ME).'select='.urlencode($q["table"]).$_;if($q["ns"])$_=preg_replace('~([?&]ns=)[^&]+~','\1'.urlencode($q["ns"]),$_);if(count($q["source"])==1)break;}}}if($y=="COUNT(*)"){$_=ME."select=".urlencode($a);$s=0;foreach((array)$_GET["where"]as$W){if(!array_key_exists($W["col"],$Gi))$_.=where_link($s++,$W["col"],$W["val"],$W["op"]);}foreach($Gi
as$fe=>$W)$_.=where_link($s++,$fe,$W);}$X=select_value($X,$_,$o,$fi);$t=h("val[$Hi][".bracket_escape($y)."]");$Y=$_POST["val"][$Hi][bracket_escape($y)];$pc=!is_array($I[$y])&&is_utf8($X)&&$J[$Ye][$y]==$I[$y]&&!$md[$y];$ei=preg_match('~text|lob~',$o["type"]);echo"<td id='$t'";if(($_GET["modify"]&&$pc)||$Y!==null){$ud=h($Y!==null?$Y:$I[$y]);echo">".($ei?"<textarea name='$t' cols='30' rows='".(substr_count($I[$y],"\n")+1)."'>$ud</textarea>":"<input name='$t' value='$ud' size='$we[$y]'>");}else{$Ae=strpos($X,"<i>â¦</i>");echo" data-text='".($Ae?2:($ei?1:0))."'".($pc?"":" data-warning='".h('Use edit link to modify this value.')."'").">$X</td>";}}}if($Qa)echo"<td>";$b->backwardKeysPrint($Qa,$J[$Ye]);echo"</tr>\n";}if(is_ajax())exit;echo"</table>\n","</div>\n";}if(!is_ajax()){if($J||$D){$Dc=true;if($_GET["page"]!="last"){if($z==""||(count($J)<$z&&($J||!$D)))$jd=($D?$D*$z:0)+count($J);elseif($x!="sql"||!$ae){$jd=($ae?false:found_rows($R,$Z));if($jd<max(1e4,2*($D+1)*$z))$jd=reset(slow_query(count_rows($a,$Z,$ae,$pd)));else$Dc=false;}}$Of=($z!=""&&($jd===false||$jd>$z||$D));if($Of){echo(($jd===false?count($J)+1:$jd-$D*$z)>$z?'<p><a href="'.h(remove_from_uri("page")."&page=".($D+1)).'" class="loadmore">'.'Load more data'.'</a>'.script("qsl('a').onclick = partial(selectLoadMore, ".(+$z).", '".'Loading'."â¦');",""):''),"\n";}}echo"<div class='footer'><div>\n";if($J||$D){if($Of){$Ie=($jd===false?$D+(count($J)>=$z?2:1):floor(($jd-1)/$z));echo"<fieldset>";if($x!="simpledb"){echo"<legend><a href='".h(remove_from_uri("page"))."'>".'Page'."</a></legend>",script("qsl('a').onclick = function () { pageClick(this.href, +prompt('".'Page'."', '".($D+1)."')); return false; };"),pagination(0,$D).($D>5?" â¦":"");for($s=max(1,$D-4);$s<min($Ie,$D+5);$s++)echo
pagination($s,$D);if($Ie>0){echo($D+5<$Ie?" â¦":""),($Dc&&$jd!==false?pagination($Ie,$D):" <a href='".h(remove_from_uri("page")."&page=last")."' title='~$Ie'>".'last'."</a>");}}else{echo"<legend>".'Page'."</legend>",pagination(0,$D).($D>1?" â¦":""),($D?pagination($D,$D):""),($Ie>$D?pagination($D+1,$D).($Ie>$D+1?" â¦":""):"");}echo"</fieldset>\n";}echo"<fieldset>","<legend>".'Whole result'."</legend>";$ec=($Dc?"":"~ ").$jd;echo
checkbox("all",1,0,($jd!==false?($Dc?"":"~ ").lang(array('%d row','%d rows'),$jd):""),"var checked = formChecked(this, /check/); selectCount('selected', this.checked ? '$ec' : checked); selectCount('selected2', this.checked || !checked ? '$ec' : checked);")."\n","</fieldset>\n";if($b->selectCommandPrint()){echo'<fieldset',($_GET["modify"]?'':' class="jsonly"'),'><legend>Modify</legend><div>
<input type="submit" value="Save"',($_GET["modify"]?'':' title="'.'Ctrl+click on a value to modify it.'.'"'),'>
</div></fieldset>
<fieldset><legend>Selected <span id="selected"></span></legend><div>
<input type="submit" name="edit" value="Edit">
<input type="submit" name="clone" value="Clone">
<input type="submit" name="delete" value="Delete">',confirm(),'</div></fieldset>
';}$hd=$b->dumpFormat();foreach((array)$_GET["columns"]as$e){if($e["fun"]){unset($hd['sql']);break;}}if($hd){print_fieldset("export",'Export'." <span id='selected2'></span>");$Mf=$b->dumpOutput();echo($Mf?html_select("output",$Mf,$ya["output"])." ":""),html_select("format",$hd,$ya["format"])," <input type='submit' name='export' value='".'Export'."'>\n","</div></fieldset>\n";}$b->selectEmailPrint(array_filter($uc,'strlen'),$f);}echo"</div></div>\n";if($b->selectImportPrint()){echo"<div>","<a href='#import'>".'Import'."</a>",script("qsl('a').onclick = partial(toggle, 'import');",""),"<span id='import' class='hidden'>: ","<input type='file' name='csv_file'> ",html_select("separator",array("csv"=>"CSV,","csv;"=>"CSV;","tsv"=>"TSV"),$ya["format"],1);echo" <input type='submit' name='import' value='".'Import'."'>","</span>","</div>";}echo"<input type='hidden' name='token' value='$qi'>\n","</form>\n",(!$pd&&$K?"":script("tableCheck();"));}}}if(is_ajax()){ob_end_clean();exit;}}elseif(isset($_GET["variables"])){$O=isset($_GET["status"]);page_header($O?'Status':'Variables');$Yi=($O?show_status():show_variables());if(!$Yi)echo"<p class='message'>".'No rows.'."\n";else{echo"<table cellspacing='0'>\n";foreach($Yi
as$y=>$X){echo"<tr>","<th><code class='jush-".$x.($O?"status":"set")."'>".h($y)."</code>","<td>".h($X);}echo"</table>\n";}}elseif(isset($_GET["script"])){header("Content-Type: text/javascript; charset=utf-8");if($_GET["script"]=="db"){$Oh=array("Data_length"=>0,"Index_length"=>0,"Data_free"=>0);foreach(table_status()as$B=>$R){json_row("Comment-$B",h($R["Comment"]));if(!is_view($R)){foreach(array("Engine","Collation")as$y)json_row("$y-$B",h($R[$y]));foreach($Oh+array("Auto_increment"=>0,"Rows"=>0)as$y=>$X){if($R[$y]!=""){$X=format_number($R[$y]);json_row("$y-$B",($y=="Rows"&&$X&&$R["Engine"]==($Ah=="pgsql"?"table":"InnoDB")?"~ $X":$X));if(isset($Oh[$y]))$Oh[$y]+=($R["Engine"]!="InnoDB"||$y!="Data_free"?$R[$y]:0);}elseif(array_key_exists($y,$R))json_row("$y-$B");}}}foreach($Oh
as$y=>$X)json_row("sum-$y",format_number($X));json_row("");}elseif($_GET["script"]=="kill")$g->query("KILL ".number($_POST["kill"]));else{foreach(count_tables($b->databases())as$l=>$X){json_row("tables-$l",$X);json_row("size-$l",db_size($l));}json_row("");}exit;}else{$Xh=array_merge((array)$_POST["tables"],(array)$_POST["views"]);if($Xh&&!$n&&!$_POST["search"]){$G=true;$Ne="";if($x=="sql"&&$_POST["tables"]&&count($_POST["tables"])>1&&($_POST["drop"]||$_POST["truncate"]||$_POST["copy"]))queries("SET foreign_key_checks = 0");if($_POST["truncate"]){if($_POST["tables"])$G=truncate_tables($_POST["tables"]);$Ne='Tables have been truncated.';}elseif($_POST["move"]){$G=move_tables((array)$_POST["tables"],(array)$_POST["views"],$_POST["target"]);$Ne='Tables have been moved.';}elseif($_POST["copy"]){$G=copy_tables((array)$_POST["tables"],(array)$_POST["views"],$_POST["target"]);$Ne='Tables have been copied.';}elseif($_POST["drop"]){if($_POST["views"])$G=drop_views($_POST["views"]);if($G&&$_POST["tables"])$G=drop_tables($_POST["tables"]);$Ne='Tables have been dropped.';}elseif($x!="sql"){$G=($x=="sqlite"?queries("VACUUM"):apply_queries("VACUUM".($_POST["optimize"]?"":" ANALYZE"),$_POST["tables"]));$Ne='Tables have been optimized.';}elseif(!$_POST["tables"])$Ne='No tables.';elseif($G=queries(($_POST["optimize"]?"OPTIMIZE":($_POST["check"]?"CHECK":($_POST["repair"]?"REPAIR":"ANALYZE")))." TABLE ".implode(", ",array_map('idf_escape',$_POST["tables"])))){while($I=$G->fetch_assoc())$Ne.="<b>".h($I["Table"])."</b>: ".h($I["Msg_text"])."<br>";}queries_redirect(substr(ME,0,-1),$Ne,$G);}page_header(($_GET["ns"]==""?'Database'.": ".h(DB):'Schema'.": ".h($_GET["ns"])),$n,true);if($b->homepage()){if($_GET["ns"]!==""){echo"<h3 id='tables-views'>".'Tables and views'."</h3>\n";$Wh=tables_list();if(!$Wh)echo"<p class='message'>".'No tables.'."\n";else{echo"<form action='' method='post'>\n";if(support("table")){echo"<fieldset><legend>".'Search data in tables'." <span id='selected2'></span></legend><div>","<input type='search' name='query' value='".h($_POST["query"])."'>",script("qsl('input').onkeydown = partialArg(bodyKeydown, 'search');","")," <input type='submit' name='search' value='".'Search'."'>\n","</div></fieldset>\n";if($_POST["search"]&&$_POST["query"]!=""){$_GET["where"][0]["op"]="LIKE %%";search_tables();}}echo"<div class='scrollable'>\n","<table cellspacing='0' class='nowrap checkable'>\n",script("mixin(qsl('table'), {onclick: tableClick, ondblclick: partialArg(tableClick, true)});"),'<thead><tr class="wrap">','<td><input id="check-all" type="checkbox" class="jsonly">'.script("qs('#check-all').onclick = partial(formCheck, /^(tables|views)\[/);",""),'<th>'.'Table','<td>'.'Engine'.doc_link(array('sql'=>'storage-engines.html')),'<td>'.'Collation'.doc_link(array('sql'=>'charset-charsets.html','mariadb'=>'supported-character-sets-and-collations/')),'<td>'.'Data Length'.doc_link(array('sql'=>'show-table-status.html','pgsql'=>'functions-admin.html#FUNCTIONS-ADMIN-DBOBJECT','oracle'=>'REFRN20286')),'<td>'.'Index Length'.doc_link(array('sql'=>'show-table-status.html','pgsql'=>'functions-admin.html#FUNCTIONS-ADMIN-DBOBJECT')),'<td>'.'Data Free'.doc_link(array('sql'=>'show-table-status.html')),'<td>'.'Auto Increment'.doc_link(array('sql'=>'example-auto-increment.html','mariadb'=>'auto_increment/')),'<td>'.'Rows'.doc_link(array('sql'=>'show-table-status.html','pgsql'=>'catalog-pg-class.html#CATALOG-PG-CLASS','oracle'=>'REFRN20286')),(support("comment")?'<td>'.'Comment'.doc_link(array('sql'=>'show-table-status.html','pgsql'=>'functions-info.html#FUNCTIONS-INFO-COMMENT-TABLE')):''),"</thead>\n";$S=0;foreach($Wh
as$B=>$T){$bj=($T!==null&&!preg_match('~table~i',$T));$t=h("Table-".$B);echo'<tr'.odd().'><td>'.checkbox(($bj?"views[]":"tables[]"),$B,in_array($B,$Xh,true),"","","",$t),'<th>'.(support("table")||support("indexes")?"<a href='".h(ME)."table=".urlencode($B)."' title='".'Show structure'."' id='$t'>".h($B).'</a>':h($B));if($bj){echo'<td colspan="6"><a href="'.h(ME)."view=".urlencode($B).'" title="'.'Alter view'.'">'.(preg_match('~materialized~i',$T)?'Materialized view':'View').'</a>','<td align="right"><a href="'.h(ME)."select=".urlencode($B).'" title="'.'Select data'.'">?</a>';}else{foreach(array("Engine"=>array(),"Collation"=>array(),"Data_length"=>array("create",'Alter table'),"Index_length"=>array("indexes",'Alter indexes'),"Data_free"=>array("edit",'New item'),"Auto_increment"=>array("auto_increment=1&create",'Alter table'),"Rows"=>array("select",'Select data'),)as$y=>$_){$t=" id='$y-".h($B)."'";echo($_?"<td align='right'>".(support("table")||$y=="Rows"||(support("indexes")&&$y!="Data_length")?"<a href='".h(ME."$_[0]=").urlencode($B)."'$t title='$_[1]'>?</a>":"<span$t>?</span>"):"<td id='$y-".h($B)."'>");}$S++;}echo(support("comment")?"<td id='Comment-".h($B)."'>":"");}echo"<tr><td><th>".sprintf('%d in total',count($Wh)),"<td>".h($x=="sql"?$g->result("SELECT @@storage_engine"):""),"<td>".h(db_collation(DB,collations()));foreach(array("Data_length","Index_length","Data_free")as$y)echo"<td align='right' id='sum-$y'>";echo"</table>\n","</div>\n";if(!information_schema(DB)){echo"<div class='footer'><div>\n";$Vi="<input type='submit' value='".'Vacuum'."'> ".on_help("'VACUUM'");$yf="<input type='submit' name='optimize' value='".'Optimize'."'> ".on_help($x=="sql"?"'OPTIMIZE TABLE'":"'VACUUM OPTIMIZE'");echo"<fieldset><legend>".'Selected'." <span id='selected'></span></legend><div>".($x=="sqlite"?$Vi:($x=="pgsql"?$Vi.$yf:($x=="sql"?"<input type='submit' value='".'Analyze'."'> ".on_help("'ANALYZE TABLE'").$yf."<input type='submit' name='check' value='".'Check'."'> ".on_help("'CHECK TABLE'")."<input type='submit' name='repair' value='".'Repair'."'> ".on_help("'REPAIR TABLE'"):"")))."<input type='submit' name='truncate' value='".'Truncate'."'> ".on_help($x=="sqlite"?"'DELETE'":"'TRUNCATE".($x=="pgsql"?"'":" TABLE'")).confirm()."<input type='submit' name='drop' value='".'Drop'."'>".on_help("'DROP TABLE'").confirm()."\n";$k=(support("scheme")?$b->schemas():$b->databases());if(count($k)!=1&&$x!="sqlite"){$l=(isset($_POST["target"])?$_POST["target"]:(support("scheme")?$_GET["ns"]:DB));echo"<p>".'Move to other database'.": ",($k?html_select("target",$k,$l):'<input name="target" value="'.h($l).'" autocapitalize="off">')," <input type='submit' name='move' value='".'Move'."'>",(support("copy")?" <input type='submit' name='copy' value='".'Copy'."'> ".checkbox("overwrite",1,$_POST["overwrite"],'overwrite'):""),"\n";}echo"<input type='hidden' name='all' value=''>";echo
script("qsl('input').onclick = function () { selectCount('selected', formChecked(this, /^(tables|views)\[/));".(support("table")?" selectCount('selected2', formChecked(this, /^tables\[/) || $S);":"")." }"),"<input type='hidden' name='token' value='$qi'>\n","</div></fieldset>\n","</div></div>\n";}echo"</form>\n",script("tableCheck();");}echo'<p class="links"><a href="'.h(ME).'create=">'.'Create table'."</a>\n",(support("view")?'<a href="'.h(ME).'view=">'.'Create view'."</a>\n":"");if(support("routine")){echo"<h3 id='routines'>".'Routines'."</h3>\n";$Yg=routines();if($Yg){echo"<table cellspacing='0'>\n",'<thead><tr><th>'.'Name'.'<td>'.'Type'.'<td>'.'Return type'."<td></thead>\n";odd('');foreach($Yg
as$I){$B=($I["SPECIFIC_NAME"]==$I["ROUTINE_NAME"]?"":"&name=".urlencode($I["ROUTINE_NAME"]));echo'<tr'.odd().'>','<th><a href="'.h(ME.($I["ROUTINE_TYPE"]!="PROCEDURE"?'callf=':'call=').urlencode($I["SPECIFIC_NAME"]).$B).'">'.h($I["ROUTINE_NAME"]).'</a>','<td>'.h($I["ROUTINE_TYPE"]),'<td>'.h($I["DTD_IDENTIFIER"]),'<td><a href="'.h(ME.($I["ROUTINE_TYPE"]!="PROCEDURE"?'function=':'procedure=').urlencode($I["SPECIFIC_NAME"]).$B).'">'.'Alter'."</a>";}echo"</table>\n";}echo'<p class="links">'.(support("procedure")?'<a href="'.h(ME).'procedure=">'.'Create procedure'.'</a>':'').'<a href="'.h(ME).'function=">'.'Create function'."</a>\n";}if(support("sequence")){echo"<h3 id='sequences'>".'Sequences'."</h3>\n";$mh=get_vals("SELECT sequence_name FROM information_schema.sequences WHERE sequence_schema = current_schema() ORDER BY sequence_name");if($mh){echo"<table cellspacing='0'>\n","<thead><tr><th>".'Name'."</thead>\n";odd('');foreach($mh
as$X)echo"<tr".odd()."><th><a href='".h(ME)."sequence=".urlencode($X)."'>".h($X)."</a>\n";echo"</table>\n";}echo"<p class='links'><a href='".h(ME)."sequence='>".'Create sequence'."</a>\n";}if(support("type")){echo"<h3 id='user-types'>".'User types'."</h3>\n";$Ti=types();if($Ti){echo"<table cellspacing='0'>\n","<thead><tr><th>".'Name'."</thead>\n";odd('');foreach($Ti
as$X)echo"<tr".odd()."><th><a href='".h(ME)."type=".urlencode($X)."'>".h($X)."</a>\n";echo"</table>\n";}echo"<p class='links'><a href='".h(ME)."type='>".'Create type'."</a>\n";}if(support("event")){echo"<h3 id='events'>".'Events'."</h3>\n";$J=get_rows("SHOW EVENTS");if($J){echo"<table cellspacing='0'>\n","<thead><tr><th>".'Name'."<td>".'Schedule'."<td>".'Start'."<td>".'End'."<td></thead>\n";foreach($J
as$I){echo"<tr>","<th>".h($I["Name"]),"<td>".($I["Execute at"]?'At given time'."<td>".$I["Execute at"]:'Every'." ".$I["Interval value"]." ".$I["Interval field"]."<td>$I[Starts]"),"<td>$I[Ends]",'<td><a href="'.h(ME).'event='.urlencode($I["Name"]).'">'.'Alter'.'</a>';}echo"</table>\n";$Bc=$g->result("SELECT @@event_scheduler");if($Bc&&$Bc!="ON")echo"<p class='error'><code class='jush-sqlset'>event_scheduler</code>: ".h($Bc)."\n";}echo'<p class="links"><a href="'.h(ME).'event=">'.'Create event'."</a>\n";}if($Wh)echo
script("ajaxSetHtml('".js_escape(ME)."script=db');");}}}page_footer();
