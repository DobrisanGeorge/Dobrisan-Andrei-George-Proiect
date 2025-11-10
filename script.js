/* === Functii Utilitare pentru Local Storage === */

/**
 * Încarcă datele utilizatorului din Local Storage sau folosește date implicite.
 * Setează data de înscriere la data curentă dacă utilizatorul este nou.
 */
function loadUserData() {
    const defaultData = {
        name: "Producător Necunoscut", 
        email: "contact@neonstudio.ro",
        daw: "Ableton Live",
        isLoggedIn: false,
        // Setează data curentă la inițiere
        joinDate: new Date().toLocaleDateString('ro-RO', { year: 'numeric', month: 'long', day: 'numeric' })
    };
    try {
        const storedData = localStorage.getItem('neonStudioUser');
        let user = storedData ? JSON.parse(storedData) : defaultData;

        // Asigură că joinDate există și este actualizat (sau setat la data curentă dacă lipsea)
        if (!user.joinDate) { 
            user.joinDate = defaultData.joinDate;
        }

        return user;
    } catch (e) {
        console.error("Eroare la citirea Local Storage:", e);
        return defaultData;
    }
}

/**
 * Salvează datele utilizatorului în Local Storage.
 */
function saveUserData(data) {
    try {
        localStorage.setItem('neonStudioUser', JSON.stringify(data));
    } catch (e) {
        console.error("Eroare la salvarea Local Storage:", e);
    }
}

/* === Functii Principale === */
document.addEventListener('DOMContentLoaded', () => {
    // Încarcă datele utilizatorului la pornirea paginii
    let user = loadUserData(); 

    // --- 1. Logica pentru Meniul Hamburger și Back-to-Top ---
    const hamburger = document.getElementById('hamburger');
    const nav = document.getElementById('mainNav');
    if (hamburger && nav) {
        hamburger.addEventListener('click', () => {
            nav.classList.toggle('show');
            hamburger.classList.toggle('active'); 
        });
    }

    const backBtn = document.getElementById('backToTop');
    if (backBtn) {
        window.addEventListener('scroll', () => {
            backBtn.style.display = window.scrollY > 300 ? 'block' : 'none';
        });
        backBtn.addEventListener('click', () => {
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });
    }

    // --- 2. Logica pentru Login (login.html) ---
    // Selectăm formularul de Login (fără ID-ul #register)
    const loginForm = document.querySelector('.form-box:not(#register) form'); 
    
    if (loginForm && window.location.pathname.endsWith('login.html')) {
        loginForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Preluarea input-urilor (ordonarea 0 e email, 1 e password)
            const emailInput = this.querySelector('input[type="email"]')?.value;
            const passwordInput = this.querySelector('input[type="password"]')?.value; 

            if (!emailInput || !passwordInput) {
                 alert("Vă rugăm introduceți atât email-ul cât și parola.");
                 return;
            }

            // LOGICĂ DE VERIFICARE SIMULATĂ:
            // Verificăm dacă email-ul introdus este diferit de cel salvat și de cel default
            let isNewEmail = (user.email === "contact@neonstudio.ro" || user.email.toLowerCase() !== emailInput.toLowerCase());
            
            // Dacă email-ul este nou ȘI utilizatorul nu era deja "logat" (simulat)
            if (isNewEmail && !user.isLoggedIn) {
                alert("Acest email nu este înregistrat. Vă rugăm folosiți opțiunea 'Creează cont'.");
                // Forțează trecerea la formularul de register
                document.getElementById('show-register')?.click();
                return;
            } 
            
            // Simulare autentificare reușită: Actualizează datele contului existent/default
            user.email = emailInput;
            let rawName = emailInput.split('@')[0];
            // Curăță și capitalizează numele bazat pe email (ex: dinucatelusul -> Dinucatelusul)
            user.name = rawName.charAt(0).toUpperCase() + rawName.slice(1).replace(/[._-]/g, ' '); 
            user.isLoggedIn = true;
            
            saveUserData(user);
            alert(`Autentificare reușită! Bine ai revenit, ${user.name}.`);
            
            // Redirecționare
            window.location.href = 'profil.html';
        });
    }

    // --- 3. Logica pentru Creează Cont (Register) ---
    const registerForm = document.getElementById('register')?.querySelector('form');
    if (registerForm && window.location.pathname.endsWith('login.html')) {
         registerForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Preluarea input-urilor
            const nameInput = this.querySelector('input[type="text"]')?.value;
            const emailInput = this.querySelector('input[type="email"]')?.value;

            // Crearea contului nou și setarea datelor
            user.name = nameInput;
            user.email = emailInput;
            user.daw = "FL Studio"; // DAW default la înregistrare
            user.joinDate = new Date().toLocaleDateString('ro-RO', { year: 'numeric', month: 'long', day: 'numeric' });
            user.isLoggedIn = true;
            
            saveUserData(user);
            alert(`Cont creat cu succes! Bine ai venit, ${user.name}.`);
            
            window.location.href = 'profil.html';
        });
    }

    // --- Logica pentru Login/Register Toggle (Mutați din HTML) ---
    const showRegister = document.getElementById('show-register');
    const showLogin = document.getElementById('show-login');
    const registerBox = document.getElementById('register');
    const loginBox = document.querySelector('.form-container .form-box:not(#register)');

    if (showRegister && showLogin && registerBox && loginBox) {
        showRegister.addEventListener('click', (e) => {
            e.preventDefault();
            loginBox.style.display = 'none';
            registerBox.style.display = 'block';
        });
        showLogin.addEventListener('click', (e) => {
            e.preventDefault();
            loginBox.style.display = 'block';
            registerBox.style.display = 'none';
        });
    }


    // --- 4. Logica pentru Setări (setari.html) ---
    const settingsForm = document.getElementById('settingsForm');
    if (settingsForm) {
        // Populează câmpurile de input cu datele salvate
        document.getElementById('full-name').value = user.name;
        document.getElementById('pref-daw').value = user.daw;
        
        settingsForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const newName = document.getElementById('full-name').value;
            const newDAW = document.getElementById('pref-daw').value;
            
            // Actualizarea Local Storage
            user.name = newName;
            user.daw = newDAW;
            saveUserData(user);
            
            alert('Setările au fost salvate cu succes! Te redirecționăm la Profil.');
            
            window.location.href = 'profil.html';
        });
    }
    
    // --- 5. Logica pentru Profil (profil.html) ---
    if (window.location.pathname.endsWith('profil.html')) {
        // Actualizează elementele HTML cu datele salvate
        const profileTitleName = document.getElementById('profile-title-name');
        if (profileTitleName) profileTitleName.textContent = user.name;
        
        const profileNameDisplay = document.getElementById('profile-name-display');
        if (profileNameDisplay) profileNameDisplay.textContent = user.name;
        
        const profileEmailDisplay = document.getElementById('profile-email-display');
        if (profileEmailDisplay) profileEmailDisplay.textContent = user.email;
        
        const profileDawDisplay = document.getElementById('profile-daw-display');
        if (profileDawDisplay) profileDawDisplay.textContent = user.daw;
        
        const profileJoinDate = document.getElementById('profile-join-date');
        if (profileJoinDate) profileJoinDate.textContent = user.joinDate;
    }
    
    // --- 6. Logica pentru Formularul de Contact (contact.html) ---
    const contactForm = document.getElementById('contactForm');
    if (contactForm) {
        contactForm.addEventListener('submit', function(e) {
            e.preventDefault();
            alert('Mulțumim! Mesajul tău a fost primit. Vom răspunde în cel mai scurt timp.');
            this.reset();
        });
    }
});