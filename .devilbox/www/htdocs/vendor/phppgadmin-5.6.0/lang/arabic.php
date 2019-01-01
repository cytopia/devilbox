<?php

 /**
  * English language file for phpPgAdmin.  Use this as a basis
  * for new translations.
  *
  * $Id: arabic.php,v 1.6 2007/04/24 11:42:07 soranzo Exp $
  */

 // Language and character set
   $lang['applang'] = 'عربي';
 $lang['applocale'] = 'ar';
 $lang['applangdir'] = 'rtl';

 // Welcome
  $lang['strintro'] = 'مرحبا الى phpPgAdmin.';
  $lang['strppahome'] = 'موقع phpPgAdmin';
  $lang['strpgsqlhome'] = 'موقع PostgreSQL';
 $lang['strpgsqlhome_url'] = 'http://www.postgresql.org/';
 $lang['strlocaldocs'] = 'تعليمات PostgreSQL (محلية)';
 $lang['strreportbug'] = 'الإبلاغ عن خطأ في البرنامج.';
 $lang['strviewfaq'] = 'قراءة الأسئلة المتكررة على الموقع';
 $lang['strviewfaq_url'] = 'http://phppgadmin.sourceforge.net/doku.php?id=faq';
 
 // Basic strings
 $lang['strlogin'] = 'الدخول';
$lang['strloginfailed'] = 'لم تنجح عملية الدخول';
 $lang['strlogindisallowed'] = 'لا يسمح بالدخول بهذه الطريقة لأسباب أمنية.';
 $lang['strserver'] = 'الخادم';
 $lang['strlogout'] = 'خروج';
 $lang['strowner'] = 'مالك Owner';
 $lang['straction'] = 'إجراء Action';
 $lang['stractions'] = 'إجراءات Actions';
 $lang['strname'] = 'الإسم';
 $lang['strdefinition'] = 'التعريف';
 $lang['strproperties'] = 'خصائص';
 $lang['strbrowse'] = 'إستعراض Browse';
 $lang['strdrop'] = 'حذف Drop';
 $lang['strdropped'] = 'محذوف Dropped';
 $lang['strnull'] = 'Null';
 $lang['strnotnull'] = 'ليس Null';
 $lang['strprev'] = '>السابق';
 $lang['strnext'] = '<التالي';
 $lang['strfirst'] = '>>الأول';
 $lang['strlast'] = 'الأخير<<';
 $lang['strfailed'] = 'فشـل';
 $lang['strcreate'] = 'إنشاء';
 $lang['strcreated'] = 'تم الإنشاء';
 $lang['strcomment'] = 'ملاحظات';
 $lang['strlength'] = 'طول';
 $lang['strdefault'] = 'الإفتراضي';
 $lang['stralter'] = 'تعديلAlter';
 $lang['strok'] = 'موافق';
 $lang['strcancel'] = 'تراجع';
 $lang['strsave'] = 'حفظ';
 $lang['strreset'] = 'إعادة Reset';
 $lang['strinsert'] = 'إدراج Insert';
 $lang['strselect'] = 'إختيار Select';
 $lang['strdelete'] = 'حذف Delete';
 $lang['strupdate'] = 'تعديل Update';
 $lang['strreferences'] = 'مراجع';
 $lang['stryes'] = 'نعم';
 $lang['strno'] = 'لا';
 $lang['strtrue'] = 'صحيح TRUE';
 $lang['strfalse'] = 'خاطئ FALSE';
 $lang['stredit'] = 'تحرير';
 $lang['strcolumns'] = 'أعمدة Columns';
 $lang['strrows'] = 'سجل/سجلات';
 $lang['strrowsaff'] = 'سجل تأثر/سجلات تأثرت';
 $lang['strobjects'] = 'object(s)';
 $lang['strexample'] = 'مثلا';
 $lang['strback'] = 'رجوع للخلف';
 $lang['strqueryresults'] = 'نتائج الإستعلام';
 $lang['strshow'] = 'اعرض';
 $lang['strempty'] = 'إفراغ Empty';
 $lang['strlanguage'] = 'اللغة';
 $lang['strencoding'] = 'الشيفرة Encoding';
 $lang['strvalue'] = 'القيمة Value';
 $lang['strunique'] = 'فريد Unique';
 $lang['strprimary'] = 'رئيسي Primary';
 $lang['strexport'] = 'تصدير Export';
 $lang['strimport'] = 'إستيراد Import';
 $lang['strsql'] = 'SQL';
 $lang['strgo'] = 'Go';
 $lang['stradmin'] = 'إدارة Admin';
 $lang['strvacuum'] = 'Vacuum';
 $lang['stranalyze'] = 'Analyze';
 $lang['strclusterindex'] = 'Cluster';
 $lang['strclustered'] = 'Clustered?';
 $lang['strreindex'] = 'Reindex';
 $lang['strrun'] = 'Run';
 $lang['stradd'] = 'إضافة';
 $lang['strevent'] = 'Event';
 $lang['strwhere'] = 'Where';
 $lang['strinstead'] = 'Do Instead';
 $lang['strwhen'] = 'When';
 $lang['strformat'] = 'Format';
 $lang['strdata'] = 'Data';
 $lang['strconfirm'] = 'تأكيد';
 $lang['strexpression'] = 'تعبير';
 $lang['strellipsis'] = '...';
 $lang['strseparator'] = ': ';
 $lang['strexpand'] = 'إفتح';
 $lang['strcollapse'] = 'سكّر';
 $lang['strexplain'] = 'Explain';
 $lang['strexplainanalyze'] = 'Explain Analyze';
 $lang['strfind'] = 'بحث';
 $lang['stroptions'] = 'خيارات';
 $lang['strrefresh'] = 'تحديث Refresh';
 $lang['strdownload'] = 'تنزيل';
 $lang['strdownloadgzipped'] = 'تنزيل على شكل ملف مضغوط بـ gzip';
 $lang['strinfo'] = 'Info';
 $lang['stroids'] = 'OIDs';
 $lang['stradvanced'] = 'Advanced';
 $lang['strvariables'] = 'Variables';
 $lang['strprocess'] = 'العملية Process';
 $lang['strprocesses'] = 'العمليات Processes';
 $lang['strsetting'] = 'Setting';
 $lang['streditsql'] = 'Edit SQL';
 $lang['strruntime'] = 'Total runtime: %s ms';
 $lang['strpaginate'] = 'Paginate results';
 $lang['struploadscript'] = 'or upload an SQL script:';
 $lang['strstarttime'] = 'Start Time';
 $lang['strfile'] = 'ملف';
 $lang['strfileimported'] = 'تم استيراد الملف.';

 // Error handling
 $lang['strbadconfig'] = 'إن الملف config.inc.php الذي لديك اصبح قديما. ستحتاج الى إعادة توليده من الملف الجديد config.inc.php-dist.';
 $lang['strnotloaded'] = 'إن اعداد  PHP الموجود على هذا الخادم لاتدعم PostgreSQL. تحتاج الى اعادة تثبيت PHP بإستخدام الخيار  --with-pgsql configure option.';
 $lang['strpostgresqlversionnotsupported'] = 'هذا الاصدار من PostgreSQL غير مدعوم. الرجاء الترقية الى الإصدار %s او أعلى.';
 $lang['strbadschema'] = 'Invalid schema specified.';
 $lang['strbadencoding'] = 'لقد فشل ضبط شيفرة العميل client encoding في قاعدة البيانات.';
 $lang['strsqlerror'] = 'خطأ SQL:';
 $lang['strinstatement'] = 'في الجملة statement:';
 $lang['strinvalidparam'] = 'Invalid script parameters.';
 $lang['strnodata'] = 'لم توجد سجلات.';
 $lang['strnoobjects'] = 'لم توجد كائنات.';
 $lang['strrownotunique'] = 'لا يوجد معرّف فريد unique identifier في هذا السجل.';
 $lang['strnouploads'] = 'تحميل الملفات غير مفعّل.';
 $lang['strimporterror'] = 'خطأ في الإستيراد.';
 $lang['strimporterrorline'] = 'خطأ في الإستيراد عند السطر: %s.';

 // Tables
 $lang['strtable'] = 'جدول Table';
 $lang['strtables'] = 'جداول Tables';
 $lang['strshowalltables'] = 'أعرض جميع الجداول Tables.';
 $lang['strnotables'] = 'لا يوجد جداول.';
 $lang['strnotable'] = 'لا يوجد جدول.';
 $lang['strcreatetable'] = 'إنشاء جدول Table جديد.';
 $lang['strtablename'] = 'إسم الجدول';
 $lang['strtableneedsname'] = 'يجب إعطاء إسم للجدول.';
 $lang['strtableneedsfield'] = 'يجب عليك تحديد على الأقل حقل واحد.';
 $lang['strtableneedscols'] = 'الجداول تتطلب عدد مقبول من الأعمدة.';
 $lang['strtablecreated'] = 'لقد تم إنشاء الجدول بنجاح.';
 $lang['strtablecreatedbad'] = 'لقد فشلت عملية إنشاء الجدول.';
 $lang['strconfdroptable'] = 'هل انت متأكد تريد حذف الجدول بإسم "%s"؟';
 $lang['strtabledropped'] = 'لقد تم حذف الجدول.';
 $lang['strtabledroppedbad'] = 'لقد فشلت عملية حذف الجدول.';
 $lang['strconfemptytable'] = 'هل انت متأكد تريد افراغ محتويات الجدول "%s"؟';
 $lang['strtableemptied'] = 'لقد تم افراغ محتويات الجدول بنجاح.';
 $lang['strtableemptiedbad'] = 'لقد فشلت عملية إفراغ محتويات الجدول.';
 $lang['strinsertrow'] = 'إدراج سجل.';
 $lang['strrowinserted'] = 'لقد تم إدراج السجل بنجاح.';
 $lang['strrowinsertedbad'] = 'لقد فشلت عملية إدراج السجل.';
 $lang['streditrow'] = 'تحرير السجل.';
 $lang['strrowupdated'] = 'تم تعديل السجل بنجاح.';
 $lang['strrowupdatedbad'] = 'لقد فشلت عملية تعديل السجل.';
 $lang['strdeleterow'] = 'إحذف السجل.';
 $lang['strconfdeleterow'] = 'هل انت متأكد تريد حذف هذا السجل؟';
 $lang['strrowdeleted'] = 'لقد تم حذف السجل بنجاح.';
 $lang['strrowdeletedbad'] = 'لقد فشلت عملية حذف السجل.';
 $lang['strinsertandrepeat'] = 'إدراج و إعادة';
 $lang['strfield'] = 'الحقل';
 $lang['strnumfields'] = 'عدد الحقول';
 $lang['strselectallfields'] = 'إختيار جميع الحقول';
 $lang['strselectneedscol'] = 'تحتاج عرض على الأقل عمود واحد.';
 $lang['strselectunary'] = 'العمليات الأحادية Unary operators لا يمكن ان يكون لها قيم.';
 $lang['straltercolumn'] = 'تعديل العمود';
 $lang['strcolumnaltered'] = 'لقد تم تعديل العمود بنجاح.';
 $lang['strcolumnalteredbad'] = 'لقد فشلت عملية تعديل العمود.';
 $lang['strconfdropcolumn'] = 'هل انت متأكد تريد حذف العمود "%s" من الجدول "%s"؟';
 $lang['strcolumndropped'] = 'لقد تم حذف العمود بنجاح.';
 $lang['strcolumndroppedbad'] = 'لقد فشلت عملية حذف العمود.';
 $lang['straddcolumn'] = 'إضافة عمود.';
 $lang['strcolumnadded'] = 'لقد تمت إضافة العمود بنجاح.';
 $lang['strcolumnaddedbad'] = 'لقد فشلت عملية إضافة العمود.';
 $lang['strcascade'] = 'CASCADE';
 $lang['strtablealtered'] = 'لقد تم تعديل الجدول بنجاح.';
 $lang['strtablealteredbad'] = 'لقد فشلت عملية تعديل الجدول.';
 $lang['strdataonly'] = 'البيانات فقط';
 $lang['strstructureonly'] = 'الهيكلية فقط';
 $lang['strstructureanddata'] = 'الهيكلية والبيانات';
 $lang['strtabbed'] = 'Tabbed';
 $lang['strauto'] = 'Auto';

 // Users
 $lang['struser'] = 'المستخدم';
 $lang['strusers'] = 'المستخدمين';
 $lang['strusername'] = 'إسم المستخدم';
 $lang['strpassword'] = 'كلمة السر';
 $lang['strsuper'] = 'مستخدم ذو صلاحيّات عليا؟';
 $lang['strcreatedb'] = 'إنشاء قاعدة بيانات؟';
 $lang['strexpires'] = 'ينتهي';
 $lang['strsessiondefaults'] = 'Session defaults';
 $lang['strnousers'] = 'لم يوجد مستخدمين.';
 $lang['struserupdated'] = 'تم تعديل المستخدم بنجاح.';
 $lang['struserupdatedbad'] = 'فشل تعديل المستخدم.';
 $lang['strshowallusers'] = 'عرض جميع المستخدمين';
 $lang['strcreateuser'] = 'إضافة مستخدم جديد';
 $lang['struserneedsname'] = 'يجب إعطاء إسم للمستخدم.';
 $lang['strusercreated'] = 'تمت عملية إضافة المستخدم بنجاح.';
 $lang['strusercreatedbad'] = 'فشلت عملية إضافة المستخدم.';
 $lang['strconfdropuser'] = 'هل انت متأكد تريد حذف المستخدم "%s"؟';
 $lang['struserdropped'] = 'تم حذف المستخدم بنجاح.';
 $lang['struserdroppedbad'] = 'فشلت عملية حذف المستخدم.';
 $lang['straccount'] = 'Account';
 $lang['strchangepassword'] = 'تغيير كلمة السر';
 $lang['strpasswordchanged'] = 'تم تغيير كلمة السر بنجاح.';
 $lang['strpasswordchangedbad'] = 'لم ينجح تغيير كلمة السر.';
 $lang['strpasswordshort'] = 'كلمة السر أقصر من الحد الأدنى.';
 $lang['strpasswordconfirm'] = 'كلمة السر المدخلة لم تتطابق مع تأكيد كلمة السر.';
 
 // Groups
 $lang['strgroup'] = 'المجموعة';
 $lang['strgroups'] = 'المجموعات';
 $lang['strnogroup'] = 'لم توجد المجموعة.';
 $lang['strnogroups'] = 'لم توجد مجموعات.';
 $lang['strcreategroup'] = 'إضافة مجموعة جديدة';
 $lang['strshowallgroups'] = 'عرض جميع المجموعات';
 $lang['strgroupneedsname'] = 'يجب إعطاء إسم للمجموعة.';
 $lang['strgroupcreated'] = 'لقد تمت إضافة المجموعة بنجاح.';
 $lang['strgroupcreatedbad'] = 'لقد فشلت عملية إضافة المجموعة.'; 
 $lang['strconfdropgroup'] = 'هل انت متأكد تريد حذف المجموعة "%s"؟';
 $lang['strgroupdropped'] = 'تم حذف المجموعة بنجاح.';
 $lang['strgroupdroppedbad'] = 'لقد فشلت عملية حذف المجموعة.';
 $lang['strmembers'] = 'الأعضاء';
 $lang['straddmember'] = 'إضافة عضو';
 $lang['strmemberadded'] = 'تمت إضافة العضو.';
 $lang['strmemberaddedbad'] = 'لقد فشلت عملية إضافة العضو.';
 $lang['strdropmember'] = 'حذف عضو';
 $lang['strconfdropmember'] = 'هل أنت متأكد تريد حذف العضو "%s" من المجموعة "%s"؟';
 $lang['strmemberdropped'] = 'تم حذف العضو.';
 $lang['strmemberdroppedbad'] = 'لقد فشل حذف العضو.';

 // Privileges
 $lang['strprivilege'] = 'الصلاحيّة';
 $lang['strprivileges'] = 'الصلاحيات';
 $lang['strnoprivileges'] = 'هذا الكائن لديه صلاحيّات المالك الإفتراضية.';
 $lang['strgrant'] = 'تصريح Grant';
 $lang['strrevoke'] = 'سحب Revoke';
 $lang['strgranted'] = 'تم تغيير الصلاحيات.';
 $lang['strgrantfailed'] = 'لقد فشل تغيير الصلاحيات.';
 $lang['strgrantbad'] = 'يجب عليك تحديد على الاقل مستخدم واحد او مجموعة واحدة و على الأقل صلاحيّة واحدة.';
 $lang['strgrantor'] = 'المصرّح Grantor';
 $lang['strasterisk'] = '*';

 // Databases
 $lang['strdatabase'] = 'قاعدة بيانات';
 $lang['strdatabases'] = 'قواعد البيانات';
 $lang['strshowalldatabases'] = 'عرض جميع قواعد البيانات';
 $lang['strnodatabase'] = 'لم يوجد قاعدة بيانات.';
 $lang['strnodatabases'] = 'لم يوجد قواعد بيانات.';
 $lang['strcreatedatabase'] = 'إنشاء قاعدة بيانات جديدة';
 $lang['strdatabasename'] = 'إسم قاعدة البيانات';
 $lang['strdatabaseneedsname'] = 'يجب عليك إعطاء إسم لقاعدة البيانات.';
 $lang['strdatabasecreated'] = 'تم إنشاء قاعدة البيانات بنجاح.';
 $lang['strdatabasecreatedbad'] = 'فشل إنشاء قاعدة البيانات.';
 $lang['strconfdropdatabase'] = 'هل أنت متأكد تريد حذف قاعدة البيانات بإسم "%s"?';
 $lang['strdatabasedropped'] = 'تم حذف قاعدة البيانات.';
 $lang['strdatabasedroppedbad'] = 'لقد فشلت عملية حذف قاعدة البيانات.';
 $lang['strentersql'] = 'أدخل الـSQL الذي تريد إستدعاءه هنا:';
 $lang['strsqlexecuted'] = 'تم إستدعاء الـSQL.';
 $lang['strvacuumgood'] = 'تمت عملية الـVacuum.';
 $lang['strvacuumbad'] = 'لقد فشلت عملية الـVacuum.';
 $lang['stranalyzegood'] = 'تمت عملية الفحص.';
 $lang['stranalyzebad'] = 'لقد فشلت عملية الفحص.';
 $lang['strreindexgood'] = 'تمت عملية إعادة الفهرسة بنجاح.';
 $lang['strreindexbad'] = 'لقد فشلت عملية إعادة الفهرسة.';
 $lang['strfull'] = 'Full';
 $lang['strfreeze'] = 'Freeze';
 $lang['strforce'] = 'Force';

 // Views
 $lang['strview'] = 'View عرض';
 $lang['strviews'] = 'عروض Views';
 $lang['strshowallviews'] = 'أعرض جميع العروض Views.';
 $lang['strnoview'] = 'لم يوجد عرض View.';
 $lang['strnoviews'] = 'لم يوجد عروض Views.';
 $lang['strcreateview'] = 'إنشاء عرض View جديد';
 $lang['strviewname'] = 'إسم العرض View';
 $lang['strviewneedsname'] = 'يجب إعطاء إسم للعرض View.';
 $lang['strviewneedsdef'] = 'يجب عليك إعطاء تعريف للعرض View.';
 $lang['strviewneedsfields'] = 'يجب عليك تحديد الحقول التي تريدها في العرض View.';
 $lang['strviewcreated'] = 'تم إنشاء العرض View.';
 $lang['strviewcreatedbad'] = 'فشلت عملية إنشاء العرض View.';
 $lang['strconfdropview'] = 'هل انت متأكد تريد حذف العرض View بإسم "%s"';
 $lang['strviewdropped'] = 'تم حذف العرض View.';
 $lang['strviewdroppedbad'] = 'لقد فشلت عملية حذف العرض View.';
 $lang['strviewupdated'] = 'تم تحديث العرض View بنجاح.';
 $lang['strviewupdatedbad'] = 'لقد فشلت عملية تحديث العرض View.';
 $lang['strviewlink'] = 'Linking Keys';
 $lang['strviewconditions'] = 'Additional Conditions';
 $lang['strcreateviewwiz'] = 'إنشاء عرض View بإستخدام الساحر Wizard.';

 // Sequences
 $lang['strsequence'] = 'تسلسل Sequence';
 $lang['strsequences'] = 'تسلسلات Sequences';
 $lang['strshowallsequences'] = 'عرض جميع التسلسلات';
 $lang['strnosequence'] = 'لم يوجد تسلسل.';
 $lang['strnosequences'] = 'لم يوجد تسلسلات.';
 $lang['strcreatesequence'] = 'إنشاء تسلسل جديد';
 $lang['strlastvalue'] = 'آخر قيمة';
 $lang['strincrementby'] = 'مقدار الزيادة Increment by'; 
 $lang['strstartvalue'] = 'قيمة البداية';
 $lang['strmaxvalue'] = 'القيمة القصوى';
 $lang['strminvalue'] = 'القيمة الدنيا';
 $lang['strcachevalue'] = 'Cache value';
 $lang['strlogcount'] = 'Log count';
 $lang['striscycled'] = 'Is cycled?';
 $lang['strsequenceneedsname'] = 'يجب إعطاء إسم للتسلسل sequence.';
 $lang['strsequencecreated'] = 'تم إنشاء التسلسل بنجاح.';
 $lang['strsequencecreatedbad'] = 'لقد فشل إنشاء التسلسل.'; 
 $lang['strconfdropsequence'] = 'هل أنت متأكد تريد حذف التسلسل بإسم "%s"؟';
 $lang['strsequencedropped'] = 'لقد تم حذف التسلسل بنجاح.';
 $lang['strsequencedroppedbad'] = 'لقد فشلت عملية حذف التسلسل.';
 $lang['strsequencereset'] = 'لقد تمت إعادة التسلسل بنجاح.';
 $lang['strsequenceresetbad'] = 'لقد فشلت إعادة التسلسل.'; 

 // Indexes
 $lang['strindex'] = 'فهرسIndex';
 $lang['strindexes'] = 'فهارسIndexes';
 $lang['strindexname'] = 'إسم الفهرس Index';
 $lang['strshowallindexes'] = 'عرض جميع الفهارس indexes';
 $lang['strnoindex'] = 'لم يوجد فهرس index.';
 $lang['strnoindexes'] = 'لم توجد فهارس indexes.';
 $lang['strcreateindex'] = 'إنشاء فهرس index جديد';
 $lang['strtabname'] = 'Tab name';
 $lang['strcolumnname'] = 'إسم العمود';
 $lang['strindexneedsname'] = 'يجب عليك إعطاء إسم للفهرس index.';
 $lang['strindexneedscols'] = 'الفهارس تتطلب عدد مقبول من الأعمدة.';
 $lang['strindexcreated'] = 'لقد تم إنشاء الفهرس بنجاح.';
 $lang['strindexcreatedbad'] = 'فشل إنشاء الفهرس.';
 $lang['strconfdropindex'] = 'هل انت متأكد تريد حذف الفهرس بإسم "%s"؟';
 $lang['strindexdropped'] = 'لقد تم حذف الفهرس بنجاح.';
 $lang['strindexdroppedbad'] = 'فشلت عملية حذف الفهرس.';
 $lang['strkeyname'] = 'إسم المفتاح Key';
 $lang['struniquekey'] = 'مفتاح فريد Unique key';
 $lang['strprimarykey'] = 'مفتاح رئيسي Primary key';
  $lang['strindextype'] = 'نوع الفهرس';
 $lang['strtablecolumnlist'] = 'الأعمدة في الجدول';
 $lang['strindexcolumnlist'] = 'الأعمدة في الفهرس';
 $lang['strconfcluster'] = 'Are you sure you want to cluster "%s"?';
 $lang['strclusteredgood'] = 'Cluster complete.';
 $lang['strclusteredbad'] = 'Cluster failed.';

 // Rules
 $lang['strrules'] = 'قواعد Rules';
 $lang['strrule'] = 'قاعدة Rule';
 $lang['strshowallrules'] = 'عرض جميع القواعد';
 $lang['strnorule'] = 'لم توجد قاعدة.';
 $lang['strnorules'] = 'لم توجد قواعد.';
 $lang['strcreaterule'] = 'إنشاء قاعدة rule جديدة';
 $lang['strrulename'] = 'إسم القاعدة rule name';
 $lang['strruleneedsname'] = 'يجب عليك إعطاء إسم للقاعدة rule.';
 $lang['strrulecreated'] = 'تم إنشاء القاعدة بنجاح.';
 $lang['strrulecreatedbad'] = 'فشل إنشاء القاعدة.';
 $lang['strconfdroprule'] = 'هل أنت متأكد تريد حذف القاعدة "%s" على "%s"؟';
 $lang['strruledropped'] = 'تم حذف القاعدة.';
 $lang['strruledroppedbad'] = 'فشل حذف القاعدة.';

 // Constraints
 $lang['strconstraints'] = 'قيود Constraints';
 $lang['strshowallconstraints'] = 'عرض جميع القيود constraints';
 $lang['strnoconstraints'] = 'لم يوجد قيود constraints.';
 $lang['strcreateconstraint'] = 'إنشاء قيد constraint جديد';
 $lang['strconstraintcreated'] = 'تم إنشاء القيد بنجاح.';
 $lang['strconstraintcreatedbad'] = 'فشل إنشاء القيد.';
 $lang['strconfdropconstraint'] = 'هل أنت متأكد تريد حذف القيد "%s" على "%s"؟';
 $lang['strconstraintdropped'] = 'تم حذف القيد بنجاح.';
 $lang['strconstraintdroppedbad'] = 'فشل حذف القيد.';
 $lang['straddcheck'] = 'إضافة فحص check';
 $lang['strcheckneedsdefinition'] = 'قيد الفحص يحتاج لتعريف.';
 $lang['strcheckadded'] = 'تم إضافة قيد الفحص بنحاح.';
 $lang['strcheckaddedbad'] = 'فشلت إضافة قيد الفحص.';
 $lang['straddpk'] = 'primary key إضافة مفتاح رئيسي.';
 $lang['strpkneedscols'] = 'المفتاح الرئيسي يتطلب على الأقل عمود واحد.';
 $lang['strpkadded'] = 'تمت إضافة المفتاح الرئيسي بنجاح.';
 $lang['strpkaddedbad'] = 'فشلت إضافة المفتاح الرئيسي.';
 $lang['stradduniq'] = 'unique key إضافة مفتاح فريد';
 $lang['struniqneedscols'] = 'المفتاح الفريد يتطلب عمود واحد على الأقل.';
 $lang['struniqadded'] = 'تمت إضافة المفتاح الفريد بنجاح.';
 $lang['struniqaddedbad'] = 'فشلت إضافة المفتاح الفريد.';
 $lang['straddfk'] = 'إضافة مفتاح خارجيforeign key';
 $lang['strfkneedscols'] = 'المفتاح الخارجي يتطلب عمود واحد على الأقل.';
 $lang['strfkneedstarget'] = 'المفتاح الخارجي يحتاج الى جدول هدف.';
 $lang['strfkadded'] = 'تمت إضافة المفتاح الخارجي بنجاح.';
 $lang['strfkaddedbad'] = 'فشلت إضافة المفتاح الخارجي.';
 $lang['strfktarget'] = 'الجدول الهدف Target table';
 $lang['strfkcolumnlist'] = 'الأعمدة في المفتاح';
 $lang['strondelete'] = 'ON DELETE';
 $lang['stronupdate'] = 'ON UPDATE';

 // Functions
 $lang['strfunction'] = 'دالة Function';
 $lang['strfunctions'] = 'دوال Functions';
 $lang['strshowallfunctions'] = 'عرض جميع الدوال functions';
 $lang['strnofunction'] = 'لم توجد دالة function.';
 $lang['strnofunctions'] = 'لم توجد دوال functions.';
 $lang['strfunctionname'] = 'إسم الدالة function name';
 $lang['strreturns'] = 'Returns';
 $lang['strarguments'] = 'Arguments';
 $lang['strproglanguage'] = 'لغة برمجة';
 $lang['strfunctionneedsname'] = 'يجب عليك إعطاء إسم للدالة function.';
 $lang['strfunctionneedsdef'] = 'يجب عليك اعطاء تعريف للدالة function definition.';
 $lang['strfunctioncreated'] = 'تم إنشاء الدالة function بنجاح.';
 $lang['strfunctioncreatedbad'] = 'لقد فشل إنشاء الدالة.';
 $lang['strconfdropfunction'] = 'هل أنت متأكد تريد حذف الدالة function بإسم "%s"?';
 $lang['strfunctiondropped'] = 'تم حذف الدالة function بنجاح.';
 $lang['strfunctiondroppedbad'] = 'لقد فشلت عملية حذف الدالة function.';
 $lang['strfunctionupdated'] = 'لقد تم تعديل الدالة function.';
 $lang['strfunctionupdatedbad'] = 'لقد فشل تعديل الدالة function.';

 // Triggers
 $lang['strtrigger'] = 'محفّز Trigger';
 $lang['strtriggers'] = 'محفّزات Triggers';
 $lang['strshowalltriggers'] = 'عرض جميع المحفزات triggers';
 $lang['strnotrigger'] = 'لم يوجد المحفز trigger.';
 $lang['strnotriggers'] = 'لم يوجد محفزات triggers.';
 $lang['strcreatetrigger'] = 'إنشاء محفّز trigger جديد';
 $lang['strtriggerneedsname'] = 'يجب إعطاء اسم للمحفز.';
 $lang['strtriggerneedsfunc'] = 'يجب تحديد دالة function للمحفز.';
 $lang['strtriggercreated'] = 'تم إنشاء المحفز بنجاح.';
 $lang['strtriggercreatedbad'] = 'لقد فشلت عملية إنشاء المحفز.';
 $lang['strconfdroptrigger'] = 'هل أنت متأكد تريد حذف المحفّز trigger "%s" على "%s"؟';
 $lang['strtriggerdropped'] = 'تم حذف المحفز.';
 $lang['strtriggerdroppedbad'] = 'فشل حذف المحفز، لم يتم الحذف.';
 $lang['strtriggeraltered'] = 'تم تعديل المحفز بنجاح.';
 $lang['strtriggeralteredbad'] = 'فشلت عملية تعديل المحفز. لم يتم التعديل.';

 // Types
 $lang['strtype'] = 'نوع Type';
 $lang['strtypes'] = 'أنواع Types';
 $lang['strshowalltypes'] = 'عرض جميع الأنواع';
 $lang['strnotype'] = 'لم يوجد النوع.';
 $lang['strnotypes'] = 'لم يوجد أنواع.';
 $lang['strcreatetype'] = 'إنشاء نوع جديد.';
 $lang['strtypename'] = 'إسم النوع';
 $lang['strinputfn'] = 'دالة الإدخال Input function';
 $lang['stroutputfn'] = 'دالة الإخراج Output function';
 $lang['strpassbyval'] = 'Passed by val?';
 $lang['stralignment'] = 'Alignment';
 $lang['strelement'] = 'Element';
 $lang['strdelimiter'] = 'Delimiter';
 $lang['strstorage'] = 'Storage';
 $lang['strtypeneedsname'] = 'يجب إعطاء إسم للنوع.';
 $lang['strtypeneedslen'] = 'يجب إعطاء طول للنوع.';
 $lang['strtypecreated'] = 'تم إنشاء النوع';
 $lang['strtypecreatedbad'] = 'فشل إنشاء النوع.';
 $lang['strconfdroptype'] = 'هل أنت متأكد تريد حذف النوع "%s"؟';
 $lang['strtypedropped'] = 'تم حذف النوع.';
 $lang['strtypedroppedbad'] = 'فشلت عملية حذف النوع.';

 // Schemas
 $lang['strschema'] = 'مخطط Schema';
 $lang['strschemas'] = 'المخططات Schemas';
 $lang['strshowallschemas'] = 'عرض جميع المخططات schemas';
 $lang['strnoschema'] = 'لم يوجد مخطط schema.';
 $lang['strnoschemas'] = 'لم توجد مخططات schemas.';
 $lang['strcreateschema'] = 'إنشاء مخطط schema جديد';
 $lang['strschemaname'] = 'إسم المخطط';
 $lang['strschemaneedsname'] = 'يجب عليك إعطاء إسم للمخطط.';
 $lang['strschemacreated'] = 'لقد تم انشاء المخطط بنجاح.';
 $lang['strschemacreatedbad'] = 'فشل إنشاء المخطط.';
 $lang['strconfdropschema'] = 'هل أنت متأكد تريد حذف المخطط Schema بإسم "%s"؟';
 $lang['strschemadropped'] = 'تم حذف المخطط.';
 $lang['strschemadroppedbad'] = 'فشلت عملية الحذف للمخطط.';
 $lang['strschemaaltered'] = 'تم تعديل المخطط.';
 $lang['strschemaalteredbad'] = 'فشلت عملية تعديل المخطط، لم يتم التعديل.';

 // Reports

 // Domains
 $lang['strdomain'] = 'نطاق Domain';
 $lang['strdomains'] = 'نطاقات Domains';
 $lang['strshowalldomains'] = 'عرض جيع النطاقات';
 $lang['strnodomains'] = 'لم يوجد نطاقات.';
 $lang['strcreatedomain'] = 'إنشاء نطاق جديد';
 $lang['strdomaindropped'] = 'تم حذف النطاق.';
 $lang['strdomaindroppedbad'] = 'لقد فشل حذف النطاق، لم يتم الحذف.';
 $lang['strconfdropdomain'] = 'هل أنت متأكد تريد حذف النطاق domain بإسم "%s"؟';
 $lang['strdomainneedsname'] = 'يجب إعطاء إسم للنطاق.';
 $lang['strdomaincreated'] = 'تم إنشاء النطاق بنجاح.';
 $lang['strdomaincreatedbad'] = 'لم يتم إنشاء النطاق، فشلت العملية.'; 
 $lang['strdomainaltered'] = 'تم تعديل النطاق.';
 $lang['strdomainalteredbad'] = 'فشلت عملية تعديل النطاق.'; 

 // Operators
 $lang['stroperator'] = 'Operator';
 $lang['stroperators'] = 'Operators';
 $lang['strshowalloperators'] = 'Show all operators';
 $lang['strnooperator'] = 'No operator found.';
 $lang['strnooperators'] = 'No operators found.';
 $lang['strcreateoperator'] = 'Create operator';
 $lang['strleftarg'] = 'Left Arg Type';
 $lang['strrightarg'] = 'Right Arg Type';
 $lang['strcommutator'] = 'Commutator';
 $lang['strnegator'] = 'Negator';
 $lang['strrestrict'] = 'Restrict';
 $lang['strjoin'] = 'Join';
 $lang['strhashes'] = 'Hashes';
 $lang['strmerges'] = 'Merges';
 $lang['strleftsort'] = 'Left sort';
 $lang['strrightsort'] = 'Right sort';
 $lang['strlessthan'] = 'Less than';
 $lang['strgreaterthan'] = 'Greater than';
 $lang['stroperatorneedsname'] = 'You must give a name for your operator.';
 $lang['stroperatorcreated'] = 'Operator created';
 $lang['stroperatorcreatedbad'] = 'Operator creation failed.';
 $lang['strconfdropoperator'] = 'Are you sure you want to drop the operator "%s"?';
 $lang['stroperatordropped'] = 'Operator dropped.';
 $lang['stroperatordroppedbad'] = 'Operator drop failed.';

 // Casts
 $lang['strcasts'] = 'Casts';
 $lang['strnocasts'] = 'No casts found.';
 $lang['strsourcetype'] = 'Source type';
 $lang['strtargettype'] = 'Target type';
 $lang['strimplicit'] = 'Implicit';
 $lang['strinassignment'] = 'In assignment';
 $lang['strbinarycompat'] = '(Binary compatible)';
 
 // Conversions
 $lang['strconversions'] = 'Conversions';
 $lang['strnoconversions'] = 'No conversions found.';
 $lang['strsourceencoding'] = 'Source encoding';
 $lang['strtargetencoding'] = 'Target encoding';
 
 // Languages
 $lang['strlanguages'] = 'Languages';
 $lang['strnolanguages'] = 'No languages found.';
 $lang['strtrusted'] = 'Trusted';
 
 // Info
 $lang['strnoinfo'] = 'No information available.';
 $lang['strreferringtables'] = 'Referring tables';
 $lang['strparenttables'] = 'Parent tables';
 $lang['strchildtables'] = 'Child tables';
 
 // Aggregates
 $lang['straggregates'] = 'Aggregates';
 $lang['strnoaggregates'] = 'No aggregates found.';
 $lang['stralltypes'] = '(All types)';

 // Operator Classes
 $lang['stropclasses'] = 'Op Classes';
 $lang['strnoopclasses'] = 'No operator classes found.';
 $lang['straccessmethod'] = 'Access method';

 // Stats and performance
 $lang['strrowperf'] = 'Row Performance';
 $lang['strioperf'] = 'I/O Performance';
 $lang['stridxrowperf'] = 'Index Row Performance';
 $lang['stridxioperf'] = 'Index I/O Performance';
 $lang['strpercent'] = '%';
 $lang['strsequential'] = 'Sequential';
 $lang['strscan'] = 'Scan';
 $lang['strread'] = 'Read';
 $lang['strfetch'] = 'Fetch';
 $lang['strheap'] = 'Heap';
 $lang['strtoast'] = 'TOAST';
 $lang['strtoastindex'] = 'TOAST Index';
 $lang['strcache'] = 'Cache';
 $lang['strdisk'] = 'Disk';
 $lang['strrows2'] = 'Rows';

 // Miscellaneous
 $lang['strtopbar'] = '%s يعمل على %s:%s -- You are logged in as user "%s"';
 $lang['strtimefmt'] = 'jS M, Y g:iA';
 $lang['strhelp'] = 'مساعدة';

?>
