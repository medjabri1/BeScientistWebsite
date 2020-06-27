<?php

    spl_autoload_register(function ($class_name) {
        include 'Classes/'. $class_name . '.php';
    });

    session_start();

    if(isset($_SESSION['user_id'])) {
		if(User::findBy($_SESSION['user_id']) != null) {
			header('Location: home.php');
			die();
		}
    }

    $error = '';
    $success = '';

    //ERRORS
    if(isset($_GET[sha1('not_found')])) $error = 'Email given not found, please sign up first!';
    if(isset($_GET[sha1('wrong_password')])) $error = 'Password given is wrong, try again!';
    if(isset($_GET[sha1('not_verified')])) $error = 'please verify your email first!';
    if(isset($_GET[sha1('exc_problem')])) $error = 'Server issues, please try signing up again!';
    if(isset($_GET[sha1('email_used')])) $error = 'Email already used by another account!';
    if(isset($_GET[sha1('problem_verify')])) $error = 'Server issues with email verification, please try again!';

    //SUCCESS
    if(isset($_GET[sha1('user_added')])) $success = 'You are signed up, sign in now!';
    if(isset($_GET[sha1('email_verified')])) $success = 'Your email is verified now, please sign in!';
    // echo "<script>window.history.pushState({}, document.title, '/');</script>";

	//CONTACT

	if(isset($_POST['mailform']))
	{
		if(!empty($_POST['nom']) AND !empty($_POST['mail']) AND !empty($_POST['message']))
		{
			$header="MIME-Version: 1.0\r\n";
			$header.='From:"Be-scientist.com"<support@BeScientis.com>'."\n";
			$header.='Content-Type:text/html; charset="uft-8"'."\n";
			$header.='Content-Transfer-Encoding: 8bit';

			$message='
			<html>
				<body>
					<div align="center">

						<u>Nom de l\'expéditeur :</u>'.$_POST['nom'].'<br />
						<u>Mail de l\'expéditeur :</u>'.$_POST['mail'].'<br />
						<br />
						'.nl2br($_POST['message']).'

					</div>
				</body>
			</html>
			';

			mail("marouaneelbaroudi@gmail.com", "CONTACT - Be-Scientis.com", $message, $header);
			$msg="Votre message a bien été envoyé !";
		}
		else
		{
			$msg="Tous les champs doivent être complétés !";
		}
	}

	//Newsletter
	if(isset($_POST['Newsletter']))
	{
		if(!empty($_POST["emailN"])){
			$bdd=new PDO("mysql:host=127.0.0.1;dbname=id13569561_pfe;charset=utf8","id13569561_root","site-PFE-esto2020");
            $requete=$bdd->prepare("INSERT INTO newsletter(email) VALUES(?)");
            $requete->execute(array($_POST['emailN']));
            extract($_POST);
            $destinataire=$emailN;
            $objet="News Letter Be-scientist";

			$header="MIME-Version: 1.0\r\n";
			$header.='From:"Be-scientist.com"<support@BeScientis.com>'."\n";
			$header.='Content-Type:text/html; charset="uft-8"'."\n";
			$header.='Content-Transfer-Encoding: 8bit';
			$message ='
				<html>
                    <body>
                          <div align="center">
                             <h2>Felicitation vous avez bien inscrire!!</h2>
                           </div>
					</body>

                 </html>';
			$mail=mail($destinataire,$objet,$message,$header);
			$msgN="Votre devez recever un message dans votre boite émail!";
         }
		else
		{
			$msgN="Vous devez taper ton émail !";
		}
	}
?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Be Scientist</title>
    <link rel="stylesheet" href="styles/style.css">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.1/css/all.css">
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
		<div class="sm"><a href="#" class="barh" onclick="showSign(); signActive('in')">Se connecter <i class="fas fa-sign-in-alt" ></i> </a></div>
		<div class="sm"><a href="#" class="barh" onclick="showSign(); signActive('up')">Inscrire <i class="fa fa-user fa-lg fa-fw" aria-hidden="true"></i></a></div>
	</div>
	<!-- Menu principale -->
  <div class="header nav" id="nav">
    <h2 class="logo">be-scientist</h2>
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
			  <form class="contact-form" action="" method="post">
				<input type="text" name="nom" class="contact-form-text" placeholder="Your name">
				<input type="email" name="mail" class="contact-form-text" placeholder="Your email">
				<textarea name="message" class="contact-form-text" placeholder="Your message"></textarea>
				<input type="submit" name="mailform" class="contact-form-btn" value="Send">
			  </form>
			  <?php
				if(isset($msg))
				{
					echo $msg;
				}
				?>
		</div>
		<!-- News Letter -->
		<div >
			<form action="" method="post" id="idns">
			  <h1>Join Our Newsletter</h1>
			  <div class="border"></div>
			  <p>you will receive an alert in your gmail inbox each time a new volume is released.</p>
			  <div class="email-box">
				<i class="fas fa-envelope"></i>
				<input class="tbox" type="email" name="emailN" value="" placeholder="Enter your email">
				<button class="btn" type="submit" name="Newsletter">Subscribe</button>
			  </div>
			</form>
			 <?php
				if(isset($msgN))
				{
					echo $msgN;
				}
				?>
		</div>
	</div>
	<!-- Login  -->
		<div class="signContainer" id="signContainer">

        <div class="closeSign" onclick="hideSign()">
            <span></span>
            <span></span>
        </div>

        <section id="sign" class="sign-section">

            <div class="sign-box">

                <div class="sign-choice">
                    <h2 class="active" onclick="signActive('in')" id="signInToggler">Sign In</h2>
                    <h2 onclick="signActive('up')" id="signUpToggler">Sign Up</h2>
                </div>

                <div class="sign-option">
                    <div id="signInDiv" class="active">
                        <form action="actions/signin.php" method="POST">
                            <label for="signin_email">Email :</label>
                            <input type="email" name="signin_email" id="signin_email" required>

                            <label for="signin_password">Password :</label>
                            <input type="password" name="signin_password" id="signin_password" required>
                            <input type="submit" class="btl" name="signin_submit" value="Sign in" onclick="checkInputs(this, 'in')">

                            <p class="errors"id="signin_errors"><br></p>
                        </form>
                    </div>

                    <div id="signUpDiv">
                        <form action="actions/signup.php" method="POST">
                            <label for="signup_name">Name :</label>
                            <input type="text" name="signup_name" id="signup_name" required>

                            <label for="signup_email">Email :</label>
                            <input type="email" name="signup_email" id="signup_email" required>

                            <label for="signup_password">Password :</label>
                            <input type="password" name="signup_password" id="signup_password" required>

                            <div class="sign-selects">
                                <div>
                                    <label for="signup_type">Type :</label>
                                    <select name="signup_type" id="signup_type">
                                        <option value="A" selected>Author</option>
                                        <option value="R">Reviewer</option>
                                        <option value="E">Editor</option>
                                    </select>
                                </div>

                                <div>
                                    <label for="signup_job">Job :</label>
                                    <select name="signup_job" id="signup_job">
                                        <option value="Professor">Professor</option>
                                        <option value="Engineer">Ingineer</option>
                                        <option value="Student">Student</option>
                                        <option value="Others" selected>Others</option>
                                    </select>
                                </div>
                            </div>

                            <input type="submit" class="btl" name="signup_submit" value="Sign Up" onclick="checkInputs(this, 'up')">

                            <p class="errors" id="signup_errors"><br></p>

                        </form>
                    </div>
                </div>

            </div>

        </section>
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

	<script src="scripts/login.js"></script>
  </body>
</html>
