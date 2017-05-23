var table = $('#example').DataTable();

$("#prevue").click(function() {
    $.fn.dataTable.ext.search.push(
        function(settings, data, dataIndex) {
            return $(table.row(dataIndex).node()).attr('id')!=0;
        }
    );
    table.draw();
});
$("#nonprevue").click(function() {
    $.fn.dataTable.ext.search.push(
        function(settings, data, dataIndex) {
            return $(table.row(dataIndex).node()).attr('id')!=1;
        }
    );
    table.draw();
});
$("#hide").click(function() {
    $.fn.dataTable.ext.search.push(
        function(settings, data, dataIndex) {
            return $(table.row(dataIndex).node()).attr('id')!="Terminé";
        }
    );
    table.draw();
});

$("#reset").click(function() {
    $.fn.dataTable.ext.search.pop();
    table.draw();
});