<?php
header('Content-Type: application/json');
session_start();

$conn = new mysqli("localhost", "root", "", "bdd");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // AJOUT : on récupère nom et prenom ici !
    $stmt = $conn->prepare("SELECT id, motdepasse, prenom, nom, est_confirme FROM utilisateur WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($user = $result->fetch_assoc()) {
        if ($user['est_confirme'] == 0) {
            echo json_encode(['success' => false, 'message' => 'Veuillez confirmer votre email d’abord.']);
            exit;
        }

        if (password_verify($password, $user['motdepasse'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_prenom'] = $user['prenom'];

            // On renvoie les données pour le LocalStorage
            echo json_encode([
                'success' => true,
                'prenom' => $user['prenom'],
                'nom' => $user['nom']
            ]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Mot de passe incorrect.']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Cet email n’existe pas.']);
    }
}
?>