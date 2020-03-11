<?php

    defined('ABSPATH') or die('No script kiddies please!');
    class LDC_Meta_Box extends LDC_Plugin_Base {

    // ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

    static public function init($file = '', $version = ''){
        if(parent::init($file, $version)){
            self::add_setting('fix-validation', array(
                'name' => 'Fix validation?',
                'on_label'  => '<i class="dashicons dashicons-yes"></i>',
                'std' => 1,
                'style' => 'square',
    			'type' => 'switch',
            ));
            if(self::get_setting('fix-validation')){
                add_action('rwmb_enqueue_scripts', array(get_class(), 'rwmb_enqueue_scripts'));
            }
        }
	}

    // ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

    static public function rwmb_enqueue_scripts($object){
        if(!empty($object->meta_box['validation'])){
            $url = self::get_assets_url() . 'js/validate.js';
            wp_dequeue_script('rwmb-validate');
            wp_deregister_script('rwmb-validate');
            wp_enqueue_script('rwmb-validate', $url, array('jquery-validation', 'jquery-validation-additional-methods'), self::get_slug() . '-' . self::get_version(), true);
            if(is_callable(array('RWMB_Helpers_Field', 'localize_script_once'))){
                RWMB_Helpers_Field::localize_script_once('rwmb-validate', 'rwmbValidate', array(
                    'summaryMessage' => esc_html__('Please correct the errors highlighted below and try again.', 'meta-box'),
                ));
            } elseif(is_callable(array('RWMB_Helpers_Field', 'localize_script_once'))){
                RWMB_Field::localize_script('rwmb-validate', 'rwmbValidate', array(
                    'summaryMessage' => esc_html__('Please correct the errors highlighted below and try again.', 'meta-box'),
                ));
            }
        }
	}

    // ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

    }
