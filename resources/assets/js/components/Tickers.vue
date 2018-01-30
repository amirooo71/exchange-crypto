<template>
    <panel title="نرخ ارزها">
        <div slot="body">
            <div class="table-responsive pre-scrollable">
                <table class="table">
                    <thead>
                    <tr class="v-bg-dark">
                        <th>نماد</th>
                        <th>نام</th>
                        <th>قیمت</th>
                        <th>تغییرات (۱ ساعت)</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="ticker in tickers">
                        <td><img src="./../../../../public/images/logo/BTC.svg" alt="بیتکوین"
                                 class="v-tiny-svg">
                        </td>
                        <td>{{ticker.symbol}}</td>
                        <td>{{ticker.price_usd}}</td>
                        <td>{{ticker.percent_change_1h}}</td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </panel>
</template>

<script>

    export default {
        name: "tickers",
        data() {
            return {
                tickers: {}
            }
        },

        mounted() {

            axios.get('/api/v1/tickers')
                .then((response) => {
                    this.tickers = response.data;
                })
                .catch((error) => {
                    console.log(error);
                });

        },
    }
</script>

<style scoped>

</style>