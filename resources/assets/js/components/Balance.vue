<template>
    <panel title="کیف پول">
        <div slot="body">
            <div class="table-responsive pre-scrollable">
                <table class="table table-condensed">
                    <thead>
                    <tr class="v-bg-dark">
                        <th></th>
                        <th>واحد</th>
                        <th class="text-center">موجودی</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="balance in balances">
                        <td>
                            <img :src="'images/logo/' + balance.ac.symbol.toUpperCase() + '.svg'" alt="دلار"
                                 class="v-tiny-svg">
                        </td>
                        <td>{{balance.ac.symbol | uppercase}}</td>
                        <td>
                            <table class="table table-borderless table-condensed"
                                   style="background: #263238;">
                                <tbody>
                                <tr>
                                    <td>{{balance.amount}}</td>
                                </tr>
                                <tr>
                                    <td>{{balance.available}}</td>
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
</template>

<script>
    export default {
        
        name: "balance",

        props: ['user'],

        data() {
            return {
                balances: '',
            }
        },

        created() {
            Event.$on('orderApplied', () => this.getUserBalance());
            Event.$on('orderDeleted', () => this.getUserBalance());
            window.Echo.channel('order-confirm.' + this.user.id).listen('OrderConfirm', () => {
                this.getUserBalance();
            });
        },

        created() {
            this.getUserBalance();
        },

        methods: {

            getUserBalance() {
                axios.get('api/v1/trade/user/balance')
                    .then(response => this.balances = response.data)
                ;
            },
        }
    }
</script>

<style scoped>

</style>