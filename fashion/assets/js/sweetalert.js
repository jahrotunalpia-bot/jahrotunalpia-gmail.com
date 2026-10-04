(function () {
    'use strict';

    window.swalSuccess = function (title, text) {
        return Swal.fire({
            icon: 'success',
            title: title || 'Berhasil!',
            text: text || '',
            confirmButtonText: 'OK',
            confirmButtonColor: '#c9a58f',
            backdrop: 'rgba(80, 55, 45, 0.35)'
        });
    };

    window.swalError = function (title, text) {
        return Swal.fire({
            icon: 'error',
            title: title || 'Terjadi Kesalahan',
            text: text || '',
            confirmButtonText: 'OK',
            confirmButtonColor: '#c9a58f',
            backdrop: 'rgba(80, 55, 45, 0.35)'
        });
    };

    window.swalConfirm = function (url, title, text) {
        return Swal.fire({
            icon: 'warning',
            title: title || 'Yakin?',
            text: text || 'Tindakan ini tidak dapat dibatalkan.',
            showCancelButton: true,
            confirmButtonText: 'Ya, lanjutkan',
            cancelButtonText: 'Batal',
            confirmButtonColor: '#c9a58f',
            cancelButtonColor: '#aaa',
            reverseButtons: true,
            backdrop: 'rgba(80, 55, 45, 0.35)'
        }).then(function (result) {
            if (result.isConfirmed) {
                window.location.href = url;
            }
        });
    };

    document.addEventListener('DOMContentLoaded', function () {
        // SweetAlert untuk pesan dari server melalui query string.
        var params = new URLSearchParams(window.location.search);
        var success = params.get('success');
        var error = params.get('error');

        var successMessages = {
            login_success: ['Login berhasil!', 'Selamat datang di StyleKita.'],
            product_added: ['Produk berhasil ditambahkan!', 'Data produk sudah tersimpan.'],
            product_updated: ['Produk berhasil diperbarui!', 'Perubahan produk sudah tersimpan.'],
            product_deleted: ['Produk berhasil dihapus!', 'Data produk sudah dihapus.'],
            transaction_deleted: ['Transaksi berhasil dihapus!', 'Data transaksi sudah dihapus.'],
            password_reset: ['Password berhasil direset!', 'Password pelanggan diubah menjadi 123456.'],
            cart_added: ['Berhasil ditambahkan!', 'Produk sudah masuk ke keranjang.'],
            cart_deleted: ['Produk dihapus!', 'Produk sudah dihapus dari keranjang.'],
            profile_updated: ['Profil berhasil disimpan!', 'Data profil kamu sudah diperbarui.'],
            checkout_success: ['Pesanan berhasil!', 'Pesanan kamu sedang diproses.'],
            registered: ['Pendaftaran berhasil!', 'Silakan login menggunakan akun yang baru dibuat.'],
            logged_out: ['Berhasil logout!', 'Sampai jumpa lagi.']
        };

        var errorMessages = {
            login_failed: ['Login gagal', 'Username/email atau password salah.']
        };

        if (success && successMessages[success]) {
            swalSuccess(successMessages[success][0], successMessages[success][1]);
            params.delete('success');
            history.replaceState(null, '', window.location.pathname + (params.toString() ? '?' + params.toString() : ''));
        } else if (error && errorMessages[error]) {
            swalError(errorMessages[error][0], errorMessages[error][1]);
            params.delete('error');
            history.replaceState(null, '', window.location.pathname + (params.toString() ? '?' + params.toString() : ''));
        }

        // SweetAlert konfirmasi untuk link berbahaya seperti hapus/reset.
        document.querySelectorAll('[data-swal-confirm]').forEach(function (el) {
            el.addEventListener('click', function (event) {
                event.preventDefault();
                swalConfirm(
                    el.getAttribute('href'),
                    el.getAttribute('data-swal-title'),
                    el.getAttribute('data-swal-text')
                );
            });
        });

        // Error server saat submit form biasa.
        if (window.__swalError) {
            swalError('Oops!', window.__swalError);
        }

        // Loading saat proses submit.
        document.querySelectorAll('form[data-swal-loading]').forEach(function (form) {
            form.addEventListener('submit', function () {
                Swal.fire({
                    title: form.getAttribute('data-swal-loading') || 'Memproses...',
                    text: 'Mohon tunggu sebentar.',
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    showConfirmButton: false,
                    backdrop: 'rgba(80, 55, 45, 0.35)',
                    didOpen: function () {
                        Swal.showLoading();
                    }
                });
            });
        });
    });
})();