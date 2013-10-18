<?php

require_once("config.inc.php");
require_once("functions.inc.php");


$theme = $omv_theme;

$manga = null;
$manga_escaped = null;

$chapter = null;
$chapter_number = null;
$chapter_number_escaped = null;
$previous_chapter = null;
$next_chapter = null;

$page = null;

$description = "";
$title = $omv_title;

$mangas = omv_get_mangas();
if (isset($_GET["manga"])) {
	$manga_title = omv_decode($_GET["manga"]);
	if (in_array($manga_title, $mangas)) {
		$manga = $manga_title;
		$manga_escaped = $_GET["manga"];
	}
}

if ($manga) {
	$description = "Read " . $manga . " Manga Online";
	$title .= " - " . $manga;

	$chapters = omv_get_chapters($manga);
	if (isset($_GET["chapter"])) {
		$chapter_number = omv_decode($_GET["chapter"]);
		$index = omv_get_chapter_index($chapters, $chapter_number);
		if ($index != -1) {
			$chapter = $chapters[$index];
			$chapter_number_escaped = $_GET["chapter"];
			if ($omv_chapters_sorting == SORT_ASC) {
				if ($index > 0) {
					$previous_chapter = $chapters[$index - 1];
				}
				if ($index < (count($chapters) -  1)) {
					$next_chapter = $chapters[$index + 1];
				}
			} else {
				if ($index < (count($chapters) -  1)) {
					$previous_chapter = $chapters[$index + 1];
				}
				if ($index > 0) {
					$next_chapter = $chapters[$index - 1];
				}
			}
		}
	} else {
		$chapter = $chapters[0];
		$chapter_number = $chapters[0]["number"];
		$chapter_number_escaped = omv_encode($chapter_number);
		
		if (count($chapters) > 1) {
			if ($omv_chapters_sorting == SORT_ASC) {
				$next_chapter = $chapters[1];
			} else {
				$previous_chapter = $chapters[1];
			}
		}
	}

	if ($chapter) {
		$pages = omv_get_pages($manga, $chapter["folder"]);
		if (isset($_GET["page"])) {
			$_page = intval($_GET["page"]);
			if (($_page >= 1) && ($_page <= count($pages))) {
				$page = $_page;
			}
		} else if (count($pages) > 0) {
			$page = 1;
		}
		$title .= " - Chapter " . $chapter_number;

		if ($page) {
			$title .= " - Page " . $page;
		}
	}
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">

<head>
<base href="<?php echo $omv_base_url ?>" />
<meta name="Keywords" content="<?php echo str_replace(' ', ',', $description) ?>" />
<meta name="Description" content="<?php echo $description ?>" />
<meta http-equiv="Content-Type" content="application/xhtml+xml; charset=utf-8" />
<meta http-equiv="Content-Language" content="en" />
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?php echo $title ?></title>
<link rel="stylesheet" href="themes/<?php echo $theme ?>/css/style.css" type="text/css" media="screen" />
<link rel="stylesheet" href="themes/<?php echo $theme ?>/css/bootstrap.css" type="text/css" media="screen" />
<link rel="stylesheet" href="themes/<?php echo $theme ?>/csss/bootstrap-theme.css" type="text/css" media="screen" />

<script type="text/javascript">
function change_manga(manga) {
	if (manga != 0) {
		document.location = "<?php echo $omv_base_url ?>" + manga;
	}
}

function change_chapter(manga, chapter) {
	if (manga != 0) {
		document.location = "<?php echo $omv_base_url ?>" + manga + "/" + chapter;
	}
}

function change_page(manga, chapter, page) {
	if (manga != 0) {
		document.location = "<?php echo $omv_base_url ?>" + manga + "/" + chapter + "/" + page;
	}
}
</script>

</head>

<body>


    <!-- Wrap all page content here -->
    <div id="wrap">

      <!-- Fixed navbar -->
      <div class="navbar navbar-default navbar-fixed-top">
        <div class="container">
          <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="index.php">O.M.V</a>
          </div>
          <div class="collapse navbar-collapse">
            <ul class="nav navbar-nav">
              <li class="active"><a href="#">Home</a></li>
              <li><a href="#">Forum</a></li>
              <li><a href="#">Contact</a></li>
              <li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">Popular Manga <b class="caret"></b></a>
                <ul class="dropdown-menu">
                  <li><a href="/Naruto/">Naruto</a></li>
                  <li><a href="/One_Piece/">One Piece</a></li>
                  <li><a href="Bleach">Bleach</a></li>
                  <li><a href="Fairy_Tail">Fairy Tail</a></li>
                  <li class="divider"></li>
                  <li class="dropdown-header">Featured</li>
                  <li><a href="#">Hajime no Ippo</a></li>
                </ul>
              </li>
            </ul>
            <ul class="nav navbar-nav navbar-right">
                  <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">Credits <b class="caret"></b></a>
                    <ul class="dropdown-menu">
                      <li><a href="mailto:cool2309@gmail.com">Saif Lacrimosa</a></li>
                      <li><a href="http://www.free-tuts.tk">Free TUTS</a></li>
                    </ul>
                  </li>
                </ul>
          </div><!--/.nav-collapse -->
        </div>
    </div>
<?php
$omv_pager = "";

$omv_pager .= "<div class=\"pager well\">\n";

$omv_pager .= "<span class='lead text-primary'>Manga <select class='form-control' style='display:inline;width:220px;' name=\"manga\" onchange=\"change_manga(this.value)\">";
$omv_pager .= "<option value=\"0\">Select Manga Title...</option>";
for ($i = 0; $i < count($mangas); $i++) {
	$m = $mangas[$i];
	$omv_pager .= "<option value=\"" . omv_encode($m) . "\"" . (($m == $manga) ? " selected=\"selected\"" : "") . ">" . $m . "</option>";
}
$omv_pager .= "</select></span>\n";
?>
<div class='container'>
<?php
if ($manga) {
	if ($chapter) {
		$omv_pager .= "<span class='lead text-info'> Chapter <select class='form-control' style='display:inline;width:100px;' name=\"chapter\" onchange=\"change_chapter('$manga_escaped', this.value)\">";
		for ($i = 0; $i < count($chapters); $i++) {
			$cnumber = $chapters[$i]["number"];
			$omv_pager .= "<option value=\"" . omv_encode($cnumber) . "\"" . (($cnumber == $chapter_number) ? " selected=\"selected\"" : "") . ">" . $cnumber . (isset($chapters[$i]["title"]) ? (" - " . $chapters[$i]["title"]) : "") . "</option>";
		}
		$omv_pager .= "</select></span><div class='well'>";
		if ($page) {
			$prevhtml = "";
			if ($page <= 1) {
				$prevhtml = "<span class='glyphicon glyphicon-chevron-left btn btn-default btn-lg'></span>";
			} else {
				$prevhtml = "<a href=\"$manga_escaped/$chapter_number_escaped/" . ($page - 1) . "\"><span class='glyphicon glyphicon-chevron-left btn btn-danger btn-lg'></span></a>";
			}
			$nexthtml = "";
			if ($page >= count($pages)) {
				$nexthtml = "<span class='glyphicon glyphicon-chevron-right btn btn-default btn-lg'></span>";
			} else {
				$nexthtml = "<a href=\"$manga_escaped/$chapter_number_escaped/" . ($page + 1) . "\"><span class='glyphicon glyphicon-chevron-right btn btn-danger btn-lg'></span></a>";
			}

			$omv_pager .= "<span class='text-default lead'>$prevhtml Page <select class='form-control' style='display:inline;width:100px;' name=\"page\" onchange=\"change_page('$manga_escaped', '$chapter_number_escaped', this.value)\">";
			for ($p = 1; $p <= count($pages); $p++) {
				$omv_pager .= "<option value=\"" . $p . "\"" . (($p == $page) ? " selected=\"selected\"" : "") . ">#" . $p . "</option>";
			}
			$omv_pager .= "</select> of " . count($pages) . " $nexthtml</span>";
		}
	}
}
$omv_pager .= "</div>";

echo $omv_pager;
?>
<?php
if ($manga) {
	if ($chapter) {
		if ($page) {
			$img = "mangas/" . $manga . "/" . $chapter["folder"] . "/" . $pages[$page - 1];
			$imgsize = omv_get_image_size($img);
			$imghtml = "<div class='img panel panel-default'><img src=\"$img\" alt=\"\" width=\"" . $imgsize["width"] . "\" height=\"" . $imgsize["height"] . "\" data-zoom-image='$img'/></div>";
			
			$prev_page_path = omv_get_previous_page($manga_escaped, $chapter_number_escaped, $page, $previous_chapter);
			$next_page_path = omv_get_next_page($manga_escaped, $chapter_number_escaped, $page, count($pages), $next_chapter);


			if ($next_page_path) {
				$imghtml = "<a href=\"$next_page_path\">" . $imghtml . "</a>";
			}
			echo $imghtml;
		} else {
			echo "<div class=\"text-danger\">There is no selected page!</div>";
		}
	} else {
		echo "<div class=\"text-danger\">There is no selected chapter!</div>";
	}
} else {
	echo "<div class=\"text-danger\">Select a manga title to get started!</div>";
}
?>

<div class="ads panel panel-default">
<!-- Begin Advertisement -->
<img src="http://www.moto-jeux.fr/wp-content/themes/BoomWPA/images/728.png" alt="Ads" />
<!-- End Advertisement -->
</div>
</div>
<?php
if ($manga && $chapter && $page) {
?>
</div>
<script type="text/javascript">
function omvKeyPressed(e) {
	var keyCode = 0;
	
	if (navigator.appName == "Microsoft Internet Explorer") {
		if (!e) {
			var e = window.event;
		}
		if (e.keyCode) {
			keyCode = e.keyCode;
			if ((keyCode == 37) || (keyCode == 39)) {
				window.event.keyCode = 0;
			}
		} else {
			keyCode = e.which;
		}
	} else {
		if (e.which) {
			keyCode = e.which;
		} else {
			keyCode = e.keyCode;
		}
	}
	
	switch (keyCode) {
<?php
if ($prev_page_path) {
?>
		case 37:
		window.location = "<?php echo $omv_base_url . $prev_page_path ?>";
		return false;
		
<?php
}
if ($next_page_path) {
?>
		case 39:
		window.location = "<?php echo $omv_base_url . $next_page_path ?>";
		return false;
		
<?php
}
?>
		default:
		return true;
	}
}
document.onkeydown = omvKeyPressed;
</script>
<?php
echo $omv_pager;
?>
</td>
</tr>
<?php
} else {
?>
<tr>
<td><br /></td>
</tr>
<?php
}
?>
</div>
</div>
<div id="footer">
        <p class="text-muted credit">All Rights Reserved 2013-2014 &copy; - <a href="#"><?php echo $omv_credits; ?></a></p>
</div>

   <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
<script src="themes/<?php echo $theme ?>/js/bootstrap.js"></script>
<script src="themes/<?php echo $theme ?>/js/application.js"></script>
</html>
