
    var showPageTimer;

    function showLoader() {
        showPageTimer = setTimeout(showPage, 0000);
}

    function showPage() {
    document.getElementById("loader").style.display = "none";
    document.getElementById("container").style.display = "block";
}
