<?php
require_once "vendor/autoload.php";
include_once "utilities.php";
use FastSimpleHTMLDom\Document;

function downloadTorrent($search, $search_url) {

    echo "\nSearching " . $search . " on Sky Torrents...\nSearch URL is : " . $search_url . "\n\n";

    // getting the html of the search page
    $webpage_data = get_data($search_url);

    // using html Dom parser library to parse and access the data
    $html = new Document();
    $html->loadHtml($webpage_data);

    $tags = $html->find('a[href]');
    // saving titles of torrent in one array named $titles if href has 'info' in it
    // saving respective magnet links in another array ig href has 'magnet' in it
    foreach ($tags as $tag) {
        $link = $tag->href;
        if (substr($link, 0, 9)=="/torrent/") {
            $title = $tag->plaintext;
            $info_array[$link] = $title;
        } else {
            continue;
        }
    }

    // display titles
    $keys  = array_keys($info_array);
    $index = 1;
    foreach ($info_array as $key => $value) {
        echo $index . ". " . $value;
        $index++;
        echo "\n";
    }

    echo "\n";
    $num = readline("Type the number in front of the torrent to download it : ");

    //downloading torrent 
    if (is_numeric($num)) {
        echo "\n\nDownloading Torrent :\n" . $info_array[$keys[$num - 1]] . "\n\n";

        $url = "https://www.torlock.com" . $keys[$num - 1];

        $webpage_data = get_data($url);

        $html = new Document();
        $html->loadHtml($webpage_data);

        $tags = $html->find('a[href]');

        foreach ($tags as $tag) {
            $link = $tag->href;
            if (substr($link, 0, 5)=="/tor/") {
                echo $download_link = "https://www.torlock.com" . $link;
            } else {
                continue;
            }
        }

        $torrent = fopen($download_link, 'r'); //the song
        $file = fopen("torrents/" . $info_array[$keys[$num - 1]] . ".torrent", 'w');
        stream_copy_to_stream($torrent, $file); //copy it to the file
        fclose($torrent);
        fclose($file);


        //Just in case user wants to add more torrents
        $str = readline("\n\nAdd more torrent? [Y/N] : ");
        if ($str == "Y" || strtoupper($str) == "Y") {
            downloadTorrent($search);
        } else {
            echo "\nExiting.";
        }
    }
}
?>
