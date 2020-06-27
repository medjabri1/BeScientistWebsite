<!DOCTYPE html>
<html lang="en" dir="ltr">
	<head>
		<meta charset="utf-8">
		<link rel="stylesheet" href="styles/style.css">
	</head>
	<body>
		<?php

		//fetch_data.php

		include('database_connection.php');

		if(isset($_POST["action"]))
		{
			$query = "
				SELECT * FROM volume
			";

			if(isset($_POST["fyears"]))
			{			
							$fyears_filter = implode("','", $_POST["fyears"]);
							$query .= "WHERE created_at = ".$fyears_filter;
			}



			$statement = $connect->prepare($query);
			$statement->execute();
			$result = $statement->fetchAll();
			$total_row = $statement->rowCount();
			$output = '';
			if($total_row > 0)
			{
				foreach($result as $row)
				{
					$output .= '
					<div class="sin-b">
						<div class="img-a">
									<div >
											<div class="carre1"></div>
											<embed src="volumes/Volume'.$row['volume_id'].'.pdf#zoom=17" style="height: 221px;" >
									</div>
						</div>
						<div class="img-text">
							<h4 style="text-align:center;" class="text-danger" >année de publication : '. $row['created_at'] .'</h4>
							<p align="center"><strong><a href="volumes/Volume'.$row['volume_id'].'.pdf#zoom=100">Télécharger le volume maintenant</a></strong></p>
						</div>

					</div>
					';
				}
			}
			else
			{
				$output = '<h3>No Data Found</h3>';
			}
			echo $output;
		}

		?>

	</body>
</html>
