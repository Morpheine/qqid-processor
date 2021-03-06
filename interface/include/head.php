<head>
    <title>QQID Database View</title>
    <meta HTTP-EQUIV="content-type" CONTENT="text/html; charset=utf-8">
    <link href="include/css/bootstrap.min.css" rel="stylesheet">
    <link href="include/css/jquery.dataTables.min.css" rel="stylesheet">
    <link href="include/css/dataTables.tableTools.min.css" rel="stylesheet">
    <link href="include/css/dataTables.colVis.min.css" rel="stylesheet">
    <link href="include/css/jquery.dataTables.yadcf.css" rel="stylesheet">
    <link href="include/css/chosen.min.css" rel="stylesheet">
    <link href="//cdn.datatables.net/plug-ins/9dcbecd42ad/integration/bootstrap/3/dataTables.bootstrap.css" rel="stylesheet">
    <!-- Custom styles for this template-->
    <link href="./include/css/signin.css" rel="stylesheet">
    <link rel="shortcut icon" href="./include/images/favicon.ico?v=2" />

    <script type="text/javascript" charset="utf-8" language="javascript" src="//code.jquery.com/jquery-1.11.1.min.js"></script>
    <script type="text/javascript" charset="utf-8" language="javascript" src="//cdn.datatables.net/1.10.4/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="include/js/bootstrap.min.js"></script>
    <script type="text/javascript" charset="utf-8" language="javascript" src="//cdn.datatables.net/plug-ins/9dcbecd42ad/integration/bootstrap/3/dataTables.bootstrap.js"></script>
    <script type="text/javascript" charset="utf-8" src="include/js/dataTables.tableTools.min.js"></script>
    <script type="text/javascript" charset="utf-8" src="include/js/dataTables.colVis.min.js"></script>
    <script type="text/javascript" charset="utf-8" src="include/js/jquery.dataTables.yadcf.js"></script>
    <script type="text/javascript" charset="utf-8" src="include/js/chosen.jquery.min.js"></script>
    <style>
        .col-md-0 {
            width: 0%;
        }
        .filters div {
            width: 95% !important;
        }
        .yadcf-filter-wrapper {
            margin-bottom: 10px;
        }
        .filter-clear.ColVis_Button {
            float: none;
        }
        .dataTables_scrollHeadInner {
            margin: 0 auto;
        }
        table {
            white-space: nowrap;
        }
        .paging_full_numbers a.paginate_button {
            color: #d11010 !important;
        }
        .paging_full_numbers a.paginate_active {
            color: #d11010 !important;
        }
    </style>
    <script charset="utf-8">
        $(document).ready(function () {
            table = $('#report').DataTable({
                "searching": true,
                "autowidth":false,
                //Brent Speed Mods
                "bLengthChange": false,
                "paging": true,
//                "serverSide": true,
                "iDisplayLength": 500,
                "orderClasses": false,
                "displayBuffer": 500,
                //End Brent Speed Mods
                "ajax": "controller.php?type=<?php echo $type;?>",
                "dom": 'i<"filter-toggle">C<"clear">T<"clear">lfr<"row"t<"fade hide columnfilterbox col-md-0"W>><"clear">p', // New
//                "dom": 'i<"filter-toggle">C<"clear">T<"clear">lfr<"row"t<"fade hide columnfilterbox col-md-0"W>><"clear">', // Old
                tableTools: {
                    "sSwfPath": "include/swf/copy_csv_xls_pdf.swf",
                    "aButtons": [
                        "print",
                        {
                            "sExtends": "collection",
                            "sButtonText": "Save",
                            "aButtons": [
                                {
                                    "sExtends": "xls",
                                    "sButtonText": "Excel",
                                    "oSelectorOpts": {
                                        page: 'current'
                                    },
                                    "fnCellRender": function (sValue, iColumn, nTr, iDataIndex) {
                                        return '"' + sValue.replace(/<br>|<BR>/g, "\n") + '"';
                                    }
                                },
                                {
                                    "sExtends": "pdf",
                                    "sButtonText": "PDF",
                                    "oSelectorOpts": {
                                        page: 'current'
                                    }
                                }
                            ]
                        }
                    ]
                },
//                "paging": false,
                "scrollX": "90%",
                "scrollY": "65%",
                "language": {
//                    "info": "Showing _END_ of _MAX_ entries",
//                    "infoFiltered": ""
                },
                "initComplete": function(settings, json) {
                    table.columns.adjust();
                }
            });
            yadcf.init(table, <?php echo $filters->getFilters();?>);

            $('.dataTables_scroll').addClass('col-md-12');

            var $toggleFilters = $('<button/>', {
                "text": 'Show Filters',
                "class": 'ColVis_Button pull-right',
                "click": function() {
                    $('.columnfilterbox').toggleClass('hide in col-md-0 col-md-2');
                    $('.dataTables_scroll').toggleClass('col-md-12 col-md-10');
                }
            });

            //BRENT: Let's speed up searching
            // Unbind the default search functionality based on changes
            $('#report_filter input').unbind();


            $('#report_filter input').bind('keyup change', function(e) {
                if (e.keyCode == 13) {
                    table.search(this.value);
                    table.draw();
                }
                else if (this.value < 1 && (e.keyCode == 8 || e.keyCode == 46)) {
                    table.search(this.value);
                    table.draw();
//                    console.log('cleared!');
                }

            });

            //BROKEN: NEED THE INPUT'S VALUE
            var $searchButton = $('<button/>', {
                "text": 'Search',
                "click": function() {
                    table.search(input.value);
                    table.draw();
                },
                "class": 'ColVis_Button filter-clear'
            });
//
//            $('.filter-toggle').append($searchButton);
            // END BRENT

            $('.filter-toggle').append($toggleFilters);
//            $('.filter-toggle').append($searchTest);

            var $clearFilters = $('<button/>', {
                "text": 'Clear Filters',
                "click": function() { yadcf.exResetAllFilters(table); },
                "class": 'ColVis_Button filter-clear'
            });

            $('.filters').append($clearFilters);

            $('.filters').appendTo('.columnfilterbox');
        });
    </script>
</head>