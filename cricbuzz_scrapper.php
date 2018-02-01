<?php
include_once "utilities.php";

function getScore() {
  $search_url = "http://synd.cricbuzz.com/j2me/1.0/livematches.xml";

  //Parsing the xml from the link using php's in built simpleXML library
  $xml = simplexml_load_file($search_url) or die("Error..");

  //Finding all the attributes from XML and displaying them
  $matches = $xml->match;
  foreach ($matches as $match) {
    $match_attributes = $match->attributes();
    $state_attributes = $match->state->attributes();
    $inning_Details = $match->mscr->inngsdetail;
    $batting_team = $match->mscr->btTm;
    $bowling_team = $match->mscr->blgTm;

    echo "\n";
    echo "Type          : " . $match_attributes[1] ."\n";
    echo "Series/League : " . $match_attributes[2] ."\n";
    echo "Teams         : " . $match_attributes[3] ."\n";
    echo "Match Number  : " . $match_attributes[4] ."\n";
    echo "Innings       : " . $match_attributes[8] ."\n";
    echo "Match State   : " . $state_attributes[0] ."\n";
    echo "Status        : " . $state_attributes[1] ."\n";

    if(!empty($batting_team) && !empty($bowling_team)){
      echo "Scores : \n";
      echo $batting_team->attributes()[1]." : ".$batting_team->Inngs->attributes()[1]."/";
      echo $batting_team->Inngs->attributes()[5]." in " .$batting_team->Inngs->attributes()[4];
      echo "\n";
      echo $bowling_team->attributes()[1]." : ".$bowling_team->Inngs->attributes()[1]."/";
      echo $bowling_team->Inngs->attributes()[5]." in " .$bowling_team->Inngs->attributes()[4];
      echo "\n";
    }

    echo "\n-----------------------------------------------------------\n";

  }
}
?>
