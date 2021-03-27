<?php
include_once 'database.php';
  define("STATUS_INACTIVE", 0);
  define("STATUS_FOR_SALE", 1);
  define("STATUS_SOLD", 2);
  define("STATUS_AUCTION_LIVE", 1001);
  define("STATUS_AUCTION_SOLD", 1002);
  define("STATUS_AUCTION_UNSOLD", 1003);

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

  function is_sale($status)
  {
    if (($status >=1) and ($status < 1000))
      return(True);
    return(False);
  }

  function is_auction($status)
  {
    if (($status >=1000) and ($status < 2000))
      return(True);
    return(False);
  }

  function display_tag($art)
  {	
  	 if (is_auction($art['status']))
  	 {
  	 	display_auction($art);

	 }
	 else if (is_sale($art['status']))
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
  	 if (($art['status'] == STATUS_AUCTION_LIVE) && !is_auction_started($art))
  	 {
  	 	echo "Auction begins at " . get_a_nice_date($art['start']);
			 	
  	 }
  	 else if (($art['status'] == STATUS_AUCTION_LIVE) && is_auction_ended($art))
  	 {
  	 	 echo "Auction closed at " . get_a_nice_date($art['auction_end']);

  	 	 echo "<br />";
  	 	 echo "Final bids being analyzed.";  	 	
  	 }
  	 else if (($art['status'] == STATUS_AUCTION_LIVE) && is_auction_live($art))
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
       display_barcode($art['assigned_address']);
	  	 echo "Auction ends at " . get_a_nice_date($art['auction_end']);
	  	 echo "<br />";
	  	 echo "Remaining: " . get_time_left($art['auction_end']);
  	 }
  	 else if ($art['status'] == STATUS_AUCTION_SOLD)
  	 {
  	 	 echo "<img src='images/sold.png' height=200 title='Sold' />";
  	 }
  	 else if ($art['status'] == STATUS_AUCTION_UNSOLD)
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

  function display_barcode($address)
  {
    echo "<img src='https://chart.googleapis.com/chart?chs=200x200&cht=qr&chl=" . $address . "' height=200 title='" . $address .  "' />";
    echo "<br />";
    echo $address;
  }

    function display_sale($art)
  {
  	 if ($art['status'] == STATUS_FOR_SALE)
  	 {
	  	 echo "Price: " . $art['price'] . " RVN";

	  	 echo "<br />";
       display_barcode($art['assigned_address']);

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
  echo "<tr valign=top>";
 

  echo "<td width=75%>";
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

