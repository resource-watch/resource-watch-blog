<?php
global $wpdb;

$types = array();
foreach ( $GLOBALS[ 'wp_post_types' ] as $key => $val ) {
    if ( $val->public && !in_array( $key, array( 'attachment' ) ) ) {
        $types[ ] = $key;
    }
}

$this->get_broken_links();
$total_posts_pages = $wpdb->get_var(
    "
            SELECT COUNT(*) FROM {$wpdb->posts}
            WHERE post_type IN (" . wpml_prepare_in( $types ) . ")
                AND post_status NOT IN ('auto-draft')
                AND ID NOT IN
                            (
                                SELECT m.post_id FROM {$wpdb->postmeta} m
                                JOIN {$wpdb->posts} p ON m.post_id = p.ID
                                WHERE m.meta_key = '_alp_processed'
                                    AND p.post_type IN (" . wpml_prepare_in( $types ) . ")
                                    AND p.post_status NOT IN ('auto-draft')
                            )
        "
);

$total_posts_pages_processed = (int) $wpdb->get_var(
    "
            SELECT COUNT(m.meta_id) FROM {$wpdb->postmeta} m
            JOIN {$wpdb->posts} p ON p.ID = m.post_id
            WHERE m.meta_key = '_alp_processed'
                AND p.post_type IN (" . wpml_prepare_in( $types ) . ")
                AND p.post_status NOT IN ('auto-draft')
        "
);
?>

<div class="wrap">

    <h2><?php echo __('Setup Sticky Links', 'wpml-sticky-links') ?></h2>    
    
    <h3><?php _e('Options', 'wpml-sticky-links')?></h3>
    <form name="icl_save_sl_options" id="icl_save_sl_options" action="" method="post">
    <?php wp_nonce_field('icl_sticky_save'); ?>
    <ul>
        <li>
            <label><input type="checkbox" name="icl_sticky_links_widgets" value="1" 
            <?php if($this->settings['sticky_links_widgets']):?>checked="checked"<?php endif;?>  />
            &nbsp;<?php _e('Turn links in text widgets to Sticky', 'wpml-sticky-links')?></label>
        </li>
    </ul>
    <p>
        <a class="button" name="save" id="save" href="#"><?php echo __('Apply','wpml-sticky-links') ?></a>
        <span class="icl_ajx_response" id="icl_ajx_response2"></span>
    </p>    
    </form>

    <p>
    <span id="alp_re_scan_toscan"><?php echo $total_posts_pages ?></span> <?php echo __('posts and pages not processed', 'wpml-sticky-links')?>    
    <input type="submit" name="re_scan" value="<?php echo __('Scan', 'wpml-sticky-links') ?>" id="alp_re_scan_but" <?php if(!$total_posts_pages):?>disabled="disabled"<?php endif;?> class="button-secondary action" title="<?php echo __('Replace permalinks with sticky links in posts that have not been checked', 'wpml-sticky-links'); ?>" />
    <input type="submit" name="re_scan" value="<?php echo __('Scan ALL posts', 'wpml-sticky-links') ?>" id="alp_re_scan_but_all" class="button-secondary action"
        title="<?php echo __('Replace permalinks with sticky links in all blog posts', 'wpml-sticky-links'); ?>" />
    <img id="alp_ajx_ldr_1" src="<?php echo WPML_STICKY_LINKS_URL ?>/res/img/ajax-loader.gif" width="16" height="16" style="display:none" alt="loading" />
    </p>
    
    <h3><?php echo __('Broken Links', 'wpml-sticky-links'); ?></h3>
    <table class="widefat" width="100%" border="1">    
    <thead>
    <tr>
        <th scope="col"><?php echo __('Post/page', 'wpml-sticky-links') ?></th>
        <th scope="col"><?php echo __('Broken link', 'wpml-sticky-links') ?></th>
        <th scope="col"><?php echo __('Suggestions', 'wpml-sticky-links') ?></th>
    </tr>
    </thead>
    
    
    <?php if($this->broken_links): ?>
        <?php foreach($this->broken_links as $bl):$links = unserialize($bl->links); if(is_string($links)) $links = unserialize($links); ?>    
        <tr>
            <td rowspan="<?php echo count($links)+1 ?>" valign="top" style="background-color:#eee">
                <a title="Edit post" href="<?php echo get_edit_post_link($bl->ID) ?>"><?php echo $bl->post_title?></a>
            </td>
        </tr> 
        <?php $incr = 0; ?>    
        <?php if($links) foreach($links as $k=>$l): $incr++; ?>
        <tr>
        <td valign="top" id="alp_bl_<?php echo $bl->ID ?>_<?php echo $incr ?>"><?php echo $k ?></td>       
        <td>
            <?php if(!empty($l['suggestions'])): ?>
            <?php foreach($l['suggestions'] as $key=>$sug): ?>
            <?php echo $sug['perma'] . '<a class="alp_use_sug" id="alp_use_sug_'.$key.'_'.$bl->ID.'_'.$incr.'" href="javascript:;"> ('.__('use', 'wpml-sticky-links').')</a>' . '<br/>'; ?>
            <?php endforeach; ?>
            <?php else: ?>
            <?php echo __('No suggestions', 'wpml-sticky-links'); ?>
            <?php endif; ?>
         </td>
        </tr>       
        <?php endforeach ;?>
        <?php endforeach ;?>
    <?php else: ?>
        <tr><td colspan="3" align="center"><?php echo __('empty', 'wpml-sticky-links') ?></td></tr>
    <?php endif; ?>
    </table>
    
    <p>
    <?php echo __('Revert sticky urls to permalinks', 'wpml-sticky-links') ?> <input type="button" id="alp_revert_urls" value="<?php echo __('Start', 'wpml-sticky-links')?>" class="button-secondary action" <?php if(!$total_posts_pages_processed): ?>disabled="disabled"<?php endif; ?> 
    title="<?php echo __('Change sticky links back to Wordpress permalinks', 'wpml-sticky-links'); ?>" /> 
        <span id="alp_rev_items_left"><?php if($total_posts_pages_processed){ echo $total_posts_pages_processed; echo ' '; echo __('items in queue', 'wpml-sticky-links'); } ?></span>
        <img id="alp_ajx_ldr_2" src="<?php echo WPML_STICKY_LINKS_URL ?>/res/img/ajax-loader.gif" width="16" height="16" style="display:none" alt="loading" />
    </p>
    
</div>