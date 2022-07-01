// / DISPLAY THE TABLE
$(document).ready(function () {
    // APPLY FILTER
    let theme = $("#theme");

    $("#applyFilter").click(function () {
        let url = "https://localhost:8000/draft-pasal";
        let paramUrl = getParamUrl(url);
        $.ajax({
            type: "GET",
            url: paramUrl,
            success: function (response) {
                renderData(response);
            },
        });
    });

    function getParamUrl(url) {
        url = addParameter(url, "theme", theme.val(), false);

        return url;
    }

    function renderData(data) {
        console.log(data);
        let pasalContainer = $("#pasal-result");

        data.forEach((element) => {
            let comp =
                "<div class='p-5 bg-white border border-slate-300 rounded-xl'>" +
                "<a data-bs-toggle='collapse' href='#collapseExample' role='button' aria-expanded='false' aria-controls='collapseExample'>" +
                "<div class='text-lg font-bold'>" +
                "                    UU No. 12 Tahun 1990 - <span class='text-cyan-600 '>Perpajakan Negara Republik indonesia</span>" +
                "</div>" +
                "<div class='text-sm'>" +
                "Pasal 1 Ayat 2" +
                "</div>" +
                "</a>" +
                "<div class='collapse' id='collapseExample'>" +
                "<div class='pt-4'>" +
                "Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred nesciunt sapiente ea proident." +
                "</div>" +
                "</div>" +
                "<div class='pt-3 mt-2 flex justify-between items-center border-t border-t-slate-100'>" +
                "<div class='font-bold text-cyan-600'>" +
                "32.39%" +
                "</div>" +
                "<div>" +
                "<div id='ck-button' class='rounded-full border px-3'>" +
                "<label>" +
                "<input class='checkboxes ' type='checkbox' value='1' onchange='toggleChecked(this)'><span>Check</span>" +
                "</label>" +
                "</div>" +
                "</div>" +
                "</div>" +
                "</div>";
            pasalContainer.append(comp);
        });
        console.log(paginate(data, 10, 1));
    }

    const paginate = (array, pageSize, pageNumber) => {
        return array.slice((pageNumber - 1) * pageSize, pageNumber * pageSize);
    };

    $("input:checkbox").change(function () {
        var numberOfChecked = $("input:checkbox:checked").length;
        // console.log(numberOfChecked)
        var selected = [];
        $("input:checkbox.checkboxes").each(function () {
            if ($(this).prop("checked")) {
                selected.push($(this).val());
            }
        });
    });
});

function toggleChecked(el) {
    if ($(el).prop("checked")) {
        $(el).attr("checked", false);
        $(el).siblings("span").html("Checked");
        $(el).parents().eq(1).toggleClass("bg-cyan-600");
    } else {
        $(el).attr("checked", true);
        $(el).siblings("span").html("Check");
        $(el).parents().eq(1).toggleClass("bg-cyan-600");
    }
}
