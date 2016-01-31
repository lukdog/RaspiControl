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

    if (confirm(about)) {
        var req = "output.php?SCRIPTID=" + butt.id;
        window.location.href = req;
    }

}