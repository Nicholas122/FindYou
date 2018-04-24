$(document).ready(function () {

    $('.entity-delete').click(function (e) {
        var route = e.currentTarget.dataset.route;

        $.confirm({
            title: 'Confirm Delete!',
            content: 'Are you sure you want to do this?',
            buttons: {
                delete: {
                    btnClass: 'btn-red any-other-class',
                    action: function () {
                        $.ajax({
                            url: route,
                            type: 'DELETE',
                            success: function(result) {
                                location.reload()
                            }
                        });
                    }
                },
                cancel: function () {

                }
            }
        });

    });

});