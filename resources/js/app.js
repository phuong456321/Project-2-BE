import './bootstrap';
import '@fortawesome/fontawesome-free/css/all.css';

// public/js/app.js

$(document).ready(function () {
    // Khi nhấn vào nút "Albums"
    $('#albums').click(function (e) {
        e.preventDefault();
        $('#main-content').html('<p>Loading...</p>'); // Hiển thị thông báo loading
        $.get('/albums', function (data) {
            $('#main-content').html(data); // Đưa nội dung album vào phần content
        });
    });

    // Khi nhấn vào nút "Profile"
    $('#profile').click(function (e) {
        e.preventDefault();
        $('#main-content').html('<p>Loading...</p>');
        $.get('/profile', function (data) {
            $('#main-content').html(data); // Đưa nội dung profile vào phần content
        });
    });

    // Khi nhấn vào nút "Home"
    $('#home').click(function (e) {
        e.preventDefault();
        $('#main-content').html('<h1>Welcome to the Home Page</h1>');
    });
});
