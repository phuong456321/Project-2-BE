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
    html.classList.add('dark'); // Thêm class "dark" nếu theme là dark
    toggleButton.textContent = 'Light'; // Cập nhật nút
  } else {
    html.classList.remove('dark'); // Xóa class "dark" nếu không phải dark
    toggleButton.textContent = 'Dark'; // Cập nhật nút
  }

  // Lắng nghe sự kiện click cho nút
  toggleButton.addEventListener('click', () => {
    const isDarkMode = html.classList.toggle('dark'); // Toggle class "dark"
    toggleButton.textContent = isDarkMode ? 'Light' : 'Dark'; // Cập nhật nội dung nút

    // Lưu trạng thái vào Local Storage
    localStorage.setItem('theme', isDarkMode ? 'dark' : 'light');
  });
});
  