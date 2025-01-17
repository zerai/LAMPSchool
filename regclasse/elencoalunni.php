<?php

require_once '../lib/req_apertura_sessione.php';

/*
  Copyright (C) 2015 Pietro Tamburrano
  Questo programma è un software libero; potete redistribuirlo e/o modificarlo secondo i termini della
  GNU Affero General Public License come pubblicata
  dalla Free Software Foundation; sia la versione 3,
  sia (a vostra scelta) ogni versione successiva.

  Questo programma é distribuito nella speranza che sia utile
  ma SENZA ALCUNA GARANZIA; senza anche l'implicita garanzia di
  POTER ESSERE VENDUTO o di IDONEITA' A UN PROPOSITO PARTICOLARE.
  Vedere la GNU Affero General Public License per ulteriori dettagli.

  Dovreste aver ricevuto una copia della GNU Affero General Public License
  in questo programma; se non l'avete ricevuta, vedete http://www.gnu.org/licenses/
 */

require_once '../php-ini' . $_SESSION['suffisso'] . '.php';
require_once '../lib/funzioni.php';
// require_once '../lib/ db / query.php';
//$lQuery = LQuery::getIstanza();

$con = mysqli_connect($db_server, $db_user, $db_password, $db_nome) or die("Errore durante la connessione: " . mysqli_error($con));

// istruzioni per tornare alla pagina di login se non c'è una sessione valida

$tipoutente = $_SESSION["tipoutente"]; //prende la variabile presente nella sessione

if ($tipoutente == "")
{
    header("location: ../login/login.php?suffisso=" . $_SESSION['suffisso']);
    die;
}


$idclasse = stringa_html('idclasse');
$data= stringa_html('data');


$titolo = "Elenco alunni senza presenza forzata";
$script = "";
stampa_head($titolo, "", $script, "MSPD");


print "<center><B>Elenco alunni della " . decodifica_classe($idclasse, $con) . "</B><br><br></center>";
// prelevamento dati alunno
// $rs = $lQuery->selectstar('tbl_alunni', 'idalunno=?', array($codalunno));
$query = "select * from tbl_alunni where idclasse=$idclasse order by cognome, nome, datanascita";
$rs = eseguiQuery($con, $query);
$esistono = false;
if (mysqli_num_rows($rs) > 0)
{
    print "<table align='center' border='1'><tr class='prima'><td>N.</td><td>Cognome</td><td>Nome</td><td>Data nascita</td><td>Cod. Fisc.</td><td>Funz.</td><td>Aut.<br>usc.<br>ant.</td>";
    if (verifica_classe_coordinata($_SESSION['idutente'], $idclasse, $con))
        {
            print "<td>Telefoni genitori</td>";
            print "<td>Email genitori</td>";
        }
    print "</tr>";
    $cont = 1;
    while ($rec = mysqli_fetch_array($rs))
    {
        print "<tr><td>$cont</td><td>" . $rec['cognome'] . "</td><td>" . $rec['nome'] . "</td><td>" . data_italiana($rec['datanascita']) . "</td><td>" . $rec['codfiscale'] . "</td>";

        if ($rec['idalunno'] == estraiAprifila1($idclasse, $con) | $rec['idalunno'] == estraiAprifila2($idclasse, $con))
            print "<td>A.F.</td>";
        elseif ($rec['idalunno'] == estraiChiudifila1($idclasse, $con) | $rec['idalunno'] == estraiChiudifila2($idclasse, $con))
            print "<td>C.F.</td>";
        else
            print "<td></td>";
        if ($rec['autuscitaantclasse'])
            print "<td><b>S</b></td>";
        elseif (maggiorenne($rec['datanascita']))
            print "<td><b>MAGG.</b></td>";
        else
            print "<td><b>N</b></td>";
        $cont++;
        if (verifica_classe_coordinata($_SESSION['idutente'], $idclasse, $con))
        {
            print "<td>".$rec['telefono']." ".$rec['telcel']."</td>";
            print "<td>".$rec['email']." ".$rec['email2']."</td>";
        }
    }
} else
    print "<BR><br><b><i><center>Nessun alunno presente!</b></i></center>";
mysqli_close($con);

