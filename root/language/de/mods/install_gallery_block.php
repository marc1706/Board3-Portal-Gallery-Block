<?php
/**
*
* @package B3P Addon - Gallery block
* @version $Id: install_gallery_block.php 58 2009-06-03 11:56:11Z Christian_N $
* @copyright (c) Christian_N ( www.phpbb-projekt.de )
* @installer based on: phpBB Gallery by nickvergessen, www.flying-bits.org
* @license http://opensource.org/licenses/gpl-license.php GNU Public License 
*
*/

/**
* DO NOT CHANGE
*/
if (!defined('IN_PHPBB'))
{
	exit;
}

if (empty($lang) || !is_array($lang))
{
	$lang = array();
}

$lang = array_merge($lang, array(
	'INSTALL_CONGRATS_EXPLAIN'		=> '<p>Du hast den Galerie Block v%s erfolgreich installiert.<br /><br /><strong>Bitte lösche oder verschiebe jetzt das Installations-Verzeichnis "install" oder benenne es um, bevor du dein Board benutzt. Solange dieses Verzeichnis existiert, ist nur der Administrations-Bereich zugänglich.</strong></p>',
	'INSTALL_INTRO_BODY'			=> 'Dieser Assistent unterstützt dich bei der Installation des Galerie Blocks in deinem Board3 Portal.',

	'STAGE_CREATE_TABLE_EXPLAIN'	=> 'Die vom Galerie Block genutzten Datenbank-Tabellen wurden erstellt und mit einigen Ausgangswerten gefüllt. Gehe weiter zum nächsten Schritt, um die Installation des Galerie Blocks abzuschließen.',
	'STAGE_ADVANCED_SUCCESSFUL'		=> 'Die vom Galerie Block genutzten Module wurden erstellt. Gehe weiter um die Installation des Galerie Blocks abzuschließen.',
	'STAGE_UNINSTALL'				=> 'Deinstallieren',

	'FILE_DELETE_FAIL'				=> 'Datei konnte nicht gelöscht werden, du musst sie manuel löschen',
	'FILE_STILL_EXISTS'				=> 'Datei existiert noch',
	'FILES_DELETE_OUTDATED'			=> 'Veraltete Dateien löschen',
	'FILES_DELETE_OUTDATED_EXPLAIN'	=> 'Wenn du die Dateien löscht, werden sie entgülig gelöscht und können nicht wiederhergestellt werden!<br /><br />Hinweis:<br />Wenn du weitere Styles und Sprachpakete installiert hast, musst du die Dateien dort von Hand löschen.',
	'FILES_OUTDATED'				=> 'Veraltete Dateien',
	'FILES_OUTDATED_EXPLAIN'		=> '<strong>Veraltete Dateien</strong> - bitte entferne die folgenden Dateien um mögliche Sicherheitslücken zu entfernen.',

	
	'UPDATE_INSTALLATION'			=> 'Galerie Block aktualisieren',
	'UPDATE_INSTALLATION_EXPLAIN' 	=> 'Mit dieser Option kannst du dein Galerie Block auf den aktuellen Versionsstand bringen.',
	'UPDATE_CONGRATS_EXPLAIN'		=> '<p>Du hast den Galerie Block erfolgreich auf v%s aktualisiert.<br /><br /><strong>Bitte lösche oder verschiebe jetzt das Installations-Verzeichnis "install" oder benenne es um, bevor du dein Board benutzt. Solange dieses Verzeichnis existiert, ist nur der Administrations-Bereich zugänglich.</strong></p>',
    
	'UNINSTALL_INTRO'				=> 'Willkommen bei der Deinstallation',
	'UNINSTALL_INTRO_BODY'			=> 'Dieser Assistent unterstützt dich bei der De-Installation des Galerie Blocks.',
	'CAT_UNINSTALL'					=> 'Deinstallieren',
	'UNINSTALL_CONGRATS'			=> 'Galerie Block deinstalliert',
	'UNINSTALL_CONGRATS_EXPLAIN'	=> '<p>Du hast den Galerie Block erfolgreich deinstalliert.<br /><br /><strong>Bitte lösche oder verschiebe jetzt das Installations-Verzeichnis "install" oder benenne es um, bevor du dein Board benutzt. Solange dieses Verzeichnis existiert, ist nur der Administrations-Bereich zugänglich.<br /><br />Denke daran die "Galerie Block"-Dateien zu löschen und Dateiänderungen am Board3 Portal rückgängig zu machen.</strong></p>',

	'VERSION_REQD_EXPLAIN'			=> '<strong>Voraussetzung</strong> - Folgenden MODs müssen mindesten ab dieser Version installiert sein!',
	'PG_VERSION_REQD'				=> 'phpBB Gallery-Version >= %s',
	'B3P_VERSION_REQD'				=> 'Board3 Portal-Version >= %s',
	
	'SUPPORT_BODY'					=> 'Für die aktuelle, stabile Version des "Galerie Blocks" wird kostenloser Support gewährt. Dieser umfasst:</p><ul><li>Installation</li><li>Technische Fragen</li><li>Probleme durch eventuelle Fehler in der Software</li><li>Aktualisierung von Release Candidates (RC) oder stabilen Versionen zur aktuellen stabilen Version</li></ul><p>Support gibt es in folgenden Foren:</p><ul><li><a href="http://www.phpbb-projekt.de/">phpbb-projekt.de - Homepage des MOD-Autor\'s Christian_N</a></li><li><a href="http://www.board3.de/">board3.de</a></li><li><a href="http://www.flying-bits.org/">flying-bits.org</a></li></ul><p>',
	'GOTO_PORTAL'					=> 'Gehe zum Portal',
	
	'NOT_INSTALLED'					=> 'Nicht installiert',
));

?>