<?php

include_once( "_config.php" );
include( SEEDCore."SEEDCore.php" );

$screen = SEEDInput_Str( 'screen' );

switch( $screen ) {
    case 'admin':     $s = drawAdmin();      break;
    case 'therapist': $s = drawTherapist();  break;
    default:          $s = drawHome();       break;
}

echo $s;

function drawHome()
{
    $s = "";


    $s = "<h2>Home</h2>";

    $s .= "<a href='?screen=therapist'><button>Therapist</button></a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href='?screen=admin'><button>Admin</button></a>";

    return( $s );
}

function drawTherapist()
{
    $s = "";

    $s = "<h2>Therapist</h2>";

    $s .= "<a href='?screen=home'><button>Home</button></a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href='?screen=admin'><button>Admin</button></a>";

    return( $s );
}

function drawAdmin()
{
    $s = "";

    $s = "<h2>Admin</h2>";

    $s .= "<a href='?screen=home'><button>Home</button></a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href='?screen=therapist'><button>Therapist</button></a>";

    return( $s );
}

?>
