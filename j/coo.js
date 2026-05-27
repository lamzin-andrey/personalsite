(function() {
    // Проверяем, было ли уже принято соглашение
    if (localStorage.getItem('cookieConsentAccepted') === 'true') {
        return;
    }

    // Создаём HTML-структуру
    const consentHTML = `
        <div id="cookie-consent-banner" style="
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            background: #c9d9ff;
            border-top: 2px solid #e0e0e0;
            box-shadow: 0 -4px 20px rgba(0, 0, 0, 0.15);
            padding: 20px;
            z-index: 9999;
            font-family: Arial, sans-serif;
            font-size: 14px;
            line-height: 1.5;
            color: #333;
            max-height: 40vh;
            overflow-y: auto;
            transition: transform 0.3s ease-in-out;
        ">
            <div style="
                max-width: 1200px;
                margin: 0 auto;
            ">
                <p style="
                    margin: 0 0 15px 0;
                    color: #555;
                    font-size: 13px;
                ">
                    Сайт использует файлы cookie и аналогичные методы для распознавания посетителей и запоминания предпочтений.
                    Сайт также может использовать их в будущем для измерения эффективности кампании и анализа трафика сайта.
                    Выбирая «Принять», вы соглашаетесь на использование этих методов нами и доверенными третьими лицами.
                </p>
                
                <div style="margin-bottom: 15px;">
                    <label style="
                        display: flex;
                        align-items: center;
                        margin-bottom: 10px;
                        cursor: pointer;
                        font-size: 14px;
                    ">
                        <input type="checkbox" class="consent-checkbox" style="
                            margin-right: 10px;
                            width: 18px;
                            height: 18px;
                            cursor: pointer;
                        ">
                        <span>Я ознакомлен с <a target="_blank" href="/files/Politika_zashity_i_obrabotki_personalnyh_dannyh_2026-03-19.doc">Политикой защиты и обработки персональных данных</a></span>
                    </label>
                    
                    <label style="
                        display: flex;
                        align-items: center;
                        margin-bottom: 10px;
                        cursor: pointer;
                        font-size: 14px;
                    ">
                        <input type="checkbox" class="consent-checkbox" style="
                            margin-right: 10px;
                            width: 18px;
                            height: 18px;
                            cursor: pointer;
                        ">
                        <span>Я согласен с <a target="_blank" href="/files/Polzovatelskoe_soglashenie_2024-12-12.docx">Пользовательским соглашением</a></span>
                    </label>
                    
                    <label style="
                        display: flex;
                        align-items: center;
                        margin-bottom: 10px;
                        cursor: pointer;
                        font-size: 14px;
                    ">
                        <input type="checkbox" class="consent-checkbox" style="
                            margin-right: 10px;
                            width: 18px;
                            height: 18px;
                            cursor: pointer;
                        ">
                        <span>Я даю <a target="_blank" href="/files/Soglasie_na_obrabotku_personalnyh_dannyh.docx">согласие на обработку персональных данных</a></span>
                    </label>
                </div>

                <button id="consent-accept-btn" style="
                    background: #4CAF50;
                    color: white;
                    border: none;
                    padding: 12px 30px;
                    font-size: 16px;
                    border-radius: 4px;
                    cursor: pointer;
                    transition: background 0.3s;
                    font-weight: bold;
                ">Принять</button>
            </div>
        </div>
    `;

    // Добавляем блок на страницу
    document.body.insertAdjacentHTML('beforeend', consentHTML);

    // Получаем элементы
    const banner = document.getElementById('cookie-consent-banner');
    const acceptBtn = document.getElementById('consent-accept-btn');
    const checkboxes = document.querySelectorAll('.consent-checkbox');

    // Обработчик клика по кнопке
    acceptBtn.addEventListener('click', function() {
        // Проверяем, все ли чекбоксы отмечены
        const allChecked = Array.from(checkboxes).every(checkbox => checkbox.checked);
        
        if (!allChecked) {
            alert('Надо согласиться с условиями');
            return;
        }

        // Сохраняем факт принятия соглашения
        localStorage.setItem('cookieConsentAccepted', 'true');
        
        // Скрываем баннер с анимацией
        banner.style.transform = 'translateY(100%)';
        
        // Удаляем элемент после анимации
        setTimeout(() => {
            banner.remove();
        }, 300);
    });

    // Добавляем hover-эффект для кнопки
    acceptBtn.addEventListener('mouseenter', function() {
        this.style.background = '#45a049';
    });
    
    acceptBtn.addEventListener('mouseleave', function() {
        this.style.background = '#4CAF50';
    });
})();
