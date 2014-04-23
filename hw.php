<?php

include "init.php";

/* Extract switches from GET array */

if ( $_GET["tm"] ) 
{
  $filterkey = "term";
  $filterval = $_GET["tm"];
  $tm = $filterval;
} 
else die("Must give me a term filter.");
if ( $_GET["wk"] ) // then reset filters...
{
  $filterkey = "week";
  $filterval = $_GET["wk"];
} 

$dh = $_GET["dh"];

switch ( $dh )
{
  case "hs":
    $dochead = "Homework Solutions";
    $docdata = "hw.dat";
  break;
  case "ha":
    $dochead = "Homework Answers";
    $docdata = "hw.dat";
  break;
  case "hp":
    $dochead = "Homework Problems";
    $docdata = "hw.dat";
  break;
  case "ex":
    $dochead = "Lecture Examples";
    $docdata = "hw.dat";
  break;
  default:
    die ("Document type '$dh' does not exist.");
}

/* Build latex document */
if ( $dh == "hs" )
{
$texfile  = "\\documentclass{book}\n";
$texfile .= "\\usepackage{p200feynman}\n";
$texfile .= "\\title{Physics $tm \\\\ $dochead}\n";
$texfile .= "\\author{}\n";
$texfile .= "\\date{}\n";
$texfile .= "\\hypersetup{pdftitle = {PHY$tm $dochead}}\n";
$texfile .= "\\hypersetup{pdfauthor = {David J. Ulrich}, pdfsubject = {Physics}}\n";
$texfile .= "\\renewcommand{\\chaptername}{Week}\n";
$texfile .= "\\begin{document}\n";
}
else
{
$texfile  = "\\documentclass{article}\n";
$texfile .= "\\usepackage{p200}\n";
$texfile .= "\\usepackage[colorlinks=true,linkcolor=blue]{hyperref}\n";
$texfile .= "\\title{Physics $tm \\\\ $dochead}\n";
$texfile .= "\\author{}\n";
$texfile .= "\\date{}\n";
$texfile .= "\\hypersetup{pdftitle = {PHY$tm $dochead}}\n";
$texfile .= "\\hypersetup{pdfauthor = {David J. Ulrich}, pdfsubject = {Physics}}\n";
$texfile .= "\\begin{document}\n";
$texfile .= "\\maketitle\n";
}

for ( $i = 0; $i < count($hw); $i++ ) 
  if ( $hw[$i][$filterkey] == $filterval ) 
    $hw2[] = $hw[$i];
//     if ( $hw[$i]['hw'] == 1 or $dh == 'ex' ) $hw2[] = $hw[$i];
//     if ( $hw[$i]['week'] > 0 ) $hw2[] = $hw[$i];

foreach ( $hw2 as $key => $val )
{
  $tmp[$key]  = substr(1000 + $val['week']      ,-3);
  $tmp[$key] .= substr(1000 + $val['difficulty'],-3);
  $tmp[$key] .= ( substr($val['label'],0,2) == 'du' ) ? 1 : 0;
  $tmp[$key] .= substr(1000 + $val['chapter']   ,-3);
  $tmp[$key] .= substr(1000 + $val['number']    ,-3);
}
asort($tmp);

foreach ( $tmp as $key => $val )
  $hwx[] = $hw2[$key];

// echo "<pre>"; print_r($hwx); die;

$lastsect = -1;
$firstshow = TRUE;
for ( $i = 0; $i < count($hwx); $i++ )
{
  $sect = $hwx[$i]['week'];

  $n  = $hwx[$i]['chapter'] . ".";
  $n .= $hwx[$i]['number'] . " ";
  $n .= str_repeat("*",$hwx[$i]['difficulty']);

  switch ( $dh )
  {
    case "hs":
      if ( $sect != $lastsect )
      {
//        if ( $lastsect != -1 ) $texfile .= "\\newpage\n";
        $texfile .= "\\setcounter{chapter}{" . ( $hwx[$i]['week'] - 1 ) . "}\n";
        $texfile .= "\\chapter[Week " . $hwx[$i]['week'] . "]{}\n";
        $lastsect = $sect;
      }
      $texfile .= "\n\\textbf{" . $n . "} \\quad ";
      $texfile .= $hwx[$i]['problem'] . " \\hspace{0pt} \\\\[\\parskip]\n";
      $texfile .= "\\textbf{Answer:} " . $hwx[$i]['answer'] . " \\\\[\\parskip]\n";
      $texfile .= $hwx[$i]['solution'] . "\n";
//      $texfile .= "\\vspace{1.5\\parskip} \\hrule \\vspace{\\parskip}\n";
      $texfile .= "\\vspace{0.5in}\n";
    break;
    case "ha":
      $texfile = str_replace("documentclass{article}","documentclass[twocolumn]{article}",$texfile);
      if ( $sect != $lastsect )
      {
//        if ( $lastsect != -1 ) $texfile .= "\\clearpage\n";
        $texfile .= "\\section*{Week $sect}\n";
        $lastsect = $sect;
      }
      $texfile .= "\n\\parbox[t]{1.5cm}{\\textbf{" . $n . "}}\n";
      $texfile .= "\\parbox[t]{5.5cm}{" . $hwx[$i]['answer'] . "}\n";
      $texfile .= "\\par\n";
    break;
    case "hp":
//      $texfile = str_replace("documentclass{article}","documentclass[twocolumn]{article}",$texfile);
      $texfile = str_replace("usepackage{p200}","usepackage{p200feynman}",$texfile);
      if ( $sect != $lastsect )
      {
        if ( $lastsect != -1 ) $texfile .= "\\clearpage\n";
        $texfile .= "\\section*{Week $sect}\n";
        $lastsect = $sect;
      }
//      $texfile .= "\n\\textbf{" . $n . "} ";
//      $texfile .= $hwx[$i]['problem'] . " \\\\[\\parskip]\n";
      $texfile .= "\n\\textbf{" . $n . "}\\marginpar{\\sf\\footnotesize " . $hwx[$i]['answer'] . "} ";
      $texfile .= $hwx[$i]['problem'] . "\n";
    break;
    case "ex":
//      $texfile = str_replace("documentclass{article}","documentclass[twocolumn]{article}",$texfile);
      $texfile = str_replace("usepackage{p200}","usepackage{p200feynman}",$texfile);
      if ( $sect != $lastsect )
      {
        if ( $lastsect != -1 ) $texfile .= "\\clearpage\n";
        $texfile .= "\\section*{Week $sect}\n";
        $lastsect = $sect;
      }
//      $texfile .= "\n\\textbf{" . $n . "} ";
//      $texfile .= $hwx[$i]['problem'] . " \\\\[\\parskip]\n";
      $texfile .= "\n";
      if ( $hwx[$i]['hw'] == 1 ) $texfile .= "\\fbox{";
      $texfile .= "\\textbf{" . $n . "}";
      if ( $hwx[$i]['hw'] == 1 ) $texfile .= "}";
      $texfile .= "\\marginpar{\\sf\\footnotesize " . $hwx[$i]['answer'] . "} ";
      $texfile .= "\\quad " . $hwx[$i]['problem'] . "\n";
    break;
    default:
      die ("Document type '$dh' does not exist.");
  }
}

// $texfile .= "\\end{enumerate}\n";

/* End latex */

$texfile .= "\\end{document}\n";

include "compilelatex.php";

?>
