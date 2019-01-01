<?php

	/**
	 * Swedish language file for phpPgAdmin.
	 * maintainer S. Malmqvist <samoola@slak.nu>
	 * Due to lack of SQL knowledge som translations may be wrong, mail me the correct one and ill fix it
	 *
	 * $Id: swedish.php,v 1.11 2007/04/24 11:42:07 soranzo Exp $
	 */

	// Language and character set
	$lang['applang'] = 'Swedish';
	$lang['applocale'] = 'sv-SE';
	$lang['applangdir'] = 'ltr';

	// Welcome  
	$lang['strintro'] = 'Välkommen till phpPgAdmin.';
	$lang['strppahome'] = 'phpPgAdmins Hemsida';
	$lang['strpgsqlhome'] = 'PostgreSQLs Hemsida';
	$lang['strpgsqlhome_url'] = 'http://www.postgresql.org/';
	$lang['strlocaldocs'] = 'PostgreSQLs Dokumentation (lokalt)';
	$lang['strreportbug'] = 'Rapportera ett fel';
	$lang['strviewfaq'] = 'Visa Frågor & Svar';
	$lang['strviewfaq_url'] = 'http://phppgadmin.sourceforge.net/doku.php?id=faq';
	
	// Basic strings
	$lang['strlogin'] = 'Logga in';
	$lang['strloginfailed'] = 'Inloggningen misslyckades';
	$lang['strlogindisallowed'] = 'Inloggningen ej tillåten';
	$lang['strserver'] = 'Server';
	$lang['strlogout'] = 'Logga ut';
	$lang['strowner'] = 'Ägare';
	$lang['straction'] = 'Åtgärd';
	$lang['stractions'] = 'Åtgärder';
	$lang['strname'] = 'Namn';
	$lang['strdefinition'] = 'Definition';
	$lang['strproperties'] = 'Egenskaper';
	$lang['strbrowse'] = 'Bläddra';
	$lang['strdrop'] = 'Ta bort';
	$lang['strdropped'] = 'Borttagen';
	$lang['strnull'] = 'Ingenting';
	$lang['strnotnull'] = 'Inte Ingenting';
	$lang['strfirst'] = '<< Första';
	$lang['strlast'] = 'Sista >>';
	$lang['strprev'] = 'Föregående';
	$lang['strfailed'] = 'Misslyckades';
	$lang['strnext'] = 'Nästa';
	$lang['strcreate'] = 'Skapa';
	$lang['strcreated'] = 'Skapad';
	$lang['strcomment'] = 'Kommentar';
	$lang['strlength'] = 'Längd';
	$lang['strdefault'] = 'Standardvärde';
	$lang['stralter'] = 'Ändra';
	$lang['strok'] = 'OK';
	$lang['strcancel'] = 'Ångra';
	$lang['strsave'] = 'Spara';
	$lang['strreset'] = 'Nollställ';
	$lang['strinsert'] = 'Infoga';
	$lang['strselect'] = 'Välj';
	$lang['strdelete'] = 'Radera';
	$lang['strupdate'] = 'Uppdatera';
	$lang['strreferences'] = 'Referencer';
	$lang['stryes'] = 'Ja';
	$lang['strno'] = 'Nej';
	$lang['strtrue'] = 'Sant';
	$lang['strfalse'] = 'Falskt';
	$lang['stredit'] = 'Redigera';
	$lang['strcolumns'] = 'Kolumner';
	$lang['strrows'] = 'Rad(er)';
	$lang['strrowsaff'] = 'Rad(er) Påverkade.';
	$lang['strobjects'] = 'Objekt';
	$lang['strexample'] = 't. ex.';
	$lang['strback'] = 'Bakåt';
	$lang['strqueryresults'] = 'Sökresultat';
	$lang['strshow'] = 'Visa';
	$lang['strempty'] = 'Tom';
	$lang['strlanguage'] = 'Språk';
	$lang['strencoding'] = 'Kodning';
	$lang['strvalue'] = 'Värde';
	$lang['strunique'] = 'Unik';
	$lang['strprimary'] = 'Primär';
	$lang['strexport'] = 'Exportera';
	$lang['strimport'] = 'Importera';
	$lang['strsql'] = 'SQL';
	$lang['strgo'] = 'Kör';
	$lang['stradmin'] = 'Admin';
	$lang['strvacuum'] = 'Städa upp';
	$lang['stranalyze'] = 'Analysera';
	$lang['strclusterindex'] = 'Kluster';
	$lang['strclustered'] = 'Klustrat?';
	$lang['strreindex'] = 'Återindexera';
	$lang['strrun'] = 'Kör';
	$lang['stradd'] = 'Lägg till';
	$lang['strinstead'] = 'Gör Istället';
	$lang['strevent'] = 'Händelse';
	$lang['strformat'] = 'Format';
	$lang['strwhen'] = 'När';
	$lang['strdata'] = 'Data';
	$lang['strconfirm'] = 'Bekräfta';
	$lang['strexpression'] = 'Uttryck';
	$lang['strellipsis'] = '...';
	$lang['strwhere'] = 'När';
	$lang['strexplain'] = 'Förklara';
	$lang['strfind'] = 'Sök';
	$lang['stroptions'] = 'Alternativ';
	$lang['strrefresh'] = 'Uppdatera';
	$lang['strcollapse'] = 'Förminska';
	$lang['strexpand'] = 'Utöka';
	$lang['strdownload'] = 'Ladda ner';
	$lang['strdownloadgzipped'] = 'Ladda ner komprimerat med gzip';
	$lang['strinfo'] = 'Info';
	$lang['stroids'] = 'OIDs';
	$lang['stradvanced'] = 'Avancerat';
	$lang['strvariables'] = 'Variabler';
	$lang['strprocess'] = 'Process';
	$lang['strprocesses'] = 'Processer';
	$lang['strsetting'] = 'Inställning';
	$lang['strparameters'] = 'Parametrar';

	// Error handling
	$lang['strnotloaded'] = 'Du har inte kompilerat in korrekt databasstöd i din PHP-installation.';
	$lang['strbadconfig'] = 'Din config.inc.php är ej uppdaterad. Du måste återskapa den från den nya config.inc.php-dist.';
	$lang['strbadencoding'] = 'Misslyckades att sätta klientkodning i databasen.';
	$lang['strbadschema'] = 'Otillåtet schema angett.';
	$lang['strinstatement'] = 'I påstående:';
	$lang['strsqlerror'] = 'SQL fel:';
	$lang['strinvalidparam'] = 'Otillåtna scriptparametrar.';
	$lang['strnodata'] = 'Hittade inga rader.';
	$lang['strnoobjects'] = 'Hittade inga objekt.';
	$lang['strrownotunique'] = 'Ingen unik nyckel för denna rad.';

	// Tables
	$lang['strtable'] = 'Tabell';
	$lang['strtables'] = 'Tabeller';
	$lang['strshowalltables'] = 'Visa alla tabeller';
	$lang['strnotables'] = 'Hittade inga tabeller.';
	$lang['strnotable'] = 'Hittade ingen tabell.';
	$lang['strcreatetable'] = 'Skapa tabell';
	$lang['strtablename'] = 'Tabellnamn';
	$lang['strtableneedsname'] = 'Du måste ge ett namn till din tabell.';
	$lang['strtableneedsfield'] = 'Du måste ange minst ett fält.';
	$lang['strtableneedscols'] = 'tabeller kräver ett tillåtet antal kolumner.';
	$lang['strtablecreated'] = 'Tabell skapad.';
	$lang['strtablecreatedbad'] = 'Misslyckades med att skapa Tabell.';
	$lang['strconfdroptable'] = 'Är du säker på att du vill ta bort tabellen "%s"?';
	$lang['strtabledropped'] = 'Tabellen borttagen.';
	$lang['strinsertrow'] = 'Infoga rad';
	$lang['strtabledroppedbad'] = 'Misslyckades med att ta bort tabellen.';
	$lang['strrowinserted'] = 'Rad infogad.';
	$lang['strconfemptytable'] = 'Är du säker på att du vill tömma tabellen "%s"?';
	$lang['strrowupdated'] = 'Rad uppdaterad.';
	$lang['strrowinsertedbad'] = 'Misslyckades att infoga rad.';
	$lang['strtableemptied'] = 'Tabellen tömd.';
	$lang['strrowupdatedbad'] = 'Misslyckades att uppdatera rad.';
	$lang['streditrow'] = 'Ändra rad';
	$lang['strrowdeleted'] = 'Rad raderad.';
	$lang['strrowdeletedbad'] = 'Misslyckades att radera rad.';
	$lang['strfield'] = 'Fält';
	$lang['strconfdeleterow'] = 'Är du säker på att du vill ta bort denna rad?';
	$lang['strnumfields'] = 'Antal fält';
	$lang['strsaveandrepeat'] = 'Infoga & Upprepa';
	$lang['strtableemptiedbad'] = 'Misslyckades med att tömma tabellen';
	$lang['strdeleterow'] = 'Radera rad';
	$lang['strfields'] = 'Fält';
	$lang['strfieldneedsname'] = 'Du måste namnge fältet';
	$lang['strcolumndropped'] = 'Kolumn raderad.';
	$lang['strselectallfields'] = 'Välj alla fält';
	$lang['strselectneedscol'] = 'Du måste visa minst en kolumn';
	$lang['strselectunary'] = 'Unära operander kan ej ha värden.';
	$lang['strcolumnaltered'] = 'Kolumn ändrad.';
	$lang['straltercolumn'] = 'Ändra kolumn';
	$lang['strcolumnalteredbad'] = 'Misslyckades att ändra kolumn.';
	$lang['strconfdropcolumn'] = 'Är du säker på att du vill radera kolumn "%s" från tabell "%s"?';
	$lang['strcolumndroppedbad'] = 'Misslyckades att radera kolumn.';
	$lang['straddcolumn'] = 'Lägg till kolumn';
	$lang['strcolumnadded'] = 'Kolumn inlagd.';
	$lang['strcolumnaddedbad'] = 'Misslyckades att lägga till kolumne.';
	$lang['strcascade'] = 'KASKAD';
	$lang['strdataonly'] = 'Endast Data';
	$lang['strtablealtered'] = 'Tabell ändrad.';
	$lang['strtablealteredbad'] = 'Misslyckades att ändra tabell.';
	
	// Users
	$lang['struser'] = 'Användare';
	$lang['strusers'] = 'Användare';
	$lang['strusername'] = 'Användarnamn';
	$lang['strpassword'] = 'Lösenord';
	$lang['strsuper'] = 'Superanvändare?';
	$lang['strcreatedb'] = 'Skapa Databas?';
	$lang['strexpires'] = 'Utgångsdatum';
	$lang['strsessiondefaults'] = 'Sessionsinställningar';
	$lang['strnewname'] = 'Nytt namn';
	$lang['strnousers'] = 'Hittade inga användare.';
	$lang['strrename'] = 'Döp om';
	$lang['struserrenamed'] = 'Användarnamn ändrat.';
	$lang['struserrenamedbad'] = 'Misslyckades att döpa om användare.';
	$lang['struserupdated'] = 'Användare uppdaterad.';
	$lang['struserupdatedbad'] = 'Misslyckades att uppdatera användare.';
	$lang['strshowallusers'] = 'Visa alla användare';
	$lang['strcreateuser'] = 'Skapa användare';
	$lang['struserneedsname'] = 'Du måste namnge användaren.';
	$lang['strconfdropuser'] = 'Är du säker på att du vill radera användaren "%s"?';
	$lang['strusercreated'] = 'Användare skapad.';
	$lang['strusercreatedbad'] = 'Misslyckades att skapa användare.';
	$lang['struserdropped'] = 'Användare raderad.';
	$lang['struserdroppedbad'] = 'Misslyckades att radera användare.';
	$lang['straccount'] = 'Konton';
	$lang['strchangepassword'] = 'Ändra lösenord';
	$lang['strpasswordchanged'] = 'Lösenord ändrat.';
	$lang['strpasswordchangedbad'] = 'Misslyckades att ändra lösenord.';
	$lang['strpasswordshort'] = 'För få tecken i lösenordet.';
	$lang['strpasswordconfirm'] = 'Lösenordet är inte samma som bekräftelsen.';
	$lang['strgroup'] = 'Grupp';
	$lang['strgroups'] = 'Grupper';
	$lang['strnogroup'] = 'Hittade ej grupp.';
	$lang['strnogroups'] = 'Hittade inga grupper.';
	$lang['strcreategroup'] = 'Skapa grupp';
	$lang['strshowallgroups'] = 'Visa alla grupper';
	$lang['strgroupneedsname'] = 'Du måste namnge din grupp.';
	$lang['strgroupcreated'] = 'Grupp skapad.';
	$lang['strgroupdropped'] = 'Grupp raderad.';
	$lang['strgroupcreatedbad'] = 'Misslyckades att skapa grupp.';	
	$lang['strconfdropgroup'] = 'Är du säker på att du vill radera grupp "%s"?';
	$lang['strprivileges'] = 'Rättigheter';
	$lang['strgrant'] = 'Tillåt';
	$lang['strgranted'] = 'Rättigheter ändrade.';
	$lang['strgroupdroppedbad'] = 'Misslyckades att radera grupp.';
	$lang['straddmember'] = 'Lägg till medlem';
	$lang['strmemberadded'] = 'Medlem inlagd.';
	$lang['strmemberaddedbad'] = 'Misslyckades att lägga till medlem.';
	$lang['strdropmember'] = 'Radera medlem';
	$lang['strconfdropmember'] = 'Är du säker på att du vill radera medlem "%s" från gruppen "%s"?';
	$lang['strmemberdropped'] = 'Medlem raderad.';
	$lang['strmemberdroppedbad'] = 'Misslyckades att radera medlem.';
	$lang['strprivilege'] = 'Rättighet';
	$lang['strnoprivileges'] = 'Detta objekt har standard ägarrättigheter.';
	$lang['strmembers'] = 'Medelemmar';
	$lang['strrevoke'] = 'Ta tillbaka';
	$lang['strgrantbad'] = 'Du måste ange minst en användare eller grupp och minst en rättighet.';
	$lang['strgrantfailed'] = 'Misslyckades att ändra rättigheter.';
	$lang['stralterprivs'] = 'Ändra rättigheter';
	$lang['strdatabase'] = 'Databas';
	$lang['strdatabasedropped'] = 'Databas raderad.';
	$lang['strdatabases'] = 'Databaser';
	$lang['strentersql'] = 'Ange SQL att köra:';
	$lang['strgrantor'] = 'Tillståndsgivare';
	$lang['strasterisk'] = '*';
	$lang['strshowalldatabases'] = 'Visa alla databaser';
	$lang['strnodatabase'] = 'Hittade ingen databas.';
	$lang['strnodatabases'] = 'Hittade inga databaser.';
	$lang['strcreatedatabase'] = 'Skapa databas';
	$lang['strdatabasename'] = 'Databasnamn';
	$lang['strdatabaseneedsname'] = 'Du måste namnge databasen.';
	$lang['strdatabasecreated'] = 'Databas skapad.';
	$lang['strdatabasecreatedbad'] = 'Misslyckades att skapa databas.';	
	$lang['strconfdropdatabase'] = 'Är du säker på att du vill radera databas "%s"?';
	$lang['strdatabasedroppedbad'] = 'Misslyckades att radera databas.';
	$lang['strsqlexecuted'] = 'SQL-kommando utfört.';
	$lang['strvacuumgood'] = 'Uppstädning utförd.';
	$lang['strvacuumbad'] = 'Uppstädning misslyckades.';
	$lang['stranalyzegood'] = 'Analysen lyckades.';
	$lang['stranalyzebad'] = 'Analysen misslyckades.';
	$lang['strstructureonly'] = 'Endast struktur';
	$lang['strstructureanddata'] = 'Struktur och data';

	// Views
	$lang['strview'] = 'Vy';
	$lang['strviews'] = 'Vyer';
	$lang['strshowallviews'] = 'Visa alla vyer';
	$lang['strnoview'] = 'Hittade ingen vy.';
	$lang['strnoviews'] = 'Hittade inga vyer.';
	$lang['strcreateview'] = 'Skapa vy';
	$lang['strviewname'] = 'Vynamn';
	$lang['strviewneedsname'] = 'Du måste namnge Vy.';
	$lang['strviewneedsdef'] = 'Du måste ange en definition för din vy.';
	$lang['strviewcreated'] = 'Vy skapad.';
	$lang['strviewcreatedbad'] = 'Misslyckades att skapa vy.';
	$lang['strconfdropview'] = 'Är du säker på att du vill radera vyn "%s"?';
	$lang['strviewdropped'] = 'Vy raderad.';
	$lang['strviewdroppedbad'] = 'Misslyckades att radera vy.';
	$lang['strviewupdated'] = 'Vy uppdaterad.';
	$lang['strviewupdatedbad'] = 'Misslyckades att uppdatera vy.';
	$lang['strviewlink'] = 'Länkade nycklar';
	$lang['strviewconditions'] = 'Ytterligare villkor';

	// Sequences
	$lang['strsequence'] = 'Sekvens';
	$lang['strsequences'] = 'Sekvenser';
	$lang['strshowallsequences'] = 'Visa alla sekvenser';
	$lang['strnosequence'] = 'Hittade ingen sekvens.';
	$lang['strnosequences'] = 'Hittade inga sekvenser.';
	$lang['strcreatesequence'] = 'Skapa sekvens';
	$lang['strlastvalue'] = 'Senaste värde';
	$lang['strincrementby'] = 'Öka med';
	$lang['strstartvalue'] = 'Startvärde';
	$lang['strmaxvalue'] = 'Största värde';
	$lang['strminvalue'] = 'Minsta värde';
	$lang['strcachevalue'] = 'Värde på cache';
	$lang['strlogcount'] = 'Räkna log';
	$lang['striscycled'] = 'Är upprepad?';
	$lang['strsequenceneedsname'] = 'Du måste ge ett namn till din sekvens.';
	$lang['strsequencecreated'] = 'Sekvens skapad.';
	$lang['strsequencecreatedbad'] = 'Misslyckades att skapa sekvens.'; 
	$lang['strconfdropsequence'] = 'Är du säker på att du vill radera sekvensen "%s"?';
	$lang['strsequencedropped'] = 'Sekvensen borrtagen.';
	$lang['strsequencedroppedbad'] = 'Misslyckades att radera sekvens.';

	// Indexes
	$lang['strindex'] = 'Index';
	$lang['strindexes'] = 'Index';
	$lang['strindexname'] = 'Indexnamn';
	$lang['strshowallindexes'] = 'Visa alla index';
	$lang['strnoindex'] = 'Hittade inget index.';
	$lang['strsequencereset'] = 'Nollställ sekvens.';
	$lang['strsequenceresetbad'] = 'Misslyckades att nollställa sekvens.';
	$lang['strnoindexes'] = 'Hittade inga index.';
	$lang['strcreateindex'] = 'Skapa index';
	$lang['strindexname'] = 'Indexnamn';
	$lang['strtabname'] = 'Tabellnamn';
	$lang['strcolumnname'] = 'Kolumnnamn';
	$lang['strindexneedsname'] = 'Du måste ge ett namn för ditt index';
	$lang['strindexneedscols'] = 'Det krävs ett giltigt antal kolumner för index.';
	$lang['strindexcreated'] = 'Index skapat';
	$lang['strindexcreatedbad'] = 'Misslyckades att skapa index.';
	$lang['strconfdropindex'] = 'Är du säker på att du vill radera index "%s"?';
	$lang['strindexdropped'] = 'Index raderat.';
	$lang['strindexdroppedbad'] = 'Misslyckades att radera index.';
	$lang['strkeyname'] = 'Nyckelvärdesnamn';
	$lang['struniquekey'] = 'Unikt nyckelvärde';
	$lang['strprimarykey'] = 'Primärnyckel';
 	$lang['strindextype'] = 'Typ av index';
	$lang['strindexname'] = 'Indexnamn';
	$lang['strtablecolumnlist'] = 'Tabellkolumner';
	$lang['strindexcolumnlist'] = 'Indexkolumner';
	$lang['strconfcluster'] = 'Är du säker på att du vill klustra "%s"?';
	$lang['strclusteredgood'] = 'Klustring avslutad.';
	$lang['strclusteredbad'] = 'Klustring misslyckades.';

	// Rules
	$lang['strrules'] = 'Regler';
	$lang['strrule'] = 'Regel';
	$lang['strshowallrules'] = 'Visa alla regler';
	$lang['strnorule'] = 'Hittade ingen regel.';
	$lang['strnorules'] = 'Hittade inga regler.';
	$lang['strcreaterule'] = 'Skapa regel';
	$lang['strrulename'] = 'Regelnamn';
	$lang['strruleneedsname'] = 'Du måste ge ett namn till din regel.';
	$lang['strrulecreated'] = 'Regel skapad.';
	$lang['strrulecreatedbad'] = 'Misslyckades att skapa regel.';
	$lang['strconfdroprule'] = 'Är du säker på att du vill radera regel "%s" för "%s"?';
	$lang['strruledropped'] = 'Regel raderat.';
	$lang['strruledroppedbad'] = 'Misslyckades att radera regel.';

	// Constraints
	$lang['strconstraints'] = 'Begränsningar';
	$lang['strshowallconstraints'] = 'Visa alla begränsningar';
	$lang['strnoconstraints'] = 'Hittade inga begränsningar.';
	$lang['strcreateconstraint'] = 'Skapa begränsning';
	$lang['strconstraintcreated'] = 'Begränsning skapad.';
	$lang['strconstraintcreatedbad'] = 'Misslyckades att skapa begränsning.';
	$lang['strconfdropconstraint'] = 'Är du säker på att du vill radera begränsning "%s" för "%s"?';
	$lang['strconstraintdropped'] = 'Begränsning raderad.';
	$lang['strconstraintdroppedbad'] = 'Misslyckades att radera begränsning.';
	$lang['straddcheck'] = 'Lägg till en koll';
	$lang['strcheckneedsdefinition'] = 'En kollbegränsning behöver definieras.';
	$lang['strcheckadded'] = 'Kollbegränsning inlagd.';
	$lang['strcheckaddedbad'] = 'Misslyckades att lägga till en koll.';
	$lang['straddpk'] = 'Lägg till primärnyckel';
	$lang['strpkneedscols'] = 'Primärnyckel behöver minst en kolumn.';
	$lang['strpkadded'] = 'Primärnyckel inlagd.';
	$lang['strpkaddedbad'] = 'Misslyckades att lägga till primärnyckel.';
	$lang['stradduniq'] = 'Lägg till Unikt nyckelvärde';
	$lang['struniqneedscols'] = 'Unikt nyckelvärde behöver minst en kolumn.';
	$lang['struniqadded'] = 'Unikt nyckelvärde inlagt.';
	$lang['struniqaddedbad'] = 'Misslyckades att lägga till unikt nyckelvärde.';
	$lang['straddfk'] = 'Lägg till utomstående nyckel';
	$lang['strfkneedscols'] = 'Utomstående nyckel behöver minst en kolumn.';
	$lang['strfkneedstarget'] = 'Utomstående nycket behöver en måltabell.';
	$lang['strfkadded'] = 'Utomstående nyckel inlagd.';
	$lang['strfkaddedbad'] = 'Misslyckades att lägga till utomstående nyckel.';
	$lang['strfktarget'] = 'Måltabell';
	$lang['strfkcolumnlist'] = 'Kolumner i nyckel';
	$lang['strondelete'] = 'VID RADERING';
	$lang['stronupdate'] = 'VID UPPDATERING';

	// Functions
	$lang['strfunction'] = 'Funktion';
	$lang['strfunctions'] = 'Funktioner';
	$lang['strshowallfunctions'] = 'Visa alla funktioner';
	$lang['strnofunction'] = 'Hittade ingen funktion.';
	$lang['strnofunctions'] = 'Hittade inga funktioner.';
	$lang['strcreatefunction'] = 'Skapa funktion';
	$lang['strfunctionname'] = 'Funktionsnamn';
	$lang['strreturns'] = 'Återger';
	$lang['strarguments'] = 'Argument';
	$lang['strfunctionneedsname'] = 'Du måste namnge din funktion.';
	$lang['strfunctionneedsdef'] = 'Du måste definiera din funktion.';
	$lang['strfunctioncreated'] = 'Funktion skapad.';
	$lang['strfunctioncreatedbad'] = 'Misslyckades att skapa funktion.';
	$lang['strconfdropfunction'] = 'Är du säker på att du vill radera funktionen "%s"?';
	$lang['strproglanguage'] = 'Programmeringsspråk';
	$lang['strfunctiondropped'] = 'Funktionen raderad.';
	$lang['strfunctiondroppedbad'] = 'Misslyckades att radera funktion.';
	$lang['strfunctionupdated'] = 'Funktion uppdaterad.';
	$lang['strfunctionupdatedbad'] = 'Misslyckades att uppdatera funktion.';

	// Triggers
	$lang['strtrigger'] = 'Mekanism';
	$lang['strtriggers'] = 'Mekanismer';
	$lang['strshowalltriggers'] = 'Visa alla Mekanismer';
	$lang['strnotrigger'] = 'Hittade ingen mekanism.';
	$lang['strnotriggers'] = 'Hittade inga mekanismer.';
	$lang['strcreatetrigger'] = 'Skapa mekanism';
	$lang['strtriggerneedsname'] = 'Du måste namnge din mekanism.';
	$lang['strtriggerneedsfunc'] = 'Du måste specificera en funktion för din mekanism.';
	$lang['strtriggercreated'] = 'Mekanism skapad.';
	$lang['strtriggerdropped'] = 'Mekanism raderad.';
	$lang['strtriggercreatedbad'] = 'Misslyckades att skapa mekanism.';
	$lang['strconfdroptrigger'] = 'Är du säker på att du vill radera mekanismen "%s" på "%s"?';
	$lang['strtriggerdroppedbad'] = 'Misslyckades att radera mekanism.';
	
	// Types
	$lang['strtype'] = 'Typ';
	$lang['strstorage'] = 'Lagring';
	$lang['strtriggeraltered'] = 'Mekanism ändrad.';
	$lang['strtriggeralteredbad'] = 'Misslyckades att ändra mekanism.';
	$lang['strtypes'] = 'Typer';
	$lang['strtypeneedslen'] = 'Du måste ange typens längd.';
	$lang['strshowalltypes'] = 'Visa alla typer';
	$lang['strnotype'] = 'Hittade ingen typ.';
	$lang['strnotypes'] = 'Hittade inga typer.';
	$lang['strcreatetype'] = 'Skapa typ';
	$lang['strtypename'] = 'Namn på typen';
	$lang['strinputfn'] = 'Infogande funktion';
	$lang['stroutputfn'] = 'Funktion för utvärden';
	$lang['strpassbyval'] = 'Genomgått utvärdering?';
	$lang['stralignment'] = 'Justering';
	$lang['strelement'] = 'Element';
	$lang['strdelimiter'] = 'Avgränsare';
	$lang['strtypeneedsname'] = 'Du måste namnge din typ.';
	$lang['strtypecreated'] = 'Typ skapad';
	$lang['strtypecreatedbad'] = 'Misslyckades att skapa typ.';
	$lang['strconfdroptype'] = 'Är du säker på att du vill radera typen "%s"?';
	$lang['strtypedropped'] = 'Typ raderad.';
	$lang['strtypedroppedbad'] = 'Misslyckades att radera typ.';

	// Schemas
	$lang['strschema'] = 'Schema';
	$lang['strschemas'] = 'Scheman';
	$lang['strshowallschemas'] = 'Visa alla scheman';
	$lang['strnoschema'] = 'Hittade inget schema.';
	$lang['strnoschemas'] = 'Hittade inga scheman.';
	$lang['strcreateschema'] = 'Skapa Schema';
	$lang['strschemaname'] = 'Schemanamn';
	$lang['strschemaneedsname'] = 'Du måste namnge ditt Schema.';
	$lang['strschemacreated'] = 'Schema skapat';
	$lang['strschemacreatedbad'] = 'Misslyckades att skapa schema.';
	$lang['strconfdropschema'] = 'Är du säker på att du vill radera schemat "%s"?';
	$lang['strschemadropped'] = 'Schema raderat.';
	$lang['strschemadroppedbad'] = 'Misslyckades att radera schema.';

	// Reports
	$lang['strtopbar'] = '%s körs på %s:%s -- Du är inloggad som användare "%s"';
	$lang['strtimefmt'] = 'jS M, Y g:iA';
	
	// Domains
	$lang['strdomain'] = 'Domän';
	$lang['strdomains'] = 'Domäner';
	$lang['strshowalldomains'] = 'Visa alla domäner';
	$lang['strnodomains'] = 'Hittade inga domäner.';
	$lang['strcreatedomain'] = 'Skapa domän';
	$lang['strdomaindropped'] = 'Domän raderad.';
	$lang['strdomaindroppedbad'] = 'Misslyckades att radera domän.';
	$lang['strconfdropdomain'] = 'Är du säker på att du vill radera domänen "%s"?';
	$lang['strdomainneedsname'] = 'Du måste ange ett domännamn.';
	$lang['strdomaincreated'] = 'Domän skapad.';
	$lang['strdomaincreatedbad'] = 'Misslyckades att skapa domän.';
	$lang['strdomainaltered'] = 'Domän ändrad.';
	$lang['strdomainalteredbad'] = 'Misslyckades att ändra domän.';
	
	// Operators
	$lang['stroperator'] = 'Operand';
	$lang['stroperators'] = 'Operander';
	$lang['strshowalloperators'] = 'Visa alla operander';
	$lang['strnooperator'] = 'Hittade ingen operand.';
	$lang['strnooperators'] = 'Hittade inga operander.';
	$lang['strcreateoperator'] = 'Skapa operand';
	$lang['strleftarg'] = 'Arg Typ Vänster';
	$lang['strrightarg'] = 'Arg Typ Höger';
	$lang['strcommutator'] = 'Växlare';
	$lang['strnegator'] = 'Negerande';
	$lang['strrestrict'] = 'Spärra';
	$lang['strjoin'] = 'Slå ihop';
	$lang['strhashes'] = 'Hashtabeller';
	$lang['strmerges'] = 'Sammanslagningar';
	$lang['strleftsort'] = 'Sortera vänster';
	$lang['strrightsort'] = 'Sortera höger';
	$lang['strlessthan'] = 'Mindre än';
	$lang['strgreaterthan'] = 'Större än';
	$lang['stroperatorneedsname'] = 'Du måste namnge operanden.';
	$lang['stroperatorcreated'] = 'Operand skapad';
	$lang['stroperatorcreatedbad'] = 'Misslyckades att skapa operand.';
	$lang['strconfdropoperator'] = 'Är du säker på att du vill radera operanden "%s"?';
	$lang['stroperatordropped'] = 'Operand raderad.';
	$lang['stroperatordroppedbad'] = 'Misslyckades att radera operand.';

	// Casts
	$lang['strcasts'] = 'Typomvandlingar';
	$lang['strnocasts'] = 'Hittade inga typomvandlingar.';
	$lang['strsourcetype'] = 'Källtyp';
	$lang['strtargettype'] = 'Måltyp';
	$lang['strimplicit'] = 'Implicit';
	$lang['strinassignment'] = 'Tilldelat i';
	$lang['strbinarycompat'] = '(Binärt kompatibel)';
	
	// Conversions
	$lang['strconversions'] = 'Omkodningar';
	$lang['strnoconversions'] = 'Hittade inga omkodningar.';
	$lang['strsourceencoding'] = 'Källkodning';
	$lang['strtargetencoding'] = 'Målkodning';
	
	// Languages
	$lang['strlanguages'] = 'Språk';
	$lang['strnolanguages'] = 'Hittade inga språk.';
	$lang['strtrusted'] = 'Pålitlig(a)';
	
	// Info
	$lang['strnoinfo'] = 'Ingen information tillgänglig.';
	$lang['strreferringtables'] = 'Refererande tabeller';
	$lang['strparenttables'] = 'Ovanstående tabeller';
	$lang['strchildtables'] = 'Underliggande tabeller';

	// Aggregates
	$lang['straggregates'] = 'Sammanslagningar';
	$lang['strnoaggregates'] = 'Hittade inga sammanslagningar.';
	$lang['stralltypes'] = '(Alla typer)';
	
	// Operator Classes
	$lang['stropclasses'] = 'Op Klasser';
	$lang['strnoopclasses'] = 'Hittade inga operandklasser.';
	$lang['straccessmethod'] = 'Kopplingsmetod';
	
	// Stats and performance
	$lang['strrowperf'] = 'Radprestanda';
	$lang['strioperf'] = 'I/O Prestanda';
	$lang['stridxrowperf'] = 'Index Radprestanda';
	$lang['stridxioperf'] = 'Index I/O Prestanda';
	$lang['strpercent'] = '%';
	$lang['strsequential'] = 'Sekventiell';
	$lang['strscan'] = 'Scanna';
	$lang['strread'] = 'Läs';
	$lang['strfetch'] = 'Hämta';
	$lang['strheap'] = 'Bunt';
	$lang['strtoast'] = 'Bränn';
	$lang['strtoastindex'] = 'Bränn Index';
	$lang['strcache'] = 'Cache';
	$lang['strdisk'] = 'Disk';
	$lang['strrows2'] = 'Rader';

	// Miscellaneous
	$lang['strtopbar'] = '%s Körs på %s:%s -- Du är inloggad som användare "%s", %s';
	$lang['strtimefmt'] = 'jS M, Y g:iA';
	$lang['strhelp'] = 'Hjälp';

?>
