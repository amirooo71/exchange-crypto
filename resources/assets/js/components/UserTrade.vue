<template>
    <panel title="تاریخچه معاملات">
        <div slot="body">
            <div class="table-responsive pre-scrollable">
                <table class="table">
                    <thead>
                    <tr class="v-bg-dark">
                        <th>میزان</th>
                        <th>قیمت</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="trade in trades">
                        <td>{{trade.amount}}</td>
                        <td>{{trade.price}}</td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </panel>
</template>

<script>
    export default {
        name: "user-trade",
        data() {
            return {
                trades: [],
            }
        },

        mounted() {
            axios.get('api/v1/user/order')
                .then((response) => {
                    this.trades = response.data;
                })
                .catch((error) => {
                    console.log(error);
                });
        }
    }
</script>

<style scoped>

</style>