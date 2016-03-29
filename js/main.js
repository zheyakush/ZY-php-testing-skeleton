(function($) {
    $(document).ready(function() {
        $("body").keydown(function(e) {
            var keyCode = (e.which) ? e.which : e.keyCode;
            var keys = [49, 50, 51, 52, 53, 54, 55, 56, 57, 97, 98, 99, 100, 101, 102, 103, 104, 105];
            var value = keys.indexOf(keyCode) >= 9 ? keys.indexOf(keyCode) - 9 : keys.indexOf(keyCode);
            if (value != -1) {
                var el = $('input[type=radio],input[type=checkbox]').eq(value);
                if (!el.parents("div").hasClass("disabled")) {
                    if (el.is(':checked') && !el.is(':radio')) {
                        el.prop("checked", false);
                    } else {
                        el.prop("checked", true);
                    }
                }
            }
            if (keyCode == 13) {
                $("form").submit();
            }
        });
        $("input[type='radio'],input[type='checkbox'], label").on("click", function(e) {
            event.stopPropagation();
        });
        $(".row").on("click", function() {
            if ($(this).hasClass("disabled")) {
                return false;
            }

            $(".row").find("input[type='radio']").prop("chec1ked", false);
            var el = $(this).find("input");
            if (el.is(':checked') && !el.is(':radio')) {
                el.prop("checked", false);
            } else {
                el.prop("checked", true);
            }

        })
    });
    document.Test = {};
    document.Test.reset = function() {
        if (confirm("Current progress will be lost. Are you sure?")) {
            window.location.replace(window.location.origin + window.location.pathname);
        } else {
            return false;
        }
    };
    document.Test.proceed = function() {
        return false;
    };
    document.Test.next = function() {
        return false;
    };

})(jQuery);