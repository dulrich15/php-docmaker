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

$dh = $_GET['dh'];

switch ( $dh )
{
  case "lw" : $dochead = "Lab Worksheets"; break;
  case "lf" : $dochead = "Lab Equipment Forms"; break;
  default   : die ("Document type '$dh' does not exist.");
}

/* Build latex document */

foreach ( $lb as $key => $val )
  if ( $val[$filterkey] == $filterval ) $lbx[$key] = $val;

switch ( $dh )
{
  case "lw" :
$texfile  = <<<EOT
\\documentclass{book}
\\usepackage{p200feynman}
\\hypersetup{pdftitle = {PHY$tm $dochead}}
\\renewcommand{\\chaptername}{Lab}
\\begin{document}

EOT;
  break;
  case ( "lf" ) :
$texfile  = <<<EOT
\\documentclass{article}
\\usepackage{p200}
\\usepackage[top=1.25in,bottom=1.25in,left=1.25in,right=1.25in]{geometry}
\\hypersetup{pdftitle = {PHY$tm $dochead}}

\\begin{document}

EOT;
}

$lastsect = -1;
foreach ( $lbx as $n => $val )
{
  $wk = $labr2date[$n]['wk'];
  $title = "{" . $val['title'] . "}";

  switch ( $dh )
  {
    case "lw" :
    if ( $n != $lastsect )
    { 
      $texfile .= "\\setcounter{chapter}{" . ( $val['lab'] - 1 ) . "}\n";
      $texfile .= "\\chapter$title\n";
    }
    $lastsect = $n;

    $texfile .= "\\section*{Necessary Materials}\n";
    $texfile .= "\\begin{items}\n";
    foreach ( $val['equipment'] as $k => $equip )
      $texfile .= "\\item " . $equip['items'] . "\n";
    $texfile .= "\\end{items}\n";
    $texfile .= $val['content'];
    break;

    case "lf" :

$texfile .= <<<EOT

\\newpage 

\\begin{center}
\\textbf{\\large LABORATORY EQUIPMENT REQUEST FORM}
\\par
\\renewcommand{\\arraystretch}{1.5}
\\renewcommand{\\tabcolsep}{0.2cm}
\\begin{tabular}{p{1.25in}p{1.25in}p{1.25in}p{1.25in}}
INSTRUCTOR: & David Ulrich & LAB SECTION: & PHY $tm \\\\
DATE(S) NEEDED: & \\textbf{Week $wk} & TIME NEEDED: & \\textbf{6:00 pm} \\\\
LAB TITLE: & \\multicolumn{3}{l}{\\textbf$title} \\\\
&&& \\\\
FILLED BY: & \\underline{\\makebox[1in]{}} & DATE FILLED: & \\underline{\makebox[1in]{}} \\\\
\\end{tabular}
\\end{center}

\\tbl{}{|c|p{2.5in}|p{1.0in}|}
{
\\hline
\\makebox[1.0in]{\\textbf{Quantity}} & 
\\makebox[2.5in]{\\textbf{\\centering Items}} & 
\\makebox[1.0in]{\\textbf{Location}} \\\\
\\hline

EOT;

    foreach ( $val['equipment'] as $i => $equip )
    {
      $texfile .= trim($equip['quantity']) . " & ";
      $texfile .= trim($equip['items']) . " & ";
      $texfile .= trim($equip['location']) . " \\\\ \n ";
    }

$texfile .= <<<EOT
\\hline
}{}

\\vspace{0.2in}
Special Instructions: \\par

EOT;

    foreach ( $val['instructions'] as $i => $instructions )
    {
      $texfile .= "\\underline{\\makebox[\\textwidth]{\\textbf{";
      $texfile .= $instructions;
      $texfile .= "}}} \\par\n";
    }

$texfile .= <<<EOT
\\vspace{0.2in}
Please allow \\underline{two weeks} to process requests.


EOT;

    break;
  }
}

/* End latex */

$texfile .= "\\end{document}\n";

include "compilelatex.php";

?>
