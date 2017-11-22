<?php
function stock_projects_shortcode($atts, $content=null){
    extract( shortcode_atts( array(
        'theme' => '1',
    ), $atts) );
    
    $project_categories = get_terms ('project_cat');
    $dynamic_number = rand(946532302365, 941652653421);
    $stock_projects_markup = '
        <script>
            jQuery(document).ready(function($){
                $(".stock-project-shorting li").click(function(){
                    $(".stock-project-shorting li").removeClass("active");
                    $(this).addClass("active");
                    var selector = $(this).attr("data-filter");
                    $(".project-list'. $dynamic_number .'").isotope({
                       filter: selector,
                    });
                });
            });
            jQuery(window).load(function(){
                jQuery(".project-list'. $dynamic_number .'").isotope();
            });
        </script>
        <div class="row">';
    if($theme == '1'){
        $stock_projects_markup .= '
        <div class="col-md-3">';
    } else {
        $stock_projects_markup .= '
        <div class="col-md-12">';
    }
    $stock_projects_markup .= '
                <ul class="stock-project-shorting stock-project-shorting-'. $theme .'">
                <li class="active" data-filter="*">All Works</li>';
    if(!empty($project_categories) && ! is_wp_error($project_categories)){
        foreach($project_categories as $category){
            $stock_projects_markup .= '<li data-filter="'. $category->slug .'">'. $category->name .'</li>';
        }
    }
    $stock_projects_markup .= '
                </ul>';
    $stock_projects_markup .= '
            </div>';
    if( $theme == 1) {
        $project_column_width = 'col-md-9';
        $project_inner_column_width = 'col-md-4';
    } else {
        $project_column_width = 'col-md-12';
        $project_inner_column_width = 'col-md-3';
    }
    $stock_projects_markup .= '
            <div class="'. $project_column_width .'">';
            $stock_projects_markup .= '
                <div class="row project-list-'. $dynamic_number .'">';
    $projects_array = $q = new WP_Query( array('posts_per_page' => -1, 'post_type' => 'project'));
    while($projects_array->have_posts()) : $projects_array->the_post();
        $project_ctagory = get_the_terms( get_the_ID(), 'project_cat');
        if($project_ctagory && ! is_wp_error($project_ctagory)) {
            $project_cat_list = array();
            foreach($project_ctagory as $category){
                $project_cat_list[] = $category-> slug;
            }
            $project_assigned_cat = join( " ", $project_cat_list );
        } else {
            $project_assigned_cat = '';
        }
        $stock_projects_markup .= '
                        <div class="'. $project_inner_column_width .' '. $project_assigned_cat .'">
                            <a href="'. get_permalink() .'" class="single-work-box">
                                <div class="work-box-bg" style="background-image: url('. get_the_post_thumbnail_url(get_the_ID(),'large') .')"><i class="fa fa-link"></i></div>
                                <p>'. get_the_title() .'</p>
                            </a>
                        </div>';
    endwhile;
    wp_reset_query();
    $stock_projects_markup .= '
                </div>
            </div>
        </div>
    ';
    return $stock_projects_markup;
}
add_shortcode('stock_projects', 'stock_projects_shortcode');
?>