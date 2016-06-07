<style>
    /*body {margin: 0px}*/
    .celda {
        width: 30px;
        height: 30px;
        cursor: pointer;
    }

    .celda_vacia {
        background-color: #EAEAEA;
    }

    .celda_low {
        background-color: rgba(69, 183, 11, 0.70);
    }

    .celda_med_low {
        background-color: rgba(11, 199, 218, 0.70);
    }

    .celda_medium {
        background-color: rgba(215, 218, 11, 0.70);
    }

    .celda_high {
        background-color: rgba(218, 135, 11, 0.70);
    }

    .celda_full {
        background-color: rgba(218, 11, 11, 0.70);
    }


    .highlight {
        font-weight: 700;
        font-size: 20px;
    }

    .cabecera {
        height: 25px;
    }

    .dia_semana {
        font-size: 18px;
        background-color: transparent;
    }

    td {
        width: 30px;
        height: 30px;
        text-align: center;
        border: 2px solid white;
        /*background-color: #f5f5f5;*/
    }

    td:hover {background-color: #f5f5f5}

    th {
        text-align: center;
        font-size: 18px!important;
    }
</style>

<div class="container-fluid">
    <?php echo $calendario; ?>
</div>
