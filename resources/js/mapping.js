import Sortable from "sortablejs";

document.addEventListener("DOMContentLoaded", function () {
    let sourceList = document.getElementById("source-data");
    let targetList = document.getElementById("target-data");

    Sortable.create(sourceList, {
        group: "shared",
        animation: 150
    });

    Sortable.create(targetList, {
        group: "shared",
        animation: 150,
        onEnd: function (evt) {
            let data = [];
            document.querySelectorAll("#target-data li").forEach(item => {
                data.push({
                    source_field: item.getAttribute("data-source"),
                    target_field: item.getAttribute("data-target")
                });
            });

            fetch('/save-mapping', {
                method: "POST",
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify({ mapping: data })
            });
        }
    });
});
