let chart = null;
let Dashboard = {
    module: () => {
        return "dashboard";
    },

    moduleApi: () => {
        return "api/" + Dashboard.module();
    },

    handelDate: () => {
        Dashboard.getData();
        Dashboard.chartDashboard();
        Dashboard.countPieData();
        Dashboard.dataCard();
        // Dashboard.activity();
    },
    getData: async () => {
        let tableData = $("table#table-data");

        let updateAction = $("#update").val();
        let deleteAction = $("#delete").val();

        let params = {};
        params.tgl_awal = $("#tgl_awal").val();
        params.tgl_akhir = $("#tgl_akhir").val();

        var data = tableData.DataTable({
            processing: true,
            // responsive: true,
            serverSide: true,
            ordering: true,
            autoWidth: false,
            searching: true,
            destroy: true,
            order: [[0, "asc"]],
            aLengthMenu: [
                [10, 25, 50, 100],
                [10, 25, 50, 100],
            ],
            lengthChange: !1,
            language: {
                paginate: {
                    previous: "<i class='mdi mdi-chevron-left'>",
                    next: "<i class='mdi mdi-chevron-right'>",
                },
            },
            drawCallback: function () {
                $(".dataTables_paginate > .pagination").addClass(
                    "pagination-rounded"
                );
            },
            ajax: {
                url: url.base_url(Dashboard.moduleApi()) + `dashboard`,
                type: "POST",
                data: params,
            },
            deferRender: true,
            createdRow: function (row, data, dataIndex) {
                // console.log('row', $(row));
            },
            buttons: ["copy", "excel", "pdf", "colvis"],
            columns: [
                {
                    data: "id",
                    render: function (data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                    },
                },
                {
                    data: "no_sk",
                    render: function (data, type, row) {
                        if (data) {
                            data;
                        } else {
                            data = "-";
                        }
                        return data;
                    },
                },
                {
                    data: "contract_number",
                },
                {
                    data: "seq_number",
                    render: function (data, type, row) {
                        if (data) {
                            data;
                        } else {
                            data = "-";
                        }
                        return data;
                    },
                },
                {
                    data: "user_delegate",
                    render: function (data, type, row) {
                        // Check if data is not null or undefined
                        if (data && data.nama_lengkap) {
                            return data.nama_lengkap; // Return the full name if it exists
                        } else {
                            return "-"; // Return "-" if full name does not exist
                        }
                    },
                },
                {
                    data: "debitur",
                },
                {
                    data: "status",
                    render: function (data, type, row) {
                        // console.log(row);

                        var html = `<span class="badge bg-warning" style="font-size:12px;">${data}</span>`;
                        if (data == "ON PROCESS") {
                            html = `<span class="badge bg-secondary" style="font-size:12px;">${data}</span>`;
                        } else if (data == "APPROVE") {
                            html = `<span class="badge bg-success" style="font-size:12px;">${data}</span>`;
                        } else if (data == "DONE") {
                            html = `<span class="badge bg-primary" style="font-size:12px;">${data}</span>`;
                        } else if (data == "COMPLETE") {
                            html = `<span class="badge bg-secondary" style="font-size:12px;">${data}</span>
                           `;
                        } else if (data == "FINISHED") {
                            html = `<span class="badge bg-dark" style="font-size:12px;">${data}</span>
                           `;
                        } else if (data == "VALIDATED") {
                            html = `<span class="badge bg-primary" style="font-size:12px;">${data}</span>
                           `;
                        }
                        return html;
                    },
                },
                {
                    data: "created_at",
                    render: function (data, type, row) {
                        function formatDate(dateString) {
                            const date = new Date(dateString);
                            const year = date.getFullYear();
                            const month = String(date.getMonth() + 1).padStart(
                                2,
                                "0"
                            );
                            const day = String(date.getDate()).padStart(2, "0");
                            return `${year}-${month}-${day}`;
                        }

                        const pengajuan = formatDate(data);
                        return pengajuan;
                    },
                },
                {
                    data: "id",
                    render: function (data, type, row) {
                        var html =
                            `<button class="btn btn-info" disabled data-id="` +
                            row.id +
                            `" onclick="Dashboard.detailProses(this)"><i class="bx bx-detail"></i></button>`;

                        if (row.status != "DRAFT" && row.status != "ON PROCESS") {
                            html =
                                `<button class="btn btn-info" data-id="` +
                                row.id +
                                `" onclick="Dashboard.detailProses(this)"><i class="bx bx-detail"></i></button>`;
                        }
                        return html;
                    },
                },
            ],
        });

        data
            .buttons()
            .container()
            .appendTo("#datatable-buttons_wrapper .col-md-6:eq(0)"),
            $(".dataTables_length select").addClass(
                "form-select form-select-sm"
            ),
            $("#selection-datatable").DataTable({
                select: {
                    style: "multi",
                },
                language: {
                    paginate: {
                        previous: "<i class='mdi mdi-chevron-left'>",
                        next: "<i class='mdi mdi-chevron-right'>",
                    },
                },
                drawCallback: function () {
                    $(".dataTables_paginate > .pagination").addClass(
                        "pagination-rounded"
                    );
                },
            });
    },

    activity: () => {
        let params = {};
        params.tgl_awal = $("#tgl_awal").val();
        params.tgl_akhir = $("#tgl_akhir").val();

        $.ajax({
            type: "POST",
            url: url.base_url(Dashboard.moduleApi()) + "activity",
            data: params,
            beforeSend: () => {
                message.loadingProses("Proses Pengambilan Data");
            },
            success: function (response) {
                message.closeLoading();
                let item = response;
                let html = "";
                let activity = $("#activity");
                for (let i = 0; i < item.length; i++) {
                    let data = item[i];
                    let formatDate = Dashboard.formatDate(data.created_at);
                    let karyawan_name = data.karyawan_name.toUpperCase();
                    html += `<li class="event-list">
                                 <div class="event-timeline-dot">
                                     <i class="bx bx-right-arrow-circle font-size-18"></i>
                                 </div>
                                 <div class="d-flex">
                                     <div class="flex-shrink-0 me-3">
                                         <h5 class="font-size-14">
                                            ${formatDate}
                                             <i
                                                 class="bx bx-right-arrow-alt font-size-16 text-primary align-middle ms-2"></i>
                                         </h5>
                                     </div>
                                     <div class="flex-grow-1">
                                         <div>
                                             <b>${karyawan_name}</b>
                                         </div>
                                         <div>
                                            ${data.action}
                                         </div>
                                     </div>
                                 </div>
                             </li>`;
                }
                activity.html(html);
            },
            error: function () {
                message.closeLoading();
                message.sweetError("Informasi", "Gagal");
            },
        });
    },

    detailProses: (elm) => {
        let params = {};
        params.id = $(elm).data("id");
        $.ajax({
            type: "POST",
            dataType: "html",
            url: url.base_url(Dashboard.moduleApi()) + "detailProses",
            data: params,
            beforeSend: () => {
                message.loadingProses("Proses Pengambilan Data");
            },
            error: function () {
                message.closeLoading();
                message.sweetError("Informasi", "Gagal");
            },
            success: function (resp) {
                message.closeLoading();
                $("#content-modal-form").html(resp);
                $("#btn-show-modal").trigger("click");
            },
        });
    },

    chartDashboard: async () => {
        $("#loaderBar").show();
        const ctx = document.querySelector("#lineChartM").getContext("2d");
        let lineChart = null;

        const updateChart = (newData) => {
            if (lineChart) {
                lineChart.destroy();
                lineChart = null;
            }

            const labels = newData.map((item) => item.tanggal);
            const data = newData.map((item) => item.jumlah_data);

            lineChart = new Chart(ctx, {
                type: "line",
                data: {
                    labels: labels,
                    datasets: [
                        // {
                        //     label: "Jumlah Data",
                        //     data: data,
                        //     fill: false,
                        //     borderColor: "rgb(75, 192, 192)",
                        //     tension: 0.1,
                        // },
                        {
                            label: "Jumlah Data",
                            data: data,
                            borderWidth: 1,
                            borderColor: "rgb(75, 192, 192)",
                            backgroundColor: "rgba(75, 192, 192, 0.2)",
                            // borderColor: "rgb(201, 203, 207)",
                            // backgroundColor: "rgba(201, 203, 207, 0.2)",
                            type: "bar",
                        },
                    ],
                },
                options: {
                    responsive: true,
                    scales: {
                        x: {
                            title: {
                                display: true,
                                text: "Tanggal",
                                font: {
                                    size: 16,
                                    family: "tahoma",
                                    weight: "normal",
                                },
                            },
                        },
                        y: {
                            beginAtZero: true,
                            display: true,
                            title: {
                                display: true,
                                text: "Jumlah Data",
                                font: {
                                    size: 16,
                                    family: "tahoma",
                                    weight: "normal",
                                },
                            },
                        },
                    },
                },
            });
        };

        const getIkm = () => {
            let params = {};
            params.tgl_awal = $("#tgl_awal").val();
            params.tgl_akhir = $("#tgl_akhir").val();

            $.ajax({
                url: url.base_url(Dashboard.moduleApi()) + `chartDashboard`,
                method: "POST",
                data: params,
                success: (response) => {
                    updateChart(response);
                },
                error: function () {
                    message.closeLoading();
                    message.sweetError("Informasi", "Gagal");
                },
                complete: function () {
                    $("#loaderBar").hide();
                },
            });
        };

        // Memanggil fungsi untuk mendapatkan data ketika halaman di-load
        getIkm();
    },

    countPieData: () => {
        let params = {};
        params.tgl_awal = $("#tgl_awal").val();
        params.tgl_akhir = $("#tgl_akhir").val();

        $.ajax({
            url: url.base_url(Dashboard.moduleApi()) + `countPieData`,
            method: "POST",
            data: params,
            beforeSend: function () {
                $("#loader").show();
            },
            success: function (response) {
                $("#allData").html(response[2]);
                if (chart !== null) {
                    chart.destroy();
                }

                const options = {
                    chart: { height: 300, type: "pie" },
                    series: response[0],
                    labels: response[1],
                    colors: [
                        "#f1b44c",
                        "#34c38f",
                        "#50a5f1",
                        "#556ee6",
                        "#808080",
                        // "#f46a6a",
                    ],
                    legend: {
                        show: true,
                        position: "bottom",
                        horizontalAlign: "center",
                        verticalAlign: "middle",
                        floating: false,
                        fontSize: "14px",
                        offsetX: 0,
                    },
                    responsive: [
                        {
                            breakpoint: 600,
                            options: {
                                chart: { height: 240 },
                                legend: { show: false },
                            },
                        },
                    ],
                };

                chart = new ApexCharts(
                    document.querySelector("#pie_chart"),
                    options
                );
                chart.render();
            },
            error: function () {
                message.closeLoading();
                message.sweetError("Informasi", "Gagal");
            },
            complete: function () {
                $("#loader").hide();
            },
        });
    },

    dataCard: () => {
        let params = {};
        params.tgl_awal = $("#tgl_awal").val();
        params.tgl_akhir = $("#tgl_akhir").val();

        $.ajax({
            type: "POST",
            url: url.base_url(Dashboard.moduleApi()) + `countPieData`,
            data: params,
            beforeSend: () => {
                message.loadingProses("Proses Pengambilan Data");
            },
            error: function () {
                message.closeLoading();
                message.sweetError("Informasi", "Gagal");
            },
            success: function (response) {
                message.closeLoading();
                $("#skDraft").html(response[0][0]);
                $("#skApprove").html(response[0][1]);
                $("#skDone").html(response[0][2]);
                $("#skFinished").html(response[0][3]);
                $("#skComplete").html(response[0][4]);
            },
        });
    },

    formatDate: (dateString) => {
        const options = { day: "2-digit", month: "short" };
        const date = new Date(dateString);
        return date.toLocaleDateString("en-GB", options);
    },

    print: (sURL) => {
        var element = document.getElementById("tesprint");
        html2pdf(element);
    },
};

$(function () {
    Dashboard.getData();
    Dashboard.chartDashboard();
    Dashboard.countPieData();
    Dashboard.dataCard();
    // Dashboard.activity();
});
