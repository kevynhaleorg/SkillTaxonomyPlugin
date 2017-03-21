<?php
/**
 * @package Akismet
 */
/*
Plugin Name: Ky Skill Taxonomy
Plugin URI: https://kydeveloper.com/
Description: Plugin to add custom taxonomies and fields to handle the skills page on the portfolio website. Tracks skills used in a job, project, and blog and reflects that in a count per skill. Provides a rest API to retrieve the data.
Version: 1.0
Author: Automattic
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

function skill_init() {
	// create a new taxonomy
	register_taxonomy(
		'skill',
		'post',
		array(
			'label' => __( 'Skills' ),
			'rewrite' => array( 'slug' => 'skill' ),
			'capabilities' => array(
				'assign_terms' => 'edit_guides',
				'edit_terms' => 'publish_guides'
			)
		)
	);
}
add_action( 'init', 'skill_init' );

// Set up new Taxonomy `SKILL`s custom fields:
// 1) active (boolean)
// 2) order (number)

function my_taxonomy_add_meta_fields( $taxonomy ) {
    ?>
    <div class="form-field term-group">
        <label for="my_field"><?php _e( 'My Field', 'ky-skill-taxonomy' ); ?></label>
        <input type="text" id="my_field" name="my_field" />
    </div>
    <?php
}
add_action( 'my_taxonomy_add_form_fields', 'my_taxonomy_add_meta_fields', 10, 2 );

function my_taxonomy_edit_meta_fields( $term, $taxonomy ) {
    $my_field = get_term_meta( $term->term_id, 'my_field', true );
    ?>
    <tr class="form-field term-group-wrap">
        <th scope="row">
            <label for="my_field"><?php _e( 'My Field', 'ky-skill-taxonomy' ); ?></label>
        </th>
        <td>
            <input type="text" id="my_field" name="my_field" value="<?php echo $my_field; ?>" />
        </td>
    </tr>
    <?php
}
add_action( 'my_taxonomy_edit_form_fields', 'my_taxonomy_edit_meta_fields', 10, 2 );

function my_taxonomy_save_taxonomy_meta( $term_id, $tag_id ) {
    if( isset( $_POST['my_field'] ) ) {
        update_term_meta( $term_id, 'my_field', esc_attr( $_POST['my_field'] ) );
    }
}
add_action( 'created_my_taxonomy', 'my_taxonomy_save_taxonomy_meta', 10, 2 );
add_action( 'edited_my_taxonomy', 'my_taxonomy_save_taxonomy_meta', 10, 2 );

function my_taxonomy_add_field_columns( $columns ) {
    $columns['my_field'] = __( 'My Field', 'ky-skill-taxonomy' );

    return $columns;
}
add_filter( 'manage_edit-my_taxonomy_columns', 'my_taxonomy_add_field_columns' );






?>