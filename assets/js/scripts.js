$(function () {
    // Masks
    var maskBehavior = function (val) {
            return val.replace(/\D/g, '').length === 11
                ? '(00) 00000-0000'
                : '(00) 0000-00009'
        },
        options = {
            onKeyPress: function (val, e, field, options) {
                field.mask(maskBehavior.apply({}, arguments), options)
            },
        }
    $('.form-tel').mask(maskBehavior, options)
    $('.form-cep').mask('00000-000')
    $('.form-cpf').mask('000.000.000-00')
    $('.form-cnpj').mask('00.000.000/0000-00')

    // Floating Labels in CF7
    var formControls = document.querySelectorAll(
        '.wpcf7 .form-control, .wpcf7 .form-select'
    )

    for (var i = 0; i < formControls.length; i++) {
        var input = formControls[i]
        var label = input.parentNode.parentNode.querySelector('label')

        input.addEventListener('focus', function () {
            this.parentNode.parentNode.classList.add('active')
        })

        input.addEventListener('blur', function () {
            var cval = this.value
            if (cval.length < 1) {
                this.parentNode.parentNode.classList.remove('active')
            }
        })

        if (label) {
            label.addEventListener('click', function () {
                var input = this.parentNode.querySelector(
                    '.form-control, .form-select'
                )
                input.focus()
            })
        }
    }

    // Get container offset
    offsetWidth()
    $(window).on('resize', function () {
        offsetWidth()
    })

    function offsetWidth() {
        var containerOffset = $('.container').offset().left

        // Aplicar padding-left apenas em desktop (lg breakpoint = 992px)
        // Evitar aplicar quando modos de contraste estão ativos (podem afetar offset)
        if (
            $(window).width() >= 992 &&
            !$('body').hasClass('high-contrast') &&
            !$('body').hasClass('grayscale-contrast')
        ) {
            $('.offcanvas-offset').css('padding-left', containerOffset + 12)
        } else {
            $('.offcanvas-offset').css('padding-left', '')
        }
        $('.container-offset').css('width', containerOffset)
    }

    // Smooth Scrolling to Anchor Links
    $('.smooth-scroll').bind('click', function (event) {
        var $anchor = $(this)

        $('html, body')
            .stop()
            .animate(
                {
                    scrollTop: $($anchor.attr('href')).offset().top - 300,
                },
                1000
            )

        event.preventDefault()
    })

    // Fancybox
    Fancybox.bind()
})
