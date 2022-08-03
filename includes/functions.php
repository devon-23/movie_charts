<?php

    /**
     * @param object $conn Connection to the database
     * @param object $id the article id
     * 
     * @return mixed An associative array containing the article with that id, or null if not found
     */

    function getMovie($conn, $id) {
        $sql = "SELECT *
                FROM movies
                WHERE id  = ?";
        
        $stmt = mysqli_prepare($conn, $sql);

        if ($stmt === false) {
            echo mysqli_error($conn);
        } else {
            mysqli_stmt_bind_param($stmt, "i", $id);

            if(mysqli_stmt_execute($stmt)) {
                $result = mysqli_stmt_get_result($stmt);
                return mysqli_fetch_array($result, MYSQLI_ASSOC);
            }
        }
    }

    function getTop($conn) {
        $sql = "SELECT id, title, release_date, original_language, popularity, vote_count FROM movies ORDER BY vote_count DESC";

        $results = mysqli_query($conn, $sql);

        if ($results === false) {
            echo mysqli_error($conn);
        } else {
            return mysqli_fetch_all($results, MYSQLI_ASSOC);
        }
    }

    function getLang($conn) {
        $sql = "SELECT original_language, COUNT(*) FROM movies GROUP BY original_language";

        $results = mysqli_query($conn, $sql);

        if ($results === false) {
            echo mysqli_error($conn);
        } else {
            return mysqli_fetch_all($results, MYSQLI_ASSOC);
        }
    }
?>