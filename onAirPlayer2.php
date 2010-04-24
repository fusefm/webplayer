<?
mysql_connect('localhost','drupal_live','FJ737zGN6UPdwf5D');
mysql_select_db('drupal_live');

if ((date("H")%2) == 1) {
	$mydate = date("H") - 1;
} else {
	$mydate = date("H");
}

if (date("w") == 0) {
	$mydate *= 60;
} else {
	$mydate = ((date("w")*24*60)+($mydate*60));
}

$onairnow = mysql_query("SELECT * FROM drup_station_schedule_item WHERE `start` = '$mydate' ORDER BY `schedule_nid` DESC LIMIT 1");
$mydate += 120;
$onairnext = mysql_query("SELECT * FROM drup_station_schedule_item WHERE `start` = '$mydate' ORDER BY `schedule_nid` DESC LIMIT 1");

if (mysql_num_rows($onairnow) > 0) {
	$shownameNow = mysql_result($onairnow,'0','program_nid');
	$nodequery = mysql_query("SELECT * FROM drup_node WHERE `nid` = '$shownameNow'");
	if (mysql_num_rows($nodequery) > 0) {
		$shownameNow = mysql_result($nodequery,'0','title');
	} else {
		$shownameNow = "<a href='' onclick=\"window.open(this.href,'link_window');return false;\"></a>";
	}
} else {
	$shownameNow = "<a href='' onclick=\"window.open(this.href,'link_window');return false;\"></a>";
}

if (mysql_num_rows($onairnext) > 0) {
	$shownameNext = mysql_result($onairnext,'0','program_nid');
	$nodequery = mysql_query("SELECT * FROM drup_node WHERE `nid` = '$shownameNext'");
        if (mysql_num_rows($nodequery) > 0) {
                $shownameNext = mysql_result($nodequery,'0','title');
        } else {
                $shownameNext = "<a href='' onclick=\"window.open(this.href,'link_window');return false;\"></a>";
        }
} else {
	$shownameNext = "<a href='' onclick=\"window.open(this.href,'link_window');return false;\"></a>";
}

?>
