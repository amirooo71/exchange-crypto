<template>
    <panel title="معاملات">
        <div slot="body">
            <div>
                <div class="col-md-6">
                    <div class="table-responsive pre-scrollable">
                        <table class="table table-condensed">
                            <thead>
                            <tr class="bg-success">
                                <th>قیمت</th>
                                <th>مقدار</th>
                                <th>نوع معامله</th>
                                <th>تاریخ</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="orderBuy in orderBuys">
                                <td>{{orderBuy.price}}</td>
                                <td>{{orderBuy.amount}}</td>
                                <td>{{orderBuy.pair.pair | uppercase}}</td>
                                <td>{{orderBuy.created_at}}</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="table-responsive pre-scrollable">
                        <table class="table table-condensed">
                            <thead>
                            <tr class="bg-danger">
                                <th>قیمت</th>
                                <th>مقدار</th>
                                <th>نوع معامله</th>
                                <th>تاریخ</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="orderSell in orderSells">
                                <td>{{orderSell.price}}</td>
                                <td>{{orderSell.amount}}</td>
                                <td>{{orderSell.pair.pair | uppercase}}</td>
                                <td>{{orderSell.created_at}}</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </panel>
</template>

<script>
    export default {
        name: "order-book",

        data() {
            return {
                orderSells: [],
                orderBuys: [],
            }
        },

        created() {
            window.Echo.channel('order-book').listen('OrderBook', e => {
                if (e.order.type == 'خرید') {
                    this.orderBuys.push(e.order);
                } else {
                    this.orderSells.push(e.order);
                }
            });
        },

        mounted() {
            this.getSellOrderBook();
            this.getBuyOrderBook();
        },

        methods: {

            getSellOrderBook() {
                axios.get('/api/v1/trade/orderbook/sell').then(response => this.orderSells = response.data);
            },

            getBuyOrderBook() {
                axios.get('/api/v1/trade/orderbook/buy').then(response => this.orderBuys = response.data);
            },
        }
    }
</script>

<style scoped>

</style>