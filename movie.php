<?php 
    require 'includes/database.php';
    require 'includes/functions.php';
    $conn = getDB();
?>
<?php
    if(isset($_GET['id'])) {
        $movie = getMovie($conn, $_GET['id']);
    } else {
        $movie = null;
    }
?>
    <?php require 'includes/header.php'; ?>
            <?php if ($movie === null): ?>
                <p>movie not found. =(</p>
            <?php else: ?>
                <div class="movie" style="background-image: <?= $url . $movie['backdrop_path'] ?>;">
                <movie>
                    <h2><?= htmlspecialchars($movie['title']); ?></h2>
                    <p><img src="<?= $url . $movie['poster_path'] ?>"></p>
                    <p><?= htmlspecialchars($movie['overview']); ?></p>
                    <hr>
                    <p>Released: <?= htmlspecialchars($movie['release_date']); ?></p>
                </movie>
                </div>
            <?php endif ?>
        </main>
    </body>
</html>