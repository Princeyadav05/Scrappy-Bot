<?php
include_once 'songs_scrapper.php';
include_once 'walls_scrapper.php';
include_once 'torrent_scrapper.php';
include_once 'cricbuzz_scrapper.php';
include_once 'vivilio_scrapper.php';
include_once 'utilities.php';

echo "**** WELCOME TO THE Mega Scrapper **** \n \n";
$name = readline("Hey There. Lets start with your name : ");
echo "\nHello " . $name . ".\n";

$select = menu();


if (is_numeric($select) && $select == 1) {
    $search = readline("Please enter the song you want to download : ");

    $songs_array   = searchSong($search);
    //returns songs array with their urls as key and titles as value.
    $song_info     = fetchSongDetails($songs_array);
    /* displays the song and asks the user which one to download and returns that
    specific url and title
    */
    $download_link = $song_info[0]; // returns with .mp3 link
    $song_title    = $song_info[1]; // song title
    $song_url      = $song_info[2]; //song_url
    $song_duration = downloadSong($download_link, $song_title);
    //* Downloads the files and returns its duration
    $songs_path    = "songs/" . $song_title . ".mp3"; //path of a song
    $connection    = new createConnection();
    $connection->savingToDb($song_title, $song_duration, $song_url, $songs_path);
    //savingToDb($song_title, $song_duration, $song_url, $songs_path);
    // saving details to database
} elseif ((is_numeric($select) && $select == 2)) {
    $search = readline("\n\nSearch Wallpapers : ");

    $walls_array = searchWallpaper($search);
    downloadWallpaper($walls_array);
} elseif ((is_numeric($select) && $select == 3)) {

    echo "\n1. Search for a specific torrent.";
    echo "\n2. Get Top Torrents. \n";

    $num = readline("Select : ");

    if ((is_numeric($num) && $num == 1)) {
        $search       = readline("Search Query: ");
        // changes green day to green%20day
        $search_query = str_replace(' ', '-', $search);;
        echo $search_url   = "https://www.torlock.com/all/torrents/" . $search_query .".html";
        echo "\n";
        downloadTorrent($search, $search_url);
    } elseif ((is_numeric($num) && $num == 2)) {
        echo $search_url = "https://www.torlock.com/top100.html";
        echo "\n";
        downloadTorrent($search, $search_url);
    }
} elseif ((is_numeric($select) && $select == 4)) {
    $search = readline("Search Novel : ");
    getNovelPrice($search);


} elseif ((is_numeric($select) && $select == 5)) {
    getScore();
} else  {
    echo "\nBad Input. Exiting.\n";
}



function menu() {
    echo "\n --- Scrapper Menu --- \n";
    echo "\n1. Download songs from Songs.pk";
    echo "\n2. Download Wallpapers from WallHaven ";
    echo "\n3. Scrap Torrents ";
    echo "\n4. Lowest Price of a Novel: ";
    echo "\n5. Get Live Cricket Score\n";

    $select = readline("Select : ");
    return $select;
}


?>
