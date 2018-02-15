<?php
/**
 * Loop Name: Preke
 */
?>

<!-- <div class="box box-effect">
  <h2>Kennisgewing:</h2>
  <h3>Ons is tans besig om aan hierde bladsy te werk en vra verskoning vir enige ongerief.</h3>
  <h3>Nuwe preke sal binnekort beskikbaar wees.
     Preke wat reeds opgelaai is, is nogsteeds beskikbaar soos altyd.</h3>
</div> -->

<div class="box box-effect">
  <h2>Kennisgewing - 1 Feb 2018:</h2>
  <h3>Dankie vir u geduld. Die preke funksionaliteit is weer herstel.</h3>
  <h3>Heel nuutste preke is besig om gelaai te word. Ons is ook besig om andere op te lei om ons hiermee te help in toekoms.</h3>
  <h3>Dankie vir u geduld.</h3>
</div>

 
 <?php
 
 function play($post_id) {

	// TA-20170913: Gaan van nou af preke op egk.tygerberg se google drive laai
	// So moet voorsien vir beide as daar 'n file is en net die gdrive link tot
    // en wyl ons al die files ook geskuif het na gdrive maar is makliker om die
    // preek files te los waar dit is vir nou.

    $url = get_post_meta( $post_id, 'preek_url', true );

    if (preg_match("/https:\/\/drive.google.com\/open\?id=([a-zA-Z0-9_-]+)/", $url, $match)) {
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

</html>

<?php if ( have_posts() ) : ?>
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

			// TA-20170913: Gaan van nou af preke op egk.tygerberg se google drive laai
			// So moet voorsien vir beide as daar 'n file is en net die gdrive link tot
            // en wyl ons al die files ook geskuif het na gdrive maar is makliker om die
            // preek files te los waar dit is vir nou.

            $file_id = get_post_meta( $post_id, 'preek_file', true );
			if (empty($file_id)) {
                $file = "";
			} else {
            	$file = wp_get_attachment_url($file_id);
			}
            $urls = play($post_id);
            $play_url = $urls[0];
            $download_url = $urls[1];
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
            </div> <!-- div .preek_datum date -->
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
            </div> <!-- div .preek_detail -->
            <div class="preek_widgets">
                <?php
					if (empty($file)) {
                       $content = ''
                         . '<div style="display: inline-block"><a style="text-decoration:none; padding-left: 0px" class="fa fa-play fa-2x" href="' . $play_url . '"></a></div>'
                         . '<div style="display: inline-block; padding-left: 15px"><a style="text-decoration:none" class="fa fa-download fa-2x" href="' . $download_url . '"></a></div>';
                     } else {
                       $content = ''
                         . '<div style="display: inline-block"><a style="text-decoration:none; padding-left: 0px" class="fa fa-play fa-2x" href="' . $file . '"></a></div>'
                         . '<div style="display: inline-block; padding-left: 15px"><a style="text-decoration:none" class="fa fa-download fa-2x" href="' . $file . '" download></a></div>';
                    }
                    echo $content;
                ?>
            </div> <!-- div .preek_widgets -->
          </div> <!-- div .box box_effect -->

	<?php endwhile; ?>

	<?php vantage_content_nav( 'nav-below' ); ?>

<?php else : ?>

	<?php get_template_part( 'no-results', 'index' ); ?>

<?php endif; ?>
