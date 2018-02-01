<?php
require_once "vendor/autoload.php";
include("getID3-master/getid3/getid3.php");
use FastSimpleHTMLDom\Document;

/* Gets the html from url */
function get_data($url) {
    $data = file_get_contents($url);
    return $data;
}

// searches the song bases on query on songs.pk
function searchSong($search) {
    $search_query = str_replace(' ', '+', $search);
    $search_url   = "https://songs.pk/search?q=" . $search_query . "&type=singles";
    echo "\nSearching " . $search . " on Songs.pk...\nSearch URL is : " . $search_url . "\n\n";

    // getting the html of the search page
    $webpage_data = get_data($search_url);

    // using html Dom parser library to parse and access the data
    $html = new Document();
    $html->loadHtml($webpage_data);

    /* finding element in html which has class .single-songs and inside that
       h3 tag and then a tag. i.e.
       <div class=".single-song">
          <h3>
              <a></a>
          </h3>
       </div>

       This will be explained to students into 3 steps. Like finding single
       class, accessing their elements. Line 37 will be shown in 3 steps and
       later will be changed into one.
    */
    $tags = $html->find('.single-songs h3 a');
    //$tags = $classlist->find('');
    foreach ($tags as $tag) {
        $link               = $tag->href; // getting hrefs of a tags
        $song_name          = $tag->innertext; // geting song_names
        $songs_array[$link] = $song_name; // saving hrefs and names in asoociative array
    }

    // returns the associative array
    return $songs_array;
}


// Displaying songs
function displaySongs($songs_array)
{
    $keys  = array_keys($songs_array);
    $index = 1;
    foreach ($songs_array as $key => $value) {
        echo $index . ". " . $value;
        $index++;
        echo "\n";
    }

    echo "\n";
    $num = readline("Type the number in front of the video to download it : ");

    if (is_numeric($num)) {
        $song_title = $songs_array[$keys[$num - 1]];
        echo "\nDownloading song - " . $song_title;
        $link     = $keys[$num - 1];
        $song_url = "https://songs.pk" . $link;
    }
    // returning url and title of song selected by user
    return array(
        $song_url,
        $song_title
    );

}


// getting the .mp3 url forthe selected song
function songDownloadUrl($song_url)
{
    $webpage_data = get_data($song_url);
    // getting the webpages of the selected sonh


    // parsing the webpage data
    $html = new Document();
    $html->loadHtml($webpage_data);

    // accessing the element with .col-body class and then its a tag.
    // this element href has the download link
    $tags = $html->find('.col-body a');
    //$tags = $classlist->find('h3 a');
    $link = $tags[0]->href; // out of all the hrefs the first one has download link.

    /* Sometimes the links are in .mp3 format and most of the times they are
        'href="https://files.mp3slash.net/stream/811eaa45533d2a837dd07274c2047e16"'
        in this format, which eventually redirects to the mo3 link.
        So, we search if the href has .mp3 and it is has then we return the
        link directly(if condition) otherwise we need to get the redirect url
        (else conditon).
    */
    if (stripos($link, '.mp3') !== false) {
        $download_link = $link;
        return $download_link;
    } else {
        $context = stream_context_create(array(
            'http' => array(
                'follow_location' => false
            )
        ));

        $html = file_get_contents($link, false, $context);
        $download_link = explode(": ",$http_response_header[6], 2)[1];
        return $download_link;
    }
}


// from the returned download link we save the file in songs/song_title.mp3 format.
// basics of file handing will be taught here.
function downloadSong($download_link, $song_title)
{
    $song = fopen($download_link, 'r'); //the song
    $file = fopen("songs/" . $song_title . ".mp3", 'w');
    stream_copy_to_stream($song, $file); //copy it to the file
    fclose($song);
    fclose($file);

    // after saving the file, we use the get id3 library to get the song duration
    $getID3        = new getID3;
    $filename      = "songs/" . $song_title . ".mp3";
    $file          = $getID3->analyze($filename);
    $song_duration = $file['playtime_seconds'];
    gmdate("H:i:s", $playtime_seconds);
    echo "\nSong Download finished! Now check the file. \n";
    return $song_duration; // returning song duration of the downloaded song
}
?>
