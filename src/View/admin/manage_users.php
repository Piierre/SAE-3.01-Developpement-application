<?php
session_start();
if ($_SESSION['role'] !== 'admin') {
    header("Location: ../home/index.php");
    exit();
}

require_once __DIR__ . '/../../../src/Model/UserModel.php';
use App\Meteo\Model\UserModel;

$pendingUsers = UserModel::getPendingUsers();
$allUsers = UserModel::getAllUsers();
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
            padding: 15px 30px; /* Increased padding */
            font-size: 1.2rem; /* Increased font size */
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
            padding: 15px 30px; /* Increased padding */
            font-size: 1.2rem; /* Increased font size */
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
            animation: fadeIn 1.5s ease-in-out forwards; /* Fade in animation */
        }

        header h1 {
            font-size: 3rem; /* Increased font size */
            animation: slideDown 1s ease-in-out; /* Slide down animation */
        }

        main {
            animation: fadeIn 1.5s ease-in-out; /* Fade in animation */
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            font-size: 1.2rem; /* Increased font size */
            animation: fadeIn 1.5s ease-in-out; /* Fade in animation */
        }

        th, td {
            padding: 15px; /* Increased padding */
            border: 1px solid #ddd;
        }

        th {
            background-color: #343a40;
            color: white;
        }

        td {
            background-color: #f8f9fa;
            color: #343a40; /* Changed text color to dark */
        }

        .action-buttons button {
            padding: 15px 30px; /* Increased padding */
            font-size: 1.2rem; /* Increased font size */
            margin: 5px;
            color: #343a40; /* Changed text color to dark */
        }

        .action-buttons button[type="submit"][value="ban"] {
            background-color: #dc3545; /* Red background for ban button */
            color: white; /* White text color */
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background 0.3s ease;
            padding: 20px 40px; /* Further increased padding */
            font-size: 1.5rem; /* Further increased font size */
        }

        .action-buttons button[type="submit"][value="ban"]:hover {
            background-color: #c82333; /* Darker red on hover */
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
        <button class="back-button" onclick="window.location.href='/SAE-3.01-Developpement-application/web/frontController.php'">🏠 Accueil</button>
        <button class="toggle-dark-mode" onclick="toggleDarkMode()">🌙 Mode sombre</button>
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
                            <td><?= htmlspecialchars($user['login']) ?></td>
                            <td>
                                <div class="action-buttons">
                                    <form method="POST" action="/SAE-3.01-Developpement-application/src/Controller/UserController.php">
                                        <input type="hidden" name="action" value="approve">
                                        <input type="hidden" name="user_id" value="<?= $user['id'] ?>">
                                        <button type="submit">Accepter</button>
                                    </form>
                                    <form method="POST" action="/SAE-3.01-Developpement-application/src/Controller/UserController.php">
                                        <input type="hidden" name="action" value="reject">
                                        <input type="hidden" name="user_id" value="<?= $user['id'] ?>">
                                        <button type="submit">Refuser</button>
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
                        <th>Action</th> <!-- Removed Status column -->
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($allUsers as $user): ?>
                        <tr>
                            <td><?= htmlspecialchars($user['login']) ?></td>
                            <td>
                                <?php if ($user['status'] !== 'banned'): ?>
                                    <form method="POST" action="/SAE-3.01-Developpement-application/src/Controller/UserController.php">
                                        <input type="hidden" name="action" value="ban">
                                        <input type="hidden" name="user_id" value="<?= $user['id'] ?>">
                                        <button type="submit" value="ban" style="background-color: #dc3545; color: white; border: none; border-radius: 5px; cursor: pointer; padding: 20px 40px; font-size: 1.5rem;">
    Bannir
</button>
                                    </form>
                                <?php else: ?>
                                    <span>Banni</span>
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
            document.body.classList.toggle('dark-mode');
        }
    </script>
</body>
</html>