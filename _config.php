<?php

/* While in development, enable all error reporting. When this code is in real-world use, this should be commented out.
 */
error_reporting(E_ALL | E_STRICT);
ini_set('display_errors', 1);
ini_set('html_errors', 1);


/* CATSDIR is the location of the cats root directory. If you're doing something weird like running cats from
 * by including cats/index.php from another directory, define CATSDIR relative to that place before your include.
 * Otherwise, this default will make things work for the normal case.
 */
if( !defined("CATSDIR") ) { define( "CATSDIR", "./" ); }


/* catsConfig.php
 *
 * Copy catsConfig_sample.php to the directory above this one and rename it to catsConfig.php.
 * Since the dependencies can be different for every installation, the config file is not stored in git.
 */
include( CATSDIR."../catsConfig.php" );

if( !defined("SEEDROOT") ) define( "SEEDROOT", "../seeds/" );
if( !defined("W_ROOT") )   define( "W_ROOT", "../w/" );

if( !file_exists(SEEDROOT."seedcore/SEEDCore.php") ) die( "SEEDROOT is not correct: ".SEEDROOT );

define( "SEEDCORE", SEEDROOT."seedcore/" );

require_once SEEDCORE."SEEDCoreForm.php";
require_once SEEDCORE."SEEDCore.php";
require_once SEEDROOT."seedapp/SEEDApp.php" ;
require_once SEEDROOT."Keyframe/KeyframeForm.php" ;
require_once SEEDROOT."Keyframe/KeyframeDB.php" ;
require_once SEEDROOT."DocRep/DocRepDB.php" ;

require_once "documents.php";


if( !defined("CATSDIR_IMG") ) { define( "CATSDIR_IMG", CATSDIR."i/img/" ); }

$dirImg = CATSDIR_IMG;

?>