// GESTION TOKE
function setToken(key, value, exdays) {
  const now = new Date();
  const item = {
    value: value,
    expires: now.getTime() + exdays * 24 * 60 * 60 * 1000 // = timestamp ms
  };
  localStorage.setItem(key, JSON.stringify(item));
}

function getToken(key) {
  const itemStr = localStorage.getItem(key);
  if (!itemStr) return "";

  try {
    const item = JSON.parse(itemStr);
    if (Date.now() > item.expires) {
      localStorage.removeItem(key);
      return "";
    }
    return item.value;
  } catch (e) {
    localStorage.removeItem(key);
    return "";
  }
}


document.addEventListener("DOMContentLoaded", function () {

  // CONNEXION 
  const loginForm = document.getElementById("loginForm");
  if (loginForm) {
    loginForm.addEventListener("submit", function (e) {
      e.preventDefault();

      const email = document.getElementById("email")?.value;
      const password = document.getElementById("password")?.value;

      if (email === "test@test.com" && password === "Password123") {
        const fakeToken = "TOKEN_JWT_123456";

        setToken("auth_token", fakeToken, 7);
        window.location.href = "Accueil_finale.html";
      } else {
        alert("Email ou mot de passe incorrect");
      }
    });
  }

  // INSCRIPTION
  const SignupForm = document.getElementById("SignupForm");
  if (SignupForm) {
    SignupForm.addEventListener("submit", function (e) {
      e.preventDefault();

      const password = document.getElementById("signup-password")?.value;
      const confirmation = document.getElementById("signup-confirmation")?.value;

      if (password !== confirmation) {
        alert("Les mots de passe ne correspondent pas");
        return;
      }

      alert("Compte créé avec succès ");
      window.location.href = "Accueil_finale.html";
    });
  }

});
