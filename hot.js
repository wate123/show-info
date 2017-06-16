/**
 * HandsOnTable wrapper
 *
 * @author   Anton Shevchuk
 * @created  09.12.2014 15:17
 */
var hot;
hot = {
    // ID
    id: 'hot',
    // instance of {Handsontable}
    instance: null,
    // options of Handsontable
    options: {
        startRows: 5,
        startCols: 10,
        //contextMenu: true,
        colHeaders: true,
        colWidths: 120,
        columnSorting: true,
        rowHeaders: true,
        stretchH: 'all',
        //fixedRowsTop: 1,
        //manualColumnMove: true,
        manualColumnResize: true,
        minSpareRows: 0,
        afterChange: function (changes, source) {
            if (source !== 'loadData') {
                chart.redraw();
            }
        },
        afterColumnSort: function (col, order) {
            hot.sortCol = col;
            hot.sortOrder = order;
            chart.redraw();
        },
        afterGetColHeader: function (col, TH) {
            // nothing for first column
            if (col === false || TH.dataset.done) {
                return;
            }
            var instance = this;

            // change style
            TH.dataset.done = 1;
            TH.style.position = 'relative';
            TH.firstChild.style.display = 'none';

            // create group of elements
            var div = document.createElement('div');
                div.className = "input-group";

            // create input element
            var input = document.createElement('input');
                input.type = 'text';
                input.value = TH.firstChild.textContent;
                input.className = "form-control input-sm col-th";

            div.appendChild(input);

            Handsontable.Dom.addEvent(input, 'change', function (e){
                var headers = instance.getColHeader();
                    headers[col] = input.value;

                instance.updateSettings({
                    colHeaders: headers
                });

                chart.redraw();
            });

            var icon = document.createElement('span');
                icon.className = "glyphicon glyphicon-sort";

            var span = document.createElement('span');
                span.className = "input-group-addon";
                span.appendChild(icon);

            div.appendChild(span);

            Handsontable.Dom.addEvent(icon, 'click', function (e){
                instance.sort(col);
            });

            TH.appendChild(div);
        }
        /*,
        colHeaders: function (col) {

            return col;
        }*/

    },
    // example of data
    data: [
        //['Genre', 'Numbers of my books'],
        ['Science Fiction', 217],
        ['General Science', 203],
        ['Computer Science', 175],
        ['History', 155],
        ['General Fiction', 72],
        ['Fantasy', 51],
        ['Law', 29]
    ],
    sortCol: null,
    sortOrder: null,
    /**
     * Init Handsontable instance
     */
    init: function () {
        // apply example data
        hot.options.data = hot.data;

        // create instance
        hot.instance = new Handsontable(
            document.getElementById(hot.id),
            hot.options
        );
        hot.instance.render();
    },
    /**
     * Setup data from google.visualization.ChartWrapper class
     *   [
     *     [val1, val2, val3],
     *     [val3, val5, val6]
     *   ]
     * @param data
     */
    load: function (data) {
        var i, j, headers = [], ready = [
            [] // cols data
        ];

        // apply column headers
        for (i = 0; i < data.dataTable.cols.length; i++) {
            headers.push(data.dataTable.cols[i].label);
        }
        hot.instance.updateSettings({
            colHeaders: headers
        });

        // apply data
        for (i = 0; i < data.dataTable.rows.length; i++) {
            ready[i] = [];
            for (j = 0; j < data.dataTable.rows[i].c.length; j++) {
                ready[i][j] = data.dataTable.rows[i].c[j].v;
            }
        }
        hot.instance.loadData(ready);
    },
    /**
     * Get not empty data from table
     * @returns {Array}
     */
    getData: function () {
        // retrieve and filter data
        var rawData = hot.instance.getData();

        // filter empty fields
        var cleanData = rawData.filter(function (item) {
            for (var i = 0; i < item.length; i++) {
                if (item[i] !== null && item[i] !== "") {
                    return true;
                }
            }
            return false;
        });
        // apply parseFloat
        cleanData = cleanData.map(function (item) {
            for (var i = 1; i < item.length; i++) {
                if (!isNaN(parseFloat(item[i]))) {
                    item[i] = parseFloat(item[i]);
                }
            }
            return item;
        });
        // apply sort
        if (hot.sortCol !== null) {
            cleanData.sort(function(a, b){
                if (hot.sortOrder) {
                    return a[hot.sortCol] > b[hot.sortCol];
                } else {
                    return a[hot.sortCol] < b[hot.sortCol];
                }
            });
        }

        // retrieve headers
        // var headers = hot.instance.getColHeader();
        var headers = [];

        $('.ht_clone_top table.htCore thead input').each(function(i, el) {
            headers.push($(el).val());
        });

        cleanData.unshift(headers);

        return cleanData;
    },
    /**
     * Clear Table
     * @returns {boolean}
     */
    clear: function () {
        if (confirm("Are sure want to clean current data?")) {
            hot.instance.clear();
        }
        return false;
    },
    /**
     * Add new column
     * @returns {boolean}
     */
    addColumn: function () {
        hot.instance.alter('insert_col');
        return false;
    },

    // Remove a column
    removeColumn: function(){
        hot.instance.alter('remove_col');
        return false;
    },

    /**
     * Add new row
     * @returns {boolean}
     */
    addRow: function () {
        hot.instance.alter('insert_row');
        return false;
    },

    //Remove a row
    removeRow: function(){
        hot.instance.alter('remove_row');
        return false;
    },

    /**
     * Add new rows
     * @param total
     * @returns {boolean}
     */
    addRows: function (total) {
        for (var i = 0; i < total; i++) {
            hot.instance.alter('insert_row');
        }
        return false;
    }
};
