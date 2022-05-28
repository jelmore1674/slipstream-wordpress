<?php

if( ! class_exists('acf_field_menu_order') ) :

class acf_field_menu_order extends acf_field_number {
	
	
	/*
	*  initialize
	*
	*  This function will setup the field type data
	*
	*  @type	function
	*  @date	5/03/2014
	*  @since	5.0.0
	*
	*  @param	n/a
	*  @return	n/a
	*/
	
	function initialize() {
		
		// vars
		$this->name = 'menu_order';
		$this->label = __("Menu Order",FEA_NS);
        $this->category = __( "Post", FEA_NS );
		$this->defaults = array(
			'default_value'	=> '',
			'min'			=> '0',
			'max'			=> '',
			'step'			=> '',
			'placeholder'	=> '',
			'prepend'		=> '',
			'append'		=> ''
		);
        add_filter( 'acf/load_field/type=number',  [ $this, 'load_menu_order_field'], 2 );
        add_filter( 'acf/update_value/type=' . $this->name,  [ $this, 'pre_update_value'], 9, 3 );      
	}

    function load_menu_order_field( $field ){
        if( ! empty( $field['custom_menu_order'] ) ){
            $field['type'] = 'menu_order';
        }
        return $field;
    }

    function load_field( $field ){
        $field['name'] = $field['type'];
        return $field;
    }

    function prepare_field( $field ){
        $field['type'] = 'number';

        return $field;
    }
    public function load_value( $value, $post_id = false, $field = false ){
        if( $post_id && is_numeric( $post_id ) ){  
            $edit_post = get_post( $post_id );
            $value = $edit_post->menu_order;
        }
        return $value;
    }

    function pre_update_value( $value, $post_id = false, $field = false ){
        if( $post_id && is_numeric( $post_id ) ){  
            $post_to_edit = [
                'ID' => $post_id,
            ];
            $post_to_edit['menu_order'] = $value;
            remove_action( 'acf/save_post', '_acf_do_save_post' );
            wp_update_post( $post_to_edit );
            add_action( 'acf/save_post', '_acf_do_save_post' );
        }
        return $value;
    }
    
}

// initialize
acf_register_field_type( 'acf_field_menu_order' );

endif; // class_exists check

?>