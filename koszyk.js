$(function() {
    var adres = 'koszyk.php';
    var $produkty = $('#produkty');
    var $koszyk = $('#koszyk');

    $produkty.on('click', 'button', function(event) {
        event.preventDefault();
        $.ajax({
            url: adres,
            method: 'POST',
            dataType: 'json',
            data: {
                akcja: 'dodaj',
                id: $(this).data('id')
            }
        })
        .done(function(data) {
            $('#wiadomosc').html(data.wiadomosc);
            $('#koszyk_podsumowanie').addClass('ukryj');
        })
        .fail(function(jqXHR) {
            $('#wiadomosc').html(jqXHR.responseJSON.wiadomosc);
        });
    });

    $koszyk
    .on('click', '#pokaz_koszyk', function(event) {
        event.preventDefault();
        $(event.delegateTarget).find('#koszyk_podsumowanie').removeClass('ukryj').children('.body').load(adres, {akcja: 'pokaz'});
    })
    .on('click', '.zamknij', function(event) {
        event.preventDefault();
        $(event.delegateTarget).find('#koszyk_podsumowanie').addClass('ukryj');
    })
    .on('click', '#produkty_podsumowanie button', function(event) {
        var $self = $(this);
        event.preventDefault();
        $.ajax({
            url: adres,
            method: 'POST',
            dataType: 'json',
            data: {
                akcja: 'usun',
                id: $(this).data('id')
            }
        })
        .done(function(data) {
            $('#wiadomosc').html(data.wiadomosc);
            $self.parent('dd').prev('dt').remove();
            $self.parent('dd').remove();
        })
        .fail(function(jqXHR) {
            $('#wiadomosc').html(jqXHR.responseJSON.wiadomosc);
        });
    })
    .on('click', '#wyczysc_koszyk', function(event) {
        event.preventDefault();
        $('<div/>').load(adres, {akcja: 'wyzeruj'}, function(data) {
            $('#wiadomosc').html(JSON.parse(data).wiadomosc);
            $('#koszyk_podsumowanie').addClass('ukryj');
        });
    });
});