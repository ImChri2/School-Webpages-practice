<?php
     session_start();
     //require_once ('lib/fpdf.php');
     $_SESSION["permit"]= "no";
?>


<!DOCTYPE html>
    <head>
        <meta content="utf-8" />
    </head>

    <body>
            <style>
            input
            {
                width: 20%;

            }
            </style>
        <center>
            <h1>CD</h1>
                <form method="post" >
                <h3>CD Anzahl: <br /><input type="number" name="cds" max="1000" min="1" required /> <br /> <br />
                        Vorname: <input type="text" name="vname" required />
                        Nachname: <input type="text" name="nname" required /> <br /><br />
                        Adresse: <br /><input type="text" name="address" placeholder="Musterstrasse 20" required /> <br /><br />
                        PLZ:    <br /><input type="text" name="plz" placeholder="40724" minlength="5" maxlength="5" required /> <br /><br />
                        Ort:    <br /><input type="text" name="place" placeholder="Düsseldorf" required /><br /> <br />
                    <input type="submit" name="submit" value="Submit!"/></h3>
                </form>
        </center>

        <br /><br /><br />
        <table border="1" style="margin-left: auto; margin-right: auto;">
        <tbody><tr>
        <td><b>Anzahl der CD's</b> </td>
        <td><b>Rabatt pro CD</b> </td>
        <td><b>Versandkosten in €</b> </td>
        </tr>
        <tr>
        <td>1-9</td> 
        <td align="right">keine Lieferung</td>
        <td align="right">kein Versand</td>
        </tr>
        <tr>
        <td>ab 10 </td>
        <td align="right">0%</td>
        <td align="right">4,00</td>
        </tr>
        <tr>
        <td>ab 50</td>
        <td align="right">8%</td>
        <td align="right">8,00</td>
        </tr>
        <tr>
        <td>ab 100</td>
        <td align="right">12%</td>
        <td align="right">15,00</td>
        </tr>
        <tr>
        <td>ab 500</td> 
        <td align="right">25%</td>
        <td align="right">kostenlos</td>
        </tr>
        </tbody></table>
        </center>

        <?php 
           
            if(isset($_POST['submit']) == true)
            {
                $cd = intval(trim($_POST['cds']));
                $vname = trim($_POST['vname']);
                $nname = trim($_POST['nname']);
                $address = trim($_POST['address']);
                $plz = intval($_POST['plz']);
                $place = trim($_POST['place']);
                $error = false;
                
                $errormessage = array();
                $costs = 10;

                if($vname == "")
                {
                    $errormessage[] = "Bitte Geben Sie Ihren Vornamen an.";
                    $error = true; 
                }
                if($nname == "")
                {
                    $errormessage[] = "Bitte Geben Sie Ihren Vornamen an.";
                    $error = true; 
                }
                if(!is_numeric($plz))
                {
                    $errormessage[] = "Die Postleitzahl darf keine Buchstaben enthalten.";
                    $error = true; 
                }
                if($error == true)
                {   
                    echo"<center><h1>Fehlernachricht</h1></center>";
                    foreach($errormessage as $errors)
                    {
                   
                        echo"<center><h3>".$errors."</h3></center>";
                    }
                    session_unset();   
                    session_destroy();
                    exit;
                }
                if($error == false)
                {       
                    $_SESSION['permit'] = 1;
                    if($cd >= 1)
                    {
                        $sending = "Keine Versand";
                        $discount = 0;
                        $discounttxt = 0;
                    }
                    if($cd >= 10)
                    {
                        $sending = 4;
                        $discount = 1;
                        $discounttxt = 0;
                    }                
                    if($cd >= 50)
                    {
                        $sending = 8;
                        $discount = 0.92;
                        $discounttxt = 8;
                    }
                    if($cd >= 100)
                    {
                        $sending = 15;
                        $discount = 0.88;
                        $discounttxt = 12;
                    }
                    if($cd >= 500)
                    {
                        $sending = 0;
                        $discount = 0.75;
                        $discounttxt = 25;
                    }

                        $netto = ($costs * $cd + $sending) * $discount;
                        $MwSt = $netto * 0.19;
                        $brutto = $netto * 1.19;
                        $skonto = Round($brutto * 0.03, 2);
                        echo"<br /><br /><br /><center><h2>Danke für ihre Bestellung ".$vname." ".$nname.":</h2><br/>";
                        echo "<table border='1' text-decoration='center'><tr><th>Anzahl</th><th>EP</th><th>Rabatt</th><th>Nettobetrag</th><th>Versand</th><th>MwSt</th><th>Gesamtbetrag</th><th>Skontobetrag</th></tr>";
                        echo"<tr><td>".$cd."</td>"."<td>10,00</td><td>".$discounttxt."%</td><td>".$netto.",00</td><td>".$sending."</td><td>".$MwSt."</td><td>".$brutto."</td><td>".$skonto."</td></tr></table></center>";
                        
                        $_SESSION["vname"] = $vname;
                        $_SESSION["nname"] = $nname;
                        $_SESSION["cd"] = $cd;
                        $_SESSION["discounttxt"] = $discounttxt;
                        $_SESSION["netto"] = $netto;
                        $_SESSION["sending"] = $sending;
                        $_SESSION["MwSt"] = $MwSt;
                        $_SESSION["brutto"] = $brutto;
                        $_SESSION["skonto"] = $skonto;#
                        $_SESSION["address"] = $address;
                        $_SESSION["plz"] = $plz;
                        $_SESSION["place"] = $place;


                        if($_SESSION['permit'] == 1)
                        {
                            echo"<br /><center><a href='rechnung.php'><button>PDF file</button></a></center>";
                        }                    
                }
            
            }
        
        ?>
    </body>
</html>


