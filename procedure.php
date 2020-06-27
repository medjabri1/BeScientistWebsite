<?php

	session_start();

	require 'vendor/autoload.php';

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

	//Initializing Variables
	$authorArticle = array();
	$articlesToCorrect = array();

	$articleToReview = array();
	$articleReviewedByMe = array();

	$waitingArticles = array();
	$inReviewerArticles = array();
	$reviewedArticles = array();
	$acceptedArticles = array();
	if(strtolower(get_class($user)) == 'author' || strtolower(get_class($user)) == 'reviewer') {

		$author = New Author();
		$reviewer = new Reviewer();

		$author->setId($user->getId());
		$author->setName($user->getName());
		$author->setEmail($user->getEmail());
		$author->setJob($user->getJob());
		$author->setCreatedAt($user->getCreatedAt());

		$reviewer->setId($user->getId());
		$reviewer->setName($user->getName());
		$reviewer->setEmail($user->getEmail());
		$reviewer->setJob($user->getJob());
		$reviewer->setCreatedAt($user->getCreatedAt());

		$authorArticle = $author->getMyArticles();
		$articlesToCorrect = $author->getArticleToCorrect();

		$articleToReview = $reviewer->getArticleToReview();
		$articleReviewedByMe = $reviewer->getArticleReviewed();

	}

	if(strtolower(get_class($user)) == 'editor') {

		$editor = Editor::findBy($user->getId());

		$waitingArticles = $editor->getWaitingArticles();
		$inReviewerArticles = $editor->getInReviewerArticles();
		$reviewedArticles = $editor->getArticleReviewed();
		$acceptedArticles = $editor->getArticleAccepted();
	}

?>

<!doctype html>
<html>
	<head>
		<meta charset="UTF-8">
		<title>Mes Articles</title>
		    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.5.0/css/all.css">
			<link rel="stylesheet" href="styles/style.css">
			<script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
			<script src="scripts/changer_mes_info.js"></script>
				<!-- Popup -->
		<script src="scripts/popup.js"></script>
	</head>
	<body>

	<div class="mh">
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
				info(9);
			  var currentele = $(this).html();
			  var ch=$(this).find('p');
			  test=ch.html();
			  $(".default_option li").html(currentele);
			  $(this).parents(".select_wrap").removeClass("active");

			  if(test==="Author"){
					 $("#IntD").css("visibility", "hidden");
					 $("#IntR").css("visibility", "hidden");
					 $("#IntA").css("visibility", "visible");
					 info(0);
			  }else{
				if(test==="Reviewer"){
					 $("#IntD").css("visibility", "hidden");
					 $("#IntA").css("visibility", "hidden");
					 $("#IntR").css("visibility", "visible");
					 info(5)
				}else{
					if(test==="Editor"){
						 $("#IntR").css("visibility", "hidden");
						 $("#IntA").css("visibility", "hidden");
						 $("#IntD").css("visibility", "visible");
						info(2);
					}
				}

			  }
			})

			let current = $(".default_option li div p").text().trim().toLowerCase();

			$("#IntR").css("visibility", "hidden");
			$("#IntA").css("visibility", "hidden");
			$("#IntD").css("visibility", "hidden");

			if(current==="author"){
					 $("#IntA").css("visibility", "visible");
					 info(0);
			  }else{
				if(current==="reviewer"){
					 $("#IntR").css("visibility", "visible");
					 info(5);
				}else{
					if(current==="editor"){
						 $("#IntD").css("visibility", "visible");
						 info(2);
					}
				}

			  }

		});

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
			<a href="index.php">ACCUEIL</a>
			<a href="index.php#services">SERVICES</a>
			<a href="inter_volume.php">DOWNLOAD/DISCOVER</a>
			<a href="index.php#contact">Contact</a>
      <label for="chk" class="hide-menu-btn">
        <i class="fas fa-times"></i>
      </label>

	   <div class="box" id="srch">
		<form >
		  <input type="text" name="" placeholder="Type..." id="rch">
		  <input type="submit" name="" value="Search" id="btnrch">
		</form>
	  </div>
    </ul>
  </div>

<script src="scripts/fixer_menu.js"></script>

	<!-- Gestion d'auteur -->
	<div id="IntA" >
		<h1 id="TIMA">Mes articles</h1>

		<div class="A_menu">
			<div class="champ" id="d1-champ" onclick="info(0)">Suivis mes articles <i class="fas fa-eye"></i></div>
			<div class="champ" id="d2-champ" onclick="info(7)">Corriger les articles  <i class="fas fa-edit"></i></div>
			<div class="champ" id="d2-champ" onclick="info(1)">Envoyer un nouveau Articles  <i class="fas fa-file-import"></i></div>

		</div>

		<table class='table' id="AI">
			<thead>
				<th>Titre</th>
				<th>Domaine</th>
				<th>Date de publication</th>
				<th>Status</th>
				<th>Décision final</th>
			</thead>
			<tbody>
				<?php if(count($authorArticle)) : ?>

					<?php foreach($authorArticle as $article) : ?>

						<tr>
						<td><?= $article->getTitle(); ?></td>
						<td><?= $article->getDomain(); ?></td>
						<td><?= $article->getCreatedAt(); ?></td>
						<td>
							<?php
								$status = strtoupper($article->getStatus());
								if($status == 'W' || $status == 'C' || $status == 'T') echo 'Pas encore';
								if($status == 'IR' || $status == 'RV') echo 'Verification';
								if($status == 'A' || $status == 'R' || $status == 'AJ') echo '<span style="color: green;">Finie</span>';
							?>
						</td>
						<td>
							<?php
								$status = strtoupper($article->getStatus());
								if($status == 'W' || $status == 'C' || $status == 'T') echo 'Pas encore';
								if($status == 'IR' || $status == 'RV') echo 'Pas encore';
								if($status == 'A') echo '<span style="color: green; font-weight: bold">Acceptée</span>';
								if($status == 'AJ') echo '<span style="color: green; font-weight: bold">Ajouter dans le volume</span>';
								if($status == 'R') echo '<span style="color: red; font-weight: bold">Rejetée</span>';
							?>
						</td>
						</tr>

					<?php endforeach; ?>

				<?php else : echo "<tr><td colspan='6'>You didnt submit any article</td></tr>"; endif; ?>
			</tbody>
		</table>

		<table class='table' id="AC">
			<thead>
				<th>Titre</th>
				<th>Domaine</th>
				<th>Date de publication</th>
				<th>Contenu</th>
				<th>Status</th>
				<th>Corriger</th>
			</thead>
			<tbody>
				<?php if(count($articlesToCorrect)) : ?>

					<?php foreach($articlesToCorrect as $article) : ?>

						<tr>
						<td><?= Article::findBy($article->getId())->getTitle() ?></td>
						<td><?= Article::findBy($article->getId())->getDomain() ?></td>
						<td><?= Article::findBy($article->getId())->getCreatedAt() ?></td>
						<td onclick="loadPDF(<?= $article->getId() ?>)"><a href="#" class="btnCtn IAL" >Afficher</a></td>
						<td><?= 'A corriger' ?></td>
						<td onclick="loadCorrect(<?= $article->getId() ?>, '<?= Article::findBy($article->getId())->getTitle() ?>', `<?= Article::findBy($article->getId())->getContent() ?>`)"><a href="#" class="btnCtn ICAR" >Corriger</a></td>

						</tr>

					<?php endforeach; ?>

				<?php else : echo "<tr><td colspan='6'>No articles to correct</td></tr>"; endif; ?>
			</tbody>
		</table>

		<form action="actions/addArticle.php" method="POST">
			<div class="wrapper" id="AP" style="margin-top: 30px">
				<div class="contact-form2">
					<div class="input-fields">
						<input type="text" class="input" placeholder="Title" name="add_title" required>
						<select class="input" name="add_domain" required>
								<option value="Computing">Computing</option>
								<option value="Mathematics">Mathematics</option>
								<option value="Physics">Physics</option>
								<option value="Divers">Divers</option>
						</select>
					</div>
					<div class="msg">
						<textarea placeholder="Message" style="resize: none" name="add_content" required></textarea>
						<input class="btn2" type="submit" value="send" name="add_submit" style="width: 100%">
					</div>
				</div>
			</div>
		</form>
	</div>
	<!-- Gestion d'admin -->
	<div id="IntD" >
		<h1 id="TSVA">Suivis la procedure</h1>

				<div class="A_menu">
					<div class="champ" id="d1-champ" onclick="info(2)">Les nouveaux articles <i class="fas fa-folder-plus"></i></div>
					<div class="champ" id="d2-champ" onclick="info(3)">Les articles envoyer <i class="fas fa-share-square"></i></div>
					<div class="champ" id="d2-champ" onclick="info(4)">Les articles vérifier <i class="fas fa-cogs"></i></div>
					<div class="champ" id="d2-champ" onclick="info(8)">Les articles Accepter <i class="fas fa-check-circle"></i></div>
				</div>


					<table class='table' id="DI">
							<thead>
								<th>Auteur</th>
								<th>Titre</th>
								<th>Domaine</th>
								<th>Date de publication</th>
								<th>Contenu</th>
								<th>Envoyer</th>
							</thead>
							<tbody>
								<?php if(count($waitingArticles)) : ?>

								<?php foreach($waitingArticles as $article) : ?>

									<tr>
									<td><?= User::findBy($article->getAuthorId())->getName() ?></td>
									<td><?= $article->getTitle() ?></td>
									<td><?= $article->getDomain() ?></td>
									<td><?= $article->getCreatedAt() ?></td>
									<td data-label='Le contenu' onclick="loadPDF(<?= $article->getId() ?>)"><a href="#" class="btnCtn IAL" >Afficher</a></td>
									<td data-label='Envoyer' onclick="sendArticle(<?= $article->getId() ?>)"><a href="#" class="BTNEN ICR">Envoyer</a></td>
									</tr>

								<?php endforeach; ?>

								<?php else : echo "<tr><td colspan='6'>No new articles</td></tr>"; endif; ?>
							</tbody>
					</table>
					<table class='table' id="DP" >
							<thead>
								<th>Auteur</th>
								<th>Reviewer</th>
								<th>Titre</th>
								<th>Domaine</th>
								<th>Date de publication</th>
								<th>Le contenu</th>
								<th>Date d'envoi</th>
							</thead>
							<tbody>
							<?php if(count($inReviewerArticles)) : ?>

								<?php foreach($inReviewerArticles as $article) : ?>

									<tr>
									<td><?= User::findBy($article->getAuthorId())->getName() ?></td>
									<td><?= User::findBy(ArticleToReview::findBy($article->getId())->getReviewerId())->getName() ?></td>
									<td><?= $article->getTitle() ?></td>
									<td><?= $article->getDomain() ?></td>
									<td><?= $article->getCreatedAt() ?></td>
									<td data-label='Le contenu' onclick="loadPDF(<?= $article->getId() ?>)"><a href="#" class="btnCtn IAL" >Afficher</a></td>
									<td><?= ArticleToReview::findBy($article->getId())->getCreatedAt() ?></td>
									</tr>

								<?php endforeach; ?>

								<?php else : echo "<tr><td colspan='6'>No articles sent</td></tr>"; endif; ?>
							</tbody>
					</table>
						<table class='table' id="DT">
							<thead>
								<th>Titre</th>
								<th>Domaine</th>
								<th>Date de publication</th>
								<th>Le contenu</th>
								<th>Date de verification</th>
								<th>Observation</th>
								<th>Décision final</th>
							</thead>
							<tbody>
							<?php if(count($reviewedArticles)) : ?>

							<?php foreach($reviewedArticles as $article) : ?>

								<tr>
								<td><?= Article::findBy($article->getId())->getTitle() ?></td>
								<td><?= Article::findBy($article->getId())->getDomain() ?></td>
								<td><?= Article::findBy($article->getId())->getCreatedAt() ?></td>
								<td data-label='Le contenu' onclick="loadPDF(<?= $article->getId() ?>)"><a href="#" class="btnCtn IAL" >Afficher</a></td>
								<td><?= $article->getCreatedAt() ?></td>
								<td data-label='Observation' onclick="showObservation('<?= ArticleReviewed::findBy($article->getId())->getObservation() ?>', '<?= Article::findBy($article->getId())->getTitle() ?>')"><a href="#" class="BTNEN ISO">Afficher</a></td>
								<td class="decisionTD">
									<form action="actions/articleDecision.php" method="POST">
										<div class="select-style">
											<select name="decision_decision" required>
												<option selected></option>
												<option value="A">Accepter</option>
												<option value="R">Rejeter</option>
												<option value="C">A corriger</option>
											</select>
										</div>
										<input type="hidden" name="decision_article" value="<?= $article->getId() ?>">
										<input type="hidden" name="decision_reviewer" value="<?= $article->getReviewerId() ?>">
										<input type="submit" value="Valider" name="decision_submit">
									</form>
								</td>
								</tr>

							<?php endforeach; ?>

							<?php else : echo "<tr><td colspan='6'>No articles verified</td></tr>"; endif; ?>
							</tbody>
					</table>
					<table class='table' id="AA">
							<thead>
								<th>Auteur</th>
								<th>Titre</th>
								<th>Domaine</th>
								<th>Date de publication</th>
								<th>Contenu</th>
							</thead>
							<tbody>
								<?php if(count($acceptedArticles)) : ?>

								<?php foreach($acceptedArticles as $article) : ?>

									<tr>
									<td><?= User::findBy($article->getAuthorId())->getName() ?></td>
									<td><?= Article::findBy($article->getId())->getTitle() ?></td>
									<td><?= Article::findBy($article->getId())->getDomain() ?></td>
									<td><?= Article::findBy($article->getId())->getCreatedAt() ?></td>
									<td data-label='Le contenu' onclick="loadPDF(<?= $article->getId() ?>)"><a href="#" class="btnCtn IAL" >Afficher</a></td>

									</tr>

									<?php
										endforeach;
										echo '</tbody>	</table><form action="actions/generate.php" method="POST"><center><input type="submit" id="BTNGEN" class="btnCtn" style="margin-top: 2%;" name="boutonGen" value="Generer les Articles dans un seul volume"></center>';
										
											echo "</form>";
									?>

								<?php else : echo "<tr><td colspan='6'>No articles accepted</td></tr></tbody>	</table>"; endif; ?>
	</div>

		<!-- Gestion du référé -->
		<div id="IntR" >
			<h1 id="TIREF">Mes demandes</h1>

				<div class="A_menu">
					<div class="champ" id="d1-champ" onclick="info(5)">Vérification des articles  <i class="fas fa-file-signature"></i></div>
					<div class="champ" id="d2-champ" onclick="info(6)">Les article verifiée <i class="fas fa-folder-minus"></i></div>

				</div>

					<table class='table' id="RI">
							<thead>
								<th>Auteur</th>
								<th>Titre</th>
								<th>Domaine</th>
								<th>Date de publication</th>
								<th>Le contenu</th>
								<th>Observation</th>
							</thead>
							<tbody>
							<?php if(count($articleToReview)) : ?>

							<?php foreach($articleToReview as $article) : ?>

								<tr>
								<td><?= User::findBy(Article::findBy($article->getId())->getAuthorId())->getName() ?></td>
								<td><?= Article::findBy($article->getId())->getTitle() ?></td>
								<td><?= Article::findBy($article->getId())->getDomain() ?></td>
								<td><?= Article::findBy($article->getId())->getCreatedAt() ?></td>
								<td data-label='Le contenu' onclick="loadPDF(<?= $article->getId() ?>)"><a href="#" class="btnCtn IAL" >Afficher</a></td>
								<td data-label='Observation' onclick="observationId(<?= $article->getId() ?>, '<?= Article::findBy($article->getId())->getTitle() ?>')"><a href="#" class="btnCtn ISO">Saisir</a></td>
								</tr>

							<?php endforeach; ?>

							<?php else : echo "<tr><td colspan='6'>No demands received</td></tr>"; endif; ?>
							</tbody>
					</table>
					<table class='table' id="RP" >
							<thead>
								<th>Auteur</th>
								<th>Titre</th>
								<th>Domaine</th>
								<th>Date de publication</th>
								<th>Le contenu</th>
								<th>Observation</th>
								<th>Date de vérification</th>
							</thead>
							<tbody>
							<?php if(count($articleReviewedByMe)) : ?>

							<?php foreach($articleReviewedByMe as $article) : ?>

								<tr>
								<td><?= User::findBy(Article::findBy($article->getId())->getAuthorId())->getName() ?></td>
								<td><?= Article::findBy($article->getId())->getTitle() ?></td>
								<td><?= Article::findBy($article->getId())->getDomain() ?></td>
								<td><?= Article::findBy($article->getId())->getCreatedAt() ?></td>
								<td data-label='Le contenu' onclick="loadPDF(<?= $article->getId() ?>)"><a href="#" class="btnCtn IAL" >Afficher</a></td>
								<td data-label='Observation' onclick="showObservation(`<?= ArticleReviewed::findBy($article->getId())->getObservation() ?>`, `<?= Article::findBy($article->getId())->getTitle() ?>`)"><a href="#" class="btnCtn ISO">Afficher</a></td>
								<td><?= $article->getCreatedAt() ?></td>
								</tr>

							<?php endforeach; ?>

							<?php else : echo "<tr><td colspan='6'>No demands received</td></tr>"; endif; ?>
								<!-- <tr>
									<td data-label='Auteur'>Auteur X</td>
									<td data-label='Titre'>prog_Java</td>
									<td data-label='Domaine'>Informatique</td>
									<td data-label='Date de publication'>01/01/2020</td>
									<td data-label='Le contenu'><a href="#" class="btnCtn IAL">Afficher</a></td>
									<td data-label='Observation'><a href="#" class="BTNEN IAO">Afficher</a></td>
									<td data-label='Date de vérification'>01/02/2020</td>
								</tr> -->
							</tbody>
					</table>
	</div>


		<!-- AFFICHER CONTENU -->
		<div class="bg-modal">
			<div class="modal-contents">

				<div class="close">+</div>
				<embed src="" type="application/pdf" width="90%" height="100%" id="articlePDF" />

			</div>
		</div>
		<!-- saisir observation -->
		<div id="ZS">
		<div class="modal-contents">

			<div class="close">+</div>
			<div class="observation-container">

				<form action="actions/reviewArticle.php" method="POST">
					<p id="observationTitle">Saisir observation</p>
					<textarea name="review_observation" id="review_observation" cols="30" rows="10" placeholder="Your observation goes here" required></textarea>
					<input type="hidden" name="review_article" id="review_article_id">
					<input type="submit" name="review_submit" id="review_submit" value="Send Observation">
				</form>

			</div>

		</div>
		</div>
		<!-- Choisir un réferé -->
		<div id="ZCR">
			<div class="modal-contents">
				<form action="actions/sendArticleToReview.php" method="POST">
					<div class="close">+</div>

					<div class="SLDR">
						<select name="send_reviewer">
							<?php

							$reviewers = Reviewer::findAll();

							foreach($reviewers as $reviewer) {

								echo "<option value='". $reviewer->getId() ."'>";
								echo $reviewer->getName();
								echo "</option>";

							}

							?>
						</select>
						<input type="hidden" name="send_article" value='' id="sendArticleID">
					</div>
					<input class="BTNCDR" type="submit" value='Valider votre choix' name="send_submit">
				</form>

			</div>
		</div>

		<!-- Afficher l'observation -->
		<!-- <div id="AO">
			<div class="modal-contents">

				<div class="close">+</div>
				<embed src="pdf/Cahier_De_Charge.pdf" type="application/pdf" width="90%" height="90%" />

			</div>
		</div> -->

		<!-- Corriger l'article -->
		<div id="CAR">
			<div class="modal-contents">

				<div class="close">+</div>
				<div class="correct-container">

					<form action="actions/correctArticle.php" method="POST">
						<p id="correctionTitle">Corriger l'article</p>
						<label for="correct_title">Title :</label>
						<input type="text" name="correct_title" id="correct_title" placeholder="Your title goes here..">

						<label for="correct_content">Content :</label>
						<textarea name="correct_content" id="correct_content" cols="30" rows="10" placeholder="Your Content goes here.." required></textarea>

						<input type="hidden" name="correct_article" id="correct_article_id">
						<input type="submit" name="correct_submit" id="correct_submit" value="Confirm Correction">
					</form>

				</div>

			</div>
		</div>
	</body>

	<script>

		let loadPDF = (id) => {
			let articlePDF = document.getElementById('articlePDF');
			articlePDF.setAttribute('src', 'uploads/'+ id +'.pdf');
		}

		let sendArticle = (id) => {
			let sendArticleID = document.getElementById('sendArticleID');
			sendArticleID.setAttribute('value', id);
		}

		let observationId = (id, title) => {
			document.getElementById('review_article_id').setAttribute('value', id);
			document.getElementById('observationTitle').innerHTML = "Saisir l'observation de l'article : <span>"+ title +"</span>";
			document.getElementById('review_observation').disabled = false;
			document.getElementById('review_submit').style.display = 'unset';
		}

		let showObservation = (observation, title) => {
		    console.log("rani hna");
			document.getElementById('observationTitle').innerHTML = "Observation de l'article : <span>"+ title +"</span>";
			document.getElementById('review_observation').textContent = observation;
			document.getElementById('review_observation').disabled = true;
			document.getElementById('review_submit').style.display = 'none';
		}

		let loadCorrect = (id, title, content) => {
			document.getElementById('correct_article_id').setAttribute('value', id);
			document.getElementById('correctionTitle').innerHTML = "Correction de l'article : <span>"+ title +"</span>";
			document.getElementById('correct_title').setAttribute('value', title);
			document.getElementById('correct_content').textContent = content;
		}

	</script>
</html>
