<template>
    <panel title="کیف پول">
        <div slot="body">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                    <tr class="v-bg-dark">
                        <th></th>
                        <th>واحد</th>
                        <th>مقدار کل</th>
                        <th>قابل استفاده</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="balance in balances">
                        <td>
                            <img src="./../../../../public/images/logo/dollar-logo.svg" alt="دلار" class="v-tiny-svg">
                        </td>
                        <!--<td>{{balance.currency.symbol}}</td>-->
                        <td>{{balance.amount}}</td>
                        <td>{{balance.available}}</td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </panel>
</template>

<script>
    export default {
        name: "balance",

        data() {
            return {
                balances: '',
            }
        },

        created() {
            Event.$on('orderApplied', () => this.getUserBalance());
            Event.$on('orderDeleted', () => this.getUserBalance());
        },

        mounted() {
            this.getUserBalance();
        },

        methods: {

            getUserBalance() {
                axios.get('api/v1/trade/user/balance')
                    .then(response => this.balances = response.data)
                    .catch(error => console.log(error.response.data))
                ;
            },
        }
    }
</script>

<style scoped>

</style>