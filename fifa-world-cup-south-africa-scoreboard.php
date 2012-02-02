<?php

/*
Plugin Name: FIFA World Cup South Africa scoreboard.
Description: Get the latest results of 2010 FIFA World Cup South Africa™. Use [wp_fifa_world_cup_scoreboard] into a post or a page and/or use the sidebar widget.
Plugin URI: http://nomikos.info/2010/06/10/fifa-world-cup-south-africa-scoreboard-wp-plugin.html
Version: 2.1
Author: NomikOS
Author URI: http://www.rentacoder.com/RentACoder/DotNet/SoftwareCoders/ShowBioInfo.aspx?lngAuthorId=7064234
*/

define( 'NOMIKOS_FIFA_WORLD_CUP_SCOREBOARD_PLUGIN_DIR', WP_PLUGIN_DIR . '/fifa-world-cup-south-africa-scoreboard' );
define( 'NOMIKOS_FIFA_WORLD_CUP_SCOREBOARD_PLUGIN_URL', plugins_url( $path = '/fifa-world-cup-south-africa-scoreboard' ) );

class nomikos_fifa_world_cup_scoreboard_class
{
    function nomikos_fifa_world_cup_scoreboard_class()
    {
        $this->scrape_url[1]                = 'http://www.fifa.com/worldcup/matches/groupstage.html';
        $this->scrape_url[2]                = 'http://www.fifa.com/worldcup/matches/index.html';
        $this->current_time_in_africa       = time() + 2 * 3600;
        $this->current_time_in_africa_mysql = date ('Y-m-d H:i:s', $this->current_time_in_africa);
        $this->results                      = array();
        $this->alert_live_flag              = 0;
    }

    # initialited only from shortcode or widget
    # -----------------------------------------
    function init()
    {
        static $ready_here = 0;

        if ($ready_here)
            return;

        $this->options = get_option('nomikos_fifa_world_cup_scoreboard_options');

        if (is_array($this->options->match_times))
        {
            foreach ($this->options->match_times as $PARTY_TIME_START)
            {
                # playing now? giving 2 hours per match
                # -------------------------------------
                if ( $PARTY_TIME_START < $this->current_time_in_africa
                && $this->current_time_in_africa < $PARTY_TIME_START + 2 * 3600 )
                {
                    $this->alert_live_flag = 1;
                    break;
                }
            }
        }

        $LETS_SCRAPE = 0;
        if ( $this->options->match_scraped )
        {
            foreach ($this->options->match_scraped as $PARTY_NUMBER => $PARTY_DATA)
            {
                $PARTY_TIME_START = $PARTY_DATA['date'];
                $PARTY_SCRAPED    = $PARTY_DATA['scraped'];

                if ( $PARTY_TIME_START + 2 * 3600 < $this->current_time_in_africa
                && ! $PARTY_SCRAPED )
                {
                    $LETS_SCRAPE = 1;
                    break;
                }
            }
        }
        else
            # i am running for first time
            # ---------------------------
            $LETS_SCRAPE = 1;

        # scrape only if the players are in the field
        # -------------------------------------------
        if ($LETS_SCRAPE || $_GET['nomikos_refresh'])
        {
            # let's scrape
            # ------------
            include (NOMIKOS_FIFA_WORLD_CUP_SCOREBOARD_PLUGIN_DIR . '/php/scrape.php');

            if ($results1 || $results2)
            {
                $this->options->results1                    = $results1;
                $this->options->results2                    = $results2;
                $this->options->last_scrape_match_timestamp = $last_scrape_match_timestamp;

                sort($match_scraped);
                $this->options->match_scraped    = $match_scraped;
                $this->options->last_scrape_date = $this->current_time_in_africa;

                # set this data only once
                # -----------------------
                if ( ! $this->options->match_times)
                {
                    sort($match_times);
                    sort($match_times_mysql);

                    $this->options->match_times       = $match_times;
                    $this->options->match_times_mysql = $match_times_mysql;
                }

                update_option('nomikos_fifa_world_cup_scoreboard_options', $this->options);
            }
        }

        $ready_here = 1;
    }

    function get_unit($string, $start, $end)
    {
        if (($pos = stripos($string, $start)) === false)
            return '';

        $str = substr($string, $pos);
        $str_two = substr($str, strlen($start));


        if (($second_pos = stripos($str_two, $end)) === false)
            return '';

        $str_three = substr($str_two, 0, $second_pos);
        return trim($str_three);
    }

    function staticbar()
    {
        global $nomikos_fifa_world_cup_scoreboard_class;
        include (NOMIKOS_FIFA_WORLD_CUP_SCOREBOARD_PLUGIN_DIR . '/templates/staticbar.tpl');
    }

    function draw_page()
    {
        $this->init();
        include (NOMIKOS_FIFA_WORLD_CUP_SCOREBOARD_PLUGIN_DIR . '/templates/page.tpl');
    }

    function header_scripts()
    {
        wp_enqueue_script('jquery');
        wp_enqueue_script('nomikos_fifa_world_cup_scoreboard_js_cookie', NOMIKOS_FIFA_WORLD_CUP_SCOREBOARD_PLUGIN_URL.'/js/jquery.cookie.js', array('jquery'));
        wp_enqueue_script('nomikos_fifa_world_cup_scoreboard_js_countdown', NOMIKOS_FIFA_WORLD_CUP_SCOREBOARD_PLUGIN_URL.'/js/jquery.countdown.js', array('jquery'));
        wp_enqueue_script('nomikos_fifa_world_cup_scoreboard_js_common', NOMIKOS_FIFA_WORLD_CUP_SCOREBOARD_PLUGIN_URL.'/js/common.js', array('jquery'));
    }

    function header_styles()
    {
        wp_enqueue_style('nomikos_fifa_world_cup_scoreboard_css_countdown', NOMIKOS_FIFA_WORLD_CUP_SCOREBOARD_PLUGIN_URL.'/css/jquery.countdown.css');
        wp_enqueue_style('nomikos_fifa_world_cup_scoreboard_css_common', NOMIKOS_FIFA_WORLD_CUP_SCOREBOARD_PLUGIN_URL.'/css/common.css');
    }

    function error($log = '')
    {
        $debug = strstr($_SERVER['SERVER_ADMIN'], '@nomikos.info') ? 1 : 0;

        $c       = date('c', time());
        $bt      = debug_backtrace();

        $output  = "[log $c\n";
        $output .= 'file : ' . $bt[0]['file'] . "\n";
        $output .= 'line : ' . $bt[0]['line'] . "\n";
        $output .= 'log  : ' . $log . "\n";

        if ($debug)
        {
            echo str_replace("\n", "<br />\n", $output);
        }
        else
        {
            $fp = @fopen(NOMIKOS_FIFA_WORLD_CUP_SCOREBOARD_PLUGIN_DIR . '/log/log.txt', 'a');
            @fwrite($fp, $output);
            @fclose($fp);
        }
    }

    # plugin install && uninstall
    # ---------------------------
    function install()
    {
        $this->options = null;
        $this->options->options->topbar      = 1;
        $this->options->options->text1       = 1;
        $this->options->options->text2       = 1;
        $this->options->options->text3       = 1;
        $this->options->show_results->number = 1;
        $this->options->show_results->date   = 1;
        update_option('nomikos_fifa_world_cup_scoreboard_options', $this->options);
    }

    function uninstall()
    {
        delete_option('nomikos_fifa_world_cup_scoreboard_options');
    }

    # admin panel options
    # -------------------
    function admin_options()
    {
        add_options_page('fifa world cup south africa scoreboard', 'FIFA World Cup South Africa scoreboard', 'manage_options', __FILE__, array($this, 'set_admin_options'));
    }

    function set_admin_options()
    {
        $this->options = get_option('nomikos_fifa_world_cup_scoreboard_options');

        if ($_POST['NOMIKOS_FIFA_WORLD_CUP_SCOREBOARD_SUBMIT'])
        {
            $this->options->options->topbar = $_POST['topbar'] ? 1 : 0;
            $this->options->options->text1 = $_POST['text1'] ? 1 : 0;
            $this->options->options->text2 = $_POST['text2'] ? 1 : 0;
            $this->options->options->text3 = $_POST['text3'] ? 1 : 0;

            $this->options->show_results->number = $_POST['number'] ? 1 : 0;
            $this->options->show_results->date   = $_POST['date'] ? 1 : 0;

            update_option('nomikos_fifa_world_cup_scoreboard_options', $this->options);

            ?>
            <div class="updated"><p>
            Update <b>successful</b>.
            </p></div>
            <?php
        }
    ?>
    <div class="wrap">
    <div id="icon-edit" class="icon32"></div>
    <h2>FIFA World Cup South Africa scoreboard</h2>
    <form name="NOMIKOS_FIFA_WORLD_CUP_SCOREBOARD_FORM" method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
    <input type="hidden" name="NOMIKOS_FIFA_WORLD_CUP_SCOREBOARD_SUBMIT" value="1">
    <p><b>Results in page/post</b>:<i></p>
    <p></p>
    <label>
    <input name="number" class="checkbox" type="checkbox" <?php echo $this->options->show_results->number ? 'checked' : '' ?>/> Show match number (first column)</label><br />
    <p></p>
    <label>
    <input name="date" class="checkbox" type="checkbox" <?php echo $this->options->show_results->date ? 'checked' : '' ?>/> Show match date (second column)</label><br />
    <p><b>Top bar</b>:<i></p>
    <p></p>
    <label>
    <input name="topbar" class="checkbox" type="checkbox" <?php echo $this->options->options->topbar ? 'checked' : '' ?>/> Show top bar</label><br />
    <div id="topbar_options" style="display:<?php echo $this->options->options->topbar ? 'block' : 'none' ?>">
    <p></p>
    <label>
    <input name="text1" class="checkbox" type="checkbox" <?php echo $this->options->options->text1 ? 'checked' : '' ?>/> Show "FIFA World Cup South Africa Scoreboard"</label><br />
    <p></p>
    <label>
    <input name="text2" class="checkbox" type="checkbox" <?php echo $this->options->options->text2 ? 'checked' : '' ?>/> Show "go to FIFA World Cup™ official website"</label><br />
    <p></p>
    <label>
    <input name="text3" class="checkbox" type="checkbox" <?php echo $this->options->options->text3 ? 'checked' : '' ?>/> Show "&copy;"
    <?php echo $this->options->options->text3 ? $_POST ? '( :) good thinking... o/\o high five! )' : '( ..;V{^^}_b that\'s cool partner! )' : '( :Þ )' ?>
    </label>
    </div>
    <p class="submit">
    <input type="submit" name="Submit" value="Update Options" />
    </p>
    </form>

    <?php
    }
}

# widget stuff
# ------------

function nomikos_fifa_world_cup_scoreboard_widget_manual($args = null)
{
    global $nomikos_fifa_world_cup_scoreboard_class;

    $nomikos_fifa_world_cup_scoreboard_class->init();

    if ( is_null($args) )
    {
        $random = rand(1, 8);
        $new_instance['title']    = '';
        $new_instance['date']     = 1;
        $new_instance['one_row']  = 0;
        $new_instance['schedule'] = 1;

        for ( $i = 0; $i < 8; $i++ )
        {
            $gletter = chr($i + 65);
            if ( $i == $random )
                $new_instance[$gletter] = 1;
            else
                $new_instance[$gletter] = 0;
        }
    }
    else
    {
        $atts = explode( '&', trim( $args ) );
        foreach ($atts as $att)
        {
            $at = explode( '=', trim( $att ) );
            $new_instance[strtolower($at[0])] = $at[1] ? 1 : 0;
        }

        $new_instance['title']   = '';
    }

    $instance['title']    = htmlspecialchars(strip_tags(trim($new_instance['title'])));
    $instance['date']     = $new_instance['date']     ? 1 : 0;
    $instance['one_row']  = $new_instance['one_row']  ? 1 : 0;
    $instance['schedule'] = $new_instance['schedule'] ? 1 : 0;
    $instance['a']        = $new_instance['a'] ? $new_instance['a'] : 0;
    $instance['b']        = $new_instance['b'] ? $new_instance['b'] : 0;
    $instance['c']        = $new_instance['c'] ? $new_instance['c'] : 0;
    $instance['d']        = $new_instance['d'] ? $new_instance['d'] : 0;
    $instance['e']        = $new_instance['e'] ? $new_instance['e'] : 0;
    $instance['f']        = $new_instance['f'] ? $new_instance['f'] : 0;
    $instance['g']        = $new_instance['g'] ? $new_instance['g'] : 0;
    $instance['h']        = $new_instance['h'] ? $new_instance['h'] : 0;

    include (NOMIKOS_FIFA_WORLD_CUP_SCOREBOARD_PLUGIN_DIR . '/templates/sidebar.tpl');
}

class nomikos_fifa_world_cup_scoreboard_widget_init extends wp_widget
{
    function nomikos_fifa_world_cup_scoreboard_widget_init()
    {
        $widget_ops = array('classname' => 'nomikos_fifa_world_cup_scoreboard_widget', 'description' => __( 'Get the latest results of 2010 FIFA World Cup South Africa™', 'nomikos_fifa_world_cup_scoreboard_widget2') );
        $this->WP_Widget('nomikos_fifa_world_cup_scoreboard-widget', __('FIFA World Cup', 'nomikos_fifa_world_cup_scoreboard_widget2'), $widget_ops);
    }

    function widget($args, $instance)
    {
        global $nomikos_fifa_world_cup_scoreboard_class;

        $nomikos_fifa_world_cup_scoreboard_class->init();

        extract($args);
        $title = $instance['title'] ? apply_filters('widget_title', $instance['title']) : '';

        echo $before_widget;
        echo $before_title . $title . $after_title;
        include (NOMIKOS_FIFA_WORLD_CUP_SCOREBOARD_PLUGIN_DIR . '/templates/sidebar.tpl');
        echo $after_widget;
    }

    function update( $new_instance, $old_instance )
    {
        $instance = $old_instance;

        $instance['title']    = strip_tags($new_instance['title']);
        $instance['date']     = $new_instance['date']    ? 1 : 0;
        $instance['one_row']  = $new_instance['one_row'] ? 1 : 0;
        $instance['schedule'] = $new_instance['schedule'] ? 1 : 0;
        $instance['a']        = $new_instance['a'] ? $new_instance['a'] : 0;
        $instance['b']        = $new_instance['b'] ? $new_instance['b'] : 0;
        $instance['c']        = $new_instance['c'] ? $new_instance['c'] : 0;
        $instance['d']        = $new_instance['d'] ? $new_instance['d'] : 0;
        $instance['e']        = $new_instance['e'] ? $new_instance['e'] : 0;
        $instance['f']        = $new_instance['f'] ? $new_instance['f'] : 0;
        $instance['g']        = $new_instance['g'] ? $new_instance['g'] : 0;
        $instance['h']        = $new_instance['h'] ? $new_instance['h'] : 0;

        return $instance;
    }

    function form( $instance )
    {
        $title = attribute_escape($instance['title']);
    ?>
        <p><label for="<?php echo $this->get_field_id('title'); ?>">Title:<i> (not required)</i></p>
        <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" /></label></p>
        <p></p>
        <p><b>Select groups</b>:<i></p>
        <label>
        <input id="<?php echo $this->get_field_id('a'); ?>" name="<?php echo $this->get_field_name('a'); ?>" class="checkbox" type="checkbox" <?php echo $instance['a'] ? 'checked' : '' ?>/> A </label><br />
        <label>
        <input id="<?php echo $this->get_field_id('b'); ?>" name="<?php echo $this->get_field_name('b'); ?>" class="checkbox" type="checkbox" <?php echo $instance['b'] ? 'checked' : '' ?>/> B </label><br />
        <label>
        <input id="<?php echo $this->get_field_id('c'); ?>" name="<?php echo $this->get_field_name('c'); ?>" class="checkbox" type="checkbox" <?php echo $instance['c'] ? 'checked' : '' ?>/> C </label><br />
        <label>
        <input id="<?php echo $this->get_field_id('d'); ?>" name="<?php echo $this->get_field_name('d'); ?>" class="checkbox" type="checkbox" <?php echo $instance['d'] ? 'checked' : '' ?>/> D </label><br />
        <label>
        <input id="<?php echo $this->get_field_id('e'); ?>" name="<?php echo $this->get_field_name('e'); ?>" class="checkbox" type="checkbox" <?php echo $instance['e'] ? 'checked' : '' ?>/> E </label><br />
        <label>
        <input id="<?php echo $this->get_field_id('f'); ?>" name="<?php echo $this->get_field_name('f'); ?>" class="checkbox" type="checkbox" <?php echo $instance['f'] ? 'checked' : '' ?>/> F </label><br />
        <label>
        <input id="<?php echo $this->get_field_id('g'); ?>" name="<?php echo $this->get_field_name('g'); ?>" class="checkbox" type="checkbox" <?php echo $instance['g'] ? 'checked' : '' ?>/> G </label><br />
        <label>
        <input id="<?php echo $this->get_field_id('h'); ?>" name="<?php echo $this->get_field_name('h'); ?>" class="checkbox" type="checkbox" <?php echo $instance['h'] ? 'checked' : '' ?>/> H </label><br />
        <p></p>
        <label>
        <input id="<?php echo $this->get_field_id('one_row'); ?>" name="<?php echo $this->get_field_name('one_row'); ?>" class="checkbox" type="checkbox" <?php echo $instance['one_row'] ? 'checked' : '' ?>/> Only one row by match </label><br />
        <p></p>
        <label>
        <input id="<?php echo $this->get_field_id('date'); ?>" name="<?php echo $this->get_field_name('date'); ?>" class="checkbox" type="checkbox" <?php echo $instance['date'] ? 'checked' : '' ?>/> Show date </label><br />
        <p></p>
        <label>
        <input id="<?php echo $this->get_field_id('schedule'); ?>" name="<?php echo $this->get_field_name('schedule'); ?>" class="checkbox" type="checkbox" <?php echo $instance['schedule'] ? 'checked' : '' ?>/> Show schedule </label><br />
    <?php
    }
}

if ( ! function_exists('d') )
{
    function d($var,  $exit = 0)
    {
        echo "\n\n\n<hr />[nomikos_degug_output_BEGIN]\n<pre>" . var_export($var, 1) . "\n</pre>[nomikos_degug_output_END]<hr />\n\n\n";
        if ($exit)
            exit;
    }
}

add_action('widgets_init', 'nomikos_fifa_world_cup_scoreboard_widget');
function nomikos_fifa_world_cup_scoreboard_widget()
{
    register_widget('nomikos_fifa_world_cup_scoreboard_widget_init');
}

$nomikos_fifa_world_cup_scoreboard_class = new nomikos_fifa_world_cup_scoreboard_class();

if ($nomikos_fifa_world_cup_scoreboard_class)
{
    register_activation_hook(__file__, array($nomikos_fifa_world_cup_scoreboard_class, 'install'));
    register_deactivation_hook(__file__, array($nomikos_fifa_world_cup_scoreboard_class, 'uninstall'));

    add_action('wp_footer', array($nomikos_fifa_world_cup_scoreboard_class, 'staticbar'));
    add_action('admin_menu', array($nomikos_fifa_world_cup_scoreboard_class, 'admin_options'));
    add_action('admin_print_scripts', array($nomikos_fifa_world_cup_scoreboard_class, 'header_scripts'));

    add_action('template_redirect', array($nomikos_fifa_world_cup_scoreboard_class, 'header_scripts'));
    add_action('template_redirect', array($nomikos_fifa_world_cup_scoreboard_class, 'header_styles'));
    add_shortcode('wp_fifa_world_cup_scoreboard', array($nomikos_fifa_world_cup_scoreboard_class, 'draw_page'));
}

?>