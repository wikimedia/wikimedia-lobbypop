<?php 
$links = array(
	'http://stats.wikimedia.org/wikimedia/animations/growth/AnimationProjectsGrowthWp.html?canvas_width=1800&canvas_height=1000',
	'http://stats.wikimedia.org/wikimedia/animations/growth/AnimationProjectsGrowthWb.html?canvas_width=1800&canvas_height=1000',
	'http://stats.wikimedia.org/wikimedia/animations/growth/AnimationProjectsGrowthWn.html?canvas_width=1800&canvas_height=1000',
	'http://stats.wikimedia.org/wikimedia/animations/growth/AnimationProjectsGrowthWq.html?canvas_width=1800&canvas_height=1000',
	'http://stats.wikimedia.org/wikimedia/animations/growth/AnimationProjectsGrowthWs.html?canvas_width=1800&canvas_height=1000',
	'http://stats.wikimedia.org/wikimedia/animations/growth/AnimationProjectsGrowthWv.html?canvas_width=1800&canvas_height=1000',
	'http://stats.wikimedia.org/wikimedia/animations/growth/AnimationProjectsGrowthWk.html?canvas_width=1800&canvas_height=1000',
);
header( 'Location: ' . $links[array_rand( $links )] );