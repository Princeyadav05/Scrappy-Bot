<?php
require_once "vendor/autoload.php";
include_once "getID3-master/getid3/getid3.php" ;
include_once "utilities.php";
use FastSimpleHTMLDom\Document;

// geting novel from the search query
function getNovelPrice($search) {
    $search_query = str_replace(' ', '+', $search);
    $search_url   = "https://www.vivilio.com/search?query=" . $search_query;
    echo "\nSearching " . $search . " on Songs.pk...\nSearch URL is : " . $search_url . "\n\n";

    $webpage_data = get_data($search_url);
    $html = new Document();
    $html->loadHtml($webpage_data);

    // getting all a tags with href and only capturing those have isbn in it
    $tags = $html->find('a[href]');

    foreach ($tags as $tag) {
        $link = $tag->href;
        $title = $tag->title;
        if (stripos($link, 'isbn') !== false) {
            $isbn_numbers[] = $title;
        } else {
          continue;
        }
    }

    //getting all span tags and only capturing those which have Rs. in it
    $prices = $html->find('span');

    foreach ($prices as $price) {
        $rupees = $price->plaintext;
        if (stripos($rupees, 'Rs.') !== false) {
            $prices_array[] = explode("&nbsp", $rupees)[1];
        } else {
          continue;
        }
    }

    $index = 1;
    foreach ($isbn_numbers as $isbn_number) {
        echo $index . ". " . $isbn_number;
        $index++;
        echo "\n";
    }

    echo "\n";
    $num = readline("Type the number in front of the song to download it : ");

    if (is_numeric($num)) {
        echo "\nThe best price of the novel " . $isbn_numbers[$num-1] . " is " . $prices_array[$num-1] . ".\n";
    }
}


?>
