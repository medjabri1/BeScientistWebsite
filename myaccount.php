<?php

	session_start();

    spl_autoload_register(function ($class_name) {
        include 'Classes/'. $class_name . '.php';
    });

    if(!isset($_SESSION['user_id'])) {
        header('Location: /');
        die();
    }

	if(isset($_POST['logout_button'])) {
		session_destroy();
		header('Location: /');
	}

	$user = User::findBy($_SESSION['user_id']);
	$is_admin = $user->getType() == 'E' ? true : false;

	$_SESSION['current_type'] = $user->getType();

	$type = User::$types[$_SESSION['current_type']];

?>

<!doctype html>
<html>
	<head>
		<meta charset="UTF-8">
		<title>My account</title>
		<link rel="stylesheet" href="styles/style.css">
		<script type="text/javascript">
				function chng(a){
					document.getElementById("MI").style.visibility="hidden";
					document.getElementById("MP").style.visibility="hidden";

					if(a===0){
						document.getElementById("MI").style.visibility="visible";
					}
					if(a===1){
						document.getElementById("MP").style.visibility="visible";
					}
				}

		</script>
	</head>
	<body>
		<!-- <object type="text/html" data="includes/menu.php" width="100%" ></object>-->
		<?php require_once('includes/menu.php'); ?>
		<?php include('includes/nav_bar.php'); ?>
		<script src="scripts/fixer_menu.js"></script>
		<h1 id="TPR">Mon Profil</h1>

		<div class="A_menu">
			<div class="champ" id="d1-champ" onclick="chng(0)">Mes informations </div>
			<div class="champ" id="d2-champ" onclick="chng(1)">Changer mon mot de passe </div>

		</div>

		  <div class="MonP" id="MI" >
				<form action="actions/updateUser.php" method="POST">
					<table class="MesI">
						<tr>
							<td>
								<label for="in">Votre nom <b>*</b> : </label><br>
								<input type="text" class="input2" placeholder="Name" id="in" value="<?= $user->getName() ?>" name="update_name" required>
							</td>
							<td>
								<label for="ie">Votre email <b>*</b> : </label><br>
								<input type="email" class="input2" placeholder="Email Address" id="ie" value="<?= $user->getEmail() ?>" name="update_email" required>
							</td>
							<td >
								<label for="im">Votre métier <b>*</b> : </label><br>
								<select class="input2" id="im" name="update_job">
									<option value="Professor" <?php if(strtolower($user->getJob()) == 'professor') echo 'selected'; ?> >Professor</option>
									<option value="Engineer" <?php if(strtolower($user->getJob()) == 'engineer') echo 'selected'; ?> >Ingineer</option>
									<option value="Student" <?php if(strtolower($user->getJob()) == 'student') echo 'selected'; ?> >Student</option>
									<option value="Others"  <?php if(strtolower($user->getJob()) == 'others') echo 'selected'; ?> >Others</option>
								</select>
							</td>
						</tr>
					</table>

					<input id="inscription" type="submit" class="mod_btn" value="Modifier" style="margin-top:10px;" name="update_submit">
				</form>

		  </div>
  <!-- Changer le mot de passe -->
	  <div class="MonP" id="MP">

		<form action="actions/updatePassword.php" method="POST">

			<label for="in2">Ancien mot de passe <b>*</b> : </label><br>
			<input type="password" class="input2" placeholder="Saisir votre ancien mot de passe" id="in2" name="password_old" required>
			<br>
			<label for="ie2">Nouveau mot de passe <b>*</b> : </label><br>
			<input type="password" class="input2" placeholder="Saisir votre nouveau mot de passe" id="ie2" name="password_new" required>
			<br>
			<label for="it2">Confirmez le mot de passe <b>*</b> : </label><br>
			<input type="password" class="input2" placeholder="Confirmez votre mot de passe" id="it2" name="password_confirm" required>
			<br>
			<input id="inscription" type="submit" class="mod_btn" style="margin-top:10px;" value="Modifier" name="password_submit">

		</form>

	</div>
	</body>
</html>
