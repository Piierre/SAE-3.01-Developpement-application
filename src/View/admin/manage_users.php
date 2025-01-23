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
    <link rel="stylesheet" href="/SAE-3.01-Developpement-application/web/assets/css/styles.css"> <!-- Lien vers le CSS -->
</head>
<body>
    <header>
        <h1>Gérer les utilisateurs</h1>
    </header>
    <main>
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
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($allUsers as $user): ?>
                        <tr>
                            <td><?= htmlspecialchars($user['login']) ?></td>
                            <td><?= htmlspecialchars($user['status']) ?></td>
                            <td>
                                <?php if ($user['status'] !== 'banned'): ?>
                                    <form method="POST" action="/SAE-3.01-Developpement-application/src/Controller/UserController.php">
                                        <input type="hidden" name="action" value="ban">
                                        <input type="hidden" name="user_id" value="<?= $user['id'] ?>">
                                        <button type="submit">Bannir</button>
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
    <footer>
        <p>© 2025 - SAÉ 3.01 | Application météorologique 🌦️</p>
    </footer>
</body>
</html>