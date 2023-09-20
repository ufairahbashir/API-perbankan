<?php
// Aktifkan error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Sertakan file koneksi ke database
require_once('database.php');

// Fungsi untuk menampilkan saldo
function getSaldo($accountNumber)
{
    global $conn;

    $sql = "SELECT balance FROM account WHERE account_number = '$accountNumber'";
    $result = $conn->query($sql);

    if ($result === false) {
        die("Error dalam query: " . $conn->error);
        exit; // Keluar dari skrip
    }

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        return $row['balance'];
    } else {
        die("Akun tidak ditemukan.");
        exit; // Keluar dari skrip
    }
}

// Fungsi untuk melakukan transfer uang
function transferUang($fromAccount, $toAccount, $amount)
{
    global $conn;

    // Mulai transaksi
    $conn->begin_transaction();

    // Kurangi saldo dari akun sumber
    $sql1 = "UPDATE account SET balance = balance - $amount WHERE account_number = '$fromAccount'";
    $result1 = $conn->query($sql1);

    // Tambahkan saldo ke akun tujuan
    $sql2 = "UPDATE account SET balance = balance + $amount WHERE account_number = '$toAccount'";
    $result2 = $conn->query($sql2);

    if ($result1 && $result2) {
        // Commit transaksi jika kedua operasi berhasil
        $conn->commit();
        echo "Transfer berhasil."; // Pesan debug
    } else {
        // Rollback transaksi jika salah satu operasi gagal
        $conn->rollback();
        echo "Transfer gagal."; // Pesan debug
    }
}


// Fungsi untuk mengecek transaksi
function cekTransaksi($accountNumber)
{
    global $conn;

    $sql = "SELECT * FROM transaction WHERE account_number = '$accountNumber' ORDER BY transaction_date DESC";
    $result = $conn->query($sql);

    if ($result === false) {
        die("Error dalam query: " . $conn->error);
        exit; // Keluar dari skrip
    }

    if ($result->num_rows > 0) {
        $transactions = array();
        while ($row = $result->fetch_assoc()) {
            $transactions[] = $row;
        }
        return $transactions;
    } else {
        die("Tidak ada transaksi untuk akun ini.");
        exit; // Keluar dari skrip
    }
}

// Cek action yang diminta
if (isset($_GET['action'])) {
    $action = $_GET['action'];
    echo "Aksi yang diminta: $action";

    if ($action === 'getSaldo') {
        if (isset($_GET['accountNumber'])) {
            $accountNumber = $_GET['accountNumber'];
            $saldo = getSaldo($accountNumber);
            echo "Saldo: $saldo";
        } else {
            die("Nomor akun tidak diberikan.");
            exit; // Keluar dari skrip
        }
    } elseif ($action === 'transferUang') {
        if (isset($_POST['fromAccount'], $_POST['toAccount'], $_POST['transferAmount'])) {
            $fromAccount = $_POST['fromAccount'];
            $toAccount = $_POST['toAccount'];
            $transferAmount = $_POST['transferAmount'];

            // Debug: Tampilkan data POST yang diterima
            var_dump($_POST);

            $result = transferUang($fromAccount, $toAccount, $transferAmount);
            echo $result;
        } else {
            die("Data transfer tidak lengkap.");
            exit; // Keluar dari skrip
        }
    } elseif ($action === 'cekTransaksi') {
        if (isset($_GET['accountNumberTransaksi'])) {
            $accountNumberTransaksi = $_GET['accountNumberTransaksi'];
            $transactions = cekTransaksi($accountNumberTransaksi);
            if (is_array($transactions)) {
                // Tampilkan data transaksi dalam format yang sesuai
                foreach ($transactions as $transaction) {
                    echo "Tanggal: " . $transaction['transaction_date'] . "<br>";
                    echo "Jumlah: " . $transaction['amount'] . "<br><br>";
                }
            } else {
                echo $transactions; // Pesan jika tidak ada transaksi
            }
        } else {
            die("Nomor akun untuk cek transaksi tidak diberikan.");
            exit; // Keluar dari skrip
        }
    } else {
        die("Aksi tidak valid.");
        exit; // Keluar dari skrip
    }
} else {
    die("Aksi tidak diberikan.");
    exit; // Keluar dari skrip
}
