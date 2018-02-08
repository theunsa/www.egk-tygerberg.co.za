<?php

add_theme_support( 'post-thumbnails' );

add_action( 'wp_enqueue_scripts', 'enqueue_child_theme_styles', PHP_INT_MAX);
function enqueue_child_theme_styles() {
    wp_enqueue_style( 'parent-style', get_template_directory_uri() . '/style.css' );
    wp_enqueue_style( 'child-style', get_stylesheet_uri(), array('parent-style') );
}

// As per https://codex.wordpress.org/Function_Reference/register_taxonomy
// Hook into the init action and call the create_X_taxonomy functions when it fires
add_action( 'init', 'create_prediker_taxonomy', 0 );
add_action( 'init', 'create_reeks_taxonomy', 0 );

// Prediker Taxonomy
function create_prediker_taxonomy() {
	$labels = array(
		'name'                       => _x( 'Predikers', 'taxonomy general name' ),
		'singular_name'              => _x( 'Prediker', 'taxonomy singular name' ),
		'search_items'               => __( 'Soek Predikers' ),
		'popular_items'              => __( 'Populêre Predikers' ),
		'all_items'                  => __( 'Alle Predikers' ),
		'parent_item'                => null,
		'parent_item_colon'          => null,
		'edit_item'                  => __( 'Edit Prediker' ),
		'update_item'                => __( 'Opdateer Prediker' ),
		'add_new_item'               => __( 'Voeg Nuwe Prediker by' ),
		'new_item_name'              => __( 'Nuwe Prediker Naam' ),
		'separate_items_with_commas' => __( 'Skei Predikers met kommas' ),
		'add_or_remove_items'        => __( 'Voeg by of verwyder Predikers' ),
		'choose_from_most_used'      => __( 'Predikers wat meeste preek' ),
		'not_found'                  => __( 'Geen Predikers gevind.' ),
		'menu_name'                  => __( 'Predikers' ),
	);

	$args = array(
		'hierarchical'          => true,
		'labels'                => $labels,
		'show_ui'               => true,
		'show_admin_column'     => true,
		'update_count_callback' => '_update_post_term_count',
		'query_var'             => true,
		'rewrite'               => array( 'slug' => 'Prediker' ),
	);
	register_taxonomy( 'preek_prediker', 'preek', $args );
}

// Reeks Taxonomy
function create_reeks_taxonomy() {
	$labels = array(
		'name'                       => _x( 'Reekse', 'taxonomy general name' ),
		'singular_name'              => _x( 'Reeks', 'taxonomy singular name' ),
		'search_items'               => __( 'Soek Reekse' ),
		'popular_items'              => __( 'Populêre Reekse' ),
		'all_items'                  => __( 'Alle Reekse' ),
		'parent_item'                => null,
		'parent_item_colon'          => null,
		'edit_item'                  => __( 'Edit Reeks' ),
		'update_item'                => __( 'Opdateer Reeks' ),
		'add_new_item'               => __( 'Voeg Nuwe Reeks by' ),
		'new_item_name'              => __( 'Nuwe Reeks Naam' ),
		'separate_items_with_commas' => __( 'Skei Reekse met kommas' ),
		'add_or_remove_items'        => __( 'Voeg by of verwyder Reekse' ),
		'choose_from_most_used'      => __( 'Reekse wat meeste preek' ),
		'not_found'                  => __( 'Geen Reekse gevind.' ),
		'menu_name'                  => __( 'Reekse' ),
	);

	$args = array(
		'hierarchical'          => true,
		'labels'                => $labels,
		'show_ui'               => true,
		'show_admin_column'     => true,
		'update_count_callback' => '_update_post_term_count',
		'query_var'             => true,
		'rewrite'               => array( 'slug' => 'Reeks' ),
	);
	register_taxonomy( 'preek_reeks', 'preek', $args );
}

function my_custom_post_preek() {
  $labels = array(
    'name'               => _x( 'Preke', 'post type general name' ),
    'singular_name'      => _x( 'Preek', 'post type singular name' ),
    'add_new'            => _x( 'Laai Nuwe Preek', 'preek' ),
    'add_new_item'       => __( 'Laai Nuwe Preek' ),
    'edit_item'          => __( 'Edit Preek' ),
    'new_item'           => __( 'Nuwe Preek' ),
    'all_items'          => __( 'Alle Preke' ),
    'view_item'          => __( 'View Preek' ),
    'search_items'       => __( 'Soek Preke' ),
    'not_found'          => __( 'Geen preke gevind nie' ),
    'not_found_in_trash' => __( 'Geen preke gevind in die Trash' ),
    'parent_item_colon'  => '',
    'menu_name'          => 'Preke'
  );
  $args = array(
    'labels'        => $labels,
    'description'   => 'Stoor preke en spesifieke inligting van \'n preek',
    'public'        => true,
    'menu_position' => 5,
    'supports'      => array( 'title' ),
    'has_archive'   => true,
    'taxonomies'    => array('preek_prediker'),
  );
  register_post_type( 'preek', $args );
}
add_action( 'init', 'my_custom_post_preek' );

function preek_change_default_title( $title ){

    $screen = get_current_screen();

    if ( 'preek' == $screen->post_type ){
        $title = 'Tema van die preek';
    }

    return $title;
}
add_filter( 'enter_title_here', 'preek_change_default_title' );

function my_updated_messages( $messages ) {
  global $post, $post_ID;
  $messages['preek'] = array(
    0 => '',
    1 => sprintf( __('Product updated. <a href="%s">View product</a>'), esc_url( get_permalink($post_ID) ) ),
    2 => __('Custom field updated.'),
    3 => __('Custom field deleted.'),
    4 => __('Product updated.'),
    5 => isset($_GET['revision']) ? sprintf( __('Product restored to revision from %s'), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
    6 => sprintf( __('Product published. <a href="%s">View product</a>'), esc_url( get_permalink($post_ID) ) ),
    7 => __('Product saved.'),
    8 => sprintf( __('Product submitted. <a target="_blank" href="%s">Preview product</a>'), esc_url( add_query_arg( 'preview', 'true', get_permalink($post_ID) ) ) ),
    9 => sprintf( __('Product scheduled for: <strong>%1$s</strong>. <a target="_blank" href="%2$s">Preview product</a>'), date_i18n( __( 'M j, Y @ G:i' ), strtotime( $post->post_date ) ), esc_url( get_permalink($post_ID) ) ),
    10 => sprintf( __('Product draft updated. <a target="_blank" href="%s">Preview product</a>'), esc_url( add_query_arg( 'preview', 'true', get_permalink($post_ID) ) ) ),
  );
  return $messages;
}
add_filter( 'post_updated_messages', 'my_updated_messages' );

// As per https://metabox.io/
function preek_register_meta_boxes( $meta_boxes )
{
    // Better has an underscore as last sign
    $prefix = 'preek_';

    $meta_boxes[] = array(
        // Meta box id, UNIQUE per meta box. Optional since 4.1.5
        'id'         => 'preek_vorm',

        // Meta box title - Will appear at the drag and drop handle bar. Required.
        'title'      => __( 'Preek Velde', 'preek_vorm_titel' ),

        // Post types, accept custom post types as well - DEFAULT is 'post'. Can be array (multiple post types) or string (1 post type). Optional.
        'post_types' => array( 'preek' ),

        // Where the meta box appear: normal (default), advanced, side. Optional.
        'context'    => 'normal',

        // Order of meta box: high (default), low. Optional.
        'priority'   => 'high',

        // Auto save: true, false (default). Optional.
        'autosave'   => true,

        // List of meta fields
        'fields'     => array(
            // TEXT
            array(
                // Field name - Will be used as label
                'name'  => __( 'Skriflesing', 'skriflesing' ),
                // Field ID, i.e. the meta key
                'id'    => "{$prefix}skriflesing",
                'type'  => 'text',
                'placeholder'  => 'Skriflesing',
            ),
            // TAXONOMIES (Prediker)
            array(
                'name'  => __( 'Prediker', 'prediker' ),
                'id'    => "{$prefix}prediker",
                'type'  => 'taxonomy',
                'taxonomy' => 'preek_prediker',
                'field_type'  => 'select_advanced',
                'placeholder'  => 'Prediker',
            ),
            // TAXONOMIES (Reekse)
            array(
                'name'  => __( 'Reeks', 'reeks' ),
                'id'    => "{$prefix}reeks",
                'type'  => 'taxonomy',
                'taxonomy' => 'preek_reeks',
                'field_type'  => 'select_advanced',
                'placeholder'  => 'Reeks',
            ),
            // TEXTAREA
            array(
                'name' => __( 'Beskrywing', 'beskrywing' ),
                'id'   => "{$prefix}beskrywing",
                'type' => 'textarea',
                'cols' => 20,
                'rows' => 3,
                'placeholder' => 'Beskrywing van die preek',
            ),
            // DATE
            array(
                'name'       => __( 'Datum', 'datum' ),
                'id'         => "{$prefix}datum",
                'type'       => 'date',
                'desc'		   =>
                	'<b>Maak asb die \'Publish\' datum (in die blok aan die regterkant) dieselfde as hierdie datum</b>',

                // jQuery date picker options. See here http://api.jqueryui.com/datepicker
                'js_options' => array(
                    'appendText'      => __( '(jjjj-mm-dd)', 'datum_append_text' ),
                    'dateFormat'      => __( 'yy-mm-dd', 'datum_date_format' ),
                    'changeMonth'     => true,
                    'changeYear'      => true,
                    'showButtonPanel' => false,
                ),
            ),
            // SELECT BOX
            array(
                'name'        => __( 'OggAand', 'ogg_aand' ),
                'id'          => "{$prefix}ogg_aand",
                'type'        => 'select',
                // Array of 'value' => 'Label' pairs for select box
                'options'     => array(
                    'Ogg' => __( 'Oggend', 'oggend' ),
                    'Mid' => __( 'Middag', 'middag' ),
                    'Aand' => __( 'Aand', 'aand' ),
                ),
                // Select multiple values, optional. Default is false.
                'multiple'    => false,
                'placeholder' => __( 'Kies ogggend, middag of aand...', 'ogg_aand_placeholder' ),
            ),
            // URL 
            array(
                'name' => __( 'Preek URL', 'url' ),
                'id'               => "{$prefix}url",
                'type'  => 'url',
                'placeholder'  => 'Paste Preek URL hier',
                'desc'		   =>
                    '<b>Stappe om preek op te laai:</b>' .
                '<ol>' .
                '<li>Laai die preek file op <a href="https://drive.google.com/open?id=0B3rqdpUizru9SUp5V0xEal81dEk">Google Drive</a> (jy het regte nodig)' .
                    '<ol style="list-style-type: lower-roman">' .
                    '<li>Laai die preek in die regte jaar directory bv <i>Preke/2017/</i></li>' .
                    '</ol>' .
                '</li>' .
                '<li>Regs-kliek op die preek file wat opgelaai is en kies "Kry deelbare skakel" ("Get shareable link")' .
                '<li>Paste die link in die "Preek URL" veld.</li>' .
                '</ol>',
            ),
           // FILE ADVANCED (WP 3.5+)
            // array(
            //     'name' => __( 'Laai preek file', 'file' ),
            //     'id'               => "{$prefix}file",
            //     'type'             => 'file_advanced',
            //     'max_file_uploads' => 1,
            //     //'mime_type'        => 'application,audio,video', // Leave blank for all file types
            //     'desc'		   =>
            //     	'<b>Stappe om preek op te laai:</b>' .
            //     	'<ol>' .
            //     	'<li>Laai die preek file op na die webserver dmv FTP' .
            //     		'<ol style="list-style-type: lower-roman">' .
            //     		'<li>Connect na FTP server \'www.egk-tygerberg.co.za\' of IP \'154.0.165.119\'</li>' .
            //     		'<li>Gebruik FTP program soos <a href="https://filezilla-project.org/">Filezilla</a></li>' .
            //     		'<li>Login user: preke@egk-tygerberg.co.za</li>' .
            //     		'<li>Password: 6GV2@b9Shy</li>' .
            //     		'<li>Sodra ingelog, copy die preek file oor</li>' .
            //     		'</ol>' .
            //     	'</li>' .
            //     	'<li>Voeg die preek file by die webwerf se Media Libray' .
            //     		'<ol style="list-style-type: lower-roman">' .
            //     		'<li>In Wordpress, click Media->Add from Server</li>' .
            //     		'<li>Kies die preek file wat met FTP gecopy is in die vorige stap</li>' .
            //     		'</ol>' .
            //     	'</li>' .
            //     	'<li>Kliek die upload button hierbo en kies die preek vanuit die Media Library</li>' .
            //     	'<li>Die preek file wat met FTP gecopy is kan maar uitgevee word</li>' .
            //     	'</ol>',
            // ),
        ),
        'validation' => array(
            'rules'    => array(
                "{$prefix}skriflesing" => array(
                    'required'  => true,
                ),
                "{$prefix}prediker" => array(
                    'required'  => true,
                ),
                "{$prefix}datum" => array(
                    'required'  => true,
                ),
                "{$prefix}ogg_aand" => array(
                    'required'  => true,
                ),
            ),
            // optional override of default jquery.validate messages
            'messages' => array(
                "{$prefix}skriflesing" => array(
                    'required'  => __( 'Skriflesing veld kan nie leeg wees nie', 'skriflesing_check' ),
                ),
                "{$prefix}prediker" => array(
                    'required'  => __( 'Prediker veld kan nie leeg wees nie', 'prediker_check' ),
                ),
                "{$prefix}datum" => array(
                    'required'  => __( 'Datum veld kan nie leeg wees nie', 'datum_check' ),
                ),
                "{$prefix}ogg_aand" => array(
                    'required'  => __( 'Kies oggend, middag of aand diens', 'ogg_aand_check' ),
                ),
            )
        )
    );

    return $meta_boxes;
}
add_filter( 'rwmb_meta_boxes', 'preek_register_meta_boxes' );

// MailChimp Top Bar extra fields
add_action( 'mctb_before_email_field', function() {
    echo '<input type="text" name="FNAME" placeholder="Jou naam ..." />';
});
add_filter( 'mctb_data', function( $vars ) {
    $vars['FNAME'] = ( isset( $_POST['FNAME'] ) ) ? sanitize_text_field( $_POST['FNAME'] ) : '';
    return $vars;
});

add_action( 'mctb_before_email_field', function() {
    echo '<input type="text" name="LNAME" placeholder="Jou van ..." />';
});
add_filter( 'mctb_data', function( $vars ) {
    $vars['LNAME'] = ( isset( $_POST['LNAME'] ) ) ? sanitize_text_field( $_POST['LNAME'] ) : '';
    return $vars;
});


/***** Preke functions *****/
function get_month_short_string($month) {
    switch ($month) {
        case 1:
            $str = 'Jan';
            break;
        case 2:
            $str = 'Feb';
            break;
        case 3:
            $str = 'Mrt';
            break;
        case 4:
            $str = 'Apr';
            break;
        case 5:
            $str = 'Mei';
            break;
        case 6:
            $str = 'Jun';
            break;
        case 7:
            $str = 'Jul';
            break;
        case 8:
            $str = 'Aug';
            break;
        case 9:
            $str = 'Sep';
            break;
        case 10:
            $str = 'Okt';
            break;
        case 11:
            $str = 'Nov';
            break;
        case 12:
            $str = 'Des';
            break;
        default:
            $str = 'Error';
    }
    return $str;
}

function get_date_parts($date) {
    $date_array = explode('-', $date);
    $date_array[1] = get_month_short_string($date_array[1]);
    return $date_array;
}


?>
