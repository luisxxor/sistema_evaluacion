<!DOCTYPE html>
<html lang="es">
    <head>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">
        <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
        <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    </head>
    <body>    
        <div class="container">
            <div class="row" style="height: 100vh; margin: 0">
                <div class="col offset-s0 s12 offset-m2 m8" id="maincontainer">
                    <div class="card">
                        <?php if(validation_errors()): ?>
                        <div class="card-panel red white-text">
                            <?php echo validation_errors(); ?>
                        </div>
                        <?php endif; ?>
                        <?php if(!empty($this->input->get('msg')) && $this->input->get('msg') == 1): ?>
                        <div class="card-panel red white-text">
                            Nombre de usuario o contraseña incorrecta
                        </div>
                        <?php endif; ?>
                        <?php echo form_open('users/dologin'); ?>
                            <div class="card-content">
                                <span class="card-title">Inicio de Sesión</span>
                                <div class="row">
                                    <div class="input-field col s12">
                                        <input id="username" type="text" name="username" class="validate" required>
                                        <label for="username" >Nombre de Usuario</label>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="input-field col s12">
                                        <input id="password" type="password" name="password" class="validate" required>
                                        <label for="password">Contraseña</label>
                                    </div>
                                </div>
                                <div class="card-actions">
                                    <button id="submitButton" class="btn waves-effect waves-light grey darken-3 right" value="submit" type="submit" name="action">Entrar
                                        <i class="material-icons right">send</i>
                                    </button>        
                                </div>
                            </div>
                        <?php echo form_close(); ?>
                    </div>
                </div>
            </div>
        </div>
        <style>
            #maincontainer {
                height: 100vh;
                display: flex;
                flex-direction: column;
                justify-content: center;
            }

            .card-actions {
                padding-bottom: 2em;
            }

            body {
                background: #232526;
                background: -webkit-linear-gradient(to left, #414345, #232526);
                background: linear-gradient(to left, #414345, #232526);
            }

            /* label color */
            .input-field label {
                color: #000!important;
            }
            /* label focus color */
            .input-field input[type=text]:focus + label {
                color: #000!important;
            }
            /* label underline focus color */
            .input-field input[type=text]:focus, .input-field input[type=password]:focus {
                border-bottom: 1px solid #000!important;
                box-shadow: 0 1px 0 0 #000!important;
            }
            /* valid color */
            .input-field input[type=text].valid, .input-field input[type=password].valid {
                border-bottom: 1px solid #000!important;
                box-shadow: 0 1px 0 0 #000!important;
            }
            /* invalid color */
            .input-field input[type=text].invalid, .input-field input[type=password].invalid {
                border-bottom: 1px solid #000!important;
                box-shadow: 0 1px 0 0 #000!important;
            }
            /* icon prefix focus color */
            .input-field .prefix.active {
                color: #000!important;
            }

        </style>
    </body>
</html>