<template>
    <panel title="خرید و فروش">
        <div slot="body" class="row">
            <div class="col-md-6">
                <!-- Basic layout-->
                <form class="form-horizontal" @submit.prevent="onBuySubmit">
                    <div class="panel ng-bg-dark">
                        <div class="panel-heading">
                            <h5 class="panel-title">خرید</h5>
                        </div>
                        <div class="panel-body">
                            <div class="form-group">
                                <label class="col-lg-3 control-label">قیمت:</label>
                                <div class="col-lg-9">
                                    <input type="text" class="form-control" v-model="price_buy">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-lg-3 control-label">مقدار:</label>
                                <div class="col-lg-9">
                                    <input type="password" class="form-control" v-model="amount_buy">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-lg-3 control-label"></label>
                                <div class="col-lg-9">
                                    <input type="password" class="form-control">
                                </div>
                            </div>

                            <div class="text-right" style="padding-top: 20px;">
                                <button type="submit" class="btn btn-success">تایید خرید <i
                                        class="icon-arrow-left13 position-right"></i></button>
                            </div>
                        </div>
                    </div>
                </form>
                <!-- /basic layout -->
            </div>
            <div class="col-md-6">
                <!-- Basic layout-->
                <form class="form-horizontal" @submit.prevent="onSaleSubmit">
                    <div class="panel ng-bg-dark">
                        <div class="panel-heading">
                            <h5 class="panel-title">فروش</h5>
                        </div>

                        <div class="panel-body">
                            <div class="form-group">
                                <label class="col-lg-3 control-label">قیمت:</label>
                                <div class="col-lg-9">
                                    <input type="text" class="form-control" v-model="price_sale">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-lg-3 control-label">مقدار:</label>
                                <div class="col-lg-9">
                                    <input type="password" class="form-control" v-model="amount_sale">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-lg-3 control-label"></label>
                                <div class="col-lg-9">
                                    <input type="password" class="form-control">
                                </div>
                            </div>

                            <div class="text-right" style="padding-top: 20px;">
                                <button type="submit" class="btn btn-danger">تایید فروش <i
                                        class="icon-arrow-left13 position-right"></i></button>
                            </div>
                        </div>
                    </div>
                </form>
                <!-- /basic layout -->
            </div>
        </div>
    </panel>
</template>

<script>
    export default {
        name: "exchange",

        data() {
            return {
                price_buy: '',
                amount_buy: '',
                price_sale: '',
                amount_sale: '',
            }
        },

        methods: {
            onBuySubmit() {

                let data = {
                    amount: this.amount_buy,
                    price: this.price_buy,
                    currency: 1,
                    type: 'BUY',
                };

                axios.post('api/v1/exchange/order ', data)
                    .then((response) => {
                        console.log(response.data);
                    })
                    .catch((error) => {
                        console.log(error);
                    })
                ;

            },
            onSaleSubmit() {

                let data = {
                    amount: this.amount_sale,
                    price: this.price_sale,
                    currency: 1,
                    type: 'SALE',
                };

                axios.post('api/v1/exchange/order ', data)
                    .then((response) => {
                        console.log(response.data);
                    })
                    .catch((error) => {
                        console.log(error);
                    })
                ;
            },
        }
    }
</script>

<style scoped>

</style>