
let currentPage = 1; // Trang hiện tại
let loading = false; // Trạng thái tải dữ liệu
// Hàm tải tác giả
function loadAuthors() {
        if (loading) return; // Nếu đang tải thì không làm gì thêm
        if (currentPage === 1) {
            $('#loading').text('Loading more authors...');
            $('#authors-list').empty(); // Xóa danh sách cũ nếu là trang đầu
            $('#loading').hide(); // Ẩn thông báo tải
        }
        loading = true;
        $('#loading').show(); // Hiển thị thông báo đang tải
        // Get selected area ID
        let areaId = $('#area').val();
        const searchQuery = $('#search').val(); // Lấy từ khóa tìm kiếm
        $.ajax({
            url: '/admin/get-authors',
            method: 'GET',
            headers: {
                'Content-Type': 'application/json; charset=utf-8',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            },
            data: {
                page: currentPage,
                area: areaId,
                search: searchQuery
            },
            success: function(data) {
                if (data.length === 0 && currentPage === 1) {
                    $('#loading').hide(); // Ẩn thông báo tải
                    $('#authors-list').append(
                        '<tr><td colspan="5" class="text-center py-4">No authors found</td></tr>');
                    loading = true;
                    $('#loading').hide(); // Ẩn thông báo tải
                    return;
                } else {
                    // Nếu không có tác giả nào trong dữ liệu trả về, dừng tải
                    if (data.length === 0) {
                        $('#loading').text('All authors have been loaded!'); // Hiển thị thông báo
                        loading = true;
                        return;
                    }
                }
                // Duyệt qua danh sách tác giả và hiển thị chúng
                data.forEach(author => {
                    $('#authors-list').append(`
                <tr class="border-b border-gray-700 text-center">
                    <td class="px-4 py-2 flex justify-center items-center">
                        <img src="/image/${author.img_id}" alt="Author Image" class="w-10 h-10 rounded-full" loading="lazy">
                    </td>
                    <td class="px-4 py-2 text-left">${author.author_name}</td>
                    <td class="px-4 py-2">${author.area.name}</td>
                    <td class="px-4 py-2">${author.songs_count}</td>
                    <td class="px-4 py-2">
                        <a href="#" class="text-blue-500 hover:underline">Edit</a> | 
                        <form action="/admin/user/${author.id}" method="DELETE" class="inline">
                            <button type="submit" class="text-red-500 hover:underline">Delete</button>
                        </form>
                    </td>
                </tr>
            `);
                });
                currentPage++; // Tăng trang khi đã tải xong
                loading = false;
                $('#loading').hide(); // Ẩn thông báo tải
            },
            error: function(xhr, status, error) {
                console.error('Error loading authors:', error);
                console.error('Response:', xhr.responseText); // In ra nội dung lỗi chi tiết từ server
                loading = false;
                $('#loading').hide(); // Ẩn thông báo tải
            }
        });
    }
    document.addEventListener('DOMContentLoaded', function() {
        $('#area').on('change', function() {
            currentPage = 1; // Reset to the first page
            loading = false;
            loadAuthors(); // Reload authors based on the selected area
        });
        // Khi nhập từ khóa tìm kiếm
        $('#search').on('input', function() {
            currentPage = 1; // Reset về trang đầu
            loadAuthors(); // Gọi lại danh sách dựa trên từ khóa tìm kiếm
        });
        const createAuthorBtn = document.getElementById('create-author-btn');
        const modal = document.getElementById('create-author-modal');
        const closeModal = document.getElementById('close-modal');
        const form = document.getElementById('create-author-form');

        // Mở popup
        createAuthorBtn.addEventListener('click', () => {
            modal.classList.remove('hidden');
        });

        // Đóng popup
        closeModal.addEventListener('click', () => {
            modal.classList.add('hidden');
        });

        // Xử lý form submit
        form.addEventListener('submit', (e) => {
            e.preventDefault();

            // Dữ liệu form
            const formData = new FormData(form);

            // Gửi dữ liệu qua AJAX
            $.ajax({
                url: '/admin/create-author',
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                },
                data: formData,
                processData: false,  // Ngừng xử lý dữ liệu dạng FormData
                contentType: false,
                success: function(data) {
                    if (data.success) {
                        flash('Author created successfully!', 'success');
                        modal.classList.add('hidden');
                        form.reset();
                        $('#area').val('');
                        $('#search').val('');
                        currentPage = 1; // Reset về trang đầu
                        loading = false;
                        loadAuthors(); // Tải lại danh sách
                    } else {
                        alert('Failed to create author. Please check your input.');
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error loading authors:', error);
                    console.error('Response Text:', xhr.responseText); // Hiển thị chi tiết nội dung response
                    loading = false;
                    $('#loading').hide(); // Ẩn thông báo tải
                }
            });
        });
    });
    // Khi người dùng cuộn xuống cuối trang
    $(document).ready(function() {
        $('.main-content').on('scroll', function() {
            if ($('.main-content').scrollTop() + $('.main-content').height() >= $('.main-content')[0].scrollHeight - 100) {
                loadAuthors(); // Tải thêm tác giả khi cuộn xuống gần cuối
            }
        });
    });

// Tải danh sách tác giả lần đầu
loadAuthors();
function flash(message, type = 'success') {
    // Lấy phần tử flash message
    const flashMessage = document.getElementById('flash-message');
    
    // Cập nhật nội dung và kiểu dáng dựa trên loại thông báo
    flashMessage.textContent = message;
    flashMessage.className = `fixed top-4 right-4 py-2 px-4 rounded-lg shadow-lg z-50 ${
        type === 'success' ? 'bg-green-500' : 'bg-red-500'
    } text-white`;
    
    // Hiển thị thông báo
    flashMessage.style.display = 'block';

    // Ẩn sau 3 giây
    setTimeout(() => {
        flashMessage.classList.add('fade-out');
        setTimeout(() => {
            flashMessage.style.display = 'none';
            flashMessage.classList.remove('fade-out');
        }, 500); // Chờ hiệu ứng kết thúc
    }, 3000);
    
}
