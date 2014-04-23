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

/* Build latex document */

foreach ( $pr as $key => $val )
  if ( $val[$filterkey] == $filterval ) $prx[] = $val;

$texfile  = <<<EOT
\\documentclass{book}
\\usepackage{p200feynman}
%\\hypersetup{pdftitle = {PHY$tm Projects}}

\\title{PHY$tm Projects}
\\author{David J. Ulrich}
\\date{}

\\renewcommand{\\chaptername}{Project}
\\begin{document}

EOT;

for ( $i = 0; $i < count($prx); $i++ )
{
    $texfile .= "\\chapter{" . $prx[$i]['title'] . "}\n\n";
    $texfile .= $prx[$i]['content'] . "\n";
}

/* End latex */

$texfile .= "\\end{document}\n";

include "compilelatex.php";

?>
