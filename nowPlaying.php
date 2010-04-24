<?php
function custom_nowPlaying() {
	$output = "";
	custom_isTrackPlaying();
	if (custom_isTrackPlaying() == TRUE) {
		$myvar = "";
		ob_start();
		include('/mnt/webarray/pubwww/drupal/onair.txt');
		$myvar = ob_get_contents();
		ob_end_clean();
		$myvar = explode("(",$myvar);
		$artisttitle = trim($myvar[0]);
		$artist = explode(" - ",$artisttitle);
		$title = trim($artist[1]);
		$artist = trim($artist[0]);
		$genre = explode("enre:",$myvar[1]);
		$genre = explode("-",$genre[1]);
		$genre = trim($genre[0]);

		$output = $title . " by " . $artist;

		if (stristr($genre,"Playlist A") !== FALSE) {
                        $output .= " from Fuse Playlist A";
                }
                if (stristr($genre,"Playlist B") !== FALSE) {
                        $output .= " from Fuse Playlist B";
                }

	}

	return $output;
}

function custom_isTrackPlaying() {
	$myvar = "";
	ob_start();
	include('/mnt/webarray/pubwww/drupal/onair.txt');
	$myvar = ob_get_contents();
	ob_end_clean();
	if (stristr($myvar,"Genre") !== FALSE) {
		$myvar = explode("(",$myvar);
		$artisttitle = trim($myvar[0]);
		$genre = explode("Genre:",$myvar[1]);
		$genre = explode("-",$genre[0]);
		$genre = trim($genre[0]);
		$duration = explode("- Duration:",$myvar[1]);
		$duration = explode(")",$duration[1]);
		$duration = trim($duration[0]);
		$mins = explode(":",$duration);
		$secs = $mins[1];
		$mins = $mins[0];

		if (($secs == "") OR ($mins == "")) {
			$secs = "0";
			$mins = "3";
		}

		$filemod = filemtime('/mnt/webarray/pubwww/drupal/onair.txt');
		$timenow = time();
		$runtill = $filemod + $secs + ($mins * 60);

		if ($timenow < $runtill) {
			return TRUE;
		} else {
			return FALSE;
		}
	} else {
		return FALSE;
	}
}
?>
