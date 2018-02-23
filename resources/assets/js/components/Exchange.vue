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

        created() {
            window.Echo.channel('order-confirm').listen('OrderConfirm', () => {
                notify('info', 'سفارش خرید با موفقیت انجام شد.');
            });
        },
    }
</script>

<style scoped>

</style>