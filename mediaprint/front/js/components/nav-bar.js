import nav_barHtml from '../page/nav-bar.html';
import { logout } from '../service/authService.js';
export default async function navBar(router) {
    const tempDiv = document.createElement('div');
    tempDiv.innerHTML = nav_barHtml;
    /* const logoutButton = tempDiv.querySelector('#logout'); */
    /* logoutButton.addEventListener('click', () => {
        alert('Logged out');
        router.navigate('/');
    }); */
    window.logoutFunction = () => {
        logout();
        router.navigate('/');
    }
    return tempDiv.firstElementChild;
}