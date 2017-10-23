<?php

header("Access-Control-Allow-Origin: *");
header('Access-Control-Allow-Credentials: true');
header('Access-Control-Max-Age: 86400');
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Content-type:application/json");


if(!isset($_GET['q']))
	die( json_encode([]) );

$feed = file_get_contents("https://news.google.com/news/feeds?q={$_GET['q']}&output=xml&hl=pt-br&ned=pt-br_br");
$feed = str_replace('<media:', '<', $feed);

$rss = simplexml_load_string($feed);

$output = [
	'title' => (string)$rss->channel->title,
	'url' => (string)$rss->channel->link,
	'description' => (string)$rss->channel->description,
	'items' => []
];
foreach($rss->channel->item as $item) {
	$output['items'][] = [
		'title' => (string)$item->title,
		'url' => (string)$item->link,
		'description' => (string)strip_tags($item->description),
	];
}

echo json_encode($output);