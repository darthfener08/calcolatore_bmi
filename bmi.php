<?php
    if($_SERVER['REQUEST_METHOD'] == 'GET'){
        header("Location: form.html");
    }
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title>Risultato BMI</title>
    <!-- Collega lo stesso file CSS per mantenere lo stile uniforme -->
    <link rel="stylesheet" href="stile.css">
</head>
<body>

    <!-- Titolo della pagina dei risultati -->
    <h1>Il tuo BMI</h1>

    <?php

    

    // 1. Leggiamo i dati inviati dal form tramite $_POST
    $nome = $_POST['nome'];
    $peso = $_POST['peso'];
    $altezza_cm = $_POST['altezza'];

    // 2. Trasformiamo l'altezza da centimetri a metri (dividendo per 100)
    $altezza_m = $altezza_cm / 100;

    // 3. Calcoliamo il BMI: peso diviso l'altezza al quadrato
    $bmi = $peso / ($altezza_m * $altezza_m);

    // 4. Arrotondiamo il risultato a 2 decimali
    $bmi_arrotondato = round($bmi, 2);

    // 5. Decidiamo la fascia in base al valore del BMI
    if ($bmi_arrotondato < 18.5) {
        $classe = "sottopeso";
        $testo = "SOTTOPESO";
    } elseif ($bmi_arrotondato >= 18.5 && $bmi_arrotondato <= 24.99) {
        $classe = "normopeso";
        $testo = "NORMOPESO";
    } elseif ($bmi_arrotondato >= 25 && $bmi_arrotondato <= 29.99) {
        $classe = "sovrappeso";
        $testo = "SOVRAPPESO";
    } else {
        $classe = "obesita";
        $testo = "OBESITÀ";
    }

    

    $myfile = fopen("log.csv", "a") or die("non puoi aprire il file");

    if($myfile == TRUE){
        //preparazione del record di log
        $record = "";
        $record .= date("d/m/Y");
        $record .= "; ";
        $record .= date("G:i:s");
        $record .= "; ";  
        $record .= $_SERVER['REMOTE_ADDR'];
        $record .= "; ";  
        $record .= $nome;  
        $record .= "; ";  
        $record .= $altezza_cm; 
        $record .= "; ";  
        $record .= $peso;  
        $record .= "; ";  
        $record .= $bmi_arrotondato; 
        $record .= "; ";
        $record .= $testo; 
        $record .= "; ";
        $record .= "\n";






        //registrazione del record
        fwrite($myfile,$record);

        fclose($myfile);
    }     

        
        
        
    ?>

    <!-- 6. Creiamo l'unica card HTML. 
         Stampa sia la classe base "card" sia la classe calcolata in PHP (es. "card normopeso") -->
    <div class="card <?php echo $classe; ?>">
        <!-- Mostra il nome dell'utente -->
        <h2><?php echo $nome; ?></h2>
        
        <!-- Mostra il riepilogo di peso e altezza -->
        <p>Peso <?php echo $peso; ?> kg &middot; Altezza <?php echo $altezza_cm; ?> cm</p>
        
        <!-- Mostra il numero del BMI grande -->
        <div class="bmi-numero"><?php echo $bmi_arrotondato; ?></div>
        
        <!-- Mostra la scritta della fascia in base al calcolo -->
        <div class="fascia-testo"><?php echo $testo; ?></div>
    </div>

    <!-- Link per tornare alla pagina del form -->
    <a href="form.html" class="link-indietro">Calcola di nuovo</a>

</body>
</html>