<?

/*
content.php
Fuse Playout System Web Content aka FusicBrainz
Generates various pages for artists and tracks in the system using MusicBrainz and Last.FM, along with the ability to display playlists
*/

ini_set('display_errors', 0); 

if (($_GET["artistimage"] == "true") AND ($_GET["mbid"] != "")) {
  header('Content-Type: image/jpeg');
  echo getimage($_GET["mbid"]);
}

// XML parsing function
function gettextbetweentags($string, $tagname) {
  $starttag = "<$tagname>";
  $endtag = "</$tagname>";
  $string = explode($starttag,$string);
  $string = explode($endtag,$string[1]);
  return $string[0];
}

// Gets data from BBC Music
function getbbcdata($mbid,$datatype) {
  // Get artist info via BBC Music in XML
  $options = array( 'http' => array(
        'user_agent'    => 'Fuse FM Data Collector',
        'max_redirects' => 10,
        'timeout'       => 20,
  ) );
  $context = stream_context_create($options);
  $handle = fopen("http://www.bbc.co.uk/music/artists/$mbid/$datatype.xml","r",false,$context);
  $xmldata = stream_get_contents($handle);
  fclose($handle);
  return $xmldata;
}

function getimage($mbid) {
  $imgurl = "http://www.bbc.co.uk/music/images/artists/542x305/$mbid.jpg";
  $options = array( 'http' => array(
        'user_agent'    => 'Fuse FM Data Collector',
        'max_redirects' => 10,
        'timeout'       => 20,
  ) );
  $context = stream_context_create($options);
  $handle = fopen($imgurl,"r",false,$context);
  $imagedata = stream_get_contents($handle);
  fclose($handle);
  return $imagedata;
}

// Gets basic info about a particular artist
function getartistdescription($mbid) {
  $xmldata = getbbcdata($mbid,"wikipedia");
  
  // Handle the XML, creating an array of (artist name, description, more info url)
  if (!strstr($xmldata,"<artist artist_type=")) {
    $artistarray = array("","","");
  } else {
    $artistarray[] = gettextbetweentags($xmldata,"name");
    $artistarray[] = gettextbetweentags($xmldata,"content");
    $artistarray[] = gettextbetweentags($xmldata,"url");
  }

  // Return the data
  return $artistarray;
}

// Gets links to artists' official homepages, fan pages and myspace - generate last.fm and musicbrainz links dynamically
function getartistlinks($mbid) {
  $xmldata = getbbcdata($mbid,"links");
  
  // Handle the XML, creating an array of (artist name, description, more info url)
  if (!strstr($xmldata,"<artist artist_type=")) {
    $artistarray = array("","","");
  } else {
    $officialhomepage = explode("<link type=\"official homepage\">",$xmldata);
    $officialhomepage = explode("</link>",$officialhomepage[1]);
    $officialhomepage = gettextbetweentags($officialhomepage[0],"url");
    $fanpage = explode("<link type=\"fanpage\">",$xmldata);
    $fanpage = explode("</link>",$fanpage[1]);
    $fanpage = gettextbetweentags($fanpage[0],"url");
    $myspace = explode("<link type=\"myspace\">",$xmldata);
    $myspace = explode("</link>",$myspace[1]);
    $myspace = gettextbetweentags($myspace[0],"url");
    $artistarray = array($officialhomepage,$fanpage,$myspace);
  }

  // Return the data
  return $artistarray;
}

// Generates a list of the tracks for this artist most recently played on Fuse with shows and links
function getlatesttracks($mbid = false,$bestguess) {
  // Check the database for recent tracks
  if ($mbid) {
    $query = mysql_query("SELECT Log_Title, Log_PlayedByShowID, UNIX_TIMESTAMP(Log_TimePlayed) FROM tbl_logs WHERE `Log_MusicBrainzArtist` = '$mbid' OR `Log_Artist` LIKE '%$bestguess%' ORDER BY Log_ID DESC LIMIT 5");
  } else {
    $query = mysql_query("SELECT Log_Title, Log_PlayedByShowID, UNIX_TIMESTAMP(Log_TimePlayed) FROM tbl_logs WHERE `Log_Artist` LIKE '%$bestguess%' ORDER BY Log_ID DESC LIMIT 5");
  }
  
  // Process any tracks found (up to 5)
  if ($query) {
    while($row = mysql_fetch_array($query)){
      $playedby = $row['Log_PlayedByShowID'];
      if ($playedby != "0") {
        $showquery = mysql_query("SELECT * FROM `tbl_show` WHERE `Show_ID` = '$playedby' LIMIT 1");
        if (mysql_num_rows($showquery) > 0) {
          $playedby = mysql_result($showquery,0,'Show_Name');
        }
      } else {
        $playedby = "Fuse FM Auto DJ (Fusic)";
      }
      $trackarray[] = array($row['Log_Title'],$playedby,$row['UNIX_TIMESTAMP(Log_TimePlayed)']);
    }
  } else {
    $trackarray = array(array("","",""));
  }
  
  // Return the data
  return $trackarray;
}

// Gets up to five shows that play a particular artist the most
function getshowcomparison($mbid = false,$bestguess,$currentshow) {
  // Check the database for tracks
  if ($mbid) {
    $query = mysql_query("SELECT Log_Title, Log_PlayedByShowID, UNIX_TIMESTAMP(Log_TimePlayed) FROM tbl_logs WHERE (`Log_MusicBrainzArtist` = '$mbid' OR `Log_Artist` LIKE '%$bestguess%') AND `Log_PlayedByShowID` != '0' AND `Log_PlayedByShowID` != '$currentshow' ORDER BY Log_ID DESC");
  } else {
    $query = mysql_query("SELECT Log_Title, Log_PlayedByShowID, UNIX_TIMESTAMP(Log_TimePlayed) FROM tbl_logs WHERE `Log_Artist` LIKE '%$bestguess%' AND `Log_PlayedByShowID` != '0' AND `Log_PlayedByShowID` != '$currentshow' ORDER BY Log_ID DESC");
  }
  
  // Process any tracks found
  if ($query) {
    while($row = mysql_fetch_array($query)){
      $playedby = $row['Log_PlayedByShowID'];
      $showquery = mysql_query("SELECT * FROM `tbl_show` WHERE `Show_ID` = '$playedby' LIMIT 1");
      if (mysql_num_rows($showquery) > 0) {
        $playedby = mysql_result($showquery,0,'Show_Name');
        $showarray[] = $playedby;
      }
    }
  }
  
  // Find out which shows play it the most and order by this
  if ($showarray) {
    $returnarray = array_count_values($showarray);
    arsort($returnarray);
    $showarray = "";
    foreach ($returnarray as $key => $value) {
      $showarray[] = $key;
    }
    if (sizeof($showarray) > "5") {
      $count = "5";
    } else {
      $count = sizeof($showarray);
    }
    for ($i=0;$i<$count;$i++) {
      $showretarray[] = $showarray[$i];
    }
  } else {
    $showretarray = array("");
  }

  // Return the data
  return $showretarray;
}

// Returns playlist entries with <br /> tags between each one
function getplaylist($playlistid) {
  $query = mysql_query("SELECT * FROM tbl_playlist_files WHERE `Playlist_ID` = '$playlistid'");
  $first = true;
  while($row = mysql_fetch_array($query)){
    if ($first) {
      $querycontents = "SELECT * FROM tbl_files WHERE ";
      $first = false;
    } else { 
      $querycontents .= " OR ";
    }
    $querycontents .= "`File_ID` = " . $row['File_ID'];
  }
  $querycontents .=  " ORDER BY File_Artist, File_Title";
  $filequery = mysql_query($querycontents);
  while($row = mysql_fetch_array($filequery)){
    $returnval .= "<a href=\"https://fusefm.co.uk/music/artist/" . urlencode($row['File_Artist']) . "/" . urlencode($row['File_Title']);
    if ($row['File_MusicBrainzArtist'] != "") {
      $returnval .= "/" . urlencode($row['File_MusicBrainzArtist']);
    }
    $returnval .= "\">" . $row['File_Artist'] . "</a> - " . $row['File_Title'] . "<br>";
  }
  return $returnval;
}

// Returns a list of recently played tracks with FusicBrainz links
function getrecentlyplayed() {
  $query = mysql_query("SELECT Log_Artist,Log_Title,Log_MusicBrainzArtist,Log_PlayedByShowID,UNIX_TIMESTAMP(`Log_TimePlayed`) FROM tbl_logs ORDER BY Log_ID DESC LIMIT 5");
  while($row = mysql_fetch_array($query)){
    $returnval .= "<a href=\"https://fusefm.co.uk/music/artist/" . urlencode($row['Log_Artist']) . "/" . urlencode($row['Log_Title']);
    if ($row['Log_MusicBrainzArtist'] != "") {
      $returnval .= "/" . urlencode($row['Log_MusicBrainzArtist']);
    }
    $returnval .= "\">" . $row['Log_Artist'] . "</a> - " . $row['Log_Title'];
    $returnval .= " <span style=\"font-style: italic;\">(Played at " . date("H:i",$row['UNIX_TIMESTAMP(`Log_TimePlayed`)']);
    if ($row['Log_PlayedByShowID'] != "0") {
      $showquery = mysql_query("SELECT * FROM tbl_show WHERE Show_ID = " . $row['Log_PlayedByShowID']);
      if (mysql_num_rows($showquery) > 0) {
        $returnval .= " by <a href=\"shows\">" . mysql_result($showquery,0,"Show_Name") . "</a>";
      }
    }
    $returnval .= ")</span><br>";
  }
  return $returnval;
}

?>