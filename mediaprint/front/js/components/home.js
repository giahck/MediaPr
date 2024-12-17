export default function homePage(router) {
    const homeContent = document.createElement('div');
    homeContent.innerHTML = `
        <h1>Welcome sadssto the Home Psdage!</h1>
        <p>You have successfully logged in.</p>
    `;


    return homeContent;  // Restituisci direttamente l'elemento HTML
}