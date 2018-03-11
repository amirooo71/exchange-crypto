<template>
    <div>
        <div class="well ng-bg-dark v-mg-t-15">
            <table class="table table-borderless table-condensed">
                <tbody>
                <tr>
                    <td rowspan="3">
                        <img :src="assetImg" alt="بیتکوین"
                             class="v-md-svg">
                    </td>
                    <td>
                        <h6>
                            <span v-if="asset">{{asset.symbol | uppercase }}</span>
                            <span v-else>BTC</span>
                            <span>/</span>
                            <span v-if="currency">{{currency.symbol | uppercase }}</span>
                            <span v-else>USD</span>
                        </h6>
                    </td>
                    <td>
                        <h6>{{price | currency }}</h6>
                    </td>
                </tr>
                <tr class="text-muted">
                    <td>
                        {{min | round }}
                        <span>کم ترین</span>
                    </td>
                    <td>
                        {{max | round}}
                        <span>بیش ترین</span>
                    </td>
                </tr>
                <tr class="text-muted">
                    <td>
                        <span>{{volume | round}}</span>
                        <span v-if="asset">{{asset.symbol | uppercase }}</span>
                        <span v-else>BTC</span>
                        <span>حجم بازار</span>
                    </td>
                    <td class="text-success">
                        <span :style="{color: pColor}">
                            <!--523.00-->
                            <!--<i></i>-->
                            (%{{pChange}})
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
                            <td>{{ticker.symbol | uppercase }}</td>
                            <td>
                                <table class="table table-borderless table-condensed table-hover"
                                       style="background: #263238;">
                                    <tbody>
                                    <tr v-for="currency in ticker.currencies" @click="onPairs(ticker,currency)"
                                        style="cursor: pointer;">
                                        <td>
                                            {{currency.symbol | uppercase }}
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
                price: '',
                min: '',
                max: '',
                volume: '',
                pChange: '',
                pColor: '',
            }

        },

        mounted() {
            this.getTickers();
            this.getDefaultTicker();
        },

        created() {
            Event.$on('SelectedTicker', data => {
                this.assetName = data.asset.symbol;
            });

            window.Echo.channel('ticker').listen('Ticker', (e) => {
                this.price = e.ticker.price;
                this.max = e.ticker.max;
                this.min = e.ticker.min;
                this.volume = e.ticker.volume;
                this.pChange = e.ticker.percent_change;
                this.pColor = e.ticker.percent_color;
                this.getTickers();
            });
        },

        filters: {

            round(num) {
                return Math.round(num);
            }

        },

        methods: {

            getTickers() {
                axios.get('api/v1/trade/tickers').then(response => this.tickers = response.data);
            },

            getDefaultTicker() {
                axios.get('api/v1/trade/default/ticker').then(response => {
                    this.price = response.data.price;
                    this.max = response.data.max;
                    this.min = response.data.min;
                    this.volume = response.data.volume;
                    this.pChange = response.data.percent_change;
                    this.pColor = response.data.percent_color;
                });
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