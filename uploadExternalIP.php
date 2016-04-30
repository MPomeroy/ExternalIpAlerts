<?php
/**
 * Light weight daemon to keep a file up to date with the current external ip
 * of the running computer. Requires curl to be installed and in PATH variable.
 */

//load the config
$configFileName = 'uploadConfig.txt';
$configFile = fopen($configFileName, 'r');
$configContent = fread($configFile, filesize($configFileName));

preg_match('/ftpUrl:(.*)/', $configContent, $config['ftpUrl']);
$config['ftpUrl'] = $config['ftpUrl'][1];

preg_match('/ftpUser:(.*)/', $configContent ,$config['ftpUser']);
$config['ftpUser'] = $config['ftpUser'][1];

preg_match('/ftpPass:(.*)/', $configContent, $config['ftpPass']);
$config['ftpPass'] = $config['ftpPass'][1];

preg_match('/storagePath:(.*)/', $configContent, $config['storagePath']);
$config['storagePath'] = $config['storagePath'][1];

while(true){

//make sure we actually get the ip address
$externalContent = false;
while($externalContent == false){
        $externalContent = `curl http://checkip.dyndns.com/`;
        if($externalContent == false){
                sleep(60);
        }
}


preg_match('/Current IP Address: ([\[\]:.[0-9a-fA-F]+)</', $externalContent, $ip);
$externalIp = $ip[1];
$fp = fopen('ip.txt', 'w');
fwrite($fp, $externalIp . ' at ' . date('m-d-G:i'));
fclose($fp);

$conn_id = ftp_connect($config['ftpUrl']);

$login_return = ftp_login($conn_id, $config['ftpUser'], $config['ftpPass']);

if(ftp_put($conn_id, $config['storagePath'], 'ip.txt', FTP_ASCII)){
	print('Success!');
}else{
	print('uploading file failed.');
}
sleep(540);
}
