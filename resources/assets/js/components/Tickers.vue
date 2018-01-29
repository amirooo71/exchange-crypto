<template>
    <panel title="نرخ ارزها">
        <div slot="body">
            <div class="table-responsive pre-scrollable">
                <table class="table">
                    <thead>
                    <tr class="v-bg-dark">
                        <!--<th></th>-->
                        <th>نام</th>
                        <th>قیمت</th>
                        <th>۲۴ ساعت گذشته</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <!--<td>-->
                        <!--img-->
                        <!--</td>-->
                        <td>{{tickers.symbol}}</td>
                        <td>{{tickers.closeQuantity}}</td>
                        <td>{{tickers.priceChangePercent}}</td>
                    </tr>
                    <tr>
                        <!--<td>-->
                        <!--img-->
                        <!--</td>-->
                        <td>{{tickers.symbol}}</td>
                        <td>{{tickers.high}}</td>
                        <td>{{tickers.priceChangePercent}}</td>
                    </tr>
                    <tr>
                        <!--<td>-->
                        <!--img-->
                        <!--</td>-->
                        <td>{{tickers.symbol}}</td>
                        <td>{{tickers.closeQuantity}}</td>
                        <td>{{tickers.priceChangePercent}}</td>
                    </tr>
                    <tr>
                        <!--<td>-->
                        <!--img-->
                        <!--</td>-->
                        <td>{{tickers.symbol}}</td>
                        <td>{{tickers.high}}</td>
                        <td>{{tickers.priceChangePercent}}</td>
                    </tr>
                    <tr>
                        <!--<td>-->
                        <!--img-->
                        <!--</td>-->
                        <td>{{tickers.symbol}}</td>
                        <td>{{tickers.closeQuantity}}</td>
                        <td>{{tickers.priceChangePercent}}</td>
                    </tr>
                    <tr>
                        <!--<td>-->
                        <!--img-->
                        <!--</td>-->
                        <td>{{tickers.symbol}}</td>
                        <td>{{tickers.high}}</td>
                        <td>{{tickers.priceChangePercent}}</td>
                    </tr>
                    <tr>
                        <!--<td>-->
                        <!--img-->
                        <!--</td>-->
                        <td>{{tickers.symbol}}</td>
                        <td>{{tickers.closeQuantity}}</td>
                        <td>{{tickers.priceChangePercent}}</td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </panel>
</template>

<script>

    import VueSocketIo from 'vue-socket.io';

    Vue.use(VueSocketIo, 'http://localhost:3000');

    export default {
        name: "tickers",
        data() {
            return {
                tickers: '',
            }
        },
        sockets: {
            connect: function () {
                console.log('socket connected')
            },

            tickerBNBBTC: function (data) {
                this.tickers = data;
            }
        },
    }
</script>

<style scoped>

    .v-danger {
        color: red;
    }

</style>