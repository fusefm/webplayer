<?php
$number = intval($_GET['number']);
$image = "/mnt/webarray/pubwww/webcams/webcam" . $number . ".jpg";
if (is_file($image)) {
	if(!($fp=fopen($image,"rb")))
	{
		print("Could not open the image");
		exit;
	}
	header("Content-type: image/jpeg");
	$contents=fread($fp,1000000);

	print($contents);
	fclose($fp);
} else {
	print("Could not open the image");
}

?>
