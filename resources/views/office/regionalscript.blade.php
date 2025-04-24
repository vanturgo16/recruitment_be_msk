<script>
    var messages = {
        selects: "{{ __('messages.select') }}",
    };

    function resetSelect(selector, placeholder) {
        $(selector).empty().append(`<option value="" selected>${placeholder}</option>`);
    }

    function loadOptions(url, selector, placeholder, dataAttr) {
        $.ajax({
            url: url,
            type: "GET",
            dataType: "json",
            success: function(data) {
                resetSelect(selector, placeholder);
                $.each(data, function(_, value) {
                    $(selector).append(
                        `<option value="${value.nama}" ${dataAttr}>${value.nama}</option>`
                            .replace(':id', value.id)
                            .replace(':kodepos', value.kodepos || '')
                    );
                });
            }
        });
    }

    $('#province').on('change', function() {
        const idProv = $(this).find('option:selected').data('idprov');
        const url = '{{ route("mappingCity", ":id") }}'.replace(':id', idProv);

        resetSelect('#city', '- ' + messages.selects + ' City -');
        resetSelect('#district', '- ' + messages.selects + ' District -');
        resetSelect('#subdistrict', '- ' + messages.selects + ' Subdistrict -');
        $('#postal_code').val('');

        if (idProv) {
            loadOptions(url, '#city', '- ' + messages.selects + ' City -', 'data-idCity=":id"');
        }
    });

    $('#city').on('change', function() {
        const idCity = $(this).find('option:selected').data('idcity');
        const url = '{{ route("mappingDistrict", ":id") }}'.replace(':id', idCity);

        resetSelect('#district', '- ' + messages.selects + ' District -');
        resetSelect('#subdistrict', '- ' + messages.selects + ' Subdistrict -');
        $('#postal_code').val('');

        if (idCity) {
            loadOptions(url, '#district', '- ' + messages.selects + ' District -', 'data-idDistrict=":id"');
        }
    });

    $('#district').on('change', function() {
        const idDistrict = $(this).find('option:selected').data('iddistrict');
        const url = '{{ route("mappingSubDistrict", ":id") }}'.replace(':id', idDistrict);

        resetSelect('#subdistrict', '- ' + messages.selects + ' Subdistrict -');
        $('#postal_code').val('');

        if (idDistrict) {
            loadOptions(url, '#subdistrict', '- ' + messages.selects + ' Subdistrict -', 'data-postalCode=":kodepos" data-idSubDistrict=":id"');
        }
    });

    $('#subdistrict').on('change', function() {
        const postalCode = $(this).find('option:selected').data('postalcode');
        $('#postal_code').val(postalCode);
    });
</script>