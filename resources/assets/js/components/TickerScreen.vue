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
                    <h6>{{defaultTicker.price | currency}}</h6>
                </td>
            </tr>
            <tr class="text-muted">
                <td>
                    {{defaultTicker.min | currency}}
                    <span>کم ترین</span>
                </td>
                <td>
                    {{defaultTicker.max | currency}}
                    <span>بیش ترین</span>
                </td>
            </tr>
            <tr class="text-muted">
                <td>
                    <span>{{defaultTicker.volume | round }}</span>
                    <span>{{assetName}}</span>
                    <span>حجم بازار</span>
                </td>
                <td class="text-success">
                       <span :style="{color: defaultTicker.pColor}">
                            <!--523.00-->
                           <!--<i></i>-->
                            (%{{defaultTicker.pChange}})
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
            }
        },

        created() {
            this.getDefaultTicker();
            Event.$on('onSelectedPair', data => {
                this.defaultTicker = data.currency;
                this.assetName = data.asset.symbol;
                this.currencyName = data.currency.symbol;
            });
        },

        methods: {

            getDefaultTicker() {
                axios.get('api/v1/trading/default/ticker').then(response => {
                    this.defaultTicker = response.data;
                });
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