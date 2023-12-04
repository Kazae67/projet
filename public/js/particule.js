for (let i = 0; i < 50; i++) {
    let particle = document.createElement('div');
    particle.className = 'particle';
    particle.style.left = `${Math.random() * 100}vw`;
    particle.style.top = `-10px`; // Commence au-dessus de la vue
    particle.style.animationDelay = `${Math.random() * 10}s`; // Délai aléatoire jusqu'à 10 secondes
    particle.style.animationDuration = `${Math.random() * 5 + 5}s`; // Durée aléatoire entre 5 et 10 secondes
    document.body.appendChild(particle);
}