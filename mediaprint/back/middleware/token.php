<?php

class TokenService {

    // Funzione per creare il token JWT
    public function createJWT($userId) {
        // Header del JWT
        $header = json_encode(['alg' => 'HS256', 'typ' => 'JWT']);

        // Payload del JWT
        $payload = json_encode([
            'sub' => $userId,    // ID dell'utente
            'iat' => time(),     // Timestamp di creazione
            'exp' => time() + 3600 // Scadenza: 1 ora
        ]);

        // Codifica in base64Url (in modo sicuro per URL)
        $base64UrlHeader = $this->base64EncodeUrlSafe($header);
        $base64UrlPayload = $this->base64EncodeUrlSafe($payload);

        // Crea la firma
        $secretKey = 'your-secret-key'; // Sostituisci con una chiave segreta
        $signature = hash_hmac('sha256', "$base64UrlHeader.$base64UrlPayload", $secretKey, true);
        $base64UrlSignature = $this->base64EncodeUrlSafe($signature);

        // Crea il token JWT concatenando le 3 parti
        $jwt = "$base64UrlHeader.$base64UrlPayload.$base64UrlSignature";
       /*  error_log("Base64 Header: $base64UrlHeader");
        error_log("Base64 Payload: $base64UrlPayload");
        error_log("Base64 Signature: $base64UrlSignature"); */
        return $jwt;
    }

    // Funzione per una codifica sicura in Base64URL
    private function base64EncodeUrlSafe($data) {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }

    // Funzione per una decodifica sicura di Base64URL
    private function base64DecodeUrlSafe($data) {
        return base64_decode(strtr($data, '-_', '+/'));
    }

    // Funzione per verificare la validità del token JWT
    public function verifyJWT($jwt) {
        $secretKey = 'your-secret-key'; // La chiave segreta per verificare la firma
    
        // Dividi il token nelle sue tre parti
        list($base64UrlHeader, $base64UrlPayload, $base64UrlSignature) = explode('.', $jwt);
    
        // Ricostruisci i dati per la firma
        $data = "$base64UrlHeader.$base64UrlPayload";
    
        // Decodifica la firma fornita
        $providedSignature = $this->base64DecodeUrlSafe($base64UrlSignature);
    
        // Genera la firma attesa
        $expectedSignature = hash_hmac('sha256', $data, $secretKey, true);
    
        // Log per debug
      /*   error_log("Data for HMAC: $data");
        error_log("Signature Generated (Base64URL): " . $this->base64EncodeUrlSafe($expectedSignature));
        error_log("Signature Provided (Base64URL): $base64UrlSignature"); */
    
        // Confronta le firme
        if (hash_equals($providedSignature, $expectedSignature)) {
            // Decodifica il payload
            $payload = json_decode($this->base64DecodeUrlSafe($base64UrlPayload), true);
    
            // Verifica la scadenza del token
            if ($payload['exp'] < time()) {
                return ['valid' => false, 'error' => 'Token scaduto'];
            }
    
            return ['valid' => true, 'payload' => $payload];
        } else {
            return ['valid' => false, 'error' => 'Firma non valida'];
        }
    }
}