document.addEventListener('DOMContentLoaded', function() {
    // Bật/tắt menu trên giao diện điện thoại
    const menuToggle = document.querySelector('.menu-toggle');
    const mainNav = document.querySelector('.main-nav');

    if (menuToggle && mainNav) {
        menuToggle.addEventListener('click', function() {
            // Khi nhấn vào nút menu, ẩn hoặc hiện menu chính
            mainNav.style.display = mainNav.style.display === 'block' ? 'none' : 'block';
        });
    }

    // Xử lý thay đổi số lượng sản phẩm trong giỏ hàng
    const quantityInputs = document.querySelectorAll('.quantity-input');

    quantityInputs.forEach(input => {
        input.addEventListener('change', function() {
            // Đảm bảo số lượng tối thiểu là 1
            if (this.value < 1) {
                this.value = 1;
            }
            // Cập nhật giỏ hàng (tuỳ thuộc vào hệ thống của bạn)
            updateCartQuantity(input);
        });
    });

    // Xử lý hiển thị ảnh sản phẩm trong trang chi tiết
    const mainImage = document.getElementById('mainProductImage');
    const thumbnailImages = document.querySelectorAll('.thumbnail-images img');

    if (mainImage && thumbnailImages.length > 0) {
        thumbnailImages.forEach(thumbnail => {
            thumbnail.addEventListener('click', function() {
                // Bỏ class active khỏi tất cả các ảnh nhỏ
                thumbnailImages.forEach(t => t.classList.remove('active'));

                // Gắn class active cho ảnh được chọn
                this.classList.add('active');

                // Đổi ảnh chính theo ảnh được click
                mainImage.src = this.src;
            });
        });
    }

    // Cuộn mượt đến phần tử có ID (khi nhấn vào các liên kết nội bộ)
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
            const target = document.querySelector(this.getAttribute('href'));

            if (target) {
                e.preventDefault(); // Ngăn trình duyệt chuyển đến ngay lập tức

                window.scrollTo({
                    top: target.offsetTop - 100, // Trừ đi chiều cao của header (nếu có)
                    behavior: 'smooth' // Cuộn mượt
                });
            }
        });
    });
});

// Hàm cập nhật số lượng sản phẩm trong giỏ hàng (mô phỏng)
function updateCartQuantity(input) {
    const productId = input.dataset.productId; // Giả sử có thuộc tính data-product-id
    const newQuantity = input.value;

    // Ghi log để kiểm tra (hoặc xử lý theo logic thực tế)
    console.log(`Cập nhật giỏ hàng: Mã sản phẩm: ${productId}, Số lượng mới: ${newQuantity}`);

    // Ở đây có thể thêm xử lý lưu vào localStorage, hoặc gửi lên server
}