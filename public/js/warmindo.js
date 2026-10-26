/**
 * Warmindo Master Client Script
 * Handles Cart, Kasir Calculations, Modals, Service selection, and Interactive Charts
 */

document.addEventListener('DOMContentLoaded', function () {
  // 1. Service Selection (Dine In vs Take Away) - Screen 54
  const serviceCards = document.querySelectorAll('.service-card');
  const tableInputGroup = document.getElementById('tableNumberGroup');
  const tableInput = document.getElementById('table_number');
  const orderTypeInput = document.getElementById('order_type');

  if (serviceCards.length > 0) {
    serviceCards.forEach(card => {
      card.addEventListener('click', function () {
        serviceCards.forEach(c => c.classList.remove('selected'));
        this.classList.add('selected');
        const type = this.getAttribute('data-service');
        if (orderTypeInput) orderTypeInput.value = type;

        if (type === 'dine_in') {
          if (tableInputGroup) tableInputGroup.style.display = 'block';
          if (tableInput) tableInput.setAttribute('required', 'required');
        } else {
          if (tableInputGroup) tableInputGroup.style.display = 'none';
          if (tableInput) {
            tableInput.removeAttribute('required');
            tableInput.value = '';
          }
        }
      });
    });
  }

  // 2. Kasir Quick Cash & Dynamic Kembalian - Screen 46
  const cashInput = document.getElementById('cash_received');
  const totalBillElem = document.getElementById('total_bill_amount');
  const changeDisplay = document.getElementById('cash_change_display');
  const shortcutButtons = document.querySelectorAll('.btn-cash-shortcut');
  const paymentForm = document.getElementById('kasirPaymentForm');

  function calculateKembalian() {
    if (!cashInput || !totalBillElem || !changeDisplay) return;
    const rawVal = cashInput.value !== undefined && cashInput.value !== null ? cashInput.value.toString().trim() : '';
    const container = changeDisplay.parentElement;

    if (rawVal === '') {
      changeDisplay.textContent = 'Rp 0';
      changeDisplay.style.color = '#ADB5BD';
      if (container) {
        container.style.backgroundColor = '#F8F9FA';
        container.style.borderColor = 'var(--border-light)';
      }
      return;
    }

    const total = parseFloat(totalBillElem.getAttribute('data-total') || 0);
    const received = parseFloat(rawVal);
    const change = received - total;

    if (change >= 0) {
      changeDisplay.textContent = 'Rp ' + change.toLocaleString('id-ID');
      changeDisplay.style.color = '#0CA678';
      if (container) {
        container.style.backgroundColor = '#F4FBF7';
        container.style.borderColor = '#C3FAE8';
      }
    } else {
      changeDisplay.textContent = 'Rp 0 (Kurang Rp ' + Math.abs(change).toLocaleString('id-ID') + ')';
      changeDisplay.style.color = '#FA5252';
      if (container) {
        container.style.backgroundColor = '#FFF5F5';
        container.style.borderColor = '#FFC9C9';
      }
    }
  }

  if (cashInput) {
    cashInput.addEventListener('input', calculateKembalian);
    // Initialize state on page load
    calculateKembalian();
  }

  if (shortcutButtons.length > 0) {
    shortcutButtons.forEach(btn => {
      btn.addEventListener('click', function () {
        const val = this.getAttribute('data-amount');
        if (cashInput) {
          cashInput.value = val;
          calculateKembalian();
        }
      });
    });
  }

  // Form Payment Validation for Insufficient Cash (Screen 47)
  if (paymentForm) {
    paymentForm.addEventListener('submit', function (e) {
      const selectedMethod = document.getElementById('selected_payment_method');
      if (selectedMethod && selectedMethod.value === 'qris') {
        // QRIS is exact non-cash payment, bypass cash validation
        return true;
      }

      const total = parseFloat(totalBillElem.getAttribute('data-total') || 0);
      const received = parseFloat(cashInput ? cashInput.value : 0);
      if (received < total) {
        e.preventDefault();
        const shortModal = document.getElementById('modalUangKurang');
        const kekuranganVal = document.getElementById('kekuranganAmount');
        if (kekuranganVal) {
          kekuranganVal.textContent = 'Rp ' + (total - received).toLocaleString('id-ID');
        }
        if (shortModal) {
          shortModal.classList.add('active');
        } else {
          alert('Jumlah uang yang dimasukkan kurang dari total tagihan!');
        }
      }
    });
  }

  // Kasir Payment Method Switch (Tunai vs QRIS)
  const btnMethodCash = document.getElementById('btnMethodCash');
  const btnMethodQris = document.getElementById('btnMethodQris');
  const cashSection = document.getElementById('cashPaymentSection');
  const qrisSection = document.getElementById('qrisPaymentSection');
  const methodInput = document.getElementById('selected_payment_method');
  const submitPaymentBtn = document.getElementById('btnSubmitPayment');
  const helperPaymentText = document.getElementById('helperPaymentText');

  if (btnMethodCash && btnMethodQris && methodInput) {
    btnMethodCash.addEventListener('click', function () {
      methodInput.value = 'cash';
      btnMethodCash.style.borderColor = 'var(--primary)';
      btnMethodCash.style.backgroundColor = '#FFF4EC';
      btnMethodCash.style.color = 'var(--primary)';
      btnMethodQris.style.borderColor = 'var(--border-light)';
      btnMethodQris.style.backgroundColor = '#FFFFFF';
      btnMethodQris.style.color = 'var(--text-muted)';

      if (cashSection) cashSection.style.display = 'block';
      if (qrisSection) qrisSection.style.display = 'none';
      if (cashInput) {
        cashInput.setAttribute('required', 'required');
        cashInput.value = '';
        calculateKembalian();
        cashInput.focus();
      }
      if (submitPaymentBtn) {
        submitPaymentBtn.textContent = 'Konfirmasi Pembayaran Tunai';
        submitPaymentBtn.style.backgroundColor = '';
      }
      if (helperPaymentText) {
        helperPaymentText.textContent = 'Pastikan nominal uang sesuai dengan total tagihan. Sistem akan menghitung kembalian secara otomatis dan meneruskan pesanan ke antrean dapur.';
      }
    });

    btnMethodQris.addEventListener('click', function () {
      methodInput.value = 'qris';
      btnMethodQris.style.borderColor = '#0CA678';
      btnMethodQris.style.backgroundColor = '#E6FCF5';
      btnMethodQris.style.color = '#0CA678';
      btnMethodCash.style.borderColor = 'var(--border-light)';
      btnMethodCash.style.backgroundColor = '#FFFFFF';
      btnMethodCash.style.color = 'var(--text-muted)';

      if (cashSection) cashSection.style.display = 'none';
      if (qrisSection) qrisSection.style.display = 'block';
      if (cashInput) {
        cashInput.removeAttribute('required');
        if (totalBillElem) {
          cashInput.value = totalBillElem.getAttribute('data-total') || 0;
        }
      }
      if (submitPaymentBtn) {
        submitPaymentBtn.textContent = 'Konfirmasi Pembayaran QRIS';
        submitPaymentBtn.style.backgroundColor = '#0CA678';
      }
      if (helperPaymentText) {
        helperPaymentText.textContent = 'Pelanggan memindai QR Code QRIS di atas. Setelah notifikasi dana masuk di rekening Warmindo terkonfirmasi, klik tombol konfirmasi untuk meneruskan ke antrean dapur.';
      }
    });
  }

  // 3. Modal open / close handlers
  document.querySelectorAll('[data-close-modal]').forEach(btn => {
    btn.addEventListener('click', function () {
      const modal = this.closest('.modal-backdrop');
      if (modal) modal.classList.remove('active');
    });
  });

  document.querySelectorAll('[data-open-modal]').forEach(btn => {
    btn.addEventListener('click', function (e) {
      e.preventDefault();
      const targetId = this.getAttribute('data-open-modal');
      const target = document.getElementById(targetId);
      if (target) target.classList.add('active');
    });
  });

  // 4. Report View Toggle: Chart vs Raw Data (Screen H)
  const reportChartBtn = document.getElementById('btnShowChart');
  const reportTableBtn = document.getElementById('btnShowTable');
  const chartSection = document.getElementById('reportChartSection');
  const tableSection = document.getElementById('reportTableSection');

  if (reportChartBtn && reportTableBtn && chartSection && tableSection) {
    reportChartBtn.addEventListener('click', function () {
      reportChartBtn.classList.add('active');
      reportTableBtn.classList.remove('active');
      chartSection.style.display = 'block';
      tableSection.style.display = 'none';
    });

    reportTableBtn.addEventListener('click', function () {
      reportTableBtn.classList.add('active');
      reportChartBtn.classList.remove('active');
      chartSection.style.display = 'none';
      tableSection.style.display = 'block';
    });
  }
});
