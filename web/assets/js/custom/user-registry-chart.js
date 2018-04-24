$(document).ready(function () {
    var userRegistryStat = JSON.parse($('#chartUserRegistry').attr('data-user-registry-stat'));

    var dataSales = {
        labels: [
            'January', 'February', 'March',
            'April', 'May', 'June', 'July',
            'August', 'September', 'October',
            'November', 'December'],

        series: [userRegistryStat]
    };

    var optionsSales = {
        lineSmooth: false,
        low: Math.min(userRegistryStat),
        high: Math.max(userRegistryStat),
        showArea: true,
        height: "245px",
        axisX: {
            showGrid: false,
        },
        lineSmooth: Chartist.Interpolation.simple({
            divisor: 10
        }),
        showLine: true,
        showPoint: true,
    };

    var responsiveSales = [
        ['screen and (max-width: 640px)', {
            axisX: {
                labelInterpolationFnc: function (value) {
                    return value[0];
                }
            }
        }]
    ];

    Chartist.Line('#chartUserRegistry', dataSales, optionsSales, responsiveSales);
});