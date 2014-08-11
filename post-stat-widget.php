<?php
class Post_Stats_Counter extends WP_Widget {
	// Controller
	function __construct() {
	$widget_ops = array('classname' => 'post_count_widget', 'description' => __('Displays the number of times a post has been viewed'));
	$control_ops = array('width' => 300, 'height' => 300);
	parent::WP_Widget(false, $name = __('Post Stats Counter'), $widget_ops, $control_ops );
?>
<?php
}

function form($instance) { 
	$defaults = array( 'title' => __('Popular Posts'), 'post_count' => __('5'));
	$instance = wp_parse_args( (array) $instance, $defaults ); 

	if ( isset( $instance[ 'title' ] ) ) {
			$title = $instance[ 'title' ];
			$post_count=$instance['post_count'];
		}
	else {
			$title =$defaults['title'];
			$post_count=$defaults['post_count'];
		}?>
	<p>
                <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title', 'wp_widget_plugin'); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
	</p>
	<p>
		<label for="<?php echo $this->get_field_id('post_count'); ?>"><?php _e('Enter no of posts :', 'wp_widget_plugin'); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id('post_count'); ?>" name="<?php echo $this->get_field_name('post_count'); ?>" type="number" value="<?php echo $post_count;?>" />
	</p>
        <p>            
                <input type="radio"
                id="<?php echo $this->get_field_id('sort_radiobox'); ?>"
                name="<?php echo $this->get_field_name('sort_radiobox'); ?>"
                <?php if (isset($instance['sort_radiobox']) && $instance['sort_radiobox']=="views") echo "checked";?>
                       value="views">Sort by views<br>
                <input type="radio"
                id="<?php echo $this->get_field_id('sort_radiobox'); ?>"
                name="<?php echo $this->get_field_name('sort_radiobox'); ?>"
                <?php if (isset($instance['sort_radiobox']) && $instance['sort_radiobox']=="comments") echo "checked";?>
                value="comments">Sort by comments
        </p>

<?php }
function update($new_instance,$old_instance){
    $instance = $old_instance;
    $instance['title'] = strip_tags( $new_instance['title'] );
    $instance['post_count'] = strip_tags( $new_instance['post_count'] );
    $instance['sort_radiobox'] = strip_tags( $new_instance['sort_radiobox'] );
    return $instance;
}

function widget($args, $instance) {
    	$title = apply_filters('widget_title', $instance['title']);
        echo "<div class='widget post-wid'>";
        if ( $title ){
            echo "<h3 class='widget-title'>".$title."</h3>";
        }        
        if (function_exists("asc_post_popularity_list_views")) {
        $post_count = $instance['post_count'];
        //Display the widget title	
	//Display the name 	
        //asc_post_popularity_list($post_count);
        $instance['sort_radiobox']="views";
        echo "<div class='post_popularity_list'>";
        if($instance['sort_radiobox']=="views")
             asc_post_popularity_list_views($post_count);
        else
             asc_post_popularity_list_comments($post_count);
        }
        echo "</div>";
    echo "</div>";
    }
}