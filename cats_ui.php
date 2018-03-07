<?php

/* Classes to help draw the user interface
 */
class CATS_UI
{

    private $screen = "";

    function __construct() {}

    function Header()
    {
        return( "<div class='cats_header'>"
               ."<img src='".CATSDIR_IMG."CATS.png' style='max-width:300px;float:left;'/>"
            ."<div style='float:right'>".($this->screen != "home"?"<a href='?screen=home'>Home</a>":"<a href='?screen=logout'>Logout</a>"."</div>")
               ."</div>"
               ."<div style='clear:both'>&nbsp;</div>"
              );
     }

     function OutputPage( $body )
     {
    $s =
    "<!DOCTYPE html>
    <html lang='en'>
    <head>
    <meta charset='utf-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1, shrink-to-fit=no'>
    <link rel='stylesheet' href='https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css' integrity='sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm' crossorigin='anonymous'>
    <script src=\"https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js\"></script>
    <script src='https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js' integrity='sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q' crossorigin='anonymous'></script>
    <script src='https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js' integrity='sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl' crossorigin='anonymous'></script>
    <style>
    a:link.toCircle, a:visited.toCircle, a:hover.toCircle, a:active.toCircle {
    	text-decoration: none;
    	color: black;
    }
    @keyframes colorChange {
    from {background-color: #b3f0ff; border-color: #b3f0ff;}
    to {background-color: #99ff99; border-color: #99ff99;}
    }
    </style>
    <script>
    function createCircle(elements, styles) {
    	for (var x in elements) {
    		var radius = styles[x][1], color = styles[x][2], textColor = styles[x][3];
    		elements[x].style.display = 'flex';
    		elements[x].style.height = 2 * radius + 'px';
    		elements[x].style.width = elements[x].style.height;
    		elements[x].style.justifyContent = 'center';
    		elements[x].style.alignItems = 'center';
    		elements[x].style.textAlign = 'center';
    		elements[x].style.marginBottom = '20px';
    		elements[x].style.color = textColor;
    		elements[x].style.borderStyle = 'inset outset outset inset';
            elements[x].style.borderWidth = '3px';
    		if(color == '#b3f0ff') {
    			elements[x].style.animation = 'colorChange 6s linear infinite alternate';
    		} else {
    			elements[x].style.animation = 'colorChange 6s linear -3s infinite alternate';
    		}
    		elements[x].style.borderRadius = radius + 'px';
    	}
    return true;
    }
    function run() {
        var x = document.querySelectorAll('.toCircle');
        var parse = [], elements = [];
        for(var y = 0; y < x.length; y++) {
    	   var classes = x[y].classList;
    	       for(var loop = 0; loop < classes.length; loop++) {
    		      if (classes.item(loop).search(/format-\d+-#?[\d\w]+-#?[\d\w]+/) !== -1) {
    			     elements.push(x[y]);
    			     parse.push(classes.item(loop).split('-'));
    		      }
    	       }
        }
    createCircle(elements, parse);
    }
    </script>
    </head>
    <body>"
    
    .$body
    
    
    
    ."<script> run(); </script>"
    ."</body></html>";

        return( $s );
    }


    function SetScreen($screen){
        $this->screen = $screen;
    }

}
