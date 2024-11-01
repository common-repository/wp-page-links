<?php
/*
Plugin Name: WP Page Links
Plugin URI: http://www.3MergingDesign.com
Description: WP Page Links allows you to choose a number of page links to display.
Author: 3 Merging Design
Version: 1.0
Author URI: http://www.3MergingDesign.com
*/

class WP_PageLinks extends WP_Widget {

	function WP_PageLinks() {
		/*Widget Settings */
		$widget_ops = array(
			'classname' => 'wp-pagelinks',
			'description' => 'WP Page Links allows you to choose a number of page links to display.');
		
		/*Widget Control Settings */
		$control_ops = array(
			'width' => 250,
			'height' => 250,
			'id_base' => 'wp-pagelinks-widget');
		
		/* Create the widget */
		$this->WP_Widget('wp-pagelinks-widget', 'WP Page Links', $widget_ops, $control_ops);
	}
	
	function form ($instance){
		/* Setup default widget settings */
		$defaults = array('numberpages' => '5','catid'=>'1','title'=>'','displayorder'=>'post_date','listdirection'=>'desc','excludepages'=>'','showlove'=>'');
		$instance = wp_parse_args( (array) $instance, $defaults ); ?>
	<p>
		<label for="<?php echo $this->get_field_id('title'); ?>">Title:</label>
		<input type="text" name="<?php echo $this->get_field_name('title') ?>" id="<?php echo $this->get_field_id('title') ?> " value="<?php echo $instance['title'] ?>" size="20">
	</p>
	
	<p>
		<label for="<?php echo $this->get_field_id('numberpages'); ?>">Number of pages:</label>
		<select style="width: 100px;" id="<?php echo $this->get_field_id('numberpages'); ?>" name="<?php echo $this->get_field_name('numberpages'); ?>">
		<?php for ($i=1;$i<=20;$i++) {
				echo '<option value="'.$i.'"';
				if ($i==$instance['numberpages']) echo ' selected="selected"';
				echo '>'.$i.'</option>';
			} ?>
		</select>
	</p>
	
	<p>
		<label for="<?php echo $this->get_field_id('displayorder'); ?>">Display Options:</label>
		<select style="width: 110px;" id="<?php echo $this->get_field_id('displayorder'); ?>" name="<?php echo $this->get_field_name('displayorder'); ?>">
			<option value="post_date" <?php if ( 'post_date' == $instance['displayorder'] ) echo ' selected="selected"'; ?>>Date Posted</option>
			<option value="post_modified" <?php if ( 'post_modified' == $instance['displayorder'] ) echo ' selected="selected"'; ?>>Date Modified</option>
			<option value="post_title" <?php if ( 'post_title' == $instance['displayorder'] ) echo ' selected="selected"'; ?>>Page Title</option>
			<option value="menu_order" <?php if ( 'menu_order' == $instance['displayorder'] ) echo ' selected="selected"'; ?>>Page Order</option>
			<option value="post_name" <?php if ( 'post_name' == $instance['displayorder'] ) echo ' selected="selected"'; ?>>Page Slug</option>
		</select>
	</p>
	
	<p>
		<label for="<?php echo $this->get_field_id('listdirection'); ?>">List Options:</label>
		<select id="<?php echo $this->get_field_id('listdirection'); ?>" name="<?php echo $this->get_field_name('listdirection'); ?>">
			<option value="asc" <?php if ( 'asc' == $instance['listdirection'] ) echo ' selected="selected"'; ?>>Lowest to Highest</option>
			<option value="desc" <?php if ( 'desc' == $instance['listdirection'] ) echo ' selected="selected"'; ?>>Highest to Lowest</option>
		</select>
	</p>
	
	<p>
		<label for="<?php echo $this->get_field_id('excludepages'); ?>">Exclude Pages:</label>
		<input type="text" name="<?php echo $this->get_field_name('excludepages') ?>" id="<?php echo $this->get_field_id('excludepages') ?> " value="<?php echo $instance['excludepages'] ?>" size="20" style="width: 130px; float:right;">
	</p>
	
	<p>
		<label for="<?php echo $this->get_field_id('showlove'); ?>">Show WP Page Links some love?</label>
		<input type="checkbox" id="<?php echo $this->get_field_id('showlove'); ?>" name="<?php echo $this->get_field_name('showlove'); ?>" <?php if ($instance['showlove']) echo 'checked="checked"' ?> />
	</p>
	
	<?php
}

function update ($new_instance, $old_instance) {
	$instance = $old_instance;

	$instance['numberpages'] = $new_instance['numberpages'];
	$instance['displayorder'] = $new_instance['displayorder'];
	$instance['title'] = $new_instance['title'];
	$instance['showlove'] = $new_instance['showlove'];
	$instance['listdirection'] = $new_instance['listdirection'];
	$instance['excludepages'] = $new_instance['excludepages'];
	
	return $instance;
}

function widget ($args,$instance) {
	extract($args);
	
	$title = $instance['title'];
	$numberpages = $instance['numberpages'];
	$displayorder = $instance['displayorder'];
	$showlove = $instance['showlove'];
	$listdirection = $instance['listdirection'];
	$excludepages = $instance['excludepages'];
	
	// retrieve posts information from database
	global $wpdb;
	$pages = wp_list_pages('title_li=&sort_column='.$displayorder.'&sort_order='.$listdirection.'&depth=-1&echo=0&number='.$numberpages.'&exclude='.$excludepages);
	
	$out = '<ul>';
	$out .= $pages;
	if ($showlove) $out .= '<li><a href="http://wordpress.org/extend/plugins/profile/3mergingdesign">Powered by 3MD.com</a></li>';
	$out .= '</ul>';
	
	//print the widget for the sidebar
	echo $before_widget;
	echo $before_title.$title.$after_title;
	echo $out;
	echo $after_widget;
	}
}

function wp_pagelinks_load_widgets() {
	register_widget('WP_PageLinks');
}

add_action('widgets_init', 'wp_pagelinks_load_widgets');

?>