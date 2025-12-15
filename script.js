/* === Functii Principale (UI Stabile) === */
document.addEventListener('DOMContentLoaded', () => {

    // --- 1. Logica pentru Meniul Hamburger ---
    const hamburger = document.getElementById('hamburger');
    const nav = document.getElementById('mainNav');
    
    // Verifică existența ambelor elemente înainte de a adăuga listener-ul
    if (hamburger && nav) {
        hamburger.addEventListener('click', () => {
            // Manipularea claselor CSS pentru a afișa/ascunde meniul mobil
            nav.classList.toggle('show');
            hamburger.classList.toggle('active'); 
        });
    }

    // --- 2. Logica pentru Back-to-Top ---
    const backBtn = document.getElementById('backToTop');
    
    if (backBtn) {
        // Manipularea proprietății CSS style.display (DOM style)
        window.addEventListener('scroll', () => {
            backBtn.style.display = window.scrollY > 300 ? 'block' : 'none';
        });
        
        backBtn.addEventListener('click', () => {
            // Scroll fluid către începutul paginii
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });
    }

    // NOTĂ: Toate celelalte funcționalități de Login/Register/Setări din versiunile anterioare
    // au fost eliminate de aici și sunt gestionate exclusiv de PHP.
});