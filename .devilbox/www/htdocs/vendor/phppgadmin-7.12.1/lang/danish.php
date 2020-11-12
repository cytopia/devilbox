<?php

	/**
	 * Danish language file for phpPgAdmin.
	 * created by Arne Eckmann <bananstat@users.sourceforge.net>
	 */

	// Language and character set
	$lang['applang'] = 'Danish';
	$lang['applocale'] = 'da-DK';
	$lang['applangdir'] = 'ltr';

	// Welcome  
	$lang['strintro'] = 'Velkommen til phpPgAdmin.';
	$lang['strppahome'] = 'phpPgAdmins Hjemmeside';
	$lang['strpgsqlhome'] = 'PostgreSQLs Hjemmeside';
	$lang['strpgsqlhome_url'] = 'http://www.postgresql.org/';
	$lang['strlocaldocs'] = 'PostgreSQL Dokumentation (lokalt)';
	$lang['strreportbug'] = 'Rapporter fejl';
	$lang['strviewfaq'] = 'Ofte stillede spørgsmål';
	$lang['strviewfaq_url'] = 'http://phppgadmin.sourceforge.net/doku.php?id=faq';
	
	// Basic strings
	$lang['strlogin'] = 'Login';
	$lang['strloginfailed'] = 'Login mislykkedes';
	$lang['strlogindisallowed'] = 'Login forbudt';
	$lang['strserver'] = 'Server';
	$lang['strlogout'] = 'Log ud';
	$lang['strowner'] = 'Ejer';
	$lang['straction'] = 'Handling';
	$lang['stractions'] = 'Handlinger';
	$lang['strname'] = 'Navn';
	$lang['strdefinition'] = 'Definition';
	$lang['strproperties'] = 'Egenskaber';
	$lang['strbrowse'] = 'Bladre';
	$lang['strdrop'] = 'Fjern';
	$lang['strdropped'] = 'Fjernet';
	$lang['strnull'] = 'Ingenting';
	$lang['strnotnull'] = 'Ikke ingenting';
	$lang['strfirst'] = '<< Første';
	$lang['strlast'] = 'Sidste >>';
	$lang['strprev'] = 'Forgående';
	$lang['strfailed'] = 'Mislykkedes';
	$lang['strnext'] = 'Næste';
	$lang['strcreate'] = 'Opret';
	$lang['strcreated'] = 'Oprettet';
	$lang['strcomment'] = 'Kommentar';
	$lang['strlength'] = 'Længde';
	$lang['strdefault'] = 'Standardværdi';
	$lang['stralter'] = 'Ændre';
	$lang['strok'] = 'OK';
	$lang['strcancel'] = 'Fortryd';
	$lang['strsave'] = 'Gem';
	$lang['strreset'] = 'Nulstil';
	$lang['strinsert'] = 'Indsæt';
	$lang['strselect'] = 'Vælg';
	$lang['strdelete'] = 'Slet';
	$lang['strupdate'] = 'Opdater';
	$lang['strreferences'] = 'Referencer';
	$lang['stryes'] = 'Ja';
	$lang['strno'] = 'Nej';
	$lang['strtrue'] = 'Sand';
	$lang['strfalse'] = 'Falsk';
	$lang['stredit'] = 'Redigere';
	$lang['strcolumn'] = 'Kolonne';
	$lang['strcolumns'] = 'Kolonner';
	$lang['strrows'] = 'Række(r)';
	$lang['strrowsaff'] = 'Række(r) berørt.';
	$lang['strobjects'] = 'Objekt';
	$lang['strexample'] = 'f.eks.';
	$lang['strback'] = 'Tilbage';
	$lang['strqueryresults'] = 'Søgeresultat';
	$lang['strshow'] = 'Vise';
	$lang['strempty'] = 'Tøm';
	$lang['strlanguage'] = 'Sprog';
	$lang['strencoding'] = 'Kodning';
	$lang['strvalue'] = 'Værdi';
	$lang['strunique'] = 'Unik';
	$lang['strprimary'] = 'Primær';
	$lang['strexport'] = 'Eksportere';
	$lang['strimport'] = 'Importere';
	$lang['strsql'] = 'SQL';
	$lang['strgo'] = 'Udfør';
	$lang['stradmin'] = 'Admin';
	$lang['strvacuum'] = 'Ryd op';
	$lang['stranalyze'] = 'Analysere';
	$lang['strclusterindex'] = 'Klynge';
	$lang['strclustered'] = 'Klynget?';
	$lang['strreindex'] = 'Genindekser';
	$lang['strrun'] = 'Udfør';
	$lang['stradd'] = 'Tilføj';
	$lang['strevent'] = 'Hændelse';
	$lang['strwhere'] = 'Hvor';
	$lang['strinstead'] = 'Gør i stedet';
	$lang['strwhen'] = 'Når';
	$lang['strformat'] = 'Format';
	$lang['strdata'] = 'Data';
	$lang['strconfirm'] = 'Bekræft';
	$lang['strexpression'] = 'Udtryk';
	$lang['strellipsis'] = '...';
	$lang['strseparator'] = ': ';
	$lang['strexpand'] = 'Udvid';
	$lang['strcollapse'] = 'Klap sammen';
	$lang['strexplain'] = 'Forklar';
	$lang['strexplainanalyze'] = 'Forklar analyze';
	$lang['strfind'] = 'Søg';
	$lang['stroptions'] = 'Alternativ';
	$lang['strrefresh'] = 'Opdater';
	$lang['strdownload'] = 'Download';
	$lang['strdownloadgzipped'] = 'Download komprimeret som gzip';
	$lang['strinfo'] = 'Info';
	$lang['stroids'] = 'OID´er';
	$lang['stradvanced'] = 'Avanceret';
	$lang['strvariables'] = 'Variable';
	$lang['strprocess'] = 'Proces';
	$lang['strprocesses'] = 'Processer';
	$lang['strsetting'] = 'Indstilling';
	$lang['streditsql'] = 'Rediger SQL';
	$lang['strruntime'] = 'Total runtime: %s ms';
	$lang['strpaginate'] = 'Paginere resultater';
	$lang['struploadscript'] = 'eller upload et SQL script:';
	$lang['strstarttime'] = 'Starttid';
	$lang['strfile'] = 'Fil';
	$lang['strfileimported'] = 'Fil importeret.';
	$lang['strparameters'] = 'Parametrer';

	// Error handling
	$lang['strnotloaded'] = 'Du har ikke ikke indlagt korrekt databaseunderstøttelse i din PHP-installation.';
	$lang['strbadconfig'] = 'Din config.inc.php er ikke opdateret. Du er nødt til at genetablere den fra den nye config.inc.php-dist.';
	$lang['strbadencoding'] = 'Det lykkedes ikke at sætte klientkodning i databasen.';
	$lang['strbadSchema'] = 'Forkert Skema angivet.';
	$lang['strinstatement'] = 'I påstanden:';
	$lang['strsqlerror'] = 'SQL fejl:';
	$lang['strinvalidparam'] = 'Ugyldig scriptparam.';
	$lang['strnodata'] = 'Ingen rækker fundet.';
	$lang['strnoobjects'] = 'Ingen objekter fundet.';
	$lang['strrownotunique'] = 'Denne række har ingen unik nøgle.';

	// Tables
	$lang['strtable'] = 'Tabel';
	$lang['strtables'] = 'Tabeller';
	$lang['strshowalltables'] = 'Vis alle tabeller';
	$lang['strnotables'] = 'Fandt ingen tabeller.';
	$lang['strnotable'] = 'Fandt ingen tabel.';
	$lang['strcreatetable'] = 'Opret tabel';
	$lang['strtablename'] = 'Tabelnavn';
	$lang['strtableneedsname'] = 'Tabel skal have et navn.';
	$lang['strtableneedsfield'] = 'Der skal mindst være et felt.';
	$lang['strtableneedscols'] = 'tabeller kræver et tilladeligt antal kolonner.';
	$lang['strtablecreated'] = 'Tabel oprettet.';
	$lang['strtablecreatedbad'] = 'Tabeloprettelse mislykkedes.';
	$lang['strconfdroptable'] = 'Er du sikker på at du vil fjerne tabellen "%s"?';
	$lang['strtabledropped'] = 'Tabel fjernet.';
	$lang['strinsertrow'] = 'Indsæt række';
	$lang['strtabledroppedbad'] = 'Det lykkedes ikke at fjerne tabellen.';
	$lang['strrowinserted'] = 'Række indsat.';
	$lang['strconfemptytable'] = 'Er du sikker på at du vil tømme tabellen "%s"?';
	$lang['strrowupdated'] = 'Række opdateret.';
	$lang['strrowinsertedbad'] = 'Det lykkedes ikke indsætte række.';
	$lang['strtableemptied'] = 'Tabellen tømt.';
	$lang['strrowupdatedbad'] = 'Opdatering af række mislykkedes.';
	$lang['streditrow'] = 'Rediger række';
	$lang['strrowdeleted'] = 'Række slettet.';
	$lang['strrowdeletedbad'] = 'Sletning af række mislykkedes.';
	$lang['strfield'] = 'Felt';
	$lang['strconfdeleterow'] = 'Er du sikker på at du vil slette denne række?';
	$lang['strnumfields'] = 'Antal felter';
	$lang['strsaveandrepeat'] = 'Gem & Fortsæt';
	$lang['strtableemptiedbad'] = 'Det lykkedes ikke at tømme tabellen';
	$lang['strdeleterow'] = 'Slet række';
	$lang['strfields'] = 'Felt';
	$lang['strfieldneedsname'] = 'Feltet skal have et navn';
	$lang['strcolumndropped'] = 'Kolonne fjernet.';
	$lang['strselectallfields'] = 'Vælg alle felter';
	$lang['strselectneedscol'] = 'Der skal vælges mindst een kolonne';
	$lang['strselectunary'] = 'Unary operander kan ikke have værdien.';
	$lang['strcolumnaltered'] = 'Kolonne ændret.';
	$lang['straltercolumn'] = 'Ændre kolonne';
	$lang['strcolumnalteredbad'] = 'Det lykkes ikke at ændre kolonne.';
	$lang['strconfdropcolumn'] = 'Er du sikker på at du vil fjerne kolonne "%s" fra tabel "%s"?';
	$lang['strcolumndroppedbad'] = 'Det lykkedes ikke at fjerne kolonne.';
	$lang['straddcolumn'] = 'Tilføj kolonne';
	$lang['strcolumnadded'] = 'Kolonne tiføjet.';
	$lang['strcolumnaddedbad'] = 'Det lykkedes ikke at tilføje kolonne.';
	$lang['strcascade'] = 'KASKAD';
	$lang['strdataonly'] = 'Udelukkende data';
	$lang['strtablealtered'] = 'Tabel ændret.';
	$lang['strtablealteredbad'] = 'Det lykkedes ikke at ændre tabel.';
	$lang['strestimatedrowcount'] = 'Anslået antal rækker';
	
	// Users
	$lang['struser'] = 'Bruger';
	$lang['strusers'] = 'Brugere';
	$lang['strusername'] = 'Brugernavn';
	$lang['strpassword'] = 'Password';
	$lang['strsuper'] = 'Superbruger?';
	$lang['strcreatedb'] = 'Opret database?';
	$lang['strexpires'] = 'Udløber';
	$lang['strsessiondefaults'] = 'Sessionsindstillinger';
	$lang['strnewname'] = 'Nyt navn';
	$lang['strnousers'] = 'Der blev ikke fundet nogen brugere.';
	$lang['strrename'] = 'Omdøb';
	$lang['struserrenamed'] = 'Brugernavn ændret.';
	$lang['struserrenamedbad'] = 'Det lykkedes ikke at omdøbe bruger.';
	$lang['struserupdated'] = 'Bruger opdateret.';
	$lang['struserupdatedbad'] = 'Opdatering af bruger mislykkedes.';
	$lang['strshowallusers'] = 'Vis alle brugere';
	$lang['strcreateuser'] = 'Opret bruger';
	$lang['struserneedsname'] = 'Bruger behøver et navn.';
	$lang['strconfdropuser'] = 'Er du sikker på at du vil slette brugeren "%s"?';
	$lang['strusercreated'] = 'Bruger oprettet.';
	$lang['strusercreatedbad'] = 'Oprettelse af bruger mislykkedes.';
	$lang['struserdropped'] = 'Bruger slettet.';
	$lang['struserdroppedbad'] = 'Sletning af bruger mislykkedes.';
	$lang['straccount'] = 'Konto';
	$lang['strchangepassword'] = 'Ændre password';
	$lang['strpasswordchanged'] = 'Password ændret.';
	$lang['strpasswordchangedbad'] = 'Ændring af password mislykkedes.';
	$lang['strpasswordshort'] = 'Password er for kort.';
	$lang['strpasswordconfirm'] = 'Password er forskellig fra bekræftelsen.';

	// Groups
	$lang['strgroup'] = 'Gruppe';
	$lang['strgroups'] = 'Grupper';
	$lang['strnogroup'] = 'Gruppe blev ikke fundet.';
	$lang['strnogroups'] = 'Ingen grupper blev fundet.';
	$lang['strcreategroup'] = 'Opret gruppe';
	$lang['strshowallgroups'] = 'Vis alle grupper';
	$lang['strgroupneedsname'] = 'Gruppen skal have et navn.';
	$lang['strgroupcreated'] = 'Gruppe oprettet.';
	$lang['strgroupdropped'] = 'Gruppe slettet.';
	$lang['strgroupcreatedbad'] = 'Oprettelse af gruppe mislykkedes.';	
	$lang['strconfdropgroup'] = 'Er du sikker på at du vil slette gruppe "%s"?';
	$lang['strgrant'] = 'Tildel';
	$lang['strgranted'] = 'Privilegier ændret.';
	$lang['strgroupdroppedbad'] = 'Det lykkedes ikke at fjerne gruppe.';
	$lang['straddmember'] = 'Tilføj medlem';
	$lang['strmemberadded'] = 'Medlem tilføjet.';
	$lang['strmemberaddedbad'] = 'Det lykkedes ikke at tilføje medlem.';
	$lang['strdropmember'] = 'Fjern medlem';
	$lang['strconfdropmember'] = 'Er du sikker på at du vil fjerne medlem "%s" fra gruppen "%s"?';
	$lang['strmemberdropped'] = 'Medlem fjernet.';
	$lang['strmemberdroppedbad'] = 'Det lykkedes ikke at fjerne medlem.';
	
	// Privileges	
	$lang['strprivilege'] = 'Rettighed';
	$lang['strprivileges'] = 'Rettigheder';
	$lang['strnoprivileges'] = 'Dette objekt har standard ejerrettigheder.';
	$lang['strmembers'] = 'Medlemmer';
	$lang['strrevoke'] = 'Inddrag';
	$lang['strgrantbad'] = 'Du skal angive mindst en bruger eller gruppe og mindst et privilegie.';
	$lang['strgrantfailed'] = 'Ændring af rettigheder mislykkedes.';
	$lang['stralterprivs'] = 'Ændre rettigheder';
	$lang['strdatabase'] = 'Database';
	$lang['strdatabasedropped'] = 'Database fjernet.';
	$lang['strdatabases'] = 'Databaser';
	$lang['strentersql'] = 'Indtast SQL til eksekvering :';
	$lang['strgrantor'] = 'Tilladelsesudsteder';
	$lang['strasterisk'] = '*';

	// Databases
	$lang['strdatabase'] = 'Database';
	$lang['strdatabases'] = 'Databaser';		
	$lang['strshowalldatabases'] = 'Vis alle databaser';
	$lang['strnodatabase'] = 'Database blev ikke fundet.';
	$lang['strnodatabases'] = 'Der blev ikke fundet nogen databaser.';
	$lang['strcreatedatabase'] = 'Opret database';
	$lang['strdatabasename'] = 'Databasenavn';
	$lang['strdatabaseneedsname'] = 'Databasen skal have et navn.';
	$lang['strdatabasecreated'] = 'Database oprettet.';
	$lang['strdatabasecreatedbad'] = 'Oprettelse af database mislykkedes.';	
	$lang['strconfdropdatabase'] = 'Er du sikker på at du vil fjerne database "%s"?';
	$lang['strdatabasedroppedbad'] = 'Fjernelse af database mislykkedes.';
	$lang['strentersql'] = 'Enter the SQL to execute below:';
	$lang['strsqlexecuted'] = 'SQL-kommando udført.';
	$lang['strvacuumgood'] = 'Vacuum udført.';
	$lang['strvacuumbad'] = 'Vacuum mislykkedes.';
	$lang['stranalyzegood'] = 'Analysen lykkedes.';
	$lang['stranalyzebad'] = 'Analysen mislykkedes.';
	$lang['strreindexgood'] = 'Reindeksering komplet.';
	$lang['strreindexbad'] = 'Reindeksering slog fejl.';
	$lang['strfull'] = 'Fuld';
	$lang['strfreeze'] = 'Fastfrys';
	$lang['strforce'] = 'Force';
	$lang['strsignalsent'] = 'Signal sendt.';
	$lang['strsignalsentbad'] = 'Afsendelse af signal mislykkedes.';
	$lang['strallobjects'] = 'Alle objekter';
	$lang['strstructureonly'] = 'Kun struktur';
	$lang['strstructureanddata'] = 'Struktur og data';
	
	// Views
	$lang['strview'] = 'View';
	$lang['strviews'] = 'Views';
	$lang['strshowallviews'] = 'Vis alle views';
	$lang['strnoview'] = 'Ingen view blev fundet.';
	$lang['strnoviews'] = 'Ingen views blev fundet.';
	$lang['strcreateview'] = 'Opret view';
	$lang['strviewname'] = 'Navn på view';
	$lang['strviewneedsname'] = 'View skal have et navn.';
	$lang['strviewneedsdef'] = 'Du skal angive en defintion for view.';
	$lang['strviewcreated'] = 'View oprettet.';
	$lang['strviewcreatedbad'] = 'Oprettelse af View mislykkedes.';
	$lang['strconfdropview'] = 'Er du sikker på at du vil fjerne view "%s"?';
	$lang['strviewdropped'] = 'View fjernet.';
	$lang['strviewdroppedbad'] = 'Fjernelse af view mislykkedes.';
	$lang['strviewupdated'] = 'View opdateret.';
	$lang['strviewupdatedbad'] = 'Opdatering af view mislykkedes.';
	$lang['strviewlink'] = 'Linking Keys';
	$lang['strviewconditions'] = 'Yderligere vilkår';
	$lang['strcreateviewwiz'] = 'Opret view med hjælp af wizard';

	// Sequences
	$lang['strsequence'] = 'Sekvens';
	$lang['strsequences'] = 'Sekvenser';
	$lang['strshowallsequences'] = 'Vis alle sekvenser';
	$lang['strnosequence'] = 'Sekvens blev ikke fundet.';
	$lang['strnosequences'] = 'Ingen sekvenser blev fundet.';
	$lang['strcreatesequence'] = 'Opret sekvens';
	$lang['strlastvalue'] = 'Seneste værdi';
	$lang['strincrementby'] = 'Øg med';
	$lang['strstartvalue'] = 'Startværdi';
	$lang['strmaxvalue'] = 'Største værdi';
	$lang['strminvalue'] = 'Mindste værdi';
	$lang['strcachevalue'] = 'Cachens værdi';
	$lang['strlogcount'] = 'Log count';
	$lang['striscycled'] = 'Is cycled?';
	$lang['strsequenceneedsname'] = 'Sekvens skal have et navn.';
	$lang['strsequencecreated'] = 'Sekvens oprettet.';
	$lang['strsequencecreatedbad'] = 'Oprettelse af sekvens mislykkedes.'; 
	$lang['strconfdropsequence'] = 'Er du sikker på at du vil fjerne sekvensen "%s"?';
	$lang['strsequencedropped'] = 'Sekvensen fjernet.';
	$lang['strsequencedroppedbad'] = 'Fjernelse af sekvens mislykkedes.';

	// Indexes
	$lang['strindex'] = 'Indeks';
	$lang['strindexes'] = 'Indekser';
	$lang['strindexname'] = 'Indeksnavn';
	$lang['strshowallindexes'] = 'Vis alle indeks';
	$lang['strnoindex'] = 'Ingen indeks blev fundet.';
	$lang['strsequencereset'] = 'Nulstil sekvens.';
	$lang['strsequenceresetbad'] = 'Nulstilling af sekvens mislykkedes.';
	$lang['strnoindexes'] = 'Ingen indeks blev fundet.';
	$lang['strcreateindex'] = 'Opret indeks';
	$lang['strindexname'] = 'Indeksnavn';
	$lang['strtabname'] = 'Tabelnavn';
	$lang['strcolumnname'] = 'Kolonnenavn';
	$lang['strindexneedsname'] = 'Indeks skal have et navn';
	$lang['strindexneedscols'] = 'Indeks kræveret gyldigt antal kolonner.';
	$lang['strindexcreated'] = 'Indeks oprettet';
	$lang['strindexcreatedbad'] = 'Oprettelse af indeks mislykkedes.';
	$lang['strconfdropindex'] = 'Er du sikker på at du vil fjerne indeks "%s"?';
	$lang['strindexdropped'] = 'Indeks fjernet.';
	$lang['strindexdroppedbad'] = 'Det lykkedes ikke at fjerne indeks.';
	$lang['strkeyname'] = 'Nøglebetegnelse';
	$lang['struniquekey'] = 'Unik nøgle';
	$lang['strprimarykey'] = 'Primærnøgle';
 	$lang['strindextype'] = 'Indekstype';
	$lang['strindexname'] = 'Indeksnavn';
	$lang['strtablecolumnlist'] = 'Tabelkolonner';
	$lang['strindexcolumnlist'] = 'Indekskolonner';
	$lang['strconfcluster'] = 'Are you sure you want to cluster "%s"?';
	$lang['strclusteredgood'] = 'Cluster complete.';
	$lang['strclusteredbad'] = 'Cluster failed.';

	// Rules
	$lang['strrules'] = 'Regler';
	$lang['strrule'] = 'Regel';
	$lang['strshowallrules'] = 'Vis alle regler';
	$lang['strnorule'] = 'Regel blev ikke fundet.';
	$lang['strnorules'] = 'Ingen regler blev fundet.';
	$lang['strcreaterule'] = 'Opret regel';
	$lang['strrulename'] = 'Regelnavn';
	$lang['strruleneedsname'] = 'Regel skal have et navn.';
	$lang['strrulecreated'] = 'Regel oprettet.';
	$lang['strrulecreatedbad'] = 'Oprettelse af regel mislykkedes.';
	$lang['strconfdroprule'] = 'Er du sikker på at du fjerne regel "%s" for "%s"?';
	$lang['strruledropped'] = 'Regel fjernet.';
	$lang['strruledroppedbad'] = 'Det lykkedes ikke at fjerne regel.';

	// Constraints
	$lang['strconstraints'] = 'Afgrænsninger';
	$lang['strshowallconstraints'] = 'Vis alle afgrænsninger';
	$lang['strnoconstraints'] = 'Der blev ikke fundet nogen afgrænsninger.';
	$lang['strcreateconstraint'] = 'Opret afgrænsning';
	$lang['strconstraintcreated'] = 'Afgrænsning oprettet.';
	$lang['strconstraintcreatedbad'] = 'Det lykkedes ikke at oprette afgrænsning.';
	$lang['strconfdropconstraint'] = 'Er du sikker på at du vil fjerne afgrænsning "%s" for "%s"?';
	$lang['strconstraintdropped'] = 'Afgrænsning fjernet.';
	$lang['strconstraintdroppedbad'] = 'Det lykkedes ikke at fjerne afgrænsning.';
	$lang['straddcheck'] = 'Tilføj check';
	$lang['strcheckneedsdefinition'] = 'Check afgrænsning skal defineres.';
	$lang['strcheckadded'] = 'Check tilføjet.';
	$lang['strcheckaddedbad'] = 'Det lykkedes ikke at tilføje check.';
	$lang['straddpk'] = 'Tilføj primærnøgle';
	$lang['strpkneedscols'] = 'Primærnøgle kræver mindst en kolonne.';
	$lang['strpkadded'] = 'Primærnøgle tilføjet.';
	$lang['strpkaddedbad'] = 'Tilføjelse af primærnøgle mislykkedes.';
	$lang['stradduniq'] = 'Tilføj unik nøgle';
	$lang['struniqneedscols'] = 'Unik nøgle kræver mindst een kolonne.';
	$lang['struniqadded'] = 'Unik nøgle tilføjet.';
	$lang['struniqaddedbad'] = 'Tilføjelse af unik nøgle mislykkedes.';
	$lang['straddfk'] = 'Tilføj ekstern nøgle';
	$lang['strfkneedscols'] = 'Ekstern nøgle kræver mindst een kolonne.';
	$lang['strfkneedstarget'] = 'Ekstern nøgle kræver en måltabel.';
	$lang['strfkadded'] = 'Ekstern nøgle tilføjet.';
	$lang['strfkaddedbad'] = 'Tilføjelse af ekstern nøgle mislykkedes.';
	$lang['strfktarget'] = 'Måltabel';
	$lang['strfkcolumnlist'] = 'Kolonner i nøgle';
	$lang['strondelete'] = 'VED SLETNING';
	$lang['stronupdate'] = 'VED OPDATERING';

	// Functions
	$lang['strfunction'] = 'Funktion';
	$lang['strfunctions'] = 'Funktioner';
	$lang['strshowallfunctions'] = 'Vis alle funktioner';
	$lang['strnofunction'] = 'Hittade ingen funktion.';
	$lang['strnofunctions'] = 'Hittade inga funktioner.';
	$lang['strcreatefunction'] = 'Opret funktion';
	$lang['strcreateplfunction'] = 'Opret SQL/PL funktion';
	$lang['strcreateinternalfunction'] = 'Opret intern funktion';
	$lang['strcreatecfunction'] = 'Opret C funktion';
	$lang['strfunctionname'] = 'Funktionsnavn';
	$lang['strreturns'] = 'Tilbage';
	$lang['strarguments'] = 'Argumenter';
	$lang['strfunctionneedsname'] = 'Funktionen skal have et navn.';
	$lang['strfunctionneedsdef'] = 'Funktionen skal defineres.';
	$lang['strfunctioncreated'] = 'Funktion oprettet.';
	$lang['strfunctioncreatedbad'] = 'Oprettelse af funktion mislykkedes.';
	$lang['strconfdropfunction'] = 'Er du sikker på at du vil slette funktionen "%s"?';
	$lang['strproglanguage'] = 'Programmeringssprog';
	$lang['strfunctiondropped'] = 'Funktionen fjernet.';
	$lang['strfunctiondroppedbad'] = 'Fjernelse af funktionen mislykkedes.';
	$lang['strfunctionupdated'] = 'Funktion opdateret.';
	$lang['strfunctionupdatedbad'] = 'Opdatering af funktion mislykkedes.';

	// Triggers
	$lang['strtrigger'] = 'Trigger';
	$lang['strtriggers'] = 'Triggere';
	$lang['strshowalltriggers'] = 'Vis alle triggere';
	$lang['strnotrigger'] = 'Hittede ingen trigger.';
	$lang['strnotriggers'] = 'Hittede ingen trigger.';
	$lang['strcreatetrigger'] = 'Opret trigger';
	$lang['strtriggerneedsname'] = 'Trigger skal have et navn.';
	$lang['strtriggerneedsfunc'] = 'Du skal specificere en funktion for trigger.';
	$lang['strtriggercreated'] = 'Trigger oprettet.';
	$lang['strtriggerdropped'] = 'Trigger fjernet.';
	$lang['strtriggercreatedbad'] = 'Det lykkedes ikke at oprette trigger.';
	$lang['strconfdroptrigger'] = 'Er du sikker på at du vil fjerne trigger "%s" på "%s"?';
	$lang['strtriggerdroppedbad'] = 'Det lykkedes ikke at fjerne trigger.';
	

	
	$lang['strstorage'] = 'Lagring';
	$lang['strtriggeraltered'] = 'Trigger ændret.';
	$lang['strtriggeralteredbad'] = 'Det lykkedes ikke at ændre trigger.';
	
	// Types
	$lang['strtype'] = 'Type';
	$lang['strtypes'] = 'Typer';
	$lang['strshowalltypes'] = 'Vis alle typer';
	$lang['strnotype'] = 'Typen blev ikke fundet.';
	$lang['strnotypes'] = 'Ingen typer fundet.';

	$lang['strtypeneedslen'] = 'Du skal angive typens længde.';	
	
	$lang['strcreatetype'] = 'Opret type';
	$lang['strtypename'] = 'Navn på typen';
	$lang['strinputfn'] = 'Input funktion';
	$lang['stroutputfn'] = 'Output funktion';
	$lang['strpassbyval'] = 'Passed by val?';
	$lang['stralignment'] = 'Justering';
	$lang['strelement'] = 'Element';
	$lang['strdelimiter'] = 'Begrænser';
	$lang['strtypeneedsname'] = 'Typen skal have et navn.';
	$lang['strtypecreated'] = 'Type oprettet';
	$lang['strtypecreatedbad'] = 'Det lykkedes ikke at oprette type.';
	$lang['strconfdroptype'] = 'Er du sikker på at du vil fjerne typen "%s"?';
	$lang['strtypedropped'] = 'Typen fjernet.';
	$lang['strtypedroppedbad'] = 'Det lykkedes ikke at fjerne typen.';

	// Schemas
	$lang['strschema'] = 'Skema';
	$lang['strschemas'] = 'Skemaer';
	$lang['strshowallschemas'] = 'Vis alle skemaer';
	$lang['strnoschema'] = 'Der blev ikke fundet noget skema.';
	$lang['strnoschemas'] = 'Der blev ikke fundet nogen skemaer.';
	$lang['strcreateschema'] = 'Opret skema';
	$lang['strschemaname'] = 'Skemanavn';
	$lang['strschemaneedsname'] = 'Skema skal have et navn.';
	$lang['strschemacreated'] = 'Skema oprettet';
	$lang['strschemacreatedbad'] = 'Det lykkedes ikke at oprette skema.';
	$lang['strconfdropschema'] = 'Er du sikker på, at du vil fjerne skemaet "%s"?';
	$lang['strschemadropped'] = 'Skema fjernet.';
	$lang['strschemadroppedbad'] = 'Det lykkedes ikka at fjerne skema.';

	// Reports
	$lang['strtopbar'] = '%s kører på %s:%s -- Du er logged ind som bruger "%s"';
	$lang['strtimefmt'] = 'jS M, Y g:iA';
	
	// Domains
	$lang['strdomain'] = 'Domæne';
	$lang['strdomains'] = 'Domæner';
	$lang['strshowalldomains'] = 'Vis alle domæner';
	$lang['strnodomains'] = 'Ingen domæner blev fundet.';
	$lang['strcreatedomain'] = 'Opret domæne';
	$lang['strdomaindropped'] = 'Domæne fjernet.';
	$lang['strdomaindroppedbad'] = 'Det lykkedes ikke at fjerne domæne.';
	$lang['strconfdropdomain'] = 'Er du sikker på at du vil fjerne domænet "%s"?';
	$lang['strdomainneedsname'] = 'Du skal indtaste et domænenavn.';
	$lang['strdomaincreated'] = 'Domæne oprettet.';
	$lang['strdomaincreatedbad'] = 'Det lykkedes ikke at oprette et domæne.';
	$lang['strdomainaltered'] = 'Domæne ændret.';
	$lang['strdomainalteredbad'] = 'Det lykkedes ikke at ændre domæne.';
	
	// Operators
	$lang['stroperator'] = 'Operator';
	$lang['stroperators'] = 'Operatorer';
	$lang['strshowalloperators'] = 'Vis alle operatorer';
	$lang['strnooperator'] = 'Operator blev ikke.';
	$lang['strnooperators'] = 'Der blev ikke fundet nogen operatorer.';
	$lang['strcreateoperator'] = 'Opret operator';
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
	$lang['stroperatorneedsname'] = 'Operator skal have et navn.';
	$lang['stroperatorcreated'] = 'Operator oprettet';
	$lang['stroperatorcreatedbad'] = 'Oprettelse af operator mislykkedes.';
	$lang['strconfdropoperator'] = 'Er du sikker på, at du vil fjerne operator "%s"?';
	$lang['stroperatordropped'] = 'Operator fjernet.';
	$lang['stroperatordroppedbad'] = 'Fjernelse af operator mislykkedes.';

	// Casts
	$lang['strcasts'] = 'Typekonverteringer';
	$lang['strnocasts'] = 'Ingen typekonverteringer fundet.';
	$lang['strsourcetype'] = 'Kildetype';
	$lang['strtargettype'] = 'Måltype';
	$lang['strimplicit'] = 'Implicit';
	$lang['strinassignment'] = 'Tildelt i';
	$lang['strbinarycompat'] = '(Binært kompatibel)';
	
	// Conversions
	$lang['strconversions'] = 'Konverteringer';
	$lang['strnoconversions'] = 'Ingen konverteringer fundet.';
	$lang['strsourceencoding'] = 'Kildekodning';
	$lang['strtargetencoding'] = 'Målkodning';
	
	// Languages
	$lang['strlanguages'] = 'Sprog';
	$lang['strnolanguages'] = 'Der blev ikke fundet noget sprog.';
	$lang['strtrusted'] = 'Pålidelig(e)';
	
	// Info
	$lang['strnoinfo'] = 'Ingen tilgængelig information.';
	$lang['strreferringtables'] = 'Refererende tabeller';
	$lang['strparenttables'] = 'Overordnede tabeller';
	$lang['strchildtables'] = 'Underordnede tabeller';

	// Aggregates
	$lang['straggregates'] = 'Sammenlægninger';
	$lang['strnoaggregates'] = 'Ingen sammenlægninger fundet.';
	$lang['stralltypes'] = '(Alle typer)';
	
	// Operator Classes
	$lang['stropclasses'] = 'Operatorklasser';
	$lang['strnoopclasses'] = 'Ingen Operatorklasser fundet.';
	$lang['straccessmethod'] = 'Tilgangsmetode';
	
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

		// Tablespaces
	$lang['strtablespace'] = 'Tabelområde';
	$lang['strtablespaces'] = 'Tabelområder';
	$lang['strshowalltablespaces'] = 'Vis alle tabelområder';
	$lang['strnotablespaces'] = 'Ingen tabelområder fundet.';
	$lang['strcreatetablespace'] = 'Opret tabelområder';
	$lang['strlocation'] = 'Location';
	$lang['strtablespaceneedsname'] = 'Tabelområdet skal have et navn.';
	$lang['strtablespaceneedsloc'] = 'Du skal angive hvilken mappe tabelområdet skal oprettes i.';
	$lang['strtablespacecreated'] = 'Tabelområde oprettet.';
	$lang['strtablespacecreatedbad'] = 'Oprettelse af tabelområde lykkedes ikke.';
	$lang['strconfdroptablespace'] = 'Er du sikker på, at du vil fjerne tabelområde "%s"?';
	$lang['strtablespacedropped'] = 'Tabelområde fjernet.';
	$lang['strtablespacedroppedbad'] = 'Fjernelse af tabelområde lykkedes ikke.';
	$lang['strtablespacealtered'] = 'Tabelområde ændret.';
	$lang['strtablespacealteredbad'] = 'Ændring af tabelområde lykkedes ikke.';
	
	// Miscellaneous
	$lang['strtopbar'] = '%s Kører på %s:%s -- Du er logged ind som bruger "%s", %s';
	$lang['strtimefmt'] = 'jS M, Y g:iA';
	$lang['strhelp'] = 'Hjælp';
	$lang['strhelpicon'] = '?';

?>
