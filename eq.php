<?php

include "init.php";

/* Extract switches from GET array */

if ( $_GET["le"] ) 
{
  $filterkey = "lecture";
  $filterval = $_GET["le"];
  $tm = $lect2date[$filterval]['tm'];
} 
else if ( $_GET["wk"] ) 
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
else 
{
  $filterkey = "term";
  $filterval = "200";
  $tm = $filterval;
} 

$dochead = "Equation Summary";

/* Build latex document */

$texfile  = <<<EOT
%\\documentclass[twocolumn,landscape]{article}
\\documentclass{article}

\\usepackage{p200}
\\hypersetup{pdftitle = {PHY$tm $dochead}}

\\title{PHY$tm $dochead}
\\author{David J. Ulrich}
\\date{}

\\setcounter{secnumdepth}{0}
%\\newcommand{\\pd}[2]{\\dfrac{\\partial #1}{\\partial #2}}

\begin{document}

\maketitle

EOT;

for ( $i = 0; $i < count($eq); $i++ ) 
  if ( $eq[$i][$filterkey] == $filterval or $tm == 200 ) $eq2[] = $eq[$i];

foreach ( $eq2 as $key => $val )
{
  $tmp[$key]  = 100000 + 1000 * $val['lecture'];
  $tmp[$key] += $key;
//  $tmp[$key] .= $val['description'];
}
asort($tmp);

foreach ( $tmp as $key => $val ) $eqx[] = $eq2[$key];

$lastlect = -1;
for ( $i = 0; $i < count($eqx); $i++ )
{
  $lect = $eqx[$i]['lecture'];
  if ( $lect != $lastlect ) $texfile .= "\\section{Lecture $lect}\n";

  $texfile .= "\\parbox{0.47\\columnwidth}{\\flushleft " . $eqx[$i]['description'] . "}\n";
  $texfile .= "\\parbox{0.04\\columnwidth}{~}\n";
  $texfile .= "\\parbox{0.47\\columnwidth}{\begin{equation*}\n" . $eqx[$i]['equation'] . "\n\end{equation*}}\n";

  $lastlect = $lect;
}

/* End latex */

$texfile .= "\\end{document}\n";

include "compilelatex.php";

?>
