<?php

require_once '../lib/req_apertura_sessione.php';

/*
  Copyright (C) 2015 Pietro Tamburrano
  Questo programma è un software libero; potete redistribuirlo e/o modificarlo secondo i termini della
  GNU Affero General Public License come pubblicata
  dalla Free Software Foundation; sia la versione 3,
  sia (a vostra scelta) ogni versione successiva.

  Questo programma è distribuito nella speranza che sia utile
  ma SENZA ALCUNA GARANZIA; senza anche l'implicita garanzia di
  POTER ESSERE VENDUTO o di IDONEITA' A UN PROPOSITO PARTICOLARE.
  Vedere la GNU Affero General Public License per ulteriori dettagli.

  Dovreste aver ricevuto una copia della GNU Affero General Public License
  in questo programma; se non l'avete ricevuta, vedete http://www.gnu.org/licenses/
 */


/* Programma per la visualizzazione dell'elenco delle tbl_classi. */

@require_once("../php-ini" . $_SESSION['suffisso'] . ".php");
@require_once("../lib/funzioni.php");

// istruzioni per tornare alla pagina di login

$tipoutente = $_SESSION["tipoutente"]; //prende la variabile presente nella sessione
if ($tipoutente == "")
{
    header("location: ../login/login.php?suffisso=" . $_SESSION['suffisso']);
    die;
}

$titolo = "Cancellazione presenza forzata";
$script = "";
stampa_head($titolo, "", $script, "SDMAP");
stampa_testata("<a href='../login/ele_ges.php'>PAGINA PRINCIPALE</a> - <a href='vispresenzeforzate.php'>ELENCO PRESENZE FORZATE</a> - $titolo", "", $_SESSION['nome_scuola'], $_SESSION['comune_scuola']);


//Connessione al server SQL
$con = mysqli_connect($db_server, $db_user, $db_password, $db_nome);
if (!$con)
{
    print("\n<h1> Connessione al server fallita </h1>");
    exit;
};

//Connessione al database
$DB = true;
if (!$DB)
{
    print("\n<h1> Connessione al database fallita </h1>");
    exit;
};

//Esecuzione query
$idalunno = stringa_html('idalunno');
$eli = "DELETE FROM tbl_presenzeforzate WHERE idpresenzaforzata=" . stringa_html('idpre');
$ris = eseguiQuery($con, $eli);
if (!($ris))
{
    print "<CENTER><FONT SIZE='+2'>\n Eliminazione non effettuata</FONT></CENTER>";
} else
{
    // print "<CENTER><FONT SIZE='+2'>\n Eliminazione effettuata</FONT></CENTER>";
    print "
                 <form method='post' id='formdoc' action='vispresenzeforzate.php'>
                     <input type='hidden' name='idalunno' value='$idalunno'>
                 </form> 
                 <SCRIPT language='JavaScript'>
                 {
                     document.getElementById('formdoc').submit();
                 }
                 </SCRIPT>";
}
stampa_piede("");
mysqli_close($con);

