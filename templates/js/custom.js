

// hiện thị  ảnh
document.getElementById("file").addEventListener("change", function (event) {
    const file = event.target.files[0]; // Lấy file đầu tiên từ input
    const preview = document.getElementById("preview");

    if (file) {
        const reader = new FileReader(); // Tạo đối tượng FileReader

        reader.onload = function (e) {
            preview.src = e.target.result; // Gán đường dẫn của ảnh vào src
            preview.style.display = "block"; // Hiển thị ảnh
        };

        reader.readAsDataURL(file); // Đọc file dưới dạng Data URL
    } else {
        preview.style.display = "none"; // Ẩn ảnh nếu không chọn file
    }
});

//hiển thị bảng màu
const colorInput = document.getElementById('colorInput');
const colorPreview = document.getElementById('colorPreview');

colorInput.addEventListener('input', () => {
    const colorValue = colorInput.value;
    colorPreview.style.backgroundColor = colorValue;
    colorPreview.textContent = colorValue;
});

// tăng giảm số lượng
function changeQuantity(amount) {
    const quantityInput = document.getElementById('quantity');
    let currentValue = parseInt(quantityInput.value) || 1;
    quantityInput.value = Math.max(currentValue + amount, 1); // Không cho phép giá trị nhỏ hơn 1
}


