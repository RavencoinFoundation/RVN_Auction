<style>
table, th, td {
  border: 1px solid black;
  border-collapse: collapse;
  text-align: left;
}
</style>

<?php
include_once 'database.php';
include_once 'status_const.php';


function display_header()
{	
	echo '<tr>';

	echo '<th>';
	echo 'start';
	echo '</th>';

	echo '<th>';
	echo 'id';
	echo '</th>';

	echo '<th>';
	echo 'status';
	echo '</th>';	


	echo '<th>';
	echo 'title';
	echo '</th>';

	echo '<th>';
	echo 'price';
	echo '</th>';

	echo '</tr>';

}


function display_row($art)
{
 	echo '<tr>';

	echo '<td>';
	echo $art['start'];
	echo '</td>';

	echo '<td>';
	echo "<a href='index.php?id=" . $art['id'] . "'>";
	echo $art['id'];
	echo "</a>";
	echo '</td>';

	echo '<td>';
	if ($art['status'] == STATUS_INACTIVE)
	{
		echo "Inactive";
	}
	else if ($art['status'] == STATUS_FOR_SALE)
	{
		echo "For Sale";		
	}
	else if ($art['status'] == STATUS_AUCTION)
	{
		echo "Auction";	
	}
	else if ($art['status'] == STATUS_UNSOLD)
	{
		echo "Unsold";		
	}
	else if ($art['status'] == STATUS_SOLD)
	{
		echo "Sold";	
	}
	else
	{
		echo "Unknown";
	}


	echo '<td>';
	echo $art['title'];
	echo '</td>';

	echo '<td>';
	echo $art['price'];
	echo '</td>';

	echo '</tr>';  

}

function display()
{
	echo '<table>';
	display_header();

	$conn = connect();
	$result = mysqli_query($conn,"SELECT * FROM nfts");
	if (mysqli_num_rows($result) > 0)
	{
		$row = mysqli_fetch_array($result);
		display_row($row);
	}
	return(0);
}

// 	$i=0;
// 	while($art = mysqli_fetch_array($result)) {
// 		display_row($art);
// 		$i++;
// 	}
// 	echo '</table>';

// }


  display();

?>