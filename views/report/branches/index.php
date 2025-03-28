<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

<div class="card">
    <div class="card-body">
        <div class="container">
            <h1 class="my-4">Отчет по филиалам за календарный месяц</h1>
            <form class="m-2 d-inline-block" method="get" action="" onsubmit="return false;">
                <label for="date" class="form-label">Выберите месяц отчета:</label>
                <input type="month" class="form-control form-control" name="date" value="<?php echo htmlspecialchars($date); ?>" onchange="changeDate(this.value)">
            </form>
            <div id="chart"></div>
        </div>
    </div>
</div>

<script>
    // Массив данных с сервера
    const branchesData = <?php echo json_encode($branchesData); ?>;

    // Цвета для статусов
    const statusColors = {
        'В ожидании': '#ffeb3b',
        'Принято': '#4caf50',
        'Отказано': '#f44336',
        'Кредит выдан': '#2196f3',
        'Отменена': '#9e9e9e',
    };

    // Функция для рендеринга графика
    function renderChart() {
        const series = Object.keys(statusColors).map(statusName => {
            return {
                name: statusName,
                data: branchesData.map(branch => branch.statuses[statusName] || 0),
                color: statusColors[statusName]
            };
        });

        const chartOptions = {
            chart: {
                type: 'bar',
                stacked: true,
                height: 700
            },
            series: series,
            xaxis: {
                categories: branchesData.map(branch => branch.name),
            },
            legend: {
                position: 'top',
                labels: {
                    colors: '#000', // Цвет текста в легенде
                }
            },
            plotOptions: {
                bar: {
                    horizontal: false,
                    dataLabels: {
                        position: 'top',
                    },
                },
            },
        };

        const chartElement = document.querySelector("#chart");
        chartElement.innerHTML = ''; // Очищаем график перед обновлением
        const chart = new ApexCharts(chartElement, chartOptions);
        chart.render();
    }

    // Переключение даты
    function changeDate(date) {
        window.location.href = '/report/branches/' + date;
    }

    // Рендеринг графика при загрузке страницы
    renderChart();
</script>