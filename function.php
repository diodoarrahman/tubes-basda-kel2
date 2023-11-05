<?php
session_start();
//Membuat koneksi ke database
$conn = mysqli_connect("localhost","root","","inventory");

//Menambah barang baru
if(isset($_POST['addnewbarang'])){
    $namabarang = $_POST['namabarang'];
    $deskripsi = $_POST['deskripsi'];
    $jumlah = $_POST['jumlah'];
    
    $addtotable = mysqli_query($conn,"insert into barang (namabarang,deskripsi,jumlah) values('$namabarang','$deskripsi','$jumlah')");
    if($addtotable){
        header("location: index.php");
    }else
    {
        echo"gagal";
        header("location:index.php");
    }
}

//Menambah barang masuk
if(isset($_POST['barangmasuk'])){
    $barangnya = $_POST['barangnya'];
    $penerima = $_POST['penerima'];
    $qty = $_POST['qty'];

    $cekstocksekarang = mysqli_query($conn,"select * from barang where idbarang='$barangnya'");
    $ambildatanya = mysqli_fetch_array($cekstocksekarang);

    $stocksekarang = $ambildatanya['jumlah'];
    $tambahkanstocksekarangdenganquantity = $stocksekarang+$qty;
    
    $addtomasuk = mysqli_query($conn,"insert into masuk (idbarang,keterangan,qty) values('$barangnya','$penerima','$qty')");
    $updatestockmasuk = mysqli_query($conn,"update barang set jumlah='$tambahkanstocksekarangdenganquantity' where idbarang='$barangnya'");

    if($addtomasuk&&$updatestockmasuk){
        header("location: masuk.php");
    }else
    {
        echo"gagal";
        header("location:masuk.php");
    }
}

//Menambah barang keluar
if(isset($_POST['addbarangkeluar'])){
    $barangnya = $_POST['barangnya'];
    $penerima = $_POST['penerima'];
    $qty = $_POST['qty'];

    $cekstocksekarang = mysqli_query($conn,"select * from barang where idbarang='$barangnya'");
    $ambildatanya = mysqli_fetch_array($cekstocksekarang);

    $stocksekarang = $ambildatanya['jumlah'];
    $tambahkanstocksekarangdenganquantity = $stocksekarang-$qty;
    
    $addtokeluar = mysqli_query($conn,"insert into keluar (idbarang,penerima,qty) values('$barangnya','$penerima','$qty')");
    $updatestockmasuk = mysqli_query($conn,"update barang set jumlah='$tambahkanstocksekarangdenganquantity' where idbarang='$barangnya'");

    if($addtokeluar&&$updatestockmasuk){
        header("location: keluar.php");
    }else
    {
        echo"gagal";
        header("location:keluar.php");
    }
}

//update info barang
if(isset($_POST['updatebarang'])){
    $idb = $_POST['idb'];
    $namabarang = $_POST['namabarang'];
    $deskripsi = $_POST['deskripsi'];
    $jumlah = $_POST['jumlah'];

    $update = mysqli_query($conn,"update barang set namabarang='$namabarang', deskripsi = '$deskripsi',jumlah='$jumlah' where idbarang ='$idb'");
    if($update){
        header("location: index.php");
    }else
    {
        echo"gagal";
        header("location:index.php");
    }
}

//menghapus barang
if(isset($_POST['hapusbarang'])){
    $idb = $_POST['idb'];

    $hapus = mysqli_query($conn,"delete from barang where idbarang ='$idb'");
    if($update){
        header("location: index.php");
    }else
    {
        echo"gagal";
        header("location:index.php");
    }
}

//update info barangmasuk
if(isset($_POST['updatebarangmasuk'])){
    $idb = $_POST['idb'];
    $idm = $_POST['idm'];
    $keterangan = $_POST['keterangan'];
    $qty = $_POST['qty'];

    $lihatstock = mysqli_query($conn,"Select * from barang where idbarang='$idb'");
    $stocknya = mysqli_fetch_array($lihatstock);
    $stockskrg = $stocknya["jumlah"];

    $qtyskrg = mysqli_query($conn,"Select * from masuk where idmasuk='$idm'");
    $qtynya = mysqli_fetch_array($qtyskrg);
    $qtyskrg = $qtynya["qty"];

    if($qty>$qtyskrg){
        $selisih = $qty-$qtyskrg;
        $kurangin = $stockskrg + $selisih;
        $kurangistocknya = mysqli_query($conn, "update barang set jumlah='$kurangin' where idbarang='$idb'");
        $updatenya = mysqli_query($conn,"update masuk set qty='$qty', keterangan = '$keterangan' where idmasuk='$idm'");
        if($kurangistocknya && $updatenya){
            header("location: masuk.php");
        }else
        {
            echo"gagal";
            header("location:masuk.php");
        }
    }
    else {
        $selisih = $qtyskrg-$qty;
        $kurangin = $stockskrg - $selisih;
        $kurangistocknya = mysqli_query($conn, "update barang set jumlah='$kurangin' where idbarang='$idb'");
        $updatenya = mysqli_query($conn,"update masuk set qty='$qty', keterangan = '$keterangan' where idmasuk='$idm'");
        if($kurangistocknya && $updatenya){
            header("location: masuk.php");
        }else
        {
            echo"gagal";
            header("location:masuk.php");
        }
    }
}

//Menghapus barang masuk
if(isset($_POST['hapusbarangmasuk'])){
    $idb = $_POST['idb'];
    $idm = $_POST['idm'];
    $qty = $_POST['kty'];

    $getdatastock = mysqli_query($conn,"Select * from barang where idbarang='$idb'");
    $data = mysqli_fetch_array($getdatastock);
    $stock = $data["jumlah"];

    $selisih= $stock-$qty;

    $update = mysqli_query($conn, "update barang set jumlah='$selisih' where idbarang='$idb'");
    $hapus = mysqli_query($conn,"delete from masuk where idmasuk ='$idm'");
    if($update){
        header("location: masuk.php");
    }else
    {
        echo"gagal";
        header("location:masuk.php");
    }
}

//update info barangkeluar
if(isset($_POST['updatebarangkeluar'])){
    $idb = $_POST['idb'];
    $idk = $_POST['idk'];
    $penerima = $_POST['penerima'];
    $qty = $_POST['qty'];

    $lihatstock = mysqli_query($conn,"Select * from barang where idbarang='$idb'");
    $stocknya = mysqli_fetch_array($lihatstock);
    $stockskrg = $stocknya["jumlah"];

    $qtyskrg = mysqli_query($conn,"Select * from keluar where idkeluar='$idk'");
    $qtynya = mysqli_fetch_array($qtyskrg);
    $qtyskrg = $qtynya["qty"];

    if($qty>$qtyskrg){
        $selisih = $qty-$qtyskrg;
        $kurangin = $stockskrg - $selisih;
        $kurangistocknya = mysqli_query($conn, "update barang set jumlah='$kurangin' where idbarang='$idb'");
        $updatenya = mysqli_query($conn,"update keluar set qty='$qty', penerima = '$penerima' where idkeluar='$idk'");
        if($kurangistocknya && $updatenya){
            header("location: keluar.php");
        }else
        {
            echo"gagal";
            header("location:keluar.php");
        }
    }
    else {
        $selisih = $qtyskrg-$qty;
        $kurangin = $stockskrg + $selisih;
        $kurangistocknya = mysqli_query($conn, "update barang set jumlah='$kurangin' where idbarang='$idb'");
        $updatenya = mysqli_query($conn,"update keluar set qty='$qty', penerima = '$penerima' where idkeluar='$idk'");
        if($kurangistocknya && $updatenya){
            header("location: keluar.php");
        }else
        {
            echo"gagal";
            header("location:keluar.php");
        }
    }
}

//Menghapus barang keluar
if(isset($_POST['hapusbarangkeluar'])){
    $idb = $_POST['idb'];
    $idk = $_POST['idk'];
    $qty = $_POST['kty'];

    $getdatastock = mysqli_query($conn,"Select * from barang where idbarang='$idb'");
    $data = mysqli_fetch_array($getdatastock);
    $stock = $data["jumlah"];

    $selisih= $stock+$qty;

    $update = mysqli_query($conn, "update barang set jumlah='$selisih' where idbarang='$idb'");
    $hapus = mysqli_query($conn,"delete from keluar where idkeluar ='$idk'");
    if($update){
        header("location: keluar.php");
    }else
    {
        echo"gagal";
        header("location: keluar.php");
    }
}
?>