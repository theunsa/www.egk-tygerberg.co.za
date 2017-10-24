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

<?php

    global $query_string;

    /*$args = array(
      'post_type' => 'preek',
      'meta_key' => 'preek_prediker'
    );
    $posts_array = get_posts( $args );
    $predikers = array();
    foreach($posts_array as $pa) {
      array_push($predikers, get_post_meta( $pa->ID, 'preek_prediker', true ));
    }
    $predikers = array_unique($predikers);
    print_r($predikers);*/

    /*query_posts( $query_string . '&order=ASC' );*/
    /*query_posts('&meta_key=datum&orderby=meta_value' );*/
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
            	$url = get_post_meta( $post_id, 'preek_url', true );
                
            	//echo $url;
            	if (preg_match("/https:\/\/drive.google.com\/open\?id=([a-zA-Z0-9_]+)/", $url, $match)) {
                   $GoogleDriveFileID = $match[1];
                   $url_play = "https://docs.google.com/uc?export=open&id=" . $GoogleDriveFileID;
                   $url_download = "https://docs.google.com/uc?export=download&id=" . $GoogleDriveFileID;

                   $ch = curl_init();
                   curl_setopt($ch, CURLOPT_URL, $url_play);
                   curl_setopt($ch, CURLOPT_HEADER  ,1);
                   curl_setopt($ch, CURLOPT_RETURNTRANSFER  ,1);
                   curl_setopt($ch, CURLOPT_FOLLOWLOCATION  ,1);
                   curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
                   $content = curl_exec($ch);
                   //print_r($content);
                   // get cookies
                   $cookies = array();
                   preg_match_all('/Set-Cookie:(?<cookie>\s{0,}.*)$/im', $content, $cookies);
                   //print_r($cookies['cookie']); // show harvested cookies
                   $warning = trim(substr($cookies['cookie'][0],0,17));

                   if($warning == "download_warning"){
                      $token = explode('=', $cookies['cookie'][0], 2);
                      $token = explode(';', $token[1], 2);
                      //print_r($token);
                      $token = trim($token[0], ';');
                      //echo "TOKEN: $token";
                      $url_play = "https://docs.google.com/uc?export=open&confirm=" . $token . "&id=" . $GoogleDriveFileID;
                      $url_download = "https://docs.google.com/uc?export=download&confirm=" . $token . "&id=" . $GoogleDriveFileID;
                   }
                } else {
                   echo "error, file not found";
                   $url_play = "";
                   $url_download = "";
               }
			} else {
            	$file = wp_get_attachment_url( $file_id );
			}


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
                $content = '<h2>';
                $content .= $title . '</h2>';
                if ( !empty($reeks) )
                      $content .= '<p class="reeks">' . $reeks . '</p>';
                $content .= ''
                    . '<p class="prediker">' . $prediker . '</p>'
                    . '<p class="skriflesing">' . $skriflesing . '</p>'
                    . '<p class="beskrywing">' . $beskrywing . '</p>';
                echo $content;
                ?>
            </div> <!-- div .preek_detail -->
            <div class="preek_widgets">
                <?php
					if (empty($file)) {
                       $content = ''
                           . '<audio controls preload="metadata">'
                           . '<source src="' . $url_play . '" type="audio/mpeg">'
                           . 'Aanlyn luister nie beskikbaar in jou browser.'
                           . '</audio>'
                           . '<a class="fa fa-download fa-2x" href="' . $url_download . '"></a>';
                     } else {
                       $content = ''
                        . '<audio controls>'
                        . '<source src="' . $file . '" type="audio/mpeg">'
                        . 'Aanlyn luister nie beskikbaar in jou browser.'
                        . '</audio>'
                        . '<a class="fa fa-download fa-2x" href="' . $file . '"></a>';
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
