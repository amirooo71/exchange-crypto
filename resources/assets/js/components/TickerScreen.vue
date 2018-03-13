<template>
    <div class="well ng-bg-dark v-mg-t-15">
        <table class="table table-borderless table-condensed">
            <tbody>
            <tr>
                <td rowspan="3">
                    <img :src="imgUrl" alt="" class="v-md-svg">
                </td>
                <td>
                    <h6>
                        <span>{{assetName | uppercase}}</span>
                        <span>/</span>
                        <span>{{currencyName | uppercase}}</span>
                    </h6>
                </td>
                <td>
                    <h6>{{defaultTicker.price ? defaultTicker.price : 0 | currency(this.currencyFilterSymbol,
                        this.currencyFilterAlignment)}}</h6>
                </td>
            </tr>
            <tr class="text-muted">
                <td>
                    {{defaultTicker.min ? defaultTicker.min : 0 | currency}}
                    <span>کم ترین</span>
                </td>
                <td>
                    {{defaultTicker.max ? defaultTicker.max : 0 | currency}}
                    <span>بیش ترین</span>
                </td>
            </tr>
            <tr class="text-muted">
                <td>
                    <span>{{defaultTicker.volume ? defaultTicker.volume : 0 | round }}</span>
                    <span>{{assetName}}</span>
                    <span>حجم بازار</span>
                </td>
                <td class="text-success">
                       <span :style="{color: defaultTicker.percent_color}">
                            <!--523.00-->
                           <!--<i></i>-->
                            (%{{defaultTicker.percent_change ? defaultTicker.percent_change : 0 }})
                        </span>
                </td>
            </tr>
            </tbody>
        </table>
    </div>
</template>

<script>

    import Vue2Filters from 'vue2-filters'

    export default {
        name: "ticker-screen",

        plugins: [
            '~/plugins/vue2-filters'
        ],

        data() {
            return {
                defaultTicker: '',
                assetName: 'BTC',
                currencyName: 'USD',
                currencyFilterAlignment: {symbolOnLeft: false},
                currencyFilterSymbol: '$',
            }
        },

        created() {
            this.getDefaultTicker();
            Event.$on('onSelectedPair', data => {
                this.setAcData(data);
                this.setCurrencyFilterAttr(data);
            });
            window.Echo.channel('ticker').listen('Ticker', (e) => {
                this.defaultTicker = e.ticker;
            });
        },

        methods: {

            getDefaultTicker() {
                axios.get('api/v1/trading/default/ticker').then(response => {
                    this.defaultTicker = response.data;
                });
            },

            setAcData(data) {
                this.defaultTicker = data.currency;
                this.assetName = data.asset.symbol;
                this.currencyName = data.currency.symbol;
            },

            setCurrencyFilterAttr(data) {

                switch (data.currency.symbol) {

                    case "usd":
                        this.currencyFilterAlignment = {symbolOnLeft: true};
                        this.currencyFilterSymbol = '$';
                        break;
                    case "btc":
                        this.currencyFilterAlignment = {symbolOnLeft: false};
                        this.currencyFilterSymbol = 'BTC ';
                        break;
                    case "irr":
                        this.currencyFilterAlignment = {symbolOnLeft: false};
                        this.currencyFilterSymbol = 'IRR ';
                        break;

                }

            },
        },

        computed: {

            imgUrl() {
                return 'images/logo/' + this.assetName.toUpperCase() + '.svg';
            }

        },

        filters: {

            round: function (num) {
                return Math.round(num);
            }

        },

    }
</script>

<style scoped>

</style>