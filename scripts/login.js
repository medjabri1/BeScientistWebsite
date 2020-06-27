 let signActive = (choice) => {
    switch(choice) {
        case 'in':
            document.querySelector('#signInToggler').classList.add('active');
            document.querySelector('#signUpToggler').classList.remove('active');

            document.querySelector('#signInDiv').classList.add('active');
            document.querySelector('#signUpDiv').classList.remove('active');
            
            break;
            
            case 'up':
            document.querySelector('#signUpToggler').classList.add('active');
            document.querySelector('#signInToggler').classList.remove('active');

            document.querySelector('#signUpDiv').classList.add('active');
            document.querySelector('#signInDiv').classList.remove('active');

            break;
    }
}

let checkInputs = (element, choice) => {
    event.preventDefault();
    switch(choice) {
        case 'in':
            email = document.querySelector('#signin_email').value;
            password = document.querySelector('#signin_password').value;
            errors = document.querySelector('#signin_errors');
            
            errors.innerHTML = '<br>';

            if(!(/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(email))) {
                errors.textContent += '* Email given is not valid';
            } else if(password.length < 8) {
                errors.textContent += '* Password given is too short';
            } else {
                element.parentNode.submit();
            }

            break;

        case 'up':
            name = document.querySelector('#signup_name').value;
            email = document.querySelector('#signup_email').value;
            password = document.querySelector('#signup_password').value;
            errors = document.querySelector('#signup_errors');
            
            errors.textContent = '';

            if(name.trim().length == 0) {
                errors.textContent += '* Name cannot be empty';
            } else if(!(/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(email))) {
                errors.textContent += '* Email given is not valid';
            } else if(password.length == 0) {
                errors.textContent += '* Password cannot be empty';
            } else if(password.length < 8) {
                errors.textContent += '* Password given is too short';
            } else {
                element.parentNode.submit();
            }

            break;
    }
}

let hideSign = () => {
    document.querySelector('#signContainer').classList.remove('active');
}

let showSign = () => {
    document.querySelector('#signContainer').classList.add('active');
}