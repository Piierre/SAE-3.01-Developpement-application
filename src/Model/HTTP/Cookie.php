<?php
    namespace App\Covoiturage\Model\HTTP;

    class Cookie {

        // Méthode pour enregistrer un cookie
        public static function enregistrer(string $cle, mixed $valeur, ?int $dureeExpiration = null): void {
            // Si la durée d'expiration n'est pas fournie, enregistrer le cookie sans date d'expiration
            if($dureeExpiration == null) {
                setcookie($cle, serialize($valeur));
            } else {
                // Enregistrer le cookie avec la durée d'expiration spécifiée
                setcookie($cle, serialize($valeur), time() + $dureeExpiration);
            }
        }

        // Méthode pour lire la valeur d'un cookie
        public static function lire(string $cle): mixed {
            // Retourner la valeur désérialisée du cookie
            return unserialize($_COOKIE[$cle]); 
        }

        // Méthode pour vérifier si un cookie existe
        public static function contient($cle) : bool {
            // Vérifier si le cookie est défini
            return isset($_COOKIE[$cle]);
        }

        // Méthode pour supprimer un cookie
        public static function supprimer($cle) : void {
            // Supprimer le cookie du tableau $_COOKIE
            unset($_COOKIE[$cle]);
            // Définir le cookie avec une date d'expiration dans le passé pour le supprimer
            setcookie($cle, "", 1);
        }
    }
?>
