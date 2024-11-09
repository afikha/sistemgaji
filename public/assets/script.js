document.addEventListener("DOMContentLoaded", () => {
    const currentPage = window.location.pathname.split("/").pop(); // Mengambil nama file saat ini

    if (currentPage === "index.html") {
        loadKaryawanData();
        document.querySelectorAll(".fa-trash-alt").forEach((icon, index) => {
            icon.addEventListener("click", () => {
                if (confirm("Apakah Anda yakin ingin menghapus data karyawan ini?")) {
                    deleteKaryawan(index);
                }
            });
        });

        // Tambahkan event listener untuk ikon info
        document.querySelectorAll(".info-link").forEach(link => {
            link.addEventListener("click", (event) => {
                event.preventDefault(); // Mencegah aksi default anchor
                const jabatan = link.getAttribute("data-jabatan"); // Ambil jabatan dari atribut data
                if (jabatan === "tenun") {
                    window.location.href = "gajitenun.html"; // Arahkan ke halaman gajitenun.html
                } else {
                    window.location.href = "gajibarang.html"; // Arahkan ke halaman gajibarang.html untuk jabatan selain tenun
                }
            });
        });
    } else if (currentPage === "tambahdatakaryawan.html") {
        const form = document.querySelector("form");
        form.addEventListener("submit", (event) => {
            event.preventDefault();
            const nama = document.getElementById("namakaryawan").value;
            const jabatan = document.getElementById("jabatan").value;
            addKaryawan(nama, jabatan);
        });
    } else if (currentPage === "editdatakaryawan.html") {
        const form = document.querySelector("form");
        const urlParams = new URLSearchParams(window.location.search);
        const index = urlParams.get("index");
        const karyawanData = JSON.parse(localStorage.getItem("karyawanData")) || [];

        if (index !== null && karyawanData[index]) {
            document.getElementById("namakaryawan").value = karyawanData[index].nama;
            document.getElementById("jabatan").value = karyawanData[index].jabatan;
        }

        form.addEventListener("submit", (event) => {
            event.preventDefault();
            karyawanData[index].nama = document.getElementById("namakaryawan").value;
            karyawanData[index].jabatan = document.getElementById("jabatan").value;
            localStorage.setItem("karyawanData", JSON.stringify(karyawanData));
            alert("Data karyawan berhasil diubah!");
            window.location.href = "index.html";
        });
    }
});

// Fungsi untuk menambah data karyawan
function addKaryawan(nama, jabatan) {
    const newKaryawan = { nama, jabatan };
    let karyawanData = JSON.parse(localStorage.getItem("karyawanData")) || [];
    karyawanData.push(newKaryawan);
    localStorage.setItem("karyawanData", JSON.stringify(karyawanData));
    alert("Data karyawan berhasil ditambahkan!");
    window.location.href = "index.html"; // Kembali ke halaman index
}

// Memuat data karyawan dari Local Storage
function loadKaryawanData() {
    const karyawanData = JSON.parse(localStorage.getItem("karyawanData")) || [];
    const tbody = document.querySelector("tbody");
    tbody.innerHTML = ""; // Bersihkan tabel sebelumnya
    karyawanData.forEach((karyawan, index) => {
        const row = document.createElement("tr");
        row.innerHTML = `
            <td>${index + 1}</td>
            <td>${karyawan.nama}</td>
            <td>${karyawan.jabatan}</td>
            <td class="action-icons">
                <a href="editdatakaryawan.html?index=${index}"><i class="fas fa-edit"></i></a>
                <a href="#"><i class="fas fa-trash-alt"></i></a>
                <a href="#" data-jabatan="${karyawan.jabatan.toLowerCase()}" class="info-link"><i class="fas fa-info-circle"></i></a>
            </td>
        `;
        tbody.appendChild(row);
    });
}

// Fungsi untuk menghapus data karyawan
function deleteKaryawan(index) {
    let karyawanData = JSON.parse(localStorage.getItem("karyawanData")) || [];
    karyawanData.splice(index, 1);
    localStorage.setItem("karyawanData", JSON.stringify(karyawanData));
    loadKaryawanData(); // Muat ulang data karyawan setelah penghapusan
}

// Menambahkan fungsi untuk toggle dropdown ketika klik ikon user
function toggleDropdown() {
    const dropdownContent = document.getElementById("userDropdown");
    dropdownContent.style.display = dropdownContent.style.display === "block" ? "none" : "block";
}
