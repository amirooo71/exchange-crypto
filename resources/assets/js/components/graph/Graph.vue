<template>
    <canvas height="117"></canvas>
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
                            data: [11, 36, 3, 5, 2, 3],
                            backgroundColor: '#455A64',
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