<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title></title>
  </head>
  <body>
  <style media="screen">
    table {
      border: 1px solid;
      /*border-collapse: collapse;*/
      font-size: 14px;
    }

    tr {
      border: 1px solid;
    }

    td {
      border: 1px solid;
    }
  </style>

    <?php
      function convenios() {

        // $url = 'https://www.amr.org.ar/gestion/webServices/autorizador/test/profesiones';
        $login = '38026';
        $password = 'IUUIASUX';
        $url = 'https://www.amr.org.ar/gestion/webServices/autorizador/v3/convenios';
        $ch = curl_init();
          curl_setopt($ch, CURLOPT_URL,$url);
          curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
          curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
          curl_setopt($ch, CURLOPT_USERPWD, "$login:$password");
          $result = curl_exec($ch);
        curl_close($ch);

        return $result;
      }

      function autorizadas($fecha) {

        // $url = 'https://www.amr.org.ar/gestion/webServices/autorizador/test/profesiones';
        $login = '38026';
        $password = 'IUUIASUX';
        $url = 'https://www.amr.org.ar/gestion/webServices/autorizador/v3/ambulatorio/realizadas?codigoConvenio=5&fecha='.$fecha;
        $ch = curl_init();
          curl_setopt($ch, CURLOPT_URL,$url);
          curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
          curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
          curl_setopt($ch, CURLOPT_USERPWD, "$login:$password");
          $result = curl_exec($ch);
        curl_close($ch);

        return $result;
      }

      // echo autorizadas("2016/06/08");
      // echo convenios();
      //
      // $fecha = "2016/06/";
      // echo "<table>";
      // for ($i=1; $i <= 31; $i++) {
      //   $date = $fecha.$i;
      //   $array = json_decode(autorizadas($date))->autorizadas;
      //   foreach ($array as $key => $value) {
      //     echo "<tr>";
      //       echo "<td>".$date."</td>";
      //       echo "<td>".$value->baseAmbulatorio->afiliado->nombre."</td>";
      //       echo "<td>".$value->baseAmbulatorio->afiliado->ID."</td>";
      //       echo "<td>".$value->baseAmbulatorio->afiliado->convenio->nombre."</td>";
      //       echo "<td>".explode(" - ",$value->baseAmbulatorio->afiliado->plan->nombre)[0]."</td>";
      //       echo "<td>".$value->prestacionesRealizadas[0]->prestacionSolicitadaBase->nomencladorBase->Nombre."</td>";
      //       echo "<td>".$value->prestacionesRealizadas[0]->prestacionSolicitadaBase->nomencladorBase->ID."</td>";
      //     echo "</tr>";
      //   }
      // }
      // echo "</table>";

    ?>

  </body>
  <input type="date" name="fecha_inico" value="">
  <input type="date" name="fecha_fin" value="">
  <input type="button" name="btn_buscar" value="Buscar">
  <div class="resultados">

  </div>
  <script type="text/javascript">

    $(document).ready(function () {
        get_recientes();
    });

    function get_recientes() {

      $.ajax({
        url: base_url+"/main/convenios",
        method: 'GET',
        dataType: 'json',
        //data: YourData,
        success: function(data){
          console.log('succes: '+data);
        }
      });

    }

  </script>
</html>
