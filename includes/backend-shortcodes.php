<?php

add_action( 'init', 'cgrid_shortcodes_init' );
function cgrid_shortcodes_init() {
	add_shortcode( 'cinzagrid', 'cgrid_shortcode' ); // Main
	add_shortcode( 'cinza_grid', 'cgrid_shortcode' ); // Fallback
}

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// Grid shortcode
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

function cgrid_shortcode( $atts = [], $content = null, $tag = 'cinzagrid' ) {

	// Enqueue scripts and styles
	wp_enqueue_script('isotope');
	wp_enqueue_style('animate');
	wp_enqueue_style('cgrid-frontend');

	// Normalize attribute keys to lowercase
	$atts = array_change_key_case( (array) $atts, CASE_LOWER );

	// Override default attributes with user attributes
	$cgrid_atts = shortcode_atts(
		array(
			'id' => '',
		), $atts, $tag
	);

	$grid_id = intval( $cgrid_atts['id'] );
	$cgrid_options = get_post_meta($grid_id, '_cgrid_options', true);
	$cgrid_skin = get_post_meta($grid_id, '_cgrid_skin', true);

	// Shortcode validation
	if ( empty($grid_id) || !$grid_id || empty($cgrid_options) ) {
		return "<p class='cgrid-error'>ERROR: Please enter a valid Cinza Grid ID.</p>";
	}

	$post_status = get_post_status($grid_id);
	if ($post_status === false || $post_status === 'draft') {
		return "<p class='cgrid-error'>ERROR: This Cinza Grid is not published yet.</p>";
	}

	// Fetch the post object to ensure it exists and is not trashed
	$post = get_post($grid_id);
	if (!$post || $post->post_status === 'trash') {
		return "<p class='cgrid-error'>ERROR: The Cinza Grid does not exist or has been deleted.</p>";
	}

	// Get setting values with validation
	$cgrid_posttype    = isset($cgrid_options['cgrid_posttype']) ? esc_attr($cgrid_options['cgrid_posttype']) : 'post';
	$cgrid_orderby     = isset($cgrid_options['cgrid_orderby']) ? esc_attr($cgrid_options['cgrid_orderby']) : 'date';
	$cgrid_orderby_meta= isset($cgrid_options['cgrid_orderby_meta']) ? esc_attr($cgrid_options['cgrid_orderby_meta']) : '';
	$cgrid_order       = isset($cgrid_options['cgrid_order']) ? esc_attr($cgrid_options['cgrid_order']) : 'ASC';
	$cgrid_num         = isset($cgrid_options['cgrid_num']) ? esc_attr($cgrid_options['cgrid_num']) : '-1';
	$cgrid_tax         = isset($cgrid_options['cgrid_tax']) ? esc_attr($cgrid_options['cgrid_tax']) : '';
	$cgrid_tax_terms   = isset($cgrid_options['cgrid_tax_terms']) ? esc_attr($cgrid_options['cgrid_tax_terms']) : '';
	$cgrid_sorting     = isset($cgrid_options['cgrid_sorting']) ? esc_attr($cgrid_options['cgrid_sorting']) : '';
	$cgrid_filters     = isset($cgrid_options['cgrid_filters']) ? esc_attr($cgrid_options['cgrid_filters']) : '';

	$cgrid_layout      = isset($cgrid_options['cgrid_layout']) ? esc_attr($cgrid_options['cgrid_layout']) : 'fitRows';
	$cgrid_full_width  = isset($cgrid_options['cgrid_full_width']) ? esc_attr($cgrid_options['cgrid_full_width']) : '0';
	$cgrid_query_string= isset($cgrid_options['cgrid_query_string']) ? esc_attr($cgrid_options['cgrid_query_string']) : '0';
	$cgrid_max_filter  = isset($cgrid_options['cgrid_max_filter']) ? esc_attr($cgrid_options['cgrid_max_filter']) : '-1';

	$cgrid_breakpoint_1= 1;
	$cgrid_columns_1   = isset($cgrid_options['cgrid_columns_1']) ? esc_attr($cgrid_options['cgrid_columns_1']) : '1';
	$cgrid_height_1    = isset($cgrid_options['cgrid_height_1']) ? esc_attr($cgrid_options['cgrid_height_1']) : '0';
	$cgrid_spacing_1   = isset($cgrid_options['cgrid_spacing_1']) ? esc_attr($cgrid_options['cgrid_spacing_1']) : '20';

	$cgrid_breakpoint_2= isset($cgrid_options['cgrid_breakpoint_2']) ? esc_attr($cgrid_options['cgrid_breakpoint_2']) : '500';
	$cgrid_columns_2   = isset($cgrid_options['cgrid_columns_2']) ? esc_attr($cgrid_options['cgrid_columns_2']) : '2';
	$cgrid_height_2    = isset($cgrid_options['cgrid_height_2']) ? esc_attr($cgrid_options['cgrid_height_2']) : '0';
	$cgrid_spacing_2   = isset($cgrid_options['cgrid_spacing_2']) ? esc_attr($cgrid_options['cgrid_spacing_2']) : '20';

	$cgrid_breakpoint_3= isset($cgrid_options['cgrid_breakpoint_3']) ? esc_attr($cgrid_options['cgrid_breakpoint_3']) : '700';
	$cgrid_columns_3   = isset($cgrid_options['cgrid_columns_3']) ? esc_attr($cgrid_options['cgrid_columns_3']) : '3';
	$cgrid_height_3    = isset($cgrid_options['cgrid_height_3']) ? esc_attr($cgrid_options['cgrid_height_3']) : '0';
	$cgrid_spacing_3   = isset($cgrid_options['cgrid_spacing_3']) ? esc_attr($cgrid_options['cgrid_spacing_3']) : '20';

	$cgrid_breakpoint_4= isset($cgrid_options['cgrid_breakpoint_4']) ? esc_attr($cgrid_options['cgrid_breakpoint_4']) : '900';
	$cgrid_columns_4   = isset($cgrid_options['cgrid_columns_4']) ? esc_attr($cgrid_options['cgrid_columns_4']) : '4';
	$cgrid_height_4    = isset($cgrid_options['cgrid_height_4']) ? esc_attr($cgrid_options['cgrid_height_4']) : '0';
	$cgrid_spacing_4   = isset($cgrid_options['cgrid_spacing_4']) ? esc_attr($cgrid_options['cgrid_spacing_4']) : '20';

	$cgrid_breakpoint_5= isset($cgrid_options['cgrid_breakpoint_5']) ? esc_attr($cgrid_options['cgrid_breakpoint_5']) : '1200';
	$cgrid_columns_5   = isset($cgrid_options['cgrid_columns_5']) ? esc_attr($cgrid_options['cgrid_columns_5']) : '5';
	$cgrid_height_5    = isset($cgrid_options['cgrid_height_5']) ? esc_attr($cgrid_options['cgrid_height_5']) : '0';
	$cgrid_spacing_5   = isset($cgrid_options['cgrid_spacing_5']) ? esc_attr($cgrid_options['cgrid_spacing_5']) : '20';

	// Retrieves an array of the latest posts, or posts matching the given criteria
	// https://developer.wordpress.org/reference/functions/get_posts/
	$aux_orderby_meta   = $cgrid_orderby === "meta_value";
	$aux_taxonomy       = !empty($cgrid_tax) && !empty($cgrid_tax_terms);
	$aux_taxonomy_terms = explode(",", $cgrid_tax_terms);

	if ($aux_orderby_meta && $aux_taxonomy) {
		$args = array(
			'post_type'   => $cgrid_posttype,
			'post_status' => 'publish',
			'numberposts' => $cgrid_num,
			'meta_key'    => $cgrid_orderby_meta,
			'orderby'     => 'meta_value',
			'order'       => $cgrid_order,
			'tax_query'   => array(
				array(
					'taxonomy' => $cgrid_tax,
					'field'    => 'slug',
					'terms'    => $aux_taxonomy_terms,
				),
			),
		);
	} else if (!$aux_orderby_meta && $aux_taxonomy) {
		$args = array(
			'post_type'   => $cgrid_posttype,
			'post_status' => 'publish',
			'numberposts' => $cgrid_num,
			'orderby'     => $cgrid_orderby,
			'order'       => $cgrid_order,
			'tax_query'   => array(
				array(
					'taxonomy' => $cgrid_tax,
					'field'    => 'slug',
					'terms'    => $aux_taxonomy_terms,
				),
			),
		);
	} else if ($aux_orderby_meta && !$aux_taxonomy) {
		$args = array(
			'post_type'   => $cgrid_posttype,
			'post_status' => 'publish',
			'numberposts' => $cgrid_num,
			'meta_key'    => $cgrid_orderby_meta,
			'orderby'     => 'meta_value',
			'order'       => $cgrid_order,
		);
	} else {
		$args = array(
			'post_type'   => $cgrid_posttype,
			'post_status' => 'publish',
			'numberposts' => $cgrid_num,
			'orderby'     => $cgrid_orderby,
			'order'       => $cgrid_order,
		);
	}
	$posts = get_posts( $args );

	// Sorting
	$sorts = '';
	$sorts_data = '';

	if (!empty($cgrid_sorting)) {
		$sorts .= '<div id="cinza-grid-'.intval($grid_id).'-sorts" class="cinza-grid-button-group">';
			$sort_lines = preg_split("/\r\n|\n|\r/", $cgrid_sorting);
			$sorts_btns_temp = '';
			$original_order_flag = false;

			foreach ($sort_lines as $sort_line) {
				if (!empty($sort_line)) {
					$sort_atts = explode ("/", $sort_line);

					// escape labels and sanitize class key used in data-sort-by and JS map
					$sort_key_raw = isset($sort_atts[0]) ? trim($sort_atts[0]) : '';
					$sort_label_raw = isset($sort_atts[1]) ? trim($sort_atts[1]) : '';

					if ($sort_key_raw === 'default') {
						$sorts_btns_temp .= '<button class="button is-checked" data-sort-by="original-order">'. esc_html($sort_label_raw) .'</button>';
						$sorts_data      .= '';
						$original_order_flag = true;
					} else {
						$sort_key = sanitize_html_class($sort_key_raw);
						$sorts_btns_temp .= '<button class="button" data-sort-by="'. esc_attr($sort_key) .'">'. esc_html($sort_label_raw) .'</button>';
						$sorts_data      .= "'" . $sort_key . "': '.{$sort_key}', ";
					}
				}
			}

			if ($original_order_flag == false) {
				$sorts .= '<button class="button is-checked" data-sort-by="original-order">Default</button>' . $sorts_btns_temp;
			} else {
				$sorts .= $sorts_btns_temp;
			}
		$sorts .= '</div>';
	}

	// Filter
	$filters = '';
	$filters_temp = isset($cgrid_options['cgrid_filters']) ? sanitize_textarea_field($cgrid_options['cgrid_filters']) : '';

	if (!empty($filters_temp)) {
		$filters = '<div id="cinza-grid-'.intval($grid_id).'-filters">';
			$filter_lines = preg_split("/\r\n|\n|\r/", $filters_temp);

			foreach ($filter_lines as $filter_line) {
				if (!empty($filter_line)) {

					$filter_first_split  = explode('/', $filter_line, 2);
					$filter_second_split = explode('/', isset($filter_first_split[1]) ? $filter_first_split[1] : '', 2);
					$filter_substring1   = isset($filter_first_split[0]) ? $filter_first_split[0] : '';
					$filter_substring2   = isset($filter_second_split[0]) ? $filter_second_split[0] : '';
					$filter_substring3   = isset($filter_second_split[1]) ? $filter_second_split[1] : '';

					$group_slug = sanitize_title( trim(strtolower($filter_substring2)) );

					$filters .= '<div class="cinza-grid-button-group" data-filter-group="'. esc_attr($group_slug) .'">';

						// First button
						$filters .= '<button class="button is-checked" data-filter="*">'. esc_html( trim($filter_substring2) ) .'</button>';

						// All other buttons
						$filter_buttons = explode(",", $filter_substring3);
						foreach ($filter_buttons as $filter_button) {
							$btn_label = trim($filter_button);
							$btn_class = sanitize_html_class( strtolower( preg_replace('/\s+/', '-', $btn_label) ) );
							$filters .= '<button class="button" id="'. esc_attr($btn_class) .'" data-filter=".'. esc_attr($btn_class) .'">'. esc_html($btn_label) .'</button>';
						}
					$filters .= '</div>';
				}
			}
		$filters .= '</div>';
	}

	if ($cgrid_layout == "fitrows") $cgrid_layout = "fitRows";

	$script = "<script>

	jQuery(window).load(function() {
		var grid = jQuery('#cinza-grid-".intval($grid_id)."').isotope();
	});

	jQuery(document).ready(function($) {

			var grid = $('#cinza-grid-".intval($grid_id)."').isotope
			({
					itemSelector: '.cinza-grid-item',
					layoutMode: '".esc_js($cgrid_layout)."',
					transitionDuration: '0.4s',
					getSortData: {".$sorts_data."}
			});

			if( '".($sorts ? '1' : '')."' != '' )
			{
				// bind sort button click
				$('#cinza-grid-".intval($grid_id)."-sorts').on( 'click', 'button', function()
				{
						var sortByValue = $(this).attr('data-sort-by');
						grid.isotope({ sortBy: sortByValue });
				});

				// change is-checked class on buttons
				$('#cinza-grid-".intval($grid_id)."-sorts').each( function( i, buttonGroup )
				{
						var buttonGroup = $( buttonGroup );
						buttonGroup.on( 'click', 'button', function() {
						buttonGroup.find('.is-checked').removeClass('is-checked');
						$( this ).addClass('is-checked');
						});
				});
		}

		if( '".($filters ? '1' : '')."' != '' )
		{
			// store filter for each group
			var filters = {};
			var filterButtonGroup = $('#cinza-grid-".intval($grid_id)."-filters');

			filterButtonGroup.on( 'click', '.button', function( event )
			{
				var button = $( event.currentTarget );

				// get group key
				var buttonGroup = button.parents('.cinza-grid-button-group');
				var filterGroup = buttonGroup.attr('data-filter-group');

				// set filter for group
				filters[ filterGroup ] = button.attr('data-filter');

				// combine filters
				var filterValue = concatValues( filters );

				// limit number if items filtered
				if(".intval($cgrid_max_filter)." > 0) {
						// Set max number of items visible when filter is active
						var filterSelector = filterValue.replace(/\\*/g, '');
						var items = $('#cinza-grid-".intval($grid_id)." .cinza-grid-item').removeClass('filter-limit');

						if(filterSelector) {
								var filteredItems = items.filter(filterSelector);
								filteredItems.slice(".intval($cgrid_max_filter).").addClass('filter-limit');
						}
				}

				// set filter for Isotope, which triggers the script for layout
				grid.isotope({ filter: filterValue });

				// change query string in real time
				if( ".intval($cgrid_query_string)." == 1 ) {
					location.hash = 'filter=' + encodeURIComponent( filterValue );
				}
			});

			// change is-checked class on buttons
			$('.cinza-grid-button-group').each( function( i, buttonGroup )
			{
				var buttonGroup = $( buttonGroup );
				buttonGroup.on( 'click', 'button', function( event )
				{
					buttonGroup.find('.is-checked').removeClass('is-checked');
					$( event.currentTarget ).addClass('is-checked');
				});
			});
		}

		// flatten object by concatenating values
		function concatValues( obj )
		{
			var value = '';
			for ( var prop in obj )
			{
				value += obj[ prop ];
			}
			return value;
		}

		// URL query string
		// Example: https://vinicius.razorfrog.dev/grid-shortcode-test/#filter=.blue
		// Example: https://vinicius.razorfrog.dev/grid-shortcode-test/#filter=.blue.small.scrollto-rowID

		function getHashFilter()
		{
			var matches = location.hash.match( /filter=([^&]+)/i );
			var hashFilter = matches && matches[1];
			hashFilter = hashFilter && decodeURIComponent( hashFilter );

			// Only accept class filters like .blue.small or *, anything else could run code through jQuery
			if ( hashFilter && !/^[A-Za-z0-9_.*, -]+$/.test( hashFilter ) ) {
				return null;
			}
			return hashFilter;
		}

		var isIsotopeInit = false;
		function onHashchange()
		{
			var hashFilter = getHashFilter();

			if ( !hashFilter && isIsotopeInit ) {
				return;
			}

			isIsotopeInit = true;

			grid.isotope
			({
				itemSelector: '.cinza-grid-item',
				filter: hashFilter
			});

			if ( hashFilter )
			{
				// remove first dot so we don't have hashSplit[0] empty
				var hashSplit = hashFilter.substring(1, hashFilter.length).split('.');

				// checks if scrollto is in the query string
				var scrollCheck = hashFilter.indexOf('scrollto');

				// if scrollto is in found in the array
				if ( scrollCheck > -1 )
				{
					// get scrollto ID, which should always be the last item in the array
					var scrollID = '#' + hashSplit[hashSplit.length - 1].replace('scrollto-','');

					// remove scrollto item from the array
					hashSplit.splice(hashSplit.length - 1, 1);

					// scroll to ID
					document.querySelector(String(scrollID)).scrollIntoView({ behavior:  'smooth' });
				}

				hashSplit.forEach(element =>
				{
					filterButtonGroup.find('[data-filter=\".' + element + '\"]').click();
				});
			}
		}
		$(window).on( 'hashchange', onHashchange );
		onHashchange();

	});
	</script>";

	// Grid items
	$grid = '<div id="cinza-grid-'.intval($grid_id).'" class="cinza-grid">';
	if ( !empty( $posts ) ){
		$debug = "";

		foreach ( $posts as $post ) {
			$grid_item = isset($cgrid_skin['cgrid_skin_content']) ? $cgrid_skin['cgrid_skin_content'] : '';
			$filter_classes = "";

			// Replace %date('l F j, Y')%
			$pattern_date = "/%date\('([^']+)'\)%/";
			$grid_item = preg_replace_callback($pattern_date, function($matches) use ($post) {
				$date_format = $matches[1];
				return esc_html( get_the_date($date_format, $post->ID) );
			}, $grid_item);

			// Replace %meta('field_name')%
			// Also supports encoded quotes caused by wp_kses inside HTML attributes,
			// like %meta(&apos;field_name&apos;)% and %meta(&#039;field_name&#039;)%.
			$pattern_meta = "/%meta\((?:'|&apos;|&#039;|&#x27;)([^'&\)]+)(?:'|&apos;|&#039;|&#x27;)\)%/";

			$grid_item = preg_replace_callback($pattern_meta, function($matches) use ($post) {
				$meta_field = $matches[1];

				return esc_html( get_post_meta($post->ID, $meta_field, true) );
			}, $grid_item);

			if (!empty($filters_temp)) {
				foreach ($filter_lines as $filter_line) {
					$filter_classes .= cgrid_filter_meta_replace($post, $filter_line);
				}
			}

			// Replace %tax('taxonomy_name')%
			$pattern_tax = "/%tax\('([^']+)'\)%/";
			$grid_item = preg_replace_callback($pattern_tax, function($matches) use ($post) {
				$taxonomy_name = $matches[1];
				$term_list = get_the_terms($post->ID, $taxonomy_name);
				if ($term_list && !is_wp_error($term_list)) {
					$terms_array = array_map(function($term) {
						return esc_html($term->name);
					}, $term_list);
					return join(' ', $terms_array);
				} else {
					return "Invalid taxonomy.";
				}
			}, $grid_item);

			if (!empty($filters_temp)) {
				foreach ($filter_lines as $filter_line) {
					$filter_classes .= cgrid_filter_tax_replace($post, $filter_line);
				}
			}

			// Replace %taxsep('taxonomy_name')%
			$pattern_taxsep = "/%taxsep\('([^']+)'\)%/";
			$grid_item = preg_replace_callback($pattern_taxsep, function($matches) use ($post) {
				$taxonomy_name = $matches[1];
				$term_list = get_the_terms($post->ID, $taxonomy_name);
				if ($term_list && !is_wp_error($term_list)) {
					$terms_array = array_map(function($term) {
						return esc_html($term->name);
					}, $term_list);
					return join(', ', $terms_array);
				} else {
					return "Invalid taxonomy.";
				}
			}, $grid_item);

			// Replace %taxurl('taxonomy_name')%
			$pattern_taxurl = "/%taxurl\('([^']+)'\)%/";
			$grid_item = preg_replace_callback($pattern_taxurl, function($matches) use ($post) {
				$taxonomy_name = $matches[1];
				$term_list = get_the_terms($post->ID, $taxonomy_name);
				if ($term_list && !is_wp_error($term_list)) {
					$terms_array = array_map(function($term) use ($taxonomy_name) {
						return '<a href="' . esc_url(get_term_link($term->slug, $taxonomy_name)) . '">' . esc_html($term->name) . '</a>';
					}, $term_list);
					return join(', ', $terms_array);
				} else {
					return "Invalid taxonomy.";
				}
			}, $grid_item);

			// Replace %img('img_size')%
			$pattern_imgsize = "/%img\('([^']+)'\)%/";
			$grid_item = preg_replace_callback($pattern_imgsize, function($matches) use ($post) {
				$size = $matches[1];
				return get_the_post_thumbnail($post->ID, $size);
			}, $grid_item);

			// Replace %imgurl('img_size')%
			$pattern_imgurlsize = "/%imgurl\('([^']+)'\)%/";
			$grid_item = preg_replace_callback($pattern_imgurlsize, function($matches) use ($post) {
				$size = $matches[1];
				return esc_url( get_the_post_thumbnail_url($post->ID, $size) );
			}, $grid_item);

			// Replace PHP shortcode
			preg_match_all('/%\[([^\s\]]+)([^\]]*)\]%/', $grid_item, $shortcode_matches);
			foreach ($shortcode_matches[0] as $index => $shortcode_tag) {
				$shortcode_name   = $shortcode_matches[1][$index];
				$shortcode_params = $shortcode_matches[2][$index];
				$shortcode_output = do_shortcode("[$shortcode_name$shortcode_params id='{$post->ID}']"); // Add the post ID to the shortcode parameters
				$grid_item = str_replace($shortcode_tag, $shortcode_output, $grid_item);
			}

			// Replace easy tags
			$code1 = array(
				'%title%',
				'%url%',
				'%slug%',
				'%date%',
				'%img%',
				'%imgurl%',
				'%content%',
				'%excerpt%',
			);

			$code2 = array(
				esc_html( get_the_title($post->ID) ),
				esc_url( get_permalink($post->ID) ),
				sanitize_title( $post->post_name ),
				esc_html( get_the_date('F j, Y', $post->ID) ),
				get_the_post_thumbnail($post->ID,'full'),
				esc_url( get_the_post_thumbnail_url($post->ID,'full') ),
				wp_kses_post( wpautop( get_post_field('post_content', $post->ID) ) ),
				esc_html( get_the_excerpt($post->ID) ),
			);

			$characters = array("&amp;", "---", "/");
			$encoded_classes = str_replace($characters, '-', strtolower($filter_classes));
			$grid .= '<div class="cinza-grid-item cinza-grid-'. intval($post->ID) . esc_attr($encoded_classes) .'">'. str_replace($code1, $code2, $grid_item) .'</div>';
		}
	}
	$grid .= '</div>';

	// Style
	$style = "<style>";
	$style .= cgrid_css_breakpoint($grid_id, $cgrid_breakpoint_1, $cgrid_columns_1, $cgrid_full_width, $cgrid_height_1, $cgrid_spacing_1);
	$style .= cgrid_css_breakpoint($grid_id, $cgrid_breakpoint_2, $cgrid_columns_2, $cgrid_full_width, $cgrid_height_2, $cgrid_spacing_2);
	$style .= cgrid_css_breakpoint($grid_id, $cgrid_breakpoint_3, $cgrid_columns_3, $cgrid_full_width, $cgrid_height_3, $cgrid_spacing_3);
	$style .= cgrid_css_breakpoint($grid_id, $cgrid_breakpoint_4, $cgrid_columns_4, $cgrid_full_width, $cgrid_height_4, $cgrid_spacing_4);
	$style .= cgrid_css_breakpoint($grid_id, $cgrid_breakpoint_5, $cgrid_columns_5, $cgrid_full_width, $cgrid_height_5, $cgrid_spacing_5);
	$style .= "</style>";

	//return $debug . $sorts . $filters . $grid . $style . $script;
	return $sorts . $filters . $grid . $style . $script;
}

function cgrid_filter_meta_replace($post, $filters_temp) {
	if (strpos($filters_temp, '%meta(') !== false) {
		$meta_start_position   = strpos($filters_temp, "%meta(");
		$meta_open_paranthesis = $meta_start_position + 5;
		$meta_close_paranthesis= $meta_start_position + strpos(substr($filters_temp, $meta_start_position, $meta_start_position+50), ")");
		$meta_code             = substr($filters_temp, $meta_start_position+1, $meta_close_paranthesis-$meta_start_position);
		$meta_code_args        = substr($filters_temp, $meta_open_paranthesis+2, $meta_close_paranthesis-$meta_open_paranthesis-3);
		$meta_formatted        = get_post_meta( $post->ID, $meta_code_args, true );
		// ensure valid CSS class fragment
		$meta_class            = sanitize_html_class( strtolower( preg_replace('/\s+/', '-', $meta_formatted) ) );
		return " " . $meta_class;
	}
}

function cgrid_filter_tax_replace($post, $filters_temp) {
	if (strpos($filters_temp, '%tax(') !== false) {
		$tax_start_position   = strpos($filters_temp, "%tax(");
		$tax_open_paranthesis = $tax_start_position + 5;
		$tax_close_paranthesis= $tax_start_position + strpos(substr($filters_temp, $tax_start_position, $tax_start_position+50), ")");
		$tax_code             = substr($filters_temp, $tax_start_position+1, $tax_close_paranthesis-$tax_start_position);
		$tax_code_args        = substr($filters_temp, $tax_open_paranthesis+1, $tax_close_paranthesis-$tax_open_paranthesis-2);
		$term_list            = get_the_terms( $post->ID, $tax_code_args );
		if ( $term_list && ! is_wp_error( $term_list ) ) {
			$terms_array = array();
			foreach ( $term_list as $term ) {
				// sanitize per-class
				$terms_array[] = sanitize_html_class( strtolower( preg_replace('/\s+/', '-', $term->name) ) );
			}
			$tax_formatted = join( ' ', $terms_array );
			return " " . $tax_formatted;
		}
	}
}

function cgrid_css_breakpoint($grid_id, $breakpoint, $col, $full_width, $height, $space) {
	$style = "
	@media only screen and (min-width: ". intval($breakpoint) ."px) {";

		if (boolval($full_width)) {
			if ($col == 1) {
				$style .= "
				#cinza-grid-".intval($grid_id)." {
					width: calc(100vw - 2 * ". intval($space) ."px);
					margin-left: calc(-50vw + ". intval($space) ."px);
					position: relative;
					left: 50%;
					right: 50%;
				}
				#cinza-grid-".intval($grid_id)." .cinza-grid-item {
					width: 100%;
					min-height: ". intval($height) ."px;
					margin: 0px 0px ". intval($space) ."px 0px;
				}";
			} else {
				$style .=  "
				#cinza-grid-".intval($grid_id)." {
					width: calc(100vw - ". intval($space) ."px);
					margin-left: calc(-50vw + ". intval($space) ."px / 2);
					position: relative;
					left: 50%;
					right: 50%;
				}
				#cinza-grid-".intval($grid_id)." .cinza-grid-item {
					width: calc(100% / ". intval($col) ." - ". intval($space) ."px - 0.5px); /* -0.5px to be safe */
					min-height: ". intval($height) ."px;
					margin: calc(". intval($space) ."px / 2);
				}";
			}
		} else {
			if ($col == 1) {
				$style .= "
				#cinza-grid-".intval($grid_id)." {
					width: 100%;
					margin: 0px;
				}
				#cinza-grid-".intval($grid_id)." .cinza-grid-item {
					width: 100%;
					min-height: ". intval($height) ."px;
					margin: 0px 0px ". intval($space) ."px 0px;
				}";
			} else {
				$style .=  "
				#cinza-grid-".intval($grid_id)." {
					width: calc(100% + ". intval($space) ."px);
					margin: 0 calc(-". intval($space) ."px / 2);
				}
				#cinza-grid-".intval($grid_id)." .cinza-grid-item {
					width: calc(100% / ". intval($col) ." - ". intval($space) ."px - 0.5px); /* -0.5px to be safe */
					min-height: ". intval($height) ."px;
					margin: calc(". intval($space) ."px / 2);
				}";
			}
		}
	$style .= "}";

	return $style;
}
