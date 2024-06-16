<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://github.com/alek-wdgt/
 * @since      1.0.0
 *
 * @package    Rankseo
 * @subpackage Rankseo/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Rankseo
 * @subpackage Rankseo/admin
 * @author     Alek Vojinovic <winspirers@gmail.com>
 */
class Rankseo_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Rankseo_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Rankseo_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/rankseo-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Rankseo_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Rankseo_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/rankseo-admin.js', array( 'jquery' ), $this->version, false );

	}


    /**
     * Init Admin Menu.
     *
     * @return void
     */
    public function init_menu() {
      wp_add_dashboard_widget(
          'rankseo_dashboard_widget',
          __( 'Rank SEO Dashboard', 'rankseo' ),
          array( $this, 'admin_page' )
      );
    }
    public function admin_page() {
      echo '<div id="rankseo"></div>';
      $this->enqueue_dashboard_widget_assets();
    }
    /**
     * * Register the stats custom post type
     *
     * @since 1.0.0
     */
    public function rankseo_stats(): void {
      $labels = array(
        'name'               => _x('Stats', 'post type general name'),
        'singular_name'      => _x('Stat', 'post type singular name'),
        'menu_name'          => _x('Stats', 'admin menu'),
        'name_admin_bar'     => _x('Stat', 'add new on admin bar'),
        'add_new'            => _x('Add New', 'stat'),
        'add_new_item'       => __('Add New Stat'),
        'new_item'           => __('New Stat'),
        'edit_item'          => __('Edit Stat'),
        'view_item'          => __('View Stat'),
        'all_items'          => __('All Stats'),
        'search_items'       => __('Search Stats'),
        'parent_item_colon'  => __('Parent Stat:'),
        'not_found'          => __('No stats found.'),
        'not_found_in_trash' => __('No stats found in Trash.')
      );

      $args = array(
        'labels'             => $labels,
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => true,
        'rewrite'            => array('slug' => 'stats'),
        'capability_type'    => 'post',
        'has_archive'        => true,
        'hierarchical'       => true,
        'show_in_rest'       => true,
        'menu_position'      => null,
        'supports'           => array('title'),
    );

    register_post_type('stats', $args);
  }

  /**
   * ACF Repeater fto populate datas
   *
   */
  public function register_statistic_fields(): void
  {
    acf_add_local_field_group(array(
          'key' => 'group_666f09bd18c56',
          'title' => 'Dates',
          'fields' => array(
              array(
                  'key' => 'field_666f09bd18564',
                  'label' => 'Statistics',
                  'name' => 'statistics',
                  'type' => 'repeater',
                  'layout' => 'table',
                  'button_label' => 'Add Row',
                  'sub_fields' => array(
                      array(
                          'key' => 'field_666f0d5fadd91',
                          'label' => 'Dates',
                          'name' => 'dates',
                          'type' => 'date_picker',
                          'display_format' => 'd/m/Y',
                          'return_format' => 'd/m/Y',
                          'first_day' => 1,
                          'parent_repeater' => 'field_666f09bd18564',
                      ),
                      array(
                          'key' => 'field_666f0d85add92',
                          'label' => 'Visitors',
                          'name' => 'visitors',
                          'type' => 'number',
                          'parent_repeater' => 'field_666f09bd18564',
                      ),
                  ),
              ),
          ),
          'location' => array(
              array(
                  array(
                      'param' => 'post_type',
                      'operator' => '==',
                      'value' => 'stats',
                  ),
              ),
          ),
          'menu_order' => 0,
          'position' => 'normal',
          'style' => 'default',
          'label_placement' => 'top',
          'instruction_placement' => 'label',
          'hide_on_screen' => '',
          'active' => true,
          'description' => '',
          'show_in_rest' => 1,
      ));
    }

  /**
   * Enqueue the assets for the dashboard widget.
   *
   * @since    1.0.0
   * @return void
   */
  public function enqueue_dashboard_widget_assets() {
    wp_enqueue_style( 'rankseo-style', plugin_dir_url( __FILE__ ) . '../build/index.css' );
    wp_enqueue_script( 'rankseo-script', plugin_dir_url( __FILE__ ) . '../build/index.js', array( 'wp-element' ), '1.0.0', true );
  }

}
