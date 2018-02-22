<?php

/* Classes to help draw the user interface
 */
class CATS_UI
{
    function __construct() {}

    function Header()
    {
        return( "<div class='cats_header'>"
               ."<img src='".CATSDIR_IMG."CATS.png' style='max-width:300px;float:left;'/>"
               ."<div style='float:right'><a href='?screen=home'>Home</a></div>"
               ."</div>"
               ."<div style='clear:both'>&nbsp;</div>"
              );
    }
}