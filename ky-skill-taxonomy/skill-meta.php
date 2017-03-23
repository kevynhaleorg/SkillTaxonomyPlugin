<?php

class KY_SKILL_META {

	public function __construct() {
    	// no constructor params
  	}

  	/*
	  * Initialize KY_SKILL_META and run hooks
	  * @since 1.0.0
	*/

	public function init() {
		add_action( 'init', array($this, 'skill_init'), 0 );
		add_action( 'skill_add_form_fields', array($this, 'skill_add_meta_fields'), 10, 2 );
		add_action( 'skill_edit_form_fields', array($this, 'skill_edit_meta_fields'), 10, 2 );
		add_action( 'edited_skill', array($this, 'skill_save_taxonomy_custom_meta_field'), 10, 2 );  
		add_action( 'create_skill', array($this, 'skill_save_taxonomy_custom_meta_field'), 10, 2 );
		add_action( 'admin_footer', array($this, 'add_load_media_script'));
 	}

 	/*
	  * Create the custom taxonomy Skill
	  * @since 1.0.0
	*/

 	public function skill_init() {
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

	/*
	  * Add the following custom fields when creating a new skill:
	  * 1) Active # whether to show in blog or not
	  * 2) Type # opetions between development, devops, and design.
	  * 3) Image # Image to display for skill in blog
	  * @since 1.0.0
	*/

	public function skill_add_meta_fields( $taxonomy ) {	

	    wp_enqueue_media();// Required to enable wp.media in javascript	

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
	        <div class="form-field term-group">
	             <label for="category-image-id"><?php _e('Image', 'hero-theme'); ?></label>
	             <input type="hidden" id="category-image-id" name="category-image-id" class="custom_media_url" value="">
	             <div id="category-image-wrapper"></div>
	             <p>
	               <input type="button" class="button button-secondary ct_tax_media_button" id="ct_tax_media_button" name="ct_tax_media_button" value="<?php _e( 'Add Image', 'hero-theme' ); ?>" />
	               <input type="button" class="button button-secondary ct_tax_media_remove" id="ct_tax_media_remove" name="ct_tax_media_remove" value="<?php _e( 'Remove Image', 'hero-theme' ); ?>" />
	            </p>
	       </div>	

	    <?php
	}

	/*
	  * Add the following custom fields when editing a current skill:
	  * 1) Active # whether to show in blog or not
	  * 2) Type # opetions between development, devops, and design.
	  * 3) Image # Image to display for skill in blog
	  * @since 1.0.0
	*/

	public function skill_edit_meta_fields( $term, $taxonomy ) {	

	    wp_enqueue_media(); // Required to enable wp.media in javascript	

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

	        <tr class="form-field term-group-wrap">
	        	<th scope="row">
	            	<label for="category-image-id"><?php _e( 'Image', 'hero-theme' ); ?></label>
	          	</th>
	          	<td>
	            	<?php $image_id = get_term_meta ( $term -> term_id, 'category-image-id', true ); ?>
	            	<input type="hidden" id="category-image-id" name="category-image-id" value="<?php echo $image_id; ?>">
	            	<div id="category-image-wrapper">
		            	<?php if ( $image_id ) { ?>
		                	<?php echo wp_get_attachment_image ( $image_id, 'thumbnail' ); ?>
		            	<?php } ?>
	            	</div>
		            <p>
		             	<input type="button" class="button button-secondary ct_tax_media_button" id="ct_tax_media_button" name="ct_tax_media_button" value="<?php _e( 'Add Image', 'hero-theme' ); ?>" />
		            	<input type="button" class="button button-secondary ct_tax_media_remove" id="ct_tax_media_remove" name="ct_tax_media_remove" value="<?php _e( 'Remove Image', 'hero-theme' ); ?>" />
		            </p>
	          	</td>
	        </tr>
	    <?php
	}

	/*
	  * Hook to save the new custom fields to the custom taxonomy skill
	  * @since 1.0.0
	*/

	public function skill_save_taxonomy_custom_meta_field( $term_id ) {
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

	    if( isset( $_POST['category-image-id'] ) && '' !== $_POST['category-image-id'] ){
	        $image = $_POST['category-image-id'];
	        update_term_meta ( $term_id, 'category-image-id', $image );
	    } else {
	        update_term_meta ( $term_id, 'category-image-id', '' );
	    }
	        
	} 

	/*
	  * Javascript file needed to use wp media loader for custom image field.
	  * Thanks to @ https://catapultthemes.com/adding-an-image-upload-field-to-categories/
	  * Review later to customize as needed.
	  * @since 1.0.0
	*/

	public function add_load_media_script() { 
	    ?>
	    <script>
	    	jQuery(document).ready( function($) {
	       		function ct_media_upload(button_class) {
	         		var _custom_media = true,
	         		_orig_send_attachment = wp.media.editor.send.attachment;

	         		$('body').on('click', button_class, function(e) {
	           			var button_id = '#'+$(this).attr('id');
	           			var send_attachment_bkp = wp.media.editor.send.attachment;
	           			var button = $(button_id);
	           			_custom_media = true;
	           			
	           			wp.media.editor.send.attachment = function(props, attachment){
	             			if ( _custom_media ) {
	               			$('#category-image-id').val(attachment.id);
	               			$('#category-image-wrapper').html('<img class="custom_media_image" src="" style="margin:0;padding:0;max-height:100px;float:none;" />');
	               			$('#category-image-wrapper .custom_media_image').attr('src',attachment.sizes.thumbnail.url).css('display','block');
	             			} 
	             			else {
	               				return _orig_send_attachment.apply( button_id, [props, attachment] );
	             			}
	            		}
	         			
	         			wp.media.editor.open(button);
	         			return false;

	      			});
	     		}

	     		ct_media_upload('.ct_tax_media_button.button'); 

	     		$('body').on('click','.ct_tax_media_remove',function(){
	       			$('#category-image-id').val('');
	       			$('#category-image-wrapper').html('<img class="custom_media_image" src="" style="margin:0;padding:0;max-height:100px;float:none;" />');
	    		});

	     		// Thanks: http://stackoverflow.com/questions/15281995/wordpress-create-category-ajax-response
	     		$(document).ajaxComplete(function(event, xhr, settings) {
	       			var queryStringArr = settings.data.split('&');

	       			if( $.inArray('action=add-tag', queryStringArr) !== -1 ){
	         			var xml = xhr.responseXML;
	         			$response = $(xml).find('term_id').text();

	         			if($response!=""){
	           				// Clear the thumb image
	           				$('#category-image-wrapper').html('');
	         			}
	       			}

	    		});
	   		});

	 	</script>
	 	<?php 
	}


}


?>