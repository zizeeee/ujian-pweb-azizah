<?php
   error_reporting(E_ALL ^ E_WARNING || E_NOTICE);
   //Koneksi Database
   $server = "";
   $user = "root";
   $password = "";
   $database = "dbcrud2022";

   //buat koneksi 
   $koneksi = mysqli_connect($server, $user, $password, $database) or die(mysqli_error($koneksi));
   
   //kode otomatis
   $q = mysqli_query($koneksi, "SELECT kode From tbaranggg order by kode desc limit 1");
   $datax = mysqli_fetch_array($q);
   if($datax){
       $no_terakhir = substr($datax['kode'], -3);
       $no = $no_terakhir + 1;

       if($no > 0 and $no <10){
          $kode = "00".$no;
       } else if ($no > 10 and $no <100){
            $kode = "0".$no;
       } else if($no > 100){
            $kode = $no;
       }
   } else {
        $kode = "001";
   }

   $tahun = date('Y');
   $vkode = "IVN-". $tahun . '-' . $kode;
   //INV-2023-001

   //jika tombol simpan diklik
   if(isset($_POST['bsimpan'])){

      //pengujian apakah data akan diedit atau disimpan baru 
      if(isset($_GET['hal']) == "edit"){
         //data akan di edit 
         $edit = mysqli_query($koneksi, "UPDATE tbaranggg SET
                                                nama = '$_POST[tnama]', 
                                                asal = '$_POST[tasal]', 
                                                jumlah = '$_POST[tjumlah]', 
                                                satuan = '$_POST[tsatuan]', 
                                                tanggal_diterima = '$_POST[ttanggal_diterima]'
                                        WHERE id_baranggg = '$_GET[id]'
                                       ");

         //uji jika edit data sukses
         if($edit){
            echo "<script>
                    alert('Edit data sukses!');
                    document.location='index.php';
                 </script>";
         }else{
            echo "<script>
                    alert('Edit data gagal!');
                    document.location='index.php';
                 </script>";
        }
      } else {
          //Data akan disimpan baru
          $simpan = mysqli_query($koneksi, " INSERT INTO tbaranggg (kode, nama, asal, jumlah, satuan, tanggal_diterima)
                                              VALUE ('$_POST[tkode]',
                                                     '$_POST[tnama]',
                                                     '$_POST[tasal]',
                                                     '$_POST[tjumlah]',
                                                     '$_POST[tsatuan]',
                                                     '$_POST[ttanggal_diterima] )
                                                     ");
                //uji jika simpan data sukses
                if($simpan){
                echo "<script>
                alert('Simpan data sukses!');
                document.location='index.php';
                </script>";
             } else {
                 echo "<script>
                 alert('Simpan data gagal!');
                 document.location='index.php';
                 </script>";
            }
        }
    

     
   }

   //deklarasi variabel untuk menampung data yang akan diedit
   $vnama = "";
   $vasal = "";
   $vjumlah = "";
   $vsatuan = "";
   $vtanggal_diterima = "";


   //pengujian jika tombol edit / hapus diklik 
   if(isset($_GET['hal'])) {
     
      //pengujian jika edit data 
      if($_GET['hal'] == "edit"){

          //tampilkan data yang akan di edit
          $tampil = mysqli_query($koneksi, "SELECT * FROM tbaranggg WHERE id_baranggg = '$_GET[id]' ");
          $data = mysqli_fetch_array($tampil);
          if($data){
              //jika data ditemukan, maka data di tampung ke dalam variable
              $vkode = $data['kode'];
              $vnama = $data['nama'];
              $vasal = $data['asal'];
              $vjumlah = $data['jumlah'];
              $vsatuan = $data['satuan'];
              $vtanggal_diterima = $data['tanggal_diterima'];
            }
        } else if ($_GET['hal'] == "hapus"){
            //persiapan hapus data 
            $hapus = mysqli_query($koneksi, "DELETE FROM tbaranggg WHERE id_baranggg = '$_GET[id]' ");
            //uji jika hapus data sukses
            if($hapus){
               echo "<script>
               alert('Hapus data sukses!');
               document.location='index.php';
               </script>";
            } else {
                echo "<script>
                alert('Hapus data gagal!');
                document.location='index.php';
                </script>";
            }
        }
    }



   
?>
   

 <!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Bootstrap demo</title>
    <link href="bootstrap/css/bootstrap.min.css"rel="stylesheet">
  </head>

  <body>
      <!-- awal container -->
      <div class="container">
        <h3 class="text-center">Data Inventaris</h3>
        <h3 class="text-center">Kantor AbyaktaSatya</h3>
        
        <!-- awal row -->
        <div class="row">
            <!-- awal col -->
            <div class="col-md-8 mx-auto">
                <!-- awal card -->
                <div class="card">
                    <div class="card-header bg-info text-light">
                      From Input Data Barang
                    </div>
                    <div class="card-body">
                        <!-- Awal Form--> 
                        <form method="POST">
                            <div class="mb-3">
                                <label class="form-label">Kode Barang</label>
                                <input type="text" name="tkode" value="<?=$vkode?>"class="form-control" placeholder="Masukkan Kode 
                                Barang">
                              </div>
                              
                              <div class="mb-3">
                                <label class="form-label">Nama Barang</label>
                                <input type="text" name="tnama" value="<?=$vnama?>" class="form-control" placeholder="Masukkan Nama 
                                Barang">
                              </div>
                              
                              <div class="mb-3">
                                <label class="form-label">Asal Barang</label>
                                <select class="form-select" name="tasal">
                                    <option value="<?=$vasal?>"><?= $vasal ?></option>
                                    <option value="Pembelian">Pembelian</option>
                                    <option value="Hibah">Hibah</option>
                                    <option value="Sumbangan">Sumbangan</option>
                                    <option value="Bantuan">Bantuan</option>
                                </select>
                            </div>

                            <div class="row">
                                <div class="col">
                                    <div class="mb-3">
                                        <label class="form-label">Jumlah</label>
                                        <input type="number" name="tjumlah" value="<?=$vjumlah?>" class="form-control" 
                                        placeholder="Masukkan Jumlah Barang">
                                    </div>
                                </div>

                                <div class="col">
                                    <div class="mb-3">
                                        <label class="form-label">Satuan</label>
                                        <select class="form-select" name="tsatuan">
                                            <option value="<?=$vsatuan?>"><?= $vsatuan ?></option>
                                            <option value="Unit">Unit</option>
                                            <option value="Kotak">Kotak</option>
                                            <option value="Pcs">Pcs</option>
                                            <option value="Pack">Pack</option>
                                        </select>
                                    </div>
                                </div>

                                    <div class="col">
                                        <div class="mb-3">
                                            <label class="form-label">Tanggal diterima</label>
                                            <input type="date" name="ttanggal_diterima" value="<?=$vtanggal_diterima?>" class="form-control" 
                                            placeholder="Masukkan Jumlah Barang">
                                        </div>
                                    </div>

                                    <div class="text-center">
                                        <hr>
                                        <button class="btn btn-primary" name="bsimpan" type="submit">Simpan</button>
                                        <button class="btn btn-danger" name="bkosongkan" type="riset">Kosongkan</button>
                                    </div>
                            </div>




                        </form>

                        <!-- Akhir Form--> 


                    </div>
                    <div class="card-footer bg-info">
                      
                    </div>
                  </div>
                  <!-- akhir card -->
            </div>
            <!-- akhir col -->
        </div>
        <!-- akhir row -->
        
        <!-- awal card -->
        <div class="card mt-4">
            <div class="card-header bg-info text-light">
               Data Barang 
            </div>
            <div class="card-body">
                <div class="col-md-6  mx-auto">
                    <form method="POST">
                        <div class="input-group mb-3">
                            <input type="text" name="tcari" value="<?= @$_POST['tcari'] ?> class="form-control"
                             placeholder="Masukkan kata kunci!">
                            <button class="btn btn-primary" name="bcari" type="submit">Cari</button>
                            <button class="btn btn-danger" name="breset" type="submit">Reset</button>
                        </div>
                    </form>
                </div>

               <table class="table table-striped table-hover table-bordered">
                   <tr>
                       <th>No.</th>
                       <th>Kode Barang</th>
                       <th>Nama Barang</th>
                       <th>Asal Barang</th>
                       <th>Jumlah</th>
                       <th>Tanggal diterima</th>
                       <th>Aksi</th>
                    </tr>
                    <?php
                       //persiapan menampilkan data 
                       $no = 1;

                       //untuk pencarian data 
                       //jika tombol cari di klik
                       if(isset($_POST['bcari'])){
                           //tampilkan data yang dicari
                           $keyword = $_POST['tcari'];
                           $q = "SELECT * FROM tbaranggg WHERE kode like '%$keyword%' or nama like '%$keyword%' order by
                           id_baranggg desc ";
                        } else {
                             $q = "SELECT * FROM tbaranggg order by id_baranggg desc";
                        }
                       

                       $tampil = mysqli_query($koneksi, $q );
                       while($data = mysqli_fetch_array($tampil)):
                    ?>                   

                           <tr>
                               <td><?= $no++ ?></td>
                               <td><?= $data['kode'] ?></td>
                               <td><?= $data['nama'] ?></td>
                               <td><?= $data['asal'] ?></td>
                               <td><?= $data['jumlah'] ?> <?= $data['satuan'] ?></td>
                               <td><?= $data['tanggal_diterima'] ?></td>
                               <td>
                                   <a href="index.php?hal=edit&id=<?=$data['id_baranggg'] ?>" class="btn 
                                   btn-warning">Edit</a>

                                   <a href="index.php?hal=hapus&id=<?=$data['id_baranggg'] ?>"class="btn 
                                   btn-danger" onclick="return confirm('Apakah anda yakin akan hapus data ini?')">Hapus</a>
                               </td>
                            </tr>

                    <?php endwhile; ?>
            
               </table>
            </div>
            <div class="card-footer bg-info">
              
            </div>
          </div>
          <!-- akhir card -->
        


      </div>
      <!-- akhir container -->



       
    <script src="bootstrap/js/bootstrap.min.js"></script>
  </body>
</html>