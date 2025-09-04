/**
 * Plausible plugin for Craft CMS
 *
 * Plausible JS
 *
 * @author    Sean Hill
 * @copyright Copyright (c) 2021 Sean Hill
 * @link      https://shorn.co.uk
 * @package   Plausible
 * @since     1.0.0
 */

var chart = new frappe.Chart("#chart", {
    data: data,
    type: 'line',
    axisOptions: {
    	xIsSeries: true
    },
    lineOptions: {
	    regionFill: 1,
	    dotSize: 5
	},
    tooltipOptions: {
        formatTooltipY: d => d + ' visitors',
    },
    height: 250,
    colors: ['#0594D1']
})

setTimeout(() => {
  	this.chart.draw()
}, 100)

window.onresize = this.chart.draw();
