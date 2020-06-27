<?php

	session_start();

    spl_autoload_register(function ($class_name) {
        include 'Classes/'. $class_name . '.php';
    });

    if(!isset($_SESSION['user_id'])) {
        header('Location: /');
        die();
	}

	if(User::findBy($_SESSION['user_id']) == null) {
		header('Location: /');
		die();
	}

	if(isset($_POST['logout_button'])) {
		session_destroy();
		header('Location: /');
	}

	$user = User::findBy($_SESSION['user_id']);
	if( $user->getType() == 'E' ) $is_admin = true;
	else $is_admin = false;

	$_SESSION['current_type'] = $user->getType();

	$type = User::$types[$_SESSION['current_type']];

    $error = '';
	$success = '';

	//ERRORS
    if(isset($_GET[sha1('exc_problem')])) $error = 'Server issues, please try again!';
    if(isset($_GET[sha1('email_used')])) $error = 'Email already used by another account!';
    if(isset($_GET[sha1('password_wrong')])) $error = 'Password given is wrong!';
    if(isset($_GET[sha1('password_confirmation_wrong')])) $error = 'Password Confirmation is wrong!';

    //SUCCESS
    if(isset($_GET[sha1('user_updated')])) $success = 'Your informations are updated now!';
    if(isset($_GET[sha1('password_updated')])) $success = 'Your password is updated now!';
    if(isset($_GET[sha1('article_added')])) $success = 'Your Article is submitted now!';
    if(isset($_GET[sha1('sended_toreviewer')])) $success = 'Article sent to reviewer!';
    if(isset($_GET[sha1('reviewed_success')])) $success = 'Observation sent successffully!';
    if(isset($_GET[sha1('article_accepted')])) $success = 'Article accepted!';
    if(isset($_GET[sha1('article_rejected')])) $success = 'Article Rejected!';
    if(isset($_GET[sha1('for_correction')])) $success = 'Article return for correction!';
    if(isset($_GET[sha1('article_corrected')])) $success = 'Article corrected successffully!';
		if(isset($_GET[sha1('volume_created')])) $success = 'Volume created successffully!';

?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Be Scientist</title>
    <link rel="stylesheet" href="styles/style.css">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.1/css/all.css">
	<script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>

  </head>
  <body>

	<!-- Erreurs -->
	<div class="global-errors" id="global-errors" title="CLICK TO HIDE">
		<?php
		if($error != "") {
			echo "<p>$error</p>";
		}
		?>
	</div>

	<div class="global-success" id="global-success" title="CLICK TO HIDE">
		<?php
		if($success != "") {
			echo "<p>$success</p>";
		}
		?>
	</div>

	<!-- Menu Haut -->
	<div class="mh">
		<div class="sm" id="d1-champ"><a href="procedure.php" class="barh" >Mes articles <i class="fas fa-newspaper"></i></a></div>
		<div class="sm"><a href="myaccount.php" class="barh">Mon compte <i class="fas fa-user-circle"></i> </a></div>
		<div class="sm">
		<form action="<?= $_SERVER['PHP_SELF'] ?>" method="POST">
			<button name="logout_button" type="submit" class="barh" style="cursor: pointer; background-color: transparent; outline: none; border: none">
				Se déconecter <i class="fas fa-sign-out-alt"></i>
			</button>
		</form>
		</div>
	<div>

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
					 $("#d1-champ").html("<a href='procedure.php' class='barh'>Mes articles <i class='fas fa-newspaper'></i></a>");
			  }else{
				if(test==="Reviewer"){
					$("#d1-champ").html("<a href='procedure.php' class='barh'>Mes demandes <i class='fas fa-envelope-open-text'></i></a>");
				}else{
					if(test==="Editor"){
						 $("#d1-champ").html("<a href='procedure.php' class='barh'>Les Procédures <i class='fas fa-clipboard'></i></a>");
					}
				}

			  }
			})

			let current = $(".default_option li div p").text().trim().toLowerCase();

			if(current === "author"){
				$("#d1-champ").html("<a href='procedure.php' class='barh'>Mes articles <i class='fas fa-newspaper'></i></a>");
				console.log('Author');
			} else {
				if(current === "reviewer"){
					$("#d1-champ").html("<a href='procedure.php' class='barh'>Mes demandes <i class='fas fa-envelope-open-text'></i></a>");
					console.log('Reviewer');
				} else {
					if(current === "editor"){
						$("#d1-champ").html("<a href='procedure.php' class='barh'>Les Procédures <i class='fas fa-clipboard'></i></a>");
						console.log('Editor');
					}
				}
			}

		});

	</script>
	</script>
</div>
	</div>
	<!-- Menu principale -->
  <div class="header nav" id="nav">
    <h2 class="logo"><a href="/">be-scientist</a></h2>
    <input type="checkbox" id="chk">
    <label for="chk" class="show-menu-btn">
      <i class="fas fa-ellipsis-h"></i>
    </label>

    <ul class="menu">
			<a href="#">ACCUEIL</a>
		  <a href="#services">SERVICES</a>
		  <a href="inter_volume.php">DOWNLOAD/DISCOVER</a>
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

<script src="scripts/fixer_menu.js"></script>



			<!-- Slideshow container -->
			<div class="slideshow-container">

			  <!-- Full-width images with number and caption text -->
			  <div class="mySlides fade">
				<div class="numbertext">1 / 3</div>
				<img src="imgs/1.jpg" style="width:100%">
				<div class="text">Caption Text</div>
			  </div>

			  <div class="mySlides fade">
				<div class="numbertext">2 / 3</div>
				<img src="imgs/2.jpg" style="width:100%">
				<div class="text">Caption Two</div>
			  </div>

			  <div class="mySlides fade">
				<div class="numbertext">3 / 3</div>
				<img src="imgs/3.jpg" style="width:100%">
				<div class="text">Caption Three</div>
			  </div>

			  <!-- Next and previous buttons -->
			  <a class="prev" onclick="plusSlides(-1)">&#10094;</a>
			  <a class="next" onclick="plusSlides(1)">&#10095;</a>
			</div>
			<br>

			<!-- The dots/circles -->
			<div style="text-align:center">
			  <span class="dot" onclick="currentSlide(1)"></span>
			  <span class="dot" onclick="currentSlide(2)"></span>
			  <span class="dot" onclick="currentSlide(3)"></span>
			</div>

			<!-- Script to slider -->
			<script src="scripts/sc_slider.js"></script>

		<!-- Services -->
		<div  id="services">
		<div class="col-lg-12 text-center">
				<h2 class="section-heading text-uppercase">Services</h2>
				<h3 class="section-subheading text-muted">Nous essayons de faire tout ce qu'on peut pour partager avec vous un ensemble d'articles dans un volume bien vérifier  .</h3>
		</div>
		<div class="box-area" >

		<!-- Author -->
			<div class="single-box">
				<div class="img-area"></div>
				<div class="img-text">
						<span class="header-text"><strong>Author</strong></span>
						<div class="line"></div>
						<h3>Envoyer les articles</h3>
						<p>Lorem ipsum dolor sit amet, consectetu adipisicing elit.
							Corporis, atque?</p>
				</div>
			</div>
		<!-- Adminitrateur -->
			<div class="single-box">
				<div class="img-area"></div>
				<div class="img-text">
						<span class="header-text"><strong>Adminitrateur</strong></span>
						<div class="line"></div>
						<h3>Organiser la procedure</h3>
						<p>Lorem ipsum dolor sit amet, consectetu adipisicing elit.
							Corporis, atque?</p>
				</div>
			</div>
		<!-- Réferé -->
			<div class="single-box">
				<div class="img-area"></div>
				<div class="img-text">
						<span class="header-text"><strong>Réferé</strong></span>
						<div class="line"></div>
						<h3>Verifier les articles</h3>
						<p>Lorem ipsum dolor sit amet, consectetu adipisicing elit.
							Corporis, atque?</p>
				</div>
			</div>
		</div>
		</div>
			<!-- Contact -->
	<div class="container" id="contact">
		<div class="contact-section">

			  <h1>Contact Us</h1>
			  <div class="border"></div>
			  <form class="contact-form" action="index.html" method="post">
				<input type="text" class="contact-form-text" placeholder="Your name">
				<input type="email" class="contact-form-text" placeholder="Your email">
				<textarea class="contact-form-text" placeholder="Your message"></textarea>
				<input type="submit" class="contact-form-btn" value="Send">
			  </form>
		</div>
		<!-- News Letter -->
		<div >
			<form action="" id="idns">
			  <h1>Join Our Newsletter</h1>
			  <div class="border"></div>
			  <p>you will receive an alert in your gmail inbox each time a new volume is released.</p>
			  <div class="email-box">
				<i class="fas fa-envelope"></i>
				<input class="tbox" type="email" name="" value="" placeholder="Enter your email">
				<button class="btn" type="button" name="button">Subscribe</button>
			  </div>
			</form>
		</div>
	</div>


	<script>

		document.querySelector('#global-errors').addEventListener('click', ()=>{
			document.querySelector('#global-errors').style.display = 'none';
			window.history.pushState({}, document.title, "/");
		});

		document.querySelector('#global-success').addEventListener('click', ()=>{
			document.querySelector('#global-success').style.display = 'none';
			window.history.pushState({}, document.title, "/");
		});

	</script>

  </body>
</html>
