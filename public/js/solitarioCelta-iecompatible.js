const EST_PLANO = 0;
const EST_HUECO = 1;
const EST_BOLA = 2;
const EST_PULSADA = 3;

const NUM_FILAS = 7;
const NUM_COLUMNAS = 7;

const PTS_COMIDA = 15;
const PTS_EXTRA = 150;
const PTS_PENALIZACION = 50;

function ElementoTablero(estado) {
    this.estado = estado;
}

function SolitarioCelta(tableroPersonalizado, tableroEstados, tiempoMaximo, tiempoActual, intervaloIniciado, intervalo, puntuacion, bolasEnJuego, huecosEnJuego) {
    this.tableroPersonalizado = tableroPersonalizado;
    this.tableroEstados = tableroEstados;
    this.tiempoMaximo = tiempoMaximo;
    this.tiempoActual = 0;
    this.intervaloIniciado = false;
    this.intervalo = intervalo;
    this.puntuacion = 0;
    this.bolasEnJuego = [];
    this.huecosEnJuego = [];
}

SolitarioCelta.prototype.limpiarBolaPulsadaAnterior = function () {
    for (var fila = 0; fila < NUM_FILAS; fila++) {
        for (var columna = 0; columna < NUM_COLUMNAS; columna++) {
            if (this.tableroEstados[fila][columna].estado == EST_PULSADA) {
                this.cambiarEstado(fila, columna, EST_BOLA);
                break;
            }
        }
    }
};

SolitarioCelta.prototype.bolaPulsada = function (bola) {
    var fila = parseInt(bola.id.split('_')[0]);
    var columna = parseInt(bola.id.split('_')[1]);
    var estado = this.tableroEstados[fila][columna].estado;
    if (estado == EST_BOLA) {
        if (this.tableroPersonalizado) {
            estado = EST_HUECO;
        } else {
            estado = EST_PULSADA;
            this.limpiarBolaPulsadaAnterior();
        }
    } else {
        if (this.tableroPersonalizado && estado == EST_HUECO || estado == EST_PULSADA) {
            estado = EST_BOLA;
        }
    }
    this.cambiarEstado(fila, columna, estado);
};

SolitarioCelta.prototype.recogerBolaPulsada = function () {
    var coordenadas = {};
    for (var fila = 0; fila < NUM_FILAS; fila++) {
        for (var columna = 0; columna < NUM_COLUMNAS; columna++) {
            if (this.tableroEstados[fila][columna].estado == EST_PULSADA) {
                coordenadas = { "fila": fila, "columna": columna };
            }
        }
    }
    return coordenadas;
};

SolitarioCelta.prototype.actualizarPuntuacion = function (puntuacionASumar) {
    if (puntuacionASumar == undefined) {
        this.puntuacion = 0;
    } else {
        this.puntuacion += puntuacionASumar;
    }
    var puntuacionDOM = document.getElementById('puntuacion');
    puntuacionDOM.value = this.puntuacion;
    if (this.puntuacion > 0) {
        puntuacionDOM.className = 'puntuacionEnPositivo';
    } else if (this.puntuacion < 0) {
        puntuacionDOM.className = 'puntuacionEnNegativo';
    } else {
        puntuacionDOM.className = '';
    }
};

SolitarioCelta.prototype.limpiarIntervalo = function () {
    if (this.intervaloIniciado) {
        clearInterval(this.intervalo);
        this.intervaloIniciado = false;
        document.getElementById('txtMaxSegundos').removeAttribute('readonly');
        document.getElementById('borrarMaxSegundos').style.visibility = "visible";
    }
};

SolitarioCelta.prototype.finDelJuego = function () {
    this.limpiarIntervalo();
    if (this.bolasEnJuego.length == 1) {
        if ((this.bolasEnJuego[0].fila == Math.floor(this.tableroEstados[0].length / 2))
            && (this.bolasEnJuego[0].columna == Math.floor(this.tableroEstados[1].length / 2))) {
            this.actualizarPuntuacion(PTS_EXTRA);
        }
        $('#victoria').modal('show');
    } else {
        this.actualizarPuntuacion(-(this.bolasEnJuego.length * PTS_PENALIZACION));
        if (this.tiempoActual == this.tiempoMaximo && this.tiempoMaximo > 0) {
            $('#derrotaTiempo').modal('show');
        } else {
            $('#derrotaMovimientos').modal('show');
        }
    }
    document.getElementById('registroPuntuacion').style.display = "flex";
    document.getElementById('guardarEstado').style.display = "none";
    for (var fila = 0; fila < NUM_FILAS; fila++) {
        for (var columna = 0; columna < NUM_COLUMNAS; columna++) {
            document.getElementById(fila + "_" + columna).onclick = undefined;
        }
    }
};

SolitarioCelta.prototype.obtenerSituacionJuego = function () {
    this.bolasEnJuego = [];
    this.huecosEnJuego = [];
    for (var fila = 0; fila < NUM_FILAS; fila++) {
        for (var columna = 0; columna < NUM_COLUMNAS; columna++) {
            if (this.tableroEstados[fila][columna].estado == EST_HUECO) {
                this.huecosEnJuego.push({ "fila": fila, "columna": columna });
            } else if (this.tableroEstados[fila][columna].estado == EST_BOLA || this.tableroEstados[fila][columna].estado == EST_PULSADA) {
                this.bolasEnJuego.push({ "fila": fila, "columna": columna });
            }
        }
    }
};

SolitarioCelta.prototype.comprobarSiFinDeJuego = function () {
    var haySolucion = false;
    this.obtenerSituacionJuego();
    if (this.bolasEnJuego.length > 1) {
        for (var idx = 0; idx < this.huecosEnJuego.length; idx++) {
            var huecoId = this.huecosEnJuego[idx].fila + "_" + this.huecosEnJuego[idx].columna;
            haySolucion = this.comprobarSiComida(huecoId, { "fila": this.huecosEnJuego[idx].fila - 2, "columna": this.huecosEnJuego[idx].columna }, false)
                || this.comprobarSiComida(huecoId, { "fila": this.huecosEnJuego[idx].fila + 2, "columna": this.huecosEnJuego[idx].columna }, false)
                || this.comprobarSiComida(huecoId, { "fila": this.huecosEnJuego[idx].fila, "columna": this.huecosEnJuego[idx].columna - 2 }, false)
                || this.comprobarSiComida(huecoId, { "fila": this.huecosEnJuego[idx].fila, "columna": this.huecosEnJuego[idx].columna + 2 }, false);
            if (haySolucion) break;
        }
    }

    if (!haySolucion) {
        this.finDelJuego();
    }
};

SolitarioCelta.prototype.comprobarSiComida = function (imgId, coordenadasBolaPulsada, comer) {
    var fila = parseInt(imgId.split('_')[0]);
    var columna = parseInt(imgId.split('_')[1]);
    var diferenciaFila = fila - coordenadasBolaPulsada.fila;
    var diferenciaColumna = columna - coordenadasBolaPulsada.columna;
    var puedeComer = false;

    if (coordenadasBolaPulsada.fila >= 0 && coordenadasBolaPulsada.fila < NUM_FILAS
        && coordenadasBolaPulsada.columna >= 0 && coordenadasBolaPulsada.columna < NUM_COLUMNAS) {
        if (((Math.abs(diferenciaFila) == 2) || (Math.abs(diferenciaColumna) == 2)) && (fila == coordenadasBolaPulsada.fila || columna == coordenadasBolaPulsada.columna)) {
            var filaBolaAComer = fila;
            if (diferenciaFila < 0) {
                filaBolaAComer += 1;
            } else if (diferenciaFila > 0) {
                filaBolaAComer -= 1;
            }
            var columnaBolaAComer = columna;
            if (diferenciaColumna < 0) {
                columnaBolaAComer += 1;
            } else if (diferenciaColumna > 0) {
                columnaBolaAComer -= 1;
            }
            puedeComer = (this.tableroEstados[filaBolaAComer][columnaBolaAComer].estado == EST_BOLA
                && (this.tableroEstados[coordenadasBolaPulsada.fila][coordenadasBolaPulsada.columna].estado == EST_BOLA
                    || this.tableroEstados[coordenadasBolaPulsada.fila][coordenadasBolaPulsada.columna].estado == EST_PULSADA));
            if (puedeComer && comer) {
                this.cambiarEstado(fila, columna, EST_BOLA);
                this.cambiarEstado(coordenadasBolaPulsada.fila, coordenadasBolaPulsada.columna, EST_HUECO);
                this.cambiarEstado(filaBolaAComer, columnaBolaAComer, EST_HUECO);
            }
        }
    }

    return puedeComer;
};

SolitarioCelta.prototype.huecoPulsado = function (hueco) {
    var coordenadasBolaPulsada = this.recogerBolaPulsada();
    if (coordenadasBolaPulsada.fila != undefined) {
        if (!this.comprobarSiComida(hueco.id, coordenadasBolaPulsada, true)) {
            utils.mostrarAlerta('No has pulsado la bola correcta para colocarla en ese hueco. Prueba con otra en su misma fila o diagonal.');
        } else {
            this.actualizarPuntuacion(PTS_COMIDA);
            this.comprobarSiFinDeJuego();
        }
    } else {
        utils.mostrarAlerta('Primero debes pulsar alguna bola.')
    }
};

SolitarioCelta.prototype.hayHueco = function () {
    for (var fila = 0; fila < NUM_FILAS; fila++) {
        for (var columna = 0; columna < NUM_COLUMNAS; columna++) {
            if (this.tableroEstados[fila][columna].estado == EST_HUECO) {
                return true;
            }
        }
    }
    return false;
};

SolitarioCelta.prototype.crearImagen = function (fila, columna, estado) {
    var imagen = document.createElement('img');
    var solitario = this;
    switch (estado) {
        case EST_HUECO:
            if (!this.tableroPersonalizado) {
                imagen.onclick = function() { solitario.huecoPulsado(imagen); }
            } else {
                imagen.onclick = function() { solitario.bolaPulsada(imagen); }
            }
            imagen.src = "/imgs/hueco.png";
            break;
        case EST_PLANO:
            imagen.src = "/imgs/plano.png";
            break;
        case EST_BOLA:
            imagen.onclick = function() { solitario.bolaPulsada(imagen); }
            imagen.src = "/imgs/bola.png";
            break;
        case EST_PULSADA:
            imagen.onclick = function() { solitario.bolaPulsada(imagen); }
            imagen.src = "/imgs/bola_pulsada.png";
            break;
    }
    imagen.id = fila + "_" + columna;
    return imagen;
};

SolitarioCelta.prototype.cambiarEstado = function (fila, columna, estado) {
    var imagen = document.getElementById(fila + "_" + columna);
    var solitario = this;
    switch (estado) {
        case EST_HUECO:
            if (!this.tableroPersonalizado) {
                imagen.onclick = function() { solitario.huecoPulsado(imagen); }
            } else {
                imagen.onclick = function() { solitario.bolaPulsada(imagen); }
            }
            imagen.src = "/imgs/hueco.png";
            break;
        case EST_PLANO:
            imagen.src = "/imgs/plano.png";
            break;
        case EST_BOLA:
            imagen.onclick = function() { solitario.bolaPulsada(imagen); }
            imagen.src = "/imgs/bola.png";
            break;
        case EST_PULSADA:
            imagen.onclick = function() { solitario.bolaPulsada(imagen); }
            imagen.src = "/imgs/bola_pulsada.png";
            break;
    }
    this.tableroEstados[fila][columna].estado = estado;
};

SolitarioCelta.prototype.establecerHueco = function () {
    var huecoCentral = document.getElementById('huecoCentral');
    var fila = 0;
    var columna = 0;
    if (huecoCentral.checked) {
        fila = Math.floor(this.tableroEstados[0].length / 2);
        columna = Math.floor(this.tableroEstados[1].length / 2);
    } else {
        while (this.tableroEstados[fila][columna].estado == EST_PLANO) {
            fila = Math.floor(Math.random() * this.tableroEstados[0].length);
            columna = Math.floor(Math.random() * this.tableroEstados[1].length);
        }
    }
    this.cambiarEstado(fila, columna, EST_HUECO);
};

SolitarioCelta.prototype.construirTablero = function () {
    var tableroDOM = document.getElementById('tablero');
    tableroDOM.innerHTML = ""; // borramos todo lo anterior.
    for (var fila = 0; fila < 2; fila++) {
        for (var columna = 0; columna < NUM_COLUMNAS; columna++) {
            if (columna > 1 && columna < 5) {
                this.tableroEstados[fila][columna] = this.tableroPersonalizado ? new ElementoTablero(EST_HUECO) : new ElementoTablero(EST_BOLA);
            } else {
                this.tableroEstados[fila][columna] = new ElementoTablero(EST_PLANO);
            }
            tableroDOM.appendChild(this.crearImagen(fila, columna, this.tableroEstados[fila][columna].estado));
        }
        tableroDOM.appendChild(document.createElement('br'));
    }
    for (var fila = 2; fila < 5; fila++) {
        this.tableroEstados[fila] = [];
        for (var columna = 0; columna < NUM_COLUMNAS; columna++) {
            this.tableroEstados[fila][columna] = this.tableroPersonalizado ? new ElementoTablero(EST_HUECO) : new ElementoTablero(EST_BOLA);
            tableroDOM.appendChild(this.crearImagen(fila, columna, this.tableroEstados[fila][columna].estado));
        }
        tableroDOM.appendChild(document.createElement('br'));
    }
    for (var fila = 5; fila < NUM_FILAS; fila++) {
        this.tableroEstados[fila] = [];
        for (var columna = 0; columna < NUM_COLUMNAS; columna++) {
            if (columna > 1 && columna < 5) {
                this.tableroEstados[fila][columna] = this.tableroPersonalizado ? new ElementoTablero(EST_HUECO) : new ElementoTablero(EST_BOLA);
            } else {
                this.tableroEstados[fila][columna] = new ElementoTablero(EST_PLANO);
            }
            tableroDOM.appendChild(this.crearImagen(fila, columna, this.tableroEstados[fila][columna].estado));
        }
        tableroDOM.appendChild(document.createElement('br'));
    }
};

SolitarioCelta.prototype.construirTableroPersonalizado = function (estadoJson) {
    this.tableroPersonalizado = true;
    this.limpiarIntervalo();
    this.inicializarUI();
    this.construirTablero();
    if (estadoJson !== undefined){
        this.tableroEstados = estadoJson.tableroEstados;
        if (estadoJson.tiempoMaximo > 0) {
            this.tiempoActual = estadoJson.tiempoActual;
            document.getElementById('txtMaxSegundos').value = estadoJson.tiempoMaximo;
        }
        this.comenzarJuego(estadoJson.tiempoMaximo > 0);
        this.actualizarPuntuacion(estadoJson.puntos);
    }
};

SolitarioCelta.prototype.reAsignarClickEnTableroPersonalizado = function () {
    for (var fila = 0; fila < NUM_FILAS; fila++) {
        for (var columna = 0; columna < NUM_COLUMNAS; columna++) {
            this.cambiarEstado(fila, columna, this.tableroEstados[fila][columna].estado);
        }
    }
};

SolitarioCelta.prototype.actualizarControlTiempoPartida = function () {
    document.getElementById('tiempoPartida').innerHTML = this.tiempoActual + "/" + this.tiempoMaximo + " segundos";
    $('#porcentajeTiempo').progress({
        percent: this.tiempoActual / this.tiempoMaximo * 100
    });
};

SolitarioCelta.prototype.comprobarTiempo = function () {
    this.tiempoActual++;
    this.actualizarControlTiempoPartida();
    if (this.tiempoActual == this.tiempoMaximo) {
        this.obtenerSituacionJuego();
        this.finDelJuego();
    }
};

SolitarioCelta.prototype.inicializarUI = function () {
    document.getElementById('informacionJuego').style.display = "none";
    document.getElementById('modoBienvenida').style.display = "none";
    document.getElementById('noPartidaEnJuego').style.display = "none";
    if (this.tableroPersonalizado) {
        document.getElementById('modoJuego').style.display = "none";
        document.getElementById('modoSituar').style.display = "block";
        document.getElementById('registroPuntuacion').style.display = "none";
        document.getElementById('guardarEstado').style.display = "none";
        document.getElementById('noPartidaEnJuego').style.display = "block";
    } else {
        document.getElementById('guardarEstado').style.display = "flex";
        document.getElementById('registroPuntuacion').style.display = "none";
        document.getElementById('informacionJuego').style.display = "block";
        if (this.intervaloIniciado) {
            document.getElementById('campoTiempoActual').style.display = "block";
        } else {
            document.getElementById('campoTiempoActual').style.display = "none";
        }
        document.getElementById('modoJuego').style.display = "block";
        document.getElementById('modoSituar').style.display = "none";
    }
    this.actualizarPuntuacion();
};

SolitarioCelta.prototype.activarTemporizador = function (tiempoActualYaDefinido) {
    var txtMaxSegundos = document.getElementById('txtMaxSegundos');
    if (!tiempoActualYaDefinido) {
        this.tiempoActual = 0;
    }
    this.tiempoMaximo = 0;
    if (txtMaxSegundos.value !== "") {
        this.tiempoMaximo = parseInt(txtMaxSegundos.value);
        this.actualizarControlTiempoPartida();
        if (this.intervaloIniciado) {
            clearInterval(this.intervalo);
        }
        txtMaxSegundos.setAttribute('readonly', 'readonly');
        document.getElementById('borrarMaxSegundos').style.visibility = "hidden";
        var solitario = this;
        this.intervalo = setInterval(function() { solitario.comprobarTiempo() }, 1000);
        this.intervaloIniciado = true;
        document.getElementById('campoTiempoActual').style.display = "block";
    }
};

SolitarioCelta.prototype.comenzarJuego = function (tiempoActualYaDefinido) {
    if (this.tableroPersonalizado) {
        if (!this.hayHueco()) {
            this.establecerHueco();
        }
        this.tableroPersonalizado = false;
        this.reAsignarClickEnTableroPersonalizado();
        this.activarTemporizador(tiempoActualYaDefinido === undefined ? false : tiempoActualYaDefinido);
        this.comprobarSiFinDeJuego();
    } else {
        this.construirTablero();
        this.establecerHueco();
        this.activarTemporizador(tiempoActualYaDefinido === undefined ? false : tiempoActualYaDefinido);
    }
    
    this.inicializarUI();
};

SolitarioCelta.prototype.guardarEstado = function(){
    var estadoJson = {
        tableroEstados : this.tableroEstados,
        tiempoMaximo: this.tiempoMaximo,
        tiempoActual: this.tiempoActual,
        puntos: this.puntuacion
    };
    var estado = {
        estadoJson: JSON.stringify(estadoJson),
        usuarioId: $('#id').val()
    };
    utils.altaRegistro('/api/matches', estado);
};

SolitarioCelta.prototype.registrarPuntuacion = function(){
    var puntuacion = {
        puntos: this.puntuacion,
        usuarioId: $('#id').val()
    };
    utils.altaRegistro('/api/results', puntuacion, function(){ document.getElementById('registroPuntuacion').style.display = "none"; });
};

SolitarioCelta.prototype.recuperarEstado = function(estado){
    if (estado.partidas.length === 1 && estado.partidas[0].estadoJson !== ""){
        var estadoJson = JSON.parse(estado.partidas[0].estadoJson);
        solitario.construirTableroPersonalizado(estadoJson);
    } else {
        utils.mostrarAlerta('Ha habido un error al recuperar la partida del usuario');
    }

};

function limpiarCampoMaxSegundos() {
    document.getElementById('txtMaxSegundos').value = "";
}

function soloNumeros(event) {
    var codigo = event.keyCode;
    var caracter = String.fromCharCode(codigo);
    if (("0123456789".indexOf(caracter) < 0) && codigo != 8 && codigo != 9) {
        return false;
    }
}