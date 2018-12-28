<?php

/**
* @maintainer He Wei Ping [laser@zhengmai.com.cn]
*/

// Language and character set
$lang['applang'] = '简体中文（统一码）';
$lang['applocale'] = 'zh-CN';
$lang['applangdir'] = 'ltr';

// Basic strings
$lang['strintro'] = '迎使用 phpPgAdmin。';
$lang['strlogin'] = '登录';
$lang['strloginfailed'] = '登录失败';
$lang['strserver'] = '服务器';
$lang['strlogout'] = '注销';
$lang['strowner'] = '所属人';
$lang['straction'] = '功能';
$lang['stractions'] = '功能';
$lang['strname'] = '名字';
$lang['strdefinition'] = '定义';
$lang['strsequence'] = '序列';
$lang['strsequences'] = '序列';
$lang['stroperators'] = '操作';
$lang['strtypes'] = '类型';
$lang['straggregates'] = '聚集';
$lang['strproperties'] = '属性';
$lang['strbrowse'] = '浏览';
$lang['strdrop'] = '删除';
$lang['strdropped'] = '已删除';
$lang['strnull'] = '空';
$lang['strnotnull'] = '非空';
$lang['strprev'] = '上一个';
$lang['strnext'] = '下一个';
$lang['strfailed'] = '失败';
$lang['strcreate'] = '创建';
$lang['strcomment'] = '注释';
//$lang['strnext'] = 'Next';
$lang['strlength'] = '长度';
$lang['strdefault'] = '默认';
$lang['stralter'] = '更改';
$lang['strcancel'] = '取消';
$lang['strsave'] = '存储';
$lang['strinsert'] = '插入';
$lang['strselect'] = '选取';
$lang['strdelete'] = '删除';
$lang['strupdate'] = '更新';
$lang['strreferences'] = '参考';
$lang['stryes'] = '是';
$lang['strno'] = '否';
$lang['stredit'] = '编辑';
$lang['strrows'] = '行';
$lang['strexample'] = '如：';
$lang['strback'] = '返回';
$lang['strqueryresults'] = '查寻结果';
$lang['strshow'] = '显示';
$lang['strempty'] = '空';
$lang['strlanguage'] = '语言';

// Error handling
$lang['strbadconfig'] = '您的 config.inc.php 已失效。您需要自行通过 config.inc.php-ist 修改。';
$lang['strnotloaded'] = '您的 PHP 中没有完整的数据库支持。';
$lang['strsqlerror'] = 'SQL:错误';
$lang['strinstatement'] = 'In statement：';
$lang['strinvalidparam'] = '无效的脚本参数';
$lang['strnodata'] = '查无此行。';

// Tables
$lang['strnotables'] = '查无此表。';
$lang['strnotable'] = '查无此表。';
$lang['strtable'] = '数据表';
$lang['strtables'] = '数据表';
$lang['strtablecreated'] = '建表完成。';
$lang['strtablecreatedbad'] = '建表失败';
$lang['strtableneedsfield'] = '至少需要一个数据段。';
$lang['strinsertrow'] = '插入行';
$lang['strrowinserted'] = '插入行完成。';
$lang['strrowinsertedbad'] = '先插入行。';
$lang['streditrow'] = '更改行';
$lang['strrowupdated'] = '完成行更新。';
$lang['strrowupdatedbad'] = '更新行失败。';
$lang['strdeleterow'] = '删除行';
$lang['strconfdeleterow'] = '真的要除所有的行？';
$lang['strrowdeleted'] = '删除除行完成。';
$lang['strrowdeletedbad'] = '除行失败。';
$lang['strsaveandrepeat'] = '重复存储';
$lang['strconfemptytable'] = '真的要清空"%s"数据表？';
$lang['strtableemptied'] = '数据表清空完成。';
$lang['strtableemptiedbad'] = '数据表清空失败。';
$lang['strconfdroptable'] = '真的要删除除"%s"数据表？';
$lang['strtabledropped'] = '善除数据表完成。';
$lang['strtabledroppedbad'] = '删除数据表失败。';

// Users
$lang['struseradmin'] = '用户管理';
$lang['struser'] = '用户';
$lang['strusers'] = '用户';
$lang['strusername'] = '用名';
$lang['strpassword'] = '密码';
$lang['strsuper'] = '超级用户';
$lang['strcreatedb'] = '建库';
$lang['strexpires'] = '过期';
$lang['strnousers'] = '查无此用户';

// Groups
$lang['strgroupadmin'] = '组管理';
$lang['strgroup'] = '组';
$lang['strgroups'] = '群组';
$lang['strnogroups'] = '查无群组。';
$lang['strcreategroup'] = '创建组';
$lang['strshowallgroups'] = '显示所有群组';
$lang['strgroupneedsname'] = '你必给您组命名。';
$lang['strgroupcreated'] = '建组完成。';
$lang['strgroupcreatedbad'] = '建组失败。';
$lang['strconfdropgroup'] = '真的要删除"%s"组？';
$lang['strgroupdropped'] = '删除组完成。';
$lang['strgroupdroppedbad'] = '删除组失败。';
$lang['strmembers'] = '成员';

// Privilges
$lang['strprivileges'] = '特权';
$lang['strgrant'] = '赋予';
$lang['strrevoke'] = '撤回';

// Databases
$lang['strdatabase'] = '数据库';
$lang['strdatabases'] = '数据库';
$lang['strnodatabases'] = '查无此数据库。';
$lang['strdatabaseneedsname'] = '你必须给您的数据库命名。';

// Views
$lang['strviewneedsname'] = '你必须给您的视图命名。';
$lang['strviewneedsdef'] = '你必须定义您的视图。';
$lang['strcreateview'] = '建立视图';
$lang['strnoviews'] = '查无视图。';
$lang['strview'] = '视图';
$lang['strviews'] = '视图';

// Sequences
$lang['strnosequences'] = '查无序列。';
$lang['strsequencename'] = '序列名称';
$lang['strlastvalue'] = '最后的数目';
$lang['strincrementby'] = '增量（加／减）';
$lang['strmaxvalue'] = '最大值';
$lang['strminvalue'] = '最小值';
$lang['strcachevalue'] = 'cache_value';
$lang['strlogcount'] = 'log_cnt';
$lang['striscycled'] = 'is_cycled';
$lang['strreset'] = '重置';

// Indexes
$lang['strindexes'] = '索引';
$lang['strindexname'] = '索引名';
$lang['strtabname'] = 'Tab Name';
$lang['strcolumnname'] = 'Column Name';
$lang['struniquekey'] = '唯一键';
$lang['strprimarykey'] = '主键';
$lang['strshowallindexes'] = '显示所有索引';
$lang['strcreateindex'] = '创建索引';
$lang['strindexneedsname'] = '你必须给您的索引命名。';
$lang['strindexneedscols'] = '你必须给你的字段赋予一个正整数。';
$lang['strindexcreated'] = '创建索引完成';
$lang['strindexcreatedbad'] = '创建索引失败.';
$lang['strconfdropindex'] = '真的要删除"%s"索引？';
$lang['strindexdropped'] = '删除索引完成。';
$lang['strindexdroppedbad'] = '删除除索引失败。';

// Rules
$lang['strrules'] = '规则';
$lang['strrule'] = '规则';
$lang['strnorules'] = '查无此规则';
$lang['strcreaterule'] = '创建规则';

// Tables
$lang['strfield'] = '列';
$lang['strfields'] = '列';
$lang['strtype'] = '类型';
$lang['strvalue'] = '值';
$lang['strshowalltables'] = '示所有表。';
$lang['strunique'] = '唯一';
$lang['strprimary'] = '主';
$lang['strkeyname'] = '键名';
$lang['strnumfields'] = '列数';
$lang['strcreatetable'] = '创建表';
$lang['strtableneedsname'] = '你必您的索引命名。';
$lang['strtableneedscols'] = '你必须给你的字段赋予一个正整数。';
$lang['strexport'] = '导出';
$lang['strconstraints'] = '强制';
$lang['strcolumns'] = '列';

// Functions
$lang['strnofunctions'] = '查无此函数';
$lang['strfunction'] = '函数';
$lang['strfunctions'] = '函数';
$lang['strreturns'] = 'Returns';
$lang['strarguments'] = '参数';
$lang['strproglanguage'] = '语言';
$lang['strfunctionneedsname'] = '你必须给您的函数命名。';
$lang['strfunctionneedsdef'] = '你必须定义您的函数。';

// Triggers
$lang['strtrigger'] = '触发器';
$lang['strtriggers'] = '触发器';
$lang['strnotriggers'] = '查无此触发器。';
$lang['strcreatetrigger'] = '创建触发器';

// Types
$lang['strtype'] = '类型';
$lang['strtypes'] = '类型';
$lang['strnotypes'] = '查无此类型。';
$lang['strcreatetype'] = '创建类型';
$lang['strconfdroptype'] = '真的要删除"%s"类型？';
$lang['strtypedropped'] = '删除类型完成。';
$lang['strtypedroppedbad'] = '删除类型失败。';
$lang['strtypecreated'] = '创建类型完成。';
$lang['strtypecreatedbad'] = '建型失败。';
$lang['strshowalltypes'] = '显示所有的类型';
$lang['strinputfn'] = '输入功能';
$lang['stroutputfn'] = '输出功能';
$lang['strpassbyval'] = 'Passed by val？';
$lang['stralignment'] = 'Alignment';
$lang['strelement'] = '元素';
$lang['strdelimiter'] = '分隔符';
$lang['strstorage'] = '磁盘存储';
$lang['strtypeneedsname'] = '你必给您的类型命名。';
$lang['strtypeneedslen'] = '你必给您的类型定义一个长度。';

// Schemas
$lang['strschema'] = '模式';
$lang['strschemas'] = '模式';
$lang['strcreateschema'] = '创建模式';
$lang['strnoschemas'] = '没有此模式';
$lang['strconfdropschema'] = '你确定要删除"%s"模式么？';
$lang['strschemadropped'] = '模式已删除';
$lang['strschemadroppedbad'] = '模式删除失败';
$lang['strschemacreated'] = '模式已建立';
$lang['strschemacreatedbad'] = '创建模式失败';
$lang['strshowallschemas'] = '显示所有模式?';
$lang['strschemaneedsname'] = '必须给模式命名';

$lang['strcreated'] = '创建完成';
$lang['strok'] = '完成';
$lang['strencoding'] = '编码';
$lang['strsql'] = 'SQL码';
$lang['stradmin'] = '管理';
$lang['strvacuum'] = '清理(Vacuum)';
$lang['stranalyze'] = '分析';
$lang['strreindex'] = '重建索引';
$lang['strrun'] = '执行';
$lang['stradd'] = '创建';
$lang['strformat'] = '格式化';
$lang['strtablename'] = '资料表名称';
$lang['strfieldneedsname'] = '你必须给您的数据段命名';
$lang['struserupdated'] = '用户更新';
$lang['struserupdatedbad'] = '用户更新';
$lang['strshowallusers'] = '显示所有用户';
$lang['strcreateuser'] = '创建用户';
$lang['strusercreated'] = '创建用户完成';
$lang['strusercreatedbad'] = '创建用户失败';
$lang['strconfdropuser'] = '您确定要删除用户"%s"么？';
$lang['struserdropped'] = '用户删除完成';
$lang['struserdroppedbad'] = '删除用户失败';
$lang['strnogroup'] = '查无此群组。';
$lang['strprivilege'] = '特权';
$lang['strgranted'] = '特权赋予完成';
$lang['strgrantfailed'] = '特权赋予失败';
$lang['strgrantuser'] = '特权赋予用户';
$lang['strgrantgroup'] = '特权赋予组';
$lang['strshowalldatabases'] = '显示所有数据库';
$lang['strnodatabase'] = '查无此数据库。';
$lang['strcreatedatabase'] = '创建数据库';
$lang['strdatabasename'] = '数据库名称';
$lang['strdatabasecreated'] = '创建数据库完成';
$lang['strdatabasecreatedbad'] = '创建数据库失败';
$lang['strconfdropdatabase'] = '您确定要删除数据库"%s"么？';
$lang['strdatabasedropped'] = '数据库删除完成';
$lang['strdatabasedroppedbad'] = '删除数据库失败';
$lang['strentersql'] = '请在下方输入要执行的SQL码：';
$lang['strvacuumgood'] = '清理(Vacuum)完成';
$lang['strvacuumbad'] = '清理(Vacuum)失败';
$lang['stranalyzegood'] = '分析完成';
$lang['stranalyzebad'] = '分析失败';
$lang['strshowallviews'] = '显示所视图';
$lang['strnoview'] = '查无此视图';
$lang['strviewname'] = '视图名称';
$lang['strviewcreated'] = '创建视图完成';
$lang['strviewcreatedbad'] = '创建视图失败';
$lang['strconfdropview'] = '您确定要删除视图"%s"么？';
$lang['strviewdropped'] = '视图删除完成';
$lang['strviewdroppedbad'] = '删除视图失败';
$lang['strviewupdated'] = '视图更新完成';
$lang['strviewupdatedbad'] = '视图更新失败';
$lang['strshowallsequences'] = '显示所有序列';
$lang['strnosequence'] = '查无此序列';
$lang['strcreatesequence'] = '创建序列';
$lang['strstartvalue'] = '起始值';
$lang['strsequenceneedsname'] = '你必须给您的序列命名';
$lang['strsequencecreated'] = '创建序列完成';
$lang['strsequencecreatedbad'] = '创建序列失败';
$lang['strconfdropsequence'] = '您确定要删除序列"%s"么？';
$lang['strsequencedropped'] = '序列删除完成';
$lang['strsequencedroppedbad'] = '删除序列失败';
$lang['strnoindex'] = '查无此索引';
$lang['strnoindexes'] = '查无此索引';
$lang['strindextype'] = '索引的类型';
$lang['strshowallrules'] = '显示所有规则';
$lang['strnorule'] = '查无此规则';
$lang['strrulename'] = '规则名称';
$lang['strruleneedsname'] = '你必须给您的规则命名';
$lang['strrulecreated'] = '创建规则完成';
$lang['strrulecreatedbad'] = '创建规则失败';
$lang['strconfdroprule'] = '您确定要把规则"%s"在资料库"%s"中删除么？';
$lang['strruledropped'] = '规则删除完成';
$lang['strruledroppedbad'] = '删除规则失败';
$lang['strshowallconstraints'] = '显示所有强制';
$lang['strnoconstraints'] = '查无此强制';
$lang['strcreateconstraint'] = '创建强制';
$lang['strconstraintcreated'] = '创建强制完成';
$lang['strconstraintcreatedbad'] = '创建强制失败';
$lang['strconfdropconstraint'] = '您确定要把强制"%s"在资料库"%s"中删除么？';
$lang['strconstraintdropped'] = '删除强制完成';
$lang['strconstraintdroppedbad'] = '删除强制失败';
$lang['straddpk'] = '新增主键';
$lang['strpkneedscols'] = '主键须要至少一列';
$lang['strpkadded'] = '主键新增完成';
$lang['strpkaddedbad'] = '新增主键失败';
$lang['strshowallfunctions'] = '显示所有函数';
$lang['strnofunction'] = '查无此函数';
$lang['strcreatefunction'] = '创建函数';
$lang['strfunctionname'] = '函数名称';
$lang['strfunctioncreated'] = '创建函数完成';
$lang['strfunctioncreatedbad'] = '创建函数失败';
$lang['strconfdropfunction'] = '您确定要删除函数"%s"么？';
$lang['strfunctiondropped'] = '删除函数完成.';
$lang['strfunctiondroppedbad'] = '删除函数失败';
$lang['strfunctionupdated'] = '更新函数完成.';
$lang['strfunctionupdatedbad'] = '更新函数失败';
$lang['strshowalltriggers'] = '显示所有触发器';
$lang['strnotrigger'] = '查无此触发器';
$lang['strtriggerneedsname'] = '你必须给您的触发器命名';
$lang['strtriggerneedsfunc'] = '你必须给您的触发器一个函数';
$lang['strtriggercreated'] = '创建触发器完成';
$lang['strtriggercreatedbad'] = '创建触发器失败';
$lang['strconfdroptrigger'] = '您确定要把触发器"%s"在资料库"%s"中删除么？';
$lang['strtriggerdropped'] = '删除触发器完成.';
$lang['strtriggerdroppedbad'] = '删除触发器失败';
$lang['strnotype'] = '查无此类型';
$lang['strtypename'] = '类型名称';
$lang['strnoschema'] = '查无此模式';
$lang['strschemaname'] = '模式名称';



// Miscellaneous
$lang['strtopbar'] = '%s 架于 %s：%s － 您是 "%s"';
$lang['strtimefmt'] = 'jS M, Y g:iA';

?>
