<?php
header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Date in the past

include("onAirPlayer2.php");
include("fusic/dbinfo.php");
include("fusic/nowplaying.php");
?>
<strong>On Air Now:</strong> <?php if ($shownameNow != "<a href='' onclick=\"window.open(this.href,'link_window');return false;\"></a>") { echo $shownameNow; } else { echo "We're on autopilot"; } ?><br />
				<?php if (getnowplaying() != "") {?><strong>Now Playing:</strong> <?php echo getnowplaying();?><br /><?php }?>
				<?php if ($shownameNext != "<a href='' onclick=\"window.open(this.href,'link_window');return false;\"></a>") { echo "<strong>Next:</strong> " . $shownameNext; }?>
