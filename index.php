<?php

include_once( "_config.php" );
include( SEEDCore."SEEDCore.php" );

$screen = SEEDInput_Str( 'screen' );

if( $screen == 'admin' ) {
    $s = drawAdmin();
} else if( substr( $screen, 0, 9 ) == "therapist" ) {
    $s = drawTherapist( $screen );
} else {
    $s = drawHome();
}

echo $s;

function drawHome()
{
    $s = "";


    $s = "<h2>Home</h2>";

    $s .= "<a href='?screen=therapist'><button>Therapist</button></a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href='?screen=admin'><button>Admin</button></a>";

    return( $s );
}

function drawTherapist( $screen )
{
    $s = "<h2>Therapist</h2>";

    switch( $screen ) {
        case "therapist":
        default:
            $s .= "<p>What would you like to do?</p>"
                 ."<a href='?screen=home'><button>Home</button></a>"
                 ."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"
                 ."<a href='?screen=therapist-materials'><button>Print Materials</button></a>"
                 ."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"
                 ."<a href='?screen=therapist-formscharts'><button>Print Forms for Charts</button></a>"
                 ."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"
                 ."<a href='?screen=therapist-linedpapers'><button>Print Different Lined Papers</button></a>"
                 ."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"
                 ."<a href='?screen=therapist-entercharts'><button>Enter Charts</button></a>"
                 ."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"
                 ."<a href='?screen=therapist-clientRx'><button>Print Client Rx Activities</button></a>"
                 ."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"
                 ."<a href='?screen=therapist-ideas'><button>Get Ideas</button></a>"
                 ."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"
                 ."<a href='?screen=therapist-downloadcustommaterials'><button>Download and Customize Marketable Materials</button></a>"
                 ."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"
                 ."<a href='?screen=therapist-team'><button>Meet the Team</button></a>"
                 ."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"
                 ."<a href='?screen=therapist-submitresources'><button>Submit Resources to Share</button></a>"
                 ;
            break;
        case "therapist-materials":
            $s .= "PRINT MATERIALS";
            break;
        case "therapist-formscharts":
            $s .= "PRINT FORMS FOR CHARTS";
            break;
        case "therapist-linedpapers":
            $s .= "PRINT DIFFERENT LINED PAPERS";
            break;
        case "therapist-entercharts":
            $s .= "ENTER CHARTS";
            break;
        case "therapist-clientRx":
            $s .= "PRINT CLIENT Rx ACTIVITIES";
            break;
        case "therapist-ideas":
            $s .= "GET IDEAS";
            break;
        case "therapist-downloadcustommaterials":
            $s .= "DOWNLOAD AND CUSTOMIZE MARKETABLE MATERIALS";
            break;
        case "therapist-team":
            $s .= "MEET THE TEAM";
            break;
        case "therapist-submitresources":
            $s .= "SUBMIT RESOURCES";
            break;
    }

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
