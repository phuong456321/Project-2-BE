import './bootstrap';
import '@fortawesome/fontawesome-free/css/all.css';
import './main.js';


document.addEventListener('DOMContentLoaded', () => {
    const toggleButton = document.getElementById('theme-toggle');
    const html = document.documentElement;
  
    // Kiểm tra theme hiện tại từ localStorage
    const currentTheme = localStorage.getItem('theme');
    if (currentTheme === 'dark') {
      html.classList.add('dark');
      toggleButton.textContent = ' Light';
    }
  
    // Sự kiện chuyển đổi theme
    toggleButton.addEventListener('click', () => {
      const isDarkMode = html.classList.toggle('dark');
      toggleButton.textContent = isDarkMode ? ' Light ' : ' Dark ';
  
      // Lưu theme vào localStorage
      localStorage.setItem('theme', isDarkMode ? 'dark' : 'light');
    });
  });
  