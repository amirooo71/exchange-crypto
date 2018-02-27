<template>
    <panel title="نرخ ارزها">
        <div slot="body">
            <div class="table-responsive pre-scrollable">
                <table class="table">
                    <thead>
                    <tr class="v-bg-dark">
                        <th>نماد</th>
                        <th>ارز</th>
                        <th>واحد پول</th>
                        <th>قیمت</th>
                        <th>تغییرات</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="ticker in tickers">
                        <td>
                            <img src="./../../../../public/images/logo/BTC.svg" alt="بیتکوین"
                                 class="v-tiny-svg">
                        </td>
                        <td>{{ticker.symbol | upper }}</td>
                        <td>
                            <table>
                                <tr v-for="currency in ticker.currencies">
                                    <td>{{currency.symbol | upper }}</td>
                                </tr>
                            </table>
                        </td>
                        <td></td>
                        <td></td>
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

                tickers: [],

            }

        },

        mounted() {
            this.getTickers();
        },

        created() {

        },

        filters: {
            upper(str) {
                return str.toUpperCase();
            }
        },

        methods: {
            getTickers() {
                axios.get('api/v1/trade/tickers ').then(response => this.tickers = response.data);
            }
        },

    }
</script>

<style scoped>

</style>