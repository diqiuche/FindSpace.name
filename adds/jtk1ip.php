<?php
$ip=$_GET['ip'];
if($ip){
	$file=fopen('jtk1', 'w');
	fwrite($file, $ip);
	echo "success";
}else{
	$file=fopen('jtk1', 'r');
	while(!feof($file)){
		echo fgets($file);
	}
}
?>