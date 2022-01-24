<?php

require '../baza.class.php';

$baza = new Baza();
$baza->spojiDB();

$upitnik = "SELECT str_korisnici FROM konfiguracija";
$ans = $baza->selectDB($upitnik);
$vrat = mysqli_fetch_array($ans);
$st=$vrat[0];
$total_records_per_page = $st;

if(isset($_POST["odabrano"])){
    $od=$_POST["odabrano"];
 
}
else{
  
}

$korime="";
if (isset($_POST["odabrano"])) {
    $string = $_POST["odabrano"];
    if(isset($_POST["txtbox"])){
        $korime=$_POST["txtbox"];

    }
  

    if ($string =="svi" && $korime=="") {

        $sql = "SELECT * FROM korisnik";
    }  
    if($string=="moderatori" && $korime==""){

        $sql = "SELECT a.naziv, k.id_korisnik, k.korisnicko_ime, k.ime, k.prezime FROM korisnik k JOIN autokuca a ON k.autokuca_id = a.id_autokuca";
    }
    if ($string =="svi" && $korime!="") {

                
        $sql = "SELECT * FROM korisnik WHERE korisnicko_ime like '$korime%' OR ime like '$korime%' OR prezime like '$korime%'";
    }  
    if($string=="moderatori" && $korime!=""){
      
        
        $sql = "SELECT a.naziv, k.id_korisnik, k.korisnicko_ime, k.ime, k.prezime FROM korisnik k JOIN autokuca a ON k.autokuca_id = a.id_autokuca WHERE k.korisnicko_ime like '$korime%' OR k.ime like '$korime%' OR k.prezime like '$korime%' OR a.naziv like '$korime%'";
        
        
    }
    $rezultat4 = $baza->selectDB($sql);
    $brojZapisa = mysqli_num_rows($rezultat4);

    $brojStranica = ceil($brojZapisa / $total_records_per_page);


    if (!isset($_POST["str"])) {
        $redniBrojStranice = 1;
    } else {
        $redniBrojStranice = $_POST["str"];
    }
    
    if (!isset($_POST['stupac'])) {
        $stupac = 'id_korisnik';
    } else {
        $stupac = $_POST['stupac'];
    }


    if (!isset($_POST['smjer'])) {
        $smjer = 'DESC'; 
    } else {
        $smjer = $_POST['smjer'];
    }

    $smjer == 'ASC' ? $smjer = 'DESC' : $smjer = 'ASC';
   
        
    

    $prviZapis = ($redniBrojStranice - 1) * $total_records_per_page;

 
    
    
   if ($string =="svi" && $korime=="") {
       
        $sql = "SELECT * FROM korisnik order by $stupac $smjer LIMIT $prviZapis, $total_records_per_page";
    }  
    if($string=="moderatori" && $korime==""){
   
        $sql ="SELECT a.naziv, k.id_korisnik, k.korisnicko_ime, k.ime, k.prezime FROM korisnik k JOIN autokuca a ON k.autokuca_id = a.id_autokuca ORDER BY $stupac $smjer LIMIT $prviZapis, $total_records_per_page";
    }
    if ($string =="svi" && $korime!="") {
       
        $sql = "SELECT * FROM korisnik WHERE korisnicko_ime LIKE '$korime%' OR ime LIKE '$korime%' OR prezime like '$korime%' order by $stupac $smjer LIMIT $prviZapis, $total_records_per_page";
    }  
    if($string=="moderatori" && $korime!=""){
     
        $sql = "SELECT a.naziv, k.id_korisnik, k.korisnicko_ime, k.ime, k.prezime FROM korisnik k JOIN autokuca a ON k.autokuca_id = a.id_autokuca WHERE k.korisnicko_ime like '$korime%' OR k.ime like '$korime%' OR k.prezime like '$korime%' OR a.naziv like '$korime%' order by $stupac $smjer LIMIT $prviZapis, $total_records_per_page";
    }
    $rezultat = $baza->selectDB($sql); 


    echo "<table class='sviservisi' border>";
    echo "<tr>";
    if($string=="moderatori"){
        echo "<th><a class='sort' id='naziv' data-order='" . $smjer . "' href='#'>Naziv autokuce</a></th>";
    }
    echo "<th><a class='sort' id='id_korisnik' data-order='" . $smjer . "' href='#'>ID korisnika</a></th>";    
    echo "<th><a class='sort' id='korisnicko_ime' data-order='" . $smjer . "' href='#'>Korisnicko ime</a></th>";
    echo "<th><a class='sort' id='ime' data-order='" . $smjer . "' href='#'>Ime</a></th>";
    echo "<th><a class='sort' id='prezime' data-order='" . $smjer . "' href='#'>Prezime</a></th>";
    echo "</tr>";
    while ($red = mysqli_fetch_array($rezultat)) {
        
        echo "<tr>";
        if($string=="moderatori"){
           echo "<td>" . $red['naziv'] . "</td>";  
        }
        echo "<td>" . $red['id_korisnik'] . "</td>";        
        echo "<td>" . $red['korisnicko_ime'] . "</td>";
        echo "<td>" . $red['ime'] . "</td>";
        echo "<td>" . $red['prezime'] . "</td>";
        echo "</tr>";
    }
    echo "</table>";




    echo '<div class="pagination" style="clear:both">';

    $redniBrojStranice == $brojStranica ? $sljedeci = $brojStranica : $sljedeci = $redniBrojStranice  + 1;
    $redniBrojStranice  == 1 ? $prethodni = $redniBrojStranice  : $prethodni = $redniBrojStranice  - 1;
    for ($i = 1; $i <= $brojStranica; $i++) {
        if ($i == 1) {
            echo '<button class="pagination_link" type="button" id="first" value="' . $i . '" > &laquo; </button>';
            echo '<button class="pagination_link" type="button" id="prev" value="' . $prethodni . '" > Prethodna </button>';
        }
        echo '<button class="pagination_link" value="' . $i . '"  type="button" id="first" > ' . $i . ' </button>';
        if ($i == $brojStranica) {
            echo '<button class="pagination_link" type="button" id="next" value="' . $sljedeci . '" > Sljedeca </button>';
            echo '<button class="pagination_link" type="button" id="last" value="' . $i . '" > &raquo; </button>';
        }
    }
    echo '</div>'; 
            
} 

