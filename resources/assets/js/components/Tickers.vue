<template>
    <div>
        <div class="well ng-bg-dark" style="margin-bottom: 15px;">
            <table class="table table-borderless table-condensed" style="display: table;">
                <tbody>
                <tr>
                    <td rowspan="3">
                        <img :src="assetImg" alt="بیتکوین"
                             class="v-md-svg">
                    </td>
                    <td>
                        <h6>
                            <span v-if="asset">{{asset.symbol | upper}}</span>
                            <span v-else>BTC</span>
                            <span>/</span>
                            <span v-if="currency">{{currency.symbol | upper}}</span>
                            <span v-else>USD</span>
                        </h6>
                    </td>
                    <td>
                        <h6>{{price | round }}</h6>
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
                                <img :src="'images/logo/'+ ticker.symbol.toUpperCase() + '.svg'" alt="بیتکوین"
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
                                        <td>{{currency.price | round | currency}}</td>
                                        <td :style="{color: currency.pColor}">%{{currency.pChange}}</td>
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
                assetName: 'BTC',
                price: 19550,
            }

        },

        mounted() {
            this.getTickers();
        },

        created() {
            Event.$on('SelectedTicker', data => {
                this.assetName = data.asset.symbol;
            });

            window.Echo.channel('ticker').listen('Ticker', (e) => {
                this.price = e.ticker.price;
                this.getTickers();
            });
        },

        filters: {
            upper(str) {
                return str.toUpperCase();
            },

            round(num) {
                return Math.round(num);
            }
        },

        methods: {

            getTickers() {
                axios.get('api/v1/trade/tickers').then(response => this.tickers = response.data);
            },

            onPairs(asset, currency) {
                this.asset = asset;
                this.currency = currency;
                this.price = currency.price;
                var data = {
                    asset: asset,
                    currency: currency,
                };
                Event.$emit('SelectedTicker', data);
            },
        },

        computed: {

            assetImg() {
                return 'images/logo/' + this.assetName.toUpperCase() + '.svg';
            }

        }

    }
</script>

<style scoped>

</style>