<template>
    <panel title="تاریخچه معاملات">
        <div slot="body">
            <div class="table-responsive pre-scrollable">
                <table class="table">
                    <thead>
                    <tr class="v-bg-dark">
                        <th>ساعت</th>
                        <th>قیمت</th>
                        <th>میزان</th>
                        <th>ارز</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="order in sortedOrders" :style="{'background': order.color}">
                        <td>{{order.created_at}}</td>
                        <td>{{order.price | currency}}</td>
                        <td>{{order.amount | round}}</td>
                        <td>BTC</td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </panel>
</template>

<script>

    window.Global_Orders = [];

    export default {

        name: "user-order",

        props: ['user'],

        data() {
            return {
                orders: [],
            }
        },

        created() {
            Event.$on('orderApplied', data => {
                data['color'] = "#78909C";
                this.orders.push(data);
                console.log(data);
            });
        },

        mounted() {
            this.getUserOrders();
        },

        filters: {
            round(num) {
                return Math.round(num);
            },
        },

        methods: {
            getUserOrders() {
                var user = JSON.parse(this.user);
                axios.get('/api/v1/orders/' + user.id + '/history')
                    .then(response => {
                        this.orders = response.data;
                    })
                    .catch(error => {
                        console.log(error.response.data);
                    })
                ;
            },

            changeBgColorForPreiodOfTime() {
                var $el = $(".new-item"),
                    x = 2000,
                    originalColor = $el.css("background");

                $el.css("background", "#78909C");
                setTimeout(function () {
                    $el.css("background", originalColor);
                }, x);
            },
        },

        computed: {
            sortedOrders() {
                return _.orderBy(this.orders, ['id'], ['desc']);
            },
        },
    }
</script>

<style scoped>

</style>