$(document).on('click', '.btn-open-invoice-register', function () {
        // 1. Bersihkan pilihan select terdahulu
        $('#select_invoice_class').html('<option value="">-- Pilih Kelas --</option>');

        // 2. Ambil data classes yang di-encode dari komponen tombol
        let rawClasses = $(this).attr('data-classes');
        let classesArray = [];

        try {
            // Decode kembali data JSON kelas balap
            classesArray = JSON.parse(decodeURIComponent(rawClasses));
        } catch (e) {
            classesArray = [];
        }

        // 3. Masukkan daftar kelas ke dalam elemen <select> di dalam modal
        if (classesArray && classesArray.length > 0) {
            classesArray.forEach(function (item) {
                // value diisi menggunakan ID utama dari RegistrationClass ('id')
                $('#select_invoice_class').append(`
                    <option value="${item.id}">${item.class_name}</option>
                `);
            });
        } else {
            $('#select_invoice_class').append('<option value="" disabled>Tidak ada kelas ditemukan</option>');
        }
    });

    // 4. Aksi saat tombol "Cetak PDF" di dalam modal diklik
    $('#form-print-invoice').on('submit', function (e) {
        e.preventDefault();

        // Ambil ID RegistrationClass yang dipilih dari dropdown
        let registrationClassId = $('#select_invoice_class').val();

        if (!registrationClassId) {
            alert('Silakan pilih kelas terlebih dahulu!');
            return;
        }

        // Bangun URL mengarah ke Route generatePdf Anda dengan parameter type
        let printUrl = `/registration/${registrationClassId}/pdf?type=kwitansi`;

        // Buka PDF di tab baru tanpa menutup halaman utama aplikasi
        window.open(printUrl, '_blank');

        // Tutup modal secara otomatis setelah mencetak
        $('#modal_invoice_race').modal('hide');
    });
