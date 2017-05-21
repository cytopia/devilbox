jush.tr.sqlite = { sqlite_apo: /'/, sqlite_quo: /"/, bra: /\[/, bac: /`/, one: /--/, com: /\/\*/, sql_var: /[:@$]/, sqlite_sqliteset: /(\b)(PRAGMA)(\s+)/i, num: jush.num };
jush.tr.sqlite_sqliteset = { sqlite_apo: /'/, sqlite_quo: /"/, bra: /\[/, bac: /`/, one: /--/, com: /\/\*/, num: jush.num, _1: /;|$/ };
jush.tr.sqliteset = { _0: /$/ };
jush.tr.sqlitestatus = { _0: /$/ };

jush.urls.sqlite_sqliteset = 'http://www.sqlite.org/$key';
jush.urls.sqlite = ['http://www.sqlite.org/$key',
	'lang_$1.html', 'lang_createvtab.html', 'lang_transaction.html',
	'lang_createindex.html', 'lang_createtable.html', 'lang_createtrigger.html', 'lang_createview.html',
	'',
	'lang_expr.html#$1', 'lang_corefunc.html#$1', 'lang_datefunc.html#$1', 'lang_aggfunc.html#$1'
];
jush.urls.sqliteset = ['http://www.sqlite.org/pragma.html#$key',
	'pragma_$1'
];
jush.urls.sqlitestatus = ['http://www.sqlite.org/compile.html#$key',
	'$1'
];

jush.links.sqlite_sqliteset = { 'pragma.html': /.+/ };

jush.links2.sqlite = /(\b)(ALTER\s+TABLE|ANALYZE|ATTACH|COPY|DELETE|DETACH|DROP\s+INDEX|DROP\s+TABLE|DROP\s+TRIGGER|DROP\s+VIEW|EXPLAIN|INSERT|CONFLICT|REINDEX|REPLACE|SELECT|UPDATE|TRANSACTION|VACUUM|(CREATE\s+VIRTUAL\s+TABLE)|(BEGIN|COMMIT|ROLLBACK)|(CREATE(?:\s+UNIQUE)?\s+INDEX)|(CREATE(?:\s+TEMP|\s+TEMPORARY)?\s+TABLE)|(CREATE(?:\s+TEMP|\s+TEMPORARY)?\s+TRIGGER)|(CREATE(?:\s+TEMP|\s+TEMPORARY)?\s+VIEW)|(ABORT|ACTION|ADD|AFTER|ALL|AS|ASC|AUTOINCREMENT|BEFORE|BY|CASCADE|CHECK|COLUMN|CONSTRAINT|CROSS|CURRENT_DATE|CURRENT_TIME|CURRENT_TIMESTAMP|DATABASE|DEFAULT|DEFERRABLE|DEFERRED|DESC|DISTINCT|EACH|END|EXCEPT|EXCLUSIVE|FAIL|FOR|FOREIGN|FROM|FULL|GROUP|HAVING|IF|IGNORE|IMMEDIATE|INDEXED|INITIALLY|INNER|INSTEAD|INTERSECT|INTO|IS|JOIN|KEY|LEFT|LIMIT|NATURAL|NO|NOT|NOTNULL|NULL|OF|OFFSET|ON|ORDER|OUTER|PLAN|PRAGMA|PRIMARY|QUERY|RAISE|REFERENCES|RELEASE|RENAME|RESTRICT|RIGHT|ROW|SAVEPOINT|SET|TEMPORARY|TO|UNION|UNIQUE|USING|VALUES|WHERE)|(like|glob|regexp|match|escape|isnull|isnotnull|between|exists|case|when|then|else|cast|collate|in|and|or|not))\b|\b(abs|coalesce|glob|ifnull|hex|last_insert_rowid|length|like|load_extension|lower|nullif|quote|random|randomblob|round|soundex|sqlite_version|substr|typeof|upper|(date|time|datetime|julianday|strftime)|(avg|count|max|min|sum|total))(\s*\(|$)/gi; // collisions: min, max, end, like, glob
jush.links2.sqliteset = /(\b)(auto_vacuum|cache_size|case_sensitive_like|count_changes|default_cache_size|empty_result_callbacks|encoding|foreign_keys|full_column_names|fullfsync|incremental_vacuum|journal_mode|journal_size_limit|legacy_file_format|locking_mode|page_size|max_page_count|read_uncommitted|recursive_triggers|reverse_unordered_selects|secure_delete|short_column_names|synchronous|temp_store|temp_store_directory|collation_list|database_list|foreign_key_list|freelist_count|index_info|index_list|page_count|table_info|schema_version|compile_options|integrity_check|quick_check|parser_trace|vdbe_trace|vdbe_listing)(\b)/gi;
jush.links2.sqlitestatus = /()(.+)()/g;
