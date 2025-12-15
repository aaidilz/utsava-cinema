<?php
session_start();
require "functions/functions.php";
$movies = query("SELECT * FROM movies");
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"
    />
    <link rel="stylesheet" href="movie/style.css" />
    <link
      rel="shortcut icon"
      href="img/logo belakang.jpg"
      type="image/x-icon"
    />
    <title>Movies</title>
  </head>

  <body>
    <header>
      <video src="video/money.mp4" autoplay muted loop></video>
      <nav>
        <div class="logo_ul">
          <img src="img/.jpg" alt="" />
          <ul>
            <li>
              <a href="movie.php">Home</a>
            </li>
            <li>
              <a href="serial.php">Series</a>
            </li>
            <li>
              <a href="#">Movies</a>
            </li>
            <li>
              <a href="#">kids</a>
            </li>
          </ul>
        </div>
        <div class="search_user">
          <input type="text" placeholder="Search..." id="Search_input" />
          <img src="img/user.jpg" alt="" />
          <a href="login.php"> <button class="btn">log out</button></a>
          <div class="search">
            <?php if(isset($_SESSION['search'])) : ?>
            <a href="#" class="card">
              <img src="img/ant-man-wasp1.jpg" alt="" />
              <div class="cont">
                <h3>ant-man-wasp</h3>
                <p>
                  Action, 2015, <span>IMDB</span
                  ><i class="bi bi-star-fill"></i> 9.8
                </p>
              </div>
            </a>
            <?php endif ; ?>
          </div>
        </div>
      </nav>
      <div class="content">
        <h1 id="title">Money Heist</h1>
        <p>
          Money Heist is a crime-themed television drama series from Spain.
          Produced by Álex Pina, the series was originally intended as a limited
          series to be told in two parts. The series originally had 15 episodes
          which aired on the Spanish television network, Antena 3, from 2 May
          2017 to 23 November 2017.
        </p>
        <div class="details">
          <h6>A Netflix Original Film</h6>
          <h5 id="gen">Thriller</h5>
          <h4 id="date">2021</h4>
          <h3 id="rate">
            <span>IMDB</span><i class="bi bi-star-fill"></i> 9.8
          </h3>
        </div>
        <div class="btns">
          <a href="#" id="play">watch <i class="bi bi-play-fill"></i></a>
          <a href="#" id="download_main">
            <i class="bi bi-cloud-arrow-down-fill"></i>
          </a>
        </div>
      </div>
      <section>
        <h4>popular</h4>
        <i class="bi bi-chevron-left"></i>
        <i class="bi bi-chevron-right"></i>
        <div class="cards">
          <?php foreach($movies as $mv) : ?>
           <a href="#" class="card">
              <img src="img/<?= $mv["poster 1"] ?>" alt="" class="poster" />
              <div class="rest_card">
                <img src="img/<?= $mv["poster 2"] ?>" alt="" />
                <div class="cont">
                  <h4><?= $mv["title"] ?></h4>
                  <div class="sub">
                    <p>Action, <?= $mv["tahun_rilis"] ?></p>
                    <h3><span>IMDB</span><i class="bi bi-star-fill"></i><?= $mv["rating"] ?></h3>
                  </div>
                </div>
              </div> 
            </a> 
            <?php endforeach ; ?>
        </div>
      </section>
    </header>
    <script src="movie/app.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

  </body>
</html>
