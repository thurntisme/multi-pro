function getToken() {
  return localStorage.getItem("authToken");
}
function removeToken() {
  localStorage.removeItem("authToken");
}

function redirectInLoggedInUser() {
  const path = window.location.pathname;
  const lastIndex = path.lastIndexOf("/");
  const firstPart = (path.substring(0, lastIndex) || "") + "/login";
  const lastPart = path.substring(lastIndex + 1);
  const excludePath = ["login", "register", "forgot-password"];
  if (!excludePath.includes(lastPart)) {
    window.location.pathname = firstPart;
  }
}

// Check if the user is logged in by verifying the token in localStorage
function checkLoginStatus() {
  const token = localStorage.getItem("authToken");
  if (!token) {
    redirectInLoggedInUser();
  } else {
    // Send a request to the server to verify the token
    fetch("authentication.php", {
      method: "POST",
      headers: {
        Authorization: token,
        "Content-Type": "application/json",
      },
      // body: JSON.stringify({ action: "check_authentication" }),
    })
      .then((response) => {
        if (!response.ok) {
          throw new Error("Unauthorized");
        }
        return response.json();
      })
      .then((data) => {
        console.log("User is authenticated:", data);
        // Proceed with the rest of your application logic
      })
      .catch((error) => {
        console.error("Authentication failed:", error);
        redirectInLoggedInUser();
      });
  }
}

jQuery(document).ready(function ($) {
  console.log("hello world");
  // checkUserLoggedIn();
  // checkLoginStatus();
});
