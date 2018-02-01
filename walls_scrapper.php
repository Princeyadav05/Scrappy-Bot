<?php
include_once 'utilities.php';
use FastSimpleHTMLDom\Document;

// searching wallpapers
function searchWallpaper($search) {
    $search_query = str_replace(' ', '+', $search);
    $search_url   = "https://alpha.wallhaven.cc/search?q=" . $search_query;
    echo "\nSearching " . $search . " on Wallhaven...\nSearch URL is : " . $search_url . "\n\n";

    //getting data of the search url
    $webpage_data = get_data($search_url);

    $html = new Document();
    $html->loadHtml($webpage_data);

    // getting all classes with preview tags
    $tags = $html->find('.preview');

    foreach ($tags as $tag) {
        $link          = $tag->href . "\n"; // getting hrefs of preview class
        $walls_array[] = $link; // saving hrefs an array
    }

    return $walls_array; //returning Array with all href links
}

function downloadWallpaper($walls_array) {
    echo "\n\nDownloading Wallpapers... \n\n";

    // iterating through every link in walls array
    foreach ($walls_array as $key => $value) {
      // getting just the image id present in a href
        $image_id = filter_var($value, FILTER_SANITIZE_NUMBER_INT);

        //adding image id to the url
        $url = "http://wallpapers.wallhaven.cc/wallpapers/full/wallhaven-" . $image_id[0][0];
        /* Now the url needs an extension at the end. On wallhaven it can be
           .jpg, .png, .bmp
           storing these 3 in an array
           and concatinating each one of them. and gettinf file contents.
           if file contents returns 404, skipping the link else saving the image.
        */
        $extensions = ['.jpg', '.png', '.bmp'];
        foreach ($extensions as $extension) {
            /*In PHP . is the concatenation operator which returns the
            concatenation of its right and left arguments
            */
            $download_url = $url . $extension; // concatinating iamge with extension

            $file = @file_get_contents($download_url);
            // getting file contents for the download_url
            // if file_get_contents returns error, @ will return false
            // if returned false -> moce to second link (if condition)
            // if returns true -> download wallpaper (else condition)
            if ($file === false) {
                continue;
            } else {
                echo "Downloading Wallhaven-" . $image_id[0][0] . $extension;
                echo "\n";
                //path of wallpaper
                $path = 'images/Wallhaven-' . $image_id[0][0] . $extension;
                // putting contents of file in path
                file_put_contents($path, file_get_contents($download_url));
            }
        }
    }
}
?>
