function setSortable() {
    $("#sortable-input").sortable({
        handle: ".header",
        // containment: "#sortable-input",
        update: function () {
            updateLabel();
        },
    });

    $("#sortable-input .content").sortable({
        handle: ".content-grab",
        // containment: ".content",
        update: function (event, item) {
            updateLabel();
        },
    });
}

const updateLabel = () => {
    var listItems = $("#sortable-input li");
    listItems.each(function (idx, li) {
        // CHANGING PASAL TITLE
        var product = $(li).find(".title");
        product.html(`Pasal ${idx + 1}`);

        var listInputItems = $(li).find(".content-item");
        listInputItems.each(function (i, listInput) {
            var ayatInput = $(listInput).find(".ayat-input");
            ayatInput.attr("name", `pasal~${idx + 1} ayat~${i + 1}`);
            ayatInput.attr("placeholder", `Ayat ${i + 1}`);
        });
    });
};

function addPasal(el) {
    let newPasal =
        "<li class='border border-slate-300 rounded-lg overflow-hidden shadow bg-white'>" +
        "<div class='header py-2 flex justify-center hover:bg-slate-100 border-b border-b-slate-300 cursor-move'>" +
        "<img src='/assets/svg/grabber.svg' class='rotate-90'>" +
        "</div>" +
        "<div class='title px-3 py-2 pb-3 font-bold'>Pasal</div>" +
        "<div class='content'>" +
        "<div class='content-item px-3 grid grid-cols-[1fr_auto_auto]'>" +
        "<textarea name='' class='ayat-input w-full h-5 border-0 border-b-2 border-b-slate-400  overflow-y-auto focus:ring-0 p-2 pt-0' required></textarea>" +
        "<div class='content-grab mb-5 cursor-move flex items-center hover:bg-slate-200 h-full'>" +
        "<img src='/assets/svg/grabber.svg' class='p-2'>" +
        "</div>" +
        "<div class='content-grab mb-5 cursor-pointer flex items-center h-full'>" +
        "<img src='/assets/svg/x.svg' onclick='removeInput(this)' class='p-2 rounded-full hover:bg-slate-200'>" +
        "</div>" +
        "</div>" +
        "</div>" +
        "<div class='flex justify-end gap-1 border-t border-t-slate-200 mt-2 p-2'>" +
        "<button type='button' onclick='addInput(this)'>" +
        " <img src='/assets/svg/plus-circle.svg' class='p-2 rounded-full fill-slate-600 hover:fill-slate-900 hover:bg-slate-200'>" +
        "</button>" +
        "<button type='button' onclick='removePasal(this)'>" +
        " <img src='/assets/svg/trash.svg' class='p-2 rounded-full fill-slate-600 hover:fill-slate-900 hover:bg-slate-200'>" +
        "</button>" +
        "</div>" +
        "</li>";
    $(el).siblings("#sortable-input").append(newPasal);
    updateLabel();
    renderInput();
    setSortable();
}

function addInput(el) {
    let newInput =
        "<div class='content-item px-3 grid grid-cols-[1fr_auto_auto]'>" +
        "<textarea name='' class='ayat-input w-full h-5 border-0 border-b-2 border-b-slate-400  focus:ring-0 p-2 pt-0' required></textarea>" +
        "<div class='content-grab mb-5 cursor-move flex items-center hover:bg-slate-200 h-full'>" +
        "<img src='/assets/svg/grabber.svg' class='p-2'>" +
        "</div>" +
        "<div class='content-grab mb-5  flex items-center h-full'>" +
        "<img src='/assets/svg/x.svg' onclick='removeInput(this)' class='p-2 rounded-full hover:bg-slate-200'>" +
        "</div>" +
        "</div>";
    $(el).parent().siblings(".content").append(newInput);
    updateLabel();
    renderInput();
}

function removeInput(el) {
    // reomve the curernt input
    $(el).parents().eq(1).remove();
    updateLabel();
    renderInput();
}

function removePasal(el) {
    // remove curernt pasal
    $(el).parents().eq(1).remove();
    updateLabel();
    renderInput();
}

function renderInput() {
    $("textarea")
        .each(function () {
            this.setAttribute(
                "style",
                "height:" + this.scrollHeight + "px;overflow-y:hidden;"
            );
        })
        .on("input", function () {
            this.style.height = "0px";
            this.style.minHeight = "1rem";
            this.style.height = this.scrollHeight + "px";
        });
}
renderInput();
updateLabel();
setSortable();
