<?php
if( !class_exists( 'avia_conditional_mega_menu' ) )
{
    add_filter('avf_mega_menu_post_meta_fields','avia_save_conditional_menu_options',10,3);
    function avia_save_conditional_menu_options($check, $menu_id, $menu_item_db)
    {
        $check = array_merge($check, array('conditional'));
        return $check;
    }

    /**
     * This class helps the user to hide/show menu items of the mega menu by using conditionals
     */
    class avia_conditional_mega_menu
    {
        function __construct()
        {	
            add_action('admin_enqueue_scripts', array(&$this,'load_script'));
            add_action('init', array(&$this,'add_conditionals_to_config'));
            add_action('avia_mega_menu_option_fields', array(&$this,'output_conditional_menu_options'), 10, 4);

            add_filter( 'wp_nav_menu_objects', array(&$this,'apply_conditional_rules'), 10, 1);
        }

        function __destruct()
        {

        }

        function load_script($hook)
        {
            if( $hook != 'nav-menus.php' ) return;

            wp_register_script( 'avia-conditional-mega-menu', AVIA_JS_URL.'conditional_load/avia_conditional_mega_menu.js', array( 'jquery' ), '1.0.0', true);
            wp_enqueue_script( 'avia-conditional-mega-menu' );
        }

        function add_conditionals_to_config()
        {
            global $avia_config;
            /*
             * Add predefined conditions to the mega menu options
             * Use the avf_avia_menu_conditions filter to remove or add new conditions
             */
            $avia_config['menu_conditions'] = array(
                'is_user_logged_in' => array('title' => __('User is logged in', 'avia_framework')),
                'avia_is_user_logged_out' => array('title' => __('User is logged out', 'avia_framework')),
                'avia_condition_admin' => array('title' => __('User is Admin', 'avia_framework')),
                'avia_condition_editor' => array('title' => __('User is Editor', 'avia_framework')),
                'avia_condition_subscriber' => array('title' => __('User is Subscriber', 'avia_framework')),
                'avia_condition_author' => array('title' => __('User is Author', 'avia_framework')),
                'avia_condition_contributor' =>  array('title' => __('User is Contributor', 'avia_framework')),
                'is_front_page' => array('title' => __('Front Page', 'avia_framework')),
                'is_single' =>  array('title' => __('Single Post', 'avia_framework'), 'supports_id' => true),
                'is_page' =>  array('title' => __('Page', 'avia_framework'), 'supports_id' => true)
            );

            $avia_config['menu_conditions'] = apply_filters('avf_avia_menu_conditions', $avia_config['menu_conditions']);
        }


        function get_menu_item_settings($item)
        {
            $conditional_logic = get_post_meta($item->ID, '_menu-item-avia-conditional', false);

            if(!empty($conditional_logic) && is_array($conditional_logic))
            {
                $conditional_logic = $conditional_logic[0];
            }
            else
            {
                $conditional_logic = array();
            }

            return $conditional_logic;
        }


        function output_conditional_menu_options($output, $item, $depth, $args)
        {
            global $avia_config;
            if(!empty($avia_config['menu_conditions']) && is_array($avia_config['menu_conditions']))
            {
                $item_id = $item->ID;
                $key = "menu-item-avia-conditional";

                $value = $this->get_menu_item_settings($item);

                $value['enableconditionallogic'] = !empty($value['enableconditionallogic']) ? 'checked="checked"' : '';
                if(empty($value['conditional'])) $value['conditional'] = '';
                if(empty($value['conditionalid'])) $value['conditionalid'] = '';
                if(empty($value['conditionalcss'])) $value['conditionalcss'] = '';
                if(empty($value['conditionalvalue'])) $value['conditionalvalue'] = '';
                ?>

                <!-- *************** start conditional logic input fields *************** -->
                <p class="description description-wide avia_conditional_checkbox">
                    <label for="edit-<?php echo 'menu-item-avia-enableconditionallogic-'.$item_id; ?>">
                        <input type="checkbox" value="active" id="edit-<?php echo 'menu-item-avia-enableconditionallogic-'.$item_id; ?>" class="menu-item-avia-enableconditionallogic" name="<?php echo $key . "[". $item_id ."][enableconditionallogic]";?>" <?php echo $value['enableconditionallogic']; ?> /><label><?php _e('Enable Conditional Logic', 'avia_framework'); ?></label>
                    </label>
                </p>


                <div class="avia_conditional_logic_field">
                <p class="description description-wide">
                    <select id="edit-<?php echo 'menu-item-avia-conditional-'.$item_id; ?>" class="menu-item-avia-conditional" name="<?php echo $key . "[". $item_id ."][conditional]"; ?>">
                        <option <?php selected( 'show',  $value['conditional'] ) ?> value="show" class="hide_css_field"><?php _e( 'Show', 'avia_framework' ) ?></option>
                        <option <?php selected( 'hide',  $value['conditional'] ) ?> value="hide" class="hide_css_field"><?php _e( 'Hide', 'avia_framework' ) ?></option>
                        <option <?php selected( 'css',  $value['conditional'] ) ?> value="css" class="show_css_field"><?php _e( 'Add custom css class', 'avia_framework' ) ?></option>
                    </select>
                    <?php _e('if', 'avia_framework'); ?>

                    <select id="edit-<?php echo 'menu-item-avia-conditional-'.$item_id; ?>" class="menu-item-avia-conditionalvalue" name="<?php echo $key . "[". $item_id ."][conditionalvalue]"; ?>">
                    <?php foreach( $avia_config['menu_conditions'] as $condition => $content ): ?>
                        <?php $class = !empty($content['supports_id']) ? 'show_id_field' : 'hide_id_field'; ?>
                        <option <?php selected( $condition, $value['conditionalvalue'] ) ?> class="<?php echo $class; ?>" value="<?php echo $condition; ?>"><?php echo $content['title']; ?></option>
                    <?php endforeach ?>
                    </select>
                </p>


                <p class="description description-wide menu-item-avia-conditionalid">
                    <label for="edit-<?php echo 'menu-item-avia-conditionalid-'.$item_id; ?>">
                        <?php _e( 'Page/Post ID', 'avia_framework' ); ?><br />
                        <input type="text" id="edit-<?php echo 'menu-item-avia-conditionalid-'.$item_id; ?>" name="<?php echo $key . "[". $item_id ."][conditionalid]";?>" value="<?php echo $value['conditionalid']; ?>" />
                    </label>
                </p>


                <p class="description description-wide menu-item-avia-conditionalcss">
                    <label for="edit-<?php echo 'menu-item-avia-conditionalcss-'.$item_id; ?>">
                        <?php _e( 'Conditional CSS Class', 'avia_framework' ); ?><br />
                        <input type="text" id="edit-<?php echo 'menu-item-avia-conditionalcss-'.$item_id; ?>" name="<?php echo $key . "[". $item_id ."][conditionalcss]";?>" value="<?php echo $value['conditionalcss']; ?>" />
                    </label>
                </p>

            <?php
            }
            ?>
            </div>
            <!-- *************** end conditional logic input fields *************** -->
        <?php
        }



        function apply_conditional_rules($items)
        {
            global $avia_config;
            $hidden_items = array();

            foreach($items as $key => $item)
            {
                $show = true;
                $conditional_logic = $this->get_menu_item_settings($item);

                /* check if parent item is hidden. If yes we must hide the submenu item too */
                if(empty($conditional_logic['enableconditionallogic']) && !empty($hidden_items))
                {
                    if(in_array($item->menu_item_parent, $hidden_items)) $show = false;
                }

                if(!empty($conditional_logic['enableconditionallogic']) && !empty($avia_config['menu_conditions']) && is_array($avia_config['menu_conditions']))
                {
                    $condition_type = !empty($conditional_logic['conditional']) ? $conditional_logic['conditional'] : '';
                    $condition =  !empty($conditional_logic['conditionalvalue']) ? $conditional_logic['conditionalvalue'] : '';
                    $conditionalid = !empty($conditional_logic['conditionalid']) ? $conditional_logic['conditionalid'] : '';

                    if(function_exists($condition))
                    {
                        if(!empty($avia_config['menu_conditions'][$condition]['supports_id']))
                        {
                            if($conditionalid)
                            {
                                $condition_result = call_user_func($condition, $conditionalid);
                            }
                            else
                            {
                                $condition_result = call_user_func($condition);
                            }
                        }
                        else
                        {
                            $condition_result = call_user_func($condition);
                        }

                        if($condition_type == 'hide' && $condition_result) $show = false;
                        if($condition_type == 'show' && !$condition_result) $show = false;

                        if($condition_type == 'css' && $condition_result)
                        {
                            if(!empty($conditional_logic['conditionalcss'])) $item->classes[] = $conditional_logic['conditionalcss'];
                        }
                    }
                }

                if(!$show)
                {
                    $hidden_items[] = $item->ID;
                    unset($items[$key]);
                }
            }

            return $items;
        }

    }
}

new avia_conditional_mega_menu();

/* some predefined conditional functions */
if(!function_exists('avia_is_user_logged_out'))
{
    function avia_is_user_logged_out()
    {
        if( !is_user_logged_in() ) return true;
        return false;
    }
}

if(!function_exists('avia_condition_admin'))
{
    function avia_condition_admin()
    {
        global $current_user;
        if( is_user_logged_in() ) return in_array( 'administrator', $current_user->roles );
        return false;
    }
}

if(!function_exists('avia_condition_editor'))
{
    function avia_condition_editor()
    {
        global $current_user;
        if( is_user_logged_in() ) foreach( array( 'administrator', 'editor' ) as $role ) if( in_array( $role, $current_user->roles ) ) return true;
        return false;
    }
}

if(!function_exists('avia_condition_author'))
{
    function avia_condition_author() {
        global $current_user;
        if( is_user_logged_in() ) foreach( array( 'administrator', 'editor', 'author' ) as $role ) if( in_array( $role, $current_user->roles ) ) return true;
        return false;
    }
}

if(!function_exists('avia_condition_contributor'))
{
    function avia_condition_contributor()
    {
        global $current_user;
        if( is_user_logged_in() ) foreach( array( 'administrator', 'editor', 'author', 'contributor' ) as $role ) if( in_array( $role, $current_user->roles ) ) return true;
        return false;
    }
}

if(!function_exists('avia_condition_subscriber'))
{
    function avia_condition_subscriber()
    {
        global $current_user;
        if( is_user_logged_in() ) foreach( array( 'administrator', 'editor', 'author', 'contributor', 'subscriber' ) as $role ) if( in_array( $role, $current_user->roles ) ) return true;
        return false;
    }
}
