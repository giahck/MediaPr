
import { jwtDecode } from 'jwt-decode';
import { BehaviorSubject } from 'rxjs';
const userIdSubject = new BehaviorSubject(null);
export const getUserIdOb = () => userIdSubject.asObservable();
export const login = async (email, password) => {
   
    try {
        const response = await fetch('http://localhost:8000/api/login', { 
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ email, password }),
        });

        // Verifica se la risposta non è OK
        if (!response.ok) {
            const errorData = await response.json();
            throw new Error(errorData.message || 'Login failed');
        }

        // Analizza la risposta JSON
        const data = await response.json();
        const token = data.token;
       // console.log(data);
        userIdSubject.next(data);
        // Decodifica il token JWT
        let decodedToken;
        try {
            decodedToken = jwtDecode(token);
        } catch (error) {
            throw new Error('Invalid JWT token');
        }

        // Verifica la scadenza del token
        const currentTime = Math.floor(Date.now() / 1000);
        if (decodedToken.exp && decodedToken.exp < currentTime) {
            throw new Error('JWT token has expired');
        }

        // Salva il token JWT nel localStorage solo se è valido
        localStorage.setItem('jwt', token);
        return data;
    } catch (error) {
        console.error('Login error:', error);
        throw error;
    }
};
export const register = async (data) => {
    console.log('Dati inviati:', data);
    try {
        console.log('Dati inviati:', data);
        let response = await fetch('http://localhost:8000/api/register', { 
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(data),
        });

        // Verifica se la risposta non è OK
        return data;
    } catch (error) {
        console.error('Login error:', error);
        throw error;
    }
};
  export const logout = () => {
    localStorage.removeItem('jwt'); // Rimuovi il token JWT dal localStorage
    userIdSubject.next(null);

  };
  
  export const getToken = () => { 
    userIdSubject.next(null);
    return localStorage.getItem('jwt');
  };
  
  export const isAuthenticated = async () => {
    const token = getToken();
    if (!token) return false;

    try {
        const response = await fetch('http://localhost:8000/api/protected', {
            method: 'GET',
            headers: {
                'Authorization': `Bearer ${token}`
            }
        });

        if (!response.ok) {
            throw new Error('Authentication failed');
        }

        const data = await response.json();
        return true;
    } catch (error) {
        console.error('Authentication error:', error);
        logout();
        return false;
    }
};