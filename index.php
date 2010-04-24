<?php
header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Date in the past

include("onAirPlayer2.php");
include("fusic/dbinfo.php");
include("fusic/nowplaying.php");

switch($_GET['quality']){
  case "hq": 
    $url = "livehi"; 
    break;
  case "lq": 
    $url = "livelo"; 
    break;
  default: 
    $url = "live"; 
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<title>Live Player | Fuse FM</title>
<link rel="shortcut icon" href="/sites/default/themes/fusefm/images/favicon.ico" type="image/x-icon" />
<link type="text/css" rel="stylesheet" media="all" href="player.css" />
</head>
<script type="text/javascript">
function reloadImage1(){
	if (document.getElementById('studio1-1').style.display == 'block') {
		var now = new Date();
		document.getElementById('Webcam1').src = 'webcams.php?number=1&time=' + now.getTime();
		setTimeout('reloadImage1()',10000);
	}
}
function reloadImage2(){
	if (document.getElementById('studio1-2').style.display == 'block') {
		var now = new Date();
		document.getElementById('Webcam2').src = 'webcams.php?number=2&time=' + now.getTime();
		setTimeout('reloadImage2()',10000);
	}
}
function reloadImage3(){
	if (document.getElementById('office').style.display == 'block') {
		var now = new Date();
		document.getElementById('Webcam3').src = 'webcams.php?number=3&time=' + now.getTime();
		setTimeout('reloadImage3()',10000);
	}
}
function reloadImage4(){
	if (document.getElementById('studio2').style.display == 'block') {
		var now = new Date();
		document.getElementById('Webcam4').src = 'webcams.php?number=4&time=' + now.getTime();
		setTimeout('reloadImage4()',10000);
	}
}
function reloadNowPlaying(){
	var xmlhttp;
	if(window.XMLHttpRequest){
		xmlhttp = new XMLHttpRequest();
		xmlhttp.onreadystatechange = function(){
			if(xmlhttp.readyState == 4)
				document.getElementById("OnAir").innerHTML = xmlhttp.responseText;
		}
		xmlhttp.open("GET","ajaxNowPlaying.php",true);
		xmlhttp.send(null);
	}
}
setTimeout('reloadImage1()',10000);
var infoReload = setInterval('reloadNowPlaying()',60000);
function showdiv(mydiv) {
	document.getElementById('studio1-1').style.display = 'none';
	document.getElementById('studio1-2').style.display = 'none';
	document.getElementById('studio2').style.display = 'none';
	document.getElementById('office').style.display = 'none';
	document.getElementById('contact').style.display = 'none';
	document.getElementById(mydiv).style.display = 'block';
	if(mydiv == "studio1-1"){
		reloadImage1();
	}
	if(mydiv == "studio1-2"){
		reloadImage2();
	}
	if(mydiv == "office"){
		reloadImage3();
	}
	if(mydiv == "studio2"){
		reloadImage4();
	}
}
</script>
<body>
	<div id="Wrapper">
		<div id="Banner">
			<img src="img/VideoBanner.png" alt="Fuse FM" />
		</div> <!-- #Banner -->
		<div id="PlayerBar">
			<div id="PlayerSwf">
      	<object type="application/x-shockwave-flash" data="player.swf" width="80" height="25">
					<param name="movie" value="player.swf" />
					<param name="bgcolor" value="#000000" />
					<param name="FlashVars" value="mp3=http://studio.fusefm.co.uk:8000/<?php echo $url;?>&showloading=autohide&autoplay=1&byteslimit=400000000&volume=100&showstop=1&showvolume=1&showslider=0&sliderwidth=0&width=80&height=25&buttonovercolor=000099" />
        </object>
			</div><!-- #PlayerSwf -->
			<div id="PlayerOpts">
        <?php 
        switch($_GET['quality']){
        	case "hq":
        	  ?><strong>256k</strong> <a href="?quality=nq">128k</a> <a href="?quality=lq">32k</a><?php 
        		break;
        	case "lq":
        	  ?><a href="?quality=hq">256k</a> <a href="?quality=nq">128k</a> <strong>32k</strong><?php 
        		break;
        	default:
        		?><a href="?quality=hq">256k</a> <strong>128k</strong> <a href="?quality=lq">32k</a><?php  
        }
        ?>
			</div><!-- #PlayerOpts -->
      <ul id="Menu">
        <li><a href="javascript:showdiv('studio1-1')">Studio 1 Cam 1</a></li>
        <li><a href="javascript:showdiv('studio1-2')">Studio 1 Cam 2</a></li>
        <li><a href="javascript:showdiv('studio2')">Studio 2 Cam</a></li>
        <li><a href="javascript:showdiv('office')">Office Cam</a></li>
        <li><a href="javascript:showdiv('contact')">Contact Form</a></li>
      </ul>
		</div><!-- #PlayerBar -->
		<div id="Main">
			<div id="Right">
  			<iframe src="http://www.coveritlive.com/index2.php/option=com_altcaster/task=viewaltcast/altcast_code=dde3204230/height=400/width=400" scrolling="no" height="400px" width="400px" frameBorder ="0" allowTransparency="true"  ><a href="http://www.coveritlive.com/mobile.php/option=com_mobile/task=viewaltcast/altcast_code=dde3204230" >Broadcast 20 Live Interaction</a></iframe>
			</div><!-- #Right -->
			<div id="OnAir">
				<strong>On Air Now:</strong> <?php if ($shownameNow != "<a href='' onclick=\"window.open(this.href,'link_window');return false;\"></a>") { echo $shownameNow; } else { echo "We're on autopilot"; } ?><br />
				<?php if (getnowplaying() != "") {?><strong>Now Playing:</strong> <?php echo getnowplaying();?><br /><?php }?>
				<?php if ($shownameNext != "<a href='' onclick=\"window.open(this.href,'link_window');return false;\"></a>") { echo "<strong>Next:</strong> " . $shownameNext; }?>
			</div>
			<br /><br />
	<div id="contact">
		<?php include ("contact.php");?><br />
		<div id="status"></div>
	</div>
	<div id="studio1-1" style="display: block">
		<img src="webcams.php?number=1" alt="Studio 1 Webcam 1" id="Webcam1" /><br />
		<br />
		<a href="javascript:void(0)" onclick="window.open('http://cdn1.ustream.tv/swf/4/viewer.229.swf?cid=1/3275884&vrsl=c.4.286','ustreamer','height=300,width=400')"><img src="img/LiveVideo.png" border="0"></a>
 	</div>
	<div id="studio1-2" style="display: none">
		<img src="webcams.php?number=2" alt="Studio 1 Webcam 2" id="Webcam2" /><br />
		<br />
		<a href="javascript:void(0)" onclick="window.open('http://cdn1.ustream.tv/swf/4/viewer.229.swf?cid=1/3275884&vrsl=c.4.286','ustreamer','height=300,width=400')"><img src="img/LiveVideo.png" border="0"></a>
	</div>
	<div id="studio2" style="display: none">
		<img src="webcams.php?number=4" alt="Studio 2 Webcam" id="Webcam4" /><br />
		<br />
		<a href="javascript:void(0)" onclick="window.open('http://cdn1.ustream.tv/swf/4/viewer.229.swf?cid=1/3275884&vrsl=c.4.286','ustreamer','height=300,width=400')"><img src="img/LiveVideo.png" border="0"></a>
	</div>
	<div id="office" style="display: none">
		<img src="webcams.php?number=3" alt="Office Webcam" id="Webcam3" /><br />
		<br />
		<a href="javascript:void(0)" onclick="window.open('http://cdn1.ustream.tv/swf/4/viewer.229.swf?cid=1/3275884&vrsl=c.4.286','ustreamer','height=300,width=400')"><img src="img/LiveVideo.png" border="0"></a>
	</div>
			<br style="clear: both;" /><br />
		</div><!-- #Main -->
	</div><!-- #Wrapper -->
</body>
</html>
