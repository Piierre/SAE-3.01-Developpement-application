<?php
$stationName = isset($_GET['name']) ? $_GET['name'] : '';
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Données de la station <?php echo htmlspecialchars($stationName); ?></title>
</head>
<body>
    <h1>Données de la station <?php echo htmlspecialchars($stationName); ?></h1>
    
    <form method="GET">
        <input type="hidden" name="page" value="station">
        <input type="hidden" name="name" value="<?php echo htmlspecialchars($stationName); ?>">
        <label for="date">Choisir une date :</label>
        <input type="date" name="date" id="date" value="<?php echo date('Y-m-d'); ?>" required>
        <button type="submit">Afficher les données</button>
    </form>

    <?php if (isset($_GET['date'])): ?>
        <h2>Température</h2>
        <img src="station_data.php?station_name=<?php echo urlencode($stationName); ?>&date=<?php echo $_GET['date']; ?>&type=temperature" alt="Graphique Température">

        <h2>Humidité</h2>
        <img src="station_data.php?station_name=<?php echo urlencode($stationName); ?>&date=<?php echo $_GET['date']; ?>&type=humidite" alt="Graphique Humidité">

        <h2>Vitesse du vent</h2>
        <img src="station_data.php?station_name=<?php echo urlencode($stationName); ?>&date=<?php echo $_GET['date']; ?>&type=vent" alt="Graphique Vent">

        <h2>Précipitations</h2>
        <img src="station_data.php?station_name=<?php echo urlencode($stationName); ?>&date=<?php echo $_GET['date']; ?>&type=precipitations" alt="Graphique Précipitations">
    <?php endif; ?>
</body>
</html>
