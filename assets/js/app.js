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

function validateEmail(inputText)
{
    const mailFormat = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;
    if(inputText.value.match(mailFormat)){
       return true;
    }else{
       return false;
    }
}
