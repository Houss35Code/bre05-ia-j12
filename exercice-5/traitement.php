<?php
// Configuration de la connexion (à adapter)
$conn = new mysqli("localhost", "username", "password", "database");

// Vérifier la connexion
if ($conn->connect_error) {
    die("Erreur de connexion : " . $conn->connect_error);
}

// Récupérer et valider l'entrée utilisateur
$user_input = $_GET['user_id'] ?? null;

// Validation : vérifier que c'est bien un nombre
if (!filter_var($user_input, FILTER_VALIDATE_INT)) {
    http_response_code(400);
    echo json_encode(['error' => 'ID invalide']);
    exit;
}

// Utiliser une requête préparée (protection contre l'injection SQL)
$stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
$stmt->bind_param("i", $user_input); // "i" = integer
$stmt->execute();
$result = $stmt->get_result();

// Vérifier si un utilisateur a été trouvé
if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    
    // Retourner les données en JSON
    header('Content-Type: application/json');
    echo json_encode([
        'success' => true,
        'user' => $user
    ]);
} else {
    http_response_code(404);
    echo json_encode([
        'success' => false,
        'message' => 'Utilisateur non trouvé'
    ]);
}

// Fermer les ressources
$stmt->close();
$conn->close();
?>