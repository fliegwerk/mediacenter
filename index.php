<?php
/*
 * fliegwerk mediacenter
 * by fliegwerk
 * (c) 2020. MIT License
 */

require "config.php";
require "search.php";

$movies_path = glob("movies/*");

function get_json_from_file($path) {
    return file_exists($path) ?
        json_decode(file_get_contents($path)) : (object)[];
}

?>
<!doctype html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="robots" content="noindex, nofollow">
    <title>Mediathek</title>
    <link href="main.css" type="text/css" rel="stylesheet">
</head>
<body>
<header>
    <h1>Mediathek</h1>
    <form method="get">
        <label for="search">Suche nach:</label>
        <input type="search" id="search" name="<?= GET_PARAM_NAME_QUERY ?>"
               placeholder="James Bond" value="<?= $_GET[GET_PARAM_NAME_QUERY] ?? "" ?>">
        <button type="submit">Suchen</button>
    </form>
</header>
<?php
if (isset($_GET[GET_PARAM_NAME_VIDEO])):
    ?>
    <main>
        <?php
        $movie_path = urldecode($_GET[GET_PARAM_NAME_VIDEO]);
        $found = array_search($movie_path, $movies_path);

        if ($found !== false):
            $data = get_json_from_file($movie_path . "/data.json");
            ?>
            <video width="100%" controls preload="metadata"
                   src="<?= $movie_path ?>/video.mp4" poster="<?= $movie_path ?>/artwork.jpg">
            </video>
            <h2><?= $data->title ?? "Unbekannter Titel" ?><br><span><?= $data->subtitle ?? "" ?></span></h2>
            <p><?= empty($data->release) ? "Unbekannt" :
                    DateTime::createFromFormat(DateTime::ISO8601, $data->release)->format("Y") ?>:
                <i><?= $data->director ?? "Unbekannter Regisseur" ?></i></p>
            <p><?= $data->description ?? "N/A" ?></p>
            <p><small><?= $data->copyright ?? "" ?></small></p>
        <?php else: ?>
            <p>Video wurde nicht gefunden</p>
        <?php endif; ?>
    </main>
<?php endif; ?>

<nav>
    <?php
    // build combined array
    $movies_data = array();
    foreach ($movies_path as $movie_path) {
        $data = get_json_from_file($movie_path . "/data.json");
        $movies_data[] = (object)[
            "path" => $movie_path,
            "title" => $data->title ?? "Unbekannter Titel",
        ];
    }
    $sorted_data = isset($_GET[GET_PARAM_NAME_QUERY]) ?
        search($movies_data, $_GET[GET_PARAM_NAME_QUERY]) : $movies_data;

    if (count($sorted_data) > 0):
        ?>
        <ul>
            <?php foreach ($sorted_data as $movie_data):
                ?>
                <li>
                    <a href="?<?= GET_PARAM_NAME_VIDEO ?>=<?= urlencode($movie_data->path) ?>">
                        <img loading="lazy" alt="<?= $movie_data->title ?>"
                             src="<?= $movie_data->path ?>/cover.jpg">
                        <span><?= $movie_data->title ?></span>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php else:
        ?>
        <p>Keine Eintr&auml;ge gefunden</p>
    <?php endif; ?>
</nav>
</body>
</html>
