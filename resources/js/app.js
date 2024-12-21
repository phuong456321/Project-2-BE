import './bootstrap';
import '@fortawesome/fontawesome-free/css/all.css';
import './main.js';


 // Đợi DOM được tải
 document.addEventListener('DOMContentLoaded', () => {
  const toggleButton = document.getElementById('theme-toggle');
  const html = document.documentElement;

  // Kiểm tra trạng thái từ Local Storage và áp dụng theme
  const currentTheme = localStorage.getItem('theme');
  if (currentTheme === 'dark') {
    html.classList.add('dark');
    toggleButton.querySelector('.text').textContent = 'Light';
    toggleButton.querySelector('.fas.fa-sun').classList.remove('!hidden');
    toggleButton.querySelector('.fas.fa-moon').classList.add('!hidden');
  } else {
    html.classList.remove('dark');
    toggleButton.querySelector('.text').textContent = 'Dark';
    toggleButton.querySelector('.fas.fa-sun').classList.add('!hidden');
    toggleButton.querySelector('.fas.fa-moon').classList.remove('!hidden');
  }

  // Lắng nghe sự kiện click
  toggleButton.addEventListener('click', () => {
    const isDarkMode = html.classList.toggle('dark');
    toggleButton.querySelector('.text').textContent = isDarkMode ? 'Light' : 'Dark';
    toggleButton.querySelector('.fas.fa-sun').classList.toggle('!hidden', !isDarkMode);
    toggleButton.querySelector('.fas.fa-moon').classList.toggle('!hidden', isDarkMode);

    // Lưu trạng thái vào Local Storage
    localStorage.setItem('theme', isDarkMode ? 'dark' : 'light');
  });
});

  