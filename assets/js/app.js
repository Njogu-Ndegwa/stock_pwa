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


function checkStockIn(buttonElement) {
    const form = buttonElement.form;
    if (!form.checkValidity()) {
      form.reportValidity();
      return false;
    }
    const itemID = form.elements[3].value;
    const itemQuantity = form.elements[4].value;

    fetch('app/formhandlers/inventory/checkLowerLevel', {
        method: 'POST',
        body: JSON.stringify({item_id: itemID, quantity: itemQuantity})
    })
    .then(res => res.json())
    .then(json => {
      if (json.message == 'Less') {
        const result = confirm("Exceed maximum threshold continue stocking in?");
        if (!result) {
          console.log('form submission cancelled');
        }else {
          form.submit();
        }
      }else {
        form.submit();
      }
    })
    .catch(err => alert(err));
}

function checkInventoryAcquisition(buttonElement) {
    const form = buttonElement.form;
    if (!form.checkValidity()) {
      form.reportValidity();
      return false;
    }
    const itemID = form.elements[3].value;
    const itemQuantity = form.elements[4].value;

    fetch('app/formhandlers/inventory/checkInventoryLevels', {
        method: 'POST',
        body: JSON.stringify({item_id: itemID, quantity: itemQuantity})
    })
    .then(res => res.json())
    .then(json => {
      if (json.message == 'Less') {
        form.submit();
      }else {
        const result = confirm("Lower than minimum threshold continue acquisition?");
        if (!result) {
          console.log('form submission cancelled');
        }else {
          form.submit();
        }
      }
    })
    .catch(err => alert(err));
}

function openDropdown(dropDownTrigger) {
  dropDownTrigger.nextElementSibling.classList.toggle("show");
}
function closeDropdown(dropDownTrigger) {
  dropDownTrigger.parentElement.classList.toggle("show");
}

// Close the dropdown if the user clicks outside of it
window.onclick = function(event) {
  if (!event.target.matches('.dropbtn')) {
    const dropdowns = document.getElementsByClassName("dropdown-content");
    let i;
    for (i = 0; i < dropdowns.length; i++) {
      let openDropdown = dropdowns[i];
      if (openDropdown.classList.contains('show')) {
        openDropdown.classList.remove('show');
      }
    }
  }
}

function addVendor(submitBtn) {
  const inputTags = submitBtn.parentElement.querySelectorAll('input');

  const vendorName = inputTags[0];
  const vendorEmail = inputTags[1];
  const vendorMobile = inputTags[2];
  const vendorDescription = inputTags[3];

  submitBtn.disabled = true;

  submitBtn.innerHTML = 'Please Wait';

  fetch('app/formhandlers/vendor/addVendorAPI', {
      method: 'POST',
      body: JSON.stringify({
        vendorName: vendorName.value,
        vendorEmail: vendorEmail.value,
        vendorMobile: vendorMobile.value,
        vendorDescription: vendorDescription.value
      })
  })
  .then(res => res.json())
  .then(json => {
    if (json.response == '200') {

      submitBtn.innerHTML = 'Success';
      let newOption = document.createElement('option');
      newOption.value = json.data[1];
      newOption.innerHTML = vendorName.value;

      document.querySelectorAll('.vendor-option').forEach((vendorSelect) => {
        vendorSelect.appendChild(newOption);
      });

      inputTags[0].innerHTML = '';
      inputTags[1].innerHTML = '';
      inputTags[2].innerHTML = '';
      inputTags[3].innerHTML = '';

    }else {
      submitBtn.innerHTML = 'Error'
    }

  })
  .catch(err => alert(err));
  setTimeout(function(){
    submitBtn.innerHTML = 'Add a Vendor';
    submitBtn.disabled = false;
  }, 1000);
}
