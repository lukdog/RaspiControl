function submitForm(butt) {
    var id = butt.id;
    id += "_Form";
    $("#" + id).submit();
}

function submitOnEnter(event, id) {
    if (event.which == 13) {
        $("#" + id).submit();
        return false;
    }

    return true;
}

function setFooterWidth() {
    var width = $("footer").width();

    width -= 13;
    width = width / 2;

    $(".footerTab").width(width);
}

function showPanel(butt) {

    var butt_id = butt.id;
    var panel = butt_id.split("_");
    var idPanel = panel[0];
    idPanel += "_Panel";

    var selector = "#" + idPanel;
    $(selector).slideToggle("normal");

}

function execCmd(butt) {
    var about = butt.getAttribute("about");
    if (about == "") {
        about = "Are you sure to execute this script?";
    }
    if (confirm(about)) {
        window.location.href = "output.php?SCRIPTID=" + butt.id;
    }

}

function showTools(butt) {
    var mainMenu = $(".mainSection");
    var toolsMenu = $(".toolsMenu");
    if (mainMenu.css("display") == "block") {
        mainMenu.slideToggle("slow");
        toolsMenu.slideToggle("normal");
        butt.style.background = "#2b2b2b";
        butt.style.color = "white";
    } else {
        mainMenu.slideToggle("normal");
        toolsMenu.slideToggle("slow");
        butt.style.background = "#d6264f";
        butt.style.color = "#2b2b2b";
    }
}

function selectBtn(butt) {
    if (butt.className == "off") {
        butt.className = "on";
        butt.innerHTML = "YES";
    } else {
        butt.className = "off";
        butt.innerHTML = "NO";
    }
}

function setSelectValue(butt) {
    var selected = butt.getAttribute("about");
    var l = selected.length;
    $("li.select > input").val(selected);
    $("li.select > input").width(l * 9);
    $("ul.list").slideToggle("normal");
}