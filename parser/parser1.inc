<?php

if ($results1 = file_get_contents(NOMIKOS_FIFA_WORLD_CUP_SCOREBOARD_PLUGIN_DIR . '/data/results1.txt'))
{
    $results1 = unserialize(base64_decode($results1));
    return;
}

$unit = $this->get_unit($page, ' id="bodyContent"', ' id="thirdRail"');

if (preg_match_all("/<table[^>]*>(.*?)<\/table>/si", $unit, $groups, PREG_SET_ORDER))
{
    foreach ($groups as $i => $group)
    {
        foreach ($group as $matchs)
        {
            preg_match_all("/<tr class=\"(odd|even)\"><td[^>]*>(.*?)<\/td><td[^>]*>(.*?)<\/td>\s*<td[^>]*>\s*<a[^>]*href=\"([^\"]+)\"[^>]*>(.*?)<\/a>\s*<\/td>\s*<td[^>]*>.*?(\w{3})\.gif.*?<\/td>\s*<td[^>]*>\s*<a[^>]*href=\"([^\"]*)\"[^>]*>([^<]*)<\/a>\s*<\/td>\s*<td[^>]*>(.*?)\s*<\/td>\s*<td[^>]*>\s*<a[^>]*href=\"([^\"]*)\"[^>]*>(.*?)<\/a><\/td>\s*<td[^>]*><a[^>]*><img[^>]*(\w{3})\.gif.*?<\/td>\s*<\/tr>/si", $matchs, $match, PREG_SET_ORDER);

            if ($match)
            {
                foreach ($match as $j => $records)
                {
                    $results1['group'][$i][$j]['number']     = strip_tags(trim($records[2]));
                    $results1['group'][$i][$j]['date']       = strip_tags(trim($records[3]));
                    $results1['group'][$i][$j]['venue_link'] = strip_tags(trim($records[4]));
                    $venue = strip_tags(trim($records[5]));
                    if ( strstr($venue, 'Port Elizabeth') )
                        $venue = 'Port Elizabeth';
                    else if ( strstr($venue, 'Bloemfontein') )
                        $venue = 'Bloemfontein';
                    else if ( strstr($venue, 'Johannesburg') )
                        $venue = 'Johannesburg';
                    else if ( strstr($venue, 'Pretoria') )
                        $venue = 'Pretoria';
                    $results1['group'][$i][$j]['venue_text'] = $venue;
                    $results1['group'][$i][$j]['team1_flag'] = strip_tags(trim($records[6]));
                    $results1['group'][$i][$j]['team1_link'] = strip_tags(trim($records[7]));
                    $results1['group'][$i][$j]['team1_text'] = strip_tags(trim($records[8]));

                    $result_text = trim($records[9]);
                    $result_text = preg_replace("/<(span|div)[^>]*>/si", '', $result_text);
                    $result_text = preg_replace("/<\/(span|div)>/si", '', $result_text);
                    # remove onclick, etc
                    $result_text = preg_replace("/ on\w+=\"[^\"]*\"/si", '', $result_text);
                    $result_text = preg_replace("/class=\"[^\"]*\"/si", '', $result_text);
                    $result_text = preg_replace("/href=\"([^\"]*)\"/si", "target=\"_blank\" href=\"http://www.fifa.com\\1\"", $result_text);
                    $result_text = preg_replace("/\/imgml\/worldcup\/matches\//si", "$NOMIKOS_FIFA_WORLD_CUP_SCOREBOARD_PLUGIN_URL/img/", $result_text);
                    $result_text = preg_replace("/\r|\n/si", '', $result_text);
                    $results1['group'][$i][$j]['result_text'] = $result_text;

                    $results1['group'][$i][$j]['team2_link']  = strip_tags(trim($records[10]));
                    $results1['group'][$i][$j]['team2_text']  = strip_tags(trim($records[11]));
                    $results1['group'][$i][$j]['team2_flag']  = strip_tags(trim($records[12]));

                    # switch month by day to match english format time
                    $date  = $results1['group'][$i][$j]['date'];
                    $date  = explode(' ', $date);
                    $date2 = $date[1];
                    $date1 = explode('/', $date[0]);
                    $date1 = $date1[1] . '/' . $date1[0];

                    $x = strtotime("2010/$date1/$date2");
                    $y = date('Y-m-d H:i:s', $x);

                    $results1['group'][$i][$j]['timestamp'] = $x;

                    $match_number = $results1['group'][$i][$j]['number'];

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