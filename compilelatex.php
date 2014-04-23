<?

if ( $_GET['tex'] == 1 ) { echo "<pre>"; die($texfile); }

$rootdir = "/var/www/p200";
$texcmd = "/usr/bin/pdflatex --interaction=nonstopmode";

$out = $_GET['out'];
if ( $out ) 
{
  $x = explode("/",$out);
  $outfile = $x[count($x) - 1];
  $y = explode(".",$outfile);
  $outroot = $y[0];
  $outpath = substr($out,0,strlen($out)-strlen($outfile)-1);
  $rootdir .= "/$outpath";
}

/* Save latex document */

$fileroot = "qwer" . round(time() / 10 * rand(1,10));
if ( $out ) $fileroot = $outroot;
$filename = $rootdir  . "/" . $fileroot;
$tex = fopen($filename . ".tex", "w");
fputs($tex , $texfile);
fclose($tex);

echo $filename;
die;

/* Compile latex document */

$command  = "cd " . $rootdir . "\n";
// $command .= "/usr/bin/makeindex " . $fileroot . ".tex\n";
$command .= $texcmd . " " . $filename . ".tex > /dev/null 2>&1 ;\n";
$command .= $texcmd . " " . $filename . ".tex > /dev/null 2>&1 ;\n";

$output = exec($command);

/* Download pdf document */

if ( ! $out ) 
{
  header('Content-Disposition: attachment; filename="' . $fileroot . '.pdf"');
  header('Content-Type: application/octet-stream');
  readfile("./" . $fileroot . ".pdf");
}

/* Clean up */

unlink($filename . ".tex");
unlink($filename . ".log");
unlink($filename . ".aux");
unlink($filename . ".out");
unlink($filename . ".nav");
unlink($filename . ".snm");
unlink($filename . ".idx");
unlink($filename . ".ilg");
unlink($filename . ".ind");
unlink($filename . ".toc");
unlink($filename . ".pdf");

?>
