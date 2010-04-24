<?

/*
generatepage.php
Fuse Playout System Web Content aka FusicBrainz
Attempts to make a page about an artist given particular parameters
*/

// STILL NEED TO SOURCE GIGS AND NEWS FROM GIGULATE
// PLUS ALL TRACKS BY THE ARTIST IN THE FUSE COLLECTION

include('dbinfo.php');
include('content.php');

$mbid = $_GET["mbid"];
$artist = $_GET["artist"];
$track = $_GET["track"];

if (stristr($mbid,";") OR stristr($artist,";") OR stristr($track,";")) {
  die("Hacking attempt");
}

if (($artist != "") OR ($mbid != "")) {

  if ($mbid == "") {
    // Do MusicBrainz query for artist mbid
    require_once("phpbrainz/phpBrainz.class.php");
    //Create new phpBrainz object
    $phpBrainz = new phpBrainz();
    $args = array(
      "title"=>str_replace("&","and",htmlspecialchars(utf8_encode($track), ENT_COMPAT, 'UTF-8')),
      "artist"=>str_replace("&","and",htmlspecialchars(utf8_encode($artist), ENT_COMPAT, 'UTF-8'))
    );
    $trackFilter = new phpBrainz_TrackFilter($args);
    if ($trackFilter) {
      $trackResults = $phpBrainz->findTrack($trackFilter);
    }
    if ($trackResults) {
      $mbartistobj = $trackResults[0]->getArtist();
      $mbid = $mbartistobj->getId();
    }
    if ($mbid == "") {
      $track = "";
      $phpBrainz = new phpBrainz();
      $args = array(
        "title"=>str_replace("&","and",htmlspecialchars(utf8_encode($track), ENT_COMPAT, 'UTF-8')),
        "artist"=>str_replace("&","and",htmlspecialchars(utf8_encode($artist), ENT_COMPAT, 'UTF-8'))
      );
      $trackFilter = new phpBrainz_TrackFilter($args);
      if ($trackFilter) {
        $trackResults = $phpBrainz->findTrack($trackFilter);
      }
      if ($trackResults) {
        $mbartistobj = $trackResults[0]->getArtist();
        $mbid = $mbartistobj->getId();
      }
    }
  }

  // Print image and description
  $descarray = getartistdescription($mbid);
  echo "<h1 class=\"title\">" . $descarray[0] . "</h1><br />";
  if ($descarray[0] != "") {
    $artist = $descarray[0];
  }
  echo "<img src=\"content.php?artistimage=true&mbid=$mbid\" width=\"475\" height=\"276\"><br />";
  if ($descarray[1] != "") {
    echo "<h2>Biography</h2>";
    echo $descarray[1] . "<br />";
  }

  // Links section
  $linkarray = getartistlinks($mbid);
  if (($descarray[2] != "") OR ($descarray[2] != "") OR ($descarray[2] != "") OR ($descarray[2] != "")) {
    echo "<h2>Links</h2>";
    if ($linkarray[0] != "") {
      echo "<b>Official Homepage:</b> <a href=\"" . $linkarray[0] . "\" target=\"_blank\">" . $linkarray[0] . "</a><br />";
    }
    if ($linkarray[1] != "") {
      echo "<b>Fan Page:</b> <a href=\"" . $linkarray[1] . "\" target=\"_blank\">" . $linkarray[1] . "</a><br />";
    }
    if ($linkarray[2] != "") {
      echo "<b>MySpace:</b> <a href=\"" . $linkarray[2] . "\" target=\"_blank\">" . $linkarray[2] . "</a><br />";
    }
    if ($descarray[2] != "") {
      echo "<b>Wikipedia:</b> <a href=\"" . $descarray[2] . "\" target=\"_blank\">" . $descarray[2] . "</a><br />";
    }
  }

  // Recently played tracks
  $latesttracks = getlatesttracks($mbid,$artist);
  if ($latesttracks[0][0] != "") {
    echo "<h2>Recently Played</h2>";  
    for ($i=0;$i<sizeof($latesttracks);$i++) {
      echo $latesttracks[$i][0] . " played by " . $latesttracks[$i][1] . " @ " . date("H:i (d/m/y)",$latesttracks[$i][2]) . "<br />";
    }
    echo "<br />View all of our recently played tracks <a href=\"/music\">here</a>";
  }

  // Most played on
  $similarshows = getshowcomparison($mbid,$artist,"291");
  if ($similarshows[0] != "") {
    echo "<h2>Most Played On</h2>";  
    for ($i=0;$i<sizeof($similarshows);$i++) {
      echo $similarshows[$i] . "<br />";
    }
    echo "<br />View all of our shows <a href=\"/shows\">here</a>";
  }

  echo "<h2>Data Sources</h2>";
  echo "Data on this page is generated automatically by <a href=\"http://www.bbc.co.uk/music\" target=\"_blank\">BBC Music</a> and other connected sources including <a href=\"http://www.wikipedia.org\" target=\"_blank\">Wikipedia</a>, <a href=\"http://www.musicbrainz.org\" target=\"_blank\">MusicBrainz</a>, <a href=\"http://www.gigulate.com\" target=\"_blank\">Gigulate</a> and <a href=\"http://www.last.fm\" target=\"_blank\">Last.FM</a>.";
} else {
  echo "<h1 class=\"title\">Unknown Artist</h1><br />";
}

?>