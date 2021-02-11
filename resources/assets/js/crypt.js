const StripeKey = process.env.MIX_CRYPT_KEY;
const CryptoJS = require("crypto-js");
const storageKey = "StripeViveres";

window.StripeCrypt = (message) => {
	localStorage.setItem(storageKey, CryptoJS.AES.encrypt(message,StripeKey).toString());
}

window.StripeDecrypt = () => {
	return CryptoJS.AES.decrypt(localStorage.getItem(storageKey),StripeKey).toString(CryptoJS.enc.Utf8);
}