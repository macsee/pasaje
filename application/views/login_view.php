
<style>
    body {
        padding-top: 40px;
        padding-bottom: 40px;
        //background-color: #eee;
    }

    .form-signin {
        max-width: 330px;
        padding: 15px;
        margin: 0 auto;
    }
    .form-signin .form-signin-heading,
    .form-signin .checkbox {
        margin-bottom: 10px;
    }
    .form-signin .checkbox {
        font-weight: normal;
    }
    .form-signin .form-control {
        position: relative;
        height: auto;
        -webkit-box-sizing: border-box;
        -moz-box-sizing: border-box;
        box-sizing: border-box;
        padding: 10px;
        font-size: 16px;
    }
    .form-signin .form-control:focus {
        z-index: 2;
    }

    #error_msg {
        color: red;
        font-size: 14px;
        text-align: center;
    }
    /*.form-signin input[type="text"] {
        margin-bottom: -1px;
        border-bottom-right-radius: 0;
        border-bottom-left-radius: 0;
    }
    .form-signin input[type="password"] {
        margin-bottom: 10px;
        border-top-left-radius: 0;
        border-top-right-radius: 0;
    }*/
</style>

<div class="container">

    <form class="form-signin" method="post" action="<?php echo base_url('index.php/main/check_login')?>">
        <div class = "form-group">
            <h2 id = "titulo" class="form-signin-heading">Iniciar Sesión</h2>
        </div>
        <div id = "usuario" class = "form-group">
            <!-- <label>Usuario</label> -->
            <input type="text" name="usuario" class="form-control" placeholder="Usuario" required autofocus autocomplete="off">
        </div>
        <div class = "form-group">
            <!-- <label>Contraseña</label> -->
            <input type="password" name="password" class="form-control" placeholder="Contraseña" required autocomplete="off">
        </div>
        <div id = "repeat_pass" class = "form-group" style = "display:none">
            <!-- <label>Usuario</label> -->
            <input type="password" name="repeat_password" class="form-control" placeholder="Repetir Contraseña" required autofocus autocomplete="off">
        </div>
        <div class = "form-group">
            <p id = "error_msg"></p>
        </div>
        <button id = "ingresar" class="btn btn-lg btn-primary btn-block" onclick="return do_login(event);" type="submit">Ingresar</button>
        <button id = "cambiar" class="btn btn-lg btn-primary btn-block" onclick="return cambiar_pass(event);" type="submit" style = "visibility:hidden">Cambiar</button>
    </form>

</div> <!-- /container -->

<script type="text/javascript">
    var base_url_log = "<?php echo base_url('index.php/login')?>";

    function do_login(event) {

        form = $(".form-signin");
        event.preventDefault();

        $.ajax({
            type: "POST",
            url: base_url_log+'/check_login',
            data: form.serialize(),
            // dataType: "json",
            success: function(response) {
                console.log(response);
                if (response != null) {
                    if (response.password == response.usuario) {
                        $("#cambiar").css('visibility', 'visible');
                        $("#ingresar").remove();

                        $("#usuario").css('display','none');

                        $("input[name='password']").val("");
                        $("input[name='password']").focus();
                        $("input[name='password']").attr('placeholder','Nueva Contraseña');
                        $("#repeat_pass").css('display', 'block');

                        $("#titulo").html("Cambiar Contraseña");
                        $("#error_msg").html("");
                    }
                    else {
                        set_credentials(response);
                    }
                }
                else {
                    $("#error_msg").html("Nombre de usuario y/o contraseña incorrectos");
                }
            }
        });
    }

    function cambiar_pass(event) {

        form = $(".form-signin");
        event.preventDefault();

        pass_nuevo = $("input[name='password']").val();
        pass_re = $("input[name='repeat_password']").val();

        if (pass_nuevo != pass_re) {
            $("#error_msg").html("Las contraseñas son diferentes");
        }
        else {
            $("#error_msg").html("");
            $.ajax({
                type: "POST",
                url: base_url_log+'/change_pass',
                data: form.serialize(),
                dataType: "json",
                success: function(response) {
                    if (response != null) {
                        set_credentials(response);
                    }
                    else {
                        $("#error_msg").html("Hubo algún error. Por favor intente más tarde");
                    }
                },
                error: function(msg) {
                    $("#error_msg").html("Hubo algún error. Por favor intente más tarde");
                }
            });
        }
    }

    function set_credentials(values) {

        $.ajax({
            type: "POST",
            url: base_url_log+'/set_credentials',
            data: values,
            // dataType: "json",
            success: function(response) {
                // console.log(response);
                window.location = base_url+'/main/'+response;
            },
            error: function(msg) {
                $("#error_msg").html("Hubo algún error. Por favor intente más tarde");
            }
        });

    }
</script>
