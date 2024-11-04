export default class Account {
    static AccountInit() {
        console.log("yes");
        $("#tab1").addClass("tab-active");
        $(".all").show();

        $("#tab1").on("click", function () {
            console.log("All");
            $("#tab1").addClass("tab-active");
            $("#tab2").removeClass("tab-active");

            $(".all").show();
            $(".subject_con").hide();
            $(".subject_div").hide();
        });

        $("#tab2").on("click", function () {
            console.log("Subject");

            $("#tab2").addClass("tab-active");
            $("#tab1").removeClass("tab-active");

            $(".all").hide();
            $(".subject_con").show();
            $(".subject_div").show();
        });

        $(".fil_values").click(function () {
            $(".fil_values").removeClass("active");
            $(this).addClass("active");

            var academic_year = $('select[name="academic_year"]').val();
            Account.getDayGraph($(this).attr("id"));
        });

        $('select[name="academic_year"]').on("change", function () {
            var academic_year = $(this).val();
            const currentUrl =
                window.location.href.split("?")[0] +
                "?academic_year=" +
                academic_year;
            window.location.href = currentUrl;
        });
    }

    static MoneyChat(income, expense, month, profit_data) {
        $("#moneychart").html("");
        console.log(income, expense, month);
        var options = {
            series: [
                {
                    name: "Income",
                    data: income,
                },
                {
                    name: "Expense",
                    data: expense,
                },
                {
                    name: "Profit",
                    data: profit_data,
                },
            ],
            chart: {
                height: 240,
                type: "area",
                toolbar: {
                    show: false,
                    tools: {
                        download: false,
                    },
                },
            },
            dataLabels: {
                enabled: false,
            },
            stroke: {
                curve: "straight",
                width: 3,
            },
            fill: {
                opacity: 0,
                gradient: {
                    shade: "dark",
                    type: "horizontal",
                    shadeIntensity: 0.5,
                    gradientToColors: undefined,
                    inverseColors: false,
                    opacityFrom: 0,
                    opacityTo: 0,
                    stops: [0, 50, 100],
                    colorStops: [],
                },
            },
            tooltip: {
                enabled: true,
                theme: false,
            },
            legend: {
                show: true,
                position: "top",
                fontSize: "14px",
                horizontalAlign: "right",
                offsetY: 10,
                markers: {
                    width: 12,
                    height: 12,
                    radius: 12,
                },
                itemMargin: {
                    horizontal: 20,
                    vertical: 0,
                },
            },
            colors: ["#5585FF", "#FF5555", "#bbdc35"],
            xaxis: {
                type: "category",
                categories: month,
            },
        };

        var chart = new ApexCharts(
            document.querySelector("#moneychart"),
            options
        );
        chart.render();
    }

    static ExpenseChart(category_name, category_data, total_graph) {
        console.log(category_name, category_data);
        var data = category_data;
        var total = total_graph;
        var colors = [];
        category_data.forEach((category, i) => {
            console.log(i);
            var color = Account.getRandomColor();
            colors.push(color);
            $(`.round_icon${i}`).css("background-color", color);
        });
        //p.css("background-color", "red");
        //var colors = ["#55FFAD"];

        var options = {
            series: data.concat(total),
            chart: {
                type: "donut",
                height: 250,
            },
            labels: category_name.concat(""),
            colors: colors.concat("#FAF9F9"),
            plotOptions: {
                pie: {
                    donut: {
                        size: "85%",
                        labels: {
                            show: true,
                            name: {
                                show: true,
                                fontSize: "16px",
                            },
                            value: {
                                show: true,
                                fontSize: "16px",
                                color: "#000",
                                offsetY: 0,
                                formatter: function (val) {
                                    return "₦" + val;
                                },
                            },
                            total: {
                                show: true,
                                label: "Income",
                                color: "#000",
                                fontSize: "14px",
                                formatter: function (w) {
                                    return "₦" + total;
                                },
                            },
                        },
                    },
                },
            },
            dataLabels: {
                style: {
                    fontSize: "0px",
                },
            },
            stroke: {
                show: false,
            },
            legend: {
                show: false,
                position: "bottom",
                fontSize: "14px",
                horizontalAlign: "left",
                offsetY: 10,
                markers: {
                    width: 12,
                    height: 12,
                    radius: 12,
                },
                itemMargin: {
                    horizontal: 20,
                    vertical: 0,
                },
            },
        };

        var chart = new ApexCharts(
            document.querySelector("#expensechart"),
            options
        );
        chart.render();
    }

    static GradeBarChart(
        subjects,
        subject_ids,
        exam_ids,
        class_ids,
        academic_year,
        term
    ) {
        var subject = subjects;
        var subject_id = subject_ids;
        var exam_id = exam_ids;
        var class_id = class_ids;
        var ac_year = academic_year;
        var ac_term = term;
        var percentage = [];
        var axiosPromises = [];

        subject_id.forEach(function (id) {
            // Push the Axios promise to the array
            axiosPromises.push(
                axios.get(
                    "getsubject_percentage" +
                        "?id=" +
                        id +
                        "&ac_year=" +
                        ac_year +
                        "&ac_term=" +
                        ac_term +
                        "&class_id=" +
                        class_id
                )
            );
        });
        Promise.all(axiosPromises)
            .then(function (responses) {
                // Loop through the responses and push data to the 'percentage' array
                responses.forEach(function (response) {
                    percentage.push(response.data.sub_percentage);
                });

                $("#subjectbarchart").html("");

                var colors = [];
                // var color = Account.getRandomColor();
                // colors.push(color);
                var options = {
                    series: [
                        {
                            name: "",
                            data: percentage,
                        },
                    ],

                    chart: {
                        height: 350,
                        type: "bar",
                        events: {
                            click: function (chart, w, e) {
                                // console.log(chart, w, e)
                            },
                        },
                    },
                    fill: {
                        colors: ["#FF0000", "#006400"],
                    },

                    title: {
                        text: "Subject Performance",
                        align: "left",
                        margin: 10,
                        offsetX: 0,
                        offsetY: 0,
                        floating: false,
                        style: {
                            fontSize: "14px",
                            fontWeight: "bold",
                            fontFamily: "Arial, sans-serif",
                            color: "#333", // Change the color as needed
                        },
                    },
                    colors: colors.concat("#BD02FF"),
                    plotOptions: {
                        bar: {
                            borderRadius: 4,
                            columnWidth: "35%",
                            distributed: true,
                        },
                    },
                    dataLabels: {
                        enabled: false,
                    },
                    legend: {
                        show: false,
                    },
                    xaxis: {
                        categories: subject,

                        labels: {
                            style: {
                                colors: colors,
                                fontSize: "12px",
                            },
                        },
                    },
                    yaxis: {
                        show: false, // Hide left y-axis labels and axis line
                        labels: {
                            formatter: function (val) {
                                return val + "%";
                            },
                        },
                    },

                    responsive: [
                        {
                            breakpoint: 480,
                            options: {
                                chart: {
                                    width: 200,
                                },
                                legend: {
                                    show: false,
                                },
                            },
                        },
                    ],
                };

                var chart = new ApexCharts(
                    document.querySelector("#subjectbarchart"),
                    options
                );
                chart.render();
            })
            .catch(function (error) {
                // Handle any errors
                console.error("Error fetching exam data:", error);
            });
    }

    static GradePieChart(results) {
        var result = results;
        var options = {
            series: result,
            chart: {
                width: 320,
                type: "pie",
            },
            title: {
                text: "Analysis",
                align: "left",
                margin: 10,
                offsetX: 0,
                offsetY: 0,
                floating: false,
                style: {
                    fontSize: "14px",
                    fontWeight: "bold",
                    fontFamily: "Arial, sans-serif",
                    color: "#333", // Change the color as needed
                },
            },
            labels: ["Promoted", "Repeated"],
            dataLabels: {
                enabled: true,
                dropShadow: {
                    blur: 3,
                    opacity: 0.8,
                },
            },
            legend: {
                show: true,
                position: "bottom",
            },

            responsive: [
                {
                    breakpoint: 480,
                    options: {
                        chart: {
                            width: 150,
                        },
                        legend: {
                            // position: "bottom",
                            show: false,
                        },
                    },
                },
            ],
        };
        var chart = new ApexCharts(
            document.querySelector("#subjectpiechart"),
            options
        );
        chart.render();
    }

    static GradeBarChart1(
        subjects,
        subject_ids,
        exam_ids,
        class_ids,
        academic_year,
        term
    ) {
        var subject = subjects;
        var subject_id = subject_ids;
        var exam_id = exam_ids;
        var class_id = class_ids;
        var ac_year = academic_year;
        var ac_term = term;
        var scores = [];
        var students = [];
        var axiosPromises = [];

        exam_id.forEach(function (id) {
            // Push the Axios promise to the array
            axiosPromises.push(
                axios.get(
                    "getexam_report_result" +
                        "?id=" +
                        id +
                        "&ac_year=" +
                        ac_year +
                        "&ac_term=" +
                        ac_term +
                        "&class_id=" +
                        class_id +
                        "&subject_id" +
                        subject_id
                )
            );
        });

        Promise.all(axiosPromises)
            .then(function (responses) {
                // Loop through the responses and push data to the 'percentage' array
                responses.forEach(function (response) {
                    if (
                        response.data &&
                        response.data.score !== undefined &&
                        response.data.student !== null
                    ) {
                        scores.push(response.data.score);
                        students.push(response.data.student);
                    } else {
                        console.error(
                            "Incomplete or missing data in response:",
                            response.data
                        );
                        // Handle the case where data is incomplete or missing
                        // For example, you could skip adding this data to the arrays
                        // or use default values instead
                    }
                });
                console.log(subject);
                console.log(students);
                console.log(scores);
                // Render chart only if data is available
                if (scores.length > 0 && students.length > 0) {
                    $("#subjectbarchart").html("");

                    var colors = [];
                    // var color = Account.getRandomColor();
                    // colors.push(color);
                    // var dataSeries = students.map(function (student, index) {
                    //     return {
                    //         name: student,
                    //     };
                    // });
                    var options = {
                        series: [
                            {
                                name: "",
                                data: scores,
                            },
                        ],

                        chart: {
                            height: 350,
                            type: "bar",
                            events: {
                                click: function (chart, w, e) {
                                    // console.log(chart, w, e)
                                },
                            },
                        },
                        fill: {
                            colors: ["#FF0000", "#006400"],
                        },

                        title: {
                            text: "Subject Performance",
                            align: "left",
                            margin: 10,
                            offsetX: 0,
                            offsetY: 0,
                            floating: false,
                            style: {
                                fontSize: "14px",
                                fontWeight: "bold",
                                fontFamily: "Arial, sans-serif",
                                color: "#333", // Change the color as needed
                            },
                        },
                        colors: colors.concat("#BD02FF"),
                        plotOptions: {
                            bar: {
                                borderRadius: 4,
                                columnWidth: "35%",
                                distributed: true,
                            },
                        },
                        dataLabels: {
                            enabled: false,
                        },
                        legend: {
                            show: false,
                        },
                        xaxis: {
                            categories: subject,

                            labels: {
                                style: {
                                    colors: colors,
                                    fontSize: "12px",
                                },
                            },
                        },
                        yaxis: {
                            show: false, // Hide left y-axis labels and axis line
                        },

                        responsive: [
                            {
                                breakpoint: 480,
                                options: {
                                    chart: {
                                        width: 200,
                                    },
                                    legend: {
                                        show: false,
                                    },
                                },
                            },
                        ],
                    };

                    var chart = new ApexCharts(
                        document.querySelector("#subjectbarchart1"),
                        options
                    );
                    chart.render();
                } else {
                    console.error(
                        "No valid data available for rendering the chart."
                    );
                }
            })
            .catch(function (error) {
                // Handle any errors
                console.error("Error fetching exam data:", error);
            });
    }

    static GradePieChart1(results) {
        var result = results;
        var options = {
            series: result,
            chart: {
                width: 320,
                type: "pie",
            },
            title: {
                text: "Analysis",
                align: "left",
                margin: 10,
                offsetX: 0,
                offsetY: 0,
                floating: false,
                style: {
                    fontSize: "14px",
                    fontWeight: "bold",
                    fontFamily: "Arial, sans-serif",
                    color: "#333", // Change the color as needed
                },
            },
            labels: ["Pass", "Fail"],
            dataLabels: {
                enabled: true,
                dropShadow: {
                    blur: 3,
                    opacity: 0.8,
                },
            },
            legend: {
                show: true,
                position: "bottom",
            },

            responsive: [
                {
                    breakpoint: 480,
                    options: {
                        chart: {
                            width: 150,
                        },
                        legend: {
                            // position: "bottom",
                            show: false,
                        },
                    },
                },
            ],
        };
        var chart = new ApexCharts(
            document.querySelector("#subjectpiechart1"),
            options
        );
        chart.render();
    }
    static GradeBarChart2(
        text = "Subject Performance",
        questions = [],
        percenatge = []
    ) {
        var questions1 = questions ? questions.length : 0;
        console.log(questions);
        $("#subjectbarchart").html("");
        console.log(percenatge);
        var colors = [];
        var width = questions1 > 10 ? "150%" : "100%";
        var scroll = questions1 > 10 ? "true" : "false";
        console.log(width);
        // var color = Account.getRandomColor();
        // colors.push(color);
        var options = {
            series: [
                {
                    data: percenatge,
                },
            ],
            chart: {
                height: 350,
                width: width,
                scrollable: scroll,
                type: "bar",
                events: {
                    click: function (chart, w, e) {
                        // console.log(chart, w, e)
                    },
                },
            },

            title: {
                text: text,
                align: "left",
                margin: 10,
                offsetX: 0,
                offsetY: 0,
                floating: false,
                style: {
                    fontSize: "14px",
                    fontWeight: "bold",
                    fontFamily: "Arial, sans-serif",
                    color: "#333", // Change the color as needed
                },
            },
            colors: colors.concat("#BD02FF"),
            plotOptions: {
                bar: {
                    borderRadius: 4,
                    columnWidth: "35%",
                    distributed: true,
                },
            },
            dataLabels: {
                enabled: false,
            },
            legend: {
                show: false,
            },
            xaxis: {
                categories: questions,

                labels: {
                    style: {
                        colors: colors,
                        fontSize: "12px",
                    },
                },
            },
            yaxis: {
                show: false, // Hide left y-axis labels and axis line
            },

            responsive: [
                {
                    breakpoint: 480,
                    options: {
                        chart: {
                            width: 200,
                        },
                        legend: {
                            show: false,
                        },
                    },
                },
            ],
        };

        var chart = new ApexCharts(
            document.querySelector("#subjectbarchart"),
            options
        );
        chart.render();
    }
    static GradePieChart2(labels = ["Repeated", "Promoted"], pass, fail) {
        var pass_count = pass;
        var fail_count = fail;
        // id.on(function(id){
        //     axios.get(
        //         "getsubject_percentage" +
        //             "?id=" +
        //             id +
        //             "&ac_year=" +
        //             ac_year +
        //             "&ac_term=" +
        //             ac_term +
        //             "&class_id=" +
        //             class_id
        //     )
        // });
        console.log(labels);
        var options = {
            series: [pass_count, fail_count],
            chart: {
                width: 320,
                type: "pie",
            },
            fill: {
                colors: ["#006400", "#FF0000"],
            },
            title: {
                text: "Analysis",
                align: "center",

                offsetX: 0,
                offsetY: 0,
                floating: false,
                style: {
                    fontSize: "14px",
                    fontWeight: "bold",
                    fontFamily: "Arial, sans-serif",
                    color: "#333", // Change the color as needed
                },
            },
            chart: {
                width: 320,
                type: "pie",
                align: "left",
            },
            labels: labels,
            dataLabels: {
                enabled: true,
                dropShadow: {
                    blur: 3,
                    opacity: 0.8,
                },
            },
            legend: {
                show: true,
                position: "bottom",
                markers: {
                    fillColors: ["#006400", "#FF0000"],
                },
            },

            responsive: [
                {
                    breakpoint: 480,
                    options: {
                        chart: {
                            width: 150,
                        },
                        legend: {
                            // position: "bottom",
                            show: false,
                        },
                    },
                },
            ],
        };
        var chart = new ApexCharts(
            document.querySelector("#subjectpiechart"),
            options
        );
        chart.render();
    }
    static GradeBarChart3() {
        $("#subjectbarchart1").html("");

        var colors = [];
        // var color = Account.getRandomColor();
        // colors.push(color);
        var options = {
            series: [
                {
                    data: [
                        21, 22, 10, 28, 16, 21, 13, 30, 40, 21, 22, 10, 28, 16,
                        21, 13, 30, 40, 21, 22, 10, 28, 16,
                    ],
                },
            ],
            chart: {
                height: 350,
                type: "bar",
                events: {
                    click: function (chart, w, e) {
                        // console.log(chart, w, e)
                    },
                },
            },
            fill: {
                colors: ["#FF0000", "#006400"],
            },

            title: {
                text: "Subject Performance",
                align: "left",
                margin: 10,
                offsetX: 0,
                offsetY: 0,
                floating: false,
                style: {
                    fontSize: "14px",
                    fontWeight: "bold",
                    fontFamily: "Arial, sans-serif",
                    color: "#333", // Change the color as needed
                },
            },
            colors: colors.concat("#BD02FF"),
            plotOptions: {
                bar: {
                    borderRadius: 4,
                    columnWidth: "35%",
                    distributed: true,
                },
            },
            dataLabels: {
                enabled: false,
            },
            legend: {
                show: false,
            },
            xaxis: {
                categories: [
                    ["Math"],
                    ["Eng"],
                    ["Phy"],
                    ["Che"],
                    ["Bio"],
                    ["Agri"],
                    ["Eco"],
                    ["Inte"],
                    ["Futh"],
                    ["Eng"],
                    ["Eng"],
                    ["Eng"],
                    ["Eng"],
                    ["Eng"],
                    ["Eng"],
                    ["Eng"],
                    ["Eng"],
                    ["Eng"],
                    ["Eng"],
                    ["Eng"],
                    ["Eng"],
                    ["Eng"],
                    ["Eng"],
                ],

                labels: {
                    style: {
                        colors: colors,
                        fontSize: "12px",
                    },
                },
            },
            yaxis: {
                show: false, // Hide left y-axis labels and axis line
            },

            responsive: [
                {
                    breakpoint: 480,
                    options: {
                        chart: {
                            width: 200,
                        },
                        legend: {
                            show: false,
                        },
                    },
                },
            ],
        };

        var chart = new ApexCharts(
            document.querySelector("#subjectbarchart1"),
            options
        );
        chart.render();
    }

    static GradePieChart3() {
        var options = {
            series: [65, 110],
            chart: {
                width: 320,
                type: "pie",
            },
            title: {
                text: "Analysis",
                align: "left",
                margin: 10,
                offsetX: 0,
                offsetY: 0,
                floating: false,
                style: {
                    fontSize: "14px",
                    fontWeight: "bold",
                    fontFamily: "Arial, sans-serif",
                    color: "#333", // Change the color as needed
                },
            },
            labels: ["Repeated", "Promoted"],
            dataLabels: {
                enabled: true,
                dropShadow: {
                    blur: 3,
                    opacity: 0.8,
                },
            },
            legend: {
                show: true,
                position: "bottom",
            },

            responsive: [
                {
                    breakpoint: 480,
                    options: {
                        chart: {
                            width: 150,
                        },
                        legend: {
                            // position: "bottom",
                            show: false,
                        },
                    },
                },
            ],
        };
        var chart = new ApexCharts(
            document.querySelector("#subjectpiechart1"),
            options
        );
        chart.render();
    }
    static FeespieChart(students_total_fees, students_paid_fees) {
        var paid = students_paid_fees;
        var total = students_total_fees;

        var options = {
            series: [paid, total],
            chart: {
                width: 380,
                type: "pie",
            },
            labels: ["Paid Fees", "Total Fees"],
            dataLabels: {
                enabled: true,
                dropShadow: {
                    blur: 3,
                    opacity: 0.8,
                },
            },
            legend: {
                show: true,
                //position: 'bottom'
            },

            responsive: [
                {
                    breakpoint: 480,
                    options: {
                        chart: {
                            width: 200,
                        },
                        legend: {
                            show: false,
                        },
                    },
                },
            ],
        };
        var chart = new ApexCharts(
            document.querySelector("#feespiechart"),
            options
        );
        chart.render();
    }

    static getRandomColor() {
        // Generate random values for R, G, and B between 0 and 255
        const r = Math.floor(Math.random() * 256);
        const g = Math.floor(Math.random() * 256);
        const b = Math.floor(Math.random() * 256);

        // Convert the values to hexadecimal and format the color code
        const colorCode = `#${r.toString(16).padStart(2, "0")}${g
            .toString(16)
            .padStart(2, "0")}${b.toString(16).padStart(2, "0")}`;

        return colorCode;
    }

    static getDayGraph(id) {
        const date = new Date();
        let currentDay = String(date.getDate()).padStart(2, "0");

        let currentMonth = String(date.getMonth() + 1).padStart(2, "0");

        let currentYear = date.getFullYear();
        let currentDate = `${currentDay}-${currentMonth}-${currentYear}`;
        //console.log(currentDate);
        //return;
        let url = window.getdaygraph + "?day=" + currentDate + "&type=" + id;
        axios
            .get(url)
            .then((response) => {
                const { income_data, expense_data, profit_data, month } =
                    response?.data;
                Account.MoneyChat(
                    income_data,
                    expense_data,

                    month,
                    profit_data
                );
            })
            .catch((error) => {
                console.log(error);
            });
    }
}
