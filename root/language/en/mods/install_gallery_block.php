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
	'INSTALL_CONGRATS_EXPLAIN'		=> 	'<p>You have succesfully installed the Gallery Block v%s<br/><br/><strong>Now delete, move or rename the "install"-folder before you use your board. As long as this directory is present, you will only have access to your ACP.</strong></p>',
	'INSTALL_INTRO_BODY'			=> 	'This installation system will guide you through installing the Gallery Block to your Board3 Portal.',

	'STAGE_CREATE_TABLE_EXPLAIN'	=> 	'The Gallery Block database tables have been created and initialized with basic values. Proceed to the next step to finish the Gallery Block installation.',
	'STAGE_ADVANCED_SUCCESSFUL'		=> 	'The Gallery Block modules have been created. Proceed to finish the Gallery Block installation.',
	'STAGE_UNINSTALL'				=> 	'Uninstall',

	'FILE_DELETE_FAIL'				=> 'File could not be deleted, you need to delete it manually',
	'FILE_STILL_EXISTS'				=> 'File still exists',
	'FILES_DELETE_OUTDATED'			=> 'Delete outdated files',
	'FILES_DELETE_OUTDATED_EXPLAIN'	=> 'When you click to delete the files, they are completly deleted and can not be restored!<br /><br />Please note:<br />If you have more styles and languages installed, you need to delete the files by hand.',
	'FILES_OUTDATED'				=> 'Outdated files',
	'FILES_OUTDATED_EXPLAIN'		=> '<strong>Outdated</strong> - In order to deny hacking attempts, please remove the following files.',


	'UPDATE_INSTALLATION'			=> 	'Update Gallery Block',
	'UPDATE_INSTALLATION_EXPLAIN'	=> 	'This option will update your Gallery Block to the current version.',
	'UPDATE_CONGRATS_EXPLAIN'		=> 	'<p>You have updated your Gallery Block successfully to v%s<br/><br/><strong>Now delete, move or rename the "install"-folder before you use your board. As long as this directory is present, you will only have access to your ACP.</strong></p>',

	'UNINSTALL_INTRO'				=> 	'Welcome to Uninstall',
	'UNINSTALL_INTRO_BODY'			=> 	'This installation system will guide you through uninstalling the Gallery Block from your Board3 Portal.',
	'CAT_UNINSTALL'					=> 	'Uninstall',
	'UNINSTALL_CONGRATS'			=> 	'Gallery Block removed',
	'UNINSTALL_CONGRATS_EXPLAIN'	=> 	'<p>You have successfully uninstalled the Gallery Block.<br /><br /><strong>Now delete, move or rename the "install"-folder before you use your board. As long as this directory is present, you will only have access to your ACP.<br /><br />Make sure to delete the Gallery Block-related files and reverse all Gallery Block-related edits of the Board3 Portal core files.</strong></p>',

	'VERSION_REQD_EXPLAIN'			=> '<strong>Requirements</strong> - The following MODs must at least from this version be installed!',
	'PG_VERSION_REQD'				=> 'phpBB Gallery version >= %s',
	'B3P_VERSION_REQD'				=> 'Board3 Portal version >= %s',
	
	'SUPPORT_BODY'					=> 	'Support for the latest stable version of the Gallery Block is available free of charge for:</p><ul><li>Installation</li><li>Technical questions</li><li>Program-related issues</li><li>Updating Release Candidates (RC) or stable versions to the latest stable version</li></ul><p>You will find support in these forums:</p><ul><li><a href="http://www.phpbb-projekt.de/">phpbb-projekt.de - Homepage of Christian_N - MOD author</a></li><li><a href="http://www.board3.de/">board3.de</a></li><li><a href="http://www.flying-bits.org/">flying-bits.org</a></li></ul><p>',
	'GOTO_PORTAL'					=> 	'Proceed to Portal',

	'NOT_INSTALLED'					=> 'Not installed',
));

?>