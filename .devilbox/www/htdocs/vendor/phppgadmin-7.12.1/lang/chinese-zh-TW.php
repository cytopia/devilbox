<?php

	/**
	* Chinese zh_TW translation file for phpPgAdmin.  Use this as a basis
	* for new translations.
	* @maintainer 郭朝益  [Kuo.ChaoYi@gmail.com]
	*
	* $Id: chinese-utf8-zh_TW.php,v 1.6 2008/01/03 03:11:57 xzilla Exp $
	* EN-Revision: 1.228
	*/

	// Language and character set
	$lang['applang'] = '正體中文（UTF-8）';
	$lang['applocale'] = 'zh-TW';
	$lang['applangdir'] = 'ltr';

	// Welcome  
	$lang['strintro'] = '歡迎使用 phpPgAdmin。';
	$lang['strppahome'] = 'phpPgAdmin 首頁';
	$lang['strpgsqlhome'] = 'PostgreSQL 首頁';
	$lang['strpgsqlhome_url'] = 'http://www.postgresql.org/';
	$lang['strlocaldocs'] = 'PostgreSQL 文件 (本機)';
	$lang['strreportbug'] = '通報程式 Bug';
	$lang['strviewfaq'] = '檢視線上 FAQ';
	$lang['strviewfaq_url'] = 'http://phppgadmin.sourceforge.net/doku.php?id=faq';
	
	// Basic strings
	$lang['strlogin'] = '登入';
	$lang['strloginfailed'] = '登入失敗';
	$lang['strlogindisallowed'] = '出於安全原因禁止登入。';
	$lang['strserver'] = '伺服器';
	$lang['strservers'] = '伺服器';
	$lang['strintroduction'] = '介紹';
	$lang['strhost'] = '主機';
	$lang['strport'] = '埠號';
	$lang['strlogout'] = '登出';
	$lang['strowner'] = '擁有人';
	$lang['straction'] = '動作';
	$lang['stractions'] = '動作';
	$lang['strname'] = '名稱';
	$lang['strdefinition'] = '定義';
	$lang['strproperties'] = '屬性';
	$lang['strbrowse'] = '瀏覽';
	$lang['strenable'] = '啟用';
	$lang['strdisable'] = '停用';
	$lang['strdrop'] = '移除';
	$lang['strdropped'] = '已移除';
	$lang['strnull'] = '空值';
	$lang['strnotnull'] = '不允許空值';
	$lang['strprev'] = '< 上一步';
	$lang['strnext'] = '下一步 >';
	$lang['strfirst'] = '<< 最前一步';
	$lang['strlast'] = '最後一步 >>';
	$lang['strfailed'] = '失敗';
	$lang['strcreate'] = '建立';
	$lang['strcreated'] = '已建立';
	$lang['strcomment'] = '註釋';
	$lang['strlength'] = '長度';
	$lang['strdefault'] = '預設值';
	$lang['stralter'] = '修改';
	$lang['strok'] = '確定';
	$lang['strcancel'] = '取消';
	$lang['strac'] = '啟用自動完成';
	$lang['strsave'] = '儲存';
	$lang['strreset'] = '重設';
	$lang['strinsert'] = '插入';
	$lang['strselect'] = '選取';
	$lang['strdelete'] = '刪除';
	$lang['strupdate'] = '更新';
	$lang['strreferences'] = '參照';
	$lang['stryes'] = '是';
	$lang['strno'] = '否';
	$lang['strtrue'] = '真(TRUE)';
	$lang['strfalse'] = '假(FALSE)';
	$lang['stredit'] = '編輯';
	$lang['strcolumn'] = '欄位';
	$lang['strcolumns'] = '欄位';
	$lang['strrows'] = '資料列';
	$lang['strrowsaff'] = '資料列受影響。';
	$lang['strobjects'] = '物件';
	$lang['strback'] = '返回';
	$lang['strqueryresults'] = '查詢結果';
	$lang['strshow'] = '顯示';
	$lang['strempty'] = '清空';
	$lang['strlanguage'] = '語言';
	$lang['strencoding'] = '字元編碼';
	$lang['strunique'] = '唯一值';
	$lang['strprimary'] = '主鍵(PK)';
	$lang['strexport'] = '匯出';
	$lang['strimport'] = '匯入';
	$lang['strallowednulls'] = '允許空字串';
	$lang['strbackslashn'] = '\N';
	$lang['stremptystring'] = '空 字串/欄位';
	$lang['strsql'] = 'SQL';
	$lang['stradmin'] = '管理';
	$lang['strvacuum'] = '清理(Vacuum)';
	$lang['stranalyze'] = '分析';
	$lang['strclusterindex'] = '叢集';
	$lang['strclustered'] = '已叢集?';
	$lang['strreindex'] = '重建索引';
	$lang['strrun'] = '執行';
	$lang['stradd'] = '新增';
	$lang['strevent'] = '事件';
	$lang['strwhere'] = '條件';
	$lang['strinstead'] = '已被取代';
	$lang['strwhen'] = '當';
	$lang['strformat'] = '格式';
	$lang['strdata'] = '資料';
	$lang['strconfirm'] = '確認';
	$lang['strexpression'] = '表達式';
	$lang['strellipsis'] = '...';
	$lang['strseparator'] = ': ';
	$lang['strexpand'] = '展開';
	$lang['strcollapse'] = '摺疊';
	$lang['strfind'] = '尋找';
	$lang['stroptions'] = '選項';
	$lang['strrefresh'] = '重新整理';
	$lang['strdownload'] = '下載';
	$lang['strdownloadgzipped'] = '以 gzip 壓縮並下載';
	$lang['strinfo'] = '資訊';
	$lang['stroids'] = 'OIDs';
	$lang['stradvanced'] = '進階';
	$lang['strvariables'] = '變數';
	$lang['strprocess'] = '進程';
	$lang['strprocesses'] = '進程';
	$lang['strsetting'] = '設定';
	$lang['streditsql'] = '編輯 SQL';
	$lang['strruntime'] = '總共執行時間: %s ms';
	$lang['strpaginate'] = '分頁顯示結果';
	$lang['struploadscript'] = '或是上傳一個 SQL 稿本檔: ';
	$lang['strstarttime'] = '啟動時間';
	$lang['strfile'] = '檔案';
	$lang['strfileimported'] = '檔案已匯入。';
	$lang['strtrycred'] = '使用這些憑證給全部伺服器';
	$lang['stractionsonmultiplelines'] = '動作在多個行列上';
	$lang['strcheckall'] = '檢查全部';
	$lang['struncheckall'] = '不檢查全部';
	$lang['strcluster'] = '叢集';

	// User-supplied SQL history
	$lang['strhistory'] = '歷程';
	$lang['strnohistory'] = '無歷程。';
	$lang['strclearhistory'] = '清空歷程';
	$lang['strdelhistory'] = '從歷程刪除';
	$lang['strconfdelhistory'] = '確實要從歷程刪除嗎 ?';
	$lang['strconfclearhistory'] = '確實要清空歷程嗎 ?';
	$lang['strnodatabaseselected'] = '請選擇一個資料庫。';

	// Database sizes
	$lang['strsize'] = '容量';
	$lang['strbytes'] = '位元組';
	$lang['strkb'] = 'kB';
	$lang['strmb'] = 'MB';
	$lang['strgb'] = 'GB';
	$lang['strtb'] = 'TB';

	// Error handling	
	$lang['strnoframes'] = '這個應用最好以一個能啟用框架頁(frame)的瀏覽器運作，但也能夠被使用在沒有框架頁下接繼運作，請按下面的連結。';
	$lang['strnoframeslink'] = '使用不包括框架頁(frame)';
	$lang['strbadconfig'] = '您的 config.inc.php 是過時的。您將需要從新的 config.inc.php-dist 重建它。';
	$lang['strnotloaded'] = '您的 PHP 環境未安裝 PostgreSQL 必要的支持。您必需重新編譯 PHP 使用 --with-pgsql 組態選項。';
	$lang['strpostgresqlversionnotsupported'] = '版本的 PostgreSQL 未被支持。請升級版本到 %s 或是更高者。';
	$lang['strbadschema'] = '無效的架構模式被指定。';
	$lang['strbadencoding'] = '在資料庫中設定客戶端字元編碼失敗。';
	$lang['strsqlerror'] = 'SQL 錯誤: ';
	$lang['strinstatement'] = '在區塊內: ';
	$lang['strinvalidparam'] = '無效的稿本變數。';
	$lang['strnodata'] = '找不到資料列。';
	$lang['strnoobjects'] = '找不到物件。';
	$lang['strrownotunique'] = '該資料列無唯一約束。';
	$lang['strnouploads'] = '上傳檔案功能是已停用。';
	$lang['strimporterror'] = '匯入錯誤。';
	$lang['strimporterror-fileformat'] = '匯入錯誤: 自動識別檔案格式已失敗。';
	$lang['strimporterrorline'] = '匯入錯誤發生在第 %s 行。';
	$lang['strimporterrorline-badcolumnnum'] = '匯入錯誤發生在第 %s 行:  該行不具備正確的欄位編號。';
	$lang['strimporterror-uploadedfile'] = '匯入錯誤: 檔案無法被上傳到這伺服器';
	$lang['strcannotdumponwindows'] = '複雜的資料表與架構模式名稱轉儲在 Windows 是未被支持的。';
	$lang['strinvalidserverparam'] = '試圖用無效的伺服器參數連結, 可能有人正試圖攻擊您的系統。';
	$lang['strnoserversupplied'] = '沒有選擇資料庫!';

	// Tables
	$lang['strtable'] = '資料表';
	$lang['strtables'] = '資料表';
	$lang['strshowalltables'] = '顯示全部資料表';
	$lang['strnotables'] = '找不到資料表。';
	$lang['strnotable'] = '找不到任何資料表。';
	$lang['strcreatetable'] = '建立新資料表';
	$lang['strtablename'] = '資料表名';
	$lang['strtableneedsname'] = '您必需為您的資料表命名。';
	$lang['strtableneedsfield'] = '您至少應指定一個欄位。';
	$lang['strtableneedscols'] = '您必需指定一個合法的欄位數量。';
	$lang['strtablecreated'] = '資料表已建立。';
	$lang['strtablecreatedbad'] = '建立資料表作業已失敗。';
	$lang['strconfdroptable'] = '您確定要移除資料表 "%s"?';
	$lang['strtabledropped'] = '資料表已移除。';
	$lang['strtabledroppedbad'] = '資料表移除已失敗。';
	$lang['strconfemptytable'] = '您確定要清空資料表 "%s"?';
	$lang['strtableemptied'] = '資料表已清空。';
	$lang['strtableemptiedbad'] = '資料表清空已失敗。';
	$lang['strinsertrow'] = '插入資料列';
	$lang['strrowinserted'] = '資料列已插入。';
	$lang['strrowinsertedbad'] = '資料列插入已失敗。';
	$lang['strrowduplicate'] = '資料列插入失敗, 試圖做複製品插入。';
	$lang['streditrow'] = '編輯資料列';
	$lang['strrowupdated'] = '資料列已更新。';
	$lang['strrowupdatedbad'] = '資料列更新已失敗。';
	$lang['strdeleterow'] = '刪除資料列';
	$lang['strconfdeleterow'] = '您確定要刪除這些資料列??';
	$lang['strrowdeleted'] = '資料列已刪除。';
	$lang['strrowdeletedbad'] = '資料列刪除已失敗。';
	$lang['strinsertandrepeat'] = '插入與重作';
	$lang['strnumcols'] = '欄位數量';
	$lang['strcolneedsname'] = '您必需為這個欄位特定一個名稱';
	$lang['strselectallfields'] = '選擇全部欄位';
	$lang['strselectneedscol'] = '您必需至少顯示一資料列。';
	$lang['strselectunary'] = '一元運算子不能有值。';
	$lang['straltercolumn'] = '修改資料列';
	$lang['strcolumnaltered'] = '資料列已修改。';
	$lang['strcolumnalteredbad'] = '資料列修改已失敗。';
	$lang['strconfdropcolumn'] = '您確定要移除欄位 "%s" 從資料表 "%s"?';
	$lang['strcolumndropped'] = '欄位已移除。';
	$lang['strcolumndroppedbad'] = '欄位移除已失敗。';
	$lang['straddcolumn'] = '新增欄位';
	$lang['strcolumnadded'] = '欄位已新增。';
	$lang['strcolumnaddedbad'] = '欄位新增已失敗。';
	$lang['strcascade'] = '附屬串聯(CASCADE)';
	$lang['strtablealtered'] = '資料表已修改。';
	$lang['strtablealteredbad'] = '資料表修改已失敗。';
	$lang['strdataonly'] = '只有資料';
	$lang['strstructureonly'] = '只有結構';
	$lang['strstructureanddata'] = '結構和資料';
	$lang['strtabbed'] = '固定(Tabbed)';
	$lang['strauto'] = '自動';
	$lang['strconfvacuumtable'] = '您確定將要清理(vacuum) "%s" 嗎?';
	$lang['strconfanalyzetable'] = '確定要分析 "%s" 嗎?';
	$lang['strestimatedrowcount'] = '已估算的資料列計數';
	$lang['strestimatedrowcount'] = '估計的資料列數';
	$lang['strspecifytabletoanalyze'] = '必須至少選擇一個資料表來分析';
	$lang['strspecifytabletoempty'] = '必須至少選擇一個資料表來清空';
	$lang['strspecifytabletodrop'] = '必須至少選擇一個資料表來移除';
	$lang['strspecifytabletovacuum'] = '必須至少選擇一個資料表來清理(vacuum)';

	// Columns
	$lang['strcolprop'] = '欄位屬性';
	$lang['strnotableprovided'] = '沒有指定資料表!';
		
	// Users
	$lang['struser'] = '使用者';
	$lang['strusers'] = '使用者';
	$lang['strusername'] = '使用者名稱';
	$lang['strpassword'] = '密碼';
	$lang['strsuper'] = '超級使用者?';
	$lang['strcreatedb'] = '能建立資料庫?';
	$lang['strexpires'] = '失效逾期';
	$lang['strsessiondefaults'] = 'Session 預設';
	$lang['strnousers'] = '找不到此使用者。';
	$lang['struserupdated'] = '使用者已更新。';
	$lang['struserupdatedbad'] = '使用者更新已失敗。';
	$lang['strshowallusers'] = '顯示所有使用者';
	$lang['strcreateuser'] = '建立新使用者';
	$lang['struserneedsname'] = '您必需為您的使用者命名。';
	$lang['strusercreated'] = '使用者已建立。';
	$lang['strusercreatedbad'] = '使用者建立已失敗。';
	$lang['strconfdropuser'] = '您確定您要移除這個使用者 "%s"?';
	$lang['struserdropped'] = '使用者已移除。';
	$lang['struserdroppedbad'] = '使用者移除已失敗。';
	$lang['straccount'] = '帳戶';
	$lang['strchangepassword'] = '變更密碼';
	$lang['strpasswordchanged'] = '密碼已變更。';
	$lang['strpasswordchangedbad'] = '密碼變更已失敗。';
	$lang['strpasswordshort'] = '密碼太簡短。';
	$lang['strpasswordconfirm'] = '所輸入的確認密碼不符。';
	
	// Groups
	$lang['strgroup'] = '群組';
	$lang['strgroups'] = '群組';
	$lang['strshowallgroups'] = '顯示全部群組';
	$lang['strnogroup'] = '找不到群組。';
	$lang['strnogroups'] = '找不到任何群組。';
	$lang['strcreategroup'] = '建立群組';
	$lang['strgroupneedsname'] = '您必需為您的群組命名。';
	$lang['strgroupcreated'] = '群組已建立。';
	$lang['strgroupcreatedbad'] = '群組建立已失敗。';
	$lang['strconfdropgroup'] = '您確定您要移除這個群組 "%s"?';
	$lang['strgroupdropped'] = '群組已移除。';
	$lang['strgroupdroppedbad'] = '群組移除已失敗。';
	$lang['strmembers'] = '成員';
	$lang['strmemberof'] = '成員屬於';
	$lang['stradminmembers'] = '管理員成員';
	$lang['straddmember'] = '增加成員';
	$lang['strmemberadded'] = '成員已加入。';
	$lang['strmemberaddedbad'] = '成員加入已失敗。';
	$lang['strdropmember'] = '移除成員';
	$lang['strconfdropmember'] = '您確定您要移除這個成員 "%s" 從這個群組 "%s"?';
	$lang['strmemberdropped'] = '成員已移除。';
	$lang['strmemberdroppedbad'] = '成員移除已失敗。';

	// Roles
	$lang['strrole'] = '角色';
	$lang['strroles'] = '角色';
	$lang['strshowallroles'] = '顯示全部角色';
	$lang['strnoroles'] = '找不到任何角色。';
	$lang['strinheritsprivs'] = '繼承特權?';
	$lang['strcreaterole'] = '建立角色';
	$lang['strcancreaterole'] = '能建立角色?';
	$lang['strrolecreated'] = '角色已建立。';
	$lang['strrolecreatedbad'] = '角色建立已失敗。';
	$lang['stralterrole'] = '修改角色';
	$lang['strrolealtered'] = '角色被修改。';
	$lang['strrolealteredbad'] = '角色修改已失敗。';
	$lang['strcanlogin'] = '可以登入?';
	$lang['strconnlimit'] = '連線限制';
	$lang['strdroprole'] = '移除角色';
	$lang['strconfdroprole'] = '您確定您要移除這個角色 "%s"?';
	$lang['strroledropped'] = '角色已移除。';
	$lang['strroledroppedbad'] = '角色移除已失敗。';
	$lang['strnolimit'] = '不限制';
	$lang['strnever'] = '從末';
	$lang['strroleneedsname'] = '您必需為這個角色命名。';

	// Privileges
	$lang['strprivilege'] = '特權';
	$lang['strprivileges'] = '特權';
	$lang['strnoprivileges'] = '這個物件有預設的擁有人特權。';
	$lang['strgrant'] = '賦予';
	$lang['strrevoke'] = '撤回';
	$lang['strgranted'] = '特權已變更。';
	$lang['strgrantfailed'] = '特權變更失敗。';
	$lang['strgrantbad'] = '您必需至少在一名使用者或群組中指定至少一項特權。';
	$lang['strgrantor'] = '授權者';
	$lang['strasterisk'] = '*';

	// Databases
	$lang['strdatabase'] = '資料庫';
	$lang['strdatabases'] = '資料庫';
	$lang['strshowalldatabases'] = '顯示全部資料庫';
	$lang['strnodatabases'] = '找不到任何資料庫。';
	$lang['strcreatedatabase'] = '建立資料庫';
	$lang['strdatabasename'] = '資料庫名稱';
	$lang['strdatabaseneedsname'] = '您必需為您的資料庫給一個名稱。';
	$lang['strdatabasecreated'] = '資料庫已建立。';
	$lang['strdatabasecreatedbad'] = '資料庫建立失敗。';
	$lang['strconfdropdatabase'] = '您確定您要移除這個資料庫 "%s"?';
	$lang['strdatabasedropped'] = '資料庫已移除。';
	$lang['strdatabasedroppedbad'] = '資料庫移除失敗。';
	$lang['strentersql'] = '在下方輸入 SQL 來執行: ';
	$lang['strsqlexecuted'] = 'SQL 已執行。';
	$lang['strvacuumgood'] = '清理完成。';
	$lang['strvacuumbad'] = '清理失敗。';
	$lang['stranalyzegood'] = '分析完成。';
	$lang['stranalyzebad'] = '分析失敗。';
	$lang['strreindexgood'] = '重建索引完成。';
	$lang['strreindexbad'] = '重建索引失敗。';
	$lang['strfull'] = '全部性';
	$lang['strfreeze'] = '凍結';
	$lang['strforce'] = '強制';
	$lang['strsignalsent'] = '訊號傳遞。';
	$lang['strsignalsentbad'] = '傳遞訊號失敗。';
	$lang['strallobjects'] = '全部物件';
	$lang['strdatabasealtered'] = '資料庫已修改。';
	$lang['strdatabasealteredbad'] = '資料庫修改已失敗。';
	$lang['strspecifydatabasetodrop'] = '必須至少指定一個資料庫來移除';

	// Views
	$lang['strview'] = '視觀表';
	$lang['strviews'] = '視觀表';
	$lang['strshowallviews'] = '顯示全部視觀表';
	$lang['strnoview'] = '找不到視觀表。';
	$lang['strnoviews'] = '找不到任何視觀表。';
	$lang['strcreateview'] = '建立視觀表';
	$lang['strviewname'] = '視觀表名稱';
	$lang['strviewneedsname'] = '您必需為您的視觀表給一個名稱。';
	$lang['strviewneedsdef'] = '您必需為你的視觀表給一個定義。';
	$lang['strviewneedsfields'] = '您必需在您的視觀表中選擇給這個欄位。';
	$lang['strviewcreated'] = '視觀表已建立。';
	$lang['strviewcreatedbad'] = '視觀表建立已失敗。';
	$lang['strconfdropview'] = '您確定您要移除這個視觀表 "%s" 嗎?';
	$lang['strviewdropped'] = '視觀表已移除。';
	$lang['strviewdroppedbad'] = '視觀表移除已失敗。';
	$lang['strviewlink'] = '連結鍵(Linking keys)';
	$lang['strviewconditions'] = '附加条件';
	$lang['strviewconditions'] = '附加的條件限制';
	$lang['strcreateviewwiz'] = '建立視觀表精靈';
	$lang['strrenamedupfields'] = '重新命名複製的字串';
	$lang['strdropdupfields'] = '移除複制的字串';
	$lang['strerrordupfields'] = '複製字串時發生錯誤';
	$lang['strviewupdated'] = '視觀表已更新。';
	$lang['strviewupdatedbad'] = '視觀表更新已失敗。';
	$lang['strspecifyviewtodrop'] = '必须至少指定一个视图来移除';
	
	// Sequences
	$lang['strsequence'] = '序列數';
	$lang['strsequences'] = '序列數';
	$lang['strshowallsequences'] = '顯示全部序列數';
	$lang['strnosequence'] = '找不到序列數。';
	$lang['strnosequences'] = '找不到任何序列數。';
	$lang['strcreatesequence'] = '建立序列數';
	$lang['strlastvalue'] = '最後值';
	$lang['strincrementby'] = '遞增量';	
	$lang['strstartvalue'] = '初始值';
	$lang['strmaxvalue'] = '最大值';
	$lang['strminvalue'] = '最小值';
	$lang['strcachevalue'] = '快取值';
	$lang['strlogcount'] = '日誌計數';
	$lang['striscycled'] = '可循環?';
	$lang['striscalled'] = '將在返回下一個值前遞增最後的值(is_called)嗎？';
	$lang['strsequenceneedsname'] = '您必需為您的序列數給一個名稱。';
	$lang['strsequencecreated'] = '序列數已建立。';
	$lang['strsequencecreatedbad'] = '序列數建立失敗。'; 
	$lang['strconfdropsequence'] = '您確定您要移除這個序列數 "%s"?';
	$lang['strsequencedropped'] = '序列數已移除。';
	$lang['strsequencedroppedbad'] = '序列數移除已失敗。';
	$lang['strsequencereset'] = '序列數重置。';
	$lang['strsequenceresetbad'] = '序列數重置已失敗。'; 
 	$lang['straltersequence'] = '修改序列數';
 	$lang['strsequencealtered'] = '序列數已修改。';
 	$lang['strsequencealteredbad'] = '序列數修改已失敗。';
 	$lang['strsetval'] = '設定序列數值';
 	$lang['strsequencesetval'] = '序列數值已設定。';
 	$lang['strsequencesetvalbad'] = '序列數值設定已失敗。';
 	$lang['strnextval'] = '遞增量';
 	$lang['strsequencenextval'] = '序列數已遞增。';
 	$lang['strsequencenextvalbad'] = '序列數已遞增失敗。';
	$lang['strspecifysequencetodrop'] = '必須至少指定一個序列數來刪除';

	// Indexes
	$lang['strindex'] = '索引';
	$lang['strindexes'] = '索引';
	$lang['strindexname'] = '索引名稱';
	$lang['strshowallindexes'] = '顯示全部索引';
	$lang['strnoindex'] = '找不到索引。';
	$lang['strnoindexes'] = '找不到任何索引。';
	$lang['strcreateindex'] = '建立索引';
	$lang['strtabname'] = '資料表名稱';
	$lang['strcolumnname'] = '欄位名稱';
	$lang['strindexneedsname'] = '您必需為您的索引給一個名稱。';
	$lang['strindexneedscols'] = '索引要求一個有效欄位數量。';
	$lang['strindexcreated'] = '索引已建立';
	$lang['strindexcreatedbad'] = '索引建立失敗。';
	$lang['strconfdropindex'] = '您確定您要移除這個索引 "%s" 嗎?';
	$lang['strindexdropped'] = '索引已移除。';
	$lang['strindexdroppedbad'] = '索引移除失敗。';
	$lang['strkeyname'] = '鍵名';
	$lang['struniquekey'] = '唯一鍵';
	$lang['strprimarykey'] = '主鍵(pkey)';
 	$lang['strindextype'] = '索引類型';
	$lang['strtablecolumnlist'] = '資料表欄位';
	$lang['strindexcolumnlist'] = '索引欄位';
	$lang['strconfcluster'] = '您確定您要叢集化 "%s" 嗎?';
	$lang['strclusteredgood'] = '叢集完成。';
	$lang['strclusteredbad'] = '叢集已失敗。';

	// Rules
	$lang['strrules'] = '規則';
	$lang['strrule'] = '規則';
	$lang['strshowallrules'] = '顯示全部規則';
	$lang['strnorule'] = '找不到規則。';
	$lang['strnorules'] = '找不到任何規則。';
	$lang['strcreaterule'] = '建立規則';
	$lang['strrulename'] = '規則名稱';
	$lang['strruleneedsname'] = '您必需為您的規則給一個名稱。';
	$lang['strrulecreated'] = '規則已建立。';
	$lang['strrulecreatedbad'] = '規則建立已失敗。';
	$lang['strconfdroprule'] = '您確定您要移除這個規則 "%s" 在 "%s"上嗎?';
	$lang['strruledropped'] = '規則規則已移除。';
	$lang['strruledroppedbad'] = '規則移除已失敗。';

	// Constraints
	$lang['strconstraint'] = '約束限制';
	$lang['strconstraints'] = '約束限制';
	$lang['strshowallconstraints'] = '顯示全部約束限制';
	$lang['strnoconstraints'] = '找不到任何約束限制。';
	$lang['strcreateconstraint'] = '建立約束限制';
	$lang['strconstraintcreated'] = '約束限制已建立。';
	$lang['strconstraintcreatedbad'] = '約束限制建立已失敗。';
	$lang['strconfdropconstraint'] = '您確定您要移除這約束限制 "%s" 在 "%s" 上嗎?';
	$lang['strconstraintdropped'] = '約束限制已移除。';
	$lang['strconstraintdroppedbad'] = '約束限制移除已失敗。';
	$lang['straddcheck'] = '增加約束檢查(Check)';
	$lang['strcheckneedsdefinition'] = '約束檢查(Check)限制需要定義。';
	$lang['strcheckadded'] = '約束檢查限制已增加。';
	$lang['strcheckaddedbad'] = '增加約束檢查限制已失敗。';
	$lang['straddpk'] = '增加主鍵(pkey)';
	$lang['strpkneedscols'] = '主鍵(pkey)要求最少一個欄位。';
	$lang['strpkadded'] = '主鍵(pkey)已增加。';
	$lang['strpkaddedbad'] = '增加主鍵(pkey)已失敗。';
	$lang['stradduniq'] = '增加唯一約束限制';
	$lang['struniqneedscols'] = '唯一約束限制要求最少一個欄位。';
	$lang['struniqadded'] = '唯一約束限制已增加。';
	$lang['struniqaddedbad'] = '增加唯一約束限制已失敗。';
	$lang['straddfk'] = '增加外部鍵(fkey)';
	$lang['strfkneedscols'] = '外部鍵(fkey)要求最少一個欄位。';
	$lang['strfkneedstarget'] = '外部鍵(fkey)要求一個資料表。';
	$lang['strfkadded'] = '外部鍵(fkey)已增加。';
	$lang['strfkaddedbad'] = '增加外部鍵(fkey)已失敗。';
	$lang['strfktarget'] = '目標資料表';
	$lang['strfkcolumnlist'] = '鍵欄位';
	$lang['strondelete'] = 'ON DELETE';
	$lang['stronupdate'] = 'ON UPDATE';

	// Functions
	$lang['strfunction'] = '函數';
	$lang['strfunctions'] = '函數';
	$lang['strshowallfunctions'] = '顯示全部函數';
	$lang['strnofunction'] = '找不到函數。';
	$lang['strnofunctions'] = '找不到任何函數。';
	$lang['strcreateplfunction'] = '建立 SQL/PL 函數';
	$lang['strcreateinternalfunction'] = '建立內部函數';
	$lang['strcreatecfunction'] = '建立 C 函數';
	$lang['strfunctionname'] = '函數名稱';
	$lang['strreturns'] = '遞回';
	$lang['strproglanguage'] = '程序語言';
	$lang['strfunctionneedsname'] = '您必需為您的函數給一個名稱。';
	$lang['strfunctionneedsdef'] = '您必需為您的函數給一個定義。';
	$lang['strfunctioncreated'] = '函數已建立。';
	$lang['strfunctioncreatedbad'] = '函數建立已失敗。';
	$lang['strconfdropfunction'] = '您確定您要移除這個函數 "%s"?';
	$lang['strfunctiondropped'] = '函數已移除。';
	$lang['strfunctiondroppedbad'] = '函數移除已失敗。';
	$lang['strfunctionupdated'] = '函數已更新。';
	$lang['strfunctionupdatedbad'] = '函數更新已失敗。';
	$lang['strobjectfile'] = '物件檔案';
	$lang['strlinksymbol'] = '連結物件標記';
	$lang['strarguments'] = '引數';
	$lang['strargmode'] = '方式';
	$lang['strargtype'] = '類型';
	$lang['strargadd'] = '增加作者引數';
	$lang['strargremove'] = '移除這個引數';
	$lang['strargnoargs'] = '這個函數將不能工作任何引數。';
	$lang['strargenableargs'] = '啟用引數已被傳遞到這個函數。';
	$lang['strargnorowabove'] = '需要資料列在這資料列之上。';
	$lang['strargnorowbelow'] = '需要資料列在這資料列之前。';
	$lang['strargraise'] = '向上移。';
	$lang['strarglower'] = '向下移。';
	$lang['strargremoveconfirm'] = '您確定你要移除這個引數? 這個作業未能完成。';
	$lang['strfunctioncosting'] = '函數成本';
	$lang['strresultrows'] = '结果集資料行數';
	$lang['strexecutioncost'] = '執行成本';
	$lang['strspecifyfunctiontodrop'] = '必須至少指定一個函數來刪除';

	// Triggers
	$lang['strtrigger'] = '觸發器';
	$lang['strtriggers'] = '觸發器';
	$lang['strshowalltriggers'] = '顯示全部觸發器';
	$lang['strnotrigger'] = '找不到觸發器。';
	$lang['strnotriggers'] = '找不到任何觸發器。';
	$lang['strcreatetrigger'] = '建立觸發器';
	$lang['strtriggerneedsname'] = '您必需為您的觸發器明確指定一個名稱。';
	$lang['strtriggerneedsfunc'] = '您必需為您的觸發器明確指定一個函數。';
	$lang['strtriggercreated'] = '觸發器已建立。';
	$lang['strtriggercreatedbad'] = '觸發器建立已失敗。';
	$lang['strconfdroptrigger'] = '您確定您要移除這個觸發器 "%s" on "%s"?';
	$lang['strconfenabletrigger'] = '您確定您要啟用這個觸發器觸發器 "%s" 在 "%s" 上?';
	$lang['strconfdisabletrigger'] = '您確定您要停用這個觸發器觸發器 "%s" on "%s" 上?';
	$lang['strtriggerdropped'] = '觸發器已移除。';
	$lang['strtriggerdroppedbad'] = '觸發器移除已失敗。';
	$lang['strtriggerenabled'] = '觸發器啟用。';
	$lang['strtriggerenabledbad'] = '觸發器啟用已失敗。';
	$lang['strtriggerdisabled'] = '觸發器停用。';
	$lang['strtriggerdisabledbad'] = '觸發器停用已失敗。';
	$lang['strtriggeraltered'] = '觸發器已修改。';
	$lang['strtriggeralteredbad'] = '觸發器修改已失敗。';
	$lang['strforeach'] = '給每個';

	// Types
	$lang['strtype'] = '類型';
	$lang['strtypes'] = '類型';
	$lang['strshowalltypes'] = '顯示全部類型';
	$lang['strnotype'] = '找不到類型。';
	$lang['strnotypes'] = '找不到任何類型。';
	$lang['strcreatetype'] = '建立類型';
	$lang['strcreatecomptype'] = '建立合成類型';
	$lang['strcreateenumtype'] = '建立列舉類型';
	$lang['strtypeneedsfield'] = '您必須明確指定最少一個欄位。';
	$lang['strtypeneedsvalue'] = '您必須明確指定最少一個值。';
	$lang['strtypeneedscols'] = '您必須明確指定有效的欄位數。';
	$lang['strtypeneedsvals'] = '您必須明確指定一個有效的欄位數。';
	$lang['strinputfn'] = '輸入類型';
	$lang['stroutputfn'] = '輸出類型';
	$lang['strpassbyval'] = '以值傳送?';
	$lang['stralignment'] = '列隊組合';
	$lang['strelement'] = '元素';
	$lang['strdelimiter'] = '分隔符號';
	$lang['strstorage'] = '儲藏所';
	$lang['strfield'] = '欄位';
	$lang['strvalue'] = '值';
	$lang['strnumfields'] = '欄位數. ';
	$lang['strnumvalues'] = '值數';
	$lang['strtypeneedsname'] = '您必需為您的類型給一個名稱。';
	$lang['strtypeneedslen'] = '您必需為您的類型給一個長度。';
	$lang['strtypecreated'] = '類型已建立';
	$lang['strtypecreatedbad'] = '類型建立已失敗。';
	$lang['strconfdroptype'] = '您確定您要移除這個類型 "%s" 嗎?';
	$lang['strtypedropped'] = '類型已移除。';
	$lang['strtypedroppedbad'] = '類型移除已失敗。';
	$lang['strflavor'] = '風格(Flavor)';
	$lang['strbasetype'] = '基礎';
	$lang['strcompositetype'] = '合成';
	$lang['strpseudotype'] = '偽(Pseudo)';
	$lang['strenum'] = 'Enum';
	$lang['strenumvalues'] = '列舉類型';

	// Schemas
	$lang['strschema'] = '架構模式';
	$lang['strschemas'] = '架構模式';
	$lang['strshowallschemas'] = '顯示全部架構模式';
	$lang['strnoschema'] = '找不到架構模式。';
	$lang['strnoschemas'] = '找不到任何架構模式。';
	$lang['strcreateschema'] = '建立架構模式';
	$lang['strschemaname'] = '架構模式名稱';
	$lang['strschemaneedsname'] = '您必需為您的架構模式給一個名稱。';
	$lang['strschemacreated'] = '架構模式已建立';
	$lang['strschemacreatedbad'] = '架構模式建立已失敗。';
	$lang['strconfdropschema'] = '您確定您要移除這個架構模式 "%s" 嗎?';
	$lang['strschemadropped'] = '架構模式已移除。';
	$lang['strschemadroppedbad'] = '架構模式移除失敗。';
	$lang['strschemaaltered'] = '架構模式已修改。';
	$lang['strschemaalteredbad'] = '架構模式修改已失敗。';
	$lang['strsearchpath'] = '架構模式搜尋路徑';
	$lang['strspecifyschematodrop'] = '您必須至少指定一個架構模式來刪除';

	// Reports

	// Domains
	$lang['strdomain'] = '共同值域';
	$lang['strdomains'] = '共同值域';
	$lang['strshowalldomains'] = '顯示全部共同值域';
	$lang['strnodomains'] = '找不到任何共同值域。';
	$lang['strcreatedomain'] = '建立共同值域';
	$lang['strdomaindropped'] = '共同值域已移除。';
	$lang['strdomaindroppedbad'] = '共同值域移除已失敗。';
	$lang['strconfdropdomain'] = '您確定您要移除這個共同值域 "%s"?';
	$lang['strdomainneedsname'] = '您必需為您的共同值域給一個名稱。。';
	$lang['strdomaincreated'] = '共同值域已建立。';
	$lang['strdomaincreatedbad'] = '共同值域建立已失敗。';	
	$lang['strdomainaltered'] = '共同值域已修改。';
	$lang['strdomainalteredbad'] = '共同值域修改已失敗。';	

	// Operators
	$lang['stroperator'] = '運算子';
	$lang['stroperators'] = '運算子';
	$lang['strshowalloperators'] = '顯示全部運算子';
	$lang['strnooperator'] = '找不到運算子。';
	$lang['strnooperators'] = '找不到任何運算子。';
	$lang['strcreateoperator'] = '建立運算子';
	$lang['strleftarg'] = '左引數類型';
	$lang['strrightarg'] = '右引數類型';
	$lang['strcommutator'] = '轉換器';
	$lang['strnegator'] = '否定器';
	$lang['strrestrict'] = '限制';
	$lang['strjoin'] = '結合';
	$lang['strhashes'] = '雜湊(Hashes)';
	$lang['strmerges'] = '合併';
	$lang['strleftsort'] = '左排序';
	$lang['strrightsort'] = '右排序';
	$lang['strlessthan'] = '小於';
	$lang['strgreaterthan'] = '大於';
	$lang['stroperatorneedsname'] = '您必需為您的運算子給一個名稱。';
	$lang['stroperatorcreated'] = '運算子已建立';
	$lang['stroperatorcreatedbad'] = '運算子建立已失敗。';
	$lang['strconfdropoperator'] = '您確定您要移除這個運算子 "%s" 嗎?';
	$lang['stroperatordropped'] = '運算子已移除。';
	$lang['stroperatordroppedbad'] = '運算子移除已失敗。';

	// Casts
	$lang['strcasts'] = '類型轉換';
	$lang['strnocasts'] = '找不到任何類型轉換。';
	$lang['strsourcetype'] = '來源類型';
	$lang['strtargettype'] = '目標類型';
	$lang['strimplicit'] = '隱含';
	$lang['strinassignment'] = '在指派中';
	$lang['strbinarycompat'] = '(二進制碼相容)';
	
	// Conversions
	$lang['strconversions'] = '編碼轉換';
	$lang['strnoconversions'] = '找不到任何編碼轉換。';
	$lang['strsourceencoding'] = '來源編碼';
	$lang['strtargetencoding'] = '目標編碼';
	
	// Languages
	$lang['strlanguages'] = '程序語言';
	$lang['strnolanguages'] = '找不到任何程序語言。';
	$lang['strtrusted'] = '被信任的';
	
	// Info
	$lang['strnoinfo'] = '無資訊可用。';
	$lang['strreferringtables'] = '參照中資料表';
	$lang['strparenttables'] = '父資料表';
	$lang['strchildtables'] = '子資料表';

	// Aggregates
	$lang['straggregate'] = '聚集函數';
	$lang['straggregates'] = '聚集函數';
	$lang['strnoaggregates'] = '找不到任何聚集函數。';
	$lang['stralltypes'] = '(全部類型)';
	$lang['strcreateaggregate'] = '建立聚集函數';
	$lang['straggrbasetype'] = '輸入資料類型';
	$lang['straggrsfunc'] = '狀態過渡函數';
	$lang['straggrstype'] = '狀態類型';
	$lang['straggrffunc'] = '最終函數';
	$lang['straggrinitcond'] = '最初條件';
	$lang['straggrsortop'] = '排序運算子';
	$lang['strconfdropaggregate'] = '您確定您要移除這個聚集函數 "%s" 嗎?';
	$lang['straggregatedropped'] = '聚集函數已移除。';
	$lang['straggregatedroppedbad'] = '聚集函數移除已失敗。';
	$lang['straggraltered'] = '聚集函數已修改。';
	$lang['straggralteredbad'] = '聚集函數修改已失敗。';
	$lang['straggrneedsname'] = '您必需具體指定一個名稱給這個聚集函數。';
	$lang['straggrneedsbasetype'] = '您必需具體指定這聚集函數的進入資料類型。';
	$lang['straggrneedssfunc'] = '您必需具體指定這這聚集函數的狀態過渡函數名稱。';
	$lang['straggrneedsstype'] = '您必需具體指定這聚集函數群狀態值的資料類型';
	$lang['straggrcreated'] = '聚集函數已建立。';
	$lang['straggrcreatedbad'] = '聚集函數建立已失敗。';
	$lang['straggrshowall'] = '顯示全部聚集函數';
	
	// Operator Classes
	$lang['stropclasses'] = '運算子類別';
	$lang['strnoopclasses'] = '找不到任何運算子類別。';
	$lang['straccessmethod'] = '存取方法';

	// Stats and performance
	$lang['strrowperf'] = '資料列性能';
	$lang['strioperf'] = 'I/O 性能';
	$lang['stridxrowperf'] = '索引資料列性能';
	$lang['stridxioperf'] = '索引 I/O 性能';
	$lang['strpercent'] = '%';
	$lang['strsequential'] = '順序性';
	$lang['strscan'] = '掃描';
	$lang['strread'] = '讀取';
	$lang['strfetch'] = '取得';
	$lang['strheap'] = '堆疊';
	$lang['strtoast'] = 'TOAST';
	$lang['strtoastindex'] = 'TOAST 索引';
	$lang['strcache'] = '快取';
	$lang['strdisk'] = '磁碟';
	$lang['strrows2'] = '資料列';

	// Tablespaces
	$lang['strtablespace'] = '表空間';
	$lang['strtablespaces'] = '表空間';
	$lang['strshowalltablespaces'] = '顯示全部表空間';
	$lang['strnotablespaces'] = '找不到任何表空間。';
	$lang['strcreatetablespace'] = '建立表空間';
	$lang['strlocation'] = '所在位置';
	$lang['strtablespaceneedsname'] = '您必需為您的表空間給一個名稱。';
	$lang['strtablespaceneedsloc'] = '您必需給一個目錄夾來建立表空間。';
	$lang['strtablespacecreated'] = '表空間已建立。';
	$lang['strtablespacecreatedbad'] = '表空間建立已失敗。';
	$lang['strconfdroptablespace'] = '您確定您要移除這個表空間 "%s"?';
	$lang['strtablespacedropped'] = '表空間已移除。';
	$lang['strtablespacedroppedbad'] = '表空間移除已失敗。';
	$lang['strtablespacealtered'] = '表空間已修改。';
	$lang['strtablespacealteredbad'] = '表空間修改已失敗。';
	
	// Miscellaneous
	$lang['strtopbar'] = '%s 運作於 %s: %s -- 您是已登入的使用者 "%s"';
	$lang['strtimefmt'] = 'jS M, Y g: iA';
	$lang['strhelp'] = '說明';
	$lang['strhelpicon'] = '?';
	$lang['strhelppagebrowser'] = '說明頁瀏覽器';
	$lang['strselecthelppage'] = '選擇一個說明頁';
	$lang['strinvalidhelppage'] = '無效說明頁。';
	$lang['strlogintitle'] = '登入到 %s';
	$lang['strlogoutmsg'] = '登出 %s';
	$lang['strloading'] = '載入中...';
	$lang['strerrorloading'] = '載入中錯誤';
	$lang['strclicktoreload'] = '點擊到重新載入';

	// Autovacuum
	$lang['strautovacuum'] = '自動清理(Autovacuum)'; 
	$lang['strturnedon'] = '已轉動 - 開啟'; 
	$lang['strturnedoff'] = '已轉動 - 關閉'; 
	$lang['strenabled'] = '啟用'; 
	$lang['strvacuumbasethreshold'] = 'Vacuum 基本門檻'; 
	$lang['strvacuumscalefactor'] = 'Vacuum 換算係數';  
	$lang['stranalybasethreshold'] = 'Analyze 基本門檻';  
	$lang['stranalyzescalefactor'] = 'Analyze 換算係數'; 
	$lang['strvacuumcostdelay'] = 'Vacuum 成本延遲'; 
	$lang['strvacuumcostlimit'] = 'Vacuum 成本限制';  

	// Table-level Locks
	$lang['strlocks'] = '鎖定';
	$lang['strtransaction'] = '事務交易 ID';
	$lang['strvirtualtransaction'] = '虛擬事務交易 ID';
	$lang['strprocessid'] = '進程 ID';
	$lang['strmode'] = '鎖定模式';
	$lang['strislockheld'] = '是鎖定執(held)?';

	// Prepared transactions
	$lang['strpreparedxacts'] = '已準備事務交易';
	$lang['strxactid'] = '事務交易 ID';
	$lang['strgid'] = 'Global ID';
	
	// Fulltext search
	$lang['strfulltext'] = '全文檢索';
	$lang['strftsconfig'] = '全文檢索組態';
	$lang['strftsconfigs'] = '組態';
	$lang['strftscreateconfig'] = '新建全文檢索組態';
	$lang['strftscreatedict'] = '新建字典';
	$lang['strftscreatedicttemplate'] = '新建字典模板';
	$lang['strftscreateparser'] = '新建分析器';
	$lang['strftsnoconfigs'] = '沒有找到全文檢索組態。';
	$lang['strftsconfigdropped'] = '全文檢索組態已移除。';
	$lang['strftsconfigdroppedbad'] = '全文檢索移除失敗。';
	$lang['strconfdropftsconfig'] = '確定要移除全文檢索檢索組態 "%s" 嗎?';
	$lang['strconfdropftsdict'] = '確定要移除全文檢索字典 "%s" 嗎?';
	$lang['strconfdropftsmapping'] = '確定要將映射 "%s" 從全文檢索組態 "%s" 中移除嗎?';
	$lang['strftstemplate'] = '模板';
	$lang['strftsparser'] = '分析器';
	$lang['strftsconfigneedsname'] = '必須指定全文檢索組態名稱。';
	$lang['strftsconfigcreated'] = '全文檢索組態已創建';
	$lang['strftsconfigcreatedbad'] = '全文檢索組態創建失敗。';
	$lang['strftsmapping'] = '映射';
	$lang['strftsdicts'] = '字典';
	$lang['strftsdict'] = '字典';
	$lang['strftsemptymap'] = '空的全文檢索組態映射。';
	$lang['strftswithmap'] = '附帶映射（With map）';
	$lang['strftsmakedefault'] = '為本地化作成默認值';
	$lang['strftsconfigaltered'] = '全文檢索已修改。';
	$lang['strftsconfigalteredbad'] = '全文檢索修改失敗。';
	$lang['strftsconfigmap'] = '全文檢索組態映射';
	$lang['strftsparsers'] = '全文檢索分析器';
	$lang['strftsnoparsers'] = '無有效的全文檢索分析器。';
	$lang['strftsnodicts'] = '無有效的全文檢索字典。';
	$lang['strftsdictcreated'] = '全文檢索字典已創建';
	$lang['strftsdictcreatedbad'] = '全文檢索字典創建失敗。';
	$lang['strftslexize'] = '詞彙';
	$lang['strftsinit'] = '初始化';
	$lang['strftsoptionsvalues'] = '選項和值';
	$lang['strftsdictneedsname'] = '必須指定全文檢索字典名稱。';
	$lang['strftsdictdropped'] = '全文檢索字典已移除。';
	$lang['strftsdictdroppedbad'] = '全文檢索字典移除失敗。';
	$lang['strftsdictaltered'] = '全文檢索字典已修改。';
	$lang['strftsdictalteredbad'] = '全文檢索字典修改失敗。';
	$lang['strftsaddmapping'] = '增加映射';
	$lang['strftsspecifymappingtodrop'] = '必須最少指定一個映射來移除';
	$lang['strftsspecifyconfigtoalter'] = '必須指定一個全文檢索配置來修改';
	$lang['strftsmappingdropped'] = '全文檢索映射已刪除。';
	$lang['strftsmappingdroppedbad'] = '全文檢索映射移除失敗。';
	$lang['strftsnodictionaries'] = '查無字典。';
	$lang['strftsmappingaltered'] = '全文檢索映射已修改。';
	$lang['strftsmappingalteredbad'] = '全文檢索映射修改失敗。';
	$lang['strftsmappingadded'] = '全文檢索映射已增加。';
	$lang['strftsmappingaddedbad'] = '全文檢索映射增加失敗。';
	$lang['strftstabconfigs'] = '組態';
	$lang['strftstabdicts'] = '字典';
	$lang['strftstabparsers'] = '分析器';
	$lang['strftsaddmapping'] = '增加映射';
	$lang['strftsspecifymappingtodrop'] = '必须最少指定一个映射来移除';
	$lang['strftsspecifyconfigtoalter'] = '必须指定一个全文检索組態来修改';
	$lang['strftsmappingdropped'] = '全文检索映射已删除。';
	$lang['strftsmappingdroppedbad'] = '全文检索映射移除失败。';
	$lang['strftsnodictionaries'] = '查无字典。';
	$lang['strftsmappingaltered'] = '全文检索映射已修改。';
	$lang['strftsmappingalteredbad'] = '全文检索映射修改失败。';
	$lang['strftsmappingadded'] = '全文检索映射已增加。';
	$lang['strftsmappingaddedbad'] = '全文检索映射增加失败。';
	$lang['strftsmappingdropped'] = '全文检索映射已删除。';
	$lang['strftsmappingdroppedbad'] = '全文检索映射移除失败。';
	$lang['strftstabconfigs'] = '組態';
	$lang['strftstabdicts'] = '字典';
	$lang['strftstabparsers'] = '分析器';
?>
