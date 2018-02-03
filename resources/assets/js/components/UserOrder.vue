<template>
    <panel title="تاریخچه معاملات">
        <div slot="body">
            <div class="table-responsive pre-scrollable">
                <table class="table">
                    <thead>
                    <tr class="v-bg-dark">
                        <th>میزان</th>
                        <th>قیمت</th>
                        <th>نوع</th>
                        <th>ارز</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
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
        name: "user-order",

        mounted() {
            Event.$on('orderApplied', function () {
                alert('Yehhhhhhhhhhhhhhhhh');
            });
        }
    }
</script>

<style scoped>

</style>