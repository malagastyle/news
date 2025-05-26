// Конфигурация
const CONFIG = {
  API: {
    NEWS: 'api/get_news.php',
    SLIDES: 'api/get_slides.php',
    LOGIN: 'api/login.php',
    REGISTER: 'api/register.php',
    ADD_NEWS: 'api/add_news.php'
  }
};

$(document).ready(function () {
  initDistrictSlider();
  loadNews();
  initAuthSystem();
  updateAuthUI(); // Обновляем UI при загрузке страницы
  if ($('body').hasClass('admin-panel-page')) {
    initAdminPanel();
  }
});

/* ===== СЛАЙДЕР ===== */
function initDistrictSlider() {
  const $slider = $('.district-slider');
  if (!$slider.length) return;
  let currentSlide = 0;
  const interval = setInterval(() => {
    currentSlide = (currentSlide + 1) % $slider.find('.slide').length;
    showSlide(currentSlide);
  }, 5000);

  function showSlide(index) {
    $slider.find('.slide').removeClass('active').eq(index).addClass('active');
  }

  $slider.find('.next').click(() => {
    currentSlide = (currentSlide + 1) % $slider.find('.slide').length;
    showSlide(currentSlide);
  });

  $slider.find('.prev').click(() => {
    currentSlide = (currentSlide - 1 + $slider.find('.slide').length) % $slider.find('.slide').length;
    showSlide(currentSlide);
  });
}

/* ===== НОВОСТИ ===== */
function loadNews() {
  $.get(CONFIG.API.NEWS, function(response) {
    if (response.success) {
      renderNews(response.data);
    }
  });
}

function renderNews(news) {
  let html = '';
  news.slice(0, 3).forEach(item => {
    html += `
    <article class="news-card">
      <div class="news-image">
        <img src="${item.image || 'images/default-news.jpg'}" alt="${item.title}">
      </div>
      <div class="news-content">
        <span class="news-date">${item.release_date}</span>
        <h3>${item.title}</h3>
        <p>${item.content.substring(0, 100)}...</p>
        <a href="#" class="read-more">Читать далее</a>
      </div>
    </article>`;
  });
  $('.news-grid').html(html);
}

/* ===== АВТОРИЗАЦИЯ ===== */
function initAuthSystem() {
  // Вход
  $('#loginForm').submit(function(e) {
    e.preventDefault();
    const formData = {
      username: $('#login-username').val(),
      password: $('#login-password').val()
    };

    $.ajax({
      url: CONFIG.API.LOGIN,
      method: 'POST',
      contentType: 'application/json',
      data: JSON.stringify(formData),
      success: function(response) {
        if (response.success) {
          localStorage.setItem('auth_token', 'authenticated');
          localStorage.setItem('user', JSON.stringify({
            username: formData.username,
            is_admin: response.is_admin
          }));
          
          updateAuthUI();
          
          // Редирект в зависимости от типа пользователя
          window.location.href = response.redirect || 'index.html';
        } else {
          $('#login-error').text(response.message).show();
        }
      },
      error: function() {
        $('#login-error').text('Ошибка сервера').show();
      }
    });
  });

  // Регистрация
  $('#registerForm').submit(function(e) {
    e.preventDefault();
    const formData = {
      username: $('#reg-username').val(),
      email: $('#reg-email').val(),
      password: $('#reg-password').val()
    };
    $.ajax({
      url: CONFIG.API.REGISTER,
      method: 'POST',
      contentType: 'application/json',
      data: JSON.stringify(formData),
      success: function(response) {
        if (response.success) {
          localStorage.setItem('auth_token', response.token);
          localStorage.setItem('user', JSON.stringify(response.user));
          updateAuthUI(); // Обновляем интерфейс
          window.location.href = 'index.html'; // Перенаправление на главную
        } else {
          $('#register-error').text(response.message).show();
        }
      },
      error: function() {
        $('#register-error').text('Ошибка регистрации').show();
      }
    });
  });

  // Выход
  $('[data-logout]').click(function() {
    localStorage.removeItem('auth_token');
    localStorage.removeItem('user');
    updateAuthUI();
    window.location.href = 'index.html';
  });
}

// === Функция обновления кнопок авторизации ===
function updateAuthUI() {
  const token = localStorage.getItem('auth_token');
  const userJSON = localStorage.getItem('user');
  const loginLink = $('#auth-login');
  const profileLink = $('#auth-profile');
  const adminLink = $('#auth-admin');

  if (token && userJSON) {
    const user = JSON.parse(userJSON);
    loginLink.hide();
    profileLink.show().text(user.username);
    if (parseInt(user.is_admin) === 1) {
      adminLink.show();
    } else {
      adminLink.hide();
    }
  } else {
    loginLink.show();
    profileLink.hide();
    adminLink.hide();
  }
}