import requests
import sqlite3
import os

def requete_sql(requete):
    '''
    Fonction qui exécute une requete SQL dans la base de donnée
    requete str:         la requête à exécuter
    return  NoneType
    '''

    conn = sqlite3.connect("stations.db")
    cur = conn.cursor()
    cur.execute(requete)
    
    conn.commit()
    conn.close()

# Lien vers l'API et chemin relatif vers la bd !
url = r"https://hubeau.eaufrance.fr/api/v1/temperature/station?size=20000"
chemin_db = r"stations.db"

# Faire une requête get à l'API en vérifiant sa disponibilité
response = requests.get(url)
if response.status_code == 200 or response.status_code == 206:
    json_donnees = response.json()  # Stocke le contenu JSON dans une variable dictionnaire
    print("Données JSON téléchargées")
else:
    print(f"Erreur lors du téléchargement des données : {response.status_code}")
    exit()

donnees_stations = [station for station in json_donnees['data'] if station['code_station'] and station['code_commune'] and station['code_departement']]
