<?php
/**
 * Light weight daemon to mail updates to the external IP address of the 
 * daemon's host computer. Requires curl to be installed and included in 
 * the PATH variable, and a mail server be installed and configured to work with
 * PHP.
 */

//load the config
$configFileName = 'mailConfig.txt';
$configFile = fopen($configFileName, 'r');
$configContent = fread($configFile, filesize($configFileName));

preg_match('/mailTo:(.*)/', $configContent, $config['mailTo']);
$config['mailTo'] = $config['mailTo'][1];

preg_match('/externalIpAddressName:(.*)/', $configContent, $config['EIpName']);
$config['EIpName'] = $config['EIpName'][1];

while(true){

//make sure we actually get the ip address
$externalContent = false;
while($externalContent == false){
	//$externalContent = `curl http://checkip.dynds.com/`;
	$externalContent = `curl http://checkip.dyndns.com/`;
	if($externalContent == false){
		sleep('60');
	}
}

preg_match('/Current IP Address: ([\[\]:.[0-9a-fA-F]+)</', $externalContent, $ip);
$externalIp = $ip[1];
$oldIpContent  = file_get_contents('ip.txt');
$fp = fopen('ip.txt', 'w'); 
fwrite($fp, $externalIp . ' at ' . date('m-d-G:i'));
fclose($fp);

preg_match("/(.*) at/", $oldIpContent, $oldIp);
//check to see if the ip address has hchanged before sending the mail
if($oldIp[1] != $externalIp){
	$mailReturn = mail($config['mailTo'], 
		$config['EIpName'] . ' - ' . $externalIp, 
		$config['EIpName'] . 'address has changed to ' . $externalIp . '.');
	if($mailReturn == false){
		print('IP was updated but sending mail failed!');
	} else{
		print('IP was updated and mail was sent successfully!');
	}
}else{
	print('IP has not changed since last check.');
}


sleep(540);
}
