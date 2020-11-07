<?php

	/**
	 * German language file for phpPgAdmin.  Use this as a basis
	 *
	 * @maintainer Laurenz Albe <laurenz.albe@wien.gv.at>
	 *
     * $Id: german.php,v 1.30 2008/02/18 23:06:51 ioguix Exp $
	 */

	// Language and character set
	$lang['applang'] = 'Deutsch';
	$lang['applocale'] = 'de-DE';
	$lang['applangdir'] = 'ltr';

	// Welcome
	$lang['strintro'] = 'Willkommen bei phpPgAdmin.';
	$lang['strppahome'] = 'phpPgAdmin Homepage';
	$lang['strpgsqlhome'] = 'PostgreSQL Homepage';
	$lang['strpgsqlhome_url'] = 'http://www.postgresql.org/';
	$lang['strlocaldocs'] = 'PostgreSQL Dokumentation (lokal)';
	$lang['strreportbug'] = 'Fehler melden';
	$lang['strviewfaq'] = 'Online-FAQ ansehen';
	$lang['strviewfaq_url'] = 'http://phppgadmin.sourceforge.net/doku.php?id=faq';
	
	// Basic strings
	$lang['strlogin'] = 'Anmelden';
	$lang['strloginfailed'] = 'Anmeldung fehlgeschlagen';
	$lang['strlogindisallowed'] = 'Anmeldung aus Sicherheitsgründen verweigert.';
	$lang['strserver'] = 'Server';
	$lang['strservers'] = 'Server';
	$lang['strintroduction'] = 'Einführung';
	$lang['strhost'] = 'Host';
	$lang['strport'] = 'Port';
	$lang['strlogout'] = 'Abmelden';
	$lang['strowner'] = 'Besitzer';
	$lang['straction'] = 'Aktion';
	$lang['stractions'] = 'Aktionen';
	$lang['strname'] = 'Name';
	$lang['strdefinition'] = 'Definition';
	$lang['strproperties'] = 'Eigenschaften';
	$lang['strbrowse'] = 'Durchsuchen';
	$lang['strenable'] = 'Einschalten';
	$lang['strdisable'] = 'Ausschalten';
	$lang['strdrop'] = 'Löschen';
	$lang['strdropped'] = 'Gelöscht';
	$lang['strnull'] = 'Null';
	$lang['strnotnull'] = 'Nicht Null';
	$lang['strprev'] = '< Zurück';
	$lang['strnext'] = 'Weiter >';
	$lang['strfirst'] = '<< Anfang';
	$lang['strlast'] = 'Ende >>';
	$lang['strfailed'] = 'Fehlgeschlagen';
	$lang['strcreate'] = 'Erstellen';
	$lang['strcreated'] = 'Erstellt';
	$lang['strcomment'] = 'Kommentar';
	$lang['strlength'] = 'Länge';
	$lang['strdefault'] = 'Standardwert';
	$lang['stralter'] = 'Ändern';
	$lang['strok'] = 'OK';
	$lang['strcancel'] = 'Abbrechen';
	$lang['strac'] = 'Automatische Vervollständigung einschalten';
	$lang['strsave'] = 'Speichern';
	$lang['strreset'] = 'Zurücksetzen';
	$lang['strinsert'] = 'Einfügen';
	$lang['strselect'] = 'Abfrage';
	$lang['strdelete'] = 'Löschen';
	$lang['strupdate'] = 'Ändern';
	$lang['strreferences'] = 'Verweise';
	$lang['stryes'] = 'Ja';
	$lang['strno'] = 'Nein';
	$lang['strtrue'] = 'WAHR';
	$lang['strfalse'] = 'FALSCH';
	$lang['stredit'] = 'Bearbeiten';
	$lang['strcolumn'] = 'Spalte';
	$lang['strcolumns'] = 'Spalten';
	$lang['strrows'] = 'Datensätze';
	$lang['strrowsaff'] = 'Datensätze betroffen.';
	$lang['strobjects'] = 'Objekt(e)';
	$lang['strback'] = 'Zurück';
	$lang['strqueryresults'] = 'Abfrageergebnis';
	$lang['strshow'] = 'Anzeigen';
	$lang['strempty'] = 'Leeren';
	$lang['strlanguage'] = 'Sprache';
	$lang['strencoding'] = 'Zeichenkodierung';
	$lang['strvalue'] = 'Wert';
	$lang['strunique'] = 'Eindeutig';
	$lang['strprimary'] = 'Primär';
	$lang['strexport'] = 'Exportieren';
	$lang['strimport'] = 'Importieren';
	$lang['strallowednulls'] = 'NULL-Zeichen erlaubt';
	$lang['strbackslashn'] = '\N';
	$lang['stremptystring'] = 'Leere Zeichenkette / Leere Spalte';
	$lang['strsql'] = 'SQL';
	$lang['stradmin'] = 'Admin';
	$lang['strvacuum'] = 'Bereinigen';
	$lang['stranalyze'] = 'Analysieren';
	$lang['strclusterindex'] = 'Cluster';
	$lang['strclustered'] = 'Geclustert?';
	$lang['strreindex'] = 'Reindexieren';
	$lang['strexecute'] = 'Ausführen';
	$lang['stradd'] = 'Hinzufügen';
	$lang['strevent'] = 'Ereignis';
	$lang['strwhere'] = 'Bedingung';
	$lang['strinstead'] = 'Tu stattdessen';
	$lang['strwhen'] = 'Wann';
	$lang['strformat'] = 'Format';
	$lang['strdata'] = 'Daten';
	$lang['strconfirm'] = 'Bestätigen';
	$lang['strexpression'] = 'Ausdruck';
	$lang['strellipsis'] = '...';
	$lang['strseparator'] = ': ';
	$lang['strexpand'] = 'Aufklappen';
	$lang['strcollapse'] = 'Zuklappen';
	$lang['strfind'] = 'Suchen';
	$lang['stroptions'] = 'Optionen';
	$lang['strrefresh'] = 'Aktualisieren';
	$lang['strdownload'] = 'Herunterladen';
	$lang['strdownloadgzipped'] = 'gzip-komprimiert herunterladen';
	$lang['strinfo'] = 'Info';
	$lang['stroids'] = 'OIDs';
	$lang['stradvanced'] = 'Erweitert';
	$lang['strvariables'] = 'Variable';
	$lang['strprocess'] = 'Prozess';
	$lang['strprocesses'] = 'Prozesse';
	$lang['strsetting'] = 'Einstellung';
	$lang['streditsql'] = 'SQL bearbeiten';
	$lang['strruntime'] = 'Laufzeit gesamt: %s ms';
	$lang['strpaginate'] = 'Ergebnisse seitenweise anzeigen';
	$lang['struploadscript'] = 'oder laden Sie ein SQL-Script hoch:';
	$lang['strstarttime'] = 'Beginnzeitpunkt';
	$lang['strfile'] = 'Datei';
	$lang['strfileimported'] = 'Datei importiert.';
	$lang['strtrycred'] = 'Diese Anmeldedaten für alle Server verwenden';
	$lang['strconfdropcred']  = 'Aus Sicherheitsgründen werden gemeinsamme Anmeldedaten beim Abmelden gelöscht. Sind Sie sicher, dass sie sich abmelden wollen?';
	$lang['stractionsonmultiplelines'] = 'Mehrzeilige Aktionen';
	$lang['strselectall'] = 'Alle auswählen';
	$lang['strunselectall'] = 'Alle abwählen';
	$lang['strlocale'] = 'Spracheinstellung';
	$lang['strcluster'] = 'Cluster';

	// User-supplied SQL history
	$lang['strhistory'] = 'Befehlsspeicher';
	$lang['strnohistory'] = 'Kein Befehlsspeicher.';
	$lang['strclearhistory'] = 'Befehlsspeicher löschen';
	$lang['strdelhistory'] = 'Aus dem Befehlsspeicher löschen';
	$lang['strconfdelhistory'] = 'Diese Abfrage wirklich aus dem Befehlsspeicher löschen?';
	$lang['strconfclearhistory'] = 'Befehlsspeicher wirklich löschen?';
	$lang['strnodatabaseselected'] = 'Bitte wählen Sie eine Datenbank aus.';

	// Database sizes
	$lang['strsize'] = 'Größe';
	$lang['strbytes'] = 'Bytes';
	$lang['strkb'] = 'kB';
	$lang['strmb'] = 'MB';
	$lang['strgb'] = 'GB';
	$lang['strtb'] = 'TB';

	// Error handling
	$lang['strnoframes'] = 'Diese Anwendung funktioniert am besten mit einem Browser, der Frames beherrscht, kann aber mit dem untenstehenden Link auch ohne Frames verwendet werden.';
	$lang['strnoframeslink'] = 'Ohne Frames arbeiten';
	$lang['strbadconfig'] = 'Ihre config.inc.php ist nicht aktuell. Sie müssen sie aus der config.inc.php-dist neu erzeugen.';
	$lang['strnotloaded'] = 'Ihre PHP-Installation unterstützt PostgreSQL nicht. Sie müssen PHP unter Verwendung der Konfigurationsoption --with-pgsql neu kompilieren.';
	$lang['strpostgresqlversionnotsupported'] = 'Ihre PostgreSQL-Version wird nicht unterstützt. Bitte stellen Sie Ihre Datenbank auf Version %s oder eine neuere Version um.';
	$lang['strbadschema'] = 'Ungültiges Schema angegeben.';
	$lang['strbadencoding'] = 'Kann die Client-Zeichenkodierung nicht in der Datenbank setzen.';
	$lang['strsqlerror'] = 'SQL-Fehler:';
	$lang['strinstatement'] = 'In der Anweisung:';
	$lang['strinvalidparam'] = 'Unzulässige Script-Parameter.';
	$lang['strnodata'] = 'Keine Datensätze gefunden.';
	$lang['strnoobjects'] = 'Keine Objekte gefunden.';
	$lang['strrownotunique'] = 'Dieser Datensatz hat keine eindeutige Spalte.';
	$lang['strnouploads'] = 'Das Hochladen von Dateien ist ausgeschaltet.';
	$lang['strimporterror'] = 'Importfehler.';
	$lang['strimporterror-fileformat'] = 'Importfehler: Dateiformat konnte nicht automatisch bestimmt werden.';
	$lang['strimporterrorline'] = 'Importfehler in Zeile %s.';
	$lang['strimporterrorline-badcolumnnum'] = 'Importfehler in Zeile %s: die Zeile hat nicht die richtige Anzahl von Spalten.';
	$lang['strimporterror-uploadedfile'] = 'Importfehler: die Datei konnte nicht auf den Server geladen werden';
	$lang['strcannotdumponwindows'] = 'Das Ablegen von komplizierten Tabellen- und Schemanamen wird auf Windows nicht unterstützt.';
	$lang['strinvalidserverparam'] = 'Es wurde versucht, mit einem ungültigen Server-Parameter eine Verbindung herzustellen. Möglicherweise versucht jemand, in Ihr System einzubrechen.'; 
	$lang['strnoserversupplied'] = 'Kein Server angegeben!';

	// Tables
	$lang['strtable'] = 'Tabelle';
	$lang['strtables'] = 'Tabellen';
	$lang['strshowalltables'] = 'Alle Tabellen anzeigen';
	$lang['strnotables'] = 'Keine Tabellen gefunden.';
	$lang['strnotable'] = 'Keine Tabelle gefunden.';
	$lang['strcreatetable'] = 'Neue Tabelle erstellen';
	$lang['strcreatetablelike'] = 'Neue Tabelle als Kopie einer bestehenden anlegen';
	$lang['strcreatetablelikeparent'] = 'Ursprüngliche Tabelle';
	$lang['strcreatelikewithdefaults'] = 'DEFAULT-Werte mitkopieren';
	$lang['strcreatelikewithconstraints'] = 'Constraints mitkopieren';
	$lang['strcreatelikewithindexes'] = 'Indizes mitkopieren';
	$lang['strtablename'] = 'Tabellenname';
	$lang['strtableneedsname'] = 'Sie müssen für die Tabelle einen Namen angeben.';
	$lang['strtablelikeneedslike'] = 'Sie müssen eine Tabelle angeben, deren Spaltendefinitionen kopiert werden sollen.';
	$lang['strtableneedsfield'] = 'Sie müssen mindestens eine Spalte angeben.';
	$lang['strtableneedscols'] = 'Sie müssen eine zulässige Anzahl von Spalten angeben.';
	$lang['strtablecreated'] = 'Tabelle erstellt.';
	$lang['strtablecreatedbad'] = 'Erstellen der Tabelle fehlgeschlagen.';
	$lang['strconfdroptable'] = 'Sind Sie sicher, dass Sie die Tabelle "%s" löschen möchten?';
	$lang['strtabledropped'] = 'Tabelle gelöscht.';
	$lang['strtabledroppedbad'] = 'Löschen der Tabelle fehlgeschlagen.';
	$lang['strconfemptytable'] = 'Sind Sie sicher, dass Sie den Inhalt der Tabelle "%s" löschen möchten?';
	$lang['strtableemptied'] = 'Tabelleninhalt gelöscht.';
	$lang['strtableemptiedbad'] = 'Löschen des Tabelleninhaltes fehlgeschlagen.';
	$lang['strinsertrow'] = 'Datensatz einfügen';
	$lang['strrowinserted'] = 'Datensatz eingefügt.';
	$lang['strrowinsertedbad'] = 'Einfügen des Datensatzes fehlgeschlagen.';
	$lang['strrowduplicate'] = 'Einfügen des Datensatzes fehlgeschlagen: es wurde versucht, ein Duplikat einzufügen.';
	$lang['streditrow'] = 'Datensatz bearbeiten';
	$lang['strrowupdated'] = 'Datensatz geändert.';
	$lang['strrowupdatedbad'] = 'Ändern des Datensatzes fehlgeschlagen.';
	$lang['strdeleterow'] = 'Datensatz löschen';
	$lang['strconfdeleterow'] = 'Sind Sie sicher, dass Sie diesen Datensatz löschen möchten?';
	$lang['strrowdeleted'] = 'Datensatz gelöscht.';
	$lang['strrowdeletedbad'] = 'Löschen des Datensatzes fehlgeschlagen.';
	$lang['strinsertandrepeat'] = 'Einfügen und Wiederholen';
	$lang['strnumcols'] = 'Anzahl der Spalten';
	$lang['strcolneedsname'] = 'Sie müssen einen Namen für die Spalte angeben';
	$lang['strselectallfields'] = 'Alle Felder auswählen';
	$lang['strselectneedscol'] = 'Sie müssen mindestens eine Spalte anzeigen lassen.';
	$lang['strselectunary'] = 'Unäre Operatoren können keine Werte haben.';
	$lang['strcolumnaltered'] = 'Spalte geändert.';
	$lang['strcolumnalteredbad'] = 'Ändern der Spalte fehlgeschlagen.';
	$lang['strconfdropcolumn'] = 'Sind Sie sicher, dass Sie die Spalte "%s" aus der Tabelle "%s" löschen möchten?';
	$lang['strcolumndropped'] = 'Spalte gelöscht.';
	$lang['strcolumndroppedbad'] = 'Löschen der Spalte fehlgschlagen.';
	$lang['straddcolumn'] = 'Spalte hinzufügen';
	$lang['strcolumnadded'] = 'Spalte hinzugefügt.';
	$lang['strcolumnaddedbad'] = 'Hinzufügen der Spalte fehlgeschlagen.';
	$lang['strcascade'] = 'CASCADE';
	$lang['strtablealtered'] = 'Tabelle geändert.';
	$lang['strtablealteredbad'] = 'Ändern der Tabelle fehlgeschlagen.';
	$lang['strdataonly'] = 'Nur die Daten';
	$lang['strstructureonly'] = 'Nur die Struktur';
	$lang['strstructureanddata'] = 'Struktur und Daten';
	$lang['strtabbed'] = 'Mit Tabluatoren';
	$lang['strauto'] = 'Automatisch';
	$lang['strconfvacuumtable'] = 'Sind sie sicher, dass Sie VACUUM auf "%s" ausführen wollen?';
	$lang['strconfanalyzetable'] = 'Sind sie sicher, dass Sie ANALYZE auf "%s" ausführen wollen?';
	$lang['strestimatedrowcount'] = 'Geschätzte Anzahl von Datensätzen';
	$lang['strspecifytabletoanalyze'] = 'Sie müssen mindestens eine Tabelle angeben, die analysiert werden soll.';
	$lang['strspecifytabletoempty'] = 'Sie müssen mindestens eine Tabelle angeben, deren Inhalt gelöscht werden soll.';
	$lang['strspecifytabletodrop'] = 'Sie müssen mindestens eine Tabelle angeben, die gelöscht werden soll.';
	$lang['strspecifytabletovacuum'] = 'Sie müssen mindestens eine Tabelle angeben, die bereinigt werden soll.';

	// Columns
	$lang['strcolprop'] = 'Spalteneigenschaften';
	$lang['strnotableprovided'] = 'Keine Tabelle angegeben!';
		
	// Users
	$lang['struser'] = 'Benutzer';
	$lang['strusers'] = 'Benutzer';
	$lang['strusername'] = 'Benutzername';
	$lang['strpassword'] = 'Passwort';
	$lang['strsuper'] = 'Superuser?';
	$lang['strcreatedb'] = 'Datenbank erstellen?';
	$lang['strexpires'] = 'Gültig bis';
	$lang['strsessiondefaults'] = 'Standardwerte für Datenbanksitzungen';
	$lang['strnousers'] = 'Keine Benutzer gefunden.';
	$lang['struserupdated'] = 'Benutzer geändert.';
	$lang['struserupdatedbad'] = 'Ändern des Benutzers fehlgeschlagen.';
	$lang['strshowallusers'] = 'Alle Benutzer anzeigen';
	$lang['strcreateuser'] = 'Benutzer anlegen';
	$lang['struserneedsname'] = 'Sie müssen einen Namen für den Benutzer angeben.';
	$lang['strusercreated'] = 'Benutzer angelegt.';
	$lang['strusercreatedbad'] = 'Anlegen des Benutzers fehlgeschlagen.';
	$lang['strconfdropuser'] = 'Sind Sie sicher, dass Sie den Benutzer "%s" löschen möchten?';
	$lang['struserdropped'] = 'Benutzer gelöscht.';
	$lang['struserdroppedbad'] = 'Löschen des Benutzers fehlgeschlagen.';
	$lang['straccount'] = 'Benutzerkonto';
	$lang['strchangepassword'] = 'Passwort ändern';
	$lang['strpasswordchanged'] = 'Passwort geändert.';
	$lang['strpasswordchangedbad'] = 'Ändern des Passwortes fehlgeschlagen.';
	$lang['strpasswordshort'] = 'Passwort ist zu kurz.';
	$lang['strpasswordconfirm'] = 'Passwort und Passwortbestätigung stimmen nicht überein.';
	
	// Groups
	$lang['strgroup'] = 'Gruppe';
	$lang['strgroups'] = 'Gruppen';
	$lang['strshowallgroups'] = 'Alle Gruppen anzeigen';
	$lang['strnogroup'] = 'Gruppe nicht gefunden.';
	$lang['strnogroups'] = 'Keine Gruppe gefunden.';
	$lang['strcreategroup'] = 'Gruppe anlegen';
	$lang['strgroupneedsname'] = 'Sie müssen für die Gruppe einen Namen angeben.';
	$lang['strgroupcreated'] = 'Gruppe angelegt.';
	$lang['strgroupcreatedbad'] = 'Anlegen der Gruppe fehlgeschlagen.';	
	$lang['strconfdropgroup'] = 'Sind Sie sicher, dass Sie die Gruppe "%s" löschen möchten?';
	$lang['strgroupdropped'] = 'Gruppe gelöscht.';
	$lang['strgroupdroppedbad'] = 'Löschen der Gruppe fehlgeschlagen.';
	$lang['strmembers'] = 'Mitglieder';
	$lang['strmemberof'] = 'Mitglied von';
	$lang['stradminmembers'] = 'Administrative Mitglieder';
	$lang['straddmember'] = 'Mitglied hinzufügen';
	$lang['strmemberadded'] = 'Mitglied hinzugefügt.';
	$lang['strmemberaddedbad'] = 'Hinzufügen des Mitglieds fehlgeschlagen.';
	$lang['strdropmember'] = 'Mitglied löschen';
	$lang['strconfdropmember'] = 'Sind Sie sicher, dass Sie das Mitglied "%s" aus der Gruppe "%s" löschen wollen?';
	$lang['strmemberdropped'] = 'Mitglied gelöscht.';
	$lang['strmemberdroppedbad'] = 'Löschen des Mitglieds fehlgeschlagen.';

	// Roles
	$lang['strrole'] = 'Rolle';
	$lang['strroles'] = 'Rollen';
	$lang['strshowallroles'] = 'Alle Rollen anzeigen';
	$lang['strnoroles'] = 'Keine Rollen gefunden.';
	$lang['strinheritsprivs'] = 'Rechte vererben?';
	$lang['strcreaterole'] = 'Rolle anlegen';
	$lang['strcancreaterole'] = 'Darf Rollen anlegen?';
	$lang['strrolecreated'] = 'Rolle angelegt.';
	$lang['strrolecreatedbad'] = 'Anlegen der Rolle fehlgeschlagen.';
	$lang['strrolealtered'] = 'Rolle geändert.';
	$lang['strrolealteredbad'] = 'Ändern der Rolle fehlgeschlagen.';
	$lang['strcanlogin'] = 'Darf sich anmelden?';
	$lang['strconnlimit'] = 'Maximalzahl an Datenbankverbindungen';
	$lang['strdroprole'] = 'Rolle löschen';
	$lang['strconfdroprole'] = 'Sind Sie sicher, dass Sie die Rolle "%s" löschen möchten?';
	$lang['strroledropped'] = 'Rolle gelöscht.';
	$lang['strroledroppedbad'] = 'Löschen der Rolle fehlgeschlagen.';
	$lang['strnolimit'] = 'Unbeschränkt';
	$lang['strnever'] = 'Nie';
	$lang['strroleneedsname'] = 'Sie müssen für die Rolle einen Namen angeben.';

	// Privileges
	$lang['strprivilege'] = 'Recht';
	$lang['strprivileges'] = 'Rechte';
	$lang['strnoprivileges'] = 'Für dieses Objekt gelten die Standard-Eigentümerrechte.';
	$lang['strgrant'] = 'Rechte erteilen';
	$lang['strrevoke'] = 'Rechte entziehen';
	$lang['strgranted'] = 'Rechte geändert.';
	$lang['strgrantfailed'] = 'Ändern der Rechte fehlgeschlagen.';
	$lang['strgrantbad'] = 'Sie müssen mindestens einen Benutzer oder eine Gruppe und mindestens ein Recht angeben.';
	$lang['strgrantor'] = 'Recht vergeben von';
	$lang['strasterisk'] = '*';

	// Databases
	$lang['strdatabase'] = 'Datenbank';
	$lang['strdatabases'] = 'Datenbanken';
	$lang['strshowalldatabases'] = 'Alle Datenbanken anzeigen';
	$lang['strnodatabases'] = 'Keine Datenbanken gefunden.';
	$lang['strcreatedatabase'] = 'Datenbank erstellen';
	$lang['strdatabasename'] = 'Datenbankname';
	$lang['strdatabaseneedsname'] = 'Sie müssen für die Datenbank einen Namen angeben.';
	$lang['strdatabasecreated'] = 'Datenbank erstellt.';
	$lang['strdatabasecreatedbad'] = 'Erstellen der Datenbank fehlgeschlagen.';
	$lang['strconfdropdatabase'] = 'Sind Sie sicher, dass Sie die Datenbank "%s" löschen möchten?';
	$lang['strdatabasedropped'] = 'Datenbank gelöscht.';
	$lang['strdatabasedroppedbad'] = 'Löschen der Datenbank fehlgeschlagen.';
	$lang['strentersql'] = 'Auszuführende SQL-Anweisungen hier eingeben:';
	$lang['strsqlexecuted'] = 'SQL-Anweisungen ausgeführt.';
	$lang['strvacuumgood'] = 'Tabellenbereinigung abgeschlossen.';
	$lang['strvacuumbad'] = 'Tabellenbereinigung fehlgeschlagen.';
	$lang['stranalyzegood'] = 'Analyse abgeschlossen.';
	$lang['stranalyzebad'] = 'Analyse fehlgeschlagen.';
	$lang['strreindexgood'] = 'Neuindexierung abgeschlossen.';
	$lang['strreindexbad'] = 'Neuindexierung fehlgeschlagen.';
	$lang['strfull'] = 'Mit Reorganisation';
	$lang['strfreeze'] = 'Aggressives "Einfrieren"';
	$lang['strforce'] = 'Erzwingen';
	$lang['strsignalsent'] = 'Signal gesendet.';
	$lang['strsignalsentbad'] = 'Senden des Signales fehlgeschlagen.';
	$lang['strallobjects'] = 'Alle Objekte';
	$lang['strdatabasealtered'] = 'Datenbank geändert.';
	$lang['strdatabasealteredbad'] = 'Ändern der Datenbank fehlgeschlagen.';
	$lang['strspecifydatabasetodrop'] = 'Sie müssen mindestens eine Datenbank angeben, die gelöscht werden soll.';

	// Views
	$lang['strview'] = 'Sicht';
	$lang['strviews'] = 'Sichten';
	$lang['strshowallviews'] = 'Alle Sichten anzeigen';
	$lang['strnoview'] = 'Keine Sicht gefunden.';
	$lang['strnoviews'] = 'Keine Sichten gefunden.';
	$lang['strcreateview'] = 'Sicht erstellen';
	$lang['strviewname'] = 'Name der Sicht';
	$lang['strviewneedsname'] = 'Sie müssen für die Sicht einen Namen angeben.';
	$lang['strviewneedsdef'] = 'Sie müssen für die Sicht eine Definition angeben.';
	$lang['strviewneedsfields'] = 'Sie müssen die Spalten angeben, die sie in der Sicht haben wollen.';
	$lang['strviewcreated'] = 'Sicht erstellt.';
	$lang['strviewcreatedbad'] = 'Erstellen der Sicht fehlgeschlagen.';
	$lang['strconfdropview'] = 'Sind Sie sicher, dass Sie die Sicht "%s" löschen möchten?';
	$lang['strviewdropped'] = 'Sicht gelöscht.';
	$lang['strviewdroppedbad'] = 'Löschen der Sicht fehlgeschlagen.';
	$lang['strviewupdated'] = 'Sicht geändert.';
	$lang['strviewupdatedbad'] = 'Ändern der Sicht fehlgeschlagen.';
	$lang['strviewlink'] = 'Verbindende Schlüssel';
	$lang['strviewconditions'] = 'Zusätzliche Bedingungen';
	$lang['strcreateviewwiz'] = 'Sicht mit dem Assistenten erstellen';
	$lang['strrenamedupfields'] = 'Doppelte Spalten umbenennen';
	$lang['strdropdupfields'] = 'Doppelte Spalten entfernen';
	$lang['strerrordupfields'] = 'Fehler bei den doppelten Spalten';
	$lang['strviewaltered'] = 'Sicht geändert.';
	$lang['strviewalteredbad'] = 'Ändern der Sicht fehlgeschlagen.';
	$lang['strspecifyviewtodrop'] = 'Sie müssen mindestens eine Sicht angeben, die gelöscht werden soll.';

	// Sequences
	$lang['strsequence'] = 'Sequenz';
	$lang['strsequences'] = 'Sequenzen';
	$lang['strshowallsequences'] = 'Alle Sequenzen anzeigen';
	$lang['strnosequence'] = 'Keine Sequenz gefunden.';
	$lang['strnosequences'] = 'Keine Sequenzen gefunden.';
	$lang['strcreatesequence'] = 'Sequenz erstellen';
	$lang['strlastvalue'] = 'Letzter Wert';
	$lang['strincrementby'] = 'Erhöhen um';	
	$lang['strstartvalue'] = 'Startwert';
	$lang['strmaxvalue'] = 'Maximalwert';
	$lang['strminvalue'] = 'Minimalwert';
	$lang['strcachevalue'] = 'Anzahl Werte im Cache';
	$lang['strlogcount'] = 'WAL-Zähler (log_cnt)';
	$lang['strcancycle'] = 'Zyklisch?';
	$lang['striscalled'] = 'Wird erhöht werden, wenn der nächste Wert angefordert wird (is_called)?';
	$lang['strsequenceneedsname'] = 'Sie müssen für die Sequenz einen Namen angeben.';
	$lang['strsequencecreated'] = 'Sequenz erstellt.';
	$lang['strsequencecreatedbad'] = 'Erstellen der Sequenz fehlgeschlagen.';
	$lang['strconfdropsequence'] = 'Sind Sie sicher, dass die die Sequenz "%s" löschen möchten?';
	$lang['strsequencedropped'] = 'Sequenz gelöscht.';
	$lang['strsequencedroppedbad'] = 'Löschen der Sequenz fehlgeschlagen.';
	$lang['strsequencereset'] = 'Sequenz zurückgesetzt.';
	$lang['strsequenceresetbad'] = 'Rücksetzen der Sequenz fehlgeschlagen.';
 	$lang['strsequencealtered'] = 'Sequenz geändert.';
 	$lang['strsequencealteredbad'] = 'Ändern der Sequenz fehlgeschlagen.';
 	$lang['strsetval'] = 'Wert setzen';
 	$lang['strsequencesetval'] = 'Sequenzwert gesetzt.';
 	$lang['strsequencesetvalbad'] = 'Setzen des Sequenzwertes fehlgeschlagen.';
 	$lang['strnextval'] = 'Wert erhöhen';
 	$lang['strsequencenextval'] = 'Sequenzwert erhöht.';
 	$lang['strsequencenextvalbad'] = 'Erhöhen des Sequenzwertes fehlgeschlagen.';
	$lang['strspecifysequencetodrop'] = 'Sie müssen mindestens eine Sequenz angeben, die gelöscht werden soll.';
	
	// Indexes
	$lang['strindex'] = 'Index';
	$lang['strindexes'] = 'Indizes';
	$lang['strindexname'] = 'Indexname';
	$lang['strshowallindexes'] = 'Alle Indizes anzeigen';
	$lang['strnoindex'] = 'Kein Index gefunden.';
	$lang['strnoindexes'] = 'Keine Indizes gefunden.';
	$lang['strcreateindex'] = 'Index erstellen';
	$lang['strtabname'] = 'Tabellenname';
	$lang['strcolumnname'] = 'Spaltenname';
	$lang['strindexneedsname'] = 'Sie müssen für den Index einen Namen angeben.';
	$lang['strindexneedscols'] = 'Sie müssen eine zulässige Anzahl an Spalten angeben.';
	$lang['strindexcreated'] = 'Index erstellt.';
	$lang['strindexcreatedbad'] = 'Erstellen des Index fehlgeschlagen.';
	$lang['strconfdropindex'] = 'Sind Sie sicher, dass sie den Index "%s" löschen möchten?';
	$lang['strindexdropped'] = 'Index gelöscht.';
	$lang['strindexdroppedbad'] = 'Löschen des Index fehlgeschlagen.';
	$lang['strkeyname'] = 'Schlüsselname';
	$lang['struniquekey'] = 'Eindeutiger Schlüssel';
	$lang['strprimarykey'] = 'Primärerschlüssel';
 	$lang['strindextype'] = 'Typ des Index';
	$lang['strtablecolumnlist'] = 'Spalten in der Tabelle';
	$lang['strindexcolumnlist'] = 'Spalten im Index';
	$lang['strconfcluster'] = 'Sind Sie sicher, dass Sie "%s" clustern wollen?';
	$lang['strclusteredgood'] = 'Clustern abgeschlossen.';
	$lang['strclusteredbad'] = 'Clustern fehlgeschlagen.';

	// Rules
	$lang['strrules'] = 'Regeln';
	$lang['strrule'] = 'Regel';
	$lang['strshowallrules'] = 'Alle Regeln anzeigen';
	$lang['strnorule'] = 'Keine Regel gefunden.';
	$lang['strnorules'] = 'Keine Regeln gefunden.';
	$lang['strcreaterule'] = 'Regel erstellen';
	$lang['strrulename'] = 'Regelname';
	$lang['strruleneedsname'] = 'Sie müssen für die Regel einen Namen angeben.';
	$lang['strrulecreated'] = 'Regel erstellt.';
	$lang['strrulecreatedbad'] = 'Erstellen der Regel fehlgeschlagen.';
	$lang['strconfdroprule'] = 'Sind Sie sicher, dass Sie die Regel "%s" in der Tabelle "%s" löschen möchten?';
	$lang['strruledropped'] = 'Regel gelöscht.';
	$lang['strruledroppedbad'] = 'Löschen der Regel fehlgeschlagen.';

	// Constraints
	$lang['strconstraint'] = 'Constraint';
	$lang['strconstraints'] = 'Constraints';
	$lang['strshowallconstraints'] = 'Alle Constraints anzeigen';
	$lang['strnoconstraints'] = 'Keine Constraints gefunden.';
	$lang['strcreateconstraint'] = 'Constraint erstellen';
	$lang['strconstraintcreated'] = 'Constraint erstellt.';
	$lang['strconstraintcreatedbad'] = 'Erstellen des Constraints fehlgeschlagen.';
	$lang['strconfdropconstraint'] = 'Sind Sie sicher, dass Sie den Constraint "%s" in der Tabelle "%s" löschen möchten?';
	$lang['strconstraintdropped'] = 'Constraint gelöscht.';
	$lang['strconstraintdroppedbad'] = 'Löschen des Constraints fehlgeschlagen.';
	$lang['straddcheck'] = 'Check-Constraint hinzufügen';
	$lang['strcheckneedsdefinition'] = 'Ein Check-Constraint braucht eine Definition.';
	$lang['strcheckadded'] = 'Check-Constraint hinzugefügt.';
	$lang['strcheckaddedbad'] = 'Hinzufügen des Check-Constraints fehlgeschlagen.';
	$lang['straddpk'] = 'Primärschlüssel hinzufügen';
	$lang['strpkneedscols'] = 'Ein Primärschlüssel benötigt mindestens eine Spalte.';
	$lang['strpkadded'] = 'Primärschlüssel hinzugefügt.';
	$lang['strpkaddedbad'] = 'Hinzufügen des Primärschlüssels fehlgeschlagen.';
	$lang['stradduniq'] = 'Eindeutigen Schlüssel hinzufügen';
	$lang['struniqneedscols'] = 'Ein eindeutiger Schlüssel benötigt mindestens eine Spalte.';
	$lang['struniqadded'] = 'Eindeutiger Schlüssel hinzugefügt.';
	$lang['struniqaddedbad'] = 'Hinzufügen eines eindeutigen Schlüssels fehlgeschlagen.';
	$lang['straddfk'] = 'Fremdschlüssel hinzufügen';
	$lang['strfkneedscols'] = 'Ein Fremdschlüssel benötigt mindestens eine Spalte.';
	$lang['strfkneedstarget'] = 'Ein Fremdschlüssel benötigt eine Zieltabelle.';
	$lang['strfkadded'] = 'Fremdschlüssel hinzugefügt.';
	$lang['strfkaddedbad'] = 'Hinzufügen eines Fremdschlüssels fehlgeschlagen.';
	$lang['strfktarget'] = 'Zieltabelle';
	$lang['strfkcolumnlist'] = 'Spalten im Schlüssel';
	$lang['strondelete'] = 'ON DELETE';
	$lang['stronupdate'] = 'ON UPDATE';

	// Functions
	$lang['strfunction'] = 'Funktion';
	$lang['strfunctions'] = 'Funktionen';
	$lang['strshowallfunctions'] = 'Alle Funktionen anzeigen';
	$lang['strnofunction'] = 'Keine Funktion gefunden.';
	$lang['strnofunctions'] = 'Keine Funktionen gefunden.';
	$lang['strcreateplfunction'] = 'SQL/PL-Funktion erstellen';
	$lang['strcreateinternalfunction'] = 'Interne Funktion erstellen';
	$lang['strcreatecfunction'] = 'C-Funktion erstellen';
	$lang['strfunctionname'] = 'Funktionsname';
	$lang['strreturns'] = 'Rückgabetyp';
	$lang['strproglanguage'] = 'Programmiersprache';
	$lang['strfunctionneedsname'] = 'Sie müssen für die Funktion einen Namen angeben.';
	$lang['strfunctionneedsdef'] = 'Sie müssen für die Funktion eine Definition angeben.';
	$lang['strfunctioncreated'] = 'Funktion erstellt.';
	$lang['strfunctioncreatedbad'] = 'Erstellen der Funktion fehlgeschlagen.';
	$lang['strconfdropfunction'] = 'Sind Sie sicher, dass sie die Funktion "%s" löschen möchten?';
	$lang['strfunctiondropped'] = 'Funktion gelöscht.';
	$lang['strfunctiondroppedbad'] = 'Löschen der Funktion fehlgeschlagen.';
	$lang['strfunctionupdated'] = 'Funktion geändert.';
	$lang['strfunctionupdatedbad'] = 'Ändern der Funktion fehlgeschlagen.';
	$lang['strobjectfile'] = 'Objektdatei';
	$lang['strlinksymbol'] = 'Link-Symbol';
	$lang['strarguments'] = 'Funktionsargumente';
	$lang['strargmode'] = 'Richtung';
	$lang['strargtype'] = 'Datentyp';
	$lang['strargadd'] = 'Weiteres Argument hinzufügen';
	$lang['strargremove'] = 'Dieses Argument entfernen';
	$lang['strargnoargs'] = 'Diese Funktion kann nur ohne Argumente aufgerufen werden.';
	$lang['strargenableargs'] = 'Diese Funktion kann mit Argumenten aufgerufen werden.';
	$lang['strargnorowabove'] = 'Oberhalb dieser Spalte muss eine weitere Spalte sein.';
	$lang['strargnorowbelow'] = 'Unterhalb dieser Spalte muss eine weitere Spalte sein.';
	$lang['strargraise'] = 'Hinaufschieben.';
	$lang['strarglower'] = 'Hinunterschieben.';
	$lang['strargremoveconfirm'] = 'Sind Sie sicher, dass Sie dieses Argument entfernen wollen? Das kann nicht rückgängig gemacht werden.';
	$lang['strfunctioncosting'] = 'Ausführungskosten';
	$lang['strresultrows'] = 'Geschätzte Anzahl der Ergebniszeilen';
	$lang['strexecutioncost'] = 'Geschätzte Ausführungskosten';
	$lang['strspecifyfunctiontodrop'] = 'Sie müssen mindestens eine Funktion angeben, die gelöscht werden soll.';

	// Triggers
	$lang['strtrigger'] = 'Trigger';
	$lang['strtriggers'] = 'Trigger';
	$lang['strshowalltriggers'] = 'Alle Trigger anzeigen';
	$lang['strnotrigger'] = 'Kein Trigger gefunden.';
	$lang['strnotriggers'] = 'Keine Trigger gefunden.';
	$lang['strcreatetrigger'] = 'Trigger erstellen';
	$lang['strtriggerneedsname'] = 'Sie müssen für den Trigger einen Namen angeben.';
	$lang['strtriggerneedsfunc'] = 'Sie müssen für den Trigger eine Funktion angeben.';
	$lang['strtriggercreated'] = 'Trigger erstellt.';
	$lang['strtriggercreatedbad'] = 'Erstellen des Triggers fehlgeschlagen.';
	$lang['strconfdroptrigger'] = 'Sind Sie sicher, dass Sie den Trigger "%s" auf der Tabelle "%s" löschen möchten?';
	$lang['strconfenabletrigger'] = 'Sind Sie sicher, dass Sie den Trigger "%s" auf der Tabelle "%s" aktivieren möchten?';
	$lang['strconfdisabletrigger'] = 'Sind Sie sicher, dass Sie den Trigger "%s" auf der Tabelle "%s" deaktivieren möchten?';
	$lang['strtriggerdropped'] = 'Trigger gelöscht.';
	$lang['strtriggerdroppedbad'] = 'Löschen des Triggers fehlgeschlagen.';
	$lang['strtriggerenabled'] = 'Trigger aktiviert.';
	$lang['strtriggerenabledbad'] = 'Aktivieren des Triggers fehlgeschlagen.';
	$lang['strtriggerdisabled'] = 'Trigger deaktiviert.';
	$lang['strtriggerdisabledbad'] = 'Deaktivieren des Triggers fehlgeschlagen.';
	$lang['strtriggeraltered'] = 'Trigger geändert.';
	$lang['strtriggeralteredbad'] = 'Ändern des Triggers fehlgeschlagen.';
	$lang['strforeach'] = 'Für alle';

	// Types
	$lang['strtype'] = 'Datentyp';
	$lang['strtypes'] = 'Datentypen';
	$lang['strshowalltypes'] = 'Alle Datentypen anzeigen';
	$lang['strnotype'] = 'Kein Datentyp gefunden.';
	$lang['strnotypes'] = 'Keine Datentypen gefunden.';
	$lang['strcreatetype'] = 'Datentyp erstellen';
	$lang['strcreatecomptype'] = 'Zusammengesetzten Typ erstellen';
	$lang['strcreateenumtype'] = 'Aufzählungstyp erstellen';
	$lang['strtypeneedsfield'] = 'Sie müssen mindestens ein Feld angeben.';
	$lang['strtypeneedsvalue'] = 'Sie müssen mindestens einen Wert angeben.';
	$lang['strtypeneedscols'] = 'Sie müssen eine gültige Anzahl von Spalten angeben.';
	$lang['strtypeneedsvals'] = 'Sie müssen eine gültige Anzahl von Werten angeben.';
	$lang['strinputfn'] = 'Eingabefunktion';
	$lang['stroutputfn'] = 'Ausgabefunktion';
	$lang['strpassbyval'] = 'Übergabe "by value"?';
	$lang['stralignment'] = 'Alignment';
	$lang['strelement'] = 'Element';
	$lang['strdelimiter'] = 'Trennzeichen';
	$lang['strstorage'] = 'Speicherung';
	$lang['strfield'] = 'Spalte';
	$lang['strnumfields'] = 'Anzahl Spalten';
	$lang['strnumvalues'] = 'Anzahl Werte';
	$lang['strtypeneedsname'] = 'Sie müssen einen Namen für den Datentyp angeben.';
	$lang['strtypeneedslen'] = 'Sie müssen eine Länge für den Datentyp angeben.';
	$lang['strtypecreated'] = 'Datentyp erstellt.';
	$lang['strtypecreatedbad'] = 'Erstellen des Datentypen fehlgeschlagen.';
	$lang['strconfdroptype'] = 'Sind Sie sicher, dass Sie den Datentyp "%s" löschen möchten?';
	$lang['strtypedropped'] = 'Datentyp gelöscht.';
	$lang['strtypedroppedbad'] = 'Löschen des Datentyps fehlgeschlagen.';
	$lang['strflavor'] = 'Art';
	$lang['strbasetype'] = 'Basis-Typ';
	$lang['strcompositetype'] = 'Zusammengesetzt';
	$lang['strpseudotype'] = 'Pseudo';
	$lang['strenum'] = 'Aufzählend';
	$lang['strenumvalues'] = 'Wert';

	// Schemas
	$lang['strschema'] = 'Schema';
	$lang['strschemas'] = 'Schemata';
	$lang['strshowallschemas'] = 'Alle Schemata anzeigen';
	$lang['strnoschema'] = 'Kein Schema gefunden.';
	$lang['strnoschemas'] = 'Keine Schemata gefunden.';
	$lang['strcreateschema'] = 'Schema erstellen';
	$lang['strschemaname'] = 'Name des Schema';
	$lang['strschemaneedsname'] = 'Sie müssen für das Schema einen Namen angeben.';
	$lang['strschemacreated'] = 'Schema erstellt.';
	$lang['strschemacreatedbad'] = 'Erstellen des Schemas fehlgeschlagen.';
	$lang['strconfdropschema'] = 'Sind Sie sicher, dass sie das Schema "%s" löschen möchten?';
	$lang['strschemadropped'] = 'Schema gelöscht.';
	$lang['strschemadroppedbad'] = 'Löschen des Schemas fehlgeschlagen';
	$lang['strschemaaltered'] = 'Schema geändert.';
	$lang['strschemaalteredbad'] = 'Ändern des Schemas fehlgeschlagen.';
	$lang['strsearchpath'] = 'Schemasuchpfad';
	$lang['strspecifyschematodrop'] = 'Sie müssen mindestens ein Schema angeben, das gelöscht werden soll.';

	// Reports

	// Domains
	$lang['strdomain'] = 'Domäne';
	$lang['strdomains'] = 'Domänen';
	$lang['strshowalldomains'] = 'Alle Domänen anzeigen';
	$lang['strnodomains'] = 'Keine Domänen gefunden.';
	$lang['strcreatedomain'] = 'Domäne erstellen';
	$lang['strdomaindropped'] = 'Domäne gelöscht.';
	$lang['strdomaindroppedbad'] = 'Löschen der Domäne fehlgeschlagen.';
	$lang['strconfdropdomain'] = 'Sind Sie sicher, dass Sie die Domäne "%s" löschen wollen?';
	$lang['strdomainneedsname'] = 'Sie müssen einen Namen für die Domäne angeben.';
	$lang['strdomaincreated'] = 'Domäne erstellt.';
	$lang['strdomaincreatedbad'] = 'Erstellen der Domäne fehlgeschlagen.';
	$lang['strdomainaltered'] = 'Domäne geändert.';
	$lang['strdomainalteredbad'] = 'Ändern der Domäne fehlgeschlagen.';	

	// Operators
	$lang['stroperator'] = 'Operator';
	$lang['stroperators'] = 'Operatoren';
	$lang['strshowalloperators'] = 'Alle Operatoren anzeigen';
	$lang['strnooperator'] = 'Kein Operator gefunden.';
	$lang['strnooperators'] = 'Keine Operatoren gefunden.';
	$lang['strcreateoperator'] = 'Operator erstellen';
	$lang['strleftarg'] = 'Typ des linken Arguments';
	$lang['strrightarg'] = 'Typ des rechter Arguments';
	$lang['strcommutator'] = 'Kommutator';
	$lang['strnegator'] = 'Negator';
	$lang['strrestrict'] = 'Funktion zur Schätzung der Restriktions-Selektivität';
	$lang['strjoin'] = 'Funktion zur Schätzung der Join-Selektivität';
	$lang['strhashes'] = 'Unterstützt Hash-Joins';
	$lang['strmerges'] = 'Unterstützt Merge-Joins';
	$lang['strleftsort'] = 'Kleiner-Operator zum Sortieren der linken Seite';
	$lang['strrightsort'] = 'Kleiner-Operator zum Sortieren der rechten Seite';
	$lang['strlessthan'] = 'Kleiner-Operator';
	$lang['strgreaterthan'] = 'Größer-Operator';
	$lang['stroperatorneedsname'] = 'Sie müssen einen Namen für den Operator angeben.';
	$lang['stroperatorcreated'] = 'Operator erstellt.';
	$lang['stroperatorcreatedbad'] = 'Erstellen des Operators fehlgeschlagen.';
	$lang['strconfdropoperator'] = 'Sind Sie sicher, dass Sie den Operator "%s" löschen wollen?';
	$lang['stroperatordropped'] = 'Operator gelöscht.';
	$lang['stroperatordroppedbad'] = 'Löschen des Operators fehlgeschlagen.';

	// Casts
	$lang['strcasts'] = 'Typumwandlungen';
	$lang['strnocasts'] = 'Keine Typumwandlungen gefunden.';
	$lang['strsourcetype'] = 'Ursprungs-Datentyp';
	$lang['strtargettype'] = 'Ziel-Datentyp';
	$lang['strimplicit'] = 'Implizit';
	$lang['strinassignment'] = 'Bei Zuweisungen';
	$lang['strbinarycompat'] = '(Binärkompatibel)';
	
	// Conversions
	$lang['strconversions'] = 'Konvertierungen';
	$lang['strnoconversions'] = 'Keine Konvertierungen gefunden.';
	$lang['strsourceencoding'] = 'Ursprungs-Zeichenkodierung';
	$lang['strtargetencoding'] = 'Ziel-Zeichenkodierung';
	
	// Languages
	$lang['strlanguages'] = 'Programmiersprachen';
	$lang['strnolanguages'] = 'Keine Sprachen gefunden.';
	$lang['strtrusted'] = 'Vertrauenswürdig';
	
	// Info
	$lang['strnoinfo'] = 'Keine Informationen vorhanden.';
	$lang['strreferringtables'] = 'Tabellen, die mit Fremdschlüsseln auf diese Tabelle verweisen';
	$lang['strparenttables'] = 'Elterntabellen';
	$lang['strchildtables'] = 'Kindtabellen';

	// Aggregates
	$lang['straggregate'] = 'Aggregatsfunktion';
	$lang['straggregates'] = 'Aggregatsfunktionen';
	$lang['strnoaggregates'] = 'Keine Aggregatsfunktionen gefunden.';
	$lang['stralltypes'] = '(Alle Typen)';
	$lang['strcreateaggregate'] = 'Aggregatsfunktion erstellen';
	$lang['straggrbasetype'] = 'Eingabedatentyp';
	$lang['straggrsfunc'] = 'Zustandsübergangsfunktion';
	$lang['straggrstype'] = 'Datentyp für den Zustandswert';
	$lang['straggrffunc'] = 'Ergebnisfunktion';
	$lang['straggrinitcond'] = 'Zustandswert zu Beginn';
	$lang['straggrsortop'] = 'Operator für Sortierung';
	$lang['strconfdropaggregate'] = 'Sind Sie sicher, dass Sie die Aggregatsfunktion "%s" löschen wollen?';
	$lang['straggregatedropped'] = 'Aggregatsfunktion gelöscht.';
	$lang['straggregatedroppedbad'] = 'Löschen der Aggregatsfunktion fehlgeschlagen.';
	$lang['straggraltered'] = 'Aggregatsfunktion geändert.';
	$lang['straggralteredbad'] = 'Ändern der Aggregatsfunktion fehlgeschlagen.';
	$lang['straggrneedsname'] = 'Sie müssen einen Namen für die Aggregatsfunktion angeben.';
	$lang['straggrneedsbasetype'] = 'Sie müssen den Eingabedatentyp für die Aggregatsfunktion angeben.';
	$lang['straggrneedssfunc'] = 'Sie müssen den Namen der Zustandsübergangsfunktion für die Aggregatsfunktion angeben.';
	$lang['straggrneedsstype'] = 'Sie müssen den Datentyp für den Zustandswert der Aggregatsfunktion angeben.';
	$lang['straggrcreated'] = 'Aggregatsfunktion erstellt.';
	$lang['straggrcreatedbad'] = 'Erstellen der Aggregatsfunktion fehlgeschlagen.';
	$lang['straggrshowall'] = 'Alle Aggregatsfunktionen anzeigen';

	// Operator Classes
	$lang['stropclasses'] = 'Operatorklassen';
	$lang['strnoopclasses'] = 'Keine Operatorklassen gefunden.';
	$lang['straccessmethod'] = 'Zugriffsmethode';

	// Stats and performance
	$lang['strrowperf'] = 'Zeilen-Performance';
	$lang['strioperf'] = 'E/A Performance';
	$lang['stridxrowperf'] = 'Index-Zeilen-Performance';
	$lang['stridxioperf'] = 'Index-E/A-Performance';
	$lang['strpercent'] = '%';
	$lang['strsequential'] = 'Sequentiell';
	$lang['strscan'] = 'Durchsuchen';
	$lang['strread'] = 'Lesen';
	$lang['strfetch'] = 'Holen';
	$lang['strheap'] = 'Heap';
	$lang['strtoast'] = 'TOAST';
	$lang['strtoastindex'] = 'TOAST-Index';
	$lang['strcache'] = 'Zwischenspeicher';
	$lang['strdisk'] = 'Festplatte';
	$lang['strrows2'] = 'Zeilen';

	// Tablespaces
	$lang['strtablespace'] = 'Tablespace';
	$lang['strtablespaces'] = 'Tablespaces';
	$lang['strshowalltablespaces'] = 'Alle Tablespaces anzeigen';
	$lang['strnotablespaces'] = 'Keine Tablespaces gefunden.';
	$lang['strcreatetablespace'] = 'Tablespace erstellen';
	$lang['strlocation'] = 'Pfad';
	$lang['strtablespaceneedsname'] = 'Sie müssen einen Namen für den Tablespace angeben.';
	$lang['strtablespaceneedsloc'] = 'Sie müssen ein Verzeichnis angeben, in dem Sie den Tablespace erstellen möchten.';
	$lang['strtablespacecreated'] = 'Tablespace erstellt.';
	$lang['strtablespacecreatedbad'] = 'Erstellen des Tablespace fehlgeschlagen.';
	$lang['strconfdroptablespace'] = 'Sind Sie sicher, dass Sie den Tablespace "%s" löschen wollen?';
	$lang['strtablespacedropped'] = 'Tablespace gelöscht.';
	$lang['strtablespacedroppedbad'] = 'Löschen des Tablespace fehlgeschlagen.';
	$lang['strtablespacealtered'] = 'Tablespace geändert.';
	$lang['strtablespacealteredbad'] = 'Ändern des Tablespace fehlgeschlagen.';

	// Miscellaneous
	$lang['strtopbar'] = '%s läuft auf %s:%s -- Sie sind als "%s" angemeldet';
	$lang['strtimefmt'] = 'D, j. n. Y, G:i';
	$lang['strhelp'] = 'Hilfe';
	$lang['strhelpicon'] = '?';
	$lang['strhelppagebrowser'] = 'Browser für Hilfeseiten';
	$lang['strselecthelppage'] = 'Hilfeseite auswählen';
	$lang['strinvalidhelppage'] = 'Ungültige Hilfeseite.';
	$lang['strlogintitle'] = 'Bei %s anmelden';
	$lang['strlogoutmsg'] = 'Von %s abgemeldet';
	$lang['strloading'] = 'Lade...';
	$lang['strerrorloading'] = 'Fehler beim Laden';
	$lang['strclicktoreload'] = 'Klicken Sie zum Neuladen';

	// Autovacuum
	$lang['strautovacuum'] = 'Autovacuum';
	$lang['strturnedon'] = 'Eingeschaltet';
	$lang['strturnedoff'] = 'Ausgeschaltet';
	$lang['strenabled'] = 'Aktiviert';
	$lang['strvacuumbasethreshold'] = 'Autovacuum-Schwellwert';
	$lang['strvacuumscalefactor'] = 'Autovacuum-Skalierungsfaktor';
	$lang['stranalybasethreshold'] = 'Analyze-Schwellwert';
	$lang['stranalyzescalefactor'] = 'Analyze-Skalierungsfaktor';
	$lang['strvacuumcostdelay'] = 'Pause nach Erreichen des Autovacuum-Kostenlimits';
	$lang['strvacuumcostlimit'] = 'Autovacuum-Kostenlimits';

	// Table-level Locks
	$lang['strlocks'] = 'Sperren';
	$lang['strtransaction'] = 'Transaktions-ID';
	$lang['strvirtualtransaction'] = 'Virtuelle Transaktions-ID';
	$lang['strprocessid'] = 'Prozess-ID';
	$lang['strmode'] = 'Art der Sperre';
	$lang['strislockheld'] = 'Sperre gewährt?';

	// Prepared transactions
	$lang['strpreparedxacts'] = 'Vorbereitete verteilte Transaktionen';
	$lang['strxactid'] = 'Transaktions-ID';
	$lang['strgid'] = 'Globale ID';
	
	// Fulltext search
	$lang['strfulltext'] = 'Volltextsuche';
	$lang['strftsconfig'] = 'Volltextsuch-Konfiguration';
	$lang['strftsconfigs'] = 'Konfigurationen';
	$lang['strftscreateconfig'] = 'Volltextsuch-Konfiguration erstellen';
	$lang['strftscreatedict'] = 'Wörterbuch erstellen';
	$lang['strftscreatedicttemplate'] = 'Wörterbuch-Blaupause erstellen';
	$lang['strftscreateparser'] = 'Parser erstellen';
	$lang['strftsnoconfigs'] = 'Keine Volltextsuch-Konfigurationen gefunden.';
	$lang['strftsconfigdropped'] = 'Volltextsuch-Konfiguration gelöscht.';
	$lang['strftsconfigdroppedbad'] = 'Löschen der Volltextsuch-Konfiguration fehlgeschlagen.';
	$lang['strconfdropftsconfig'] = 'Sind Sie sicher, dass Sie die Volltextsuch-Konfiguration "%s" löschen möchten?';
	$lang['strconfdropftsdict'] = 'Sind Sie sicher, dass Sie das Wörterbuch "%s" löschen möchten?';
	$lang['strconfdropftsmapping'] = 'Sind Sie sicher, dass Sie die Zuordnung "%s" der Volltextsuch-Konfiguration "%s" löschen möchten?';
	$lang['strftstemplate'] = 'Blaupause';
	$lang['strftsparser'] = 'Parser';
	$lang['strftsconfigneedsname'] = 'Sie müssen für die Volltextsuch-Konfiguration einen Namen angeben.';
	$lang['strftsconfigcreated'] = 'Volltextsuch-Konfiguration erstellt.';
	$lang['strftsconfigcreatedbad'] = 'Erstellen der Volltextsuch-Konfiguration fehlgeschlagen.';
	$lang['strftsmapping'] = 'Zuordnung';
	$lang['strftsdicts'] = 'Wörterbücher';
	$lang['strftsdict'] = 'Wörterbuch';
	$lang['strftsemptymap'] = 'Leere Zuordnung für Volltextsuch-Konfiguration.';
	$lang['strftswithmap'] = 'Mit Zuordnung';
	$lang['strftsmakedefault'] = 'Als Standardwert für die angegebene Spracheinstellung festlegen';
	$lang['strftsconfigaltered'] = 'Volltextsuch-Konfiguration geändert.';
	$lang['strftsconfigalteredbad'] = 'Ändern der Volltextsuch-Konfiguration fehlgeschlagen.';
	$lang['strftsconfigmap'] = 'Zuordnung für Volltextsuch-Konfiguration';
	$lang['strftsparsers'] = 'Parsers für Volltextsuch-Konfiguration';
	$lang['strftsnoparsers'] = 'Keine Parsers für Volltextsuch-Konfiguration vorhanden';
	$lang['strftsnodicts'] = 'Keine Wörterbücher für die Volltextsuche vorhanden.';
	$lang['strftsdictcreated'] = 'Wörterbuch für die Volltextsuche erstellt.';
	$lang['strftsdictcreatedbad'] = 'Erstellen des Wörterbuches für die Volltextsuche fehlgeschlagen.';
	$lang['strftslexize'] = 'Funktion zum Zerlegen in Lexeme';
	$lang['strftsinit'] = 'Initialisierungsfunktion';
	$lang['strftsoptionsvalues'] = 'Optionen und Werte';
	$lang['strftsdictneedsname'] = 'Sie müssen für das Volltextsuch-Wörterbuch einen Namen angeben.';
	$lang['strftsdictdropped'] = 'Wörterbuches für die Volltextsuche gelöscht.';
	$lang['strftsdictdroppedbad'] = 'Löschen des Wörterbuches für die Volltextsuche fehlgeschlagen.';
	$lang['strftsdictaltered'] = 'Wörterbuches für die Volltextsuche geändert.';
	$lang['strftsdictalteredbad'] = 'Ändern des Wörterbuches für die Volltextsuche fehlgeschlagen.';
	$lang['strftsaddmapping'] = 'Neue Zuordnung hinzufügen';
	$lang['strftsspecifymappingtodrop'] = 'Sie müssen mindestens eine Zuordnung angeben, die gelöscht werden soll.';
	$lang['strftsspecifyconfigtoalter'] = 'Sie müssen eine Volltextsuch-Konfiguration angeben, die geändert werden soll';
	$lang['strftsmappingdropped'] = 'Volltextsuch-Zuordnung gelöscht.';
	$lang['strftsmappingdroppedbad'] = 'Löschen der Volltextsuch-Zuordnung fehlgeschlagen.';
	$lang['strftsnodictionaries'] = 'Keine Wörterbücher gefunden.';
	$lang['strftsmappingaltered'] = 'Volltextsuch-Zuordnung geändert.';
	$lang['strftsmappingalteredbad'] = 'Ändern der Volltextsuch-Zuordnung fehlgeschlagen.';
	$lang['strftsmappingadded'] = 'Volltextsuch-Zuordnung hinzugefügt.';
	$lang['strftsmappingaddedbad'] = 'Hinzufügen der Volltextsuch-Zuordnung fehlgeschlagen.';
	$lang['strftstabconfigs'] = 'Volltextsuch-Konfigurationen';
	$lang['strftstabdicts'] = 'Wörterbücher';
	$lang['strftstabparsers'] = 'Parser';
?>
