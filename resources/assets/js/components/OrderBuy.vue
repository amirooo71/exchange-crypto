<template>
    <form class="form-horizontal" @submit.prevent="onSubmit" @keydown="errors.clear($event.target.name)">
        <div class="panel ng-bg-dark">
            <div class="panel-heading">
                <h5 class="panel-title">خرید</h5>
            </div>
            <div class="panel-body">
                <div class="form-group">
                    <label class="col-lg-3 control-label">قیمت:</label>
                    <div class="col-lg-9">
                        <input type="text" class="form-control" name="price" v-model="price">
                        <span class="text-danger help-block" v-if="errors.has('price')"
                              v-text="errors.get('price')"></span>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-lg-3 control-label">مقدار:</label>
                    <div class="col-lg-9">
                        <input type="text" class="form-control" name="amount" v-model="amount">
                        <span class="text-danger help-block" v-if="errors.has('amount')"
                              v-text="errors.get('amount')"></span>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-lg-3 control-label">کل:</label>
                    <div class="col-lg-9">
                        <input type="text" class="form-control" :value="price * amount">
                    </div>
                </div>

                <div class="text-right">
                    <button type="submit" class="btn btn-success" :disabled="errors.any()">تایید خرید <i
                            class="icon-arrow-left13 position-right"></i></button>
                </div>
            </div>
        </div>
    </form>
</template>

<script>

    window.Event = new Vue();

    class Errors {

        constructor() {
            this.errors = {};
        }

        get(field) {
            if (this.errors[field]) {
                return this.errors[field][0];
            }
        }

        has(field) {
            return this.errors.hasOwnProperty(field);
        }

        any() {
            return Object.keys(this.errors).length > 0;
        }

        record(errors) {
            this.errors = errors;
        }

        clear(field) {
            delete this.errors[field];
        }

    }
    
    export default {
        name: "order-buy",

        data() {
            return {
                price: '',
                amount: '',
                currency_id: 1,
                errors: new Errors(),
            }
        },

        methods: {

            onSubmit() {
                axios.post('api/v1/trade/orderbuy ', this.$data)
                    .then(this.onSuccess)
                    .catch(error => this.errors.record(error.response.data))
                ;
            },

            onSuccess(response) {
                this.notify();
                this.price = '';
                this.amount = '';
                Event.$emit('orderApplied', response.data);
            },

            notify() {
                new Noty({
                    type: 'success',
                    layout: 'bottomRight',
                    theme: 'mint',
                    text: 'تراکنش خرید با موفقیت ثبت شد.'
                }).show();
            },
        }
    }
</script>

<style scoped>

</style>