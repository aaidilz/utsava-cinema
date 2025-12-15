// ambil elemen2 yang dibutuhkan
var keyword = document.getElementById("keyword");
var search = document.getElementById("search");
var search_user = document.getElementById("search_user");

//tambahkan event ketika keyword ditulis
keyword.addEventListener("keyup", function () {
  // buat object ajax
  var xhr = new XMLHttpRequest();

  // cek kesiapan ajax
  xhr.onreadystatechange = function () {
    if (xhr.readyState == 4 && xhr.status == 200) {
      console.log(xhr.responseText);
    }
  };

  // eksekusi ajax
  xhr.open("GET", "ajax/coba.text", true);
  xhr.send();
});

let left_btn = document.getElementsByClassName("bi-chevron-left")[0];
let right_btn = document.getElementsByClassName("bi-chevron-right")[0];
let cards = document.getElementsByClassName("cards")[0];

left_btn.addEventListener("click", () => {
  cards.scrollLeft -= 140;
});
right_btn.addEventListener("click", () => {
  cards.scrollLeft += 140;
});

let json_url = "movie.json";

fetch(json_url)
  .then((Response) => Response.json())
  .then((data) => {
    data.forEach((ele, i) => {
      let { nama, imdb, date, sposter, bposter, genre, url } = ele;
      let card = document.createElement("a");
      card.classList.add("card");
      card.href = url;
      card.innerHTML = cards.appendChild(card);
    });

    document.getElementById("title").innerText = date[0].nama;
    document.getElementById("gen").innerText = date[0].genre;
    document.getElementById("date").innerText = date[0].date;
    document.getElementById("rate").innerHTML =
      '<span>IMDB</span><i class="bi bi-star-fill"></i> ${date[0].imdb}';

      //search data load
      data.forEachelement => {
        let { nama, imdb, date, sposter, bposter, genre, url } = ele;
        let card = document.createElement("a");
        card.classList.add("card");
        card.href = url;
        card.innerHTML = 
        
        
  ;
  }
