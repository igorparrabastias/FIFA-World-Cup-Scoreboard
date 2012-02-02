
<?php if ( ! $nomikos_fifa_world_cup_scoreboard_class->options->options->topbar ) return; ?>
<?php if ( ! $nomikos_fifa_world_cup_scoreboard_class->options->results2 ) return; ?>

<div id="staticBar">
<table cellspacing="0" cellpadding="0" width="100%">
<tr>
    <?php if ( $nomikos_fifa_world_cup_scoreboard_class->options->options->text3 ) : ?>
    <td align="center" width="20">
    <a target="_blank" title="FIFA World Cup South Africa Scoreboard - Wordpress plugin &copy; nomikos.info - Get it now!" href="http://nomikos.info/2010/06/10/fifa-world-cup-south-africa-scoreboard-wp-plugin.html">&copy;</a>
    </td>
    <?php endif; ?>
    <?php if ( $nomikos_fifa_world_cup_scoreboard_class->options->options->text1 ) : ?>
    <td align="center" width="180">
    FIFA World Cup&trade; Scoreboard
    </td>
    <?php endif; ?>
    <td>
    <table cellspacing="0" cellpadding="0" id="staticBar_table">
    <tr>
        <td align="center" nowrap>
<?php

$NOMIKOS_FIFA_WORLD_CUP_SCOREBOARD_PLUGIN_URL = NOMIKOS_FIFA_WORLD_CUP_SCOREBOARD_PLUGIN_URL;
$output = '';

$next_scrape_match_number = 0;
foreach ( $nomikos_fifa_world_cup_scoreboard_class->options->match_times as $m )
{
    if ($nomikos_fifa_world_cup_scoreboard_class->current_time_in_africa < $m)
    {
        $next_scrape_match_number = $m;
        break;
    }
}

foreach ( $nomikos_fifa_world_cup_scoreboard_class->options->results2 as $i => $group )
{
    foreach ( $group as $istage => $matchs )
    {
        foreach ( $matchs as $records )
        {
            $result_text = $records['result_text'];

            $result_text = preg_replace("/<a[^>]*>\s*<img[^>]*matchcamera.png[^>]*>\s*<\/a>/si", '', $result_text);
            $result_text = preg_replace("/<a[^>]*>\s*<img[^>]*matchmore.png[^>]*>\s*<\/a>/si", '', $result_text);

            if ( stristr($result_text, 'live') )
            $output .=  <<<EOF
<div class="staticBar_table_div">
<b>Now playing:</b>
&nbsp;
<img width="19" height="13" src="$NOMIKOS_FIFA_WORLD_CUP_SCOREBOARD_PLUGIN_URL/img/{$records['team1_flag']}.gif" title="{$records['team1_text']}" />&nbsp;<a target="_blank" href="http://www.fifa.com{$records['team1_link']}">{$records['team1_text']}</a>
&nbsp;
<b>{$result_text}</b>
&nbsp;
<a target="_blank" href="http://www.fifa.com{$records['team2_link']}">{$records['team2_text']}</a>&nbsp;<img width="19" height="13" src="$NOMIKOS_FIFA_WORLD_CUP_SCOREBOARD_PLUGIN_URL/img/{$records['team2_flag']}.gif" title="{$records['team2_text']}" />
&nbsp;
$istage - <a target="_blank" href="http://www.fifa.com{$records['venue_link']}">{$records['venue_text']}</a>.
</div>
EOF;
            else if ( $nomikos_fifa_world_cup_scoreboard_class->options->last_scrape_match_timestamp == $records['timestamp'] )
            $output .=  <<<EOF
<div class="staticBar_table_div">
Last result:
&nbsp;
<img width="19" height="13" src="$NOMIKOS_FIFA_WORLD_CUP_SCOREBOARD_PLUGIN_URL/img/{$records['team1_flag']}.gif" title="{$records['team1_text']}" />&nbsp;<a target="_blank" href="http://www.fifa.com{$records['team1_link']}">{$records['team1_text']}</a>
&nbsp;
<b>{$result_text}</b>
&nbsp;
<a target="_blank" href="http://www.fifa.com{$records['team2_link']}">{$records['team2_text']}</a>&nbsp;<img width="19" height="13" src="$NOMIKOS_FIFA_WORLD_CUP_SCOREBOARD_PLUGIN_URL/img/{$records['team2_flag']}.gif" title="{$records['team2_text']}" />
&nbsp;
$istage - <a target="_blank" href="http://www.fifa.com{$records['venue_link']}">{$records['venue_text']}</a>.
</div>
EOF;
            else if ( $next_scrape_match_number == $records['timestamp'] )
            {
                $output .=  <<<EOF
<div class="staticBar_table_div">
Next match:
&nbsp;
<img width="19" height="13" src="$NOMIKOS_FIFA_WORLD_CUP_SCOREBOARD_PLUGIN_URL/img/{$records['team1_flag']}.gif" title="{$records['team1_text']}" />&nbsp;<a target="_blank" href="http://www.fifa.com{$records['team1_link']}">{$records['team1_text']}</a>
&nbsp;
<b>{$result_text}</b>
&nbsp;
<a target="_blank" href="http://www.fifa.com{$records['team2_link']}">{$records['team2_text']}</a>&nbsp;<img width="19" height="13" src="$NOMIKOS_FIFA_WORLD_CUP_SCOREBOARD_PLUGIN_URL/img/{$records['team2_flag']}.gif" title="{$records['team2_text']}" />
&nbsp;
$istage - <a target="_blank" href="http://www.fifa.com{$records['venue_link']}">{$records['venue_text']}</a>.
</div>
EOF;
        }
        }
    }
}

echo $output;
?>
        </td>
    </tr>
    </table>
    </td>
    <?php if ( $nomikos_fifa_world_cup_scoreboard_class->options->options->text2 ) : ?>
    <td align="center" width="160">
    <a target="_blank" title="FIFA World Cup official website" href="http://www.fifa.com/worldcup/index.html">FIFA&trade; official website</a>
    </td>
    <?php endif; ?>
    <td align="center" id="staticBar3" width="20" style="cursor:pointer" title="Refresh data">&divide;&nbsp;</td>
    <td align="center" id="staticBar2" width="20" style="cursor:pointer" title="Close">&times;&nbsp;</td>
</tr>
</table>
</div>
