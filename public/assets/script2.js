//  KODE JAVASCRIPT UNTUK GAJI TENUN
// Menyimpan data gaji tenun di localStorage
function saveData() {
    const mingguKe = document.getElementById('mingguKe').value;
    const hari1 = parseInt(document.getElementById('hari1').value) || 0;
    const hari2 = parseInt(document.getElementById('hari2').value) || 0;
    const hari3 = parseInt(document.getElementById('hari3').value) || 0;
    const hari4 = parseInt(document.getElementById('hari4').value) || 0;
    const hari5 = parseInt(document.getElementById('hari5').value) || 0;
    const hari6 = parseInt(document.getElementById('hari6').value) || 0;

    const totalPengerjaan = hari1 + hari2 + hari3 + hari4 + hari5 + hari6;
    const gaji = totalPengerjaan * 75000; // Misalkan Rp.75.000 per pengerjaan

    const data = {
        mingguKe,
        hari1,
        hari2,
        hari3,
        hari4,
        hari5,
        hari6,
        totalPengerjaan,
        gaji
    };

    let dataKaryawan = JSON.parse(localStorage.getItem('dataKaryawan')) || [];
    dataKaryawan.push(data);
    localStorage.setItem('dataKaryawan', JSON.stringify(dataKaryawan));

    alert('Data berhasil disimpan!');
    window.location.href = 'gajitenun.html'; // Kembali ke halaman gaji tenun
}

// Menghapus data berdasarkan minggu ke
function deleteData(mingguKe) {
    let dataKaryawan = JSON.parse(localStorage.getItem('dataKaryawan')) || [];
    dataKaryawan = dataKaryawan.filter(item => item.mingguKe !== mingguKe);
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
    dataKaryawan.forEach((item, index) => {
        const row = `
            <tr>
                <td>${item.mingguKe}</td>
                <td>${item.hari1}</td>
                <td>${item.hari2}</td>
                <td>${item.hari3}</td>
                <td>${item.hari4}</td>
                <td>${item.hari5}</td>
                <td>${item.hari6}</td>
                <td>${item.totalPengerjaan}</td>
                <td>Rp.${item.gaji.toLocaleString()}</td>
                <td class="action-icons">
                    <a href="editdatatenun.html?mingguKe=${item.mingguKe}"><i class="fas fa-edit"></i></a>
                    <a href="#" onclick="deleteData('${item.mingguKe}')"><i class="fas fa-trash-alt"></i></a>
                </td>
            </tr>
        `;
        tbody.innerHTML += row;
    });
}

// Mengisi form edit
function fillEditForm() {
    const params = new URLSearchParams(window.location.search);
    const mingguKe = params.get('mingguKe');
    const dataKaryawan = JSON.parse(localStorage.getItem('dataKaryawan')) || [];
    const item = dataKaryawan.find(data => data.mingguKe === mingguKe);

    if (item) {
        document.getElementById('mingguKe').value = item.mingguKe;
        document.getElementById('hari1').value = item.hari1;
        document.getElementById('hari2').value = item.hari2;
        document.getElementById('hari3').value = item.hari3;
        document.getElementById('hari4').value = item.hari4;
        document.getElementById('hari5').value = item.hari5;
        document.getElementById('hari6').value = item.hari6;
    }
}

// Memperbarui data
function updateData() {
    const mingguKe = document.getElementById('mingguKe').value;
    const hari1 = parseInt(document.getElementById('hari1').value) || 0;
    const hari2 = parseInt(document.getElementById('hari2').value) || 0;
    const hari3 = parseInt(document.getElementById('hari3').value) || 0;
    const hari4 = parseInt(document.getElementById('hari4').value) || 0;
    const hari5 = parseInt(document.getElementById('hari5').value) || 0;
    const hari6 = parseInt(document.getElementById('hari6').value) || 0;

    const totalPengerjaan = hari1 + hari2 + hari3 + hari4 + hari5 + hari6;
    const gaji = totalPengerjaan * 75000; // Misalkan Rp.75.000 per pengerjaan

    let dataKaryawan = JSON.parse(localStorage.getItem('dataKaryawan')) || [];
    const index = dataKaryawan.findIndex(data => data.mingguKe === mingguKe);

    if (index > -1) {
        dataKaryawan[index] = { mingguKe, hari1, hari2, hari3, hari4, hari5, hari6, totalPengerjaan, gaji };
        localStorage.setItem('dataKaryawan', JSON.stringify(dataKaryawan));
        alert('Data berhasil diperbarui!');
        window.location.href = 'gajitenun.html'; // Kembali ke halaman gaji tenun
    }
}

// Menangani submit form di tambahdatatenun.html
document.addEventListener('DOMContentLoaded', () => {
    const form = document.querySelector('form');
    if (form) {
        form.addEventListener('submit', (event) => {
            event.preventDefault();
            saveData();
        });
    }
});

// Menangani submit form di editdatatenun.html
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

// Render data saat gajitenun.html dimuat
document.addEventListener('DOMContentLoaded', () => {
    if (document.title === "Sistem Penggajian Pabrik Sarung Goyor") {
        renderData();
    }
});

