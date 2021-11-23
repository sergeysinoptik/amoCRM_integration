let popupBg = document.querySelector('.popup__bg');
let popup = document.querySelector('.popup');
let popupMobile = document.querySelector('.popup__mobile');
let openPopupButton = document.querySelector('.popup__open-button');
let closePopupButtons = document.querySelectorAll('.popup__close-button');
let phoneNumber = document.querySelector('.form__phone-field');
let phoneNumberMobile = document.querySelector('.form__phone-field-mobile');
let form = document.querySelector('.form__desctop');
let formMobile = document.querySelector('.form__mobile');

openPopupButton.addEventListener('click',() => { 
    popupBg.classList.add('active');
    popup.classList.add('active');
    popupMobile.classList.add('active');
});

var elements = document.getElementsByClassName('form__phone-field');
        for (var i = 0; i < elements.length; i++) {
          new IMask(elements[i], {
            mask: '+{7} (000)000-00-00',
          });
        }

closePopupButtons.forEach((button) => { 
    button.addEventListener('click', (e) => {
        popupBg.classList.remove('active');
        popup.classList.remove('active');
        popupMobile.classList.remove('active');
    })
});

document.addEventListener('click', (e) => { 
    if(e.target === popupBg) {
        popupBg.classList.remove('active');
        popup.classList.remove('active');
        popupMobile.classList.remove('active');
    }
});

phoneNumber.addEventListener('click', (e) => {
    e.target.value = '+7 (';
});


form.onsubmit = function(e) {
    if (phoneNumber.value.length == 17) {
        e.preventDefault();      

        let formData = new FormData(form);
        let xhr = new XMLHttpRequest();
        xhr.open("POST", "./mail.php");
        xhr.send(formData);
        xhr.onload = function() {
            form.reset();
            alert("Success");
        };
    } else {
        e.preventDefault();
        alert("false");
    }
};

formMobile.onsubmit = function(e) {
    if (phoneNumberMobile.value.length == 17) {
        e.preventDefault();      

        let formData = new FormData(formMobile);
        let xhr = new XMLHttpRequest();
        xhr.open("POST", "./mail.php");
        xhr.send(formData);
        xhr.onload = function() {
            formMobile.reset();
            alert("Success");
        };
    } else {
        let formData = new FormData(formMobile);
        e.preventDefault();
        alert(formData);
    }
};
