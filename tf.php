<?php

include "init.php";

/* Extract switches from GET array */

if ( $_GET["wk"] ) 
{
  $filterkey = "week";
  $filterval = $_GET["wk"];
  $tm = $week2term[$filterval];
} 
else if ( $_GET["tm"] ) 
{
  $filterkey = "term";
  $filterval = $_GET["tm"];
  $tm = $filterval;
} 
else die("Must give me a filter switch.");

$dochead = "True-False Statements";

/* Build latex document */

$texfile  = "\\documentclass[twocolumn]{article}\n";
$texfile .= "\\usepackage[margin=1in, paperwidth=8.5in, paperheight=11in]{geometry}\n";
$texfile .= "\\usepackage{p200}\n";
$texfile .= "\\usepackage[colorlinks=true,linkcolor=blue]{hyperref}\n";

$texfile .= "\\title{Physics $tm \\\\ $dochead}\n";
$texfile .= "\\hypersetup{pdftitle = {PHY$tm $dochead}}\n";
$texfile .= "\\hypersetup{pdfauthor = {David J. Ulrich}, pdfsubject = {Physics}}\n";

$texfile .= "\\begin{document}\n";
$texfile .= "\\maketitle\n";

for ( $i = 0; $i < count($tf); $i++ ) 
  if ( $tf[$i][$filterkey] == $filterval ) $tfq[] = $tf[$i];

$lastweek = -1;
for ( $i = 0; $i < count($tfq); $i++ )
{
  $week = $tfq[$i]['week'];
	
  if ( $week != $lastweek ) $texfile .= "\\section*{Week $week}\n";
  else $texfile .= "\\rule[0.5\\parskip]{0.5\\columnwidth}{0.1pt} \\par \n";

  $texfile .= "\\makebox[12mm]{\\textbf{True:}\\hfill} " . $tfq[$i]['T'] . " \\par \n";
  $texfile .= "\\makebox[12mm]{\\textbf{False:}\\hfill} " . $tfq[$i]['F'] . " \\par \n";
  $texfile .= "\\makebox[12mm]{\\textbf{Why?}\\hfill} " . $tfq[$i]['why'] . " \\par \n";

  $lastweek = $week;
}

/* End latex */

$texfile .= "\\end{document}\n";

include "compilelatex.php";

?>
