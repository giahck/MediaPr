import { login,register } from '../service/authService.js';
import loginHtml from '../page/login.html';

export default async function loginPage(router) {
  const tempDiv = document.createElement('div');
  tempDiv.innerHTML = loginHtml;
  const form = tempDiv.querySelector('#login-form');
  const  registerForm = tempDiv.querySelector('#register-form');
  const signUpButton = tempDiv.querySelector('#registrazione');
  const welcomeContainer = tempDiv.querySelector('#welcome-container');
  const registerContainer = tempDiv.querySelector('#register-container');
  const loginContainer = tempDiv.querySelector('#login-container');

  form.addEventListener('submit', async (event) => {
    event.preventDefault();
    const email = form.querySelector('#email').value;
    const password = form.querySelector('#password').value;    
    
    try {
      await login(email, password); 
      alert('Login Successful');
      router.navigate('/home'); 
    } catch (error) {
      alert('Invalid Credentials: ' + error);
    }
  });
  registerForm.addEventListener('submit', async (event) => {
    event.preventDefault();
    const formData = new FormData(registerForm);

    const data = {
   /*    prefisso: formData.get('register-prefisso'), */
      telefono: formData.get('register-telefono'),
      nome: formData.get('register-nome'),
      cognome: formData.get('register-cognome'),
      email: formData.get('register-email'),
      password: formData.get('register-password'),
      data_nascita: formData.get('register-dob'),
      indirizzo: 'via merda 1',
    };

    // Validazione aggiuntiva
    const telefonoPattern = /^\d{10}$/;
    if (!telefonoPattern.test(data.telefono)) {
      alert('Il numero di telefono deve essere di 10 cifre.');
      return;
    }

   /*  const prefissoPattern = /^\+\d{1,3}$/;
    if (!prefissoPattern.test(data.prefisso)) {
      alert('Il prefisso deve essere nel formato +39.');
      return;
    } */

    if (new Date(data.dob) > new Date('2020-12-31')) {
      alert('La data di nascita non può essere successiva al 2020.');
      return;
    }
    console.log('Dati del modulo:', data);
    try {
      await register(data); 
      alert('registrazione Successful');
      router.navigate('/login'); 
    } catch (error) {
      alert('Invalid Credentials: ' + error);
    }

    // Puoi aggiungere qui la logica per inviare i dati al server
  });
  signUpButton.addEventListener('click', () => {
    if (registerContainer.style.display === 'block') {
      // Transizione dal registro verso sinistra, login al centro
      registerContainer.classList.add('move-from-left');
      loginContainer.classList.remove('move-from-right', 'hidden');
      setTimeout(() => {
        registerContainer.style.display = 'none';
        loginContainer.style.display = 'block';
      }, 1000); // Durata della transizione
    } else {
      // Transizione dal login verso destra, registro al centro
      loginContainer.classList.add('move-from-right');
      registerContainer.classList.remove('move-from-left', 'hidden');
      
      setTimeout(() => {
        loginContainer.style.display = 'none';
        registerContainer.style.display = 'block';
      }, 1000); // Durata della transizione
    }
  });

  return tempDiv.firstElementChild;  
}