<?php
// Koneksi ke database
$conn = mysqli_connect("localhost", "root", "", "crud_siswa");

// Cek koneksi
if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

// Fungsi ambil semua data siswa
function getAllSiswa($conn)
{
    return mysqli_query($conn, "SELECT * FROM siswa");
}

// Fungsi tambah siswa
function tambahSiswa($conn, $nama, $kelas, $alamat)
{
    $query = "INSERT INTO siswa (nama, kelas, alamat) VALUES ('$nama', '$kelas', '$alamat')";
    return mysqli_query($conn, $query);
}

// Fungsi edit siswa
function editSiswa($conn, $id, $nama, $kelas, $alamat)
{
    $query = "UPDATE siswa SET nama='$nama', kelas='$kelas', alamat='$alamat' WHERE id=$id";
    return mysqli_query($conn, $query);
}

// Fungsi hapus siswa
function hapusSiswa($conn, $id)
{
    return mysqli_query($conn, "DELETE FROM siswa WHERE id=$id");
}

// Ambil data siswa (untuk form edit)
function getSiswaById($conn, $id)
{
    return mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM siswa WHERE id=$id"));
}

// Operasi Tambah
if (isset($_POST['tambah'])) {
    tambahSiswa($conn, htmlspecialchars($_POST['nama']), htmlspecialchars($_POST['kelas']), htmlspecialchars($_POST['alamat']));
    header("Location: index.php");
}

// Operasi Edit
if (isset($_POST['edit'])) {
    editSiswa($conn, htmlspecialchars($_POST['id']), htmlspecialchars($_POST['nama']), htmlspecialchars($_POST['kelas']), htmlspecialchars($_POST['alamat']));
    header("Location: index.php");
}

// Operasi Hapus
if (isset($_GET['hapus'])) {
    hapusSiswa($conn, $_GET['hapus']);
    header("Location: index.php");
}

// Ambil data siswa jika dalam mode edit
$siswaEdit = isset($_GET['edit']) ? getSiswaById($conn, $_GET['edit']) : null;

// Ambil semua data siswa untuk ditampilkan
$siswaList = getAllSiswa($conn);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRUD Sederhana 1 File</title>
    <!-- Link CSS Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container mt-5">
        <h1 class="mb-4">Data Siswa</h1>

        <!-- Form Tambah / Edit -->
        <form action="" method="post" class="mb-4">
            <input type="hidden" name="id" value="<?= $siswaEdit['id'] ?? ''; ?>">
            <div class="mb-3">
                <label for="nama" class="form-label">Nama</label>
                <input type="text" class="form-control" name="nama" id="nama" placeholder="Nama" value="<?= $siswaEdit['nama'] ?? ''; ?>" required>
            </div>
            <div class="mb-3">
                <label for="kelas" class="form-label">Kelas</label>
                <input type="text" class="form-control" name="kelas" id="kelas" placeholder="Kelas" value="<?= $siswaEdit['kelas'] ?? ''; ?>" required>
            </div>
            <div class="mb-3">
                <label for="alamat" class="form-label">Alamat</label>
                <textarea class="form-control" name="alamat" id="alamat" placeholder="Alamat" required><?= $siswaEdit['alamat'] ?? ''; ?></textarea>
            </div>
            <button type="submit" name="<?= $siswaEdit ? 'edit' : 'tambah'; ?>" class="btn btn-primary">
                <?= $siswaEdit ? 'Edit' : 'Tambah'; ?>
            </button>
        </form>

        <!-- Tabel Data Siswa -->
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama</th>
                    <th>Kelas</th>
                    <th>Alamat</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php $i = 1;
                foreach ($siswaList as $siswa): ?>
                    <tr>
                        <td><?= $i++; ?></td>
                        <td><?= $siswa['nama']; ?></td>
                        <td><?= $siswa['kelas']; ?></td>
                        <td><?= $siswa['alamat']; ?></td>
                        <td>
                            <a href="index.php?edit=<?= $siswa['id']; ?>" class="btn btn-warning btn-sm">Edit</a>
                            <a href="index.php?hapus=<?= $siswa['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus?')">Hapus</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- Link JS Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>