jQuery(document).ready(function($) {
    $("form[name='NOMIKOS_FIFA_WORLD_CUP_SCOREBOARD_FORM'] input[name='topbar']").click(function() {
        if (this.checked)
        $("#topbar_options").animate({
            opacity: "show"
        });
        else
        $("#topbar_options").animate({
            opacity: "hide"
        });
    });

    // in seconds
    var tz = new Date();
    var tz = tz.getTimezoneOffset() * 60;
    var tz_dif_africa = 2 * 3600;

    if ($.cookie("nomikos_fifa_world_cup_scoreboard_tzChangerLocal") == null) {
        $.cookie("nomikos_fifa_world_cup_scoreboard_tzChangerLocal", "1", {path: '/', expires: 0});
    }

    if ($.cookie("nomikos_fifa_world_cup_scoreboard_staticBarClose") == null) {
        $.cookie("nomikos_fifa_world_cup_scoreboard_staticBarClose", "0", {path: '/', expires: 0});
    }

    if ($.cookie("nomikos_fifa_world_cup_scoreboard_tzChangerLocal") == 1) {
        nmksFifaWorldCupScoreboard_settzChanger(true);
    }

    if (document.getElementById("staticBar2b"))
    if ($.cookie("nomikos_fifa_world_cup_scoreboard_staticBarClose") == 1) {
        $("#staticBar2b").attr('checked', false);
    }
    else {
        $("#staticBar2b").attr('checked', true);
    }

    $(".tzChanger").click(function() {
        nmksFifaWorldCupScoreboard_settzChanger(this.checked);
    });

    var tzs = $(".tzShower");
    $.each(tzs, function(i, item){
        var real_date2 = Number(item.parentNode.parentNode.id);
        var real_date2 = real_date2 - (tz - tz_dif_africa);
        var real_date2 = new Date(real_date2 * 1000);
        $(this).countdown({
            until: real_date2,
            labels1: ['Years', 'Months', 'Weeks', 'Days', 'Hours', 'Mins', 'Secs'],
            labels: ['Years', 'Months', 'Weeks', 'Days', 'Hours', 'Mins', 'Secs'],
            });
    });

    if ( $.cookie("nomikos_fifa_world_cup_scoreboard_staticBarClose") == 0 ) {
        nmksFifaWorldCupScoreboard_openstaticBarClose();
    }

    $("#staticBar2").click(function() {
        $("#staticBar2b").attr('checked', false);
        nmksFifaWorldCupScoreboard_closestaticBarClose();
    });

    $("#staticBar2b").click(function() {
        if (this.checked)
            nmksFifaWorldCupScoreboard_openstaticBarClose();
        else
            nmksFifaWorldCupScoreboard_closestaticBarClose();
    });

    $("#staticBar3").click(function() {
        location = location + '?nomikos_refresh=1'
    });

    $(".group_stage2").show();

    $(".nomikos_fifa_world_cup_scoreboard_class_table_group").click(function() {
        var group = $(this).text();
        if ( group == "A"
        || group == "B"
        || group == "C"
        || group == "D"
        || group == "E"
        || group == "F"
        || group == "G"
        || group == "H" ) {
            $("#groupA").hide();
            $("#groupB").hide();
            $("#groupC").hide();
            $("#groupD").hide();
            $("#groupE").hide();
            $("#groupF").hide();
            $("#groupG").hide();
            $("#groupH").hide();
            $(".group_stage2").hide();
            $("#group" + group).animate({
                opacity: "show"
            });
        }
        else if ( group == "Stage 2" ) {
            $("#groupA").hide();
            $("#groupB").hide();
            $("#groupC").hide();
            $("#groupD").hide();
            $("#groupE").hide();
            $("#groupF").hide();
            $("#groupG").hide();
            $("#groupH").hide();
            $(".group_stage2").animate({
                opacity: "show"
            })
        }
    });

    $(".nomikos_fifa_world_cup_scoreboard_class_table_group_page").click(function() {
        var group = $(this).text();
        location = '#_group' + group;
    });

    function nmksFifaWorldCupScoreboard_settzChanger(checked) {
        $(".tzChanger").attr('checked', checked);
        if (checked) {
            $.cookie("nomikos_fifa_world_cup_scoreboard_tzChangerLocal", "1", {path: '/', expires: 0});
        }
        else {
            $.cookie("nomikos_fifa_world_cup_scoreboard_tzChangerLocal", "0", {path: '/', expires: 0});
        }
        var tzt = $(".tzChangerText");
        $.each(tzt, function(i, item){
            var real_date = Number(item.parentNode.parentNode.id);
            if ($.cookie("nomikos_fifa_world_cup_scoreboard_tzChangerLocal") == 1)
                var new_date = real_date - tz + tz_dif_africa;
            else
                var new_date = real_date + tz;
            var date = new Date(new_date * 1000);
            var hours = date.getHours();
            var minutes = date.getMinutes();
            minutes = minutes == 0 ? minutes + '0' : minutes;
            var month = date.getMonth() + 1;
            month = '0' + month;
            var day = date.getDate();
            day = day < 10 ? '0' + day : day;
            var formattedTime = day + '/' + month + ' ' + hours + ':' + minutes;
            $(item).text(formattedTime);
        });
    }

    function nmksFifaWorldCupScoreboard_closestaticBarClose() {
        $("#staticBar").css("display", "none");
        var height1 = $("body").css("padding-top");
        var height2 = $("#staticBar").css("height");
        var re = new RegExp(/px|em|pt/);
        var height1 = height1.replace(re, "");
        var height2 = height2.replace(re, "");
        $("body").css("padding-top", Number(height1) - Number(height2) + 'px');

        $.cookie("nomikos_fifa_world_cup_scoreboard_staticBarClose", "1", {path: '/', expires: 0});
    }

    function nmksFifaWorldCupScoreboard_openstaticBarClose() {
        if ( ! document.getElementById("staticBar"))
            return;

        $("#staticBar").css("display", "block");
        var height1 = $("body").css("padding-top");
        var height2 = $("#staticBar").css("height");
        var re = new RegExp(/px|em|pt/);
        var height1 = height1.replace(re, "");
        var height2 = height2.replace(re, "");
        $("body").css("padding-top", Number(height1) + Number(height2) + 'px');

        $.cookie("nomikos_fifa_world_cup_scoreboard_staticBarClose", "0", {path: '/', expires: 0});

        var counter = 0;
        var divs = $(".staticBar_table_div");
        var rpage = divs.length;
        function showDiv () {
            divs.hide()
                .filter(function (index) { return index == counter % rpage })
                .fadeIn(1000);
                counter++;
        };
        showDiv();
        setInterval(function () {
            showDiv()
        }, 5000);
    }    
})
