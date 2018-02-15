<?php
/**
 * The template used for displaying single page content of a preek
 */
get_header(); ?>

<?php
 function play($post_id) {
    $url = get_post_meta( $post_id, 'preek_url', true );
    if (preg_match("/https:\/\/drive.google.com\/open\?id=([a-zA-Z0-9_]+)/", $url, $match)) {
         $GoogleDriveFileID = $match[1];
         $url_play = "https://docs.google.com/uc?export=open&id=" . $GoogleDriveFileID;
         $url_download = "https://docs.google.com/uc?export=download&id=" . $GoogleDriveFileID;
      } else {
         $url_play = "";
         $url_download = "";
     }
     return [$url_play, $url_download];
 }
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

    <div class="entry-main">

        <?php do_action('vantage_entry_main_top') ?>

        <header class="entry-header">
            <?php if( has_post_thumbnail() && siteorigin_setting('blog_featured_image_type') == 'large' ): ?>
            <div class="entry-thumbnail">
                <a href="<?php the_permalink() ?>">
                    <?php the_post_thumbnail( 'vantage-thumbnail-no-sidebar' ) ?>
                </a>
            </div>
            <?php endif; ?>

        </header>
        <!-- .entry-header -->

        <div class="entry-content">

            <?php /* Start the Loop */ ?>
            <?php while ( have_posts() ) : the_post(); ?>
            <?php
                $post_id = $post->ID;
                $skriflesing = get_post_meta( $post_id, 'preek_skriflesing', true );
                $prediker_taxonomy = wp_get_post_terms($post_id, 'preek_prediker');
                if ( empty($prediker_taxonomy) )
                $prediker = '';
                else
                $prediker = $prediker_taxonomy[0]->name;
                $reeks_taxonomy = wp_get_post_terms($post_id, 'preek_reeks');
                if ( empty($reeks_taxonomy) )
                $reeks = '';
                else
                $reeks = $reeks_taxonomy[0]->name;
                $beskrywing = get_post_meta( $post_id, 'preek_beskrywing', true );
                $datum = get_post_meta( $post_id, 'preek_datum', true );
                $ogg_aand = get_post_meta( $post_id, 'preek_ogg_aand', true );
                $date_parts = get_date_parts($datum);
                $dag = $date_parts[2];
                $maand = $date_parts[1];
                $jaar = $date_parts[0];

                $file_id = get_post_meta( $post_id, 'preek_file', true );
                if (empty($file_id)) {
                    $file = "";
                } else {
                    $file = wp_get_attachment_url($file_id);
                }
                [$play_url, $download_url] = play($post_id);
            ?>
                <div class="box box_effect">
                    <div class="preek_datum date">
                        <?php
        $content = '<div>'
            . '<div class="month">' . $maand . '</div>'
            . '<div class="day">' . $dag . '</div>'
            . '<div class="ogg_aand">' . $ogg_aand . '</div>'
            . '</div>'
            . '<div class="year">' . $jaar. '</div>';
            echo $content;
        ?>
                            <div class="preek_tags">
                                <?php
        $tags = wp_get_post_tags( $post_id, array('fields' => 'names') );
        $content = '';
        foreach ($tags as $tag) {
            $content .= '<span class="tag">' . $tag . '</span>' . '<br/>';
        }
        echo $content;
        ?>
                            </div>
                    </div>
                    <!-- div .preek_datum date -->
                    <div class="preek_detail">
                        <?php
        $title = the_title('', '', false);
        $content = '<h2>' . $title . '</h2>';
        if ( !empty($reeks) )
                $content .= '<p class="reeks">' . $reeks . '</p>';
        $content .= ''
            . '<p class="prediker">' . $prediker . '</p>'
            . '<p class="skriflesing">' . $skriflesing . '</p>';
        if ( !empty($beskrywing) )
            $content .= '<p class="beskrywing">' . $beskrywing . '</p>';
        echo $content;
        ?>
                    </div>
                    <!-- div .preek_detail -->
                    <div class="preek_widgets">
                        <?php
            if (empty($file)) {
                $content = ''
                    . '<div style="display: inline-block"><a style="text-decoration:none; padding-left: 0px" class="fa fa-play fa-2x" href="' . $play_url . '"></a></div>'
                    . '<div style="display: inline-block; padding-left: 15px"><a style="text-decoration:none" class="fa fa-download fa-2x" href="' . $download_url . '"></a></div>';
                } else {
                $content = ''
                    . '<div style="display: inline-block"><a style="text-decoration:none; padding-left: 0px" class="fa fa-play fa-2x" href="' . $file . '"></a></div>'
                    . '<div style="display: inline-block; padding-left: 15px"><a style="text-decoration:none" class="fa fa-download fa-2x" href="' . $file . '"></a></div>';
            }
            echo $content;
        ?>
                    </div>
                    <!-- div .preek_widgets -->
                </div>
                <!-- div .box box_effect -->

                <?php endwhile; ?>
        </div>
        <!-- .entry-content -->

        <?php do_action('vantage_entry_main_bottom') ?>

    </div>
    <div style="margin-bottom:1.5em"></div>
</article>
<!-- #post-<?php get_Footer(); ?> -->
