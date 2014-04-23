<?php

include "init.php";

/* Extract switches from GET array */

if ( $_GET["le"] ) 
{
  $filterkey = "lecture";
  $filterval = $_GET["le"];
  $tm = $lect2date[$filterval]['tm'];
} 
else if ( $_GET["tm"] ) 
{
  $filterkey = "term";
  $filterval = $_GET["tm"];
  $tm = $filterval;
} 
else if ( $_GET["wk"] ) 
{
  $filterkey = "week";
  $filterval = $_GET["wk"];
  $tm = $week2term[$filterval];
} 
else 
{
  $filterkey = "term";
  $filterval = "200";
  $tm = $filterval;
} 

/* Read in lecture notes data */

foreach ( $ln as $key => $val )
  if ( $val[$filterkey] == $filterval or $tm == 200 ) $lnq[] = $val;

/* Build latex document */

if ( $_GET['outline'] )
{

$texfile  = "\\documentclass{beamer}\n";
$texfile .= "\\usepackage{p200beamer}\n";
$texfile .= "\n";
$texfile .= "%\\usetheme{Luebeck}\n";
$texfile .= "\\useoutertheme{default}\n";
$texfile .= "\\usefonttheme{serif}\n";
$texfile .= "\\setbeamertemplate{navigation symbols}{}\n";
$texfile .= "\\setbeamersize{text margin left=4mm,text margin right=4mm}\n";
$texfile .= "\\setbeamertemplate{footline}[page number]\n";
$texfile .= "\n";
$texfile .= "\\begin{document}\n";

for ( $i = 0; $i < count($lnq); $i++ )
{
$texfile .= "\n";
$texfile .= "\\begin{frame}{" . $lnq[$i]['title'] . "}{Lecture " . $lnq[$i]['lecture'] . "}\n";
$texfile .= "\\begin{enum}\n";
foreach ( $lnq[$i]['outline'] as $item => $text ) $texfile .= "\\item " . $text . "\n";
$texfile .= "\\end{enum}\n";
$texfile .= "\\end{frame}\n";
if ( $_GET['outline'] == 2 )
foreach ( $lnq[$i]['outline'] as $item => $text ) 
{
$texfile .= "\\begin{frame}{" . $text . "}";
$texfile .= "{Lecture " . $lnq[$i]['lecture'] . " : " . $lnq[$i]['title'] . "}\n";
$texfile .= "\\end{frame}\n";
}
}

}
else
{

$texfile  = "\\documentclass{book}\n";
$texfile .= "\\usepackage{p200feynman}\n";
$texfile .= "\\renewcommand{\\chaptername}{Lecture}\n";
$texfile .= "\\hypersetup{pdftitle = {PHY$tm Lecture Notes}}\n";
$texfile .= "\\makeindex\n";
$texfile .= "\n";
$texfile .= "\\begin{document}\n";
$texfile .= "%\\renewcommand{\\contentsname}{Physics $tm \\\\ Lecture Notes}\n";
$texfile .= "\\setlength\parskip{-2mm}\n";
$texfile .= "\\tableofcontents\n";
$texfile .= "\\setlength\parskip{0.1in}\n";
$texfile .= "\n";

$term = -1;
for ( $i = 0; $i < count($lnq); $i++ )
{
  if ( $term != $lnq[$i]['term'] ) 
  {
    $term = $lnq[$i]['term'];
    $texfile .= "\n";
    $texfile .= "% \\part{Physics $term} \\label{pt:p$term} \n";
  }
  $label = $lnq[$i]['label'];
  $title = $lnq[$i]['title'];
  $rdg = $lnq[$i]['reading'];
  $texfile .= "\n\n";
  $texfile .= "\\setcounter{chapter}{" . ( $lnq[$i]['lecture'] - 1 ) . "}\n";
  $texfile .= "\\chapter[$title]{" . $title . " \\\\[5mm] \\small{" . $rdg . "}} ";
  $texfile .= "\\label{ch:$label} \n\n";
  $texfile .= $lnq[$i]['content'];
}

$texfile .= "\n\n";
$texfile .= "\\printindex\n";

}

/* End latex */

$texfile .= "\n";
$texfile .= "\\end{document}";

include "compilelatex.php";

?>
