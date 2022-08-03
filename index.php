<?php 
    require 'includes/database.php'; 
    $conn = getDB();

    $sql = "SELECT *
        FROM movies
        ORDER BY id;";

    $results = mysqli_query($conn, $sql);

    if ($results === false) {
        echo mysqli_error($conn);
    } else {
        $movies = mysqli_fetch_all($results, MYSQLI_ASSOC);
    }
?>

<?php require 'includes/header.php'; ?>
        <a href="charts.php">See movie charts</a>
            <?php if (empty($movies)): ?>
                <p>No movies found. =(</p>
            <?php else: ?>
                <h2>movies</h2>
            <ul>
                <?php foreach ($movies as $movie): ?>
                <li>
                    <movie>
                        <h3><a href="movie.php?id=<?= $movie['id']; ?>"><?= htmlspecialchars($movie['title']); ?></a></h3>
                        <!-- <p><?= htmlspecialchars($movie['content']); ?></p> -->
                    </movie>
                </li>
                <?php endforeach; ?>
            </ul>
            <?php endif; ?>
        </main>
    </body>
</html>