<?php
$conn = mysqli_connect("localhost", "root", "", "api_movies");

function get_film($user_input)
{
    global $conn;
    $films = [];
    $sql = "";

    if ($user_input == null) {
        $sql = "SELECT * FROM movie";
    } else {
        $sql = "SELECT * FROM movie WHERE title LIKE '%$user_input%'";
    }

    $result = mysqli_query($conn, $sql);

    if ($result && mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $film_id = $row["id"];
            $film = $row;

            
            $sql = "SELECT actor.id, actor.first_name, actor.last_name, actor.birth_date FROM actor JOIN movie_actor ON movie_actor.movie_id = '$film_id' AND movie_actor.actor_id = actor.id";
            $result_actors = mysqli_query($conn, $sql);
            $actors = mysqli_fetch_all($result_actors, MYSQLI_ASSOC);
            $film["Actors"] = $actors;

            $sql = "SELECT director.id, director.first_name, director.last_name, director.birth_date FROM director JOIN movie_director ON movie_director.movie_id = '$film_id' AND movie_director.director_id = director.id";
            $result_directors = mysqli_query($conn, $sql);
            $directors = mysqli_fetch_all($result_directors, MYSQLI_ASSOC);
            $film["Directors"] = $directors;

            
            $sql = "SELECT genre.name FROM genre JOIN movie_genre ON movie_genre.movie_id = '$film_id' AND movie_genre.genre_id = genre.id";
            $result_genres = mysqli_query($conn, $sql);
            $genres = mysqli_fetch_all($result_genres, MYSQLI_ASSOC);
            $film["Genres"] = $genres;

            $films[] = $film;
        }
    }

    return $films;
}



function get_attori($user_input)
{
    global $conn;
    $actors = [];
    $sql = "";

    if ($user_input == null) {
        $sql = "SELECT * FROM actor";
    } else {
        $sql = "SELECT * FROM actor WHERE first_name LIKE '%$user_input%' OR last_name LIKE '%$user_input%'";
    }

    $result = mysqli_query($conn, $sql);

    if ($result && mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $actors[] = $row;
        }
    }

    return $actors;
}

function get_generi($user_input)
{
    global $conn;
    $genres = [];
    $sql = "";

    if ($user_input == null) {
        $sql = "SELECT * FROM genre";
    } else {
        $sql = "SELECT * FROM genre WHERE name LIKE '%$user_input%'";
    }

    $result = mysqli_query($conn, $sql);

    if ($result && mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $genres[] = $row;
        }
    }

    return $genres;
}

function get_registi($user_input)
{
    global $conn;
    $directors = [];
    $sql = "";

    if ($user_input == null) {
        $sql = "SELECT * FROM director";
    } else {
        $sql = "SELECT * FROM director WHERE first_name LIKE '%$user_input%' OR last_name LIKE '%$user_input%'";
    }

    $result = mysqli_query($conn, $sql);

    if ($result && mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $directors[] = $row;
        }
    }

    return $directors;
}

function get_users($user_input)
{
    global $conn;
    $users = [];
    $sql = "";

    if ($user_input == null) {
        $sql = "SELECT * FROM user";
    } else {
        $sql = "SELECT * FROM user WHERE first_name LIKE '%$user_input%' OR last_name LIKE '%$user_input%' OR email LIKE '%$user_input%'";
    }

    $result = mysqli_query($conn, $sql);

    if ($result && mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $users[] = $row;
        }
    }

    return $users;
}

function create_user($nome, $cognome, $email, $password)
{
    global $conn;

    $existing_user_sql = "SELECT * FROM user WHERE email='$email'";
    $existing_user_result = mysqli_query($conn, $existing_user_sql);
    if ($existing_user_result && mysqli_num_rows($existing_user_result) > 0) {
        return "Utente già esistente";
    }

    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    $registration_date = date("Y-m-d"); 

    $insert_user_sql = "INSERT INTO user (first_name, last_name, email, password, registration_date) VALUES ('$nome', '$cognome', '$email', '$hashed_password', '$registration_date')";
    $insert_user_result = mysqli_query($conn, $insert_user_sql);

    if ($insert_user_result) {
        return "Utente creato con successo";
    } else {
        return "Si è verificato un errore durante la creazione dell'utente";
    }
}

function get_attori_by_film($film)
{
    global $conn;
    $actors = [];
    $film_ids = [];

    $film_data = json_decode($film, true);

    foreach ($film_data as $entry) {
        $film_ids[] = $entry["id"];
    }

    foreach ($film_ids as $film_id) {
        $sql = "SELECT actor.id, actor.first_name, actor.last_name, actor.birth_date 
                FROM actor 
                JOIN movie_actor ON movie_actor.movie_id = '$film_id' AND movie_actor.actor_id = actor.id";
        $result = mysqli_query($conn, $sql);

        if ($result && mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                $actors[] = $row;
            }
        }
    }

    return $actors;
}
