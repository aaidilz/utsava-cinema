<?php

function koneksi()

{
    $conn = mysqli_connect('localhost', 'root', '', 'pw2024_tubes_233040095');
    return $conn;
}
function query($query)
{

 // koneksi ke database
    $conn = koneksi();

 // query ke tabel mahasiswa
   $result = mysqli_query($conn, $query);

 // menyiapkan data mahasiswa
   $rows = [];
   while($row = mysqli_fetch_assoc($result)) {
       $rows[] = $row;
   }

   return $rows;
}

function tambah($data)
{
  $conn = koneksi();

  $title = htmlspecialchars ($data['title']);
  $description = htmlspecialchars ($data['description']);
  $tahun_rilis = htmlspecialchars ($data['tahun_rilis']);
  $genre = htmlspecialchars ($data['genre_id']);
  $rating = htmlspecialchars ($data['rating']);

  $query = "INSERT INTO movies 
  VALUES (null, '$title', '$description', '$tahun_rilis', '$genre', '$rating')
  ";

  mysqli_query($conn, $query) or die(mysqli_error($conn)); 

  return mysqli_affected_rows($conn);
}

function hapus($id)

{
  $conn = koneksi();
  mysqli_query($conn, "DELETE FROM movies WHERE id = $id");
  return mysqli_affected_rows($conn);
}

function ubah($data)
{
  $conn = koneksi();

  $id = htmlspecialchars ($data ['id']);
  $title = htmlspecialchars ($data ['title']);
  $description = htmlspecialchars ($data ['description']);
  $tahun_rilis = htmlspecialchars ($data ['tahun_rilis']);
  $genre = htmlspecialchars ($data ['genre_id']);
  $rating = htmlspecialchars ($data ['rating']);

  $query = "UPDATE movies SET
  title = '$title', 
  description = '$description', 
  tahun_rilis = '$tahun_rilis', 
  genre_id = '$genre'
  rating = '$rating'
  WHERE id = '$id";

  mysqli_query($conn, $query) or die(mysqli_error($conn)); 
  return mysqli_affected_rows($conn);
}
function registrasi($data) {
    global $con;

    $username = htmlspecialchars(ucwords(stripslashes($data["username"])));
    $password1 = htmlspecialchars(mysqli_real_escape_string($con, $data["password1"]));

    $password1 = password_hash($password1, PASSWORD_DEFAULT);

    mysqli_query($con, "INSERT INTO user(username, password) VALUES ('$username', '$password1')");

    return mysqli_affected_rows($con);
}
?>  