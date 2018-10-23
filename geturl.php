<?php

if($argc < 2){
	echo "$argv[0] \"Video ID\"\n";
	exit;
}
$onlyUrl = ($argc >2);

if(!$onlyUrl) echo "NHK Winter Olympic Games, PyeongChang 2018\n";

$videoId = $argv[1];
if(!$onlyUrl) echo "ID $videoId\n";

$xmlFile = "https://sports.nhk.or.jp/videodata/{$videoId}.xml";
if(!$onlyUrl) echo "xml: {$xmlFile}\n";

$xml = simplexml_load_file($xmlFile);
//var_dump($xml->videoSources->videoSource[1]);
$hlsUrl = $xml->xpath('//videoSource[@format="HLS"]')[0]->uri;
if(!$onlyUrl) echo "HLS: $hlsUrl\n";

$request = json_encode(array(
		"Type" => 1,
		"User" => "",
		"VideoId" => $videoId,
		"VideoSource" => $hlsUrl."",
		"VideoKind" => "Video",
		"AssetState" => "3",
		"PlayerType" => "HTML5",
		"VideoSourceFormat" => "HLS",
		"VideoSourceName" => "HLS",
		"DRMType" => "",
		"AuthType" => "",
		"ContentKeyData" => "",
		"Other" => "",
	),JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES );
//echo $request."\n";

$context = stream_context_create(array("http" => array(
	"method" => "POST",
	"header" => 
		"Content-Type: application/json\r\n".
		//"Cookie: _ga=GA1.3.390695535.1490711452; nol=%7B%22weather%22%3A%7B%22AreaCode%22%3A%2213113%22%2C%22CityCode%22%3A%221311300%22%2C%22AmedasCode%22%3A%2244132%22%2C%22AreaName%22%3A%22%E6%9D%B1%E4%BA%AC%E9%83%BD%E6%B8%8B%E8%B0%B7%E5%8C%BA%22%7D%7D; _ga=GA1.4.390695535.1490711452; _gid=GA1.3.327290351.1519183040; _gid=GA1.4.327290351.1519183040; AMCVS_02C51F6A550AFE4E0A4C98A7%40AdobeOrg=1; s_cc=true; _dvp=TK:C0ObxjerU; s_sq=%5B%5BB%5D%5D; _gat=1; _gat_podium=1; _gat_bpodium=1; AMCV_02C51F6A550AFE4E0A4C98A7%40AdobeOrg=-1176276602%7CMCMID%7C07073385839843923560743402509540858020%7CMCAID%7CNONE%7CMCOPTOUT-1519205820s%7CNONE%7CMCAAMLH-1519351877%7C11%7CMCAAMB-1519803420%7Cj8Odv6LonN4r3an7LhD3WZrU1bUpAkFkkiY1ncBR96t2PTI; s_ppv_noll=video%253Aelement%253Avideo%253D36612%2C44%2C13%2C258%2C1707%2C258%2C1707%2C960%2C1.5%2CL; s_catvwd_nol=news%3Enhkworld%3Eindex%3Eguide%3Eolympic%3Evideo%3E0ec2dae2-62b0-4b8a-abdd-1fac6a9429e9%3Esports2%3Esports%3Ecgisearch%3Eradio%3Ecommon%3Econtents%3Eathletes%3Eapi; s_nr_nol=1519198620755-Repeat; s_ppv_nol=video%253Aelement%253Avideo%253D36612%2C32%2C75%2C608%2C1707%2C258%2C1707%2C960%2C1.5%2CL\r\n".
		"Cookie: _dvp=TK:C0ObxjerU\r\n".
		//"Accept: */*\r\n".
		//"User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/64.0.3282.167 Safari/537.36\r\n".
		//"Referer: https://sports.nhk.or.jp/divaPlayer/html5/divaplayer.html?videoid=".$videoId."&configurationFileUrl=https://sports.nhk.or.jp//divaplayer/settings/settings-vod-ja.xml&divaAutoplay=false\r\n",
		"Referer: https://sports.nhk.or.jp/\r\n",
		"",
		"content" => $request,
)));

$rawToken = file_get_contents("https://sports.nhk.or.jp/api/api-akamai/tokenize",false,$context);
$token = json_decode($rawToken);
//var_dump($token);
if(!$onlyUrl) echo "URL: ";
echo $token->ContentUrl;

if(!$onlyUrl) echo "\n";



