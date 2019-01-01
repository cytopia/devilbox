<?php

	/**
	 * Translated by Chih-Hsin Lee [chlee@femh.org.tw]
	 * $Id: chinese-tr.php,v 1.15 2007/04/24 11:42:07 soranzo Exp $
	 *
	 *
	 */

	// Language and character set
	$lang['applang'] = '正體中文（big5）';
	$lang['applocale'] = 'zh-TW';
	$lang['applangdir'] = 'ltr';
	
	// Welcome  
	$lang['strintro'] = '歡迎使用phpPgAdmin.';
	$lang['strppahome'] = 'phpPgAdmin首頁';
	$lang['strpgsqlhome'] = 'PostgreSQL首頁';
	$lang['strpgsqlhome_url'] = 'http://www.postgresql.org/';
	$lang['strlocaldocs'] = 'PostgreSQL 文件 (本機)';
	$lang['strreportbug'] = '通報程式臭蟲';
	$lang['strviewfaq'] = '常見問與答';
	$lang['strviewfaq_url'] = 'http://phppgadmin.sourceforge.net/doku.php?id=faq';
	
	// Basic strings
	$lang['strlogin'] = '登入';
	$lang['strloginfailed'] = '登入失敗';
	$lang['strlogindisallowed'] = '伺服器拒絕登入';
	$lang['strserver'] = '伺服器';
	$lang['strlogout'] = '退出';
	$lang['strowner'] = '所屬人';
	$lang['straction'] = '功能';
	$lang['stractions'] = '功能';
	$lang['strname'] = '名字';
	$lang['strdefinition'] = '定義';
	$lang['strproperties'] = '屬性';
	$lang['strbrowse'] = '瀏覽';
	$lang['strdrop'] = '刪除';
	$lang['strdropped'] = '已刪除';
	$lang['strnull'] = '允許空值';
	$lang['strnotnull'] = '不允許空值';
	$lang['strprev'] = '< 上一步';
	$lang['strnext'] = '下一步 >';
	$lang['strfirst'] = '<< 第一步';
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
	$lang['strsave'] = '儲存';
	$lang['strreset'] = '重設';
	$lang['strinsert'] = '插入';
	$lang['strselect'] = '選取';
	$lang['strdelete'] = '刪除';
	$lang['strupdate'] = '更新';
	$lang['strreferences'] = '參考';
	$lang['stryes'] = '是';
	$lang['strno'] = '否';
	$lang['strtrue'] = '真';
	$lang['strfalse'] = '假';
	$lang['stredit'] = '修改';
	$lang['strcolumns'] = '資料行';
	$lang['strrows'] = '資料列';
	$lang['strrowsaff'] = '資料列受影響。';
	$lang['strobjects'] = '物件';
	$lang['strexample'] = '例如：';
	$lang['strback'] = '返回';
	$lang['strqueryresults'] = '查詢結果';
	$lang['strshow'] = '顯示';
	$lang['strempty'] = '空';
	$lang['strlanguage'] = '語言';
	$lang['strencoding'] = '編碼';
	$lang['strvalue'] = '值';
	$lang['strunique'] = '獨一鍵';
	$lang['strprimary'] = '主索引鍵';
	$lang['strexport'] = '匯出';
	$lang['strimport'] = '匯入';
	$lang['strsql'] = 'SQL';
	$lang['strgo'] = '開始';
	$lang['stradmin'] = '管理';
	$lang['strvacuum'] = '清理(Vacuum)';
	$lang['stranalyze'] = '分析';
	$lang['strclusterindex'] = '叢集';
	$lang['strclustered'] = '叢集?';
	$lang['strreindex'] = '重建索引';
	$lang['strrun'] = '執行';
	$lang['stradd'] = '加入';
	$lang['strevent'] = '事件';
	$lang['strwhere'] = 'Where';
	$lang['strinstead'] = 'Do Instead';
	$lang['strwhen'] = '當';
	$lang['strformat'] = '格式';
	$lang['strdata'] = '資料';
	$lang['strconfirm'] = '確認';
	$lang['strexpression'] = '表示式';
	$lang['strellipsis'] = '...';
	$lang['strexpand'] = '展開';
	$lang['strcollapse'] = '摺疊';
	$lang['strexplain'] = '闡明';
	$lang['strexplainanalyze'] = '闡明分析';
	$lang['strfind'] = '尋找';
	$lang['stroptions'] = '選項';
	$lang['strrefresh'] = '重新整理';
	$lang['strdownload'] = '下載';
	$lang['strdownloadgzipped'] = '以gzip壓縮後下載';
	$lang['strinfo'] = '資訊'; 
    $lang['stroids'] = 'OIDs'; 
    $lang['stradvanced'] = '進階';
	$lang['strvariables'] = '變數';
	$lang['strprocess'] = '程序';
	$lang['strprocesses'] = '程序';
	$lang['strsetting'] = '設定';
	$lang['streditsql'] = '編輯SQL';
	$lang['strruntime'] = '總共執行時間: %s ms';
	$lang['strpaginate'] = '分頁顯示結果';
	$lang['struploadscript'] = '或是上傳一個SQL指令檔:';
	$lang['strstarttime'] = '開始時間';
	$lang['strfile'] = '檔案';
	$lang['strfileimported'] = '檔案已匯入。';

	// Error handling
	$lang['strbadconfig'] = '您的 config.inc.php 無效。請利用 config.inc.php-dist 建立您的 config.inc.php 檔。';
	$lang['strnotloaded'] = '您的 PHP 環境未安裝必需的資料庫支援。';
	$lang['strbadschema'] = '指定了無效的模式 (schema)。';
	$lang['strbadencoding'] = '資料庫無法設定用戶端的編碼方式。';
	$lang['strsqlerror'] = 'SQL 錯誤：';
	$lang['strinstatement'] = '於陳述內：';
	$lang['strinvalidparam'] = '無效的 script 變數。';
	$lang['strnodata'] = '找不到任何資料列。';
	$lang['strnoobjects'] = '找不到任何物件。';
	$lang['strrownotunique'] = '此顯料列無獨特識別項。';
	$lang['strnouploads'] = '上傳檔案功能已停用。';
	$lang['strimporterror'] = '匯入錯誤。';
	$lang['strimporterrorline'] = '匯入錯誤發生於第 %s 行。';

	// Tables
	$lang['strtable'] = '資料表';
	$lang['strtables'] = '資料表';
	$lang['strshowalltables'] = '顯示所有的資料表';
	$lang['strnotables'] = '找不到此資料表。';
	$lang['strnotable'] = '找不到任何資料表。';
	$lang['strcreatetable'] = '建立新資料表';
	$lang['strtablename'] = '資料表名';
	$lang['strtableneedsname'] = '您需為您的資料表命名。';
	$lang['strtableneedsfield'] = '您至少應指定一個欄位。';
	$lang['strtableneedscols'] = '資料表需要一定數目的資料行。';
	$lang['strtablecreated'] = '成功建立資料表。';
	$lang['strtablecreatedbad'] = '建立資料表作業失敗。';
	$lang['strconfdroptable'] = '您確定要刪除資料表 "%s"?';
	$lang['strtabledropped'] = '成功刪除資料表。';
	$lang['strtabledroppedbad'] = '刪除資料表作業失敗。';
	$lang['strconfemptytable'] = '您確定要清空資料表 "%s"?';
	$lang['strtableemptied'] = '成功清空資料表。';
	$lang['strtableemptiedbad'] = '清空資料表作業失敗。';
	$lang['strinsertrow'] = '插入資料行';
	$lang['strrowinserted'] = '成功插入資料行。';
	$lang['strrowinsertedbad'] = '插入資料行作業失敗。';
	$lang['streditrow'] = '修改資料行';
	$lang['strrowupdated'] = '成功更新資料行。';
	$lang['strrowupdatedbad'] = '更新資料行作業失敗。';
	$lang['strdeleterow'] = '刪除資料行';
	$lang['strconfdeleterow'] = '您確定要刪除些資料行?';
	$lang['strrowdeleted'] = '成功刪除資料行。';
	$lang['strrowdeletedbad'] = '資料行刪除作業失敗。';
	$lang['strsaveandrepeat'] = '儲存並重覆';
	$lang['strfield'] = '欄位';
	$lang['strfields'] = '欄位';
	$lang['strnumfields'] = '欄位數目';
	$lang['strfieldneedsname'] = '您需為您的欄位命名。';
	$lang['strselectallfields'] = '選擇所有欄位';
	$lang['strselectneedscol'] = '至少應顯示一資料列。';
	$lang['strselectunary'] = '不能為一元運算子指定數值。';
	$lang['straltercolumn'] = '修改資料列';
	$lang['strcolumnaltered'] = '成功修改資料列。';
	$lang['strcolumnalteredbad'] = '修改資料列作業失敗。';
	$lang['strconfdropcolumn'] = '您確定要刪除資料列 "%s" 於資料表 "%s"?';
	$lang['strcolumndropped'] = '成功刪除資料列。';
	$lang['strcolumndroppedbad'] = '刪除資料列作業失敗。';
	$lang['straddcolumn'] = '加入新資料欄';
	$lang['strcolumnadded'] = '成功加入資料欄。';
	$lang['strcolumnaddedbad'] = '加入資料欄作業失敗。';
	$lang['strcascade'] = 'CASCADE';
	$lang['strtablealtered'] = '資料表已修改。';
	$lang['strtablealteredbad'] = '資料表修改作業失敗。';
	$lang['strdataonly'] = '只顯示資料'; 
    $lang['strstructureonly'] = '只有結構'; 
    $lang['strstructureanddata'] = '資料和結構'; 
	$lang['strtabbed'] = 'Tabbed';
	$lang['strauto'] = '自動';

	// Users
	$lang['struser'] = '用戶';
	$lang['strusers'] = '用戶';
	$lang['strusername'] = '用戶名';
	$lang['strpassword'] = '密碼';
	$lang['strsuper'] = '超級用戶?';
	$lang['strcreatedb'] = '允許建立資料庫?';
	$lang['strexpires'] = '失效';
	$lang['strsessiondefaults'] = '預設Session';
	$lang['strnousers'] = '找不到此用戶。';
	$lang['struserupdated'] = '成功更新用戶。';
	$lang['struserupdatedbad'] = '更新用戶作業失敗。';
	$lang['strshowallusers'] = '顯示所有用戶';
	$lang['strcreateuser'] = '建立新用戶';
	$lang['struserneedsname'] = '請為此用戶命戶';
	$lang['strusercreated'] = '成功建立新用戶。';
	$lang['strusercreatedbad'] = '建立新用戶作業失敗。';
	$lang['strconfdropuser'] = '您確定要刪除用戶 "%s"?';
	$lang['struserdropped'] = '用戶已刪除。';
	$lang['struserdroppedbad'] = '刪除用戶作業失敗。';
	$lang['straccount'] = '帳戶';
	$lang['strchangepassword'] = '更改密碼';
	$lang['strpasswordchanged'] = '成功更改密碼。';
	$lang['strpasswordchangedbad'] = '更改密碼作業失敗。';
	$lang['strpasswordshort'] = '密碼太短。';
	$lang['strpasswordconfirm'] = '所輸入的兩組密碼不同。';
	
	// Groups
	$lang['strgroup'] = '群組';
	$lang['strgroups'] = '群組';
	$lang['strnogroup'] = '找不到此群組。';
	$lang['strnogroups'] = '找不到任何群組。';
	$lang['strcreategroup'] = '建立新群組';
	$lang['strshowallgroups'] = '顯示所有群組';
	$lang['strgroupneedsname'] = '您需為您的群組命名。';
	$lang['strgroupcreated'] = '成功建立群組。';
	$lang['strgroupcreatedbad'] = '群組建立作業失敗。';	
	$lang['strconfdropgroup'] = '您確定刪除群組 "%s"?';
	$lang['strgroupdropped'] = '成功刪除群組。';
	$lang['strgroupdroppedbad'] = '刪除群組作業失敗。';
	$lang['strmembers'] = '用戶';
	$lang['straddmember'] = '新增用戶';
	$lang['strmemberadded'] = '已加入用戶。';
	$lang['strmemberaddedbad'] = '新增用戶失敗。';
	$lang['strdropmember'] = '刪除用戶';
	$lang['strconfdropmember'] = '您確定要刪除用戶 "%s" 從群組 "%s"中?';
	$lang['strmemberdropped'] = '用戶已刪除。';
	$lang['strmemberdroppedbad'] = '刪除用戶作業失敗。';
	
	// Privilges
	$lang['strprivilege'] = '特權';
	$lang['strprivileges'] = '特權';
	$lang['strnoprivileges'] = '該物件有預設的所屬入特權。';
	$lang['strgrant'] = '賦予';
	$lang['strrevoke'] = '撤回';
	$lang['strgranted'] = '成功更改特權。';
	$lang['strgrantfailed'] = '更改特權作業失敗。';
	$lang['strgrantbad'] = '您應為一名使用者或群組指定至少一個特權。';
	$lang['stralterprivs'] = '更改特權';
	$lang['strgrantor'] = '授權者';
	$lang['strasterisk'] = '*';
	
	// Databases
	$lang['strdatabase'] = '資料庫';
	$lang['strdatabases'] = '資料庫';
	$lang['strshowalldatabases'] = '顯示所有資料庫';
	$lang['strnodatabase'] = '找不到此資料庫。';
	$lang['strnodatabases'] = '找不到任何資料庫。';
	$lang['strcreatedatabase'] = '建立新資料庫';
	$lang['strdatabasename'] = '資料庫名';
	$lang['strdatabaseneedsname'] = '您需為您的資料庫命名。';
	$lang['strdatabasecreated'] = '成功建立資料庫。';
	$lang['strdatabasecreatedbad'] = '建立資料庫作業失敗。';	
	$lang['strconfdropdatabase'] = '您確定要刪除資料庫 "%s"?';
	$lang['strdatabasedropped'] = '成功刪除資料庫。';
	$lang['strdatabasedroppedbad'] = '刪除資料庫作業失敗。';
	$lang['strentersql'] = '於下方輸入所要執行的 SQL 陳述式：';
	$lang['strsqlexecuted'] = '成功執行 SQL 。';
	$lang['strvacuumgood'] = '清理(Vacuum)作業完成。';
	$lang['strvacuumbad'] = '清理(Vacuum)作業失敗。';
	$lang['stranalyzegood'] = '分析作業完成。';
	$lang['stranalyzebad'] = '分析作業失敗.';
	$lang['strreindexgood'] = '索引重建完成。';
	$lang['strreindexbad'] = '索引重建失敗。';
	$lang['strfull'] = '完整';
	$lang['strfreeze'] = '凍結';
	$lang['strforce'] = '強制';
	
	// Views
	$lang['strview'] = '視表';
	$lang['strviews'] = '視表';
	$lang['strshowallviews'] = '顯示所有視表';
	$lang['strnoview'] = '找不到此視表。';
	$lang['strnoviews'] = '找不到任何視表。';
	$lang['strcreateview'] = '建立新視表';
	$lang['strviewname'] = '視表名';
	$lang['strviewneedsname'] = '您需為您的視表命名。';
	$lang['strviewneedsdef'] = '您必須定義你的視表。';
	$lang['strviewneedsfields'] = '請選擇要加入視表的資料欄。';
	$lang['strviewcreated'] = '成功建立新視表。';
	$lang['strviewcreatedbad'] = '建立新視表作業失敗';
	$lang['strconfdropview'] = '您確定要刪除視表 "%s"?';
	$lang['strviewdropped'] = '成功刪除視表。';
	$lang['strviewdroppedbad'] = '刪除視表作業失敗。';
	$lang['strviewupdated'] = '成功更新視表。';
	$lang['strviewupdatedbad'] = '更新視表作業失敗。';
	$lang['strviewlink'] = '連結索引';
	$lang['strviewconditions'] = '額外條件';
	$lang['strcreateviewwiz'] = '使用視表精靈';

	// Sequences
	$lang['strsequence'] = '序列';
	$lang['strsequences'] = '序列';
	$lang['strshowallsequences'] = '顯示所有序列';
	$lang['strnosequence'] = '找不到此序列。';
	$lang['strnosequences'] = '找不到任何序列。';
	$lang['strcreatesequence'] = '建立新序列';
	$lang['strlastvalue'] = '結束值';
	$lang['strincrementby'] = '增量 (加/減) ';	
	$lang['strstartvalue'] = '啟始值';
	$lang['strmaxvalue'] = '最大值';
	$lang['strminvalue'] = '最少值';
	$lang['strcachevalue'] = '快取值';
	$lang['strlogcount'] = '登錄數量';
	$lang['striscycled'] = '循環?';
	$lang['strsequenceneedsname'] = '您需為您的序列命名。';
	$lang['strsequencecreated'] = '成功建立新序列。';
	$lang['strsequencecreatedbad'] = '建立新序列作業失敗。'; 
	$lang['strconfdropsequence'] = '您確定要刪除序列 "%s"?';
	$lang['strsequencedropped'] = '成功刪除序列。';
	$lang['strsequencedroppedbad'] = '刪除序列作業失敗。';
	$lang['strsequencereset'] = '已重設序列。'; 
    $lang['strsequenceresetbad'] = '重設序列失敗。'; 
    
	// Indexes
	$lang['strindex'] = '索引';
	$lang['strindexes'] = '索引';
	$lang['strindexname'] = '索引名';
	$lang['strshowallindexes'] = '顯示所有的索引';
	$lang['strnoindex'] = '找不到此索引。';
	$lang['strnoindexes'] = '找不到任何索引。';
	$lang['strcreateindex'] = '建立新索引';
	$lang['strtabname'] = '檢索名';
	$lang['strcolumnname'] = '資料列名';
	$lang['strindexneedsname'] = '您需為您的索引命名。';
	$lang['strindexneedscols'] = '索引應有一定數問的資料列。';
	$lang['strindexcreated'] = '成功建立新索引';
	$lang['strindexcreatedbad'] = '建立索引作業失敗。';
	$lang['strconfdropindex'] = '您確定要刪除索引 "%s"?';
	$lang['strindexdropped'] = '成功刪除索引。';
	$lang['strindexdroppedbad'] = '刪除索引作業失敗。';
	$lang['strkeyname'] = '鍵名';
	$lang['struniquekey'] = '獨一鍵';
	$lang['strprimarykey'] = '主索引鍵';
 	$lang['strindextype'] = '索引類型';
	$lang['strtablecolumnlist'] = '資料表所含的資料列';
	$lang['strindexcolumnlist'] = '索引所含的資料列';
    $lang['strconfcluster'] = '您確定要叢集 "%s"?'; 
    $lang['strclusteredgood'] = '叢集完成。'; 
    $lang['strclusteredbad'] = '叢集失敗。'; 

	// Rules
	$lang['strrules'] = '規則';
	$lang['strrule'] = '規則';
	$lang['strshowallrules'] = '顯示所有的規則';
	$lang['strnorule'] = '找不到此規則。';
	$lang['strnorules'] = '找不到任何規則。';
	$lang['strcreaterule'] = '建立新規則';
	$lang['strrulename'] = '規則名';
	$lang['strruleneedsname'] = '您需為您的規則命名。';
	$lang['strrulecreated'] = '成功建立新規則。';
	$lang['strrulecreatedbad'] = '建立新規則作業失敗。';
	$lang['strconfdroprule'] = '您確定要刪除 "%s" 於 "%s"?';
	$lang['strruledropped'] = '成功刪除規則。';
	$lang['strruledroppedbad'] = '刪除規則作業失敗。';

	// Constraints
	$lang['strconstraints'] = '約束';
	$lang['strshowallconstraints'] = '顯示所有的約束';
	$lang['strnoconstraints'] = '找不到此約束。';
	$lang['strcreateconstraint'] = '建立新約束';
	$lang['strconstraintcreated'] = '成功建立新約束。';
	$lang['strconstraintcreatedbad'] = '新建約束作業失敗。';
	$lang['strconfdropconstraint'] = '您確定要刪除約束 "%s" 於 "%s"?';
	$lang['strconstraintdropped'] = '成功刪除約束。';
	$lang['strconstraintdroppedbad'] = '刪除約束作業失敗。';
	$lang['straddcheck'] = '加入新查驗 (check)';
	$lang['strcheckneedsdefinition'] = '您需定義您的查驗 (check)。';
	$lang['strcheckadded'] = '成功加入新查驗 (check)。';
	$lang['strcheckaddedbad'] = '加入新查驗 (check) 作業失敗。';
	$lang['straddpk'] = '加入主索引鍵';
	$lang['strpkneedscols'] = '主索引鍵至少應包含一個資料行。';
	$lang['strpkadded'] = '成功加入主索引鍵。';
	$lang['strpkaddedbad'] = '加入主索引鍵作作業失敗。';
	$lang['stradduniq'] = '加入獨一鍵';
	$lang['struniqneedscols'] = '獨一鍵至少應包含一個資料行。';
	$lang['struniqadded'] = '成功加入獨一鍵。';
	$lang['struniqaddedbad'] = '加入獨一鍵作業失敗。';
	$lang['straddfk'] = '加入外部索引鍵';
	$lang['strfkneedscols'] = '外部索引鍵至少應包含一個資料行。';
	$lang['strfkneedstarget'] = '外部索引鍵需參照目標資料表。';
	$lang['strfkadded'] = '成功加入外部索引鍵。';
	$lang['strfkaddedbad'] = '加入外部索引鍵作業失敗。';
	$lang['strfktarget'] = '目標資料表';
	$lang['strfkcolumnlist'] = '鍵所含的資料行';
	$lang['strondelete'] = '於刪除時';
	$lang['stronupdate'] = '於更改時';	

	// Functions
	$lang['strfunction'] = '函數';
	$lang['strfunctions'] = '函數';
	$lang['strshowallfunctions'] = '顯示所有的函數';
	$lang['strnofunction'] = '找不到此函數。';
	$lang['strnofunctions'] = '找不到任何函數。';
	$lang['strcreatefunction'] = '建立新函數';
	$lang['strfunctionname'] = '函數名';
	$lang['strreturns'] = '返回';
	$lang['strarguments'] = '參數';
	$lang['strproglanguage'] = '程式語言';
	$lang['strfunctionneedsname'] = '您需為您的函數命名。';
	$lang['strfunctionneedsdef'] = '您必須定義您的函數。';
	$lang['strfunctioncreated'] = '成功建立新函數。';
	$lang['strfunctioncreatedbad'] = '新建函數作業失敗。';
	$lang['strconfdropfunction'] = '您確定要刪除函數 "%s"?';
	$lang['strfunctiondropped'] = '成功刪除函數。';
	$lang['strfunctiondroppedbad'] = '刪除函數作業失敗。';
	$lang['strfunctionupdated'] = '成功更改函數。';
	$lang['strfunctionupdatedbad'] = '更改函數作業失敗。';

	// Triggers
	$lang['strtrigger'] = '觸發器';
	$lang['strtriggers'] = '觸發器';
	$lang['strshowalltriggers'] = '顯示所有的觸發器';
	$lang['strnotrigger'] = '找不到此觸發器。';
	$lang['strnotriggers'] = '找不到任何觸發器。';
	$lang['strcreatetrigger'] = '建立新觸發器';
	$lang['strtriggerneedsname'] = '您需為您的觸發器命名。';
	$lang['strtriggerneedsfunc'] = '您必須為你的觸發器指定一個函數。';
	$lang['strtriggercreated'] = '成功建立新觸發器。';
	$lang['strtriggercreatedbad'] = '建立觸發器作業失敗。';
	$lang['strconfdroptrigger'] = '您確定要刪除觸發器 "%s" 於 "%s"?';
	$lang['strtriggerdropped'] = '成功刪除觸發器。';
	$lang['strtriggerdroppedbad'] = '刪除觸發器作業失敗。';
	$lang['strtriggeraltered'] = '觸發器已修改。';
	$lang['strtriggeralteredbad'] = '修改觸發器作業失敗。';

	// Types
	$lang['strtype'] = '類型';
	$lang['strtypes'] = '類型';
	$lang['strshowalltypes'] = '顯示所有的類型';
	$lang['strnotype'] = '找不到此類型。';
	$lang['strnotypes'] = '找不到任何類型。';
	$lang['strcreatetype'] = '建立新類型';
	$lang['strtypename'] = '類型名';
	$lang['strinputfn'] = '輸入函數';
	$lang['stroutputfn'] = '輸出函數';
	$lang['strpassbyval'] = '以值傳送?';
	$lang['stralignment'] = '排列';
	$lang['strelement'] = '元素';
	$lang['strdelimiter'] = '分隔符號';
	$lang['strstorage'] = '儲存';
	$lang['strtypeneedsname'] = '您需為您的類型命名。';
	$lang['strtypeneedslen'] = '您必須指定您的類型的長度。';
	$lang['strtypecreated'] = '成功建立新類型';
	$lang['strtypecreatedbad'] = ' 建立類型作業失敗。';
	$lang['strconfdroptype'] = '您確定要刪除類型 "%s"?';
	$lang['strtypedropped'] = '成功刪除類型。';
	$lang['strtypedroppedbad'] = '刪除類型作業失敗。';

	// Schemas
	$lang['strschema'] = '模式';
	$lang['strschemas'] = '模式';
	$lang['strshowallschemas'] = '顯示所有的模式';
	$lang['strnoschema'] = '找不到此模式';
	$lang['strnoschemas'] = '找不到任何模式。';
	$lang['strcreateschema'] = '建立新模式';
	$lang['strschemaname'] = '模式名';
	$lang['strschemaneedsname'] = '您需為您的模式命名。';
	$lang['strschemacreated'] = '成功建立新模式。';
	$lang['strschemacreatedbad'] = '建立模式作業失敗。';
	$lang['strconfdropschema'] = '您確定要刪除模式 "%s"?';
	$lang['strschemadropped'] = '成功刪除模式。 ';
	$lang['strschemadroppedbad'] = '刪除模式作業失敗。';
	$lang['strschemaaltered'] = '成功修改模式';
	$lang['strschemaalteredbad'] = '修改模式失敗。';
	
	// Reports
	
	// Domains
	$lang['strdomain'] = '領域';
	$lang['strdomains'] = '領域';
	$lang['strshowalldomains'] = '顯示所有領域';
	$lang['strnodomains'] = '找不到任何領域。';
	$lang['strcreatedomain'] = '新建領域';
	$lang['strdomaindropped'] = '領域已刪除。';
	$lang['strdomaindroppedbad'] = '領域刪除作業失敗。';
	$lang['strconfdropdomain'] = '您確定要刪除領域 "%s"?';
	$lang['strdomainneedsname'] = '您需為此領域命名。';
	$lang['strdomaincreated'] = '領域已建立。';
	$lang['strdomaincreatedbad'] = '新建領域作業失敗。';	
	$lang['strdomainaltered'] = '領域已修改。';
	$lang['strdomainalteredbad'] = '修改領域作業失敗。';
	
	// Operators
	$lang['stroperator'] = '運算子';
	$lang['stroperators'] = '運算子';
	$lang['strshowalloperators'] = '顯示所有運算子s';
	$lang['strnooperator'] = '找不到運算子。';
	$lang['strnooperators'] = '找不到任何運算子。';
	$lang['strcreateoperator'] = '新建運算子';
	$lang['strleftarg'] = '左引數型態';
	$lang['strrightarg'] = '右引數型態';
    $lang['strcommutator'] = '轉換器'; 
    $lang['strnegator'] = '否定器'; 
    $lang['strrestrict'] = '限制'; 
    $lang['strjoin'] = '結合'; 
    $lang['strhashes'] = 'Hashes'; 
    $lang['strmerges'] = '合併'; 
    $lang['strleftsort'] = '左排序'; 
    $lang['strrightsort'] = '右排序'; 
    $lang['strlessthan'] = '小於'; 
    $lang['strgreaterthan'] = '大於'; 
	$lang['stroperatorneedsname'] = '您需為您的運算子命名。';
	$lang['stroperatorcreated'] = '運算子已建立';
	$lang['stroperatorcreatedbad'] = '運算子新建作業失敗。';
	$lang['strconfdropoperator'] = '您確定要刪除運算子 "%s"?';
	$lang['stroperatordropped'] = '運算子已刪除。';
	$lang['stroperatordroppedbad'] = '運算子刪除失敗。';

	// Casts 
   $lang['strcasts'] = '型別轉換'; 
   $lang['strnocasts'] = '找不到型別轉換。'; 
   $lang['strsourcetype'] = '原始型別'; 
   $lang['strtargettype'] = '目摽型別'; 
   $lang['strimplicit'] = '隱含'; 
   $lang['strinassignment'] = '指派中'; 
   $lang['strbinarycompat'] = '(二元相符)'; 

   // Conversions 
   $lang['strconversions'] = '轉換'; 
   $lang['strnoconversions'] = '找不到轉換。'; 
   $lang['strsourceencoding'] = '原始編碼'; 
   $lang['strtargetencoding'] = '目標編碼'; 

   // Languages 
   $lang['strlanguages'] = '語言'; 
   $lang['strnolanguages'] = '找不到語言。'; 
   $lang['strtrusted'] = '受信任的'; 

   // Info 
   $lang['strnoinfo'] = '無法取得資訊。'; 
   $lang['strreferringtables'] = '參照資料表'; 
   $lang['strparenttables'] = '父資料表'; 
   $lang['strchildtables'] = '子資料表'; 

   // Aggregates
   $lang['straggregates'] = '匯總';
   $lang['strnoaggregates'] = '找不到任何匯總。';
   $lang['stralltypes'] = '(全部類型)';	
	
   // Operator Classes
   $lang['stropclasses'] = '運算子類別';
   $lang['strnoopclasses'] = '找不到任何運算類別。';
   $lang['straccessmethod'] = '拒絕存取';
   
   // Stats and performance
   $lang['strrowperf'] = '資料行效率';
   $lang['strioperf'] = 'I/O效率';
   $lang['stridxrowperf'] = '索引行效率';
   $lang['stridxioperf'] = '索引I/O效率';
   $lang['strpercent'] = '%';
   $lang['strsequential'] = '循序';
   $lang['strscan'] = '掃描';
   $lang['strread'] = '讀取';
   $lang['strfetch'] = 'Fetch';
   $lang['strheap'] = 'Heap';
   $lang['strtoast'] = 'TOAST';
   $lang['strtoastindex'] = 'TOAST索引';
   $lang['strcache'] = '快取';
   $lang['strdisk'] = '磁碟';
   $lang['strrows2'] = '資料行';
   
	// Miscellaneous
   $lang['strtopbar'] = '%s 執行於 %s:%s － 您是 "%s"';
   $lang['strtimefmt'] = 'jS M, Y g:iA';
   $lang['strhelp'] = '說明';
	
?>
