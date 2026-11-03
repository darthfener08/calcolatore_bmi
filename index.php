<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title>Calcolatore BMI</title>
    <style>
    body {
        font-family: sans-serif;
        background-color: #F1F3F5;
        color: #1F2328;
        margin: 0;
        display: flex;
        flex-direction: column; 
        justify-content: center; 
        align-items: center; 
        min-height: 100vh; 
        gap: 20px; 
    }

    .box-form {
        background-color: white;
        width: 360px;
        padding: 24px;
        border-radius: 12px;
        box-shadow: 0 4px 10px rgba(0,0,0,0.05);
    }

    form {
        display: flex;
        flex-direction: column;
        gap: 15px;
    }

    .gruppo {
        display: flex;
        flex-direction: column;
        gap: 5px;
    }

    input {
        width: 100%;
        padding: 10px;
        border: 1px solid #cccccc;
        border-radius: 8px;
        box-sizing: border-box;
    }

    button {
        background-color: #1F2328;
        color: white;
        padding: 12px;
        border: none;
        border-radius: 8px;
        width: 100%;
        cursor: pointer;
    }

    .card {
        width: 360px;
        padding: 24px;
        border: 3px solid;
        border-radius: 12px;
        box-sizing: border-box;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 10px;
    }

    .card h2, .card p {
        margin: 0; 
    }

    .bmi-numero {
        font-size: 48px;
        font-weight: bold;
    }

    .fascia-testo {
        font-weight: bold;
    }

    .link-indietro {
        color: #1F2328;
        text-decoration: none;
    }

    .sottopeso {
        background-color: #E3EEF9;
        border-color: #2F6FB3;
    }
    .sottopeso .fascia-testo {
        color: #2F6FB3;
    }

    .normopeso {
        background-color: #E3F4E6;
        border-color: #256D34;
    }
    .normopeso .fascia-testo {
        color: #256D34;
    }

    .sovrappeso {
        background-color: #FCEBD7;
        border-color: #9A4F06;
    }
    .sovrappeso .fascia-testo {
        color: #9A4F06;
    }

    .obesita {
        background-color: #FBE2E2;
        border-color: #B32D2D;
    }
    .obesita .fascia-testo {
        color: #B32D2D;
    }
    </style>
</head>
<body>

    <?php if ($_SERVER['REQUEST_METHOD'] === 'POST'): ?>

        <?php
        $nome = htmlspecialchars($_POST['nome']);
        $peso = floatval($_POST['peso']);
        $altezza_cm = floatval($_POST['altezza']);

        $altezza_m = $altezza_cm / 100;
        $bmi = ($altezza_m > 0) ? ($peso / ($altezza_m * $altezza_m)) : 0;
        $bmi_arrotondato = round($bmi, 2);

        // Ottimizzata la logica dei controlli di fascia
        if ($bmi_arrotondato < 18.5) {
            $classe = "sottopeso";
            $testo = "SOTTOPESO";
        } elseif ($bmi_arrotondato <= 24.99) {
            $classe = "normopeso";
            $testo = "NORMOPESO";
        } elseif ($bmi_arrotondato <= 29.99) {
            $classe = "sovrappeso";
            $testo = "SOVRAPPESO";
        } else {
            $classe = "obesita";
            $testo = "OBESITÀ";
        }

        // Mantenuto il fix fondamentale per la scrittura corretta del file log.csv
        if ($myfile = @fopen("log.csv", "a")) {
            $record = implode("; ", [
                date("d/m/Y"),
                date("G:i:s"),
                $_SERVER['REMOTE_ADDR'],
                $nome,
                $altezza_cm,
                $peso,
                $bmi_arrotondato,
                $testo
            ]) . ";\n";
            fwrite($myfile, $record);
            fclose($myfile);
        }
        ?>

        <h1>Il tuo BMI</h1>

        <div class="card <?= $classe ?>">

            <h2><?= $nome ?></h2>

            <p>Peso <?= $peso ?> kg &middot; Altezza <?= $altezza_cm ?> cm</p>

            <div class="bmi-numero"><?= $bmi_arrotondato ?></div>

            <div class="fascia-testo"><?= $testo ?></div>
            
        </div>

        <a href="index.php" class="link-indietro">Calcola di nuovo</a>

    <?php else: ?>

        <h1>Calcolatore BMI</h1>

        <div class="box-form">
            <form action="index.php" method="post">
                <div class="gruppo">
                    <label for="nome">Nome</label>
                    <input type="text" id="nome" name="nome" required>
                </div>

                <div class="gruppo">
                    <label for="peso">Peso (kg)</label>
                    <input type="number" id="peso" name="peso" step="0.1" required>
                </div>

                <div class="gruppo">
                    <label for="altezza">Altezza (cm)</label>
                    <input type="number" id="altezza" name="altezza" required>
                </div>

                <button type="submit">Calcola</button>
            </form>
        </div>

    <?php endif; ?>

</body>
</html>
