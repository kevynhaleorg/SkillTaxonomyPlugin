<?php
/**
 * @package Akismet
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

function skill_init() {
	// create a new taxonomy

    $labels = array(
        'name'                           => 'Skills',
        'singular_name'                  => 'Skills',
        'search_items'                   => 'Search Skills',
        'all_items'                      => 'All Skills',
        'edit_item'                      => 'Edit Skill',
        'update_item'                    => 'Update Skill',
        'add_new_item'                   => 'Add New Skill',
        'new_item_name'                  => 'New Skill Name',
        'menu_name'                      => 'Skill',
        'view_item'                      => 'View Skill',
        'popular_items'                  => 'Popular Skill',
        'separate_items_with_commas'     => 'Separate skills with commas',
        'add_or_remove_items'            => 'Add or remove skills',
        'choose_from_most_used'          => 'Choose from the most used skills',
        'not_found'                      => 'No skills found'
  ); 


	register_taxonomy(
		'skill',
		'post',
		array(
            'label'              => __( 'Skill' ),
			'labels'             => $labels,
            'hierarchical'       => false,
            'show_in_rest'       => true,
		)
	);
}
add_action( 'init', 'skill_init', 0 );

// Set up new Taxonomy `SKILL`s custom fields:
// 1) active (boolean)
// 2) order (number)

function skill_add_meta_fields( $taxonomy ) {
    ?>
        <div class="form-field">
            <label for="term_meta[class_term_meta]"><?php _e( 'Active', 'active' ); ?></label>
            <select name="term_meta[class_term_meta]" id="term_meta[class_term_meta]">
                <option value="false">false</option>
                <option value="true">true</option>
            </select>
            <p class="description"><?php _e( 'Enter a value for this field','active' ); ?></p>
        </div>
        <div class="form-field">
            <label for="term_meta[class_type_meta]"><?php _e( 'Type', 'ky_type' ); ?></label>
            <select name="term_meta[class_type_meta]" id="term_meta[class_type_meta]">
                <option value="development">Development</option>
                <option value="devops">Devops</option>
                <option value="design">Design</option>
            </select>
            <p class="description"><?php _e( 'Enter a value for this field','ky_type' ); ?></p>
        </div>
        <div class="form-field">
        <label for="series_image"><?php _e( 'Series Image:', 'journey' ); ?></label>
        <input type="text" name="series_image[image]" id="series_image[image]" class="series-image" value="<?php echo $seriesimage; ?>">
        <input class="upload_image_button button" name="_add_series_image" id="_add_series_image" type="button" value="Select/Upload Image" />
        <script>
            jQuery(document).ready(function() {
                jQuery('#_add_series_image').click(function() {
                    wp.media.editor.send.attachment = function(props, attachment) {
                        jQuery('.series-image').val(attachment.url);
                    }
                    wp.media.editor.open(this);
                    return false;
                });
            });
        </script>
    </div>

    <?php
}
add_action( 'skill_add_form_fields', 'skill_add_meta_fields', 10, 2 );

function skill_edit_meta_fields( $term, $taxonomy ) {
    $t_id = $term->term_id;
    $term_meta = get_option( "taxonomy_$t_id" ); 
    $value = esc_attr( $term_meta['class_term_meta']);
    $valueType = esc_attr( $term_meta['class_type_meta']);

       ?>
        <tr class="form-field">
        <th scope="row" valign="top"><label for="term_meta[class_term_meta]"><?php _e( 'Active', 'active' ); ?></label></th>
            <td>
                <select name="term_meta[class_term_meta]" id="term_meta[class_term_meta]" value="<?php echo esc_attr( $term_meta['class_term_meta'] ) ? esc_attr( $term_meta['class_term_meta'] ) : ''; ?>">
                    <option value="false" <?php echo $value == "false" ? "selected" : '';?> >false</option>
                    <option value="true" <?php echo $value == "true" ? "selected" : '';?> >true</option>
                </select>
                <p class="description"><?php _e( 'Enter a value for this field','active' ); ?></p>
            </td>
        </tr>

        <tr class="form-field">
        <th scope="row" valign="top"><label for="term_meta[class_type_meta]"><?php _e( 'Type', 'ky_type' ); ?></label></th>
            <td>
                <select name="term_meta[class_type_meta]" id="term_meta[class_type_meta]" value="<?php echo esc_attr( $term_meta['class_type_meta'] ) ? esc_attr( $term_meta['class_type_meta'] ) : ''; ?>">
                    <option value="development" <?php echo $valueType == "development" ? "selected" : '';?> >Development</option>
                    <option value="devops" <?php echo $valueType == "devops" ? "selected" : '';?> >Devops</option>
                    <option value="design" <?php echo $valueType == "design" ? "selected" : '';?> >Design</option>
                </select>
                <p class="description"><?php _e( 'Enter a value for this field','ky_type' ); ?></p>
            </td>
        </tr>
    <?php
}
add_action( 'skill_edit_form_fields', 'skill_edit_meta_fields', 10, 2 );

function skill_save_taxonomy_custom_meta_field( $term_id ) {
        if ( isset( $_POST['term_meta'] ) ) {
            
            $t_id = $term_id;
            $term_meta = get_option( "taxonomy_$t_id" );
            $cat_keys = array_keys( $_POST['term_meta'] );
            foreach ( $cat_keys as $key ) {
                if ( isset ( $_POST['term_meta'][$key] ) ) {
                    $term_meta[$key] = $_POST['term_meta'][$key];
                }
            }
            // Save the option array.
            update_option( "taxonomy_$t_id", $term_meta );
        }
        
    }  
add_action( 'edited_skill', 'skill_save_taxonomy_custom_meta_field', 10, 2 );  
add_action( 'create_skill', 'skill_save_taxonomy_custom_meta_field', 10, 2 );







?>