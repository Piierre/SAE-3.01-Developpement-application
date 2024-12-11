import requests
import sqlite3

# Fonction pour exécuter une requête SQL dans la base de données
def requete_sql(requete, params=None):
    """
    Fonction qui exécute une requête SQL dans la base de données.
    - requete (str): La requête SQL à exécuter
    - params (tuple): Paramètres pour la requête (optionnel)
    """
    conn = sqlite3.connect("stations.db")  # Connexion à la base de données
    cur = conn.cursor()
    if params:
        cur.execute(requete, params)
    else:
        cur.execute(requete)
    conn.commit()
    conn.close()

# Créer la table SQLite si elle n'existe pas déjà
def creer_table():
    requete = """
    CREATE TABLE IF NOT EXISTS stations (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        code_station TEXT UNIQUE,
        code_commune TEXT,
        code_departement TEXT,
        nom_station TEXT
    );
    """
    requete_sql(requete)

# Télécharger les données depuis l'API
url = r"https://hubeau.eaufrance.fr/api/v1/temperature/station?size=20000"
response = requests.get(url)
if response.status_code == 200 or response.status_code == 206:
    json_donnees = response.json()  # Stocke le contenu JSON dans une variable dictionnaire
    print("Données JSON téléchargées")
else:
    print(f"Erreur lors du téléchargement des données : {response.status_code}")
    exit()

donnees_stations = [
    {
        "code_station": station['code_station'],
        "code_commune": station['code_commune'],
        "code_departement": station['code_departement'],
        "nom_station": station.get('libelle_station', '')
    }
    for station in json_donnees['data']
    if station['code_station'] and station['code_commune'] and station['code_departement']
]

# Insérer les données dans la base de données
def inserer_donnees():
    for station in donnees_stations:
        requete = """
        INSERT OR IGNORE INTO stations (code_station, code_commune, code_departement, nom_station)
        VALUES (?, ?, ?, ?);
        """
        params = (
            station['code_station'],
            station['code_commune'],
            station['code_departement'],
            station['nom_station']
        )
        requete_sql(requete, params)
    print("Données insérées dans la base SQLite.")

# Lire et afficher les données depuis SQLite
def consulter_donnees():
    conn = sqlite3.connect("stations.db")
    cur = conn.cursor()
    cur.execute("SELECT * FROM stations LIMIT 10")  # Limite à 10 résultats pour la lisibilité
    stations = cur.fetchall()
    conn.close()

    print("Voici un aperçu des données dans la base de données :")
    for station in stations:
        print(station)

# Étapes principales du script
creer_table()  # Créer la table si nécessaire
inserer_donnees()  # Insérer les données dans SQLite
consulter_donnees()  # Afficher un aperçu des données insérées
