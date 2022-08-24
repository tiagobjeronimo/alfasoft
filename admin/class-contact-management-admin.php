<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @since      1.0.0
 *
 * @package    Contact_Management
 * @subpackage Contact_Management/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Contact_Management
 * @subpackage Contact_Management/admin
 * @author     Tiago Jerónimo <tbjeronimo@gmail.com>
 */
class Contact_Management_Admin
{

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
	public function __construct($plugin_name, $version)
	{

		$this->plugin_name = $plugin_name;
		$this->version = $version;
	}

	public function cm_cpt()
	{
		register_post_type(
			'cm_person',
			array(
				'labels' => array(
					'name' => __('Persons', 'textdomain'),
					'singular_name' => __('Person', 'textdomain'),
				),
				'has_archive' => true,
				'hierarquical' => true,
				'public' => true,
				'show_in_menu' => false,
				'supports' => false
			)
		);

		register_post_type(
			'cm_contact',
			array(
				'labels' => array(
					'name' => __('Contacts', 'textdomain'),
					'singular_name' => __('Contact', 'textdomain'),
				),
				'has_archive' => true,
				'hierarquical' => true,
				'public' => true,
				'show_in_menu' => false,
				'supports' => false
			)
		);
	}

	public function cm_cpt_custom_fields()
	{
		add_meta_box('cm_person_id', 'ID', array($this, 'add_person_id_html'), 'cm_person');
		add_meta_box('cm_person_name', 'Name', array($this, 'add_name_html'), 'cm_person');
		add_meta_box('cm_person_email', 'Email address', array($this, 'add_email_html'), 'cm_person');
		add_meta_box('cm_person_contacts', 'Contacts', array($this, 'add_contacts_html'), 'cm_person');

		add_meta_box('cm_contact_person_id', 'ID', array($this, 'add_contact_person_id_html'), 'cm_contact');
		add_meta_box('cm_contact_id', 'ID', array($this, 'add_contact_id_html'), 'cm_contact');
		add_meta_box('cm_contact_country_code', 'Country Code', array($this, 'add_country_code_html'), 'cm_contact');
		add_meta_box('cm_contact_number', 'Number', array($this, 'add_number_html'), 'cm_contact');
	}

	public function add_person_id_html()
	{
		$value = null;
		global $post;

		if (isset($post))
			$value = $post->ID;
?>
		<input type="text" id="cm_person_id" name="cm_person_id" value="<?php echo esc_attr($value); ?>" size="25" disabled />
	<?php
	}

	public function add_name_html()
	{
		$value = null;
		global $post;

		if (isset($post)) {
			$value = get_post_meta($post->ID, 'cm_person_name', true);
		}
	?>
		<input type="text" id="cm_person_name" name="cm_person_name" value="<?php echo esc_attr($value); ?>" size="25" />
	<?php
	}

	public function add_email_html()
	{
		$value = null;
		global $post;

		if (isset($post))
			$value = get_post_meta($post->ID, 'cm_person_email', true);
	?>
		<input type="text" id="cm_person_email" name="cm_person_email" value="<?php echo esc_attr($value); ?>" size="25" />
	<?php
	}

	public function add_contacts_html()
	{
		$value = null;
		global $post;

		$query_args = array(
			'post_type' => 'cm_contact',
			'meta_query' => array(
				array(
					'key' => 'cm_contact_person_id',
					'value' => $post->ID
				)
			)
		);
		$query_contacts = new WP_Query($query_args); ?>

		<table>

			<?php while ($query_contacts->have_posts()) {
				$query_contacts->the_post();
				$contact_id = get_the_ID(); ?>

				<tr>
					<td><?php echo get_post_meta($contact_id, 'cm_contact_country_code', true); ?></td>
					<td><?php echo get_post_meta($contact_id, 'cm_contact_number', true); ?></td>
					<td><a href="<?php echo get_edit_post_link(); ?>">Edit</a></td>
					<td><a href="<?php echo get_delete_post_link(); ?>">Delete</a></td>
				</tr>
			<?php } ?>

		</table>
	<?php }

	public function add_contact_person_id_html()
	{
		$value = null;
		global $post;

		if (isset($_GET['cm_contact_person_id']))
			$value = $_GET['cm_contact_person_id'];
		else
			$value = get_post_meta($post->ID, 'cm_contact_person_id', true);
	?>
		<input type="text" id="cm_contact_person_id" name="cm_contact_person_id" value="<?php echo esc_attr($value); ?>" size="25" />
	<?php
	}

	public function add_contact_id_html()
	{
		$value = null;
		global $post;

		if (isset($post))
			$value = $post->ID;
	?>
		<input type="text" id="cm_contact_id" name="cm_contact_id" value="<?php echo esc_attr($value); ?>" size="25" disabled />
	<?php
	}

	public function add_country_code_html()
	{
		$value = null;
		global $post;

		if (isset($post)) {
			$value = get_post_meta($post->ID, 'cm_contact_country_code', true);
		}
	?>
		<input type="text" id="cm_contact_country_code" name="cm_contact_country_code" value="<?php echo esc_attr($value); ?>" size="25" />
	<?php
	}

	public function add_number_html()
	{
		$value = null;
		global $post;

		if (isset($post)) {
			$value = get_post_meta($post->ID, 'cm_contact_number', true);
		}
	?>
		<input type="text" id="cm_contact_number" name="cm_contact_number" value="<?php echo esc_attr($value); ?>" size="25" />
	<?php
	}

	public function cm_cpt_person_save($data)
	{
		if (isset($GLOBALS['post']) && $data['post_type'] == 'cm_person') {
			global $post;
			/* update_post_meta($post->ID, 'cm_person_id', sanitize_text_field($_POST['cm_person_id'])); */
			update_post_meta($post->ID, 'cm_person_name', sanitize_text_field($_POST['cm_person_name']));
			update_post_meta($post->ID, 'cm_person_email', sanitize_text_field($_POST['cm_person_email']));

			$data['post_title'] = sanitize_text_field($_POST['cm_person_name']);
		}

		return $data;
	}

	public function cm_cpt_contact_save($data)
	{
		if (isset($GLOBALS['post']) && $data['post_type'] == 'cm_contact') {
			global $post;
			update_post_meta($post->ID, 'cm_contact_person_id', sanitize_text_field($_POST['cm_contact_person_id']));
			update_post_meta($post->ID, 'cm_contact_country_code', sanitize_text_field($_POST['cm_contact_country_code']));
			update_post_meta($post->ID, 'cm_contact_number', sanitize_text_field($_POST['cm_contact_number']));

			/* $data['post_title'] = sanitize_text_field($_POST['cm_person_name']); */
		}

		return $data;
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles()
	{

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Contact_Management_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Contact_Management_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/contact-management-admin.css', array(), $this->version, 'all');
	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts()
	{

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Contact_Management_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Contact_Management_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/contact-management-admin.js', array('jquery'), $this->version, false);
	}

	public function cm_options_page()
	{
		add_menu_page(
			'Contact Management',
			'Contact Management Options',
			'manage_options',
			'contact-management',
			array($this, 'cm_options_page_html')
		);
	}

	public function cm_options_page_html()
	{
		if (!current_user_can('manage_options')) {
			return;
		} ?>

		<div class="wrap">
			<h1><?php echo esc_html(get_admin_page_title()); ?></h1>

			<a href="<?php echo admin_url('post-new.php?post_type=cm_person'); ?>">Add person</a>

			<table style="margin-top: 25px">
				<tr>
					<th style="text-align: left">Person name</th>
					<th style="text-align: left">Person email</th>
				</tr>

				<?php $query_persons = new WP_Query(array('post_type' => 'cm_person'));

				while ($query_persons->have_posts()) {
					$query_persons->the_post();
					$person_id = get_the_ID(); ?>

					<tr>
						<td><?php the_title(); ?></td>
						<td><?php echo get_post_meta($person_id, 'cm_person_email', true); ?></td>
						<td><a href="<?php echo get_edit_post_link(); ?>">Edit</a></td>
						<td><a href="<?php echo get_delete_post_link(); ?>">Delete</a></td>
						<td><a href="<?php echo admin_url('post-new.php?post_type=cm_contact&cm_contact_person_id=' . $person_id); ?>">Add contact</a></td>
					</tr>

				<?php } ?>
			</table>
		</div>
<?php }
}
