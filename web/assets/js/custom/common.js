$(document).ready(function () {
    $('#memoryUsage').circliful({
        foregroundColor: '#fa7b5d',
        animation: 0,
        textSize: 10,
        textStyle: 'font-size: 12px;',
        progressColor: {0: '#5fcc54', 40: '#fa7b5d', 60: '#ff7e76', 90: '#ff3f36'}
    });

    $("#spaceUsage").circliful({
        animation: 0,
        textSize: 10,
        textStyle: 'font-size: 12px;',
        textColor: '#666',
        progressColor: {0: '#5fcc54', 40: '#fa7b5d', 60: '#ff7e76', 90: '#ff3f36'}
    });
});
