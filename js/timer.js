(function($, Test) {

    if (typeof Test.timer === "undefined") {
        Test.timer = {};
    }

    Test.timer.init = function() {
        if (typeof Test.timer.setMinutes === "undefined") {
            Test.timer.type = "increasing";
            Test.timer.seconds = 0;
        } else {
            Test.timer.type = "decreasing";
            Test.timer.seconds = Test.timer.setMinutes * 60;
        }
        Test.timer.timeIsUp = false;
    };

    Test.timer.toString = function() {
        var hours = Math.floor(Test.timer.seconds / 3600);
        var minutes = Math.floor((Test.timer.seconds-hours * 3600) / 60);
        var seconds = Test.timer.seconds - hours * 3600 - minutes * 60;
        hours = hours < 10 ? "0" + hours : hours;
        minutes = minutes < 10 ? "0" + minutes : minutes;
        seconds = seconds < 10 ? "0" + seconds : seconds;


        return hours + ":" + minutes + ":" + seconds;
    };
    Test.timer.run = function() {
        var interval = setInterval(function() {
            if (Test.timer.type === "decreasing") {
                $("#timer").text(Test.timer.toString());
                if (Test.timer.seconds === 0 && null !== Test.timer.setMinutes) {
                    Test.timer.timeIsUp = true;
                    $("button").hide();
                    $("#timer").removeClass("inProgress").addClass("runOut");
                    clearInterval(interval);
                }
                Test.timer.seconds -= 1;
            }
            if (Test.timer.type === "increasing") {
                Test.timer.seconds += 1;
                $("#timer").text(Test.timer.toString());
            }
        }, 1000);
    }

})(jQuery, Test);