<?php
   /*
   Plugin Name: Author Stat Counter
   Plugin URI: http://www.ideaboxthemes.com
   Description: A plugin to count post views by an author and display popular posts using a sidebar widget or shortcode
   Version: 1.0
   Author: Saumya Sharma
   Author URI: http://ideaboxthemes.com
   License: GPL2 or later
   License URI: http://www.gnu.org/licenses/gpl-2.0.html
   */
?>
<?php
//add_action( 'admin_init', 'asc_show_views' );
//add_action( 'wp_head','asc_show_views');
add_action( 'wp_head','asc_add_view');
//add_action( 'init', 'register_shortcodes');

//function register_shortcodes(){
  //add_shortcode('posts', 'asc_add_view');
  //add_shortcode('pst', 'asc_popularity_list');
//}
function asc_add_view(){
    if(is_single()){
        global $post;    
        $current_views=get_post_meta($post->ID, "asc_views", true);
        if(!isset($current_views) OR empty($current_views) OR !is_numeric($current_views) ) {
            $current_views = 0;
        }
        $new_views = $current_views + 1;
        update_post_meta($post->ID, "asc_views", $new_views);
        return $new_views;
    }
}
function asc_get_view_count() {
    global $post;            
    $current_views = get_post_meta($post->ID, "asc_views", true);
    if(!isset($current_views) OR empty($current_views) OR !is_numeric($current_views) ) {
        $current_views = 0;
    }
    return $current_views;
}
function asc_get_author_id( $post_id = 0 ){
    $post = get_post( $post_id );
    $auth_id=$post->post_author;
    echo '<a href="'.get_author_posts_url(get_the_author_meta( 'ID' )).'">'.get_the_author_meta( 'user_firstname', $auth_id)." ".get_the_author_meta( 'user_lastname', $auth_id).'</a>';
}
function asc_get_author_details($post_id=0){
    $post = get_post( $post_id );
    $auth_id=$post->post_author;
    echo get_the_author_meta( 'description', $auth_id);   
}
function asc_show_author_post_view($post_count,$post_id=0){
    $post = get_post( $post_id );
    $auth_id=$post->post_author;
    $auth_list=new WP_Query(
            array(
                'author'=>$auth_id,
                "posts_per_page" => $post_count,
                "meta_key" => "asc_views",
                "orderby"=>'meta_value_num',
                "order" => "DESC"
            ));
    if($auth_list->have_posts()){
        //echo '<a href="'.get_author_posts_url(get_the_author_meta( 'ID' )).'">'.get_the_author_meta( 'user_firstname', $auth_id)." ".get_the_author_meta( 'user_lastname', $auth_id).'</a>'."<br>".get_the_author_meta('description',$auth_id);
        while ( $auth_list->have_posts() ) : $auth_list->the_post();
            echo "<div class='auth-post-list'>"; 
                echo"<div class='feature_thumbnail'>";
                    the_post_thumbnail('featured-thumb');
                echo "</div>";
                echo "<div class='post_title'>".'<a href="'.get_permalink().'">'.the_title('', '', false).'</a>'.'<br>'."</div>";
                echo "<div class='post_comments'>";
                    echo comments_number();
                echo "</div>";
            echo "</div>";
        endwhile;
    }
}
function asc_show_author_post_comment($post_count,$post_id=0){
    $post = get_post( $post_id );
    $auth_id=$post->post_author;
    $auth_list=new WP_Query(
            array(
                'author'=>$auth_id,
                "posts_per_page" => $post_count,
                "orderby"=>'comment_count',
                "order" => "DESC"
            ));
    if($auth_list->have_posts()){
        //echo '<a href="'.get_author_posts_url(get_the_author_meta( 'ID' )).'">'.get_the_author_meta( 'user_firstname', $auth_id)." ".get_the_author_meta( 'user_lastname', $auth_id).'</a>'."<br>".get_the_author_meta('description',$auth_id);
        while ( $auth_list->have_posts() ) : $auth_list->the_post();
            echo "<div class='auth-post-list'>"; 
                echo"<div class='feature_thumbnail'>";
                    the_post_thumbnail('featured-thumb');
                echo "</div>";
                echo "<div class='post_title'>".'<a href="'.get_permalink().'">'.the_title('', '', false).'</a>'.'<br>'."</div>";
                echo "<div class='post_comments'>";
                    echo comments_number();
                echo "</div>";
            echo "</div>";
        endwhile;
    }
}
function asc_show_views($singular = "view", $plural = "views", $before = "This post has: ") {
    global $post;
    // asc_add_view();
    $current_views = get_post_meta($post->ID, "asc_views", true);  
    $views_text = $before . $current_views . " ";
    if ($current_views == 1) {
        $views_text .= $singular;
    }
    else {
        $views_text .= $plural;
    }
    echo $views_text."<br>";
    echo "<div class='post_comments'>";
        echo comments_number();
    echo "</div>";
}
function asc_post_popularity_list_views($post_count) {
    $args = array(
        "posts_per_page" => $post_count,
        "post_type" => "post",
        "post_status" => "publish",
        "meta_key" => "asc_views",
        "orderby" => "meta_value_num",
        "order" => "DESC"
    );
    global $post;
    $asc_list = new WP_Query($args);
    if($asc_list->have_posts()) { echo "<ul class='post_pop_list'>"; }   
        while ( $asc_list->have_posts() ) : $asc_list->the_post();                
            echo '<li class="post_item"><a href="'.get_permalink($post->ID).'">'.the_title('', '', false).'</a></li>';
            echo "<div class='view_count'>";
                asc_show_views();
            echo "</div>";
        endwhile;
	if($asc_list->have_posts()) { echo "</ul>";}
}
function asc_post_popularity_list_comments($post_count) {
    $args = array(
        "posts_per_page" => $post_count,
	"post_type" => "post",
	"post_status" => "publish",
	"orderby" => "comment_count",
	"order" => "DESC"
    );
    global $post;
    $asc_list = new WP_Query($args);
    if($asc_list->have_posts()) { echo "<ul>"; }
        while ( $asc_list->have_posts() ) : $asc_list->the_post();                                     
            echo '<li class="post_item"><a href="'.get_permalink($post->ID).'">'.the_title('', '', false).'</a></li>';
            echo "<div class='view_count'>";
                asc_show_views();
            echo "</div>";       
	endwhile;
	if($asc_list->have_posts()) { echo "</ul>";}
}

include 'author-stat-widget.php';
include 'post-stat-widget.php';
add_action('widgets_init',create_function('', 'return register_widget("Post_Stats_Counter");'));
add_action('widgets_init',create_function('', 'return register_widget("Author_Stats_Counter");'));
?>