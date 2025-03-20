<?php
header('Content-Type: application/json');
require_once 'config.php';

function get_random_question($conn, $used_items) {
    $used_items_str = implode(',', array_map(function($id) { return (int)$id; }, $used_items));
    $where_clause = empty($used_items_str) ? "" : "WHERE id NOT IN ($used_items_str)";
    
    $sql = "SELECT id, name, image_url FROM fruits_vegetables $where_clause ORDER BY RAND() LIMIT 1";
    $result = mysqli_query($conn, $sql);
    
    if (!$result || mysqli_num_rows($result) === 0) {
        return null;
    }
    
    $question = mysqli_fetch_assoc($result);
    
    // Get random choices
    $sql = "SELECT name FROM fruits_vegetables WHERE id != {$question['id']} ORDER BY RAND() LIMIT 3";
    $result = mysqli_query($conn, $sql);
    
    $choices = [$question['name']];
    while ($row = mysqli_fetch_assoc($result)) {
        $choices[] = $row['name'];
    }
    
    // Shuffle choices
    shuffle($choices);
    
    return [
        'id' => $question['id'],
        'image_url' => $question['image_url'],
        'correct_answer' => $question['name'],
        'choices' => $choices
    ];
}

function save_game_result($conn, $player_name, $score, $time_started, $duration) {
    $time_ended = date('Y-m-d H:i:s');
    
    $stmt = mysqli_prepare($conn, 
        "INSERT INTO players (player_name, score, date_played, time_started, time_ended, duration_seconds) 
         VALUES (?, ?, NOW(), ?, ?, ?)"
    );
    
    mysqli_stmt_bind_param($stmt, "sissi", $player_name, $score, $time_started, $time_ended, $duration);
    
    if (!mysqli_stmt_execute($stmt)) {
        return false;
    }
    
    return true;
}

function get_high_scores($conn) {
    $sql = "SELECT player_name, score, date_played, duration_seconds 
            FROM players 
            ORDER BY score DESC, duration_seconds ASC 
            LIMIT 10";
            
    $result = mysqli_query($conn, $sql);
    $scores = [];
    
    while ($row = mysqli_fetch_assoc($result)) {
        $scores[] = $row;
    }
    
    return $scores;
}

$action = $_GET['action'] ?? '';

switch ($action) {
    case 'get_question':
        $used_items = json_decode($_POST['used_items'] ?? '[]');
        $question = get_random_question($conn, $used_items);
        echo json_encode(['success' => true, 'data' => $question]);
        break;
        
    case 'save_result':
        $data = json_decode(file_get_contents('php://input'), true);
        $success = save_game_result(
            $conn,
            $data['player_name'],
            $data['score'],
            $data['time_started'],
            $data['duration']
        );
        echo json_encode(['success' => $success]);
        break;
        
    case 'get_high_scores':
        $scores = get_high_scores($conn);
        echo json_encode(['success' => true, 'data' => $scores]);
        break;
        
    default:
        echo json_encode(['success' => false, 'error' => 'Invalid action']);
}

mysqli_close($conn);
?>
