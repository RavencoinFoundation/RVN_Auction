<?php
include_once 'database.php';
  define("STATUS_INACTIVE", 0);
  define("STATUS_FOR_SALE", 1);
  define("STATUS_AUCTION", 2);
  define("STATUS_UNSOLD", 999);
  define("STATUS_SOLD", 42);

  function install_state_change_monitor($seconds)
  {
  	$millisecs = $seconds * 1000;
  	echo "<script>";
	echo "setTimeout(function(){";
   	echo "	window.location.reload(1);";
	echo "}, " . $millisecs . ");";
  	echo "</script>";
  }

  function display_art($id, $url)
  {
  	echo "<a href='/" . $id . "'>";
  	echo "<img src='" . $url . "' width=100% title='" . $id . "' alt='" . $id . "'>";
  	echo "</a>";
  }

  function display_info($title, $artist, $nft, $description)
  {
  	echo "Title: " . $title;
  	echo "<br />";
  	echo "Artist: " . $artist;
  	echo "<br />";
  	echo $description;
  	echo "<br />";
  	echo "<br />";
  	echo "NFT: " . $nft;
  	echo "<br />";
  	echo "<br />";

  }

  function display_tag($art)
  {	
  	 if ($art['is_sale'] == 0)
  	 {
  	 	display_auction($art);

	 }
	 else
	 {
  	 	display_sale($art);
	 }
  }

  function get_a_nice_date($timestamp)
  {
  	$date = new DateTime($timestamp); 
	return($date->format('Y-m-d H:i:s e'));  
  }

  function get_time_left($end)
  {
  	$end_unix = strtotime($end);
  	$date = new DateTime();
  	$now_unix = $date->getTimestamp();
  	$seconds = $end_unix - $now_unix;
  	$hours = floor($seconds/3600);
  	$minutes = floor(($seconds - $hours*3600) / 60);
  	$seconds_left = ($seconds - $hours*3600) - $minutes*60;
  	return($hours . ":" . str_pad($minutes, 2, '0', STR_PAD_LEFT) . ":" . str_pad($seconds_left, 2, '0', STR_PAD_LEFT));
  	

  }

  function is_auction_started($art)
  {
  	$date = new DateTime();
  	#echo $date->getTimestamp();
  	#echo "<br />";
  	#echo strtotime($art['start']);
	return($date->getTimestamp() >= strtotime($art['start']));

  }

  function is_auction_ended($art)
  {
  	$date = new DateTime();
	return($date->getTimestamp() > strtotime($art['auction_end']));  	
  }

  function is_auction_live($art)
  {
  	return(is_auction_started($art) && !is_auction_ended($art));
  }


  function display_auction($art)
  {
  	 if (($art['status'] == STATUS_AUCTION) && !is_auction_started($art))
  	 {
  	 	echo "Auction begins at " . get_a_nice_date($art['start']);
			 	
  	 }
  	 else if (($art['status'] == STATUS_AUCTION) && is_auction_ended($art))
  	 {
  	 	 echo "Auction closed at " . get_a_nice_date($art['auction_end']);

  	 	 echo "<br />";
  	 	 echo "Final bids being analyzed.";  	 	
  	 }
  	 else if (($art['status'] == STATUS_AUCTION) && is_auction_live($art))
  	 {
  	 	 echo "<strong>Auction is Live</strong><br />";
  	 	 if ($art['current_bid']  > 0)
  	 	 {
	  	 	echo "Current Bid: " . $art['current_bid'] . " RVN";
	  	 	echo "Bid Increment: " . $art['bid_increment'] . " RVN";
	  	 	echo "Bid at least: " . $art['current_bid'] + $art['bid_increment'] . " RVN";
	  	 }
	  	 else
	  	 {
	  	 	echo "No Bidders Yet";
	  	 	echo "<br />";
	  	 	echo "Minimum Bid: " . $art['min_bid'] . " RVN";
	  	 }

	  	 echo "<br />";
  	 	 echo "<img src='https://chart.googleapis.com/chart?chs=200x200&cht=qr&chl=" . $art['address'] . "' height=200 title='" . $art['address'] .  "' />";
	  	 echo "<br />";
	  	 echo $art['address'];
	  	 echo "<br />";
	  	 echo "Auction ends at " . get_a_nice_date($art['auction_end']);
	  	 echo "<br />";
	  	 echo "Remaining: " . get_time_left($art['auction_end']);
  	 }
  	 else if ($art['status'] == STATUS_SOLD)
  	 {
  	 	 echo "<img src='images/sold.png' height=200 title='Sold' />";
  	 }
  	 else if ($art['status'] == STATUS_UNSOLD)
  	 {
  	 	echo "Auction Complete";
  	 	echo "<br />";
  	 	echo "Auction started: " . get_a_nice_date($art['start']);
  	 	echo "<br />";
  	 	echo "Auction ended: " . get_a_nice_date($art['auction_end']);
  	 	echo "<br />";
  	 	echo "Minimum bid of " . $art['min_bid'] . " not reached.";
  	 	echo "<br />";  	 	
  	 }
  	 else
  	 {
  	 	echo "Auction info is updating...";
  	 	echo "<br />";
  	 	echo "Auction started: " . get_a_nice_date($art['start']);
  	 	echo "<br />";
  	 	echo "Auction ended: " . get_a_nice_date($art['auction_end']);
  	 	echo "<br />";
  	 	echo "Last known bid: " . $art['current_bid'];
  	 	echo "<br />";
  	 }
  }

    function display_sale($art)
  {
  	 if ($art['status'] == STATUS_FOR_SALE)
  	 {
	  	 echo "Price: " . $art['price'] . " RVN";
	  	 echo "<br />";
  	 	 echo "<img src='https://chart.googleapis.com/chart?chs=200x200&cht=qr&chl=" . $art['address'] . "' height=200 title='" . $art['address'] .  "' />";
	  	 echo "<br />";
	  	 echo $art['address'];
	  	 echo "<br />";
  	 }
  	 else if ($art['status'] == STATUS_SOLD)
  	 {
  	 	 echo "<img src='images/sold.png' height=200 title='Sold' />";
  	 }
  }

  function read_data($id)
  {
    $conn = connect();
	$result = mysqli_query($conn,"SELECT * FROM nfts where id='".$id."'");
	if (mysqli_num_rows($result) > 0)
	{
		$row = mysqli_fetch_array($result);
		return($row);
	}
	return(0);
  }

  #Clean up the input - lowercase letters and numbers and a dash
  $id =  preg_replace('/[^a-z0-9-]/', '', $_GET['id']);

  $art = read_data($id);
  
  #DEBUG info
  #var_dump($art);

  if ($art == 0)
  {
  	exit("id: <strong>" . $id . "</strong> not found.  Usage: example.com/index.php?id=an-art-piece");
  }	

  echo '<table>';
  echo '<tr>';
 

  echo '<td width=75%>';
  display_art($art['id'], $art['image_url']);
  echo '</td>';

  echo '<td>';
  display_info($art['title'], $art['artist'], $art['nft'], $art['description']);

  display_tag($art);
  echo '</td>';


  echo '<tr>';
  echo '</table>';

  install_state_change_monitor(5);
?>

