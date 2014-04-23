<?php

$tm = ( $_POST['tm'] ) ? $_POST['tm'] : 203;
echo "<form method=post>\n"; 
echo "<h3>Physics <input type='text' size='2' value='$tm' name='tm'> Docs</h3>\n";
echo "</form>\n";

echo "<ul>\n";
echo "<li><a href='sy.php?tm=$tm'>Class Syllabus</a></li>\n";
echo "<li><a href='ci.php?tm=$tm'>Calendar Import</a></li>\n";
echo "<li><a href='ln.php?tm=$tm'>Lecture Notes</a>\n";
echo "<a href='ln.php?tex=1'>All</a>\n";
if ( $tm == 201 ) for ( $i =  1; $i <= 11; $i++ ) echo " <a href='ln.php?le=$i&tex=1'>$i</a>";
if ( $tm == 202 ) for ( $i = 12; $i <= 22; $i++ ) echo " <a href='ln.php?le=$i&tex=1'>$i</a>";
if ( $tm == 203 ) for ( $i = 23; $i <= 30; $i++ ) echo " <a href='ln.php?le=$i&tex=1'>$i</a>";
echo "</li>\n";

echo "<li><a href='ls.php?tm=$tm'>Lecture Slides (Screen)</a>";
if ( $tm == 201 ) for ( $i =  1; $i <= 11; $i++ ) echo " <a href='ls.php?le=$i'>$i</a>";
if ( $tm == 202 ) for ( $i = 12; $i <= 22; $i++ ) echo " <a href='ls.php?le=$i'>$i</a>";
if ( $tm == 203 ) for ( $i = 23; $i <= 30; $i++ ) echo " <a href='ls.php?le=$i'>$i</a>";
echo "</li>\n";

echo "<li><a href='ls.php?tm=$tm&up=2'>Lecture Slides:2up (Paper)</a></li>\n";
echo "<li><a href='eq.php?tm=$tm'>Equation Summary</a></li>\n";
echo "<li><a href='lb.php?tm=$tm&dh=lw'>Lab Worksheets</a></li>\n";
echo "<li><a href='lb.php?tm=$tm&dh=lf'>Lab Equipment Forms</a></li>\n";
echo "<li><a href='pr.php?tm=$tm'>Project Worksheets</a></li>\n";

echo "<li><a href='tf.php?tm=$tm'>True-False Statements</a>\n";
// for ( $i = 1; $i <= 10; $i++ ) echo " <a href='tf.php?tm=$tm&wk=$i'>$i</a>";
echo "</li>\n";

echo "<li><a href='hw.php?tm=$tm&dh=hp'>Homework Problems</a>";
// for ( $i = 1; $i <= 10; $i++ ) echo " <a href='hw.php?tm=$tm&wk=$i&dh=hp'>$i</a>";
echo "</li>\n";

echo "<li><a href='hw.php?tm=$tm&dh=ha'>Homework Answers</a>\n";
// for ( $i = 1; $i <= 10; $i++ ) echo " <a href='hw.php?tm=$tm&wk=$i&dh=ha'>$i</a>";
echo "</li>\n";

echo "<li><a href='hw.php?tm=$tm&dh=hs'>Homework Solutions</a>\n";
// for ( $i = 1; $i <= 10; $i++ ) echo " <a href='hw.php?tm=$tm&wk=$i&dh=hs'>$i</a>";
echo "</li>\n";

echo "</ul>\n";

$sd = ( $_POST['sd'] ) ? $_POST['sd'] : 2011;
echo "<form method=post>\n"; 
echo "<p>Seed = <input type='text' size='4' value='$sd' name='sd'></p>\n";
echo "</form>\n";

echo "<ul>\n";
echo "<li><a href='ex.php?tm=$tm&sd=$sd'>Final Exam</a></li>\n";
echo "<li><a href='ex.php?tm=$tm&sd=$sd&ky=a'>Final Exam Key</a></li>\n";
echo "<li><a href='ex.php?tm=$tm&sd=$sd&ky=s'>Final Exam Solutions</a></li>\n";
echo "<li><a href='qz.php?tm=$tm&sd=$sd'>Quizzes</a></li>\n";
echo "<li><a href='qz.php?tm=$tm&sd=$sd&ky=a'>Quiz Key</a></li>\n";
echo "<li><a href='qz.php?tm=$tm&sd=$sd&ky=s'>Quiz Solutions</a></li>\n";
echo "</ul>\n";

?>
