<?php
$handle = @fopen("https://fusefm.co.uk/onAir.php?when=now&link=yes&newWindow=yes", "r");
if ($handle) {
    while (!feof($handle)) {
        $buffer = fgets($handle, 4096);
        $shownameNow = $buffer;
    }
    fclose($handle);
}

$handle = @fopen("https://fusefm.co.uk/onAir.php?when=next&link=yes&newWindow=yes", "r");
if ($handle) {
    while (!feof($handle)) {
        $buffer = fgets($handle, 4096);
        $shownameNext = $buffer;
    }
    fclose($handle);
}

//$shownameNow = str_replace('&','And',$shownameNow);
//$shownameNow = "Now on air: " . $shownameNow;

//$shownameNext = str_replace('&','And',$shownameNext);
//$shownameNext = "Next Up: " . $shownameNext;

?>
