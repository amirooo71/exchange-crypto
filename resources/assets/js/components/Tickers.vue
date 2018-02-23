<template>
    <panel title="نرخ ارزها">
        <div slot="body">
            <div class="table-responsive pre-scrollable">
                <table class="table">
                    <thead>
                    <tr class="v-bg-dark">
                        <th>نماد</th>
                        <th>نام</th>
                        <th>قیمت</th>
                        <th>تغییرات</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td>
                            <img src="./../../../../public/images/logo/BTC.svg" alt="بیتکوین"
                                 class="v-tiny-svg">
                        </td>
                        <td>بیتکوین</td>
                        <td>{{price}}</td>
                        <td></td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </panel>
</template>

<script>

    // import VueSocketio from 'vue-socket.io';
    // Vue.use(VueSocketio, 'http://localhost:3000');

    export default {
        name: "tickers",

        data() {

            return {

                price: '',

            }

        },


        created() {
            window.Echo.channel('order-confirm').listen('OrderConfirm', e => {
                this.price = e.price;
            });
        },

        // sockets: {
        //
        //     connect: function () {
        //         console.log('socket connected')
        //     },
        //
        //     'test-channel:UserSignUp': function(data){
        //         this.users.push(data.username);
        //     },
        // },

    }
</script>

<style scoped>

</style>