<?php

	/**
	 * Hebrew language file for phpPgAdmin.
	 * Jonatan Perry <Jonatan44@hotpop.com>
	 *
	 * $Id: hebrew.php,v 1.4 2007/04/24 11:42:07 soranzo Exp $
	 */
	 
	// Language and character set
	$lang['applang'] = 'Hebrew';
	$lang['applocale'] = 'he-IL';
	$lang['applangdir'] = 'rtl';

	// Welcome  
	$lang['strintro'] = 'ברוכים הבאים ל phpPgAdmin.';
	$lang['strppahome'] = 'עמוד הבית של phpPgAdmin.';
	$lang['strpgsqlhome'] = 'עמוד הבית של PostgreSQL.';
	$lang['strpgsqlhome_url'] = 'http://www.postgresql.org/';
	$lang['strlocaldocs'] = 'תיעוד PostgreSQL (מקומי)';
	$lang['strreportbug'] = 'דווח על באג';
	$lang['strviewfaq'] = 'צפה ב FAQ מקוון';
	$lang['strviewfaq_url'] = 'http://phppgadmin.sourceforge.net/doku.php?id=faq';
	
	// Basic strings
	$lang['strlogin'] = 'התחברות';
	$lang['strloginfailed'] = 'התחברות נכשלה';
	$lang['strlogindisallowed'] = 'התחברות מבוטלת בשל בעיות אבטחה.';
	$lang['strserver'] = 'שרת';
	$lang['strlogout'] = 'התנתקות';
	$lang['strowner'] = 'בעל';
	$lang['straction'] = 'פעולה';
	$lang['stractions'] = 'פעולות';
	$lang['strname'] = 'שם';
	$lang['strdefinition'] = 'הגדרה';
	$lang['strproperties'] = 'העדפות';
	$lang['strbrowse'] = 'דפדף';
	$lang['strdrop'] = 'מחק';
	$lang['strdropped'] = 'נמחק';
	$lang['strnull'] = 'Null';
	$lang['strnotnull'] = 'לא null';
	$lang['strprev'] = 'קודם &lt;';
	$lang['strnext'] = '&gt; הבאה';
	$lang['strfirst'] = 'ראשון &lt;&lt;';
	$lang['strlast'] = '&gt;&gt; אחרון';
	$lang['strfailed'] = 'נכשל';
	$lang['strcreate'] = 'צור';
	$lang['strcreated'] = 'נוצר';
	$lang['strcomment'] = 'הערה';
	$lang['strlength'] = 'אורך';
	$lang['strdefault'] = 'ברירת מחדל';
	$lang['stralter'] = 'ערוך';
	$lang['strok'] = 'OK';
	$lang['strcancel'] = 'בטל';
	$lang['strsave'] = 'שמור';
	$lang['strreset'] = 'שחזר';
	$lang['strinsert'] = 'הכנס';
	$lang['strselect'] = 'בחר';
	$lang['strdelete'] = 'מחק';
	$lang['strupdate'] = 'עדכן';
	$lang['strreferences'] = 'תיעודים';
	$lang['stryes'] = 'כן';
	$lang['strno'] = 'לא';
	$lang['strtrue'] = 'אמת';
	$lang['strfalse'] = 'שקר';
	$lang['stredit'] = 'ערוך';
	$lang['strcolumns'] = 'עמודות';
	$lang['strrows'] = 'שורות';
	$lang['strrowsaff'] = 'שורות מושפעות';
	$lang['strobjects'] = 'נתונים';
	$lang['strexample'] = 'דוגמה.';
	$lang['strback'] = 'קודם';
	$lang['strqueryresults'] = 'תוצאות השאילתה';
	$lang['strshow'] = 'הראה';
	$lang['strempty'] = 'רוקן';
	$lang['strlanguage'] = 'שפה';
	$lang['strencoding'] = 'קידוד';
	$lang['strvalue'] = 'ערך';
	$lang['strunique'] = 'מיוחד';
	$lang['strprimary'] = 'ראשי';
	$lang['strexport'] = 'יצוא';
	$lang['strimport'] = 'יבוא';
	$lang['strsql'] = 'SQL';
	$lang['strgo'] = 'לך';
	$lang['stradmin'] = 'מנהל';
	$lang['strvacuum'] = 'ריק';
	$lang['stranalyze'] = 'נתח';
	$lang['strclusterindex'] = 'אשכול';
	$lang['strclustered'] = 'מאושכל?';
	$lang['strreindex'] = 'אנדקס מחדש';
	$lang['strrun'] = 'הרץ';
	$lang['stradd'] = 'הוסף';
	$lang['strevent'] = 'אירוע';
	$lang['strwhere'] = 'איפה';
	$lang['strinstead'] = 'תעשה במקום';
	$lang['strwhen'] = 'כש';
	$lang['strformat'] = 'סוג';
	$lang['strdata'] = 'מידע';
	$lang['strconfirm'] = 'אשר';
	$lang['strexpression'] = 'ביטוי';
	$lang['strellipsis'] = '...';
	$lang['strseparator'] = ': ';
	$lang['strexpand'] = 'הרחב';
	$lang['strcollapse'] = 'צמצם';
	$lang['strexplain'] = 'הסבק';
	$lang['strexplainanalyze'] = 'הסבר נתיחה';
	$lang['strfind'] = 'מצא';
	$lang['stroptions'] = 'אפשרויות';
	$lang['strrefresh'] = 'רענן';
	$lang['strdownload'] = 'הורד';
	$lang['strdownloadgzipped'] = 'הורד דחוס ב gzip';
	$lang['strinfo'] = 'מידע';
	$lang['stroids'] = 'OIDs';
	$lang['stradvanced'] = 'מתקדם';
	$lang['strvariables'] = 'משתנים';
	$lang['strprocess'] = 'תהליך';
	$lang['strprocesses'] = 'תהליכים';
	$lang['strsetting'] = 'הגדרות';
	$lang['streditsql'] = 'ערוך SQL';
	$lang['strruntime'] = 'זמן-ריצה כולל: %s ms';
	$lang['strpaginate'] = 'Paginate results';
	$lang['struploadscript'] = 'או העלה תסריט SQL:';
	$lang['strstarttime'] = 'זמן התחלה';
	$lang['strfile'] = 'קובץ';
	$lang['strfileimported'] = 'קובץ יובא';

	// Error handling
	$lang['strbadconfig'] = 'קובץ ה config.inc.php שלך אינו מעודכן. אתה תיצטרך ליצור אחד חדש יותר מהקובץ config.inc.php-dist החדש';
	$lang['strnotloaded'] = 'התקנת ה PHP שלך אינה תומכת ב PostgreSQL. אתה תיצטרך להדר אותה מחדש אם הפקודה --with-pqlsql בזמן ההגדרה';
	$lang['strphpversionnotsupported'] = 'גירסת ה PHP שלך אינה ניתמכת. אנא עדכן אותה לגירסה %s או חדשה יותר.';
	$lang['strpostgresqlversionnotsupported'] = 'גערסת ה PostgrSQL שלך אינה ניתמכת. אנא עדכן אותה לגירסה %s או חדשה יותר.';
	$lang['strbadschema'] = 'תרשים שגוי צויין';
	$lang['strbadencoding'] = 'נכשל בהתאמת קידוד משתמש למסד הנתונים.';
	$lang['strsqlerror'] = 'שגיאת SQL:';
	$lang['strinstatement'] = 'בהצהרה:';
	$lang['strinvalidparam'] = 'ממדי תסריט שגוי.';
	$lang['strnodata'] = 'לא נמצאו שורות.';
	$lang['strnoobjects'] = 'לא נמצאו נתונים.';
	$lang['strrownotunique'] = 'No unique identifier for this row.';
	$lang['strnouploads'] = 'העלאת קבצים בוטלה.';
	$lang['strimporterror'] = 'שגיאת יבוא.';
	$lang['strimporterrorline'] = 'שגיאת יבוא בשורה: %s.';

	// Tables
	$lang['strtable'] = 'טבלה';
	$lang['strtables'] = 'טבלאות';
	$lang['strshowalltables'] = 'הראה את כל הטבלאות.';
	$lang['strnotables'] = 'לא נמצאו טבלאות.';
	$lang['strnotable'] = 'טבלה לא נמצאה.';
	$lang['strcreatetable'] = 'צור טבלה';
	$lang['strtablename'] = 'שם טבלה';
	$lang['strtableneedsname'] = 'אתה חייב לתת שם לטבלה שלך.';
	$lang['strtableneedsfield'] = 'אתה חייב לציין לפחות שדה אחד.';
	$lang['strtableneedscols'] = 'אתה חייב לציין מספר תקין של עמודות.';
	$lang['strtablecreated'] = 'טבלה נוצרה.';
	$lang['strtablecreatedbad'] = 'יצירת טבלה נכשלה.';
	$lang['strconfdroptable'] = 'אתה בטוח שברצונך למחוק את הטבלה  &quot;%s&quot;?';
	$lang['strtabledropped'] = 'טבלה נמחקה.';
	$lang['strtabledroppedbad'] = 'מחיקת טבלה נכשלה.';
	$lang['strconfemptytable'] = 'האם אתה בטוח שברצונך לרוקן את הטבלה &quot;%s&quot;?';
	$lang['strtableemptied'] = 'טבלה רוקנה.';
	$lang['strtableemptiedbad'] = 'ריקון טבלה נכשל.';
	$lang['strinsertrow'] = 'הכנס שדה';
	$lang['strrowinserted'] = 'שדה הוכנס.';
	$lang['strrowinsertedbad'] = 'הכנסת שדה נכשלה.';
	$lang['streditrow'] = 'ערוך שדה';
	$lang['strrowupdated'] = 'שדה עודכן.';
	$lang['strrowupdatedbad'] = 'עידכון שדה נכשל.';
	$lang['strdeleterow'] = 'מחק שדה';
	$lang['strconfdeleterow'] = 'האם אתה בטוח שברצונך למחוק את השדה הזה?';
	$lang['strrowdeleted'] = 'שדה נמחק.';
	$lang['strrowdeletedbad'] = 'מחיקת שדה נכשל.';
	$lang['strinsertandrepeat'] = 'חזור &amp;הכנס ';
	$lang['strfield'] = 'שדה';
	$lang['strnumfields'] = 'מספר השדות';
	$lang['strselectallfields'] = 'בחר את כל השדות';
	$lang['strselectneedscol'] = 'אתה חייב להראות לפחות שדה אחד';
	$lang['strselectunary'] = 'Unary operators cannot have values.';
	$lang['straltercolumn'] = 'ערוך עמודה';
	$lang['strcolumnaltered'] = 'עמודה נערכה';
	$lang['strcolumnalteredbad'] = 'עריכת עמודה נכשלה.';
	$lang['strconfdropcolumn'] = 'האם אתה בטוח שברצונך למחוק את העמודה &quot;%s&quot; מהטבלה &quot;%squot;?';
	$lang['strcolumndropped'] = 'עמודה נחמקה.';
	$lang['strcolumndroppedbad'] = 'מחיקת עמודה נכשלה.';
	$lang['straddcolumn'] = 'הוסף עמודה.';
	$lang['strcolumnadded'] = 'עמודה נוספה.';
	$lang['strcolumnaddedbad'] = 'הוספת עמודה נכשלה';
	$lang['strcascade'] = 'CASCADE';
	$lang['strtablealtered'] = 'טבלה נערכה.';
	$lang['strtablealteredbad'] = 'עריכת טבלה נכשלה.';
	$lang['strdataonly'] = 'מידע בלבד';
	$lang['strstructureonly'] = 'מבנה בילבד';
	$lang['strstructureanddata'] = 'מבנה ונתונים';
	$lang['strtabbed'] = 'תוייק';
	$lang['strauto'] = 'אוטומטי';

	// Users
	$lang['struser'] = 'משתמש';
	$lang['strusers'] = 'משתמשים';
	$lang['strusername'] = 'שם משתמש';
	$lang['strpassword'] = 'סיסמה';
	$lang['strsuper'] = 'משתמש על?';
	$lang['strcreatedb'] = 'יצירת בסיס נתונים?';
	$lang['strexpires'] = 'Expires';
	$lang['strsessiondefaults'] = 'הפעלת ברירת מחדל';
	$lang['strnousers'] = 'לא נמצאו משתמשים';
	$lang['struserupdated'] = 'משתמש עודכן.';
	$lang['struserupdatedbad'] = 'עדכון משתמש נכשל.';
	$lang['strshowallusers'] = 'הראה את כל המשתמשים.';
	$lang['strcreateuser'] = 'צור משתמש';
	$lang['struserneedsname'] = 'אתה חייב לתת שם למשתמש שלך.';
	$lang['strusercreated'] = 'משתמש נוצר.';
	$lang['strusercreatedbad'] = 'יצירת משתמש נכשל.';
	$lang['strconfdropuser'] = 'אתה בטוח שברצונך למחוק את המשתמש &quot;%s&quot;?';
	$lang['struserdropped'] = 'משתמש נמחק.';
	$lang['struserdroppedbad'] = 'מחיקת משתמש נכשל.';
	$lang['straccount'] = 'חשבון';
	$lang['strchangepassword'] = 'שנה סיסמה';
	$lang['strpasswordchanged'] = 'סיסמה שונתה.';
	$lang['strpasswordchangedbad'] = 'נכשל בשינוי סיסמה.';
	$lang['strpasswordshort'] = 'סיסמה קצרה מידי.';
	$lang['strpasswordconfirm'] = 'סיסמה אינה תואמת לאישורה.';
	
	// Groups
	$lang['strgroup'] = 'קבוצה';
	$lang['strgroups'] = 'קבוצות';
	$lang['strnogroup'] = 'קבוצה לא נמצאה.';
	$lang['strnogroups'] = 'לא נמצאו קבוצות.';
	$lang['strcreategroup'] = 'צור קבוצה';
	$lang['strshowallgroups'] = 'הראה את כל הקבוצות';
	$lang['strgroupneedsname'] = 'אתה חייב לתת שם לקבוצה שלך.';
	$lang['strgroupcreated'] = 'קבוצה נוצרה.';
	$lang['strgroupcreatedbad'] = 'יצירת קבוצה נכשלה.';	
	$lang['strconfdropgroup'] = 'האם אתה בטוח שברצונך למחוק את הקבוצה &quot;%s&quot;?';
	$lang['strgroupdropped'] = 'קבוצה נמחקה.';
	$lang['strgroupdroppedbad'] = 'מחיקת קבוצה נכשלה.';
	$lang['strmembers'] = 'חברים';
	$lang['straddmember'] = 'הוסף חבר';
	$lang['strmemberadded'] = 'חבר נוסף.';
	$lang['strmemberaddedbad'] = 'הוספת חבר נכשלה.';
	$lang['strdropmember'] = 'מחק חבר.';
	$lang['strconfdropmember'] = 'באם אתה בטוח שברצונך למחוק את החבר &quot;%s&quot; מהקבוצה &quot;%s&quot;?';
	$lang['strmemberdropped'] = 'חבר נמחק.';
	$lang['strmemberdroppedbad'] = 'מחיקת חבר נכשלה.';

	// Privileges
	$lang['strprivilege'] = 'זכות';
	$lang['strprivileges'] = 'זכויות';
	$lang['strnoprivileges'] = 'לנתון זה יש זכויות ברירת מחדל של בעלים.';
	$lang['strgrant'] = 'Grant';
	$lang['strrevoke'] = 'Revoke';
	$lang['strgranted'] = 'זכויות שונו.';
	$lang['strgrantfailed'] = 'שינוי זכויות נכשל.';
	$lang['strgrantbad'] = 'אתה חייב לציין לפחות משתמש אחד אם קבוצה בעלי זכות.';
	$lang['strgrantor'] = 'Grantor';
	$lang['strasterisk'] = '*';

	// Databases
	$lang['strdatabase'] = 'בסיס נתונים';
	$lang['strdatabases'] = 'בסיסי נתונים';
	$lang['strshowalldatabases'] = 'הראה את כל בסיסי הנתונים.';
	$lang['strnodatabase'] = 'בסיס נתונים לא נמצאה.';
	$lang['strnodatabases'] = 'לא נמצאו בסיסי נתונים.';
	$lang['strcreatedatabase'] = 'צור בסיס נתונים.';
	$lang['strdatabasename'] = 'שם בסיס הנתונים';
	$lang['strdatabaseneedsname'] = 'אתה חייב לתת שם לבסיס הנתונים שלך';
	$lang['strdatabasecreated'] = 'בסיס נתונים נוצר';
	$lang['strdatabasecreatedbad'] = 'יצירת בסיס נתונים נכשלה.';
	$lang['strconfdropdatabase'] = 'אתה בטוח שברצונך למחוק את בסיס הנתונים &quot;%s&quot;?';
	$lang['strdatabasedropped'] = 'מסד נתונים נמחק';
	$lang['strdatabasedroppedbad'] = 'מחיקת מסד הנתונים נכשלה.';
	$lang['strentersql'] = 'הכנס את ה SQL כדאי להריץ אותו פה:';
	$lang['strsqlexecuted'] = 'SQL הורץ.';
	$lang['strvacuumgood'] = 'ריק הושלם.';
	$lang['strvacuumbad'] = 'ריק נכשל.';
	$lang['stranalyzegood'] = 'ניתוח הושלם.';
	$lang['stranalyzebad'] = 'ניתוח נכשל.';
	$lang['strreindexgood'] = 'אנידוקס מחדש הושלם.';
	$lang['strreindexbad'] = 'אנידוקס מחדש נכשל.';
	$lang['strfull'] = 'מלא';
	$lang['strfreeze'] = 'Freeze';
	$lang['strforce'] = 'כוח';
	$lang['strsignalsent'] = 'שלח אות.';
	$lang['strsignalsentbad'] = 'שליחת אות נכשלה.';
	$lang['strallobjects'] = 'כל העצמים';

	// Views
	$lang['strview'] = 'צפייה';
	$lang['strviews'] = 'צפיות';
	$lang['strshowallviews'] = 'הראה את כל הצפיות';
	$lang['strnoview'] = 'צפייה לא נמצאה.';
	$lang['strnoviews'] = 'צפיות לא נמצאו.';
	$lang['strcreateview'] = 'צור צפייה';
	$lang['strviewname'] = 'שם הצפייה';
	$lang['strviewneedsname'] = 'אתה חייב לתת שם לצפייה';
	$lang['strviewneedsdef'] = 'אתה חייב לציין הגדרה לצפייה.';
	$lang['strviewneedsfields'] = 'אתה חייב לתת את העמודה אשר אתה רוצה לצפייה.';
	$lang['strviewcreated'] = 'צפייה נוצרה.';
	$lang['strviewcreatedbad'] = 'יצירת צפייה נכשלה.';
	$lang['strconfdropview'] = 'האם אתה בטוח שברצונך למחוק את הצפייה &quot;%s&quot;';
	$lang['strviewdropped'] = 'צפייה נמחקה.';
	$lang['strviewdroppedbad'] = 'מחיקת צפייה נכשלה.';
	$lang['strviewupdated'] = 'צפייה עודכנה.';
	$lang['strviewupdatedbad'] = 'עדכון צפייה נכשלה.';
	$lang['strviewlink'] = 'קישורי מפתח';
	$lang['strviewconditions'] = 'Additional Conditions';
	$lang['strcreateviewwiz'] = 'צור צפייה בעזרת אשף.';

	// Sequences
	$lang['strsequence'] = 'נוסחה';
	$lang['strsequences'] = 'נוסחאות';
	$lang['strshowallsequences'] = 'הראה את כל הנוסחאות.';
	$lang['strnosequence'] = 'נוסחה לא נמצאה.';
	$lang['strnosequences'] = 'לא נמצאו נוסחאות.';
	$lang['strcreatesequence'] = 'צור נוסחה';
	$lang['strlastvalue'] = 'ערך אחרון';
	$lang['strincrementby'] = 'Increment by';
	$lang['strstartvalue'] = 'ערך התחלה';
	$lang['strmaxvalue'] = 'ערך מקסימלי';
	$lang['strminvalue'] = 'ערך מינימלי';
	$lang['strcachevalue'] = 'ערך מטמון';
	$lang['strlogcount'] = 'ספירת יומן';
	$lang['striscycled'] = 'Is cycled?';
	$lang['strsequenceneedsname'] = 'אתה חייב לציין שם לנוסחה שלך.';
	$lang['strsequencecreated'] = 'נוסחה נוצרה.';
	$lang['strsequencecreatedbad'] = 'יצירת נוסחה נכשלה.'; 
	$lang['strconfdropsequence'] = 'האם אתה בטוח שברצונך למחוק את הנוסחה &quot;%s&quot;?';
	$lang['strsequencedropped'] = 'נוסחה נמחקה..';
	$lang['strsequencedroppedbad'] = 'נחיקת נוסחה נכשלה.';
	$lang['strsequencereset'] = 'נוסחה אותחלה.';
	$lang['strsequenceresetbad'] = 'איתחול נוסחה נכשלה.'; 

	// Indexes
	$lang['strindex'] = 'אינדקס';
	$lang['strindexes'] = 'אינדקסים';
	$lang['strindexname'] = 'שם האינדקס';
	$lang['strshowallindexes'] = 'צפה בכל האינדקסים';
	$lang['strnoindex'] = 'אינדקס לא נמצאה.';
	$lang['strnoindexes'] = 'לא נמצאו אינדקסים.';
	$lang['strcreateindex'] = 'צור אינקדס.';
	$lang['strtabname'] = 'תקייה אינקדס';
	$lang['strcolumnname'] = 'שם העמודה';
	$lang['strindexneedsname'] = 'אתה חייב לציין שם לאינדקס.';
	$lang['strindexneedscols'] = 'אינדקסים דורשים מספר תקין של עמודות.';
	$lang['strindexcreated'] = 'אינדקס נוצר.';
	$lang['strindexcreatedbad'] = 'יצירת אינקדס נכשלה.';
	$lang['strconfdropindex'] = 'האם אתה בטוח שברצונל למחוק את האינקדס &quot;%s&quot;?';
	$lang['strindexdropped'] = 'אינדקס נמחק.';
	$lang['strindexdroppedbad'] = 'מחיקת אינדקס נכשלה.';
	$lang['strkeyname'] = 'שם המפתח';
	$lang['struniquekey'] = 'מםתח יחודי';
	$lang['strprimarykey'] = 'מפתח ראשי';
 	$lang['strindextype'] = 'סוג האינדקס';
	$lang['strtablecolumnlist'] = 'עמודות בטבלה';
	$lang['strindexcolumnlist'] = 'עמודות באינדקס';
	$lang['strconfcluster'] = 'האם אתה בטוח שברצונך למחוק את האשכול &quot;%s&quot;?';
	$lang['strclusteredgood'] = 'אשכול הושלם.';
	$lang['strclusteredbad'] = 'אשכול נכשל.';

	// Rules
	$lang['strrules'] = 'חוקים';
	$lang['strrule'] = 'חוק';
	$lang['strshowallrules'] = 'הראה את כל החוקים';
	$lang['strnorule'] = 'חוק לא נמצאה.';
	$lang['strnorules'] = 'לא נמצאו חוקים.';
	$lang['strcreaterule'] = 'צור חוק';
	$lang['strrulename'] = 'שם החוק';
	$lang['strruleneedsname'] = 'אתה חייב לציין שם לחוק.';
	$lang['strrulecreated'] = 'חוק נוצר.';
	$lang['strrulecreatedbad'] = 'יצירת חוק נכשלה.';
	$lang['strconfdroprule'] = 'האם אתה בטוח שברצונך למחוק את החוק &quot;%s&quot; מ &quotl%s&quot;?';
	$lang['strruledropped'] = 'חוק נמחק.';
	$lang['strruledroppedbad'] = 'מחיקת חוק נכשלה.';

	// Constraints
	$lang['strconstraints'] = 'מבנים';
	$lang['strshowallconstraints'] = 'הראה את כל המבנים.';
	$lang['strnoconstraints'] = 'לא נמצאו מבנים.';
	$lang['strcreateconstraint'] = 'צור מבנה';
	$lang['strconstraintcreated'] = 'מבנה נוצר.';
	$lang['strconstraintcreatedbad'] = 'יצירת מבנה נכשלה.';
	$lang['strconfdropconstraint'] = 'האם אתה בטוח שברצונך למחוק את הבמנה &quot;%s&quot; מ &quot;%s&quot;?';
	$lang['strconstraintdropped'] = 'מבנה נמחק.';
	$lang['strconstraintdroppedbad'] = 'מחיקת מבנה נכשלה.';
	$lang['straddcheck'] = 'הוסף בדיקה';
	$lang['strcheckneedsdefinition'] = 'בדיקת מבנה זקוקה להגדרה.';
	$lang['strcheckadded'] = 'בדיקה מבנה נוספה.';
	$lang['strcheckaddedbad'] = 'בדיקת מבנה נכשלה.';
	$lang['straddpk'] = 'הוסף מפתח ראשי';
	$lang['strpkneedscols'] = 'מפתח ראשי דורש לפחות עמודה אחת.';
	$lang['strpkadded'] = 'מפתח ראשי נוסף.';
	$lang['strpkaddedbad'] = 'הוספת מפתח ראשי נכשלה.';
	$lang['stradduniq'] = 'הוסף מפתח מיוחד.';
	$lang['struniqneedscols'] = 'מפתח מיוחד דורש לפחות עמודה אחת.';
	$lang['struniqadded'] = 'מפתח מיוחד נוסף.';
	$lang['struniqaddedbad'] = 'נוספת מפתח מיוחד נכשלה.';
	$lang['straddfk'] = 'הוסף מפתח זר';
	$lang['strfkneedscols'] = 'מפתח זר דורש לפתוח עמודה אחת.';
	$lang['strfkneedstarget'] = 'מפתח זר דורש טבלת מטרה.';
	$lang['strfkadded'] = 'מפתח זר נוסף.';
	$lang['strfkaddedbad'] = 'יצירת מפתח זר נכשלה.';
	$lang['strfktarget'] = 'טבלת מטרה';
	$lang['strfkcolumnlist'] = 'עמודות במפתח';
	$lang['strondelete'] = 'DELETE ב';
	$lang['stronupdate'] = 'UPDATE ב';

	// Functions
	$lang['strfunction'] = 'פונקציה';
	$lang['strfunctions'] = 'פונקציות';
	$lang['strshowallfunctions'] = 'הראה את כל הפונקציות';
	$lang['strnofunction'] = 'פונקציה לא נמצאה.';
	$lang['strnofunctions'] = 'לא נמצאו פונקציות.';
	$lang['strcreateplfunction'] = 'צור פונקצית SQL/PL';
	$lang['strcreateinternalfunction'] = 'צור פונקציה פנימית';
	$lang['strcreatecfunction'] = 'צור פונקצית C';
	$lang['strfunctionname'] = 'שם הפונקציה';
	$lang['strreturns'] = 'חזרות';
	$lang['strarguments'] = 'ארגומנטים';
	$lang['strproglanguage'] = 'שפת תיכנות';
	$lang['strfunctionneedsname'] = 'אתה חייב לתת שם לפונקציה שלך.';
	$lang['strfunctionneedsdef'] = 'אתה חייב לציין הגדרה לפונקציה.';
	$lang['strfunctioncreated'] = 'פונקצייה נוצרה.';
	$lang['strfunctioncreatedbad'] = 'יצירת פונקצייה נכשלה.';
	$lang['strconfdropfunction'] = 'האם אתה בטוח שברצונך למחוק את הפונקצייה &quot;%s&quot;?';
	$lang['strfunctiondropped'] = 'פונקצייה נמחקה.';
	$lang['strfunctiondroppedbad'] = 'מחיקת פונקציה נכשלה.';
	$lang['strfunctionupdated'] = 'פונקציה עודכנה.';
	$lang['strfunctionupdatedbad'] = 'עדכון פונקציה נכשל.';
	$lang['strobjectfile'] = 'קובץ אובייקט';
	$lang['strlinksymbol'] = 'קישור סימלי';

	// Triggers
	$lang['strtrigger'] = 'זרז';
	$lang['strtriggers'] = 'זרזים';
	$lang['strshowalltriggers'] = 'הראה את כל הזרזים';
	$lang['strnotrigger'] = 'זרז לא נימצאה.';
	$lang['strnotriggers'] = 'לא נימצאו זרזים.';
	$lang['strcreatetrigger'] = 'צור זרז';
	$lang['strtriggerneedsname'] = 'אתה חייב לציין שם לזרז.';
	$lang['strtriggerneedsfunc'] = 'אתה חייב לציין פונקציה לזרז.';
	$lang['strtriggercreated'] = 'זרז נוצר.';
	$lang['strtriggercreatedbad'] = 'יצירת זרז נכשלה.';
	$lang['strconfdroptrigger'] = 'האם אתה בטוח שברצונך למחוק את הזרז &quot;%s&quot; מ &quot;%s&quot;?';
	$lang['strtriggerdropped'] = 'זרז נמחק.';
	$lang['strtriggerdroppedbad'] = 'מחיקת זרז נכשלה.';
	$lang['strtriggeraltered'] = 'זרז נערך.';
	$lang['strtriggeralteredbad'] = 'עריכת זרז נכשלה.';

	// Types
	$lang['strtype'] = 'סוג';
	$lang['strtypes'] = 'סוגים';
	$lang['strshowalltypes'] = 'הראה את כל הסוגים.';
	$lang['strnotype'] = 'סוג לא נמצאה.';
	$lang['strnotypes'] = 'לא נמצאו סוגים.';
	$lang['strcreatetype'] = 'צור סוג';
	$lang['strcreatecomptype'] = 'Create composite type';
	$lang['strtypeneedsfield'] = 'אתה חייב לציין לפחות שדה אחד.';
	$lang['strtypeneedscols'] = 'אתה חייב לציין מספר תקין של שדות.';	
	$lang['strtypename'] = 'שם הסוג';
	$lang['strinputfn'] = 'פונקצית קלט';
	$lang['stroutputfn'] = 'פונקצית פלט';
	$lang['strpassbyval'] = 'Passed by val?';
	$lang['stralignment'] = 'Alignment';
	$lang['strelement'] = 'אלמנט';
	$lang['strdelimiter'] = 'Delimiter';
	$lang['strstorage'] = 'אחסנה';
	$lang['strtypeneedsname'] = 'אתה חייב לציין שם לסוג.';
	$lang['strtypeneedslen'] = 'אתה חייב לציין אורך לסוג.';
	$lang['strtypecreated'] = 'סוג נוצר.';
	$lang['strtypecreatedbad'] = 'יצירת סוג נכשלה.';
	$lang['strconfdroptype'] = 'האם אתה בטוח שברצונך למחוק את הסוג &quot;%s&quot;?';
	$lang['strtypedropped'] = 'סוג נמחק.';
	$lang['strtypedroppedbad'] = 'מחיקת סוג נכשלה.';
	$lang['strflavor'] = 'Flavor';
	$lang['strbasetype'] = 'בסיס';
	$lang['strcompositetype'] = 'Composite';
	$lang['strpseudotype'] = 'פאסדו';

	// Schemas
	$lang['strschema'] = 'תרשים';
	$lang['strschemas'] = 'תרשמים';
	$lang['strshowallschemas'] = 'הראה את כל התרשימים';
	$lang['strnoschema'] = 'תרשים לא נמצאה.';
	$lang['strnoschemas'] = 'לא נמצאו תרשימים.';
	$lang['strcreateschema'] = 'צור תרשים';
	$lang['strschemaname'] = 'שם התרשים';
	$lang['strschemaneedsname'] = 'אתה חייב לציין שם לתרשים.';
	$lang['strschemacreated'] = 'תרשים נוצר';
	$lang['strschemacreatedbad'] = 'יצירת תרשים נכשלה.';
	$lang['strconfdropschema'] = 'האם אתה בטוח שברצונך למחוק את התרשים &quot;?&quot;?';
	$lang['strschemadropped'] = 'תרשים נמחק.';
	$lang['strschemadroppedbad'] = 'מחיקת תרשים נכשלה.';
	$lang['strschemaaltered'] = 'תרשים נערך.';
	$lang['strschemaalteredbad'] = 'עריכת תרשים נכשלה.';
	$lang['strsearchpath'] = 'חיפוש מיקום התרשים.';

	// Reports

	// Domains
	$lang['strdomain'] = 'תחום';
	$lang['strdomains'] = 'תחומים';
	$lang['strshowalldomains'] = 'הראה את כל התחומים';
	$lang['strnodomains'] = 'לא נמצאו תחומים.';
	$lang['strcreatedomain'] = 'צור תחום';
	$lang['strdomaindropped'] = 'תחום נמחק.';
	$lang['strdomaindroppedbad'] = 'מחיקת תחום נכשלה.';
	$lang['strconfdropdomain'] = 'האם אתה בטוח שברצונך למחוק את התחום &quot;%s&quot;?';
	$lang['strdomainneedsname'] = 'אתה חייב לציין שם לתחום שלך.';
	$lang['strdomaincreated'] = 'תחום נוצר.';
	$lang['strdomaincreatedbad'] = 'יצירת תחום נכשלה.';	
	$lang['strdomainaltered'] = 'תחום נערך.';
	$lang['strdomainalteredbad'] = 'עריכת תחום נכשלה.';	

	// Operators
	$lang['stroperator'] = 'מפעיל';
	$lang['stroperators'] = 'מפעילים';
	$lang['strshowalloperators'] = 'הראה את כל המפעילים';
	$lang['strnooperator'] = 'מפעיל לא נמצאה.';
	$lang['strnooperators'] = 'לא נמצאו מפעילים.';
	$lang['strcreateoperator'] = 'צור מפעיל';
	$lang['strleftarg'] = 'סידור סוג לשמאל';
	$lang['strrightarg'] = 'סידור שמאל לימין';
	$lang['strcommutator'] = 'Commutator';
	$lang['strnegator'] = 'Negator';
	$lang['strrestrict'] = 'Restrict';
	$lang['strjoin'] = 'חבר';
	$lang['strhashes'] = 'Hashes';
	$lang['strmerges'] = 'Merges';
	$lang['strleftsort'] = 'סידור לשמאל';
	$lang['strrightsort'] = 'סידור לימין';
	$lang['strlessthan'] = 'פחות מ';
	$lang['strgreaterthan'] = 'גדול מ';
	$lang['stroperatorneedsname'] = 'אתה חייב לציין שם למפעיל.';
	$lang['stroperatorcreated'] = 'מפעיל נוצר.';
	$lang['stroperatorcreatedbad'] = 'יצירת מפעיל נכשלה.';
	$lang['strconfdropoperator'] = 'האם אתה בטוח שברצונך למחוק את המפעיל &quot;%s&quot;?';
	$lang['stroperatordropped'] = 'מפעיל נמחק.';
	$lang['stroperatordroppedbad'] = 'מחיקת מפעיל נכשלה.';

	// Casts
	$lang['strcasts'] = 'Casts';
	$lang['strnocasts'] = 'No casts found.';
	$lang['strsourcetype'] = 'סוג המקור';
	$lang['strtargettype'] = 'סוג המטרה';
	$lang['strimplicit'] = 'Implicit';
	$lang['strinassignment'] = 'In assignment';
	$lang['strbinarycompat'] = '(Binary compatible)';
	
	// Conversions
	$lang['strconversions'] = 'המרה';
	$lang['strnoconversions'] = 'לא נימצאה המרה.';
	$lang['strsourceencoding'] = 'סוג הקידוד של המקור';
	$lang['strtargetencoding'] = 'סוג הקידוד של המטרה';
	
	// Languages
	$lang['strlanguages'] = 'שפות';
	$lang['strnolanguages'] = 'לא נמצאו שפות';
	$lang['strtrusted'] = 'Trusted';
	
	// Info
	$lang['strnoinfo'] = 'אין מידע זמין.';
	$lang['strreferringtables'] = 'Referring tables';
	$lang['strparenttables'] = 'Parent tables';
	$lang['strchildtables'] = 'Child tables';
	
	// Aggregates
	$lang['straggregates'] = 'Aggregates';
	$lang['strnoaggregates'] = 'No aggregates found.';
	$lang['stralltypes'] = '(כל הסוגים)';

	// Operator Classes
	$lang['stropclasses'] = 'Op Classes';
	$lang['strnoopclasses'] = 'No operator classes found.';
	$lang['straccessmethod'] = 'שיטת גישה';

	// Stats and performance
	$lang['strrowperf'] = 'תפקוד שדות';
	$lang['strioperf'] = 'תפקוד קלט/פלט';
	$lang['stridxrowperf'] = 'תפקוד אנדוקס שדה';
	$lang['stridxioperf'] = 'תפקוד אנדוקס קלט/פלט';
	$lang['strpercent'] = '%';
	$lang['strsequential'] = 'Sequential';
	$lang['strscan'] = 'חיפוש';
	$lang['strread'] = 'קרא';
	$lang['strfetch'] = 'Fetch';
	$lang['strheap'] = 'Heap';
	$lang['strtoast'] = 'TOAST';
	$lang['strtoastindex'] = 'TOAST Index';
	$lang['strcache'] = 'מטמון';
	$lang['strdisk'] = 'דיסק';
	$lang['strrows2'] = 'שורה';

	// Tablespaces
	$lang['strtablespace'] = 'מרחבון';
	$lang['strtablespaces'] = 'מרחבונים';
	$lang['strshowalltablespaces'] = 'הראה את כל המרחבונים';
	$lang['strnotablespaces'] = 'לא נמצאו מרחבונים.';
	$lang['strcreatetablespace'] = 'צור מרחבון';
	$lang['strlocation'] = 'מיקום';
	$lang['strtablespaceneedsname'] = 'אתה חייב לציין שם למרחבון.';
	$lang['strtablespaceneedsloc'] = 'אתה חייב לציין תיקיה שבה יבצר המרחבון.';
	$lang['strtablespacecreated'] = 'מרחבון נוצר.';
	$lang['strtablespacecreatedbad'] = 'יצירת מרחבון נכשלה.';
	$lang['strconfdroptablespace'] = 'האם אתה בטוח שברצונך למחוק את המרחבון &quot;%s&quot;?';
	$lang['strtablespacedropped'] = 'מרחבון נמחק.';
	$lang['strtablespacedroppedbad'] = 'מחיקת מרחבון נכשלה.';
	$lang['strtablespacealtered'] = 'מרחבון נערך.';
	$lang['strtablespacealteredbad'] = 'עריכת מרחבון מכשלה.';
	
	// Miscellaneous
	$lang['strtopbar'] = '%s רץ על %s:%s -- אתה מחובר כמשתמש - &quot;%s&quot;';
	$lang['strtimefmt'] = 'jS M, Y g:iA';
	$lang['strhelp'] = 'עזרה';

?>
