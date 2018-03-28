<?php

/* CATSDIR is the location of the cats root directory. If you're doing something weird like running cats from
 * by including cats/index.php from another directory, define CATSDIR relative to that place before your include.
 * Otherwise, this default will make things work for the normal case.
 */
if( !defined("CATSDIR") ) { define( "CATSDIR", "./" ); }


/* catsConfig.php
 * You have to create a file "catsConfig.php" in the directory above this one, to define directory dependencies.
 * Since the dependencies can be different for every installation, that file is not stored in git.
 * The following values should be defined. If you don't the defaults below will be used instead.

 * You probably want to define theses locations using CATSDIR, since it's probably the only fixed location that php knows.
 * e.g. defined( "SEEDROOT", CATSDIR."../seeds/" );
 *
 * SEEDROOT        = location of the "seeds" library e.g seeds/seedcore, seeds/seedlib.  Must have a trailing slash.
 * W_ROOT          = location of the directory where third-party software is collected.  Must have a trailing slash.
 * CATS_CONFIG_DIR = absolute directory where you keep your config files e.g. google api client keys
 */
include( CATSDIR."../catsConfig.php" );

if( !defined("SEEDROOT") ) define( "SEEDROOT", "../seeds/" );
if( !defined("W_ROOT") )   define( "W_ROOT", "../w/" );

if( !file_exists(SEEDROOT."seedcore/SEEDCore.php") ) die( "SEEDROOT is not correct: ".SEEDROOT );
if( !file_exists(W_ROOT."cats/i/img/CATS.png") )     die( "W_ROOT is not correct: ".W_ROOT );


define( "SEEDCORE", SEEDROOT."seedcore/" );

require_once SEEDCORE."SEEDCore.php" ;
require_once SEEDCORE."SEEDSessionAccount.php" ;
require_once SEEDROOT."Keyframe/KeyframeForm.php" ;
require_once SEEDROOT."Keyframe/KeyframeDB.php" ;

if( !defined("CATSDIR_IMG") ) { define( "CATSDIR_IMG", CATSDIR."i/img/" ); }

$dirImg = CATSDIR_IMG;
if( !isset($dirJQuery) )    { $dirJQuery =    "./jquery/"; }

?>