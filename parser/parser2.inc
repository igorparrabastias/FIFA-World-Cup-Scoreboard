<?php

$unit  = $this->get_unit($page, ' id="tzcClient"', ' id="thirdRail"');
$stage = array('Round of 16', 'Quarter-finals', 'Semi-finals', 'Match for third place', 'Final');

if (preg_match_all("/<table[^>]*>(.*?)<\/table>/si", $unit, $groups, PREG_SET_ORDER))
{
    foreach ($groups as $i => $group)
    {
        $istage = array_shift($stage);

        foreach ($group as $matchs)
        {
            preg_match_all("/<tr class=\"(odd|even)\">\s*<td[^>]*>(.*?)<\/td><td[^>]*>(.*?)<\/td>\s*<td[^>]*>\s*<a[^>]*href=\"([^\"]+)\"[^>]*>(.*?)<\/a>\s*<\/td>\s*<td[^>]*>(.*?)\s*<td[^>]*>(.*?)\s*<td[^>]*>(.*?)\s*<td[^>]*>(.*?)\s*<td[^>]*>(.*?)<\/tr>/si", $matchs, $match, PREG_SET_ORDER);

            if ($match)
            {
                foreach ($match as $j => $records)
                {
                    $results2['group'][$istage][$j]['number']     = strip_tags(trim($records[2]));
                    $results2['group'][$istage][$j]['date']       = strip_tags(trim($records[3]));
                    $results2['group'][$istage][$j]['venue_link'] = strip_tags(trim($records[4]));

                    $venue = strip_tags(trim($records[5]));
                    if ( strstr($venue, 'Port Elizabeth') )
                        $venue = 'Port Elizabeth';
                    else if ( strstr($venue, 'Bloemfontein') )
                        $venue = 'Bloemfontein';
                    else if ( strstr($venue, 'Johannesburg') )
                        $venue = 'Johannesburg';
                    else if ( strstr($venue, 'Pretoria') )
                        $venue = 'Pretoria';
                    $results2['group'][$istage][$j]['venue_text'] = $venue;

                    $team1_flag = trim($records[6]);
                    if (preg_match("/(\w{3})\.gif/si", $team1_flag, $aux))
                        $team1_flag = $aux[1];
                    else
                        $team1_flag = '';
                    $results2['group'][$istage][$j]['team1_flag']  = $team1_flag;

                    $results2['group'][$istage][$j]['team1_text'] = strip_tags(trim($records[7]));

                    $result_text = trim($records[8]);
                    $result_text = preg_replace("/<(span|div)[^>]*>/si", '', $result_text);
                    $result_text = preg_replace("/<(span|div)[^>]*>/si", '', $result_text);
                    $result_text = preg_replace("/<\/(span|div|td)>/si", '', $result_text);
                    # remove onclick, etc
                    $result_text = preg_replace("/ on\w+=\"[^\"]*\"/si", '', $result_text);
                    $result_text = preg_replace("/class=\"[^\"]*\"/si", '', $result_text);
                    $result_text = preg_replace("/href=\"([^\"]*)\"/si", "target=\"_blank\" href=\"http://www.fifa.com\\1\"", $result_text);
                    $result_text = preg_replace("/\/imgml\/worldcup\/matches\//si", "$NOMIKOS_FIFA_WORLD_CUP_SCOREBOARD_PLUGIN_URL/img/", $result_text);
                    $result_text = preg_replace("/\r|\n/si", '', $result_text);
                    $results2['group'][$istage][$j]['result_text'] = $result_text;

                    $results2['group'][$istage][$j]['team2_text']  = strip_tags(trim($records[9]));

                    $team2_flag = trim($records[10]);
                    if (preg_match("/(\w{3})\.gif/si", $team2_flag, $aux))
                        $team2_flag = $aux[1];
                    else
                        $team2_flag = '';
                    $results2['group'][$istage][$j]['team2_flag']  = $team2_flag;

                    # switch month by day to match english format time
                    $date  = $results2['group'][$istage][$j]['date'];
                    $date  = explode(' ', $date);
                    $date2 = $date[1];
                    $date1 = explode('/', $date[0]);
                    $date1 = $date1[1] . '/' . $date1[0];

                    $x = strtotime("2010/$date1/$date2");
                    $y = date('Y-m-d H:i:s', $x);

                    $results2['group'][$istage][$j]['timestamp'] = $x;

                    $match_number = $results2['group'][$istage][$j]['number'];

                    $match_scraped[$match_number]['date']    = $x;
                    $match_scraped[$match_number]['scraped'] = 0;

                    # has numeric results (d:d) => scraped ready
                    if (preg_match("/\d+:\d+/si", $result_text))
                    {
                        $match_scraped[$match_number]['scraped'] = 1;

                        if ($x > $last_scrape_match_timestamp)
                        {
                            $last_scrape_match_timestamp = $x;
                        }
                    }

                    # set this data only once
                    # -----------------------
                    if ( ! $this->options->match_times )
                    {
                        if ( ! in_array($x, $match_times))
                        {
                            $match_times[]       = $x;
                            $match_times_mysql[] = $y;
                        }
                    }
                }
            }
        }
    }
}

?>