<?php
/**
 * Plugin Name: WordPress Widget Embed
 * Description: Allow people to embed WordPress content in an iframe on other websites
 * Version: 1.0
 * Author: Wajiha Rizvi  
 * Author URI: https://profiles.wordpress.org/wajiharizvi29
 */

class WPWidgetEmbed  
{
    public function __construct()
    {
        add_action('template_redirect', array($this, 'catch_widget_query'));
        add_action('init', array($this, 'widget_add_vars'));
    }
    
    /**
     * Adds our widget query variable to WordPress $vars
     */
    public function widget_add_vars() 
    { 
        global $wp; 
        $wp->add_query_var('em_embed'); 
        $wp->add_query_var('em_domain'); 
    }
    
    private function export_posts()
    {
        $outstring  = '<html>';
        $outstring .= '<head><style>';
        $outstring .= 'ul {
                padding:0;
                margin:0;
              }
              li {
                 list-style-type:none;
               }';
        $outstring .= '</style></head><body>';
         
        /* Here we get recent posts for the blog */
        $args = array(
            'numberposts' => 6,
            'offset' => 0,
            'category' => 0,
            'orderby' => 'post_date',
            'order' => 'DESC',
            'post_type' => 'post',
            'post_status' => 'publish',
            'suppress_filters' => true
        );
        
        $recent_posts = wp_get_recent_posts($args);
        
        $outstring .= '<div class="widget-posts"><ul>';
        foreach($recent_posts as $recent)
        {
            $outstring .= '<li><a target="_blank" href="' . get_permalink($recent["ID"]) . '">' . $recent["post_title"]. '</a></li>';
        }
        
        $outstring .= '</ul></div>';
        $outstring .= '</body></html>';
        
        return $outstring;
    }

    /**
     * Catches our query variable. If it's there, we'll stop the
     * rest of WordPress from loading and do our thing, whatever 
     * that may be. 
     */
    public function catch_widget_query()
    {
        /* If no 'embed' parameter found, return */
        if(!get_query_var('em_embed')) return;
        
        /* 'embed' variable is set, export any content you like */
        
        if(get_query_var('em_embed') == 'posts')
        { 
            $data_to_embed = $this->export_posts();
            echo $data_to_embed;
        }
        
        exit();
    }
}
 
$widget = new WPWidgetEmbed();

?>
