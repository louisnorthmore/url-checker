<?php

/*
 * Check URL Redirects
 * Author: Louis Northmore
 * URL: http://github.com/louisnorthmore/url-checker
 *
 * Expects urls.csv in the same folder
 * urls.csv format: <url>,<url>
 *
 */

ini_set('default_socket_timeout', 2);

$urls = file('urls.csv');
$this_log = date('Y_m_d-His').'.csv';
file_put_contents('broken_'.$this_log, "http_code,url,expected,actual\n");

$working_urls = 0;
$broken_urls  = 0;
$error_urls   = 0;
$url_count    = 0;

foreach ($urls as $url) {
    $line   = explode(',', $url);
    $source = $line[0];
    $dest   = $line[1];

    if ($source === 'skip') {
        continue;
    }

    echo "Checking $source -> $dest";

    checkUrl($source, $dest);

    $url_count++;
}

summary();

/**
 *
 * checkUrl
 *
 * @param $source
 * @param $dest
 *
 * @return void
 */
function checkUrl($source, $dest)
{

    global $working_urls;
    global $broken_urls;
    global $error_urls;
    global $this_log;

    $info = get_headers($source);

    $http_code   = substr($info[0], 9, 3);
    $actual_dest = $info[3];

    $dest = str_replace(array("\n", "\t", "\r"), '', $dest);

    $actual_dest = str_replace('Location: ', '', $actual_dest);

    if ($actual_dest === $dest) {
        echo "$http_code redirect successful ($http_code) ($actual_dest)\r\n\r\n";
        $working_urls++;
    } else {
        if (empty($actual_dest) === true) {
            $error_urls++;
            echo "Error\r\n\r\n";
        } else {
            echo "Redirect incorrect ($http_code) Expected: ($dest)\r\nGot: ($actual_dest)\r\n\r\n";

            //log
            file_put_contents('broken_'.$this_log, "$http_code,$source,$dest,$actual_dest\n", FILE_APPEND);

            $broken_urls++;
        }
    }

}

/**
 * Summary
 * @return void
 */
function summary()
{
    global $working_urls;
    global $broken_urls;
    global $error_urls;
    global $url_count;
    echo "\r\nSummary: Total URLS: $url_count Working: $working_urls Broken: $broken_urls Errors: $error_urls\r\n\r\n";

}
