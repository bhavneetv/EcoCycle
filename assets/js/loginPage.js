// DOM Elements
const loginTab = document.getElementById("loginTab");
const signupTab = document.getElementById("signupTab");
const loginForm = document.getElementById("loginForm");
const signupForm = document.getElementById("signupForm");
const loginBtn = document.getElementById("loginBtn");
const signupBtn = document.getElementById("signupBtn");

// Password toggle elements
const toggleLoginPassword = document.getElementById("toggleLoginPassword");
const toggleSignupPassword = document.getElementById("toggleSignupPassword");
const toggleConfirmPassword = document.getElementById("toggleConfirmPassword");

// Form inputs
const loginEmail = document.getElementById("loginEmail");
const loginPassword = document.getElementById("loginPassword");
const signupName = document.getElementById("signupName");
const signupEmail = document.getElementById("signupEmail");
const pincode = document.getElementById("signupAddress");
const signupPassword = document.getElementById("signupPassword");
const confirmPassword = document.getElementById("confirmPassword");

// Tab Switching
function switchTab(activeTab, inactiveTab, showForm, hideForm) {
  // Update tab styles
  activeTab.classList.add(
    "bg-gradient-to-r",
    "from-green-500",
    "to-blue-500",
    "text-white"
  );
  activeTab.classList.remove("text-gray-400");

  inactiveTab.classList.remove(
    "bg-gradient-to-r",
    "from-green-500",
    "to-blue-500",
    "text-white"
  );
  inactiveTab.classList.add("text-gray-400");

  // Switch forms with animation
  hideForm.classList.add("hidden");
  showForm.classList.remove("hidden");
  showForm.classList.add("slide-in");

  // Clear all error messages and form data
  clearErrors();
  clearForms();
}

loginTab.addEventListener("click", () => {
  switchTab(loginTab, signupTab, loginForm, signupForm);
});

signupTab.addEventListener("click", () => {
  switchTab(signupTab, loginTab, signupForm, loginForm);
});

// Password Toggle Functionality
function togglePasswordVisibility(input, toggleBtn) {
  const isPassword = input.type === "password";
  input.type = isPassword ? "text" : "password";

  const icon = toggleBtn.querySelector("svg");
  if (isPassword) {
    // Show eye-off icon
    icon.innerHTML = `
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L12 12m-2.122-2.122L7.757 7.757"></path>
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3l18 18"></path>
      `;
  } else {
    // Show eye icon
    icon.innerHTML = `
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
      `;
  }
}

toggleLoginPassword.addEventListener("click", () => {
  togglePasswordVisibility(loginPassword, toggleLoginPassword);
});

toggleSignupPassword.addEventListener("click", () => {
  togglePasswordVisibility(signupPassword, toggleSignupPassword);
});

toggleConfirmPassword.addEventListener("click", () => {
  togglePasswordVisibility(confirmPassword, toggleConfirmPassword);
});

// Validation Functions
function validateEmail(email) {
  const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
  return emailRegex.test(email);
}

function showError(input, errorElement, message) {
  errorElement.textContent = message;
  errorElement.classList.remove("hidden");
  input.classList.add("border-red-500", "error-shake");

  // Remove shake animation after it completes
  setTimeout(() => {
    input.classList.remove("error-shake");
  }, 500);
}

function hideError(input, errorElement) {
  errorElement.classList.add("hidden");
  input.classList.remove("border-red-500");
}

function clearErrors() {
  const errorElements = document.querySelectorAll('[id$="Error"]');
  const inputElements = document.querySelectorAll("input");

  errorElements.forEach((error) => error.classList.add("hidden"));
  inputElements.forEach((input) => input.classList.remove("border-red-500"));
}

function clearForms() {
  loginEmail.value = "";
  loginPassword.value = "";
  signupName.value = "";
  signupEmail.value = "";
  signupPassword.value = "";
  confirmPassword.value = "";
  document.getElementById("rememberMe").checked = false;
}

// Login Validation
function validateLogin() {
  let isValid = true;
  const email = loginEmail.value.trim();
  const password = loginPassword.value.trim();

  // Clear previous errors
  hideError(loginEmail, document.getElementById("loginEmailError"));
  hideError(loginPassword, document.getElementById("loginPasswordError"));

  // Email validation
  if (!email) {
    showError(
      loginEmail,
      document.getElementById("loginEmailError"),
      "Email is required"
    );
    isValid = false;
  } else if (!validateEmail(email)) {
    showError(
      loginEmail,
      document.getElementById("loginEmailError"),
      "Please enter a valid email address"
    );
    isValid = false;
  }

  // Password validation
  if (!password) {
    showError(
      loginPassword,
      document.getElementById("loginPasswordError"),
      "Password is required"
    );
    isValid = false;
  } else if (password.length < 1) {
    showError(
      loginPassword,
      document.getElementById("loginPasswordError"),
      "Password must be at least 6 characters"
    );
    isValid = false;
  }

  return isValid;
}

// Signup Validation
function validateSignup() {
  let isValid = true;
  const name = signupName.value.trim();
  const email = signupEmail.value.trim();
  const password = signupPassword.value.trim();
  const confirmPass = confirmPassword.value.trim();
  const pincode = signupAddress.value.trim();

  // Clear previous errors
  hideError(signupName, document.getElementById("signupNameError"));
  hideError(signupEmail, document.getElementById("signupEmailError"));
  hideError(signupPassword, document.getElementById("signupPasswordError"));
  hideError(confirmPassword, document.getElementById("confirmPasswordError"));

  // Name validation
  if (!name) {
    showError(
      signupName,
      document.getElementById("signupNameError"),
      "Full name is required"
    );
    isValid = false;
  } else if (name.length < 2) {
    showError(
      signupName,
      document.getElementById("signupNameError"),
      "Name must be at least 2 characters"
    );
    isValid = false;
  }

  // Email validation
  // if (!email) {
  //     showError(signupEmail, document.getElementById('signupEmailError'), 'Email is required');
  //     isValid = false;
  // } else if (!validateEmail(email)) {
  //     showError(signupEmail, document.getElementById('signupEmailError'), 'Please enter a valid email address');
  //     isValid = false;
  // }

  // Password validation
  if (!password) {
    showError(
      signupPassword,
      document.getElementById("signupPasswordError"),
      "Password is required"
    );
    isValid = false;
  } else if (password.length < 1) {
    showError(
      signupPassword,
      document.getElementById("signupPasswordError"),
      "Password must be at least 6 characters"
    );
    isValid = false;
  }

  // Confirm password validation
  if (!confirmPass) {
    showError(
      confirmPassword,
      document.getElementById("confirmPasswordError"),
      "Please confirm your password"
    );
    isValid = false;
  } else if (password !== confirmPass) {
    showError(
      confirmPassword,
      document.getElementById("confirmPasswordError"),
      "Passwords do not match"
    );
    isValid = false;
  }

  if (!pincode) {
    showError(
      pincode,
      document.getElementById("signupAddressError"),
      "Pincode is required"
    );
    isValid = false;
  }

  return isValid;
}

// Form Submission
loginBtn.addEventListener("click", (e) => {
  if (!validateLogin()) {
    return;
  }

  e.preventDefault();

  loginBtn.innerHTML = `
  <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
  <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
  <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
  </svg>
  Signing In...
  `;
  loginBtn.disabled = true;

  login(loginEmail.value, loginPassword.value);

  setTimeout(() => {
    // Reset button
    loginBtn.innerHTML = "Sign In";
    loginBtn.disabled = false;

    // Redirect to dashboard
  }, 2500);
});
function login() {
  $.ajax({
    url: "includes/login.php",
    type: "POST",
    data: {
      email: loginEmail.value,
      password: loginPassword.value,
    },
    success: function (data) {
      if (data == "success") {
        // window.
        window.location.href = "login.php";
      } else {
        document.getElementById("loginBtn").textContent = data;
        document.getElementById("loginBtn").style.color = "red";
        //  document.getElementById("loginBtn").style.backgroundColor="red";
        document
          .getElementById("loginBtn")
          .classList.remove("bg-gradient-to-r");
        document.getElementById("loginBtn").classList.add("bg-red-450");
        document.getElementById("loginBtn").style.transition = "all 0.5s ease";

        setTimeout(() => {
          document.getElementById("loginBtn").style.color = "white";
          document.getElementById("loginBtn").style.backgroundColor =
            "transparent";
          document.getElementById("loginBtn").classList.add("bg-gradient-to-r");
          // document.getElementById("loginBtn").classList.add("bg-red-450");
          document.getElementById("loginBtn").style.border = "1px solid white";
          document.getElementById("loginBtn").style.transition =
            "all 0.5s ease";
        }, 2500);
      }
    },
  });
}

document.getElementById("signupBtn").addEventListener("click", () => {
  let v = validateSignup();
  if (!v) {
    // window.location.reload();
    return;
  }

  // alert("Signup Button Clicked");
  // Show loading state
  signupBtn.innerHTML = `
          <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
              <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
              <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
          </svg>
          Creating Account...
      `;
  signupBtn.disabled = true;
  signup();

  // Simulate API call
  setTimeout(() => {
    // Reset button
    signupBtn.innerHTML = "Create Account";
    signupBtn.disabled = false;

    // Redirect to dashboard
    //  window.location.href = 'index.html';
  }, 2500);
});

// Real-time validation
// loginEmail.addEventListener('input', () => {
//     if (loginEmail.value.trim() && validateEmail(loginEmail.value.trim())) {
//         hideError(loginEmail, document.getElementById('loginEmailError'));
//     }
// });

signupEmail.addEventListener("input", () => {
  if (signupEmail.value.trim() && validateEmail(signupEmail.value.trim())) {
    hideError(signupEmail, document.getElementById("signupEmailError"));
  }
});

confirmPassword.addEventListener("input", () => {
  if (
    confirmPassword.value.trim() &&
    signupPassword.value.trim() === confirmPassword.value.trim()
  ) {
    hideError(confirmPassword, document.getElementById("confirmPasswordError"));
  }
});

// Enter key submission
document.addEventListener("keydown", (e) => {
  if (e.key === "Enter") {
    if (!loginForm.classList.contains("hidden")) {
      loginBtn.click();
    } else if (!signupForm.classList.contains("hidden")) {
      signupBtn.click();
    }
  }
});

// Initialize with entrance animation
document.addEventListener("DOMContentLoaded", () => {
  const authContainer = document.querySelector(".w-full.max-w-md");
  authContainer.style.opacity = "0";
  authContainer.style.transform = "translateY(20px)";

  setTimeout(() => {
    authContainer.style.transition = "all 0.6s ease";
    authContainer.style.opacity = "1";
    authContainer.style.transform = "translateY(0)";
  }, 100);
});

document.getElementById("guestBtn").addEventListener("click", function () {
  window.location.href = "dashboard/index.php";
});

// document.getElementById("signupBtn").addEventListener("click", function () {
//   alert("Signup Button Clicked");

// });
function signup() {
  $.ajax({
    url: "includes/signup.php",
    type: "POST",
    data: {
      name: $("#signupName").val(),
      email: $("#signupEmail").val(),
      password: $("#signupPassword").val(),

      pincode: $("#signupAddress").val(),
    },
    success: function (data) {
      if (data == "success") {
        alert("activation email sent to your email");
        window.location.href = "../../login.php";
      } else {
        document.getElementById("signupBtn").textContent = data;
        document.getElementById("signupBtn").style.color = "red";
        //  document.getElementById("loginBtn").style.backgroundColor="red";
        document
          .getElementById("signupBtn")
          .classList.remove("bg-gradient-to-r");
        document.getElementById("signupBtn").classList.add("bg-red-450");
        document.getElementById("signupBtn").style.transition = "all 0.5s ease";

        setTimeout(() => {
          document.getElementById("signupBtn").style.color = "white";
          document.getElementById("signupBtn").style.backgroundColor =
            "transparent";
          document
            .getElementById("signupBtn")
            .classList.add("bg-gradient-to-r");
          // document.getElementById("loginBtn").classList.add("bg-red-450");
          document.getElementById("signupBtn").style.border = "1px solid white";
          document.getElementById("signupBtn").style.transition =
            "all 0.5s ease";
        }, 2500);
      }
    },
  });
}

const showPopupBtn = document.getElementById("showPopupBtn");
const forgotPasswordPopup = document.getElementById("forgotPasswordPopup");
const closePopupBtn = document.getElementById("closePopupBtn");
const backToLoginBtn = document.getElementById("backToLoginBtn");
const sendResetBtn = document.getElementById("sendResetBtn");
const forgotEmail = document.getElementById("forgotEmail");
const forgotEmailError = document.getElementById("forgotEmailError");

// Show popup
showPopupBtn.addEventListener("click", () => {
  forgotPasswordPopup.classList.remove("hidden");
  document.body.style.overflow = "hidden";
  forgotEmail.focus();
});

// Close popup function
function closePopup() {
  const popupCard = forgotPasswordPopup.querySelector(".popup-enter");
  popupCard.classList.remove("popup-enter");
  popupCard.classList.add("popup-exit");

  setTimeout(() => {
    forgotPasswordPopup.classList.add("hidden");
    document.body.style.overflow = "auto";
    popupCard.classList.remove("popup-exit");
    popupCard.classList.add("popup-enter");
    // Reset form
    forgotEmail.value = "";
    hideError();
  }, 200);
}

// Close popup events
closePopupBtn.addEventListener("click", closePopup);
backToLoginBtn.addEventListener("click", closePopup);

// Close on overlay click
forgotPasswordPopup.addEventListener("click", (e) => {
  if (e.target === forgotPasswordPopup) {
    closePopup();
  }
});

// Close on Escape key
document.addEventListener("keydown", (e) => {
  if (e.key === "Escape" && !forgotPasswordPopup.classList.contains("hidden")) {
    closePopup();
  }
});

// Validation functions
function validateEmail(email) {
  const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
  return re.test(email);
}

function showError(message) {
  forgotEmailError.textContent = message;
  forgotEmailError.classList.remove("hidden");
  forgotEmail.classList.add("border-red-500");
}

function hideError() {
  forgotEmailError.classList.add("hidden");
  forgotEmail.classList.remove("border-red-500");
}

// Send reset link
sendResetBtn.addEventListener("click", async () => {
  const email = forgotEmail.value.trim();

  // Hide previous errors
  hideError();

  // Validate email
  if (!email) {
    showError("Email address is required");
    return;
  }

  if (!validateEmail(email)) {
    showError("Please enter a valid email address");
    return;
  }

  // Show loading state
  const btnText = sendResetBtn.querySelector(".btn-text");
  const spinner = sendResetBtn.querySelector(".spinner");

  btnText.textContent = "Sending...";
  spinner.classList.remove("hidden");
  sendResetBtn.disabled = true;

  try {
    const response = await fetch('backend/sendResetLink.php', {
      method: 'POST',
      headers: {
          'Content-Type': 'application/json',
      },
      body: JSON.stringify({
          email: email,
      })
  });
  const data = await response.json();
  // console.log(data);

  // Simulate delay
  await new Promise(resolve => setTimeout(resolve, 2000));
  if (data == "true") {
    alert(`Reset link sent to ${email}! Check your inbox.`);
    closePopup();
  } else {
    showError("Failed to send reset link. Please try again.");
  }
  } catch (error) {
    console.log(error);
    showError("Failed to send reset link. Please try again.");
  } finally {
    // Reset button state
    btnText.textContent = "Send Reset Link";
    spinner.classList.add("hidden");
    sendResetBtn.disabled = false;
  }
});

// Enter key to submit
forgotEmail.addEventListener("keypress", (e) => {
  if (e.key === "Enter") {
    sendResetBtn.click();
  }
});

// Clear error on input
forgotEmail.addEventListener("input", () => {
  if (!forgotEmailError.classList.contains("hidden")) {
    hideError();
  }
});

function resetPassword() {
  $.ajax({});
}
