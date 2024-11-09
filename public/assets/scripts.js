// Menyimpan data gaji barang di localStorage
function saveData() {
    const tanggal = document.getElementById('tanggal').value;
    const barangMasuk = parseInt(document.getElementById('barangmasuk').value) || 0;
    const barangKeluar = parseInt(document.getElementById('barangkeluar').value) || 0;
    const sisaBahan = parseInt(document.getElementById('sisabahan').value) || 0;
    const totalDikerjakan = parseInt(document.getElementById('totaldikerjakan').value) || 0;

    const gaji = totalDikerjakan * 50000; // Misalkan Rp.50.000 per pengerjaan

    const data = {
        tanggal,
        barangMasuk,
        barangKeluar,
        sisaBahan,
        totalDikerjakan,
        gaji
    };

    let dataKaryawan = JSON.parse(localStorage.getItem('dataKaryawan')) || [];
    dataKaryawan.push(data);
    localStorage.setItem('dataKaryawan', JSON.stringify(dataKaryawan));

    alert('Data berhasil disimpan!');
    window.location.href = 'gajibarang.html'; // Kembali ke halaman gaji barang
}

// Menghapus data berdasarkan tanggal
function deleteData(tanggal) {
    let dataKaryawan = JSON.parse(localStorage.getItem('dataKaryawan')) || [];
    dataKaryawan = dataKaryawan.filter(item => item.tanggal !== tanggal);
    localStorage.setItem('dataKaryawan', JSON.stringify(dataKaryawan));
    alert('Data berhasil dihapus!');
    renderData(); // Refresh data di tabel
}

// Menampilkan data dari localStorage ke tabel
function renderData() {
    const dataKaryawan = JSON.parse(localStorage.getItem('dataKaryawan')) || [];
    const tbody = document.querySelector('tbody');
    tbody.innerHTML = '';

    // Tampilkan semua data
    dataKaryawan.forEach((item) => {
        const row = `
            <tr>
                <td>${item.tanggal}</td>
                <td>${item.barangMasuk}</td>
                <td>${item.barangKeluar}</td>
                <td>${item.sisaBahan}</td>
                <td>${item.totalDikerjakan}</td>
                <td class="action-icons">
                    <a href="editdatabarang.html?tanggal=${item.tanggal}"><i class="fas fa-edit"></i></a>
                    <a href="#" onclick="deleteData('${item.tanggal}')"><i class="fas fa-trash-alt"></i></a>
                </td>
            </tr>
        `;
        tbody.innerHTML += row;
    });
}

// Mengisi form edit
function fillEditForm() {
    const params = new URLSearchParams(window.location.search);
    const tanggal = params.get('tanggal');
    const dataKaryawan = JSON.parse(localStorage.getItem('dataKaryawan')) || [];
    const item = dataKaryawan.find(data => data.tanggal === tanggal);

    if (item) {
        document.getElementById('tanggal').value = item.tanggal;
        document.getElementById('barangmasuk').value = item.barangMasuk;
        document.getElementById('barangkeluar').value = item.barangKeluar;
        document.getElementById('sisabahan').value = item.sisaBahan;
        document.getElementById('totaldikerjakan').value = item.totalDikerjakan;
    }
}

// Memperbarui data
function updateData() {
    const tanggal = document.getElementById('tanggal').value;
    const barangMasuk = parseInt(document.getElementById('barangmasuk').value) || 0;
    const barangKeluar = parseInt(document.getElementById('barangkeluar').value) || 0;
    const sisaBahan = parseInt(document.getElementById('sisabahan').value) || 0;
    const totalDikerjakan = parseInt(document.getElementById('totaldikerjakan').value) || 0;

    const gaji = totalDikerjakan * 50000; // Misalkan Rp.50.000 per pengerjaan

    let dataKaryawan = JSON.parse(localStorage.getItem('dataKaryawan')) || [];
    const index = dataKaryawan.findIndex(data => data.tanggal === tanggal);

    if (index > -1) {
        dataKaryawan[index] = { tanggal, barangMasuk, barangKeluar, sisaBahan, totalDikerjakan, gaji };
        localStorage.setItem('dataKaryawan', JSON.stringify(dataKaryawan));
        alert('Data berhasil diperbarui!');
        window.location.href = 'gajibarang.html'; // Kembali ke halaman gaji barang
    }
}

// Menangani submit form di tambahdatabarang.html
document.addEventListener('DOMContentLoaded', () => {
    const form = document.querySelector('form');
    if (form) {
        form.addEventListener('submit', (event) => {
            event.preventDefault();
            saveData();
        });
    }
});

// Menangani submit form di editdatabarang.html
document.addEventListener('DOMContentLoaded', () => {
    const form = document.querySelector('form');
    if (form) {
        fillEditForm(); // Isi form jika sedang dalam mode edit
        form.addEventListener('submit', (event) => {
            event.preventDefault();
            updateData();
        });
    }
});

// Render data saat gajibarang.html dimuat
document.addEventListener('DOMContentLoaded', () => {
    if (document.title === "Sistem Penggajian Pabrik Sarung Goyor") {
        renderData();
    }
});

// Menghitung total gaji
function calculateTotalGaji() {
    const dataKaryawan = JSON.parse(localStorage.getItem('dataKaryawan')) || [];
    const totalGaji = dataKaryawan.reduce((sum, item) => sum + item.gaji, 0);
    document.getElementById('total-gaji').innerText = totalGaji.toLocaleString();
}

// Menangani tombol hitung total gaji
document.addEventListener('DOMContentLoaded', () => {
    const calculateButton = document.querySelector('.btn-calculate');
    if (calculateButton) {
        calculateButton.addEventListener('click', calculateTotalGaji);
    }
});
