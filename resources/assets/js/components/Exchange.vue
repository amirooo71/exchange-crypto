<template>
    <panel title="خرید و فروش">
        <div slot="body" class="row">
            <div class="col-md-6">
                <order-buy></order-buy>
            </div>
            <div class="col-md-6">
                <order-sell></order-sell>
            </div>
        </div>
    </panel>
</template>

<script>

    export default {
        name: "exchange",
    }
</script>

<style scoped>

</style>