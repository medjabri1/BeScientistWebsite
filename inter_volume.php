<?php

//index.php

include('database_connection.php');

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <title>Download/discover volumes</title>
    <script src="scripts/jquery-1.10.2.min.js"></script>
    <link rel="stylesheet" href="styles/style.css">
</head>

<body>
    <!-- interface volumes -->

    <?php include('includes/nav_bar.php'); ?>
    <script src="scripts/fixer_menu.js"></script>
    <div style="position: relative;top: 20%;">
        	<h2 align="center">Ici vous trouverez les volumes originales généré par notre platform</h2>
        	<br />

                <center>

                    <div class="sel-f">
                      <select name="slt-1" id="slt-b">
                        <option value="tt">filter les volume</option>
                        <?php

                                  $query = "SELECT created_at FROM volume ";
                                  $statement = $connect->prepare($query);
                                  $statement->execute();
                                  $result = $statement->fetchAll();
                                  foreach($result as $row)
                                  {
                                  ?>
                                      <option  class="common_selector fyears" value="<?php echo$row['created_at']; ?>" > Les volumes qui sont publié en<?php echo $row['created_at']; ?></option>
                                  <?php
                                  }
                                  ?>

                  </select>






                    </div>
                </center>




                <div class="box-a filter_data col-lg-12">

                </div>
    </div>

<style>
#loading
{
	text-align:center;
	background: url('img/loader.gif') no-repeat center;
	height: 150px;
}
</style>

<script>
$(document).ready(function(){

    filter_data();

    function filter_data()
    {
        $('.filter_data').html('<div id="loading" style="" ></div>');
        var action = 'fetch_data';
        var fyears = get_filter('fyears');
        $.ajax({
            url:"fetch_data.php",
            method:"POST",
            data:{action:action,  fyears:fyears},
            success:function(data){
                $('.filter_data').html(data);
            }
        });
    }

    function get_filter(class_name)
    {
        var filter = [];
        $('.'+class_name+':checked').each(function(){
            filter.push($(this).val());
        });
        return filter;
    }

    $('.common_selector').click(function(){
        filter_data();
    });



});
</script>

</body>

</html>
