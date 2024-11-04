export default class DashboardConfig {
    // Sign-Up Chart[chart-2]
    static SignUpChart() {
        var options = {
            series: [
                {
                    name: "Mobile",
                    data: [44, 55, 57, 56, 61, 58, 63],
                },
                {
                    name: "Web",
                    data: [76, 55, 80, 60, 50, 65, 77],
                },
                {
                    name: "Normal",
                    data: [35, 41, 36, 26, 45, 48, 52],
                },
            ],
            chart: {
                type: "bar",
                height: 250,
            },
            plotOptions: {
                bar: {
                    horizontal: false,
                    columnWidth: "55%",
                    endingShape: "rounded",
                },
            },
            dataLabels: {
                enabled: false,
            },
            stroke: {
                show: true,
                width: 2,
                colors: ["transparent"],
            },
            xaxis: {
                categories: ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"],
            },
            yaxis: {},
            title: {
                text: "Daily Sign-Up Count",
            },
            fill: {
                opacity: 2,
            },
            tooltip: {
                y: {
                    formatter: function (val) {
                        return "$ " + val + " thousands";
                    },
                },
            },
        };

        var chartElement = document.querySelector("#signupchart");

        if (chartElement) {
            var signupchart = new ApexCharts(chartElement, options);
            signupchart.render();
        } else {
            console.log("Element with ID 'signupchart' does not exist.");
        }
    }

    // Revenue chart [chart-1]
    static RevenueChart() {
        var options = {
            series: [
                {
                    name: "Mobile",
                    type: "column",
                    data: [
                        440, 505, 414, 671, 227, 413, 201, 352, 752, 320, 257,
                        160,
                    ],
                },
                {
                    name: "Web",
                    type: "Column",
                    data: [23, 42, 35, 27, 43, 22, 17, 31, 22, 22, 12, 16],
                },
            ],
            chart: {
                type: "line",
                height: 250,
            },
            stroke: {
                curve: "smooth",
            },
            title: {
                text: "Revenue Analysis",
            },
            dataLabels: {
                enabled: true,
                enabledOnSeries: [1],
            },
            series: [
                {
                    name: "sales",
                    data: [60, 40, 80, 60, 80, 90, 40],
                },
            ],
            xaxis: {
                categories: [
                    "Jan",
                    "Feb",
                    "Mar",
                    "Apr",
                    "May",
                    "Jun",
                    "Jul",
                    "Aug",
                ],
            },
        };
        var chartElement = document.querySelector("#revenuechart");

        if (chartElement) {
            var revenuechart = new ApexCharts(chartElement, options);
            revenuechart.render();
        } else {
            console.log("Element with ID 'revenuechart' does not exist.");
        }
    }

    //Subscription Chart[chart-3]
    static async SubcriptionChart() {
        async function getPlanList() {
            let url = window.getPlanList;
            console.log(url);
            let plan_info = [];

            if (url) {
                try {
                    const response = await axios.get(url);
                    console.log(response.data);
                    plan_info = response.data.result || [];
                } catch (error) {
                    console.error("Error fetching plan list:", error);
                }
            }
            console.log("plan info1", plan_info);
            return plan_info;
        }

        let plan_info = await getPlanList();

        plan_info.forEach((plan) => {
            var options1 = {
                chart: {
                    type: "radialBar",
                    height: 200,
                    width: 200,
                },
                series: [plan.percentage],
                plotOptions: {
                    radialBar: {
                        hollow: {
                            size: "50%",
                        },
                    },
                },
                labels: [plan.name],
            };

            var subscriptionchart1 = new ApexCharts(
                document.querySelector("#subscriptionchart" + plan.id),
                options1
            );

            subscriptionchart1.render();
        });
    }

    // Balance History [chart-4]
    static BalanceChart() {
        var options = {
            series: [
                {
                    name: "Balance",
                    data: [0, 270, 200, 400, 380, 600, 200, 350],
                },
            ],
            chart: {
                height: 250,
                type: "line",
                zoom: {
                    enabled: false,
                },
            },
            dataLabels: {
                enabled: false,
            },
            stroke: {
                curve: "smooth",
            },
            title: {
                text: "Balance History",
                align: "left",
            },
            grid: {
                row: {
                    colors: ["#f3f3f3", "transparent"], // takes an array which will be repeated on columns
                    opacity: 1.5,
                },
            },
            xaxis: {
                categories: ["Mar", "Apr", "May", "Jun", "Jul", "Aug"],
            },
        };

        var balhistorychart = new ApexCharts(
            document.querySelector("#balhistorychart"),
            options
        );
        balhistorychart.render();
    }

    // Payment chart in revenue.blade file[chart-5]
    static PaymentChart() {
        var options = {
            series: [
                {
                    name: "Paystack",
                    data: [
                        200, 0, 200, 0, 400, 0, 200, 400, 100, 300, 0, 200, 400,
                        200,
                    ],
                },
                {
                    name: "Bank",
                    data: [
                        100, 200, 100, 200, 0, 100, 200, 100, 200, 100, 300, 0,
                        200,
                    ],
                },
            ],
            chart: {
                height: 400,
                type: "line",
                zoom: {
                    enabled: false,
                },
            },
            dataLabels: {
                enabled: false,
            },
            stroke: {
                curve: "smooth",
            },
            title: {
                text: "Payment Method",
                align: "left",
            },
            grid: {
                row: {
                    colors: ["#f3f3f3", "transparent"], // takes an array which will be repeated on columns
                    opacity: 1.5,
                },
            },
            xaxis: {
                categories: [
                    "01",
                    "02",
                    "03",
                    "04",
                    "05",
                    "06",
                    "07",
                    "08",
                    "09",
                    "10",
                    "11",
                    "12",
                ],
            },
        };

        var paymentchart = new ApexCharts(
            document.querySelector("#paymentchart"),
            options
        );
        paymentchart.render();
    }
}
