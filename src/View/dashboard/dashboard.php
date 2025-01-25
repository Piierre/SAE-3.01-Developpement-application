<?php
require_once __DIR__ . '/../../../src/Lib/MessageFlash.php'; // Ensure this path is correct

use App\Meteo\Lib\MessageFlash;

MessageFlash::lireTousMessages();
?>

<h1>Bienvenue, <?= htmlspecialchars($_SESSION['login']) ?> !</h1>

<section>
    <h2>Mes Météothèques</h2>
    <?php if (empty($mesMeteotheques)): ?>
        <p>Aucune météothèque créée.</p>
    <?php else: ?>
        <ul>
            <?php foreach ($mesMeteotheques as $meteotheque): ?>
                <li>
                    <strong><?= htmlspecialchars($meteotheque['titre']) ?></strong> :
                    <?= htmlspecialchars($meteotheque['description']) ?>
                    <a href="export_csv.php?id=<?= $meteotheque['id'] ?>">Exporter</a>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
</section>

<section>
    <h2>Mes Favoris</h2>
    <?php if (empty($mesFavoris)): ?>
        <p>Aucun favori enregistré.</p>
    <?php else: ?>
        <ul>
            <?php foreach ($mesFavoris as $favori): ?>
                <li>
                    <strong><?= htmlspecialchars($favori['titre']) ?></strong> :
                    <?= htmlspecialchars($favori['description']) ?>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
</section>

<?php if ($_SESSION['role'] === 'admin'): ?>
<section>
    <h2>Toutes les Météothèques</h2>
    <ul>
        <?php foreach ($toutesMeteotheques as $meteotheque): ?>
            <li>
                <strong><?= htmlspecialchars($meteotheque['titre']) ?></strong> :
                <?= htmlspecialchars($meteotheque['description']) ?>
            </li>
        <?php endforeach; ?>
    </ul>
</section>
<?php endif; ?>
