function FrontEndUtils() {

}

FrontEndUtils.prototype.peticionAjax = function (url, method, params, doneFn, failFn, token) {
    var peticion =
        $.ajax({
            url: url,
            method: method,
            headers: token !== undefined ? {Authorization: "Bearer " + token} : {},
            data: params
        });
    if (doneFn !== undefined) {
        peticion.done(doneFn);
    }
    if (failFn !== undefined) {
        peticion.fail(failFn);
    }

}

FrontEndUtils.prototype.rellenarTabla = function (rows, cols, id) {
    for (var idx = 0; idx < rows.length; idx++) {
        var row = $('<tr>').append($('<td>').append(rows[idx][cols[0]]));
        for (var col = 1; col < cols.length; col++){
            row.append($('<td>').append(rows[idx][cols[col]]));
        }
        $(id).find("tbody").append(row);
    }
}

FrontEndUtils.prototype.limpiarTabla = function (id){
    $(id).find('tbody').find('tr').each(function() { $(this).remove()});
}

FrontEndUtils.prototype.ocultarCelda = function(oBtn){
    $(oBtn).parent().parent().hide('slow');
}

FrontEndUtils.prototype.login = function () {
    var peticionLogin = {
        nombreUsuario: $("#nombreUsuario").val(),
        password: $("#password").val()
    };
    this.peticionAjax('/api/login', 'POST', peticionLogin,
        function (respuesta) {
            if (respuesta.token !== undefined) {
                sessionStorage.setItem('token', respuesta.token);
                window.location.href = '/login?token=' + respuesta.token;
            }
        },
        function (respuesta) {
            if (respuesta.status === 500) {
                alert("No se ha podido iniciar sesión debido a un error interno en el servidor");
            } else if (respuesta.status === 404) {
                alert("No se ha encontrado el usuario y contraseña introducidos");
            } else if (respuesta.status === 403) {
                alert("El usuario está deshabilitado");
            }
        });
}

FrontEndUtils.prototype.activarDesactivarUsuario = function (id, activar, doneFn, argsDoneFn){
    var peticionPut = {
        habilitado: activar
    };
    this.peticionAjax('/api/users/' + id, 'PUT', peticionPut,
        function(respuesta){
            doneFn(argsDoneFn);
        },
        function(respuesta, err){
            console.log(respuesta);
            console.log(err);
            alert('No se ha podido actualizar el usuario');
        }, sessionStorage.getItem('token'));
}

FrontEndUtils.prototype.getInactivos = function () {
    this.peticionAjax('/api/users/deshabilitados', 'GET', {},
        function (respuesta) {
            if (respuesta.usuarios !== undefined && respuesta.usuarios.length > 0){
                for (var idx = 0; idx < respuesta.usuarios.length; idx++) {
                    console.log(respuesta.usuarios[idx].id);
                    var $btn = $("#btnActivarTemplate").clone();
                    $btn.attr('onclick', $btn.attr('onclick').replace('_id_', respuesta.usuarios[idx].id));
                    $btn.removeAttr('id');
                    $btn.attr('class', $btn.attr('class').replace('hdn', ''));
                    respuesta.usuarios[idx]['custom'] = $btn;
                    utils.rellenarTabla([respuesta.usuarios[idx]], ['nombreUsuario', 'email', 'created_at', 'custom'], '#lineas');

                }
            }
        },
        function (respuesta) {
            console.log(respuesta);
        }, sessionStorage.getItem('token'));
}

FrontEndUtils.prototype.getTop10 = function() {
    var peticionTop = {
        "fechaInicio": $("#txtFechaInicio").val(),
        "fechaFin": $("#txtFechaFin").val()
    };

    this.peticionAjax('/api/results/top10', 'POST', peticionTop, 
    function (respuesta){
        if (respuesta.resultados !== undefined){
            utils.limpiarTabla('#lineas');
            utils.rellenarTabla(respuesta.resultados, ['puntos', 'fechaCreacion', 'usuarioId'], '#lineas');
        } else {
            alert("No se han obtenido resultados");
        }
    },
    function (respuesta){
        console.log(respuesta);
    }, sessionStorage.getItem('token'));
}

FrontEndUtils.prototype.getUsuarios = function(){
    this.peticionAjax('/api/users', 'GET', {}, 
    function(respuesta){
        if (respuesta.usuarios !== undefined){
            utils.rellenarTabla(respuesta.usuarios, ['nombreUsuario', 'email', '', 'nombre', 'apellidos', 'telefono', 'habilitado', 'esAdmin', '', ''], '#lineas');
        }
    },
    function(respuesta){
        console.log(respuesta);
    }, sessionStorage.getItem('token'));
}

FrontEndUtils.prototype.getPuntuaciones = function(){
    this.peticionAjax('/api/results', 'GET', {}, 
    function(respuesta){
        if (respuesta.resultados !== undefined){
            utils.rellenarTabla(respuesta.resultados, ['usuarioId', 'puntos', 'fechaCreacion'], '#lineas');
        }
    },
    function(respuesta){
        console.log(respuesta);
    }, sessionStorage.getItem('token'));
}

FrontEndUtils.prototype.getPartidas = function(){
    this.peticionAjax('/api/matches', 'GET', {}, 
    function(respuesta){
        if (respuesta.partidas !== undefined){
            utils.rellenarTabla(respuesta.partidas, ['usuarioId', 'estadoJson', 'fechaCreacion'], '#lineas');
        }
    },
    function(respuesta){
        console.log(respuesta);
    }, sessionStorage.getItem('token'));
}

FrontEndUtils.prototype.registro = function() {
    if ($("#password").val() === $("#passwordConfirmation").val()){
        var peticionRegistro = {
            nombreUsuario: $("#nombreUsuario").val(),
            email: $("#email").val(),
            password: $("#password").val()
        };
        this.peticionAjax('/api/users', 'POST', peticionRegistro,
        function(respuesta){
            if (respuesta !== undefined && respuesta.id > 0){
                alert('El alta del usuario se ha realizado con éxito. El siguiente paso es que un administrador valide tu cuenta');
            }
            console.log(respuesta);
        },
        function(respuesta){
            if (respuesta.status === 400){
                alert('El usuario o email no son únicos en el sistema');
            } else if (respuesta.status === 422){
                alert('Nombre de usuario, email y contraseña son campos obligatorios en el registro');
            }
            console.log(respuesta.status);
        }, sessionStorage.getItem('token'));
    }
}

utils = new FrontEndUtils();