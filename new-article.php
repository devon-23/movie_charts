<?php
    require 'includes/database.php';

    $errors = [];
    $title = '';
    $content = '';
    $published_at = '';

    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        $title = $_POST['title'];
        $content = $_POST['content'];
        $published_at = $_POST['published_at'];

        if($title == '') {
            $errors[] = 'title is required';
        }

        if ($published_at != '') {
            $date_time = date_create_from_format('Y-m-d\TH:i', $published_at);

            if ($date_time === false) {
                $errors[] = 'Invalid date and time.';
            } else {
                $date_errors = date_get_last_errors();
                if ($date_errors['warning_count'] > 0) {
                    $errors[] = 'Invalid date and time.';
                }
            }
        }

        if($content == '') {
            $errors[] = 'content is required';
        }

        if(empty($errors)) {
            $conn = getDB();
            $sql = "INSERT INTO article (title, content, published_at)
                VALUES (?, ?, ?)";
        
            $stmt = mysqli_prepare($conn, $sql);

            if ($stmt === false) {
                echo mysqli_error($conn);
            } else {
                if ($published_at == '') {
                    $published_at = null;
                }

                mysqli_stmt_bind_param($stmt, "sss", $title, $content, $published_at);

                if(mysqli_stmt_execute($stmt)) {
                    $id = mysqli_insert_id($conn);

                    if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') {
                        $protocol = 'https';
                    } else {
                        $protocol = 'http';
                    }
                    //This is because the files are in the ~barcla29 directory. Otherwise, you will get a file not found.
                    header("Location: $protocol://" . $_SERVER['HTTP_HOST'] . "/article.php?id=$id");
                    exit;
                } else {
                    echo mysqli_stmt_error($stmt);
                }
            }
        }
    }
?>

    <?php require 'includes/header.php'; ?>
        <h2>New Article</h2>

        <?php if(!empty($errors)): ?>
            <ul>
                <?php foreach($errors as $error): ?>
                    <li><?= $error ?></li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>

            <form method="POST">
                <div>
                    <label for="title">Title</label>
                    <input name="title" id="title" placeholder="Article title" value="<?= htmlspecialchars($title); ?>">
                </div>
                <div>
                    <label for="content">Content</label>
                    <textarea name="content" rows="4" cols="40" id="content" placeholder="Article Content"><?= htmlspecialchars($content); ?></textarea>
                </div>
                <div>
                    <label for="published_at">Publication Date and Time</label>
                    <input type="datetime-local" name="published_at" id="publish_at" value="<?= htmlspecialchars($published_at); ?>">
                </div>
                <button>Add</button>
            </form>
        </main>
    </body>
</html>