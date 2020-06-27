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

<!-- Menu principale -->
  <div class="header nav" id="nav">
    <h2 class="logo">be-scientist</h2>
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
		  <input type="submit" name="" value="Search" id="btnrch"></button>
		</form>
	  </div>
    </ul>
  </div>

<script src="../scripts/fixer_menu.js"></script>
	</body>
</html>
