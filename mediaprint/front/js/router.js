import { isAuthenticated } from './service/authService.js';
import navBar from './components/nav-bar.js';
export class Router {
    constructor(app) {
        this.app = app;
        this.routes = {};
        this.protectedRoutes = [];
        window.addEventListener('popstate', () => this.loadPage());
    }

    addRoute(path, component, isProtected = false) {
        this.routes[path] = component;
        if (isProtected) {
            this.protectedRoutes.push(path);
        }
    }

    async loadPage() {
        const path = window.location.pathname;
        const component = this.routes[path];
        const autenticato =await isAuthenticated();
      /*  console.log(autenticato); */
        // Se la rotta è protetta e l'utente non è autenticato, reindirizza al login
        if (this.protectedRoutes.includes(path) && !autenticato) {
            alert('You must be logged in to access this page.');
            this.navigate('/');
            return;
        }

        this.app.innerHTML = ''; 
        // Se l'utente è autenticato e tenta di accedere alla pagina di login, reindirizza alla home
        if (path === '/' && autenticato) {
            this.navigate('/home');
            return;
        }
        if (autenticato) {
            this.app.appendChild(await navBar(this));
        }


        if (component) {
            const module = await component();
         
            this.app.appendChild(module);
        } else {
            this.navigate('/');
        }
    }

    navigate(path) {
        window.history.pushState({}, path, window.location.origin + path);
        this.loadPage();
    }
}