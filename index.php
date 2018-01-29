<?php

include( "../seedcore/SEEDCore.php" );

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
    
    
    return( $s );
}

function drawTherapist()
{
    $s = "";
    
    
    return( $s );
}

function drawAdmin()
{
    $s = "";
    
    
    return( $s );
}

?>
