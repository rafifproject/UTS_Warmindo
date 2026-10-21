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
    const total = parseFloat(totalBillElem.getAttribute('data-total') || 0);
    const received = parseFloat(cashInput.value || 0);
    const change = received - total;

    if (change >= 0) {
      changeDisplay.textContent = 'Rp ' + change.toLocaleString('id-ID');
      changeDisplay.classList.remove('text-danger');
      changeDisplay.classList.add('text-success');
    } else {
      changeDisplay.textContent = 'Rp 0 (Kurang Rp ' + Math.abs(change).toLocaleString('id-ID') + ')';
      changeDisplay.classList.remove('text-success');
      changeDisplay.classList.add('text-danger');
    }
  }

  if (cashInput) {
    cashInput.addEventListener('input', calculateKembalian);
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
