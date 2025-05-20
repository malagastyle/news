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

// Инициализация при загрузке
$(document).ready(function() {
  initDistrictSlider();
  loadNews();
  initAuthSystem();
  
  if ($('body').hasClass('admin-panel-page')) {
    initAdminPanel();
  }
});

// Слайдер района
function initDistrictSlider() {
  const $slider = $('.district-slider');
  if (!$slider.length) return;

  const $slides = $slider.find('.slide');
  const $dots = $slider.find('.slider-dots');
  let currentIndex = 0;
  let interval;

  // Создаем точки навигации
  $slides.each((index, slide) => {
    $dots.append(`<span class="dot" data-index="${index}"></span>`);
  });

  const $allDots = $slider.find('.dot');

  // Показываем слайд
  function showSlide(index) {
    $slides.removeClass('active').eq(index).addClass('active');
    $allDots.removeClass('active').eq(index).addClass('active');
    currentIndex = index;
  }

  // Следующий слайд
  function nextSlide() {
    const newIndex = (currentIndex + 1) % $slides.length;
    showSlide(newIndex);
  }

  // Запуск автоматического переключения
  function startAutoSlide() {
    clearInterval(interval);
    interval = setInterval(nextSlide, 5000);
  }

  // Обработчики событий
  $slider.find('.next').click(nextSlide);
  
  $slider.find('.prev').click(() => {
    const newIndex = (currentIndex - 1 + $slides.length) % $slides.length;
    showSlide(newIndex);
  });

  $slider.on('mouseenter', () => clearInterval(interval));
  $slider.on('mouseleave', startAutoSlide);

  $dots.on('click', '.dot', function() {
    const index = $(this).data('index');
    showSlide(index);
  });

  // Инициализация
  showSlide(0);
  startAutoSlide();
}

$(document).ready(function() {
  initDistrictSlider();
});

function renderSlides(slides) {
  let html = '';
  slides.forEach((slide, index) => {
    html += `
    <div class="slide ${index === 0 ? 'active' : ''}">
      <img src="${slide.image_path}" alt="${slide.title}">
      <div class="slide-content">
        <h3>${slide.title}</h3>
        <p>${slide.description}</p>
      </div>
    </div>`;
  });
  
  $('.district-slider').html(html);
}

function startSlider() {
  const $slider = $('.district-slider');
  if ($slider.length === 0) return;

  let currentSlide = 0;
  const slides = $slider.find('.slide');
  const totalSlides = slides.length;
  
  function showSlide(index) {
    slides.removeClass('active').eq(index).addClass('active');
  }
  
  setInterval(() => {
    currentSlide = (currentSlide + 1) % totalSlides;
    showSlide(currentSlide);
  }, 5000);
}

/* ===== СИСТЕМА НОВОСТЕЙ ===== */
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
          localStorage.setItem('auth_token', response.token);
          window.location.href = 'profile.html';
        } else {
          $('#login-error').text(response.message).show();
        }
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
          alert('Регистрация успешна!');
          window.location.href = 'login.html';
        } else {
          $('#register-error').text(response.message).show();
        }
      }
    });
  });

  // Выход
  $('[data-logout]').click(function() {
    localStorage.removeItem('auth_token');
    window.location.href = 'index.html';
  });
}

/* ===== АДМИН-ПАНЕЛЬ ===== */
function initAdminPanel() {
  // Drag & Drop для изображений
  const $dropArea = $('.drop-area');
  
  $dropArea.on('dragover', function(e) {
    e.preventDefault();
    $(this).addClass('dragover');
  }).on('dragleave drop', function() {
    $(this).removeClass('dragover');
  }).on('drop', function(e) {
    e.preventDefault();
    const file = e.originalEvent.dataTransfer.files[0];
    handleImageUpload(file);
  });

  $('#news-image').change(function() {
    handleImageUpload(this.files[0]);
  });

  // Добавление новости
  $('#addNewsForm').submit(function(e) {
    e.preventDefault();
    const formData = {
      title: $('#news-title').val(),
      content: $('#news-content').val(),
      image: $('#news-image-preview').attr('src') || ''
    };
    
    $.ajax({
      url: CONFIG.API.ADD_NEWS,
      method: 'POST',
      contentType: 'application/json',
      data: JSON.stringify(formData),
      headers: {
        'Authorization': 'Bearer ' + localStorage.getItem('auth_token')
      },
      success: function(response) {
        if (response.success) {
          alert('Новость добавлена!');
          $('#addNewsForm')[0].reset();
          $('.image-preview').empty();
        }
      }
    });
  });
}

function handleImageUpload(file) {
  if (!file.type.match('image.*')) return;

  const reader = new FileReader();
  reader.onload = function(e) {
    $('.image-preview').html(`<img id="news-image-preview" src="${e.target.result}">`);
  };
  reader.readAsDataURL(file);
}