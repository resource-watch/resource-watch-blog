<?php
/**
 *
 *  This function is the function that iterates over each comment entry and displays it.
 *  Actually its not really a loop but a function called by the iterating wordpress class
 *
 */

function avia_inc_custom_comments($comment, $args, $depth)
{
    $GLOBALS['comment'] = $comment;

    ?>
    <li <?php comment_class(); ?> id="li-comment-<?php comment_ID() ?>">

        <div id="comment-<?php comment_ID(); ?>">
        <article>
            <div class="gravatar">
                <?php
                //display the gravatar
                $gravatar_alt = esc_html(get_comment_author());
                echo get_avatar($comment,'60', '', $gravatar_alt); ?>
            </div>

            <!-- display the comment -->
            <div class='comment_content'>
                <header class="comment-header">
                    <?php
                    $author = '<cite class="comment_author_name">'.get_comment_author().'</cite>';
                    $link = get_comment_author_url();
                    if(!empty($link))
                        $author = '<a rel="nofollow" href="'.$link.'" >'.$author.'</a>';

                    printf('<cite class="author_name heading">%s</cite> <span class="says">%s</span>', $author, __('says:','avia_framework')) ?>
                    <?php edit_comment_link(__('(Edit)','avia_framework'),'  ','') ?>

                    <!-- display the comment metadata like time and date-->
                        <div class="comment-meta commentmetadata">
                            <a href="<?php echo htmlspecialchars( get_comment_link( $comment->comment_ID ) ) ?>">
                                <time <?php /* avia_markup_helper(array('context' => 'comment_time'));*/ ?> >
                                    <?php printf(__('%1$s at %2$s','avia_framework'), get_comment_date(),  get_comment_time()) ?>
                                </time>
                            </a>
                        </div>
                </header>

                <!-- display the comment text -->
                <div class='comment_text entry-content-wrapper clearfix' <?php /* avia_markup_helper(array('context' => 'comment_text')); */ ?>>
                <?php comment_text(); ?>
                <?php if ($comment->comment_approved == '0') : ?>
                <em><?php _e('Your comment is awaiting moderation.','avia_framework') ?></em>
                <?php endif; ?>
                <?php comment_reply_link(array_merge( $args, array('depth' => $depth, 'max_depth' => $args['max_depth']))) ?>
                </div>
            </div>

        </article>
    </div>
<?php
}
