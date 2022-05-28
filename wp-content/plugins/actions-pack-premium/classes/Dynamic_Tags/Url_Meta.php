<?php

namespace Actions_Pack\Dynamic_Tags;

use Elementor\Controls_Manager;
use ElementorPro\Modules\DynamicTags\Tags\Base\Data_Tag;
use ElementorPro\Modules\DynamicTags\Module;


Class Url_Meta extends Data_Tag {

    public function get_name() {
        return 'ap_url_meta';
    }

    public function get_title() {
        return __( 'URL Meta', 'actions-pack' );
    }

    public function get_group() {
        return 'actions-pack';
    }

    public function get_categories() {
        return [ Module::URL_CATEGORY ];
    }

    public function register_controls() {
        $this->add_control(
            'user_type',
            [
                'label' => __( 'User Type', 'actions-pack' ),
                'type' => Controls_Manager::SELECT,
                'options' =>[
                    'loggedin-user' => 'Logged-In User',
                    'post-author' => 'Post Author',
                    'author-archive' => 'Author Archive'
                ],
                'default' => 'logged-user'
            ]
        );
        $this->add_control(
            'meta_key',
            [
                'label' => __( 'Meta Key', 'actions-pack' ),
            ]
        );
    }

    public function get_panel_template_setting_key() {
        return 'meta_key';
    }

    public function get_panel_template() {
        return ' ({{{ meta_key }}})';
    }

    public function get_value( array $options = [] ){

        $url = '#';

        $user_type = $this->get_settings( 'user_type' );
        $meta_key = $this->get_settings( 'meta_key' );

        if( !empty($user_type) && !empty($meta_key))
        {
            switch($user_type)
            {
                case 'loggedin-user' :
                    if(is_user_logged_in()){
                        $url = get_user_meta( get_current_user_id(), $meta_key, true );
                    }
                    break;
                case 'post-author' :
                    if(in_the_loop()){
                        $url = get_user_meta( get_the_author_meta('ID'), $meta_key, true );
                    }
                    break;
                case 'author-archive' :
                    if(is_author()){
                        $url = get_user_meta( get_queried_object_id(), $meta_key, true );
                    }
                    break;
            }
        }

        return $url;
    }
}