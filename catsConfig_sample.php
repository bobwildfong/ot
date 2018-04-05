<?php

/* catsConfig
 
   Copy this file to the directory above CATSDIR and change the definitions below as needed.
   The config information can be different for every installation, so it is not versioned in git.

   SEEDROOT        = location of the "seeds" library e.g seeds/seedcore, seeds/seedlib.  Must have a trailing slash.
   W_ROOT          = location of the directory where third-party software is collected.  Must have a trailing slash.
   CATS_CONFIG_DIR = absolute directory where you keep your config files e.g. google api client keys
*/

define( "SEEDROOT", CATSDIR."../seeds/" );
define( "W_ROOT",   CATSDIR."../w/" );
define( "CATS_CONFIG_DIR", "/home/cats/cats_config_dir/" );

?>