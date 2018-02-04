<template>
    <canvas></canvas>
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
                type: 'line',
                data: {
                    labels: ["Red", "Blue", "Yellow", "Green", "Purple", "Orange"],
                    datasets: [
                        {
                            label: '# of Votes',
                            data: [11, 36, 3, 5, 2, 8],
                            backgroundColor: '#455A64',
                        },
                        {
                            label: '# of Votes',
                            data: [7, 26, 3, 15, 2, 15],
                            backgroundColor: '#546E7A',
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
        }
    }
</script>

<style scoped>

</style>