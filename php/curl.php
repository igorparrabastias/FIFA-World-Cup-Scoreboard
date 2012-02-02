<?php
class cURL
{
    var $headers;
    var $user_agent;
    var $compression;
    var $cookie_file;
    var $proxy;
    
    function cURL($compression = 'gzip', $proxy = '')
    {
        global $nomikos_fifa_world_cup_scoreboard_class;
    
        $this->headers[] = 'Accept: */*';
        $this->headers[] = 'Connection: Keep-Alive';
        $this->headers[] = 'Content-type: application/x-www-form-urlencoded;charset=UTF-8';
        $this->user_agent = 'Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 5.1; .NET CLR 1.0.3705; .NET CLR 1.1.4322; Media Center PC 4.0)';
        $this->compression = $compression;
        $this->proxy = $proxy;
    
        if ( ! is_writable(NOMIKOS_FIFA_WORLD_CUP_SCOREBOARD_PLUGIN_DIR . '/php/tmp/cookies.txt'))
        {
            $nomikos_fifa_world_cup_scoreboard_class->error('Please make dir ' . NOMIKOS_FIFA_WORLD_CUP_SCOREBOARD_PLUGIN_DIR . '/php/tmp and change premissions to 707.');
            $nomikos_fifa_world_cup_scoreboard_class->error('Please create file ' . NOMIKOS_FIFA_WORLD_CUP_SCOREBOARD_PLUGIN_DIR . '/php/tmp/cookies.txt and change premissions to 646.');
            # website seems not need to write to this file!!! So no kill.
        }
    
        $this->cookie(NOMIKOS_FIFA_WORLD_CUP_SCOREBOARD_PLUGIN_DIR . '/php/tmp/cookies.txt');
    }
    
    function cookie($cookie_file)
    {
        global $nomikos_fifa_world_cup_scoreboard_class;
    
        if (file_exists($cookie_file))
        {
            $this->cookie_file = $cookie_file;
        }
        else
        {
            @fopen($cookie_file, 'w') or $nomikos_fifa_world_cup_scoreboard_class->error('The cookie file could not be opened. Make sure this directory has the correct permissions');
            $this->cookie_file = $cookie_file;
            @fclose($this->cookie_file);
        }
    }
    
    function get($url)
    {
        global $nomikos_fifa_world_cup_scoreboard_class;
    
        $url = str_replace('&amp;', '&', $url);
        $process = @curl_init($url);
    
        @curl_setopt($process, CURLOPT_HTTPHEADER, $this->headers);
        @curl_setopt($process, CURLOPT_HEADER, 0);
        @curl_setopt($process, CURLOPT_USERAGENT, $this->user_agent);
        @curl_setopt($process, CURLOPT_SSL_VERIFYHOST, 0);
        @curl_setopt($process, CURLOPT_SSL_VERIFYPEER, false);
        @curl_setopt($process, CURLOPT_VERBOSE, 1);
        @curl_setopt($process, CURLOPT_COOKIEFILE, $this->cookie_file);
        @curl_setopt($process, CURLOPT_COOKIEJAR, $this->cookie_file);
        @curl_setopt($process, CURLOPT_ENCODING , $this->compression);
        @curl_setopt($process, CURLOPT_TIMEOUT, 30);
        @curl_setopt($process, CURLOPT_RETURNTRANSFER, 1);
        @curl_setopt($process, CURLOPT_FOLLOWLOCATION, 1);
        if ($this->proxy) @curl_setopt($cUrl, CURLOPT_PROXY, 'proxy_ip:proxy_port');
    
        $return = @curl_exec($process);
    
        if($return === false)
        {
            $nomikos_fifa_world_cup_scoreboard_class->error(@curl_error($process));
        }
    
        @curl_close($process);
        return $return;
    }
}
?>
