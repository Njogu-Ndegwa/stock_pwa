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

function changeUnit(selectElement) {
  if (selectElement.value == 'Hardware' || selectElement.value == 'Aluminum') {
    document.querySelector('#unit').innerHTML = '(Units)';
  }else {
    document.querySelector('#unit').innerHTML = '(in KG)';
  }
}


function updateItemCode(selectElement) {
  document.querySelector('#itemCodeStockIn').value = selectElement.options[selectElement.options.selectedIndex].getAttribute('data-code')
}

function getLocationWarehouses(selectElement) {

  fetch('app/formhandlers/warehouse/getLocationWarehouse', {
      method: 'POST',
      body: JSON.stringify({location_id: selectElement.value})
  })
  .then(res => res.json())
  .then(json => {
    const selectWarehouse = document.querySelector('#warehouseIDStockIn');
    selectWarehouse.innerHTML = '';
    if (json.response == '204') {

      const option = document.createElement('option');
      option.innerHTML = 'There are no warehouses in that location';
      option.disabled=true;
      option.selected=true;
      selectWarehouse.appendChild(option);

    }else if (json.response == '200') {

      json.data.forEach((warehouseLocation) => {

        const option = document.createElement('option');
        option.innerHTML = warehouseLocation.warehouse_name;
        option.value = warehouseLocation.warehouse_id;
        selectWarehouse.appendChild(option);

      });

    }
  })
  .catch(err => alert(err));
}
function addSerialNumber () {
    document.querySelectorAll('#itemsTable > tr').forEach((item, index) => {
      item.querySelector('td:nth-child(1)').innerHTML = index + 2;
    })
};

addSerialNumber();

function addItemRow() {
  const cloneChild = document.querySelector('#itemsTable').querySelector('tbody').querySelector('tr').cloneNode(true);
  cloneChild.querySelectorAll('input').forEach((inputElement) => {
    inputElement.value='';
  })
  document.querySelector('#itemsTable').appendChild(cloneChild);
  addSerialNumber();
}

function calculateEstimate() {
  let weight = parseFloat(document.querySelector('#weightInput').value);
  const profile = document.querySelector('#profileType').value;
  if (isNaN(weight)) {
    weight = 0;
  }

  let result;
  switch (profile) {
    case 'Heavy':
      result = weight/28;
      break;
      case 'Medium':
        result = weight/24;
        break;
    default:
      result = weight/20;
  }
  document.querySelector('#powderEstimate').value = result.toFixed(2);
}

function fieldUpdate(selectElement) {
  if (selectElement.value == 'Powder') {
    document.querySelector('#itemCode').disabled = false;
    document.querySelector('#vendorName').disabled = false;
    document.querySelector('#unit').innerHTML = '(in KG)';
  }else {
    document.querySelector('#itemCode').disabled = true;
    document.querySelector('#vendorName').disabled = true;
    document.querySelector('#unit').innerHTML = '(in Units)';
  }
}
