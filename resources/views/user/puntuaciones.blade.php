@extends('user.portada')
@section('titulo', 'Ver puntuaciones')
@section('cuerpo')
    <input type="hidden" id="id" value="{{$usuarioAuth -> id}}">
    <div class="container">
        <div class="ui segment">
            <h2>@yield('titulo')</h2>
            <p>Desde esta vista podrás ver tu histórico de puntuaciones y el top 5 del histórico</p>
            <div class="divider"></div>
            <div class="ui grid">
                <div class="eight wide column">
                    <h3>Historial de puntuaciones</h3>
                    <div class="divider"></div>
                    <table id="historial" class="ui celled table">
                        <thead>
                            <tr>
                                <th>Puntos</th>
                                <th>Fecha</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
                <div class="eight wide column">
                    <h3>Top 5</h3>
                    <div class="divider"></div>
                    <table id="top5" class="ui celled table">
                        <thead>
                        <tr>
                            <th>Puntos</th>
                            <th>Fecha</th>
                        </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <script>
        utils.getPuntuacionesDeUsuario($('#id').val(), '#historial', ['puntos', 'fechaCreacion']);
        utils.getTop5DeUsuario($('#id').val());
    </script>
@endsection