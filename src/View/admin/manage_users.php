<?php
session_start();
if ($_SESSION['role'] !== 'admin') {
    header("Location: ../home/index.php"); // Rediriger si l'utilisateur n'est pas admin
    exit();
}

require_once __DIR__ . '/../../../src/Model/UserModel.php';
use App\Meteo\Model\UserModel;

$pendingUsers = UserModel::getPendingUsers(); // Récupérer les utilisateurs en attente
$allUsers = UserModel::getAllUsers(); // Récupérer tous les utilisateurs
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gérer les utilisateurs</title>
    <link rel="stylesheet" href="/SAE-3.01-Developpement-application/web/assets/css/styles.css"> 
    <style>
        .back-button {
            padding: 15px 30px; /* Augmenter le padding */
            font-size: 1.2rem; /* Augmenter la taille de la police */
            background-color: #28a745;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
            position: absolute;
            top: 20px;
            right: 20px;
        }

        .back-button:hover {
            background-color: #218838;
        }

        .toggle-dark-mode {
            padding: 15px 30px; /* Augmenter le padding */
            font-size: 1.2rem; /* Augmenter la taille de la police */
            background-color: #343a40;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: all 0.3s ease;
            position: absolute;
            top: 20px;
            right: 150px;
        }

        .toggle-dark-mode:hover {
            background-color: #23272b;
        }

        .dark-mode {
            background-color: #121212;
            color: #ffffff;
        }

        .fade-in {
            opacity: 0;
            animation: fadeIn 1.5s ease-in-out forwards; /* Animation de fondu */
        }

        header h1 {
            font-size: 3rem; /* Augmenter la taille de la police */
            animation: slideDown 1s ease-in-out; /* Animation de glissement vers le bas */
        }

        main {
            animation: fadeIn 1.5s ease-in-out; /* Animation de fondu */
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            font-size: 1.2rem; /* Augmenter la taille de la police */
            animation: fadeIn 1.5s ease-in-out; /* Animation de fondu */
        }

        th, td {
            padding: 15px; /* Augmenter le padding */
            border: 1px solid #ddd;
        }

        th {
            background-color: #343a40;
            color: white;
        }

        td {
            background-color: #f8f9fa;
            color: #343a40; /* Changer la couleur du texte en sombre */
        }

        .action-buttons button {
            padding: 15px 30px; /* Augmenter le padding */
            font-size: 1.2rem; /* Augmenter la taille de la police */
            margin: 5px;
            color: #343a40; /* Changer la couleur du texte en sombre */
        }

        .action-buttons button[type="submit"][value="ban"] {
            background-color: #dc3545; /* Fond rouge pour le bouton de bannissement */
            color: white; /* Texte blanc */
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background 0.3s ease;
            padding: 20px 40px; /* Augmenter encore le padding */
            font-size: 1.5rem; /* Augmenter encore la taille de la police */
        }

        .action-buttons button[type="submit"][value="ban"]:hover {
            background-color: #c82333; /* Rouge plus foncé au survol */
        }

        @keyframes slideDown {
            from { transform: translateY(-100%); }
            to { transform: translateY(0); }
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
    </style>
</head>
<body>
    <header class="fade-in">
        <h1>Gérer les utilisateurs</h1>
        <button class="back-button" onclick="window.location.href='/SAE-3.01-Developpement-application/web/frontController.php'">🏠 Accueil</button> <!-- Bouton pour retourner à l'accueil -->
        <button class="toggle-dark-mode" onclick="toggleDarkMode()">🌙 Mode sombre</button> <!-- Bouton pour basculer le mode sombre -->
    </header>
    <main class="fade-in">
        <section>
            <h2>Utilisateurs en attente</h2>
            <table>
                <thead>
                    <tr>
                        <th>Nom d'utilisateur</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($pendingUsers as $user): ?>
                        <tr>
                            <td><?= htmlspecialchars($user['login']) ?></td> <!-- Afficher le nom d'utilisateur -->
                            <td>
                                <div class="action-buttons">
                                    <form method="POST" action="/SAE-3.01-Developpement-application/src/Controller/UserController.php">
                                        <input type="hidden" name="action" value="approve">
                                        <input type="hidden" name="user_id" value="<?= $user['id'] ?>">
                                        <button type="submit">Accepter</button> <!-- Bouton pour accepter l'utilisateur -->
                                    </form>
                                    <form method="POST" action="/SAE-3.01-Developpement-application/src/Controller/UserController.php">
                                        <input type="hidden" name="action" value="reject">
                                        <input type="hidden" name="user_id" value="<?= $user['id'] ?>">
                                        <button type="submit">Refuser</button> <!-- Bouton pour refuser l'utilisateur -->
                                    </form>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </section>

        <section>
            <h2>Tous les utilisateurs</h2>
            <table>
                <thead>
                    <tr>
                        <th>Nom d'utilisateur</th>
                        <th>Action</th> <!-- Enlever la colonne Statut -->
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($allUsers as $user): ?>
                        <tr>
                            <td><?= htmlspecialchars($user['login']) ?></td> <!-- Afficher le nom d'utilisateur -->
                            <td>
                                <?php if ($user['status'] !== 'banned'): ?>
                                    <form method="POST" action="/SAE-3.01-Developpement-application/src/Controller/UserController.php">
                                        <input type="hidden" name="action" value="ban">
                                        <input type="hidden" name="user_id" value="<?= $user['id'] ?>">
                                        <button type="submit" value="ban" style="background-color: #dc3545; color: white; border: none; border-radius: 5px; cursor: pointer; padding: 20px 40px; font-size: 1.5rem;">
                                            Bannir <!-- Bouton pour bannir l'utilisateur -->
                                        </button>
                                    </form>
                                <?php else: ?>
                                    <span>Banni</span> <!-- Afficher "Banni" si l'utilisateur est banni -->
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </section>
    </main>
    <footer class="fade-in">
        <p>© 2025 - SAÉ 3.01 | Application météorologique 🌦️</p>
    </footer>
    <script>
        function toggleDarkMode() {
            document.body.classList.toggle('dark-mode'); // Basculer le mode sombre
        }
    </script>
</body>
</html>