
<?php
if ( ! $nomikos_fifa_world_cup_scoreboard_class->options->results1 )
{
    echo 'No results for FIFA World Cup South Africa scoreboard :(';
    return;
}

$NOMIKOS_FIFA_WORLD_CUP_SCOREBOARD_PLUGIN_URL = NOMIKOS_FIFA_WORLD_CUP_SCOREBOARD_PLUGIN_URL;
$have_coutries_links = array();
?>

<table class="nomikos_fifa_world_cup_scoreboard_class_table">
<caption>
FIFA World Cup™ Scoreboard
</caption>
<tr style="text-align:center">
<td class="nomikos_fifa_world_cup_scoreboard_class_table_group">A</td>
<td class="nomikos_fifa_world_cup_scoreboard_class_table_group">B</td>
<td class="nomikos_fifa_world_cup_scoreboard_class_table_group">C</td>
<td class="nomikos_fifa_world_cup_scoreboard_class_table_group">D</td>
<td class="nomikos_fifa_world_cup_scoreboard_class_table_group">
<?php if ( $nomikos_fifa_world_cup_scoreboard_class->options->options->topbar ) : ?>
<input type="checkbox" title="Close/Open top bar" id="staticBar2b">
<?php else : ?>
&nbsp;
<?php endif; ?>
</td>
</tr>
<tr style="text-align:center">
<td class="nomikos_fifa_world_cup_scoreboard_class_table_group">E</td>
<td class="nomikos_fifa_world_cup_scoreboard_class_table_group">F</td>
<td class="nomikos_fifa_world_cup_scoreboard_class_table_group">G</td>
<td class="nomikos_fifa_world_cup_scoreboard_class_table_group">H</td>
<td class="nomikos_fifa_world_cup_scoreboard_class_table_group"><input type="checkbox" class="tzChanger" title="Check to show dates in local time. Uncheck to show Africa time"></td>
</tr>
<tr style="text-align:center">
<td class="nomikos_fifa_world_cup_scoreboard_class_table_group" colspan="5">Stage 2</td>
</tr>
</table>

<?php
foreach ( $nomikos_fifa_world_cup_scoreboard_class->options->results1 as $i => $group )
{
    foreach ( $group as $j => $matchs )
    {
        $gletter = chr($j + 97);
        echo '<table class="nomikos_fifa_world_cup_scoreboard_class_table" id="group'.strtoupper($gletter).'" style="display:none;"><caption>Group '.strtoupper($gletter).'</caption>';

        foreach ( $matchs as $records )
        {
            $have_coutries_links[$records['team1_flag']] = $records['team1_link'];
            $have_coutries_links[$records['team2_flag']] = $records['team2_link'];

            if ( $instance['date'] )
            {
                $date = "<br /><span class=\"tzChangerText\">{$records['date']}</span>";
            }

            $result_text = $records['result_text'];

            $result_text = preg_replace("/<a[^>]*>\s*<img[^>]*matchcamera.png[^>]*>\s*<\/a>/si", '', $result_text);
            $result_text = preg_replace("/<a[^>]*>\s*<img[^>]*matchmore.png[^>]*>\s*<\/a>/si", '', $result_text);

            if ( $instance['one_row'] )
echo <<<EOF
<tr id="{$records['timestamp']}">
<td rowspan="2" align="right">
<a target="_blank" href="http://www.fifa.com{$records['team1_link']}">
<img width="19" height="13" src="$NOMIKOS_FIFA_WORLD_CUP_SCOREBOARD_PLUGIN_URL/img/{$records['team1_flag']}.gif" title="{$records['team1_text']}" /></a></td>
<td align="center"><a target="_blank" href="http://www.fifa.com{$records['venue_link']}">{$records['venue_text']}</a>$date</td>
<td rowspan="2" align="left">
<a target="_blank" href="http://www.fifa.com{$records['team2_link']}">
<img width="19" height="13" src="$NOMIKOS_FIFA_WORLD_CUP_SCOREBOARD_PLUGIN_URL/img/{$records['team2_flag']}.gif" title="{$records['team2_text']}" /></a></td>
</tr>
<tr>
<td align="center">$result_text</td>
</tr>
EOF;
            else
            {
echo <<<EOF
<tr id="{$records['timestamp']}">
<td align="right"><img width="19" height="13" src="$NOMIKOS_FIFA_WORLD_CUP_SCOREBOARD_PLUGIN_URL/img/{$records['team1_flag']}.gif" title="{$records['team1_text']}" /></td>
<td align="center"><a target="_blank" href="http://www.fifa.com{$records['venue_link']}">{$records['venue_text']}</a>$date</td>
<td align="left"><img width="19" height="13" src="$NOMIKOS_FIFA_WORLD_CUP_SCOREBOARD_PLUGIN_URL/img/{$records['team2_flag']}.gif" title="{$records['team2_text']}" /></td>
</tr>
<tr>
<td align="right"><a target="_blank" href="http://www.fifa.com{$records['team1_link']}">{$records['team1_text']}</a></td>
<td align="center">$result_text</td>
<td align="left"><a target="_blank" href="http://www.fifa.com{$records['team2_link']}">{$records['team2_text']}</a></td>
</tr>
EOF;
            }
        }
        echo '</table>';
    }
}

foreach ( $nomikos_fifa_world_cup_scoreboard_class->options->results2 as $i => $group )
{
    foreach ( $group as $istage => $matchs )
    {
        echo '<table class="nomikos_fifa_world_cup_scoreboard_class_table group_stage2" style="display:none;"><caption>'.$istage.'</caption>';

        foreach ( $matchs as $records )
        {
            if ( $instance['date'] )
            {
                $date = "<br /><span class=\"tzChangerText\">{$records['date']}</span>";
            }

            $result_text = $records['result_text'];
            $result_text = preg_replace("/<a[^>]*>\s*<img[^>]*matchcamera.png[^>]*>\s*<\/a>/si", '', $result_text);
            $result_text = preg_replace("/<a[^>]*>\s*<img[^>]*matchmore.png[^>]*>\s*<\/a>/si", '', $result_text);

            if ( $instance['one_row'] )
echo <<<EOF
<tr id="{$records['timestamp']}">
<td align="center" colspan="3"><div class="tzShower" style="block"></div></td>
</tr>
<tr id="{$records['timestamp']}">
<td rowspan="2" align="right">
<a target="_blank" href="http://www.fifa.com{$have_coutries_links[$records['team1_flag']]}">
<img width="19" height="13" src="$NOMIKOS_FIFA_WORLD_CUP_SCOREBOARD_PLUGIN_URL/img/{$records['team1_flag']}.gif" title="{$records['team1_text']}" /></a></td>
<td align="center"><a target="_blank" href="http://www.fifa.com{$records['venue_link']}">{$records['venue_text']}</a>$date</td>
<td rowspan="2" align="left">
<a target="_blank" href="http://www.fifa.com{$have_coutries_links[$records['team2_flag']]}">
<img width="19" height="13" src="$NOMIKOS_FIFA_WORLD_CUP_SCOREBOARD_PLUGIN_URL/img/{$records['team2_flag']}.gif" title="{$records['team2_text']}" /></a></td>
</tr>
<tr>
<td align="center">$result_text</td>
</tr>
<tr>
<td align="center" colspan="3"></td>
</tr>
EOF;
            else
            {
echo <<<EOF
<tr id="{$records['timestamp']}">
<td align="center" colspan="3"><div class="tzShower" style="block"></div></td>
</tr>
<tr id="{$records['timestamp']}">
<td align="right"><img width="19" height="13" src="$NOMIKOS_FIFA_WORLD_CUP_SCOREBOARD_PLUGIN_URL/img/{$records['team1_flag']}.gif" title="{$records['team1_text']}" /></td>
<td align="center"><a target="_blank" href="http://www.fifa.com{$records['venue_link']}">{$records['venue_text']}</a>$date</td>
<td align="left"><img width="19" height="13" src="$NOMIKOS_FIFA_WORLD_CUP_SCOREBOARD_PLUGIN_URL/img/{$records['team2_flag']}.gif" title="{$records['team2_text']}" /></td>
</tr>
EOF;

echo <<<EOF
<tr>
EOF;

if ( ! $records['team1_flag'])
echo <<<EOF
<td>{$records['team1_text']}</td>
EOF;
else
echo <<<EOF
<td align="right"><a target="_blank" href="http://www.fifa.com{$have_coutries_links[$records['team1_flag']]}">{$records['team1_text']}</a></td>
EOF;

echo <<<EOF
<td align="center">$result_text</td>
EOF;

if ( ! $records['team2_flag'])
echo <<<EOF
<td>{$records['team2_text']}</td>
EOF;
else
echo <<<EOF
<td align="left"><a target="_blank" href="http://www.fifa.com{$have_coutries_links[$records['team2_flag']]}">{$records['team2_text']}</a></td>
</tr>
EOF;
            }
        }
        echo '</table>';
    }
}
?>

<?php if ( $instance['schedule'] ) : ?>

<table class="nomikos_fifa_world_cup_scoreboard_class_table">
<tr style="text-align:center">
<td colspan="8">
<?php
echo <<<EOF
<a target="_blank" href="$NOMIKOS_FIFA_WORLD_CUP_SCOREBOARD_PLUGIN_URL/img/Fifa-2010-Schedule.png">
<img width="160" height="92" src="$NOMIKOS_FIFA_WORLD_CUP_SCOREBOARD_PLUGIN_URL/img/Fifa-2010-Schedule-thumbnail.png" title="Fifa 2010 Schedule" /></a>
EOF;
?>
</td>
</tr>
<caption>
Match Schedule
</caption>
</table>

<?php endif; ?>