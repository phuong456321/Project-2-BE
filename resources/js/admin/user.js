
let currentPage = 1; // Trang hiện tại
let loading = false; // Trạng thái tải dữ liệu
// Hàm tải tác giả
function loadUsers() {
    if (loading) return; // Nếu đang tải thì không làm gì thêm
    if (currentPage === 1) {
        $('#loading').text('Loading more users...');
        $('#users-list').empty(); // Xóa danh sách cũ nếu là trang đầu
        $('#loading').hide(); // Ẩn thông báo tải
    }
    loading = true;
    $('#loading').show(); // Hiển thị thông báo đang tải
    // Get selected status
    let status = $('#status').val();
    const searchQuery = $('#search').val(); // Lấy từ khóa tìm kiếm
    $.ajax({
        url: '/admin/get-users',
        method: 'GET',
        headers: {
            'Content-Type': 'application/json; charset=utf-8',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
        },
        data: {
            page: currentPage,
            status: status,
            search: searchQuery
        },
        success: function (data) {
            if (data.length === 0 && currentPage === 1) {
                $('#loading').hide(); // Ẩn thông báo tải
                $('#users-list').append(
                    '<tr><td colspan="9" class="text-center py-4">No users found</td></tr>');
                loading = true;
                $('#loading').hide(); // Ẩn thông báo tải
                return;
            } else {
                // Nếu không có tác giả nào trong dữ liệu trả về, dừng tải
                if (data.length === 0) {
                    $('#loading').text('All users have been loaded!'); // Hiển thị thông báo
                    loading = true;
                    return;
                }
            }
            // Duyệt qua danh sách tác giả và hiển thị chúng
            data.forEach(user => {
                $('#users-list').append(`
                <tr id="user-row-{{ $user->id }}" class="border-b border-gray-700 text-center ${user.status == 'inactive'? 'bg-red-400' : 'bg-gray-800'}">
                    <td class="px-4 py-2 flex justify-center items-center"><img src="/image/${user.avatar_id}" alt="User Image" class="w-10 h-10 rounded-full" loading="lazy"></td>
                    <td class="px-4 py-2">${user.name}</td>
                    <td class="px-4 py-2">${user.email}</td>
                    <td class="px-4 py-2">${user.plan.charAt(0).toUpperCase() + user.plan.slice(1)}</td>
                    <td class="px-4 py-2">${user.status.charAt(0).toUpperCase() + user.status.slice(1)}</td>
                    <td class="px-4 py-2">${user.email_verified_at ? 'Verified' : 'Unverified'}</td>
                    <td class="px-4 py-2">${user.role.charAt(0).toUpperCase() + user.role.slice(1)}</td>
                    <td class="px-4 py-2">${user.restricted_tracks_count}</td>
                    <td class="px-4 py-2">
                        ${user.status === 'active' ? `
                        <button 
                            data-user-id="${user.id}" 
                            class="ban-user-btn text-white hover:underline font-bold bg-red-600 rounded p-1" data-action="ban"
                        >
                            Block User
                        </button>
                        ` : `
                        <button 
                            data-user-id="${user.id}" 
                            class="unban-user-btn text-white hover:underline font-bold bg-green-600 rounded p-1" data-action="unban"
                        >
                            Unblock User
                        </button>
                        `}
                    </td>
                </tr>
            `);
            });
            currentPage++; // Tăng trang khi đã tải xong
            loading = false;
            $('#loading').hide(); // Ẩn thông báo tải
        },
        error: function (xhr, status, error) {
            console.error('Error loading authors:', error);
            console.error('Response:', xhr.responseText); // In ra nội dung lỗi chi tiết từ server
            loading = false;
            $('#loading').hide(); // Ẩn thông báo tải
        }
    });
}
document.addEventListener('DOMContentLoaded', function () {
    $('#status').on('change', function () {
        currentPage = 1; // Reset to the first page
        loading = false;
        loadUsers(); // Reload users based on the selected status
    });
    // Khi nhập từ khóa tìm kiếm
    $('#search').on('input', function () {
        currentPage = 1; // Reset về trang đầu
        loadUsers(); // Gọi lại danh sách dựa trên từ khóa tìm kiếm
    });

    // Lấy modal và các nút trong modal
    const modal = document.getElementById('confirmation-modal');
    const confirmButton = document.getElementById('confirm-ban');
    const cancelButton = document.getElementById('cancel-ban');
    const modalTitle = document.getElementById('modal-title');
    const modalMessage = document.getElementById('modal-message');

    // Biến toàn cục để lưu ID người dùng và hành động
    let userIdToAction = null;
    let actionToTake = null; // "ban" hoặc "unban"

    // Hàm mở modal và lưu ID người dùng và hành động
    function openModal(userId, action) {
        userIdToAction = userId;
        actionToTake = action;

        // Thay đổi nội dung modal tùy thuộc vào hành động
        if (actionToTake === "ban") {
            modalTitle.innerText = "Block User Confirmation";
            modalMessage.innerText = "Are you sure you want to block this user?";
            confirmButton.innerText = "Block User";
            confirmButton.classList.add("bg-red-600");
        } else if (actionToTake === "unban") {
            modalTitle.innerText = "Unblock User Confirmation";
            modalMessage.innerText = "Are you sure you want to unblock this user?";
            confirmButton.innerText = "Unblock User";
            confirmButton.classList.remove("bg-red-600");
            confirmButton.classList.add("bg-green-600");
        }

        modal.classList.remove('hidden');
    }

    // Hàm đóng modal
    function closeModal() {
        modal.classList.add('hidden');
    }

    // Khi nhấn "Yes, Ban" hoặc "Yes, Unban" -> thực hiện hành động ban hoặc unban
    confirmButton.addEventListener('click', function () {
        if (actionToTake === "ban") {
            // Thực hiện hành động ban user với userIdToAction
            fetch(`/admin/users/${userIdToAction}/ban`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
                .then(response => {
                    if (response.ok) {
                        flash('User has been banned!', 'success');
                    } else {
                        flash('Failed to ban user.', 'error');
                    }
                    currentPage = 1;
                    loadUsers();
                })
                .catch(error => {
                    console.error('Error:', error);
                    flash('An error occurred.', 'error');
                });
        } else if (actionToTake === "unban") {
            // Thực hiện hành động unban user với userIdToAction
            fetch(`/admin/users/${userIdToAction}/unban`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
                .then(response => {
                    if (response.ok) {
                        flash('User has been unbanned!', 'success');
                    } else {
                        flash('Failed to unban user.', 'error');
                    }
                    currentPage = 1;
                    loadUsers();
                })
                .catch(error => {
                    console.error('Error:', error);
                    flash('An error occurred.', 'error');
                });
        }

        // Đóng modal sau khi ban/unban user
        closeModal();
    });

    // Khi nhấn "Cancel" -> đóng modal mà không làm gì
    cancelButton.addEventListener('click', function () {
        closeModal();
    });
        // Gắn sự kiện vào phần tử cha (#users-list)
        const usersList = document.getElementById('users-list');

        // Lắng nghe sự kiện click trên tất cả các nút .ban-user-btn trong #users-list
        usersList.addEventListener('click', function (e) {
            if (e.target && e.target.classList.contains('ban-user-btn')) {
                const userId = e.target.getAttribute('data-user-id');
                openModal(userId, "ban"); // Mở modal và truyền hành động "ban"
            } else if (e.target && e.target.classList.contains('unban-user-btn')) {
                const userId = e.target.getAttribute('data-user-id');
                openModal(userId, "unban"); // Mở modal và truyền hành động "unban"
        }
    });

});
// Khi người dùng cuộn xuống cuối trang
$(document).ready(function () {
    $('.main-content').on('scroll', function () {
        if ($('.main-content').scrollTop() + $('.main-content').height() >= $('.main-content')[0].scrollHeight - 100) {
            loadUsers(); // Tải thêm tác giả khi cuộn xuống gần cuối
        }
    });
});

// Tải danh sách user lần đầu
loadUsers();
function flash(message, type = 'success') {
    // Lấy phần tử flash message
    const flashMessage = document.getElementById('flash-message');

    // Cập nhật nội dung và kiểu dáng dựa trên loại thông báo
    flashMessage.textContent = message;
    flashMessage.className = `fixed top-4 right-4 py-2 px-4 rounded-lg shadow-lg z-50 ${type === 'success' ? 'bg-green-500' : 'bg-red-500'
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
