import '../stimulus_bridge_init'
import 'bootstrap'
import { Toast } from 'bootstrap/dist/js/bootstrap.esm.min'
import IMask from 'imask';

require('../scss/sign.scss')
require('../css/sign.css')
require('../../templates/bootstrap-5.1.3/sign-in/signin.css')

/**
 * Show_me_password
 * TODO: отображение пароля не работает
 */
window.addEventListener("load", function() {
    let showMePassword = document.getElementById('vendor_security_vendorSecurity_show_me_password');
    let firstPassword = document.getElementById('vendor_security_vendorSecurity_plainPassword_first');
    let secondPassword = document.getElementById('vendor_security_vendorSecurity_plainPassword_second');

    showMePassword.addEventListener('click', () => {
        if ( firstPassword.type === "text" ) {
            firstPassword.type = "password"
            secondPassword.type = "password"
            //showPassword.classList.remove('fa-eye-slash');
        } else {
            firstPassword.type = "text"
            secondPassword.type = "text"
            firstPassword.classList.add('mb-2')
            secondPassword.classList.add('mb-2')
            //showPassword.classList.toggle("fa-eye-slash");
        }
    })
})

/**
 * Password validation
 */
if (document.querySelectorAll('input[type="password"]').length > 1) {

    let passwordFirst = document.getElementById('vendor_security_vendorSecurity_plainPassword_first')
    let passwordSecond = document.getElementById('vendor_security_vendorSecurity_plainPassword_second')

    let submit = document.querySelector('button[type="submit"]')

    submit.classList.add('disabled')

    passwordFirst.addEventListener('input', plainValidation)
    passwordSecond.addEventListener('input', plainValidation)

    function plainValidation(){
        if (passwordFirst.value !== passwordSecond.value) {
            submit.classList.add('disabled')
            passwordFirst.classList.remove('is-valid')
            passwordFirst.classList.add('is-invalid')
            passwordSecond.classList.remove('is-valid')
            passwordSecond.classList.add('is-invalid')
        } else {
            let submit = document.querySelector('button[type="submit"]')
            passwordFirst.classList.remove('is-invalid')
            passwordFirst.classList.add('is-valid')
            passwordSecond.classList.remove('is-invalid')
            passwordSecond.classList.add('is-valid')
            submit.classList.remove('disabled')
        }
    }
}
/**
 * Toast init
 */
Array.from(document.querySelectorAll('.toast'))
    .forEach(toastNode => new Toast(toastNode).show());

/**
 * Bootstrap validation
 */
Array.prototype.slice.call(document.querySelectorAll('form[class="needs-validation"]'))
    .forEach(function (form) {
        form.addEventListener('submit', function (event) {
            if (!form.checkValidity()) {
                event.preventDefault()
                event.stopPropagation()
            }

            form.classList.add('was-validated')
        }, false)
    })

/**
 * IMask pattern
 */
if (document.querySelectorAll('input[type="tel"]').length > 0) {
    const vendorSecurity_phone = document.getElementById('vendor_registration_vendorSecurity_phone');
    const maskOptions = {
        mask: '+00000000000[0000]',
        overwrite: true,
    };
    const mask = IMask(vendorSecurity_phone, maskOptions);
}

    // app.post('/sendSMS', function (req, res) {
    //     const {phoneNumber, recaptchaToken} = req.body;
    //
    //     const identityToolkit = google.identitytoolkit({
    //         auth: 'GCP_API_KEY',
    //         version: 'v3',
    //     });
    //
    //     identityToolkit.relyingparty.verifyPhoneNumber({
    //         code: verificationCode,
    //         sessionInfo: phoneSessionId,
    //     });
    //
    //     // verification code accepted, update phoneNumberVerified flag in database
    //     // ...
    // });
