<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tabela INSS/IRPF</title>
    <link rel="stylesheet" href="./css/style.css">
  </head>

  <body>
    <div id="title">Cálculo de Salário Líquido</div>
    <form class="ler" action="" method="post">
      <input type="text" name="nome" id="a01" required placeholder=" Nome">
      <input type="text" name="salariobruto" id="a02" required placeholder=" Salário Bruto">
      <input type="number" name="dependentes" id="a03" required placeholder=" N° de Dependentes">
      <button type="submit" name="Envio">Enviar</button>
    </form>

    <?php
      error_reporting(0);
      ini_set(“display_errors”, 0 );
      $x=0;
      $y=0;
    ?>

    <?php
      if(isset($_POST['Envio'])&&$_POST['nome']!=NULL){
        $nome = $_POST['nome'];
        $bruto = $_POST['salariobruto'];
        $numDependentes = $_POST['dependentes'];
        $aliquotaINSS = 0;

        if($bruto <= 1045){
          $aliquotaINSS = 7.5;
        }

        else if($bruto >= 1045.01 && $bruto <= 2089.6){
          $aliquotaINSS = 9;
        }

        else if($bruto >= 2089.61 && $bruto <= 3134.4){
          $aliquotaINSS = 12;
        }

        else if($bruto >= 3134.41 && $bruto <= 6101.06){
          $aliquotaINSS = 14;
        }

        $salarioBase = $bruto - (($aliquotaINSS * $bruto) / 100);

        if($salarioBase <= 1903.98){
          $aliquota = 0;
          $parcelaDedução = 0;
        }

        else if($salarioBase >= 1903.99 && $salarioBase <= 2826.65){
          $aliquota = 7.5;
          $parcelaDedução = 142.8;
        }

        else if($salarioBase >= 2826.66 && $salarioBase <= 3751.05){
          $aliquota = 15;
          $parcelaDedução = 354.8;
        }

        else if($salarioBase >= 3751.06 && $salarioBase <= 4664.68){
          $aliquota = 22.5;
          $parcelaDedução = 636.13;
        }

        else if($salarioBase > 4664.48){
          $aliquota = 27.5;
          $parcelaDedução = 869.36;
        }

        $salaLiquido = $salarioBase - ((($aliquota * $salarioBase) / 100) - $parcelaDedução);
        $deduçãoDependentes = ($numDependentes * 189.59);
        $salaLiquido = $salaLiquido - $deduçãoDependentes;
        $totalDescontos = ($bruto - $salaLiquido);
        $inputArquivo = "$nome|$bruto|$aliquotaINSS|$aliquota|$parcelaDedução|$deduçãoDependentes|$totalDescontos|$salaLiquido\n";
        $arquivo = fopen('dados.txt','a+');
        fwrite($arquivo, $inputArquivo);
        fclose($arquivo);
        $arquivo = fopen("dados.txt",'r');
        while(true) {
          $Valores[$x] = fgets($arquivo);
          $ValoresTab = explode('|',$Valores[$x]);
          $nomeTab[$y] = $ValoresTab[0];
          $brutoTab[$y] = $ValoresTab[1];
          $alINSS[$y] = $ValoresTab[2];
          $alIRRF[$y] = $ValoresTab[3];
          $parTab[$y] = $ValoresTab[4];
          $depTab[$y] = $ValoresTab[5];
          $descTab[$y] = $ValoresTab[6];
          $liqTab[$y] = $ValoresTab[7];
          $brutoTab[$y] = number_format($brutoTab[$y], 2, '.', '');
          $alINSS[$y] = number_format($alINSS[$y], 2, '.', '');
          $alIRRF[$y] = number_format($alIRRF[$y], 2, '.', '');
          $parTab[$y] = number_format($parTab[$y], 2, '.', '');
          $depTab[$y] = number_format($depTab[$y], 2, '.', '');
          $descTab[$y] = number_format($descTab[$y], 2, '.', '');
          $liqTab[$y] = number_format($liqTab[$y], 2, '.', '');
          if ($Valores[$x] == null) break;
          $x++;
          $y++;
        }
      }
        fclose($arquivo);
     ?>
     <table class="tab">
       <tr>
         <th>Nome</th>
         <th>Salário Bruto</th>
         <th>INSS</th>
         <th>IRPF</th>
         <th>parcela IRPF</th>
         <th>$ Dependentes</th>
         <th>Desconto</th>
         <th>Salário Líquido</th>
       </tr>

     <?php
     if ($_POST['nome']!=NULL) {
      for ($i=0; $i < $y; $i++) {
         echo "<tr> <td>$nomeTab[$i]</td> <td>R$$brutoTab[$i]</td> <td>$alINSS[$i]%</td> <td>$alIRRF[$i]%</td> <td>R$$parTab[$i]</td> <td>R$$depTab[$i]</td> <td>R$$descTab[$i]</td> <td>R$$liqTab[$i]</td><tr>";
      }
      $y++;
     }
     ?>
    </table>
  </body>
</html>
