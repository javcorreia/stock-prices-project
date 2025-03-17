import {jwtDecode} from "jwt-decode"; // Install this with: npm install jwt-decode

export function isJwtValid(jwt) {
    if (!jwt) return false; // No JWT present

    try {
        const decoded = jwtDecode(jwt); // Decode the token
        const currentTimestamp = Math.floor(Date.now() / 1000);
        return decoded.exp > currentTimestamp; // Check if it's not expired
    } catch (e) {
        return false; // Invalid JWT structure
    }
}
