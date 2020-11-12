<?php

	/**
	 * Russian KOI8 language file for phpPgAdmin. 
	 * @maintainer Alexander Khodorivsky [askh@ukr.net]
	 *
	 * $Id: russian.php,v 1.12 2007/04/24 11:42:07 soranzo Exp $
	 */

	// Language and character set
	$lang['applang'] = 'Русский КОИ8';
	$lang['applocale'] = 'ru-RU';
	$lang['applangdir'] = 'ltr';

	// Welcome  
	$lang['strintro'] = 'Добро пожаловать в phpPgAdmin.';
	$lang['strppahome'] = 'phpPgAdmin - домашняя страница';
	$lang['strpgsqlhome'] = 'PostgreSQL - домашняя страница';
	$lang['strpgsqlhome_url'] = 'http://www.postgresql.org/';
	$lang['strlocaldocs'] = 'PostgreSQL - документация (локально)';
	$lang['strreportbug'] = 'Отчет об ошибках';
	$lang['strviewfaq'] = 'Просмотр онлайн FAQ';
	$lang['strviewfaq_url'] = 'http://phppgadmin.sourceforge.net/doku.php?id=faq';
	
	// Basic strings
	$lang['strlogin'] = 'Логин';
	$lang['strloginfailed'] = 'Неверный логин';
	$lang['strlogindisallowed'] = 'Логин недопустим по соображениям безопасности';
	$lang['strserver'] = 'Сервер';
	$lang['strlogout'] = 'Перерегистрация';
	$lang['strowner'] = 'Пользователь';
	$lang['straction'] = 'Действие';
	$lang['stractions'] = 'Действия';
	$lang['strname'] = 'Имя';
	$lang['strdefinition'] = 'Определение';
	$lang['strproperties'] = 'Свойства';
	$lang['strbrowse'] = 'Просмотреть';
	$lang['strdrop'] = 'Удалить';
	$lang['strdropped'] = 'Удалено';
	$lang['strnull'] = 'Null';
	$lang['strnotnull'] = 'Not Null';
	$lang['strprev'] = '< Предыд.';
	$lang['strnext'] = 'След. >';
	$lang['strfirst'] = '<< Перв.';
	$lang['strlast'] = 'Посл. >>';
	$lang['strfailed'] = 'Прервано';
	$lang['strcreate'] = 'Создать';
	$lang['strcreated'] = 'Создано';
	$lang['strcomment'] = 'Комментарий';
	$lang['strlength'] = 'Длина';
	$lang['strdefault'] = 'По умолчанию';
	$lang['stralter'] = 'Изменить';
	$lang['strok'] = 'OK';
	$lang['strcancel'] = 'Отменить';
	$lang['strsave'] = 'Сохранить';
	$lang['strreset'] = 'Сбросить';
	$lang['strinsert'] = 'Вставить';
	$lang['strselect'] = 'Выбрать';
	$lang['strdelete'] = 'Удалить';
	$lang['strupdate'] = 'Обновить';
	$lang['strreferences'] = 'Ссылки';
	$lang['stryes'] = 'Да';
	$lang['strno'] = 'Нет';
	$lang['strtrue'] = 'Истина';
	$lang['strfalse'] = 'Ложь';
	$lang['stredit'] = 'Редактировать';
	$lang['strcolumns'] = 'Атрибуты';
	$lang['strrows'] = 'запис(ь/и/ей)';
	$lang['strrowsaff'] = 'запис(ь/и/ей) обработано.';
	$lang['strobjects'] = 'объект(а/ов)';
	$lang['strexample'] = 'и т.д.';
	$lang['strback'] = 'Назад'; 	
	$lang['strqueryresults'] = 'Результаты запроса';
	$lang['strshow'] = 'Показать';
	$lang['strempty'] = 'Очистить';
	$lang['strlanguage'] = 'Язык';
	$lang['strencoding'] = 'Кодировка';
	$lang['strvalue'] = 'Величина';
	$lang['strunique'] = 'Уникальный';
	$lang['strprimary'] = 'Первичный';
	$lang['strexport'] = 'Экспорт';
	$lang['strimport'] = 'Импорт';
	$lang['strsql'] = 'SQL';
	$lang['strgo'] = 'Выполнить';
	$lang['stradmin'] = 'Управление';
	$lang['strvacuum'] = 'Перестроить';
	$lang['stranalyze'] = 'Анализировать';
	$lang['strclusterindex'] = 'Кластеризовать';
	$lang['strclustered'] = 'Кластеризован?';
	$lang['strreindex'] = 'Перестроить индекс';
	$lang['strrun'] = 'Выполнить';
	$lang['stradd'] = 'Добавить';
	$lang['strevent'] = 'Событие';
	$lang['strwhere'] = 'Где';
	$lang['strinstead'] = 'Делать вместо';
	$lang['strwhen'] = 'Когда';
	$lang['strformat'] = 'Формат';
	$lang['strdata'] = 'Данные';
	$lang['strconfirm'] = 'Подтвердить';
	$lang['strexpression'] = 'Выражение';
	$lang['strellipsis'] = '...';
	$lang['strexpand'] = 'Расширить';
	$lang['strcollapse'] = 'Свернуть';
	$lang['strexplain'] = 'Объяснить';
	$lang['strexplainanalyze'] = 'Объяснить анализ';
	$lang['strfind'] = 'Найти';
	$lang['stroptions'] = 'Опции';
	$lang['strrefresh'] = 'Обновить';
	$lang['strdownload'] = 'Загрузить';
	$lang['strdownloadgzipped'] = 'Загрузить архив gzip';
	$lang['strinfo'] = 'Сведения';
	$lang['stroids'] = 'OIDs';
	$lang['stradvanced'] = 'Дополнительно';
	$lang['strvariables'] = 'Переменные';
	$lang['strprocess'] = 'Процесс';
	$lang['strprocesses'] = 'Процессы';
	$lang['strsetting'] = 'Опции';
	$lang['streditsql'] = 'Редактировать SQL';
	$lang['strruntime'] = 'Время выполнения: %s мсек';
	$lang['strpaginate'] = 'Нумеровать страницы с результатами';
	$lang['struploadscript'] = 'или загрузить SQL-скрипт:';
	$lang['strstarttime'] = 'Время начала';
	$lang['strfile'] = 'Файл';
	$lang['strfileimported'] = 'Файл импортирован.';

	// Error handling
	$lang['strbadconfig'] = 'Ваш config.inc.php является устаревшим. Вам необходимо обновить его из config.inc.php-dist.';
	$lang['strnotloaded'] = 'Ваша инсталяция PHP не поддерживает PostgreSQL. Вам необходимо пересобрать PHP, используя параметр --with-pgsql для configure.';
	$lang['strbadschema'] = 'Обнаружена неверная схема.';
	$lang['strbadencoding'] = 'Failed to set client encoding in database.';
	$lang['strsqlerror'] = 'Ошибка SQL:';
	$lang['strinstatement'] = 'В операторе:';
	$lang['strinvalidparam'] = 'Неверный параметр скрипта.';
	$lang['strnodata'] = 'Данных не найдено.';
	$lang['strnoobjects'] = 'Объектов не найдено.';
	$lang['strrownotunique'] = 'Нет уникального идентификатора для этой записи.';
	$lang['strnouploads'] = 'Загрузка файла невозможна.';
	$lang['strimporterror'] = 'Ошибка импорта.';
	$lang['strimporterrorline'] = 'Ошибка канала при импорте %s.';

	// Tables
	$lang['strtable'] = 'Таблица';
	$lang['strtables'] = 'Таблицы';
	$lang['strshowalltables'] = 'Показать все таблицы';
	$lang['strnotables'] = 'Таблиц не найдено.';
	$lang['strnotable'] = ' Таблица не обнаружена.';
	$lang['strcreatetable'] = 'Создать таблицу';
	$lang['strtablename'] = 'Имя таблицы';
	$lang['strtableneedsname'] = 'Вам необходимо определить имя таблицы.';
	$lang['strtableneedsfield'] = 'Вам необходимо определить по крайней мере одно поле.';
	$lang['strtableneedscols'] = 'Вам необходимо указать допустимое число атрибутов.';
	$lang['strtablecreated'] = 'Таблица создана.';
	$lang['strtablecreatedbad'] = 'Создание таблицы прервано.';
	$lang['strconfdroptable'] = 'Вы уверены, что хотите удалить таблицу "%s"?';
	$lang['strtabledropped'] = 'Таблица удалена.';
	$lang['strtabledroppedbad'] = 'Удаление таблицы прервано.';
	$lang['strconfemptytable'] = 'Вы уверены, что хотите очистить таблицу "%s"?';
	$lang['strtableemptied'] = 'Таблица очищена.';
	$lang['strtableemptiedbad'] = 'Очистка таблицы прервана.';
	$lang['strinsertrow'] = 'Добавить запись';
	$lang['strrowinserted'] = 'Запись добавлена.';
	$lang['strrowinsertedbad'] = 'Добавление записи прервано.';
	$lang['streditrow'] = 'Редактировать запись';
	$lang['strrowupdated'] = 'Запись обновлена.';
	$lang['strrowupdatedbad'] = 'Обновление записи прервано.';
	$lang['strdeleterow'] = 'Удалить запись';
	$lang['strconfdeleterow'] = 'Вы уверены, что хотите удалить запись?';
	$lang['strrowdeleted'] = 'Запись удалена.';
	$lang['strrowdeletedbad'] = 'Удаление записи прервано.';
	$lang['strsaveandrepeat'] = 'Вставить и повторить';
	$lang['strfield'] = 'Поле';
	$lang['strfields'] = 'Поля';
	$lang['strnumfields'] = 'Кол-во полей';
	$lang['strfieldneedsname'] = 'Вам необходимо назвать поле';
	$lang['strselectallfields'] = 'Выбрать все поля';
	$lang['strselectneedscol'] = 'Вам необходимо указать по крайней мере один атрибут';
	$lang['strselectunary'] = 'Унарный оператор не может иметь величину.';
	$lang['straltercolumn'] = 'Изменить атрибут';
	$lang['strcolumnaltered'] = 'Атрибут изменен.';
	$lang['strcolumnalteredbad'] = 'Изменение атрибута прервано.';
	$lang['strconfdropcolumn'] = 'Вы уверены, что хотите удалить атрибут "%s" таблицы "%s"?';
	$lang['strcolumndropped'] = 'Атрибут удален.';
	$lang['strcolumndroppedbad'] = 'Удаление атрибута прервано.';
	$lang['straddcolumn'] = 'Добавить атрибут';
	$lang['strcolumnadded'] = 'Атрибут добавлен.';
	$lang['strcolumnaddedbad'] = 'Добавление атрибута прервано.';
	$lang['strdataonly'] = 'Только данные';
	$lang['strcascade'] = 'Каскадом';
	$lang['strtablealtered'] = 'Таблица изменена.';
	$lang['strtablealteredbad'] = 'Изменение таблицы прервано.';
	$lang['strdataonly'] = 'Только данные';
	$lang['strstructureonly'] = 'Только структуру';
	$lang['strstructureanddata'] = 'Структуру и данные';
	$lang['strtabbed'] = 'Через табуляцию';
	$lang['strauto'] = 'Авто';

	// Users
	$lang['struser'] = 'Пользователь';
	$lang['strusers'] = 'Пользователи';
	$lang['strusername'] = 'Имя пользователя';
	$lang['strpassword'] = 'Пароль';
	$lang['strsuper'] = 'Суперпользователь?';
	$lang['strcreatedb'] = 'Создать базу данных?';
	$lang['strexpires'] = 'Срок действия';
	$lang['strsessiondefaults'] = 'Опции сеанса по умолчанию';
	$lang['strnousers'] = 'Нет таких пользователей.';
	$lang['struserupdated'] = 'Пользователь обновлен.';
	$lang['struserupdatedbad'] = 'Обновление пользователя прервано.';
	$lang['strshowallusers'] = 'Показать всех пользователей';
	$lang['strcreateuser'] = 'Создать пользователя';
	$lang['struserneedsname'] = 'Вы должны ввести имя пользователя.';
	$lang['strusercreated'] = 'Пользователь создан.';
	$lang['strusercreatedbad'] = 'Создание пользователя прервано.';
	$lang['strconfdropuser'] = 'Вы уверены, что хотите удалить пользователя "%s"?';
	$lang['struserdropped'] = 'Пользователь удален.';
	$lang['struserdroppedbad'] = 'Удаление пользователя прервано.';
	$lang['straccount'] = 'Аккаунт';
	$lang['strchangepassword'] = 'Изменить пароль';
	$lang['strpasswordchanged'] = 'Пароль изменен.';
	$lang['strpasswordchangedbad'] = 'Изменение пароля прервано.';
	$lang['strpasswordshort'] = 'Пароль слишком короткий.';
	$lang['strpasswordconfirm'] = 'Пароль не соответствует подтверждению.';

	// Groups
	$lang['strgroup'] = 'Группа';
	$lang['strgroups'] = 'Группы';
	$lang['strnogroup'] = 'Группа не обнаружена.';
	$lang['strnogroups'] = 'Ни одной группы не найдено.';
	$lang['strcreategroup'] = 'Создать группу';
	$lang['strshowallgroups'] = 'Показать все группы';
	$lang['strgroupneedsname'] = 'Вам необходимо указать название группы.';
	$lang['strgroupcreated'] = 'Группа создана.';
	$lang['strgroupcreatedbad'] = 'Создание группы прервано.';
	$lang['strconfdropgroup'] = 'Вы уверены, что хотите удалить группу "%s"?';
	$lang['strgroupdropped'] = 'Группа удалена.';
	$lang['strgroupdroppedbad'] = 'Удаление группы прервано.';
	$lang['strmembers'] = 'Участников';
	$lang['straddmember'] = 'Добавить участника';
	$lang['strmemberadded'] = 'Участник добавлен.';
	$lang['strmemberaddedbad'] = 'Добавление участника прервано.';
	$lang['strdropmember'] = 'Удалить участника';
	$lang['strconfdropmember'] = 'Вы уверены, что хотите удалить участника "%s" из группы "%s"?';
	$lang['strmemberdropped'] = 'Участник удален.';
	$lang['strmemberdroppedbad'] = 'Удаление участника прервано.';

	// Privilges
	$lang['strprivilege'] = 'Привилегия';
	$lang['strprivileges'] = 'Привилегии';
	$lang['strnoprivileges'] = 'Объект не имеет привилегий.';
	$lang['strgrant'] = 'Усилить';
	$lang['strrevoke'] = 'Ослабить';
	$lang['strgranted'] = 'Привилегии изменены.';
	$lang['strgrantfailed'] = 'Изменение привилегий прервано.';
	$lang['strgrantbad'] = 'Вам необходимо указать хотя бы одного пользователя или группу и хотя бы одну привилегию.';
	$lang['stralterprivs'] = 'Изменить привилегии';
	$lang['strgrantor'] = 'Донор';
	$lang['strasterisk'] = '*';

	// Databases
	$lang['strdatabase'] = 'База данных';
	$lang['strdatabases'] = 'Базы данных';
	$lang['strshowalldatabases'] = 'Показать все базы данных';
	$lang['strnodatabase'] = 'База данных не обнаружена.';
	$lang['strnodatabases'] = 'Ни одной базы данных не найдено.';
	$lang['strcreatedatabase'] = 'Создать базу данных';
	$lang['strdatabasename'] = 'Имя базы данных';
	$lang['strdatabaseneedsname'] = 'Вам необходимо присвоить имя Вашей базе данных.';
	$lang['strdatabasecreated'] = 'База данных создана.';
	$lang['strdatabasecreatedbad'] = 'Создание базы данных прервано.';
	$lang['strconfdropdatabase'] = 'Вы уверены, что хотите уничтожить базу данных "%s"?';
	$lang['strdatabasedropped'] = ' База данных уничтожена.';
	$lang['strdatabasedroppedbad'] = 'Уничтожение базы данных прервано.';
	$lang['strentersql'] = 'Введите SQL-запрос ниже:';
	$lang['strsqlexecuted'] = 'SQL-запрос выполнен.';
	$lang['strvacuumgood'] = 'Операция перестройки завершена.';
	$lang['strvacuumbad'] = 'Операция перестройки прервана.';
	$lang['stranalyzegood'] = ' Операция анализа завершена.';
	$lang['stranalyzebad'] = ' Операция анализа завершена.';
	$lang['strreindexgood'] = 'Переиндексация завершена.';
	$lang['strreindexbad'] = 'Переиндексация прервана.';
	$lang['strfull'] = 'Полностью';
	$lang['strfreeze'] = 'Заморозить';
	$lang['strforce'] = 'Принудительно';

	// Views
	$lang['strview'] = 'Представление';
	$lang['strviews'] = 'Представления';
	$lang['strshowallviews'] = 'Показать все представления';
	$lang['strnoview'] = 'Представление не найдено.';
	$lang['strnoviews'] = 'Ни одного представления не найдено.';
	$lang['strcreateview'] = 'Создать представление';
	$lang['strviewname'] = 'Имя представления';
	$lang['strviewneedsname'] = 'Вам необходимо указать имя представления.';
	$lang['strviewneedsdef'] = 'Вам необходимо определить атрибуты представления.';
	$lang['strviewneedsfields'] = 'Вам необходимо определить атрибуты для выборки в ваше представление.';
	$lang['strviewcreated'] = 'Представление создано.';
	$lang['strviewcreatedbad'] = 'Создание представления прервано.';
	$lang['strconfdropview'] = 'Вы уверены, что хотите уничтожить представление "%s"?';
	$lang['strviewdropped'] = 'Представление уничтожено.';
	$lang['strviewdroppedbad'] = 'Уничтожение представления прервано.';
	$lang['strviewupdated'] = 'Представление обновлено.';
	$lang['strviewupdatedbad'] = 'Обновление представления прервано.';
	$lang['strviewlink'] = 'Связанные ключи';
	$lang['strviewconditions'] = 'Дополнительные условия';
	$lang['strcreateviewwiz'] = 'Создать представление помощником';

	// Sequences
	$lang['strsequence'] = 'Последовательность';
	$lang['strsequences'] = ' Последовательности';
	$lang['strshowallsequences'] = 'Показать все последовательности';
	$lang['strnosequence'] = 'Последовательность не обнаружена.';
	$lang['strnosequences'] = 'Ни одной последовательности не найдено.';
	$lang['strcreatesequence'] = 'Создать последовательность';
	$lang['strlastvalue'] = 'Последнее значение';
	$lang['strincrementby'] = 'Увеличение на';
	$lang['strstartvalue'] = 'Начальное значение';
	$lang['strmaxvalue'] = 'Макс. величина';
	$lang['strminvalue'] = 'Мин. величина';
	$lang['strcachevalue'] = 'Размер кэша';
	$lang['strlogcount'] = 'Log Count';
	$lang['striscycled'] = 'Зациклить?';
	$lang['strsequenceneedsname'] = 'Вам необходимо указать имя последовательности.';
	$lang['strsequencecreated'] = 'Последовательность создана.';
	$lang['strsequencecreatedbad'] = 'Создание последовательности прервано.';
	$lang['strconfdropsequence'] = 'Вы уверены, что хотите уничтожить последовательность "%s"?';
	$lang['strsequencedropped'] = 'Последовательность уничтожена.';
	$lang['strsequencedroppedbad'] = 'Уничтожение последовательности прервано.';
	$lang['strsequencereset'] = 'Последовательность сброшена.';
	$lang['strsequenceresetbad'] = 'Сброс последовательности прерван.'; 

	// Indexes
	$lang['strindex'] = 'Индекс';
	$lang['strindexes'] = 'Индексы';
	$lang['strindexname'] = 'Имя индекса';
	$lang['strshowallindexes'] = 'Показать все индексы';
	$lang['strnoindex'] = 'Индекс не обнаружен.';
	$lang['strnoindexes'] = 'Ни одного индекса не найдено.';
	$lang['strcreateindex'] = 'Создать индекс';
	$lang['strtabname'] = 'Имя таблицы';
	$lang['strcolumnname'] = 'Имя атрибута';
	$lang['strindexneedsname'] = 'Вам необходимо указать имя индекса';
	$lang['strindexneedscols'] = 'Вам необходимо указать допустимое количество атрибутов.';
	$lang['strindexcreated'] = 'Индекс создан.';
	$lang['strindexcreatedbad'] = 'Создание индекса прервано.';
	$lang['strconfdropindex'] = 'Вы уверены, что хотите уничтожить индекс "%s"?';
	$lang['strindexdropped'] = 'Индекс уничтожен.';
	$lang['strindexdroppedbad'] = 'Уничтожение индекса прервано.';
	$lang['strkeyname'] = 'Имя ключа';
	$lang['struniquekey'] = 'Уникальный ключ';
	$lang['strprimarykey'] = 'Первичный ключ';
	$lang['strindextype'] = 'Вид индекса';
	$lang['strindexname'] = 'Имя индекса';
	$lang['strtablecolumnlist'] = 'Атрибутов в таблице';
	$lang['strindexcolumnlist'] = 'Атрибутов в индексе';
	$lang['strconfcluster'] = 'Вы уверены, что хотите кластеризовать "%s"?';
	$lang['strclusteredgood'] = 'Кластеризация завершена.';
	$lang['strclusteredbad'] = 'Кластеризация прервана.';

	// Rules
	$lang['strrules'] = 'Правила';
	$lang['strrule'] = 'Правило';
	$lang['strshowallrules'] = 'Показать все правила';
	$lang['strnorule'] = 'Правило не найдено.';
	$lang['strnorules'] = 'Ни одного правила не найдено.';
	$lang['strcreaterule'] = 'Создать правило';
	$lang['strrulename'] = 'Имя правила';
	$lang['strruleneedsname'] = 'Вам необходимо указать имя правила.';
	$lang['strrulecreated'] = 'Правило создано.';
	$lang['strrulecreatedbad'] = 'Создание правила прервано.';
	$lang['strconfdroprule'] = 'Вы уверены, что хотите уничтожить правило "%s" on "%s"?';
	$lang['strruledropped'] = 'Правило уничтожено.';
	$lang['strruledroppedbad'] = 'Уничтожение правила прервано.';

	// Constraints
	$lang['strconstraints'] = 'Ограничения';
	$lang['strshowallconstraints'] = 'Показать все ограничения';
	$lang['strnoconstraints'] = 'Ни одного ограничения не найдено.';
	$lang['strcreateconstraint'] = 'Создать ограничение';
	$lang['strconstraintcreated'] = 'Ограничение создано.';
	$lang['strconstraintcreatedbad'] = 'Создание ограничения прервано.';
	$lang['strconfdropconstraint'] = 'Вы уверены, что хотите уничтожить ограничение "%s" on "%s"?';
	$lang['strconstraintdropped'] = 'Ограничение уничтожено.';
	$lang['strconstraintdroppedbad'] = 'Уничтожение ограничения прервано.';
	$lang['straddcheck'] = 'Добавить проверку';
	$lang['strcheckneedsdefinition'] = 'Ограничение проверки нуждается в определении.';
	$lang['strcheckadded'] = 'Ограничение проверки добавлено.';
	$lang['strcheckaddedbad'] = 'Добавление ограничения проверки прервано.';
	$lang['straddpk'] = 'Добавить первичный ключ';
	$lang['strpkneedscols'] = 'Первичный ключ должен включать хотя бы один атрибут.';
	$lang['strpkadded'] = 'Первичный ключ добавлен.';
	$lang['strpkaddedbad'] = 'Добавление первичного ключа прервано.';
	$lang['stradduniq'] = 'Добавить уникальный ключ';
	$lang['struniqneedscols'] = 'Уникальный ключ должен включать хотя бы один атрибут.';
	$lang['struniqadded'] = 'Уникальный ключ добавлен.';
	$lang['struniqaddedbad'] = 'Добавление уникального ключа прервано.';
	$lang['straddfk'] = 'Добавить внешний ключ';
	$lang['strfkneedscols'] = 'Внешний ключ должен включать хотя бы один атрибут.';
	$lang['strfkneedstarget'] = 'Внешнему ключу необходимо указать целевую таблицу.';
	$lang['strfkadded'] = 'Внешний ключ добавлен.';
	$lang['strfkaddedbad'] = 'Добавление внешнего ключа прервано.';
	$lang['strfktarget'] = 'Целевая таблица';
	$lang['strfkcolumnlist'] = 'Атрибуты в ключе';
	$lang['strondelete'] = 'ON DELETE';
	$lang['stronupdate'] = 'ON UPDATE';	

	// Functions
	$lang['strfunction'] = 'Функция';
	$lang['strfunctions'] = ' Функции';
	$lang['strshowallfunctions'] = 'Показать все функции';
	$lang['strnofunction'] = 'Функция не обнаружена.';
	$lang['strnofunctions'] = 'Ни одной функции не найдено.';
	$lang['strcreatefunction'] = 'Создать функцию';
	$lang['strfunctionname'] = 'Имя функции';
	$lang['strreturns'] = 'Возвращаемое значение';
	$lang['strarguments'] = 'Аргументы';
	$lang['strproglanguage'] = 'Язык программирования';
	$lang['strproglanguage'] = 'Язык';
	$lang['strfunctionneedsname'] = 'Вам необходимо указать имя функции.';
	$lang['strfunctionneedsdef'] = 'Вам необходимо определить функцию.';
	$lang['strfunctioncreated'] = 'Функция создана.';
	$lang['strfunctioncreatedbad'] = 'Создание функции прервано.';
	$lang['strconfdropfunction'] = 'Вы уверены, что хотите уничтожить функцию "%s"?';
	$lang['strfunctiondropped'] = 'Функция уничтожена.';
	$lang['strfunctiondroppedbad'] = 'Уничтожение функции прервано.';
	$lang['strfunctionupdated'] = 'Функция обновлена.';
	$lang['strfunctionupdatedbad'] = 'Обновление функции прервано.';

	// Triggers
	$lang['strtrigger'] = 'Триггер';
	$lang['strtriggers'] = ' Триггеры';
	$lang['strshowalltriggers'] = 'Показать все триггеры';
	$lang['strnotrigger'] = 'Триггер не обнаружен.';
	$lang['strnotriggers'] = 'Ни одного триггера не найдено.';
	$lang['strcreatetrigger'] = 'Создать триггер';
	$lang['strtriggerneedsname'] = 'Вам необходимо указать имя триггера.';
	$lang['strtriggerneedsfunc'] = 'Вам необходимо определить функцию триггера.';
	$lang['strtriggercreated'] = 'Триггер создан.';
	$lang['strtriggercreatedbad'] = 'Создание триггера прервано.';
	$lang['strconfdroptrigger'] = 'Вы уверены, что хотите уничтожить триггер "%s" на "%s"?';
	$lang['strtriggerdropped'] = 'Триггер уничтожен.';
	$lang['strtriggerdroppedbad'] = 'Уничтожение триггера прервано.';
	$lang['strtriggeraltered'] = 'Триггер изменен.';
	$lang['strtriggeralteredbad'] = 'Изменение триггера прервано.';

	// Types
	$lang['strtype'] = 'Тип данных';
	$lang['strtypes'] = 'Типы данных';
	$lang['strshowalltypes'] = 'Показать все типы данных';
	$lang['strnotype'] = 'Тип данных не обнаружен.';
	$lang['strnotypes'] = 'Ни одного типа данных не найдено.';
	$lang['strcreatetype'] = 'Создать тип данных';
	$lang['strtypename'] = 'Имя типа данных';
	$lang['strinputfn'] = 'Функция ввода';
	$lang['stroutputfn'] = 'Функция вывода';
	$lang['strpassbyval'] = 'Передать по значению?';
	$lang['stralignment'] = 'Выравнивание';
	$lang['strelement'] = 'Элемент';
	$lang['strdelimiter'] = 'Разделитель';
	$lang['strstorage'] = 'Storage';
	$lang['strtypeneedsname'] = 'Вам необходимо указать имя типа данных.';
	$lang['strtypeneedslen'] = 'Вам необходимо указать размер для типа данных.';
	$lang['strtypecreated'] = 'Тип данных создан.';
	$lang['strtypecreatedbad'] = 'Создание типа данных прервано.';
	$lang['strconfdroptype'] = 'Вы уверены, что хотите уничтожить тип данных "%s"?';
	$lang['strtypedropped'] = 'Тип данных уничтожен.';
	$lang['strtypedroppedbad'] = 'Уничтожение типа данных прервано.';

	// Schemas
	$lang['strschema'] = 'Схема';
	$lang['strschemas'] = 'Схемы';
	$lang['strshowallschemas'] = 'Показать все схемы';
	$lang['strnoschema'] = 'Схема не обнаружена.';
	$lang['strnoschemas'] = 'Ни одной схемы не найдено.';
	$lang['strcreateschema'] = 'Создать схему';
	$lang['strschemaname'] = 'Имя схемы';
	$lang['strschemaneedsname'] = 'Вам необходимо указать имя схемы.';
	$lang['strschemacreated'] = 'Схема создана.';
	$lang['strschemacreatedbad'] = 'Создание схемы прервано.';
	$lang['strconfdropschema'] = 'Вы уверены, что хотите уничтожить схему "%s"?';
	$lang['strschemadropped'] = 'Схема уничтожена.';
	$lang['strschemadroppedbad'] = 'Уничтожение схемы прервано.';
	$lang['strschemaaltered'] = 'Схема обновлена.';
	$lang['strschemaalteredbad'] = 'Обновление схемы прервано.';

	// Reports

	// Domains
	$lang['strdomain'] = 'Домен';
	$lang['strdomains'] = 'Домены';
	$lang['strshowalldomains'] = 'Показать все домены';
	$lang['strnodomains'] = 'Ни одного домена не найдено.';
	$lang['strcreatedomain'] = 'Создать домен';
	$lang['strdomaindropped'] = 'Домен удален.';
	$lang['strdomaindroppedbad'] = 'Удаление домена прервано.';
	$lang['strconfdropdomain'] = 'Вы уверены, что хотите удалить домен "%s"?';
	$lang['strdomainneedsname'] = 'Вам необходимо указать имя домена.';
	$lang['strdomaincreated'] = 'Домен создан.';
	$lang['strdomaincreatedbad'] = 'Создание домена прервано.';	
	$lang['strdomainaltered'] = 'Домен изменен.';
	$lang['strdomainalteredbad'] = 'Изменение домена прервано.';	

	// Operators
	$lang['stroperator'] = 'Оператор';
	$lang['stroperators'] = 'Операторы';
	$lang['strshowalloperators'] = 'Показать все операторы';
	$lang['strnooperator'] = 'Оператор не обнаружен.';
	$lang['strnooperators'] = 'Операторы не обнаружены.';
	$lang['strcreateoperator'] = 'Создать оператор';
	$lang['strleftarg'] = 'Тип левого аргумента';
	$lang['strrightarg'] = 'Тип правого аргумента';
	$lang['strcommutator'] = 'Преобразование';
	$lang['strnegator'] = 'Отрицание';
	$lang['strrestrict'] = 'Ослабление';
	$lang['strjoin'] = 'Объединение';
	$lang['strhashes'] = 'Хеширование';
	$lang['strmerges'] = 'Слияние';
	$lang['strleftsort'] = 'Сотировка по левому';
	$lang['strrightsort'] = 'Сотировка по правому';
	$lang['strlessthan'] = 'Меньше';
	$lang['strgreaterthan'] = 'Больше';
	$lang['stroperatorneedsname'] = 'Вам необходимо указать название оператора.';
	$lang['stroperatorcreated'] = 'Оператор создан';
	$lang['stroperatorcreatedbad'] = 'Создание оператора прервано.';
	$lang['strconfdropoperator'] = 'Вы уверены, что хотите уничтожить оператор "%s"?';
	$lang['stroperatordropped'] = 'Оператор удален.';
	$lang['stroperatordroppedbad'] = 'Удаление оператора прервано.';

	// Casts
	$lang['strcasts'] = 'Образцы';
	$lang['strnocasts'] = 'Образцов не найдено.';
	$lang['strsourcetype'] = 'Тип источника';
	$lang['strtargettype'] = 'Тип приемника';
	$lang['strimplicit'] = 'Неявный';
	$lang['strinassignment'] = 'В назначении';
	$lang['strbinarycompat'] = '(двоично совместимый)';
	
	// Conversions
	$lang['strconversions'] = 'Преобразование';
	$lang['strnoconversions'] = 'Преобразований не найдено.';
	$lang['strsourceencoding'] = 'Кодировка источника';
	$lang['strtargetencoding'] = 'Кодировка приемника';
	
	// Languages
	$lang['strlanguages'] = 'Языки';
	$lang['strnolanguages'] = 'Языков не найдено.';
	$lang['strtrusted'] = 'Проверено';
	
	// Info
	$lang['strnoinfo'] = 'Нет доступной информации.';
	$lang['strreferringtables'] = 'Ссылающиеся таблицы';
	$lang['strparenttables'] = 'Родительские таблицы';
	$lang['strchildtables'] = 'Дочерние таблицы';

	// Aggregates
	$lang['straggregates'] = 'Агрегатные выражения';
	$lang['strnoaggregates'] = 'Агрегатных выражений не найдено.';
	$lang['stralltypes'] = '(Все типы)';

	// Operator Classes
	$lang['stropclasses'] = 'Классы операторов';
	$lang['strnoopclasses'] = 'Классов операторов не найдено.';
	$lang['straccessmethod'] = 'Метод доступа';

	// Stats and performance
	$lang['strrowperf'] = 'Представление записи';
	$lang['strioperf'] = 'Представление ввода/вывода';
	$lang['stridxrowperf'] = 'Представление индекса записи';
	$lang['stridxioperf'] = 'Представление индекса ввода/вывода';
	$lang['strpercent'] = '%';
	$lang['strsequential'] = 'Последовательный';
	$lang['strscan'] = 'Сканировать';
	$lang['strread'] = 'Читать';
	$lang['strfetch'] = 'Извлечь';
	$lang['strheap'] = 'Мусор';
	$lang['strtoast'] = 'TOAST';
	$lang['strtoastindex'] = 'TOAST индекс';
	$lang['strcache'] = 'Кеш';
	$lang['strdisk'] = 'Диск';
	$lang['strrows2'] = 'Записи';

	// Miscellaneous
	$lang['strtopbar'] = '%s выполняется на %s:%s -- Вы зарегистрированы как "%s"';
	$lang['strtimefmt'] = ' j-m-Y  g:i';
	$lang['strhelp'] = 'Помощь';

?>
