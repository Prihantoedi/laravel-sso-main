let code;

const createCaptcha = () => {
    document.getElementById('captcha').innerHTML = '';

    let charsArray = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ@!#$%^&*';

    let lengthOtp = 6;

    let captcha = []

    for (let i = 0; i < lengthOtp; i++){

        // this code will not allow repetition of characters
        let index = Math.floor(Math.random() * charsArray.length + 1); // get the next character from the array

        if(captcha.indexOf(charsArray[index]) == -1) captcha.push(charsArray[index]);

        else i--;
    }

    let canv = document.createElement('canvas');
    canv.id = 'captcha';
    canv.width = 100;
    canv.height = 50;
    let ctx = canv.getContext('2d');

    ctx.font = '25px Georgia';
    ctx.strokeText(captcha.join(''), 0, 30);

    // storing captcha so it can be validate
    code = captcha.join('');
    document.getElementById('captcha').appendChild(canv);

}

let authBtn = document.getElementById('auth-btn');

authBtn.addEventListener('click', function(event){
    event.preventDefault();
    let captchaNotif = document.getElementById('captcha-notif');
    if(document.getElementById('captchaTextBox').value === code){

        const msgTxt = 'Your captcha is valid';
        captchaNotif.innerHTML = msgTxt;
        captchaNotif.style.color = 'green';
        
        setTimeout( () => {
            window.location.href = `http://127.0.0.1:8080/transit?acc=${token['access']}`;
        }, 3000);
        
    } else{

        const msgTxt = 'Invalid Captcha!';
        captchaNotif.innerHTML = msgTxt;
        captchaNotif.style.color = 'red';
        createCaptcha();
    }

    captchaNotif.style.display = 'block';
    // 
});