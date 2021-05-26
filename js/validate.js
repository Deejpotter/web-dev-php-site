function validateAccount() {
    console.log('Validating');

    try {
        email = document.getElementById('email').value;
        password = document.getElementById('password').value;
        retype = document.getElementById('retype').value;
        document.getElementById('js_validation_message').style.color = '#d80a0a';
        if (!email) {
            document.getElementById('js_validation_message').innerHTML = "Email field must be completed";
            return false;
        }
        if (!password) {
            document.getElementById('js_validation_message').innerHTML = "Password field must be completed";
            return false;
        }
        if (!retype) {
            document.getElementById('js_validation_message').innerHTML = "Re-type password field must be completed";
            return false;
        }
        console.log("Validated successfully");
        return true;
    } catch (e) {
        console.log("Can't set info");
        return false;
    }
}

function validateLogin() {
    console.log('Validating');

    try {
        email = document.getElementById('email').value;
        password = document.getElementById('password').value;
        document.getElementById('js_validation_message').style.color = '#d80a0a';
        if (!email) {
            document.getElementById('js_validation_message').innerHTML = "Email field must be completed";
            return false;
        }
        if (!password) {
            document.getElementById('js_validation_message').innerHTML = "Password field must be completed";
            return false;
        }
        console.log("Validated successfully");
        return true;
    } catch (e) {
        console.log("Not validated successfully");
        return false;
    }
}

function validatePet() {
    console.log('Validating');

    try {
        type = document.getElementById('type').value;
        breed = document.getElementById('breed').value;
        dob = document.getElementById('dob').value;
        document.getElementById('js_validation_message').style.color = '#d80a0a';
        if (!type) {
            document.getElementById('js_validation_message').innerHTML = "Type field must be completed";
            return false;
        }
        if (!breed) {
            document.getElementById('js_validation_message').innerHTML = "Breed field must be completed";
            return false;
        }
        if (!dob) {
            document.getElementById('js_validation_message').innerHTML = "Date of Birth field must be completed";
            return false;
        }
        console.log("Validated successfully");
        return true;
    } catch (e) {
        console.log("Can't set info");
        return false;
    }
}