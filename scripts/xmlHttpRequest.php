<?php
// change the 4 variables below
$yourName = 'Studio';
$yourEmail = 'studio@fusefm.co.uk';
$yourSubject = '[Fuse Live Player Message]';
$referringPage = 'http://studio.fusefm.co.uk/player/contact.php';
// no need to change the rest unless you want to. You could add more error checking but I'm gonna do that later in the official release

header('Content-Type: text/xml');
echo '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>';

echo '<resultset>';

function cleanPosUrl ($str) {
$nStr = $str;
$nStr = str_replace("**am**","&",$nStr);
$nStr = str_replace("**pl**","+",$nStr);
$nStr = str_replace("**eq**","=",$nStr);
return stripslashes($nStr);
}
	if ( $_GET['contact'] == true && $_GET['xml'] == true && isset($_POST['posText']) ) {
	$to = ''.$yourName.' <'.$yourEmail.'>';
	$subject = '[Fuse Live Player Message] '.cleanPosUrl($_POST['posRegard']);
	$message = cleanPosUrl($_POST['posText']);
	$headers = "From: ".cleanPosUrl($_POST['posName'])." <".cleanPosUrl($_POST['posEmail']).">\r\n";
	$headers .= "\r\n";
	$mailit = mail($to,$subject,$message,$headers);
		
		if ( @$mailit )
		{ $posStatus = 'OK'; $posConfirmation = 'Success! Your message has been sent to the studio...'; }
		else
		{ $posStatus = 'NOTOK'; $posConfirmation = 'Your message could not be sent. Please try back at another time...'; }
		
		if ( $_POST['selfCC'] == 'send' )
		{
		$ccEmail = cleanPosUrl($_POST['posEmail']);
		@mail($ccEmail,$subject,$message,"From: Yourself <".$ccEmail.">\r\nTo: Yourself");
		}
	
	echo '
		<status>'.$posStatus.'</status>
		<confirmation>'.$posConfirmation.'</confirmation>
		<regarding>'.cleanPosUrl($_POST['posRegard']).'</regarding>
		';
	}
echo'	</resultset>';

?>
