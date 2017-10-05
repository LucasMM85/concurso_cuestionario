$(document).ready(function () {
    $('form[name="form-examen-login"]').on("submit", function (e) {
        var documento = $(this).find('input[name="cuit"]');

        if ($.trim(documento.val()) === "") {
            e.preventDefault();
            $('#alertLogin').text('Debe ingresar el CUIT O CUIL.');
            $("#formAlert").fadeIn(400);
        } else {
            e.preventDefault();
            $("#formAlert").fadeOut(400, function () {
                var request;

                if (request) {
                    request.abort();
                }
                var $form = $("#form-examen-login");
                var $inputs = $form.find("input, select, button, textarea");
                var serializedData = $form.serialize();

                $inputs.prop("disabled", true);

                request = $.ajax({
                    url: "php/funciones/validar.php",
                    dataType: 'json',
                    type: "post",
                    data: serializedData
                });

                request.done(function (response, textStatus, jqXHR){
                    if(textStatus == "success") {
                        if (response.error == 1) {
                            mostrarError(response);
                        } else {
                            completarDatos(response);
                        }
                    }
                });

                request.fail(function (jqXHR, textStatus, errorThrown){
                    console.error(
                        "Ocurrió un error: "+
                        textStatus, errorThrown
                    );
                });

                request.always(function () {
                    $inputs.prop("disabled", false);
                });
            });
        }
    });

    $(".alert").find(".close").on("click", function (e) {
        e.stopPropagation();
        e.preventDefault();
        $(this).closest(".alert").fadeOut(400);
    });

    $(".bot-opcion").on("click", function () {

        var request;

        if (request) {
            request.abort();
        }

        var ids = this.id;
        var idArray = ids.split('-');
        var $grupoBot = $(this).parent().find($(".bot-opcion"));
        var $botonActual = $(this);
        $grupoBot.prop("disabled", true);

        request = $.ajax({
            url: "php/funciones/respondePregunta.php",
            datatype: 'json',
            type: "post",
            data: {'idCuestionarioRespondido': $("#input_idcuestionarioRespondido").val(),
                   'idPregunta': idArray[0],
                   'idOpcionRespondida': idArray[1]}
        });

        request.done(function (response, textStatus, jqXHR){
            if(textStatus == "success"){
                if(response.idEstado == 2){
                    for(var i=0; i<$grupoBot.length; i++){
                        if($($grupoBot[i]).hasClass('list-group-item-info')){
                            $($grupoBot[i]).removeClass('list-group-item-info');
                        }
                    }
                    $botonActual.addClass('list-group-item-info');
                    actualizarTimer(response.fechaHoraFinal);
                } else if(response.idEstado == 3){
                    finalizarNavBar();
                    finalizarCuestionarioFrontend();
                }
            }
        });

        request.fail(function (jqXHR, textStatus, errorThrown){
            console.error(
                "Ocurrió un error: "+
                textStatus, errorThrown
            );
        });

        request.always(function () {
            $grupoBot.prop("disabled", false);
        });

    });

    $('form[name="form-validacion"]').on("submit", function (e) {
        e.preventDefault();

        var request;

        if (request) {
            request.abort();
        }
        var $form = $("#form-validacion");
        var $inputs = $form.find("input, select, button, textarea");
        var serializedData = $form.serialize();

        $inputs.prop("disabled", true);

        request = $.ajax({
            url: "php/funciones/procesaCuestionario.php",
            dataType: 'json',
            type: "post",
            data: serializedData
        });

        request.done(function (response, textStatus, jqXHR){
            if(textStatus == "success"){
                iniciarCuestionario(response);
            } else if(response.status == 1){
                mostrarError(response);
            }
        });

        request.fail(function (jqXHR, textStatus, errorThrown){
            console.error(
                "Ocurrió un error: "+
                textStatus, errorThrown
            );
        });

        request.always(function () {
            $inputs.prop("disabled", false);
        });
    });
});

$(function() {
    $('#documento').keydown(function(e) {
        var key   = e.keyCode ? e.keyCode : e.which;

        if (!( [8, 9, 13, 27, 46, 110, 190].indexOf(key) !== -1 ||
                (key == 65 && ( e.ctrlKey || e.metaKey  ) ) ||
                (key >= 35 && key <= 40) ||
                (key >= 48 && key <= 57 && !(e.shiftKey || e.altKey)) ||
                (key >= 96 && key <= 105)
            )) e.preventDefault();
    });
});

function completarDatos($response){
    $('#persona').text($response.persona);
    $('#cuit').text($response.cuit);
    $('#cuestionario').text($response.cuestionario);
    $('#idcuestionario').text($response.idCuestionario);
    $('#input_idcuestionarioRespondido').val($response.idCuestionario);
    $('#tiempo').text($response.tiempo);
    $("#pagina_login").hide();
    $("#pagina_recepcion").show();
}

function mostrarError($response) {
    $('#alertLogin').text($response.errorMessage);
    $("#formAlert").fadeIn(400);
}


/* FUNCIONES PARA MOSTRAR EL CUESTIONARIO */

function actualizarTimer(fechaHoraFinal){
    $('#nav_cronometro').countdown(fechaHoraFinal, function(event) {
        var $this = $(this).html(event.strftime(''
            + '<span>%H</span>:'
            + '<span>%M</span>:'
            + '<span>%S</span>'));
    });
}

function iniciarCuestionario($response) {
    $("#pagina_recepcion").hide();
    $("#encabezado").hide();
    $("#nav_persona").text($response.persona.apellidosNombres);
    $("#nav_tema").text($response.temario);
    $("#nav_titulo").text($response.estadoCuestionarioRespondido)
    $('#nav_cronometro').countdown($response.fechaHoraFinal, function(event) {
        var $this = $(this).html(event.strftime(''
            + '<span>%H</span>:'
            + '<span>%M</span>:'
            + '<span>%S</span>'));
    })
        .on('finish.countdown', function (){
            finalizarNavBar("Cuestionario Finalizado");
        });
    agregarPreguntas($response.preguntas);

    $("#pagina_cuestionario").show();
}

function agregarPreguntas($arrayPreguntas) {
    var i = 0;
    while (i < $arrayPreguntas.length){
        var $pregunta = $arrayPreguntas[i];
        $("#template-panel-pregunta").clone().appendTo("#accordion-cuestionario").show();
        var $panelPregunta = $('#panel-cuestionario').find($("#template-panel-pregunta"));
        $panelPregunta.attr('id',$pregunta.id);
        $panelPregunta.removeClass('hidden');
        //$panelPregunta.find(".texto-pregunta").text($pregunta.orden+" - "+$pregunta.descripcion);
        insertarHtml($pregunta, $panelPregunta);
        $('#template-panel-respuestas').clone().appendTo($panelPregunta);
        var $panelRespuestas = $panelPregunta.find($('#template-panel-respuestas'));
        $panelRespuestas.attr("id", 'colapsar'+$pregunta.id);
        for (var j=0;j<$pregunta.opciones.length;j++){
            var $opcion = $pregunta.opciones[j];
            $('#template-boton-respuesta').clone(true).appendTo($panelRespuestas);
            var $botonRespuesta = $panelRespuestas.find('#template-boton-respuesta');
            $botonRespuesta.attr('id',$pregunta.id+'-'+$opcion.id);
            //$botonRespuesta.html($opcion.orden+" "+$opcion.descripcion);
            insertarHtml($opcion, $botonRespuesta);
            if($opcion.isSeleccionada){
                $botonRespuesta.addClass('list-group-item-info');
            }
            $botonRespuesta.removeClass('hidden');
        }
        i++;
    }
}

function insertarHtml(opcion, $botonRespuesta){
    if(opcion.tipodato === "html"){
        $botonRespuesta.append(opcion.descripcion);
    } else if(opcion.tipodato === "imagen"){
        //$botonRespuesta.html(opcion.orden+" "+opcion.descripcion);
        insertarImagen(opcion, $botonRespuesta);
    } else if(opcion.tipodato === "texto"){
        $botonRespuesta.html(opcion.orden+" - "+opcion.descripcion);
    }
}

function insertarImagen(opcion, $elemento){
    var src = opcion.descripcion;
    var img = document.createElement("IMG");
    if(src.search("img") != -1){
        img.src = img.baseURI+src;
    } else {
        img.src = img.baseURI+"img/"+src;
    }
    img.onload = function () {
        if(img.width > 700){
            img.width = 700;
        }
    };
    var div = document.createElement("DIV");
    var texto = document.createTextNode(opcion.orden+" - ");
    div.appendChild(texto);
    div.style.textAlign = "left";
    div.style.float = "left";
    $elemento.css("text-align","center");
    //$elemento.html(div);
    //$elemento.html(img);
    $elemento.append(div);
    $elemento.append(img);
}

function finalizarNavBar(mensajeEstado) {
    $('#nav_titulo').text(mensajeEstado);
    $navTitulo = $('#nav_titulo').parent().parent().parent().parent();
    $navTitulo.addClass('navbar-rojo');
    $navUl = $('#nav_titulo').parent().parent().parent().parent();
    $ulList = $navUl.find('ul');
    for(var i=0;i<$ulList.length;i++){
        $($ulList[i]).addClass('navbar-rojo');
    }
    finalizarCuestionarioFrontend();
}

function modalFinalizar() {
    $('#modalFinalizar').modal();
}

function finalizarCuestionario() {
    var idCuestionarioRespondido = $('#input_idcuestionarioRespondido').val();
    var dni = $('#dniModal').val();

    if(dni == ""){
        $('#alertModalTexto').text("Debe ingresar su DNI");
        $('#alertModal').removeClass('hidden');
        $('#alertModal').fadeIn(400);
    } else {
        $('#alertModal').fadeOut(400);
        request = $.ajax({
            url: "php/funciones/finalizarCuestionario.php",
            datatype: 'json',
            type: "post",
            data: {'idCuestionarioRespondido': idCuestionarioRespondido,
                   'dni': dni
            }
        });

        request.done(function (response, textStatus, jqXHR){
            if(textStatus == "success"){
                if(response.error == 1){
                    $('#alertModalTexto').text(response.errorMessage);
                    $('#alertModal').removeClass('hidden');
                    $('#alertModal').fadeIn(400);
                } else {
                    $('#modalFinalizar').modal('hide')
                    limpiarModal();
                    finalizarNavBar(response.mensajeEstado);
                }
            }
        });

        request.fail(function (jqXHR, textStatus, errorThrown){
            console.error(
                "Ocurrió un error: "+
                textStatus, errorThrown
            );
        });

        request.always(function () {

        });
    }
}

function limpiarModal() {
    $('#alertModal').fadeOut();
    $('#dniModal').val("");
}

function finalizarCuestionarioFrontend() {
    $('#nav-boton-finalizar').prop("disabled", true);
    $('#nav-boton-corregir').prop("disabled", false);
    $('#nav_cronometro').countdown('stop');
}

function corregirCuestionario() {
    var idCuestionarioRespondido = $('#input_idcuestionarioRespondido').val();

    request = $.ajax({
        url: "php/funciones/corregirCuestionario.php",
        datatype: 'json',
        type: "post",
        data: {'idCuestionarioRespondido': idCuestionarioRespondido}
    });

    request.done(function (response, textStatus, jqXHR){
        if(textStatus == "success"){
            $('#corregirTituloTema').text(response.cuestionario);
            $('#corregirApellidoNombre').text(response.persona);
            $('#corregirAprobadoDesaprobado').text(response.textoResultado);
            $('#corregirPuntaje').text("Puntaje: "+response.puntaje);
            $('#corregirIdCuestionario').val(idCuestionarioRespondido);
            $('#modalCorregir').modal();
        }
    });

    request.fail(function (jqXHR, textStatus, errorThrown){
        console.error(
            "Ocurrió un error: "+
            textStatus, errorThrown
        );
    });
}