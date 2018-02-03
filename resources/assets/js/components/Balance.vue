<template>
    <panel title="کیف پول">
        <div slot="body">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                    <tr class="v-bg-dark">
                        <th></th>
                        <th>واحد</th>
                        <th>مقدار</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td></td>
                        <td>تومان</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td><img src="./../../../../public/images/logo/dollar-logo.svg" alt="دلار"
                                 class="v-tiny-svg"></td>
                        <td>دلار</td>
                        <td>12634</td>
                    </tr>
                    <tr>
                        <td><img src="./../../../../public/images/logo/BTC.svg" alt="بیتکوین"
                                 class="v-tiny-svg"></td>
                        <td>بیتکوین</td>
                        <td></td>
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
    }
</script>

<style scoped>

</style>