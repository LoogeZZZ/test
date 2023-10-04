<?php

// Подключение к базе данных MySQL
$servername = '127.0.0.3';
$username = 'root';
$password = 'Azazell1245781!';
$dbname = 'tasks';

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die('Connection failed: ' . $conn->connect_error);
}

// GET-маршрут для получения списка задач
if ($_SERVER['REQUEST_METHOD'] === 'GET' && $_SERVER['REQUEST_URI'] === '/api/tasks') {
    $sql = 'SELECT * FROM tasks';
    $result = $conn->query($sql);

    $tasks = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $tasks[] = $row;
        }
    }

    header('Content-Type: application/json');
    echo json_encode($tasks);
    exit;
}

// POST-маршрут для создания новой задачи
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_SERVER['REQUEST_URI'] === '/api/tasks') {
    $data = json_decode(file_get_contents('php://input'), true);
    $title = $data['title'] ?? '';

    $sql = "INSERT INTO tasks (title) VALUES ('$title')";
    if ($conn->query($sql) === TRUE) {
        header('Content-Type: application/json');
        echo json_encode(['message' => 'Task created']);
    } else {
        http_response_code(500);
        echo 'Error: ' . $conn->error;
    }
    exit;
}
// PUT-маршрут для обновления задачи
if ($_SERVER['REQUEST_METHOD'] === 'PUT' && $_SERVER['REQUEST_URI'] === '/api/tasks') {
    $data = json_decode(file_get_contents('php://input'), true);
    $id = $data['id'] ?? '';
    $title = $data['title'] ?? '';

    $sql = "UPDATE tasks SET title='$title' WHERE id='$id'";
    if ($conn->query($sql) === TRUE) {
        header('Content-Type: application/json');
        echo json_encode(['message' => 'Task updated']);
    } else {
        http_response_code(500);
        echo 'Error: ' . $conn->error;
    }
    exit;
}
// DELETE-маршрут для удаления задачи
if ($_SERVER['REQUEST_METHOD'] === 'DELETE' && $_SERVER['REQUEST_URI'] === '/api/tasks') {
    $data = json_decode(file_get_contents('php://input'), true);
    $id = $data['id'] ?? '';

    $sql = "DELETE FROM tasks WHERE id='$id'";
    if ($conn->query($sql) === TRUE) {
        header('Content-Type: application/json');
        echo json_encode(['message' => 'Task deleted']);
    } else {
        http_response_code(500);
        echo 'Error: ' . $conn->error;
    }
    exit;
}

// Если маршрут не найден
http_response_code(404);
echo 'Not Found';

// Закрытие соединения с базой данных
$conn->close();

