<?php

	/**
	 * Japanese language file for phpPgAdmin.
	 * @maintainer Tadashi Jokagi [elf2000@users.sourceforge.net]
	 *
	 * $Id: japanese.php,v 1.19 2007/12/27 04:08:35 xzilla Exp $
	 * EN-Revision: 1.228
	 */

	// Language and character set
	$lang['applang'] = '日本語(EUC-JP)';
	$lang['applocale'] = 'ja-JP';
	$lang['applangdir'] = 'ltr';
  
	// Welcome  
	$lang['strintro'] = 'ようこそ phpPgAdmin へ。';
	$lang['strppahome'] = 'phpPgAdmin ホームページ';
	$lang['strpgsqlhome'] = 'PostgreSQL ホームページ';
	$lang['strpgsqlhome_url'] = 'http://www.postgresql.org/';
	$lang['strlocaldocs'] = 'PostgreSQL ドキュメント (ローカル)';
	$lang['strreportbug'] = 'バグレポート';
	$lang['strviewfaq'] = 'FAQ を表示する';
	$lang['strviewfaq_url'] = 'http://phppgadmin.sourceforge.net/doku.php?id=faq';
	
	// Basic strings
	$lang['strlogin'] = 'ログイン';
	$lang['strloginfailed'] = 'ログインに失敗しました';
	$lang['strlogindisallowed'] = 'ログインが許可されませんでした。';
	$lang['strserver'] = 'サーバー';
	$lang['strservers'] = 'サーバー';
	$lang['strintroduction'] = '導入';
	$lang['strhost'] = 'ホスト';
	$lang['strport'] = 'ポート';
	$lang['strlogout'] = 'ログアウト';
	$lang['strowner'] = '所有者';
	$lang['straction'] = 'アクション';
	$lang['stractions'] = '操作';
	$lang['strname'] = '名前';
	$lang['strdefinition'] = '定義';
	$lang['strproperties'] = 'プロパティ';
	$lang['strbrowse'] = '表示';
	$lang['strenable']  =  '有効';
	$lang['strdisable']  =  '無効';
	$lang['strdrop'] = '破棄';
	$lang['strdropped'] = '破棄しました。';
	$lang['strnull'] = 'NULL';
	$lang['strnotnull'] = 'NOT NULL';
	$lang['strprev'] = '前に';
	$lang['strnext'] = '次に';
	$lang['strfirst'] = '<< 最初';
	$lang['strlast'] = '最後 >>';
	$lang['strfailed'] = '失敗';
	$lang['strcreate'] = '作成';
	$lang['strcreated'] = '作成しました。';
	$lang['strcomment'] = 'コメント';
	$lang['strlength'] = '長さ';
	$lang['strdefault'] = 'デフォルト';
	$lang['stralter'] = '変更';
	$lang['strok'] = 'OK';
	$lang['strcancel'] = '取り消し';
	$lang['strac']  =  '自動補完を有効にする';
	$lang['strsave'] = '保存';
	$lang['strreset'] = 'リセット';
	$lang['strinsert'] = '挿入';
	$lang['strselect'] = '選択';
	$lang['strdelete'] = '削除';
	$lang['strupdate'] = '更新';
	$lang['strreferences'] = '参照';
	$lang['stryes'] = 'はい';
	$lang['strno'] = 'いいえ';
	$lang['strtrue'] = '真';
	$lang['strfalse'] = '偽';
	$lang['stredit'] = '編集';
	$lang['strcolumn'] = 'カラム';
	$lang['strcolumns'] = 'カラム';
	$lang['strrows'] = 'レコード';
	$lang['strrowsaff'] = '影響を受けたレコード';
	$lang['strobjects'] = 'オブジェクト';
	$lang['strback'] = '戻る';
	$lang['strqueryresults'] = 'クエリ結果';
	$lang['strshow'] = '表示';
	$lang['strempty'] = '空にする';
	$lang['strlanguage'] = '言語';
	$lang['strencoding'] = 'エンコード';
	$lang['strvalue'] = '値';
	$lang['strunique'] = 'ユニーク';
	$lang['strprimary'] = 'プライマリ';
	$lang['strexport'] = 'エクスポート';
	$lang['strimport'] = 'インポート';
	$lang['strallowednulls']  =  'NULL 文字を許可する';
	$lang['strbackslashn']  =  '\N';
	$lang['stremptystring']  =  '空の文字列/項目';
	$lang['strsql'] = 'SQL';
	$lang['stradmin'] = '管理';
	$lang['strvacuum'] = 'バキューム';
	$lang['stranalyze'] = '解析';
	$lang['strclusterindex']  =  'クラスター';
$lang['strclustered'] = 'Clustered?';
	$lang['strreindex'] = '再インデックス';
	$lang['strexecute']  =  '実行する';
	$lang['stradd'] = '追加';
	$lang['strevent'] = 'イベント';
	$lang['strwhere'] = 'Where';
	$lang['strinstead'] = '代行';
	$lang['strwhen'] = 'When';
	$lang['strformat'] = 'フォーマット';
	$lang['strdata'] = 'データ';
	$lang['strconfirm'] = '確認';
	$lang['strexpression'] = '評価式';
	$lang['strellipsis'] = '...';
	$lang['strseparator'] = ': ';
	$lang['strexpand'] = '展開';
	$lang['strcollapse'] = '閉じる';
	$lang['strfind'] = '検索';
	$lang['stroptions'] = 'オプション';
	$lang['strrefresh'] = '再表示';
	$lang['strdownload'] = 'ダウンロード';
	$lang['strdownloadgzipped'] = 'gzip で圧縮してダウンロード';
	$lang['strinfo'] = '情報';
	$lang['stroids'] = 'OID ';
	$lang['stradvanced'] = '高度な';
	$lang['strvariables'] = '変数';
	$lang['strprocess'] = 'プロセス';
	$lang['strprocesses'] = 'プロセス';
	$lang['strsetting'] = '設定';
	$lang['streditsql'] = 'SQL 編集';
	$lang['strruntime'] = '総実行時間: %s ミリ秒';
	$lang['strpaginate'] = '結果のページ分割処理を行う';
	$lang['struploadscript'] = 'または SQL スクリプトをアップロード:';
	$lang['strstarttime'] = '開始時間';
	$lang['strfile'] = 'ファイル';
	$lang['strfileimported'] = 'ファイルをインポートしました。';
	$lang['strtrycred']  =  'すべてのサーバーでこの情報を使う';
	$lang['stractionsonmultiplelines']  =  '複数行の操作';
	$lang['strselectall']  =  'すべて選択する';
	$lang['strunselectall']  =  'すべて選択を解除する';
	$lang['strlocale']  =  'ロケール';

	// User-supplied SQL history
	$lang['strhistory']  =  '履歴';
	$lang['strnohistory']  =  '履歴がありません。';
	$lang['strclearhistory']  =  '履歴を消去するす';
	$lang['strdelhistory']  =  '履歴から削除する';
	$lang['strconfdelhistory']  =  '本当に履歴からこの要求を削除しますか?';
	$lang['strconfclearhistory']  =  '本当に履歴を消去しますか?';
	$lang['strnodatabaseselected']  =  'データベースを選択してください。';

	// Database Sizes
	$lang['strsize']  =  'サイズ';
	$lang['strbytes']  =  'バイト';
	$lang['strkb']  =  'kB';
	$lang['strmb']  =  'MB';
	$lang['strgb']  =  'GB';
	$lang['strtb']  =  'TB';

	// Error handling
	$lang['strnoframes'] = 'このアプリケーションを使用するためにはフレームが使用可能なブラウザーが必要です。';
	$lang['strnoframeslink'] = 'フレームを除外して使う';
	$lang['strbadconfig'] = 'config.inc.php が旧式です。新しい config.inc.php-dist から再作成する必要があります。';
	$lang['strnotloaded'] = 'データベースをサポートするように PHP のコンパイル・インストールがされていません。configure の --with-pgsql オプションを用いて PHP を再コンパイルする必要があります。';
	$lang['strpostgresqlversionnotsupported'] = 'このバージョンの PostgreSQL はサポートしていません。バージョン %s 以上にアップグレードしてください。';
	$lang['strbadschema'] = '無効のスキーマが指定されました。';
	$lang['strbadencoding'] = 'データベースの中でクライアントエンコードを指定しませんでした。';
	$lang['strsqlerror'] = 'SQL エラー:';
	$lang['strinstatement'] = '文:';
	$lang['strinvalidparam'] = 'スクリプトパラメータが無効です。';
	$lang['strnodata'] = 'レコードが見つかりません。';
	$lang['strnoobjects'] = 'オブジェクトが見つかりません。';
	$lang['strrownotunique'] = 'このレコードには一意識別子がありません。';
	$lang['strnouploads'] = 'ファイルアップロードが無効です。';
	$lang['strimporterror'] = 'インポートエラー';
	$lang['strimporterror-fileformat']  =  'インポートエラー: ファイル形式を自動的に確定できません。.';
	$lang['strimporterrorline'] = '%s 行目がインポートエラーです。';
	$lang['strimporterrorline-badcolumnnum']  =  '%s 行でインポートエラー:  行は正しい列数を持っていません。';
	$lang['strimporterror-uploadedfile']  =  'インポートエラー: サーバーにファイルをアップロードすることができないかもしれません。';
	$lang['strcannotdumponwindows']  =  'Windows 上での複合テーブルとスキーマ名のダンプはサポートしていません。';
$lang['strinvalidserverparam']  =  'Attempt to connect with invalid server parameter, possibly someone is trying to hack your system.'; 
	$lang['strnoserversupplied']  =  'サーバーが指定されていません!';

	// Tables
	$lang['strtable'] = 'テーブル';
	$lang['strtables'] = 'テーブル';
	$lang['strshowalltables'] = 'すべてのテーブルを表示する';
	$lang['strnotables'] = 'テーブルが見つかりません。';
	$lang['strnotable'] = 'テーブルが見つかりません。';
	$lang['strcreatetable'] = 'テーブルを作成する';
	$lang['strcreatetablelike']  =  'このテーブルを元に新しいテーブルを作成する';
	$lang['strcreatetablelikeparent']  =  '元テーブル';
	$lang['strcreatelikewithdefaults']  =  'DEFAULT を含む';
	$lang['strcreatelikewithconstraints']  =  'CONSTRAINTS を含む';
	$lang['strcreatelikewithindexes']  =  'INDEX を含む';
	$lang['strtablename'] = 'テーブル名';
	$lang['strtableneedsname'] = 'テーブル名を指定する必要があります。';
$lang['strtablelikeneedslike']  =  'You must give a table to copy properties from.';
	$lang['strtableneedsfield'] = '少なくとも一つのフィールドを指定しなければなりません。';
	$lang['strtableneedscols'] = '有効なカラム数を指定しなければなりません。';
	$lang['strtablecreated'] = 'テーブルを作成しました。';
	$lang['strtablecreatedbad'] = 'テーブルの作成に失敗しました。';
	$lang['strconfdroptable'] = 'テーブル「%s」を本当に破棄しますか?';
	$lang['strtabledropped'] = 'テーブルを破棄しました。';
	$lang['strtabledroppedbad'] = 'テーブルの破棄に失敗しました。';
	$lang['strconfemptytable'] = '本当にテーブル「%s」の内容を破棄しますか?';
	$lang['strtableemptied'] = 'テーブルが空になりました.';
	$lang['strtableemptiedbad'] = 'テーブルを空にできませんでした。';
	$lang['strinsertrow'] = 'レコードの挿入';
	$lang['strrowinserted'] = 'レコードを挿入しました。';
	$lang['strrowinsertedbad'] = 'レコードの挿入に失敗しました。';
	$lang['strrowduplicate']  =  'レコードの挿入に失敗し、挿入の複製を試みました。';
	$lang['streditrow'] = 'レコード編集';
	$lang['strrowupdated'] = 'レコードを更新しました。';
	$lang['strrowupdatedbad'] = 'レコードの更新に失敗しました。';
	$lang['strdeleterow'] = 'レコード削除';
	$lang['strconfdeleterow'] = '本当にこのレコードを削除しますか?';
	$lang['strrowdeleted'] = 'レコードを削除しました。';
	$lang['strrowdeletedbad'] = 'レコードの削除に失敗しました。';
	$lang['strinsertandrepeat'] = '挿入と繰り返し';
	$lang['strnumcols'] = 'カラムの数';
	$lang['strcolneedsname'] = 'カラムの名前を指定しなければりません。';
	$lang['strselectallfields'] = 'すべてのフィールドを選択する';
	$lang['strselectneedscol'] = '少なくとも一カラムは必要です。';
	$lang['strselectunary'] = '単項のオペレーターは値を持つことができません。';
	$lang['strcolumnaltered'] = 'カラムを変更しました。';
	$lang['strcolumnalteredbad'] = 'カラムの変更に失敗しました。';
	$lang['strconfdropcolumn'] = '本当にカラム「%s」をテーブル「%s」から破棄していいですか?';
	$lang['strcolumndropped'] = 'カラムを破棄しました。';
	$lang['strcolumndroppedbad'] = 'カラムの破棄に失敗しました。';
	$lang['straddcolumn'] = 'カラムの追加';
	$lang['strcolumnadded'] = 'カラムを追加しました。';
	$lang['strcolumnaddedbad'] = 'カラムの追加に失敗しました。';
	$lang['strcascade'] = 'カスケード';
	$lang['strtablealtered'] = 'テーブルを変更しました。';
	$lang['strtablealteredbad'] = 'テーブルの変更に失敗しました。';
	$lang['strdataonly'] = 'データのみ';
	$lang['strstructureonly'] = '構造のみ';
	$lang['strstructureanddata'] = '構造とデータ';
	$lang['strtabbed'] = 'タブ区切り';
	$lang['strauto'] = '自動';
	$lang['strconfvacuumtable'] = '本当に「%s」をバキュームしますか?';
	$lang['strconfanalyzetable']  =  '「%s」を本当に分析(ANALYZE)しますか?';
	$lang['strestimatedrowcount'] = '評価済レコード数';
	$lang['strspecifytabletoanalyze']  =  'テーブルを解析には少なくとも 1 つ指定しなければなりません';
	$lang['strspecifytabletoempty']  =  'テーブルを空にするには少なくとも 1 つ指定しなければなりません';
	$lang['strspecifytabletodrop']  =  'テーブルを破棄するには少なくとも 1 つ指定しなければなりません';
	$lang['strspecifytabletovacuum']  =  'テーブルをバキュームするには少なくとも 1 つ指定しなければなりません';

	// Columns
	$lang['strcolprop']  =  'カラムのプロパティ';
	$lang['strnotableprovided']  =  'テーブルが指定されていません!';
		
	// Users
	$lang['struser'] = 'ユーザー';
	$lang['strusers'] = 'ユーザー';
	$lang['strusername'] = 'ユーザー名';
	$lang['strpassword'] = 'パスワード';
	$lang['strsuper'] = 'スーパーユーザーですか?';
	$lang['strcreatedb'] = 'データベースを作成しますか?';
	$lang['strexpires'] = '有効期限';
	$lang['strsessiondefaults'] = 'セッションデフォルト';
	$lang['strnousers'] = 'ユーザーが見つかりません。';
	$lang['struserupdated'] = 'ユーザーを更新しました。';
	$lang['struserupdatedbad'] = 'ユーザーの更新に失敗しました。';
	$lang['strshowallusers'] = 'すべてのユーザーを表示する';
	$lang['strcreateuser'] = 'ユーザーを作成する';
	$lang['struserneedsname'] = 'ユーザーの名前をが必要です。';
	$lang['strusercreated'] = 'ユーザーを作成しました。';
	$lang['strusercreatedbad'] = 'ユーザーの作成に失敗しました。';
	$lang['strconfdropuser'] = '本当にユーザー「%s」を破棄しますか?';
	$lang['struserdropped'] = 'ユーザーを破棄しました。';
	$lang['struserdroppedbad'] = 'ユーザーの削除に破棄しました';
	$lang['straccount'] = 'アカウント';
	$lang['strchangepassword'] = 'パスワード変更';
	$lang['strpasswordchanged'] = 'パスワードの変更をしました。';
	$lang['strpasswordchangedbad'] = 'パスワードの変更に失敗しました。';
	$lang['strpasswordshort'] = 'パスワードが短すぎます。';
	$lang['strpasswordconfirm'] = 'パスワードの確認が一致しませんでした。';
		
	// Groups
	$lang['strgroup'] = 'グループ';
	$lang['strgroups'] = 'グループ';
	$lang['strshowallgroups']  =  'すべてのグループを表示する';
	$lang['strnogroup'] = 'グループがありません。';
	$lang['strnogroups'] = 'グループが見つかりません。';
	$lang['strcreategroup'] = 'グループを作成する';
	$lang['strgroupneedsname'] = 'グループ名を指定しなければなりません。';
	$lang['strgroupcreated'] = 'グループを作成しました。';
	$lang['strgroupcreatedbad'] = 'グループの作成に失敗しました。';	
	$lang['strconfdropgroup'] = '本当にグループ「%s」を破棄しますか?';
	$lang['strgroupdropped'] = 'グループを破棄しました。';
	$lang['strgroupdroppedbad'] = 'グループの破棄に失敗しました。';
	$lang['strmembers'] = 'メンバー';
	$lang['strmemberof']  =  '次のグループのメンバー:';
	$lang['stradminmembers']  =  '管理メンバー';
	$lang['straddmember'] = 'メンバーを追加する';
	$lang['strmemberadded'] = 'メンバーを追加しました。';
	$lang['strmemberaddedbad'] = 'メンバーの追加に失敗しました。';
	$lang['strdropmember'] = 'メンバー破棄';
	$lang['strconfdropmember'] = '本当にメンバー「%s」をグループ「%s」から破棄しますか?';
	$lang['strmemberdropped'] = 'メンバーを破棄しました。';
	$lang['strmemberdroppedbad'] = 'メンバーの破棄に失敗しました。';

	// Roles
	$lang['strrole']  =  'ロール';
	$lang['strroles']  =  'ロール';
	$lang['strshowallroles']  =  'すべてのロールを表示する';
	$lang['strnoroles']  =  'ロールが見つかりません。';
	$lang['strinheritsprivs']  =  '特権を引き継ぎますか?';
	$lang['strcreaterole']  =  'ロールを作成する';
	$lang['strcancreaterole']  =  'ロールを作成できますか?';
	$lang['strrolecreated']  =  'ロールを作成しました。';
	$lang['strrolecreatedbad']  =  'ロールの作成に失敗しました。';
	$lang['strrolealtered']  =  'ロールを変更しました。';
	$lang['strrolealteredbad']  =  'ロールの変更に失敗しました。';
	$lang['strcanlogin']  =  'ログインできますか?';
	$lang['strconnlimit']  =  '接続制限';
	$lang['strdroprole']  =  'ロールを破棄する';
	$lang['strconfdroprole']  =  '本当にロール「%s」を破棄しますか?';
	$lang['strroledropped']  =  'ロールを破棄しました。';
	$lang['strroledroppedbad']  =  'ロールの破棄に失敗しました。';
	$lang['strnolimit']  =  '制限なし';
	$lang['strnever']  =  'Never';
	$lang['strroleneedsname']  =  'ロールの名前を指定しなければなりません。';

	// Privileges
	$lang['strprivilege'] = '特権';
	$lang['strprivileges'] = '特権';
	$lang['strnoprivileges'] = 'このオブジェクトは特権を持っていません。';
	$lang['strgrant'] = '権限';
	$lang['strrevoke'] = '廃止';
	$lang['strgranted'] = '特権を与えました。';
	$lang['strgrantfailed'] = '特権を与える事に失敗しました。';
	$lang['strgrantbad'] = '少なくとも一人のユーザーかグループに、少なくともひとつの特権を指定しなければなりません。';
	$lang['strgrantor'] = '譲与';
	$lang['strasterisk'] = '*';

	// Databases
	$lang['strdatabase'] = 'データベース';
	$lang['strdatabases'] = 'データベース';
	$lang['strshowalldatabases'] = 'すべてのデータベースを表示する';
	$lang['strnodatabases'] = 'データベースがまったくありません。';
	$lang['strcreatedatabase'] = 'データベースを作成する';
	$lang['strdatabasename'] = 'データベース名';
	$lang['strdatabaseneedsname'] = 'データベース名を指定しなければなりません。';
	$lang['strdatabasecreated'] = 'データベースを作成しました。';
	$lang['strdatabasecreatedbad'] = 'データベースの作成に失敗しました。';	
	$lang['strconfdropdatabase'] = '本当にデータベース「%s」を破棄しますか?';
	$lang['strdatabasedropped'] = 'データベースを破棄しました。';
	$lang['strdatabasedroppedbad'] = 'データベースの破棄に失敗しました。';
	$lang['strentersql'] = '下に実行するSQLを入力します:';
	$lang['strsqlexecuted'] = 'SQLを実行しました。';
	$lang['strvacuumgood'] = 'バキュームを完了しました。';
	$lang['strvacuumbad'] = 'バキュームに失敗しました。';
	$lang['stranalyzegood'] = '解析を完了しました。';
	$lang['stranalyzebad'] = '解析に失敗しました。';
	$lang['strreindexgood'] = '再インデックスを完了しました。';
	$lang['strreindexbad'] = '再インデックスに失敗しました。';
	$lang['strfull'] = 'すべて';
	$lang['strfreeze'] = 'フリーズ';
	$lang['strforce'] = '強制';
	$lang['strsignalsent'] = 'シグナル送信';
	$lang['strsignalsentbad'] = 'シグナル送信に失敗しました';
	$lang['strallobjects'] = 'すべてのオブジェクト';
	$lang['strdatabasealtered']  =  'データベースを変更しました。';
	$lang['strdatabasealteredbad']  =  'データベースの変更に失敗しました。';
	$lang['strspecifydatabasetodrop']  =  'データベースを破棄するには少なくとも 1 つ指定しなければなりません';

	// Views
	$lang['strview'] = 'ビュー';
	$lang['strviews'] = 'ビュー';
	$lang['strshowallviews'] = 'すべてのビューを表示する';
	$lang['strnoview'] = 'ビューがありません。';
	$lang['strnoviews'] = 'ビューが見つかりません。';
	$lang['strcreateview'] = 'ビューを作成する';
	$lang['strviewname'] = 'ビュー名';
	$lang['strviewneedsname'] = 'ビュー名を指定しなければなりません。';
	$lang['strviewneedsdef'] = '定義名を指定しなければなりません。';
	$lang['strviewneedsfields'] = 'ビューのの中から選択し、希望のカラムを指定しなければなりません。';
	$lang['strviewcreated'] = 'ビューを作成しました。';
	$lang['strviewcreatedbad'] = 'ビューの作成に失敗しました。';
	$lang['strconfdropview'] = '本当にビュー「%s」を破棄しますか?';
	$lang['strviewdropped'] = 'ビューを破棄しました。';
	$lang['strviewdroppedbad'] = 'ビューの破棄に失敗しました。';
	$lang['strviewupdated'] = 'ビューを更新しました。';
	$lang['strviewupdatedbad'] = 'ビューの更新に失敗しました。';
	$lang['strviewlink'] = 'リンクしたキー';
	$lang['strviewconditions'] = '追加条件';
	$lang['strcreateviewwiz'] = 'ウィザードでビューを作成する';
	$lang['strrenamedupfields']  =  '重複項目の名前を変更する';
	$lang['strdropdupfields']  =  '重複項目を破棄する';
	$lang['strerrordupfields']  =  '重複項目のエラーです';
	$lang['strviewaltered']  =  'ビューを変更しました。';
	$lang['strviewalteredbad']  =  'ビューの変更に失敗しました。';
	$lang['strspecifyviewtodrop']  =  'ビューを破棄するには少なくとも 1 つ指定しなければなりません';

	// Sequences
	$lang['strsequence'] = 'シーケンス';
	$lang['strsequences'] = 'シーケンス';
	$lang['strshowallsequences'] = 'すべてのシーケンスを表示する';
	$lang['strnosequence'] = 'シーケンスがありません。';
	$lang['strnosequences'] = 'シーケンスが見つかりません。';
	$lang['strcreatesequence'] = 'シーケンスを作成する';
	$lang['strlastvalue'] = '最終値';
	$lang['strincrementby'] = '増加数';	
	$lang['strstartvalue'] = '開始値';
	$lang['strmaxvalue'] = '最大値';
	$lang['strminvalue'] = '最小値';
	$lang['strcachevalue'] = 'キャッシュ値';
	$lang['strlogcount'] = 'ログカウント';
$lang['strcancycle']  =  'Can cycle?';
$lang['striscalled']  =  'Will increment last value before returning next value (is_called)?';
	$lang['strsequenceneedsname'] = 'シーケンス名を指定しなければなりません。';
	$lang['strsequencecreated'] = 'シーケンスを作成しました。';
	$lang['strsequencecreatedbad'] = 'シーケンスの作成に失敗しました。'; 
	$lang['strconfdropsequence'] = '本当にシーケンス「%s」を破棄しますか?';
	$lang['strsequencedropped'] = 'シーケンスを破棄しました。';
	$lang['strsequencedroppedbad'] = 'シーケンスの破棄に失敗しました。';
	$lang['strsequencereset'] = 'シーケンスリセットを行いました。';
	$lang['strsequenceresetbad'] = 'シーケンスのリセットに失敗しました。'; 
	$lang['strsequencealtered']  =  'シーケンスを変更しました。';
	$lang['strsequencealteredbad']  =  'シーケンスの変更に失敗しました。';
	$lang['strsetval']  =  '値を設定する';
	$lang['strsequencesetval']  =  'シーケンス値を設定しました。';
	$lang['strsequencesetvalbad']  =  'シーケンス値の設定に失敗しました。';
	$lang['strnextval']  =  '値を増加する';
	$lang['strsequencenextval']  =  '値を増加しました。';
	$lang['strsequencenextvalbad']  =  '値の増加に失敗しました。';
	$lang['strspecifysequencetodrop']  =  'シーケンスを破棄するには少なくとも 1 つ指定しなければなりません';

	// Indexes
	$lang['strindex'] = 'インデックス';
	$lang['strindexes'] = 'インデックス';
	$lang['strindexname'] = 'インデックス名';
	$lang['strshowallindexes'] = 'すべてのインデックスを表示する';
	$lang['strnoindex'] = 'インデックスがありません。';
	$lang['strnoindexes'] = 'インデックスが見つかりません。';
	$lang['strcreateindex'] = 'インデックスを作成する';
	$lang['strtabname'] = 'タブ名';
	$lang['strcolumnname'] = 'カラム名';
	$lang['strindexneedsname'] = '有効なインデックス名を指定しなければいけません。';
	$lang['strindexneedscols'] = '有効なカラム数を指定しなければいけません。';
	$lang['strindexcreated'] = 'インデックスを作成しました。';
	$lang['strindexcreatedbad'] = 'インデックスの作成に失敗しました。';
	$lang['strconfdropindex'] = '本当にインデックス「%s」を破棄しますか?';
	$lang['strindexdropped'] = 'インデックスを破棄しました。';
	$lang['strindexdroppedbad'] = 'インデックスの破棄に失敗しました。';
	$lang['strkeyname'] = 'キー名';
	$lang['struniquekey'] = 'ユニークキー';
	$lang['strprimarykey'] = 'プライマリキー';
	$lang['strindextype'] = 'インデックスタイプ';
	$lang['strtablecolumnlist'] = 'テーブル中のカラム';
	$lang['strindexcolumnlist'] = 'インデックス中のカラム';
	$lang['strconfcluster'] = '本当に「%s」をクラスターにしますか?';
	$lang['strclusteredgood'] = 'クラスター完了です。';
	$lang['strclusteredbad'] = 'クラスターに失敗しました。';
	$lang['strcluster']  =  'クラスター';

	// Rules
	$lang['strrules'] = 'ルール';
	$lang['strrule'] = 'ルール';
	$lang['strshowallrules'] = 'すべてのルールを表示する';
	$lang['strnorule'] = 'ルールがありません。';
	$lang['strnorules'] = 'ルールが見つかりません。';
	$lang['strcreaterule'] = 'ルールを作成する';
	$lang['strrulename'] = 'ルール名';
	$lang['strruleneedsname'] = 'ルール名を指定しなければなりません。';
	$lang['strrulecreated'] = 'ルールを作成しました。';
	$lang['strrulecreatedbad'] = 'ルールの作成に失敗しました。';
	$lang['strconfdroprule'] = '本当にルール「%s」をデータベース「%s」から破棄しますか?';
	$lang['strruledropped'] = 'ルールを破棄しました。';
	$lang['strruledroppedbad'] = 'ルールの破棄に失敗しました。';

	// Constraints
	$lang['strconstraint'] = '検査制約';
	$lang['strconstraints'] = '検査制約';
	$lang['strshowallconstraints'] = 'すべての検査制約を表示する';
	$lang['strnoconstraints'] = '検査制約がありません。';
	$lang['strcreateconstraint'] = '検査制約を作成する';
	$lang['strconstraintcreated'] = '検査制約を作成しました。';
	$lang['strconstraintcreatedbad'] = '検査制約の作成に失敗しました。';
	$lang['strconfdropconstraint'] = '本当に検査制約「%s」をデータベース「%s」から破棄しますか?';
	$lang['strconstraintdropped'] = '検査制約を破棄しました。';
	$lang['strconstraintdroppedbad'] = '検査制約の破棄に失敗しました。';
	$lang['straddcheck'] = '検査を追加する';
	$lang['strcheckneedsdefinition'] = '検査制約には定義が必要です。';
	$lang['strcheckadded'] = '検査制約を追加しました。';
	$lang['strcheckaddedbad'] = '検査制約の追加に失敗しました。';
	$lang['straddpk'] = 'プライマリキーを追加する';
	$lang['strpkneedscols'] = 'プライマリキーは少なくとも一カラムを必要とします。';
	$lang['strpkadded'] = 'プライマリキーを追加しました。';
	$lang['strpkaddedbad'] = 'プライマリキーの追加に失敗しました。';
	$lang['stradduniq'] = 'ユニークキーを追加する';
	$lang['struniqneedscols'] = 'ユニークキーは少なくとも一カラムを必要とします。';
	$lang['struniqadded'] = 'ユニークキーを追加しました。';
	$lang['struniqaddedbad'] = 'ユニークキーの追加に失敗しました。';
	$lang['straddfk'] = '外部キーを追加する';
	$lang['strfkneedscols'] = '外部キーは少なくとも一カラムを必要とします。';
	$lang['strfkneedstarget'] = '外部キーはターゲットテーブルを必要とします。';
	$lang['strfkadded'] = '外部キーを追加しました。';
	$lang['strfkaddedbad'] = '外部キーの追加に失敗しました。';
	$lang['strfktarget'] = '対象テーブル';
	$lang['strfkcolumnlist'] = 'キー中のカラム';
	$lang['strondelete'] = 'ON DELETE';
	$lang['stronupdate'] = 'ON UPDATE';	

	// Functions
	$lang['strfunction'] = '関数';
	$lang['strfunctions'] = '関数';
	$lang['strshowallfunctions'] = 'すべて関数を表示する';
	$lang['strnofunction'] = '関数がありません。';
	$lang['strnofunctions'] = '関数が見つかりません。';
	$lang['strcreateplfunction'] = 'SQL/PL 関数を作成する';
	$lang['strcreateinternalfunction'] = '内部関数を作成する';
	$lang['strcreatecfunction'] = 'C 関数を作成する';
	$lang['strfunctionname'] = '関数名';
	$lang['strreturns'] = '返り値';
	$lang['strproglanguage'] = 'プログラミング言語';
	$lang['strfunctionneedsname'] = '関数名を指定しなければなりません。';
	$lang['strfunctionneedsdef'] = '関数の定義をしなければなりあせん。';
	$lang['strfunctioncreated'] = '関数を作成しました。';
	$lang['strfunctioncreatedbad'] = '関数の作成に失敗しました。';
	$lang['strconfdropfunction'] = '本当に関数「%s」を破棄しますか?';
	$lang['strfunctiondropped'] = '関数を破棄しました。';
	$lang['strfunctiondroppedbad'] = '関数の破棄に失敗しました。';
	$lang['strfunctionupdated'] = '関数を更新しました。';
	$lang['strfunctionupdatedbad'] = '関数の更新に失敗しました。';
	$lang['strobjectfile'] = 'オブジェクトファイル';
	$lang['strlinksymbol'] = 'リンクシンボル';
	$lang['strarguments']  =  '引数';
	$lang['strargmode']  =  'モード';
	$lang['strargtype']  =  '種類';
	$lang['strargadd']  =  '他の引数を追加する';
	$lang['strargremove']  =  'この引数を削除する';
	$lang['strargnoargs']  =  'この関数はいくつかの引数を取らなでしょう。';
$lang['strargenableargs']  =  'Enable arguments being passed to this function.';
	$lang['strargnorowabove']  =  'この行の上に行が必要です。';
	$lang['strargnorowbelow']  =  'この行の下に行が必要です。';
	$lang['strargraise']  =  '上に移動します。';
	$lang['strarglower']  =  '下に移動します。';
	$lang['strargremoveconfirm']  =  '本当にこの引数を削除しますか? これは戻すことができませんThis CANNOT be undone。';
$lang['strfunctioncosting']  =  'Function Costing';
$lang['strresultrows']  =  'Result Rows';
$lang['strexecutioncost']  =  'Execution Cost';
	$lang['strspecifyfunctiontodrop']  =  '関数を破棄するには少なくとも 1 つ指定しなければなりません';

	// Triggers
	$lang['strtrigger'] = 'トリガー';
	$lang['strtriggers'] = 'トリガー';
	$lang['strshowalltriggers'] = 'すべてのトリガーを表示する';
	$lang['strnotrigger'] = 'トリガーがありません。';
	$lang['strnotriggers'] = 'トリガーが見つかりません。';
	$lang['strcreatetrigger'] = 'トリガーを作成する';
	$lang['strtriggerneedsname'] = 'トリガー名を指定する必要があります。';
	$lang['strtriggerneedsfunc'] = 'トリガーのための関数を指定しなければなりません。';
	$lang['strtriggercreated'] = 'トリガーを作成しました。';
	$lang['strtriggercreatedbad'] = 'トリガーの作成に失敗しました。';
	$lang['strconfdroptrigger'] = '本当にトリガー「%s」をデータベース「%s」から破棄しますか?';
	$lang['strconfenabletrigger']  =  '本当に「%2$s」のトリガー「%1$s」を有効にしますか?';
	$lang['strconfdisabletrigger']  =  '本当に「%2$s」のトリガー「%1$s」を無効にしますか?';
	$lang['strtriggerdropped'] = 'トリガーを破棄しました。';
	$lang['strtriggerdroppedbad'] = 'トリガーの破棄に失敗しました。';
	$lang['strtriggerenabled']  =  'トリガーを有効にしました。';
	$lang['strtriggerenabledbad']  =  'トリガーの有効化に失敗しました。';
	$lang['strtriggerdisabled']  =  'トリガーを無効にしました。';
	$lang['strtriggerdisabledbad']  =  'トリガーの無効化に失敗しました。';
	$lang['strtriggeraltered'] = 'トリガーを変更しました。';
	$lang['strtriggeralteredbad'] = 'トリガーの変更に失敗しました。';
$lang['strforeach']  =  'For each';

	// Types
	$lang['strtype'] = 'データ型';
	$lang['strtypes'] = 'データ型';
	$lang['strshowalltypes'] = 'すべてのデータ型を表示する';
	$lang['strnotype'] = 'データ型がありません。';
	$lang['strnotypes'] = 'データ型が見つかりませんでした。';
	$lang['strcreatetype'] = 'データ型を作成する';
	$lang['strcreatecomptype'] = '複合型を作成する';
$lang['strcreateenumtype']  =  'Create enum type';
	$lang['strtypeneedsfield'] = '少なくとも 1 つのフィールドを指定しなければなりません。';
	$lang['strtypeneedsvalue']  =  '少なくとも 1 つの値を指定しなければなりません。';
	$lang['strtypeneedscols'] = '有効なフィールドの数を指定しなければなりません。';
$lang['strtypeneedsvals']  =  'You must specify a valid number of values.';
	$lang['strinputfn'] = '入力関数';
	$lang['stroutputfn'] = '出力関数';
$lang['strpassbyval'] = 'Passed by val?';
	$lang['stralignment'] = 'アライメント';
	$lang['strelement'] = '要素';
	$lang['strdelimiter'] = 'デミリタ';
	$lang['strstorage'] = 'ストレージ';
	$lang['strfield'] = 'フィールド';
$lang['strvalue']  =  'Value';
$lang['strvalue']  =  'Value';
	$lang['strnumfields'] = 'フィールド数';
$lang['strnumvalues']  =  'Num. of values';
	$lang['strtypeneedsname'] = '型名を指定しなければなりません。';
	$lang['strtypeneedslen'] = 'データ型の長さを指定しなければなりません。';
	$lang['strtypecreated'] = 'データ型を作成しました。';
	$lang['strtypecreatedbad'] = 'データ型の作成に失敗しました。';
	$lang['strconfdroptype'] = '本当にデータ型「%s」を破棄しますか?';
	$lang['strtypedropped'] = 'データ型を破棄しました。';
	$lang['strtypedroppedbad'] = 'データ型の破棄に失敗しました。';
	$lang['strflavor'] = '種類';
	$lang['strbasetype'] = '基本';
	$lang['strcompositetype'] = '複合型';
	$lang['strpseudotype'] = '擬似データ';
$lang['strenum']  =  'Enum';
$lang['strenumvalues']  =  'Enum Values';

	// Schemas
	$lang['strschema'] = 'スキーマ';
	$lang['strschemas'] = 'スキーマ';
	$lang['strshowallschemas'] = 'すべてのスキーマを表示する';
	$lang['strnoschema'] = 'スキーマがありません。';
	$lang['strnoschemas'] = 'スキーマが見つかりません。';
	$lang['strcreateschema'] = 'スキーマを作成する';
	$lang['strschemaname'] = 'スキーマ名';
	$lang['strschemaneedsname'] = 'スキーマ名を指定する必要があります。';
	$lang['strschemacreated'] = 'スキーマを作成しました。';
	$lang['strschemacreatedbad'] = 'スキーマの作成に失敗しました。';
	$lang['strconfdropschema'] = '本当にスキーマ「%s」を破棄しますか?';
	$lang['strschemadropped'] = 'スキーマを破棄しました。';
	$lang['strschemadroppedbad'] = 'スキーマの破棄に失敗しました。';
	$lang['strschemaaltered'] = 'スキーマを変更しました。';
	$lang['strschemaalteredbad'] = 'スキーマの変更に失敗しました。';
	$lang['strsearchpath'] = 'スキーマ検索パス';
	$lang['strspecifyschematodrop']  =  'スキーマを破棄するには少なくとも 1 つ指定しなければなりません';

	// Reports

	// Domains
	$lang['strdomain'] = 'ドメイン';
	$lang['strdomains'] = 'ドメイン';
	$lang['strshowalldomains'] = 'すべてのドメインを表示する';
	$lang['strnodomains'] = 'ドメインがありません。';
	$lang['strcreatedomain'] = 'ドメイン作成';
	$lang['strdomaindropped'] = 'ドメインを破棄しました。';
	$lang['strdomaindroppedbad'] = 'ドメインの破棄に失敗しました。';
	$lang['strconfdropdomain'] = '本当にドメイン「%s」を破棄しますか?';
	$lang['strdomainneedsname'] = 'ドメイン名を指定する必要があります。';
	$lang['strdomaincreated'] = 'ドメインを作成しました。';
	$lang['strdomaincreatedbad'] = 'ドメインの作成に失敗しました。';	
	$lang['strdomainaltered'] = 'ドメインを変更しました。';
	$lang['strdomainalteredbad'] = 'ドメインの変更に失敗しました。';	

	// Operators
	$lang['stroperator'] = '演算子';
	$lang['stroperators'] = '演算子';
	$lang['strshowalloperators'] = 'すべての演算子を表示する';
	$lang['strnooperator'] = '演算子が見つかりません。';
	$lang['strnooperators'] = '演算子クラスが見つかりません。';
	$lang['strcreateoperator'] = '演算子を作成しました。';
	$lang['strleftarg'] = '左引数タイプ';
	$lang['strrightarg'] = '右引数タイプ';
	$lang['strcommutator'] = '交代';
	$lang['strnegator'] = '否定';
	$lang['strrestrict'] = '制限';
	$lang['strjoin'] = '結合';
	$lang['strhashes'] = 'ハッシュ';
	$lang['strmerges'] = '併合';
	$lang['strleftsort'] = '左ソート';
	$lang['strrightsort'] = '右ソート';
	$lang['strlessthan'] = '未満';
	$lang['strgreaterthan'] = '以上';
	$lang['stroperatorneedsname'] = '演算子名を指定する必要があります。';
	$lang['stroperatorcreated'] = '演算子を作成しました。';
	$lang['stroperatorcreatedbad'] = '演算子の作成に失敗しました。';
	$lang['strconfdropoperator'] = '本当に演算子「%s」を破棄しますか?';
	$lang['stroperatordropped'] = '演算子を破棄しました。';
	$lang['stroperatordroppedbad'] = '演算子の破棄に失敗しました。';

	// Casts
	$lang['strcasts'] = 'キャスト';
	$lang['strnocasts'] = 'キャストが見つかりません。';
	$lang['strsourcetype'] = 'ソースタイプ';
	$lang['strtargettype'] = 'ターゲットタイプ';
	$lang['strimplicit'] = '暗黙';
$lang['strinassignment'] = 'In assignment';
	$lang['strbinarycompat'] = '(バイナリー互換)';
	
	// Conversions
	$lang['strconversions'] = '変換';
	$lang['strnoconversions'] = '変換が見つかりません。';
	$lang['strsourceencoding'] = '変換元エンコード';
	$lang['strtargetencoding'] = '変換先エンコード';
	
	// Languages
	$lang['strlanguages'] = '言語';
	$lang['strnolanguages'] = '言語が存在しません。';
$lang['strtrusted'] = 'Trusted';
	
	// Info
	$lang['strnoinfo'] = '有効な情報がありません。';
	$lang['strreferringtables'] = '参照テーブル';
	$lang['strparenttables'] = '親テーブル';
	$lang['strchildtables'] = '子テーブル';

	// Aggregates
	$lang['straggregate']  =  '集計';
	$lang['straggregates'] = '集計';
	$lang['strnoaggregates'] = '集計がありません。';
	$lang['stralltypes'] = '(すべての種類)';
	$lang['strcreateaggregate']  =  '集計を作成する';
	$lang['straggrbasetype']  =  '入力データの種類';
	$lang['straggrsfunc']  =  '状態遷移関数';
	$lang['straggrstype']  =  '状態データの種類';
	$lang['straggrffunc']  =  '終了関数';
	$lang['straggrinitcond']  =  '初期状態';
	$lang['straggrsortop']  =  'ソート操作';
	$lang['strconfdropaggregate']  =  '本当に集計「%s」を破棄しますか?';
	$lang['straggregatedropped']  =  '集計を破棄しました。';
	$lang['straggregatedroppedbad']  =  '集計の破棄に失敗しました。';
	$lang['straggraltered']  =  '集計を変更しました。';
	$lang['straggralteredbad']  =  '集計の変更に失敗しました。';
	$lang['straggrneedsname']  =  '集計は名前を指定しなければなりません';
	$lang['straggrneedsbasetype']  =  '集計は入力データの種類を指定しなければなりません';
	$lang['straggrneedssfunc']  =  '集計は状態遷移関数の名前を指定しなければなりません';
	$lang['straggrneedsstype']  =  '集計の状態値のデータの種類を指定しなければなりません';
	$lang['straggrcreated']  =  '集計を作成しました。';
	$lang['straggrcreatedbad']  =  '集計の作成に失敗しました。';
	$lang['straggrshowall']  =  'すべての集計を表示する';

	// Operator Classes
	$lang['stropclasses'] = '演算子クラス';
	$lang['strnoopclasses'] = '演算子クラスが見つかりません。';
	$lang['straccessmethod'] = 'アクセス方法';

	// Stats and performance
	$lang['strrowperf'] = '行パフォーマンス';
	$lang['strioperf'] = 'I/O パフォーマンス';
	$lang['stridxrowperf'] = 'インデックス行パフォーマンス';
	$lang['stridxioperf'] = 'インデックス I/O パフォーマンス';
	$lang['strpercent'] = '%';
	$lang['strsequential'] = 'シーケンシャル';
	$lang['strscan'] = '検索';
	$lang['strread'] = '読込';
	$lang['strfetch'] = '取得';
	$lang['strheap'] = 'ヒープ';
	$lang['strtoast'] = 'TOAST';
	$lang['strtoastindex'] = 'TOAST インデックス';
	$lang['strcache'] = 'キャッシュ';
	$lang['strdisk'] = 'ディスク';
	$lang['strrows2'] = '行';

	// Tablespaces
	$lang['strtablespace'] = 'テーブル空間';
	$lang['strtablespaces']  =  'テーブル空間';
	$lang['strshowalltablespaces'] = 'すべてのテーブルスペースを表示する';
	$lang['strnotablespaces'] = 'テーブル空間が見つかりません。';
	$lang['strcreatetablespace'] = 'テーブル空間を作成する';
	$lang['strlocation'] = 'ロケーション';
	$lang['strtablespaceneedsname'] = 'テーブル空間名を指定する必要があります。';
	$lang['strtablespaceneedsloc'] = 'テーブル空間作成をするディレクトリを指定する必要があります。';
	$lang['strtablespacecreated'] = 'テーブル空間を作成しました。';
	$lang['strtablespacecreatedbad'] = 'テーブル空間の作成に失敗しました。';
	$lang['strconfdroptablespace'] = '本当にテーブル空間「%s」を破棄しますか?';
	$lang['strtablespacedropped'] = 'テーブル空間を破棄しました。';
	$lang['strtablespacedroppedbad'] = 'テーブル空間の破棄に失敗しました。';
	$lang['strtablespacealtered'] = 'テーブル空間を変更しました。';
	$lang['strtablespacealteredbad'] = 'テーブル空間の変更に失敗しました。';

	// Miscellaneous
	$lang['strtopbar'] = 'サーバー %2$s のポート番号 %3$s で実行中の %1$s に接続中 -- ユーザー「%4$s」としてログイン中';
	$lang['strtimefmt'] = 'Y 年 n 月 j 日 G:i';
	$lang['strhelp'] = 'ヘルプ';
	$lang['strhelpicon'] = '?';
	$lang['strhelppagebrowser']  =  'ヘルプページブラウザー';
	$lang['strselecthelppage']  =  'ヘルプページを選んでください';
	$lang['strinvalidhelppage']  =  '無効なヘルプページです。';
	$lang['strlogintitle'] = '%s にログイン';
	$lang['strlogoutmsg'] = '%s をログアウトしました。';
	$lang['strloading'] = '読み込み中...';
	$lang['strerrorloading'] = '読み込み中のエラーです。';
	$lang['strclicktoreload'] = 'クリックで再読み込み';

	// Autovacuum
	$lang['strautovacuum']  =  'オートバキューム'; 
	$lang['strturnedon']  =  'オンにする'; 
	$lang['strturnedoff']  =  'オフにする'; 
$lang['strenabled']  =  'Enabled'; 
	$lang['strvacuumbasethreshold']  =  '閾値に基づいたバキューム'; 
$lang['strvacuumscalefactor']  =  'Vacuum Scale Factor';  
	$lang['stranalybasethreshold']  =  '閾値に基づいた解析';  
$lang['stranalyzescalefactor']  =  'Analyze Scale Factor'; 
$lang['strvacuumcostdelay']  =  'Vacuum Cost Delay'; 
$lang['strvacuumcostlimit']  =  'Vacuum Cost Limit';  

	// Table-level Locks
	$lang['strlocks']  =  'ロック';
	$lang['strtransaction']  =  'トランザクション ID';
	$lang['strvirtualtransaction']  =  '仮想トランザクション ID';
	$lang['strprocessid']  =  'プロセス ID';
	$lang['strmode']  =  'ロックモード';
$lang['strislockheld']  =  'Is lock held?';

	// Prepared transactions
	$lang['strpreparedxacts']  =  'プリペアを用いたトランザクション';
	$lang['strxactid']  =  'トランザクション ID';
	$lang['strgid']  =  '全体 ID';
	
	// Fulltext search
	$lang['strfulltext']  =  '全文テキスト検索';
	$lang['strftsconfig']  =  'FTS 設定';
    $lang['strftsconfigs']  =  '設定';
	$lang['strftscreateconfig']  =  'FTS 設定の作成';
	$lang['strftscreatedict']  =  '辞書を作成する';
	$lang['strftscreatedicttemplate']  =  '辞書のテンプレートを作成する';
	$lang['strftscreateparser']  =  'パーサーを作成する';
	$lang['strftsnoconfigs']  =  'FTS 設定が見つかりません。';
	$lang['strftsconfigdropped']  =  'FTS 設定を破棄しました。';
	$lang['strftsconfigdroppedbad']  =  'FTS 設定の破棄に失敗しました。';
	$lang['strconfdropftsconfig']  =  '本当に FTS 設定「%s」を破棄しますか?';
	$lang['strconfdropftsdict']  =  '本当に FTS 辞書「%s」を破棄しますか?';
	$lang['strconfdropftsmapping']  =  '本当に FTS 設定「%s」のマップ「%s」を破棄しますか?';
	$lang['strftstemplate']  =  'テンプレート';
	$lang['strftsparser']  =  'パーサー';
	$lang['strftsconfigneedsname']  =  'FTS 設定には名前を指定する必要があります。';
	$lang['strftsconfigcreated']  =  'FTS 設定を作成しました。';
	$lang['strftsconfigcreatedbad']  =  'FTS 設定の作成に失敗しました。';
$lang['strftsmapping']  =  'Mapping';
	$lang['strftsdicts']  =  '辞書';
	$lang['strftsdict']  =  '辞書';
	$lang['strftsemptymap']  =  'FTS 設定マップが空です。';
$lang['strftswithmap']  =  'With map';
$lang['strftsmakedefault']  =  'Make default for given locale';
	$lang['strftsconfigaltered']  =  'FTS 設定を変更しました。';
	$lang['strftsconfigalteredbad']  =  'FTS 設定の変更に失敗しました。';
	$lang['strftsconfigmap']  =  'FTS 設定マップ';
	$lang['strftsparsers']  =  'FTS パーサー';
	$lang['strftsnoparsers']  =  '利用できる FTS パーサーがありません。';
	$lang['strftsnodicts']  =  '利用できる FTS 辞書がありません。';
	$lang['strftsdictcreated']  =  'FTS 辞書を作成しました。';
	$lang['strftsdictcreatedbad']  =  'FTS 辞書の作成に失敗しました';
$lang['strftslexize']  =  'Lexize';
$lang['strftsinit']  =  'Init';
	$lang['strftsoptionsvalues']  =  'オプションと値';
	$lang['strftsdictneedsname']  =  'FTS 辞書の名前を指定しなければなりません。';
	$lang['strftsdictdropped']  =  'FTS 辞書を破棄しました。';
	$lang['strftsdictdroppedbad']  =  'FTS 辞書の破棄に失敗しました。';
	$lang['strftsdictaltered']  =  'FTS 辞書を変更しました。';
	$lang['strftsdictalteredbad']  =  'FTS 辞書の変更に失敗しました。';
	$lang['strftsaddmapping']  =  '新規マップを追加する';
	$lang['strftsspecifymappingtodrop']  =  'マップを破棄をするには少なくとも 1 つ指定しなければなりません';
	$lang['strftsspecifyconfigtoalter']  =  'FTS 設定を変更するには指定しなければなりません';
	$lang['strftsmappingdropped']  =  'FTS マップを破棄しました。';
	$lang['strftsmappingdroppedbad']  =  'FTS マップの破棄に失敗しました。';
	$lang['strftsnodictionaries']  =  '辞書が見つかりません。';
	$lang['strftsmappingaltered']  =  'FTS マップを変更しました。';
	$lang['strftsmappingalteredbad']  =  'FTS マップの変更に失敗しました。';
	$lang['strftsmappingadded']  =  'FTS マップを追加しました。';
	$lang['strftsmappingaddedbad']  =  'FTS マップの追加に失敗しました。';
	$lang['strftsmappingdropped']  =  'FTS マップを破棄しました。';
	$lang['strftsmappingdroppedbad']  =  'FTS マップの破棄に失敗しました。';
	$lang['strftstabconfigs']  =  '設定';
	$lang['strftstabdicts']  =  '辞書';
	$lang['strftstabparsers']  =  'パーサー';
?>
