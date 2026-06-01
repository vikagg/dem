document.addEventListener('DOMContentLoaded', function() {
    const slider = document.querySelector('.slider');
    const slides = document.querySelectorAll('.slide');
    const prevBtn = document.querySelector('.prev');
    const nextBtn = document.querySelector('.next');
    const dotsContainer = document.querySelector('.dots');
    
    if (slider && slides.length > 0) {
        let currentIndex = 0;
        const totalSlides = slides.length;
        
        if (dotsContainer) {
            for (let i = 0; i < totalSlides; i++) {
                const dot = document.createElement('span');
                dot.classList.add('dot');
                if (i === 0) dot.classList.add('active');
                dot.addEventListener('click', () => goToSlide(i));
                dotsContainer.appendChild(dot);
            }
        }
        
        function updateSlider() {
            slider.style.transform = 'translateX(-' + (currentIndex * 100) + '%)';
            const allDots = document.querySelectorAll('.dot');
            for (let i = 0; i < allDots.length; i++) {
                if (i === currentIndex) {
                    allDots[i].classList.add('active');
                } else {
                    allDots[i].classList.remove('active');
                }
            }
        }
        
        function nextSlide() {
            currentIndex = (currentIndex + 1) % totalSlides;
            updateSlider();
        }
        
        function prevSlide() {
            currentIndex = (currentIndex - 1 + totalSlides) % totalSlides;
            updateSlider();
        }
        
        function goToSlide(index) {
            currentIndex = index;
            updateSlider();
        }
        
        if (prevBtn) prevBtn.addEventListener('click', prevSlide);
        if (nextBtn) nextBtn.addEventListener('click', nextSlide);
        
        setInterval(nextSlide, 3000);
    }
    
    const mobileMenuBtn = document.getElementById('mobileMenuBtn');
    const navMenu = document.getElementById('navMenu');
    
    if (mobileMenuBtn && navMenu) {
        mobileMenuBtn.addEventListener('click', function() {
            navMenu.classList.toggle('show');
        });
        
        document.addEventListener('click', function(event) {
            if (!mobileMenuBtn.contains(event.target) && !navMenu.contains(event.target)) {
                navMenu.classList.remove('show');
            }
        });
    }
    
    const regForm = document.getElementById('registerForm');
    if (regForm) {
        regForm.addEventListener('submit', function(e) {
            let valid = true;
            const login = document.getElementById('login');
            const pass = document.getElementById('password');
            const fullname = document.getElementById('fullname');
            const phone = document.getElementById('phone');
            const email = document.getElementById('email');
            
            const oldErrors = document.querySelectorAll('.error-msg');
            for (let i = 0; i < oldErrors.length; i++) {
                oldErrors[i].remove();
            }
            
            if (login && !/^[a-zA-Z0-9]{6,}$/.test(login.value)) {
                showError(login, 'Логин: латиница и цифры, не менее 6 символов');
                valid = false;
            }
            if (pass && pass.value.length < 8) {
                showError(pass, 'Пароль не менее 8 символов');
                valid = false;
            }
            if (fullname && !/^[А-Яа-яЁё\s]+$/.test(fullname.value)) {
                showError(fullname, 'ФИО только кириллица и пробелы');
                valid = false;
            }
            if (phone && !/^8\(\d{3}\)\d{3}-\d{2}-\d{2}$/.test(phone.value)) {
                showError(phone, 'Формат: 8(XXX)XXX-XX-XX');
                valid = false;
            }
            if (email && !/^[^\s@]+@([^\s@.,]+\.)+[^\s@.,]{2,}$/.test(email.value)) {
                showError(email, 'Введите корректный email');
                valid = false;
            }
            if (!valid) e.preventDefault();
        });
    }
    
    function showError(input, message) {
        const error = document.createElement('span');
        error.className = 'error-msg';
        error.textContent = message;
        input.parentNode.insertBefore(error, input.nextSibling);
    }
});