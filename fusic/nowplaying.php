<?

/*
nowplaying.php
Fuse Playout System Web Content aka FusicBrainz
Returns current now playing information provided a track is still playing
*/

// Return now playing info if the track is still playing
function getnowplaying() {
  $query = mysql_query("SELECT Log_Artist,Log_Title,Log_MusicBrainzArtist,UNIX_TIMESTAMP(`Log_TimePlayed`),Log_Duration FROM tbl_logs ORDER BY Log_ID DESC LIMIT 1");
  while($row = mysql_fetch_array($query)){
    if (time() < ($row['UNIX_TIMESTAMP(`Log_TimePlayed`)'] + $row['Log_Duration'])) {
      $returnval = $row['Log_Title'] . " by <a href=\"https://fusefm.co.uk/music/artist/" . urlencode($row['Log_Artist']) . "/" . urlencode($row['Log_Title']);
      if ($row['Log_MusicBrainzArtist'] != "") {
        $returnval .= "/" . urlencode($row['Log_MusicBrainzArtist']);
      }
      $returnval .= "\" target=\"_blank\">" . $row['Log_Artist'] . "</a>";
    }
  }
  return $returnval;
}

?>
