<template>
    <canvas height="130"></canvas>
</template>

<script>

    import Chart from 'chart.js';

    export default {
        name: "graph",

        data() {
            return {}
        },

        mounted() {
            var ctx = this.$el.getContext('2d');
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: ["Red", "Blue", "Yellow", "Green", "Purple", "Orange"],
                    datasets: [
                        {
                            label: '# of Votes',
                            data: [7, 26, 3, 15, 2, 15],
                            backgroundColor: '#455A64',
                        },
                        {
                            label: '# of Votes',
                            data: [4, 16, 7, 25, 6, 35],
                            backgroundColor: '#345A64',
                        },
                    ]
                },
                options: {
                    scales: {
                        yAxes: [{
                            ticks: {
                                beginAtZero: true
                            }
                        }]
                    }
                }
            });
        },

    }
</script>

<style scoped>

</style>