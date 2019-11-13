<?php

    // ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

    defined('LDC_Meta_Box') or die('No script kiddies please!');

    // ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

    if(!function_exists('ldc_meta_box_bootstrap_fields')){
        function ldc_meta_box_bootstrap_fields($atts = array()){
            if(is_admin()){
                return;
            }
            $defaults = array(
				'btn_class' => 'primary',
				'column_width' => 'md',
                'version' => 4,
			);
			$atts = shortcode_atts($defaults, $atts);
            if($atts['version'] != 4){
                return;
            }
            add_action('rwmb_enqueue_scripts', function(){
                $url = plugin_dir_url(LDC_Meta_Box) . 'includes/select2-bootstrap4-theme-1.3.2/dist/select2-bootstrap4.min.css';
                wp_enqueue_style('select2-bootstrap4-theme', $url, array('rwmb-select2'), '1.3.2');
                $data = 'if(typeof(jQuery.fn.select2) !== \'undefined\'){ jQuery.fn.select2.defaults.set(\'theme\', \'bootstrap4\'); }';
                wp_add_inline_script('rwmb-select2', $data);
            });
            add_filter('rwmb_outer_html', function($outer_html) use($atts){
                if($outer_html){
                    require_once(plugin_dir_path(LDC_Meta_Box) . 'includes/simple-html-dom-1.9.1/simple_html_dom.php');
                    $outer_html = str_replace('class="rwmb-row', 'class="row', $outer_html);
    				$outer_html = str_replace('class="rwmb-column rwmb-column-', 'class="col-' . $atts['column_width'] . '-', $outer_html);
    				$html = str_get_html($outer_html);
    				foreach($html->find('div.rwmb-field') as $form_group){
    					if(!$form_group->hasClass('rwmb-hidden-wrapper')){
    						$form_group->addClass('form-group');
    						foreach($form_group->find('.rwmb-input, .rwmb-label') as $element){
    							$element->addClass('w-100');
    						}
    						foreach($form_group->find('.rwmb-input input, .rwmb-input select') as $element){
    							$element->addClass('mw-100');
    						}
    						foreach($form_group->find('div.rwmb-input-group') as $input_group){
    							$input_group->class = 'input-group';
    							foreach($input_group->find('span.rwmb-input-group-prepend') as $input_group_prepend){
    								$input_group_prepend->class = 'input-group-text';
    								$input_group_prepend->outertext = '<div class="input-group-prepend">' . $input_group_prepend->outertext . '</div>';
    							}
    							foreach($input_group->find('span.rwmb-input-group-append') as $input_group_append){
    								$input_group_append->class = 'input-group-text';
    								$input_group_append->outertext = '<div class="input-group-append">' . $input_group_append->outertext . '</div>';
    							}
    						}
    						foreach($form_group->find('input[type=email], input[type=number], input[type=password], input[type=text], input[type=url], textarea') as $input){
    							$input->addClass('form-control');
    						}
                            foreach($form_group->find('select') as $input){
    							$input->addClass('custom-select');
    						}
    						foreach($form_group->find('input[type=file]') as $input){
    							$input->addClass('custom-file-input');
    							$input->outertext = '<div class="custom-file">' . $input->outertext . '<label class="custom-file-label text-truncate" for="' . (isset($input->id) ? $input->id : '') . '" data-browse="' . ($input->getAttribute('data-browse') ? $input->getAttribute('data-browse') : 'Browse') . '">' . ($input->getAttribute('data-choose') ? $input->getAttribute('data-choose') : 'Choose file') . '</label></div>';
    						}
    						foreach($form_group->find('input[type=range]') as $input){
    							$input->addClass('custom-range');
    							$output = $input->next_sibling();
    							if($output){
    								$output->addClass('ml-0');
    							}
    							$parent = $input->parent();
    							if($parent){
    								$parent->addClass('text-center');
    							}
    						}
    						foreach($form_group->find('ul.rwmb-input-list') as $list){
    							$list_outertext = '';
    							foreach($list->find('input') as $input){
    								$id = uniqid();
    								$input->id = $id;
    								$input->addClass('custom-control-input');
    								$label = trim(str_replace($input->outertext, '', $input->parent()->innertext));
    								$inline = ($list->hasClass('rwmb-inline') ? ' custom-control-inline' : '');
    								$list_outertext .= '<div class="custom-control custom-' . $input->type . $inline . '">' . $input->outertext . '<label class="custom-control-label" for="' . $id . '">' . $label . '</label></div>';
    							}
    							$list->outertext = $list_outertext;
    						}
    						foreach($form_group->find('label.rwmb-switch-label') as $switch){
    							$input = $switch->find('input', 0);
    							$input->addClass('custom-control-input');
    							$description = $switch->next_sibling();
    							$description->addClass('ldc-meta-box-remove');
    							$switch->outertext = '<div class="custom-control custom-switch">' . $input->outertext . '<label class="custom-control-label" for="' . $input->id . '">' . ($description ? $description->innertext : '')  . '</label></div>';
    						}
    						foreach($form_group->find('p.description') as $description){
    							if(!$description->hasClass('ldc-meta-box-remove')){
    								$description->outertext = '<small class="form-text text-muted">' . $description->innertext . '</small>';
    							} else {
    								$description->outertext = '';
    							}
    						}
    					}
    				}
    				$outer_html = $html->save();
    			}
        		return $outer_html;
            });
            add_action('wp_enqueue_scripts', function() use($atts){
                $data = 'jQuery(function($){ $(\'button.rwmb-button\').each(function(){ if(!$(this).hasClass(\'.btn\')){ $(this).addClass(\'btn btn-' . $atts['btn_class'] . '\'); } }); });';
    			wp_add_inline_script('jquery', $data);
    			wp_enqueue_script('bs-custom-file-input', 'https://cdn.jsdelivr.net/npm/bs-custom-file-input/dist/bs-custom-file-input.min.js', array('jquery'), '1.3.2');
    			$data = 'jQuery(function($){ bsCustomFileInput.init(); });';
    			wp_add_inline_script('bs-custom-file-input', $data);
            });
            add_action('wp_head', function(){ ?>
               <style><?php
                   if(defined('FL_THEME_VERSION')){ ?>
                       .form-control {
                           font-size: 1rem !important;
                           height: calc(1.5em + .75rem + 2px) !important;
                           transition: border-color .15s ease-in-out, box-shadow .15s ease-in-out !important;
                       }
                       textarea.form-control {
                           height: auto !important;
                       }<?php
                   } ?>
                   .rwmb-button {
                       margin: 0 !important;
                   }
                   .rwmb-button.add-clone {
                       margin-left: 15px !important;
                   }
                   .rwmb-error {
                       background: transparent !important;
                       border: 0 !important;
                       border-radius: 0 !important;
                       margin: 0 !important;
                       padding: 0 !important;
                   }
                   .form-control.rwmb-error {
                       background: #fff !important;
                       background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='%23dc3545' viewBox='-2 -2 7 7'%3e%3cpath stroke='%23dc3545' d='M0 0l3 3m0-3L0 3'/%3e%3ccircle r='.5'/%3e%3ccircle cx='3' r='.5'/%3e%3ccircle cy='3' r='.5'/%3e%3ccircle cx='3' cy='3' r='.5'/%3e%3c/svg%3E") !important;
                       background-position: center right calc(0.375em + 0.1875rem) !important;
                       background-repeat: no-repeat !important;
                       background-size: calc(0.75em + 0.375rem) calc(0.75em + 0.375rem) !important;
                       border: 1px solid #ced4da !important;
                       border-color: #dc3545 !important;
                       border-radius: .25rem !important;
                       padding: .375rem .75rem !important;
                       padding-right: calc(1.5em + 0.75rem) !important;
                   }
                   textarea.form-control.rwmb-error {
                       padding-right: calc(1.5em + .75rem) !important;
                       background-position: top calc(.375em + .1875rem) right calc(.375em + .1875rem) !important;
                   }
                   .form-control.rwmb-error:focus {
                       border-color: #dc3545 !important;
                       box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25) !important;
                   }
                   p.rwmb-error {
                       color: #dc3545 !important;
                       display: block !important;
                       font-size: 80% !important;
                       font-weight: 400 !important;
                       margin: .25rem 0 0 !important;
                   }
                   .rwmb-field .select2-container {
                       min-width: 0 !important;
                       width: 100% !important;
                   }
                   .rwmb-file {
                       margin-bottom: 0 !important;
                   }
                   .rwmb-form-submit {
                       padding-top: 0 !important;
                   }
                   .rwmb-uploaded {
                       padding: 0 !important;
                   }
                   .select2-container--bootstrap4 .select2-selection--multiple .select2-selection__rendered {
                       max-width: 100% !important;
                   }
               </style><?php
            });
        }
    }

    // ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

    if(!function_exists('ldc_meta_box_fix_conditional_logic')){
        function ldc_meta_box_fix_conditional_logic(){
            add_action('init', function(){
                if(!defined('MB_FRONTEND_SUBMISSION_DIR') and defined('MB_USER_PROFILE_DIR')){
    				define('MB_FRONTEND_SUBMISSION_DIR', MB_USER_PROFILE_DIR);
    			}
            }, 21);
        }
    }

    // ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

    if(!function_exists('ldc_meta_box_fix_validation')){
        function ldc_meta_box_fix_validation(){
            add_action('rwmb_enqueue_scripts', function(RW_Meta_Box $object){
                if(!empty($object->meta_box['validation'])){
                    $url = plugin_dir_url(LDC_Meta_Box) . 'validate.js';
                    wp_dequeue_script('rwmb-validate');
    				wp_deregister_script('rwmb-validate');
    				wp_enqueue_script('rwmb-validate', $url, array('jquery-validation', 'jquery-validation-additional-methods'), 'ldc-meta-box-' . LDC_Meta_Box_Version, true);
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
            });
        }
    }

    // ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
