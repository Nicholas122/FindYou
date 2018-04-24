$(document).ready(function () {
    function updateMemoryWidget(data) {
        var memoryUsage = $('#memoryUsage');

        memoryUsage.empty().removeData().circliful({
            animation: 0,
            foregroundColor: '#fa7b5d',
            percent: parseInt(data),
            textSize: 10,
            textStyle: 'font-size: 12px;',
            progressColor: {0: '#5fcc54', 40: '#fa7b5d', 60: '#ff7e76', 90: '#ff3f36'}
        });
    }

    function updateUptime(data) {
        var serverUptime = $('#server-uptime').text(data);
    }

    function getCookie(cname) {
        var name = cname + "=";
        var ca = document.cookie.split(';');
        for(var i=0; i<ca.length; i++) {
            var c = ca[i];
            while (c.charAt(0)==' ') c = c.substring(1);
            if (c.indexOf(name) == 0) return c.substring(name.length,c.length);
        }
        return "";
    }

    setInterval(function () {
        $.get('/admin-widgets', function (data) {
            var response = JSON.parse(data);

            updateMemoryWidget(response.memoryUsage);
            updateUptime(response.serverUptime);
        })
    }, 10000);
});