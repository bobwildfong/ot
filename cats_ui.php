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

     function Login()
     {
         return( "<div class='cats_login' style='display:block;border:1px solid #ccc; padding:10px; width:500px'>"
                  ."<div style='width:80%margin:0px auto;'>"
                    ."<h2 style='margin-bottom:20px'>Please Login to CATS</h2>"
                    ."<form method='post'>"
                      ."<div class='row'>"
                        ."<div class='col-md-3' style='text-align:right'>User</div>"
                        ."<div class='col-md-9'><input type='text' name='userid' value='' placeholder='Your first name'/></div>"
                      ."</div><div class='row' style='margin-top:10px'>"
                        ."<div class='col-md-3'>&nbsp;</div>"
                        ."<div class='col-md-9'><input type='submit' value='Login'/></div>"
                      ."</div>"
                    ."</form>"
                  ."</div>"
                ."</div>"
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
.otButton { border:1px solid #aaa; width:80%; text-align:center; padding: 20px 0px;};
</style>
<script>
function createCircle(toStyle, radius, color, textColor) {
	toStyle.style.display = 'flex';
	toStyle.style.height = 2 * radius + 'px';
	toStyle.style.width = toStyle.style.height;
	toStyle.style.justifyContent = 'center';
	toStyle.style.alignItems = 'center';
	toStyle.style.marginBottom = '20px';
    toStyle.style.textAlign = 'center';
	toStyle.style.color = textColor;
	toStyle.style.borderStyle = 'inset outset outset inset';
	toStyle.style.borderColor = color;
	toStyle.style.backgroundColor = color;
	toStyle.style.borderRadius = radius + 'px';
	return true;
}
function run() {
    var x = document.querySelectorAll('a.toCircle');
    for(var y = 0; y < x.length; y++) {
	   var classes = x[y].classList;
	   for(var loop in classes) {
		  if (classes.item(loop).search(/format-\d+-#?[\d\w]+-#?[\d\w]+/) !== -1) {
			 var parse = classes.item(loop).split('-');
			 createCircle(x[y], parse[1], parse[2], parse[3]);
		  }
	   }
    }
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
