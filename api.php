<?php
include_once 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    if ($_SERVER['PATH_INFO'] == '/movies') {
        if (isset($_GET['titolo'])) {
            $film = json_encode(get_film($_GET['titolo']));
            echo $film;
        } else {
            echo json_encode(get_film(null));
        }
    } elseif ($_SERVER['PATH_INFO'] == '/actors') {
        if (isset($_GET['attore'])) {
            echo json_encode(get_attori($_GET['attore']));
        } else {
            echo json_encode(get_attori(null));
        }
    } elseif ($_SERVER['PATH_INFO'] == '/genres') {
        if (isset($_GET['genere'])) {
            echo json_encode(get_generi($_GET['genere']));
        } else {
            echo json_encode(get_generi(null));
        }
    } elseif ($_SERVER['PATH_INFO'] == '/directors') {
        if (isset($_GET['regista'])) {
            echo json_encode(get_registi($_GET['regista']));
        } else {
            echo json_encode(get_registi(null));
        }
    } elseif ($_SERVER["PATH_INFO"] == '/users') {
        if (isset($_GET['utente'])) {
            echo json_encode(get_users($_GET['utente']));
        } else {
            echo json_encode(get_users(null));
        }
    }
} elseif ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if ($_SERVER['PATH_INFO'] == '/users') {
        $nome = $_POST['nome'];
        $cognome = $_POST['cognome'];
        $email = $_POST['email'];
        $password = $_POST['password'];

        $result = create_user($nome, $cognome, $email, $password);
    }
} else {
    http_response_code(404);
};