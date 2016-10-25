<?php
/**
 * New version of MediaWiki web-based config/installation
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 * http://www.gnu.org/copyleft/gpl.html
 *
 * @file
 */

define( 'MW_CONFIG_CALLBACK', 'Installer::overrideConfig' );
define( 'MEDIAWIKI_INSTALL', true );

chdir( dirname( __DIR__ ) );
require dirname( __DIR__ ) . '/includes/WebStart.php';

require( dirname( __FILE__ ) . '/WpiSettings.php' );

wfInstallerMain();

function wfInstallerMain() {
//	global $wgRequest, $wgLang, $wgMetaNamespace, $wgCanonicalNamespaceNames;
	global $IP, $wgWPIOptionStore, $wgLanguageCode, $wgEnableUploads, $wgLogo;

	$siteName = $wgWPIOptionStore['sitename'];
	$adminName = $wgWPIOptionStore['adminname'];
	$wgLanguageCode = $wgWPIOptionStore['lang'];
	$wgEnableUploads = (strtolower($wgWPIOptionStore['enablefileupload']) == 'yes');
	$wgLogo = "\$wgStylePath/common/images/wiki.png";
	
	$wgWPIOptionStore['scriptpath'] = getScriptPath();
	
	ob_start();
	echo "<pre>";
	
	$installer = InstallerOverrides::getCliInstaller( $siteName, $adminName, $wgWPIOptionStore );

	$status = $installer->doEnvironmentChecks();
	if( $status->isGood() ) {
		$installer->showMessage( 'config-env-good' );
		$installer->execute();
		$installer->writeConfigurationFile( $IP );
		// Modify LocalSettings for WindowsAzure
		if ( strtolower($wgWPIOptionStore['usewindowsazure']) == "yes" ) {
			$azureSettings = "
require_once(\"\$IP/extensions/WindowsAzureSDK/WindowsAzureSDK.php\");
require_once(\"\$IP/extensions/WindowsAzureStorage/WindowsAzureStorage.php\");

\$wgFileBackends[] = array(
		'name'        => 'azure-backend',
		'class'       => 'WindowsAzureFileBackend',
		'lockManager' => 'nullLockManager',
		'azureAccount'   => '{$wgWPIOptionStore['azureaccount']}',
		'azureKey'       => '{$wgWPIOptionStore['azurekey']}',

		//IMPORTANT: Mind the container naming conventions! http://msdn.microsoft.com/en-us/library/dd135715.aspx
		'wikiId'       => '{$wgWPIOptionStore['wikiId']}',
		'containerPaths' => array(
				'media-public'  => 'media-public',
				'media-thumb'   => 'media-thumb',
				'media-deleted' => 'media-deleted',
				'media-temp'    => 'media-temp',

		)
);

\$wgLocalFileRepo = array (
	'class'             => 'LocalRepo',
	'name'              => 'local',
	'scriptDirUrl'      => '/php/mediawiki-filebackend-azure',
	'scriptExtension'   => '.php',
	'url'               => \$wgScriptPath.'/img_auth.php', // It is important to set this to img_auth. Basically, there is no alternative.
	'hashLevels'        => 2,
	'thumbScriptUrl'    => false,
	'transformVia404'   => false,
	'deletedHashLevels' => 3,
	'backend'           => 'azure-backend',
	'zones' => 
			array (
					'public' => 
							array (
								'container' => 'local-public',
								'directory' => '',
							),
					'thumb' => 
							array(
								'container' => 'local-public',
								'directory' => 'thumb',
							),
					'deleted' => 
							array (
								'container' => 'local-public',
								'directory' => 'deleted',
							),
					'temp' => 
							array(
								'container' => 'local-public',
								'directory' => 'temp',
							)
		)
);

\$wgImgAuthPublicTest = false;
";
			file_put_contents( $IP.'/LocalSettings.php', $azureSettings, FILE_APPEND );
		}
	}
	echo "</pre>";
	
	ob_end_clean();
	
	// Installation done. Now change the cli installer to web installer
    @rename($IP.'/mw-config/index.php', $IP.'/mw-config/index_cli.php');
    @rename($IP.'/mw-config/index_web.php', $IP.'/mw-config/index.php');
    
    @unlink($IP.'/mw-config/WpiSettings.php');
	
	header('Location: ../');
}

/**
 * Get the script path
 * 
 * @return String $wpiScriptPath The web platform installer script path
 */

function getScriptPath() {
    //setting the default value as empty
    $wpiScriptPath = '';
    $path = false;
	if ( !empty( $_SERVER['PHP_SELF'] ) ) {
		$path = $_SERVER['PHP_SELF'];
	} elseif ( !empty( $_SERVER['SCRIPT_NAME'] ) ) {
		$path = $_SERVER['SCRIPT_NAME'];
	}
	if ($path !== false) {
		$wpiScriptPath = preg_replace( '{^(.*)/(mw-)?config.*$}', '$1', $path );
	}
	return $wpiScriptPath;
}
