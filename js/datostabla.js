jQuery('#thacienda').DataTable({
        "destroy":true,
        "responsive": true,
        "ajax": "scripts/listar_hacienda.php",
        "deferRender": true,        
        "scrollY": 266,
        "paging": false,
        "scrollCollapse": true,
        "dom": '<"top">rt<"bottom"><"clear">',
        "ordering": false,
        "columns": [
          {data:"nrocontrato", title: "Contrato"},
          {data:"apeynom", title: "Vendedor"},
          {data:"categoria", title: "Cat"},
          {data:"edad", title: "Edad"},
          {data:"razatipo", title: "Raza/Tipo"},
          {data:"pelaje", title: "Pelaje"},
          {data:"cantidad", title: "Cab"},
          {data:"destetados", title: "Dest"},
          {data:"alimentacion", title: "Alim"},
          {data:"precioinicial", title: "Precio"},
          {data:"tipoprecio", title: "Tipo"},
          {data:"modificar", title: ""}, //M
          {data:"eliminar", title: ""}, //E
          {data:"fotos", title: ""}  //VIDEO
        ],
        "language":{
                "sProcessing":     "Procesando...",
                "sLengthMenu":     "Mostrar _MENU_ registros",
                "sZeroRecords":    "No se encontraron resultados",
                "sEmptyTable":     "Ning\u00FAn dato disponible en esta tabla",
                "sInfo":           "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
                "sInfoEmpty":      "Mostrando registros del 0 al 0 de un total de 0 registros",
                "sInfoFiltered":   "(filtrado de un total de _MAX_ registros)",
                "sInfoPostFix":    "",
                "sSearch":         "Buscar:",
                "sUrl":            "",
                "sInfoThousands":  ",",
                "sLoadingRecords": "Cargando...",
                "oPaginate": {
                    "sFirst":    "Primero",
                    "sLast":     "\u00DAltimo",
                    "sNext":     "Siguiente",
                    "sPrevious": "Anterior"
                },
                "oAria": {
                    "sSortAscending":  ": Activar para ordenar la columna de manera ascendente",
                    "sSortDescending": ": Activar para ordenar la columna de manera descendente"
                }
            }
    });  