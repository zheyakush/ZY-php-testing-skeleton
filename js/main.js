(function($) {
    $(document).ready(function() {
        $(document).on("keydown", "body", function(e) {
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
                $("form button:first").trigger("click");
            }
            // right
            if (keyCode == 39) {
                $("form button:eq(0)").trigger("click");
            }
            // left
            if (keyCode == 37) {
                $("form button:eq(1)").trigger("click");
            }
        });
        $(document).on("click", "input[type='radio'],input[type='checkbox'], label", function(e) {
            e.stopPropagation();
        });
        $(document).on("click", ".row", function() {
            if ($(this).hasClass("disabled")) {
                return false;
            }

            $(".row").find("input[type='radio']").prop("checked", false);
            var el = $(this).find("input");
            if (el.is(':checked') && !el.is(':radio')) {
                el.prop("checked", false);
            } else {
                el.prop("checked", true);
            }

        })
    });


    if (typeof Test === "undefined") {
        Test = {};
    }
    Test.reset = function() {
        if (confirm("Current progress will be lost. Are you sure?")) {
            window.location.replace(window.location.origin + window.location.pathname);
        } else {
            return false;
        }
    };
    Test.proceed = function(btn) {
        if (Test.timer.timeIsUp) {
            alert("Your time is over! Reset your test and try again!");
            return false;
        }
        $.ajax({
            method: "POST",
            data: $(btn).parents("form").serialize(),
            success: function(data) {
                if (typeof data["redirect"] !== "undefined") {
                    window.location.replace(window.location.origin + window.location.pathname)
                }
                if ($("#step" + data.step + " .content").length) {
                    $(".content").html(data.html)
                } else {
                    $("*[id^='step']").remove();
                    $("body").html(data.html)
                }
            }
        });
    };

    Test.next = function(btn) {
        if (Test.timer.timeIsUp) {
            alert("Your time is over! Reset your test and try again!");
            return false;
        }
        $.ajax({
            method: "POST",
            data: $(btn).parents("form").serialize(),
            success: function(data) {
                if (typeof data["redirect"] !== "undefined") {
                    window.location.replace(window.location.origin + window.location.pathname)
                }
                if ($("#step" + data.step + " .content").length) {
                    $(".content").html(data.html)
                } else {
                    $("*[id^='step']").remove();
                    $("body").html(data.html)
                }
            }
        });
        return false;
    };
    Test.prev = function(btn) {
        var nextQ = $(btn).parents("form").find("input[name='q']").val();
        $(btn).parents("form").find("input[name='q']").val(nextQ - 2);
        $.ajax({
            method: "POST",
            data: $(btn).parents("form").serialize(),
            success: function(data) {
                if (typeof data["redirect"] !== "undefined") {
                    window.location.replace(window.location.origin + window.location.pathname)
                }
                if ($("#step" + data.step + " .content").length) {
                    $(".content").html(data.html)
                } else {
                    $("*[id^='step']").remove();
                    $("body").html(data.html)
                }
            }
        });
        return false;
    };

})(jQuery);