/**
 * Google Charts API wrapper
 *
 * @author   Anton Shevchuk
 * @author   Jun Lin
 * @created  09.12.2014 15:27
 * @modefiy  06.15.2017
 */

var chart;
chart = {
    // DOM ID
    id: 'chart',
    // default Chart type
    type: 'PieChart',
    // default Data
    data: null,
    // default options for ChartWrapper
    default: {
      width: 800,
      height: 380
    },
    // options for ChartWrapper
    options: {
      is3D: true,
    },
    /**
     * google.visualization.ChartWrapper
     * @see https://google-developers.appspot.com/chart/interactive/docs/reference#chartwrapperobject
     */
    wrap: null,
    /**
     * google.visualization.ChartEditor
     * @see https://google-developers.appspot.com/chart/interactive/docs/reference#google_visualization_charteditor
     */
    editor: null,
    /**
     * Initial ChartWrapper instance and draw preview
     */
    init: function () {
        // prepare chart wrapper
        chart.wrap = new google.visualization.ChartWrapper();
        chart.wrap.setChartType(chart.type);
        chart.wrap.setContainerId(chart.id);
        chart.wrap.setOptions(chart.options);

        // draw chart
        chart.redraw();
    },
    /**
     * Load new ChartWrapper from URL or other source
     * @param data
     */
    load: function (data) {
        chart.wrap = new google.visualization.ChartWrapper(data);
        chart.wrap.setOption('width', chart.default.width);
        chart.wrap.setOption('height', chart.default.height);
        chart.wrap.draw();
    },
    /**
     * Update chart from table data
     */
    redraw: function () {
        try {
            // prepare data
            chart.data = new google.visualization.arrayToDataTable(hot.getData());
            // update data
            chart.wrap.setDataTable(chart.data);
            // reset options
            chart.wrap.setOption('width', chart.default.width);
            chart.wrap.setOption('height', chart.default.height);
            // redraw chart
            chart.wrap.draw();
        } catch (e) {
            // console.log(e);
        }
    },
    /**
     * Create ChartEditor instance
     * @returns {boolean}
     */
    edit: function () {
        if (!chart.editor) {
            chart.editor = new google.visualization.ChartEditor();

            google.visualization.events.addListener(chart.editor, 'ok', function () {
                chart.wrap = chart.editor.getChartWrapper();
                chart.redraw();
            });
        }

        chart.editor.openDialog(chart.wrap, {});
        return false;
    },
    /**
     * Generate embed code
     * @returns {boolean}
     */
    embed: function () {
        // var width, height;
        // var size = $('input[name=size]:checked', '#options').val().split('x');
        //
        // width = parseInt(size[0]);
        // height = parseInt(size[1]);
        //
        // chart.wrap.setOption('width', width);
        // chart.wrap.setOption('height', height);
        //
        // var data = chart.wrap.toJSON();
        //
        // var path = location.pathname.substr(0, location.pathname.lastIndexOf('/') + 1);
        // var url = '//' + location.host + path + 'embed.html?created=' + (new Date().getTime())
        //     + '#w=' + width + '&h=' + height + '&d=' + data;
        //
        // if (getHashValue('noedit')) {
        //     url += '&noedit=1';
        // }
        //
        // $("#embed-url").val('http:' + url);
        // $("#embed-html").val(
        //     "<iframe src='" + url + "' frameborder='0' width='" + (width + 10) + "' height='" + (height + 10) + "'></iframe>"
        // );
        //
        // $("#embed-iframe")
        //     .width((width > 860) ? 860 : width + 16)
        //     .height((height > 600) ? 600 : height + 10)
        //     .attr('src', url);

        $('#embed-modal').modal();
        return false;
    }
};
