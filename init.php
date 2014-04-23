<?php

function Dat2Array($filename)
{
  $filename = "content/" . $filename;
  $fh = fopen($filename, "r");
  $data = fread($fh, filesize($filename));
  fclose($fh);

  $data = str_replace("\r","",$data);
  $data = str_replace("@@@","\\",$data);

  $eol = "\n";
  $begdelim = "========$eol";
  $enddelim = "$eol========";
  $rowdelim = "$eol========$eol";
  $coldelim = "$eol--------$eol";

  $data = substr($data,strlen($begdelim));
  $data = substr($data,0,-strlen($enddelim));

  $r = explode($rowdelim, $data);
  for ( $i = 0; $i < count($r); $i++ )
    $tbl[$i] = explode($coldelim, $r[$i]);

  return $tbl;
}

/* Read in dates and build maps */

$tbl = Dat2Array('dt.dat');
for ( $i = 0; $i < count($tbl); $i++ )
{
  $tm = $tbl[$i][0];
  $x = explode("\n",$tbl[$i][1]);
  for ( $j = 0; $j < count($x); $j++ )
  {
    $wk = $j + 1;

    $y = explode(":",$x[$j]);
    $weekday = array('Mon','Wed');
    foreach ( $weekday as $k => $dy )
    {
      $dt[$tm][$wk][$dy] = trim($y[$k]);
      $z = explode(" ",$dt[$tm][$wk][$dy]);
      $n = intval($z[1]);
      if ( $z[0] == "Lecture" ) $lect2date[$n] = array( 'tm' => $tm, 'wk' => $wk, 'dy' => $dy );
      if ( $z[0] == "Lab"     ) $labr2date[$n] = array( 'tm' => $tm, 'wk' => $wk, 'dy' => $dy );
    }
  }
}

/*
for ( $i = 1; $i < 32; $i++ )
{
  if ( $i >=  1 and $i <=  9 ) $chap2term[$i] = "201";
  if ( $i >= 10 and $i <= 17 ) $chap2term[$i] = "202";
  if ( $i >= 18 and $i <= 23 ) $chap2term[$i] = "203";
  if ( $i >= 24 and $i <= 27 ) $chap2term[$i] = "202";
  if ( $i >= 28 and $i <= 32 ) $chap2term[$i] = "203";
}
*/

$season[201] = 'Winter';
$season[202] = 'Spring';
$season[203] = 'Summer';

// include "prob2week.php";
include "prob2lect.php";

/* Build arrays */

$tbl = Dat2Array('tf.dat');
for ( $i = 0; $i < count($tbl); $i++ )
{
  $x = explode(":",$tbl[$i][0]);
  $tf[$i]['lecture'] = intval($x[0]);
  $tf[$i]['week'] = $lect2date[$tf[$i]['lecture']]['wk'];
  $tf[$i]['term'] = $lect2date[$tf[$i]['lecture']]['tm'];
  $tf[$i]['T'] = $tbl[$i][1];
  $tf[$i]['F'] = $tbl[$i][2];
  $tf[$i]['why'] = $tbl[$i][3];
}

$tbl = Dat2Array('cj.dat');
for ( $i = 0; $i < count($tbl); $i++ )
{
  $x = explode(":",$tbl[$i][0]);

  $cj[$i]['label'] = $x[0];
  $cj[$i]['lecture'] = $prob2lect[$x[0]][0];
  $cj[$i]['week'] = $lect2date[$cj[$i]['lecture']]['wk'];
  $cj[$i]['term'] = $lect2date[$cj[$i]['lecture']]['tm'];
//  $cj[$i]['term'] = $prob2week[$x[0]][0];
//  $cj[$i]['week'] = $prob2week[$x[0]][1];
  $cj[$i]['source'] = substr($x[0],0,4);
  $cj[$i]['chapter'] = intval(substr($x[0],4,2));
  $cj[$i]['number'] = intval(substr($x[0],7,3));
  if ( $cj[$i]['source'] == "cj7e" ) $cj[$i]['number'] .= "[7e]";  
  $cj[$i]['difficulty'] = intval($x[1]);
  $cj[$i]['problem'] = $tbl[$i][1];
  $cj[$i]['answer'] = $tbl[$i][2];
  $cj[$i]['solution'] = $tbl[$i][3];
  $cj[$i]['hw'] = $prob2lect[$x[0]][1];

  $hw[] = $cj[$i];
}

$tbl = Dat2Array('du.dat');
for ( $i = 0; $i < count($tbl); $i++ )
{
  $x = explode(":",$tbl[$i][0]);

  $du[$i]['label'] = $x[0];
  $du[$i]['lecture'] = $prob2lect[$x[0]][0];
  $du[$i]['week'] = $lect2date[$du[$i]['lecture']]['wk'];
  $du[$i]['term'] = $lect2date[$du[$i]['lecture']]['tm'];
//  $du[$i]['term'] = $prob2week[$x[0]][0];
//  $du[$i]['week'] = $prob2week[$x[0]][1];
  $du[$i]['source'] = $x[2];
  $du[$i]['chapter'] = 'du';
  $du[$i]['number'] = intval(substr($x[0],2,3));  
  $du[$i]['difficulty'] = 5;
//  $du[$i]['difficulty'] = intval($x[1]);
  $du[$i]['problem'] = $tbl[$i][1];
  $du[$i]['answer'] = $tbl[$i][2];
  $du[$i]['solution'] = $tbl[$i][3];
  $du[$i]['hw'] = $prob2lect[$x[0]][1];

  $hw[] = $du[$i];
}

/*
$tbl = Dat2Array('pp.dat');
for ( $i = 0; $i < count($tbl); $i++ )
{
  $x = explode(":",$tbl[$i][0]);

  $pp[$i]['label'] = $x[0];
  $pp[$i]['lecture'] = $prob2lect[$x[0]][0];
  $pp[$i]['week'] = $lect2date[$pp[$i]['lecture']]['wk'];
  $pp[$i]['term'] = $lect2date[$pp[$i]['lecture']]['tm'];
//  $pp[$i]['term'] = $prob2week[$x[0]][0];
//  $pp[$i]['week'] = $prob2week[$x[0]][1];
  $pp[$i]['source'] = $x[2];
  $pp[$i]['chapter'] = 'pp';
  $pp[$i]['number'] = intval(substr($x[0],2,3));  
  $pp[$i]['difficulty'] = intval($x[1]);
  $pp[$i]['problem'] = $tbl[$i][1];
  $pp[$i]['answer'] = $tbl[$i][2];
  $pp[$i]['solution'] = $tbl[$i][3];
  $pp[$i]['hw'] = $prob2lect[$x[0]][1];

  $hw[] = $pp[$i];
}
*/

// echo "<pre>"; print_r($hw); die;

$tbl = Dat2Array('eq.dat');
for ( $i = 0; $i < count($tbl); $i++ )
{
  $x = explode(":",$tbl[$i][0]);
  $eq[$i]['lecture'] = $x[0];
  $eq[$i]['term'] = $lect2date[$eq[$i]['lecture']]['tm'];
  $eq[$i]['week'] = $lect2date[$eq[$i]['lecture']]['wk'];
  $eq[$i]['description'] = $x[1];
  $eq[$i]['equation'] = $tbl[$i][1];  
}

$tbl = Dat2Array('lb.dat');
for ( $i = 0; $i < count($tbl); $i++ )
{
  $x = explode(":",$tbl[$i][0]);
  $n = intval($x[0]);
  $lb[$n]['term'] = $labr2date[$n]['tm'];
  $lb[$n]['week'] = $labr2date[$n]['wk'];
  $lb[$n]['lab'] = $n;
  $lb[$n]['title'] = trim($x[1]);
  $x = explode("\n",$tbl[$i][1]);
  for ( $j = 0; $j < count($x); $j++ )
  {
    $y = explode(":",$x[$j]);
    $lb[$n]['equipment'][$j]['quantity'] = trim($y[0]);
    $lb[$n]['equipment'][$j]['items'] = trim($y[1]);
    $lb[$n]['equipment'][$j]['location'] = trim($y[2]);
  }
  $x = explode("\n",$tbl[$i][2]);
  $lb[$n]['instructions'] = array(trim($x[0]),trim($x[1]),trim($x[2]));
  $lb[$n]['content'] = $tbl[$i][3];
}

$tbl = Dat2Array('ln.dat');
for ( $i = 0; $i < count($tbl); $i++ )
{
  $x = explode(":",$tbl[$i][0]);
  $n = intval($x[0]);
  $ln[$n]['n'] = $n;
  $ln[$n]['term'] = $lect2date[$n]['tm'];
  $ln[$n]['week'] = $lect2date[$n]['wk'];
  $ln[$n]['lecture'] = $n;
  $ln[$n]['label'] = $x[1];
  $ln[$n]['title'] = $tbl[$i][1];
  $ln[$n]['reading'] = $tbl[$i][2];
  $ln[$n]['outline'] = explode("\n",$tbl[$i][3]);
  $ln[$n]['content'] = $tbl[$i][4];
}

// need preg recursive match ?? http://www.php.net/manual/en/function.preg-match-all.php#89092
//  $str = $ln[6]['content'];
//  preg_match_all('/\\\eq({.*})({.*})({.*})/s', $str, $matches);
//  echo "<pre>"; print_r($matches); die;

$eq = array();

//for ( $k = 0; $k < count($ln); $k++ )
foreach ( $ln as $k => $val )
{

$q = explode("\eq{",$val['content']);

for ( $j = 1; $j < count($q); $j++ )
{
$str = $q[$j];

//$qq[$k][$j] = "\eq{";
$qqq[$k][$j][0] = "";
$qqq[$k][$j][1] = "";
$qqq[$k][$j][2] = "";

$n0 = 0; $n1 = 1; $n2 = 0;
for ( $i = 0; $i < strlen($str); $i++ )
{
$x = substr($str,$i,1);
if ( $x == "{" ) $n1++;
if ( $x == "}" ) $n2++;
if ( $n1 == $n2 ) $n0++;
if ( $n0 <= 3 ) {
//$qq[$k][$j] .= $x;
$qqq[$k][$j][$n0] .= $x;
}
}
$eq[] = array(
'lecture' => $k,
'term' => $lect2date[$k]['tm'],
'week' => $lect2date[$k]['wk'],
'equation' => str_replace("\\frac","\\dfrac",trim(substr($qqq[$k][$j][1],2))),
'description' => substr($qqq[$k][$j][2],2),
'label' => $qqq[$k][$j][0]
);
}
}

$tbl = Dat2Array('ls.dat');
for ( $i = 0; $i < count($tbl); $i++ )
{
  $x = $tbl[$i][0];
  $n = intval(substr($x,0,2));
  $ls[$i]['term'] = $lect2date[$n]['tm'];
  $ls[$i]['week'] = $lect2date[$n]['wk'];
  $ls[$i]['lecture'] = $n;
  $ls[$i]['slide'] = intval(substr($x,2,2));
  $ls[$i]['title'] = trim(substr($x,5));
  $ls[$i]['content'] = $tbl[$i][1];
}

$tbl = Dat2Array('pr.dat');
for ( $i = 0; $i < count($tbl); $i++ )
{
  $x = explode(":",$tbl[$i][0]);
  $pr[$i]['term'] = $x[0];
  $pr[$i]['title'] = $x[1];
  $pr[$i]['content'] = $tbl[$i][1];
}

?>
