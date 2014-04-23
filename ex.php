<?php

include "init.php";

/* Extract switches from GET array */

if ( $_GET["tm"] ) 
{
  $filterkey = "term";
  $filterval = $_GET["tm"];
  $tm = $filterval;
} 
else die("Must give me a filter switch.");

$sd = ( $_GET['sd'] ) ? $_GET['sd'] : rand(1000,9999); 
srand($sd);

$dh = $_GET['dh'];
$ky = $_GET['ky'];

$ntf = 0; 
$nhw = 6; 
$dochead = "Final Exam";

if ( $ky == "a" ) $dochead .= " Key (Seed = $sd)";
if ( $ky == "s" ) $dochead .= " Solutions (Seed = $sd)";

/* Latex preamble */

$texfile  = "\\documentclass{article}\n";
$texfile .= "\\pagestyle{headings}\n";

$texfile .= "\\usepackage{p200}\n";
$texfile .= "\\usepackage[colorlinks=true,linkcolor=blue]{hyperref}\n";

$texfile .= "\\hypersetup{pdftitle = {PHY$tm $dochead}}\n";
$texfile .= "\\hypersetup{pdfauthor = {David J. Ulrich}, pdfsubject = {Physics}}\n";

$texfile .= "% Seed = $sd\n";
$texfile .= "\\begin{document}\n";

$texfile .= "\\begin{tikzpicture}[remember picture,overlay]\n";
$texfile .= "\\node [xshift=-1in,yshift=-1in] at (current page.north east) [below left] {Name: \\underline{\\makebox[2in]{}}};\n";
// $texfile .= "\\node [xshift=1in,yshift=-1in] at (current page.north west) [below right] {\\tiny " . $q . "};\n";
$texfile .= "\\end{tikzpicture}\n";

$texfile .= "\\begin{center}\n";
$texfile .= "\\LARGE Physics $tm \\\\ $dochead\n";
$texfile .= "\\end{center}\n";

$texfile .= "\\thispagestyle{empty}\n";

/* Show TF */

if ( $ntf > 0 )
{

$texfile .= "\\section*{True or False?}\n";
$texfile .= "Mark whether the following statements are true or false. (One point each.)\n";
$texfile .= "\\begin{enumerate}\n";

$tfq = array();
foreach ( $tf as $key => $val ) 
  if ( $val['term'] == $tm ) 
    $tfq[] = $val;
shuffle($tfq);

for ( $i = 0; $i < $ntf; $i++ )
{
  $ans = ( rand(1,2) == 1 ) ? "T" : "F";
  $texfile .= "\\item \\parbox[t]{0.4in}{\\centering \\underline{\\makebox[0.3in]{\\textbf{";
  if ( $ky ) $texfile .= $ans;
  $texfile .= "}}}} \\parbox[t]{4.0in}{";
  $texfile .= $tfq[$i][$ans];
  $texfile .= "} \\par \n";
}

$texfile .= "\\end{enumerate}\n";

}

/* Show HW */

if ( $ntf > 5 ) $texfile .= "\\newpage \n";
$texfile .= "\\section*{Word Problems}\n";
$texfile .= "Show all your work and circle your final answer. (Ten points each.) \par\n";

$hwq = array();
foreach ( $hw as $key => $val ) 
  if ( $val['term'] == $tm and $val['difficulty'] < 2 and $val['hw'] == 1 ) 
    $hwq[] = $val;
shuffle($hwq);

// echo "<pre>"; print_r($hwq); die;

foreach ( $hwq as $key => $val )
{
  $x = explode("\\tweak{",$val['problem']);
  if ( count($x) > 1 ) 
  {
    $n = strpos($x[1],"}");
    $y = substr($x[1],0,$n);
    $yy = substr($x[1],$n+1);
    $d = strlen(substr(strrchr($y, "."), 1));
    if ( $d == 0 )
    {
      $d = -5;
      do $d++; while ( round($y,$d) != $y or $d == 5 );
    }
    $z = $y * ( 1 + 0.2 * rand(-10,10) / 10 );
    $z = number_format(round($z,$d),$d,'.',',');
    if ( $ky ) $hwq[$key]['problem'] =  $x[0] . "\\fbox{" . $z . "}" . $yy;
    else $hwq[$key]['problem'] =  $x[0] . $z . $yy;
    $hwq[$key]['answer'] = "Tweaked---with " . $y . " answer was " . $hwq[$key]['answer'];
  }
}

// echo "<pre>"; print_r($hwq); die;

for ( $i = 0; $i < $nhw; $i++ )
{
  if ( $i > 0 ) $texfile .= "\\newpage\n";
  $dtag = ( $hwq[$i]['difficulty'] > 0 ) ? " " . str_repeat("*",$hwq[$i]['difficulty']) : "";
  $texfile .= "\\textbf{" . ($i + 1) . "." . $dtag . "} \\quad ";
//  $texfile .= "\\textbf{" . $dx[1] . $dtag . "} \\quad ";
  $texfile .= $hwq[$i]['problem'] . "\n";
  if ( $ky ) $texfile .= "\\par \\textbf{Answer: } " . $hwq[$i]['answer'] . "\n";
  if ( $ky == "s" ) $texfile .= "\\par " . $hwq[$i]['solution'] . "\n";
}

/* End latex */

$texfile .= "\\end{document}";

include "compilelatex.php";

?>
