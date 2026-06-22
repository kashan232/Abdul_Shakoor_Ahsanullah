<!-- JavaScript Libraries -->
<script src="{{ asset('assets/global/js/jquery-3.6.0.min.js') }}"></script>
<script src="{{ asset('assets/global/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('assets/admin/js/vendor/bootstrap-toggle.min.js') }}"></script>
<script src="{{ asset('assets/admin/js/vendor/jquery.slimscroll.min.js') }}"></script>

<!-- IziToast for Notifications -->
<link rel="stylesheet" href="{{ asset('assets/global/css/iziToast.min.css') }}">
<script src="{{ asset('assets/global/js/iziToast.min.js') }}"></script>


<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.min.js" integrity="sha384-+sLIOodYLS7CIrQpBjl+C7nPvqq+FbNUBDunl/OZv93DB7Ln/533i8e/mZXLi/P+" crossorigin="anonymous"></script>
<script>
    $(document).ready(function() {
        $('#example').DataTable({
            dom: 'Bfrtip',
            buttons: [
                'copyHtml5',
                'excelHtml5',
                'csvHtml5',
                'pdfHtml5'
            ]
        });
    });

    (function($) {
        "use strict";
        $('.generatePassword').on('click', function() {
            $(this).siblings('[name=password]').val(generatePassword());
        });

        $('.cuModalBtn').on('click', function() {
            let passwordField = $('#cuModal').find($('[name=password]'));
            let label = passwordField.parents('.form-group').find('label')
            if ($(this).data('resource')) {
                passwordField.removeAttr('required');
                label.removeClass('required')
            } else {
                passwordField.attr('required', 'required');
                label.addClass('required')
            }
        });

        function generatePassword(length = 12) {
            let charset = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()_+<>?/";
            let password = '';

            for (var i = 0, n = charset.length; i < length; ++i) {
                password += charset.charAt(Math.floor(Math.random() * n));
            }

            return password
        }
    })(jQuery);
</script>
<script>
    "use strict";

    function notify(status, message) {
        if (typeof message == 'string') {
            iziToast[status]({
                message: message,
                position: "topRight"
            });
        } else {
            $.each(message, function(i, val) {
                iziToast[status]({
                    message: val,
                    position: "topRight"
                });
            });
        }
    }
</script>

<script src="{{ asset('assets/admin/js/nicEdit.js') }}"></script>
<script src="{{ asset('assets/admin/js/vendor/select2.min.js') }}"></script>
<script src="{{ asset('assets/admin/js/app.js?v=1') }}"></script>
<script src="{{ asset('assets/admin/js/cu-modal.js') }}"></script>
<script>
    "use strict";
    bkLib.onDomLoaded(function() {
        $(".nicEdit").each(function(index) {
            $(this).attr("id", "nicEditor" + index);
            new nicEditor({
                fullPanel: true
            }).panelInstance('nicEditor' + index, {
                hasPanel: true
            });
        });
    });

    (function($) {

        $(document).on('mouseover ', '.nicEdit-main,.nicEdit-panelContain', function() {
            $('.nicEdit-main').focus();
        });

        $("form").on('submit', function(e) {
            let form = $(this)[0];
            if ($(form).find('.nicEdit').length == 0) {
                e.preventDefault();
                $(this).find('[type="submit"]').attr("disabled", "disabled");
                form.submit();
            }
        });

    })(jQuery);
</script>

<!-- Tracking scripts removed for performance -->
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

<script>
    "use strict";
    window.onload = function() {

        var options = {
            series: [{
                    name: 'Total Purchase',
                    data: []
                },
                {
                    name: 'Total Purchase Return',
                    data: []
                },
                {
                    name: 'Total Sale',
                    data: []
                },
                {
                    name: 'Total Sale Return',
                    data: []
                }
            ],
            chart: {
                type: 'bar',
                height: 417,
                toolbar: {
                    show: false
                }
            },
            plotOptions: {
                bar: {
                    horizontal: false,
                    columnWidth: '50%',
                    endingShape: 'rounded'
                },
            },
            dataLabels: {
                enabled: false,
            },
            stroke: {
                show: true,
                width: 2,
                colors: ['transparent']
            },
            xaxis: {
                categories: []
            },
            yaxis: {
                title: {
                    text: "USD",
                    style: {
                        color: '#7c97bb'
                    }
                }
            },
            grid: {
                xaxis: {
                    lines: {
                        show: false
                    }
                },
                yaxis: {
                    lines: {
                        show: false
                    }
                },
            },
            fill: {
                colors: ['#008ffb', '#fbb225', '#00e396', '#ea5455'],
                opacity: 1
            },
            tooltip: {
                y: {
                    formatter: function(val) {
                        return `$ ${val}`
                    }
                }
            },
            legend: {
                markers: {
                    width: 12,
                    height: 12,
                    strokeWidth: 0,
                    strokeColor: '#fff',
                    fillColors: ['#008ffb', '#fbb225', '#00e396', '#ea5455'],
                    radius: 12,
                },
            }
        };

        var chart = new ApexCharts(document.querySelector("#apex-bar-chart"), options);
        chart.render();
    }
</script>
<script>
    if ($('li').hasClass('active')) {
        $('#sidebar__menuWrapper').animate({
            scrollTop: eval($(".active").offset().top - 320)
        }, 500);
    }

    // function for active deactive sidebar option
    let sidebarItems = document.querySelectorAll(".sidebar-menu-item a");

    sidebarItems.forEach(function (item) {
        // Check if the link matches the current URL
        if (item.href === window.location.href) {
            item.parentElement.classList.add("active");
            
            // Expand the parent dropdown category
            let submenu = item.closest('.sidebar-submenu');
            if (submenu) {
                submenu.style.display = 'block';
                let dropdown = submenu.closest('.sidebar-dropdown');
                if (dropdown) {
                    dropdown.classList.add("active");
                    dropdown.classList.add("open");
                }
            }
        }
    });
    
</script>

</body>

</html>