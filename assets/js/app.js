/**
 * Validate the login page form
 */
function validateLoginForm(event) {
    const usernameInput = event.currentTarget.elements[1];
    const passwordInput = event.currentTarget.elements[2];
    let valid = true;
    if (usernameInput.value == '') {
        usernameInput.style.backgroundColor = '#d40e0e5e';
        valid = false;
    }else {
        usernameInput.style.backgroundColor = '';
    }
    if(passwordInput.value == ''){
        passwordInput.style.backgroundColor = '#d40e0e5e';
        valid = false;
    }else {
        passwordInput.style.backgroundColor = '';
    }
    if (usernameInput.value.includes('@')) {
        if (!validateEmail(usernameInput)) {
            usernameInput.style.backgroundColor = '#d40e0e5e';
            valid = false;
        }else {
            usernameInput.style.backgroundColor = '';
        }
    }
    if (!valid) {
        event.preventDefault();
        return false;
    }
}

/**
 * Validate the login page form
 */
function validateSuperAdminForm(event) {
    const inputElements = event.currentTarget.elements;
    const emailInput = inputElements[1];
    const usernameInput = inputElements[2];
    const passwordInput = inputElements[3];
    const confPasswordInput = inputElements[4];

    let valid = true;

    if (emailInput.value == '') {
      emailInput.style.backgroundColor = '#d40e0e5e';
      valid = false;
    }else {
      if (!validateEmail(emailInput)) {
          emailInput.style.backgroundColor = '#d40e0e5e';
          valid = false;
      }else {
          emailInput.style.backgroundColor = '';
      }
    }

    if (usernameInput.value == '') {
      usernameInput.style.backgroundColor = '#d40e0e5e';
      valid = false;
    }else {
      usernameInput.style.backgroundColor = '';
    }

    if (passwordInput.value == '') {
      passwordInput.style.backgroundColor = '#d40e0e5e';
      valid = false;
    }else {
      passwordInput.style.backgroundColor = '';
    }

    if (confPasswordInput.value == '') {
      confPasswordInput.style.backgroundColor = '#d40e0e5e';
      valid = false;
    }else {
      confPasswordInput.style.backgroundColor = '';
    }

    if (confPasswordInput.value != passwordInput.value) {
      confPasswordInput.style.backgroundColor = '#d40e0e5e';
      passwordInput.style.backgroundColor = '#d40e0e5e';
      valid = false;
    }else {
      passwordInput.style.backgroundColor = '';
      confPasswordInput.style.backgroundColor = '';
    }

    if (!valid) {
        event.preventDefault();
        return false;
    }
}

/**
 * Validate that an input is of email format
 */
function validateEmail(inputText)
{
    const mailFormat = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;
    if(inputText.value.match(mailFormat)){
       return true;
    }else{
       return false;
    }
}

document.querySelector('#keyInput').value = getRandomString(15);

/**
 * Generate a random string
 */
function getRandomString(length) {
    var randomChars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
    var result = '';
    for ( var i = 0; i < length; i++ ) {
        result += randomChars.charAt(Math.floor(Math.random() * randomChars.length));
    }

    return result;
}

/**
 * Open a modal
 */
function openModal(modalID) {
  const modal = document.querySelector(modalID);
  modal.style.height = '100vh';
  modal.style.overflow = 'scroll';
}

/**
 * Close a modal
 */
function closeModal(modalID) {
  const modal = document.querySelector(modalID);
  modal.style.height = '0vh';
  modal.style.overflow = 'hidden';
}

/**
 * Close an alert box
 */
function closeAlert(alertElement) {
  alertElement.parentElement.style.display = 'none';
}
