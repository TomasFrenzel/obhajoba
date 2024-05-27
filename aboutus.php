<?php
// Název stránky
$title = 'O nás';

// Zahrnutí záhlaví
include_once('./temp/heading.php');

// Obsah sekce
echo '<!-- About us section -->
<section class="container">
  <h2 class="display-3 text-white fw-bold mt-5">O <span class="colored-text">projektu</span></h2>
  <div class="about-container d-flex justify-content-between pt-1">
    <div class="col-lg-6 mt-4">
      <div class="row about-description text-white ms-1 mt-5">
        Tento projekt vznikl za účelem informovat ohledně nebezpečí na
        internetu. Na nají stránce se nedozvíš jenom co je to hoax nebo
        phishing, ale jak je taky odhalit. Mojím cílem je poukázat na
        problém informační negramotnosti, a jak občas můžou být podvodníci
        kreativní.
      </div>
      <div class="row about-description text-white ms-1 my-5">
        Jsem studentem střední školy Informačních technologií. Před půl rokem
        nám bylo zadáno vytvořit maturitní projekt na náše zvolené téma. Vybral jsem si bezpečnost na internetu. 
        Mojim cílem bylo vytvoit stránku, která propojí praxy a vzdělání.
      </div>
    </div>
    <div class="col-lg-5 d-flex justify-content-end">
      <img
        src="./img/aboutus-blob-image.svg"
        alt="ilustrační obrázek"
        class="about-image" />
    </div>
  </div>
</section>
<section class="container mt-2 pt-3 d-flex flex-column align-items-center" id="about-project-section">
<!-- Video -->
<div id="video-container" class="mt-5">
    <video id="video" controls poster="./imgs/thumbnail.png">
        <source src="./video/video.mp4" type="video/mp4">
    </video>
</div>
</section>';

// Zahrnutí zápatí
include_once('./temp/footer.php');
?>