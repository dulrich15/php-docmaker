<?php

include "init.php";

$tm = $_GET['tm'];
$yr = ( $_GET['yr'] ) ? $_GET['yr'] : 2011;

$firstmonday = 1 + ( ( 7 + 1 - strftime("%w", mktime(0,0,0,1,1,$yr)) ) % 7 );
$day = $firstmonday + 7 * 12 * ( $tm - 201 );

$ics  = "BEGIN:VCALENDAR\n";

foreach ( $dt[$tm] as $wk => $wkdt )
foreach ( $wkdt as $dy => $topic )
if ( $topic )
{
  $begdate = mktime(26,0,0,1,$day,$yr);
  $enddate = mktime(28,50,0,1,$day,$yr);
  $begdate = mktime(18,0,-date('Z'),1,$day,$yr);
  $enddate = mktime(20,50,-date('Z'),1,$day,$yr);
  $z = explode(" ",$topic);
  switch ( trim($z[0]) )
  {
    case "Lecture" :
      $n = intval($z[1]);
      $smry = "Lecture $n";
      $subdesc = explode("\\ref{ch:",$ln[$n]['reading']);
      $desc = $ln[$n]['title'] . ". " . $subdesc[0];
      foreach ( $subdesc as $key => $val )
      {
        if ( $key > 0 ) 
        {
          $sd = explode("}",$val);
          $label = $sd[0];
          foreach ( $ln as $lnkey => $lnval ) if ( $lnval['label'] == $label ) $val = str_replace($val,$label,$lnkey) . $sd[1];
          $desc .= $val;
        }
      }
    break;
    case "Lab" :
      $n = intval($z[1]);
      $smry = "Lab $n";
      $desc = $lb[$n]['title'] . ". Review lab, prep for quiz.";
    break;
    default :
      $smry = strtoupper(trim($topic));
      $desc = "";
  }

  $ics .= "BEGIN:VEVENT\n";
  $ics .= "DTSTART:" . strftime("%Y%m%dT%H%M%SZ", $begdate) . "\n";
  $ics .= "DTEND:" . strftime("%Y%m%dT%H%M%SZ", $enddate) . "\n";
  $ics .= "SUMMARY:$smry\n";
  $ics .= "LOCATION:Rock Creek Bldg 7 Rm ";
  $ics .= ( $dy == "Mon" ) ? 223 : 225; 
  $ics .= "\n";
  $ics .= "DESCRIPTION:$desc\n";
  $ics .= "END:VEVENT\n";

  $day += ( $dy == 'Mon' ) ? 2 : 5; 
}

$ics .= "END:VCALENDAR";

echo "<pre>$ics";

?>
