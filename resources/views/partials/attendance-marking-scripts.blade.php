<script>
    (function () {
        var root = document.getElementById('attMarking');
        if (!root) return;
        var yearUrl = root.getAttribute('data-year-url');
        var periodBase = root.getAttribute('data-period-base');
        var scopeId = root.getAttribute('data-scope-id');
        var monthEl = document.getElementById('month');
        var currentMonth = monthEl ? monthEl.getAttribute('data-current') : null;

        function loadMonths(preselect) {
            var year = document.getElementById('year').value;
            if (!year) return;
            $.ajax({
                type: "GET",
                url: yearUrl + "?year=" + year + "&intakeid=" + scopeId,
                success: function (res) {
                    var $m = $("#month").empty();
                    $.each(res || {}, function (key, value) {
                        $m.append('<option value="' + key + '"' + (key == preselect ? ' selected' : '') + '>' + value + '</option>');
                    });
                }
            });
        }
        loadMonths(currentMonth);
        $('#year').change(function () { loadMonths(null); });
        $('#month').change(function () {
            var year = document.getElementById('year').value;
            var month = $(this).val();
            if (year && month) { window.location = periodBase + "/" + scopeId + "/" + year + "/" + month; }
        });

        $('.bulk-set').on('click', function () {
            $('.att-status input[value="' + $(this).data('status') + '"]').prop('checked', true);
            updateSummary();
        });

        function updateSummary() {
            var counts = { present: 0, absent: 0, late: 0, excused: 0 };
            $('.att-status input:checked').each(function () { counts[$(this).val()]++; });
            $('#attSummary').html(
                '<span class="badge badge-success mr-1">Present ' + counts.present + '</span>' +
                '<span class="badge badge-danger mr-1">Absent ' + counts.absent + '</span>' +
                '<span class="badge badge-warning mr-1">Late ' + counts.late + '</span>' +
                '<span class="badge badge-info">Excused ' + counts.excused + '</span>'
            );
        }
        $(document).on('change', '.att-status input', updateSummary);
        updateSummary();
    })();
</script>
