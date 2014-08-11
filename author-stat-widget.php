<?php
class Author_Stats_Counter extends WP_Widget {
	// Controller
	function __construct() {
	$widget_ops = array('classname' => 'author_widget', 'description' => __('Displays the details of the author related to a single post and other posts by the same author'));
	$control_ops = array('width' => 300, 'height' => 300);
	parent::WP_Widget(false, $name = __('Author Stats'), $widget_ops, $control_ops );

        }

function form($instance) { 
	$defaults = array( 'title' => __('Author Details'), 'Enter no of posts to display' => __('5'));
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
                <input class="checkbox" type="checkbox" <?php checked($instance['show_post_checkbox'], 'on'); ?> id="<?php echo $this->get_field_id('show_post_checkbox'); ?>" name="<?php echo $this->get_field_name('show_post_checkbox'); ?>" /> 
                <label for="<?php echo $this->get_field_id('show_post_checkbox'); ?>">Show Top posts by author</label>
        </p>
        <p>
		<label for="<?php echo $this->get_field_id('post_count'); ?>"><?php _e('No. of Posts:', 'wp_widget_plugin'); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id('post_count'); ?>" name="<?php echo $this->get_field_name('post_count'); ?>" type="number" value="<?php echo $post_count;?>" >
	</p>
        <p>                
                <input class="checkbox" type="checkbox" <?php checked($instance['show_details_checkbox'], 'on'); ?> id="<?php echo $this->get_field_id('show_details_checkbox'); ?>" name="<?php echo $this->get_field_name('show_details_checkbox'); ?>" /> 
                <label for="<?php echo $this->get_field_id('show_details_checkbox'); ?>">Show Author Description</label>        
        </p>
        <p>
            Select the way you want to sort the posts.
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
    $instance['show_post_checkbox']=strip_tags($new_instance['show_post_checkbox']);
    $instance['show_details_checkbox']=strip_tags($new_instance['show_details_checkbox']);
    $instance['sort_radiobox']=strip_tags($new_instance['sort_radiobox']);
    return $instance;
}

function widget($args, $instance) {
        $title = apply_filters('widget_title', $instance['title']);
        // Display the widget title
        echo "<div class='auth-wid widget'>";
        if(is_single()){
            if ( $title ){
                echo "<h3 class='widget-title'>".$title."</h3>";
            }
        
            if(function_exists("asc_get_author_id")){
                echo "<div class='author_id'>";
                echo "By: ";
                asc_get_author_id();
                echo "<br>";
                echo "</div>";
            }
            if($instance['show_post_checkbox'])
            {
        	if (function_exists("asc_show_author_post_view")) 
                    {
                        if($instance['post_count']){
                            $post_count = apply_filters('widget_title',$instance['post_count']); 
                        }
                        else{
                            $post_count= 5;
                        }
                        echo "<div class='author_post_list'>";
                        echo "<p>".'More posts by the author:'."</p><br>";
                        if($instance['sort_radiobox']=="views")
                            asc_show_author_post_view($post_count);
                        else
                            asc_show_author_post_comment($post_count);
                    }
                    echo "</div>";
            }
            if($instance['show_details_checkbox']){
                echo "<div class='author_description'>";
                echo "<p>".'Author Description: '."</p>";
                asc_get_author_details();
                echo "</div>";
            }
	}
        echo "</div>";
}
}