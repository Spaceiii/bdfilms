<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "bdfilms";

$query = isset($_GET["q"]) && $_GET["q"] != "";
$categories = isset($_GET["c"]) && $_GET["c"] != "";

$conn = mysqli_connect($servername, $username, $password, $dbname);

if (!$conn) {
    die("connection échouée");
}

if ($query) {
    $request = "SELECT f.title, f.release_year, f.genres, r.director FROM films AS f, realisateurs AS r WHERE r.movie_id = f.movie_id AND f.title LIKE '". $_GET["q"] ."%' LIMIT 20";
    if ($categories) {
        $request = "SELECT f.title, f.release_year, f.genres, r.director FROM films AS f, realisateurs AS r WHERE r.movie_id = f.movie_id AND f.title LIKE ". $_GET["q"] ."%' LIMIT 50";
    }

    $result = mysqli_query($conn, $request);

    if (!$result) {
        die("Erreur MySQLi : " . mysqli_error($conn));
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BD Films</title>
</head>
<body>
    <h1>BD Film</h1>
    <h2>Les meilleurs films de l'année</h2>

    <form action="<?= $_SERVER["SCRIPT_NAME"] ?>" method="get">
        <label for="q_title">Titre :</label>
        <input type="text" id="q_title" name="q">
        <label for="q_categories">catégories :</label>
        <select id="q_categories" name="c">
            <option value="">---</option>
            <option value="action">Action</option>
            <option value="science fiction">Science Fiction</option>
            <option value="adventure">Aventure</option>
            <option value="comedy">Comédie</option>
            <option value="family">Familial</option>
            <option value="thriller">Thriller</option>
        </select>
        <input type="submit" value="Envoyer">
    </form>
    <?php if ($query && mysqli_num_rows($result) > 0): ?>
        <h3>Résultats :</h3>
        <?php while($row = mysqli_fetch_assoc($result)) : ?>
            <?php if (!$categories) : ?>
                <p>
                    <?= $row["title"] ?>, <?= $row["release_year"] ?>, <?= $row["director"] ?>
                <?php elseif(strpos(strtolower($row["genres"]), strtolower($_GET["c"]))) : ?>
                    <?= $row["title"] ?>, <?= $row["release_year"] ?>, <?= $row["director"] ?>
                </p>
            <?php endif ?>
        <?php endwhile ?>
    <?php elseif ($query): ?>
        <p>Pas de résultat pour "<?= $_GET["q"] ?>"</p>
    <?php else : ?>
        <p>Entrer une recherche ...</p>
    <?php endif ?>
</body>
</html>
<?php 
mysqli_close($conn);
?>