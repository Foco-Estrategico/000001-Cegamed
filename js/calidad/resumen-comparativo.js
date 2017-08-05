$(document).ready(function(){
    var Comparison = false;
    $(".Left").addClass("FullWidth");
    var General = [],
        Items = [],
        ItemsName = [],
        Notas = [
                [1],[2],[3],[4],[5]
            ];


    $("#Mostrar").click(function(){
        var EmpresaLeft = $("select[name='EmpresaLeft']").val();
        var EmpresaRight = $("select[name='EmpresaRight']").val();
        var EjecutivoLeft = $("select[name='EjecutivoLeft']").val();
        var EjecutivoRight = $("select[name='EjecutivoRight']").val();
        if(Comparison){
            if(((EmpresaLeft != "") || (EjecutivoLeft != "")) || ((EmpresaRight != "") || (EjecutivoRight != ""))){
                ShowGraphs();
                $("#result").show();
            }else{
                alert("Debe seleccionar almenos una empresa de cada comparativo");
            }
        }else{
            if((EmpresaLeft != "") || (EjecutivoLeft != "")){
                ShowGraphs();
                $("#result").show();
            }else{
                alert("Debe seleccionar una empresa");
            }
        }
    });
    $("#Comparar").change(function(){
        $(".SingleHuman .Valores").html("");
        $(".SingleHuman .Caracteristicas").html("");
        $(".HumanComparison .Left").html("");
        $(".HumanComparison .Right").html("");
        $(".HumanComparison .Center").html("");
        if($(this).is(':checked')){
            $(".InputForComparison").prop("disabled", false);
            $(".InputForComparison").selectpicker('refresh');
            Comparison = true;
            $("#result").hide();
            $(".Right").show();
            $(".Left").removeClass("FullWidth");
            $(".HumanComparison").show();
            $(".SingleHuman").hide();
        }else{
            $("#result").hide();
            $(".InputForComparison").prop("disabled", true);
            $(".InputForComparison").selectpicker('refresh');
            Comparison = false;
            $(".Right").hide();
            $(".Left").addClass("FullWidth");
            $(".HumanComparison").hide();
            $(".SingleHuman").show();
        }
    });
    function ShowGraphs(){
        var Comparacion = false;
        var MandanteLeft = $("select[name='EmpresaLeft']").val();
        var EjecutivoLeft = $("select[name='EjecutivoLeft']").val();
        var MandanteRight = "";
        var EjecutivoRight = "";
        var Tipo = $("select[name='Tipo']").val();
        var CantXAxis = 0;
        switch(Tipo){
            case 'Mes':
                CantXAxis = 4;
            break;
            case 'Historico':
                CantXAxis = 6;
            break;
        }
        if($("#Comparar").is(':checked')){
            MandanteRight = $("select[name='EmpresaRight']").val();
            EjecutivoRight = $("select[name='EjecutivoRight']").val();
            Comparacion = true;
        }
        $("#BarChartResumenLeft").html("");
        $("#BarChartResumenRight").html("");
        $.ajax({
            type: "POST",
            url: "../includes/calidad/GetComparisonGraphData.php",
            dataType: "html",
            async: false,
            data: {
                Comparacion: Comparacion,
                MandanteLeft: MandanteLeft,
                EjecutivoLeft: EjecutivoLeft,
                MandanteRight: MandanteRight,
                EjecutivoRight: EjecutivoRight,
                Tipo: Tipo
            },
            success: function(data){
                console.log(data);
                var json = JSON.parse(data);
                console.log(json);
                General[0] = json.General[0];
                General[1] = json.General[1];
                Items[0] = json.GeneralItems[0];
                Items[1] = json.GeneralItems[1];
                ItemsName = json.ItemsName;
                //console.log(json.HumanComparison);
                $("#Perfil .Left .PerfilEjecutivo .Titulo").html(json.Perfil["Left"]["Titulo"]);
                $("#Perfil .Left .PerfilEjecutivo .Descripcion").html(json.Perfil["Left"]["Descripcion"]);
                fillHumanComparison(json.HumanComparison);
                var plotLeft = $.plot(".Charts.Comparison .Left > .Chart", [
                        {
                            label: 'Calidad',
                            data: General[0][0],
                            bars:{
                                align: "left"
                            }
                        },
                        {
                            label: 'Ejecutivo',
                            data: General[0][1],
                            bars:{
                                align: "center"
                            }
                        },
                        {
                            label: 'Empresa',
                            data: General[0][2],
                            bars:{
                                align: "right"
                            }
                        }
                    ],
                    {
                        series: {
                            bars: {
                                show: true,
                                fill: true
                            },
                        },
                        bars: {
                            align: "center",
                            horizontal: false,
                            barWidth: .4
                        },
                        colors: ['green', 'black','red' ],
                        legend: {
                            show: true,
                            position: 'nw',
                            margin: [30, -55]
                        },
                        grid: {
                            borderWidth: 0,
                            hoverable: true,
                            clickable: true
                        },
                        yaxis: {
                            min: 0,
                            max: 5,
                            ticks: Notas,
                            //ticks: 4,
                            tickColor: '#eeeeee'
                        },
                        xaxis: {
                            min: 1,
                            max: CantXAxis,
                            ticks: json.MesesGeneral,
                            tickColor: '#ffffff'
                        }
                    });
                if(Comparacion){
                    $("#Perfil .Right .PerfilEjecutivo .Titulo").html(json.Perfil["Right"]["Titulo"]);
                    $("#Perfil .Right .PerfilEjecutivo .Descripcion").html(json.Perfil["Right"]["Descripcion"]);
                    var plotRight = $.plot(".Charts.Comparison .Right > .Chart", [
                            {
                                label: 'Calidad',
                                data: General[1][0],
                                bars:{
                                    align: "left"
                                }
                            },
                            {
                                label: 'Ejecutivo',
                                data: General[1][1],
                                bars:{
                                    align: "center"
                                }
                            },
                            {
                                label: 'Empresa',
                                data: General[1][2],
                                bars:{
                                    align: "right"
                                }
                            }
                        ],{
                        series: {
                            bars: {
                                show: true,
                                fill: true
                            },
                        },
                        bars: {
                            align: "center",
                            horizontal: false,
                            barWidth: .4
                        },
                        colors: ['green', 'black','red' ],
                        legend: {
                            show: true,
                            position: 'ne',
                            margin: [30, -55]
                        },
                        grid: {
                            borderWidth: 0,
                            hoverable: true,
                            clickable: true
                        },
                        yaxis: {
                            min: 0,
                            max: 5,
                            ticks: Notas,
                            //ticks: 4,
                            tickColor: '#eeeeee'
                        },
                        xaxis: {
                            min: 1,
                            max: CantXAxis,
                            ticks: json.MesesGeneral,
                            tickColor: '#ffffff'
                        }
                    });
                }
                Morris.Bar({
                    element: 'BarChartResumenLeft',
                    data: Items[0]["General"],
                    xkey: 'evaluacion',
                    ykeys: ['calidad', 'ejecutivo', 'empresa'],
                    labels: ['Calidad', 'Ejecutivo', 'Empresa'],
                    gridEnabled: false,
                    gridLineColor: 'transparent',
                    barColors: ['green', 'black', 'red'],
                    resize:true,
                    hideHover: 'auto',
                    horizontal: true,
                    ymin: 0,
                    ymax: 5,
                    numLines: 6,
                });
                if(Comparacion){
                    Morris.Bar({
                        element: 'BarChartResumenRight',
                        data: Items[1]["General"],
                        xkey: 'evaluacion',
                        ykeys: ['calidad', 'ejecutivo', 'empresa'],
                        labels: ['Calidad', 'Ejecutivo', 'Empresa'],
                        gridEnabled: false,
                        gridLineColor: 'transparent',
                        barColors: ['green', 'black', 'red'],
                        resize:true,
                        hideHover: 'auto',
                        horizontal: true,
                        ymin: 0,
                        ymax: 5,
                        numLines: 6,
                    });
                }
                $(".Charts.Comparison .Left .Items").html("");
                for(var i = 0; i < ItemsName.length; i++){
                    var ID = ItemsName[i][0];
                    var Value = ItemsName[i][1];
                    $(".Charts.Comparison .Left .Items").append("<div style='width:100%;'><h4>"+Value+"</h4><div class='Chart' id='Left"+ID+"' style='width:100%;height:250px;'></div></div>");
                }
                setTimeout(function(){
                    for (var i = 0; i < ItemsName.length; i++) {
                        var ID = ItemsName[i][0];
                        var Value = ItemsName[i][1];
                        /*console.log("Inicio de "+Value);
                            console.log(Items[0][0][ID][0]);
                            console.log(Items[0][0][ID][1]);
                            console.log(Items[0][0][ID][2]);
                        console.log("Fin de "+Value);*/
                        $.plot($(".Charts.Comparison .Left .Items").find(".Chart#Left"+ID),
                            [
                                {
                                    label: 'Calidad',
                                    data: Items[0][0][ID][0],
                                    bars:{
                                        align: "left"
                                    }
                                },
                                {
                                    label: 'Ejecutivo',
                                    data: Items[0][0][ID][1],
                                    bars:{
                                        align: "center"
                                    }
                                },
                                {
                                    label: 'Empresa',
                                    data: Items[0][0][ID][2],
                                    bars:{
                                        align: "right"
                                    }
                                }
                            ],
                            {
                                series: {
                                    bars: {
                                        show: true,
                                        fill: true
                                    },
                                },
                                bars: {
                                    align: "center",
                                    horizontal: false,
                                    barWidth: .4
                                },
                                colors: ['green', 'black', 'red'],
                                legend: {
                                    show: true,
                                    position: 'nw',
                                    margin: [0, -55]
                                },
                                grid: {
                                    borderWidth: 0,
                                    hoverable: true,
                                    clickable: true
                                },
                                yaxis: {
                                    min: 0,
                                    max: 5,
                                    ticks: Notas,
                                    //ticks: 4,
                                    tickColor: '#eeeeee'
                                },
                                xaxis: {
                                    min: 1,
                                    max: CantXAxis,
                                    //ticks: 12,
                                    ticks: json.MesesGeneral,
                                    tickColor: '#ffffff'
                                }
                            });
                    }
                },1000);
                if(Comparacion){
                    $(".Charts.Comparison .Right .Items").html("");
                    for(var i = 0; i < ItemsName.length; i++){
                        var ID = ItemsName[i][0];
                        var Value = ItemsName[i][1];
                        $(".Charts.Comparison .Right .Items").append("<div style='width:100%;'><h4>"+Value+"</h4><div class='Chart' id='Right"+ID+"' style='width:100%;height:250px;'></div></div>");
                    }
                    setTimeout(function(){
                        for (var i = 0; i < ItemsName.length; i++) {
                            var ID = ItemsName[i][0];
                            var Value = ItemsName[i][1];
                            /*console.log("Inicio de "+Value);
                                console.log(Items[1][0][ID][0]);
                                console.log(Items[1][0][ID][1]);
                                console.log(Items[1][0][ID][2]);
                            console.log("Fin de "+Value);*/
                            $.plot($(".Charts.Comparison .Right .Items").find(".Chart#Right"+ID),
                                [
                                    {
                                        label: 'Calidad',
                                        data: Items[1][0][ID][0],
                                        bars:{
                                            align: "left"
                                        }
                                    },
                                    {
                                        label: 'Ejecutivo',
                                        data: Items[1][0][ID][1],
                                        bars:{
                                            align: "center"
                                        }
                                    },
                                    {
                                        label: 'Empresa',
                                        data: Items[1][0][ID][2],
                                        bars:{
                                            align: "right"
                                        }
                                    }
                                ],
                                {
                                    series: {
                                        bars: {
                                            show: true,
                                            fill: true
                                        },
                                    },
                                    bars: {
                                        align: "center",
                                        horizontal: false,
                                        barWidth: .4
                                    },
                                    colors: ['green', 'black', 'red'],
                                    legend: {
                                        show: true,
                                        position: 'nw',
                                        margin: [0, -55]
                                    },
                                    grid: {
                                        borderWidth: 0,
                                        hoverable: true,
                                        clickable: true
                                    },
                                    yaxis: {
                                        min: 0,
                                        max: 5,
                                        ticks: Notas,
                                        //ticks: 4,
                                        tickColor: '#eeeeee'
                                    },
                                    xaxis: {
                                        min: 1,
                                        max: CantXAxis,
                                        //ticks: 12,
                                        ticks: json.MesesGeneral,
                                        tickColor: '#ffffff'
                                    }
                                });
                        }
                    },1000);
                }
            },
            error: function(){
            }
        });
    }
    $("<div id='flot-tooltip'></div>").css({
        position: "absolute",
        display: "none",
        padding: "10px",
        color: "#fff",
        "text-align":"right",
        "background-color": "#1c1e21"
    }).appendTo("body");
    $("body").bind("plothover", ".Charts.Comparison .Chart", function (event, pos, item) {

        if (item) {
            var x = item.datapoint[0].toFixed(2),  y = item.datapoint[1];
            $("#flot-tooltip").html("<p class='h4'>" + item.series.label + "</p>" + y)
                .css({top: item.pageY+5, left: item.pageX+5})
                .fadeIn(200);
        } else {
            $("#flot-tooltip").hide();
        }

    });
    $(".inputEmpresa").change(function(){
        var Name = $(this).attr("name");
        if(typeof Name !== "undefined"){
            Name = Name.replace("Empresa","");
            var idEmpresa = $(this).val();
            
            $.ajax({
                type: "POST",
                url: "../includes/calidad/fillEjecutivos.php",
                data: {
                    idEmpresa: idEmpresa
                    },
                dataType: "html",
                success: function(data){
                    $("select[name='Ejecutivo"+Name+"']").html(data);
                    $("select[name='Ejecutivo"+Name+"']").selectpicker('refresh');
                },
                error: function(){
                }
            });
        }
    });
    $(".inputEjecutivo").change(function(){
        var Name = $(this).attr("name");
        if(typeof Name !== "undefined"){
            Name = Name.replace("Ejecutivo","");
            /*$("select[name='Empresa"+Name+"']").val("");
            $("select[name='Empresa"+Name+"']").selectpicker('refresh');*/
        }
    });
    fillEmpresas();
    fillEjecutivos();
    function fillEmpresas(){
        $.ajax({
            type: "POST",
            url: "../includes/calidad/fillEmpresas.php",
            data: { },
            dataType: "html",
            success: function(data){
                $("select[name='EmpresaLeft']").html(data);
                $("select[name='EmpresaLeft']").selectpicker('refresh');
                $("select[name='EmpresaRight']").html(data);
                $("select[name='EmpresaRight']").selectpicker('refresh');
            },
            error: function(){
            }
        });
    }
    function fillEjecutivos(){
        $.ajax({
            type: "POST",
            url: "../includes/calidad/fillEjecutivos.php",
            data: { },
            dataType: "html",
            success: function(data){
                $("select[name='EjecutivoLeft']").html(data);
                $("select[name='EjecutivoLeft']").selectpicker('refresh');
                $("select[name='EjecutivoRight']").html(data);
                $("select[name='EjecutivoRight']").selectpicker('refresh');
            },
            error: function(){
            }
        });
    }
    function fillHumanComparison(Data){
        var Comparacion = false;
        var MandanteLeft = $("select[name='EmpresaLeft']").val();
        var EjecutivoLeft = $("select[name='EjecutivoLeft']").val();
        var MandanteRight = "";
        var EjecutivoRight = "";
        if($("#Comparar").is(':checked')){
            MandanteRight = $("select[name='EmpresaRight']").val();
            EjecutivoRight = $("select[name='EjecutivoRight']").val();
            Comparacion = true;
        }
        var ArrayHumanComparisonLeft = [];
        var ArrayHumanComparisonRight = [];
        var Progress = "";
        $(".HumanComparison .Left").html("");
        $(".HumanComparison .Right").html("");
        $(".HumanComparison .Center").html("");
        $(".SingleHuman .Valores").html("");
        $(".SingleHuman .Caracteristicas").html("");
        /*console.log(Data.Left);
        console.log(Data.Right);*/
        for(var i=0;i<=Data.Left.length - 1;i++){
            var ValorLeft = Number(Data.Left[i].Valor);
            var EtiquetaLeft = ValorLeft+''+Data.Left[i].Etiqueta;
            var PorcentajeLeft = Data.Left[i].Porcentaje;

            if((Comparacion) && (i <= (Data.Right.length - 1))){
                var ValorRight = Number(Data.Right[i].Valor);
                var EtiquetaRight = ValorRight+''+Data.Right[i].Etiqueta;
                var PorcentajeRight = Data.Right[i].Porcentaje;
                var PromedioLeft = 0;
                var PromedioRight = 0;
                if((ValorLeft + ValorRight) > 0){
                    PromedioLeft = (ValorLeft / (ValorLeft + ValorRight)) * 100;
                    PromedioRight  = (ValorRight / (ValorLeft + ValorRight)) * 100;
                }else{
                    PromedioLeft = 0;
                }
                PromedioLeft = PromedioLeft.toFixed(2);
                PromedioRight = PromedioRight.toFixed(2);

                var WidthLeft = PorcentajeLeft == false ? 100 : PromedioLeft;
                var WidthRight = PorcentajeRight == false ? 100 : PromedioRight;
                EtiquetaLeft = WidthLeft == 0 ? "" : EtiquetaLeft;
                EtiquetaRight = WidthRight == 0 ? "" : EtiquetaRight;
                
                /*if(EjecutivoLeft != ""){
                    EtiquetaLeft = ((ValorLeft > 0 ) && (PorcentajeLeft)) ? "Si" : EtiquetaLeft;
                    WidthLeft = "100";
                    EtiquetaLeft = EtiquetaLeft == "" ? "No" : EtiquetaLeft;
                }
                if(EjecutivoRight != ""){
                    EtiquetaRight = ((ValorRight > 0) && (PorcentajeRight)) ? "Si" : EtiquetaRight;
                    WidthRight = "100";
                    EtiquetaRight = EtiquetaRight == "" ? "No" : EtiquetaRight;
                }*/

                Progress = "<div data-toggle='tooltip' data-original-title='"+ PromedioLeft +"%' class='progress progress-lg active add-tooltip ProgresRotate'><div style='color: #333333; width:"+ WidthLeft +"%' class='progress-bar progress-bar-danger'>"+EtiquetaLeft+"</div></div>";
                $(".HumanComparison .Left").append(Progress);
                
                Progress = "<div data-toggle='tooltip' data-original-title='"+ PromedioRight +"%' class='progress progress-lg active add-tooltip'><div style='color: #333333; width:"+ WidthRight +"%' class='progress-bar progress-bar-info'>"+EtiquetaRight+"</div></div>";
                $(".HumanComparison .Right").append(Progress);
            }else{
                var WidthLeft = PorcentajeLeft == false ? 100 : ValorLeft;
                EtiquetaLeft = ValorLeft == 0 ? "No" : EtiquetaLeft;
                EtiquetaLeft = ValorLeft == 100 ? "Si" : EtiquetaLeft;
                WidthLeft = ValorLeft == 0 ? "100" : WidthLeft;
                Progress = "<div data-toggle='tooltip' data-original-title='"+ ValorLeft +"%' class='progress progress-lg active add-tooltip'><div style='color: #333333; width:"+ WidthLeft +"%' class='progress-bar progress-bar-info'>"+EtiquetaLeft+"</div></div>";
                $(".SingleHuman .Valores").append(Progress);
            }
        }
        var Center = '<div class="progress-lg" style="text-align: center;">Total Ejecutivos</div>'+
                    '<div class="progress-lg" style="text-align: center;">Estado Civil (Casado)</div>'+
                    '<div class="progress-lg" style="text-align: center;">Edad</div>'+
                    '<div class="progress-lg" style="text-align: center;">Antiguedad</div>'+
                    '<div class="progress-lg" style="text-align: center;">Tipo Contrato (Plazo Fijo)</div>'+
                    '<div class="progress-lg" style="text-align: center;">Cargas</div>'+
                    '<div class="progress-lg" style="text-align: center;">Sexo (Femenino)</div>'+
                    '<div class="progress-lg" style="text-align: center;">Nacionalidad (Chilena)</div>'+
                    '<div class="progress-lg" style="text-align: center;">Tipo de Ejecutivo (Básico)</div>'+
                    '<div class="progress-lg" style="text-align: center;">Tipo de Contrato (Indefinido)</div>';
        var Rotacion = '<div class="progress-lg" style="text-align: center;">Rotación</div>'+
                    '<div class="progress-lg" style="text-align: center;">Rotación (Despido)</div>'+
                    '<div class="progress-lg" style="text-align: center;">Rotación (Renuncia)</div>';
        if((EjecutivoLeft.trim() == "") && (EjecutivoRight.trim() == "")){
            Center += Rotacion;
        }
        if(Comparacion){
            $(".HumanComparison .Center").append(Center);
        }else{
            $(".SingleHuman .Caracteristicas").append(Center);
        }
    }
});