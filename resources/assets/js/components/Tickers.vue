<template>
    <div>
        <div class="well ng-bg-dark" style="margin-bottom: 15px;">
            <table class="table table-borderless table-condensed" style="display: table;">
                <tbody>
                <tr>
                    <td rowspan="3">
                        <img src="./../../../../public/images/logo/BTC.svg" alt="بیتکوین"
                             class="v-md-svg">
                    </td>
                    <td>
                        <h5>
                            <span v-if="asset">{{asset.symbol | upper}}</span>
                            <span v-else>BTC</span>
                            <span>/</span>
                            <span v-if="currency">{{currency.symbol | upper}}</span>
                            <span v-else>USD</span>
                        </h5>
                    </td>
                    <td>
                        <h5>10,813</h5>
                    </td>
                </tr>
                <tr class="text-muted">
                    <td>
                        10,270
                        <span>کم ترین</span>
                    </td>
                    <td>
                        11,065
                        <span>بیش ترین</span>
                    </td>
                </tr>
                <tr class="text-muted">
                    <td>
                        <span>45,872</span>
                        <span v-if="asset">{{asset.symbol | upper}}</span>
                        <span v-else>BTC</span>
                        <span>حجم بازار</span>
                    </td>
                    <td class="text-success">
                        <span>
                            523.00
                            <i></i>
                            (5.08%)
                        </span>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
        <panel title="نرخ ارزها">
            <div slot="body">
                <div class="table-responsive pre-scrollable">
                    <table class="table table-condensed">
                        <thead>
                        <tr class="v-bg-dark">
                            <th>نماد</th>
                            <th>ارز</th>
                            <th class="text-center">تغییرات</th>
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
                                <table class="table table-borderless table-condensed table-hover"
                                       style="background: #263238;">
                                    <tbody>
                                    <tr v-for="currency in ticker.currencies" @click="onPairs(ticker,currency)"
                                        style="cursor: pointer;">
                                        <td>
                                            {{currency.symbol | upper }}
                                        </td>
                                        <td>19500</td>
                                        <td>24%</td>
                                    </tr>
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </panel>
    </div>
</template>

<script>

    export default {
        name: "tickers",

        data() {

            return {

                tickers: [],
                asset: '',
                currency: '',

            }

        },

        mounted() {
            this.getTickers();
        },

        filters: {
            upper(str) {
                return str.toUpperCase();
            }
        },

        methods: {
            getTickers() {
                axios.get('api/v1/trade/tickers').then(response => this.tickers = response.data);
            },

            onPairs(asset, currency) {
                this.asset = asset;
                this.currency = currency;
                var data = {
                    asset: asset,
                    currency: currency,
                };
                Event.$emit('SelectedTicker', data);
            },
        },

    }
</script>

<style scoped>

</style>