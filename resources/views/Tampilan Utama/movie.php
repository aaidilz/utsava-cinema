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
    <link rel="stylesheet" href="movie/style.css" />
    <link
      rel="shortcut icon"
      href="img/logo belakang.jpg"
      type="image/x-icon"
    />
    <title>Utsava-Cinema</title>
  </head>

  <body>
    <header>
      <video src="#" autoplay muted loop></video>
      <nav>
        <div class="logo_ul">
          <img src="#" alt="" />
          <ul>
            <li>
              <a href="movie.php">Home</a>
            </li>
            <li>
              <a href="#">Series</a>
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
          <div class="search">
            <?php if(isset($_SESSION['search'])) : ?>
            <a href="#" class="card">
              <img src="#" alt="" />
              <div class="cont">
                <h3>#</h3>
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
        <h1 id="title">#</h1>
        <p>
        </p>
        <div class="details">
          <h6>#</h6>
          <h5 id="gen">#</h5>
          <h4 id="date">#</h4>
          <h3 id="rate">
            <span>#</span><i class="bi bi-star-fill"></i> 9.8
          </h3>
        </div>
        <div class="btns">
          <a href="#" id="play">#<i class="bi bi-play-fill"></i></a>
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
  </body>
</html>
