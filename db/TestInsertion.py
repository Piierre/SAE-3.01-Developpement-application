import mysql.connector
import requests
import json

# Paramètres de connexion à la base de données
db_config = {
    "host": "localhost",
    "user": "root",
    "password": "123",
    "database": "MeteoDB"
}

# Configuration de l'API
api_url = "https://public.opendatasoft.com/api/explore/v2.1/catalog/datasets/donnees-synop-essentielles-omm/records"
limit = 100  # Nombre de résultats par page
offset = 0   # Offset initial

# Connexion à la base de données
try:
    db_connection = mysql.connector.connect(**db_config)
    cursor = db_connection.cursor()

    print("Connexion réussie à la base de données.")
except mysql.connector.Error as err:
    print(f"Erreur de connexion : {err}")
    exit()

# Requête SQL pour insérer ou mettre à jour les données
insert_query = """
    INSERT INTO Station (id, nom, latitude, longitude, altitude, code_comm, code_dep, code_reg)
    VALUES (%s, %s, %s, %s, %s, %s, %s, %s)
    ON DUPLICATE KEY UPDATE
        nom = VALUES(nom), latitude = VALUES(latitude), longitude = VALUES(longitude),
        altitude = VALUES(altitude), code_comm = VALUES(code_comm),
        code_dep = VALUES(code_dep), code_reg = VALUES(code_reg)
"""

# Récupérer les données depuis l'API et insérer dans la BDD
try:
    while True:
        # Construire l'URL avec offset et limit
        response = requests.get(f"{api_url}?limit={limit}&offset={offset}")
        if response.status_code != 200:
            print("Erreur de récupération de l'API :", response.status_code)
            break

        data = response.json()

        # Vérifier s'il y a des résultats
        if not data.get('results'):
            print("Aucune donnée supplémentaire.")
            break

        # Insérer les données dans la table Station
        for fields in data['results']:
            if 'numer_sta' not in fields:
                continue  # Ignorer si 'numer_sta' (ID) est manquant

            values = (
                fields.get('numer_sta'),
                fields.get('nom', 'Inconnu'),
                fields.get('latitude'),
                fields.get('longitude'),
                fields.get('altitude'),
                fields.get('codegeo'),
                fields.get('code_dep'),
                fields.get('code_reg')
            )

            try:
                cursor.execute(insert_query, values)
                print(f"Station insérée ou mise à jour : {fields.get('numer_sta')}")
            except mysql.connector.Error as err:
                print(f"Erreur lors de l'insertion : {err}")

        # Incrémenter l'offset pour paginer
        offset += limit
        print(f"Offset actuel : {offset}")

    # Commit des changements
    db_connection.commit()
    print("Toutes les données ont été insérées ou mises à jour avec succès.")

except Exception as e:
    print(f"Erreur générale : {e}")

finally:
    # Fermer la connexion
    cursor.close()
    db_connection.close()
    print("Connexion à la base de données fermée.")
