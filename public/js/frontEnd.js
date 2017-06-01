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

};

FrontEndUtils.prototype.crearColumna = function (row, col) {
    var splitCols = col.split('.');
    if (splitCols.length === 1) { // en el caso de que no sea un campo compuesto
        return $('<td>').append(row[col]);
    } else { // en otro caso sólo se tiene en cuenta el primer nivel
        return $('<td>').append(row[splitCols[0]][splitCols[1]]);
    }
};

FrontEndUtils.prototype.rellenarTabla = function (rows, cols, id) {
    for (var idx = 0; idx < rows.length; idx++) {
        var row = $('<tr>').append(utils.crearColumna(rows[idx], cols[0]));
        for (var col = 1; col < cols.length; col++) {
            row.append(utils.crearColumna(rows[idx], cols[col]));
        }
        $(id).find("tbody").append(row);
    }
};

FrontEndUtils.prototype.limpiarTabla = function (id) {
    $(id).find('tbody').find('tr').each(function () {
        $(this).remove()
    });
};

FrontEndUtils.prototype.ocultarCelda = function (oBtn) {
    $(oBtn).parent().parent().hide('slow');
};

FrontEndUtils.prototype.login = function () {
    if ($("form").valid()) {
        var peticionLogin = {
            nombreUsuario: $("#nombreUsuario").val(),
            password: $("#password").val()
        };
        utils.peticionAjax('/api/login', 'POST', peticionLogin,
            function (respuesta) {
                if (respuesta.token !== undefined) {
                    sessionStorage.setItem('token', respuesta.token);
                    window.location.href = '/login?token=' + respuesta.token;
                }
            },
            function (respuesta) {
                if (respuesta.status === 500) {
                    utils.mostrarAlerta("No se ha podido iniciar sesión debido a un error interno en el servidor");
                } else if (respuesta.status === 404) {
                    utils.mostrarAlerta("No se ha encontrado el usuario y contraseña introducidos");
                } else if (respuesta.status === 403) {
                    utils.mostrarAlerta("El usuario está deshabilitado");
                }
            });
    } else {
        utils.mostrarAlerta('Revisa que hayas rellenado todos los campos obligatorios');
    }
};

FrontEndUtils.prototype.activarDesactivarUsuario = function (id, activar, doneFn, argsDoneFn) {
    var peticionPut = {
        habilitado: activar
    };
    utils.peticionAjax('/api/users/' + id, 'PUT', peticionPut,
        function (respuesta) {
            doneFn(argsDoneFn);
        },
        function (respuesta, err) {
            console.log(respuesta);
            console.log(err);
            utils.mostrarAlerta('No se ha podido actualizar el usuario');
        }, sessionStorage.getItem('token'));
};

FrontEndUtils.prototype.getInactivos = function () {
    utils.peticionAjax('/api/users/deshabilitados', 'GET', {},
        function (respuesta) {
            if (respuesta.usuarios !== undefined && respuesta.usuarios.length > 0) {
                for (var idx = 0; idx < respuesta.usuarios.length; idx++) {
                    respuesta.usuarios[idx]['custom'] = utils.generarClonBoton('#btnActivarTemplate', respuesta.usuarios[idx].id);
                    utils.rellenarTabla([respuesta.usuarios[idx]], ['nombreUsuario', 'email', 'created_at', 'custom'], '#lineas');
                }
            } else {
                utils.mostrarAlerta('No hay usuarios inactivos en este momento');
            }
        },
        function (respuesta) {
            console.log(respuesta);
        }, sessionStorage.getItem('token'));
};

FrontEndUtils.prototype.getTop10 = function () {
    var fechaInicio = $("#txtFechaInicio").val();
    var fechaFin = $("#txtFechaFin").val();
    if (fechaInicio !== "" && fechaFin !== "") {
        var peticionTop = {
            "fechaInicio": fechaInicio,
            "fechaFin": fechaFin
        };
        utils.peticionAjax('/api/results/top10', 'POST', peticionTop,
            function (respuesta) {
                if (respuesta.resultados !== undefined && respuesta.resultados.length > 0) {
                    utils.limpiarTabla('#lineas');
                    $('#lineas').removeClass('hdn');
                    utils.rellenarTabla(respuesta.resultados, ['puntos', 'fechaCreacion', 'usuario.nombreUsuario'], '#lineas');
                } else {
                    utils.mostrarAlerta("No se han obtenido resultados");
                }
            },
            function (respuesta) {
                utils.mostrarAlerta('Las fechas introducidas no tienen un formato correcto (mm/dd/yyyy) o bien la fecha de inicio es mayor que la de fin.');
                console.log(respuesta);
            }, sessionStorage.getItem('token'));
    } else {
        utils.mostrarAlerta('Al menos debes facilitar fecha de inicio y fin.');
    }
};

FrontEndUtils.prototype.getTop5DeUsuario = function (usuarioId){
    if (usuarioId > 0){
        utils.peticionAjax('/api/results/user/' + usuarioId + '/top5', 'GET', {},
        function (respuesta){
            if (respuesta.resultados !== undefined){
                utils.limpiarTabla('#top5');
                utils.rellenarTabla(respuesta.resultados, ['puntos', 'fechaCreacion'], '#top5');
            }
        },
        function (respuesta){
            utils.mostrarAlerta('No se pudieron obtener el listado de las 5 mejores puntuaciones del usuario');
        }, sessionStorage.getItem('token'));
    } else {
        utils.mostrarAlerta('Al menos debes facilitar el identificador del usuario');
    }
}

FrontEndUtils.prototype.getUsuarios = function () {
    utils.peticionAjax('/api/users', 'GET', {},
        function (respuesta) {
            if (respuesta.usuarios !== undefined) {
                utils.limpiarTabla('#lineas');
                for (var idx = 0; idx < respuesta.usuarios.length; idx++) {
                    var modifyDiv = $('<div>').append(utils.generarClonBoton('#btnModificarTemplate', respuesta.usuarios[idx].id));
                    modifyDiv.append(utils.generarClonBoton('#btnEliminarTemplate', respuesta.usuarios[idx].id));
                    respuesta.usuarios[idx]['modify'] = modifyDiv;
                    utils.rellenarTabla([respuesta.usuarios[idx]], ['nombreUsuario', 'email', 'nombre', 'apellidos', 'telefono', 'habilitado', 'esAdmin', 'modify'], '#lineas');
                }

            }
        },
        function (respuesta) {
            console.log(respuesta);
        }, sessionStorage.getItem('token'));
};

FrontEndUtils.prototype.getPuntuaciones = function () {
    utils.peticionAjax('/api/results', 'GET', {},
        function (respuesta) {
            if (respuesta.resultados !== undefined) {
                utils.limpiarTabla('#lineas');
                for (var idx = 0; idx < respuesta.resultados.length; idx++) {
                    var modifyDiv = $('<div>').append(utils.generarClonBoton('#btnModificarTemplate', respuesta.resultados[idx].id));
                    modifyDiv.append(utils.generarClonBoton('#btnEliminarTemplate', respuesta.resultados[idx].id));
                    respuesta.resultados[idx]['modify'] = modifyDiv;
                    utils.rellenarTabla([respuesta.resultados[idx]], ['usuario.nombreUsuario', 'puntos', 'fechaCreacion', 'modify'], '#lineas');
                }
            }
        },
        function (respuesta) {
            console.log(respuesta);
        }, sessionStorage.getItem('token'));
};

FrontEndUtils.prototype.getPuntuacionesDeUsuario = function (usuarioId, tablaId, cols) {
    if (usuarioId > 0) {
        tablaId = tablaId === undefined ? '#lineas' : tablaId;
        cols = cols === undefined ? ['puntos', 'fechaCreacion', 'usuario.nombreUsuario'] : cols;
        utils.peticionAjax('/api/results/user/' + usuarioId, 'GET', {},
            function (respuesta) {
                console.log(respuesta.resultados);
                utils.limpiarTabla(tablaId);
                $('#lineas').removeClass('hdn');
                utils.rellenarTabla(respuesta.resultados, cols, tablaId);
            },
            function (respuesta) {
                utils.mostrarAlerta('No se han podido recuperar las puntuaciones para el usuario');
            }, sessionStorage.getItem('token'));
    } else {
        utils.mostrarAlerta('Al menos debes seleccionar un usuario');
    }
};

FrontEndUtils.prototype.getPartidas = function () {
    utils.peticionAjax('/api/matches', 'GET', {},
        function (respuesta) {
            if (respuesta.partidas !== undefined) {
                utils.limpiarTabla('#lineas');
                console.log(respuesta.partidas);
                for (var idx = 0; idx < respuesta.partidas.length; idx++) {
                    var modifyDiv = $('<div>').append(utils.generarClonBoton('#btnModificarTemplate', respuesta.partidas[idx].id));
                    modifyDiv.append(utils.generarClonBoton('#btnEliminarTemplate', respuesta.partidas[idx].id));
                    respuesta.partidas[idx]['modify'] = modifyDiv;
                    utils.rellenarTabla([respuesta.partidas[idx]], ['usuario.nombreUsuario', 'estadoJson', 'fechaCreacion', 'modify'], '#lineas');
                }
            }
        },
        function (respuesta) {
            console.log(respuesta + " " + respuesta.status);
        }, sessionStorage.getItem('token'));
};

FrontEndUtils.prototype.registro = function () {
    $("form").validate({
        rules: {
            passwordConfirmation:{
                equalTo: '#password'
            }
        }
    });
    if ($("form").valid()) {
        var peticionRegistro = {
            nombreUsuario: $("#nombreUsuario").val(),
            email: $("#email").val(),
            password: $("#password").val()
        };
        utils.peticionAjax('/api/users', 'POST', peticionRegistro,
            function (respuesta) {
                if (respuesta !== undefined && respuesta.id > 0) {
                    utils.mostrarAlerta('El alta del usuario se ha realizado con éxito. El siguiente paso es que un administrador valide tu cuenta');
                    $("#formRegistro").hide();
                    $("#postRegistro").removeClass('hdn');
                }
            },
            function (respuesta) {
                if (respuesta.status === 400) {
                    utils.mostrarAlerta('El usuario o email no son únicos en el sistema');
                } else if (respuesta.status === 422) {
                    utils.mostrarAlerta('Nombre de usuario, email y contraseña son campos obligatorios en el registro');
                }
                console.log(respuesta.status);
            }, sessionStorage.getItem('token'));
    } else {
        utils.mostrarAlerta('Revisa que has rellenado todos los campos obligatorios y que el formato del email es correcto');
    }
};

FrontEndUtils.prototype.altaRegistro = function (url, objeto, refreshFn) {
    utils.peticionAjax(url, 'POST', objeto,
        function (respuesta) {
            if (respuesta !== undefined && respuesta.id > 0) {
                utils.mostrarAlerta('Nuevo registro creado con éxito');
                if (refreshFn !== undefined) {
                    refreshFn();
                }
            }
        },
        function (respuesta) {
            utils.mostrarAlerta('Ha habido un error al crear el registro (' + respuesta.status + respuesta.message + ')')
        }, sessionStorage.getItem('token'));
};

FrontEndUtils.prototype.actualizarRegistro = function (url, objeto, refreshFn) {
    utils.peticionAjax(url, 'PUT', objeto,
        function (respuesta) {
            if (respuesta !== undefined && respuesta.id > 0) {
                utils.mostrarAlerta('Registro actualizado con éxito');
                if (refreshFn !== undefined) {
                    refreshFn();
                }
            }
        },
        function (respuesta) {
            utils.mostrarAlerta('Ha habido un error al actualizar el registro (' + respuesta.status + ')')
        }, sessionStorage.getItem('token'));
};

FrontEndUtils.prototype.eliminarRegistro = function (url, refreshFn) {
    utils.peticionAjax(url, 'DELETE', {},
        function (respuesta) {
            refreshFn();
            utils.mostrarAlerta('El registro se ha eliminado de la base de datos');
        },
        function (respuesta) {
            utils.mostrarAlerta('No se ha podido dar de baja el registro (' + respuesta.status + ')');
        }, sessionStorage.getItem('token'));
};

FrontEndUtils.prototype.recuperarRegistro = function (url, params, doneFn){
    utils.peticionAjax(url, 'GET', params,
    function(respuesta){
        doneFn(respuesta);
    },
    function(respuesta){
        utils.mostrarAlerta('No se ha podido recuperar el registro (' + respuesta.status + ')');
    }, sessionStorage.getItem('token'));
};

FrontEndUtils.prototype.prepararModalUpdate = function (modalId, id, url) {
    $(modalId).find('form')[0].reset();
    utils.peticionAjax(url, 'GET', {},
        function (respuesta) {
            if (respuesta !== undefined && respuesta.id > 0) {
                $("#id").val(id);
                for (var clave in respuesta) {
                    if (respuesta.hasOwnProperty(clave)) {
                        var elemId = "#" + clave;
                        if ($(elemId).is(':checkbox') && respuesta[clave]) {
                            $(elemId).prop('checked', true);
                        } else {
                            $(elemId).val(respuesta[clave]);
                        }
                    }
                }
                utils.mostrarModalRegistro(modalId, false);
            } else {
                utils.mostrarAlerta('Ha habido un error cargando el registro');
            }
        },
        function (respuesta) {
            utils.mostrarAlerta('Ha habido un error cargando el registro');
        }, sessionStorage.getItem('token'));
};

FrontEndUtils.prototype.mostrarModalRegistro = function (modalId, esNuevo) {
    $(modalId).find('.new').each(function () {
        if (esNuevo) {
            $(this).show();
        } else {
            $(this).hide();
        }
    });
    $(modalId).find('.update').each(function () {
        if (esNuevo) {
            $(this).hide();
        } else {
            $(this).show();
        }
    });
    if (esNuevo) {
        $(modalId).find('form')[0].reset();
    }
    $(modalId).modal('show');
};

FrontEndUtils.prototype.prepararModalDelete = function (modalId, id) {
    $("#id").val(id);
    $(modalId).modal('show');
};

FrontEndUtils.prototype.generarClonBoton = function (btnId, id) {
    var $btn = $(btnId).clone();
    $btn.attr('onclick', $btn.attr('onclick').replace(new RegExp('_id_', 'g'), id));
    $btn.removeAttr('id');
    $btn.attr('class', $btn.attr('class').replace('hdn', 'ui button'));
    return $btn;
};

FrontEndUtils.prototype.mostrarAlerta = function (texto, titulo) {
    titulo = titulo === undefined ? "Advertencia" : titulo;
    $("body").find('#modalAdvertencia').remove();
    $("body").append($('<div class="ui modal" id="modalAdvertencia">')
        .append($('<div class="header">').append(titulo))
        .append($('<div class="content">').append(texto))
        .append($('<div class="actions">').append($('<button type="button" class="ui deny green button">').append('Aceptar'))));
    $("#modalAdvertencia").modal('show');
};

FrontEndUtils.prototype.crearSelectUsuarios = function (selectId) {
    utils.peticionAjax('/api/users', 'GET', {},
        function (respuesta) {
            if (respuesta.usuarios !== undefined && respuesta.usuarios.length > 0) {
                for (var idx = 0; idx < respuesta.usuarios.length; idx++) {
                    $(selectId).append($('<option value=' + respuesta.usuarios[idx]['id'] + '>').append(respuesta.usuarios[idx]['nombreUsuario']));
                }
            } else {
                utils.mostrarAlerta('No existen usuarios en la base de datos');
            }
        },
        function (respuesta) {
            utils.mostrarAlerta('Ha habido un error recuperando los usuarios');
        }, sessionStorage.getItem('token'));

};

FrontEndUtils.prototype.cerrarSesion = function(){
  sessionStorage.clear();
  location.href='/signout';
};

utils = new FrontEndUtils();