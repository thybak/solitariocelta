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

FrontEndUtils.prototype.rellenarTabla = function (rows, cols, id) {
    for (var idx = 0; idx < rows.length; idx++) {
        var row = $('<tr>').append($('<td>').append(rows[idx][cols[0]]));
        for (var col = 1; col < cols.length; col++) {
            row.append($('<td>').append(rows[idx][cols[col]]));
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
                alert("No se ha podido iniciar sesión debido a un error interno en el servidor");
            } else if (respuesta.status === 404) {
                alert("No se ha encontrado el usuario y contraseña introducidos");
            } else if (respuesta.status === 403) {
                alert("El usuario está deshabilitado");
            }
        });
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
            alert('No se ha podido actualizar el usuario');
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
            }
        },
        function (respuesta) {
            console.log(respuesta);
        }, sessionStorage.getItem('token'));
};

FrontEndUtils.prototype.getTop10 = function () {
    var peticionTop = {
        "fechaInicio": $("#txtFechaInicio").val(),
        "fechaFin": $("#txtFechaFin").val()
    };

    utils.peticionAjax('/api/results/top10', 'POST', peticionTop,
        function (respuesta) {
            if (respuesta.resultados !== undefined) {
                utils.limpiarTabla('#lineas');
                utils.rellenarTabla(respuesta.resultados, ['puntos', 'fechaCreacion', 'usuarioId'], '#lineas');
            } else {
                alert("No se han obtenido resultados");
            }
        },
        function (respuesta) {
            console.log(respuesta);
        }, sessionStorage.getItem('token'));
};

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
                utils.rellenarTabla(respuesta.resultados, ['usuarioId', 'puntos', 'fechaCreacion'], '#lineas');
            }
        },
        function (respuesta) {
            console.log(respuesta);
        }, sessionStorage.getItem('token'));
};

FrontEndUtils.prototype.getPartidas = function () {
    utils.peticionAjax('/api/matches', 'GET', {},
        function (respuesta) {
            if (respuesta.partidas !== undefined) {
                utils.limpiarTabla('#lineas');
                utils.rellenarTabla(respuesta.partidas, ['usuarioId', 'estadoJson', 'fechaCreacion'], '#lineas');
            }
        },
        function (respuesta) {
            console.log(respuesta);
        }, sessionStorage.getItem('token'));
};

FrontEndUtils.prototype.registro = function () {
    if ($("#password").val() === $("#passwordConfirmation").val()) {
        var peticionRegistro = {
            nombreUsuario: $("#nombreUsuario").val(),
            email: $("#email").val(),
            password: $("#password").val()
        };
        utils.peticionAjax('/api/users', 'POST', peticionRegistro,
            function (respuesta) {
                if (respuesta !== undefined && respuesta.id > 0) {
                    alert('El alta del usuario se ha realizado con éxito. El siguiente paso es que un administrador valide tu cuenta');
                }
            },
            function (respuesta) {
                if (respuesta.status === 400) {
                    alert('El usuario o email no son únicos en el sistema');
                } else if (respuesta.status === 422) {
                    alert('Nombre de usuario, email y contraseña son campos obligatorios en el registro');
                }
                console.log(respuesta.status);
            }, sessionStorage.getItem('token'));
    }
};

FrontEndUtils.prototype.altaRegistro = function (url, objeto, refreshFn) {
    utils.peticionAjax(url, 'POST', objeto,
        function (respuesta) {
            if (respuesta !== undefined && respuesta.id > 0) {
                alert('Nuevo registro creado con éxito');
                if (refreshFn !== undefined) {
                    refreshFn();
                }
            }
        },
        function (respuesta) {
            alert('Ha habido un error al crear el registro (' + respuesta.status + ')')
        }, sessionStorage.getItem('token'));
};

FrontEndUtils.prototype.actualizarRegistro = function (url, objeto, refreshFn) {
    utils.peticionAjax(url, 'PUT', objeto,
        function (respuesta) {
            if (respuesta !== undefined && respuesta.id > 0) {
                alert('Registro actualizado con éxito');
                if (refreshFn !== undefined) {
                    refreshFn();
                }
            }
        },
        function (respuesta) {
            alert('Ha habido un error al actualizar el registro (' + respuesta.status + ')')
        }, sessionStorage.getItem('token'));
};

FrontEndUtils.prototype.eliminarRegistro = function (url, refreshFn) {
    console.log(url);
    utils.peticionAjax(url, 'DELETE', {},
        function (respuesta) {
            refreshFn();
            alert('El registro se ha eliminado de la base de datos');
        },
        function (respuesta) {
            alert('No se ha podido dar de baja el registro (' + respuesta.status + ')');
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
                alert('Ha habido un error cargando el registro');
            }
        },
        function (respuesta) {
            alert('Ha habido un error cargando el registro');
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

FrontEndUtils.prototype.prepararModalDelete = function(modalId, id){
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

utils = new FrontEndUtils();