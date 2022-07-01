$(document).scroll(function () {
    myID = document.getElementById("scrollToBottom");

    var myScrollFunc = function () {
        var y = window.scrollY;
        let className =
            "grid place-items-center rounded-full h-11 w-11 bg-white border border-sky-400 fixed right-8 bottom-8 ease-out animate-bounce hover:shadow-lg text-sky-600 z-50";
        if (y >= 100) {
            myID.className = className + "opacity-1";
        } else {
            myID.className = className + "opacity-0";
        }
    };

    window.addEventListener("scroll", myScrollFunc);
});
