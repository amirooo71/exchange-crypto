<template>
    <panel title="فرم سفارش">
        <div slot="body" class="row">
            <div class="col-md-6 col-sm-6 col-xs-6">
                <order-buy></order-buy>
            </div>
            <div class="col-md-6 col-sm-6 col-xs-6">
                <order-sell></order-sell>
            </div>
        </div>
    </panel>
</template>

<script>

    export default {

        name: "exchange",

        props: ['user'],

        created() {
            window.Echo.channel('order-confirm.' + this.user.id).listen('OrderConfirm', (e) => {
                notify('info', this.getConfirmOrderMsg(e.order, e.price));
            });
        },

        methods: {

            getConfirmOrderMsg($order, $price) {
                return "سفارشی با مقدار " + $order.amount + " روی قیمت " + $price + " انجام شد. ";
            },
        }
    }
</script>

<style scoped>

</style>