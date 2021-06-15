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

function validateRecipe() {
    console.log('Validating');

    try {
        image = document.getElementById('image').value;
        recipeName = document.getElementById('name').value;
        alt = document.getElementById('alt').value;
        subtitle = document.getElementById('subtitle').value;
        ingredients = document.getElementById('ingredients').value;
        method = document.getElementById('method').value;
        document.getElementById('js_validation_message').style.color = '#d80a0a';
        if (!image) {
            document.getElementById('js_validation_message').innerHTML = "An image must be selected";
            return false;
        }
        if (!recipeName) {
            document.getElementById('js_validation_message').innerHTML = "Name field must be completed";
            return false;
        }
        if (!alt) {
            document.getElementById('js_validation_message').innerHTML = "Alt field must be completed";
            return false;
        }
        if (!subtitle) {
            document.getElementById('js_validation_message').innerHTML = "Date of Birth field must be completed";
            return false;
        }
        if (!ingredients) {
            document.getElementById('js_validation_message').innerHTML = "Ingredients field must be completed";
            return false;
        }
        if (!method) {
            document.getElementById('js_validation_message').innerHTML = "Method field must be completed";
            return false;
        }
        console.log("Validated successfully");
        return true;
    } catch (e) {
        console.log("Can't set info");
        return false;
    }
}