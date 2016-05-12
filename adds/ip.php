<?php
$ip=$_GET['ip'];
if($ip){
	$file=fopen('UbuntuPcIP', 'w');
	fwrite($file, $ip);
	echo "success";
}else{
	$file=fopen('UbuntuPcIP', 'r');
	while(!feof($file)){
		echo fgets($file);
	}
}
?>