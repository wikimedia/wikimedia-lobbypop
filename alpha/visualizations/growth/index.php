<?php 
$links = array(
	'wb.html',
	'wk.html',
	'wn.html',
	'wp.html',
	'wq.html',
	'ws.html',
	'wv.html',
);
header( 'Location: ' . $links[array_rand( $links )] );