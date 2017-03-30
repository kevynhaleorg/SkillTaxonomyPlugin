<?php
/**
 * @package KySkillTaxonomy
 */
/*
Plugin Name: Ky Skill Taxonomy
Plugin URI: https://kydeveloper.com/
Description: Plugin to add custom taxonomies and fields to handle the skills page on the portfolio website. Tracks skills used in a job, project, and blog and reflects that in a count per skill. Provides a rest API to retrieve the data.
Version: 1.0
Author: Kevyn Hale
Author URI: kydeveloper.com
License: GPLv2 or later
Text Domain: kydeveloper
*/

/*
This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.

Copyright 2005-2015 Automattic, Inc.
*/

// Add Custom Skill Taxonomy

include("skill-meta.php");

$KY_SKILL_META = new KY_SKILL_META();
$KY_SKILL_META -> init();

add_action( 'rest_api_init', function () {
	register_rest_route( 'kyskills/v1', '/skills', array(
		'methods' => 'GET',
		'callback' => 'get_skills',
	) );
} );

add_action( 'rest_api_init', function () {
	register_rest_route( 'kyskills/v1', '/skill', array(
		'methods' => 'GET',
		'callback' => 'get_skill',
	) );
} );

function get_skills() {
	$skills = get_terms( array(
    	'taxonomy' => 'skill',
    	'hide_empty' => false,
		) );

	$result = [];
	$i = 0;
	foreach ($skills as $skill) {

		$result[$i]['name'] = $skill->name;
		$result[$i]['slug'] = $skill->slug;
		$result[$i]['id'] = $skill->term_id;
		$result[$i]['description'] = $skill->description;
		$result[$i]['count'] = $skill->count;

		$optional = get_option( "taxonomy_" . $skill->term_id );
		$result[$i]['type'] = $optional['class_type_meta'];
		$result[$i]['active'] = $optional['class_term_meta'];


		$result[$i]['image'] = wp_get_attachment_url ( 
			get_term_meta ( $skill->term_id, 'category-image-id', true ));

		$i++;
	}
	
	return $result;
}

function get_skill( $data ) {

	$response = [];

	if (!$data['slug'] || !$data['type']) {
		return array(
			'result' => 'error',
			'message' => 'Must provide both a slug and type.'
			);
	}

	$slug = $data['slug'];
	$type = $data['type'];

	$args = array(
		'post_type'         => 'post',
    	'order'             => 'DESC',
    	'orderby'           => 'date',
    	'posts_per_page'    => 3,
		'tax_query' => array(
	    	array(
	    		'taxonomy' => 'skill',
	    		'field' => 'slug',
	    		'terms' => $slug
	     	),
	    	array(
	    		'taxonomy' => 'category',
	    		'field' => 'slug',
	    		'terms' => $type
	    	)
	  )
	);
	$search = new WP_Query( $args );

	$count = 0;
	while ($search->have_posts()) {

		$search->the_post();
		$response[$count]['id'] = get_the_ID($post->ID);
	    $response[$count]['thumbnail'] = get_the_post_thumbnail_url ( $post->ID );
	    $response[$count]['title'] = get_the_title($post->ID);

	    $count++;
	}

	unset($search);	

	wp_reset_query();

	return $response;

}










?>