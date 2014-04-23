<?php

include "init.php";

$nlp = 10;
if ( $_GET["n"] ) $nlp = $_GET["n"];

/* Extract switches from GET array */

if ( $_GET["tm"] ) $tm = $_GET["tm"];
else die("Must give me a filter switch.");

$sd = ( $_GET['sd'] ) ? $_GET['sd'] : rand(1000,9999); 
srand($sd);

/* Build latex document */

$texfile  = "\\documentclass{article}\n";
$texfile .= "\\usepackage[margin=1in, paperwidth=8.5in, paperheight=11in]{geometry}\n";
$texfile .= "\\usepackage{p200}\n";
$texfile .= "\\usepackage[colorlinks=true,linkcolor=blue]{hyperref}\n";
$texfile .= "\\hypersetup{pdftitle = {PHY$tm Lecture Prep}}\n";
$texfile .= "\\hypersetup{pdfauthor = {David J. Ulrich}, pdfsubject = {Physics}}\n";$texfile .= "\n";$texfile .= "\\newcommand{\\prep}[1]{\\item #1}\n";$texfile .= "\\newcommand{\\hide}[1]{\\underline{\\makebox[4cm]{" . $hide . "}}}\n";$texfile .= "\\renewcommand{\\href}[2]{#2}\n";$texfile .= "\\renewcommand{\\jargon}[2][]{#2}\n";$texfile .= "\\renewcommand{\\footnote}[2][0pt]{}\n";$texfile .= "\n";$texfile .= "\\linespread{1.6}\n";
$texfile .= "\\begin{document}\n";

foreach ( $dt[$tm] as $wk => $val )
{

if ( $dt[$tm][$wk]['Mon'] != "Final Exam" )
{

$dochead = "Lecture Prep Week \#$wk";
$ky = $_GET['ky'];
if ( $ky ) 
{
  $hide = "\\textbf{#1}";
  $dochead .= " Key (Seed = $sd)";
}

$texfile .= "\\thispagestyle{empty}\n";
$texfile .= "\\begin{center}\n";
$texfile .= "\\LARGE Physics $tm \\\\ $dochead\n";
$texfile .= "\\end{center}\n";
$texfile .= "\\vspace{0.25in}\n";

$lnq = array();
foreach ( $ln as $key => $val )
  if ( $val['term'] == $tm and $val['week'] == $wk ) $lnq[] = $val;

$lpq = array();
for ( $k = 0; $k < count($lnq); $k++ )
{
  $x = explode("\prep{",$lnq[$k]['content']);

  for ( $i = 1; $i < count($x); $i++ )
  {
    $item  = "";
    $y[$i] = explode("\hide{",$x[$i]);
    $z[$i] = explode("}",$y[$i][1]);
    $item .= "\prep{" . ucfirst($y[$i][0]);
    $item .= "\hide{" . $z[$i][0] . "}";
    $j = 1;
    do { $item .= $z[$i][$j] . "}"; $j++; } 
    while ( strpos($z[$i][$j-1],"{") !== false );
    $item .= "\n";
    $lpq[] = $item;
  }
  shuffle($lpq);
}

//echo"<pre>";print_r($lpq);die;

$texfile .= "\n";$texfile .= "\\begin{enumerate}\n";//$texfile .= "\\setlength\\parskip{0.2in}\n";
for ( $i = 0; $i < $nlp; $i++ ) $texfile .= $lpq[$i];
$texfile .= "\\end{enumerate}\n";
$texfile .= "\n";
$texfile .= "\\newpage \n";
$texfile .= "\n";

}
}

//echo"<pre>";print_r($z);die;

/* End latex */

$texfile .= "\\end{document}\n";

include "compilelatex.php";

?>
