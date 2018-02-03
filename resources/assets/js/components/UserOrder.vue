<template>
    <panel title="تاریخچه معاملات">
        <div slot="body">
            <div class="table-responsive pre-scrollable">
                <table class="table">
                    <thead>
                    <tr class="v-bg-dark">
                        <th>میزان</th>
                        <th>قیمت</th>
                        <th>ساعت</th>
                        <th>ارز</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="order in orders">
                        <td>{{order.amount}}</td>
                        <td>{{order.price}}</td>
                        <td>{{order.created_at}}</td>
                        <td>BTC</td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </panel>
</template>

<script>
    export default {
        name: "user-order",

        props: ['user'],

        data() {
            return {
                orders: {}
            }
        },

        mounted() {

            Event.$on('orderApplied', function () {
                // TO DO
            });

            this.getUserOrders();
        },

        methods: {
            getUserOrders() {
                // console.log(this.user);
                axios.get('/api/v1/orders/21/history')
                    .then(response => {
                        this.orders = response.data;
                    })
                    .catch(error => {
                        console.log(error.response.data);
                    })
                ;
            },
        }
    }
</script>

<style scoped>

</style>