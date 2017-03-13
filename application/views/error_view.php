<!-- <meta http-equiv="refresh" content="3;url=<?php echo base_url('index.php/login/logout')?>"/> -->
<h3 style ="text-align:center;margin-top:100px;text-style:italic">
    No tiene permiso para acceder a esta seccion.<br>
    Redireccionando en <span class="seconds"></span>
</h3>
<script type="text/javascript">
    var url = "<?php echo $url?>";
    $(document).ready(function() {
        var counter = 5;
        var interval = setInterval(function() {
            $('.seconds').html(counter);
            counter--;
            if (counter == 0) {
                location.href = url;
            }
        }, 1000);
    });

</script>
