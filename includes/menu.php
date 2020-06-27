

<!doctype html>
<html>
	<head>
		<meta charset="UTF-8">
		<title>Menu</title>
		<link rel="stylesheet" href="../styles/style.css">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.1/css/all.css">
			<script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
	</head>
	<body>
	<div class="mh">
		<?php if(!$is_admin) : ?>
			<div class="sm" id="d1-champ"><a href="procedure.php" class="barh" >Mes articles <i class="fas fa-newspaper"></i></a></div>
		<?php else : ?>
			<div class="sm" id="d1-champ"><a href="/procedure.php" class="barh" >Les Procédures <i class="fas fa-clipboard"></i></a></div>
		<?php endif; ?>
		<div class="sm"><a href="/myaccount.php" class="barh">Mon compte <i class="fas fa-user-circle"></i> </a></div>
		<div class="sm">
		<form action="<?= $_SERVER['PHP_SELF'] ?>" method="POST">
			<button name="logout_button" type="submit" class="barh" style="cursor: pointer; background-color: transparent; outline: none; border: none">
				Se déconecter <i class="fas fa-sign-out-alt"></i>
			</button>
		</form>
		</div>

		<div class="select_wrap">
			<ul class="default_option">
				<li>
					<div class="option pizza">
						<i class="fas fa-user-edit"></i>
						<p><?= $type ?><p>
					</div>
				</li>
			</ul>
			<ul class="select_ul">

				<?php if(!$is_admin) : ?>

				<li>
					<div class="option pizza">
						<i class="fas fa-user-edit"></i>
						<p>Author<p>
					</div>
				</li>
				<li>
					<div class="option pizza">
						<i class="fas fa-user-cog"></i>
						<p>Reviewer<p>
					</div>
				</li>

				<?php else : ?>

				<li>
					<div class="option burger">

						<i class="fas fa-user-tie"></i>
						<p>Editor</p>
					</div>
				</li>

				<?php endif; ?>

			</ul>
		</div>
	</div>
		<script>
		var test="Author";
		$(document).ready(function(){
			$(".default_option").click(function(){
			  $(this).parent().toggleClass("active");
			})

			$(".select_ul li").click(function(){
			  var currentele = $(this).html();
			  var ch=$(this).find('p');
			  test=ch.html();
			  $(".default_option li").html(currentele);
			  $(this).parents(".select_wrap").removeClass("active");

			  if(test==="Author"){
					 $("#d1-champ").html("<a href='/procedure.php' class='barh'>Mes articles <i class='fas fa-newspaper'></i></a>");
			  }else{
				if(test==="Reviewer"){
					 $("#d1-champ").html("<a href='/procedure.php' class='barh'>Mes demandes <i class='fas fa-envelope-open-text'></i></a>");
				}else{
					if(test==="Administrator"){
						 $("#d1-champ").html("<a href='/procedure.php' class='barh'>Les Procédures <i class='fas fa-clipboard'></i></a>");
					}
				}

			  }
			})

		});

	</script>
</div>
	</div>

	<!-- Menu principale -->
	<!--
  <div class="header nav" id="nav">
    <h2 class="logo">be-scientist</h2>
    <input type="checkbox" id="chk">
    <label for="chk" class="show-menu-btn">
      <i class="fas fa-ellipsis-h"></i>
    </label>

    <ul class="menu">
		  <a href="#">ACCUEIL</a>
		  <a href="#services">SERVICES</a>
		  <a href="#">DOWNLOAD/DISCOVER</a>
		  <a href="#contact">Contact</a>
      <label for="chk" class="hide-menu-btn">
        <i class="fas fa-times"></i>
      </label>

	   <div class="box" id="srch">
		<form >
		  <input type="text" name="" placeholder="Type..." id="rch">
		  <input type="submit" name="" value="Search" id="btnrch"></button>
		</form>
	  </div>
    </ul>
  </div>


<script src="../scripts/fixer_menu.js"></script>
	</body>
</html>
