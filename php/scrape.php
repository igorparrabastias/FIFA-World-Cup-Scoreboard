<?php

/**
 * echo error to inform the plugin user.
 * **/

$results1                     = array();
$results2                    = array();
$match_times                 = array();
$match_times_mysql           = array();
$match_scraped               = array();
$last_scrape_match_timestamp = 0;
$NOMIKOS_FIFA_WORLD_CUP_SCOREBOARD_PLUGIN_URL = NOMIKOS_FIFA_WORLD_CUP_SCOREBOARD_PLUGIN_URL;

if ( extension_loaded('curl'))
{
    include (NOMIKOS_FIFA_WORLD_CUP_SCOREBOARD_PLUGIN_DIR . '/php/curl.php');
    $cc = new cURL();
    @set_time_limit(60);
}

$parser_number = 1;
foreach ($this->scrape_url as $scrape_url)
{
    $page = '';
    $page = $cc->get($scrape_url);

    if ( ! $page)
    if ( ! $page = file_get_contents($scrape_url))
    {
        echo "WARNING: can't fetch (file_get_contents) scrape from: $scrape_url";
        continue;
    }

    if ( ! file_exists($parser = NOMIKOS_FIFA_WORLD_CUP_SCOREBOARD_PLUGIN_DIR . '/parser/parser'. $parser_number .'.inc'))
    {
        echo "WARNING: parser:$parser missing.";
        continue;
    }

    include ($parser);

    if (empty(${results.$parser_number}['group']))
    {
        echo "WARNING: parser:$parser breaked or page not available.";
    }

    $parser_number++;
}
?>
