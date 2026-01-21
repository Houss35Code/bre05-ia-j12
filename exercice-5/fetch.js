function fetchData(id) {
    // Validation côté client (en plus de la validation serveur)
    if (!Number.isInteger(id) || id <= 0) {
        console.error('ID invalide');
        return;
    }
    
    // Encoder l'URL pour éviter les problèmes
    const url = `traitement.php?user_id=${encodeURIComponent(id)}`;
    
    fetch(url)
        .then(response => {
            // Vérifier le statut de la réponse
            if (!response.ok) {
                throw new Error(`Erreur HTTP : ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                console.log('Utilisateur trouvé :', data.user);
            } else {
                console.log('Erreur :', data.message);
            }
        })
        .catch(error => {
            console.error('Erreur lors de la récupération :', error);
        });
}