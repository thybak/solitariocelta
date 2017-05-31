@extends('user.portada')
@section('titulo', 'Menú de juego')
@section('cuerpo')
    <script src="{{ URL::asset('js/solitarioCelta-iecompatible.js') }}"></script>
    <script type="text/javascript">
        var solitario = new SolitarioCelta(false, new Array([], []));
    </script>
    <form class="ui fluid form">
        <input type="hidden" id="id" value="{{$usuarioAuth->id}}">
        <div class="ui secondary pointing menu">
            <div class="ui container">
                <div class="item">
                    <h3><i class="options icon"></i> @yield('titulo')</h3>
                </div>
                <a class="item" onclick="utils.recuperarRegistro('/api/matches/user/' + $('#id').val(),{}, solitario.recuperarEstado)">
                    <i class="download icon"></i> Recuperar estado
                </a>
                <a class="item" onclick="solitario.construirTableroPersonalizado()">
                    <i class="move icon"></i> Situar
                </a>
                <a class="item" onclick="solitario.comenzarJuego()">
                    <i class="play icon"></i> Empezar
                </a>
                <a class="item" id="guardarEstado" onclick="solitario.guardarEstado()">
                    <i class="save icon"></i> Guardar estado
                </a>
                <a class="item" id="registroPuntuacion" onclick="solitario.registrarPuntuacion()">
                    <i class="save icon"></i> Registrar puntuación
                </a>
            </div>
        </div>
        <div class="ui grid centered stackable">
            <div class="four wide column mgn-top-3"></div>
            <div class="eight wide column mgn-top-3" id="tablero">
                <h2>¡Bienvenido {{$usuarioAuth->nombreUsuario}}!</h2>
                <p>¿Por qué no empiezas a jugar?</p>
            </div>
            <div class="four wide column mgn-top-3"></div>
            <div class="four wide column mgn-top-3">
                <div class="item" id="modoJuego">
                    <div class="ui visible message">
                        <div class="header">
                            <i class="info circle icon"></i> Modo de juego activo
                        </div>
                        <div class="ui divider"></div>
                        <p>Para jugar:</p>
                        <ol class="list">
                            <li>Debes seleccionar una bola.</li>
                            <li>Pulsar en un hueco horizontal o vertical con una bola de por medio.</li>
                            <li>Ganas si te quedas con una sola bola en el tablero.</li>
                        </ol>
                    </div>
                </div>
                <div class="item" id="modoSituar">
                    <div class="ui visible message">
                        <div class="header">
                            <i class="info circle icon"></i> Modo de personalización activo
                        </div>
                        <div class="ui divider"></div>
                        <p>Para editar:</p>
                        <ul class="list">
                            <li>Puedes insertar una bola haciendo click en un hueco.</li>
                            <li>O bien quitar dicha bola haciendo click sobre ella.</li>
                        </ul>
                    </div>
                </div>
                <div class="item" id="modoBienvenida">
                    <div class="ui visible message">
                        <div class="header">
                            <i class="info circle icon"></i> ¡Comienza a jugar!
                        </div>
                        <div class="ui divider"></div>
                        <ul class="list">
                            <li>Configura la partida a tu gusto.</li>
                            <li>Dale al botón de jugar para empezar a probar tu ingenio.</li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="four wide column mgn-top-3">
                <div class="ui segment">
                    <h4 class="ui header">Configuración del juego</h4>
                    <div class="ui divider"></div>
                    <div class="field">
                        <table class="ui celled table">
                            <tr>
                                <td>
                                    <div class="field txt-center">
                                        <label for="huecoCentral">Hueco central</label>
                                        <input type="radio" id="huecoCentral" name="hueco" checked/>
                                    </div>
                                </td>
                                <td>
                                    <div class="field txt-center">
                                        <label for="huecoAleatorio">Hueco aleatorio</label>
                                        <input type="radio" id="huecoAleatorio" name="hueco"/>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2">
                                    <div class="field txt-center">
                                        <label for="txtMaxSegundos">Tiempo máximo (segundos)</label>
                                        <input type="text" id="txtMaxSegundos" name="txtMaxSegundos" maxlength="5"
                                               class="w25" onkeydown="return soloNumeros(event)"/>
                                        <div class="ui vertical animated button" id="borrarMaxSegundos"
                                             onclick="limpiarCampoMaxSegundos()">
                                            <div class="hidden content">Borrar</div>
                                            <div class="visible content">
                                                <i class="remove icon"></i>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        </table>
                    </div>

                </div>
            </div>
            <div class="four wide column mgn-top-3">
                <div class="ui segment">
                    <h4 class="ui header">Información de la partida</h4>
                    <div class="ui divider"></div>
                    <div id="informacionJuego">
                        <div class="field" id="campoTiempoActual">
                            <label for="tiempoPartida"><b>Tiempo de partida</b></label>
                            <div class="ui progress error" id="porcentajeTiempo" data-percent="0">
                                <div class="bar">
                                    <div class="progress"></div>
                                </div>
                                <div class="label" id="tiempoPartida">0</div>
                            </div>
                        </div>
                        <div class="field">
                            <label for="puntuacion"><b>Puntuación</b></label>
                            <div class="ui input right floated">
                                <input placeholder="0..." type="text" id="puntuacion" name="puntuacion" readonly/>
                            </div>
                        </div>
                    </div>
                    <div id="noPartidaEnJuego">
                        <div class="ui yellow message">
                            <i class="warning sign icon"></i> No hay ninguna partida en juego.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
    <div class="ui basic modal" id="derrotaTiempo">
        <div class="ui icon header">
            <i class="hourglass end icon"></i> Se acabó el tiempo
        </div>
        <div class="content">
            <p>No has sido capaz de completar el solitario celta en el tiempo límite. ¿Por qué no pruebas a
                quitarlo?</p>
        </div>
        <div class="actions">
            <div class="ui red basic cancel inverted button">
                <i class="close icon"></i> Cerrar
            </div>
        </div>
    </div>
    <div class="ui basic modal" id="derrotaMovimientos">
        <div class="ui icon header">
            <i class="remove icon"></i> Te has quedado sin movimientos
        </div>
        <div class="content">
            <p>No has conseguido completar el solitario celta, prueba a intentarlo de nuevo.</p>
        </div>
        <div class="actions">
            <div class="ui red basic cancel inverted button">
                <i class="close icon"></i> Cerrar
            </div>
        </div>
    </div>
    <div class="ui basic modal" id="victoria">
        <div class="ui icon header">
            <i class="birthday icon"></i> ¡Enhorabuena!
        </div>
        <div class="content">
            <p>Has conseguido completar correctamente el solitario celta. ¡Prueba con huecos aleatorios o
                personalizándote tu propio tablero!</p>
        </div>
        <div class="actions">
            <div class="ui red basic cancel inverted button">
                <i class="close icon"></i> Cerrar
            </div>
        </div>
    </div>
@endsection