<?php
include "fonctions.php";
include "ChiffresEnLettres.php";
$use=new fonctions();
if(isset($_POST["form"]))
{
    if($_POST["form"]=="auth")
    {
        if (isset($_POST["uname"])and isset($_POST["pwd"]))
        {
            $uname=$_POST["uname"];
            $upass=$_POST["pwd"];
            $u=$use->Logedin($uname,$upass);
            $lgdin=false;
            if(!empty($uname)&&!empty($upass))
            {
                foreach ($u as $t)
                {
                    $lgdin=true;
                    if(($t['uname']===$uname) &&(($t['pwd']===$upass)))
                    {

                        session_start();
                        $_SESSION['cin']=$t["cin"];
                        header('location:../index.php');

                    }
                }
            }
            if($lgdin==false)
            {
                echo '<body onLoad="alert(\'Membre non reconnu...\')">';
                // puis on le redirige vers la page d'accueil
                echo '<meta http-equiv="refresh" content="0;URL=../login.php">';
            }


        }
        else
        {

        }
    }
    if($_POST["form"]=="addP")
    {
        session_start();
        if (isset($_POST["num_ass"])and isset($_POST["nom_ass"])and isset($_POST["pre_ass"])and isset($_POST["qualite"])and isset($_POST["nom"])and isset($_POST["pre"])and isset($_POST["tel"])and isset($_POST["diag"])and isset($_POST["cin_kine"]))
        {
            $use->addPatient($_POST["num_ass"],$_SESSION["cin"],strtoupper($_POST["nom_ass"]),strtoupper($_POST["pre_ass"]),$_POST["qualite"],strtoupper($_POST["nom"]),strtoupper($_POST["pre"]),$_POST["diag"],$_POST["tel"]);
            $pp=$use->getLastPatient();
            $id='';
            foreach ($pp as $p)
            {
                $id=$p["id"];
            }
            header('location:../pec.php?n_ass='.$id);
        }
    }
    if($_POST["form"]=="deleteP")
    {
        session_start();
        if (isset($_POST["num_ass"]))
        {
            $use->deletePatient($_POST["num_ass"]);
            header('location:../patients.php');
        }
    }
    if($_POST["form"]=="editP")
    {
        session_start();
        if (isset($_POST["num_ass"])and isset($_POST["nom_ass"])and isset($_POST["pre_ass"])and isset($_POST["qualite"])and isset($_POST["nom"])and isset($_POST["pre"])and isset($_POST["tel"])and isset($_POST["diag"]))
        {
            $use->editPatient($_POST["num_ass"],$_SESSION["cin"],strtoupper($_POST["nom_ass"]),strtoupper($_POST["pre_ass"]),$_POST["qualite"],strtoupper($_POST["nom"]),strtoupper($_POST["pre"]),$_POST["diag"],$_POST["tel"]);
            header('location:../patients.php');
        }
    }
    if($_POST["form"]=="addPEC")
    {
        session_start();

        if(isset($_POST['num_dec'])and isset($_POST["num_ass"]) and isset($_POST["nbs"]) and isset($_POST["nb_p_s"]) and isset($_POST["date_deb"])and isset($_POST["pu"])and isset( $_POST["id"]))
        {


            $date = str_replace('/', '-',  $_POST["jr".$_POST["nbs"]]);
            $use->addDec($_POST["num_ass"],$_POST['num_dec'],$_POST["nbs"],$_POST["nb_p_s"],$_POST["date_deb"],$_POST["pu"],date('Y-m-d', strtotime($date)),$_POST["id"]);
            echo $_POST["jr".$_POST["nbs"]];
            $y=date("Y");
            $s=$use->nbrF($y);
            $s=$s+1;
            if(strlen($s)==1)
            {
                $s='0'.$s;
            }
            $nfac=$s.'/'.$y;
            $tt_ttc=$_POST["nbs"]*$_POST["pu"];
            $tt_ht=$tt_ttc-($tt_ttc*0.07);
            $mtva=$tt_ttc-$tt_ht;
            $ch=new ChiffreEnLettre();
            $ttt=(int)$tt_ttc;
            $mt_let=$ch->Conversion($ttt);
            $use->addFac($_POST['num_dec'],$nfac,$_POST["date_deb"],$_POST["jr".$_POST["nbs"]],$_POST["pu"],$tt_ht,7,$tt_ttc,$mt_let,$mtva);
            for($i=1;$i<($_POST["nbs"]+1);$i++)
            {
                $use->addCon($_POST['num_dec'],$i,'nhar mel nharat',$_POST["jr".$i],$_POST["date_deb"]);
            }
            header('location:../pec.php?n_ass='.$_POST["id"]);
        }
    }
    if($_POST["form"]=="editPEC")
    {
        session_start();

        if(isset($_POST['num_dec'])and isset($_POST["num_ass"]) and isset($_POST["nbs"]) and isset($_POST["nb_p_s"]) and isset($_POST["date_deb"])and isset($_POST["pu"])and isset($_POST["id"]))
        {

            $use->editDec($_POST['num_dec'],$_POST["nbs"],$_POST["nb_p_s"],$_POST["date_deb"],$_POST["pu"]);

            $y=date("Y");
            $s=$use->nbrF($y);
            $s=$s+1;
            $s='0'.$s;
            $nfac=$s.'/'.$y;
            $tt_ttc=$_POST["nbs"]*$_POST["pu"];
            $tt_ht=$tt_ttc-($tt_ttc*0.07);
            $mtva=$tt_ttc-$tt_ht;
            $ch=new ChiffreEnLettre();
            $ttt=(int)$tt_ttc;
            $mt_let=$ch->Conversion($ttt);
            $use->editFac($_POST["num_dec"],$_POST["date_deb"],$_POST["jr".$_POST["nbs"]],$_POST["pu"],$tt_ht,7,$tt_ttc,$mt_let,$mtva);

            for($i=1;$i<($_POST["nbs"]+1);$i++)
            {

                $use->editCon($_POST['num_dec'],$i,'nhar mel nharat',$_POST["jr".$i],$_POST["date_deb"]);
            }
            header('location:../pec.php?n_ass='.$_POST["id"]);
        }
    }
    if($_POST["form"]=="deletePEC")
    {
        if (isset($_POST['num_ass'])and isset($_POST['num_dec']))
        {
            $use->deletePEC($_POST['num_ass'],$_POST['num_dec']);
            header('location:../pec.php?n_ass='.$_POST["id"]);
        }
    }
    if($_POST["form"]=="creatBord")
    {
        if(!empty($_POST["n_dec"]))
        {
            $nbrFac=count($_POST["n_dec"]);
            $total=0;
            $year=date('Y');
            $s=$use->nbrB($year);
            $s=$s+1;
            $s='0'.$s;
            if(strlen($s)==2)
            {
                $s='0'.$s;
            }
            $nBor=$s.'/'.$year;
            foreach ($_POST["n_dec"] as $numD)
            {
                $fac=$use->getFact($numD);
                foreach ($fac as $f)
                {
                    $total=$total+$f["total_ttc"];
                }
                $use->addDetBor($numD,$nBor);
                $use->edit_dec_bord($numD);
            }
            $ttt=(int)$total;
            $use->premierligne($s,$year,$nbrFac,$total);
            $drouj=[];
            foreach ($_POST["n_dec"] as $numD)
            {
                $fac=$use->getFact($numD);

                foreach ($fac as $f)
                {
                    array_push($drouj,$f["n_fac"]);

                }
            }
            for($count=0;$count<count($drouj);$count++)
            {
                $makloub=substr($drouj[$count],3);
                $makloub=$makloub.substr($drouj[$count],0,2);
                $drouj[$count]=$makloub;
            }
            sort($drouj);
            for ($count=0;$count<count($drouj);$count++)
            {
                $drouj[$count]=substr($drouj[$count],4).'/'.substr($drouj[$count],0,4);
            }
            for($count=0;$count<count($drouj);$count++)
            {
                $fatoura=$use->getFact(null,$drouj[$count]);
                foreach ($fatoura as $ssss)
                {
                    $dec=$use->getDec(null,$ssss["n_decision"]);
                    foreach ($dec as $dd)
                    {
                        $con=$use->getCon($ssss["n_decision"],$dd["nbr_s"]);
                        foreach ($con as $cc)
                        {
                            $use->lignefact($year,$s,$ssss["n_fac"],$ssss["n_decision"],$dd["n_assure"],$dd["nb_s_sem"],$dd["nbr_s"],$dd["dat_deb"],$cc["date_jour"],$ssss["total_ttc"],$ssss["total_ht"],$ssss["tva"],$ssss["mnt_tva"],$ssss["datee"]);
                        }

                    }
                }
            }

            $ch=new ChiffreEnLettre();
            $ttletre=$ch->Conversion($ttt);
            $use->creatBord($nBor,$total,$ttletre);
        }

        var_dump($drouj);
        header('location:../bord.php');
    }
    if($_POST["form"]=="deleteB")
    {
        if (isset($_POST['num_bor']))
        {
            $use->deletBord($_POST['num_bor']);
            header('location:../bord.php');
        }
    }
    if($_POST["form"]=="jrf")
    {
        if (isset($_POST['dated'])and isset($_POST['datef']))
        {
            $use->addjrf($_POST['dated'],$_POST['datef']);
            header('location:../index.php');
        }
    }
    if($_POST["form"]=="sjrf")
    {
        if (isset($_POST['dates']))
        {
            $use->supjrf($_POST['dates']);
            header('location:../index.php');
        }
    }
}
else
{
    if($_GET["form"]=="checkAss")
    {
        if(isset($_GET["q"])and isset($_GET["y"]) and isset($_GET["z"]))
        {
            $d=$use->check_num_ass_avalibility($_GET["q"],$_GET["y"],$_GET["z"]);
            if($d->rowCount()>0)
            {
                $pp=$use->getLastPatient();
                $id='';
                foreach ($pp as $p)
                {
                    $id=$p["id"];
                }
                echo '<span class="text-danger"> <i class="zmdi zmdi-close-circle"></i> Patient existe deja! <a href="pec.php?n_ass='.$id.'">aller a sa page </a></span>';
            }
            else
            {
                echo "<span class='text-success'> <i class=\"zmdi zmdi-badge-check\"></i> Patient valable</span>";
            }
        }
    }
    if($_GET["form"]=="checkDec")
    {
        if(isset($_GET["q"]))
        {
            $d=$use->check_num_dec_avalibility($_GET["q"]);
            if($d>0)
            {
                echo '<span class="text-danger"> <i class="zmdi zmdi-close-circle"></i> Num. Decision existe deja! </a></span>';
            }
            else
            {
                echo "<span class='text-success'> <i class=\"zmdi zmdi-badge-check\"></i> Num. decision valable</span>";
            }
        }
    }
}


