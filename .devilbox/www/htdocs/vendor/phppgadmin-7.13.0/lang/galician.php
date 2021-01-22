<?php

	/**
	 * Galician language file for phpPgAdmin.
	 * Tradución ao galego do phpPgAdmin.
	 *
	 * Maintainer:
	 * Mantedor:
	 *	Adrián Chaves Fernández (Gallaecio) <adriyetichaves@gmail.com>
	 *	Proxecto Trasno, <proxecto@trasno.net>.
	 *
	 *
	 *	Comentarios sobre a tradución:
	 *	- Escolleuse «eliminar» como tradución para “drop”, e «borrar» como tradución para
	 *	“delete”.
	 *	- Fixéronse certas escollas de vocabulario: “vacuum” → «purgación» (aconsellada por
	 * 	Leandro Regueiro), “cluster” → «contentrado».
	 *
	 */

	// Language and character set
	$lang['applang'] = 'Galego';
	$lang['applocale'] = 'gl-ES';
	$lang['applangdir'] = 'ltr';

	// Welcome
	$lang['strintro'] = 'Benvida ou benvido ao phpPgAdmin.';
	$lang['strppahome'] = 'Sitio web do phpPgAdmin';
	$lang['strpgsqlhome'] = 'Sitio web de PostgreSQL';
	$lang['strpgsqlhome_url'] = 'http://www.postgresql.org/';
	$lang['strlocaldocs'] = 'Documentación de PostgreSQL (local)';
	$lang['strreportbug'] = 'Informar dun erro';
	$lang['strviewfaq'] = 'Ver as preguntas máis frecuentes en liña';
	$lang['strviewfaq_url'] = 'http://phppgadmin.sourceforge.net/doku.php?id=faq';

	// Basic strings
	$lang['strlogin'] = 'Identificarse';
	$lang['strloginfailed'] = 'Non se puido levar a cabo a identificación.';
	$lang['strlogindisallowed'] = 'A identificación está desactivada por motivos de seguridade.';
	$lang['strserver'] = 'Servidor';
	$lang['strservers'] = 'Servidores';
	$lang['strgroupservers'] = 'Servidores no grupo «%s»';
	$lang['strallservers'] = 'Todos os servidores';
	$lang['strintroduction'] = 'Introdución';
	$lang['strhost'] = 'Enderezo IP';
	$lang['strport'] = 'Porto';
	$lang['strlogout'] = 'Saír';
	$lang['strowner'] = 'Propietario';
	$lang['straction'] = 'Acción';
	$lang['stractions'] = 'Accións';
	$lang['strname'] = 'Nome';
	$lang['strdefinition'] = 'Definición';
	$lang['strproperties'] = 'Propiedades';
	$lang['strbrowse'] = 'Navegar';
	$lang['strenable'] = 'Activar';
	$lang['strdisable'] = 'Desactivar';
	$lang['strdrop'] = 'Eliminar';
	$lang['strdropped'] = 'Eliminada';
	$lang['strnull'] = 'Nulo';
	$lang['strnotnull'] = 'Non nulo';
	$lang['strprev'] = '< Anterior';
	$lang['strnext'] = 'Seguinte >';
	$lang['strfirst'] = '« Principio';
	$lang['strlast'] = 'Final »';
	$lang['strfailed'] = 'Fallou';
	$lang['strcreate'] = 'Crear';
	$lang['strcreated'] = 'Creada';
	$lang['strcomment'] = 'Comentario';
	$lang['strlength'] = 'Lonxitude';
	$lang['strdefault'] = 'Predeterminado';
	$lang['stralter'] = 'Cambiar';
	$lang['strok'] = 'Aceptar';
	$lang['strcancel'] = 'Cancelar';
	$lang['strkill'] = 'Matar';
	$lang['strac'] = 'Activar o completado automático';
	$lang['strsave'] = 'Gardar';
	$lang['strreset'] = 'Restablecer';
	$lang['strrestart'] = 'Reiniciar';
	$lang['strinsert'] = 'Inserir';
	$lang['strselect'] = 'Seleccionar';
	$lang['strdelete'] = 'Borrar';
	$lang['strupdate'] = 'Actualizar';
	$lang['strreferences'] = 'Fai referencia a';
	$lang['stryes'] = 'Si';
	$lang['strno'] = 'Non';
	$lang['strtrue'] = 'CERTO';
	$lang['strfalse'] = 'FALSO';
	$lang['stredit'] = 'Editar';
	$lang['strcolumn'] = 'Columna';
	$lang['strcolumns'] = 'Columnas';
	$lang['strrows'] = 'fila(s)';
	$lang['strrowsaff'] = 'fila(s) afectadas.';
	$lang['strobjects'] = 'obxecto(s)';
	$lang['strback'] = 'Volver';
	$lang['strqueryresults'] = 'Resultados da consulta';
	$lang['strshow'] = 'Amosar';
	$lang['strempty'] = 'Baleiro';
	$lang['strlanguage'] = 'Lingua';
	$lang['strencoding'] = 'Codificación';
	$lang['strvalue'] = 'Valor';
	$lang['strunique'] = 'Único';
	$lang['strprimary'] = 'Primaria';
	$lang['strexport'] = 'Exportar';
	$lang['strimport'] = 'Importar';
	$lang['strallowednulls'] = 'Permitir valores nulos';
	$lang['strbackslashn'] = '\N';
	$lang['stremptystring'] = 'Cadea ou campo baleiro';
	$lang['strsql'] = 'SQL';
	$lang['stradmin'] = 'Administración';
	$lang['strvacuum'] = 'Purgar';
	$lang['stranalyze'] = 'Analizar';
	$lang['strclusterindex'] = 'Concentrar';
	$lang['strclustered'] = 'Concentrada?';
	$lang['strreindex'] = 'Indexar';
	$lang['strexecute'] = 'Executar';
	$lang['stradd'] = 'Engadir';
	$lang['strevent'] = 'Evento';
	$lang['strwhere'] = 'Onde';
	$lang['strinstead'] = 'En vez de iso';
	$lang['strwhen'] = 'Cando';
	$lang['strformat'] = 'Formato';
	$lang['strdata'] = 'Datos';
	$lang['strconfirm'] = 'Confirmar';
	$lang['strexpression'] = 'Expresión';
	$lang['strellipsis'] = '...';
	$lang['strseparator'] = ': ';
	$lang['strexpand'] = 'Expandir';
	$lang['strcollapse'] = 'Colapsar';
	$lang['strfind'] = 'Buscar';
	$lang['stroptions'] = 'Opcións';
	$lang['strrefresh'] = 'Actualizar';
	$lang['strdownload'] = 'Descargar';
	$lang['strdownloadgzipped'] = 'Descargar comprimida con gzip';
	$lang['strinfo'] = 'Info';
	$lang['stroids'] = 'OIDs';
	$lang['stradvanced'] = 'Avanzado';
	$lang['strvariables'] = 'Variables';
	$lang['strprocess'] = 'Proceso';
	$lang['strprocesses'] = 'Procesos';
	$lang['strsetting'] = 'Configuración';
	$lang['streditsql'] = 'Editar SQL';
	$lang['strruntime'] = 'Tempo total funcionando: %s ms';
	$lang['strpaginate'] = 'Amosar os resultados por páxinas';
	$lang['struploadscript'] = 'ou cargue un guión en SQL:';
	$lang['strstarttime'] = 'Hora de inicio';
	$lang['strfile'] = 'Ficheiro';
	$lang['strfileimported'] = 'Importouse o ficheiro.';
	$lang['strtrycred'] = 'Utilizar estas credenciais para todos os servidores';
	$lang['strconfdropcred'] = 'Por motivos de seguridade, ao desconectarse destruirase a información compartida sobre a súa identidade. Está seguro de que quere desconectarse?';
	$lang['stractionsonmultiplelines'] = 'Accións en varias liñas';
	$lang['strselectall'] = 'Marcar todo';
	$lang['strunselectall'] = 'Desmarcar todo';
	$lang['strlocale'] = 'Configuración rexional';
	$lang['strcollation'] = 'Recompilación';
	$lang['strctype'] = 'Tipo de carácter';
	$lang['strdefaultvalues'] = 'Valores predeterminados';
	$lang['strnewvalues'] = 'Valores novos';
	$lang['strstart'] = 'Iniciar';
	$lang['strstop'] = 'Deter';
	$lang['strgotoppage'] = 'Volver arriba';
	$lang['strtheme'] = 'Tema visual';
	$lang['strcluster'] = 'Concentrador';

	// Admin
	$lang['stradminondatabase'] = 'As seguintes tarefas administrativas realizaranse en toda a base de datos «%s».';
	$lang['stradminontable'] = 'As seguintes tarefas administrativas realizaranse na táboa «%s».';

	// User-supplied SQL history
	$lang['strhistory'] = 'Historial';
	$lang['strnohistory'] = 'Sen historial.';
	$lang['strclearhistory'] = 'Borrar o historial';
	$lang['strdelhistory'] = 'Borrar do historial';
	$lang['strconfdelhistory'] = 'Seguro que quere borrar esta solicitude do historial?';
	$lang['strconfclearhistory'] = 'Seguro que quere borrar o historial?';
	$lang['strnodatabaseselected'] = 'Escolla unha base de datos.';

	// Database sizes
	$lang['strnoaccess'] = 'Sen acceso';
	$lang['strsize'] = 'Tamaño';
	$lang['strbytes'] = 'bytes';
	$lang['strkb'] = 'kiB';
	$lang['strmb'] = 'MiB';
	$lang['strgb'] = 'GiB';
	$lang['strtb'] = 'TiB';

	// Error handling
	$lang['strnoframes'] = 'Este aplicativo funciona mellor nos navegadores que permiten o uso de marcos, pero pode usalo sen eles premendo na ligazón que hai máis abaixo.';
	$lang['strnoframeslink'] = 'Utilizar sen marcos';
	$lang['strbadconfig'] = 'O seu ficheiro de configuración «config.inc.php» está obsoleto. Terá que volvelo crear a partires do novo ficheiro «config.inc.php-dist».';
	$lang['strnotloaded'] = 'A súa instalación de PHP non está preparada para utilizar PostgreSQL. Terá que compilar PHP de novo utilizando a opción de configuración «--with-pgsql».';
	$lang['strpostgresqlversionnotsupported'] = 'Este aplicativo non é compatible coa versión de PostgreSQL que está a usar. Actualíceo á versión %s ou outra versión posterior.';
	$lang['strbadschema'] = 'O esquema especificado non era correcto.';
	$lang['strbadencoding'] = 'Non se deu establecida a codificación do cliente na base de datos.';
	$lang['strsqlerror'] = 'Erro de SQL:';
	$lang['strinstatement'] = 'Na instrución:';
	$lang['strinvalidparam'] = 'Os parámetros fornecidos ao guión non son correctos.';
	$lang['strnodata'] = 'Non se atopou fila algunha.';
	$lang['strnoobjects'] = 'Non se atopou obxecto algún.';
	$lang['strrownotunique'] = 'Esta fila non ten ningún identificador único.';
	$lang['strnouploads'] = 'A carga de ficheiros está desactivada.';
	$lang['strimporterror'] = 'Produciuse un erro ao importar.';
	$lang['strimporterror-fileformat'] = 'Produciuse un erro ao importar: non se puido determinar de maneira automática o formato do ficheiro.';
	$lang['strimporterrorline'] = 'Produciuse un erro ao importar, na liña %s.';
	$lang['strimporterrorline-badcolumnnum'] = 'Produciuse un erro ao importar, na liña %s: a liña non ten unha cantidade de columnas axeitada.';
	$lang['strimporterror-uploadedfile'] = 'Produciuse un erro ao importar: non se puido cargar o ficheiro no servidor.';
	$lang['strcannotdumponwindows'] = 'O envorcado de táboas complexas e mais nomes de esquemas non pode efectuarse en sistemas Microsoft Windows.';
	$lang['strinvalidserverparam'] = 'Produciuse un intento de conexión cun servidor non permitido como parámetro, é posible que alguén estea intentando atacar o seu sistema.';
	$lang['strnoserversupplied'] = 'Non se forneceu ningún servidor!';
	$lang['strbadpgdumppath'] = 'Produciuse un erro ao exportar: non se conseguiu executar pg_dump (a ruta indicada no seu ficheiro «conf/config.inc.php» é «%s»). Cambie a ruta no ficheiro de configuración e volva intentalo.';
	$lang['strbadpgdumpallpath'] = 'Produciuse un erro ao exportar: non se conseguiu executar pg_dumpall (a ruta indicada no seu ficheiro «conf/config.inc.php» é «%s»). Cambie a ruta no ficheiro de configuración e volva intentalo.';
	$lang['strconnectionfail'] = 'Non se puido establecer a conexión co servidor.';

	// Tables
	$lang['strtable'] = 'Táboa';
	$lang['strtables'] = 'Táboas';
	$lang['strshowalltables'] = 'Amosar todas as táboas';
	$lang['strnotables'] = 'Non se atopou táboa algunha.';
	$lang['strnotable'] = 'Non se atopou táboa algunha.';
	$lang['strcreatetable'] = 'Crear unha táboa';
	$lang['strcreatetablelike'] = 'Crear unha táboa coma';
	$lang['strcreatetablelikeparent'] = 'Táboa orixinal';
	$lang['strcreatelikewithdefaults'] = 'INCLUÍR OS VALORES PREDETERMINADOS';
	$lang['strcreatelikewithconstraints'] = 'INCLUÍR AS RESTRICIÓNS';
	$lang['strcreatelikewithindexes'] = 'INCLUÍR OS ÍNDICES';
	$lang['strtablename'] = 'Nome da táboa';
	$lang['strtableneedsname'] = 'Debe fornecer un nome para a táboa.';
	$lang['strtablelikeneedslike'] = 'Debe fornecer unha táboa á que copiarlle as propiedades.';
	$lang['strtableneedsfield'] = 'Debe indicar polo menos un campo.';
	$lang['strtableneedscols'] = 'Debe indicar un número de columnas correcto.';
	$lang['strtablecreated'] = 'Creouse a táboa.';
	$lang['strtablecreatedbad'] = 'Non se conseguiu crear a táboa.';
	$lang['strconfdroptable'] = 'Está seguro de que quere eliminar a táboa «%s»?';
	$lang['strtabledropped'] = 'Eliminouse a táboa.';
	$lang['strtabledroppedbad'] = 'Non se conseguiu eliminar a táboa.';
	$lang['strconfemptytable'] = 'Está seguro de que quere baleirar a táboa «%s»?';
	$lang['strtableemptied'] = 'Baleirouse a táboa.';
	$lang['strtableemptiedbad'] = 'Non se conseguiu baleirar a táboa.';
	$lang['strinsertrow'] = 'Inserir unha fila';
	$lang['strrowinserted'] = 'Inseriuse unha fila.';
	$lang['strrowinsertedbad'] = 'Non se conseguiu inserir a fila.';
	$lang['strnofkref'] = 'Non existe ningún valor que coincida na clave externa «%s».';
	$lang['strrowduplicate'] = 'Non se conseguiu inserir a fila, intentouse facer unha inxección duplicada.';
	$lang['streditrow'] = 'Modificar a fila';
	$lang['strrowupdated'] = 'Actualizouse a fila.';
	$lang['strrowupdatedbad'] = 'Non se conseguiu actualizar a fila.';
	$lang['strdeleterow'] = 'Borrar a fila';
	$lang['strconfdeleterow'] = 'Está seguro de que quere borrar esta fila?';
	$lang['strrowdeleted'] = 'Borrouse a fila.';
	$lang['strrowdeletedbad'] = 'Non se conseguiu borrar a fila.';
	$lang['strinsertandrepeat'] = 'Inserir e repetir';
	$lang['strnumcols'] = 'Número de columnas';
	$lang['strcolneedsname'] = 'Debe especificar un nome para a columna';
	$lang['strselectallfields'] = 'Marcar todos os campos';
	$lang['strselectneedscol'] = 'Debe amosar polo menos unha columna.';
	$lang['strselectunary'] = 'Os operadores dun só operando non poden ter valores.';
	$lang['strcolumnaltered'] = 'Modificouse a columna.';
	$lang['strcolumnalteredbad'] = 'Non se conseguiu modificar a columna.';
	$lang['strconfdropcolumn'] = 'Está seguro de que quere eliminar a columna «%s» da táboa «%s»?';
	$lang['strcolumndropped'] = 'Eliminouse a columna.';
	$lang['strcolumndroppedbad'] = 'Non se conseguiu eliminar a columna.';
	$lang['straddcolumn'] = 'Engadir unha columna';
	$lang['strcolumnadded'] = 'Engadiuse a columna.';
	$lang['strcolumnaddedbad'] = 'Non se conseguiu engadir a columna.';
	$lang['strcascade'] = 'EN CASCADA';
	$lang['strtablealtered'] = 'Modificouse a táboa.';
	$lang['strtablealteredbad'] = 'Non se conseguiu modificar a táboa.';
	$lang['strdataonly'] = 'Só datos';
	$lang['strstructureonly'] = 'Só estrutura';
	$lang['strstructureanddata'] = 'Estrutura e datos';
	$lang['strtabbed'] = 'Tabulado';
	$lang['strauto'] = 'Detectar';
	$lang['strconfvacuumtable'] = 'Está seguro de que quere purgar «%s»?';
	$lang['strconfanalyzetable'] = 'Está seguro de que quere analizar «%s»?';
	$lang['strconfreindextable'] = 'Está seguro de que quere indexar «%s»?';
	$lang['strconfclustertable'] = 'Está seguro de que quere concentrar «%s»?';
	$lang['strestimatedrowcount'] = 'Número estimado de filas';
	$lang['strspecifytabletoanalyze'] = 'Debe especificar polo menos unha táboa a analizar.';
	$lang['strspecifytabletoempty'] = 'Debe especificar polo menos unha táboa a baleirar.';
	$lang['strspecifytabletodrop'] = 'Debe especificar polo menos unha táboa a eliminar.';
	$lang['strspecifytabletovacuum'] = 'Debe especificar polo menos unha táboa a purgar.';
	$lang['strspecifytabletoreindex'] = 'Debe especificar polo menos unha táboa a indexar.';
	$lang['strspecifytabletocluster'] = 'Debe especificar polo menos unha táboa a concentrar.';
	$lang['strnofieldsforinsert'] = 'Non pode inserir filas nunha táboa sen columnas.';

	// Columns
	$lang['strcolprop'] = 'Propiedades da columna';
	$lang['strnotableprovided'] = 'Non se forneceu ningunha táboa!';

	// Users
	$lang['struser'] = 'Usuario';
	$lang['strusers'] = 'Usuarios';
	$lang['strusername'] = 'Nome';
	$lang['strpassword'] = 'Contrasinal';
	$lang['strsuper'] = 'Administrador?';
	$lang['strcreatedb'] = 'Crear bases de datos?';
	$lang['strexpires'] = 'Caducidade';
	$lang['strsessiondefaults'] = 'Valores predeterminados da sesión';
	$lang['strnousers'] = 'Non se atopou ningún usuario.';
	$lang['struserupdated'] = 'Actualizouse o usuario.';
	$lang['struserupdatedbad'] = 'Non se conseguiu actualizar o usuario.';
	$lang['strshowallusers'] = 'Listar todos os usuarios';
	$lang['strcreateuser'] = 'Crear un usuario';
	$lang['struserneedsname'] = 'Debe fornecer un nome para o usuario.';
	$lang['strusercreated'] = 'Creouse o usuario.';
	$lang['strusercreatedbad'] = 'Non se conseguiu crear o usuario.';
	$lang['strconfdropuser'] = 'Está seguro de que quere eliminar o usuario «%s»?';
	$lang['struserdropped'] = 'Eliminouse o usuario.';
	$lang['struserdroppedbad'] = 'Non se conseguiu eliminar o usuario.';
	$lang['straccount'] = 'Conta';
	$lang['strchangepassword'] = 'Cambiar de contrasinal';
	$lang['strpasswordchanged'] = 'Cambiouse o contrasinal.';
	$lang['strpasswordchangedbad'] = 'Non se conseguiu cambiar o contrasinal.';
	$lang['strpasswordshort'] = 'O contrasinal é curto de máis.';
	$lang['strpasswordconfirm'] = 'Os contrasinais introducidos son distintos.';

	// Groups
	$lang['strgroup'] = 'Grupo';
	$lang['strgroups'] = 'Grupos';
	$lang['strshowallgroups'] = 'Amosar todos os grupos';
	$lang['strnogroup'] = 'Non se atopou o grupo.';
	$lang['strnogroups'] = 'Non se atopou grupo algún.';
	$lang['strcreategroup'] = 'Crear un grupo';
	$lang['strgroupneedsname'] = 'Debe fornecer un nome para o grupo.';
	$lang['strgroupcreated'] = 'Creouse o grupo.';
	$lang['strgroupcreatedbad'] = 'Non se conseguiu crear o grupo.';
	$lang['strconfdropgroup'] = 'está seguro de que quere eliminar o grupo «%s»?';
	$lang['strgroupdropped'] = 'Eliminouse o grupo.';
	$lang['strgroupdroppedbad'] = 'Non se conseguiu eliminar o grupo.';
	$lang['strmembers'] = 'Membros';
	$lang['strmemberof'] = 'Membros de';
	$lang['stradminmembers'] = 'Membros administradores';
	$lang['straddmember'] = 'Engadir un membro';
	$lang['strmemberadded'] = 'Engadiuse o membro.';
	$lang['strmemberaddedbad'] = 'Non se conseguiu engadir o membro.';
	$lang['strdropmember'] = 'Eliminar o membro';
	$lang['strconfdropmember'] = 'Está seguro de que quere eliminar o membro «%s» do grupo «%s»?';
	$lang['strmemberdropped'] = 'Eliminouse o membro.';
	$lang['strmemberdroppedbad'] = 'Non se conseguiu eliminar o membro.';

	// Roles
	$lang['strrole'] = 'Rol';
	$lang['strroles'] = 'Roles';
	$lang['strshowallroles'] = 'Amosar todos os roles';
	$lang['strnoroles'] = 'Non se atopou rol algún.';
	$lang['strinheritsprivs'] = 'Herdar os privilexios?';
	$lang['strcreaterole'] = 'Crear un rol';
	$lang['strcancreaterole'] = 'Pode crear roles?';
	$lang['strrolecreated'] = 'Creouse o rol.';
	$lang['strrolecreatedbad'] = 'Non se conseguiu crear o rol.';
	$lang['strrolealtered'] = 'Modificouse o rol.';
	$lang['strrolealteredbad'] = 'Non se conseguiu modificar o rol.';
	$lang['strcanlogin'] = 'Pode identificarse?';
	$lang['strconnlimit'] = 'Límite da conexión';
	$lang['strdroprole'] = 'Eliminar o rol';
	$lang['strconfdroprole'] = 'Está seguro de que quere eliminar o rol «%s»?';
	$lang['strroledropped'] = 'Eliminouse o rol.';
	$lang['strroledroppedbad'] = 'Non se conseguiu eliminar o rol.';
	$lang['strnolimit'] = 'Sen límite';
	$lang['strnever'] = 'Nunca';
	$lang['strroleneedsname'] = 'Ten que darlle un nome ao rol.';

	// Privileges
	$lang['strprivilege'] = 'Privilexio';
	$lang['strprivileges'] = 'Privilexios';
	$lang['strnoprivileges'] = 'Este obxecto ten os privilexios predeterminados do propietario.';
	$lang['strgrant'] = 'Conceder';
	$lang['strrevoke'] = 'Revogar';
	$lang['strgranted'] = 'Cambiáronse os privilexios.';
	$lang['strgrantfailed'] = 'Non se conseguiu cambiar os privilexios.';
	$lang['strgrantbad'] = 'Ten que especificar polo menos un usuario ou grupo e un privilexio.';
	$lang['strgrantor'] = 'Autor da concesión';
	$lang['strasterisk'] = '*';

	// Databases
	$lang['strdatabase'] = 'Base de datos';
	$lang['strdatabases'] = 'Bases de datos';
	$lang['strshowalldatabases'] = 'Amosar todas as bases de datos';
	$lang['strnodatabases'] = 'Non se atopou base de datos algunha.';
	$lang['strcreatedatabase'] = 'Crear unha base de datos';
	$lang['strdatabasename'] = 'Nome da base de datos';
	$lang['strdatabaseneedsname'] = 'Debe darlle un nome á base de datos.';
	$lang['strdatabasecreated'] = 'Creouse a base de datos.';
	$lang['strdatabasecreatedbad'] = 'Non se conseguiu crear a base de datos.';
	$lang['strconfdropdatabase'] = 'Está seguro de que quere eliminar a base de datos «%s»?';
	$lang['strdatabasedropped'] = 'Eliminouse a base de datos.';
	$lang['strdatabasedroppedbad'] = 'Non se conseguiu eliminar a base de datos.';
	$lang['strentersql'] = 'Introduza a continuación as instrucións SQL a executar:';
	$lang['strsqlexecuted'] = 'Executáronse as instrucións SQL.';
	$lang['strvacuumgood'] = 'Completouse a purgación.';
	$lang['strvacuumbad'] = 'Non se conseguiu efectuar a purgación.';
	$lang['stranalyzegood'] = 'Completouse a análise.';
	$lang['stranalyzebad'] = 'Non se conseguiu completar a análise.';
	$lang['strreindexgood'] = 'Completouse o indexado.';
	$lang['strreindexbad'] = 'Non se conseguiu completar o indexado.';
	$lang['strfull'] = 'Completo';
	$lang['strfreeze'] = 'Agresivo';
	$lang['strforce'] = 'Forzar';
	$lang['strsignalsent'] = 'Enviouse o sinal.';
	$lang['strsignalsentbad'] = 'Non se conseguiu enviar o sinal.';
	$lang['strallobjects'] = 'Todos os obxectos';
	$lang['strdatabasealtered'] = 'Modificouse a base de datos.';
	$lang['strdatabasealteredbad'] = 'Non se conseguiu modificar a base de datos.';
	$lang['strspecifydatabasetodrop'] = 'Debe especificar polo menos unha base de datos a eliminar.';
	$lang['strtemplatedb'] = 'Modelo';
	$lang['strconfanalyzedatabase'] = 'Está seguro de que quere analizar todas as táboas da base de datos «%s»?';
	$lang['strconfvacuumdatabase'] = 'Está seguro de que quere purgar todas as táboas da base de datos «%s»?';
	$lang['strconfreindexdatabase'] = 'Está seguro de que quere indexar todas as táboas da base de datos «%s»?';
	$lang['strconfclusterdatabase'] = 'Está seguro de que quere concentrar todas as táboas da base de datos «%s»?';

	// Views
	$lang['strview'] = 'Vista';
	$lang['strviews'] = 'Vistas';
	$lang['strshowallviews'] = 'Listar todas as vistas';
	$lang['strnoview'] = 'Non se atopou vista algunha.';
	$lang['strnoviews'] = 'Non se atopou vista algunha.';
	$lang['strcreateview'] = 'Crear unha vista';
	$lang['strviewname'] = 'Nome da vista';
	$lang['strviewneedsname'] = 'Debe fornecer un nome para a vista.';
	$lang['strviewneedsdef'] = 'Debe fornecer unha descrición da vista.';
	$lang['strviewneedsfields'] = 'Debe indicar que columnas quere ter na vista.';
	$lang['strviewcreated'] = 'Creouse a vista.';
	$lang['strviewcreatedbad'] = 'Non se conseguiu crear a vista.';
	$lang['strconfdropview'] = 'Está seguro de que quere eliminar a vista «%s»?';
	$lang['strviewdropped'] = 'Eliminouse a vista.';
	$lang['strviewdroppedbad'] = 'Non se conseguiu eliminar a vista.';
	$lang['strviewupdated'] = 'Actualizouse a vista.';
	$lang['strviewupdatedbad'] = 'Non se conseguiu actualizar a vista.';
	$lang['strviewlink'] = 'Unión de claves';
	$lang['strviewconditions'] = 'Condicións adicionais';
	$lang['strcreateviewwiz'] = 'Crear unha vista co asistente';
	$lang['strrenamedupfields'] = 'Renomear os campos duplicados';
	$lang['strdropdupfields'] = 'Eliminar os campos duplicados';
	$lang['strerrordupfields'] = 'Non permitir que haxa campos duplicados';
	$lang['strviewaltered'] = 'Modificouse a vista.';
	$lang['strviewalteredbad'] = 'Non se conseguiu modificar a vista.';
	$lang['strspecifyviewtodrop'] = 'Debe especificar polo menos unha vista a borrar.';

	// Sequences
	$lang['strsequence'] = 'Secuencia';
	$lang['strsequences'] = 'Secuencias';
	$lang['strshowallsequences'] = 'Amosar todas as secuencias';
	$lang['strnosequence'] = 'Non se atopou secuencia algunha.';
	$lang['strnosequences'] = 'Non se atopou secuencia algunha.';
	$lang['strcreatesequence'] = 'Crear unha secuencia';
	$lang['strlastvalue'] = 'Último valor';
	$lang['strincrementby'] = 'Aumentar en';
	$lang['strstartvalue'] = 'Valor inicial';
	$lang['strrestartvalue'] = 'Valor do reinicio';
	$lang['strmaxvalue'] = 'Valor máximo';
	$lang['strminvalue'] = 'Valor mínimo';
	$lang['strcachevalue'] = 'Valor da caché';
	$lang['strlogcount'] = 'Conta de rexistros';
	$lang['strcancycle'] = 'Pode repetirse?';
	$lang['striscalled'] = 'Aumentará o último valor antes de devolver o seguinte (is_called)?';
	$lang['strsequenceneedsname'] = 'Debe fornecer un nome para a secuencia.';
	$lang['strsequencecreated'] = 'Creouse a secuencia.';
	$lang['strsequencecreatedbad'] = 'Non se conseguiu crear a secuencia.';
	$lang['strconfdropsequence'] = 'Está seguro de que quere eliminar a secuencia «%s»?';
	$lang['strsequencedropped'] = 'Eliminouse a secuencia.';
	$lang['strsequencedroppedbad'] = 'Non se conseguiu eliminar a secuencia.';
	$lang['strsequencerestart'] = 'Reiniciouse a secuencia.';
	$lang['strsequencerestartbad'] = 'Non se conseguiu reiniciar a secuencia.';
	$lang['strsequencereset'] = 'Restableceuse a secuencia.';
	$lang['strsequenceresetbad'] = 'Non se conseguiu restablecer a secuencia.';
	$lang['strsequencealtered'] = 'Modificouse a secuencia.';
	$lang['strsequencealteredbad'] = 'Non se conseguiu modificar a secuencia.';
	$lang['strsetval'] = 'Establecer o valor';
	$lang['strsequencesetval'] = 'Estableceuse o valor da secuencia.';
	$lang['strsequencesetvalbad'] = 'Non se conseguiu establecer o valor da secuencia.';
	$lang['strnextval'] = 'Aumentar o valor';
	$lang['strsequencenextval'] = 'Aumentouse o valor da secuencia.';
	$lang['strsequencenextvalbad'] = 'Non se conseguiu aumentar o valor da secuencia.';
	$lang['strspecifysequencetodrop'] = 'Debe especificar polo menos unha secuencia a eliminar.';

	// Indexes
	$lang['strindex'] = 'Índice';
	$lang['strindexes'] = 'Índices';
	$lang['strindexname'] = 'Nome do índice';
	$lang['strshowallindexes'] = 'Listar todos os índices';
	$lang['strnoindex'] = 'Non se atopou índice algún.';
	$lang['strnoindexes'] = 'Non se atopou índice algún.';
	$lang['strcreateindex'] = 'Crear un índice';
	$lang['strtabname'] = 'Nome da lapela';
	$lang['strcolumnname'] = 'Nome da columna';
	$lang['strindexneedsname'] = 'Debe fornecer un nome para o índice.';
	$lang['strindexneedscols'] = 'Os índices teñen que ter un número de columnas correcto.';
	$lang['strindexcreated'] = 'Creouse o índice.';
	$lang['strindexcreatedbad'] = 'Non se conseguiu crear o índice.';
	$lang['strconfdropindex'] = 'Está seguro de que quere eliminar o índice «%s»?';
	$lang['strindexdropped'] = 'Eliminouse o índice.';
	$lang['strindexdroppedbad'] = 'Non se conseguiu eliminar o índice.';
	$lang['strkeyname'] = 'Nome da clave';
	$lang['struniquekey'] = 'Clave única';
	$lang['strprimarykey'] = 'Clave primaria';
	$lang['strindextype'] = 'Tipo de índice';
	$lang['strtablecolumnlist'] = 'Columnas na táboa';
	$lang['strindexcolumnlist'] = 'Columnas no índice';
	$lang['strconfcluster'] = 'Está seguro de que quere concentrar «%s»?';
	$lang['strclusteredgood'] = 'Completouse o concentrado.';
	$lang['strclusteredbad'] = 'Non se conseguiu completar o concentrado.';
	$lang['strconcurrently'] = 'Simultaneamente';
	$lang['strnoclusteravailable'] = 'A táboa non está concentrada nun índice.';

	// Rules
	$lang['strrules'] = 'Regras';
	$lang['strrule'] = 'Regra';
	$lang['strshowallrules'] = 'Listar todas as regras';
	$lang['strnorule'] = 'Non se atopou regra algunha.';
	$lang['strnorules'] = 'Non se atopou regra algunha.';
	$lang['strcreaterule'] = 'Crear unha regra';
	$lang['strrulename'] = 'Nome da regra';
	$lang['strruleneedsname'] = 'Debe fornecer un nome para a regra.';
	$lang['strrulecreated'] = 'Creouse a regra.';
	$lang['strrulecreatedbad'] = 'Non se conseguiu crear a regra.';
	$lang['strconfdroprule'] = 'Está seguro de que quere eliminar a regra «%s» en «%s»?';
	$lang['strruledropped'] = 'Eliminouse a regra.';
	$lang['strruledroppedbad'] = 'Non se conseguiu eliminar a regra.';

	// Constraints
	$lang['strconstraint'] = 'Restrición';
	$lang['strconstraints'] = 'Restricións';
	$lang['strshowallconstraints'] = 'Listar todas as restricións';
	$lang['strnoconstraints'] = 'Non se atopou restrición algunha.';
	$lang['strcreateconstraint'] = 'Crear unha restrición';
	$lang['strconstraintcreated'] = 'Creouse a restrición.';
	$lang['strconstraintcreatedbad'] = 'Non se conseguiu crear a restrición.';
	$lang['strconfdropconstraint'] = 'Está seguro de que quere eliminar a restrición «%s» en «%s»?';
	$lang['strconstraintdropped'] = 'Eliminouse a restrición.';
	$lang['strconstraintdroppedbad'] = 'Non se conseguiu eliminar a restrición.';
	$lang['straddcheck'] = 'Engadir unha comprobación';
	$lang['strcheckneedsdefinition'] = 'A comprobación necesita unha definición.';
	$lang['strcheckadded'] = 'Engadiuse a comprobación.';
	$lang['strcheckaddedbad'] = 'Non se conseguiu engadir a comprobación.';
	$lang['straddpk'] = 'Engadir unha clave primaria';
	$lang['strpkneedscols'] = 'A clave primaria necesita polo menos unha columna.';
	$lang['strpkadded'] = 'Engadiuse a clave primaria.';
	$lang['strpkaddedbad'] = 'Non se conseguiu engadir a clave primaria.';
	$lang['stradduniq'] = 'Engadir unha clave única';
	$lang['struniqneedscols'] = 'A clave única necesita polo menos unha columna.';
	$lang['struniqadded'] = 'Engadiuse a clave única.';
	$lang['struniqaddedbad'] = 'Non se conseguiu engadir a clave única.';
	$lang['straddfk'] = 'Engadir unha clave externa';
	$lang['strfkneedscols'] = 'A clave externa necesita polo menos unha columna.';
	$lang['strfkneedstarget'] = 'A clave externa necesita unha táboa externa.';
	$lang['strfkadded'] = 'Engadiuse a clave externa.';
	$lang['strfkaddedbad'] = 'Non se conseguiu engadir a clave externa.';
	$lang['strfktarget'] = 'Táboa externa';
	$lang['strfkcolumnlist'] = 'Columnas na clave';
	$lang['strondelete'] = 'AO ACTUALIZAR';	// Sei que son instrucións cando se usa SQL, pero na
	$lang['stronupdate'] = 'AO BORRAR';	// interface paréceme mellor traducilos.

	// Functions
	$lang['strfunction'] = 'Función';
	$lang['strfunctions'] = 'Funcións';
	$lang['strshowallfunctions'] = 'Listar todas as funcións';
	$lang['strnofunction'] = 'Non se atopou función algunha.';
	$lang['strnofunctions'] = 'Non se atopou función algunha.';
	$lang['strcreateplfunction'] = 'Crear unha función SQL/PL';
	$lang['strcreateinternalfunction'] = 'Crear unha función interna';
	$lang['strcreatecfunction'] = 'Crear unha función en C';
	$lang['strfunctionname'] = 'Nome da función';
	$lang['strreturns'] = 'Devolve';
	$lang['strproglanguage'] = 'Linguaxe de programación';
	$lang['strfunctionneedsname'] = 'Debe fornecer un nome para a función.';
	$lang['strfunctionneedsdef'] = 'Debe fornecer unha definición para a función.';
	$lang['strfunctioncreated'] = 'Creouse a función.';
	$lang['strfunctioncreatedbad'] = 'Non se conseguiu crear a función.';
	$lang['strconfdropfunction'] = 'Está seguro de que quere eliminar a función «%s»?';
	$lang['strfunctiondropped'] = 'Eliminouse a función.';
	$lang['strfunctiondroppedbad'] = 'Non se conseguiu eliminar a función.';
	$lang['strfunctionupdated'] = 'Actualizouse a función.';
	$lang['strfunctionupdatedbad'] = 'Non se conseguiu actualizar a función.';
	$lang['strobjectfile'] = 'Ficheiro de obxecto';
	$lang['strlinksymbol'] = 'Símbolo da ligazón';
	$lang['strarguments'] = 'Argumentos';
	$lang['strargmode'] = 'Modo';
	$lang['strargtype'] = 'Tipo';
	$lang['strargadd'] = 'Engadir outro argumento';
	$lang['strargremove'] = 'Borrar este argumento';
	$lang['strargnoargs'] = 'Esta función non recibirá argumento ningún.';
	$lang['strargenableargs'] = 'Permitir que se lle fornezan argumentos á función.';
	$lang['strargnorowabove'] = 'Ten que haber unha fila antes desta.';
	$lang['strargnorowbelow'] = 'Ten que haber unha fila despois desta.';
	$lang['strargraise'] = 'Subir.';
	$lang['strarglower'] = 'Baixar.';
	$lang['strargremoveconfirm'] = 'Está seguro de que quere borrar este argumento? Esta acción non se pode desfacer.';
	$lang['strfunctioncosting'] = 'Custo da función';
	$lang['strresultrows'] = 'Filas resultantes';
	$lang['strexecutioncost'] = 'Custo de execución';
	$lang['strspecifyfunctiontodrop'] = 'Debe especificar polo menos unha función a eliminar.';

	// Triggers
	$lang['strtrigger'] = 'Disparador';
	$lang['strtriggers'] = 'Disparadores';
	$lang['strshowalltriggers'] = 'Listar todos os disparadores';
	$lang['strnotrigger'] = 'Non se atopor disparador algún.';
	$lang['strnotriggers'] = 'Non se atopor disparador algún.';
	$lang['strcreatetrigger'] = 'Crear un disparador';
	$lang['strtriggerneedsname'] = 'Debe fornecer un nome para o disparador.';
	$lang['strtriggerneedsfunc'] = 'Debe especificar unha función para o disparador.';
	$lang['strtriggercreated'] = 'Creouse o disparador.';
	$lang['strtriggercreatedbad'] = 'Non se conseguiu crear o disparador.';
	$lang['strconfdroptrigger'] = 'Está seguro de que quere eliminar o disparador «%s» en «%s»?';
	$lang['strconfenabletrigger'] = 'Está seguro de que quere activar o disparador «%s» en «%s»?';
	$lang['strconfdisabletrigger'] = 'Está seguro de que quere desactivar o disparador «%s» en «%s»?';
	$lang['strtriggerdropped'] = 'Eliminouse o disparador.';
	$lang['strtriggerdroppedbad'] = 'Non se conseguiu eliminar o disparador.';
	$lang['strtriggerenabled'] = 'Activouse o disparador.';
	$lang['strtriggerenabledbad'] = 'Non se conseguiu activar o disparador.';
	$lang['strtriggerdisabled'] = 'Desactivouse o disparador.';
	$lang['strtriggerdisabledbad'] = 'Non se conseguiu desactivar o disparador.';
	$lang['strtriggeraltered'] = 'Modificouse o disparador.';
	$lang['strtriggeralteredbad'] = 'Non se conseguiu modificar o disparador.';
	$lang['strforeach'] = 'Por cada'; // «For each [row or instruction]»

	// Types
	$lang['strtype'] = 'Tipo';
	$lang['strtypes'] = 'Tipos';
	$lang['strshowalltypes'] = 'Listar todos os tipos';
	$lang['strnotype'] = 'Non se atopou tipo algún.';
	$lang['strnotypes'] = 'Non se atopou tipo algún.';
	$lang['strcreatetype'] = 'Crear un tipo';
	$lang['strcreatecomptype'] = 'Crear un tipo composto';
	$lang['strcreateenumtype'] = 'Crear un tipo de enumeración';
	$lang['strtypeneedsfield'] = 'Debe especificar polo menos un campo.';
	$lang['strtypeneedsvalue'] = 'Debe especificar polo menos un valor.';
	$lang['strtypeneedscols'] = 'Debe especificar un número correcto de campos.';
	$lang['strtypeneedsvals'] = 'Debe especificar un número correcto de valores.';
	$lang['strinputfn'] = 'Función de entrada';
	$lang['stroutputfn'] = 'Función de saída';
	$lang['strpassbyval'] = 'Pasado por valor?';
	$lang['stralignment'] = 'Aliñación';
	$lang['strelement'] = 'Elemento';
	$lang['strdelimiter'] = 'Delimitador';
	$lang['strstorage'] = 'Almacenamento';
	$lang['strfield'] = 'Campo';
	$lang['strnumfields'] = 'Cantidade de campos';
	$lang['strnumvalues'] = 'Cantidade de valores';
	$lang['strtypeneedsname'] = 'Debe fornecer un nome para o tipo.';
	$lang['strtypeneedslen'] = 'Debe fornecer unha lonxitude para o tipo.';
	$lang['strtypecreated'] = 'Creouse o tipo.';
	$lang['strtypecreatedbad'] = 'Non se conseguiu crear o tipo.';
	$lang['strconfdroptype'] = 'Está seguro de que quere eliminar o tipo «%s»?';
	$lang['strtypedropped'] = 'Eliminouse o tipo.';
	$lang['strtypedroppedbad'] = 'Non se conseguiu eliminar o tipo.';
	$lang['strflavor'] = 'Subtipo';
	$lang['strbasetype'] = 'Base';
	$lang['strcompositetype'] = 'Composto';
	$lang['strpseudotype'] = 'Pseudo';
	$lang['strenum'] = 'Enumeración';
	$lang['strenumvalues'] = 'Valores da enumeración';

	// Schemas
	$lang['strschema'] = 'Esquema';
	$lang['strschemas'] = 'Esquemas';
	$lang['strshowallschemas'] = 'Listar todos os esquemas';
	$lang['strnoschema'] = 'Non se atopou esquema algún.';
	$lang['strnoschemas'] = 'Non se atopou esquema algún.';
	$lang['strcreateschema'] = 'Crear un esquema';
	$lang['strschemaname'] = 'Nome do esquema';
	$lang['strschemaneedsname'] = 'Debe fornecer un nome para o esquema.';
	$lang['strschemacreated'] = 'Creouse o esquema.';
	$lang['strschemacreatedbad'] = 'Non se conseguiu crear o esquema.';
	$lang['strconfdropschema'] = 'Está seguro de que quere eliminar o esquema «%s»?';
	$lang['strschemadropped'] = 'Eliminouse o esquema.';
	$lang['strschemadroppedbad'] = 'Non se conseguiu eliminar o esquema.';
	$lang['strschemaaltered'] = 'Modificouse o esquema.';
	$lang['strschemaalteredbad'] = 'Non se conseguiu modificar o esquema.';
	$lang['strsearchpath'] = 'Ruta de busca do esquema';
	$lang['strspecifyschematodrop'] = 'Debe especificar polo menos un esquema a eliminar.';

	// Reports

	// Domains
	$lang['strdomain'] = 'Dominio';
	$lang['strdomains'] = 'Dominios';
	$lang['strshowalldomains'] = 'Listar todos os dominios';
	$lang['strnodomains'] = 'Non se atopou dominio algún.';
	$lang['strcreatedomain'] = 'Crear un dominio';
	$lang['strdomaindropped'] = 'Eliminouse o dominio.';
	$lang['strdomaindroppedbad'] = 'Non se conseguiu eliminar o dominio.';
	$lang['strconfdropdomain'] = 'Está seguro de que quere eliminar o dominio «%s»?';
	$lang['strdomainneedsname'] = 'Debe fornecer un nome para o dominio.';
	$lang['strdomaincreated'] = 'Creouse o dominio.';
	$lang['strdomaincreatedbad'] = 'Non se conseguiu crear o dominio.';
	$lang['strdomainaltered'] = 'Modificouse o dominio.';
	$lang['strdomainalteredbad'] = 'Non se conseguiu modificar o dominio.';

	// Operators
	$lang['stroperator'] = 'Operador';
	$lang['stroperators'] = 'Operadores';
	$lang['strshowalloperators'] = 'Listar todos os operadores';
	$lang['strnooperator'] = 'Non se atopou operador algún.';
	$lang['strnooperators'] = 'Non se atopou operador algún.';
	$lang['strcreateoperator'] = 'Crear un operador';
	$lang['strleftarg'] = 'Tipo do argumento esquerdo';
	$lang['strrightarg'] = 'Tipo do argumento dereito';
	$lang['strcommutator'] = 'Conmutador';
	$lang['strnegator'] = 'Negación';
	$lang['strrestrict'] = 'Restrinxir';
	$lang['strjoin'] = 'Unir';
	$lang['strhashes'] = 'Hashes'; // Non sei como traducilo.
	$lang['strmerges'] = 'Mesturas';
	$lang['strleftsort'] = 'Ordenar pola esquerda';
	$lang['strrightsort'] = 'Ordenar pola dereita';
	$lang['strlessthan'] = 'Menor que';
	$lang['strgreaterthan'] = 'Maior que';
	$lang['stroperatorneedsname'] = 'Debe fornecer un nome para o operador.';
	$lang['stroperatorcreated'] = 'Creouse o operador.';
	$lang['stroperatorcreatedbad'] = 'Non se conseguiu crear o operador.';
	$lang['strconfdropoperator'] = 'Está seguro de que quere eliminar o operador «%s»?';
	$lang['stroperatordropped'] = 'Eliminouse o operador.';
	$lang['stroperatordroppedbad'] = 'Non se conseguiu eliminar o operador.';

	// Casts
	$lang['strcasts'] = 'Molde';
	$lang['strnocasts'] = 'Non se atopou molde algún.';
	$lang['strsourcetype'] = 'Tipo orixe';
	$lang['strtargettype'] = 'Tipo obxectivo';
	$lang['strimplicit'] = 'Implícito';
	$lang['strinassignment'] = 'Na asignación';
	$lang['strbinarycompat'] = '(Compatible a nivel binario)';

	// Conversions
	$lang['strconversions'] = 'Conversións';
	$lang['strnoconversions'] = 'Non se atopou conversión algunha.';
	$lang['strsourceencoding'] = 'Codificación orixinal';
	$lang['strtargetencoding'] = 'Codificación obxectivo';

	// Languages
	$lang['strlanguages'] = 'Linguas';
	$lang['strnolanguages'] = 'Non se atopou lingua algunha.';
	$lang['strtrusted'] = 'De confianza';

	// Info
	$lang['strnoinfo'] = 'Non hai información dispoñible.';
	$lang['strreferringtables'] = 'Táboas que fan referencia a esta';
	$lang['strparenttables'] = 'Táboas superiores';
	$lang['strchildtables'] = 'Táboas subordinadas';

	// Aggregates
	$lang['straggregate'] = 'Conxunto';
	$lang['straggregates'] = 'Conxuntos';
	$lang['strnoaggregates'] = 'Non se atopou conxunto algún.';
	$lang['stralltypes'] = '(Todos os tipos)';
	$lang['strcreateaggregate'] = 'Crear un conxunto';
	$lang['straggrbasetype'] = 'Tipo de dato de entrada';
	$lang['straggrsfunc'] = 'Función de cambio de estado';
	$lang['straggrstype'] = 'Tipo de dato para o valor do estado';
	$lang['straggrffunc'] = 'Función final';
	$lang['straggrinitcond'] = 'Condición inicial';
	$lang['straggrsortop'] = 'Operador de orde';
	$lang['strconfdropaggregate'] = 'Está seguro de que quere eliminar o conxunto «%s»?';
	$lang['straggregatedropped'] = 'Eliminouse o conxunto.';
	$lang['straggregatedroppedbad'] = 'Non se conseguiu eliminar o conxunto.';
	$lang['straggraltered'] = 'Modificouse o conxunto.';
	$lang['straggralteredbad'] = 'Non se conseguiu eliminar o conxunto.';
	$lang['straggrneedsname'] = 'Debe fornecer un nome para o conxunto.';
	$lang['straggrneedsbasetype'] = 'Debe fornecer un tipo de dato de entrada para o conxunto.';
	$lang['straggrneedssfunc'] = 'Debe fornecer o nome da función de cambio de estado para o conxunto.';
	$lang['straggrneedsstype'] = 'Debe fornecer un tipo de dato para o valor do estado do conxunto.';
	$lang['straggrcreated'] = 'Creouse o conxunto.';
	$lang['straggrcreatedbad'] = 'Non se conseguiu crear o conxunto.';
	$lang['straggrshowall'] = 'Listar todos os conxuntos';

	// Operator Classes
	$lang['stropclasses'] = 'Clases de operador';
	$lang['strnoopclasses'] = 'Non se atopor clase de operador algunha.';
	$lang['straccessmethod'] = 'Método de acceso';

	// Stats and performance
	$lang['strrowperf'] = 'Rendemento das filas';
	$lang['strioperf'] = 'Rendemento da entrada e saída';
	$lang['stridxrowperf'] = 'Rendemento das filas do índice';
	$lang['stridxioperf'] = 'Rendemento da entrada e saída do índice';
	$lang['strpercent'] = '%';
	$lang['strsequential'] = 'Secuencial';
	$lang['strscan'] = 'Explorar';
	$lang['strread'] = 'Ler';
	$lang['strfetch'] = 'Obter';
	$lang['strheap'] = 'Pila';
	$lang['strtoast'] = 'TOAST'; // Non traduzo por se son siglas, que non o teño claro.
	$lang['strtoastindex'] = 'Índice TOAST';
	$lang['strcache'] = 'Caché';
	$lang['strdisk'] = 'Disco';
	$lang['strrows2'] = 'Filas';

	// Tablespaces
	$lang['strtablespace'] = 'Alias de ruta';
	$lang['strtablespaces'] = 'Alias de ruta';
	$lang['strshowalltablespaces'] = 'Listar todos os alias de ruta';
	$lang['strnotablespaces'] = 'Non se atopou alias de ruta algún.';
	$lang['strcreatetablespace'] = 'Crear un alias de ruta';
	$lang['strlocation'] = 'Lugar';
	$lang['strtablespaceneedsname'] = 'Debe fornecer un nome para o alias de ruta.';
	$lang['strtablespaceneedsloc'] = 'Debe fornecer unha ruta para a que crear o alias.';
	$lang['strtablespacecreated'] = 'Creouse o alias de ruta.';
	$lang['strtablespacecreatedbad'] = 'non se conseguiu crear o alias de ruta.';
	$lang['strconfdroptablespace'] = 'Está seguro de que quere borrar o alias de ruta «%s»?';
	$lang['strtablespacedropped'] = 'Eliminouse o alias de ruta.';
	$lang['strtablespacedroppedbad'] = 'Non se conseguiu eliminar o alias de ruta.';
	$lang['strtablespacealtered'] = 'Modificouse o alias de ruta.';
	$lang['strtablespacealteredbad'] = 'Non se conseguiu modificar o alias de ruta.';

	// Miscellaneous
	$lang['strtopbar'] = '%s, executándose no enderezo %s:%s. Está identificado coma «%s».';
	$lang['strtimefmt'] = 'd/m/Y, G:i:s';
	$lang['strhelp'] = 'Axuda';
	$lang['strhelpicon'] = '?';
	$lang['strhelppagebrowser'] = 'Navegador das páxinas de axuda';
	$lang['strselecthelppage'] = 'Escolla unha páxina de axuda';
	$lang['strinvalidhelppage'] = 'Páxina de axuda incorrecta.';
	$lang['strlogintitle'] = 'Identificarse en %s';
	$lang['strlogoutmsg'] = 'Saíu de %s';
	$lang['strloading'] = 'Cargando...';
	$lang['strerrorloading'] = 'Produciuse un erro durante o proceso de carga';
	$lang['strclicktoreload'] = 'Prema aquí para recargar';

	// Autovacuum
	$lang['strautovacuum'] = 'Purgación automática';
	$lang['strturnedon'] = 'Acendido';
	$lang['strturnedoff'] = 'Apagado';
	$lang['strenabled'] = 'Activado';
	$lang['strnovacuumconf'] = 'Non se atopou ningunha configuración para purgacións automáticas.';
	$lang['strvacuumbasethreshold'] = 'Límite da base da purgación';
	$lang['strvacuumscalefactor'] = 'Factores de escala da purgación';
	$lang['stranalybasethreshold'] = 'Límite da base da análise';
	$lang['stranalyzescalefactor'] = 'Factores de escala da análise';
	$lang['strvacuumcostdelay'] = 'Atraso do custo da purgación';
	$lang['strvacuumcostlimit'] = 'Custo límite da purgación';
	$lang['strvacuumpertable'] = 'Configuración da purgación automática por táboa';
	$lang['straddvacuumtable'] = 'Engadir unha configuración de purgación automática dunha táboa';
	$lang['streditvacuumtable'] = 'Modificar a configuración de purgación automática da táboa «%s»';
	$lang['strdelvacuumtable'] = 'Está seguro de que quere eliminar a configuración de purgación automática da táboa «%s»?';
	$lang['strvacuumtablereset'] = 'A configuración de purgación automática da táboa «%s» restableceuse aos seus valores predeterminados';
	$lang['strdelvacuumtablefail'] = 'Non se conseguiu eliminar a configuración de purgación automática da táboa «%s»';
	$lang['strsetvacuumtablesaved'] = 'Gardouse a configuración de purgación automática da táboa «%s».';
	$lang['strsetvacuumtablefail'] = 'Non se conseguiu gardar a configuración de purgación automática da táboa «%s».';
	$lang['strspecifydelvacuumtable'] = 'Debe especificar unha táboa da que borrar os parámetros de purgación.';
	$lang['strspecifyeditvacuumtable'] = 'Debe especificar unha táboa na que modificar os parámetros de purgación.';
	$lang['strnotdefaultinred'] = 'Os valores que non sexan os predeterminados están en cor vermella.';

	// Table-level Locks
	$lang['strlocks'] = 'Bloqueos';
	$lang['strtransaction'] = 'Identificador da transacción';
	$lang['strvirtualtransaction'] = 'Identificador da transacción virtual';
	$lang['strprocessid'] = 'Identificador do proceso';
	$lang['strmode'] = 'Modo de bloqueo';
	$lang['strislockheld'] = 'Está activo o bloqueo?';

	// Prepared transactions
	$lang['strpreparedxacts'] = 'Transaccións preparadas';
	$lang['strxactid'] = 'Identificador da transacción';
	$lang['strgid'] = 'Identificador global';

	// Fulltext search
	$lang['strfulltext'] = 'Busca de texto completa';
	$lang['strftsconfig'] = 'Configuración de BTC';
	$lang['strftsconfigs'] = 'Configuracións';
	$lang['strftscreateconfig'] = 'Crear unha configuración de BTC';
	$lang['strftscreatedict'] = 'Crear un dicionario';
	$lang['strftscreatedicttemplate'] = 'Crear un modelo de dicionario';
	$lang['strftscreateparser'] = 'Crear un analizador';
	$lang['strftsnoconfigs'] = 'Non se atopou configuración de BTC algunha.';
	$lang['strftsconfigdropped'] = 'Eliminouse a configuración de BTC.';
	$lang['strftsconfigdroppedbad'] = 'Non se conseguiu eliminar a configuración de BTC.';
	$lang['strconfdropftsconfig'] = 'Está seguro de que quere eliminar a configuración de BTC «%s»?';
	$lang['strconfdropftsdict'] = 'Está seguro de que quere eliminar o dicionario de BTC «%s»?';
	$lang['strconfdropftsmapping'] = 'Está seguro de que quere eliminar a aplicación «%s» da configuración de BTC «%s»?';
	$lang['strftstemplate'] = 'Modelo';
	$lang['strftsparser'] = 'Analizador';
	$lang['strftsconfigneedsname'] = 'Debe fornecer un nome para a configuración de BTC.';
	$lang['strftsconfigcreated'] = 'Creouse a configuración de BTC.';
	$lang['strftsconfigcreatedbad'] = 'non se conseguiu crear a configuración de BTC.';
	$lang['strftsmapping'] = 'Aplicación';
	$lang['strftsdicts'] = 'Dicionarios';
	$lang['strftsdict'] = 'Dicionario';
	$lang['strftsemptymap'] = 'Aplicación da configuración de BTC baleira.';
	$lang['strftsconfigaltered'] = 'Modificouse a configuración de BTC.';
	$lang['strftsconfigalteredbad'] = 'Non se conseguiu modificar a configuración de BTC.';
	$lang['strftsconfigmap'] = 'Aplicación da configuración de BTC';
	$lang['strftsparsers'] = 'Analizadores de BTC';
	$lang['strftsnoparsers'] = 'Non hai ningún analizador de BTC dispoñible.';
	$lang['strftsnodicts'] = 'Non hai ningún dicionario de BTC dispoñible.';
	$lang['strftsdictcreated'] = 'Creouse o dicionario de BTC.';
	$lang['strftsdictcreatedbad'] = 'Non se conseguiu crear o dicionario de BTC.';
	$lang['strftslexize'] = 'Análise léxica';
	$lang['strftsinit'] = 'Comezo';
	$lang['strftsoptionsvalues'] = 'Opcións e valores';
	$lang['strftsdictneedsname'] = 'Debe fornecer un nome para o dicionario de BTC.';
	$lang['strftsdictdropped'] = 'Eliminouse o dicionario de BTC.';
	$lang['strftsdictdroppedbad'] = 'Non se conseguiu eliminar o dicionario de BTC.';
	$lang['strftsdictaltered'] = 'Modificouse o dicionario de BTC.';
	$lang['strftsdictalteredbad'] = 'Non se conseguiu modifica o dicionario de BTC.';
	$lang['strftsaddmapping'] = 'Engadir unha nova aplicación';
	$lang['strftsspecifymappingtodrop'] = 'Debe especificar polo menos unha aplicación a eliminar.';
	$lang['strftsspecifyconfigtoalter'] = 'Debe especificar polo menos unha configuración de BTC a modificar';
	$lang['strftsmappingdropped'] = 'Eliminouse a aplicación de BTC.';
	$lang['strftsmappingdroppedbad'] = 'Non se conseguiu eliminar a aplicación de BTC.';
	$lang['strftsnodictionaries'] = 'Non se atopou dicionario algún.';
	$lang['strftsmappingaltered'] = 'Modificouse a aplicación de BTC.';
	$lang['strftsmappingalteredbad'] = 'Non se conseguiu modificar a aplicación de BTC.';
	$lang['strftsmappingadded'] = 'Engadiuse a aplicación de BTC.';
	$lang['strftsmappingaddedbad'] = 'Non se conseguiu engadir a aplicación de BTC.';
	$lang['strftstabconfigs'] = 'Configuracións';
	$lang['strftstabdicts'] = 'Dicionarios';
	$lang['strftstabparsers'] = 'Analizadores';
	$lang['strftscantparsercopy'] = 'Non se pode especificar tanto un analizador coma un modelo durante a creación dunha configuración de busca de texto.';
?>
