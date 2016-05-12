<?php
$pcip=$_GET['pcip'];
$androidip=$_GET['androidip'];
$info="#+BEGIN\n#+UPDATE_TIME ".date('Y-m-d H:i:s')."\n#+MESSAGE\n#######################################################################\n#\n# --- Welcome to www.findspace.name ----\n# Connect Me:   Website:http://www.findspace.name\n#\n#######################################################################\n#+MESSAGE_END\n\n\n#Google service\n";
$fileWrite=fopen('hosts2', 'w');
fwrite($fileWrite, $info);
if($pcip){
	$fileRead0 = fopen("pcbase", "r") or die("Unable to open file!");
	// 输出单行直到 end-of-file
	while(!feof($fileRead0)) {
	  // echo fgets($fileRead0) . "\n";
		fwrite($fileWrite, $pcip."  ".fgets($fileRead0));
	}
	fclose($fileRead0);
	echo "pcip ok";
}
if($androidip){
	$fileRead1 = fopen("androidbase", "r") or die("Unable to open file!");
	while(!feof($fileRead1)) {
	  // echo fgets($fileRead0) . "\n";
		fwrite($fileWrite, $androidip."  ".fgets($fileRead1));
	}
	fclose($fileRead1);
	echo "androidip ok ";
}
	fclose($fileWrite);
	
?>
