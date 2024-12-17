import { Router } from './router.js';


document.addEventListener('DOMContentLoaded', init);

async function init() {
    const app = document.getElementById('app');
    
    // Inizializza il router
    const router = new Router(app);


    // Aggiungi le route
    addRoutes(router);

    // Esegui il routing iniziale
    router.loadPage();
}

function addRoutes(router) {
    router.addRoute('/', async () => {
        const module = await import('./components/login.js');
        return module.default(router);
    }, false);

    router.addRoute('/home', async () => {
        const module = await import('./components/home.js');
        return module.default(router);
    }, true);
}

/* router.addRoute('/', () => import('./components/login.js').then((module) => module.default(router)));
router.addRoute('/home', () => import('./components/home.js').then((module) => module.default(router)),true); */