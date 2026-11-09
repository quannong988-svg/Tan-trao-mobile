document.addEventListener('click', function (e) {
  const btn = e.target.closest('.btn-add-cart');
  if (!btn) return;
  e.preventDefault();

  const productId = btn.dataset.id;
  const qtyInput = document.querySelector('#qty-' + productId) || document.querySelector('#quantity');
  const qty = qtyInput ? parseInt(qtyInput.value || 1) : 1;

  fetch(window.BASE_URL + 'cart_action.php', {
    method: 'POST',
    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
    body: `action=add&id=${encodeURIComponent(productId)}&qty=${encodeURIComponent(qty)}`
  })
    .then(res => res.json())
    .then(data => {
      if (data.success) {
        document.querySelectorAll('.cart-count').forEach(el => el.textContent = data.cartCount);
        btn.textContent = '✓ Đã thêm';
        setTimeout(() => { btn.textContent = '🛒 Thêm vào giỏ'; }, 1200);
      }
    })
    .catch(() => alert('Có lỗi xảy ra, vui lòng thử lại.'));
});
document.addEventListener('click', function (e) {
  if (e.target.matches('.qty-plus')) {
    const input = e.target.parentElement.querySelector('input');
    input.value = parseInt(input.value) + 1;
  }
  if (e.target.matches('.qty-minus')) {
    const input = e.target.parentElement.querySelector('input');
    input.value = Math.max(1, parseInt(input.value) - 1);
  }
});
