<?php 
$host = '127.0.0.1';
$db   = 'netland';
$user = 'root';
$pass = '';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];
try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    throw new \PDOException($e->getMessage(), (int)$e->getCode());
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body style="display: flex; flex-direction: row;">
    <div class="left" style="display: flex; width: 50%; flex-direction: column;">
        <h1>Welkom op het netland beheerderpaneel</h1>
        <a href="login.php"><button>Login</button></a>
        <h1>Onze Media:</h1>
        <a href="index.php">Refresh Pagina</a>
        <table style="width: 55%; height: 60%;">
            <tr>
                <th>Soort</th>
                <th>Titel</th>
                <th>Duur/Seizoenen</th>
                <th>Tools</th>
            </tr>
            <!-- Hier worden de films/series laten zien -->
            <?php
                $stmt = $pdo->query('SELECT * FROM series');
                while ($row = $stmt->fetch()) : ?> 
                    <tr>
                        <td>Serie</td>
                        <td><?= $row['title'] ?></td> 
                        <td><?= $row['seasons'] ?></td>
                        <td><form action="index.php" method="post"><button type="submit" name="details_series" value="<?= $row['id'] ?>" >Details</button></form></td>
                        <td><form action="index.php" method="post"><button type="submit" name="wijzig_series" value="<?= $row['id'] ?>" >Wijzig</button></form></td>
                    </tr>
                <?php endwhile;
                $stmt = $pdo->query('SELECT * FROM movies');
                while ($row = $stmt->fetch()) : ?> 
                    <tr>
                        <td>Film</td>
                        <td><?= $row['title'] ?></td> 
                        <td><?= $row['duur'] ?></td>
                        <td><form action="index.php" method="post"><button type="submit" name="details_films" value="<?= $row['id'] ?>" >Details</button></form></td>
                        <td><form action="index.php" method="post"><button type="submit" name="wijzig_films" value="<?= $row['id'] ?>" >Wijzig</button></form></td>
                    </tr>
                <?php endwhile; ?>
        </table>
        <form action="index.php" method="post"><button type="submit" name="add_serie">Serie toevoegen</button></form>
        <form action="index.php" method="post"><button type="submit" name="add_film">Film toevoegen</button></form>
    </div>

    <div class="right" style="display: flex; width: 50%; flex-direction: column;">
    <!-- Hier is de code om de films te wijzigen -->
    <?php
    if (isset($_POST['wijzig_films'])) :
        $id = $_POST['wijzig_films'];

        $stmt = $pdo->prepare("SELECT * FROM movies WHERE id= :id"); 
        $stmt->bindParam(':id', $id);
        $stmt->execute(); 
        while ($row = $stmt->fetch()) : ?>
            <h1>Bewerk hier jou film:</h1>
            <h1><?= $row['title']?> - <?= $row['duur'] ?> minuten</h1>
            <form action="index.php" method="post">
                <div style="display: flex; align-items:center; height: 20px;"><h2>Titel-</h2><input type="text" value="<?= $row['title']?>" name="title" id=""></div><br>
                <div style="display: flex; align-items:center; height: 20px;"><h2>Duur-</h2><input type="text" value="<?= $row['duur']?>" name="duur" id=""></div><br>
                <div style="display: flex; align-items:center; height: 20px;"><h2>Datum van uitkomst-</h2><input type="text" value="<?= $row['datum_van_uitkomst']?>" name="datum_van_uitkomst" id=""></div><br>
                <div style="display: flex; align-items:center; height: 20px;"><h2>Land van uitkomst-</h2><input type="text" value="<?= $row['land_van_uitkomst']?>" name="land_van_uitkomst" id=""></div><br>
                <div style="display: flex; align-items:center; height: 50px;"><h2>Omschrijving-</h2><textarea rows="4" cols="50" type="text" name="description" id=""><?= $row['description']?></textarea></div><br>
                <div style="display: flex; align-items:center; height: 20px;"><h2>Youtube Trailer id-</h2><input type="text" value="<?= $row['youtube_trailer_id']?>" name="youtube_trailer_id" id=""></div><br>
                <button type="submit" value="<?= $id ?>" name="verander_movie">Wijzig</button>
            </form>
        <?php endwhile; ?>
    <?php endif;

    if (isset($_POST['verander_movie'])) {
        $id = $_POST['verander_movie'];
        $title = $_POST['title'];
        $duur = $_POST['duur'];
        $datum_van_uitkomst = $_POST['datum_van_uitkomst'];
        $land_van_uitkomst = $_POST['land_van_uitkomst'];
        $description = $_POST['description'];
        $youtube_trailer_id = $_POST['youtube_trailer_id'];

        $sql = "UPDATE movies SET title = ?, duur = ?, datum_van_uitkomst = ?, land_van_uitkomst = ?, description = ?, youtube_trailer_id = ? WHERE id = ?";
        $pdo->prepare($sql)->execute([$title, $duur, $datum_van_uitkomst, $land_van_uitkomst, $description, $youtube_trailer_id, $id]);
    }
    ?>

    <!-- Hier is de code om de series te wijzigen -->
    <?php
    if (isset($_POST['wijzig_series'])) :
        $id = $_POST['wijzig_series'];

        $stmt = $pdo->prepare("SELECT * FROM series WHERE id= :id"); 
        $stmt->bindParam(':id', $id);
        $stmt->execute(); 
        while ($row = $stmt->fetch()) : ?>
            <h1>Bewerk hier jou serie:</h1>
            <h1><?= $row['title']?> - <?= $row['rating'] ?></h1>
            <form action="index.php" method="post">
                <div style="display: flex; align-items:center; height: 20px;"><h2>Titel-</h2><input type="text" value="<?= $row['title']?>" name="title" id=""></div><br>
                <div style="display: flex; align-items:center; height: 20px;"><h2>Rating-</h2><input type="text" value="<?= $row['rating']?>" name="rating" id=""></div><br>
                <div style="display: flex; align-items:center; height: 20px;"><h2>Awards-</h2><input type="text" value="<?= $row['has_won_awards']?>" name="has_won_awards" id=""></div><br>
                <div style="display: flex; align-items:center; height: 20px;"><h2>Seizoen-</h2><input type="text" value="<?= $row['seasons']?>" name="seasons" id=""></div><br>
                <div style="display: flex; align-items:center; height: 20px;"><h2>Country-</h2><input type="text" value="<?= $row['country']?>" name="country" id=""></div><br>
                <div style="display: flex; align-items:center; height: 20px;"><h2>Language-</h2><input type="text" value="<?= $row['language']?>" name="language" id=""></div><br>
                <div style="display: flex; align-items:center; height: 50px;"><h2>Omschrijving-</h2><textarea rows="4" cols="50" type="text" name="description" id=""><?= $row['description']?></textarea></div><br>
                <button type="submit" value="<?= $id ?>" name="verander_serie">Wijzig</button>
            </form>
        <?php endwhile; ?>
    <?php endif; 

    if (isset($_POST['verander_serie'])) {
        $id = $_POST['verander_serie'];
        $title = $_POST['title'];
        $rating = $_POST['rating'];
        $has_won_awards = $_POST['has_won_awards'];
        $seasons = $_POST['seasons'];
        $country = $_POST['country'];
        $language = $_POST['language'];
        $description = $_POST['description'];

        $sql = "UPDATE series SET title = ?, rating = ?, has_won_awards = ?, seasons = ?, description = ?, language = ?, country = ? WHERE id = ?";
        $pdo->prepare($sql)->execute([$title, $rating, $has_won_awards, $seasons, $description, $language, $country, $id]);
    }
    // Hier is de code can de details van de films
    if (isset($_POST['details_films'])) :
        $id = $_POST['details_films'];

        $stmt = $pdo->prepare("SELECT * FROM movies WHERE id= :id"); 
        $stmt->bindParam(':id', $id);
        $stmt->execute(); 
        while ($row = $stmt->fetch()) : ?>
            <h1><?= $row['title']?> - <?= $row['duur'] ?> minuten</h1>
            <div><h2>Datum van uitkomst: <?= $row['datum_van_uitkomst'] ?></h2></div>
            <div><h2>Land van uitkomst: <?= $row['land_van_uitkomst'] ?></h2></div>
            <div><h3><?= $row['description'] ?><h3></td></div>
            <div><iframe width="720" height="480" src="https://www.youtube.com/embed/<?= $row['youtube_trailer_id'] ?>" frameborder="0" allowfullscreen></iframe></div>
        <?php endwhile; ?>
    <?php endif; ?>
    <!-- Hier is de code van de details van de series -->
    <?php
    if (isset($_POST['details_series'])) :
        $id = $_POST['details_series'];

        $stmt = $pdo->prepare("SELECT * FROM series WHERE id= :id"); 
        $stmt->bindParam(':id', $id);
        $stmt->execute(); 
        while ($row = $stmt->fetch()) : ?>
            <h1><?= $row['title']?> - <?= $row['rating'] ?></h1>
            <div><h3>Awards: <?= $row['has_won_awards'] ?></h3></div>
            <div><h3>Seasons <?= $row['seasons'] ?></h3></div>
            <div><h3>Country <?= $row['country'] ?><h3></div>
            <div><h3>Language <?= $row['language'] ?><h3></div>
            <div><h3><?= $row['description'] ?><h3></div>
            <div><iframe width="720" height="480" src="https://www.youtube.com/embed/<?= $row['youtube_trailer_id'] ?>" frameborder="0" allowfullscreen></iframe></div>
        <?php endwhile; ?>
    <?php endif; ?>
    <!-- Hier is de code om nieuwe films toetevoegen -->
    <?php if (isset($_POST['add_film'])) : ?>
        <form action="index.php" method="post">
            <h1>Film toevoegen:</h1>
            <div style="display: flex; align-items:center; height: 20px;"><h2>Titel-</h2><input type="text" name="title" id=""></div><br>
            <div style="display: flex; align-items:center; height: 20px;"><h2>Duur-</h2><input type="text" name="duur" id=""></div><br>
            <div style="display: flex; align-items:center; height: 20px;"><h2>Datum van uitkomst-</h2><input type="text" name="datum_van_uitkomst" id=""></div><br>
            <div style="display: flex; align-items:center; height: 20px;"><h2>Land van uitkomst-</h2><input type="text" name="land_van_uitkomst" id=""></div><br>
            <div style="display: flex; align-items:center; height: 20px;"><h2>Youtube Trailer id-</h2><input type="text" name="youtube_trailer_id" id=""></div><br>
            <div style="display: flex; align-items:center; height: 50px;"><h2>Omschrijving-</h2><textarea rows="4" cols="50" type="text" name="description" id=""></textarea></div><br>
        <button type="submit" name="submit">Toevoegen</button>
        </form>
    <?php endif; ?>

    <?php
    if (isset($_POST['submit'])) {
        $title = $_POST['title'];
        $duur = $_POST['duur'];
        $datum_van_uitkomst = $_POST['datum_van_uitkomst'];
        $land_van_uitkomst = $_POST['land_van_uitkomst'];
        $description = $_POST['description'];
        $youtube_trailer_id = $_POST['youtube_trailer_id'];

        $sql = "INSERT INTO movies(title, duur, datum_van_uitkomst, land_van_uitkomst, description, youtube_trailer_id) VALUES (?,?,?,?,?,?);";
        $stmt= $pdo->prepare($sql);
        $stmt->execute([$title, $duur, $datum_van_uitkomst, $land_van_uitkomst, $description, $youtube_trailer_id]);
    }
    ?>
    <!-- Hier de code om nieuwe series toetevoegen -->
    <?php if (isset($_POST['add_serie'])) : ?>
        <form action="index.php" method="post">
            <h1>Serie toevoegen:</h1>
            <div style="display: flex; align-items:center; height: 20px;"><h2>Titel-</h2><input type="text" name="title" id=""></div><br>
            <div style="display: flex; align-items:center; height: 20px;"><h2>Rating-</h2><input type="text" name="rating" id=""></div><br>
            <div style="display: flex; align-items:center; height: 20px;"><h2>Awards-</h2><input type="text" name="has_won_awards" id=""></div><br>
            <div style="display: flex; align-items:center; height: 20px;"><h2>Seizoen-</h2><input type="text" name="seasons" id=""></div><br>
            <div style="display: flex; align-items:center; height: 20px;"><h2>Country-</h2><input type="text" name="country" id=""></div><br>
            <div style="display: flex; align-items:center; height: 20px;"><h2>Language-</h2><input type="text" name="language" id=""></div><br>
            <div style="display: flex; align-items:center; height: 50px;"><h2>Omschrijving-</h2><textarea rows="4" cols="50" type="text" name="description" id=""></textarea></div><br>
            <button type="submit" name="submit">Toevoegen</button>
        </form>
    <?php endif; ?>

    <?php
    if (isset($_POST['submit'])) {
        $title = $_POST['title'];
        $rating = $_POST['rating'];
        $has_won_awards = $_POST['has_won_awards'];
        $seasons = $_POST['seasons'];
        $country = $_POST['country'];
        $language = $_POST['language'];
        $description = $_POST['description'];

        $sql = "INSERT INTO series(title, rating, has_won_awards, seasons, description, language, country) VALUES (?,?,?,?,?,?,?); ";
        $stmt= $pdo->prepare($sql);
        $stmt->execute([$title, $rating, $has_won_awards, $seasons, $description, $language, $country]);
    }
    ?>

</div>
</body>

</html>