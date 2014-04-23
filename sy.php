<?php

date_default_timezone_set('America/Los_Angeles');

include "init.php";

$tm = $_GET['tm'];
$yr = ( $_GET['yr'] ) ? $_GET['yr'] : 2011;
$dochead = "Syllabus";
$season = $season[$tm];

switch ( $tm )
{
  case "201" : 
    $docsub = "The Motion of Idealized Systems";
    $chaps = "1--9";
    $topics = "projectiles, statics, Newton's laws, energy, collisions, circular motion and rotational dynamics";
    if ( $yr == 2010 ) $crns = "11087 and 12547";
    if ( $yr == 2011 ) $crns = "11028 and 12334";
  break;
  case "202" : 
    $docsub = "The Properties of Matter and Energy";
    $chaps = "10--17 and 24--27";
    $topics = "elastic and fluid motion, heat and temperature, kinetic theory, wave motion, sound, and light";
    if ( $yr == 2010 ) $crns = "20969 and 22469";
    if ( $yr == 2011 ) $crns = "20896 and 22248";
  break;
  case "203" : 
    $docsub = "The Microscopic Sources of Force";
    $chaps = "18--24 and 28--32";
    $topics = "electromagnetism, relativity, quantum mechanics, and nuclear science";
    if ( $yr == 2010 ) $crns = "31353 and 31361";
    if ( $yr == 2011 ) $crns = "31201 and 31209";
  break;
  default : die("Term must be 201, 202, or 203 only.");
}

//$gs[] = array('category' => "Lab Work",     'percent' => 0.40);
//$gs[] = array('category' => "Quizzes",      'percent' => 0.30);
//$gs[] = array('category' => "Final Exam",   'percent' => 0.20);
//$gs[] = array('category' => "Supplemental", 'percent' => 0.10);

//$gs[] = array('category' => "Lecture Prep", 'percent' => 0.10);
$gs[] = array('category' => "Lab Work",     'percent' => 0.20);
$gs[] = array('category' => "Quizzes",      'percent' => 0.30);
$gs[] = array('category' => "Final Exam",   'percent' => 0.40);
$gs[] = array('category' => "Supplemental", 'percent' => 0.10);

/* Build latex document */

$texfile = <<<EOT
\\documentclass{article}
\\usepackage{p200}

\\usepackage[colorlinks=true,linkcolor=blue]{hyperref}
\\hypersetup{pdftitle = {PHY$tm $dochead}}
\\hypersetup{pdfauthor = {David J. Ulrich}, pdfsubject = {Physics}}

\\title{Physics $tm $dochead \\\\ $docsub}
\\author{David J. Ulrich}
\\date{ $season $yr }

\\begin{document}
\\maketitle

EOT;

/* Document body */

$texfile .= <<<EOT
\\section{Course Information}

\\begin{tabular}{@{}p{2cm}|l}
\\textbf{CRN}        & $crns \\\\
\\textbf{Date/Time}  & Mon, Wed 6:00-8:50 pm \\\\
\\textbf{Campus}     & PCC, Rock Creek Campus, Bldg 7 \\\\
\\textbf{Room}       & 223 (Mon) and 225 (Wed) \\\\
\\end{tabular}

\\section{Contact Information}

\\begin{tabular}{@{}p{2cm}|l}
\\textbf{Instructor} & David J. Ulrich \\\\
\\textbf{Office}     & Building 7, Room 202 \\\\
\\textbf{E-mail}     & \\href{mailto:david.ulrich15@pcc.edu}{\\url{david.ulrich15@pcc.edu}} \\\\
\\textbf{Website}    & \\url{http://spot.pcc.edu/~dulrich} \\\\
\\end{tabular}

\\section{Course Overview}

\\paragraph{Textbook} Physics, by Cutnell and Johnson, 8th edition.

This course will cover Chapters $chaps in the text and include such topics as $topics. 
Each Monday session will be held in Room 223. This room contains much of the materials 
used for the labs. Therefore labs will fall on the Monday meetings. The Wednesday sessions 
will consist solely in lecture and will be held in Room 225.

\\section{Grading Scheme}

The breakdown for your total grade will be:

\\begin{center}
\\begin{tabular}{lc}
\\textbf{Category} & \\textbf{Percent} \\\\

EOT;

for ( $i = 0; $i < count($gs); $i++ )
  $texfile .= $gs[$i]['category'] . " & " . $gs[$i]['percent'] * 100 . "\\% \\\\ \n";

$texfile .= <<<EOT
\\end{tabular}
\\end{center}

\\subsection{Lecture Prep}

The lecture prep worksheets are available on the website. They consist of 10 statements
that can be found in the Lecture Notes for the week. The intention is that you read 
through the Lecture Notes and fill in the blanks before each Wednesday lecture.
\\subsection{Lab Work}

The lab worksheets are also available on the website. Please bring your own copy to work 
on. You will be required to record your data and observations, answer the questions, and 
return them to me before you leave. There will be no take-home work required with the 
labs. Only those present will receive credit for participating. There will be no make-up 
labs. You will not be allowed to make up work by attending other physics classes.
\\subsection{Homework}

All the weekly homework assignments are available on the website. The answers and my 
solutions are also available. The assignments will not graded so use the materials as you 
see fit. The quiz problems will be selected from these so it is in your best interest to 
do them when assigned.

\\subsection{Quizzes and Final Exam}

The first hour of each Monday will be used for a short quiz. The quiz questions will come 
from the previously assigned homework assignment. The tests will be open book, but no
notes. If you need help remembering an equation or the value of some constant I will
certainly be willing to tell you---please don't hesitate to ask during the test.

See the class schedule below for the date of the final. It will consist of problems taken 
from the homework. You will be given the entire three hour period to complete the test.

\\emph{Any form of cheating on any exam will result in a fail grade.}

\\subsection{Supplemental Points}

You have some options how to earn your supplemental credit. You can choose to do one of 
following things.

\\subsubsection*{Thing 1: Book Report}

I generally think of physics books in three broad categories: books about people, events, 
or theory. My personal reading list is dominated by popular descriptions of modern physics 
but I am also interested in biography and history. So if those appeal to you more, please 
feel free to choose them.

The report is to be 6 to 8 typed pages in length. You must let me know by the end of week 
six whether you are going to do the report. At that time I will need to approve the title 
of the book you are planning to use. The final draft will be due the last day of class.

\\subsubsection*{Thing 2: Projects}

The projects are essentially multi-part homework assignments.  Generally they involve 
topics normally out of scope for an introductory class like this. Your job is to walk 
through the solution by working through each part. Although the projects are self-
contained, I won't cover all of the requirements for the project in the lectures. I will 
give partial credit where appropriate. If you get stuck, feel free to ask me or your 
classmates for help.
EOT;
/*
\\subsubsection*{Thing 3: Presentation}

Prepare a 2 or 3 slide presentation with a 10 minute talk on a topic relevant to class. 
You will need to submit your slides and an outline of what you are going to discuss to me 
by the Wednesday prior to the last day of class. It's a good idea to run the topic and 
your approach by me before you invest too much time into the presentation.
*/
$texfile .= <<<EOT
\\subsubsection*{Thing 3: Opt-Out}

Don't want to do any of the above? That's fine: I will take the grade on your final as a 
substitute. In effect this would make your final worth 50\% of your grade.

\\section{Class Schedule}

\\begin{tabular}{@{}p{12mm}p{16mm}p{80mm}}

EOT;

$firstmonday = 1 + ( ( 7 + 1 - strftime("%w", mktime(0,0,0,1,1,$yr)) ) % 7 );
$day = $firstmonday + 7 * 12 * ( $tm - 201 );

foreach ( $dt[$tm] as $wk => $wkdt )
foreach ( $wkdt as $dy => $topic )
if ( $topic )
{
  $date = mktime(0,0,0,1,$day,$yr);
//  $texfile .= strftime("%a %b %d", $date) . " & ";
  $texfile .= @strftime("%b %e", $date) . " & ";
  $z = explode(" ",$topic);
  switch ( trim($z[0]) )
  {
    case "Lecture" :
      $n = intval($z[1]);
      $texfile .= "Lecture $n & ";
      $texfile .= $ln[$n]['title'] . " \\\\ \n";
    break;
    case "Lab" :
      $n = intval($z[1]);
      $texfile .= "Lab $n & ";
      $texfile .= $lb[$n]['title'] . " \\\\ \n";
    break;
    default :
      $texfile .= "& ";
      $texfile .= strtoupper(trim($topic)) . " \\\\ \n";
  }
  $day += ( $dy == 'Mon' ) ? 2 : 5; 
}

$texfile .= <<<EOT
\\end{tabular}

\\section{Miscellaneous}

\\paragraph{Instructional ADA statement} If you require specific instructional 
accommodations, please notify me early in the course. A request for accommodation may 
require documentation of disability through the Office for Students with Disabilities at 
977-4341.

\\paragraph{Flexibility Statement} Assignment/exam calendars may be changed in response 
to institutional, weather, and class problems.

\\paragraph{Withdrawal Policy} As the student, it is your responsibility to process a 
Drop via the Web or at a Registration Office within the specified time periods set forth 
by PCC.
EOT;

/* End latex */

$texfile .= "\\end{document}\n";

include "compilelatex.php";

?>
