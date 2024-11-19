<?php
/**
 * Customize the output of menus for Foundation top bar
 * classes to active searches: col-small-small-margin-top col-small-12 col-medium-6 col-divider livesearch player-profiles
**/
if ( ! class_exists( 'g365_Top_Bar_Walker' ) ) :
class g365_Top_Bar_Walker extends Walker_Nav_Menu {
	private $reg_output;
	function start_lvl( &$output, $depth = 0, $args = array() ) {
		$indent = str_repeat("\t", $depth);
    $is_columned = ( $depth > 0 ) ? '' : ' grid-x flex-align-content-start';
    $output .= "\n$indent<ul class=\"menu vertical$is_columned\">\n";
	}
	function end_lvl( &$output, $depth = 0, $args = array() ) {
		$indent = str_repeat("\t", $depth);
    $output .= "\n$indent</ul>\n";
	}
	function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {
		//this annonyamous function builds the html class string
		$g365_make_class = function( $classes_array, $item, $args ) {
			if( empty($classes_array) ) return;
			return ( ' class="' . esc_attr( join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes_array ), $item, $args ) ) ) . '"' );
		};
		//this annonyamous function builds the actual menu links
		$g365_make_link = function( $atts, $args, $itemtitle, $itemID, $extra_data, $before_link = '' ) {
//         print_r($atts);
			$attributes = '';
			//make all the attirbutes for a link element
			foreach ( $atts as $attr => $value ) {
				if ( ! empty( $value ) ) {
					//if we are processing the url use a different sanitization function
					$value = ( 'href' === $attr ) ? esc_url( $value ) : esc_attr( $value );
					$attributes .= ' ' . $attr . '="' . trim($value) . '"';
				}
			}
			//add any before info from the wp_nav function
			$item_output = $args->before;
			//build opening anchor tag
			$item_output .= '<a'. $attributes .'>';
			// This filter is documented in wp-includes/post-template.php
			$item_output .= $args->link_before . $before_link . apply_filters( 'the_title', $itemtitle, $itemID ) . $args->link_after;
      
// 			if( $item->description != '' ) {
// 				$item_output .= ' - ' . $item->description;
// 			}
      if( $extra_data !== null ) $item_output .= '<small class="block subheader">' . $extra_data . '</small>';
			//add closing anchor tag
			$item_output .= '</a>';
			//add any after info from the wp_nav function
			$item_output .= $args->after;
			return $item_output;
		};
		$g365_sort_classes = function( $all_classes ) {
			$classes = (object) array(
				'col' => array(),
				'li' => array(),
				'anc' => array(),
				'divider' => false
			);
			foreach( $all_classes as $dex => $class ) {
				if( strpos($class, 'col-') !== false ) {
					if( strpos($class, 'divider') !== false ) {
						$classes->divider = true;
						continue;
					}
					$classes->col[] = str_replace( 'col-', '', $class );
				} elseif( strpos($class, 'anc-') !== false ) {
					$classes->anc[] = str_replace( 'anc-', '', $class );
				} else {
          $classes->li[] = $class;
				}
			}
			$classes->col[] = 'cell';
			return $classes;
		};
		$indent = ( $depth ) ? str_repeat( "\t", $depth ) : '';
		//collect and format all the attibutes for each link element
		$id = apply_filters( 'nav_menu_item_id', 'menu-item-'. $item->ID, $item, $args );
		$id = $id ? ' id="' . esc_attr( $id ) . '"' : '';
		//collect this item's attrubute data
		$atts = array(
			'title'  => !empty( $item->attr_title ) ? $item->attr_title : '',
			'target' => !empty( $item->target )     ? $item->target     : '',
			'rel'    => !empty( $item->xfn )        ? $item->xfn        : '',
			'href'   => !empty( $item->url )        ? $item->url        : ''
		);
		$atts = apply_filters( 'nav_menu_link_attributes', $atts, $item, $args, $depth );
		//figure out what content we need to add for each element

    $all_classes = $g365_sort_classes( $item->classes );
    if( !empty($all_classes->anc) ){
      if( empty($atts['class']) ) $atts["class"] = array();
      $atts['class'] = implode(' ', array_merge($atts['class'], $all_classes->anc));
    }
    //start with a li tag
    $data_tag_info = '';
    if( in_array('event-menu-button', $all_classes->li) ) {
      $ev_target = array_filter($all_classes->li, function($value) { return strpos($value, 'ev-target-') !== false; });
      if( count($ev_target) === 1 ) $data_tag_info = ' data-ev-target="' . substr(array_values($ev_target)[0], 10) . '"';
    } 
    $output .= $indent . '<li' . $id . $g365_make_class( $all_classes->li, $item, $args ) . $data_tag_info . ">\n";
    if( in_array('event-menu-season', $all_classes->li) ) {
      $menu_locations = get_nav_menu_locations();
      $event_season_menuID = $menu_locations['event_menu_season'];
      $event_menu = get_transient( 'menu-cache-menuid-' . $event_season_menuID . '-event-menu-season' );
      if ( false === $event_menu ) {
        $event_menu = g365_event_menu_season_nav();
        set_transient( 'menu-cache-menuid-' . $event_season_menuID . '-event-menu-season', $event_menu, 15552000 );
      }
      $output .= ( empty($event_menu) ) ? ('<p class="error">Seasonal Event Menu Retrieval Error. ' . $event_menu . '.</p>') : $event_menu;
    } elseif( in_array('event-menu-region', $all_classes->li) ) {
      $menu_locations = get_nav_menu_locations();
      $event_season_menuID = $menu_locations['event_menu_region'];
      $event_menu = get_transient( 'menu-cache-menuid-' . $event_season_menuID . '-event-menu-region' );
      if ( false === $event_menu ) {
        $event_menu = g365_event_menu_region_nav();
        set_transient( 'menu-cache-menuid-' . $event_season_menuID . '-event-menu-region', $event_menu, 15552000 );
      }
      $output .= ( empty($event_menu) ) ? ('<p class="error">Regional Event Menu Retrieval Error. ' . $event_menu . '.</p>') : $event_menu;
    } else {
      //add the link
      $item_output = $g365_make_link( $atts, $args, $item->title, $item->ID, null );
      if( in_array('livesearch', $all_classes->li) ) {
        $output .= "$indent<div" . $id . $g365_make_class( $all_classes->li, $item, $args ) . ">";
        if( in_array('club-profiles', $all_classes->li) ) {
          $output .= '<h4 class="nav-title">Club Team Search</h4><span class="search-mag fi-magnifying-glass"></span><input type="text" class="search-hero g365_livesearch_input" data-g365_type="club_profiles" placeholder="Enter Team Name" autocomplete="off">';
        } elseif( in_array('player-profiles', $all_classes->li) ) {
          $output .= '<h4 class="nav-title">Player Search</h4><span class="search-mag fi-magnifying-glass"></span><input type="text" class="search-hero g365_livesearch_input" data-g365_type="player_profiles" placeholder="Enter Player Name" autocomplete="off">';
        } else {
          $output .= "\n$indent<span>&nbsp;</span>";
        }
      } else {
        $extra_data = null;
        $before_link = '';
        if( $item->object === 'product' ) {
          $product_event_link = intval(get_post_meta( $item->object_id, '_event_link', true ));
          if( $product_event_link !== 0 ) {
            $product_event = g365_get_event_data($product_event_link, true);
            if( !empty($product_event->logo_img) && in_array('logo-img', $all_classes->li) ) $before_link .= '<img class="menu-line-img" src="' . $product_event->logo_img . '" />';
            $product_data = array();
            if( !empty($product_event->dates) ) $product_data[] = g365_build_dates($product_event->dates, 1, true);
            $this_location = (in_array('loc-abbr', $item->classes) && !empty($product_event->short_locations)) ? $product_event->short_locations : $product_event->locations;
            if( !empty($this_location) ) $product_data[] = implode(', ', array_map(function($val){ return explode(',', $val)[0]; }, explode('|', $this_location)));
            if( !empty($product_data) ) $extra_data = implode(' | ', $product_data);
          }
        }
        $output .= $g365_make_link( $atts, $args, $item->title, $item->ID, $extra_data, $before_link );
      }
    }
    $item_output = '';
		$output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
//     if( in_array('event-menu', $all_classes->li) ) $output .= g365_event_menu_nav();
	}
	function end_el( &$output, $item, $depth = 0, $args = array() ) {
    $output .= "</li>\n";
	}
}
endif;

if ( ! class_exists( 'g365_Event_Walker' ) ) :
class g365_Event_Walker extends Walker_Nav_Menu {
	private $reg_output;
  private $reg_switch = false;
  private $dropdown_first = false;
	private $reg_grid_switch = false;
	private $filler_block = false;
	function start_lvl( &$output, $depth = 0, $args = array() ) {
		$indent = str_repeat("\t", $depth);
    //start the first level of dropdowns
    $output .= "\n$indent<div class=\"grid-x align-top\">\n";
    if( $this->reg_switch ) $this->reg_output .= "\n$indent<div class=\"grid-x align-top\">\n";
	}
	function end_lvl( &$output, $depth = 0, $args = array() ) {
		$indent = str_repeat("\t", $depth);
    $output .= "\n$indent</div>\n";
    if($this->reg_switch) $this->reg_output .= "\n$indent</div>\n";
	}
	function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {
		//this annonyamous function builds the html class string
		$g365_make_class = function( $classes_array, $item, $args ) {
			if( empty($classes_array) ) return;
			return ( ' class="' . esc_attr( join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes_array ), $item, $args ) ) ) . '"' );
		};
		//this annonyamous function builds the actual menu links
		$g365_make_link = function( $atts, $args, $itemtitle, $itemID, $extra_data, $before_link = '' ) {
//         print_r($atts);
			$attributes = '';
			//make all the attirbutes for a link element
			foreach ( $atts as $attr => $value ) {
				if ( ! empty( $value ) ) {
					//if we are processing the url use a different sanitization function
					$value = ( 'href' === $attr ) ? esc_url( $value ) : esc_attr( $value );
					$attributes .= ' ' . $attr . '="' . trim($value) . '"';
				}
			}
			//add any before info from the wp_nav function
			$item_output = $args->before;
			//build opening anchor tag or header if no href
      if( empty($atts['href']) ) {
        if( $args->current_depth === 0 && empty($args->drop_to_h3) ) {
    			$item_output .= '<h2' . $attributes .'>';
        } else {
          $item_output .= '<h3' . $attributes .'>';
        }
      } else {
  			$item_output .= '<a' . $attributes .'>';
      }
			// This filter is documented in wp-includes/post-template.php
			$item_output .= $args->link_before . $before_link . apply_filters( 'the_title', $itemtitle, $itemID ) . $args->link_after;
      
// 			if( $item->description != '' ) {
// 				$item_output .= ' - ' . $item->description;
// 			}
      if( $extra_data !== null ) $item_output .= '<small class="block subheader">' . $extra_data . '</small>';
			//build closeing anchor tag or header if no href
      if( empty($atts['href']) ) {
        if( $args->current_depth === 0 && empty($args->drop_to_h3) ) {
    			$item_output .= '</h2>';
        } else {
    			$item_output .= '</h3>';
        }
      } else {
  			$item_output .= '</a>';
      }
			//add closing anchor tag
			//add any after info from the wp_nav function
			$item_output .= $args->after;
			return $item_output;
		};
		$g365_sort_classes = function( $all_classes ) {
			$classes = (object) array(
				'col' => array(),
				'li' => array(),
				'anc' => array(),
				'divider' => false
			);
			foreach( $all_classes as $dex => $class ) {
				if( strpos($class, 'col-') !== false ) {
					if( strpos($class, 'divider') !== false ) {
						$classes->divider = true;
						continue;
					}
					$classes->col[] = str_replace( 'col-', '', $class );
				} elseif( strpos($class, 'anc-') !== false ) {
					$classes->anc[] = str_replace( 'anc-', '', $class );
				} else {
          $classes->li[] = $class;
				}
			}
			$classes->col[] = 'cell';
			return $classes;
		};
		$indent = ( $depth ) ? str_repeat( "\t", $depth ) : '';
		//collect and format all the attibutes for each link element
		$id = apply_filters( 'nav_menu_item_id', 'menu-item-'. $item->ID, $item, $args );
		$id = $id ? ' id="' . esc_attr( $id ) . '"' : '';
		//collect this item's attrubute data
		$atts = array(
			'title'  => !empty( $item->attr_title ) ? $item->attr_title : '',
			'target' => !empty( $item->target )     ? $item->target     : '',
			'rel'    => !empty( $item->xfn )        ? $item->xfn        : '',
			'href'   => !empty( $item->url )        ? $item->url        : ''
		);
		$atts = apply_filters( 'nav_menu_link_attributes', $atts, $item, $args, $depth );
		//figure out what content we need to add for each element

    $all_classes = $g365_sort_classes( $item->classes );
    if( !empty($all_classes->anc) ){
      if( empty($atts['class']) ) $atts["class"] = array();
      $atts['class'] = implode(' ', array_merge($atts['class'], $all_classes->anc));
    }

    
    
    //is this a new column, close the last column
    //reset the div closure
    if( $all_classes->divider && $this->dropdown_first ) {
      $output .=  "\n$indent</div>";
      $this->dropdown_first = false;
    }
    //end regional column formatting change
    if( $depth === 0 ) $this->reg_grid_switch = false;
    //is this a regional event column, start a format change
    if( in_array('regions-add', $all_classes->col) ) {
      $this->reg_grid_switch = true;
      //if the region column is created and finished, add it for mobile usage
      if(!empty($this->reg_output) && !$this->reg_switch) $output .= $this->reg_output;
      //end region template creation and set it for duplication for the next regions-add
      $this->reg_switch = false;
    }
    //is this the start of region label column creation
    if( in_array('regions-stack', $all_classes->col) ) $this->reg_switch = true;
    //is this a new column, write the opening div
    if( $all_classes->divider ) {
      $output .=  "\n$indent<div" . $g365_make_class( $all_classes->col, $item, $args ) . ">\n";
      if( $this->reg_switch ) $this->reg_output .=  "\n$indent<div" . $g365_make_class( $all_classes->col, $item, $args ) . ">\n";
      $this->dropdown_first = true;
    }
    //logo-super  
//          $output .= '<img class="menu-column-img" src="https://grassroots365.com/wp-content/uploads/event-logos/g365_tournaments-signature_400x300.png" />';
    if( $args->walker->has_children ) {
      $all_classes->li[] = 'nav-title';
      if( !empty($item->title) ) {
        //make the title
        $this_title = apply_filters( 'the_title', $item->title, $item->ID );
        $this_title_data = $this_title;
        //see if we need to add a header image
      $item->description = str_replace('||', '<br/>', $item->description); //  // Added by Dara 1/17/2020 Replace || with <br/>
      if( in_array('sc-logo', $all_classes->li) ) $this_title = '<img class="menu-column-img" src="https://grassroots365.com/wp-content/uploads/2019/08/G365_Qualifier_SoCal-Logo_400x300.png" /><span class="column-description">'  . $item->description .'</span><span class="column-title">' . $this_title . '</span>';
      if( in_array('nc-logo', $all_classes->li) ) $this_title = '<img class="menu-column-img" src="https://grassroots365.com/wp-content/uploads/2019/08/G365_Qualifier_NorCal-Logo_400x300.png" /><span class="column-description">' . $item->description .'</span><span class="column-title">' . $this_title . '</span>';
      if( in_array('pn-logo', $all_classes->li) ) $this_title = '<img class="menu-column-img" src="https://grassroots365.com/wp-content/uploads/2019/08/G365_Qualifier_Pacific_Northwest-Logo_400x300.png" /><span class="column-description">' . $item->description .'</span><span class="column-title">' . $this_title . '</span>';
      if( in_array('mw-logo', $all_classes->li) ) $this_title = '<img class="menu-column-img" src="https://grassroots365.com/wp-content/uploads/2019/08/G365_Qualifier_Mountainwest-Logo_400x300.png" /><span class="column-description">' . $item->description .'</span><span class="column-title">' . $this_title . '</span>';
      if( in_array('sw-logo', $all_classes->li) ) $this_title = '<img class="menu-column-img" src="https://grassroots365.com/wp-content/uploads/2019/08/G365_Qualifier_Southwest-Logo_400x300.png" /><span class="column-description">' . $item->description .'</span><span class="column-title">' . $this_title . '</span>';
        $output .= $indent . '<div' . (( in_array('halfer', $all_classes->li) ) ? ' class="cell small-12 large-6"' : '' ) . '><h4' . $g365_make_class( $all_classes->li, $item, $args ) . 'data-title="' . $this_title_data . '">' . $this_title . "</h4>\n";
      if( $this->reg_switch ) $this->reg_output .= $indent . '<h4' . $g365_make_class( $all_classes->li, $item, $args ) . 'data-title="' . $this_title . '">' . $this_title . "</h4>\n";
      }
    } elseif( in_array('livesearch', $all_classes->li) ) {
      $output .= "$indent<div" . $id . $g365_make_class( $all_classes->li, $item, $args ) . ">";
      if( in_array('club-profiles', $all_classes->li) ) {
        $output .= '<h4 class="nav-title">Club Team Search</h4><span class="search-mag fi-magnifying-glass"></span><input type="text" class="search-hero g365_livesearch_input" data-g365_type="club_profiles" placeholder="Enter Team Name" autocomplete="off">';
      } elseif( in_array('player-profiles', $all_classes->li) ) {
        $output .= '<h4 class="nav-title">Player Search</h4><span class="search-mag fi-magnifying-glass"></span><input type="text" class="search-hero g365_livesearch_input" data-g365_type="player_profiles" placeholder="Enter Player Name" autocomplete="off">';
      } else {
        $output .= "\n$indent<span>&nbsp;</span>";
      }
      $output .= "\n$indent</div>\n";
    } else {
      //start with a li tag
      if( $this->reg_grid_switch ) {
        //see if we need a trailing filler block and reset the variable
        $this->filler_block = array_filter( $all_classes->li, function($class_val){ return (strpos($class_val,'filler-block') !== false) ? true : false; });
        //only use the first instance
        if( count($this->filler_block) > 0 ) {
          $this->filler_block = $this->filler_block[0];
        } else {
          $this->filler_block = false;
        }
        //all of these items will have a cell class
        $all_classes->li[] = 'cell';
        //if we don't have a link, it's a sub header
        if( $atts['href'] === '' ) {
          $all_classes->li[] = 'nav-sub-title small-12';
        } else {
          //do we have an order on width from an event logo standpoint
          if( in_array('logo-img', $all_classes->li) ) {
            //is it a regular logo or a big one
            if( in_array('logo-super', $all_classes->li) || in_array('menu-column-img-single', $all_classes->li) ) {
              $all_classes->li[] = 'small-12';
            } else {
              $all_classes->li[] = 'small-4';
            }
          } else {
            //regular cell size
            $all_classes->li[] = 'small-3';
          }
        }
        //write the opening div tag

        $output .= "$indent<div" . $id . $g365_make_class( $all_classes->li, $item, $args ) . ">";
        $extra_data = null;
        $before_link = '';
        if( $item->object === 'product' ) {
          $product_event_link = intval(get_post_meta( $item->object_id, '_event_link', true ));
          if( $product_event_link !== 0 ) {
            $product_event = g365_get_event_data($product_event_link, true);
            //if we have a location
            $this_location = (empty($product_event->short_locations)) ? (!empty($product_event->locations) ? $product_event->locations : '') : $product_event->short_locations;
            $this_location = g365_build_locations($this_location, 2);
            //if we have dates switch change all the links around
            if( empty($atts['class']) ) $atts['class'] = '';
            $atts['class'] .= ' link-date-only';
            if( !empty($product_event->dates) ) {
              $dates_formatted = preg_replace('/ - /', '-', g365_build_dates($product_event->dates, 2, false));
              $extra_data = $item->title;
              $atts['title'] = $item->title . ' -- ' . $dates_formatted;
              $item->title = $dates_formatted;
              if( !empty($this_location ) ) $item->title .= '<span class="additional-info">' . $this_location . '</span>';
            }
            //if we have a logo and the orders, add event logo
            if( !empty($product_event->logo_img) && in_array('logo-img', $all_classes->li) ) $item->title = '<img class="menu-column-img" src="' . $product_event->logo_img . '" />' . $item->title;
            if( !empty($product_event->locations) ) $atts['title'] .=  ' ' . g365_build_locations($product_event->locations);
          }
        }
      } else {
//         LIVE SITE
        //set default if element doesn't have formatting classes
        if( !in_array('cell', $all_classes->li) ) $all_classes->li[] = 'cell small-12';
        //if we don't have a link then treat it as a (sub)title
        if( $atts['href'] === '' ) {
          $all_classes->li[] = 'nav-sub-title';
//           $item->description = str_replace('||', '<br/>', $item->description); //  // Added by Dara 1/17/2020 Replace || with <br/>
//           if( $item->title == 'SoCal' ) $item->title = '<img class="menu-column-img" src="https://grassroots365.com/wp-content/uploads/2019/08/G365_Qualifier_SoCal-Logo_400x300.png" /><span class="column-description">'  . $item->description .'</span><span class="column-title">' . $item->title . '</span>';
//           if( in_array('nc-logo', $all_classes->li) ) $item->title = '<img class="menu-column-img" src="https://grassroots365.com/wp-content/uploads/2019/08/G365_Qualifier_NorCal-Logo_400x300.png" /><span class="column-description">' . $item->description .'</span><span class="column-title">' . $item->title . '</span>';
//           if( in_array('pn-logo', $all_classes->li) ) $item->title = '<img class="menu-column-img" src="https://grassroots365.com/wp-content/uploads/2019/08/G365_Qualifier_Pacific_Northwest-Logo_400x300.png" /><span class="column-description">' . $item->description .'</span><span class="column-title">' . $item->title . '</span>';
//           if( in_array('mw-logo', $all_classes->li) ) $item->title = '<img class="menu-column-img" src="https://grassroots365.com/wp-content/uploads/2019/08/G365_Qualifier_Mountainwest-Logo_400x300.png" /><span class="column-description">' . $item->description .'</span><span class="column-title">' . $item->title . '</span>';
//           if( in_array('sw-logo', $all_classes->li) ) $item->title = '<img class="menu-column-img" src="https://grassroots365.com/wp-content/uploads/2019/08/G365_Qualifier_Southwest-Logo_400x300.png" /><span class="column-description">' . $item->description .'</span><span class="column-title">' . $item->title . '</span>';
        }
        //start the element
        $output .= $indent . '<div' . $id . $g365_make_class( $all_classes->li, $item, $args ) . ">";
        //set variable for strict
        $extra_data = null;
        //if it's a product see if we have additional data
        if( $item->object === 'product' ) {
          //the the event link
          $product_event_link = intval(get_post_meta( $item->object_id, '_event_link', true ));
          //if we have a link, see where it goes
          if( $product_event_link !== 0 ) {
            $product_event = g365_get_event_data($product_event_link, true);
            //if we have dates switch change all the links around
            if( !empty($product_event->dates) ) {
              $dates_formatted = preg_replace('/ - /', '-', g365_build_dates($product_event->dates, 2, false));
              $atts['title'] = $item->title . ' -- ' . $dates_formatted;
              $atts['class'] .= ' link-date-only';
              $extra_data = $item->title;
              $item->title = $dates_formatted;
            }
            //if we have a location
            if( !empty($product_event->short_locations) || !empty($product_event->locations) ) {
              $this_location = (in_array('loc-abbr', $item->classes) && !empty($product_event->short_locations)) ? $product_event->short_locations : $product_event->locations;
//               old
//               $this_location = implode(', ', array_map(function($val){ return explode(',', $val)[0]; }, explode('|', $this_location)));
//               new
              
              $this_location = implode('<br>', array_map(function($val){ return explode(',', $val)[0]; }, explode('|', $this_location)));
              $extra_data = '<span class="additional-info">' . $this_location . '</span>';
            }
            //if we have a logo and the orders, add event logo and change around the 
            if( !empty($product_event->logo_img) && in_array('logo-img', $all_classes->li) ) {
              $item->title = '<img class="menu-column-img' . '" src="' . $product_event->logo_img . '" />';
              if( !empty($dates_formatted) ) $extra_data = $dates_formatted . $extra_data;
            }
          }
        }
      }
      if($this->reg_switch) $this->reg_output .= $indent . '<div' . $id . $g365_make_class( $all_classes->li, $item, $args ) . ">";
      $args->current_depth = $depth;
      if( in_array('row-label', $all_classes->col) ) $args->drop_to_h3 = true;
      //if we are the main title, add the regions button
      if( in_array('resizer-main', $all_classes->col) ) {
        $args->link_after .= '<div class="small-block">';
        $args->link_after .= '<a id="g365-all-events-button" class="button small large-right show-for-large" style="display: none;">View All Events</a>';
        if( !empty($atts['href']) ) {
          $args->link_after .= '<a class="button small large-left" style="display: none; href="' . $atts['href'] . '">Overview</a>';
          unset($atts['href']);
        }
        $args->link_after .= '</div>';
      }
      //if we are the main title, add the regions button
      if( in_array('revealer-main', $all_classes->col) ) {
        $args->link_after .= '<div class="small-block">';
        $args->link_after .= '<a id="g365-all-regions-button" class="button small large-right" style="display: none;">View All Regions</a>';
        if( !empty($atts['href']) ) {
          $args->link_after .= '<a class="button small large-left" style="display: none; href="' . $atts['href'] . '">Overview</a>';
          unset($atts['href']);
        }
        $args->link_after .= '</div>';
      }
      //add the link
      $item_output = $g365_make_link( $atts, $args, $item->title, $item->ID, $extra_data );
      //if we are the main title, clean up
      if( in_array('resizer-main', $all_classes->col) || in_array('revealer-main', $all_classes->col) ) $args->link_after = '';
      //filter and apply link output
      $output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
      //add parts to regional column template
      if($this->reg_switch) $this->reg_output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
		}
	}
	function end_el( &$output, $item, $depth = 0, $args = array() ) {
		$indent = str_repeat("\t", $depth);
    $output .= "\n$indent</div>\n";
    //add parts to regional column template
    if( $this->reg_switch ) $this->reg_output .= "\n$indent</div>\n";
    //check to see if we need filler blocks and how many
    if( !empty($this->filler_block) && (is_int(intval(substr($this->filler_block, -1))) || $this->filler_block === 'filler-block') ) {
      for ( $i = 0; $i < (( $this->filler_block === 'filler-block' ) ? 1 : intval(substr($this->filler_block, -1)) ); $i++) {
        $output .= '<div class="cell small-3 menu-item-object-product"></div>';
      }
      $this->filler_block = false;
    }
	}
}
endif;

if ( ! class_exists( 'g365_Mobile_Walker' ) ) :
class g365_Mobile_Walker extends Walker_Nav_Menu {
	function start_lvl( &$output, $depth = 0, $args = array() ) {
		$indent = str_repeat("\t", $depth);
		$output .= "\n$indent<ul class=\"nested\">\n";
	}
}
endif;

if ( ! class_exists( 'g365_Mega_Walker' ) ) :
class g365_Mega_Walker extends Walker_Nav_Menu {
	private $curItem;
	function start_lvl( &$output, $depth = 0, $args = array() ) {
		$indent = str_repeat("\t", $depth);
		switch($depth) {
			case 0:
				$output .= "\n$indent<ul class=\"menu vertical submenu\">\n";
				$output .= "\n$indent<li>";
				$output .= "\n$indent<div class=\"grid-x grid-margin-x\">";
				$output .= "\n$indent<div class=\"cell\">";
				break;
			case 1:
				//if we are starting the first level of dropdowns use this
				$output .= "\n$indent<div class=\"nested\">\n";
				break;
			default:
				//if we are starting the any other level of dropdowns use this
				$output .= "\n$indent<div class=\"nested\">\n";
				break;
		}
	}
	function end_lvl( &$output, $depth = 0, $args = array() ) {
		$indent = str_repeat("\t", $depth);
		switch($depth) {
			case 0:
				$output .= "\n$indent</div>\n";
				$output .= "\n$indent</div>\n";
				$output .= "\n$indent</li>\n";
				$output .= "\n$indent</ul>\n";
				break;
			case 1:
				//if we are starting the first level of dropdowns use this
				$output .= "\n$indent</div>\n";
				break;
			default:
				//if we are starting the any other level of dropdowns use this
				$output .= "\n$indent</div>\n";
				break;
		}
	}
	function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {
//     if($item->object === 'product') {}
		//this annonyamous function builds the html class string
		$g365_make_class = function( $classes_array, $item, $args ) {
			if( empty($classes_array) ) return;
			return ( ' class="' . esc_attr( join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes_array ), $item, $args ) ) ) . '"' );
		};
		//this annonyamous function builds the actual menu links
		$g365_make_link = function( $atts, $args, $itemtitle, $itemID, $extra_data, $before_link = '' ) {
			$attributes = '';
			//make all the attirbutes for a link element
			foreach ( $atts as $attr => $value ) {
				if ( ! empty( $value ) ) {
					//if we are processing the url use a different sanitization function
					$value = ( 'href' === $attr ) ? esc_url( $value ) : esc_attr( $value );
					$attributes .= ' ' . $attr . '="' . $value . '"';
				}
			}
			//add any before info from the wp_nav function
			$item_output = $args->before;
			//build opening anchor tag
			$item_output .= '<a'. $attributes .'>';
			// This filter is documented in wp-includes/post-template.php
			$item_output .= $args->link_before . $before_link . apply_filters( 'the_title', $itemtitle, $itemID ) . $args->link_after;
      
// 			if( $item->description != '' ) {
// 				$item_output .= ' - ' . $item->description;
// 			}
      if( $extra_data !== null ) $item_output .= '<small class="block subheader">' . $extra_data . '</small>';
			//add closing anchor tag
			$item_output .= '</a>';
			//add any after info from the wp_nav function
			$item_output .= $args->after;
			return $item_output;
		};
		$g365_sort_classes = function( $all_classes ) {
			$classes = (object) array(
				'col' => array(),
				'li' => array(),
				'anc' => array(),
				'divider' => false
			);
			foreach( $all_classes as $dex => $class ) {
				if( strpos($class, 'col-') !== false ) {
					if( strpos($class, 'divider') !== false ) {
						$classes->divider = true;
						continue;
					}
					$classes->col[] = str_replace( 'col-', '', $class );
				} elseif( strpos($class, 'anc-') !== false ) {
					$classes->anc[] = str_replace( 'anc-', '', $class );
				} else {
          $classes->li[] = $class;
				}
			}
			$classes->col[] = 'cell';
			return $classes;
		};
		$indent = ( $depth ) ? str_repeat( "\t", $depth ) : '';
		//collect and format all the attibutes for each link element
		$id = apply_filters( 'nav_menu_item_id', 'menu-item-'. $item->ID, $item, $args );
		$id = $id ? ' id="' . esc_attr( $id ) . '"' : '';
		//collect this item's attrubute data
		$atts = array(
			'title'  => !empty( $item->attr_title ) ? $item->attr_title : '',
			'target' => !empty( $item->target )     ? $item->target     : '',
			'rel'    => !empty( $item->xfn )        ? $item->xfn        : '',
			'href'   => !empty( $item->url )        ? $item->url        : ''
		);
		$atts = apply_filters( 'nav_menu_link_attributes', $atts, $item, $args, $depth );
		//figure out what content we need to add for each element
		switch($depth) {
			case 0:
        $all_classes = $g365_sort_classes( $item->classes );
        if( empty($all_classes->col) ) {
          $li_classes = $all_classes->li;
          $atts['class'] = [];
        } else {
          $li_classes = $all_classes->col;
          $atts['class'] = $all_classes->li;
        }
        if( !empty($all_classes->anc) ){
          if( empty($atts['class']) ) $atts["class"] = array();
          $atts['class'] = array_merge($atts['class'],$all_classes->anc);
        }
				//start with a li tag
				$output .= $indent . '<li' . $id . $g365_make_class( $li_classes, $item, $args ) . ">\n";
				//add the link
        
        if( empty($atts['href']) ) {
          $item_output = $indent . '<h4' . $g365_make_class( $atts['class'], $item, $args ) . '>' . apply_filters( 'the_title', $item->title, $item->ID ) . "</h4>\n";
        } else {
          $atts['class'] = esc_attr( join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $atts['class'] ), $item, $args ) ) );
          $item_output = $g365_make_link( $atts, $args, $item->title, $item->ID, null );
        }
				break;
			case 1:
				//is this a column
				if( !empty(array_filter($item->classes, function($check_class) {return (strpos($check_class, 'col-') !== false ? true : false);})) ) {
					$all_classes = $g365_sort_classes( $item->classes );
					$item->classes = $all_classes->li;
					if( $all_classes->divider ) $output .=  "$indent\n</div>$indent\n<div" . $g365_make_class( $all_classes->col, $item, $args ) . ">\n";
				}
        $switch_to_img_header = false;
        if( in_array('sig-logo-img', $item->classes) ) { $output .= '<img class="menu-column-img" src="https://grassroots365.com/wp-content/uploads/event-logos/g365_tournaments-signature_400x300.png" />'; $switch_to_img_header = true; }
        if( in_array('nat-logo-img', $item->classes) ) { $output .= '<img class="menu-column-img" src="https://grassroots365.com/wp-content/uploads/event-logos/g365_tournaments-national_400x300.png" />'; $switch_to_img_header = true; }
        if( in_array('kic-logo-img', $item->classes) ) { $output .= '<img class="menu-column-img" src="https://grassroots365.com/wp-content/uploads/event-logos/g365_tournaments-kickoff_400x300.png" />'; $switch_to_img_header = true; }
        if( in_array('wes-logo-img', $item->classes) ) { $output .= '<img class="menu-column-img" src="https://grassroots365.com/wp-content/uploads/event-logos/g365_tournaments-west_400x300.png" />'; $switch_to_img_header = true; }
        if( in_array('cla-logo-img', $item->classes) ) { $output .= '<img class="menu-column-img" src="https://grassroots365.com/wp-content/uploads/event-logos/g365_tournaments-classic_400x300.png" />'; $switch_to_img_header = true; }
        if( in_array('inv-logo-img', $item->classes) ) { $output .= '<img class="menu-column-img" src="https://grassroots365.com/wp-content/uploads/event-logos/g365_tournaments-invitational_400x300.png" />'; $switch_to_img_header = true; }
        if( in_array('sho-logo-img', $item->classes) ) { $output .= '<img class="menu-column-img" src="https://grassroots365.com/wp-content/uploads/event-logos/g365_tournaments-showcase_400x300.png" />'; $switch_to_img_header = true; }
				if( $args->walker->has_children ) {
					$item->classes[] = 'nav-title' . ( ($switch_to_img_header) ? ' hide' : '');
					if( !empty($item->title) ) $output .= $indent . '<h5' . $g365_make_class( $item->classes, $item, $args ) . '>' . apply_filters( 'the_title', $item->title, $item->ID ) . "</h5>\n";
				} elseif( in_array('livesearch', $item->classes) ) {
					$output .= "$indent<div" . $id . $g365_make_class( $item->classes, $item, $args ) . ">";
					if( in_array('club-profiles', $item->classes) ) {
						$output .= '<h4 class="nav-title">Club Team Search</h4><span class="search-mag fi-magnifying-glass"></span><input type="text" class="search-hero g365_livesearch_input" data-g365_type="club_profiles" placeholder="Enter Team Name" autocomplete="off">';
					} elseif( in_array('player-profiles', $item->classes) ) {
						$output .= '<h4 class="nav-title">Player Search</h4><span class="search-mag fi-magnifying-glass"></span><input type="text" class="search-hero g365_livesearch_input" data-g365_type="player_profiles" placeholder="Enter Player Name" autocomplete="off">';
					} else {
						$output .= "\n$indent<span>&nbsp;</span>";
					}
					$output .= "\n$indent</div>\n";
				} else {
					$output .= "$indent<div" . $id . $g365_make_class( $item->classes, $item, $args ) . ">";
          $extra_data = null;
          $before_link = '';
          if( $item->object === 'product' ) {
            $product_event_link = intval(get_post_meta( $item->object_id, '_event_link', true ));
            if( $product_event_link !== 0 ) {
              $product_event = g365_get_event_data( $product_event_link, true );
              if( !empty($product_event->logo_img) && in_array('logo-img', $item->classes) ) $before_link .= '<img class="menu-line-img" src="' . $product_event->logo_img . '" />';
              $product_data = array();
              if( !empty($product_event->dates) ) $product_data[] = g365_build_dates($product_event->dates, 1, true);
              if( !empty($product_event->locations) ) $product_data[] = implode(', ', array_map(function($val){ return explode(',', $val)[0]; }, explode('|', $product_event->locations)));
              if( !empty($product_data) ) $extra_data = implode(' | ', $product_data);
            }
          }
					$output .= $g365_make_link( $atts, $args, $item->title, $item->ID, $extra_data, $before_link );
					$output .= "\n$indent</div>\n";
				}
				$item_output = '';
				break;
			default:
				//start with a li tag
				$output .= $indent . '<div' . $id . $g365_make_class( $item->classes, $item, $args ) . ">";
        $extra_data = null;
        if( $item->object === 'product' ) {
          $product_event_link = intval(get_post_meta( $item->object_id, '_event_link', true ));
          if( $product_event_link !== 0 ) {
            $product_event = g365_get_event_data($product_event_link, true );
            if( !empty($product_event->logo_img) && in_array('logo-img', $item->classes) ) $output .= '<img class="menu-line-img" src="' . $product_event->logo_img . '" />';
            $product_data = array();
            if( !empty($product_event->dates) ) $product_data[] = g365_build_dates($product_event->dates, 1, true);
            if( !empty($product_event->locations) ) $product_data[] = implode(', ', array_map(function($val){ return explode(',', $val)[0]; }, explode('|', $product_event->locations)));
            if( !empty($product_data) ) $extra_data = implode(' | ', $product_data);
          }
        }
				//add the link
				$item_output = $g365_make_link( $atts, $args, $item->title, $item->ID, $extra_data );
				break;
		}
		$output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
	}
	function end_el( &$output, $item, $depth = 0, $args = array() ) {
		switch($depth) {
			case 0:
				$output .= "</li>\n";
				break;
			case 1:
				break;
			default:
				$output .= "\n$indent</div>\n";
				break;
		}
	}
}
endif;


if ( ! class_exists( 'g365_Side_Slide_Walker' ) ) :
class g365_Side_Slide_Walker extends Walker_Nav_Menu {
	private $curItem;
	function start_lvl( &$output, $depth = 0, $args = array() ) {
		$indent = str_repeat("\t", $depth);
		switch($depth) {
			case 0:
				$output .= "\n$indent<ul class=\"menu\">\n";
				$output .= "\n$indent<li>";
				$output .= "\n$indent<div class=\"grid-x grid-margin-x\">";
				$output .= "\n$indent<div class=\"cell\">";
				break;
			case 1:
				//if we are starting the first level of dropdowns use this
				$output .= "\n$indent<div class=\"nested\">\n";
				break;
			default:
				//if we are starting the any other level of dropdowns use this
				$output .= "\n$indent<div class=\"nested\">\n";
				break;
		}
	}
	function end_lvl( &$output, $depth = 0, $args = array() ) {
		$indent = str_repeat("\t", $depth);
		switch($depth) {
			case 0:
				$output .= "\n$indent</div>\n";
				$output .= "\n$indent</div>\n";
				$output .= "\n$indent</li>\n";
				$output .= "\n$indent</ul>\n";
				break;
			case 1:
				//if we are starting the first level of dropdowns use this
				$output .= "\n$indent</div>\n";
				break;
			default:
				//if we are starting the any other level of dropdowns use this
				$output .= "\n$indent</div>\n";
				break;
		}
	}
	function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {
//     if($item->object === 'product') {}
		//this annonyamous function builds the html class string
		$g365_make_class = function( $classes_array, $item, $args ) {
			if( empty($classes_array) ) return;
			return ( ' class="' . esc_attr( join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes_array ), $item, $args ) ) ) . '"' );
		};
		//this annonyamous function builds the actual menu links
		$g365_make_link = function( $atts, $args, $itemtitle, $itemID, $extra_data, $before_link = '' ) {
			$attributes = '';
			//make all the attirbutes for a link element
			foreach ( $atts as $attr => $value ) {
				if ( ! empty( $value ) ) {
					//if we are processing the url use a different sanitization function
					$value = ( 'href' === $attr ) ? esc_url( $value ) : esc_attr( $value );
					$attributes .= ' ' . $attr . '="' . $value . '"';
				}
			}
			//add any before info from the wp_nav function
			$item_output = $args->before;
			//build opening anchor tag
			$item_output .= '<a'. $attributes .'>';
			// This filter is documented in wp-includes/post-template.php
			$item_output .= $args->link_before . $before_link . apply_filters( 'the_title', $itemtitle, $itemID ) . $args->link_after;
      
// 			if( $item->description != '' ) {
// 				$item_output .= ' - ' . $item->description;
// 			}
      if( $extra_data !== null ) $item_output .= '<small class="block subheader">' . $extra_data . '</small>';
			//add closing anchor tag
			$item_output .= '</a>';
			//add any after info from the wp_nav function
			$item_output .= $args->after;
			return $item_output;
		};
		$g365_sort_classes = function( $all_classes ) {
			$classes = (object) array(
				'col' => array(),
				'li' => array(),
				'anc' => array(),
				'divider' => false
			);
			foreach( $all_classes as $dex => $class ) {
				if( strpos($class, 'col-') !== false ) {
					if( strpos($class, 'divider') !== false ) {
						$classes->divider = true;
						continue;
					}
					$classes->col[] = str_replace( 'col-', '', $class );
				} elseif( strpos($class, 'anc-') !== false ) {
					$classes->anc[] = str_replace( 'anc-', '', $class );
				} else {
          $classes->li[] = $class;
				}
			}
			return $classes;
		};
		$indent = ( $depth ) ? str_repeat( "\t", $depth ) : '';
		//collect and format all the attibutes for each link element
		$id = apply_filters( 'nav_menu_item_id', 'menu-item-'. $item->ID, $item, $args );
		$id = $id ? ' id="' . esc_attr( $id ) . '"' : '';
		//collect this item's attrubute data
		$atts = array(
			'title'  => !empty( $item->attr_title ) ? $item->attr_title : '',
			'target' => !empty( $item->target )     ? $item->target     : '',
			'rel'    => !empty( $item->xfn )        ? $item->xfn        : '',
			'href'   => !empty( $item->url )        ? $item->url        : ''
		);
		$atts = apply_filters( 'nav_menu_link_attributes', $atts, $item, $args, $depth );
		//figure out what content we need to add for each element
		switch($depth) {
			case 0:
        $all_classes = $g365_sort_classes( $item->classes );
        if( empty($all_classes->col) ) {
          $li_classes = $all_classes->li;
          $atts['class'] = [];
        } else {
          $li_classes = $all_classes->col;
          $atts['class'] = $all_classes->li;
        }
        if( !empty($all_classes->anc) ){
          if( empty($atts['class']) ) $atts["class"] = array();
          $atts['class'] = array_merge($atts['class'],$all_classes->anc);
        }
				//start with a li tag
				$output .= $indent . '<li' . $id . $g365_make_class( $li_classes, $item, $args ) . ">\n";
				//add the link
        
        if( empty($atts['href']) ) {
          $item_output = $indent . '<a' . $g365_make_class( $atts['class'], $item, $args ) . '>' . apply_filters( 'the_title', $item->title, $item->ID ) . "</a>\n";
        } else {
          $atts['class'] = esc_attr( join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $atts['class'] ), $item, $args ) ) );
          $item_output = $g365_make_link( $atts, $args, $item->title, $item->ID, null );
        }
				break;
			case 1:
				//is this a column
				if( !empty(array_filter($item->classes, function($check_class) {return (strpos($check_class, 'col-') !== false ? true : false);})) ) {
					$all_classes = $g365_sort_classes( $item->classes );
					$item->classes = $all_classes->li;
          $all_classes->col[] = 'cell';
					if( $all_classes->divider ) $output .=  "$indent\n</div>$indent\n<div" . $g365_make_class( $all_classes->col, $item, $args ) . ">\n";
				}
        $switch_to_img_header = false;
        if( in_array('sig-logo-img', $item->classes) ) { $output .= '<img class="menu-column-img" src="https://grassroots365.com/wp-content/uploads/event-logos/g365_tournaments-signature_400x300.png" />'; $switch_to_img_header = true; }
        if( in_array('nat-logo-img', $item->classes) ) { $output .= '<img class="menu-column-img" src="https://grassroots365.com/wp-content/uploads/event-logos/g365_tournaments-national_400x300.png" />'; $switch_to_img_header = true; }
        if( in_array('kic-logo-img', $item->classes) ) { $output .= '<img class="menu-column-img" src="https://grassroots365.com/wp-content/uploads/event-logos/g365_tournaments-kickoff_400x300.png" />'; $switch_to_img_header = true; }
        if( in_array('wes-logo-img', $item->classes) ) { $output .= '<img class="menu-column-img" src="https://grassroots365.com/wp-content/uploads/event-logos/g365_tournaments-west_400x300.png" />'; $switch_to_img_header = true; }
        if( in_array('cla-logo-img', $item->classes) ) { $output .= '<img class="menu-column-img" src="https://grassroots365.com/wp-content/uploads/event-logos/g365_tournaments-classic_400x300.png" />'; $switch_to_img_header = true; }
        if( in_array('inv-logo-img', $item->classes) ) { $output .= '<img class="menu-column-img" src="https://grassroots365.com/wp-content/uploads/event-logos/g365_tournaments-invitational_400x300.png" />'; $switch_to_img_header = true; }
        if( in_array('sho-logo-img', $item->classes) ) { $output .= '<img class="menu-column-img" src="https://grassroots365.com/wp-content/uploads/event-logos/g365_tournaments-showcase_400x300.png" />'; $switch_to_img_header = true; }
				if( $args->walker->has_children ) {
					$item->classes[] = 'nav-title' . ( ($switch_to_img_header) ? ' hide' : '');
					if( !empty($item->title) ) $output .= $indent . '<h5' . $g365_make_class( $item->classes, $item, $args ) . '>' . apply_filters( 'the_title', $item->title, $item->ID ) . "</h5>\n";
				} elseif( in_array('livesearch', $item->classes) ) {
					$output .= "$indent<div" . $id . $g365_make_class( $item->classes, $item, $args ) . ">";
					if( in_array('club-profiles', $item->classes) ) {
						$output .= '<h4 class="nav-title">Club Team Search</h4><span class="search-mag fi-magnifying-glass"></span><input type="text" class="search-hero g365_livesearch_input" data-g365_type="club_profiles" placeholder="Enter Team Name" autocomplete="off">';
					} elseif( in_array('player-profiles', $item->classes) ) {
						$output .= '<h4 class="nav-title">Player Search</h4><span class="search-mag fi-magnifying-glass"></span><input type="text" class="search-hero g365_livesearch_input" data-g365_type="player_profiles" placeholder="Enter Player Name" autocomplete="off">';
					} else {
						$output .= "\n$indent<span>&nbsp;</span>";
					}
					$output .= "\n$indent</div>\n";
				} else {
					$output .= "$indent<div" . $id . $g365_make_class( $item->classes, $item, $args ) . ">";
          $extra_data = null;
          $before_link = '';
          if( $item->object === 'product' ) {
            $product_event_link = intval(get_post_meta( $item->object_id, '_event_link', true ));
            if( $product_event_link !== 0 ) {
              $product_event = g365_get_event_data( $product_event_link, true );
              if( !empty($product_event->logo_img) && in_array('logo-img', $item->classes) ) $before_link .= '<img class="menu-line-img" src="' . $product_event->logo_img . '" />';
              $product_data = array();
              if( !empty($product_event->dates) ) $product_data[] = g365_build_dates($product_event->dates, 1, true);
              if( !empty($product_event->locations) ) $product_data[] = implode(', ', array_map(function($val){ return explode(',', $val)[0]; }, explode('|', $product_event->locations)));
              if( !empty($product_data) ) $extra_data = implode(' | ', $product_data);
            }
          }
					$output .= $g365_make_link( $atts, $args, $item->title, $item->ID, $extra_data, $before_link );
					$output .= "\n$indent</div>\n";
				}
				$item_output = '';
				break;
			default:
        $item->classes[] = 'cell';
				//start with a li tag
				$output .= $indent . '<div' . $id . $g365_make_class( $item->classes, $item, $args ) . ">";
        $extra_data = null;
        if( $item->object === 'product' ) {
          $product_event_link = intval(get_post_meta( $item->object_id, '_event_link', true ));
          if( $product_event_link !== 0 ) {
            $product_event = g365_get_event_data($product_event_link, true );
            if( !empty($product_event->logo_img) && in_array('logo-img', $item->classes) ) $output .= '<img class="menu-line-img" src="' . $product_event->logo_img . '" />';
            $product_data = array();
            if( !empty($product_event->dates) ) $product_data[] = g365_build_dates($product_event->dates, 1, true);
            if( !empty($product_event->locations) ) $product_data[] = implode(', ', array_map(function($val){ return explode(',', $val)[0]; }, explode('|', $product_event->locations)));
            if( !empty($product_data) ) $extra_data = implode(' | ', $product_data);
          }
        }
				//add the link
				$item_output = $g365_make_link( $atts, $args, $item->title, $item->ID, $extra_data );
				break;
		}
		$output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
	}
	function end_el( &$output, $item, $depth = 0, $args = array() ) {
		switch($depth) {
			case 0:
				$output .= "</li>\n";
				break;
			case 1:
				break;
			default:
				$output .= "\n$indent</div>\n";
				break;
		}
	}
}
endif;

?>