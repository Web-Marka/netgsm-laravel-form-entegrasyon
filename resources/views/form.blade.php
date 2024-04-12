<form action="{{ route('reservation') }}" method="post" id="reserveForm">
    @csrf
    <div class="row align-items-center">
        <div class="col-md-4 col-xl-3 bdrr1 bdrrn-sm">
            <label>Adınız ve Soyadınız</label>
            <div class="advance-search-field position-relative text-start">
                <div class="box-search">
                    <input class="form-control bgc-f7 bdrs12 ps-0" type="text" name="reserve_name"
                        placeholder="Lütfen adınızı giriniz" required>
                </div>
            </div>
        </div>
        <div class="col-md-4 col-xl-2 bdrr1 bdrrn-sm px20 pl15-sm">
            <div class="mt-3 mt-md-0 px-0">
                <div class="bootselect-multiselect">
                    <label class="fz14">Telefon</label>
                    <div class="advance-search-field position-relative text-start">
                        <div class="box-search">
                            <input class="form-control bgc-f7 bdrs12 ps-0" type="tel" name="reserve_phone"
                                maxlength="11" placeholder="Telefon numaranız" required>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4 col-xl-2 bdrr1 bdrrn-sm px20 pl15-sm">
            <div class="mt-3 mt-md-0">
                <div class="bootselect-multiselect">
                    <label class="fz14">Yetişkin</label>
                    <select class="selectpicker" name="reserve_adult" id="reserve_adult" required>
                        <option selected="" disabled>Lütfen Bir Seçim Yapın</option>
                        <option value="1">1</option>
                        <option value="2">2</option>
                        <option value="3">3</option>
                        <option value="4">4</option>
                        <option value="5">5</option>
                        <option value="6">6</option>
                        <option value="7">7</option>
                        <option value="8">8</option>
                        <option value="9">9</option>
                        <option value="10">10</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="col-md-4 col-xl-2 bdrr1 bdrrn-sm px20 pl15-sm">
            <div class="mt-3 mt-md-0">
                <div class="bootselect-multiselect">
                    <label class="fz14">Çocuk</label>
                    <select class="selectpicker" name="reserve_kid" id="reserve_kid" required>
                        <option selected="" disabled>Lütfen Bir Seçim Yapın</option>
                        <option value="0">0</option>
                        <option value="1">1</option>
                        <option value="2">2</option>
                        <option value="3">3</option>
                        <option value="4">4</option>
                        <option value="5">5</option>
                        <option value="6">6</option>
                        <option value="7">7</option>
                        <option value="8">8</option>
                        <option value="9">9</option>
                        <option value="10">10</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-lg-4 col-xl-3">
            <div class="d-flex align-items-center justify-content-start justify-content-lg-center mt-4 mt-md-0">
                <button class="ud-btn btn-thm ms-2" type="submit">
                    <span class="flaticon-send me-1"></span>
                    Rezervasyon Oluştur
                </button>
            </div>
        </div>
    </div>
</form>
<script type="text/javascript">
    $(document).ready(function() {
        $('#reserveForm').validate({
            rules: {
                reserve_name: {
                    required: true,
                },
                reserve_phone: {
                    required: true,
                },
                reserve_adult: {
                    required: true,
                    min: 1,
                },
                reserve_kid: {
                    required: true,
                },
            },
            messages: {
                reserve_name: {
                    required: 'Lütfen adınızı ve soyadınızı girin.',
                },
                reserve_phone: {
                    required: 'Lütfen telefon numaranızı girin.',
                },
                reserve_adult: {
                    required: 'Lütfen yetişkin sayısını seçin.',
                    min: 'Lütfen geçerli bir değer seçin.',
                },
                reserve_kid: {
                    required: 'Lütfen çocuk sayısını seçin.',
                },
            },
            errorElement: 'p',
            errorPlacement: function(error, element) {
                error.addClass('invalid-feedback');
                error.addClass('text-danger');
                error.addClass('mt-1');
                if (element.attr('name') == 'reserve_adult' || element.attr('name') ==
                    'reserve_kid') {
                    error.insertAfter(element.closest('.bootselect-multiselect'));
                } else {
                    element.closest('.advance-search-field').append(error);
                }
            },
            highlight: function(element, errorClass, validClass) {
                $(element).addClass('is-invalid');
            },
            unhighlight: function(element, errorClass, validClass) {
                $(element).removeClass('is-invalid');
            },
        });

        $.validator.addMethod("valueNotEquals", function(value, element, arg) {
            return arg !== value;
        }, "Lütfen bir değer seçin.");

        $('#reserve_adult, #reserve_kid').rules('add', {
            messages: {
                valueNotEquals: "Lütfen bir değer seçin."
            }
        });

        const url = "{{ route('reservation') }}";
        const csrfToken = "{{ csrf_token() }}";
        $('#reserveForm').on('submit', function(event) {
            event.preventDefault();
            if (validateForm()) {
                $.ajax({
                    url: url,
                    method: 'POST',
                    data: $(this).serialize(),
                    dataType: 'json',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken
                    },
                    success: function(response) {
                        Swal.fire(
                            'Başarılı!',
                            'Size en kısa süre içerisinde dönüş yapılacaktır.',
                            'success'
                        ).then(() => {
                            location.reload();
                        });
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        console.error('Hata:', errorThrown);
                        Swal.fire(
                            'Hata!',
                            'Rezervasyon talep edilirken bir hata oluştu. Eksik alanları doldurarak tekrar deneyin.',
                            'error'
                        );
                    }
                });
            }
        });

        // Form doğrulama işlemi
        function validateForm() {
            var isValid = true;
            $('#reserveForm input, #reserveForm select').each(function() {
                if ($(this).prop('required') && $.trim($(this).val()) === '') {
                    isValid = false;
                    $(this).addClass('is-invalid');
                } else {
                    $(this).removeClass('is-invalid');
                }
            });
            return isValid;
        }
    });
</script>
