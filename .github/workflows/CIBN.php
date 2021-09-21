<?php
error_reporting(E_ALL);

$channelId = $_REQUEST ['id'];

if ((file_exists('Utime')) && (filesize('Utime'))) 
{
    $Utime = time()- filemtime('Utime');

    if ($Utime > 3600)  
	{
         GetLive();
    }  
} 
else 
{
    GetLive();
	file_put_contents('Utime',time());
} 

$PlayUrl = file_get_contents($channelId);

header('Location: '.$PlayUrl);

function GetLive()
{
	$Data = file_get_contents('http://api.epg2.cibn.cc/v1/loopChannelList?epgId=1000');
	$L = strpos($Data,'[');
    $R = strpos($Data,']');
    $Out = substr($Data,$L+1,$R-$L-1);
	$List = explode ('{',$Out);
	$Size = count($List);
	for($i=0; $i<=$Size; $i++)
	{
		$Live = '{'.$List[$i];
        $Live = str_replace('},','}',$Live);	
        $Josn = json_decode($Live);
		$m3u8 = $Josn->m3u8;
	    $id = $Josn->id;
		file_put_contents($id,$m3u8);
	}
}
?>