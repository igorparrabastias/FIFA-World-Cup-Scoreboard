
<?php
if ( ! $this->options->results1 )
{
    echo 'No results for FIFA World Cup South Africa scoreboard :(';
    return;
}

$NOMIKOS_FIFA_WORLD_CUP_SCOREBOARD_PLUGIN_URL = NOMIKOS_FIFA_WORLD_CUP_SCOREBOARD_PLUGIN_URL;
$have_coutries_links = array();
?>

<table class="nomikos_fifa_world_cup_scoreboard_class_table">
<tr style="text-align:center">
<td class="nomikos_fifa_world_cup_scoreboard_class_table_group_page">A</td>
<td class="nomikos_fifa_world_cup_scoreboard_class_table_group_page">B</td>
<td class="nomikos_fifa_world_cup_scoreboard_class_table_group_page">C</td>
<td class="nomikos_fifa_world_cup_scoreboard_class_table_group_page">D</td>
<td class="nomikos_fifa_world_cup_scoreboard_class_table_group_page">E</td>
<td class="nomikos_fifa_world_cup_scoreboard_class_table_group_page">F</td>
<td class="nomikos_fifa_world_cup_scoreboard_class_table_group_page">G</td>
<td class="nomikos_fifa_world_cup_scoreboard_class_table_group_page">H</td>
<td class="nomikos_fifa_world_cup_scoreboard_class_table_group_page">Round of 16</td>
<td class="nomikos_fifa_world_cup_scoreboard_class_table_group_page">Quarter-finals</td>
<td class="nomikos_fifa_world_cup_scoreboard_class_table_group_page">Semi-finals</td>
<td class="nomikos_fifa_world_cup_scoreboard_class_table_group_page">Match for third place</td>
<td class="nomikos_fifa_world_cup_scoreboard_class_table_group_page">Final</td>
</tr>
</table>

<?php
foreach ( $this->options->results1 as $i => $group )
{
    foreach ( $group as $j => $matchs )
    {
        $gletter = chr($j + 97);

        echo '<a name="_group'.strtoupper($gletter).'"></a><br /><br />';
        echo '<table class="nomikos_fifa_world_cup_scoreboard_class_table"><caption>Group '.strtoupper($gletter).'</caption>';
        echo '<tr>';

        if ($this->options->show_results->number)
            echo '<td width="5%">Match</td>';
        if ($this->options->show_results->date)
            echo '<td width="5%">
            <input type="checkbox" class="tzChanger" title="Check to show dates in local time. Uncheck to show Africa time"> T
            </td>';
        echo '<td width="20%">Venue</td><td width="20">&nbsp;</td><td width="15%">&nbsp;</td><td width="20%" align="center">Results</td><td width="15%">&nbsp;</td><td width="20">&nbsp;</td></tr>';

        foreach ( $matchs as $k => $records )
        {
            $have_coutries_links[$records['team1_flag']] = $records['team1_link'];
            $have_coutries_links[$records['team2_flag']] = $records['team2_link'];

            $result_text = $records['result_text'];
            $result_text = str_replace('http://localhost/agenda/wp-content/plugins/fifa-world-cup-south-africa-scoreboard', $NOMIKOS_FIFA_WORLD_CUP_SCOREBOARD_PLUGIN_URL, $result_text);

            echo "<tr id=\"{$records['timestamp']}\">";
            if ($this->options->show_results->number)
                echo "<td align=\"right\">{$records['number']}</td>";
            if ($this->options->show_results->date)
                echo "<td><span class=\"tzChangerText\">{$records['date']}</span></td>";
echo <<<EOF
<td><a target="_blank" href="http://www.fifa.com{$records['venue_link']}">{$records['venue_text']}</a></td>
<td align="right"><img width="19" height="13" src="$NOMIKOS_FIFA_WORLD_CUP_SCOREBOARD_PLUGIN_URL/img/{$records['team1_flag']}.gif" /></td>
<td><a target="_blank" href="http://www.fifa.com{$records['team1_link']}">{$records['team1_text']}</a></td>
<td align="center">{$result_text}</td>
<td align="right"><a target="_blank" href="http://www.fifa.com{$records['team2_link']}">{$records['team2_text']}</a></td>
<td><img width="19" height="13" src="$NOMIKOS_FIFA_WORLD_CUP_SCOREBOARD_PLUGIN_URL/img/{$records['team2_flag']}.gif" /></td>
</tr>
EOF;
        }

        echo '</table>';
    }
}

foreach ( $this->options->results2 as $i => $group )
{
    foreach ( $group as $istage => $matchs )
    {
        echo '<a name="_group'.$istage.'"></a><br /><br />';
        echo '<table class="nomikos_fifa_world_cup_scoreboard_class_table"><caption>'.$istage.'</caption>';
        echo '<tr>';

        if ($this->options->show_results->number)
            echo '<td width="5%">Match</td>';
        if ($this->options->show_results->date)
            echo '<td width="5%">
            <input type="checkbox" class="tzChanger" title="Check to show dates in local time. Uncheck to show Africa time"> T
            </td>';
        echo '<td width="20%">Venue</td><td width="20">&nbsp;</td><td width="15%">&nbsp;</td><td width="20%" align="center">Results</td><td width="15%">&nbsp;</td><td width="20">&nbsp;</td></tr>';

        foreach ( $matchs as $k => $records )
        {
            echo "<tr id=\"{$records['timestamp']}\">";
            if ($this->options->show_results->number)
                echo "<td align=\"right\">{$records['number']}</td>";
            if ($this->options->show_results->date)
                echo "<td><span class=\"tzChangerText\">{$records['date']}</span></td>";
echo <<<EOF
<td><a target="_blank" href="http://www.fifa.com{$records['venue_link']}">{$records['venue_text']}</a>
<div class="tzShower" style="display:block;margin-top:6px;padding-top:2px;"></div>
</td>
EOF;

if ( ! $records['team1_flag'])
echo <<<EOF
<td>&nbsp;</td>
<td>{$records['team1_text']}</td>
EOF;
else
echo <<<EOF
<td align="right"><img width="19" height="13" src="$NOMIKOS_FIFA_WORLD_CUP_SCOREBOARD_PLUGIN_URL/img/{$records['team1_flag']}.gif" /></td>
<td><a target="_blank" href="http://www.fifa.com{$have_coutries_links[$records['team1_flag']]}">{$records['team1_text']}</a></td>
EOF;

echo <<<EOF
<td align="center">{$records['result_text']}</td>
EOF;

if ( ! $records['team2_flag'])
echo <<<EOF
<td align="right">{$records['team2_text']}</td>
<td>&nbsp;</td>
EOF;
else
echo <<<EOF
<td align="right"><a target="_blank" href="http://www.fifa.com{$have_coutries_links[$records['team2_flag']]}">{$records['team2_text']}</a></td>
<td><img width="19" height="13" src="$NOMIKOS_FIFA_WORLD_CUP_SCOREBOARD_PLUGIN_URL/img/{$records['team2_flag']}.gif" /></td>
EOF;
echo <<<EOF
</tr>
EOF;
        }

        echo '</table>';
    }
}
?>