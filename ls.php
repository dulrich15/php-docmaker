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
else die("Must give me a filter switch.");

$lstitle[1] = "Prerequisites";
$lstitle[2] = "Measurements, Units, and Vectors";
$lstitle[3] = "Motion in 1D";
$lstitle[4] = "Motion in 2D";
$lstitle[5] = "Force and Equilibrium";
$lstitle[6] = "Newton's Laws of Motion";
$lstitle[7] = "Uniform Circular Motion";
$lstitle[8] = "Energy";
$lstitle[9] = "Momentum";
$lstitle[10] = "Rotation";
$lstitle[11] = "Celestial Mechanics";
$lstitle[12] = "Harmonic Motion";
$lstitle[13] = "Elasticity";
$lstitle[14] = "Fluids";
$lstitle[15] = "Heat and Temperature";
$lstitle[16] = "Kinetic Theory";
$lstitle[17] = "Thermodynamics";
$lstitle[18] = "Wave Motion and Radiation";
$lstitle[19] = "Wave Motion and Interference";
$lstitle[20] = "Geometric Optics";
$lstitle[21] = "Physical Optics";
$lstitle[22] = "Feynman Optics";
$lstitle[23] = "The Electric Field";
$lstitle[24] = "Electric Potential and Basic Electronics";
$lstitle[25] = "The Magnetic Field";
$lstitle[26] = "Electromagnetic Induction and AC Electronics";
$lstitle[27] = "Electromagnetic Radiation and Special Relativity";
$lstitle[28] = "Quantum Mechanics";
$lstitle[29] = "Radioactivity and Nuclear Energy";
$lstitle[30] = "The Standard Model";

/* Build latex document */

$texfile  = "\\documentclass{beamer}\n";
$texfile .= "\\usepackage{p200}\n";

if ( $_GET['up'] == 2 )
{
$texfile .= <<<EOT
\\usepackage{pgfpages}
\\pgfpagesuselayout{2 on 1}[letterpaper,border shrink=2mm]
\\pgfpageslogicalpageoptions{1}{border code=\\pgfusepath{stroke}}
\\pgfpageslogicalpageoptions{2}{border code=\\pgfusepath{stroke}}
\\pgfpageslogicalpageoptions{3}{border code=\\pgfusepath{stroke}}
\\pgfpageslogicalpageoptions{4}{border code=\\pgfusepath{stroke}}
EOT;
}

$texfile .= <<<EOT

% Beamer template controls

%\\usetheme{Luebeck}
\\useoutertheme{default}
\\usefonttheme{serif}
\\setbeamertemplate{navigation symbols}{}
\\setbeamersize{text margin left=4mm,text margin right=4mm}
\\setbeamertemplate{footline}[page number]

% Set up title slides

\\title{Physics $tm Lecture Slides}
\\author{David J. Ulrich}
\\date{}

% For circuit schematics
\\tikzstyle {res} = [decorate,decoration={zigzag,amplitude=1mm,segment length=2mm}]

% For Feynman diagrams
\\tikzstyle {actual} = [fill,circle,inner sep=0.5mm]
\\tikzstyle {virtual} = [actual,fill=white,draw]

\\tikzstyle {electron} = [postaction={decorate},decoration={markings,mark=at position 0.7 with {\\arrow{>}}}]

\\tikzstyle {photon} = [decorate,decoration={snake,segment length=1mm,amplitude=0.5mm}]
\\tikzstyle {gluon} = [decorate,decoration={coil,aspect=1,segment length=1mm,amplitude=0.5mm}]
\\tikzstyle {weak} = [decorate,decoration={zigzag,segment length=1mm,amplitude=0.5mm}]

% For drawing tables (periodic chart)
\\tikzstyle {nix} = [minimum size=12mm,inner sep=0mm]
\\tikzstyle {box} = [nix,draw,ultra thin]

\\newcommand{\\ep}[3]
{
\\begin{minipage}{10mm}
\\centering
\\tiny \\ifthenelse{\\equal{#1}{}}{~}{#1} \\\\
\\small \\ifthenelse{\\equal{#2}{}}{~}{#2} \\\\
\\tiny \\ifthenelse{\\equal{#3}{}}{~}{\\raisebox{-1mm}{#3}}
\\end{minipage}
}

\\begin{document}

%\\frame[plain]{\\maketitle}

%\\setlength\\parskip{0in}
%\\frame[squeeze]{\\small \\tableofcontents}
%\\setlength\\parskip{0.1in}

EOT;

$texfile .="\n";

for ( $i = 0; $i < count($ls); $i++ ) 
  if ( $ls[$i][$filterkey] == $filterval ) $lsx[] = $ls[$i];

$lect = -1;
for ( $i = 0; $i < count($lsx); $i++ )
{
  $n = $lsx[$i]['lecture'];
  if ( $lect != $n )
  {
    $lect = $n;
//    $tm = $lect2date[$filterval]['tm'];
    $wk = $lect2date[$filterval]['wk'];
    $dy = $lect2date[$filterval]['dy'];

    $rdgx = explode("\\ref{ch:",$ln[$n]['reading']);
    $rdg = $rdgx[0];
    foreach ( $rdgx as $rdgkey => $rdgval )
    {
      $rdglbl = explode("}",$rdgval);
      foreach ( $ln as $lnkey => $lnval )
        if ( $lnval['label'] == $rdglbl[0] ) $rdg .= $lnkey . $rdglbl[1];
    }

    $texfile .= "\\subsection{" . $ln[$n]['title'] . "}\n";
    $texfile .= "\\title{\\insertsubsection}\n";
    $texfile .= "\\author{Physics $tm}\n";
    $texfile .= "\\date{Lecture $n \\\\[5mm] \\tiny ($rdg)}\n";
    $texfile .= "\\maketitle\n";
    $texfile .= "\n";
  }
  $texfile .= "\\begin{frame}{" . $lsx[$i]['title'] . "}\n";
  $texfile .= "\\begin{center}\n";
  $texfile .= $lsx[$i]['content'] . "\n";
  $texfile .= "\\end{center}\n";
  $texfile .= "\\end{frame}\n\n";
}

/* End latex */

$texfile .= "\\end{document}";
// echo "<pre>"; die($texfile);
include "compilelatex.php";

?>
